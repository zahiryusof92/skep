<?php

use Carbon\Carbon;

class HomeController extends BaseController {
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

    public function home() {

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

        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $data = Files::getDashboardData();

        $viewData = array(
            'title' => trans('app.app_name_short'),
            'panel_nav_active' => 'home_panel',
            'main_nav_active' => 'home_main',
            'sub_nav_active' => 'home',
            'user_permission' => $user_permission,
            'data' => $data,
            'image' => ""
        );

        return View::make('home_en.dashboard', $viewData);
    }

    public function getAGMRemainder() {
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
                                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@editMinutes', $model->latestMeetingDocument->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                                }

                                return $button;
                            })
                            ->make(true);
        }
    }

    public function getNeverAGM() {
        $condition = function ($query) {
            $query->whereDoesntHave('meetingDocument');
            $query->orWhereHas('meetingDocument', function ($query2) {
                $query2->where('meeting_document.agm_date', '0000-00-00');
            });
            $query->where('files.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition);
            } else {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
                        ->where($condition);
            } else {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['files.*', 'strata.id as strata_id'])
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
                            ->addColumn('action', function ($model) {
                                $button = '';
                                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@house', $model->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';

                                return $button;
                            })
                            ->make(true);
        }
    }

    public function getAGM12Months() {
        $condition = function ($query) {
            $query->where('meeting_document.agm_date', '!=', '0000-00-00');
            $query->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-12 Months')));
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
                                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@editMinutes', $model->latestMeetingDocument->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                                }

                                return $button;
                            })
                            ->make(true);
        }
    }

    public function getAGM15Months() {
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
                                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@editMinutes', $model->latestMeetingDocument->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                                }

                                return $button;
                            })
                            ->make(true);
        }
    }

    public function getDesignationRemainder() {
        $current_year = date('Y');
        $current_month = date('m', strtotime('first day of +1 month'));

        $condition = function ($query1) use ($current_month, $current_year) {
            $query1->where(function ($query2) {
                $query2->where('ajk_details.month', '>', '0');
            });
            $query1->where(function ($query3) {
                $query3->where('ajk_details.year', '>', '0');
            });
//            $query1->where(function ($query4) use ($current_month, $current_year) {
//                $query4->where('ajk_details.month', '>', $current_month);
//                $query4->where('ajk_details.year', '!=', $current_year);                
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
                        ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.year, ajk_details.month) as monthyear")])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition);
            } else {
                $file = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
                        ->join('files', 'ajk_details.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.year, ajk_details.month) as monthyear")])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where($condition);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
                        ->join('files', 'ajk_details.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.year, ajk_details.month) as monthyear")])
                        ->where($condition);
            } else {
                $file = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
                        ->join('files', 'ajk_details.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['ajk_details.*', 'designation.id as designation_id', 'strata.id as strata_id', DB::raw("CONCAT(ajk_details.year, ajk_details.month) as monthyear")])
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
                            ->editColumn('year', function ($model) {
                                return $model->year;
                            })
                            ->addColumn('action', function ($model) {
                                $button = '';
                                if (AccessGroup::hasUpdate(9)) {
                                    $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', $model->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . $model->id . '\')"><i class="fa fa-trash"></i></button>&nbsp';
                                }

                                return $button;
                            })
                            ->make(true);
        }
    }

    public function getMemoHome() {
        $today = date('Y-m-d');

        if (!Auth::user()->getAdmin()) {
            $memo = Memo::where('publish_date', '<=', $today)
                    ->where(function($query) use ($today) {
                        $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                    })
                    ->where(function($query) {
                        $query->where('company_id', Auth::user()->company_id)->orWhere('company_id', 99);
                    })
                    ->where('is_active', 1)
                    ->where('is_deleted', 0);
        } else {
            if (empty(Session::get('admin_cob'))) {
                $memo = Memo::where('publish_date', '<=', $today)
                        ->where(function($query) use ($today) {
                            $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                        })
                        ->where('is_active', 1)
                        ->where('is_deleted', 0);
            } else {
                $memo = Memo::where('publish_date', '<=', $today)
                        ->where(function($query) use ($today) {
                            $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
                        })
                        ->where(function($query) {
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
                            return '<button type="button" class="btn btn-xs btn-success" onclick="getMemoDetails(\'' . $model->id . '\')">' . trans('app.forms.view') . '</button>';
                        })
                        ->make(true);
    }

    public function getMemoDetails() {
        $data = Input::all();
        if (Request::ajax()) {

            $result = "";
            $id = $data['id'];

            $memo = Memo::find($id);

            if (count($memo) > 0) {

                $result .= "<div class='modal-header'>";
                $result .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
                $result .= "<h4 class='modal-title' id='myModalLabel'>" . ($memo->subject != "" ? $memo->subject : "-") . "</h4>";
                $result .= "<h6 class='modal-title' id=''>" . (date('d-M-Y', strtotime($memo->memo_date)) != "" ? date('d-M-Y', strtotime($memo->memo_date)) : "-") . "</h6>";
                $result .= "</div>";
                $result .= "<div class='modal-body'>";
                $result .= "<p>" . ($memo->description != "" ? $memo->description : "-") . "</p>";
                $result .= "</div>";
            } else {
                $result = trans('app.errors.no_data_found');
            }

            print $result;
        }
    }

    public function getCompanyName() {
        $company = Company::find(Auth::user()->company_id);

        print $company->name;
    }

}
