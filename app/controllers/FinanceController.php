<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Enums\AdminStatus;
use Services\NotificationService;

class FinanceController extends BaseController
{

    // add finance file list
    public function addFinanceFileList()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file_no = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file_no = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file_no = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file_no = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        $year = Files::getVPYear();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(37));

        $viewData = array(
            'title' => trans('app.menus.finance.add_finance_file_list'),
            'panel_nav_active' => 'finance_panel',
            'main_nav_active' => 'finance_main',
            'sub_nav_active' => 'add_finance_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no,
            'year' => $year,
            'month' => Finance::monthList()
        );

        return View::make('finance_en.add_finance_file', $viewData);
    }

    public function submitAddFinanceFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = $data['file_id'];
            $year = $data['year'];
            $month = $data['month'];

            $file = Files::find($file_id);
            if ($file) {
                $check_exist = Finance::where('file_id', $file_id)->where('year', $year)->where('month', $month)->where('is_deleted', 0)->count();
                if ($check_exist <= 0) {
                    $finance = new Finance();
                    $finance->file_id = $file->id;
                    $finance->company_id = $file->company_id;
                    $finance->month = $month;
                    $finance->year = $year;
                    $finance->is_active = 1;
                    $success = $finance->save();

                    $previous_file = '';
                    if ($month > 1) {
                        $previous_month = $month - 1;
                        $previous_file = Finance::with([
                            'financeCheck', 'financeSummary', 'financeContract', 'financeIncome', 'financeAdmin',
                            'financeRepair', 'financeReportPerbelanjaan', 'financeReport', 'financeReportExtra',
                            'financeStaff', 'financeUtility', 'financeVandal'
                        ])
                            ->where('file_id', $file_id)
                            ->where('year', $year)
                            ->where('month', $previous_month)
                            ->where('is_deleted', 0)
                            ->first();
                    } else {
                        $previous_year = $year - 1;
                        $previous_file = Finance::with([
                            'financeCheck', 'financeSummary', 'financeContract', 'financeIncome', 'financeAdmin',
                            'financeRepair', 'financeReportPerbelanjaan', 'financeReport', 'financeReportExtra',
                            'financeStaff', 'financeUtility', 'financeVandal'
                        ])
                            ->where('file_id', $file_id)
                            ->where('year', $previous_year)
                            ->where('month', 12)
                            ->where('is_deleted', 0)
                            ->first();
                    }

                    if ($success) {
                        /*
                         * create Check
                         */
                        $check = new FinanceCheck();
                        $check->finance_file_id = $finance->id;
                        $check->is_active = 1;
                        // if(!empty($previous_file)) {
                        //     /** Clone Finance Check */
                        //     $check->date = $previous_file->financeCheck->date;
                        //     $check->name = $previous_file->financeCheck->name;
                        //     $check->position = $previous_file->financeCheck->position;
                        //     $check->remarks = $previous_file->financeCheck->remarks;
                        // }
                        $createCheck = $check->save();

                        if ($createCheck) {
                            /*
                             * create Summary
                             */
                            $summary_keys = $this->module['finance']['tabs']['summary']['only'];
                            $messages = Config::get('constant.others.messages');
                            $countSum = 1;

                            foreach ($summary_keys as $key) {
                                $summary = new FinanceSummary();
                                $summary->finance_file_id = $finance->id;
                                $summary->name = $messages[$key];
                                $summary->summary_key = $key;
                                $summary->amount = 0;
                                $summary->sort_no = $countSum;
                                $summary->save();

                                $countSum++;
                            }

                            /** Clone Finance Report */
                            if (!empty($previous_file)) {
                                $previous_report = $previous_file->financeReport;
                                if ($previous_report->count()) {
                                    foreach ($previous_report as $p_report) {
                                        $report = new FinanceReport();
                                        $report->finance_file_id = $finance->id;
                                        $report->type = $p_report->type;
                                        $report->fee_sebulan = $p_report->fee_sebulan;
                                        $report->unit = $p_report->unit;
                                        $report->fee_semasa = $p_report->fee_semasa;
                                        $report->tunggakan_belum_dikutip = $p_report->tunggakan_belum_dikutip;
                                        $report->no_akaun = $p_report->no_akaun;
                                        $report->nama_bank = $p_report->nama_bank;
                                        $report->baki_bank_akhir = $p_report->baki_bank_akhir;
                                        $report->baki_bank_awal = $p_report->baki_bank_awal;
                                        $report->save();
                                    }
                                }

                                /** Clone Finance Report Extra */
                                $previous_mf_report_extra = $previous_file->financeReportExtra;
                                if ($previous_mf_report_extra->count()) {
                                    foreach ($previous_mf_report_extra as $p_extra) {
                                        $extra = new FinanceReportExtra();
                                        $extra->finance_file_id = $finance->id;
                                        $extra->type = $p_extra->type;
                                        $extra->fee_sebulan = $p_extra->fee_sebulan;
                                        $extra->unit = $p_extra->unit;
                                        $extra->fee_semasa = $p_extra->fee_semasa;
                                        $extra->save();
                                    }
                                }

                                /** Clone Finance Report Perbelanjaan */
                                $previous_report_perbelanjaan = $previous_file->financeReportPerbelanjaan;
                                if (!empty($previous_file) && $previous_report_perbelanjaan->count()) {
                                    foreach ($previous_report_perbelanjaan as $p_perbelanjaan) {
                                        $perbelanjaan = new FinanceReportPerbelanjaan();
                                        $perbelanjaan->finance_file_id = $finance->id;
                                        $perbelanjaan->type = $p_perbelanjaan->type;
                                        $perbelanjaan->name = $p_perbelanjaan->name;
                                        $perbelanjaan->report_key = $p_perbelanjaan->report_key;
                                        $perbelanjaan->amount = 0;
                                        $perbelanjaan->sort_no = $p_perbelanjaan->sort_no;
                                        $perbelanjaan->save();
                                    }
                                }
                            } else {
                                /*
                                * create MF Report
                                */
                                $reportMF = new FinanceReport();
                                $reportMF->finance_file_id = $finance->id;
                                $reportMF->type = 'MF';
                                $createMF = $reportMF->save();

                                if ($createMF) {
                                    $tableFieldMF = [
                                        'utility' => 'UTILITI (BAHAGIAN A SAHAJA)',
                                        'contract' => 'PENYENGGARAAN',
                                        'repair' => 'PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN',
                                        'vandalisme' => 'PEMBAIKAN/PENGGANTIAN/PEMBELIAN (VANDALISME)',
                                        'staff' => 'PEKERJA',
                                        'admin' => 'PENTADBIRAN'
                                    ];

                                    $count = 1;
                                    foreach ($tableFieldMF as $key => $name) {
                                        $reportMF = new FinanceReportPerbelanjaan();
                                        $reportMF->type = 'MF';
                                        $reportMF->finance_file_id = $finance->id;
                                        $reportMF->name = $name;
                                        $reportMF->report_key = $key;
                                        $reportMF->amount = 0;
                                        $reportMF->sort_no = $count;
                                        $reportMF->save();

                                        $count++;
                                    }
                                }

                                /*
                                * create SF Report
                                */
                                $reportSF = new FinanceReport();
                                $reportSF->finance_file_id = $finance->id;
                                $reportSF->type = 'SF';
                                $createSF = $reportSF->save();

                                if ($createSF) {
                                    $tableFieldSF = [
                                        'repair' => 'PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN',
                                        'vandalisme' => 'PEMBAIKAN/PENGGANTIAN/PEMBELIAN (VANDALISME)'
                                    ];

                                    $counter = 1;
                                    foreach ($tableFieldSF as $key => $name) {
                                        $reportSF = new FinanceReportPerbelanjaan();
                                        $reportSF->type = 'SF';
                                        $reportSF->finance_file_id = $finance->id;
                                        $reportSF->name = $name;
                                        $reportSF->report_key = $key;
                                        $reportSF->amount = 0;
                                        $reportSF->sort_no = $counter;
                                        $reportSF->save();

                                        $counter++;
                                    }
                                }
                            }

                            /** Clone Finance Income */
                            // $previous_income = $previous_file->financeIncome;
                            // if(!empty($previous_file) && $previous_income->count()) {
                            //     foreach($previous_income as $p_income) {
                            //         $income = new FinanceIncome();
                            //         $income->finance_file_id = $finance->id;
                            //         $income->name = $p_income->name;
                            //         $income->tunggakan = $p_income->tunggakan;
                            //         $income->semasa = $p_income->semasa;
                            //         $income->hadapan = $p_income->hadapan;
                            //         $income->sort_no = $p_income->sort_no;
                            //         $income->save();
                            //     }
                            // } else {
                            /*
                                 * create Income
                                 */
                            $income_keys = $this->module['finance']['tabs']['income']['default'];
                            $messages = Config::get('constant.others.tbl_fields_name');

                            foreach ($income_keys as $count => $key) {
                                $income = new FinanceIncome();
                                $income->finance_file_id = $finance->id;
                                $income->name = $messages['income_' . $key];
                                $income->tunggakan = 0;
                                $income->semasa = 0;
                                $income->hadapan = 0;
                                $income->sort_no = ++$count;
                                $income->save();
                            }
                            // }

                            /** Clone Finance Utility */
                            // $previous_utility = $previous_file->financeUtility;
                            // if(!empty($previous_file) && $previous_utility->count()) {
                            //     foreach($previous_utility as $p_utility) {
                            //         $utility = new FinanceUtility();
                            //         $utility->finance_file_id = $finance->id;
                            //         $utility->type = $p_utility->type;
                            //         $utility->name = $p_utility->name;
                            //         $utility->tunggakan = $p_utility->tunggakan;
                            //         $utility->semasa = $p_utility->semasa;
                            //         $utility->hadapan = $p_utility->hadapan;
                            //         $utility->tertunggak = $p_utility->tertunggak;
                            //         $utility->sort_no = $p_utility->sort_no;
                            //         $utility->save();
                            //     }
                            // } else {
                            /*
                                 * create Utility A
                                 */
                            $tableFieldUtilityA = [
                                'BIL AIR METER PUKAL',
                                'BIL ELEKTRIK HARTA BERSAMA',
                            ];

                            foreach ($tableFieldUtilityA as $count => $name) {
                                $utilityA = new FinanceUtility();
                                $utilityA->finance_file_id = $finance->id;
                                $utilityA->type = 'BHG_A';
                                $utilityA->name = $name;
                                $utilityA->tunggakan = 0;
                                $utilityA->semasa = 0;
                                $utilityA->hadapan = 0;
                                $utilityA->tertunggak = 0;
                                $utilityA->sort_no = ++$count;
                                $utilityA->save();
                            }

                            /*
                                 * create Utility B
                                 */
                            $tableFieldUtilityB = [
                                'BIL METER AIR PEMILIK-PEMILIK (DI BAWAH AKAUN METER PUKAL SAHAJA)',
                                'BIL CUKAI TANAH',
                            ];

                            foreach ($tableFieldUtilityB as $count => $name) {
                                $utilityB = new FinanceUtility();
                                $utilityB->finance_file_id = $finance->id;
                                $utilityB->type = 'BHG_B';
                                $utilityB->name = $name;
                                $utilityB->tunggakan = 0;
                                $utilityB->semasa = 0;
                                $utilityB->hadapan = 0;
                                $utilityB->tertunggak = 0;
                                $utilityB->sort_no = ++$count;
                                $utilityB->save();
                            }
                            // }

                            /** Clone Finance Contract */
                            // $previous_contract = $previous_file->financeContract;
                            // if(!empty($previous_file) && $previous_contract->count()) {
                            //     foreach($previous_contract as $p_contract) {
                            //         $contract = new FinanceContract();
                            //         $contract->finance_file_id = $finance->id;
                            //         $contract->name = $p_contract->name;
                            //         $contract->tunggakan = $p_contract->tunggakan;
                            //         $contract->semasa = $p_contract->semasa;
                            //         $contract->hadapan = $p_contract->hadapan;
                            //         $contract->tertunggak = $p_contract->tertunggak;
                            //         $contract->sort_no = $p_contract->sort_no;
                            //         $contract->save();
                            //     }
                            // } else {
                            /*
                                 * create Contract
                                 */
                            $tableFieldContract = [
                                'FI FIRMA KOMPETEN LIF',
                                'PEMBERSIHAN (KONTRAK)',
                                'KESELAMATAN',
                                'INSURANS',
                                'JURUTERA ELEKTRIK',
                                'CUCI TANGKI AIR',
                                'UJI PENGGERA KEBAKARAN',
                                'CUCI KOLAM RENANG',
                                'SEDUT PEMBETUNG',
                                'POTONG RUMPUT/LANSKAP',
                                'SISTEM KAD AKSES',
                                'SISTEM CCTV',
                                'UJI PERALATAN/ALAT PEMADAM KEBAKARAN',
                                'KUTIPAN SAMPAH PUKAL',
                                'KAWALAN SERANGGA',
                            ];

                            foreach ($tableFieldContract as $count => $name) {
                                $contract = new FinanceContract();
                                $contract->finance_file_id = $finance->id;
                                $contract->name = $name;
                                $contract->tunggakan = 0;
                                $contract->semasa = 0;
                                $contract->hadapan = 0;
                                $contract->tertunggak = 0;
                                $contract->sort_no = ++$count;
                                $contract->save();
                            }
                            // }

                            /** Clone Finance Repair */
                            // $previous_repair = $previous_file->financeRepair;
                            // if(!empty($previous_file) && $previous_repair->count()) {
                            //     foreach($previous_repair as $p_repair) {
                            //         $repair = new FinanceRepair();
                            //         $repair->finance_file_id = $finance->id;
                            //         $repair->type = $p_repair->type;
                            //         $repair->name = $p_repair->name;
                            //         $repair->tunggakan = $p_repair->tunggakan;
                            //         $repair->semasa = $p_repair->semasa;
                            //         $repair->hadapan = $p_repair->hadapan;
                            //         $repair->tertunggak = $p_repair->tertunggak;
                            //         $repair->sort_no = $p_repair->sort_no;
                            //         $repair->save();
                            //     }
                            // } else {
                            /*
                                 * create Repair MF
                                 */
                            $tableFieldRepairMF = [
                                'LIF',
                                'TANGKI AIR',
                                'BUMBUNG',
                                'GUTTER',
                                'RAIN WATER DOWN PIPE',
                                'PEMBENTUNG',
                                'PERPAIPAN',
                                'WAYAR BUMI',
                                'PENDAWAIAN ELEKTRIK',
                                'TANGGA/HANDRAIL',
                                'JALAN',
                                'PAGAR',
                                'LONGKANG',
                                'SUBSTATION TNB',
                                'ALAT PEMADAM KEBAKARAN',
                                'SISTEM KAD AKSES',
                                'CCTV',
                                'PELEKAT KENDERAAN',
                                'GENSET',
                            ];

                            foreach ($tableFieldRepairMF as $count => $name) {
                                $repairMF = new FinanceRepair();
                                $repairMF->finance_file_id = $finance->id;
                                $repairMF->type = 'MF';
                                $repairMF->name = $name;
                                $repairMF->tunggakan = 0;
                                $repairMF->semasa = 0;
                                $repairMF->hadapan = 0;
                                $repairMF->tertunggak = 0;
                                $repairMF->sort_no = ++$count;
                                $repairMF->save();
                            }

                            /*
                                 * create Repair SF
                                 */
                            $tableFieldRepairSF = [
                                'LIF',
                                'TANGKI AIR',
                                'BUMBUNG',
                                'GUTTER',
                                'RAIN WATER DOWN PIPE',
                                'PEMBENTUNG',
                                'PERPAIPAN',
                                'WAYAR BUMI',
                                'PENDAWAIAN ELEKTRIK',
                                'TANGGA/HANDRAIL',
                                'JALAN',
                                'PAGAR',
                                'LONGKANG',
                                'SUBSTATION TNB',
                                'ALAT PEMADAM KEBAKARAN',
                                'SISTEM KAD AKSES',
                                'CCTV',
                                'GENSET',
                            ];

                            foreach ($tableFieldRepairSF as $count => $name) {
                                $repairSF = new FinanceRepair();
                                $repairSF->finance_file_id = $finance->id;
                                $repairSF->type = 'SF';
                                $repairSF->name = $name;
                                $repairSF->tunggakan = 0;
                                $repairSF->semasa = 0;
                                $repairSF->hadapan = 0;
                                $repairSF->tertunggak = 0;
                                $repairSF->sort_no = ++$count;
                                $repairSF->save();
                            }
                            // }

                            /** Clone Finance Vandalisme */
                            // $previous_vandalisme = $previous_file->financeVandal;
                            // if(!empty($previous_file) && $previous_vandalisme->count()) {
                            //     foreach($previous_vandalisme as $p_vandalisme) {
                            //         $vandalisme = new FinanceVandal();
                            //         $vandalisme->finance_file_id = $finance->id;
                            //         $vandalisme->type = $p_vandalisme->type;
                            //         $vandalisme->name = $p_vandalisme->name;
                            //         $vandalisme->tunggakan = $p_vandalisme->tunggakan;
                            //         $vandalisme->semasa = $p_vandalisme->semasa;
                            //         $vandalisme->hadapan = $p_vandalisme->hadapan;
                            //         $vandalisme->tertunggak = $p_vandalisme->tertunggak;
                            //         $vandalisme->sort_no = $p_vandalisme->sort_no;
                            //         $vandalisme->save();
                            //     }
                            // } else {
                            /*
                                * create Vandalisme MF
                                */
                            $tableFieldVandalismeMF = [
                                'LIF',
                                'WAYAR BUMI',
                                'PENDAWAIAN ELEKTRIK',
                                'PAGAR',
                                'SUBSTATION TNB',
                                'PERALATAN/ALAT PEMADAM KEBAKARAN',
                                'SISTEM KAD AKSES',
                                'CCTV',
                                'GENSET',
                            ];

                            foreach ($tableFieldVandalismeMF as $count => $name) {
                                $vandalismeMF = new FinanceVandal();
                                $vandalismeMF->finance_file_id = $finance->id;
                                $vandalismeMF->type = 'MF';
                                $vandalismeMF->name = $name;
                                $vandalismeMF->tunggakan = 0;
                                $vandalismeMF->semasa = 0;
                                $vandalismeMF->hadapan = 0;
                                $vandalismeMF->tertunggak = 0;
                                $vandalismeMF->sort_no = ++$count;
                                $vandalismeMF->save();
                            }

                            /*
                                * create Vandalisme SF
                                */
                            $tableFieldVandalismeSF = [
                                'LIF',
                                'WAYAR BUMI',
                                'PENDAWAIAN ELEKTRIK',
                                'PAGAR',
                                'SUBSTATION TNB',
                                'PERALATAN/ALAT PEMADAM KEBAKARAN',
                                'SISTEM KAD AKSES',
                                'CCTV',
                                'GENSET',
                            ];

                            foreach ($tableFieldVandalismeSF as $count => $name) {
                                $vandalismeSF = new FinanceVandal();
                                $vandalismeSF->finance_file_id = $finance->id;
                                $vandalismeSF->type = 'SF';
                                $vandalismeSF->name = $name;
                                $vandalismeSF->tunggakan = 0;
                                $vandalismeSF->semasa = 0;
                                $vandalismeSF->hadapan = 0;
                                $vandalismeSF->tertunggak = 0;
                                $vandalismeSF->sort_no = ++$count;
                                $vandalismeSF->save();
                            }
                            // }


                            /** Clone Finance Staff */
                            // $previous_staff = $previous_file->financeVandal;
                            // if(!empty($previous_file) && $previous_staff->count()) {
                            //     foreach($previous_staff as $p_staff) {
                            //         $staff = new FinanceStaff();
                            //         $staff->finance_file_id = $finance->id;
                            //         $staff->name = $staff->name;
                            //         $staff->gaji_per_orang = $staff->gaji_per_orang;
                            //         $staff->bil_pekerja = $staff->bil_pekerja;
                            //         $staff->tunggakan = $staff->tunggakan;
                            //         $staff->semasa = $staff->semasa;
                            //         $staff->hadapan = $staff->hadapan;
                            //         $staff->tertunggak = $staff->tertunggak;
                            //         $staff->sort_no = $staff->sort_no;
                            //         $staff->save();
                            //     }
                            // } else {
                            /*
                                * create Staff
                                */
                            $tableFieldStaff = [
                                'PENGAWAL KESELAMATAN',
                                'PEMBERSIHAN',
                                'RENCAM',
                                'KERANI',
                                'JURUTEKNIK',
                                'PENYELIA',
                            ];

                            foreach ($tableFieldStaff as $count => $name) {
                                $staff = new FinanceStaff();
                                $staff->finance_file_id = $finance->id;
                                $staff->name = $name;
                                $staff->gaji_per_orang = 0;
                                $staff->bil_pekerja = 0;
                                $staff->tunggakan = 0;
                                $staff->semasa = 0;
                                $staff->hadapan = 0;
                                $staff->tertunggak = 0;
                                $staff->sort_no = ++$count;
                                $staff->save();
                            }
                            // }


                            /** Clone Finance Admin */
                            // $previous_admin = $previous_file->financeAdmin;
                            // if(!empty($previous_file) && $previous_admin->count()) {
                            //     foreach($previous_admin as $p_admin) {
                            //         $admin = new FinanceAdmin();
                            //         $admin->finance_file_id = $finance->id;
                            //         $admin->name = $p_admin->name;
                            //         $admin->tunggakan = $p_admin->tunggakan;
                            //         $admin->semasa = $p_admin->semasa;
                            //         $admin->hadapan = $p_admin->hadapan;
                            //         $admin->tertunggak = $p_admin->tertunggak;
                            //         $admin->sort_no = $p_admin->sort_no;
                            //         $admin->save();
                            //     }
                            // } else {
                            /*
                                * create Admin
                                */
                            $tableFieldAdmin = [
                                'TELEFON & INTERNET',
                                'PERALATAN',
                                'ALAT TULIS PEJABAT',
                                'PETTY CASH',
                                'SEWAAN MESIN FOTOKOPI',
                                'PERKHIDMATAN SISTEM UBS @ LAIN-LAIN SISTEM',
                                'PERKHIDMATAN AKAUN',
                                'PERKHIDMATAN AUDIT',
                                'CAJ PERUNDANGAN',
                                'CAJ PENGHANTARAN & KUTIPAN',
                                'CAJ BANK',
                                'FI EJEN PENGURUSAN',
                                'PERBELANJAAN MESYUARAT',
                                'ELAUN JMB/MC',
                                'LAIN-LAIN TUNTUTAN JMB/MC',
                            ];

                            foreach ($tableFieldAdmin as $count => $name) {
                                $admin = new FinanceAdmin();
                                $admin->finance_file_id = $finance->id;
                                $admin->name = $name;
                                $admin->tunggakan = 0;
                                $admin->semasa = 0;
                                $admin->hadapan = 0;
                                $admin->tertunggak = 0;
                                $admin->sort_no = ++$count;
                                $admin->save();
                            }
                            // }
                        }

                        # Audit Trail
                        $remarks = 'Finance File : ' . $finance->file->file_no . " " . $finance->year . "-" . strtoupper($finance->monthName()) .  $this->module['audit']['text']['data_inserted'];
                        $this->addAudit($finance->file_id, "COB Finance", $remarks);

                        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                            /**
                             * Add Notification & send email to COB and JMB
                             */
                            $not_draft_strata = $finance->file->strata;
                            $notify_data['file_id'] = $finance->file->id;
                            $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($finance->id)]);
                            $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($finance->id)]);
                            $notify_data['strata'] = "You";
                            $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance->file->file_no;
                            $notify_data['title'] = "COB File Finance File";
                            $notify_data['module'] = "Finance File";

                            (new NotificationService())->store($notify_data);
                        }

                        return "true";
                    } else {
                        return "false";
                    }
                } else {
                    return "file_already_exists";
                }
            }
        }

        return "false";
    }

    // finance list
    public function financeList()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $user = Auth::user();

        if (!$user->getAdmin()) {
            if (!empty($user->file_id)) {
                $file = Files::where('id', $user->file_id)->where('company_id', $user->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file = Files::where('company_id', $user->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }
        $year = Files::getVPYear();

        $viewData = array(
            'title' => trans('app.menus.finance.finance_file_list'),
            'panel_nav_active' => 'finance_panel',
            'main_nav_active' => 'finance_main',
            'sub_nav_active' => 'finance_file_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'year' => $year,
            'month' => Finance::monthList(),
            'file' => $file,
            'image' => ""
        );

        return View::make('finance_en.finance_list', $viewData);
    }

    public function getFinanceList()
    {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['finance_file.*', 'strata.id as strata_id', 'finance_check.is_active as status'])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_deleted', 0)
                    ->where('finance_file.is_deleted', 0);
            } else {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['finance_file.*', 'strata.id as strata_id', 'finance_check.is_active as status'])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_deleted', 0)
                    ->where('finance_file.is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['finance_file.*', 'strata.id as strata_id', 'finance_check.is_active as status'])
                    ->where('files.is_deleted', 0)
                    ->where('finance_file.is_deleted', 0);
            } else {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['finance_file.*', 'strata.id as strata_id', 'finance_check.is_active as status'])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('files.is_deleted', 0)
                    ->where('finance_file.is_deleted', 0);
            }
        }

        if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
            $start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
            $today = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date')) : Carbon::now();
            $file = $file->where(function ($query) use ($start_date, $today) {
                $query->where(function ($query1) use ($start_date) {
                    $query1->where('finance_file.year', '>', $start_date->year)
                        ->orWhere(function ($query2) use ($start_date) {
                            $query2->where('finance_file.year', $start_date->year)
                                ->where(function ($query3) use ($start_date) {
                                    $query3->where('finance_file.month', '>', $start_date->month)
                                        ->orWhere('finance_file.month', $start_date->month);
                                });
                        });
                })
                    ->where(function ($query1) use ($today) {
                        $query1->where('finance_file.year', '<', $today->year)
                            ->orWhere(function ($query2) use ($today) {
                                $query2->where('finance_file.year', $today->year)
                                    ->where(function ($query3) use ($today) {
                                        $query3->where('finance_file.month', '<', $today->month)
                                            ->orWhere('finance_file.month', $today->month);
                                    });
                            });
                    });
            });
        }
        if (!empty(Input::get('month'))) {
            $month = (substr(Input::get('month'), 0, 1) === "0") ? str_replace("0", "", Input::get('month')) : Input::get('month');
            $file = $file->where('month', $month);
        }
        if (!empty(Input::get('company'))) {
            $file = $file->where('company.short_name', Input::get('company'));
        }

        return Datatables::of($file)
            ->addColumn('cob', function ($model) {
                return ($model->file_id ? $model->file->company->short_name : '-');
            })
            ->editColumn('file_no', function ($model) {
                return "<a style='text-decoration:underline;' href='" . URL::action('FinanceController@editFinanceFileList', Helper::encode($model->id)) . "'>" . $model->file->file_no . " " . $model->year . "-" . strtoupper($model->monthName()) . "</a>";
            })
            ->editColumn('created_at', function ($model) {
                return date('d/m/Y', strtotime($model->created_at));
            })
            ->addColumn('strata', function ($model) {
                return ($model->file_id ? $model->file->strata->strataName() : '-');
            })
            ->editColumn('month', function ($model) {
                return ($model->month ? $model->monthName() : '');
            })
            ->editColumn('year', function ($model) {
                return ($model->year != '0' ? $model->year : '');
            })
            ->addColumn('status', function ($model) {
                if ($model->status == 1) {
                    $is_active = trans('app.forms.approved');
                } else {
                    $is_active = trans('app.forms.rejected');
                }

                return $is_active;
            })
            ->addColumn('action', function ($model) {
                $button = '';
                if (AccessGroup::hasUpdate(38)) {
                    // if ($model->is_active == 1) {
                    //     $status = trans('app.forms.active');
                    //     $button .= '<a href="#" class="" onclick="inactiveFinanceList(\'' . Helper::encode($model->id) . '\')"><img src=' . asset("assets/common/img/icon/disable-eye.png") . ' width="20px"></a>&nbsp;';
                    // } else {
                    //     $status = trans('app.forms.inactive');
                    //     $button .= '<a href="#" class="" onclick="activeFinanceList(\'' . Helper::encode($model->id) . '\')"><img src=' . asset("assets/common/img/icon/eye.png") . ' width="28px"></a>&nbsp;';
                    // }
                    $button .= '<a href="' . route('finance_file.edit', [Helper::encode($model->id)]) . '" class=""><img src=' . asset("assets/common/img/icon/edit.png") . ' width="20px"></a>&nbsp;&nbsp;';
                    $button .= '<a href="#" class="" onclick="deleteFinanceList(\'' . Helper::encode($model->id) . '\')"><img src=' . asset("assets/common/img/icon/trash.png") . ' width="20px"></a>';
                }

                return $button;
            })
            ->make(true);
    }

    public function deleteFinanceList()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $finance = Finance::findOrFail($id);
            if ($finance) {
                $finance->is_active = 0;
                $finance->is_deleted = 1;
                $deleted = $finance->save();

                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Finance File : ' . $finance->file->file_no . " " . $finance->year . "-" . strtoupper($finance->monthName()) .  $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($finance->file_id, "COB Finance", $remarks);

                    if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                        /**
                         * Add Notification & send email to COB and JMB
                         */
                        $not_draft_strata = $finance->file->strata;
                        $notify_data['file_id'] = $finance->file->id;
                        $notify_data['route'] = route('finance_file.index');
                        $notify_data['cob_route'] = route('finance_file.index');
                        $notify_data['strata'] = "You";
                        $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance->file->file_no;
                        $notify_data['title'] = "Finance File";
                        $notify_data['module'] = "Finance File";

                        (new NotificationService())->store($notify_data, 'deleted');
                    }
                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        } else {
            return 'false';
        }
    }

    public function editFinanceFileList($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $file_no = Files::where('is_active', 1)->where('is_deleted', 0)->get();
        $financeCheckData = FinanceCheck::where('finance_file_id', Helper::decode($id))->firstOrFail();
        $financeCheckOldData = FinanceCheckOld::where('finance_file_id', Helper::decode($id))->first();
        $financeSummary = FinanceSummary::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeSummaryOld = FinanceSummaryOld::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financefiledata = Finance::where('id', Helper::decode($id))->first();
        $financeFileAdmin = FinanceAdmin::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeFileAdminOld = FinanceAdminOld::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeFileContract = FinanceContract::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeFileContractOld = FinanceContractOld::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeFileStaff = FinanceStaff::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeFileStaffOld = FinanceStaffOld::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeFileVandalA = FinanceVandal::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
        $financeFileVandalAOld = FinanceVandalOld::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
        $financeFileVandalB = FinanceVandal::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $financeFileVandalBOld = FinanceVandalOld::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $financeFileRepairA = FinanceRepair::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
        $financeFileRepairAOld = FinanceRepairOld::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
        $financeFileRepairB = FinanceRepair::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $financeFileRepairBOld = FinanceRepairOld::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $financeFileUtilityA = FinanceUtility::where('finance_file_id', Helper::decode($id))->where('type', 'BHG_A')->orderBy('sort_no', 'asc')->get();
        $financeFileUtilityAOld = FinanceUtilityOld::where('finance_file_id', Helper::decode($id))->where('type', 'BHG_A')->orderBy('sort_no', 'asc')->get();
        $financeFileUtilityB = FinanceUtility::where('finance_file_id', Helper::decode($id))->where('type', 'BHG_B')->orderBy('sort_no', 'asc')->get();
        $financeFileUtilityBOld = FinanceUtilityOld::where('finance_file_id', Helper::decode($id))->where('type', 'BHG_B')->orderBy('sort_no', 'asc')->get();
        $financeFileIncome = FinanceIncome::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
        $financeFileIncomeOld = FinanceIncomeOld::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();

        $mfreport = FinanceReport::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->first();
        $mfreportOld = FinanceReportOld::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->first();
        $mfreportExtra = FinanceReportExtra::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->get();
        $mfreportExtraOld = FinanceReportExtraOld::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->get();
        $reportMF = FinanceReportPerbelanjaan::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
        $reportMFOld = FinanceReportPerbelanjaanOld::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();

        $sfreport = FinanceReport::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->first();
        $sfreportOld = FinanceReportOld::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->first();
        $sfreportExtra = FinanceReportExtra::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->get();
        $sfreportExtraOld = FinanceReportExtraOld::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->get();
        $reportSF = FinanceReportPerbelanjaan::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $reportSFOld = FinanceReportPerbelanjaanOld::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $disallow = Helper::isAllow($financefiledata->file_id, $financefiledata->company_id);
        $adminStatus = AdminStatus::toArray();

        $viewData = array(
            'title' => trans('app.menus.finance.edit_finance_file_list'),
            'panel_nav_active' => 'finance_panel',
            'main_nav_active' => 'finance_main',
            'sub_nav_active' => 'finance_file_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no,
            'financefiledata' => $financefiledata,
            'checkdata' => $financeCheckData,
            'checkOldData' => $financeCheckOldData,
            'summary' => $financeSummary,
            'summaryOld' => $financeSummaryOld,
            'adminFile' => $financeFileAdmin,
            'adminOldFile' => $financeFileAdminOld,
            'contractFile' => $financeFileContract,
            'contractOldFile' => $financeFileContractOld,
            'staffFile' => $financeFileStaff,
            'staffOldFile' => $financeFileStaffOld,
            'vandala' => $financeFileVandalA,
            'vandalaOld' => $financeFileVandalAOld,
            'vandalb' => $financeFileVandalB,
            'vandalbOld' => $financeFileVandalBOld,
            'repaira' => $financeFileRepairA,
            'repairaOld' => $financeFileRepairAOld,
            'repairb' => $financeFileRepairB,
            'repairbOld' => $financeFileRepairBOld,
            'incomeFile' => $financeFileIncome,
            'incomeOldFile' => $financeFileIncomeOld,
            'utila' => $financeFileUtilityA,
            'utilaOld' => $financeFileUtilityAOld,
            'utilb' => $financeFileUtilityB,
            'utilbOld' => $financeFileUtilityBOld,
            'mfreport' => $mfreport,
            'mfreportOld' => $mfreportOld,
            'mfreportExtra' => $mfreportExtra,
            'mfreportExtraOld' => $mfreportExtraOld,
            'reportMF' => $reportMF,
            'reportMFOld' => $reportMFOld,
            'sfreport' => $sfreport,
            'sfreportOld' => $sfreportOld,
            'sfreportExtra' => $sfreportExtra,
            'sfreportExtraOld' => $sfreportExtraOld,
            'reportSF' => $reportSF,
            'reportSFOld' => $reportSFOld,
            'finance_file_id' => $id,
            'adminStatus' => $adminStatus
        );

        return View::make('finance_en.edit_finance_file', $viewData);
    }

    public function updateFinanceFileCheck()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);

            $files = Finance::findOrFail($id);
            if ($files) {
                $finance = FinanceCheck::where('finance_file_id', $files->id)->first();
                if ($finance) {
                    $old = FinanceCheckOld::firstOrNew(array('finance_file_id' => $files->id));

                    $old->date = $finance->date;
                    $old->name = $finance->name;
                    $old->position = $finance->position;
                    $old->is_active = $finance->is_active;
                    $old->remarks = $finance->remarks;
                    $old->save();

                    $finance->date = $data['date'];
                    $finance->name = $data['name'];
                    $finance->position = $data['position'];
                    $finance->is_active = $data['is_active'];
                    $finance->remarks = $data['remarks'];
                    $finance->save();

                    /** Arrange audit fields changes */
                    $date_field = $old->date == $finance->date ? "" : "date";
                    $name_field = $old->name == $finance->name ? "" : "name";
                    $position_field = $old->position == $finance->position ? "" : "position";
                    $is_active_field = $old->is_active == $finance->is_active ? "" : "status";
                    $remarks_field = $old->remarks == $finance->remarks ? "" : "remarks";

                    $audit_fields_changed = "";
                    if (!empty($name_field) || !empty($date_field) || !empty($position_field) || !empty($is_active_field) || !empty($remarks_field)) {
                        $audit_fields_changed .= "<br><ul>";
                        $audit_fields_changed .= !empty($name_field) ? "<li>$name_field</li>" : "";
                        $audit_fields_changed .= !empty($date_field) ? "<li>$date_field</li>" : "";
                        $audit_fields_changed .= !empty($position_field) ? "<li>$position_field</li>" : "";
                        $audit_fields_changed .= !empty($is_active_field) ? "<li>$is_active_field</li>" : "";
                        $audit_fields_changed .= !empty($remarks_field) ? "<li>$remarks_field</li>" : "";
                        $audit_fields_changed .= "</ul>";
                    }
                    /** End Arrange audit fields changes */
                    # Audit Trail
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'Finance File Check: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit($files->file_id, "COB Finance", $remarks);
                    }

                    if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                        /**
                         * Add Notification & send email to COB and JMB
                         */
                        $not_draft_strata = $files->file->strata;
                        $notify_data['file_id'] = $files->file->id;
                        $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]);
                        $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]);
                        $notify_data['strata'] = "You";
                        $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                        $notify_data['title'] = "Finance File Check";
                        $notify_data['module'] = "Finance File Check";

                        (new NotificationService())->store($notify_data);
                    }
                }

                return "true";
            }
        }

        return "false";
    }

    public function updateFinanceFileSummary()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefix = 'sum_';

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceSummary::where('finance_file_id', $files->id)->get();
                if (count($currents) > 0) {
                    foreach ($currents as $current) {
                        $old = FinanceSummaryOld::firstOrNew(array(
                            'finance_file_id' => $files->id,
                            'summary_key' => $current->summary_key
                        ));

                        $old->name = $current->name;
                        $old->amount = $current->amount;
                        $old->sort_no = $current->sort_no;
                        $old->save();
                    }
                }

                $remove = FinanceSummary::where('finance_file_id', $files->id)->delete();
                if ($remove) {
                    for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                        if (!empty($data[$prefix . 'name'][$i])) {
                            $finance = new FinanceSummary;
                            $finance->finance_file_id = $files->id;
                            $finance->name = $data[$prefix . 'name'][$i];
                            $finance->summary_key = $data[$prefix . 'summary_key'][$i];
                            $finance->amount = $data[$prefix . 'amount'][$i];
                            $finance->sort_no = $i;
                            $finance->save();
                        }
                    }
                }
                # Audit Trail
                //                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';
                //
                //                $auditTrail = new AuditTrail();
                //                $auditTrail->module = "COB Finance File";
                //                $auditTrail->remarks = $remarks;
                //                $auditTrail->audit_by = Auth::user()->id;
                //                $auditTrail->save();

                return "true";
            }
        }

        return "false";
    }

    public function updateFinanceFileReportMf()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $type = 'MF';
            $prefix = 'mfr_';

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->get();

                if (count($currents) > 0) {
                    FinanceReportPerbelanjaanOld::where('finance_file_id', $files->id)->where('type', $type)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceReportPerbelanjaanOld::firstOrNew(array(
                            'finance_file_id' => $files->id,
                            'type' => $type,
                            'report_key' => $current->report_key
                        ));

                        $old->name = !empty($current->name) ? $current->name : '';
                        $old->amount = $current->amount;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }
                $extra_currents = FinanceReportExtra::where('finance_file_id', $files->id)->where('type', $type)->get();

                if (count($extra_currents) > 0) {
                    FinanceReportExtraOld::where('finance_file_id', $files->id)->where('type', $type)->delete();
                    foreach ($extra_currents as $extra) {
                        $extra_old = FinanceReportExtraOld::create(array(
                            'finance_file_id' => $files->id,
                            'type' => $type
                        ));

                        $extra_old->fee_sebulan = $extra->fee_sebulan;
                        $extra_old->unit = $extra->unit;
                        $extra_old->fee_semasa = $extra->fee_semasa;
                        $extra_old->save();
                    }
                }

                $remove = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->delete();
                $finance = FinanceReport::where('finance_file_id', $files->id)->where('type', $type)->first();

                // if ($remove && $finance) {
                $main_old = FinanceReportOld::firstOrNew(array(
                    'finance_file_id' => $files->id,
                    'type' => $type
                ));

                $main_old->fee_sebulan = $finance->fee_sebulan;
                $main_old->unit = $finance->unit;
                $main_old->fee_semasa = $finance->fee_semasa;
                $main_old->tunggakan_belum_dikutip = $finance->tunggakan_belum_dikutip;
                $main_old->no_akaun = $finance->no_akaun;
                $main_old->nama_bank = $finance->nama_bank;
                $main_old->baki_bank_awal = $finance->baki_bank_awal;
                $main_old->baki_bank_akhir = $finance->baki_bank_akhir;
                $main_old->save();

                $finance->fee_sebulan = $data[$prefix . 'fee_sebulan'];
                $finance->unit = $data[$prefix . 'unit'];
                $finance->fee_semasa = $data[$prefix . 'fee_semasa'];
                $finance->tunggakan_belum_dikutip = $data[$prefix . 'tunggakan_belum_dikutip'];
                $finance->no_akaun = $data[$prefix . 'no_akaun'];
                $finance->nama_bank = $data[$prefix . 'nama_bank'];
                $finance->baki_bank_awal = $data[$prefix . 'baki_bank_awal'];
                $finance->baki_bank_akhir = $data[$prefix . 'baki_bank_akhir'];
                $finance->save();

                if (!empty($data[$prefix . 'name']) && count($data[$prefix . 'name'])) {
                    for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                        if (!empty($data[$prefix . 'name'][$i])) {
                            $frp = new FinanceReportPerbelanjaan;
                            $frp->type = $type;
                            $frp->finance_file_id = $files->id;
                            $frp->name = $data[$prefix . 'name'][$i];
                            $frp->report_key = $data[$prefix . 'report_key'][$i];
                            $frp->amount = $data[$prefix . 'amount'][$i];
                            $frp->sort_no = $i;
                            $frp->is_custom = 0;
                            $frp->save();
                        }
                    }
                }

                /** MF Report Extra */
                $delete_extra = FinanceReportExtra::where('finance_file_id', $files->id)->where('type', $type)->delete();
                if (empty($data[$prefix . 'fee_sebulan_is_custom']) == false) {
                    for ($i = 0; $i < count($data[$prefix . 'fee_sebulan_is_custom']); $i++) {
                        if (!empty($data[$prefix . 'fee_sebulan_is_custom'][$i])) {
                            $frextra = new FinanceReportExtra;
                            $frextra->type = $type;
                            $frextra->finance_file_id = $files->id;
                            $frextra->fee_sebulan = $data[$prefix . 'fee_sebulan_is_custom'][$i];
                            $frextra->unit = $data[$prefix . 'unit_is_custom'][$i];
                            $frextra->fee_semasa = $data[$prefix . 'fee_semasa_is_custom'][$i];
                            $frextra->save();
                        }
                    }
                }
                // }

                /** Arrange audit fields changes */
                $check_currents = FinanceReport::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_old = FinanceReportOld::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_extra = FinanceReportExtra::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_extra_old = FinanceReportExtraOld::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_b = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_b_old = FinanceReportPerbelanjaanOld::where('finance_file_id', $files->id)->where('type', $type)->get();
                $finance_report_differents = Helper::check_diff_multi($check_currents_old->toArray(), $check_currents->toArray());
                $finance_report_extra_differents = Helper::check_diff_multi($check_currents_extra_old->toArray(), $check_currents_extra->toArray());
                $finance_report_b_differents = Helper::check_diff_multi($check_currents_b_old->toArray(), $check_currents_b->toArray());
                $audit_fields_changed = "";
                if (count($finance_report_differents) || count($finance_report_extra_differents) || count($finance_report_b_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                }
                if (count($finance_report_differents)) {
                    $audit_fields_changed .= "<li>Finance MF Report : (";
                    $new_line = '';
                    foreach ($finance_report_differents as $frd_key => $frd) {
                        if (is_array($frd) && count($frd)) {
                            foreach ($frd as $frd_data_key => $frd_data) {
                                $name = str_replace("_", " ", $frd_data_key);
                                $new_line .= $name . '=' . $frd_data . ', ';
                            }
                        } else {
                            if (!empty($frd)) {
                                $name = str_replace("_", " ", $frd_key);
                                $new_line .= $name . '=' . $frd . ', ';
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                }
                if (count($finance_report_extra_differents)) {
                    $audit_fields_changed .= "<li>Finance MF Report Extra : (";
                    $new_line_1 = '';
                    foreach ($finance_report_extra_differents as $fr_extra_key => $fr_extra) {
                        if ($fr_extra == null) {
                            $new_line_1 .= $new_line_1 ? ', data removed' : 'data_removed, ';
                        } else {
                            if (is_array($fr_extra) && count($fr_extra)) {
                                foreach ($fr_extra as $fr_extra_data_key => $fr_extra_data) {
                                    $name = str_replace("_", " ", $fr_extra_data_key);
                                    $new_line_1 .= $name . '=' . $fr_extra_data . ', ';
                                }
                            } else {
                                if (!empty($fr_extra)) {
                                    $name = str_replace("_", " ", $fr_extra_key);
                                    $new_line_1 .= $name . '=' . $fr_extra . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line_1) . ")</li>";
                }
                if (count($finance_report_b_differents)) {
                    $audit_fields_changed .= "<li>Finance MF Report Perbelanjaan: (";
                    $new_line_2 = '';
                    foreach ($finance_report_b_differents as $fr_b_key => $fr_b) {
                        if (is_array($fr_b) && count($fr_b)) {
                            foreach ($fr_b as $fr_b_data_key => $fr_b_data) {
                                $name = str_replace("_", " ", $fr_b_data_key);
                                $new_line_2 .= $name . '=' . $fr_b_data . ', ';
                            }
                        } else {
                            if (!empty($fr_b)) {
                                $name = str_replace("_", " ", $fr_b_key);
                                $new_line_2 .= $name . '=' . $fr_b . ', ';
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line_2) . ")</li>";
                }
                if (count($finance_report_differents) || count($finance_report_extra_differents) || count($finance_report_b_differents)) {
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#mfreport";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#mfreport";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File MF Report";
                    $notify_data['module'] = "Finance File MF Report";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileReportSf()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $type = 'SF';
            $prefix = 'sfr_';

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->get();
                if (count($currents) > 0) {
                    FinanceReportPerbelanjaanOld::where('finance_file_id', $files->id)->where('type', $type)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceReportPerbelanjaanOld::firstOrNew(array(
                            'finance_file_id' => $files->id,
                            'type' => $type,
                            'report_key' => $current->report_key
                        ));

                        $old->name = !empty($current->name) ? $current->name : '';
                        $old->amount = $current->amount;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }
                $extra_currents = FinanceReportExtra::where('finance_file_id', $files->id)->where('type', $type)->get();
                if (count($extra_currents) > 0) {
                    FinanceReportExtraOld::where('finance_file_id', $files->id)->where('type', $type)->delete();
                    foreach ($extra_currents as $extra) {
                        $extra_old = FinanceReportExtraOld::create(array(
                            'finance_file_id' => $files->id,
                            'type' => $type
                        ));

                        $extra_old->fee_sebulan = $extra->fee_sebulan;
                        $extra_old->unit = $extra->unit;
                        $extra_old->fee_semasa = $extra->fee_semasa;
                        $extra_old->save();
                    }
                }

                $remove = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->delete();
                $finance = FinanceReport::where('finance_file_id', $files->id)->where('type', $type)->first();

                // if ($remove && $finance) {
                $main_old = FinanceReportOld::firstOrNew(array(
                    'finance_file_id' => $files->id,
                    'type' => $type
                ));

                $main_old->fee_sebulan = $finance->fee_sebulan;
                $main_old->unit = $finance->unit;
                $main_old->fee_semasa = $finance->fee_semasa;
                $main_old->tunggakan_belum_dikutip = $finance->tunggakan_belum_dikutip;
                $main_old->no_akaun = $finance->no_akaun;
                $main_old->nama_bank = $finance->nama_bank;
                $main_old->baki_bank_awal = $finance->baki_bank_awal;
                $main_old->baki_bank_akhir = $finance->baki_bank_akhir;
                $main_old->save();

                $finance->fee_sebulan = $data[$prefix . 'fee_sebulan'];
                $finance->unit = $data[$prefix . 'unit'];
                $finance->fee_semasa = $data[$prefix . 'fee_semasa'];
                $finance->tunggakan_belum_dikutip = $data[$prefix . 'tunggakan_belum_dikutip'];
                $finance->no_akaun = $data[$prefix . 'no_akaun'];
                $finance->nama_bank = $data[$prefix . 'nama_bank'];
                $finance->baki_bank_awal = $data[$prefix . 'baki_bank_awal'];
                $finance->baki_bank_akhir = $data[$prefix . 'baki_bank_akhir'];
                $finance->save();

                if (!empty($data[$prefix . 'name'])) {
                    for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                        if (!empty($data[$prefix . 'name'][$i])) {
                            $frp = new FinanceReportPerbelanjaan;
                            $frp->type = $type;
                            $frp->finance_file_id = $files->id;
                            $frp->name = $data[$prefix . 'name'][$i];
                            $frp->report_key = $data[$prefix . 'report_key'][$i];
                            $frp->amount = $data[$prefix . 'amount'][$i];
                            $frp->sort_no = $i;
                            $frp->is_custom = $data[$prefix . 'is_custom'][$i];
                            $frp->save();
                        }
                    }
                }

                /** SF Report Extra */
                $delete_extra = FinanceReportExtra::where('finance_file_id', $files->id)->where('type', $type)->delete();
                if (empty($data[$prefix . 'fee_sebulan_is_custom']) == false) {
                    for ($i = 0; $i < count($data[$prefix . 'fee_sebulan_is_custom']); $i++) {
                        if (!empty($data[$prefix . 'fee_sebulan_is_custom'][$i])) {
                            $frextra = new FinanceReportExtra;
                            $frextra->type = $type;
                            $frextra->finance_file_id = $files->id;
                            $frextra->fee_sebulan = $data[$prefix . 'fee_sebulan_is_custom'][$i];
                            $frextra->unit = $data[$prefix . 'unit_is_custom'][$i];
                            $frextra->fee_semasa = $data[$prefix . 'fee_semasa_is_custom'][$i];
                            $frextra->save();
                        }
                    }
                }
                // }

                /** Arrange audit fields changes */
                $check_currents = FinanceReport::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_old = FinanceReportOld::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_extra = FinanceReportExtra::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_extra_old = FinanceReportExtraOld::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_b = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->get();
                $check_currents_b_old = FinanceReportPerbelanjaanOld::where('finance_file_id', $files->id)->where('type', $type)->get();
                $finance_report_differents = Helper::check_diff_multi($check_currents_old->toArray(), $check_currents->toArray());
                $finance_report_extra_differents = Helper::check_diff_multi($check_currents_extra_old->toArray(), $check_currents_extra->toArray());
                $finance_report_b_differents = Helper::check_diff_multi($check_currents_b_old->toArray(), $check_currents_b->toArray());
                $audit_fields_changed = "";
                if (count($finance_report_differents) || count($finance_report_extra_differents) || count($finance_report_b_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                }
                if (count($finance_report_differents)) {
                    $audit_fields_changed .= "<li>Finance SF Report : (";
                    $new_line = '';
                    foreach ($finance_report_differents as $frd_key => $frd) {
                        if (is_array($frd) && count($frd)) {
                            foreach ($frd as $frd_data_key => $frd_data) {
                                $name = str_replace("_", " ", $frd_data_key);
                                $new_line .= $name . '=' . $frd_data . ', ';
                            }
                        } else {
                            if (!empty($frd)) {
                                $name = str_replace("_", " ", $frd_key);
                                $new_line .= $name . '=' . $frd . ', ';
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                }
                if (count($finance_report_extra_differents)) {
                    $audit_fields_changed .= "<li>Finance SF Report Extra : (";
                    $new_line_1 = '';
                    foreach ($finance_report_extra_differents as $fr_extra_key => $fr_extra) {
                        if ($fr_extra == null) {
                            $new_line_1 .= $new_line_1 ? ', data removed' : 'data_removed, ';
                        } else {
                            if (is_array($fr_extra) && count($fr_extra)) {
                                foreach ($fr_extra as $fr_extra_data_key => $fr_extra_data) {
                                    $name = str_replace("_", " ", $fr_extra_data_key);
                                    $new_line_1 .= $name . '=' . $fr_extra_data . ', ';
                                }
                            } else {
                                if (!empty($fr_extra)) {
                                    $name = str_replace("_", " ", $fr_extra_key);
                                    $new_line_1 .= $name . '=' . $fr_extra . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line_1) . ")</li>";
                }
                if (count($finance_report_b_differents)) {
                    $audit_fields_changed .= "<li>Finance SF Report Perbelanjaan: (";
                    $new_line_2 = '';
                    foreach ($finance_report_b_differents as $fr_b_key => $fr_b) {
                        if ($fr_b == null) {
                            $new_line_2 .= $new_line_2 ? ', data removed' : 'data_removed, ';
                        } else {
                            if (is_array($fr_b) && count($fr_b)) {
                                foreach ($fr_b as $fr_b_data_key => $fr_b_data) {
                                    if (!in_array($fr_b_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $fr_b_data_key);
                                        $new_line_2 .= $name . '=' . $fr_b_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($fr_b)) {
                                    $name = str_replace("_", " ", $fr_b_key);
                                    $new_line_2 .= $name . '=' . $fr_b . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line_2) . ")</li>";
                }
                if (count($finance_report_differents) || count($finance_report_extra_differents) || count($finance_report_b_differents)) {
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#sfreport";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#sfreport";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File SF Report";
                    $notify_data['module'] = "Finance File SF Report";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileAdmin()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefix = 'admin_';

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceAdmin::where('finance_file_id', $files->id)->get();

                if (count($currents) > 0) {
                    FinanceAdminOld::where('finance_file_id', $files->id)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceAdminOld::firstOrNew([
                            'finance_file_id' => $files->id,
                            'name' => $current->name
                        ]);
                        $old->tunggakan = $current->tunggakan;
                        $old->semasa = $current->semasa;
                        $old->hadapan = $current->hadapan;
                        $old->tertunggak = $current->tertunggak;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }

                $remove = FinanceAdmin::where('finance_file_id', $files->id)->delete();

                if ($remove) {
                    for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                        if (!empty($data[$prefix . 'name'][$i])) {
                            $finance = new FinanceAdmin();
                            $finance->finance_file_id = $files->id;
                            $finance->name = $data[$prefix . 'name'][$i];
                            $finance->tunggakan = $data[$prefix . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefix . 'semasa'][$i];
                            $finance->hadapan = $data[$prefix . 'hadapan'][$i];
                            $finance->tertunggak = $data[$prefix . 'tertunggak'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefix . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                /** Arrange audit fields changes */
                $check_finance_admin = FinanceAdmin::where('finance_file_id', $files->id)->get();
                $check_finance_admin_old = FinanceAdminOld::where('finance_file_id', $files->id)->get();
                $check_differents = Helper::check_diff_multi($check_finance_admin_old->toArray(), $check_finance_admin->toArray());
                $audit_fields_changed = "";
                if (count($check_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= "<li>Finance Admin : (";
                    $new_line = '';
                    foreach ($check_differents as $fad_key => $fad) {
                        if ($fad == null) {
                            $new_line .= 'data removed, ';
                        } else {
                            if (is_array($fad) && count($fad)) {
                                foreach ($fad as $fad_data_key => $fad_data) {
                                    if (!in_array($fad_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $fad_data_key);
                                        $new_line .= $name . '=' . $fad_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($fad)) {
                                    $name = str_replace("_", " ", $fad_key);
                                    $new_line .= $name . '=' . $fad . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#admin";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#admin";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File Admin";
                    $notify_data['module'] = "Finance File Admin";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileIncome()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefix = 'income_';

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceIncome::where('finance_file_id', $files->id)->get();

                if (count($currents) > 0) {
                    FinanceIncomeOld::where('finance_file_id', $files->id)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceIncomeOld::firstOrNew([
                            'finance_file_id' => $files->id,
                            'name' => $current->name
                        ]);
                        $old->tunggakan = $current->tunggakan;
                        $old->semasa = $current->semasa;
                        $old->hadapan = $current->hadapan;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }

                $remove = FinanceIncome::where('finance_file_id', $files->id)->delete();

                if ($remove) {
                    for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                        if (!empty($data[$prefix . 'name'][$i])) {
                            $finance = new FinanceIncome;
                            $finance->finance_file_id = $files->id;
                            $finance->name = $data[$prefix . 'name'][$i];
                            $finance->tunggakan = $data[$prefix . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefix . 'semasa'][$i];
                            $finance->hadapan = $data[$prefix . 'hadapan'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefix . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                /** Arrange audit fields changes */
                $check_finance_income = FinanceIncome::where('finance_file_id', $files->id)->get();
                $check_finance_income_old = FinanceIncomeOld::where('finance_file_id', $files->id)->get();
                $check_differents = Helper::check_diff_multi($check_finance_income_old->toArray(), $check_finance_income->toArray());
                $audit_fields_changed = "";
                if (count($check_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= "<li>Finance Income : (";
                    $new_line = '';
                    foreach ($check_differents as $fid_key => $fid) {
                        if ($fid == null) {
                            $new_line .= $new_line ? ', data removed' : 'data_removed, ';
                        } else {
                            if (is_array($fid) && count($fid)) {
                                foreach ($fid as $fid_data_key => $fid_data) {
                                    if (!in_array($fid_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $fid_data_key);
                                        $new_line .= $name . '=' . $fid_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($fid)) {
                                    $name = str_replace("_", " ", $fid_key);
                                    $new_line .= $name . '=' . $fid . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#income";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#income";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File Income";
                    $notify_data['module'] = "Finance File Income";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileUtility()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefixs = [
                'util_',
                'utilb_',
            ];

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceUtility::where('finance_file_id', $files->id)->get();

                if (count($currents) > 0) {
                    FinanceUtilityOld::where('finance_file_id', $files->id)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceUtilityOld::firstOrNew([
                            'finance_file_id' => $files->id,
                            'type' => $current->type,
                            'name' => $current->name
                        ]);
                        $old->tunggakan = $current->tunggakan;
                        $old->semasa = $current->semasa;
                        $old->hadapan = $current->hadapan;
                        $old->tertunggak = $current->tertunggak;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }

                $remove = FinanceUtility::where('finance_file_id', $files->id)->delete();

                if ($remove) {
                    foreach ($prefixs as $prefix) {
                        for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                            if (!empty($data[$prefix . 'name'][$i])) {
                                $finance = new FinanceUtility;
                                $finance->finance_file_id = $files->id;
                                $finance->name = $data[$prefix . 'name'][$i];
                                if ($prefix == 'util_') {
                                    $finance->type = 'BHG_A';
                                } else {
                                    $finance->type = 'BHG_B';
                                }
                                $finance->tunggakan = $data[$prefix . 'tunggakan'][$i];
                                $finance->semasa = $data[$prefix . 'semasa'][$i];
                                $finance->hadapan = $data[$prefix . 'hadapan'][$i];
                                $finance->tertunggak = $data[$prefix . 'tertunggak'][$i];
                                $finance->sort_no = $i;
                                $finance->is_custom = $data[$prefix . 'is_custom'][$i];
                                $finance->save();
                            }
                        }
                    }
                }

                /** Arrange audit fields changes */
                $check_finance_utility = FinanceUtility::where('finance_file_id', $files->id)->get();
                $check_finance_utility_old = FinanceUtilityOld::where('finance_file_id', $files->id)->get();
                $check_differents = Helper::check_diff_multi($check_finance_utility_old->toArray(), $check_finance_utility->toArray());
                $audit_fields_changed = "";
                if (count($check_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= "<li>Finance Utility : (";
                    $new_line = '';
                    foreach ($check_differents as $fud_key => $fud) {
                        if ($fud == null) {
                            $new_line .= $new_line ? ', data removed' : 'data_removed, ';
                        } else {
                            if (is_array($fud) && count($fud)) {
                                foreach ($fud as $fud_data_key => $fud_data) {
                                    if (!in_array($fud_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $fud_data_key);
                                        $new_line .= $name . '=' . $fud_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($fud)) {
                                    $name = str_replace("_", " ", $fud_key);
                                    $new_line .= $name . '=' . $fud . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#utility";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#utility";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File Utility";
                    $notify_data['module'] = "Finance File Utility";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileVandal()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefixs = [
                'maintenancefee_',
                'singkingfund_'
            ];

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceVandal::where('finance_file_id', $files->id)->get();

                if (count($currents) > 0) {
                    FinanceVandalOld::where('finance_file_id', $files->id)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceVandalOld::firstOrNew([
                            'finance_file_id' => $files->id,
                            'type' => $current->type,
                            'name' => $current->name
                        ]);
                        $old->tunggakan = $current->tunggakan;
                        $old->semasa = $current->semasa;
                        $old->hadapan = $current->hadapan;
                        $old->tertunggak = $current->tertunggak;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }

                $remove = FinanceVandal::where('finance_file_id', $files->id)->delete();

                if ($remove) {
                    foreach ($prefixs as $prefix) {
                        for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                            if (!empty($data[$prefix . 'name'][$i])) {
                                $finance = new FinanceVandal;
                                $finance->finance_file_id = $files->id;
                                $finance->name = $data[$prefix . 'name'][$i];
                                if ($prefix == 'maintenancefee_') {
                                    $finance->type = 'MF';
                                } else {
                                    $finance->type = 'SF';
                                }
                                $finance->tunggakan = $data[$prefix . 'tunggakan'][$i];
                                $finance->semasa = $data[$prefix . 'semasa'][$i];
                                $finance->hadapan = $data[$prefix . 'hadapan'][$i];
                                $finance->tertunggak = $data[$prefix . 'tertunggak'][$i];
                                $finance->sort_no = $i;
                                $finance->is_custom = $data[$prefix . 'is_custom'][$i];
                                $finance->save();
                            }
                        }
                    }
                }

                /** Arrange audit fields changes */
                $check_finance_vandal = FinanceVandal::where('finance_file_id', $files->id)->get();
                $check_finance_vandal_old = FinanceVandalOld::where('finance_file_id', $files->id)->get();
                $check_differents = Helper::check_diff_multi($check_finance_vandal_old->toArray(), $check_finance_vandal->toArray());
                $audit_fields_changed = "";
                if (count($check_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= "<li>Finance Vandalisme : (";
                    $new_line = '';
                    foreach ($check_differents as $fvd_key => $fvd) {
                        if ($fvd == null) {
                            $new_line .= 'data removed, ';
                        } else {
                            if (is_array($fvd) && count($fvd)) {
                                foreach ($fvd as $fvd_data_key => $fvd_data) {
                                    if (!in_array($fvd_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $fvd_data_key);
                                        $new_line .= $name . '=' . $fvd_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($fvd)) {
                                    $name = str_replace("_", " ", $fvd_key);
                                    $new_line .= $name . '=' . $fvd . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#vandalisme";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#vandalisme";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File Vandalisme";
                    $notify_data['module'] = "Finance File Vandalisme";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileRepair()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefixs = [
                'repair_maintenancefee_',
                'repair_singkingfund_'
            ];

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceRepair::where('finance_file_id', $files->id)->get();

                if (count($currents) > 0) {
                    FinanceRepairOld::where('finance_file_id', $files->id)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceRepairOld::firstOrNew([
                            'finance_file_id' => $files->id,
                            'type' => $current->type,
                            'name' => $current->name
                        ]);
                        $old->tunggakan = $current->tunggakan;
                        $old->semasa = $current->semasa;
                        $old->hadapan = $current->hadapan;
                        $old->tertunggak = $current->tertunggak;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }

                $remove = FinanceRepair::where('finance_file_id', $files->id)->delete();

                if ($remove) {
                    foreach ($prefixs as $prefix) {
                        for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                            if (!empty($data[$prefix . 'name'][$i])) {
                                $finance = new FinanceRepair;
                                $finance->finance_file_id = $files->id;
                                $finance->name = $data[$prefix . 'name'][$i];
                                if ($prefix == 'repair_maintenancefee_') {
                                    $finance->type = 'MF';
                                } else {
                                    $finance->type = 'SF';
                                }
                                $finance->tunggakan = $data[$prefix . 'tunggakan'][$i];
                                $finance->semasa = $data[$prefix . 'semasa'][$i];
                                $finance->hadapan = $data[$prefix . 'hadapan'][$i];
                                $finance->tertunggak = $data[$prefix . 'tertunggak'][$i];
                                $finance->sort_no = $i;
                                $finance->is_custom = $data[$prefix . 'is_custom'][$i];
                                $finance->save();
                            }
                        }
                    }
                }

                /** Arrange audit fields changes */
                $check_finance_repair = FinanceRepair::where('finance_file_id', $files->id)->get();
                $check_finance_repair_old = FinanceRepairOld::where('finance_file_id', $files->id)->get();
                $check_differents = Helper::check_diff_multi($check_finance_repair_old->toArray(), $check_finance_repair->toArray());
                $audit_fields_changed = "";
                if (count($check_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= "<li>Finance Repair : (";
                    $new_line = '';
                    foreach ($check_differents as $frd_key => $frd) {
                        if ($frd == null) {
                            $new_line .= 'data removed, ';
                        } else {
                            if (is_array($frd) && count($frd)) {
                                foreach ($frd as $frd_data_key => $frd_data) {
                                    if (!in_array($frd_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $frd_data_key);
                                        $new_line .= $name . '=' . $frd_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($frd)) {
                                    $name = str_replace("_", " ", $frd_key);
                                    $new_line .= $name . '=' . $frd . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#repair";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#repair";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File Repair";
                    $notify_data['module'] = "Finance File Repair";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileContract()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefix = 'contract_';

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceContract::where('finance_file_id', $files->id)->get();

                if (count($currents) > 0) {
                    FinanceContractOld::where('finance_file_id', $files->id)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceContractOld::firstOrNew([
                            'finance_file_id' => $files->id,
                            'name' => $current->name
                        ]);
                        $old->tunggakan = $current->tunggakan;
                        $old->semasa = $current->semasa;
                        $old->hadapan = $current->hadapan;
                        $old->tertunggak = $current->tertunggak;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }

                $remove = FinanceContract::where('finance_file_id', $files->id)->delete();

                if ($remove) {
                    for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                        if (!empty($data[$prefix . 'name'][$i])) {
                            $finance = new FinanceContract;
                            $finance->finance_file_id = $files->id;
                            $finance->name = $data[$prefix . 'name'][$i];
                            $finance->tunggakan = $data[$prefix . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefix . 'semasa'][$i];
                            $finance->hadapan = $data[$prefix . 'hadapan'][$i];
                            $finance->tertunggak = $data[$prefix . 'tertunggak'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefix . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                /** Arrange audit fields changes */
                $check_finance_contract = FinanceContract::where('finance_file_id', $files->id)->get();
                $check_finance_contract_old = FinanceContractOld::where('finance_file_id', $files->id)->get();
                $check_differents = Helper::check_diff_multi($check_finance_contract_old->toArray(), $check_finance_contract->toArray());
                $audit_fields_changed = "";
                if (count($check_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= "<li>Finance Contract : (";
                    $new_line = '';
                    foreach ($check_differents as $fcd_key => $fcd) {
                        if ($fcd == null) {
                            $new_line .= !empty($new_line) ? ', data removed' : 'data removed, ';
                        } else {
                            if (is_array($fcd) && count($fcd)) {
                                foreach ($fcd as $fcd_data_key => $fcd_data) {
                                    if (!in_array($fcd_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $fcd_data_key);
                                        $new_line .= $name . '=' . $fcd_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($fcd)) {
                                    $name = str_replace("_", " ", $fcd_key);
                                    $new_line .= $name . '=' . $fcd . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#contractexp";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#contractexp";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File Contract";
                    $notify_data['module'] = "Finance File Contract";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileStaff()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);
            $prefix = 'staff_';

            $files = Finance::findOrFail($id);
            if ($files) {
                $currents = FinanceStaff::where('finance_file_id', $files->id)->get();

                if (count($currents) > 0) {
                    FinanceStaffOld::where('finance_file_id', $files->id)->delete();
                    foreach ($currents as $current) {
                        $old = FinanceStaffOld::firstOrNew([
                            'finance_file_id' => $files->id,
                            'name' => $current->name
                        ]);
                        $old->gaji_per_orang = $current->gaji_per_orang;
                        $old->bil_pekerja = $current->bil_pekerja;
                        $old->tunggakan = $current->tunggakan;
                        $old->semasa = $current->semasa;
                        $old->hadapan = $current->hadapan;
                        $old->tertunggak = $current->tertunggak;
                        $old->sort_no = $current->sort_no;
                        $old->is_custom = $current->is_custom;
                        $old->save();
                    }
                }

                $remove = FinanceStaff::where('finance_file_id', $files->id)->delete();

                if ($remove) {
                    for ($i = 0; $i < count($data[$prefix . 'name']); $i++) {
                        if (!empty($data[$prefix . 'name'][$i])) {
                            $finance = new FinanceStaff;
                            $finance->finance_file_id = $files->id;
                            $finance->name = $data[$prefix . 'name'][$i];
                            $finance->gaji_per_orang = $data[$prefix . 'gaji_per_orang'][$i];
                            $finance->bil_pekerja = $data[$prefix . 'bil_pekerja'][$i];
                            $finance->tunggakan = $data[$prefix . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefix . 'semasa'][$i];
                            $finance->hadapan = $data[$prefix . 'hadapan'][$i];
                            $finance->tertunggak = $data[$prefix . 'tertunggak'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefix . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                /** Arrange audit fields changes */
                $check_finance_staff = FinanceStaff::where('finance_file_id', $files->id)->get();
                $check_finance_staff_old = FinanceStaffOld::where('finance_file_id', $files->id)->get();
                $check_differents = Helper::check_diff_multi($check_finance_staff_old->toArray(), $check_finance_staff->toArray());
                $audit_fields_changed = "";
                if (count($check_differents)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= "<li>Finance Staff : (";
                    $new_line = '';
                    foreach ($check_differents as $fsd_key => $fsd) {
                        if ($fsd == null) {
                            $new_line .= 'data removed, ';
                        } else {
                            if (is_array($fsd) && count($fsd)) {
                                foreach ($fsd as $fsd_data_key => $fsd_data) {
                                    if (!in_array($fsd_data_key, ['sort_no', 'id', 'updated_at'])) {
                                        $name = str_replace("_", " ", $fsd_data_key);
                                        $new_line .= $name . '=' . $fsd_data . ', ';
                                    }
                                }
                            } else {
                                if (!empty($fsd)) {
                                    $name = str_replace("_", " ", $fsd_key);
                                    $new_line .= $name . '=' . $fsd . ', ';
                                }
                            }
                        }
                    }
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->file_id, "COB Finance File", $remarks);
                }
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $files->file->strata;
                    $notify_data['file_id'] = $files->file->id;
                    $notify_data['route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#staff";
                    $notify_data['cob_route'] = route('finance_file.edit', ['id' => Helper::encode($files->id)]) . "#staff";
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file->file_no;
                    $notify_data['title'] = "Finance File Staff";
                    $notify_data['module'] = "Finance File Staff";

                    (new NotificationService())->store($notify_data);
                }

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFile()
    {
        $data = Input::all();

        if (Request::ajax()) {
            $id = Helper::decode($data['finance_file_id']);

            $files = Finance::findOrFail($id);
            if ($files) {
                /*
                 * CHECK
                 */
                $financeCheck = FinanceCheck::where('finance_file_id', $id)->first();
                if ($financeCheck) {
                    $financeCheck->date = $data['date'];
                    $financeCheck->name = $data['name'];
                    $financeCheck->position = $data['position'];
                    $financeCheck->is_active = $data['is_active'];
                    $financeCheck->remarks = $data['remarks'];
                    $financeCheck->save();
                }

                /*
                 * SUMMARY
                 */
                $prefixSummary = 'sum_';
                $removeSummary = FinanceSummary::where('finance_file_id', $id)->delete();
                if ($removeSummary) {
                    for ($i = 0; $i < count($data[$prefixSummary . 'name']); $i++) {
                        if (!empty($data[$prefixSummary . 'name'][$i])) {
                            $finance = new FinanceSummary;
                            $finance->finance_file_id = $id;
                            $finance->name = $data[$prefixSummary . 'name'][$i];
                            $finance->summary_key = $data[$prefixSummary . 'summary_key'][$i];
                            $finance->amount = $data[$prefixSummary . 'amount'][$i];
                            $finance->sort_no = $i;
                            $finance->save();
                        }
                    }
                }

                /*
                 * MF REPORT
                 */
                $typeMF = 'MF';
                $prefixMF = 'mfr_';
                $removeMF = FinanceReportPerbelanjaan::where('finance_file_id', $id)->where('type', $typeMF)->delete();
                $finance = FinanceReport::where('finance_file_id', $id)->where('type', $typeMF)->first();
                if ($removeMF) {
                    $finance->fee_sebulan = $data[$prefixMF . 'fee_sebulan'];
                    $finance->unit = $data[$prefixMF . 'unit'];
                    $finance->fee_semasa = $data[$prefixMF . 'fee_semasa'];
                    $finance->no_akaun = $data[$prefixMF . 'no_akaun'];
                    $finance->nama_bank = $data[$prefixMF . 'nama_bank'];
                    $finance->baki_bank_awal = $data[$prefixMF . 'baki_bank_awal'];
                    $finance->baki_bank_akhir = $data[$prefixMF . 'baki_bank_akhir'];
                    $finance->save();

                    for ($i = 0; $i < count($data[$prefixMF . 'name']); $i++) {
                        if (!empty($data[$prefixMF . 'name'][$i])) {
                            $frp = new FinanceReportPerbelanjaan;
                            $frp->type = $typeMF;
                            $frp->finance_file_id = $id;
                            $frp->name = $data[$prefixMF . 'name'][$i];
                            $frp->report_key = $data[$prefixMF . 'report_key'][$i];
                            $frp->amount = $data[$prefixMF . 'amount'][$i];
                            $frp->sort_no = $i;
                            $frp->is_custom = 0;
                            $frp->save();
                        }
                    }
                }

                /*
                 * SF REPORT
                 */
                $typeSF = 'SF';
                $prefixSF = 'sfr_';
                $removeSF = FinanceReportPerbelanjaan::where('finance_file_id', $id)->where('type', $typeSF)->delete();
                if ($removeSF) {
                    $finance = FinanceReport::where('finance_file_id', $id)->where('type', $typeSF)->first();
                    if ($finance) {
                        $finance->fee_sebulan = $data[$prefixSF . 'fee_sebulan'];
                        $finance->unit = $data[$prefixSF . 'unit'];
                        $finance->fee_semasa = $data[$prefixSF . 'fee_semasa'];
                        $finance->no_akaun = $data[$prefixSF . 'no_akaun'];
                        $finance->nama_bank = $data[$prefixSF . 'nama_bank'];
                        $finance->baki_bank_awal = $data[$prefixSF . 'baki_bank_awal'];
                        $finance->baki_bank_akhir = $data[$prefixSF . 'baki_bank_akhir'];
                        $finance->save();

                        for ($i = 0; $i < count($data[$prefixSF . 'name']); $i++) {
                            if (!empty($data[$prefixSF . 'name'][$i])) {
                                $frp = new FinanceReportPerbelanjaan;
                                $frp->type = $typeSF;
                                $frp->finance_file_id = $id;
                                $frp->name = $data[$prefixSF . 'name'][$i];
                                $frp->report_key = $data[$prefixSF . 'report_key'][$i];
                                $frp->amount = $data[$prefixSF . 'amount'][$i];
                                $frp->sort_no = $i;
                                $frp->is_custom = $data[$prefixSF . 'is_custom'][$i];
                                $frp->save();
                            }
                        }
                    }
                }

                /*
                 * INCOME
                 */
                $prefixIncome = 'income_';
                $removeIncome = FinanceIncome::where('finance_file_id', $id)->delete();
                if ($removeIncome) {
                    for ($i = 0; $i < count($data[$prefixIncome . 'name']); $i++) {
                        if (!empty($data[$prefixIncome . 'name'][$i])) {
                            $finance = new FinanceIncome;
                            $finance->finance_file_id = $id;
                            $finance->name = $data[$prefixIncome . 'name'][$i];
                            $finance->tunggakan = $data[$prefixIncome . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefixIncome . 'semasa'][$i];
                            $finance->hadapan = $data[$prefixIncome . 'hadapan'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefixIncome . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                /*
                 * UTILITY
                 */
                $prefixUtilities = [
                    'util_',
                    'utilb_',
                ];
                $removeUtility = FinanceUtility::where('finance_file_id', $id)->delete();
                if ($removeUtility) {
                    foreach ($prefixUtilities as $prefixUtility) {
                        for ($i = 0; $i < count($data[$prefixUtility . 'name']); $i++) {
                            if (!empty($data[$prefixUtility . 'name'][$i])) {
                                $finance = new FinanceUtility;
                                $finance->finance_file_id = $id;
                                $finance->name = $data[$prefixUtility . 'name'][$i];
                                if ($prefixUtility == 'util_') {
                                    $finance->type = 'BHG_A';
                                } else {
                                    $finance->type = 'BHG_B';
                                }
                                $finance->tunggakan = $data[$prefixUtility . 'tunggakan'][$i];
                                $finance->semasa = $data[$prefixUtility . 'semasa'][$i];
                                $finance->hadapan = $data[$prefixUtility . 'hadapan'][$i];
                                $finance->tertunggak = $data[$prefixUtility . 'tertunggak'][$i];
                                $finance->sort_no = $i;
                                $finance->is_custom = $data[$prefixUtility . 'is_custom'][$i];
                                $finance->save();
                            }
                        }
                    }
                }

                /*
                 * CONTRACT
                 */
                $prefixContract = 'contract_';
                $removeContract = FinanceContract::where('finance_file_id', $id)->delete();
                if ($removeContract) {
                    for ($i = 0; $i < count($data[$prefixContract . 'name']); $i++) {
                        if (!empty($data[$prefixContract . 'name'][$i])) {
                            $finance = new FinanceContract();
                            $finance->finance_file_id = $id;
                            $finance->name = $data[$prefixContract . 'name'][$i];
                            $finance->tunggakan = $data[$prefixContract . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefixContract . 'semasa'][$i];
                            $finance->hadapan = $data[$prefixContract . 'hadapan'][$i];
                            $finance->tertunggak = $data[$prefixContract . 'tertunggak'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefixContract . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                /*
                 * REPAIR
                 */
                $prefixRepairs = [
                    'repair_maintenancefee_',
                    'repair_singkingfund_'
                ];
                $removeRepair = FinanceRepair::where('finance_file_id', $id)->delete();
                if ($removeRepair) {
                    foreach ($prefixRepairs as $prefixRepair) {
                        for ($i = 0; $i < count($data[$prefixRepair . 'name']); $i++) {
                            if (!empty($data[$prefixRepair . 'name'][$i])) {
                                $finance = new FinanceRepair();
                                $finance->finance_file_id = $id;
                                $finance->name = $data[$prefixRepair . 'name'][$i];
                                if ($prefixRepair == 'repair_maintenancefee_') {
                                    $finance->type = 'MF';
                                } else {
                                    $finance->type = 'SF';
                                }
                                $finance->tunggakan = $data[$prefixRepair . 'tunggakan'][$i];
                                $finance->semasa = $data[$prefixRepair . 'semasa'][$i];
                                $finance->hadapan = $data[$prefixRepair . 'hadapan'][$i];
                                $finance->tertunggak = $data[$prefixRepair . 'tertunggak'][$i];
                                $finance->sort_no = $i;
                                $finance->is_custom = $data[$prefixRepair . 'is_custom'][$i];
                                $finance->save();
                            }
                        }
                    }
                }

                /*
                 * VANDALISME
                 */
                $prefixVandals = [
                    'maintenancefee_',
                    'singkingfund_'
                ];
                $removeVandal = FinanceVandal::where('finance_file_id', $id)->delete();
                if ($removeVandal) {
                    foreach ($prefixVandals as $prefixVandal) {
                        for ($i = 0; $i < count($data[$prefixVandal . 'name']); $i++) {
                            if (!empty($data[$prefixVandal . 'name'][$i])) {
                                $finance = new FinanceVandal();
                                $finance->finance_file_id = $id;
                                $finance->name = $data[$prefixVandal . 'name'][$i];
                                if ($prefixVandal == 'maintenancefee_') {
                                    $finance->type = 'MF';
                                } else {
                                    $finance->type = 'SF';
                                }
                                $finance->tunggakan = $data[$prefixVandal . 'tunggakan'][$i];
                                $finance->semasa = $data[$prefixVandal . 'semasa'][$i];
                                $finance->hadapan = $data[$prefixVandal . 'hadapan'][$i];
                                $finance->tertunggak = $data[$prefixVandal . 'tertunggak'][$i];
                                $finance->sort_no = $i;
                                $finance->is_custom = $data[$prefixVandal . 'is_custom'][$i];
                                $finance->save();
                            }
                        }
                    }
                }

                /*
                 * STAFF
                 */
                $prefixStaff = 'staff_';
                $removeStaff = FinanceStaff::where('finance_file_id', $id)->delete();
                if ($removeStaff) {
                    for ($i = 0; $i < count($data[$prefixStaff . 'name']); $i++) {
                        if (!empty($data[$prefixStaff . 'name'][$i])) {
                            $finance = new FinanceStaff();
                            $finance->finance_file_id = $id;
                            $finance->name = $data[$prefixStaff . 'name'][$i];
                            $finance->gaji_per_orang = $data[$prefixStaff . 'gaji_per_orang'][$i];
                            $finance->bil_pekerja = $data[$prefixStaff . 'bil_pekerja'][$i];
                            $finance->tunggakan = $data[$prefixStaff . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefixStaff . 'semasa'][$i];
                            $finance->hadapan = $data[$prefixStaff . 'hadapan'][$i];
                            $finance->tertunggak = $data[$prefixStaff . 'tertunggak'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefixStaff . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                /*
                 * ADMIN
                 */
                $prefixAdmin = 'admin_';
                $removeAdmin = FinanceAdmin::where('finance_file_id', $id)->delete();
                if ($removeAdmin) {
                    for ($i = 0; $i < count($data[$prefixAdmin . 'name']); $i++) {
                        if (!empty($data[$prefixAdmin . 'name'][$i])) {
                            $finance = new FinanceAdmin();
                            $finance->finance_file_id = $id;
                            $finance->name = $data[$prefixAdmin . 'name'][$i];
                            $finance->tunggakan = $data[$prefixAdmin . 'tunggakan'][$i];
                            $finance->semasa = $data[$prefixAdmin . 'semasa'][$i];
                            $finance->hadapan = $data[$prefixAdmin . 'hadapan'][$i];
                            $finance->tertunggak = $data[$prefixAdmin . 'tertunggak'][$i];
                            $finance->sort_no = $i;
                            $finance->is_custom = $data[$prefixAdmin . 'is_custom'][$i];
                            $finance->save();
                        }
                    }
                }

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' all data' . $this->module['audit']['text']['data_updated'];
                $this->addAudit($files->file_id, "COB Finance File", $remarks);

                return "true";
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }

    public function financeSupport()
    {
        //get user permission

        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.finance.finance_support'),
            'panel_nav_active' => 'finance_panel',
            'main_nav_active' => 'finance_main',
            'sub_nav_active' => 'finance_support_list',
            'user_permission' => $user_permission,
            'file' => $file,
            'cob' => $cob,
            'image' => ""
        );

        return View::make('finance_en.finance_support_list', $viewData);
    }

    public function getFinanceSupportList()
    {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $filelist = FinanceSupport::where('file_id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            } else {
                $filelist = FinanceSupport::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $filelist = FinanceSupport::where('is_deleted', 0)->orderBy('id', 'desc')->get();
            } else {
                $filelist = FinanceSupport::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }
        }



        if (count($filelist) > 0) {
            $data = array();
            foreach ($filelist as $filelists) {
                $files = Files::where('id', $filelists->file_id)->first();
                if ($files) {
                    $button = "";
                    $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFinanceSupport(\'' . Helper::encode($filelists->id) . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>&nbsp;';

                    $data_raw = array(
                        ($files->company ? $files->company->short_name : ''),
                        "<a style='text-decoration:underline;' href='" . URL::action('FinanceController@editFinanceSupport', Helper::encode($filelists->id)) . "'>" . (!empty($files) ? $files->file_no : '-') . "</a>",
                        ($files->strata ? $files->strata->strataName() : ''),
                        date('d/m/Y', strtotime($filelists->date)),
                        $filelists->name,
                        number_format($filelists->amount, 2),
                        $button
                    );

                    array_push($data, $data_raw);
                }
            }
            $output_raw = array(
                "aaData" => $data
            );

            $output = json_encode($output_raw);
            return $output;
        } else {
            $output_raw = array(
                "aaData" => []
            );

            $output = json_encode($output_raw);
            return $output;
        }
    }

    public function addFinanceSupport()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file_no = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file_no = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file_no = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file_no = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(39));

        $viewData = array(
            'title' => trans('app.menus.finance.add_finance_support'),
            'panel_nav_active' => 'finance_panel',
            'main_nav_active' => 'finance_main',
            'sub_nav_active' => 'finance_support_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no
        );

        return View::make('finance_en.add_finance_support', $viewData);
    }

    public function submitFinanceSupport()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = $data['file_id'];
            $is_active = $data['is_active'];

            $files = Files::find($file_id);
            if ($files) {
                $finance = new FinanceSupport();
                $finance->file_id = $files->id;
                $finance->company_id = $files->company_id;
                $finance->date = $data['date'];
                $finance->name = $data['name'];
                $finance->amount = $data['amount'];
                $finance->remark = $data['remark'];
                $finance->is_active = $is_active;
                $success = $finance->save();

                if ($success) {
                    # Audit Trail
                    $remarks = 'Finance Support : ' . $finance->name . $this->module['audit']['text']['data_inserted'];
                    $this->addAudit($finance->file_id, "COB Finance Support", $remarks);

                    if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                        /**
                         * Add Notification & send email to COB and JMB
                         */
                        $not_draft_strata = $finance->file->strata;
                        $notify_data['file_id'] = $finance->file->id;
                        $notify_data['route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                        $notify_data['cob_route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                        $notify_data['strata'] = "You";
                        $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance->file->file_no;
                        $notify_data['title'] = "COB File Finance Support";
                        $notify_data['module'] = "Finance Support";

                        (new NotificationService())->store($notify_data);
                    }

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function editFinanceSupport($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file_no = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file_no = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file_no = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $file_no = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }
        $financeSupportData = FinanceSupport::where('id', Helper::decode($id))->firstOrFail();
        $disallow = Helper::isAllow($financeSupportData->file_id, $financeSupportData->company_id, !AccessGroup::hasUpdate(39));

        $viewData = array(
            'title' => trans('app.menus.finance.edit_finance_support'),
            'panel_nav_active' => 'finance_panel',
            'main_nav_active' => 'finance_main',
            'sub_nav_active' => 'finance_support_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no,
            'financesupportdata' => $financeSupportData
        );

        return View::make('finance_en.edit_finance_support', $viewData);
    }

    public function updateFinanceSupport()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = $data['file_id'];
            $id = Helper::decode($data['id']);

            $files = Files::find($file_id);
            if ($files) {
                $finance = FinanceSupport::findOrFail($id);
                if ($finance) {
                    /** Arrange audit fields changes */
                    $name_field = $data['name'] == $finance->name ? "" : "name";
                    $date_field = $data['date'] == $finance->date ? "" : "date";
                    $amount_field = $data['amount'] == $finance->amount ? "" : "amont";
                    $remark_field = $data['remark'] == $finance->remark ? "" : "remark";

                    $audit_fields_changed = "";
                    if (!empty($name_field) || !empty($remark_field) || !empty($date_field) || !empty($amount_field)) {
                        $audit_fields_changed .= "<br><ul>";
                        $audit_fields_changed .= !empty($name_field) ? "<li>$name_field</li>" : "";
                        $audit_fields_changed .= !empty($date_field) ? "<li>$date_field</li>" : "";
                        $audit_fields_changed .= !empty($remark_field) ? "<li>$remark_field</li>" : "";
                        $audit_fields_changed .= !empty($amount_field) ? "<li>$amount_field</li>" : "";
                        $audit_fields_changed .= "</ul>";
                    }
                    /** End Arrange audit fields changes */

                    $finance->file_id = $files->id;
                    $finance->company_id = $files->company_id;
                    $finance->date = $data['date'];
                    $finance->name = $data['name'];
                    $finance->amount = $data['amount'];
                    $finance->remark = $data['remark'];
                    $finance->is_active = 1;
                    $success = $finance->save();

                    if ($success) {
                        # Audit Trail
                        if (!empty($audit_fields_changed)) {
                            $remarks = 'Finance Support : ' . $finance->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                            $this->addAudit($finance->file_id, "COB Finance Support", $remarks);
                        }

                        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                            /**
                             * Add Notification & send email to COB and JMB
                             */
                            $not_draft_strata = $finance->file->strata;
                            $notify_data['file_id'] = $finance->file->id;
                            $notify_data['route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                            $notify_data['cob_route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                            $notify_data['strata'] = "You";
                            $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance->file->file_no;
                            $notify_data['title'] = "COB File Finance Support";
                            $notify_data['module'] = "Finance Support";

                            (new NotificationService())->store($notify_data);
                        }

                        print "true";
                    } else {
                        print "false";
                    }
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function activeFinanceList()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $finance_file = Finance::findOrFail($id);
            $finance_file->is_active = 1;
            $updated = $finance_file->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Finance File : ' . $finance_file->file->file_no . " " . $finance_file->year . "-" . strtoupper($finance_file->monthName()) . $this->module['audit']['text']['status_active'];
                $this->addAudit($finance_file->file_id, "COB Finance File", $remarks);

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

                    (new NotificationService())->store($notify_data, 'status updated');
                }
                print "true";
            } else {
                print "false";
            }
        }
    }

    public function inactiveFinanceList()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $finance_file = Finance::findOrFail($id);
            $finance_file->is_active = 0;
            $updated = $finance_file->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Finance File : ' . $finance_file->file->file_no . " " . $finance_file->year . "-" . strtoupper($finance_file->monthName()) . $this->module['audit']['text']['status_inactive'];
                $this->addAudit($finance_file->file_id, "COB Finance File", $remarks);

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

                    (new NotificationService())->store($notify_data, 'status updated');
                }
                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteFinanceSupport()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $finance_support = FinanceSupport::findOrFail($id);
            $finance_support->is_deleted = 1;
            $deleted = $finance_support->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Finance Support : ' . $finance_support->name . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($finance_support->file_id, "COB Finance Support", $remarks);

                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $finance_support->file->strata;
                    $notify_data['file_id'] = $finance_support->file->id;
                    $notify_data['route'] = route('finance_support.index');
                    $notify_data['cob_route'] = route('finance_support.index');
                    $notify_data['strata'] = "your";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance_support->file->file_no;
                    $notify_data['title'] = "COB File Finance Support";
                    $notify_data['module'] = "Finance Support";

                    (new NotificationService())->store($notify_data, 'deleted');
                }

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateFinanceFileList()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = Helper::decode($data['file_id']);
            $is_active = $data['is_active'];

            $id = Helper::decode($data['id']);

            $finance_support = FinanceSupport::findOrFail($id);
            /** Arrange audit fields changes */
            $name_field = $data['name'] == $finance_support->name ? "" : "name";
            $date_field = $data['date'] == $finance_support->date ? "" : "date";
            $amount_field = $data['amount'] == $finance_support->amount ? "" : "amount";
            $is_active_field = $data['is_active'] == $finance_support->is_active ? "" : "status";
            $remarks_field = $data['remarks'] == $finance_support->remarks ? "" : "remarks";

            $audit_fields_changed = "";
            if (!empty($name_field) || !empty($is_active_field) || !empty($date_field) || !empty($amount_field) || !empty($remarks_field)) {
                $audit_fields_changed .= "<br><ul>";
                $audit_fields_changed .= !empty($name_field) ? "<li>$name_field</li>" : "";
                $audit_fields_changed .= !empty($date_field) ? "<li>$date_field</li>" : "";
                $audit_fields_changed .= !empty($is_active_field) ? "<li>$is_active_field</li>" : "";
                $audit_fields_changed .= !empty($amount_field) ? "<li>$amount_field</li>" : "";
                $audit_fields_changed .= "</ul>";
            }
            /** End Arrange audit fields changes */

            $finance_support->file_id = $file_id;
            $finance_support->date = $data['date'];
            $finance_support->name = $data['name'];
            $finance_support->amount = $data['amount'];
            $finance_support->remarks = $data['remarks'];
            $finance_support->is_active = $is_active;
            $success = $finance_support->save();

            if ($success) {
                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Finance Support : ' . $finance_support->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($finance_support->file_id, "COB Finance Support", $remarks);
                }

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function saveFinanceSummary($finance, $data_summary_amount)
    {
        $summary_keys = $this->module['finance']['tabs']['summary']['only'];
        $messages = Config::get('constant.others.messages');

        $currents = FinanceSummary::where('finance_file_id', $finance->id)->get();
        if (count($currents) > 0) {
            foreach ($currents as $current) {
                $old = FinanceSummaryOld::firstOrNew(array(
                    'finance_file_id' => $finance->id,
                    'summary_key' => $current->summary_key
                ));

                $old->name = $current->name;
                $old->amount = $current->amount;
                $old->sort_no = $current->sort_no;
                $old->save();
            }
        }
        $remove = FinanceSummary::where('finance_file_id', $finance->id)->delete();
        // if ($remove) {
        $i = 1;
        foreach ($summary_keys as $summary_key) {
            $finance_summary = new FinanceSummary;
            $finance_summary->finance_file_id = $finance->id;
            $finance_summary->name = $messages[$summary_key];
            $finance_summary->summary_key = $summary_key;
            $finance_summary->amount = $data_summary_amount[$summary_key];
            $finance_summary->sort_no = $i;
            $finance_summary->save();
            $i++;
        }
        // }
        # Audit Trail
        if (!empty($finance->file)) {
            $remarks = 'Finance File: ' . $finance->file->file_no . " " . $finance->year . "-" . strtoupper($finance->monthName()) . ' summary' . $this->module['audit']['text']['data_updated'];
            $this->addAudit($finance->file->id, "COB Finance File", $remarks);
        }
    }

    public function recalculateSummary()
    {
        if (!Auth::user()->getAdmin()) {
            App::abort(404);
        }
        $finance_latest = Finance::latest()->first();
        $finance_files = Finance::where('is_summary', false)->take(100)->get();
        $finance_file_id = 0;
        foreach ($finance_files as $finance) {
            $finance_file_summary = FinanceSummary::where('finance_file_id', $finance->id)->delete();
            $finance_file_summary_old = FinanceSummaryOld::where('finance_file_id', $finance->id)->delete();
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
            $finance_utility = $finance->financeUtility;
            if ($finance_utility) {
                foreach ($finance_utility as $utility) {
                    if (str_contains($utility->name, 'AIR')) {
                        $data_summary_amount['bill_air'] += ($utility->tunggakan + $utility->semasa + $utility->hadapan);
                    } else if (str_contains($utility->name, 'ELEKTRIK')) {
                        $data_summary_amount['bill_elektrik'] += ($utility->tunggakan + $utility->semasa + $utility->hadapan);
                    } else if (str_contains($utility->name, 'CUKAI TANAH')) {
                        $data_summary_amount['caruman_cukai'] += ($utility->tunggakan + $utility->semasa + $utility->hadapan);
                    } else {
                        $data_summary_amount['utility'] += ($utility->tunggakan + $utility->semasa + $utility->hadapan);
                    }
                }
            }
            $finance_contract = $finance->financeContract;
            if ($finance_contract) {
                foreach ($finance_contract as $contract) {
                    $data_summary_amount['contract'] += ($contract->tunggakan + $contract->semasa + $contract->hadapan);
                }
            }
            $finance_repair = $finance->financeRepair;
            if ($finance_repair) {
                foreach ($finance_repair as $repair) {
                    $data_summary_amount['repair'] += ($repair->tunggakan + $repair->semasa + $repair->hadapan);
                }
            }
            $finance_vandalisme = $finance->financeVandal;
            if ($finance_vandalisme) {
                foreach ($finance_vandalisme as $vandalisme) {
                    $data_summary_amount['vandalisme'] += ($vandalisme->tunggakan + $vandalisme->semasa + $vandalisme->hadapan);
                }
            }
            $finance_staff = $finance->financeStaff;
            if ($finance_staff) {
                foreach ($finance_staff as $staff) {
                    $data_summary_amount['staff'] += ($staff->gaji_per_orang * $staff->bil_pekerja);
                }
            }
            $finance_admin = $finance->financeAdmin;
            if ($finance_admin) {
                foreach ($finance_admin as $admin) {
                    $data_summary_amount['admin'] += ($admin->tunggakan + $admin->semasa + $admin->hadapan);
                }
            }
            $this->saveFinanceSummary($finance, $data_summary_amount);

            $finance->is_summary = true;
            $finance->save();

            $finance_file_id = $finance->id;
        }

        return 'Recalculate Summary done. Current Finance File Id : ' . $finance_file_id . " and the latest Finance File Id : " . $finance_latest->id;
    }
}
