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
                        if ($model->file_id > 0) {
                            return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file->id)) . "'>" . $model->file_no . "</a>";
                        }
                        if ($model->user->isJMB()) {
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
                        if ($model->user->isJMB()) {
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
                        if ($model->user->isJMB()) {
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

    public function strataProfile()
    {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(29));

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $data = Files::getStrataProfileAnalytic();

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
        $query = Files::with(['financeLatest', 'company'])
            ->file();

        if (!empty($request['company_id'])) {
            $company = Company::where('short_name', $request['company_id'])->first();
            $query = $query->where('files.company_id', $company->id);
        }
        // if(!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
        //     $start_date = !empty(Input::get('start_date'))? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0); 
        //     $today = !empty(Input::get('end_date'))? Carbon::parse(Input::get('end_date')) : Carbon::now();
        //     $query = $query->where(function($query) use($start_date, $today){
        //                     $query->where(function($query1) use($start_date) {
        //                         $query1->where('finance_file.year','>',$start_date->year)
        //                                 ->orWhere(function($query2) use($start_date){
        //                                 $query2->where('finance_file.year',$start_date->year)
        //                                         ->where(function($query3) use($start_date) {
        //                                             $query3->where('finance_file.month', '>', $start_date->month)
        //                                                     ->orWhere('finance_file.month', $start_date->month);
        //                                         });
        //                                 });
        //                     })
        //                     ->where(function($query1) use($today) {
        //                         $query1->where('finance_file.year','<',$today->year)
        //                                 ->orWhere(function($query2) use($today){
        //                                 $query2->where('finance_file.year',$today->year)
        //                                         ->where(function($query3) use($today) {
        //                                             $query3->where('finance_file.month', '<', $today->month)
        //                                                     ->orWhere('finance_file.month', $today->month);
        //                                         });
        //                                 });
        //                     });
        //             });
        // }

        $data = array();
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
                        } else if ($percentage < 79 && $percentage >= 50) {
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
                    } else if ($percentage < 79 && $percentage >= 50) {
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
            $items = Files::getStrataProfileAnalytic($request);
            $response = [
                'success' => true,
                'data' => $items
            ];

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
                $latest_percentage = 0;
                $zone = 'KELABU';
                $rate = [];
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

                

                $latest_finance = Finance::with(['financeReportMF', 'financeReportSF', 'financeReportMFExtra', 'financeReportSFExtra', 'financeIncomeMF', 'financeIncomeSF'])
                    ->where('finance_file.file_id', $files->id)
                    ->where('finance_file.is_active', 1)
                    ->where('finance_file.is_deleted', 0)
                    ->orderBy('finance_file.year', 'desc')
                    ->orderBy('finance_file.month', 'desc')
                    ->first();

                if ($latest_finance->count() > 0) {
                    $latest_mf_fee_semasa = $latest_finance->financeReportMF->sum('fee_semasa');
                    $latest_sf_fee_semasa = $latest_finance->financeReportSF->sum('fee_semasa');
                    $latest_fee_semasa = $latest_mf_fee_semasa + $latest_sf_fee_semasa;

                    $latest_mf_fee_semasa_extra = $latest_finance->financeReportMFExtra->sum('fee_semasa');
                    $latest_sf_fee_semasa_extra = $latest_finance->financeReportSFExtra->sum('fee_semasa');
                    $latest_fee_semasa_extra = $latest_mf_fee_semasa_extra + $latest_sf_fee_semasa_extra;

                    $total_latest_sepatut_dikutip = $latest_fee_semasa + $latest_fee_semasa_extra;

                    $latest_mf_income = $latest_finance->financeIncomeMF->sum('semasa');
                    $latest_sf_income = $latest_finance->financeIncomeSF->sum('semasa');
                    $total_latest_berjaya_dikutip = $latest_mf_income + $latest_sf_income;

                    if ($total_latest_berjaya_dikutip > 0 && $total_latest_sepatut_dikutip > 0) {
                        $latest_percentage = round(($total_latest_berjaya_dikutip / $total_latest_sepatut_dikutip) * 100, 2);
                    }

                    if ($latest_percentage >= 80) {
                        $zone = 'BIRU';
                    } else if ($latest_percentage < 79 && $latest_percentage >= 50) {
                        $zone = 'KUNING';
                    } else {
                        $zone = "MERAH";
                    }

                    $rate = [
                        'mf_fee' => $latest_finance->financeReportMF->lists('fee_sebulan'),
                        'mf_fee_extra' => $latest_finance->financeReportMFExtra->lists('fee_sebulan'),
                        'sf_fee' => $latest_finance->financeReportSF->lists('fee_sebulan'),
                        'sf_fee_extra' => $latest_finance->financeReportSFExtra->lists('fee_sebulan'),
                    ];
                }

                $collection = [
                    'rate' => $rate,
                    'percentage' => $latest_percentage,
                    'zone' => $zone,
                ];

                // return '<pre>' . print_r($collection, true) . '</pre>';

                $finances = Finance::with(['financeReportMF', 'financeReportSF', 'financeReportMFExtra', 'financeReportSFExtra', 'financeIncomeMF', 'financeIncomeSF'])
                    ->where('finance_file.file_id', $files->id)
                    ->where('finance_file.is_active', 1)
                    ->where('finance_file.is_deleted', 0)
                    ->orderBy('finance_file.year', 'desc')
                    ->orderBy('finance_file.month', 'desc')
                    ->get();

                if ($finances->count() > 0) {
                    foreach ($finances as $finance) {
                        $mf_fee_semasa = $finance->financeReportMF->sum('fee_semasa');
                        $sf_fee_semasa = $finance->financeReportSF->sum('fee_semasa');
                        $fee_semasa = $mf_fee_semasa + $sf_fee_semasa;

                        $mf_fee_semasa_extra = $finance->financeReportMFExtra->sum('fee_semasa');
                        $sf_fee_semasa_extra = $finance->financeReportSFExtra->sum('fee_semasa');
                        $fee_semasa_extra = $mf_fee_semasa_extra + $sf_fee_semasa_extra;

                        $total_sepatut_dikutip = $fee_semasa + $fee_semasa_extra;

                        $mf_income = $finance->financeIncomeMF->sum('semasa');
                        $sf_income = $finance->financeIncomeSF->sum('semasa');
                        $total_berjaya_dikutip = $mf_income + $sf_income;

                        $percentage = 0;
                        if ($total_berjaya_dikutip > 0 && $total_sepatut_dikutip > 0) {
                            $percentage = round(($total_berjaya_dikutip / $total_sepatut_dikutip) * 100, 2);
                        }

                        $ageing[$finance->year][$finance->monthName()] = [
                            'fee_semasa' => $fee_semasa,
                            'fee_semasa_extra' => $fee_semasa_extra,
                            'sepatut_dikutip' => $total_sepatut_dikutip,
                            'berjaya_dikutip' => $total_berjaya_dikutip,
                            'percentage' => $percentage,
                        ];
                    }
                }
            }

            // return '<pre>' . print_r($ageing, true) . '</pre>';

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
                'collection' => $collection,
                'ageing' => $ageing,
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

    public function getManagementList()
    {
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();

            if (!empty(Auth::user()->file_id)) {
                $files = Files::with(['company', 'strata'])->where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $files = Files::with(['company', 'strata'])->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::with(['company', 'strata'])->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::with(['company', 'strata'])->where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        if (count($files) > 0) {
            $data = array();

            $files->reduce(function ($carry, $file) use (&$data) {
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
                        $file->managementAgent->agent,
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(65));
        if (Request::ajax()) {
            $request = Request::all();
            $model = Files::file()
                ->join('strata', 'files.id', '=', 'strata.file_id')
                ->join('management', 'files.id', '=', 'management.file_id')
                ->selectRaw("files.id as id, files.file_no as file_no, strata.name as strata_name," .
                    "files.is_active as is_active, management.is_jmb as is_jmb, management.is_mc as is_mc," .
                    "management.is_agent as is_agent, management.is_others as is_others")
                ->where(function ($query) use ($request) {
                    if (!empty($request['file_id'])) {
                        $query->where('files.id', $request['file_id']);
                    }
                });
            return Datatables::of($model)
                ->editColumn('file_no', function ($model) {
                    return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->id)) . "'>" . $model->file_no . "</a>";
                })
                ->editColumn('developer', function ($model) {
                    return ucfirst($model->developer_name);
                })
                ->editColumn('city', function ($model) {
                    return ucfirst($model->city_name);
                })
                ->editColumn('category', function ($model) {
                    return ucfirst($model->category_name);
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
                    if (!$model->is_jmb && !$model->is_mc && !$model->is_agent && !$model->is_agent && !$model->is_others) {
                        $content .= trans('Non-Set');
                    }
                    return rtrim($content, ",");
                })
                ->addColumn('status', function ($model) {
                    return $model->is_active ? trans('app.forms.yes') : trans('app.forms.no');
                })
                ->filter(function ($query) use ($request) {
                    if (!empty($request['management'])) {
                        if (in_array('jmb', $request['management']) && in_array('mc', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('is_agent', 1)
                                ->orWhere('is_others', 1)
                                ->orWhere('is_mc', 1)
                                ->orWhere('is_jmb', 1);
                        } else if (in_array('mc', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('is_agent', 1)
                                ->orWhere('is_others', 1)
                                ->orWhere('is_mc', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('is_agent', 1)
                                ->orWhere('is_others', 1)
                                ->orWhere('is_jmb', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('agent', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('is_agent', 1)
                                ->orWhere('is_mc', 1)
                                ->orWhere('is_jmb', 1);
                        } else if (in_array('agent', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('is_agent', 1)
                                ->orWhere('is_others', 1);
                        } else if (in_array('others', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('is_others', 1)
                                ->orWhere('is_mc', 1);
                        } else if (in_array('agent', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('is_agent', 1)
                                ->orWhere('is_mc', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('others', $request['management'])) {
                            $query->where('is_jmb', 1)
                                ->orWhere('is_others', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('agent', $request['management'])) {
                            $query->where('is_jmb', 1)
                                ->orWhere('is_agent', 1);
                        } else if (in_array('jmb', $request['management']) && in_array('mc', $request['management'])) {
                            $query->where('is_jmb', 1)
                                ->orWhere('is_mc', 1);
                        } else if (in_array('others', $request['management'])) {
                            $query->where('is_others', 1)
                                ->where('is_mc', 0);
                        } else if (in_array('agent', $request['management'])) {
                            $query->where('is_agent', 1)
                                ->where('is_mc', 0);
                        } else if (in_array('mc', $request['management'])) {
                            $query->where('is_mc', 1);
                        } else if (in_array('jmb', $request['management'])) {
                            $query->where('is_jmb', 1)
                                ->where('is_mc', 0);
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
}
