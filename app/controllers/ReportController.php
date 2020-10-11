<?php

class ReportController extends BaseController {

    public function ownerTenant() {
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
            $cob_name = Company::find($cob_company)->pluck('short_name');
        }
        $file_no = '';
        $file_name = 'All Files';
        if (isset($data['file_no']) && !empty($data['file_no'])) {
            $file_no = $data['file_no'];
            $file_name = Files::find($file_no)->pluck('file_no');
        }

        if (!empty($cob_company) && !empty($file_no)) {
            $purchaser = DB::table('buyer')
                    ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                    ->leftJoin('company', 'files.company_id', '=', 'company.id')
                    ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                    ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
                    ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name', 'strata.name as strata_name')
                    ->where('files.company_id', $cob_company)
                    ->where('files.id', $file_no)
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('unit_no', 'asc')
                    ->get();
        } else if (!empty($cob_company)) {
            $purchaser = DB::table('buyer')
                    ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                    ->leftJoin('company', 'files.company_id', '=', 'company.id')
                    ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                    ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
                    ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name', 'strata.name as strata_name')
                    ->where('files.company_id', $cob_company)
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else if (!empty($file_no)) {
            $purchaser = DB::table('buyer')
                    ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                    ->leftJoin('company', 'files.company_id', '=', 'company.id')
                    ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                    ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
                    ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name', 'strata.name as strata_name')
                    ->where('files.id', $file_no)
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else {
            $purchaser = DB::table('buyer')
                    ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                    ->leftJoin('company', 'files.company_id', '=', 'company.id')
                    ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                    ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
                    ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name', 'strata.name as strata_name')
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

}
