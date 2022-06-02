<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Finder\Finder;

class FinanceAPIController extends BaseController {

    public function __construct() {
        $this->config = Config::get('constant.module.finance.tabs');
        $this->others = Config::get('constant.others');
        $this->tbl_fields_name = $this->others['tbl_fields_name'];
    }

    public function findFinanceFile($id) {
        $response = '';
        try {

            $finance = Finance::find($id);

            if($finance) {
                $response = [
                    'status' => 200,
                    'message' => 'Record found',
                ];
            } else {
                $response = [
                    'status' => 404,
                    'message' => "ID $id does not found in our finance file!",
                ];
            }
        } catch (Exception $e) {
            throw($e);
        }

        return $response;

    }
    
    public function findFinanceFileByNo($file_no, $month, $year) {
        $response = '';
        try {
            $file = Files::where('file_no',$file_no)->first();
            
            if($file) {
                $finance = Finance::where('file_id', $file->id)->where('month',$month)->where('year',$year)->first();
                if($finance) {
                    $response = [
                        'status' => 200,
                        'data' => [
                            'id' => $finance->id
                        ],
                        'message' => 'Record found',
                    ];
                } else {
                    $response = [ 
                        'status' => 404,
                        'message' => "ID finance file does not found in our finance file!",
                    ];
                }
            } else {
                $response = [
                    'status' => 404,
                    'message' => "File no $file_no does not found!",
                ];
            }
            
        } catch (Exception $e) {
            throw($e);
        }
        
        return $response;

    }

    /**
     * This function is to create audit trail
     * @param int $id 
     * @param string $method (create, update)
     * @param boolean $sub_module
     */
    public function createAuditTrail($id, $method, $sub_module = false) {
        # Audit Trail
        $text = $action = '';
        $module = 'COB Finance File';
        
        // if($sub_module) {
        //     $text = "with id : $id";
        //     $module = 'COB Finance';
        // } else {
            $files = Finance::find($id);
            $text = ': ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName());
        // }
        
        if($method == 'create') {
            $action = 'created';
        } else if($method == 'update') {
            $action = 'updated';
        }

        $auditTrail = new AuditTrail();
        $auditTrail->module = $module;
        $auditTrail->remarks = "Finance File $text has been $action.";
        $auditTrail->audit_by = Auth::user()->id;
        $auditTrail->save();

        return [
            'status' => 200,
            'message' => $auditTrail->remarks
        ];
    }

    /**
     * Get summary selected attributes
     */
    public function getSummaryAttribute() {
        /**
         * bill_air = (utility(bhg_a)(A+B+C) + utility(bhg_b)(A+B+C)) - bil_air + bil_meter_air
         * bill_elektrik = (utility(bhg_a)(A+B+C)) - bil_elektrik
         * caruman_cukai = (utility(bhg_b)(A+B+C)) - bil_cukai_tanah
         * jumlah_pembelanjaan = (sum all the fields above)
         */
        $data = [
            'bill_air' => 0,
            'bill_elektrik' => 0,
            'caruman_cukai' => 0,
            'utility' => 0,
            'contract' => 0,
            'repair' => 0,
            'vandalisme' => 0,
            'staff' => 0,
            'admin' => 0,
            
        ];
        return $data;
    }

    public function createOrUpdateFile($data, $id = null) {
        
        $response = '';
        try {
            
            $file_params_needs = $this->config['main']['only'];
            $params = Arr::only($data, $file_params_needs);

            // /**
            //  * Validation Process
            //  */
            // $validate_data = (new FinanceValidatorController())->validateFile($params);
            
            // if($validate_data['status'] == 422) {
                
            //     return $validate_data;
            // }
            
            DB::transaction(function() use($params, &$response) {
                
                $file_no = $params['file_no'];
                $year = $params['year'];
                $month = $params['month'];
                $from_api = $params['from_api'];

                /**
                 * Create Process
                 */
                $file = Files::where(compact('file_no'))->first();

                if ($file) {
                    $check_exist = Finance::where('file_id', $file->getKey())
                                            ->where('year', $year)
                                            ->where('month', $month)
                                            ->where('is_deleted', $this->others['is_deleted']['false']['slug'])
                                            ->count();
                    
                    $finance = new Finance();
                    if ($check_exist <= 0) {
                        $finance->file_id = $file->getKey();
                        $finance->company_id = $file->company_id;
                        $finance->month = $month;
                        $finance->year = $year;
                        $finance->from_api = $from_api;
                        $finance->is_active = $this->others['status']['active']['slug'];
                        $finance->save();
                        
                        $response = [
                            'status' => 200,
                            'data'  => $finance
                        ];
                    } else {
                        
                        $response = [
                            'status' => 400,
                            'message' => 'Finance File already exists!'
                        ];
                    }
                    
                    return $response;
                } else {
                    
                    $response = [
                        'status' => 400,
                        'message' => "File No $file_no was not found in our records !"
                    ];
                    return $response;
                }

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateCheck($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            /**
             * Validation Process
             */
            // $validate_data = (new FinanceValidatorController())->validateCheck($data);
           
            // if($validate_data['status'] == 422) {
                
            //     return $validate_data;
            // }

            $params_needed = $this->config['check']['only'];
            $params = Arr::only($data['check'], $params_needed);
            // $params['finance_file_id'] = $finance_id;
            
            DB::transaction(function() use($params, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                /**
                 * create or update check process
                 */
                $check = new FinanceCheck();
                if($is_update) {
                    $check = FinanceCheck::where('finance_file_id', $finance_id)->first();
                }else {
                    $check = FinanceCheck::where('finance_file_id', $finance_id)->first();
                    if(empty($check) == true) {
                        $check = new FinanceCheck();
                    }
                }
                
                if(empty($check->id) == false) {
                    $check_clone = FinanceCheckOld::firstOrNew(array('finance_file_id' => $finance_id));
                
                    $check_clone->date = $check->date;
                    $check_clone->name = $check->name;
                    $check_clone->position = $check->position;
                    $check_clone->is_active = $check->is_active;
                    $check_clone->remarks = $check->remarks;
                    $check_clone->save();
                }

                $check->finance_file_id = $finance_id;
                $check->date = $params['date'];
                $check->name = $params['name'];
                $check->position = $params['position'];
                $check->is_active = (empty($params['is_active']) == false)? $params['is_active'] : $this->others['status']['active']['slug'];
                $check->remarks = $params['remarks'];
                $check->save();

                $response = [
                    'status' => 200,
                    'data'  => $check
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }
    
    public function createOrUpdateSummary($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            /**
             * Validation Process
             */
            // $validate_data = (new FinanceValidatorController())->validateSummary($data);
           
            // if($validate_data['status'] == 422) {
                
            //     return $validate_data;
            // }
            $my_config = $this->config['summary'];
            $params_needed = $my_config['only'];
            $params = Arr::only($data['summary'], $params_needed);
            
            DB::transaction(function() use($params, $params_needed, $finance_id, $my_config, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                /**
                 * create summary process
                 */
                if($is_update) {
                    $delete_summary = $this->deleteFinanceSummary($finance_id);                    
                }
                $prefix = $my_config['prefix'];
                $i = 1;
                foreach($params_needed as $key) {
                    $summary = new FinanceSummary;
                    $summary->finance_file_id = $finance_id;
                    $summary->name = $this->tbl_fields_name[$prefix . $key];
                    $summary->summary_key = $key;
                    $summary->amount = (empty($params[$key]) == false)? $params[$key] : 0;
                    $summary->sort_no = $i;
                    $new_data = $summary->save();

                    $i++;

                }

                $response = [
                    'status' => 200,
                    'data'  => $new_data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateReport($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            /**
             * Validation Process
             */
            // $validate_data = (new FinanceValidatorController())->validateReport($data);
           
            // if($validate_data['status'] == 422) {
                
            //     return $validate_data;
            // }
            
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $params = [];
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                if($is_update) {
                    $current_items = FinanceReportPerbelanjaan::where('finance_file_id', $finance_id)->get();
                    FinanceReportPerbelanjaanOld::where('finance_file_id', $finance_id)->get();

                    foreach($current_items as $current) {
                        $frp_clone = FinanceReportPerbelanjaanOld::firstOrNew(array('finance_file_id' => $current->finance_file_id, 
                                                                                'type' => $current->type,
                                                                                'report_key' => $current->report_key));
    
                        $frp_clone->name = $current->name;
                        $frp_clone->amount = $current->amount;
                        $frp_clone->sort_no = $current->sort_no;
                        $frp_clone->is_custom = $current->is_custom;
                        $frp_clone->save();
                    }

                    FinanceReportPerbelanjaan::where('finance_file_id', $finance_id)->delete();
                }

                /**
                 * create report process
                 */
                $new_data = '';
                $my_config = $this->config['report'];
                $prefix = $my_config['prefix'];

                foreach($data['report'] as $key => $val) {
                    
                    $params_needed = Arr::get($my_config['type'],"$key.only");
                    $extra_params = Arr::get($my_config,"extra");
                    $params_needed = array_merge($params_needed, $extra_params);
                    $params = Arr::only($data['report'][$key], $params_needed);
                    $type = $my_config['type'][$key]['name'];
                    
                    $report = new FinanceReport();
                    if($is_update) {
                        $report = FinanceReport::where('finance_file_id', $finance_id)->where('type', $key)->first();
                        $report_clone = FinanceReportOld::firstOrNew(array('finance_file_id' => $finance_id, 'type' => $key));

                        $report_clone->fee_sebulan = $report->fee_sebulan;
                        $report_clone->unit = $report->unit;
                        $report_clone->fee_semasa = $report->fee_semasa;
                        $report_clone->no_akaun = $report->no_akaun;
                        $report_clone->nama_bank = $report->nama_bank;
                        $report_clone->baki_bank_awal = $report->baki_bank_awal;
                        $report_clone->baki_bank_akhir = $report->baki_bank_akhir;
                        $report_clone->save();
                    }
                    $report->finance_file_id = $finance_id;
                    $report->type = $type;
                    $report->fee_sebulan = $val['fee_sebulan'];
                    $report->unit = $val['unit'];
                    $report->fee_semasa = $val['fee_semasa'];
                    $report->no_akaun = $val['no_akaun'];
                    $report->nama_bank = $val['nama_bank'];
                    $report->baki_bank_awal = $val['baki_bank_awal'];
                    $report->baki_bank_akhir = $val['baki_bank_akhir'];
                    $report->save();

                    if($report) {
                        
                        $normal_params = Arr::except($params,'is_custom');
                        foreach($normal_params as $key2 => $val2) {
                            $frp = new FinanceReportPerbelanjaan();
                            
                            $frp->type = $type;
                            $frp->finance_file_id = $finance_id;
                            $frp->name = $this->tbl_fields_name[$prefix . $key2];
                            $frp->report_key = $key2;
                            $frp->amount = $val2;
                            $frp->sort_no = array_search($key2, array_keys($params)) + 1;
                            $frp->is_custom = 0;
                            $frp->save();
                            

                            $new_data = $frp;
                        }
                        
                        /**
                         * Create custom finance report perbelanjaan process
                         */
                        if($key == 'sf') {

                            if(empty($params['is_custom']) == false) {
                                foreach($params['is_custom'] as $key3 => $val3) {
                                    $frp = new FinanceReportPerbelanjaan();
                                    $frp->type = $type;
                                    $frp->finance_file_id = $finance_id;
                                    $frp->name = $val3['name'];
                                    $frp->report_key = 'custom'. ($new_data->sort_no + 1);
                                    $frp->amount = $val3['amount'];
                                    $frp->sort_no = $new_data->sort_no + (1);
                                    $frp->is_custom = 1;
                                    $frp->save();

                                    $new_data = $frp;
                                }
                            }
                        }

                        $extra_currents = FinanceReportExtra::where('finance_file_id', $finance_id)->where('type', $key)->get();
                        if(count($extra_currents) > 0) {
                            FinanceReportExtraOld::where('finance_file_id', $finance_id)->where('type', $key)->delete();
                            foreach($extra_currents as $extra) {
                                $extra_old = FinanceReportExtraOld::firstOrNew(array('finance_file_id' => $finance_id,
                                                                                        'type' => $key));
            
                                $extra_old->fee_sebulan = $extra->fee_sebulan;
                                $extra_old->unit = $extra->unit;
                                $extra_old->fee_semasa = $extra->fee_semasa;
                                $extra_old->save();
                            }
                        }

                        $delete_extra_currents = FinanceReportExtra::where('finance_file_id', $finance_id)->where('type', $key)->delete();
                        if(!empty($val['extra']) && count($val['extra']) > 0) {
                            foreach($val['extra'] as $extra_val) {
                                $new_extra = new FinanceReportExtra();

                                $new_extra->finance_file_id = $finance_id;
                                $new_extra->type = $key;
                                $new_extra->fee_sebulan = $extra_val['fee_sebulan'];
                                $new_extra->unit = $extra_val['unit'];
                                $new_extra->fee_semasa = $extra_val['fee_semasa'];
                                $new_extra->save();
                            }
                        }
                        
                    }

                }

                $response = [
                    'status' => 200,
                    'data'  => $new_data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateIncome($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }
                
                if($is_update) {
                    $this->deleteFinanceIncome($finance_id);
                }

                /**
                 * create income process
                 */
                $new_data = '';
                $my_config = $this->config['income'];
                $prefix = $my_config['prefix'];
                $default_params = $my_config['default'];
                $count = 0;
                if(!empty($data['income']['main'])) {

                    foreach($default_params as $key) {
                        $get_key = array_search($key, array_column($data['income']['main'], 'default'));
                        $income = new FinanceIncome();
                        $tunggakan = $semasa = $hadapan = '';
                        if(!is_bool($get_key) && $get_key >= 0) {
                            $get_array = $data['income']['main'][$get_key];
                            
                            $tunggakan = $get_array['tunggakan'];
                            $semasa = $get_array['semasa'];
                            $hadapan = $get_array['hadapan'];
                            
                            $get_array = '';
                        } else {
                            $tunggakan = $semasa = $hadapan = 0;

                        }
                        $income->finance_file_id = $finance_id;
                        $income->name = $this->tbl_fields_name[$prefix . $key];
                        $income->tunggakan = $tunggakan;
                        $income->semasa = $semasa;
                        $income->hadapan = $hadapan;
                        $income->sort_no = ++$count;
                        $income->save();

                        $new_data = $income;
                    }
                    
                }
                if(!empty($data['income']['is_custom'])) {

                    foreach($data['income']['is_custom'] as $val) {
                        $income = new FinanceIncome();
                        $income->finance_file_id = $finance_id;
                        $income->name = $val['name'];
                        $income->tunggakan = $val['tunggakan'];
                        $income->semasa = $val['semasa'];
                        $income->hadapan = $val['hadapan'];
                        $income->sort_no = ++$count;
                        $income->is_custom = 1;
                        $income->save();

                        $new_data = $income;
                    }
                }
                

                $response = [
                    'status' => 200,
                    'data'  => $new_data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }
    
    public function createOrUpdateUtility($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                if($is_update) {
                    $this->deleteFinanceUtility($finance_id);
                }

                /**
                 * create utility process
                 */
                $new_data = '';
                $my_config = $this->config['utility'];
                $prefix = $my_config['prefix'];
                $params = Arr::get($my_config,"only");
                foreach($data['utility'] as $key => $val) {
                    $count = 0;
                    $default_params = $my_config['type'][$key]['default'];
                    foreach($default_params as $key1) {
                        $get_key = array_search($key1, array_column($val['main'], 'default'));
                        $name = $tunggakan = $semasa = $hadapan = $tertunggak = '';

                        $finance = new FinanceUtility;
                        //if finance key not found will define default value in it
                        if(!is_bool($get_key) && $get_key >= 0) {
                            $get_array = $val['main'][$get_key];
                            $params_needed = Arr::only($get_array,$params);
                            
                            $name = $this->tbl_fields_name[$prefix . $params_needed['default']];
                            $tunggakan = $params_needed['tunggakan'];
                            $semasa = $params_needed['semasa'];
                            $hadapan = $params_needed['hadapan'];
                            $tertunggak = $params_needed['tertunggak'];
                            
                            $get_key='';

                        } else {
                            $name = $this->tbl_fields_name[$prefix . $key1];
                            $tunggakan = 0;
                            $semasa = 0;
                            $hadapan = 0;
                            $tertunggak = 0;

                        }

                        $finance->finance_file_id = $finance_id;
                        $finance->name = $name;
                        $finance->type = $my_config["type"][$key]['name'];
                        $finance->tunggakan = $tunggakan;
                        $finance->semasa = $semasa;
                        $finance->hadapan = $hadapan;
                        $finance->tertunggak = $tertunggak;
                        $finance->sort_no = ++$count;
                        $finance->is_custom = 0;
                        $finance->save();

                        $new_data = $finance;

                        /**
                         * define summary attribute
                         * (utility(bhg_a)(A+B+C) + utility(bhg_b)(A+B+C)) - bil_air + bil_meter_air
                         */
                        if(in_array($key1,['bil_air','bil_meter_air'])) {
                            $data['summary']['bill_air'] += ($finance->tunggakan + $finance->semasa + $finance->hadapan);
                        }

                        /**
                         * define summary attribute
                         * (utility(bhg_a)(A+B+C)) - bil_elektrik
                         */
                        if(in_array($key1,['bil_elektrik'])) {
                            $data['summary']['bill_elektrik'] += ($finance->tunggakan + $finance->semasa + $finance->hadapan);
                        }
                        
                        /**
                         * define summary attribute
                         * caruman_cukai = (utility(bhg_b)(A+B+C)) - bil_cukai_tanah
                         */
                        if(in_array($key1,['bil_cukai_tanah'])) {
                            $data['summary']['caruman_cukai'] += ($finance->tunggakan + $finance->semasa + $finance->hadapan);
                        }
                    }

                    if($key == "bhg_b") {

                        if(empty($val['is_custom']) == false) {
                            foreach($val['is_custom'] as $key2 => $val2) {
                                $params_needed = Arr::only($val2,$params);
        
                                $finance = new FinanceUtility;
                                $finance->finance_file_id = $finance_id;
                                $finance->name = $params_needed['name'];
                                $finance->type = $my_config["type"][$key]['name'];
                                $finance->tunggakan = $params_needed['tunggakan'];
                                $finance->semasa = $params_needed['semasa'];
                                $finance->hadapan = $params_needed['hadapan'];
                                $finance->tertunggak = $params_needed['tertunggak'];
                                $finance->sort_no = ++$count;
                                $finance->is_custom = 1;
                                $finance->save();
                            }
    
                            $new_data = $finance;
                        }
                    }
                }
                

                $response = [
                    'status' => 200,
                    'data'  => $data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateContract($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                if($is_update) {
                    $this->deleteFinanceContract($finance_id);
                }

                /**
                 * create contract process
                 */
                $new_data = '';
                $my_config = $this->config['contract'];
                $prefix = $my_config['prefix'];
                $default_params = $my_config['default'];

                $count = 0;
                if(!empty($data['contract']['main'])) {

                    foreach($default_params as $key) {
                        $get_key = array_search($key, array_column($data['contract']['main'], 'default'));
                        $contract = new FinanceContract();
                        $tunggakan = $semasa = $hadapan = $tertunggak = '';
                        if(!is_bool($get_key) && $get_key >= 0) {
                            $get_array = $data['contract']['main'][$get_key];
                            
                            $tunggakan = $get_array['tunggakan'];
                            $semasa = $get_array['semasa'];
                            $hadapan = $get_array['hadapan'];
                            $tertunggak = $get_array['tertunggak'];
                            
                            $get_array = '';

                        } else {
                            $tunggakan = $semasa = $hadapan = $tertunggak = 0;

                        }
                        $contract->finance_file_id = $finance_id;
                        $contract->name = $this->tbl_fields_name[$prefix . $key];
                        $contract->tunggakan = $tunggakan;
                        $contract->semasa = $semasa;
                        $contract->hadapan = $hadapan;
                        $contract->tertunggak = $tertunggak;
                        $contract->sort_no = ++$count;
                        $contract->save();

                        $new_data = $contract;
                        /**
                         * 
                         * contract
                         */
                        $data['summary']['contract'] += ($contract->tunggakan + $contract->semasa + $contract->hadapan);
                    }
                }

                if(!empty($data['contract']['is_custom'])) {

                    foreach($data['contract']['is_custom'] as $val) {
                        $contract = new FinanceContract();
                        $contract->finance_file_id = $finance_id;
                        $contract->name = $val['name'];
                        $contract->tunggakan = $val['tunggakan'];
                        $contract->semasa = $val['semasa'];
                        $contract->hadapan = $val['hadapan'];
                        $contract->tertunggak = $val['tertunggak'];
                        $contract->sort_no = ++$count;
                        $contract->is_custom = 1;
                        $contract->save();

                        $new_data = $contract;
                        /**
                         * 
                         * contract
                         */
                        $data['summary']['contract'] += ($contract->tunggakan + $contract->semasa + $contract->hadapan);
                    }
                }
                

                $response = [
                    'status' => 200,
                    'data'  => $data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateRepair($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $params = [];
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                if($is_update) {
                    $this->deleteFinanceRepair($finance_id);
                }

                /**
                 * create repair process
                 */
                $new_data = '';
                $my_config = $this->config['repair'];
                $prefix = $my_config['prefix'];

                foreach($data['repair'] as $key => $val) {
                    $default_params = $my_config['type'][$key]['default'];
                    
                    $count = 0;
                    if(!empty($data['repair'][$key]['main'])) {

                        foreach($default_params as $key1) {
                            $get_key = array_search($key1, array_column($data['repair'][$key]['main'], 'default'));
                            $repair = new FinanceRepair();
                            $tunggakan = $semasa = $hadapan = $tertunggak = '';
                            if(!is_bool($get_key) && $get_key >= 0) {
                                $get_array = $data['repair'][$key]['main'][$get_key];
                                
                                $tunggakan = $get_array['tunggakan'];
                                $semasa = $get_array['semasa'];
                                $hadapan = $get_array['hadapan'];
                                $tertunggak = $get_array['tertunggak'];
                                
                                $get_array = '';

                            } else {
                                $tunggakan = $semasa = $hadapan = $tertunggak = 0;

                            }
                            $repair->finance_file_id = $finance_id;
                            $repair->name = $this->tbl_fields_name[$prefix . $key1];
                            $repair->type = $my_config['type'][$key]['name'];
                            $repair->tunggakan = $tunggakan;
                            $repair->semasa = $semasa;
                            $repair->hadapan = $hadapan;
                            $repair->tertunggak = $tertunggak;
                            $repair->sort_no = ++$count;
                            $repair->save();

                            $new_data = $repair;

                            /**
                             * define summary attribute
                             * repair
                             */
                            $data['summary']['repair'] += ($repair->tunggakan + $repair->semasa + $repair->hadapan);
                        }
                    }

                    if(!empty($data['repair'][$key]['is_custom'])) {

                        foreach($data['repair'][$key]['is_custom'] as $val) {
                            $repair = new FinanceRepair();
                            $repair->finance_file_id = $finance_id;
                            $repair->name = $val['name'];
                            $repair->type = $my_config['type'][$key]['name'];
                            $repair->tunggakan = $val['tunggakan'];
                            $repair->semasa = $val['semasa'];
                            $repair->hadapan = $val['hadapan'];
                            $repair->tertunggak = $val['tertunggak'];
                            $repair->sort_no = ++$count;
                            $repair->is_custom = 1;
                            $repair->save();

                            $new_data = $repair;
                            
                            /**
                             * define summary attribute
                             * repair
                             */
                            $data['summary']['repair'] += ($repair->tunggakan + $repair->semasa + $repair->hadapan);
                        }
                    }

                }

                $response = [
                    'status' => 200,
                    'data'  => $data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateVandal($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $params = [];
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                if($is_update) {
                    $this->deleteFinanceVandal($finance_id);
                }

                /**
                 * create vandal process
                 */
                $new_data = '';
                $my_config = $this->config['vandal'];
                $prefix = $my_config['prefix'];

                foreach($data['vandal'] as $key => $val) {
                    $default_params = $my_config['type'][$key]['default'];
                    
                    $count = 0;
                    if(!empty($data['vandal'][$key]['main'])) {

                        foreach($default_params as $key1) {
                            $get_key = array_search($key1, array_column($data['vandal'][$key]['main'], 'default'));
                            $vandal = new FinanceVandal();
                            $tunggakan = $semasa = $hadapan = $tertunggak = '';
                            if(!is_bool($get_key) && $get_key >= 0) {
                                $get_array = $data['vandal'][$key]['main'][$get_key];
                                
                                $tunggakan = $get_array['tunggakan'];
                                $semasa = $get_array['semasa'];
                                $hadapan = $get_array['hadapan'];
                                $tertunggak = $get_array['tertunggak'];
                                
                                $get_array = '';

                            } else {
                                $tunggakan = $semasa = $hadapan = $tertunggak = 0;

                            }
                            $vandal->finance_file_id = $finance_id;
                            $vandal->name = $this->tbl_fields_name[$prefix . $key1];
                            $vandal->type = $my_config['type'][$key]['name'];
                            $vandal->tunggakan = $tunggakan;
                            $vandal->semasa = $semasa;
                            $vandal->hadapan = $hadapan;
                            $vandal->tertunggak = $tertunggak;
                            $vandal->sort_no = ++$count;
                            $vandal->save();

                            $new_data = $vandal;

                            /**
                             * define summary attribute
                             * vandalisme
                             */
                            $data['summary']['vandalisme'] += ($vandal->tunggakan + $vandal->semasa + $vandal->hadapan);
                        }
                    }

                    if(!empty($data['vandal'][$key]['is_custom'])) {

                        foreach($data['vandal'][$key]['is_custom'] as $val) {
                            $vandal = new FinanceVandal();
                            $vandal->finance_file_id = $finance_id;
                            $vandal->name = $val['name'];
                            $vandal->type = $my_config['type'][$key]['name'];
                            $vandal->tunggakan = $val['tunggakan'];
                            $vandal->semasa = $val['semasa'];
                            $vandal->hadapan = $val['hadapan'];
                            $vandal->tertunggak = $val['tertunggak'];
                            $vandal->sort_no = ++$count;
                            $vandal->is_custom = 1;
                            $vandal->save();

                            $new_data = $vandal;
                            
                            /**
                             * define summary attribute
                             * vandalisme
                             */
                            $data['summary']['vandalisme'] += ($vandal->tunggakan + $vandal->semasa + $vandal->hadapan);
                        }
                    }

                }

                $response = [
                    'status' => 200,
                    'data'  => $new_data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateStaff($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                if($is_update) {
                    $this->deleteFinanceStaff($finance_id);
                }

                /**
                 * create staff process
                 */
                $new_data = '';
                $my_config = $this->config['staff'];
                $prefix = $my_config['prefix'];
                $default_params = $my_config['default'];

                $count = 0;
                if(!empty($data['staff']['main'])) {

                    foreach($default_params as $key) {
                        $get_key = array_search($key, array_column($data['staff']['main'], 'default'));
                        $staff = new FinanceStaff();
                        $tunggakan = $semasa = $hadapan = $tertunggak = $gaji_per_orang = $bil_pekerja = '';
                        if(!is_bool($get_key) && $get_key >= 0) {
                            $get_array = $data['staff']['main'][$get_key];
                            
                            $gaji_per_orang = $get_array['gaji_per_orang'];
                            $bil_pekerja = $get_array['bil_pekerja'];
                            $tunggakan = $get_array['tunggakan'];
                            $semasa = $get_array['semasa'];
                            $hadapan = $get_array['hadapan'];
                            $tertunggak = $get_array['tertunggak'];
                            
                            $get_array = '';

                        } else {
                            $tunggakan = $semasa = $hadapan = $tertunggak = $gaji_per_orang = $bil_pekerja = 0;

                        }
                        $staff->finance_file_id = $finance_id;
                        $staff->name = $this->tbl_fields_name[$prefix . $key];
                        $staff->gaji_per_orang = $gaji_per_orang;
                        $staff->bil_pekerja = $bil_pekerja;
                        $staff->tunggakan = $tunggakan;
                        $staff->semasa = $semasa;
                        $staff->hadapan = $hadapan;
                        $staff->tertunggak = $tertunggak;
                        $staff->sort_no = $count;
                        $staff->save();
                        $count++;
                        $new_data = $staff;

                        /**
                         * define summary attribute
                         * staff
                         */
                        $data['summary']['staff'] += ($staff->gaji_per_orang * $staff->bil_pekerja);
                    }
                }

                if(!empty($data['staff']['is_custom'])) {

                    foreach($data['staff']['is_custom'] as $val) {
                        $staff = new FinanceStaff();
                        $staff->finance_file_id = $finance_id;
                        $staff->name = $val['name'];
                        $staff->gaji_per_orang = $val['gaji_per_orang'];
                        $staff->bil_pekerja = $val['bil_pekerja'];
                        $staff->tunggakan = $val['tunggakan'];
                        $staff->semasa = $val['semasa'];
                        $staff->hadapan = $val['hadapan'];
                        $staff->tertunggak = $val['tertunggak'];
                        $staff->sort_no = $count;
                        $staff->is_custom = 1;
                        $staff->save();

                        $count++;
                        $new_data = $staff;
                        
                        /**
                         * define summary attribute
                         * staff
                         */
                        $data['summary']['staff'] += ($staff->gaji_per_orang * $staff->bil_pekerja);
                    }
                }
                

                $response = [
                    'status' => 200,
                    'data'  => $data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function createOrUpdateAdmin($data, $finance_id, $is_update = false) {

        $response = '';
        try {
            
            
            DB::transaction(function() use($data, $finance_id, &$response, $is_update) {
                /**
                 * find finance file
                 */
                $finance = $this->findFinanceFile($finance_id);
                if(in_array($finance['status'],[400, 404, 422])) {
                    $response = $finance;
                    return $finance;
                }

                if($is_update) {
                    $this->deleteFinanceAdmin($finance_id);
                }

                /**
                 * create admin process
                 */
                $new_data = '';
                $my_config = $this->config['admin'];
                $prefix = $my_config['prefix'];
                $default_params = $my_config['default'];

                $count = 0;
                if(!empty($data['admin']['main'])) {

                    foreach($default_params as $key) {
                        $get_key = array_search($key, array_column($data['admin']['main'], 'default'));
                        $admin = new FinanceAdmin();
                        $tunggakan = $semasa = $hadapan = $tertunggak = '';
                        if(!is_bool($get_key) && $get_key >= 0) {
                            $get_array = $data['admin']['main'][$get_key];
                            
                            $tunggakan = $get_array['tunggakan'];
                            $semasa = $get_array['semasa'];
                            $hadapan = $get_array['hadapan'];
                            $tertunggak = $get_array['tertunggak'];
                            
                            $get_array = '';

                        } else {
                            $tunggakan = $semasa = $hadapan = $tertunggak = 0;

                        }
                        $admin->finance_file_id = $finance_id;
                        $admin->name = $this->tbl_fields_name[$prefix . $key];
                        $admin->tunggakan = $tunggakan;
                        $admin->semasa = $semasa;
                        $admin->hadapan = $hadapan;
                        $admin->tertunggak = $tertunggak;
                        $admin->sort_no = $count;
                        $admin->save();

                        $count++;
                        $new_data = $admin;
                        
                        /**
                         * define summary attribute
                         * admin
                         */
                        $data['summary']['admin'] += ($admin->tunggakan + $admin->semasa + $admin->hadapan);
                    }
                }

                if(!empty($data['admin']['is_custom'])) {

                    foreach($data['admin']['is_custom'] as $val) {
                        $admin = new FinanceAdmin();
                        $admin->finance_file_id = $finance_id;
                        $admin->name = $val['name'];
                        $admin->tunggakan = $val['tunggakan'];
                        $admin->semasa = $val['semasa'];
                        $admin->hadapan = $val['hadapan'];
                        $admin->tertunggak = $val['tertunggak'];
                        $admin->sort_no = $count;
                        $admin->is_custom = 1;
                        $admin->save();
                        
                        $count++;
                        $new_data = $admin;

                        /**
                         * define summary attribute
                         * admin
                         */
                        $data['summary']['admin'] += ($admin->tunggakan + $admin->semasa + $admin->hadapan);
                    }
                }
                

                $response = [
                    'status' => 200,
                    'data'  => $data
                ];
                
                return $response;
                

            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    } 

    public function addNewFinance() {
        
        $response = '';
        try {
            
            
            $request_params = Request::all();
            $request_params['summary'] = array();
            
            DB::transaction(function() use($request_params, &$response) {
                /*
                *Process all validation before create process
                *
                */
                // validate file process
                $validate_data = (new FinanceValidatorController())->validateFile($request_params);
                
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file check process
                $validate_data = (new FinanceValidatorController())->validateCheck($request_params);
           
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file report process
                $validate_data = (new FinanceValidatorController())->validateReport($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file income process
                $validate_data = (new FinanceValidatorController())->validateIncome($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file utility process
                $validate_data = (new FinanceValidatorController())->validateUtility($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file contract process
                $validate_data = (new FinanceValidatorController())->validateContract($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file repair process
                $validate_data = (new FinanceValidatorController())->validateRepair($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file vandalisme process
                $validate_data = (new FinanceValidatorController())->validateVandal($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file staff process
                $validate_data = (new FinanceValidatorController())->validateStaff($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file admin process
                $validate_data = (new FinanceValidatorController())->validateAdmin($request_params);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }
                
                /**
                 * Get Summary Attributes
                 */
                $request_params['summary'] = $this->getSummaryAttribute();
                
                /*
                * create File
                */
                $finance_id = '';
                $create_file = $this->createOrUpdateFile($request_params);
                if(in_array($create_file['status'],[400, 404, 422])) {
                    $response = $create_file;
                    return $response;
                }
                $finance_id = $create_file['data']['id'];

                /*
                * create Check
                */
                $create_check = '';
                if($create_file['status'] == 200) {
                    $create_check = $this->createOrUpdateCheck($request_params, $finance_id);
                }
                
                if(in_array($create_check['status'],[400, 404, 422])) {
                    //delete finance
                    $this->deleteAllFinanceRecord($finance_id);
                    
                    $response = $create_check;
                    return $response;
                }
    
                /*
                * create Report
                */
                $create_report = '';
                if($create_check['status'] == 200) {
                    $create_report = $this->createOrUpdateReport($request_params, $finance_id);
                }
                
                if(in_array($create_report['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_report;
                    return $response;
                }
    
                /*
                * create Vandalisme
                */
                $create_vandal = '';
                if($create_report['status'] == 200) {
                    $create_vandal = $this->createOrUpdateVandal($request_params, $finance_id);
                }
                
                if(in_array($create_vandal['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_vandal;
                    return $response;
                }
    
                /*
                * create Income
                */
                $create_income = '';
                if($create_vandal['status'] == 200) {
                    $create_income = $this->createOrUpdateIncome($request_params, $finance_id);
                }
                
                if(in_array($create_income['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_income;
                    return $response;
                }
    
                /*
                * create Utility
                */
                $create_utility = '';
                if($create_income['status'] == 200) {
                    $create_utility = $this->createOrUpdateUtility($request_params, $finance_id);
                    $request_params['summary'] = array_merge($request_params['summary'], $create_utility['data']['summary']);
                }

                if(in_array($create_utility['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_utility;
                    return $response;
                }
    
                /*
                * create Contract
                */
                $create_contract = '';
                if($create_utility['status'] == 200) {
                    $create_contract = $this->createOrUpdateContract($request_params, $finance_id);
                    $request_params['summary'] = array_merge($request_params['summary'], $create_contract['data']['summary']);
                }
                
                if(in_array($create_contract['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_contract;
                    return $response;
                }
    
                /*
                * create Repair
                */
                $create_repair = '';
                if($create_contract['status'] == 200) {
                    $create_repair = $this->createOrUpdateRepair($request_params, $finance_id);
                    $request_params['summary'] = array_merge($request_params['summary'], $create_repair['data']['summary']);
                }
                
                if(in_array($create_repair['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_repair;
                    return $response;
                }
    
                /*
                * create Staff
                */
                $create_staff = '';
                if($create_repair['status'] == 200) {
                    $create_staff = $this->createOrUpdateStaff($request_params, $finance_id);
                    $request_params['summary'] = array_merge($request_params['summary'], $create_staff['data']['summary']);
                }
                
                if(in_array($create_staff['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_staff;
                    return $response;
                }
    
                /*
                * create Admin
                */
                $create_admin = '';
                if($create_staff['status'] == 200) {
                    $create_admin = $this->createOrUpdateAdmin($request_params, $finance_id);
                    $request_params['summary'] = array_merge($request_params['summary'], $create_admin['data']['summary']);
                }
                
                if(in_array($create_admin['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_admin;
                    return $response;
                }

                /*
                * create Summary
                */
                $create_summary = '';
                $create_summary = $this->createOrUpdateSummary($request_params, $finance_id);
                
                
                if(in_array($create_summary['status'],[400, 404, 422])) {
                    //delete 
                    $this->deleteAllFinanceRecord($finance_id);
                    $response = $create_summary;
                    return $response;
                }
                
                $response = $this->createAuditTrail($finance_id,'create');
                
                return $response;
            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }
    
    public function addNewFinanceCheck() {
        
        $response = '';
        try {
            
            
            $request_params = Input::all();
            
            DB::transaction(function() use($request_params, &$response) {
                /*
                *Process all validation before create process
                *
                */

                // validate file check process
                $validate_data = (new FinanceValidatorController())->validateCheck($request_params, true);
           
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }
                
                
                /*
                * check finance file exist
                */
                $finance_id = "";
                $check_finance = $this->findFinanceFileByNo($request_params['file_no'], $request_params['month'], $request_params['year']);
                if($check_finance['status'] == 404) {
                    $response = $check_finance;
                    return $response;
                }
                
                $finance_id = $check_finance['data']['id'];

                /*
                * create Check
                */
                $create_check = '';
                if($check_finance['status'] == 200) {
                    $create_check = $this->createOrUpdateCheck($request_params, $finance_id);
                }
                
                if(in_array($create_check['status'],[400, 404, 422])) {
                    
                    $response = $create_check;
                    return $response;
                }
    

                $response = $this->createAuditTrail($finance_id, 'create', true);

                return $response;
            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }
    
    public function addNewFinanceSummary() {
        
        $response = '';
        try {
            
            
            $request_params = Input::all();
            
            DB::transaction(function() use($request_params, &$response) {
                /*
                *Process all validation before create process
                *
                */

                // validate file summary process
                $validate_data = (new FinanceValidatorController())->validateSummary($request_params, true);
           
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }
                
                
                /*
                * check finance file exist
                */
                $finance_id = "";
                $check_finance = $this->findFinanceFileByNo($request_params['file_no'], $request_params['month'], $request_params['year']);
                if($check_finance['status'] == 404) {
                    $response = $check_finance;
                    return $response;
                }
                $finance_id = $check_finance['data']['id'];

                /*
                * create Summary
                */
                $create_summary = '';
                $create_summary = $this->createOrUpdateSummary($request_params, $finance_id);
                
                
                if(in_array($create_summary['status'],[400, 404, 422])) {
                    
                    $response = $create_summary;
                    return $response;
                }
    
                $response = $this->createAuditTrail($finance_id, 'create', true);

                return $response;
            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function updateFinance() {
        
        $response = '';
        try {
            
            
            $request_params = Input::all();
            $request_params['summary'] = array();
            DB::transaction(function() use($request_params, &$response) {
                /*
                *Process all validation before create process
                *
                */
                // validate file process
                $validate_data = (new FinanceValidatorController())->validateFile($request_params, true);
                
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }
                
                // validate file check process
                $validate_data = (new FinanceValidatorController())->validateCheck($request_params, true);
           
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file report process
                $validate_data = (new FinanceValidatorController())->validateReport($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file income process
                $validate_data = (new FinanceValidatorController())->validateIncome($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file utility process
                $validate_data = (new FinanceValidatorController())->validateUtility($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file contract process
                $validate_data = (new FinanceValidatorController())->validateContract($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file repair process
                $validate_data = (new FinanceValidatorController())->validateRepair($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file vandalisme process
                $validate_data = (new FinanceValidatorController())->validateVandal($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file staff process
                $validate_data = (new FinanceValidatorController())->validateStaff($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                // validate file admin process
                $validate_data = (new FinanceValidatorController())->validateAdmin($request_params, true);
            
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }

                /**
                 * Get Summary Attributes
                 */
                $request_params['summary'] = $this->getSummaryAttribute();
                
                
                $finance_id = "";
                $check_finance = $this->findFinanceFileByNo($request_params['file_no'], $request_params['month'], $request_params['year']);
                if($check_finance['status'] == 404) {
                    $response = $check_finance;
                    return $response;
                }
                $finance_id = $check_finance['data']['id'];

                /*
                * update Check
                */
                $update_check = '';
                $update_check = $this->createOrUpdateCheck($request_params, $finance_id, true);
                
                
                if(in_array($update_check['status'],[400, 404, 422])) {
                    
                    $response = $update_check;
                    return $response;
                }
    
                /*
                * create Report
                */
                $update_report = '';
                if($update_check['status'] == 200) {
                    $update_report = $this->createOrUpdateReport($request_params, $finance_id, true);
                }
                
                if(in_array($update_report['status'],[400, 404, 422])) {
                    $response = $update_report;
                    return $response;
                }
    
                /*
                * create Income
                */
                $update_income = '';
                if($update_report['status'] == 200) {
                    $update_income = $this->createOrUpdateIncome($request_params, $finance_id, true);
                }
                
                if(in_array($update_income['status'],[400, 404, 422])) {
                    
                    $response = $update_income;
                    return $response;
                }
    
                /*
                * create Utility
                */
                $update_utility = '';
                if($update_income['status'] == 200) {
                    $update_utility = $this->createOrUpdateUtility($request_params, $finance_id, true);
                    $request_params['summary'] = array_merge($request_params['summary'], $update_utility['data']['summary']);
                }
                
                if(in_array($update_utility['status'],[400, 404, 422])) {
                    $response = $update_utility;
                    return $response;
                }
    
                /*
                * create Contract
                */
                $update_contract = '';
                if($update_utility['status'] == 200) {
                    $update_contract = $this->createOrUpdateContract($request_params, $finance_id, true);
                    $request_params['summary'] = array_merge($request_params['summary'], $update_contract['data']['summary']);
                }
                
                if(in_array($update_contract['status'],[400, 404, 422])) {
                    $response = $update_contract;
                    return $response;
                }
    
                /*
                * create Repair
                */
                $update_repair = '';
                if($update_contract['status'] == 200) {
                    $update_repair = $this->createOrUpdateRepair($request_params, $finance_id, true);
                    $request_params['summary'] = array_merge($request_params['summary'], $update_repair['data']['summary']);
                }
                
                if(in_array($update_repair['status'],[400, 404, 422])) {
                    
                    $response = $update_repair;
                    return $response;
                }
    
                /*
                * create Vandalisme
                */
                $update_vandal = '';
                if($update_repair['status'] == 200) {
                    $update_vandal = $this->createOrUpdateVandal($request_params, $finance_id, true);
                }
                
                if(in_array($update_vandal['status'],[400, 404, 422])) {
                    
                    $response = $update_vandal;
                    return $response;
                }
    
                /*
                * create Staff
                */
                $update_staff = '';
                if($update_vandal['status'] == 200) {
                    $update_staff = $this->createOrUpdateStaff($request_params, $finance_id, true);
                    $request_params['summary'] = array_merge($request_params['summary'], $update_staff['data']['summary']);
                }
                
                if(in_array($update_staff['status'],[400, 404, 422])) {
                    
                    $response = $update_staff;
                    return $response;
                }
    
                /*
                * create Admin
                */
                $update_admin = '';
                if($update_staff['status'] == 200) {
                    $update_admin = $this->createOrUpdateAdmin($request_params, $finance_id, true);
                    $request_params['summary'] = array_merge($request_params['summary'], $update_admin['data']['summary']);
                }
                
                /*
                * create Summary
                */
                $update_summary = '';
                $update_summary = $this->createOrUpdateSummary($request_params, $finance_id, true);
                
                
                if(in_array($update_summary['status'],[400, 404, 422])) {
                    
                    $response = $update_summary;
                    return $response;
                }
                
                if(in_array($update_admin['status'],[400, 404, 422])) {
                    
                    $response = $update_admin;
                    return $response;
                }
                
                $response = $this->createAuditTrail($finance_id,'update');
                return $response;
            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }
    
    public function updateFinanceCheck() {
        
        $response = '';
        try {
            
            
            $request_params = Input::all();
            
            DB::transaction(function() use($request_params, &$response) {
                /*
                *Process all validation before create process
                *
                */

                // validate file check process
                $validate_data = (new FinanceValidatorController())->validateCheck($request_params, true);
           
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }
                
                /*
                * check finance file exist
                */
                $finance_id = "";
                $check_finance = $this->findFinanceFileByNo($request_params['file_no'], $request_params['month'], $request_params['year']);
                if($check_finance['status'] == 404) {
                    $response = $check_finance;
                    return $response;
                }
                $finance_id = $check_finance['data']['id'];

                /*
                * update Check
                */
                $update_check = '';
                if($check_finance['status'] == 200) {
                    $update_check = $this->createOrUpdateCheck($request_params, $finance_id, true);
                }
                
                if(in_array($update_check['status'],[400, 404, 422])) {
                    
                    $response = $update_check;
                    return $response;
                }
    

                $response = $this->createAuditTrail($finance_id, 'update', true);

                return $response;
            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }
    
    public function updateFinanceSummary() {
        
        $response = '';
        try {
            
            
            $request_params = Input::all();
            
            DB::transaction(function() use($request_params, &$response) {
                /*
                *Process all validation before create process
                *
                */

                // validate file summary process
                $validate_data = (new FinanceValidatorController())->validateSummary($request_params, true);
           
                if($validate_data['status'] == 422) {
                    $response = $validate_data;
                    return $response;
                }
                
                
                /*
                * check finance file exist
                */
                $finance_id = "";
                $check_finance = $this->findFinanceFileByNo($request_params['file_no'], $request_params['month'], $request_params['year']);
                if($check_finance['status'] == 404) {
                    $response = $check_finance;
                    return $response;
                }
                $finance_id = $check_finance['data']['id'];

                /*
                * update Summary
                */
                $update_summary = '';
                $update_summary = $this->createOrUpdateSummary($request_params, $finance_id, true);
                
                
                if(in_array($update_summary['status'],[400, 404, 422])) {
                    
                    $response = $update_summary;
                    return $response;
                }
    
                $response = $this->createAuditTrail($finance_id, 'update', true);

                return $response;
            });

        } catch (Exception $e) {
            throw($e);
        }

        return $response;
    }

    public function deleteAllFinanceRecord($id) {
        $response = '';
        try {
            $check_exist = $this->findFinanceFile($id);
            
            if($check_exist['status'] == 200) {
                $data = Finance::find($id);
                $data->financeAdmin()->delete();
                $data->financeCheck()->delete();
                $data->financeContract()->delete();
                $data->financeIncome()->delete();
                $data->financeRepair()->delete();
                $data->financeReportPerbelanjaan()->delete();
                $data->financeReport()->delete();
                $data->financeStaff()->delete();
                $data->financeSummary()->delete();
                $data->financeUtility()->delete();
                $data->financeVandal()->delete();
                $data->delete();
    
                
                $response = [
                    'status' => 200,
                    'data'  => 'Records deleted'
                ];

            } else {
                $response = $check_exist;
            }

        } catch(Exception $e) {
            throw($e);
        }
        return $response;
    }

    // public function deleteFinance($id) {
    //     $response = '';
    //     try {

    //         $response = Finance::find($id);
    //         $response->delete();

    //     } catch (Exception $e) {
    //         throw($e);
    //     }
    //     return $response;
    // }

    // public function deleteFinanceCheck($finance_file_id) {
    //     $response = '';
    //     try {

    //         $response = FinanceCheck::where('finance_file_id',$finance_file_id)->delete();

    //     } catch (Exception $e) {
    //         throw($e);
    //     }
    //     return $response;
    // }

    public function deleteFinanceSummary($finance_file_id) {
        $response = '';
        try {

            $items = FinanceSummary::where('finance_file_id',$finance_file_id)->get();
            FinanceSummaryOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $summary_clone = FinanceSummaryOld::firstOrNew(array('finance_file_id' => $finance_file_id, 'summary_key' => $item->summary_key));
                
                $summary_clone->name = $item->name;
                $summary_clone->amount = $item->amount;
                $summary_clone->sort_no = $item->sort_no;
                $summary_clone->save();
            }

            $response = FinanceSummary::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }

    // public function deleteFinanceReport($finance_file_id) {
    //     $response = '';
    //     try {
    //         FinanceReport::where('finance_file_id',$finance_file_id)->delete();
    //         $response = FinanceReportPerbelanjaan::where('finance_file_id',$finance_file_id)->delete();

    //     } catch (Exception $e) {
    //         throw($e);
    //     }
    //     return $response;
    // }

    public function deleteFinanceIncome($finance_file_id) {
        $response = '';
        try {
            $items = FinanceIncome::where('finance_file_id',$finance_file_id)->get();
            FinanceIncomeOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $old = FinanceIncomeOld::firstOrNew(['finance_file_id' => $finance_file_id,
                                                    'name' => $item->name]);
                $old->tunggakan = $item->tunggakan;
                $old->semasa = $item->semasa;
                $old->hadapan = $item->hadapan;
                $old->sort_no = $item->sort_no;
                $old->is_custom = $item->is_custom;
                $old->save();
            }

            $response = FinanceIncome::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }

    public function deleteFinanceUtility($finance_file_id) {
        $response = '';
        try {
            $items = FinanceUtility::where('finance_file_id',$finance_file_id)->get();
            FinanceUtilityOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $old = FinanceUtilityOld::firstOrNew(['finance_file_id' => $finance_file_id,
                                                    'name' => $item->name,
                                                    'type' => $item->type]);
                
                $old->tunggakan = $item->tunggakan;
                $old->semasa = $item->semasa;
                $old->hadapan = $item->hadapan;
                $old->tertunggak = $item->tertunggak;
                $old->sort_no = $item->sort_no;
                $old->is_custom = $item->is_custom;
                $old->save();
            }

            $response = FinanceUtility::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }

    public function deleteFinanceContract($finance_file_id) {
        $response = '';
        try {
            $items = FinanceContract::where('finance_file_id',$finance_file_id)->get();
            FinanceContractOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $old = FinanceContractOld::firstOrNew(['finance_file_id' => $finance_file_id,
                                                        'name' => $item->name]);
                     
                $old->tunggakan = $item->tunggakan;
                $old->semasa = $item->semasa;
                $old->hadapan = $item->hadapan;
                $old->tertunggak = $item->tertunggak;
                $old->sort_no = $item->sort_no;
                $old->is_custom = $item->is_custom;
                $old->save();                                   
            }
            $response = FinanceContract::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }

    public function deleteFinanceRepair($finance_file_id) {
        $response = '';
        try {
            $items = FinanceRepair::where('finance_file_id',$finance_file_id)->get();
            FinanceRepairOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $old = FinanceRepairOld::firstOrNew(['finance_file_id' => $finance_file_id,
                                                        'type' => $item->type,
                                                        'name' => $item->name]);
                     
                $old->tunggakan = $item->tunggakan;
                $old->semasa = $item->semasa;
                $old->hadapan = $item->hadapan;
                $old->tertunggak = $item->tertunggak;
                $old->sort_no = $item->sort_no;
                $old->is_custom = $item->is_custom;
                $old->save();                                   
            }
            
            $response = FinanceRepair::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }

    public function deleteFinanceVandal($finance_file_id) {
        $response = '';
        try {
            $items = FinanceVandal::where('finance_file_id',$finance_file_id)->get();
            FinanceVandalOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $old = FinanceVandalOld::firstOrNew(['finance_file_id' => $finance_file_id,
                                                        'type' => $item->type,
                                                        'name' => $item->name]);
                     
                $old->tunggakan = $item->tunggakan;
                $old->semasa = $item->semasa;
                $old->hadapan = $item->hadapan;
                $old->tertunggak = $item->tertunggak;
                $old->sort_no = $item->sort_no;
                $old->is_custom = $item->is_custom;
                $old->save();                                   
            }
            
            $response = FinanceVandal::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }

    public function deleteFinanceStaff($finance_file_id) {
        $response = '';
        try {
            $items = FinanceStaff::where('finance_file_id',$finance_file_id)->get();
            FinanceStaffOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $old = FinanceStaffOld::firstOrNew(['finance_file_id' => $finance_file_id,
                                                        'name' => $item->name]);
                     
                $old->gaji_per_orang = $item->gaji_per_orang;
                $old->bil_pekerja = $item->bil_pekerja;
                $old->tunggakan = $item->tunggakan;
                $old->semasa = $item->semasa;
                $old->hadapan = $item->hadapan;
                $old->tertunggak = $item->tertunggak;
                $old->sort_no = $item->sort_no;
                $old->is_custom = $item->is_custom;
                $old->save();                                   
            }
            
            $response = FinanceStaff::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }

    public function deleteFinanceAdmin($finance_file_id) {
        $response = '';
        try {
            $items = FinanceAdmin::where('finance_file_id',$finance_file_id)->get();
            FinanceAdminOld::where('finance_file_id',$finance_file_id)->delete();
            foreach($items as $item) {
                $old = FinanceAdminOld::firstOrNew(['finance_file_id' => $finance_file_id,
                                                        'name' => $item->name]);
                     
                $old->tunggakan = $item->tunggakan;
                $old->semasa = $item->semasa;
                $old->hadapan = $item->hadapan;
                $old->tertunggak = $item->tertunggak;
                $old->sort_no = $item->sort_no;
                $old->is_custom = $item->is_custom;
                $old->save();                                   
            }
            
            $response = FinanceAdmin::where('finance_file_id',$finance_file_id)->delete();

        } catch (Exception $e) {
            throw($e);
        }
        return $response;
    }
}