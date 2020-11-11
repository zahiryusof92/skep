<?php

class ReportController extends BaseController {

    public function ownerTenant() {
        if (!AccessGroup::hasAccess(49)) {
            $title = trans('app.errors.page_not_found');
            return View::make('404_en', compact('title'));
        }

        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();

            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('status', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('status', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('is_deleted', 0)->orderBy('status', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('status', 'asc')->get();
            }
        }

        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();

        if (isset($data['file_id']) && !empty($data['file_id'])) {
            $file_id = $data['file_id'];
            $owner = Buyer::where('file_id', $file_id)->where('is_deleted', 0)->get();
            $tenant = Tenant::where('file_id', $file_id)->where('is_deleted', 0)->get();
        } else {
            $file_id = '';
            $owner = '';
            $tenant = '';
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.owner'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'owner_tenant_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'race' => $race,
            'file_id' => $file_id,
            'owner' => $owner,
            'tenant' => $tenant,
            'image' => ''
        );

        return View::make('report_en.owner_tenant', $viewData);
    }

    public function strataProfile() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->get();

        $access_permission = 0;
        foreach ($user_permission as $permission) {
            if ($permission->submodule_id == 29) {
                $access_permission = $permission->access_permission;
            }
        }

        if ($access_permission) {
            $viewData = array(
                'title' => trans('app.menus.reporting.strata_profile'),
                'panel_nav_active' => 'reporting_panel',
                'main_nav_active' => 'reporting_main',
                'sub_nav_active' => 'strata_profile_list',
                'user_permission' => $user_permission,
                'cob' => $cob,
                'parliament' => $parliament,
                'image' => '',
            );

            return View::make('report_en.strata_profile', $viewData);
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

    public function getStrataProfile() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_deleted', 0)->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->get();
            }
        }

        if ($file) {
            $data = Array();
            foreach ($file as $files) {
                $parliament_name = '<i>(not set)</i>';

                $parliament = Parliment::find($files->strata->parliament);
                if ($parliament) {
                    $parliament_name = $parliament->description;
                }

                $berjaya_dikutip = 0;
                $sepatut_dikutip = 0;
                $purata_dikutip = 0;

                if ($files->finance) {
                    foreach ($files->finance as $finance) {
                        if ($finance->year == date('Y')) {
                            if ($finance->financeIncome) {
                                foreach ($finance->financeReport as $report) {
                                    if ($report->type == 'SF') {
                                        $sepatut_dikutip = $sepatut_dikutip + $report->fee_semasa;
                                    }
                                }
                                foreach ($finance->financeIncome as $income) {
                                    if ($income->name == 'SINKING FUND') {
                                        $berjaya_dikutip = $berjaya_dikutip + $income->semasa;
                                    }
                                }
                            }
                        }
                    }
                }

                if (!empty($berjaya_dikutip) && !empty($sepatut_dikutip)) {
                    $purata_dikutip = round(($berjaya_dikutip / $sepatut_dikutip) * 100, 2);
                }

                if ($purata_dikutip >= 80) {
                    $zone = 'Biru';
                } else if ($purata_dikutip < 79 && $purata_dikutip >= 50) {
                    $zone = 'Kuning';
                } else {
                    $zone = 'Merah';
                }

                $data_raw = array(
                    "<a style='text-decoration:underline;' href='" . URL::action('ReportController@viewStrataProfile', $files->id) . "'>" . $files->file_no . "</a>",
                    $files->company->short_name,
                    $parliament_name,
                    $zone
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

    public function viewStrataProfile($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $access_permission = 0;
        foreach ($user_permission as $permission) {
            if ($permission->submodule_id == 29) {
                $access_permission = $permission->access_permission;
            }
        }

        if ($access_permission) {
            $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();

            $files = Files::find($id);
            if ($files) {
                $pbt = '';
                $strata_name = '';
                $total_unit = 0;
                $total_block = '';
                $total_floor = '';
                $mf_rate = 0;
                $sf_rate = 0;
                $berjaya_dikutip = 0;
                $sepatut_dikutip = 0;
                $purata_dikutip = 0;
                $lif = 'TIADA';
                $lif_unit = 0;
                $type_meter = '';

                if ($files) {
                    $pbt = $files->company->short_name;
                }

                if ($files->strata) {
                    $strata_name = $files->strata->name;
                    $total_block = $files->strata->block_no;
                    $total_floor = $files->strata->total_floor;
                }

                if ($files->resident) {
                    $total_unit = $total_unit + $files->resident->unit_no;
                }
                if ($files->commercial) {
                    $total_unit = $total_unit + $files->commercial->unit_no;
                }

                if ($files->facility) {
                    $check_lif = $files->facility->lift;
                    if ($check_lif) {
                        $lif = 'ADA';
                        $lif_unit = $files->facility->lift_unit;
                    }
                }

                if ($files->other) {
                    $type_meter = $files->other->water_meter;
                }

                if ($files->finance) {
                    foreach ($files->finance as $finance) {
                        if ($finance->year == date('Y')) {
                            if ($finance->financeIncome) {
                                foreach ($finance->financeReport as $report) {
                                    if ($report->type == 'MF') {
                                        $mf_rate = $report->fee_sebulan;
                                    }
                                    if ($report->type == 'SF') {
                                        $sf_rate = $report->fee_sebulan;
                                        $sepatut_dikutip = $sepatut_dikutip + $report->fee_semasa;
                                    }
                                }
                                foreach ($finance->financeIncome as $income) {
                                    if ($income->name == 'SINKING FUND') {
                                        $berjaya_dikutip = $berjaya_dikutip + $income->semasa;
                                    }
                                }
                            }
                        }
                    }
                }

                if (!empty($berjaya_dikutip) && !empty($sepatut_dikutip)) {
                    $purata_dikutip = round(($berjaya_dikutip / $sepatut_dikutip) * 100, 2);
                }

                if ($purata_dikutip >= 80) {
                    $zone = 'BIRU';
                } else if ($purata_dikutip < 79 && $purata_dikutip >= 50) {
                    $zone = 'KUNING';
                } else {
                    $zone = 'MERAH';
                }
            }

            $result = array(
                'pbt' => $pbt,
                'strata_name' => $strata_name,
                'total_unit' => $total_unit,
                'total_block' => $total_block,
                'total_floor' => $total_floor,
                'mf_rate' => $mf_rate,
                'sf_rate' => $sf_rate,
                'zone' => $zone,
                'lif' => $lif,
                'lif_unit' => $lif_unit,
                'type_meter' => $type_meter,
                'purata_dikutip' => $purata_dikutip
            );

//            return "<pre>" . print_r($result, true) . "</pre>";

            $viewData = array(
                'title' => trans('app.menus.reporting.strata_profile'),
                'panel_nav_active' => 'reporting_panel',
                'main_nav_active' => 'reporting_main',
                'sub_nav_active' => 'strata_profile_list',
                'user_permission' => $user_permission,
                'files' => $files,
                'race' => $race,
                'result' => $result,
                'image' => '',
            );

            return View::make('report_en.view_strata_profile', $viewData);
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

    //purchaser
    public function purchaser() {
        $purchaser = array();
        $data = Input::all();

        $cob_company = '';
        $cob_name = 'All COB';
        if (isset($data['company']) && !empty($data['company'])) {
            $cob_company = $data['company'];
            $cob_name = $data['company'];
        }

        $file_no = '';
        $file_name = 'All Files';
        if (isset($data['file_no']) && !empty($data['file_no'])) {
            $file_no = $data['file_no'];
            $file_name = $data['file_no'];
        }

        if (!empty($cob_company) && !empty($file_no)) {
            $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('race', 'buyer.race_id', '=', 'race.id')
                    ->select(['buyer.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('files.file_no', $file_no)
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else if (!empty($cob_company)) {
            $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('race', 'buyer.race_id', '=', 'race.id')
                    ->select(['buyer.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else if (!empty($file_no)) {
            $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('race', 'buyer.race_id', '=', 'race.id')
                    ->select(['buyer.*'])
                    ->where('files.file_no', $file_no)
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else {
            $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('race', 'buyer.race_id', '=', 'race.id')
                    ->select(['buyer.*'])
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.purchaser'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'image' => "",
            'file_no' => $file_no,
            'file_name' => $file_name,
            'cob_company' => $cob_company,
            'cob_name' => $cob_name,
            'purchaser' => $purchaser
        );

        return View::make('report_en.purchaser', $viewData);
    }

    /*
     * Complaint Report
     */

    public function complaint() {
        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $defect_category = DefectCategory::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            $cob_id = $data['cob_id'];
            $file_info = Files::getComplaintReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getComplaintReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.complaint'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'complaint_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'defect_category' => $defect_category,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('report_en.complaint', $viewData);
    }

    /*
     * Insurance Report
     */

    public function insurance() {
        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $insurance_provider = InsuranceProvider::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            $cob_id = $data['cob_id'];
            $file_info = Files::getInsuranceReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getInsuranceReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.insurance'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'insurance_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'insurance_provider' => $insurance_provider,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('report_en.insurance', $viewData);
    }

    public function collection() {
        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            $cob_id = $data['cob_id'];
            $file_info = Files::getCollectionReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getCollectionReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.collection'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'collection_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('report_en.collection', $viewData);
    }

    /*
     * Council Report
     */

    public function council() {
        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            $cob_id = $data['cob_id'];
            $file_info = Files::getCouncilReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getCouncilReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.council'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'council_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('report_en.council', $viewData);
    }

    public function dun() {
        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            $cob_id = $data['cob_id'];
            $file_info = Files::getDunReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getDunReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.dun'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'dun_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'category' => $category,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('report_en.dun', $viewData);
    }

    public function parliment() {
        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            $cob_id = $data['cob_id'];
            $file_info = Files::getParlimentReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getParlimentReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.parliment'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'parliment_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'category' => $category,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('report_en.parliment', $viewData);
    }

    public function vp() {
        $data = Input::all();

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $year = Files::getVPYear();

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            if (isset($data['year']) && !empty($data['year'])) {
                $cob_id = $data['cob_id'];
                $year_id = $data['year'];
            } else {
                $cob_id = $data['cob_id'];
                $year_id = '';
            }
        } else {
            if (isset($data['year']) && !empty($data['year'])) {
                $cob_id = '';
                $year_id = $data['year'];
            } else {
                $cob_id = '';
                $year_id = '';
            }
        }

        $file_info = Files::getVPReport($cob_id, $year_id ? $year_id : date('Y'));

        $viewData = array(
            'title' => trans('app.menus.reporting.vp'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'vp_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'year' => $year,
            'year_id' => $year_id ? $year_id : date('Y'),
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'cob_name' => $cob_id ? Company::where('id', $cob_id)->pluck('name') : '',
            'image' => ''
        );

        return View::make('report_en.vp', $viewData);
    }

    public function management() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();

            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        $types = array(
            array('value' => 'JMB', 'name' => 'JMB'),
            array('value' => 'MC', 'name' => 'MC'),
            array('value' => 'Agent', 'name' => 'Agent'),
            array('value' => 'Others', 'name' => 'Others')
        );

        $filename = Files::getFileName();

        $viewData = array(
            'title' => trans('app.menus.reporting.management_list'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'management_list_report_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'types' => $types,
            'files' => $files,
            'filename' => $filename,
            'image' => ''
        );

        return View::make('report_en.management', $viewData);
    }

    public function managementList() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $result = array();
        $data = Input::all();

        $cob_company = '';
        $cob_name = 'All COB';
        if (isset($data['company']) && !empty($data['company'])) {
            $cob_company = $data['company'];
            $cob_name = $data['company'];
        }

        $file_no = '';
        $file_name = 'All Files';
        if (isset($data['file_no']) && !empty($data['file_no'])) {
            $file_no = $data['file_no'];
            $file_name = $data['file_no'];
        }

        $filename = '';
        $fname = 'All File Names';
        if (isset($data['file_name']) && !empty($data['file_name'])) {
            $filename = $data['file_name'];
            $fname = $data['file_name'];
        }

        $type = '';
        $type_name = 'All Types';
        if (isset($data['type']) && !empty($data['type'])) {
            $type = $data['type'];
            $type_name = $data['type'];
        }

        if ((!empty($cob_company) && !empty($file_no)) && !empty($filename)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('files.file_no', $file_no)
                    ->where('strata.name', $filename)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->get();
        } else if (!empty($cob_company) && !empty($filename)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('strata.name', $filename)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->get();
        } else if (!empty($file_no) && !empty($filename)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('files.file_no', $file_no)
                    ->where('strata.name', $filename)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->get();
        } else if (!empty($cob_company) && !empty($file_no)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('files.file_no', $file_no)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->get();
        } else if (!empty($cob_company)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*'])
                    ->where('company.short_name', $cob_company)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->get();
        } else if (!empty($file_no)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*'])
                    ->where('files.file_no', $file_no)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->get();
        } else if (!empty($filename)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*'])
                    ->where('strata.name', $filename)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*'])
                            ->where('id', Auth::user()->file_id)
                            ->where('company_id', Auth::user()->company_id)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->get();
                } else {
                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*'])
                            ->where('company_id', Auth::user()->company_id)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->get();
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*'])
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->get();
                } else {
                    $files = Files::join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['files.*'])
                            ->where('company_id', Session::get('admin_cob'))
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->get();
                }
            }
        }

        if ($files) {
            foreach ($files as $file) {
                if ($type == 'JMB') {
                    if ($file->managementJMB) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'JMB',
                            $file->managementJMB->name,
                            ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                            $file->managementJMB->email,
                            $file->managementJMB->phone_no
                        );

                        array_push($result, $data_raw);
                    }
                } else if ($type == 'MC') {
                    if ($file->managementMC) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'MC',
                            $file->managementMC->name,
                            ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                            $file->managementMC->email,
                            $file->managementMC->phone_no
                        );

                        array_push($result, $data_raw);
                    }
                } else if ($type == 'Agent') {
                    if ($file->managementAgent) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'Agent',
                            $file->managementAgent->name,
                            ($file->managementAgent->address1 ? $file->managementAgent->address1 : '') . ($file->managementAgent->address2 ? '<br/>' . $file->managementAgent->address2 : '') . ($file->managementAgent->address3 ? '<br/>' . $file->managementAgent->address3 : ''),
                            $file->managementAgent->email,
                            $file->managementAgent->phone_no
                        );

                        array_push($result, $data_raw);
                    }
                } else if ($type == 'Others') {
                    if ($file->managementOthers) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'Others',
                            $file->managementOthers->name,
                            ($file->managementOthers->address1 ? $file->managementOthers->address1 : '') . ($file->managementOthers->address2 ? '<br/>' . $file->managementOthers->address2 : '') . ($file->managementOthers->address3 ? '<br/>' . $file->managementOthers->address3 : ''),
                            $file->managementOthers->email,
                            $file->managementOthers->phone_no
                        );

                        array_push($result, $data_raw);
                    }
                } else {
                    if ($file->managementJMB) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'JMB',
                            $file->managementJMB->name,
                            ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                            $file->managementJMB->email,
                            $file->managementJMB->phone_no
                        );

                        array_push($result, $data_raw);
                    }

                    if ($file->managementMC) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'MC',
                            $file->managementMC->name,
                            ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                            $file->managementMC->email,
                            $file->managementMC->phone_no
                        );

                        array_push($result, $data_raw);
                    }

                    if ($file->managementAgent) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'Agent',
                            $file->managementAgent->name,
                            ($file->managementAgent->address1 ? $file->managementAgent->address1 : '') . ($file->managementAgent->address2 ? '<br/>' . $file->managementAgent->address2 : '') . ($file->managementAgent->address3 ? '<br/>' . $file->managementAgent->address3 : ''),
                            $file->managementAgent->email,
                            $file->managementAgent->phone_no
                        );

                        array_push($result, $data_raw);
                    }

                    if ($file->managementOthers) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'Others',
                            $file->managementOthers->name,
                            ($file->managementOthers->address1 ? $file->managementOthers->address1 : '') . ($file->managementOthers->address2 ? '<br/>' . $file->managementOthers->address2 : '') . ($file->managementOthers->address3 ? '<br/>' . $file->managementOthers->address3 : ''),
                            $file->managementOthers->email,
                            $file->managementOthers->phone_no
                        );

                        array_push($result, $data_raw);
                    }
                }
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.management_list'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'management_list_report_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'cob_company' => $cob_company,
            'cob_name' => $cob_name,
            'file_no' => $file_no,
            'file_name' => $file_name,
            'filename' => $filename,
            'fname' => $fname,
            'type' => $type,
            'type_name' => $type_name,
            'result' => $result,
            'image' => ''
        );

        return View::make('report_en.management_list', $viewData);
    }

    public function getManagementList() {
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();

            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        if (count($files) > 0) {
            $data = array();
            foreach ($files as $file) {
                if ($file->managementJMB) {
                    $data_raw = array(
                        $file->company->short_name,
                        $file->file_no,
                        $file->strata->name,
                        'JMB',
                        $file->managementJMB->name,
                        ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                        $file->managementJMB->email,
                        $file->managementJMB->phone_no
                    );

                    array_push($data, $data_raw);
                }

                if ($file->managementMC) {
                    $data_raw = array(
                        $file->company->short_name,
                        $file->file_no,
                        $file->strata->name,
                        'MC',
                        $file->managementMC->name,
                        ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                        $file->managementMC->email,
                        $file->managementMC->phone_no
                    );

                    array_push($data, $data_raw);
                }

                if ($file->managementAgent) {
                    $data_raw = array(
                        $file->company->short_name,
                        $file->file_no,
                        $file->strata->name,
                        'Agent',
                        $file->managementAgent->name,
                        ($file->managementAgent->address1 ? $file->managementAgent->address1 : '') . ($file->managementAgent->address2 ? '<br/>' . $file->managementAgent->address2 : '') . ($file->managementAgent->address3 ? '<br/>' . $file->managementAgent->address3 : ''),
                        $file->managementAgent->email,
                        $file->managementAgent->phone_no
                    );

                    array_push($data, $data_raw);
                }

                if ($file->managementOthers) {
                    $data_raw = array(
                        $file->company->short_name,
                        $file->file_no,
                        $file->strata->name,
                        'Others',
                        $file->managementOthers->name,
                        ($file->managementOthers->address1 ? $file->managementOthers->address1 : '') . ($file->managementOthers->address2 ? '<br/>' . $file->managementOthers->address2 : '') . ($file->managementOthers->address3 ? '<br/>' . $file->managementOthers->address3 : ''),
                        $file->managementOthers->email,
                        $file->managementOthers->phone_no
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

}
