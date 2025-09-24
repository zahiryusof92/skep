<?php

use Carbon\Carbon;
use Helper\Helper;
use Helper\KCurl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Job\ImportFile;
use Maatwebsite\Excel\Facades\Excel;
use Services\NotificationService;

class ImportController extends BaseController
{

    public function showView($name)
    {
        if (View::exists($name)) {
            return View::make($name);
        } else {
            $viewData = array(
                'title' => trans('app.errors.page_not_found'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'image' => ""
            );
            return View::make('404_en', $viewData);
        }
    }

    public function importBuyer()
    {
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['buyer']['import'];
            $data['import_file'] = curl_file_create($_FILES['import_file']['tmp_name'], $_FILES['import_file']['type'], $_FILES['import_file']['name']);
            $data['file_id'] = Helper::decode(Input::get('file_id'));

            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         $data, true)));

            // if(empty($response->status) == false && $response->status == 200) {

            $excel = Input::file('import_file');
            $file_id = Helper::decode(Input::get('file_id'));

            if ($excel && $file_id) {

                $file = Files::find($file_id);

                if ($file) {

                    $path = $excel->getRealPath();
                    $data = Excel::load($path, function ($reader) {
                    })->get();

                    if (!empty($data) && $data->count()) {
                        foreach ($data->toArray() as $row) {
                            if (!empty($row)) {
                                // 1. File Number
                                $file_no = '';
                                if (isset($row['1']) && !empty($row['1'])) {
                                    $file_no = trim($row['1']);
                                }

                                if (!empty($file_no)) {
                                    $exist_file = Files::where('file_no', $file_no)->where('id', $file->id)->first();
                                    if ($exist_file) {
                                        // 2. NO.UNIT
                                        $unit_no = '';
                                        if (isset($row['2']) && !empty($row['2'])) {
                                            $unit_no = trim($row['2']);
                                        }

                                        if (!empty($unit_no)) {
                                            $check_buyer = Buyer::where('file_id', $file->id)->where('unit_no', $unit_no)->where('is_deleted', 0)->first();
                                            if (!$check_buyer) {
                                                $race = '';
                                                // 15. BANGSA
                                                if (isset($row['15']) && !empty($row['15'])) {
                                                    $race_raw = trim($row['15']);

                                                    if (!empty($race_raw)) {
                                                        $race_query = Race::where('name_en', ucwords(strtolower($race_raw)))->orWhere('name_my', ucwords(strtolower($race_raw)))->where('is_deleted', 0)->first();
                                                        if ($race_query) {
                                                            $race = $race_query->id;
                                                        } else {
                                                            $race_query = new Race();
                                                            $race_query->name_en = ucwords(strtolower($race_raw));
                                                            $race_query->name_my = ucwords(strtolower($race_raw));
                                                            $race_query->is_active = 1;
                                                            $race_query->save();

                                                            $race = $race_query->id;
                                                        }
                                                    }
                                                }

                                                $nationality = '';
                                                // 16. KEWARGANEGARAAN
                                                if (isset($row['16']) && !empty($row['16'])) {
                                                    $nationality_raw = trim($row['16']);

                                                    if (!empty($nationality_raw)) {
                                                        $nationality_query = Nationality::where('name', ucwords(strtolower($nationality_raw)))->where('is_deleted', 0)->first();
                                                        if ($nationality_query) {
                                                            $nationality = $nationality_query->id;
                                                        } else {
                                                            $nationality_query = new Nationality();
                                                            $nationality_query->name = ucwords(strtolower($nationality_raw));
                                                            $nationality_query->is_active = 1;
                                                            $nationality_query->save();

                                                            $nationality = $nationality_query->id;
                                                        }
                                                    }
                                                }

                                                $buyer = new Buyer();
                                                $buyer->file_id = $file->id; // 1. File Number
                                                $buyer->unit_no = $unit_no; // 2. NO.UNIT
                                                $buyer->no_petak = $row['3']; // 3. NO.PETAK
                                                $buyer->no_petak_aksesori = $row['4']; // 4. NO.PETAK AKSESORI (JIKA ADA)
                                                $buyer->keluasan_lantai_petak = $row['5']; // 5. KELUASAN LANTAI PETAK (SQ.M)
                                                $buyer->keluasan_lantai_petak_aksesori = $row['6']; // 6. KELUASAN LANTAI PETAK AKSESORI (SQ.M)
                                                $buyer->unit_share = $row['7']; // 7. UNIT SHARE
                                                $buyer->jenis_kegunaan = $row['8']; // 8. JENIS KEGUNAAN
                                                $buyer->owner_name = $row['9']; // 9. NAMA PEMILIK                                        
                                                $buyer->ic_company_no = $row['10']; // 10. NO.KAD PENGENALAN
                                                $buyer->email = $row['11']; // 11. EMEL
                                                $buyer->phone_no = $row['12']; // 12. NO.TELEFON BIMBIT
                                                $buyer->address = $row['13']; // 13. ALAMAT
                                                $buyer->alamat_surat_menyurat = $row['14']; // 14. ALAMAT SURAT MENYURAT
                                                $buyer->race_id = $race; // 15. BANGSA
                                                $buyer->nationality_id = $nationality; // 16. KEWARGANEGARAAN
                                                // 17. STATUS PENGHUNIAN (PEMILIK,PENYEWA,KOSONG)
                                                $buyer->caj_penyelenggaraan = $row['18']; // 18. CAJ PENYENGGARAAN (RM)
                                                $buyer->sinking_fund = $row['19']; // 19. SINKING FUND (RM)
                                                $buyer->remarks = $row['20']; // 20. CATATAN
                                                $buyer->nama2 = $row['21']; // 21. NAMA PEMILIK 2
                                                $buyer->ic_no2 = $row['22']; // 22. NO.KAD PENGENALAN PEMILIK 2
                                                $buyer->email2 = $row['23']; // 23. EMEL PEMILIK 2
                                                $buyer->phone_no2 = $row['24']; // 24. NO.TELEFON BIMBIT PEMILIK 2
                                                $buyer->nama3 = $row['25']; // 25. NAMA PEMILIK 3
                                                $buyer->ic_no3 = $row['26']; // 26. NO.KAD PENGENALAN PEMILIK 3
                                                $buyer->email3 = $row['27']; // 27. EMEL PEMILIK 3
                                                $buyer->phone_no3 = $row['28']; // 28. NO.TELEFON BIMBIT PEMILIK 3
                                                $buyer->lawyer_name = $row['29']; // 29. NAMA PEGUAMCARA
                                                $buyer->lawyer_address = $row['30']; // 30. ALAMAT PEGUAMCARA
                                                $buyer->lawyer_fail_ref_no = $row['31']; // 31. NO RUJ FAIL PEGUAMCARA
                                                $buyer->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        # Audit Trail
                        $remarks = 'COB Buyer List (' . $file->file_no . ')' . $this->module['audit']['text']['data_imported'];
                        $this->addAudit($file->id, "COB File", $remarks);

                        print "true";
                    } else {
                        print "empty_data";
                    }
                } else {
                    print "false";
                }
            } else {
                print "empty_file";
            }
            // } else {
            //     print "false";
            // }
        } else {
            print "false";
        }
    }

    public function importTenant()
    {
        if (Request::ajax()) {
            $excel = Input::file('import_file');
            $file_id = Input::get('file_id');

            if ($excel && $file_id) {

                $file = Files::find($file_id);

                if ($file) {

                    $path = $excel->getRealPath();
                    $data = Excel::load($path, function ($reader) {
                    })->get();

                    if (!empty($data) && $data->count()) {
                        foreach ($data->toArray() as $row) {
                            if (!empty($row)) {
                                // 1. File No.
                                $file_no = '';
                                if (isset($row['1']) && !empty($row['1'])) {
                                    $file_no = trim($row['1']);
                                }

                                if (!empty($file_no)) {
                                    $exist_file = Files::where('file_no', $file_no)->where('id', $file->id)->first();
                                    if ($exist_file) {
                                        // 2. Unit No.
                                        $unit_no = '';
                                        if (isset($row['2']) && !empty($row['2'])) {
                                            $unit_no = trim($row['2']);
                                        }

                                        if (!empty($unit_no)) {
                                            $check_tenant = Tenant::where('file_id', $file->id)->where('unit_no', $unit_no)->where('is_deleted', 0)->first();
                                            if (!$check_tenant) {
                                                $race = '';
                                                if (isset($row['17']) && !empty($row['17'])) {
                                                    $race_raw = trim($row['17']);

                                                    if (!empty($race_raw)) {
                                                        $race_query = Race::where('name_en', ucwords(strtolower($race_raw)))->orWhere('name_my', ucwords(strtolower($race_raw)))->where('is_deleted', 0)->first();
                                                        if ($race_query) {
                                                            $race = $race_query->id;
                                                        } else {
                                                            $race_query = new Race();
                                                            $race_query->name_en = ucwords(strtolower($race_raw));
                                                            $race_query->name_my = ucwords(strtolower($race_raw));
                                                            $race_query->is_active = 1;
                                                            $race_query->save();

                                                            $race = $race_query->id;
                                                        }
                                                    }
                                                }

                                                $nationality = '';
                                                if (isset($row['18']) && !empty($row['18'])) {
                                                    $nationality_raw = trim($row['18']);

                                                    if (!empty($nationality_raw)) {
                                                        $nationality_query = Nationality::where('name', ucwords(strtolower($nationality_raw)))->where('is_deleted', 0)->first();
                                                        if ($nationality_query) {
                                                            $nationality = $nationality_query->id;
                                                        } else {
                                                            $nationality_query = new Nationality();
                                                            $nationality_query->name = ucwords(strtolower($nationality_raw));
                                                            $nationality_query->is_active = 1;
                                                            $nationality_query->save();

                                                            $nationality = $nationality_query->id;
                                                        }
                                                    }
                                                }

                                                $tenant = new Tenant();
                                                $tenant->file_id = $file->id;
                                                $tenant->unit_no = $unit_no;
                                                $tenant->no_petak = $row['3'];
                                                $tenant->no_petak_aksesori = $row['4'];
                                                $tenant->keluasan_lantai_petak = $row['5'];
                                                $tenant->keluasan_lantai_petak_aksesori = $row['6'];
                                                $tenant->unit_share = $row['7'];
                                                $tenant->jenis_kegunaan = $row['8'];
                                                $tenant->tenant_name = $row['9'];
                                                $tenant->ic_company_no = $row['10'];
                                                $tenant->nama2 = $row['11'];
                                                $tenant->ic_no2 = $row['12'];
                                                $tenant->address = $row['13'];
                                                $tenant->alamat_surat_menyurat = $row['14'];
                                                $tenant->phone_no = $row['15'];
                                                $tenant->email = $row['16'];
                                                $tenant->race_id = $race;
                                                $tenant->nationality_id = $nationality;
                                                $tenant->caj_penyelenggaraan = $row['20'];
                                                $tenant->sinking_fund = $row['21'];
                                                $tenant->remarks = $row['22'];
                                                $tenant->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        # Audit Trail
                        $remarks = 'COB Tenant List (' . $file->file_no . ')' . $this->module['audit']['text']['data_imported'];
                        $this->addAudit($file->id, "COB File", $remarks);

                        print "true";
                    } else {
                        print "empty_data";
                    }
                } else {
                    print "false";
                }
            } else {
                print "empty_file";
            }
        } else {
            print "false";
        }
    }

    public function importCOBFile()
    {
        if (Request::ajax()) {
            $file = Input::file('import_file');
            $company_id = Input::get('import_company');
            $status = Input::get('status');

            if ($file) {

                $path = $file->getRealPath();
                $data = Excel::load($path, function ($reader) {
                })->get();

                if (!empty($data) && $data->count()) {
                    $delay = 0;
                    $incrementDelay = 2;
                    foreach ($data->toArray() as $row) {
                        if (!empty($row)) {
                            // 1. File No.
                            $file_no = '';
                            if (isset($row['1']) && !empty($row['1'])) {
                                $file_no = trim($row['1']);
                            }

                            if (!empty($file_no)) {
                                Queue::later(Carbon::now()->addSeconds($delay), ImportFile::class, array('row' => $row, 'company_id' => $company_id, 'status' => $status, 'user_id' => Auth::user()->id));
                                $delay += $incrementDelay;
                            }
                        }
                    }

                    print 'true';
                } else {
                    print "empty_data";
                }
            } else {
                print "empty_file";
            }
        } else {
            print "false";
        }
    }

    public function importFinanceFile($is_api = false)
    {
        $response = '';
        DB::transaction(function () use ($is_api, &$response) {
            // if (Request::ajax()) {
            $file = Input::file('import_file');
            $file_id = Input::get('import_file_id');
            $month = Input::get('import_month');
            $year = Input::get('import_year');
            $status = Input::get('status');
            if (!empty(Input::get('import_file_no'))) {
                $file_no = Files::where('file_no', Input::get('import_file_no'))->first();
                if (empty($file_no)) {
                    $response = 'empty_file_no';
                    return $response;
                } else {
                    $file_id = $file_no->id;
                }
            }

            if ($file) {
                $path = $file->getRealPath();
                $data = Excel::load($path, function ($reader) {
                })->get();

                /** Find Finance File */
                $finance_file = Finance::with([
                    'file', 'financeAdmin', 'financeCheck', 'financeContract', 'financeIncome',
                    'financeRepair', 'financeReportPerbelanjaan', 'financeReport', 'financeStaff',
                    'financeUtility', 'financeVandal'
                ])
                    ->where(compact('file_id'))
                    ->where(compact('month'))
                    ->where(compact('year'))
                    ->where('is_deleted', 0)
                    ->first();

                $data_summary_amount = [
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

                if (!empty($finance_file) && !empty($data) && $data->count()) {
                    /**
                     * Finance data loop
                     */

                    foreach ($data as $row) {
                        $title = strtolower($row->getTitle());
                        if ($title == 'sheet1' && $row->count()) {
                            /** Finance Check */
                            $check_data = $row[0];
                            $finance_check = $finance_file->financeCheck->first();
                            $finance_check->date = $check_data[0];
                            $finance_check->name = $check_data[1];
                            $finance_check->position = $check_data[2];
                            // $finance_check->is_active = (strtolower($check_data[3]) == "yes")? "1" : "0";
                            $finance_check->remarks = $check_data[3];
                            $finance_check->save();
                        } else if ($title == 'report mf' && $row->count()) {
                            /** Finance Report MF And Perbelanjaan */
                            $report_main = $row[0];

                            $report_mf = $finance_file->financeReport()->where('type', 'MF')->first();
                            $report_mf->fee_sebulan = (empty($report_main[0]) == false) ? $report_main[0] : 0;
                            $report_mf->unit = (empty($report_main[1]) == false) ? $report_main[1] : 0;
                            $report_mf->fee_semasa = (empty($report_main[2]) == false) ? $report_main[2] : 0;
                            $report_mf->tunggakan_belum_dikutip = (empty($report_main[3]) == false) ? $report_main[3] : 0;
                            $report_mf->no_akaun = (empty($report_main[4]) == false) ? $report_main[4] : '';
                            $report_mf->nama_bank = (empty($report_main[5]) == false) ? $report_main[5] : '';
                            $report_mf->baki_bank_awal = (empty($report_main[6]) == false) ? $report_main[6] : 0;
                            $report_mf->baki_bank_akhir = (empty($report_main[7]) == false) ? $report_main[7] : 0;
                            $report_mf->save();
                        } else if ($title == 'report mf additional' && $row->count()) {
                            /** Finance Report MF Extra */
                            $mf_extra_count = $row->count();

                            if ($mf_extra_count > 0) {
                                for ($i = 0; $i < $mf_extra_count; $i++) {
                                    $mf_extra = $finance_file->financeReportExtra()
                                        ->where('type', 'MF')
                                        ->where('fee_sebulan', $row[$i][0])
                                        ->first();

                                    if (empty($mf_extra) == false) {
                                        $mf_extra->unit = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                        $mf_extra->fee_semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                        $mf_extra->save();
                                    } else {
                                        if (!empty($row[$i][0]) && $row[$i][0] > 0) {
                                            $new_mf_extra = new FinanceReportExtra();
                                            $new_mf_extra->finance_file_id = $finance_file->id;
                                            $new_mf_extra->type = 'MF';
                                            $new_mf_extra->fee_sebulan = (empty($row[$i][0]) == false) ? $row[$i][0] : 0;
                                            $new_mf_extra->unit = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                            $new_mf_extra->fee_semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                            $new_mf_extra->save();
                                        }
                                    }
                                }
                            }
                        } else if ($title == 'report sf' && $row->count()) {
                            /** Finance Report SF And Perbelanjaan */
                            $report_main = $row[0];
                            $report_sf = $finance_file->financeReport()->where('type', 'SF')->first();
                            $report_sf->fee_sebulan = (empty($report_main[0]) == false) ? $report_main[0] : 0;
                            $report_sf->unit = (empty($report_main[1]) == false) ? $report_main[1] : 0;
                            $report_sf->fee_semasa = (empty($report_main[2]) == false) ? $report_main[2] : 0;
                            $report_sf->tunggakan_belum_dikutip = (empty($report_main[3]) == false) ? $report_main[3] : 0;
                            $report_sf->no_akaun = (empty($report_main[4]) == false) ? $report_main[4] : '';
                            $report_sf->nama_bank = (empty($report_main[5]) == false) ? $report_main[5] : '';
                            $report_sf->baki_bank_awal = (empty($report_main[6]) == false) ? $report_main[6] : 0;
                            $report_sf->baki_bank_akhir = (empty($report_main[7]) == false) ? $report_main[7] : 0;
                            $report_sf->save();

                            $perkara_count = $row->count();
                            $current_num_perbelanjaan = $finance_file->financeReportPerbelanjaan()->where('type', 'SF')->count();
                            $current_sort_perbelanjaan = $finance_file->financeReportPerbelanjaan()->where('type', 'SF')->count();
                            for ($i = 8; $i < $perkara_count; $i++) {
                                $perbelanjaan = $finance_file->financeReportPerbelanjaan()->where('type', 'SF')->where('name', $row[$i][0])->first();

                                if (empty($perbelanjaan) == false) {
                                    $perbelanjaan->amount = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $perbelanjaan->save();
                                } else {
                                    $current_num_perbelanjaan += 1;
                                    $new_perbelanja = new FinanceReportPerbelanjaan();
                                    $new_perbelanja->finance_file_id = $report_sf->finance_file_id;
                                    $new_perbelanja->type = $report_sf->type;
                                    $new_perbelanja->name = $row[$i][0];
                                    $new_perbelanja->amount = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $new_perbelanja->report_key = 'custom' . $current_num_perbelanjaan;
                                    $new_perbelanja->sort_no = $current_sort_perbelanjaan;
                                    $new_perbelanja->is_custom = 1;
                                    $new_perbelanja->save();

                                    $current_sort_perbelanjaan += 1;
                                }
                            }
                        } else if ($title == 'report sf additional' && $row->count()) {
                            /** Finance Report SF Extra */
                            $sf_extra_count = $row->count();

                            if ($sf_extra_count > 0) {
                                for ($i = 0; $i < $sf_extra_count; $i++) {
                                    $sf_extra = $finance_file->financeReportExtra()
                                        ->where('type', 'SF')
                                        ->where('fee_sebulan', $row[$i][0])
                                        ->first();

                                    if (empty($sf_extra) == false) {
                                        $sf_extra->unit = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                        $sf_extra->fee_semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                        $sf_extra->save();
                                    } else {
                                        if (!empty($row[$i][0]) && $row[$i][0] > 0) {
                                            $new_sf_extra = new FinanceReportExtra();
                                            $new_sf_extra->finance_file_id = $finance_file->id;
                                            $new_sf_extra->type = 'SF';
                                            $new_sf_extra->fee_sebulan = (empty($row[$i][0]) == false) ? $row[$i][0] : 0;
                                            $new_sf_extra->unit = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                            $new_sf_extra->fee_semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                            $new_sf_extra->save();
                                        }
                                    }
                                }
                            }
                        } else if ($title == 'income' && $row->count()) {
                            /** Finance Income */
                            $incomes = $finance_file->financeIncome();


                            $perkara_count = $row->count();
                            $current_sort_income = $incomes->count();
                            for ($i = 0; $i < $perkara_count; $i++) {
                                $income_first_col = $row[$i][0];
                                $income = $finance_file->financeIncome()->where('name', $income_first_col)->first();
                                if ($income_first_col != null) {
                                    if (empty($income)) {
                                        $income = new FinanceIncome();
                                        $income->finance_file_id = $finance_file->getKey();
                                        $income->name = $income_first_col;
                                        $income->sort_no = $current_sort_income;
                                        $income->is_custom = 1;

                                        $current_sort_income += 1;
                                    }
                                    $income->tunggakan = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $income->semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                    $income->hadapan = (empty($row[$i][3]) == false) ? $row[$i][3] : 0;

                                    $income->save();
                                }
                            }
                        } else if ($title == 'utility' && $row->count()) {
                            /** Finance Utility */

                            $type_a = "BHG_A";
                            $type_b = "BHG_B";
                            $perkara_count = $row->count();
                            $current_sort_utility_a = $finance_file->financeUtility()->where('type', $type_a)->count();
                            $current_sort_utility_sf = $finance_file->financeUtility()->where('type', $type_b)->count();
                            for ($i = 1; $i < $perkara_count; $i++) {

                                $utility_first_col_a = $row[$i][0];
                                $utility_first_col_b = $row[$i][6];
                                /** Utility BHG A */
                                if (empty($utility_first_col_a) == false) {
                                    $utility_a = $finance_file->financeUtility()->where('type', $type_a)->where('name', $utility_first_col_a)->first();

                                    if (empty($utility_a)) {
                                        $current_sort_utility_a += 1;

                                        $utility_a = new FinanceUtility();
                                        $utility_a->finance_file_id = $finance_file->getKey();
                                        $utility_a->type = $type_a;
                                        $utility_a->name = $utility_first_col_a;
                                        $utility_a->sort_no = $current_sort_utility_a;
                                        $utility_a->is_custom = 1;
                                    }
                                    $utility_a->tunggakan = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $utility_a->semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                    $utility_a->hadapan = (empty($row[$i][3]) == false) ? $row[$i][3] : 0;
                                    $utility_a->tertunggak = (empty($row[$i][4]) == false) ? $row[$i][4] : 0;

                                    $utility_a->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    if (str_contains($utility_first_col_a, 'AIR')) {
                                        $data_summary_amount['bill_air'] += ($utility_a->tunggakan + $utility_a->semasa + $utility_a->hadapan);
                                    } else {
                                        $data_summary_amount['bill_elektrik'] += ($utility_a->tunggakan + $utility_a->semasa + $utility_a->hadapan);
                                    }
                                }
                                /** Utility BHG B */
                                if (empty($utility_first_col_b) == false) {
                                    $utility_b = $finance_file->financeUtility()->where('type', $type_b)->where('name', $utility_first_col_b)->first();

                                    if (empty($utility_b)) {
                                        $current_sort_utility_sf += 1;

                                        $utility_b = new FinanceUtility();
                                        $utility_b->finance_file_id = $finance_file->getKey();
                                        $utility_b->type = $type_b;
                                        $utility_b->name = $utility_first_col_b;
                                        $utility_b->sort_no = $current_sort_utility_sf;
                                        $utility_b->is_custom = 1;
                                    }
                                    $utility_b->tunggakan = (empty($row[$i][7]) == false) ? $row[$i][7] : 0;
                                    $utility_b->semasa = (empty($row[$i][8]) == false) ? $row[$i][8] : 0;
                                    $utility_b->hadapan = (empty($row[$i][9]) == false) ? $row[$i][9] : 0;
                                    $utility_b->tertunggak = (empty($row[$i][10]) == false) ? $row[$i][10] : 0;

                                    $utility_b->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    if (str_contains($utility_first_col_b, 'AIR')) {
                                        $data_summary_amount['bill_air'] += ($utility_b->tunggakan + $utility_b->semasa + $utility_b->hadapan);
                                    } else if (str_contains($utility_first_col_b, 'CUKAI TANAH')) {
                                        $data_summary_amount['caruman_cukai'] += ($utility_b->tunggakan + $utility_b->semasa + $utility_b->hadapan);
                                    } else {
                                        $data_summary_amount['utility'] += ($utility_b->tunggakan + $utility_b->semasa + $utility_b->hadapan);
                                    }
                                }
                            }
                        } else if ($title == 'contract' && $row->count()) {
                            /** Finance Contract */
                            $contracts = $finance_file->financeContract();

                            $perkara_count = $row->count();
                            $current_sort_contract = $contracts->count();
                            for ($i = 0; $i < $perkara_count; $i++) {
                                $contract_first_col = $row[$i][0];
                                $contract = $finance_file->financeContract()->where('name', $contract_first_col)->first();
                                if ($contract_first_col != null) {
                                    if (empty($contract)) {
                                        $contract = new FinanceContract();
                                        $contract->finance_file_id = $finance_file->getKey();
                                        $contract->name = $contract_first_col;
                                        $contract->sort_no = $current_sort_contract;
                                        $contract->is_custom = 1;

                                        $current_sort_contract += 1;
                                    }
                                    $contract->tunggakan = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $contract->semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                    $contract->hadapan = (empty($row[$i][3]) == false) ? $row[$i][3] : 0;
                                    $contract->tertunggak = (empty($row[$i][4]) == false) ? $row[$i][4] : 0;

                                    $contract->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    $data_summary_amount['contract'] += ($contract->tunggakan + $contract->semasa + $contract->hadapan);
                                }
                            }
                        } else if ($title == 'repair' && $row->count()) {
                            /** Finance Repair */

                            $type_mf = "MF";
                            $type_sf = "SF";
                            $perkara_count = $row->count();
                            $current_sort_repair_mf = $finance_file->financeRepair()->where('type', $type_mf)->count();
                            $current_sort_repair_sf = $finance_file->financeRepair()->where('type', $type_sf)->count();
                            for ($i = 1; $i < $perkara_count; $i++) {

                                $repair_first_col_mf = $row[$i][0];
                                $repair_first_col_sf = $row[$i][6];
                                /** Repair MF */
                                if (empty($repair_first_col_mf) == false) {
                                    $repair_mf = $finance_file->financeRepair()->where('type', $type_mf)->where('name', $repair_first_col_mf)->first();
                                    if (empty($repair_mf)) {
                                        $repair_mf = new FinanceRepair();
                                        $repair_mf->finance_file_id = $finance_file->getKey();
                                        $repair_mf->type = $type_mf;
                                        $repair_mf->name = $repair_first_col_mf;
                                        $repair_mf->sort_no = $current_sort_repair_mf;
                                        $repair_mf->is_custom = 1;

                                        $current_sort_repair_mf += 1;
                                    }

                                    $repair_mf->tunggakan = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $repair_mf->semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                    $repair_mf->hadapan = (empty($row[$i][3]) == false) ? $row[$i][3] : 0;
                                    $repair_mf->tertunggak = (empty($row[$i][4]) == false) ? $row[$i][4] : 0;

                                    $repair_mf->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    $data_summary_amount['repair'] += ($repair_mf->tunggakan + $repair_mf->semasa + $repair_mf->hadapan);
                                }
                                /** Repair SF */
                                if (empty($repair_first_col_sf) == false) {
                                    $repair_sf = $finance_file->financeRepair()->where('type', $type_sf)->where('name', $repair_first_col_sf)->first();
                                    if (empty($repair_sf)) {
                                        $repair_sf = new FinanceRepair();
                                        $repair_sf->finance_file_id = $finance_file->getKey();
                                        $repair_sf->type = $type_sf;
                                        $repair_sf->name = $repair_first_col_sf;
                                        $repair_sf->sort_no = $current_sort_repair_sf;
                                        $repair_sf->is_custom = 1;

                                        $current_sort_repair_sf += 1;
                                    }
                                    $repair_sf->tunggakan = (empty($row[$i][7]) == false) ? $row[$i][7] : 0;
                                    $repair_sf->semasa = (empty($row[$i][8]) == false) ? $row[$i][8] : 0;
                                    $repair_sf->hadapan = (empty($row[$i][9]) == false) ? $row[$i][9] : 0;
                                    $repair_sf->tertunggak = (empty($row[$i][10]) == false) ? $row[$i][10] : 0;

                                    $repair_sf->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    $data_summary_amount['repair'] += ($repair_sf->tunggakan + $repair_sf->semasa + $repair_sf->hadapan);
                                }
                            }
                        } else if ($title == 'vandalisme' && $row->count()) {
                            /** Finance Vandalisme */

                            $type_mf = "MF";
                            $type_sf = "SF";
                            $perkara_count = $row->count();
                            $current_sort_vandal_mf = $finance_file->financeVandal()->where('type', $type_mf)->count();
                            $current_sort_vandal_sf = $finance_file->financeVandal()->where('type', $type_sf)->count();
                            for ($i = 1; $i < $perkara_count; $i++) {

                                $vandal_first_col_mf = $row[$i][0];
                                $vandal_first_col_sf = $row[$i][6];
                                /** Vandal MF */
                                if (empty($vandal_first_col_mf) == false) {
                                    $vandal_mf = $finance_file->financeVandal()->where('type', $type_mf)->where('name', $vandal_first_col_mf)->first();
                                    if (empty($vandal_mf)) {
                                        $vandal_mf = new FinanceVandal();
                                        $vandal_mf->finance_file_id = $finance_file->getKey();
                                        $vandal_mf->type = $type_mf;
                                        $vandal_mf->name = $vandal_first_col_mf;
                                        $vandal_mf->sort_no = $current_sort_vandal_mf;
                                        $vandal_mf->is_custom = 1;

                                        $current_sort_vandal_mf += 1;
                                    }
                                    $vandal_mf->tunggakan = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $vandal_mf->semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                    $vandal_mf->hadapan = (empty($row[$i][3]) == false) ? $row[$i][3] : 0;
                                    $vandal_mf->tertunggak = (empty($row[$i][4]) == false) ? $row[$i][4] : 0;

                                    $vandal_mf->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    $data_summary_amount['vandalisme'] += ($vandal_mf->tunggakan + $vandal_mf->semasa + $vandal_mf->hadapan);
                                }
                                /** Vandal SF */
                                if (empty($vandal_first_col_sf) == false) {
                                    $vandal_sf = $finance_file->financeVandal()->where('type', $type_sf)->where('name', $vandal_first_col_sf)->first();
                                    if (empty($vandal_sf)) {
                                        $vandal_sf = new FinanceVandal();
                                        $vandal_sf->finance_file_id = $finance_file->getKey();
                                        $vandal_sf->type = $type_sf;
                                        $vandal_sf->name = $vandal_first_col_sf;
                                        $vandal_sf->sort_no = $current_sort_vandal_sf;
                                        $vandal_sf->is_custom = 1;

                                        $current_sort_vandal_sf += 1;
                                    }
                                    $vandal_sf->tunggakan = (empty($row[$i][7]) == false) ? $row[$i][7] : 0;
                                    $vandal_sf->semasa = (empty($row[$i][8]) == false) ? $row[$i][8] : 0;
                                    $vandal_sf->hadapan = (empty($row[$i][9]) == false) ? $row[$i][9] : 0;
                                    $vandal_sf->tertunggak = (empty($row[$i][10]) == false) ? $row[$i][10] : 0;

                                    $vandal_sf->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    $data_summary_amount['vandalisme'] += ($vandal_sf->tunggakan + $vandal_sf->semasa + $vandal_sf->hadapan);
                                }
                            }
                        } else if ($title == 'staff' && $row->count()) {
                            /** Finance Staff */
                            $staffs = $finance_file->financeStaff();

                            $perkara_count = $row->count();
                            $current_sort_staff = $staffs->count();
                            for ($i = 0; $i < $perkara_count; $i++) {
                                $staff_first_col = $row[$i][0];
                                $staff = $finance_file->financeStaff()->where('name', $staff_first_col)->first();

                                if (empty($staff_first_col) == false) {
                                    if (empty($staff)) {
                                        $staff = new FinanceStaff();
                                        $staff->finance_file_id = $finance_file->getKey();
                                        $staff->name = $staff_first_col;
                                        $staff->sort_no = $current_sort_staff;
                                        $staff->is_custom = 1;

                                        $current_sort_staff += 1;
                                    }
                                    $staff->gaji_per_orang = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $staff->bil_pekerja = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                    $staff->tunggakan = (empty($row[$i][3]) == false) ? $row[$i][3] : 0;
                                    $staff->semasa = (empty($row[$i][4]) == false) ? $row[$i][4] : 0;
                                    $staff->hadapan = (empty($row[$i][5]) == false) ? $row[$i][5] : 0;
                                    $staff->tertunggak = (empty($row[$i][6]) == false) ? $row[$i][6] : 0;

                                    $staff->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    $data_summary_amount['staff'] += ($staff->gaji_per_orang * $staff->bil_pekerja);
                                }
                            }
                        } else if ($title == 'admin' && $row->count()) {
                            /** Finance Admin */
                            $admins = $finance_file->financeAdmin();

                            $perkara_count = $row->count();
                            $current_sort_admin = $admins->count();
                            for ($i = 0; $i < $perkara_count; $i++) {
                                $admin_first_col = $row[$i][0];
                                $admin = $finance_file->financeAdmin()->where('name', $admin_first_col)->first();

                                if (empty($admin_first_col) == false) {
                                    if (empty($admin)) {
                                        $admin = new FinanceAdmin();
                                        $admin->finance_file_id = $finance_file->getKey();
                                        $admin->name = $admin_first_col;
                                        $admin->sort_no = $current_sort_admin;
                                        $admin->is_custom = 1;

                                        $current_sort_admin += 1;
                                    }
                                    $admin->tunggakan = (empty($row[$i][1]) == false) ? $row[$i][1] : 0;
                                    $admin->semasa = (empty($row[$i][2]) == false) ? $row[$i][2] : 0;
                                    $admin->hadapan = (empty($row[$i][3]) == false) ? $row[$i][3] : 0;
                                    $admin->tertunggak = (empty($row[$i][4]) == false) ? $row[$i][4] : 0;

                                    $admin->save();

                                    /**
                                     * Summary Calculation
                                     */
                                    $data_summary_amount['admin'] += ($admin->tunggakan + $admin->semasa + $admin->hadapan);
                                }
                            }
                        }
                    }

                    # Audit Trail
                    $remarks = $finance_file->file->file_no . " finance data" . $this->module['audit']['text']['data_imported'];
                    $this->addAudit($finance_file->file->id, "COB Finance", $remarks);

                    (new FinanceController())->saveFinanceSummary($finance_file, $data_summary_amount);

                    if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                        /**
                         * Add Notification & send email to COB and JMB
                         */
                        $not_draft_strata = $finance_file->file->strata;
                        $notify_data['file_id'] = $finance_file->file->id;
                        $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($finance_file->id)]);
                        $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($finance_file->id)]);
                        $notify_data['strata'] = "your";
                        $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance_file->file->file_no;
                        $notify_data['title'] = "Finance File";
                        $notify_data['module'] = "Finance File";

                        (new NotificationService())->store($notify_data, 'imported');
                    }

                    $response = 'true';
                    return $response;
                } else {
                    $response = 'empty_datas';
                    return $response;
                }
            } else {
                $response = 'empty_file';
                return $response;
            }
            // } else {
            //     print "false";
            // }
        });

        if ($is_api) {
            return $response;
        } else {
            print $response;
        }
    }
}
