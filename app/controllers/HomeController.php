<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class HomeController extends BaseController
{
    /*
      |--------------------------------------------------------------------------
      | Default Home Controller
      |--------------------------------------------------------------------------
      |
      | You may wish to use controllers instead of, or in addition to, Closure
      | based routes. That's great! Here is an example controller method to
      | get you started. To route to this controller, just add the route:
      |
      |	Route::get('/', 'HomeController@showWelcome');
      |
     */

    public function home()
    {
        if (Auth::user()->isMPS()) {
            return Redirect::to('/fileList');
        }

        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }
        
        $year = Files::getVPYear();
        $data = Files::getDashboardData();

        $activeMemo = '';
        if (!Auth::user()->isLPHS()) {
            $activeMemo = self::getActiveMemoHome();
        }

        $ageing = '';
        if (Auth::user()->isJMB() || Auth::user()->isMC()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::find(Auth::user()->file_id);
                if ($file) {
                    $ageing = $file->financeAgeing();
                }
            }
        }

        if (Auth::user()->isLawyer()) {
            $viewData = array(
                'title' => trans('app.app_name_short'),
                'panel_nav_active' => 'home_panel',
                'main_nav_active' => 'home_main',
                'sub_nav_active' => 'home',
                'image' => ""
            );

            return View::make('home_en.dashboard_lawyer', $viewData);
        }

        $viewData = array(
            'title' => trans('app.app_name_short'),
            'panel_nav_active' => 'home_panel',
            'main_nav_active' => 'home_main',
            'sub_nav_active' => 'home',
            'user_permission' => $user_permission,
            'data' => $data,
            'cob' => $cob,
            'year' => $year,
            'activeMemo' => $activeMemo,
            'ageing' => $ageing,
            'image' => ""
        );

        return View::make('home_en.dashboard', $viewData);
    }

    public function getAGMRemainder()
    {
        $condition = function ($query) {
            $query->where('meeting_document.agm_date', '!=', '0000-00-00');
            $query->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-1 year')));
            $query->where('meeting_document.is_deleted', 0);
            $query->where('files.is_active', 1);
            $query->where('files.is_deleted', 0);
            $query->orderBy('meeting_document.agm_date', 'desc');
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            } else {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where($condition);
            } else {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition);
            }
        }

        if ($file) {
            return Datatables::of($file)
                ->addColumn('cob', function ($model) {
                    return ($model->company_id ? $model->company->short_name : '-');
                })
                ->addColumn('file_no', function ($model) {
                    return $model->file_no;
                })
                ->addColumn('strata', function ($model) {
                    return ($model->strata_id ? $model->strata->name : '-');
                })
                ->addColumn('agm_date', function ($model) {
                    return ($model->meeting_document_id ? date('d-M-Y', strtotime($model->latestMeetingDocument->agm_date)) : '-');
                })
                ->addColumn('agm_expiry_date', function ($model) {
                    return ($model->meeting_document_id ? date('d-M-Y', strtotime($model->latestMeetingDocument->agm_date . " + 1 year")) : '-');
                })
                ->addColumn('action', function ($model) {
                    $button = '';
                    if (AccessGroup::hasUpdate(9)) {
                        $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@editMinutes', Helper::encode($model->latestMeetingDocument->id)) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                    }

                    return $button;
                })
                ->make(true);
        }
    }

    public function getNeverAGM()
    {
        // $condition = function ($query) {
        //     $query->whereDoesntHave('meetingDocument');
        //     $query->orWhereHas('meetingDocument', function ($query2) {
        //         $query2->where('meeting_document.agm_date', '0000-00-00');
        //     });
        //     $query->where('files.is_active', 1);
        //     $query->where('files.is_deleted', 0);
        // };

        // if (!Auth::user()->getAdmin()) {
        //     if (!empty(Auth::user()->file_id)) {
        //         $file = Files::join('company', 'files.company_id', '=', 'company.id')
        //                 ->join('strata', 'files.id', '=', 'strata.file_id')
        //                 ->select(['files.*', 'strata.id as strata_id'])
        //                 ->where('files.id', Auth::user()->file_id)
        //                 ->where('files.company_id', Auth::user()->company_id)
        //                 ->where($condition);
        //     } else {
        //         $file = Files::join('company', 'files.company_id', '=', 'company.id')
        //                 ->join('strata', 'files.id', '=', 'strata.file_id')
        //                 ->select(['files.*', 'strata.id as strata_id'])
        //                 ->where('files.company_id', Auth::user()->company_id)
        //                 ->where($condition);
        //     }
        // } else {
        //     if (empty(Session::get('admin_cob'))) {
        //         $file = Files::join('company', 'files.company_id', '=', 'company.id')
        //                 ->join('strata', 'files.id', '=', 'strata.file_id')
        //                 ->select(['files.*', 'strata.id as strata_id'])
        //                 ->where($condition);
        //     } else {
        //         $file = Files::join('company', 'files.company_id', '=', 'company.id')
        //                 ->join('strata', 'files.id', '=', 'strata.file_id')
        //                 ->select(['files.*', 'strata.id as strata_id'])
        //                 ->where('files.company_id', Session::get('admin_cob'))
        //                 ->where($condition);
        //     }
        // }
        $file = Files::neverHasAGM()
            ->where(function ($query) {
                if (Request::has('short_name') && !empty(Request::get('short_name'))) {
                    $query->where('company.short_name', Request::get('short_name'));
                }
            })
            ->select(['files.*', 'strata.id as strata_id']);

        if ($file) {
            return Datatables::of($file)
                ->addColumn('cob', function ($model) {
                    return ($model->company_id ? $model->company->short_name : '-');
                })
                ->addColumn('file_no', function ($model) {
                    return $model->file_no;
                })
                ->addColumn('strata', function ($model) {
                    return ($model->strata_id ? $model->strata->name : '-');
                })
                ->addColumn('action', function ($model) {
                    $button = '';
                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@house', Helper::encode($model->id)) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';

                    return $button;
                })
                ->make(true);
        }
    }

    public function getAGM12Months()
    {
        $condition = function ($query) {
            $query->where('meeting_document.agm_date', '!=', '0000-00-00');
            $query->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-12 Months')));
            $query->where('meeting_document.agm_date', '>', date('Y-m-d', strtotime('-15 Months')));
            $query->where('meeting_document.is_deleted', 0);
            $query->where('files.is_active', 1);
            $query->where('files.is_deleted', 0);
            $query->orderBy('meeting_document.agm_date', 'desc');
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            } else {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where($condition);
            } else {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition);
            }
        }

        if ($file) {
            return Datatables::of($file)
                ->addColumn('cob', function ($model) {
                    return ($model->company_id ? $model->company->short_name : '-');
                })
                ->addColumn('file_no', function ($model) {
                    return $model->file_no;
                })
                ->addColumn('strata', function ($model) {
                    return ($model->strata_id ? $model->strata->name : '-');
                })
                ->addColumn('agm_date', function ($model) {
                    return ($model->meeting_document_id ? date('d-M-Y', strtotime($model->latestMeetingDocument->agm_date)) : '-');
                })
                ->addColumn('agm_expiry_date', function ($model) {
                    return ($model->meeting_document_id ? date('d-M-Y', strtotime($model->latestMeetingDocument->agm_date . " + 1 year")) : '-');
                })
                ->addColumn('action', function ($model) {
                    $button = '';
                    if (AccessGroup::hasUpdate(9)) {
                        $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@editMinutes', Helper::encode($model->latestMeetingDocument->id)) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                    }

                    return $button;
                })
                ->make(true);
        }
    }

    public function getAGM15Months()
    {
        $condition = function ($query) {
            $query->where('meeting_document.agm_date', '!=', '0000-00-00');
            $query->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-15 Months')));
            $query->where('meeting_document.is_deleted', 0);
            $query->where('files.is_active', 1);
            $query->where('files.is_deleted', 0);
            $query->orderBy('meeting_document.agm_date', 'desc');
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            } else {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where($condition);
            } else {
                $file = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['files.*', 'meeting_document.id as meeting_document_id', 'strata.id as strata_id'])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition);
            }
        }

        if ($file) {
            return Datatables::of($file)
                ->addColumn('cob', function ($model) {
                    return ($model->company_id ? $model->company->short_name : '-');
                })
                ->addColumn('file_no', function ($model) {
                    return $model->file_no;
                })
                ->addColumn('strata', function ($model) {
                    return ($model->strata_id ? $model->strata->name : '-');
                })
                ->addColumn('agm_date', function ($model) {
                    return ($model->meeting_document_id ? date('d-M-Y', strtotime($model->latestMeetingDocument->agm_date)) : '-');
                })
                ->addColumn('agm_expiry_date', function ($model) {
                    return ($model->meeting_document_id ? date('d-M-Y', strtotime($model->latestMeetingDocument->agm_date . " + 1 year")) : '-');
                })
                ->addColumn('action', function ($model) {
                    $button = '';
                    if (AccessGroup::hasUpdate(9)) {
                        $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@editMinutes', Helper::encode($model->latestMeetingDocument->id)) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                    }

                    return $button;
                })
                ->make(true);
        }
    }

    public function getDesignationRemainder()
    {
        $current_year = date('Y');
        $current_month = date('m', strtotime('first day of +1 month'));

        $condition = function ($query1) use ($current_month, $current_year) {
            $query1->where(function ($query2) {
                $query2->where('ajk_details.month', '>', '0');
            });
            $query1->where(function ($query3) {
                $query3->where('ajk_details.start_year', '>', '0');
            });
            //            $query1->where(function ($query4) use ($current_month, $current_year) {
            //                $query4->where('ajk_details.month', '>', $current_month);
            //                $query4->where('ajk_details.start_year', '!=', $current_year);                
            //            });
            $query1->where('ajk_details.is_deleted', 0);
            $query1->where('designation.is_deleted', 0);
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
                    ->join('files', 'ajk_details.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.start_year, ajk_details.month) as monthyear")])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            } else {
                $file = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
                    ->join('files', 'ajk_details.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.start_year, ajk_details.month) as monthyear")])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
                    ->join('files', 'ajk_details.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.start_year, ajk_details.month) as monthyear")])
                    ->where($condition);
            } else {
                $file = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
                    ->join('files', 'ajk_details.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.start_year, ajk_details.month) as monthyear")])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition);
            }
        }

        if ($file) {
            return Datatables::of($file)
                ->addColumn('cob', function ($model) {
                    return ($model->file_id ? $model->file->company->short_name : '-');
                })
                ->addColumn('file_no', function ($model) {
                    return ($model->file_id ? $model->file->file_no : '');
                })
                ->addColumn('strata', function ($model) {
                    return ($model->strata_id ? $model->file->strata->name : '-');
                })
                ->editColumn('designation', function ($model) {
                    return ($model->designation_id ? $model->designations->description : '-');
                })
                ->editColumn('name', function ($model) {
                    return ($model->name);
                })
                ->editColumn('phone_no', function ($model) {
                    return ($model->phone_no);
                })
                ->editColumn('month', function ($model) {
                    return $model->monthName();
                })
                ->editColumn('start_year', function ($model) {
                    return $model->start_year;
                })
                ->editColumn('end_year', function ($model) {
                    return $model->end_year;
                })
                ->addColumn('action', function ($model) {
                    $button = '';
                    if (AccessGroup::hasUpdate(9)) {
                        $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', Helper::encode($model->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                    }

                    return $button;
                })
                ->make(true);
        }
    }

    public function getInsuranceRemainder()
    {
        $expiry = Carbon::now()->addMonth()->toDateString();

        $condition = function ($query1) use ($expiry) {
            $query1->where('plc_validity_to', '<=', $expiry);
            $query1->where('files.is_deleted', 0);
            $query1->where('insurance.is_deleted', 0);
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Insurance::join('files', 'insurance.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('insurance_provider', 'insurance.insurance_provider_id', '=', 'insurance_provider.id')
                    ->select(['insurance.*'])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            } else {
                $file = Insurance::join('files', 'insurance.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('insurance_provider', 'insurance.insurance_provider_id', '=', 'insurance_provider.id')
                    ->select(['insurance.*'])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where($condition);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Insurance::join('files', 'insurance.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('insurance_provider', 'insurance.insurance_provider_id', '=', 'insurance_provider.id')
                    ->select(['insurance.*'])
                    ->where($condition);
            } else {
                $file = Insurance::join('files', 'insurance.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->join('insurance_provider', 'insurance.insurance_provider_id', '=', 'insurance_provider.id')
                    ->select(['insurance.*'])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where($condition);
            }
        }

        if ($file) {
            return Datatables::of($file)
                ->addColumn('cob', function ($model) {
                    return ($model->file_id ? $model->file->company->short_name : '-');
                })
                ->addColumn('file_no', function ($model) {
                    return ($model->file_id ? $model->file->file_no : '');
                })
                ->addColumn('strata', function ($model) {
                    return ($model->file_id ? $model->file->strata->name : '-');
                })
                ->addColumn('provider', function ($model) {
                    return ($model->insurance_provider_id ? $model->provider->name : '-');
                })
                ->editColumn('plc_validity_to', function ($model) {
                    return ($model->plc_validity_to ? $model->plc_validity_to : '-');
                })
                ->addColumn('action', function ($model) {
                    $button = '';
                    if (AccessGroup::hasUpdate(46)) {
                        $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AdminController@updateInsurance', ['All', Helper::encode($model->id)]) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                    }

                    return $button;
                })
                ->make(true);
        }
    }

    public function getMemoHome()
    {
        $today = date('Y-m-d');

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where(function ($query) {
                        $query->where('company_id', Auth::user()->company_id)->orWhere('company_id', 99);
                    })
                    ->where(function ($query) {
                        $query->where('file_id', Auth::user()->file_id)->whereNotNull('file_id');
                        $query->orWhereNull('file_id');
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0);
            } else {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where(function ($query) {
                        $query->where('company_id', Auth::user()->company_id)->orWhere('company_id', 99);
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0);
            } else {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where(function ($query) {
                        $query->where('company_id', Session::get('admin_cob'))->orWhere('company_id', 99);
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0);
            }
        }

        return Datatables::of($memo)
            ->editColumn('memo_date', function ($model) {
                return ($model->memo_date ? date('d-M-Y', strtotime($model->memo_date)) : '');
            })
            ->addColumn('action', function ($model) {
                return '<button type="button" class="btn btn-xs btn-success" onclick="getMemoDetails(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.view') . '</button>';
            })
            ->make(true);
    }

    public function getActiveMemoHome()
    {
        $today = date('Y-m-d');

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where(function ($query) {
                        $query->where('company_id', Auth::user()->company_id)->orWhere('company_id', 99);
                    })
                    ->where(function ($query) {
                        $query->where('file_id', Auth::user()->file_id)->whereNotNull('file_id');
                        $query->orWhereNull('file_id');
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->get();
            } else {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where(function ($query) {
                        $query->where('company_id', Auth::user()->company_id)->orWhere('company_id', 99);
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->get();
            } else {
                $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function ($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where(function ($query) {
                        $query->where('company_id', Session::get('admin_cob'))->orWhere('company_id', 99);
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->get();
            }
        }

        return $memo;
    }

    public function getMemoDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $result = "";
            $id = Helper::decode($data['id']);

            $memo = Memo::findOrFail($id);

            if (count($memo) > 0) {

                $result .= "<div class='modal-header'>";
                $result .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
                $result .= "<h4 class='modal-title' id='myModalLabel'>" . ($memo->subject != "" ? $memo->subject : "-") . "</h4>";
                $result .= "<h6 class='modal-title' id=''>" . (date('d-M-Y', strtotime($memo->memo_date)) != "" ? date('d-M-Y', strtotime($memo->memo_date)) : "-") . "</h6>";
                $result .= "</div>";
                $result .= "<div class='modal-body'>";
                $result .= "<p>" . ($memo->description != "" ? $memo->description : "-") . "</p>";
                if (!empty($memo->document_file)) {
                    $files = explode(',', $memo->document_file);
                    foreach ($files as $file) {
                        $result .= "<img src='" . $file . "' style='width:100%;'/><br/><br/>";
                    }
                }
                $result .= "</div>";
            } else {
                $result = trans('app.errors.no_data_found');
            }

            print $result;
        }
    }

    public function getCompanyName()
    {
        $company = Company::find(Auth::user()->company_id);

        print $company->name;
    }
}
