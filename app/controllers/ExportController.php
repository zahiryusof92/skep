<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Job\GetExportData;

class ExportController extends BaseController
{

    public function exportCouncilFacility()
    {
        $config_facilities = Config::get('constant.module.cob.facility');
        $facilities = Company::join('files', 'company.id', '=', 'files.company_id')
            ->join('facility', 'files.id', '=', 'facility.file_id')
            ->select(['files.file_no', 'facility.*'])
            ->where('company.short_name', '=', "MPAJ")
            ->where('files.is_active', '!=', "2")
            ->where('files.is_deleted', '=', "0")
            ->get();

        $data = [];
        foreach ($facilities as $facility) {
            foreach ($config_facilities as $key => $val) {

                if ($facility->$key == true) {
                    if (empty($data[$key])) {
                        $data[$key]['title'] = $val['title'];
                        $data[$key]['total_facility'] = intval($facility[$key . '_unit']);
                        $data[$key]['total_files'] = 0;
                    } else {
                        $data[$key]['total_facility'] += intval($facility[$key . '_unit']);
                        $data[$key]['total_files'] += 1;
                    }
                }
            }
        }

        return Excel::create('CouncilFacility', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download();
    }

    public function exportCouncilFacilityByStrata()
    {
        $config_facilities = Config::get('constant.module.cob.facility');
        $facilities = Company::join('files', 'company.id', '=', 'files.company_id')
            ->join('facility', 'files.id', '=', 'facility.file_id')
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->select(['files.file_no', 'facility.*', 'strata.name'])
            ->where('company.short_name', '=', "MPAJ")
            ->where('files.is_active', '!=', "2")
            ->where('files.is_deleted', '=', "0")
            ->get();

        $data = [];
        $i = 0;
        foreach ($facilities as $key => $facility) {
            $new_data[$i]['file_no'] = $facility->file_no;
            $new_data[$i]['strata_name'] = $facility->name;
            $new_data[$i]['total_facility'] = 0;

            foreach ($config_facilities as $key1 => $val) {
                $new_data[$i]['total_facility'] += $facility[$key1 . '_unit'];
                $new_data[$i][$val['title']] = (empty($facility[$key1 . '_unit'])) ? 0 : $facility[$key1 . '_unit'];
            }
            if ($new_data[$i]['total_facility'] > 0) {
                $data = array_merge($data, $new_data);
            }
        }

        return Excel::create('CouncilFacilityByStrata', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {

                $sheet->fromArray($data);
            });
        })->download();
    }

    public function reporting()
    {
        $files = Files::with([
            'company', 'strata', 'managementDeveloper', 'managementJMB', 'managementMC', 'managementAgent',
            'managementOthers', 'ajk_details'
        ])
            // ->whereIn('files.id',[8,19])
            // ->whereHas('company', function($query) {
            //     return $query->where('short_name','MPS');
            // })
            ->orderBy('company_id', 'asc')
            ->get();

        $types = ['Developer', 'JMB', 'MC', 'Agent', 'Others'];
        $data = [];
        $delay = 0;
        $incrementDelay = 2;
        foreach ($files as $key => $file) {
            // $queue = new GetExportData;
            // $queue_data = Queue::later(Carbon::now()->addSeconds($delay), GetExportData::class, array('item' => $file));
            //         $delay += $incrementDelay;

            // array_merge($queue_data, $data);
            if (empty($file->strata->name) == false) {
                $data[$key]['cob'] = $file->company->short_name;
                $data[$key]['file_no'] = $file->file_no;
                $data[$key]['file_name'] = $file->strata->name;

                foreach ($types as $key1 => $type) {
                    $data[$key]['management'][$key1]['type'] = $type;
                    $type = 'management' . $type;
                    if ($type == 'managementAgent') {
                        if (empty($file->$type) == false) {
                            $management_name = $file->$type->agent;
                        } else {
                            $management_name = '';
                        }
                    } else {
                        if (empty($file->$type) == false) {
                            $management_name = $file->$type->name;
                        } else {
                            $management_name = '';
                        }
                    }
                    $data[$key]['management'][$key1]['name'] = $management_name;
                    $data[$key]['management'][$key1]['address'] = (empty($file->$type) == false) ? $file->$type->address1 : '';
                    $data[$key]['management'][$key1]['email'] = (empty($file->$type) == false) ? $file->$type->email : '';
                    $data[$key]['management'][$key1]['phone_no'] = (empty($file->$type) == false) ? $file->$type->phone_no : '';
                }
                if (count($file->ajk_details) > 0) {
                    foreach ($file->ajk_details as $key2 => $ajk) {
                        $data[$key]['ajk'][$key2]['name'] = $ajk->name;
                        $data[$key]['ajk'][$key2]['designation'] = $ajk->designations->description;
                        $data[$key]['ajk'][$key2]['phone_no'] = $ajk->phone_no;
                        $data[$key]['ajk'][$key2]['start_year'] = $ajk->start_year;
                        $data[$key]['ajk'][$key2]['end_year'] = $ajk->end_year;
                    }
                }
            }
        }

        // Queue::later(Carbon::now()->addSeconds($delay), GetExportData::class, array('items' => $data));
        // return \Response::json(array('data'=> $data));
        // return \View::make('exports.reporting',array('datas' => $data));
        return Excel::create('reporting', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->loadView('exports.reporting', array('datas' => $data));
            });
        })->download();
    }

    public function JMBMCSignByCouncil()
    {
        $items = DB::table('users')
            ->join('audit_trail', 'audit_trail.audit_by', '=', 'users.id')
            ->join('company', 'users.company_id', '=', 'company.id')
            ->join('files', 'users.file_id', '=', 'files.id')
            ->join('house_scheme', 'house_scheme.file_id', '=', 'files.id')
            ->where('audit_trail.remarks', 'like', '%signed%')
            ->selectRaw('users.username, users.full_name, files.file_no, house_scheme.name as house_scheme, 
                                 files.id as file_id, company.short_name')
            ->where('users.is_deleted', 0)
            ->whereIn('users.role', [3, 24])
            ->whereIn('company.short_name', ['MBPJ'])
            ->groupBy(['users.username'])
            ->get();
        $data = [];
        $i = 0;
        foreach ($items as $key => $item) {
            $is_update = false;
            $finance_file = DB::table('finance_file')
                ->join('finance_file_summary', 'finance_file_summary.finance_file_id', '=', 'finance_file.id')
                ->selectRaw('finance_file.created_at, finance_file_summary.created_at as summary_created')
                ->where('finance_file.is_deleted', 0)
                ->where('finance_file.file_id', $item->file_id)
                ->where('finance_file_summary.summary_key', 'bill_elektrik')
                ->get();

            foreach ($finance_file as $finance) {
                if ($finance->summary_created > $finance->created_at) {
                    $is_update = true;
                }
            }
            if ($is_update) {
                $new_data[$i]['username'] = $item->username;
                $new_data[$i]['full name'] = $item->full_name;
                $new_data[$i]['file no'] = (is_array($item->file_no)) ? implode(',', $item->file_no) : $item->file_no;
                $new_data[$i]['scheme name'] = (empty($item->house_scheme)) ? '-' : $item->house_scheme;

                $data = array_merge($data, $new_data);
            }
        }
        return Excel::create('jmbmc_logon_report', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {

                $sheet->fromArray($data);
            });
        })->download();
    }

    public function ownerByCouncil()
    {
        $items = DB::table('files')
            ->join('buyer', 'buyer.file_id', '=', 'files.id')
            ->join('house_scheme', 'house_scheme.file_id', '=', 'files.id')
            ->join('company', 'files.company_id', '=', 'company.id')
            ->selectRaw('count(buyer.id) as total, files.file_no, company.short_name, house_scheme.name,
                            house_scheme.address1, house_scheme.address2, house_scheme.address3, house_scheme.address4,
                            house_scheme.poscode, house_scheme.city, house_scheme.state')
            ->where('files.is_deleted', 0)
            ->where('buyer.is_deleted', 0)
            ->groupBy(['house_scheme.name'])
            ->get();

        $data = [];
        $i = 0;
        $total = 0;
        foreach ($items as $key => $item) {
            $city = City::where('id', $item->city)->first();
            $state = State::where('id', $item->state)->first();
            $new_data[$i]['no of owners'] = $item->total;
            $new_data[$i]['file no'] = $item->file_no;
            $new_data[$i]['scheme name'] = $item->name;
            $new_data[$i]['council'] = $item->short_name;
            $new_data[$i]['address'] = $item->address1 . ', ' . $item->address2 . ', ' . $item->address3
                . ', ' . $item->address4 . ', ' . $item->poscode . ', ';
            $new_data[$i]['address'] .= (empty($city)) ? '' : $city->description;
            $new_data[$i]['address'] .= (empty($state)) ? '' : (' ' . $state->name);

            $data = array_merge($data, $new_data);
            $total += $item->total;
        }
        $new_data[$i]['no of owners'] = $total;
        $new_data[$i]['file no'] = '';
        $new_data[$i]['scheme name'] = '';
        $new_data[$i]['council'] = '';
        $new_data[$i]['address'] = '';

        $data = array_merge($data, $new_data);

        return Excel::create('owner_by_council', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download();
    }

    public function JMBActivity()
    {
        $items = DB::table('users')
            ->join('audit_trail', 'users.id', '=', 'audit_trail.audit_by')
            ->join('company', 'users.company_id', '=', 'company.id')
            ->selectRaw('users.email, users.full_name, audit_trail.remarks as activity, audit_trail.created_at,
                                company.short_name')
            ->where('users.role', 3)
            ->where('users.is_deleted', 0)
            ->whereBetween('audit_trail.created_at', ["2021-08-06 00:00:00", "2021-09-03 23:59:59"])
            ->orderBy('users.full_name', 'asc')
            ->get();

        $data = [];
        $i = 0;
        foreach ($items as $key => $item) {
            $new_data[$i]['email'] = $item->email;
            $new_data[$i]['full name'] = $item->full_name;
            $new_data[$i]['activity'] = $item->activity;
            $new_data[$i]['council'] = $item->short_name;
            $new_data[$i]['created at'] = $item->created_at;

            $data = array_merge($data, $new_data);
        }

        $data = array_merge($data, $new_data);

        return Excel::create('jmb_activity', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download();
    }

    public function strataName()
    {
        $items = Strata::join('files', 'strata.file_id', '=', 'files.id')
            ->join('company', 'files.company_id', '=', 'company.id')
            ->where('company.short_name', 'MPS')
            ->select(['strata.*', 'files.file_no as file_no'])
            ->get();

        $data = [];
        $i = 0;
        foreach ($items as $key => $item) {
            $new_data[$i]['file'] = $item->file_no;
            $new_data[$i]['name'] = $item->name;

            $data = array_merge($data, $new_data);
        }

        return Excel::create('strata-data-' . date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download();
    }

    public function tunggakanFinance()
    {
        $items = Finance::with(['financeReport', 'financeIncome', 'file.strata', 'company'])
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->whereBetween('month', [3, 5])
            ->where('year', 2022)
            ->get();

        $data = [];
        $i = 0;
        foreach ($items as $key => $item) {
            $new_data[$i]['cob'] = $item->company->short_name;
            $new_data[$i]['strata'] = $item->file->strata->name;
            $new_data[$i]['file'] = $item->file->file_no;
            $new_data[$i]['year'] = $item->year;
            $new_data[$i]['month'] = $item->month;
            $new_data[$i]['MF Tunggakan'] = $item->financeReport()->where('type', 'mf')->first() ? $item->financeReport()->where('type', 'mf')->first()->tunggakan_belum_dikutip : 0;
            $new_data[$i]['SF Tunggakan'] = $item->financeReport()->where('type', 'sf')->first() ? $item->financeReport()->where('type', 'sf')->first()->tunggakan_belum_dikutip : 0;
            $new_data[$i]['Income MF Tunggakan'] = $item->financeIncome()->where('name', 'maintenance fee')->first() ? $item->financeIncome()->where('name', 'maintenance fee')->first()->tunggakan : 0;
            $new_data[$i]['Income SF Tunggakan'] = $item->financeIncome()->where('name', 'sinking fund')->first() ? $item->financeIncome()->where('name', 'sinking fund')->first()->tunggakan : 0;

            $data = array_merge($data, $new_data);
        }

        return Excel::create('tunggakan finance-data-' . date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download();
    }

    public function fileDetails()
    {
        $commercial_files = Commercial::where('unit_no', '>=', 500)
            ->get();
        $residential_files = Residential::where('unit_no', '>=', 500)
            ->get();
        $file_ids = array_merge(array_pluck($commercial_files, 'file_id'), array_pluck($residential_files, 'file_id'));

        $files = Files::with([
            'company', 'strata', 'managementDeveloper', 'managementJMB', 'managementMC', 'managementAgent',
            'managementOthers', 'ajk_details', 'resident', 'commercial'
        ])
            ->whereIn('id', $file_ids)
            ->where('files.is_deleted', false)
            ->orderBy('company_id', 'asc')
            ->get();

        $types = ['Developer', 'JMB', 'MC', 'Agent', 'Others'];
        $data = [];
        foreach ($files as $key => $file) {
            if (empty($file->strata->name) == false) {
                $data[$key]['cob'] = $file->company->short_name;
                $data[$key]['file_no'] = $file->file_no;
                $data[$key]['strata'] = $file->strata->name;
                $data[$key]['residential_unit_no'] = $file->resident ? $file->resident->unit_no : '-';
                $data[$key]['commercial_unit_no'] = $file->commercial ? $file->commercial->unit_no : '-';

                foreach ($types as $key1 => $type) {
                    $data[$key]['management'][$key1]['type'] = $type;
                    $type = 'management' . $type;
                    if ($type == 'managementAgent') {
                        if (empty($file->$type) == false) {
                            $management_name = $file->$type->agent;
                        } else {
                            $management_name = '';
                        }
                    } else {
                        if (empty($file->$type) == false) {
                            $management_name = $file->$type->name;
                        } else {
                            $management_name = '';
                        }
                    }
                    $data[$key]['management'][$key1]['name'] = $management_name;
                    $data[$key]['management'][$key1]['address'] = (empty($file->$type) == false) ? $file->$type->address1 : '';
                    $data[$key]['management'][$key1]['email'] = (empty($file->$type) == false) ? $file->$type->email : '';
                    $data[$key]['management'][$key1]['phone_no'] = (empty($file->$type) == false) ? $file->$type->phone_no : '';
                }
                if (count($file->ajk_details) > 0) {
                    foreach ($file->ajk_details as $key2 => $ajk) {
                        $data[$key]['ajk'][$key2]['name'] = $ajk->name;
                        $data[$key]['ajk'][$key2]['designation'] = $ajk->designations->description;
                        $data[$key]['ajk'][$key2]['phone_no'] = $ajk->phone_no;
                        $data[$key]['ajk'][$key2]['start_year'] = $ajk->start_year;
                        $data[$key]['ajk'][$key2]['end_year'] = $ajk->end_year;
                    }
                }
            }
        }
        // dd($data);
        // $file_ids = Files::with(['managementDeveloper', 'managementJMB', 'managementMC', 'managementAgent', 'managementOthers', 'ajk_details', 'buyer', 'company', 'strata'])
        //                 ->join('strata', 'strata.file_id', '=', 'files.id')
        //                 ->leftJoin('residential_block', 'residential_block.file_id', '=', 'files.id')
        //                 ->leftJoin('commercial_block', 'commercial_block.file_id', '=', 'files.id')
        //                 ->where('residential_block.unit_no', '>=', 500)
        //                 ->orWhere('commercial_block.unit_no', '>=', 500)
        //                 ->get();
        // $files = Files::with(['managementDeveloper', 'managementJMB', 'managementMC', 'managementAgent', 'managementOthers', 'ajk_details', 'buyer', 'company', 'strata'])
        //                 ->whereIn('id', array_pluck($file_ids, 'id'))
        //                 ->get();

        //             // dd($files);
        // $data = [];
        // $i = 0;
        // $total = 0;
        // foreach($files as $key => $file) {
        //     $city = City::where('id', $file->city)->first();                   
        //     $state = State::where('id', $file->state)->first();                   
        //     $new_data[$key]['no'] = ($key + 1);
        //     $new_data[$key]['file no'] = $file->file_no;
        //     $new_data[$key]['strata'] = $file->strata->name;
        //     $new_data[$key]['council'] = $file->company->short_name;
        //     $new_data[$key]['address'] = $file->address1 .', '. $file->address2 .', '. $file->address3
        //                                 .', '. $file->address4 .', '. $file->poscode .', ';
        //     $new_data[$key]['address'] .= (empty($city))? '' : $city->description;
        //     $new_data[$key]['address'] .= (empty($state))? '' : (' '. $state->name);

        //     $new_data[$key]['address'] .= (empty($state))? '' : (' '. $state->name);

        //     if(!empty($file->managementJMB)) {
        //         $new_data[$key]['management']['type']['jmb']['name'] = $file->managementJMB->name;
        //         $new_data[$key]['management']['type']['jmb']['phone_no'] = $file->managementJMB->phone_no;    
        //     }
        //     if(!empty($file->managementMC)) {
        //         $new_data[$key]['management']['type']['mc']['name'] = $file->managementMC->name;
        //         $new_data[$key]['management']['type']['mc']['phone_no'] = $file->managementMC->phone_no;
        //     }
        //     if(!empty($file->ajk_details)) {
        //         $new_data[$key]['management']['type']['ajk'] = [];
        //         foreach($file->ajk_details as $key_ajk => $ajk) {
        //             $new_data[$key]['management']['type']['ajk'][$key_ajk]['name'] = $ajk->name;
        //             $new_data[$key]['management']['type']['ajk'][$key_ajk]['email'] = $ajk->email;
        //             $new_data[$key]['management']['type']['ajk'][$key_ajk]['designation'] = $ajk->designation;
        //             $new_data[$key]['management']['type']['ajk'][$key_ajk]['phone_no'] = $ajk->phone_no;
        //         }
        //     }


        //     $data = array_merge($data, $new_data);
        // }
        return Excel::create('file-details-' . date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->loadView('exports.file-detail', array('datas' => $data));
            });
        })->download();
    }

    public function exportCOBFile()
    {
        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }

        $viewData = array(
            'title' => trans('app.forms.export_cob_files'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'export_file',
            'image' => '',
            'cob' => $cob,
        );

        return View::make('exports.files', $viewData);
    }

    public function submitExportCOBFile()
    {
        $data = Input::all();

        $rules = [];
        $message = [];

        $rules = array(
            'company' => 'required',
        );

        $messages = array(
            'company.required' => 'The COB field is required.'
        );

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $data = [];

            $company_id = Input::get('company');

            $council = Company::with('files')->find($company_id);
            if ($council) {

                if ($council->files->count() > 0) {
                    $count = 1;

                    foreach ($council->files as $file) {
                        $houseScheme = HouseScheme::where('file_id', $file->id)->first();
                        if ($houseScheme) {
                            $developer = Developer::find($houseScheme->developer);
                            if ($developer) {
                                $developer_city = City::find($developer->city);
                                $developer_state = State::find($developer->state);
                                $developer_country = Country::find($developer->country);
                            }
                        } else {
                            $developer = '';
                        }

                        $strata = Strata::where('file_id', $file->id)->first();
                        if ($strata) {
                            $strata_parliament = Parliment::find($strata->parliament);
                            $strata_dun = Dun::find($strata->dun);
                            $strata_park = Park::find($strata->park);
                            $strata_city = City::find($strata->city);
                            $strata_state = State::find($strata->state);
                            $strata_country = Country::find($strata->country);
                            $strata_town = City::find($strata->town);
                            $strata_area = Area::find($strata->area);
                            $strata_land_area_UOM = UnitMeasure::find($strata->land_area_unit);
                            $strata_land_title = LandTitle::find($strata->land_title);
                            $strata_category = Category::find($strata->category);
                            $strata_perimeter = Perimeter::find($strata->perimeter);

                            $strata_residential = Residential::where('strata_id', $strata->id)->first();
                            if ($strata->is_residential && $strata_residential) {
                                $strata_residential_mf_uom = UnitOption::find($strata_residential->maintenance_fee_option);
                                $strata_residential_sf_uom = UnitOption::find($strata_residential->sinking_fund_option);
                            } else {
                                $strata_residential_mf_uom = '';
                                $strata_residential_sf_uom = '';
                            }

                            $strata_commercial = Commercial::where('strata_id', $strata->id)->first();
                            if ($strata->is_commercial && $strata_commercial) {
                                $strata_commercial_mf_uom = UnitOption::find($strata_commercial->maintenance_fee_option);
                                $strata_commercial_sf_uom = UnitOption::find($strata_commercial->sinking_fund_option);
                            } else {
                                $strata_commercial_mf_uom = '';
                                $strata_commercial_sf_uom = '';
                            }
                        } else {
                            $strata_residential = '';
                            $strata_commercial = '';
                        }

                        $management = Management::where('file_id', $file->id)->first();
                        if ($management) {
                            $management_jmb = ManagementJMB::where('file_id', $file->id)->first();
                            if ($management->is_jmb && $management_jmb) {
                                $management_jmb_city = City::find($management_jmb->city);
                                $management_jmb_state = State::find($management_jmb->state);
                                $management_jmb_country = Country::find($management_jmb->country);
                            } else {
                                $management_jmb_city = '';
                                $management_jmb_state = '';
                                $management_jmb_country = '';
                            }

                            $management_mc = ManagementMC::where('file_id', $file->id)->first();
                            if ($management->is_mc && $management_mc) {
                                $management_mc_city = City::find($management_mc->city);
                                $management_mc_state = State::find($management_mc->state);
                                $management_mc_country = Country::find($management_mc->country);
                            } else {
                                $management_mc_city = '';
                                $management_mc_state = '';
                                $management_mc_country = '';
                            }

                            $management_agent = ManagementAgent::where('file_id', $file->id)->first();
                            if ($management->is_agent && $management_agent) {
                                $management_agent_city = City::find($management_agent->city);
                                $management_agent_state = State::find($management_agent->state);
                                $management_agent_country = Country::find($management_agent->country);
                            } else {
                                $management_agent_city = '';
                                $management_agent_state = '';
                                $management_agent_country = '';
                            }

                            $management_others = ManagementOthers::where('file_id', $file->id)->first();
                            if ($management->is_others && $management_others) {
                                $management_others_city = City::find($management_others->city);
                                $management_others_state = State::find($management_others->state);
                                $management_others_country = Country::find($management_others->country);
                            } else {
                                $management_others_city = '';
                                $management_others_state = '';
                                $management_others_country = '';
                            }
                        }

                        $monitoring = Monitoring::where('file_id', $file->id)->first();
                        $others_details = OtherDetails::where('file_id', $file->id)->first();

                        $data[] = array(
                            'Bil' => $count++,
                            'File No.' => $file->file_no,
                            'Cob File ID' => '',
                            'Year' => (!empty($file->year) ? $file->year : ''),

                            /**
                             * Housing Scheme
                             */
                            'Name' => ($houseScheme ? $houseScheme->name : ''),
                            'Housing Scheme Name' => ($houseScheme ? $houseScheme->name : ''),
                            'Developer' => ($developer ? $developer->name : ''),
                            'Developer Address 1' => ($developer ? $developer->address1 : ''),
                            'Developer Address 2' => ($developer ? $developer->address2 : ''),
                            'Developer Address 3' => ($developer ? $developer->address3 : ''),
                            'Developer Address 4' => ($developer ? $developer->address4 : ''),
                            'Developer Postcode' => ($developer ? $developer->poscode : ''),
                            'Developer City' => ($developer ? ($developer_city ? $developer_city->description : '') : ''),
                            'Developer State' => ($developer ? ($developer_state ? $developer_state->name : '') : ''),
                            'Developer Country' => ($developer ? ($developer_country ? $developer_country->name : '') : ''),
                            'Developer Office No.' => ($developer ? $developer->phone_no : ''),
                            'Developer Fax No.' => ($developer ? $developer->fax_no : ''),
                            'Developer Status' => ($developer ? ($developer->is_active ? 'Active' : '') : ''),

                            /**
                             * Strata
                             */
                            'Strata Title' => ($strata ? ($strata->title ? 'Y' : '') : ''),
                            'Strata' => ($strata ? $strata->name : ''),
                            'Strata Parliament' => ($strata ? ($strata_parliament ? $strata_parliament->description : '') : ''),
                            'Strata DUN' => ($strata ? ($strata_dun ? $strata_dun->description : '') : ''),
                            'Strata Park' => ($strata ? ($strata_park ? $strata_park->description : '') : ''),
                            'Strata Address 1' => ($strata ? $strata->address1 : ''),
                            'Strata Address 2' => ($strata ? $strata->address2 : ''),
                            'Strata Address 3' => ($strata ? $strata->address3 : ''),
                            'Strata Address 4' => ($strata ? $strata->address4 : ''),
                            'Strata Postcode' => ($strata ? $strata->poscode : ''),
                            'Strata City' => ($strata_city ? $strata_city->description : ''),
                            'Strata State' => ($strata_state ? $strata_state->name : ''),
                            'Strata Country' => ($strata_country ? $strata_country->name : ''),
                            'Strata Total Block' => ($strata ? $strata->block_no : ''),
                            'Strata Floor' => ($strata ? $strata->total_floor : ''),
                            'Strata Year' => ($strata ? $strata->year : ''),
                            'Strata Ownership No' => ($strata ? $strata->ownership_no : ''),
                            'Strata District' => ($strata_town ? $strata_town->description : ''),
                            'Strata Area' => ($strata_area ? $strata_area->description : ''),
                            'Strata Total Land Area' => ($strata ? $strata->land_area : ''),
                            'Strata Total Land Area UOM' => ($strata_land_area_UOM ? $strata_land_area_UOM->description : ''),
                            'Strata Lot No.' => ($strata ? $strata->lot_no : ''),
                            'Strata Vacant Possession Date' => ($strata ? ($strata->date > 0 ? $strata->date : '') : ''),
                            'Strata Date CCC' => ($strata ? ($strata->ccc_date > 0 ? $strata->ccc_date : '') : ''),
                            'Strata CCC No.' => ($strata ? $strata->ccc_no : ''),
                            'Strata Land Title' => ($strata_land_title ? $strata_land_title->description : ''),
                            'Strata Category' => ($strata_category ? $strata_category->description : ''),
                            'Strata Perimeter' => ($strata_perimeter ? $strata_perimeter->description_en : ''),
                            'Strata Total Share Unit' => ($strata ? $strata->total_share_unit : ''),
                            'Strata Residential' => ($strata ? ($strata->is_residential ? 'Yes' : '') : ''),
                            'Strata Residential Total Unit' => ($strata_residential ? $strata_residential->unit_no : ''),
                            'Strata Residential Maintenance Fee' => ($strata_residential ? $strata_residential->maintenance_fee : ''),
                            'Strata Residential Maintenance Fee UOM' => ($strata_residential ? ($strata_residential_mf_uom ? $strata_residential_mf_uom->description : '') : ''),
                            'Strata Residential Singking Fund' => ($strata_residential ? $strata_residential->sinking_fund : ''),
                            'Strata Residential Singking Fund UOM' => ($strata_residential ? ($strata_residential_sf_uom ? $strata_residential_sf_uom->description : '') : ''),
                            'Strata Commercial' => ($strata ? ($strata->is_commercial ? 'Yes' : '') : ''),
                            'Strata Commercial Total Unit' => ($strata_commercial ? $strata_commercial->unit_no : ''),
                            'Strata Commercial Maintenance Fee' => ($strata_commercial ? $strata_commercial->maintenance_fee : ''),
                            'Strata Commercial Maintenance Fee UOM' => ($strata_commercial ? ($strata_commercial_mf_uom ? $strata_commercial_mf_uom->description : '') : ''),
                            'Strata Commercial Singking Fund' => ($strata_commercial ? $strata_commercial->sinking_fund : ''),
                            'Strata Commercial Singking Fund UOM' => ($strata_commercial ? ($strata_commercial_sf_uom ? $strata_commercial_sf_uom->description : '') : ''),
                            'Strata Others' => '',

                            /**
                             * Management JMB
                             */
                            'Management JMB' => ($management_jmb ? 'Yes' : ''),
                            'Management JMB Date Formed' => ($management_jmb ? ($management_jmb->date_formed > 0 ? $management_jmb->date_formed : '') : ''),
                            'Management JMB Certificate Series No' => ($management_jmb ? $management_jmb->certificate_no : ''),
                            'Management JMB Name' => ($management_jmb ? $management_jmb->name : ''),
                            'Management JMB Address 1'  => ($management_jmb ? $management_jmb->address1 : ''),
                            'Management JMB Address 2'  => ($management_jmb ? $management_jmb->address2 : ''),
                            'Management JMB Address 3'  => ($management_jmb ? $management_jmb->address3 : ''),
                            'Management JMB Address 4'  => ($management_jmb ? $management_jmb->address4 : ''),
                            'Management JMB Postcode'  => ($management_jmb ? $management_jmb->poscode : ''),
                            'Management JMB City'  => ($management_jmb ? ($management_jmb_city ? $management_jmb_city->description : '') : ''),
                            'Management JMB State'  => ($management_jmb ? ($management_jmb_state ? $management_jmb_state->name : '') : ''),
                            'Management JMB Country'  => ($management_jmb ? ($management_jmb_country ? $management_jmb_country->name : '') : ''),
                            'Management JMB Office No.' => ($management_jmb ? $management_jmb->phone_no : ''),
                            'Management JMB Fax No.' => ($management_jmb ? $management_jmb->fax_no : ''),
                            'Management JMB Email' => ($management_jmb ? $management_jmb->email : ''),

                            /**
                             * Management MC
                             */
                            'Management MC' => ($management_mc ? 'Yes' : ''),
                            'Management MC Date Formed'  => ($management_mc ? ($management_mc->date_formed > 0 ? $management_mc->date_formed : '') : ''),
                            'Management MC First AGM Date'  => ($management_mc ? ($management_mc->date_formed > 0 ? $management_mc->date_formed : '') : ''),
                            'Management MC Name' => ($management_mc ? $management_mc->name : ''),
                            'Management MC Address 1' => ($management_mc ? $management_mc->address1 : ''),
                            'Management MC Address 2' => ($management_mc ? $management_mc->address2 : ''),
                            'Management MC Address 3' => ($management_mc ? $management_mc->address3 : ''),
                            'Management MC Address 4' => ($management_mc ? $management_mc->address4 : ''),
                            'Management MC Postcode' => ($management_mc ? $management_mc->poscode : ''),
                            'Management MC City'  => ($management_mc ? ($management_mc_city ? $management_mc_city->description : '') : ''),
                            'Management MC State'  => ($management_mc ? ($management_mc_state ? $management_mc_state->name : '') : ''),
                            'Management MC Country'  => ($management_mc ? ($management_mc_country ? $management_mc_country->name : '') : ''),
                            'Management MC Office No.' => ($management_mc ? $management_mc->phone_no : ''),
                            'Management MC Fax No.' => ($management_mc ? $management_mc->fax_no : ''),
                            'Management MC Email' => ($management_mc ? $management_mc->email : ''),

                            /**
                             * Management Agent
                             */
                            'Management Agent' => ($management_agent ? 'Yes' : ''),
                            'Management Agent Selected By' => ($management_agent ? $management_agent->selected_by : ''),
                            'Management Agent Name' => ($management_agent ? $management_agent->name : ''),
                            'Management Agent Address 1' => ($management_agent ? $management_agent->address1 : ''),
                            'Management Agent Address 2' => ($management_agent ? $management_agent->address2 : ''),
                            'Management Agent Address 3' => ($management_agent ? $management_agent->address3 : ''),
                            'Management Agent Address 4' => ($management_agent ? $management_agent->address4 : ''),
                            'Management Agent Postcode' => ($management_agent ? $management_agent->poscode : ''),
                            'Management Agent City'  => ($management_agent ? ($management_agent_city ? $management_agent_city->description : '') : ''),
                            'Management Agent State'  => ($management_agent ? ($management_agent_state ? $management_agent_state->name : '') : ''),
                            'Management Agent Country'  => ($management_agent ? ($management_agent_country ? $management_agent_country->name : '') : ''),
                            'Management Agent Office No.' => ($management_agent ? $management_agent->phone_no : ''),
                            'Management Agent Fax No.' => ($management_agent ? $management_agent->fax_no : ''),
                            'Management Agent Email' => ($management_agent ? $management_agent->email : ''),

                            /**
                             * Management Other
                             */
                            'Management Other' => ($management_others ? 'Yes' : ''),
                            'Management Other Name' => ($management_others ? $management_others->name : ''),
                            'Management Other Address 1' => ($management_others ? $management_others->address1 : ''),
                            'Management Other Address 2' => ($management_others ? $management_others->address2 : ''),
                            'Management Other Address 3' => ($management_others ? $management_others->address3 : ''),
                            'Management Other Address 4' => ($management_others ? $management_others->address4 : ''),
                            'Management Other Postcode' => ($management_others ? $management_others->poscode : ''),
                            'Management Other City'  => ($management_others ? ($management_others_city ? $management_others_city->description : '') : ''),
                            'Management Other State'  => ($management_others ? ($management_others_state ? $management_others_state->name : '') : ''),
                            'Management Other Country'  => ($management_others ? ($management_others_country ? $management_others_country->name : '') : ''),
                            'Management Other Office No.' => ($management_others ? $management_others->phone_no : ''),
                            'Management Other Fax No.' => ($management_others ? $management_others->fax_no : ''),
                            'Management Other Email' => ($management_others ? $management_others->email : ''),

                            /**
                             * No Management
                             */
                            'No Management' => ($management ? ($management->no_management ? 'Yes' : '') : ''),
                            'Management Date Start' => ($management ? ($management->start > 0 ? $management->start : '') : ''),
                            'Management Date End' => ($management ? ($management->end > 0 ? $management->end : '') : ''),

                            /**
                             * Monitoring
                             */
                            'Monitoring Precalculate Plan' => ($monitoring ? ($monitoring->pre_calculate ? 'Yes' : '') : ''),
                            'Monitoring Buyer Registration' => ($monitoring ? ($monitoring->buyer_registration ? 'Yes' : '') : ''),
                            'Monitoring Certificate No' => ($monitoring ? $monitoring->certificate_no : ''),
                            'Monitoring Financial Report Start Month' => '',

                            /**
                             * Others
                             */
                            'Others Name' => ($others_details ? $others_details->name : ''),
                            'Others Latitude' => ($others_details ? ($others_details->latitude > 0 ? $others_details->latitude : '') : ''),
                            'Others Longitude' => ($others_details ? ($others_details->longitude > 0 ? $others_details->longitude : '') : ''),

                            'Status' => ($file ? ($file->is_active ? 'Active' : '') : ''),
                            'Certificate No' => '',
                            'New File No.' => '',
                        );
                    }
                }

                // return '<pre>' . print_r($data, true) . '</pre>';

                return Excel::create(strtoupper($council->short_name) . '_Files_' . date('YmdHis'), function ($excel) use ($data) {
                    $excel->sheet('Sheet1', function ($sheet) use ($data) {
                        $sheet->fromArray($data);
                    });
                })->export();
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }
}
