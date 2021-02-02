<?php

class FinanceController extends BaseController {

    // add finance file list
    public function addFinanceFileList() {
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

        $viewData = array(
            'title' => trans('app.menus.cob.add_finance_file_list'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'add_finance_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no,
            'month' => Finance::monthList()
        );

        return View::make('finance_en.add_finance_file', $viewData);
    }

    public function submitAddFinanceFile() {
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

                    if ($success) {
                        /*
                         * create Check
                         */
                        $check = new FinanceCheck();
                        $check->finance_file_id = $finance->id;
                        $check->is_active = 1;
                        $createCheck = $check->save();

                        if ($createCheck) {
                            /*
                             * create Summary
                             */
                            $tableFieldSummary = [
                                'bill_air' => 'Bil Air',
                                'bill_elektrik' => 'Bil. Elektrik',
                                'caruman_insuran' => 'Caruman Insuran',
                                'caruman_cukai' => 'Caruman Cukai Tanah',
                                'fi_firma' => 'Fi Firma Kompeten Lif',
                                'pembersihan' => 'Pembersihan Termasuk potong rumput, lanskap, kutipan sampah pukal dan lain-lain',
                                'keselamatan' => 'Keselamatan Termasuk Sistem CCTV, Palang Automatik, Kad Akses, Alat Pemadam Api, Penggera Kebakaran dan lain-lain	',
                                'jurutera_elektrik' => 'Jurutera Elektrik',
                                'mechaninal' => 'Mechaninal & Electrical Termasuk semua kerja-kerja penyenggaraan/ pembaikan /penggantian/ pembelian lampu, pendawaian elektrik, wayar bumi, kelengkapan lif, substation TNB,Genset dan lain-lain',
                                'civil' => 'Civil & Structure Termasuk semua kerja-kerja penyenggaraan/ pembaikan /penggantian/ pembelian tangki air, bumbung, kolam renang, pembentung, perpaipan, tangga, pagar, longkang dan lain-lain',
                                'kawalan_serangga' => 'Kawalan Serangga',
                                'kos_pekerja' => 'Kos Pekerja',
                                'pentadbiran' => 'Pentadbiran Termasuk telefon, internet, alat tulis pejabat, petty cash, sewaan mesin fotokopi, fi audit, caj bank dan lain-lain',
                                'fi_ejen_pengurusan' => 'Fi Ejen Pengurusan',
                                'lain_lain' => 'Lain-Lain-sekiranya ada Termasuk sila senaraikan',
                            ];

                            $countSum = 1;
                            foreach ($tableFieldSummary as $key => $name) {
                                $summary = new FinanceSummary();
                                $summary->finance_file_id = $finance->id;
                                $summary->name = $name;
                                $summary->summary_key = $key;
                                $summary->amount = 0;
                                $summary->sort_no = $countSum;
                                $summary->save();

                                $countSum++;
                            }

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
                                    $reportMF->report_key = $key;
                                    $reportSF->amount = 0;
                                    $reportSF->sort_no = $counter;
                                    $reportSF->save();

                                    $counter++;
                                }
                            }

                            /*
                             * create Income
                             */
                            $tableFieldIncome = [
                                'MAINTENANCE FEE',
                                'SINKING FUND',
                                'INSURAN BANGUNAN',
                                'CUKAI TANAH',
                                'PELEKAT KENDERAAN',
                                'KAD AKSES',
                                'SEWAAN TLK',
                                'SEWAAN KEDAI',
                                'SEWAAN HARTA BERSAMA',
                                'DENDA UNDANG-UNDANG KECIL',
                                'DENDA LEWAT BAYAR MAINTENANCE FEE @ SINKING FUND',
                                'BIL METER AIR PEMILIK-PEMILIK(DI BAWAH AKAUN METER PUKAL SAHAJA)',
                            ];

                            foreach ($tableFieldIncome as $count => $name) {
                                $income = new FinanceIncome();
                                $income->finance_file_id = $finance->id;
                                $income->name = $name;
                                $income->tunggakan = 0;
                                $income->semasa = 0;
                                $income->hadapan = 0;
                                $income->sort_no = ++$count;
                                $income->save();
                            }

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
                        }

                        # Audit Trail
                        $remarks = 'Finance File with id : ' . $finance->id . ' has been created.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB Finance";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = Auth::user()->id;
                        $auditTrail->save();

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
    public function financeList() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        
        $user = Auth::user();
        
        if(!in_array($user->role,[1,2])) {
            $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'strata.id as strata_id'])
                    ->where('files.id', $user->file_id)
                    ->where('files.company_id', $user->company_id)
                    ->where('files.is_active', '!=', 2)
                    ->where('files.is_deleted', 0);
        } else {
            $file = Files::where('is_deleted', 0)->get();
        }
        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }
        $year = Files::getVPYear();
        
        $viewData = array(
            'title' => trans('app.menus.cob.finance_file_list'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
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

    public function getFinanceList() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['finance_file.*', 'strata.id as strata_id'])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_deleted', 0);
            } else {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['finance_file.*', 'strata.id as strata_id'])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['finance_file.*', 'strata.id as strata_id'])
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_deleted', 0);
            } else {
                $file = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['finance_file.*', 'strata.id as strata_id'])
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_deleted', 0);
            }
        }

        return Datatables::of($file)
                        ->addColumn('cob', function ($model) {
                            return ($model->file_id ? $model->file->company->short_name : '-');
                        })
                        ->editColumn('file_no', function ($model) {
                            return "<a style='text-decoration:underline;' href='" . URL::action('FinanceController@editFinanceFileList', $model->id) . "'>" . $model->file->file_no . " " . $model->year . "-" . strtoupper($model->monthName()) . "</a>";
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
                        ->addColumn('active', function ($model) {
                            if ($model->is_active == 1) {
                                $is_active = trans('app.forms.active');
                            } else {
                                $is_active = trans('app.forms.inactive');
                            }

                            return $is_active;
                        })
                        ->addColumn('action', function ($model) {
                            $button = '';
                            if (AccessGroup::hasUpdate(38)) {
                                if ($model->is_active == 1) {
                                    $status = trans('app.forms.active');
                                    $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveFinanceList(\'' . $model->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                                } else {
                                    $status = trans('app.forms.inactive');
                                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeFinanceList(\'' . $model->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                                }
                                $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFinanceList(\'' . $model->id . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>&nbsp;';
                            }

                            return $button;
                        })
                        ->make(true);
    }

    public function deleteFinanceList() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $finance = Finance::find($id);
            if ($finance) {
                $finance->is_active = 0;
                $finance->is_deleted = 1;
                $deleted = $finance->save();

                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Finance File with id : ' . $finance->id . ' has been deleted.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB Finance";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

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

    public function editFinanceFileList($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $file_no = Files::where('is_active', 1)->where('is_deleted', 0)->get();
        $financeCheckData = FinanceCheck::where('finance_file_id', $id)->first();
        $financeSummary = FinanceSummary::where('finance_file_id', $id)->orderBy('sort_no', 'asc')->get();
        $financefiledata = Finance::where('id', $id)->first();
        $financeFileAdmin = FinanceAdmin::where('finance_file_id', $id)->orderBy('sort_no', 'asc')->get();
        $financeFileContract = FinanceContract::where('finance_file_id', $id)->orderBy('sort_no', 'asc')->get();
        $financeFileStaff = FinanceStaff::where('finance_file_id', $id)->orderBy('sort_no', 'asc')->get();
        $financeFileVandalA = FinanceVandal::where('finance_file_id', $id)->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
        $financeFileVandalB = FinanceVandal::where('finance_file_id', $id)->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $financeFileRepairA = FinanceRepair::where('finance_file_id', $id)->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
        $financeFileRepairB = FinanceRepair::where('finance_file_id', $id)->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
        $financeFileUtilityA = FinanceUtility::where('finance_file_id', $id)->where('type', 'BHG_A')->orderBy('sort_no', 'asc')->get();
        $financeFileUtilityB = FinanceUtility::where('finance_file_id', $id)->where('type', 'BHG_B')->orderBy('sort_no', 'asc')->get();
        $financeFileIncome = FinanceIncome::where('finance_file_id', $id)->orderBy('sort_no', 'asc')->get();

        $mfreport = FinanceReport::where('finance_file_id', $id)->where('type', 'MF')->first();
        $reportMF = FinanceReportPerbelanjaan::where('finance_file_id', $id)->where('type', 'MF')->orderBy('sort_no', 'asc')->get();

        $sfreport = FinanceReport::where('finance_file_id', $id)->where('type', 'SF')->first();
        $reportSF = FinanceReportPerbelanjaan::where('finance_file_id', $id)->where('type', 'SF')->orderBy('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.edit_finance_file_list'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'finance_file_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no,
            'financefiledata' => $financefiledata,
            'checkdata' => $financeCheckData,
            'summary' => $financeSummary,
            'adminFile' => $financeFileAdmin,
            'contractFile' => $financeFileContract,
            'staffFile' => $financeFileStaff,
            'vandala' => $financeFileVandalA,
            'vandalb' => $financeFileVandalB,
            'repaira' => $financeFileRepairA,
            'repairb' => $financeFileRepairB,
            'incomeFile' => $financeFileIncome,
            'utila' => $financeFileUtilityA,
            'utilb' => $financeFileUtilityB,
            'mfreport' => $mfreport,
            'reportMF' => $reportMF,
            'sfreport' => $sfreport,
            'reportSF' => $reportSF,
            'finance_file_id' => $id
        );

        return View::make('finance_en.edit_finance_file', $viewData);
    }

    public function updateFinanceFileCheck() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];

            $files = Finance::find($id);
            if ($files) {
                $finance = FinanceCheck::where('finance_file_id', $files->id)->first();
                if ($finance) {
                    $finance->date = $data['date'];
                    $finance->name = $data['name'];
                    $finance->position = $data['position'];
                    $finance->is_active = $data['is_active'];
                    $finance->remarks = $data['remarks'];
                    $finance->save();
                }

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return "true";
            }
        }

        return "false";
    }

    public function updateFinanceFileSummary() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefix = 'sum_';

            $files = Finance::find($id);
            if ($files) {
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

    public function updateFinanceFileReportMf() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $type = 'MF';
            $prefix = 'mfr_';

            $files = Finance::find($id);
            if ($files) {
                $remove = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->delete();
                $finance = FinanceReport::where('finance_file_id', $files->id)->where('type', $type)->first();

                if ($remove && $finance) {
                    $finance->fee_sebulan = $data[$prefix . 'fee_sebulan'];
                    $finance->unit = $data[$prefix . 'unit'];
                    $finance->fee_semasa = $data[$prefix . 'fee_semasa'];
                    $finance->no_akaun = $data[$prefix . 'no_akaun'];
                    $finance->nama_bank = $data[$prefix . 'nama_bank'];
                    $finance->baki_bank_awal = $data[$prefix . 'baki_bank_awal'];
                    $finance->baki_bank_akhir = $data[$prefix . 'baki_bank_akhir'];
                    $finance->save();

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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileReportSf() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $type = 'SF';
            $prefix = 'sfr_';

            $files = Finance::find($id);
            if ($files) {
                $remove = FinanceReportPerbelanjaan::where('finance_file_id', $files->id)->where('type', $type)->delete();
                $finance = FinanceReport::where('finance_file_id', $files->id)->where('type', $type)->first();

                if ($remove && $finance) {
                    $finance->fee_sebulan = $data[$prefix . 'fee_sebulan'];
                    $finance->unit = $data[$prefix . 'unit'];
                    $finance->fee_semasa = $data[$prefix . 'fee_semasa'];
                    $finance->no_akaun = $data[$prefix . 'no_akaun'];
                    $finance->nama_bank = $data[$prefix . 'nama_bank'];
                    $finance->baki_bank_awal = $data[$prefix . 'baki_bank_awal'];
                    $finance->baki_bank_akhir = $data[$prefix . 'baki_bank_akhir'];
                    $finance->save();

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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileAdmin() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefix = 'admin_';

            $files = Finance::find($id);
            if ($files) {
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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileIncome() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefix = 'income_';

            $files = Finance::find($id);
            if ($files) {
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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileUtility() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefixs = [
                'util_',
                'utilb_',
            ];

            $files = Finance::find($id);
            if ($files) {
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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileVandal() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefixs = [
                'maintenancefee_',
                'singkingfund_'
            ];

            $files = Finance::find($id);
            if ($files) {
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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileRepair() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefixs = [
                'repair_maintenancefee_',
                'repair_singkingfund_'
            ];

            $files = Finance::find($id);
            if ($files) {
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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileContract() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefix = 'contract_';

            $files = Finance::find($id);
            if ($files) {
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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFileStaff() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];
            $prefix = 'staff_';

            $files = Finance::find($id);
            if ($files) {
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

                # Audit Trail
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return 'true';
            }
        }

        return 'false';
    }

    public function updateFinanceFile() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['finance_file_id'];

            $files = Finance::find($id);
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
                $remarks = 'Finance File: ' . $files->file->file_no . " " . $files->year . "-" . strtoupper($files->monthName()) . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return "true";
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }

    public function financeSupport() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.cob.finance_support'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'finance_support_list',
            'user_permission' => $user_permission,
            'file' => $file,
            'image' => ""
        );

        return View::make('finance_en.finance_support_list', $viewData);
    }

    public function getFinanceSupportList() {
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
            $data = Array();
            foreach ($filelist as $filelists) {
                $files = Files::where('id', $filelists->file_id)->first();
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFinanceSupport(\'' . $filelists->id . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>&nbsp;';

                $data_raw = array(
                    "<a style='text-decoration:underline;' href='" . URL::action('FinanceController@editFinanceSupport', $filelists->id) . "'>" . $files->file_no . "</a>",
                    date('d/m/Y', strtotime($filelists->date)),
                    $filelists->name,
                    number_format($filelists->amount, 2),
                    $button
                );

                array_push($data, $data_raw);
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

    public function addFinanceSupport() {
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

        $viewData = array(
            'title' => trans('app.menus.cob.add_finance_support'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'finance_support_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no
        );

        return View::make('finance_en.add_finance_support', $viewData);
    }

    public function submitFinanceSupport() {
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
                    $remarks = 'Finance Support with id : ' . $finance->id . ' has been inserted.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB Finance Support";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();
                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function editFinanceSupport($id) {
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
        $financeSupportData = FinanceSupport::where('id', $id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.edit_finance_support'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'finance_support_list',
            'user_permission' => $user_permission,
            'image' => "",
            'file_no' => $file_no,
            'financesupportdata' => $financeSupportData
        );

        return View::make('finance_en.edit_finance_support', $viewData);
    }

    public function updateFinanceSupport() {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = $data['file_id'];
            $id = $data['id'];

            $files = Files::find($file_id);
            if ($files) {
                $finance = FinanceSupport::find($id);
                if ($finance) {
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
                        $remarks = 'Finance Support with id : ' . $finance->id . ' has been updated.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB Finance Support";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = Auth::user()->id;
                        $auditTrail->save();
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

    public function activeFinanceList() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $files = Finance::find($id);
            $files->is_active = 1;
            $updated = $files->save();
            if ($updated) {
                # Audit Trail
                $remarks = $files->file_no . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File Active";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function inactiveFinanceList() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $prefix = Finance::find($id);
            $prefix->is_active = 0;
            $updated = $prefix->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'COB Finance File has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance File Inactive";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteFinanceSupport() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $finance = FinanceSupport::find($id);
            $finance->is_deleted = 1;
            $deleted = $finance->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Finance Support with id : ' . $finance->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance Support";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateFinanceFileList() {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = $data['file_id'];
            $is_active = $data['is_active'];

            $financeSupportId = $data['id'];

            $finance = FinanceSupport::find($financeSupportId);
            $finance->file_id = $file_id;
            $finance->date = $data['date'];
            $finance->name = $data['name'];
            $finance->amount = $data['amount'];
            $finance->remarks = $data['remarks'];
            $finance->is_active = $is_active;
            $success = $finance->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Finance Support with id : ' . $finance->id . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB Finance Support";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();
                print "true";
            } else {
                print "false";
            }
        }
    }

}
