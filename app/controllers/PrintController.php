<?php

use Helper\Helper;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class PrintController extends BaseController {

    //audit trail
    public function printAuditTrail() {
        $data = Input::all();
        
        $start = $data['start'];
        $end = $data['end'];
        
        $query = DB::table('audit_trail')
                    ->join('users', 'audit_trail.audit_by', '=', 'users.id')
                    ->select('audit_trail.*', 'users.full_name as name');
        if (!Auth::user()->getAdmin()) {
            $query = $query->where('files.company_id', Auth::user()->company_id);
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('files.company_id', Session::get('admin_cob'));
            }
        }
        if(!empty($start)) {
            $query = $query->where('audit_trail.created_at', '>=', $start. '00:00:00');
        }
        if(!empty($end)) {
            $query = $query->where('audit_trail.created_at', '<=', $end. '23:59:59');
        }
        
        $audit_trail = $query->orderBy('audit_trail.id', 'desc')
                            ->get();

        $viewData = array(
            'title' => trans('app.menus.reporting.audit_trail_report'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'start' => $start,
            'end' => $end,
            'audit_trail' => $audit_trail
        );

        return View::make('print_en.audit_trail', $viewData);
    }

    public function printAuditTrailNew() {
        $request = Request::all();
        $request['module'] = $request['print_module'];
        $request['date_from'] = $request['print_date_from'];
        $request['date_to'] = $request['print_date_to'];
        $data = AuditTrail::getAnalyticData($request);
        $models = AuditTrail::self()
                ->where(function($query) use($request) {
                    if(!empty($request['company_id'])) {
                        $query->where('users.company_id', $request['company_id']);
                    }
                    if(!empty($request['role_id'])) {
                        $query->where('users.role', $request['role_id']);
                    }
                    if(!empty($request['module'])) {
                        $query->where('audit_trail.module', $request['module']);
                    }
                    if(!empty($request['file_id'])) {
                        $query->where('users.file_id', $request['file_id']);
                    }
                    if(!empty($request['date_from']) && empty($request['date_to'])) {
                        $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                        $query->where('audit_trail.created_at', '>=', $date_from);
                    }
                    if(!empty($request['date_to']) && empty($request['date_from'])) {
                        $date_to = date('Y-m-d', strtotime($request['date_to']));
                        $query->where('audit_trail.created_at', '<=', $date_to . " 23:59:59");
                    }
                    if(!empty($request['date_from']) && !empty($request['date_to'])) {
                        $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                        $date_to = date('Y-m-d', strtotime($request['date_to']));
                        $query->whereBetween('audit_trail.created_at', [$date_from, $date_to . ' 23:59:59']);
                    }
                })
                ->select(['audit_trail.*', 'company.short_name as company', 'users.full_name as full_name', 'role.name as role_name', 'files.file_no'])
                ->get();
        $viewData = array(
            'title' => trans('app.menus.reporting.audit_trail_report'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'data' => $data,
            'models' => $models,
        );

        return View::make('print_en.audit_trail', $viewData);
    }

    //file by location
    public function printFileByLocation() {
        $viewData = array(
            'title' => trans('app.menus.reporting.file_by_location_report'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
        );

        return View::make('print_en.file_by_location', $viewData);
    }

    //rating summary
    public function printRatingSummary() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        $stratas = 0;
        $ratings = 0;
        $fiveStars = 0;
        $fourStars = 0;
        $threeStars = 0;
        $twoStars = 0;
        $oneStars = 0;

        if (count($file) > 0) {
            foreach ($file as $files) {
                $strata = Strata::where('file_id', $files->id)->count();
                $rating = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->count();

                $fiveStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 81)->where('total_score', '<=', 100)->count();
                $fourStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 61)->where('total_score', '<=', 80)->count();
                $threeStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 41)->where('total_score', '<=', 60)->count();
                $twoStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 21)->where('total_score', '<=', 40)->count();
                $oneStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 1)->where('total_score', '<=', 20)->count();

                $stratas += $strata;
                $ratings += $rating;
                $fiveStars += $fiveStar;
                $fourStars += $fourStar;
                $threeStars += $threeStar;
                $twoStars += $twoStar;
                $oneStars += $oneStar;
            }
        }

        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        $viewData = array(
            'title' => trans('app.menus.reporting.rating_summary_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'rating_summary_list',
            'strata' => $stratas,
            'rating' => $ratings,
            'fiveStar' => $fiveStars,
            'fourStar' => $fourStars,
            'threeStar' => $threeStars,
            'twoStar' => $twoStars,
            'oneStar' => $oneStars,
            'category' => $category,
            'image' => ""
        );

        return View::make('print_en.rating_summary', $viewData);
    }

    //management summary
    public function printManagementSummary() {
        $data = Files::getManagementSummaryCOB();

        $viewData = array(
            'title' => trans('app.menus.reporting.management_summary_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'management_summary_list',
            'data' => $data,
            'image' => ""
        );

        return View::make('print_en.management_summary', $viewData);
    }

    //cob file / management
    public function printCobFileManagement() {
        $data = Files::getManagementSummaryCOB();

        $viewData = array(
            'title' => trans('app.menus.reporting.cob_file_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'cob_file_management_list',
            'data' => $data,
            'image' => ""
        );

        return View::make('print_en.cob_file_management', $viewData);
    }

    public function printOwnerTenant($id) {
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

        if (isset($id) && !empty($id)) {
            $file_id = Helper::decode($id);
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

        return View::make('print_en.owner_tenant', $viewData);
    }

    public function printStrataProfile($id) {
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

            $files = Files::with(['financeLatest'])
                            ->file()
                            ->find(Helper::decode($id));
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
                $tnb = '';

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
                    $tnb = ucfirst($files->other->tnb);
                }

                if ($files->financeLatest) {
                    $finance = $files->financeLatest;
                    $finance_income = $finance->financeIncome;
                    $finance_report_fee = $finance->financeReport;
                    $finance_report_fee_semasa = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                    if ($finance_income) {
                        foreach ($finance_report_fee as $report) {
                            if ($report->type == 'MF') {
                                $mf_rate = $report->fee_sebulan;
                            }
                            if ($report->type == 'SF') {
                                $sf_rate = $report->fee_sebulan;
                                $sepatut_dikutip = $sepatut_dikutip + $report->fee_semasa;
                            }
                        }
                        foreach ($finance_income as $income) {
                            if ($income->name == 'SINKING FUND') {
                                $berjaya_dikutip = $berjaya_dikutip + $income->semasa;
                            }
                        }
                    }
    
                    if (!empty($berjaya_dikutip) && !empty($sepatut_dikutip)) {
                        $purata_dikutip = round(($berjaya_dikutip / $sepatut_dikutip) * 100, 2);
                    }
    
                    if($finance_report_fee_semasa > 0) {
                        if ($purata_dikutip >= 80) {
                            $zone = 'BIRU';
                        } else if ($purata_dikutip < 79 && $purata_dikutip >= 50) {
                            $zone = 'KUNING';
                        } else {
                            $zone = "MERAH";
                        }
                    } else {
                        $zone = "KELABU";
                    }
                } else {
                    $zone = "KELABU";
                }
            }
            $finances = Finance::with(['financeIncome', 'financeReport'])
                            ->join('files', 'finance_file.file_id', '=', 'files.id')
                            ->where('finance_file.file_id', $files->id)
                            ->where('finance_file.is_active', true)
                            ->where('finance_file.is_deleted', false)
                            ->selectRaw('finance_file.*, files.file_no')
                            ->orderBy('finance_file.id', 'desc')
                            ->get();

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
                'tnb' => $tnb,
                'purata_dikutip' => $purata_dikutip,
                'finances' => $finances
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

            return View::make('print_en.strata_profile', $viewData);
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
    public function printPurchaser() {
        $purchaser = array();
        $data = Input::all();

        $cob_company = '';
        if (isset($data['company'])) {
            $cob_company = $data['company'];
        }
        $file_no = '';
        if (isset($data['file_no'])) {
            $file_no = $data['file_no'];
        }
        $scheme_name = '';
        if (isset($data['scheme_name'])) {
            $scheme_name = $data['scheme_name'];
        }
        $unit_no = '';
        if (isset($data['unit_no'])) {
            $unit_no = $data['unit_no'];
        }
        $unit_share = '';
        if (isset($data['unit_share'])) {
            $unit_share = $data['unit_share'];
        }
        $buyer = '';
        if (isset($data['buyer'])) {
            $buyer = $data['buyer'];
        }
        $phone_number = '';
        if (isset($data['phone_number'])) {
            $phone_number = $data['phone_number'];
        }
        $email = '';
        if (isset($data['email'])) {
            $email = $data['email'];
        }
        $race = '';
        if (isset($data['race'])) {
            $race = $data['race'];
        }

        if (!empty($cob_company) && !empty($file_no)) {
            $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
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
                    ->select(['buyer.*'])
                    ->where('files.file_no', $file_no)
                    ->where('buyer.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                } else {
                    $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                } else {
                    $purchaser = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                }
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.purchaser'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'file_no' => $file_no,
            'cob_company' => $cob_company,
            'scheme_name' => $scheme_name,
            'unit_no' => $unit_no,
            'unit_share' => $unit_share,
            'buyer' => $buyer,
            'phone_number' => $phone_number,
            'email' => $email,
            'race' => $race,
            'purchaser' => $purchaser
        );

        return View::make('print_en.purchaser', $viewData);
    }

    public function printTenant() {
        $tenant = array();
        $data = Input::all();

        $cob_company = '';
        if (isset($data['company'])) {
            $cob_company = $data['company'];
        }
        $file_no = '';
        if (isset($data['file_no'])) {
            $file_no = $data['file_no'];
        }
        $scheme_name = '';
        if (isset($data['scheme_name'])) {
            $scheme_name = $data['scheme_name'];
        }
        $unit_no = '';
        if (isset($data['unit_no'])) {
            $unit_no = $data['unit_no'];
        }
        $unit_share = '';
        if (isset($data['unit_share'])) {
            $unit_share = $data['unit_share'];
        }
        $tenant_name = '';
        if (isset($data['tenant_name'])) {
            $tenant_name = $data['tenant_name'];
        }
        $phone_number = '';
        if (isset($data['phone_number'])) {
            $phone_number = $data['phone_number'];
        }
        $email = '';
        if (isset($data['email'])) {
            $email = $data['email'];
        }
        $race = '';
        if (isset($data['race'])) {
            $race = $data['race'];
        }

        if (!empty($cob_company) && !empty($file_no)) {
            $tenant = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['tenant.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('files.file_no', $file_no)
                    ->where('tenant.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else if (!empty($cob_company)) {
            $tenant = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['tenant.*'])
                    ->where('company.short_name', $cob_company)
                    ->where('tenant.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else if (!empty($file_no)) {
            $tenant = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['tenant.*'])
                    ->where('files.file_no', $file_no)
                    ->where('tenant.is_deleted', 0)
                    ->orderBy('company.short_name', 'ASC')
                    ->orderBy('files.file_no', 'ASC')
                    ->orderBy('unit_no', 'ASC')
                    ->get();
        } else {
            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $tenant = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('files.id', Auth::user()->file_id)
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                } else {
                    $tenant = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('files.company_id', Auth::user()->company_id)
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $tenant = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                } else {
                    $tenant = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                            ->join('company', 'files.company_id', '=', 'company.id')
                            ->join('strata', 'files.id', '=', 'strata.file_id')
                            ->select(['buyer.*'])
                            ->where('files.company_id', Session::get('admin_cob'))
                            ->where('buyer.is_deleted', 0)
                            ->where('files.is_deleted', 0)
                            ->orderBy('company.short_name', 'ASC')
                            ->orderBy('files.file_no', 'ASC')
                            ->orderBy('unit_no', 'ASC')
                            ->get();
                }
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.tenant'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'file_no' => $file_no,
            'cob_company' => $cob_company,
            'scheme_name' => $scheme_name,
            'unit_no' => $unit_no,
            'unit_share' => $unit_share,
            'tenant_name' => $tenant_name,
            'phone_number' => $phone_number,
            'email' => $email,
            'race' => $race,
            'tenant' => $tenant
        );

        return View::make('print_en.tenant', $viewData);
    }

    public function printInsurance($cob_id) {
        if ($cob_id && $cob_id != 'all') {
            $file_info = Files::getInsuranceReportByCOB(Helper::decode($cob_id));
        } else {
            $file_info = Files::getInsuranceReportByCOB();
        }

        $insurance_provider = InsuranceProvider::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();

        $viewData = array(
            'title' => trans('app.menus.reporting.insurance'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'insurance_provider' => $insurance_provider,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
        );

        return View::make('print_en.insurance', $viewData);
    }

    public function printComplaint($cob_id) {
        if ($cob_id && $cob_id != 'all') {
            $file_info = Files::getComplaintReportByCOB(Helper::decode($cob_id));
        } else {
            $file_info = Files::getComplaintReportByCOB();
        }

        $defect_category = DefectCategory::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();

        $viewData = array(
            'title' => trans('app.menus.reporting.complaint'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'defect_category' => $defect_category,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
        );

        return View::make('print_en.complaint', $viewData);
    }

    public function printCollection($cob_id) {
        if ($cob_id && $cob_id != 'all') {
            $file_info = Files::getCollectionReportByCOB(Helper::decode($cob_id));
        } else {
            $file_info = Files::getCollectionReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.collection'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'file_info' => $file_info,
            'cob_id' => $cob_id,
        );

        return View::make('print_en.collection', $viewData);
    }

    public function printCouncil($cob_id) {
        if ($cob_id && $cob_id != 'all') {
            $file_info = Files::getCouncilReportByCOB(Helper::decode($cob_id));
        } else {
            $file_info = Files::getCouncilReportByCOB();
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.council'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'file_info' => $file_info,
            'cob_id' => $cob_id,
        );

        return View::make('print_en.council', $viewData);
    }

    public function printDun($cob_id) {
        if ($cob_id && $cob_id != 'all') {
            $file_info = Files::getDunReportByCOB(Helper::decode($cob_id));
        } else {
            $file_info = Files::getDunReportByCOB();
        }

        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        $viewData = array(
            'title' => trans('app.menus.reporting.dun'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'category' => $category,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('print_en.dun', $viewData);
    }

    public function printParliment($cob_id) {
        if ($cob_id && $cob_id != 'all') {
            $file_info = Files::getParlimentReportByCOB(Helper::decode($cob_id));
        } else {
            $file_info = Files::getParlimentReportByCOB();
        }

        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        $viewData = array(
            'title' => trans('app.menus.reporting.parliment'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'category' => $category,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'image' => ''
        );

        return View::make('print_en.parliment', $viewData);
    }

    public function printVp() {
        $data = Input::all();

        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            if (isset($data['year']) && !empty($data['year'])) {
                $cob_id = Helper::decode($data['cob_id']);
                $year_id = $data['year'];
            } else {
                $cob_id = Helper::decode($data['cob_id']);
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
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'year_id' => $year_id ? $year_id : date('Y'),
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'cob_name' => $cob_id ? Company::where('id', $cob_id)->pluck('name') : ''
        );

        return View::make('print_en.vp', $viewData);
    }

    public function printManagementList() {
        $result = array();
        $data = Input::all();

        $cob_company = '';
        if (isset($data['company']) && !empty($data['company'])) {
            $cob_company = $data['company'];
        }
        $file_no = '';
        if (isset($data['file_no']) && !empty($data['file_no'])) {
            $file_no = $data['file_no'];
        }
        $filename = '';
        if (isset($data['file_name']) && !empty($data['file_name'])) {
            $filename = $data['file_name'];
        }
        $type = '';
        if (isset($data['type']) && !empty($data['type'])) {
            $type = $data['type'];
        }
        $type_name = '';
        if (isset($data['type_name'])) {
            $type_name = $data['type_name'];
        }
        $address = '';
        if (isset($data['address'])) {
            $address = $data['address'];
        }
        $email = '';
        if (isset($data['email'])) {
            $email = $data['email'];
        }
        $phone_number = '';
        if (isset($data['phone_number'])) {
            $phone_number = $data['phone_number'];
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
                            $file->managementAgent->agent,
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
                            $file->managementAgent->agent,
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

//        return "<pre>" . print_r($result, true) . "</pre>";

        $viewData = array(
            'title' => trans('app.menus.reporting.purchaser'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'file_no' => $file_no,
            'cob_company' => $cob_company,
            'type' => $type,
            'type_name' => $type_name,
            'address' => $address,
            'email' => $email,
            'phone_number' => $phone_number,
            'result' => $result
        );

        return View::make('print_en.management_list', $viewData);
    }

    public function printFinanceFile($id) {
        $finance = Finance::find(Helper::decode($id));
        if ($finance) {
            $file_no = Files::where('is_active', 1)->where('is_deleted', 0)->get();
            $financeCheckData = FinanceCheck::where('finance_file_id', Helper::decode($id))->first();
            $financeSummary = FinanceSummary::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
            $financefiledata = Finance::where('id', Helper::decode($id))->first();
            $financeFileAdmin = FinanceAdmin::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
            $financeFileContract = FinanceContract::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
            $financeFileStaff = FinanceStaff::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();
            $financeFileVandalA = FinanceVandal::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
            $financeFileVandalB = FinanceVandal::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
            $financeFileRepairA = FinanceRepair::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();
            $financeFileRepairB = FinanceRepair::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();
            $financeFileUtilityA = FinanceUtility::where('finance_file_id', Helper::decode($id))->where('type', 'BHG_A')->orderBy('sort_no', 'asc')->get();
            $financeFileUtilityB = FinanceUtility::where('finance_file_id', Helper::decode($id))->where('type', 'BHG_B')->orderBy('sort_no', 'asc')->get();
            $financeFileIncome = FinanceIncome::where('finance_file_id', Helper::decode($id))->orderBy('sort_no', 'asc')->get();

            $mfreport = FinanceReport::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->first();
            $reportMF = FinanceReportPerbelanjaan::where('finance_file_id', Helper::decode($id))->where('type', 'MF')->orderBy('sort_no', 'asc')->get();

            $sfreport = FinanceReport::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->first();
            $reportSF = FinanceReportPerbelanjaan::where('finance_file_id', Helper::decode($id))->where('type', 'SF')->orderBy('sort_no', 'asc')->get();

            $viewData = array(
                'title' => 'Print Finance',
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
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
                'finance_file_id' => Helper::decode($id)
            );

            return View::make('print_en.print_finance_file', $viewData);
        }
    }

    public function printLandTitle($cob_id, $land_title_id) {
        if (!AccessGroup::hasAccess(60)) {
            $viewData = array(
                'title' => trans('app.errors.page_not_found'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'image' => ""
            );

            return View::make('404_en', $viewData);
        }
        
        if (!empty($cob_id) && $cob_id == 'all') {
            $cob_id = '';
        } elseif(!empty($cob_id && $cob_id != 'all')) {
            $cob_id = Helper::decode($cob_id);
        }
        if (!empty($land_title_id) && $land_title_id == 'all') {
            $land_title_id = '';
        }
        $file_info = Files::getLandTitleReportByCOB($cob_id, $land_title_id);

        $viewData = array(
            'title' => trans('app.menus.reporting.land_title'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'land_title_id' => $land_title_id,
            'image' => ''
        );

        return View::make('print_en.land_title', $viewData);
    }

    //print finance support
    public function financeSupport() {
        $data = Input::all();

        $company_id = !empty($data['company'])? $data['company'] : '';

        if(!Auth::user()->getAdmin()) {
            $company_id = Auth::user()->company_id;
        } else {
            if(!empty($company_id)) {
                $company = Company::where('short_name', $company_id)->firstOrFail();
                $company_id = $company->id;
            }
            if(!empty(Session::get('admin_cob'))) {
                $company_id = Session::get('admin_cob');
            }
            
        }
        $query = FinanceSupport::where('is_deleted', 0)
                                ->where('is_active', 1);
        if(!empty($company_id)) {
            $query = $query->where('company_id', $company_id);
        }
        $items = $query->get();
        
        $viewData = array(
            'title' => trans('app.menus.finance.finance_support'),
            'panel_nav_active' => 'finance_panel',
            'main_nav_active' => 'finance_main',
            'sub_nav_active' => 'finance_support_list',
            'company_id' => $company_id,
            'items' => $items
        );

        return View::make('print_en.finance_support', $viewData);

    }

    public function epks() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(64));
        $request = Input::all();
        if(!empty($request['from'])) {
            $request['date_from'] = $request['from'];
        }
        if(!empty($request['to'])) {
            $request['date_to'] = $request['to'];
        }
        $data = Epks::getAnalyticData($request);
        
        $viewData = array(
            'title' => trans('app.menus.reporting.epks'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'epks_report_list',
            'data' => $data,
            'image' => ''
        );

        return View::make('print_en.epks', $viewData);

    }

}
