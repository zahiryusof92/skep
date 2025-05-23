<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;
use Repositories\ReportRepo;

class ReportController extends BaseController
{

    //audit trail
    public function auditTrail()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(24));

        if (Request::ajax()) {
            $request = Request::all();
            $data = AuditTrail::getAnalyticData($request);
            if (!empty($request['filter'])) {
                return Response::json([
                    'data' => $data,
                    'success' => true,
                ]);
            } else {
                $models = AuditTrail::self()
                    ->select(['audit_trail.*', 'company.short_name as company', 'users.full_name as full_name', 'role.name as role_name', 'files.file_no']);

                return Datatables::of($models)
                    ->editColumn('company_id', function ($model) {
                        if ($model->company_id > 0) {
                            return $model->company;
                        }
                        return Str::upper($model->user->getCOB->short_name);
                    })
                    ->editColumn('file_id', function ($model) {
                        if ($model->file) {
                            return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file->id)) . "'>" . $model->file_no . "</a>";
                        }
                        if ($model->user && ($model->user->isJMB() || $model->user->isMC() || $model->user->isDeveloper())) {
                            if (empty($model->user->getFile)) {
                                return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->user->getFile->id)) . "'>" . $model->user->getFile->file_no . "</a>";
                            }
                        }
                        return '-';
                    })
                    ->editColumn('remarks', function ($model) {
                        if (is_array($model->remarks)) {
                        }
                        return $model->remarks;
                    })
                    ->editColumn('audit_by', function ($model) {
                        return $model->user->full_name;
                    })
                    ->editColumn('role_name', function ($model) {
                        return ($model->user->getAdmin()) ? trans('System Administrator') : Str::upper($model->role_name);
                    })
                    ->editColumn('created_at', function ($model) {
                        return date('d-m-Y H:i A', strtotime($model->created_at));
                    })
                    // ->addColumn('strata_name', function($model) {
                    //     if($model->file_id > 0) {
                    //         return $model->strata_name;
                    //     }
                    //     return Auth::user()->getFile->strata->name;
                    // })
                    ->filter(function ($query) use ($request) {
                        if (!empty($request['company_id'])) {
                            $query->where('users.company_id', $request['company_id']);
                        }
                        if (!empty($request['role_id'])) {
                            $query->where('users.role', $request['role_id']);
                        }
                        if (!empty($request['module'])) {
                            $query->where('audit_trail.module', $request['module']);
                        }
                        if (!empty($request['file_id'])) {
                            $query->where('users.file_id', $request['file_id']);
                        }
                        // if(!empty($request['strata'])) {
                        //     $query->where('strata.id', $request['strata']);
                        // }
                        if (!empty($request['date_from']) && empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $query->where('audit_trail.created_at', '>=', $date_from);
                        }
                        if (!empty($request['date_to']) && empty($request['date_from'])) {
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->where('audit_trail.created_at', '<=', $date_to . " 23:59:59");
                        }
                        if (!empty($request['date_from']) && !empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->whereBetween('audit_trail.created_at', [$date_from, $date_to . ' 23:59:59']);
                        }
                    })
                    ->make(true);
            }
        }
        $data = AuditTrail::getAnalyticData();

        $viewData = array(
            'title' => trans('app.menus.reporting.audit_trail_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'audit_trail_list',
            'data' => $data,
            'image' => ""
        );

        return View::make('report_en.audit_trail', $viewData);
    }

    public function auditLogon()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(24));

        if (Request::ajax()) {
            $request = Request::all();
            $request['module'] = 'System Authentication';
            $data = AuditTrail::getAnalyticData($request);
            if (!empty($request['filter'])) {
                return Response::json([
                    'data' => $data,
                    'success' => true,
                ]);
            } else {
                $models = AuditTrail::self()
                    ->where('audit_trail.module', 'System Authentication')
                    ->select(['audit_trail.*', 'company.short_name as company', 'users.full_name as full_name', 'role.name as role_name', 'files.file_no']);

                return Datatables::of($models)
                    ->editColumn('company_id', function ($model) {
                        if ($model->company_id > 0) {
                            return $model->company;
                        }
                        return Str::upper($model->user->getCOB->short_name);
                    })
                    ->editColumn('file_id', function ($model) {
                        if ($model->file_id > 0) {
                            return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file->id)) . "'>" . $model->file_no . "</a>";
                        }
                        if ($model->user && ($model->user->isJMB() || $model->user->isMC() || $model->user->isDeveloper())) {
                            if (empty($model->user->getFile)) {
                                return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->user->getFile->id)) . "'>" . $model->user->getFile->file_no . "</a>";
                            }
                        }
                        return '-';
                    })
                    ->editColumn('remarks', function ($model) {
                        if (is_array($model->remarks)) {
                        }
                        return $model->remarks;
                    })
                    ->editColumn('audit_by', function ($model) {
                        return $model->user->full_name;
                    })
                    ->editColumn('role_name', function ($model) {
                        return ($model->user->getAdmin()) ? trans('System Administrator') : Str::upper($model->role_name);
                    })
                    ->editColumn('created_at', function ($model) {
                        return date('d-m-Y H:i A', strtotime($model->created_at));
                    })
                    // ->addColumn('strata_name', function($model) {
                    //     if($model->file_id > 0) {
                    //         return $model->strata_name;
                    //     }
                    //     return Auth::user()->getFile->strata->name;
                    // })
                    ->filter(function ($query) use ($request) {
                        if (!empty($request['company_id'])) {
                            $query->where('users.company_id', $request['company_id']);
                        }
                        if (!empty($request['role_id'])) {
                            $query->where('users.role', $request['role_id']);
                        }
                        if (!empty($request['module'])) {
                            $query->where('audit_trail.module', $request['module']);
                        }
                        if (!empty($request['file_id'])) {
                            $query->where('users.file_id', $request['file_id']);
                        }
                        // if(!empty($request['strata'])) {
                        //     $query->where('strata.id', $request['strata']);
                        // }
                        if (!empty($request['date_from']) && empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $query->where('audit_trail.created_at', '>=', $date_from);
                        }
                        if (!empty($request['date_to']) && empty($request['date_from'])) {
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->where('audit_trail.created_at', '<=', $date_to . " 23:59:59");
                        }
                        if (!empty($request['date_from']) && !empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->whereBetween('audit_trail.created_at', [$date_from, $date_to . ' 23:59:59']);
                        }
                    })
                    ->make(true);
            }
        }
        $request['module'] = 'System Authentication';
        $data = AuditTrail::getAnalyticData($request);

        $viewData = array(
            'title' => trans('app.menus.reporting.audit_logon_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'audit_logon_list',
            'data' => $data,
            'image' => ""
        );

        return View::make('report_en.audit_logon', $viewData);
    }

    public function auditLogonOld()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(24));

        if (Request::ajax()) {
            $request = Request::all();
            $request['module'] = 'System Administration';
            $request['description'] = 'signed';
            $data = AuditTrail::getAnalyticData($request);
            if (!empty($request['filter'])) {
                return Response::json([
                    'data' => $data,
                    'success' => true,
                ]);
            } else {
                $models = AuditTrail::self()
                    ->where('audit_trail.module', 'System Administration')
                    ->where('audit_trail.remarks', "LIKE", "%signed%")
                    ->select(['audit_trail.*', 'company.short_name as company', 'users.full_name as full_name', 'role.name as role_name', 'files.file_no']);

                return Datatables::of($models)
                    ->editColumn('company_id', function ($model) {
                        if ($model->company_id > 0) {
                            return $model->company;
                        }
                        return Str::upper($model->user->getCOB->short_name);
                    })
                    ->editColumn('file_id', function ($model) {
                        if ($model->file_id > 0) {
                            return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file->id)) . "'>" . $model->file_no . "</a>";
                        }
                        if ($model->user && ($model->user->isJMB() || $model->user->isMC() || $model->user->isDeveloper())) {
                            if (empty($model->user->getFile)) {
                                return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->user->getFile->id)) . "'>" . $model->user->getFile->file_no . "</a>";
                            }
                        }
                        return '-';
                    })
                    ->editColumn('remarks', function ($model) {
                        if (is_array($model->remarks)) {
                        }
                        return $model->remarks;
                    })
                    ->editColumn('audit_by', function ($model) {
                        return $model->user->full_name;
                    })
                    ->editColumn('role_name', function ($model) {
                        return ($model->user->getAdmin()) ? trans('System Administrator') : Str::upper($model->role_name);
                    })
                    ->editColumn('created_at', function ($model) {
                        return date('d-m-Y H:i A', strtotime($model->created_at));
                    })
                    // ->addColumn('strata_name', function($model) {
                    //     if($model->file_id > 0) {
                    //         return $model->strata_name;
                    //     }
                    //     return Auth::user()->getFile->strata->name;
                    // })
                    ->filter(function ($query) use ($request) {
                        if (!empty($request['company_id'])) {
                            $query->where('users.company_id', $request['company_id']);
                        }
                        if (!empty($request['role_id'])) {
                            $query->where('users.role', $request['role_id']);
                        }
                        if (!empty($request['module'])) {
                            $query->where('audit_trail.module', $request['module']);
                        }
                        if (!empty($request['file_id'])) {
                            $query->where('users.file_id', $request['file_id']);
                        }
                        // if(!empty($request['strata'])) {
                        //     $query->where('strata.id', $request['strata']);
                        // }
                        if (!empty($request['date_from']) && empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $query->where('audit_trail.created_at', '>=', $date_from);
                        }
                        if (!empty($request['date_to']) && empty($request['date_from'])) {
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->where('audit_trail.created_at', '<=', $date_to . " 23:59:59");
                        }
                        if (!empty($request['date_from']) && !empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->whereBetween('audit_trail.created_at', [$date_from, $date_to . ' 23:59:59']);
                        }
                    })
                    ->make(true);
            }
        }
        $request['module'] = 'System Administration';
        $request['description'] = 'signed';
        $data = AuditTrail::getAnalyticData($request);

        $viewData = array(
            'title' => trans('app.menus.reporting.audit_logon_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'audit_logon_old_list',
            'data' => $data,
            'image' => ""
        );

        return View::make('report_en.audit_logon_old', $viewData);
    }

    //file by location
    public function fileByLocation()
    {
        $strata = Strata::get();
        $categoryList = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $facilityList = Config::get('constant.module.cob.facility');
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(25));

        $viewData = array(
            'title' => trans('app.menus.reporting.file_by_location_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'file_by_location_list',
            'strata' => $strata,
            'categoryList' => $categoryList,
            'facilityList' => $facilityList,
            'image' => ""
        );

        return View::make('report_en.file_by_location', $viewData);
    }

    public function getFileByLocation()
    {
        $data = array();
        $request = Request::all();
        $query = Files::where('is_deleted', 0);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id);
            } else {
                $query = $query->where('company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('company_id', Session::get('admin_cob'));
            }
        }
        $files = $query->orderBy('id', 'asc')->get();

        if ($files->count() > 0) {
            $file_ids = array_pluck($files, "id");

            $strata = Strata::with(['parliment', 'duns', 'parks', 'file', 'facility'])
                ->join('facility', 'strata.id', '=', 'facility.strata_id')
                ->whereIn('strata.file_id', $file_ids)
                ->select(['strata.*'])
                ->where(function ($query) use ($request) {
                    if (!empty($request['category'])) {
                        $query->where('strata.category', $request['category']);
                    }
                    if (!empty($request['facility'])) {
                        $column = 'facility.' . $request['facility'];
                        $query->where("$column", true);
                    }
                })
                ->chunk("500", function ($models) use (&$data) {
                    foreach ($models as $model) {
                        $parliament = $model->parliment;
                        $dun = $model->duns;
                        $park = $model->parks;
                        $files = $model->file;

                        if (count($parliament) > 0) {
                            $parliament_name = $parliament->description;
                        } else {
                            $parliament_name = "-";
                        }
                        if (count($dun) > 0) {
                            $dun_name = $dun->description;
                        } else {
                            $dun_name = "-";
                        }
                        if (count($park) > 0) {
                            $park_name = $park->description;
                        } else {
                            $park_name = "-";
                        }
                        if ($model->name == "") {
                            $strata_name = "-";
                        } else {
                            $strata_name = $model->name;
                        }

                        $data_raw = array(
                            $parliament_name,
                            $dun_name,
                            $park_name,
                            $files->file_no,
                            $strata_name
                        );

                        array_push($data, $data_raw);
                    }
                });
        }

        $output_raw = array(
            "aaData" => $data
        );

        $output = json_encode($output_raw);
        return $output;

        // if (count($file) > 0) {
        //     // foreach ($file as $files) {
        //         $file_ids = array_pluck($file, 'id');
        //         // $strata = Strata::where('file_id', $files->id)->get();
        //         $strata = Strata::with(['parliment','duns','parks','file'])->whereIn('file_id', $file_ids)->get();

        //         if (count($strata) > 0) {
        //             // foreach ($strata as $stratas) {
        //             $strata->reduce(function ($carry, $stratas) use(&$data){
        //                 // $parliament = Parliment::find($stratas->parliament);
        //                 // $dun = Dun::find($stratas->dun);
        //                 // $park = Park::find($stratas->park);
        //                 // $files = Files::find($stratas->file_id);
        //                 $parliament = $stratas->parliment;
        //                 $dun = $stratas->duns;
        //                 $park =$stratas->parks;
        //                 $files = $stratas->file;

        //                 if (count($parliament) > 0) {
        //                     $parliament_name = $parliament->description;
        //                 } else {
        //                     $parliament_name = "-";
        //                 }
        //                 if (count($dun) > 0) {
        //                     $dun_name = $dun->description;
        //                 } else {
        //                     $dun_name = "-";
        //                 }
        //                 if (count($park) > 0) {
        //                     $park_name = $park->description;
        //                 } else {
        //                     $park_name = "-";
        //                 }
        //                 if ($stratas->name == "") {
        //                     $strata_name = "-";
        //                 } else {
        //                     $strata_name = $stratas->name;
        //                 }

        //                 $data_raw = array(
        //                     $parliament_name,
        //                     $dun_name,
        //                     $park_name,
        //                     $files->file_no,
        //                     $strata_name
        //                 );

        //                 array_push($data, $data_raw);
        //             });
        //         }
        //     // }

        //     $output_raw = array(
        //         "aaData" => $data
        //     );

        //     $output = json_encode($output_raw);
        //     return $output;
        // } else {
        //     $output_raw = array(
        //         "aaData" => []
        //     );

        //     $output = json_encode($output_raw);
        //     return $output;
        // }
    }

    //management summary
    public function managementSummary()
    {
        $data = Files::getManagementSummaryCOB();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(27));

        $viewData = array(
            'title' => trans('app.menus.reporting.management_summary_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'management_summary_list',
            'data' => $data,
            'image' => ""
        );

        return View::make('report_en.management_summary', $viewData);
    }

    //cob file / management
    public function cobFileManagement()
    {
        $data = Files::getManagementSummaryCOB();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(28));

        $viewData = array(
            'title' => trans('app.menus.reporting.cob_file_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'cob_file_management_list',
            'data' => $data,
            'image' => ""
        );

        return View::make('report_en.cob_file_management', $viewData);
    }

    public function ownerTenant()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(49));

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
            $file_id = Helper::decode($data['file_id']);
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

    public function strataProfileV2()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(29));

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            $data = Files::getStrataProfileAnalytic([], true, true);
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $data = [];
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $data = Files::getStrataProfileAnalytic([], true, true);
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.strata_profile_v2'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'strata_profile_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'parliament' => $parliament,
            'data' => $data,
            'image' => '',
        );

        return View::make('report_en.strata_profile_v2', $viewData);
    }

    public function getStrataProfileV2()
    {
        $request = Request::all();
        $proceed = false;
        $data = array();

        if (!Auth::user()->getAdmin()) {
            $proceed = true;
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $proceed = true;
            } else if (!empty($request['company_id'])) {
                $proceed = true;
            }
        }

        if ($proceed) {
            $query = Files::with(['financeLatest', 'company'])
                ->file();

            $query = $query->where('files.is_active', true);

            if (!empty($request['company_id'])) {
                $company = Company::where('short_name', $request['company_id'])->first();
                $query = $query->where('files.company_id', $company->id);
            }

            $files = $query->chunk(500, function ($files) use (&$data) {
                foreach ($files as $file) {
                    $finance = $file->financeLatest;
                    if ($finance) {
                        $finance_income_semasa = $finance->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                        $finance_report_fee_semasa = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                        $finance_report_fee_semasa = $finance_report_fee_semasa + $finance->financeReportExtra()->where('type', 'SF')->sum('fee_semasa');

                        if ($finance_report_fee_semasa > 0) {
                            $percentage = round(($finance_income_semasa / $finance_report_fee_semasa) * 100);

                            if ($percentage >= 80) {
                                $zone = 'Biru';
                            } else if ($percentage < 79 && $percentage >= 40) {
                                $zone = 'Kuning';
                            } else {
                                $zone = 'Merah';
                            }
                        } else {
                            $zone = 'Merah';
                        }
                    } else {
                        continue;
                    }

                    $data_raw = array(
                        "<a style='text-decoration:underline;' href='" . URL::action('ReportController@viewStrataProfile', Helper::encode($file->id)) . "'>" . $file->file_no . "</a>",
                        $file->strata->name,
                        $file->company->short_name,
                        ($file->strata->parliment) ? $file->strata->parliment->description : '-',
                        $zone
                    );

                    array_push($data, $data_raw);
                }
            });
        }

        $output_raw = array(
            "aaData" => $data
        );

        $output = json_encode($output_raw);
        return $output;
    }

    public function getStrataProfileAnalyticV2()
    {
        try {
            $request = Request::all();
            $proceed = false;

            if (!Auth::user()->getAdmin()) {
                $proceed = true;
            } else {
                if (!empty(Session::get('admin_cob'))) {
                    $proceed = true;
                } else if (!empty($request['company_id'])) {
                    $proceed = true;
                }
            }

            if ($proceed) {
                $items = Files::getStrataProfileAnalytic($request, true, true);
                $response = [
                    'success' => true,
                    'data' => $items
                ];
            } else {
                $response = [
                    'success' => false,
                    'data' => '',
                ];
            }

            return Response::json($response);
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function strataProfile()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(29));

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            $data = Files::getStrataProfileAnalytic();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $data = [];
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $data = Files::getStrataProfileAnalytic();
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.strata_profile'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'strata_profile_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'parliament' => $parliament,
            'data' => $data,
            'image' => '',
        );

        return View::make('report_en.strata_profile', $viewData);
    }

    public function getStrataProfile()
    {
        $request = Request::all();
        $proceed = false;
        $data = array();

        if (!Auth::user()->getAdmin()) {
            $proceed = true;
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $proceed = true;
            } else if (!empty($request['company_id'])) {
                $proceed = true;
            }
        }

        if ($proceed) {
            $query = Files::with(['financeLatest', 'company'])
                ->file();

            if (!empty($request['company_id'])) {
                $company = Company::where('short_name', $request['company_id'])->first();
                $query = $query->where('files.company_id', $company->id);
            }

            $files = $query->chunk(500, function ($files) use (&$data) {
                foreach ($files as $file) {
                    $finance = $file->financeLatest;
                    if ($finance) {
                        $finance_income_semasa = $finance->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                        $finance_report_fee_semasa = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                        $finance_report_fee_semasa = $finance_report_fee_semasa + $finance->financeReportExtra()->where('type', 'SF')->sum('fee_semasa');

                        if ($finance_report_fee_semasa > 0) {
                            $percentage = round(($finance_income_semasa / $finance_report_fee_semasa) * 100);

                            if ($percentage >= 80) {
                                $zone = 'Biru';
                            } else if ($percentage < 79 && $percentage >= 40) {
                                $zone = 'Kuning';
                            } else {
                                $zone = 'Merah';
                            }
                        } else {
                            $zone = 'Kelabu';
                        }
                    } else {
                        $zone = 'Kelabu';
                    }
                    $data_raw = array(
                        "<a style='text-decoration:underline;' href='" . URL::action('ReportController@viewStrataProfile', Helper::encode($file->id)) . "'>" . $file->file_no . "</a>",
                        $file->strata->name,
                        $file->company->short_name,
                        ($file->strata->parliment) ? $file->strata->parliment->description : '-',
                        $zone
                    );

                    array_push($data, $data_raw);
                }
            });
        }

        $output_raw = array(
            "aaData" => $data
        );

        $output = json_encode($output_raw);

        return $output;
    }

    public function getStrataProfileFinance($file_id)
    {
        $finance = Finance::with(['financeIncome', 'financeReport', 'financeReportExtra'])
            ->join('files', 'finance_file.file_id', '=', 'files.id')
            ->where('finance_file.file_id', $file_id)
            ->where('finance_file.is_active', true)
            ->where('finance_file.is_deleted', false)
            ->selectRaw('finance_file.*, files.file_no')
            ->orderBy('finance_file.year', 'desc')
            ->orderBy('finance_file.month', 'desc');

        return Datatables::of($finance)
            ->addColumn('zone', function ($model) {
                $finance_income_semasa = $model->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                $finance_report_fee_semasa = $model->financeReport()->where('type', 'SF')->sum('fee_semasa');
                $finance_report_fee_semasa = $finance_report_fee_semasa + $model->financeReportExtra()->where('type', 'SF')->sum('fee_semasa');

                if ($finance_report_fee_semasa > 0) {
                    $percentage = round(($finance_income_semasa / $finance_report_fee_semasa) * 100);

                    if ($percentage >= 80) {
                        $zone = 'Biru';
                    } else if ($percentage < 79 && $percentage >= 40) {
                        $zone = 'Kuning';
                    } else {
                        $zone = 'Merah';
                    }
                } else {
                    $zone = 'Kelabu';
                }
                return $zone;
            })
            ->editColumn('file_id', function ($model) {
                return "<a style='text-decoration:underline;' href='" . URL::action('FinanceController@editFinanceFileList', Helper::encode($model->id)) . "'>" . $model->file_no . " " . $model->year . "-" . strtoupper($model->monthName()) . "</a>";
            })
            ->make(true);
    }

    public function getStrataProfileAnalytic()
    {
        try {
            $request = Request::all();
            $proceed = false;

            if (!Auth::user()->getAdmin()) {
                $proceed = true;
            } else {
                if (!empty(Session::get('admin_cob'))) {
                    $proceed = true;
                } else if (!empty($request['company_id'])) {
                    $proceed = true;
                }
            }

            if ($proceed) {
                $items = Files::getStrataProfileAnalytic($request);
                $response = [
                    'success' => true,
                    'data' => $items
                ];
            } else {
                $response = [
                    'success' => false,
                    'data' => '',
                ];
            }

            return Response::json($response);
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function viewStrataProfile($id)
    {
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
            $tnb = '';

            $files = Files::with(['financeLatest'])->findOrFail(Helper::decode($id));
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
                $zone = 'KELABU';
                $ageing = [];

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

                if ($finance = $files->financeLatest) {
                    $finance_income_semasa = $finance->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                    $finance_report_fee_semasa = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                    $finance_report_fee_semasa = $finance_report_fee_semasa + $finance->financeReportExtra()->where('type', 'SF')->sum('fee_semasa');

                    if ($finance_report_fee_semasa > 0) {
                        $purata_dikutip = round(($finance_income_semasa / $finance_report_fee_semasa) * 100, 2);
                        if ($purata_dikutip >= 80) {
                            $zone = 'BIRU';
                        } else if ($purata_dikutip < 79 && $purata_dikutip >= 40) {
                            $zone = 'KUNING';
                        } else {
                            $zone = "MERAH";
                        }
                    } else {
                        $zone = 'KELABU';
                    }
                } else {
                    $zone = "KELABU";
                }

                $ageing = $files->financeAgeing();
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
                'tnb' => $tnb,
                'purata_dikutip' => $purata_dikutip,
                'ageing' => $ageing['data'],
                'ageing_graph' => $ageing['graph'],
            );

            // return '<pre>' . print_r($result, true) . '</pre>';

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
    public function purchaser()
    {
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
                ->select(['buyer.*'])
                ->where('company.short_name', $cob_company)
                ->where('files.file_no', $file_no)
                ->where('buyer.is_deleted', 0)
                ->where('files.is_deleted', 0)
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
                ->where('files.is_deleted', 0)
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
                ->where('files.is_deleted', 0)
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

    //tenant
    public function tenant()
    {
        $tenant = array();
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
            $tenant = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                ->join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->select(['tenant.*'])
                ->where('company.short_name', $cob_company)
                ->where('files.file_no', $file_no)
                ->where('tenant.is_deleted', 0)
                ->where('files.is_deleted', 0)
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
                ->where('files.is_deleted', 0)
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
                ->where('files.is_deleted', 0)
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
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmtenantsub_list',
            'image' => "",
            'file_no' => $file_no,
            'file_name' => $file_name,
            'cob_company' => $cob_company,
            'cob_name' => $cob_name,
            'tenant' => $tenant
        );

        return View::make('report_en.tenant', $viewData);
    }

    /*
     * Complaint Report
     */

    public function complaint()
    {
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
            $cob_id = Helper::decode($data['cob_id']);
            $file_info = Files::getComplaintReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getComplaintReportByCOB();
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(51));

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

    public function insurance()
    {
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
            $cob_id = Helper::decode($data['cob_id']);
            $file_info = Files::getInsuranceReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getInsuranceReportByCOB();
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(50));

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

    public function collection()
    {
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
            $cob_id = Helper::decode($data['cob_id']);
            $file_info = Files::getCollectionReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getCollectionReportByCOB();
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(52));

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

    public function council()
    {
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
            $cob_id = Helper::decode($data['cob_id']);
            $file_info = Files::getCouncilReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getCouncilReportByCOB();
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(53));

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

    public function dun()
    {
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
            $cob_id = Helper::decode($data['cob_id']);
            $file_info = Files::getDunReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getDunReportByCOB();
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(54));

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

        // return '<pre>' . print_r($file_info, true) . '</pre>';

        return View::make('report_en.dun', $viewData);
    }

    public function parliment()
    {
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
            $cob_id = Helper::decode($data['cob_id']);
            $file_info = Files::getParlimentReportByCOB($cob_id);
        } else {
            $cob_id = '';
            $file_info = Files::getParlimentReportByCOB();
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(55));

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

    public function vp()
    {
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(56));

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

    public function management()
    {
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
            array('value' => 'Developer', 'name' => 'Developer'),
            array('value' => 'JMB', 'name' => 'JMB'),
            array('value' => 'MC', 'name' => 'MC'),
            array('value' => 'Agent', 'name' => 'Agent'),
            array('value' => 'Others', 'name' => 'Others')
        );

        $filename = Files::getFileName();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(57));

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

    public function managementList()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $request = Input::all();

        $cob_company = '';
        $cob_name = 'All COB';
        if (isset($request['company']) && !empty($request['company'])) {
            $cob_company = $request['company'];
            $cob_name = $request['company'];
        }

        $file_no = '';
        $file_name = 'All Files';
        if (isset($request['file_no']) && !empty($request['file_no'])) {
            $file_no = $request['file_no'];
            $file_name = $request['file_no'];
        }

        $filename = '';
        $fname = 'All File Names';
        if (isset($request['file_name']) && !empty($request['file_name'])) {
            $filename = $request['file_name'];
            $fname = $request['file_name'];
        }

        $type = '';
        $type_name = 'All Types';
        if (isset($request['type']) && !empty($request['type'])) {
            $type = $request['type'];
            $type_name = $request['type'];
        }

        $date_from = '';
        if (isset($request['date_from']) && !empty($request['date_from'])) {
            $date_from = $request['date_from'];
        }

        $date_to = '';
        if (isset($request['date_to']) && !empty($request['date_to'])) {
            $date_to = $request['date_to'];
        }

        if (!empty($date_from) && empty($date_to)) {
            $date_to = date('Y-m-d');
        }

        if ((!empty($cob_company) && !empty($file_no)) && !empty($filename)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->select(['files.*'])
                ->where('company.short_name', $cob_company)
                ->where('files.file_no', $file_no)
                ->where('strata.name', $filename)
                ->where('files.is_deleted', 0)
                ->orderBy('company.short_name', 'ASC')
                ->orderBy('files.file_no', 'ASC')
                ->get();
        } else if (!empty($cob_company) && !empty($filename)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->select(['files.*'])
                ->where('company.short_name', $cob_company)
                ->where('strata.name', $filename)
                ->where('files.is_deleted', 0)
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
                ->where('files.is_deleted', 0)
                ->orderBy('company.short_name', 'ASC')
                ->orderBy('files.file_no', 'ASC')
                ->get();
        } else if (!empty($cob_company) && !empty($file_no)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->select(['files.*'])
                ->where('company.short_name', $cob_company)
                ->where('files.file_no', $file_no)
                ->where('files.is_deleted', 0)
                ->orderBy('company.short_name', 'ASC')
                ->orderBy('files.file_no', 'ASC')
                ->get();
        } else if (!empty($cob_company)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->select(['files.*'])
                ->where('company.short_name', $cob_company)
                ->where('files.is_deleted', 0)
                ->orderBy('company.short_name', 'ASC')
                ->orderBy('files.file_no', 'ASC')
                ->get();
        } else if (!empty($file_no)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->select(['files.*'])
                ->where('files.file_no', $file_no)
                ->where('files.is_deleted', 0)
                ->orderBy('company.short_name', 'ASC')
                ->orderBy('files.file_no', 'ASC')
                ->get();
        } else if (!empty($filename)) {
            $files = Files::join('company', 'files.company_id', '=', 'company.id')
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->select(['files.*'])
                ->where('strata.name', $filename)
                ->where('files.is_deleted', 0)
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

        if ($files->count() > 0) {
            $data = array();

            $files->reduce(function ($carry, $file) use (&$data, &$date_from, &$date_to, &$type) {
                $data_raw = array();

                if ($type == 'JMB') {
                    if ($file->managementJMB) {
                        if (!empty($date_from) && !empty($date_to)) {
                            if (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00') {
                                if ($file->managementJMB->date_formed >= $date_from && $file->managementJMB->date_formed <= $date_to) {
                                    $data_raw = array(
                                        $file->company->short_name,
                                        $file->file_no,
                                        $file->strata->name,
                                        'JMB',
                                        (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00' ? $file->managementJMB->date_formed : ''),
                                        $file->managementJMB->name,
                                        ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                                        $file->managementJMB->email,
                                        $file->managementJMB->phone_no
                                    );
                                }
                            }
                        } else {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'JMB',
                                (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00' ? $file->managementJMB->date_formed : ''),
                                $file->managementJMB->name,
                                ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                                $file->managementJMB->email,
                                $file->managementJMB->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }
                } else if ($type == 'MC') {
                    if ($file->managementMC) {
                        if (!empty($date_from) && !empty($date_to)) {
                            if (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00') {
                                if ($file->managementMC->date_formed >= $date_from && $file->managementMC->date_formed <= $date_to) {
                                    $data_raw = array(
                                        $file->company->short_name,
                                        $file->file_no,
                                        $file->strata->name,
                                        'MC',
                                        (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00' ? $file->managementMC->date_formed : ''),
                                        $file->managementMC->name,
                                        ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                                        $file->managementMC->email,
                                        $file->managementMC->phone_no
                                    );
                                }
                            }
                        } else {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'MC',
                                (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00' ? $file->managementMC->date_formed : ''),
                                $file->managementMC->name,
                                ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                                $file->managementMC->email,
                                $file->managementMC->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }
                } else if ($type == 'Agent') {
                    if ($file->managementAgent) {
                        if (empty($date_from) && empty($date_to)) {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'Agent',
                                '',
                                $file->managementAgent->agent,
                                ($file->managementAgent->address1 ? $file->managementAgent->address1 : '') . ($file->managementAgent->address2 ? '<br/>' . $file->managementAgent->address2 : '') . ($file->managementAgent->address3 ? '<br/>' . $file->managementAgent->address3 : ''),
                                $file->managementAgent->email,
                                $file->managementAgent->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }
                } else if ($type == 'Others') {
                    if ($file->managementOthers) {
                        if (empty($date_from) && empty($date_to)) {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'Others',
                                '',
                                $file->managementOthers->name,
                                ($file->managementOthers->address1 ? $file->managementOthers->address1 : '') . ($file->managementOthers->address2 ? '<br/>' . $file->managementOthers->address2 : '') . ($file->managementOthers->address3 ? '<br/>' . $file->managementOthers->address3 : ''),
                                $file->managementOthers->email,
                                $file->managementOthers->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }
                } else if ($type == 'Developer') {
                    if ($file->managementDeveloper) {
                        if (empty($date_from) && empty($date_to)) {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'Developer',
                                '',
                                $file->managementDeveloper->name,
                                ($file->managementDeveloper->address_1 ? $file->managementDeveloper->address_1 : '') . ($file->managementDeveloper->address_2 ? '<br/>' . $file->managementDeveloper->address_2 : '') . ($file->managementDeveloper->address_3 ? '<br/>' . $file->managementDeveloper->address_3 : ''),
                                '',
                                $file->managementDeveloper->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }
                } else {
                    if ($file->managementJMB) {
                        if (!empty($date_from) && !empty($date_to)) {
                            if (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00') {
                                if ($file->managementJMB->date_formed >= $date_from && $file->managementJMB->date_formed <= $date_to) {
                                    $data_raw = array(
                                        $file->company->short_name,
                                        $file->file_no,
                                        $file->strata->name,
                                        'JMB',
                                        (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00' ? $file->managementJMB->date_formed : ''),
                                        $file->managementJMB->name,
                                        ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                                        $file->managementJMB->email,
                                        $file->managementJMB->phone_no
                                    );
                                }
                            }
                        } else {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'JMB',
                                (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00' ? $file->managementJMB->date_formed : ''),
                                $file->managementJMB->name,
                                ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                                $file->managementJMB->email,
                                $file->managementJMB->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }

                    if ($file->managementMC) {
                        if (!empty($date_from) && !empty($date_to)) {
                            if (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00') {
                                if ($file->managementMC->date_formed >= $date_from && $file->managementMC->date_formed <= $date_to) {
                                    $data_raw = array(
                                        $file->company->short_name,
                                        $file->file_no,
                                        $file->strata->name,
                                        'MC',
                                        (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00' ? $file->managementMC->date_formed : ''),
                                        $file->managementMC->name,
                                        ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                                        $file->managementMC->email,
                                        $file->managementMC->phone_no
                                    );
                                }
                            }
                        } else {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'MC',
                                (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00' ? $file->managementMC->date_formed : ''),
                                $file->managementMC->name,
                                ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                                $file->managementMC->email,
                                $file->managementMC->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }

                    if ($file->managementAgent) {
                        if (empty($date_from) && empty($date_to)) {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'Agent',
                                '',
                                $file->managementAgent->agent,
                                ($file->managementAgent->address1 ? $file->managementAgent->address1 : '') . ($file->managementAgent->address2 ? '<br/>' . $file->managementAgent->address2 : '') . ($file->managementAgent->address3 ? '<br/>' . $file->managementAgent->address3 : ''),
                                $file->managementAgent->email,
                                $file->managementAgent->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }

                    if ($file->managementOthers) {
                        if (empty($date_from) && empty($date_to)) {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'Others',
                                '',
                                $file->managementOthers->name,
                                ($file->managementOthers->address1 ? $file->managementOthers->address1 : '') . ($file->managementOthers->address2 ? '<br/>' . $file->managementOthers->address2 : '') . ($file->managementOthers->address3 ? '<br/>' . $file->managementOthers->address3 : ''),
                                $file->managementOthers->email,
                                $file->managementOthers->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }

                    if ($file->managementDeveloper) {
                        if (empty($date_from) && empty($date_to)) {
                            $data_raw = array(
                                $file->company->short_name,
                                $file->file_no,
                                $file->strata->name,
                                'Developer',
                                '',
                                $file->managementDeveloper->name,
                                ($file->managementDeveloper->address_1 ? $file->managementDeveloper->address_1 : '') . ($file->managementDeveloper->address_2 ? '<br/>' . $file->managementDeveloper->address_2 : '') . ($file->managementDeveloper->address_3 ? '<br/>' . $file->managementDeveloper->address_3 : ''),
                                '',
                                $file->managementDeveloper->phone_no
                            );
                        }

                        if (!empty($data_raw)) {
                            array_push($data, $data_raw);
                        }
                    }
                }
            });
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
            'date_from' => $date_from,
            'date_to' => $date_to,
            'result' => $data,
            'image' => ''
        );

        return View::make('report_en.management_list', $viewData);
    }

    public function getManagementList()
    {
        $request = Request::all();

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::with(['company', 'strata'])->where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $files = Files::with(['company', 'strata'])->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::with(['company', 'strata'])->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $files = Files::with(['company', 'strata'])->where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        if ($files->count() > 0) {
            $data = array();

            $date_from = '';
            if (isset($request['date_from']) && !empty($request['date_from'])) {
                $date_from = $request['date_from'];
            }

            $date_to = '';
            if (isset($request['date_to']) && !empty($request['date_to'])) {
                $date_to = $request['date_to'];
            }

            if (!empty($date_from) && empty($date_to)) {
                $date_to = date('Y-m-d');
            }

            $files->reduce(function ($carry, $file) use (&$data, &$date_from, &$date_to) {
                $data_raw = array();

                if ($file->managementJMB) {
                    if (!empty($date_from) && !empty($date_to)) {
                        if (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00') {
                            if ($file->managementJMB->date_formed >= $date_from && $file->managementJMB->date_formed <= $date_to) {
                                $data_raw = array(
                                    $file->company->short_name,
                                    $file->file_no,
                                    $file->strata->name,
                                    'JMB',
                                    (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00' ? $file->managementJMB->date_formed : ''),
                                    $file->managementJMB->name,
                                    ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                                    $file->managementJMB->email,
                                    $file->managementJMB->phone_no
                                );
                            }
                        }
                    } else {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'JMB',
                            (!empty($file->managementJMB->date_formed) && $file->managementJMB->date_formed != '0000-00-00' ? $file->managementJMB->date_formed : ''),
                            $file->managementJMB->name,
                            ($file->managementJMB->address1 ? $file->managementJMB->address1 : '') . ($file->managementJMB->address2 ? '<br/>' . $file->managementJMB->address2 : '') . ($file->managementJMB->address3 ? '<br/>' . $file->managementJMB->address3 : ''),
                            $file->managementJMB->email,
                            $file->managementJMB->phone_no
                        );
                    }

                    if (!empty($data_raw)) {
                        array_push($data, $data_raw);
                    }
                }

                if ($file->managementMC) {
                    if (!empty($date_from) && !empty($date_to)) {
                        if (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00') {
                            if ($file->managementMC->date_formed >= $date_from && $file->managementMC->date_formed <= $date_to) {
                                $data_raw = array(
                                    $file->company->short_name,
                                    $file->file_no,
                                    $file->strata->name,
                                    'MC',
                                    (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00' ? $file->managementMC->date_formed : ''),
                                    $file->managementMC->name,
                                    ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                                    $file->managementMC->email,
                                    $file->managementMC->phone_no
                                );
                            }
                        }
                    } else {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'MC',
                            (!empty($file->managementMC->date_formed) && $file->managementMC->date_formed != '0000-00-00' ? $file->managementMC->date_formed : ''),
                            $file->managementMC->name,
                            ($file->managementMC->address1 ? $file->managementMC->address1 : '') . ($file->managementMC->address2 ? '<br/>' . $file->managementMC->address2 : '') . ($file->managementMC->address3 ? '<br/>' . $file->managementMC->address3 : ''),
                            $file->managementMC->email,
                            $file->managementMC->phone_no
                        );
                    }

                    if (!empty($data_raw)) {
                        array_push($data, $data_raw);
                    }
                }

                if ($file->managementAgent) {
                    if (empty($date_from) && empty($date_to)) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'Agent',
                            '',
                            $file->managementAgent->agent,
                            ($file->managementAgent->address1 ? $file->managementAgent->address1 : '') . ($file->managementAgent->address2 ? '<br/>' . $file->managementAgent->address2 : '') . ($file->managementAgent->address3 ? '<br/>' . $file->managementAgent->address3 : ''),
                            $file->managementAgent->email,
                            $file->managementAgent->phone_no
                        );
                    }

                    if (!empty($data_raw)) {
                        array_push($data, $data_raw);
                    }
                }

                if ($file->managementOthers) {
                    if (empty($date_from) && empty($date_to)) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'Others',
                            '',
                            $file->managementOthers->name,
                            ($file->managementOthers->address1 ? $file->managementOthers->address1 : '') . ($file->managementOthers->address2 ? '<br/>' . $file->managementOthers->address2 : '') . ($file->managementOthers->address3 ? '<br/>' . $file->managementOthers->address3 : ''),
                            $file->managementOthers->email,
                            $file->managementOthers->phone_no
                        );
                    }

                    if (!empty($data_raw)) {
                        array_push($data, $data_raw);
                    }
                }

                if ($file->managementDeveloper) {
                    if (empty($date_from) && empty($date_to)) {
                        $data_raw = array(
                            $file->company->short_name,
                            $file->file_no,
                            $file->strata->name,
                            'Developer',
                            '',
                            $file->managementDeveloper->name,
                            ($file->managementDeveloper->address_1 ? $file->managementDeveloper->address_1 : '') . ($file->managementDeveloper->address_2 ? '<br/>' . $file->managementDeveloper->address_2 : '') . ($file->managementDeveloper->address_3 ? '<br/>' . $file->managementDeveloper->address_3 : ''),
                            '',
                            $file->managementDeveloper->phone_no
                        );
                    }

                    if (!empty($data_raw)) {
                        array_push($data, $data_raw);
                    }
                }
            });
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

    //rating summary
    public function ratingSummary()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(26));
        $summary_data = Files::getRatingByCategory();
        $rating_data = Files::getDashboardData();

        $viewData = array(
            'title' => trans('app.menus.reporting.rating_summary_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'rating_summary_list',
            'rating_data' => $rating_data,
            'summary_data' => $summary_data,
            'image' => ""
        );

        return View::make('report_en.rating_summary', $viewData);
    }

    public function landTitle()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(60));

        $data = Input::all();

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

        $cob_id = '';
        $land_title_id = '';
        if (isset($data['cob_id']) && !empty($data['cob_id'])) {
            $cob_id = Helper::decode($data['cob_id']);
        }
        if (isset($data['land_title_id']) && !empty($data['land_title_id'])) {
            $land_title_id = $data['land_title_id'];
        }
        $file_info = Files::getLandTitleReportByCOB($cob_id, $land_title_id);

        $viewData = array(
            'title' => trans('app.menus.reporting.land_title'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'land_title_report_list',
            'cob' => $cob,
            'category' => $category,
            'file_info' => $file_info,
            'cob_id' => $cob_id,
            'land_title_id' => $land_title_id,
            'image' => ''
        );

        return View::make('report_en.land_title', $viewData);
    }

    public function epks()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(64));

        if (Request::ajax()) {
            $request = Request::all();
            $data = Epks::getAnalyticData($request);
            if (!empty($request['filter'])) {
                return Response::json([
                    'data' => $data,
                    'success' => true,
                ]);
            }
        }
        $data = Epks::getAnalyticData();

        $viewData = array(
            'title' => trans('app.menus.reporting.epks'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'epks_report_list',
            'data' => $data,
            'image' => ''
        );

        return View::make('report_en.epks', $viewData);
    }

    public function generate()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("Report Generator"));

        if (Request::ajax()) {
            $request = Request::all();
            $model = Files::with([
                'strata.towns',
                'strata.categories',
                'houseScheme.developers',
                'management',
                'managementDeveloperLatest',
                'managementJMBLatest',
                'managementMCLatest',
                'insurance',
                'other',
                'resident',
                'commercial',
                'draft'
            ])
                ->file()
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->join('house_scheme', 'files.id', '=', 'house_scheme.file_id')
                ->join('others_details', 'files.id', '=', 'others_details.file_id')
                ->leftJoin('category', 'category.id', '=', 'strata.category')
                ->leftJoin('developer', 'developer.id', '=', 'house_scheme.developer')
                ->leftJoin('city', 'city.id', '=', 'strata.town')
                ->leftJoin('dun', 'strata.dun', '=', 'dun.id')
                ->leftJoin('area', 'strata.area', '=', 'area.id')
                // ->leftJoin('residential_block', 'strata.id', '=', 'residential_block.strata_id')
                // ->leftJoin('residential_block_extra', 'strata.id', '=', 'residential_block_extra.strata_id')
                // ->leftJoin('commercial_block', 'strata.id', '=', 'commercial_block.strata_id')
                // ->leftJoin('commercial_block_extra', 'strata.id', '=', 'commercial_block_extra.strata_id')
                ->join('management', 'files.id', '=', 'management.file_id')
                ->selectRaw("files.id as id, files.file_no as file_no," .
                    "developer.name as developer_name, strata.name as strata_name, strata.area as area, strata.dun as dun," .
                    "city.description as city_name, category.description as category_name," .
                    "files.is_active as is_active, management.is_jmb as is_jmb, management.is_mc as is_mc," .
                    "management.is_agent as is_agent, management.is_developer as is_developer," .
                    "management.liquidator as liquidator, management.under_10_units as under_10_units," .
                    "management.is_others as is_others, management.bankruptcy as bankruptcy," .
                    "management.no_management as no_management")
                ->where(function ($query) use ($request) {
                    if (!empty($request['file_id'])) {
                        $query->whereIn('files.id', $request['file_id']);
                    }
                    if (!empty($request['city'])) {
                        $query->whereIn('strata.town', $request['city']);
                    }
                    if (!empty($request['category'])) {
                        $query->whereIn('strata.category', $request['category']);
                    }
                    if (!empty($request['developer'])) {
                        $query->whereIn('house_scheme.developer', $request['developer']);
                    }
                    if (!empty($request['dun'])) {
                        $query->whereIn('strata.dun', $request['dun']);
                    }
                    if (!empty($request['area'])) {
                        $query->whereIn('strata.area', $request['area']);
                    }
                });

            return Datatables::of($model)
                ->editColumn('file_no', function ($model) {
                    return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->id)) . "'>" . $model->file_no . "</a>";
                })
                ->editColumn('strata_name', function ($model) {
                    return $model->strata_name ? ucfirst($model->strata_name) : "-";
                })
                ->editColumn('developer', function ($model) {
                    return ucfirst($model->developer_name);
                })
                ->editColumn('dun', function ($model) {
                    return $model->dun ? ucfirst($model->strata->duns->description) : "-";
                })
                ->editColumn('area', function ($model) {
                    return $model->area ? ucfirst($model->strata->areas->description) : "-";
                })
                ->editColumn('city', function ($model) {
                    return ucfirst($model->city_name);
                })
                ->editColumn('category', function ($model) {
                    return ucfirst($model->category_name);
                })
                ->addColumn('management_name', function ($model) {
                    $management = ($model->is_mc) ? $model->managementMCLatest : $model->managementJMBLatest;
                    return (!empty($management) && $management->name) ? $management->name : '-';
                })
                ->addColumn('sum_residential', function ($model) {
                    $sum_residential = Residential::where('file_id', $model->id)->sum('unit_no');
                    $sum_residential_extra = ResidentialExtra::where('file_id', $model->id)->sum('unit_no');
                    return $sum_residential + $sum_residential_extra;
                })
                ->addColumn('sum_commercial', function ($model) {
                    $sum_commercial = Commercial::where('file_id', $model->id)->sum('unit_no');
                    $sum_commercial_extra = CommercialExtra::where('file_id', $model->id)->sum('unit_no');
                    return $sum_commercial + $sum_commercial_extra;
                })
                ->addColumn('management', function ($model) {
                    $content = '';
                    if ($model->is_jmb && !$model->is_mc) {
                        $content .= trans('JMB') . ',';
                    }
                    if ($model->is_mc) {
                        $content .= trans('MC') . ',';
                    }
                    if ($model->is_agent && !$model->is_mc) {
                        $content .= trans('Agent') . ',';
                    }
                    if ($model->is_others && !$model->is_mc) {
                        $content .= trans('Others') . ',';
                    }
                    if ($model->liquidator) {
                        $content .= trans('app.forms.liquidator') . ',';;
                    }
                    if ($model->is_developer) {
                        $content .= trans('app.forms.developer') . ',';;
                    }
                    if ($model->under_10_units) {
                        $content .= trans('app.forms.under_10_units') . ',';;
                    }
                    if ($model->bankruptcy) {
                        $content .= trans('app.forms.bankruptcy') . ',';;
                    }
                    if ($model->no_management) {
                        $content .= trans('app.forms.no_management');
                    }
                    if (!$model->is_jmb && !$model->is_mc && !$model->is_agent && !$model->is_agent && !$model->is_others && !$model->under_10_units && !$model->bankruptcy && !$model->no_management) {
                        $content .= trans('Non-Set');
                    }
                    return rtrim($content, ",");
                })
                ->addColumn('status', function ($model) {
                    return $model->is_active ? trans('app.forms.yes') : trans('app.forms.no');
                })
                ->addColumn('latest_file_draft_date', function ($model) {
                    return !empty($model->draft) ? $model->draft->created_at->toDateTimeString() : '-';
                })
                ->addColumn('latest_insurance_date', function ($model) {
                    return $model->insurance->count() ? $model->insurance()->latest()->first()->created_at->toDateTimeString() : "-";
                })
                ->addColumn('jmb_date_formed', function ($model) {
                    return $model->management->is_jmb ? $model->managementJMBLatest->date_formed : '-';
                })
                ->addColumn('mc_date_formed', function ($model) {
                    return $model->management->is_mc ? $model->managementMCLatest->date_formed : '-';
                })
                ->filter(function ($query) use ($request) {
                    if (!empty($request['management'])) {
                        if (in_array('jmb', $request['management']) && in_array('mc', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('management.is_agent', 1)
                                ->orWhere('management.is_others', 1)
                                ->orWhere('management.is_mc', 1)
                                ->orWhere('management.is_jmb', 1);
                        } else if (in_array('mc', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('management.is_agent', 1)
                                ->orWhere('management.is_others', 1)
                                ->orWhere('management.is_mc', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('management.is_agent', 1)
                                ->orWhere('management.is_others', 1)
                                ->orWhere('management.is_jmb', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('agent', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('management.is_agent', 1)
                                ->orWhere('management.is_mc', 1)
                                ->orWhere('management.is_jmb', 1);
                        } else if (in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('management.is_agent', 1)
                                ->orWhere('management.is_others', 1);
                        } else if (in_array('others', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('management.is_others', 1)
                                ->orWhere('management.is_mc', 1);
                        } else if (in_array('agent', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('management.is_agent', 1)
                                ->orWhere('management.is_mc', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('management.is_jmb', 1)
                                ->orWhere('management.is_others', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('agent', $request['management'])) {
                            $query->where('management.is_jmb', 1)
                                ->orWhere('management.is_agent', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('management.is_jmb', 1)
                                ->orWhere('management.is_mc', 1);
                        } else if (in_array('others', $request['management'])) {
                            $query->where('management.is_others', 1)
                                ->where('management.is_mc', 0);
                        } else if (in_array('agent', $request['management'])) {
                            $query->where('management.is_agent', 1)
                                ->where('management.is_mc', 0);
                        } else if (in_array('mc', $request['management'])) {
                            $query->where('management.is_mc', 1);
                        } else if (in_array('jmb', $request['management'])) {
                            $query->where('management.is_jmb', 1)
                                ->where('management.is_mc', 0)
                                ->where('management.is_agent', false);
                        } else if (in_array('non', $request['management'])) {
                            $query->where('management.no_management', true);
                        } else if (in_array('is_developer', $request['management'])) {
                            $query->where('management.is_developer', true);
                        } else if (in_array('liquidator', $request['management'])) {
                            $query->where('management.liquidator', true);
                        } else if (in_array('under_10_units', $request['management'])) {
                            $query->where('management.under_10_units', true);
                        } else if (in_array('bankruptcy', $request['management'])) {
                            $query->where('management.bankruptcy', true);
                        }
                    }
                })
                ->make(true);
        }
        $management = Request::get('management') ? Request::get('management') : '';

        $viewData = array(
            'title' => trans('app.menus.reporting.generate'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'generate_report_list',
            'management' => $management,
            'image' => ''
        );

        return View::make('report_en.generate', $viewData);
    }

    public function generateSelected()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("Report Generator"));

        $request = Request::all();
        $models = (new ReportRepo())->generateReport($request);
        $route = ($request['export'] == 'excel') ? route('api.v1.export.generateReport') : route('print.generate.index');

        $viewData = array(
            'title' => trans('app.menus.reporting.generate'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'generate_report_list',
            'route' => $route,
            'request_params' => $request,
            'models' => $models,
            'image' => ''
        );

        return View::make('report_en.generate_selected', $viewData);
    }

    public function statistic()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("Statistics Report"));
        $datas = (new ReportRepo())->statisticsReport(Request::all());
        $cities = City::self()->get();
        if (Request::ajax()) {
            return View::make('report_en.statistic.table', compact('datas', 'cities'));
        }
        $last_10_years = Carbon::now()->subYears(10)->format('Y');
        $this_year = Carbon::now()->format('Y');
        $years = [];
        for ($i = $this_year; $i > $last_10_years; $i--) {
            array_push($years, $i);
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.statistic'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'statistic_report_list',
            'cities' => $cities,
            'years' => $years,
            'datas' => $datas,
            'image' => ''
        );

        return View::make('report_en.statistic', $viewData);
    }

    public function fileMovement()
    {
        // $file_movements = FileMovementUser::leftJoin('file_movements', 'file_movement_users.file_movement_id', '=', 'file_movements.id')
        //     ->leftJoin('files', 'file_movements.file_id', '=', 'files.id')
        //     ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
        //     ->leftJoin('users', 'file_movement_users.user_id', '=', 'users.id')
        //     // ->select('file_movements.*', 'file_movement_users.created_at as movement_date', 'users.full_name as appointed_name')
        //     ->where('file_movements.is_deleted', 0)
        // ->get();

        // return '<pre>' . print_r($file_movements->toArray(), true) . '</pre>';

        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule('Movement of File'));

        if (Request::ajax()) {
            $request = Request::all();

            $models = FileMovementUser::leftJoin('file_movements', 'file_movement_users.file_movement_id', '=', 'file_movements.id')
                ->leftJoin('files', 'file_movements.file_id', '=', 'files.id')
                ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
                ->leftJoin('users', 'file_movement_users.user_id', '=', 'users.id')
                ->select('file_movements.*', 'files.file_no as file_no', 'strata.name as strata_name', 'file_movement_users.created_at as movement_date', 'users.full_name as appointed_name')
                ->where('file_movements.is_deleted', 0);

            if (!empty($request['filter'])) {
                return Datatables::of($models)
                    ->editColumn('movement_date', function ($model) {
                        return date('d-m-Y H:i A', strtotime($model->movement_date));
                    })
                    ->editColumn('file_no', function ($model) {
                        return "<a style='text-decoration:underline;' href='" . URL::action('CobFileMovementController@index', Helper::encode($model->file_id)) . "'>" . $model->file_no . "</a>";
                    })
                    ->editColumn('strata_name', function ($model) {
                        return $model->strata_name;
                    })
                    ->editColumn('appointed_name', function ($model) {
                        return $model->appointed_name;
                    })
                    ->make(true);
            } else {
                return Datatables::of($models)
                    ->editColumn('movement_date', function ($model) {
                        return date('d-m-Y', strtotime($model->movement_date));
                    })
                    ->editColumn('file_no', function ($model) {
                        return "<a style='text-decoration:underline;' href='" . URL::action('CobFileMovementController@index', Helper::encode($model->file_id)) . "'>" . $model->file_no . "</a>";
                    })
                    ->editColumn('strata_name', function ($model) {
                        return $model->strata_name;
                    })
                    ->editColumn('appointed_name', function ($model) {
                        return $model->appointed_name;
                    })
                    ->filter(function ($query) use ($request) {
                        if (!empty($request['date_from']) && empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $query->where('file_movement_users.created_at', '>=', $date_from);
                        }
                        if (!empty($request['date_to']) && empty($request['date_from'])) {
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->where('file_movement_users.created_at', '<=', $date_to . " 23:59:59");
                        }
                        if (!empty($request['date_from']) && !empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->whereBetween('file_movement_users.created_at', [$date_from, $date_to . ' 23:59:59']);
                        }
                    })
                    ->make(true);
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.file_movement'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'file_movement_report_list',
            'image' => ''
        );

        return View::make('report_en.file_movement', $viewData);
    }

    public function finance()
    {
       Helper::isAllow(0, 0, !AccessGroup::hasAccessModule('Finance / Month'));

        $request = Request::all();
        $year = Files::getVPYear();
        $company = Company::find(Auth::user()->company_id);

        if (!Auth::user()->getAdmin()) {
            if (!empty($user->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)
                    ->where('company_id', $company->id)
                    ->where('is_deleted', 0)
                    ->orderBy('year', 'asc')
                    ->get();
            } else {
                $files = Files::where('company_id', $company->id)
                    ->where('is_deleted', 0)
                    ->orderBy('year', 'asc')
                    ->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)
                    ->orderBy('year', 'asc')
                    ->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))
                    ->where('is_deleted', 0)
                    ->orderBy('year', 'asc')
                    ->get();
            }
        }

        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)
                ->where('is_main', 0)
                ->where('is_deleted', 0)
                ->orderBy('name')
                ->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))
                ->get();
        }

        if (Request::ajax()) {
            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $models = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select('finance_file.*', 'files.file_no as file_no', 'strata.name as strata_name', 'finance_check.is_active as status')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', $company->id)
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_active', 1)
                        ->where('finance_file.is_deleted', 0);
                } else {
                    $models = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select('finance_file.*', 'files.file_no as file_no', 'strata.name as strata_name', 'finance_check.is_active as status')
                        ->where('files.company_id', $company->id)
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_active', 1)
                        ->where('finance_file.is_deleted', 0);
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $models = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select('finance_file.*', 'files.file_no as file_no', 'strata.name as strata_name', 'finance_check.is_active as status')
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_active', 1)
                        ->where('finance_file.is_deleted', 0);
                } else {
                    $models = Finance::join('files', 'finance_file.file_id', '=', 'files.id')
                        ->join('finance_check', 'finance_check.finance_file_id', '=', 'finance_file.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select('finance_file.*', 'files.file_no as file_no', 'strata.name as strata_name', 'finance_check.is_active as status')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->where('finance_file.is_active', 1)
                        ->where('finance_file.is_deleted', 0);
                }
            }

            return Datatables::of($models)
                ->addColumn('cob', function ($model) {
                    return ($model->file->company ? $model->file->company->short_name : '-');
                })
                ->editColumn('file_no', function ($model) {
                    return $model->file->file_no;
                })
                ->editColumn('created_at', function ($model) {
                    return date('d/m/Y', strtotime($model->created_at));
                })
                ->editColumn('strata_name', function ($model) {
                    return ($model->file->strata ? $model->file->strata->strataName() : '-');
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
                ->filter(function ($query) use ($request) {
                    if (!empty($request['cob'])) {
                        $query->where('company.id', $request['cob']);
                    }
                    if (!empty($request['file'])) {
                        $query->where('files.id', $request['file']);
                    }
                    if (!empty($request['year'])) {
                        $query->where('finance_file.year', $request['year']);
                    }
                    if (!empty($request['month'])) {
                        $query->where('finance_file.month', $request['month']);
                    }
                    if (!empty($request['start_date']) || !empty($request['end_date'])) {
                        $start_date = !empty($request['start_date']) ? Carbon::parse($request['start_date']) : Carbon::create(1984, 1, 35, 13, 0, 0);
                        $end_date = !empty($request['end_date']) ? Carbon::parse($request['end_date']) : Carbon::now();
                        $query->whereBetween('finance_file.created_at', [$start_date, $end_date]);
                    }
                })
                ->make(true);
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.finance'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'finance_report_list',
            'image' => '',
            'cob' => $cob,
            'year' => $year,
            'month' => Finance::monthList(),
            'files' => $files,
            'company' => $company,
        );

        if (isset($request['tab']) && $request['tab'] == 'tab_2') {
            return View::make('report_en.finance_tab_2', $viewData);
        }

        return View::make('report_en.finance_tab_1', $viewData);
        
    }
}
