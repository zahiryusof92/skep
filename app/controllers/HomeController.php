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

        $cob = array();
        if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->get();
            }
        }

        $ageing = '';
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::find(Auth::user()->file_id);
                if ($file) {
                    $ageing = $file->financeAgeing();
                }
            }
        }

        // Stats/charts load via AJAX so first HTML paint is not blocked
        $data = array(
            'total_strata' => '…',
            'total_active_strata' => '…',
            'total_inactive_strata' => '…',
            'total_less_10_units' => '…',
            'total_jmb' => '…',
            'total_mc' => '…',
            'total_developer' => '…',
            'total_liquidator' => '…',
            'total_agent' => '…',
            'total_no_management' => '…',
            'total_owner' => '…',
            'total_tenant' => '…',
            'management' => array(),
            'rating' => array(),
            'never' => array('categories' => array(), 'data' => array()),
        );

        $viewData = array(
            'title' => trans('app.app_name_short'),
            'panel_nav_active' => 'home_panel',
            'main_nav_active' => 'home_main',
            'sub_nav_active' => 'home',
            'user_permission' => array(),
            'data' => $data,
            'cob' => $cob,
            'year' => array(),
            'activeMemo' => array(),
            'ageing' => $ageing,
            'image' => ""
        );

        return View::make('home_en.dashboard', $viewData);
    }

    /**
     * Heavy dashboard counters/charts — loaded async after page paint.
     */
    public function getDashboardStats()
    {
        $dashboardCacheKey = 'dashboard_data_' . Auth::user()->id . '_' . (Session::get('admin_cob') ?: Auth::user()->company_id) . '_' . (Auth::user()->file_id ?: '0');
        $data = Cache::remember($dashboardCacheKey, 300, function () {
            return Files::getDashboardData();
        });

        return Response::json(array(
            'success' => true,
            'data' => $data,
        ));
    }

    /**
     * Active memo alerts — loaded async (do not block dashboard HTML).
     */
    public function getActiveMemoAlerts()
    {
        if (Auth::user()->isLPHS()) {
            return Response::json(array('success' => true, 'ids' => array()));
        }

        $memos = self::getActiveMemoHome();
        $ids = array();
        if ($memos && count($memos) > 0) {
            foreach ($memos as $memo) {
                $ids[] = Helper::encode($memo->id);
            }
        }

        return Response::json(array(
            'success' => true,
            'ids' => $ids,
        ));
    }

    public function getAGMRemainder()
    {
        $query = $this->buildAgmReminderQuery(function ($query) {
            $query->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-1 year')));
        });

        return $this->agmReminderDatatable($query);
    }

    public function getNeverAGM()
    {
        $query = Files::neverHasAGMGroupByFileId()
            ->where(function ($query) {
                if (Request::has('short_name') && !empty(Request::get('short_name'))) {
                    $query->where('company.short_name', Request::get('short_name'));
                }
            })
            ->select([
                'files.id',
                'files.file_no',
                'company.short_name as cob',
                'strata.name as strata',
            ]);

        return Datatables::of($query)
            ->editColumn('cob', function ($model) {
                return $model->cob ?: '-';
            })
            ->editColumn('file_no', function ($model) {
                return $model->file_no ?: '';
            })
            ->editColumn('strata', function ($model) {
                return $model->strata ?: '-';
            })
            ->addColumn('action', function ($model) {
                return '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@house', Helper::encode($model->id)) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
            })
            ->make(true);
    }

    public function getAGM12Months()
    {
        $query = $this->buildAgmReminderQuery(function ($query) {
            $query->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-12 Months')))
                ->where('meeting_document.agm_date', '>', date('Y-m-d', strtotime('-15 Months')));
        });

        return $this->agmReminderDatatable($query);
    }

    public function getAGM15Months()
    {
        $query = $this->buildAgmReminderQuery(function ($query) {
            $query->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-15 Months')));
        });

        return $this->agmReminderDatatable($query);
    }

    /**
     * Shared AGM reminder query — joined columns only (no N+1).
     *
     * @param callable $dateFilter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildAgmReminderQuery($dateFilter)
    {
        $query = Files::join('meeting_document', 'meeting_document.file_id', '=', 'files.id')
            ->join('company', 'files.company_id', '=', 'company.id')
            ->join('strata', 'files.id', '=', 'strata.file_id')
            ->select([
                'files.id',
                'files.file_no',
                'company.short_name as cob',
                'strata.name as strata',
                'meeting_document.id as meeting_document_id',
                'meeting_document.agm_date',
            ])
            ->where('meeting_document.agm_date', '!=', '0000-00-00')
            ->where('meeting_document.is_deleted', 0)
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0);

        $dateFilter($query);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id);
            } else {
                $query->where('files.company_id', Auth::user()->company_id);
            }
        } else if (!empty(Session::get('admin_cob'))) {
            $query->where('files.company_id', Session::get('admin_cob'));
        }

        return $query;
    }

    /**
     * Shared Datatables formatter for AGM reminder tables.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    protected function agmReminderDatatable($query)
    {
        $canUpdate = AccessGroup::hasUpdate(9);
        $viewLabel = trans('app.forms.view');

        return Datatables::of($query)
            ->editColumn('cob', function ($model) {
                return $model->cob ?: '-';
            })
            ->editColumn('file_no', function ($model) {
                return $model->file_no ?: '';
            })
            ->editColumn('strata', function ($model) {
                return $model->strata ?: '-';
            })
            ->editColumn('agm_date', function ($model) {
                return $model->agm_date ? date('d-M-Y', strtotime($model->agm_date)) : '-';
            })
            ->addColumn('agm_expiry_date', function ($model) {
                return $model->agm_date ? date('d-M-Y', strtotime($model->agm_date . ' + 1 year')) : '-';
            })
            ->addColumn('action', function ($model) use ($canUpdate, $viewLabel) {
                if (!$canUpdate || !$model->meeting_document_id) {
                    return '';
                }

                return '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@editMinutes', Helper::encode($model->meeting_document_id)) . '\'">' . $viewLabel . '</button>&nbsp;';
            })
            ->make(true);
    }

    public function getDesignationRemainder()
    {
        $currentYear = (int) date('Y');

        // Reminder window only: ending last year .. next year (not entire AJK history)
        $query = AJKDetails::join('designation', 'ajk_details.designation', '=', 'designation.id')
            ->join('files', 'ajk_details.file_id', '=', 'files.id')
            ->join('company', 'files.company_id', '=', 'company.id')
            ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
            ->select([
                'ajk_details.id',
                'ajk_details.name',
                'ajk_details.email',
                'ajk_details.phone_no',
                'ajk_details.month',
                'ajk_details.start_year',
                'ajk_details.end_year',
                'company.short_name as cob',
                'files.file_no as file_no',
                'strata.name as strata',
                'designation.description as designation',
            ])
            ->where('ajk_details.is_deleted', 0)
            ->where('designation.is_deleted', 0)
            ->where('files.is_deleted', 0)
            ->where('files.is_active', 1)
            ->where('ajk_details.month', '>', 0)
            ->where('ajk_details.start_year', '>', 0)
            ->where('ajk_details.end_year', '>=', $currentYear - 1)
            ->where('ajk_details.end_year', '<=', $currentYear + 1);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id);
            } else {
                $query->where('files.company_id', Auth::user()->company_id);
            }
        } else if (!empty(Session::get('admin_cob'))) {
            $query->where('files.company_id', Session::get('admin_cob'));
        }

        $canUpdate = AccessGroup::hasUpdate(9);
        $months = AJKDetails::monthList();

        return Datatables::of($query)
            ->editColumn('cob', function ($model) {
                return $model->cob ?: '-';
            })
            ->editColumn('file_no', function ($model) {
                return $model->file_no ?: '';
            })
            ->editColumn('strata', function ($model) {
                return $model->strata ?: '-';
            })
            ->editColumn('designation', function ($model) {
                return $model->designation ?: '-';
            })
            ->editColumn('month', function ($model) use ($months) {
                $key = str_pad($model->month, 2, '0', STR_PAD_LEFT);
                return isset($months[$key]) ? $months[$key] : ($model->month ?: '-');
            })
            ->addColumn('action', function ($model) use ($canUpdate) {
                if (!$canUpdate) {
                    return '';
                }
                return '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', Helper::encode($model->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
            })
            ->make(true);
    }

    public function getInsuranceRemainder()
    {
        $from = Carbon::now()->subMonths(6)->toDateString();
        $to = Carbon::now()->addMonth()->toDateString();

        // Reminder window: expired in last 6 months OR expiring within 1 month
        $query = Insurance::join('files', 'insurance.file_id', '=', 'files.id')
            ->join('company', 'files.company_id', '=', 'company.id')
            ->leftJoin('strata', 'files.id', '=', 'strata.file_id')
            ->leftJoin('insurance_provider', 'insurance.insurance_provider_id', '=', 'insurance_provider.id')
            ->select([
                'insurance.id',
                'insurance.plc_validity_to',
                'company.short_name as cob',
                'files.file_no as file_no',
                'strata.name as strata',
                'insurance_provider.name as provider',
            ])
            ->where('files.is_deleted', 0)
            ->where('files.is_active', 1)
            ->where('insurance.is_deleted', 0)
            ->whereNotNull('insurance.plc_validity_to')
            ->where('insurance.plc_validity_to', '!=', '0000-00-00')
            ->whereBetween('insurance.plc_validity_to', [$from, $to]);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id);
            } else {
                $query->where('files.company_id', Auth::user()->company_id);
            }
        } else if (!empty(Session::get('admin_cob'))) {
            $query->where('files.company_id', Session::get('admin_cob'));
        }

        $canUpdate = AccessGroup::hasUpdate(46);

        return Datatables::of($query)
            ->editColumn('cob', function ($model) {
                return $model->cob ?: '-';
            })
            ->editColumn('file_no', function ($model) {
                return $model->file_no ?: '';
            })
            ->editColumn('strata', function ($model) {
                return $model->strata ?: '-';
            })
            ->editColumn('provider', function ($model) {
                return $model->provider ?: '-';
            })
            ->editColumn('plc_validity_to', function ($model) {
                return $model->plc_validity_to ?: '-';
            })
            ->addColumn('action', function ($model) use ($canUpdate) {
                if (!$canUpdate) {
                    return '';
                }
                return '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AdminController@updateInsurance', ['All', Helper::encode($model->id)]) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
            })
            ->make(true);
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
        $memo = Memo::where('publish_date', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->where('expired_date', '>=', $today)->orWhereNull('expired_date');
            })
            ->where('is_active', 1)
            ->where('is_deleted', 0);

        if (!Auth::user()->getAdmin()) {
            $memo->where(function ($query) {
                $query->where('company_id', Auth::user()->company_id)->orWhere('company_id', 99);
            });
            if (!empty(Auth::user()->file_id)) {
                $memo->where(function ($query) {
                    $query->where('file_id', Auth::user()->file_id)->whereNotNull('file_id');
                    $query->orWhereNull('file_id');
                });
            }
        } else if (!empty(Session::get('admin_cob'))) {
            $memo->where(function ($query) {
                $query->where('company_id', Session::get('admin_cob'))->orWhere('company_id', 99);
            });
        }

        return $memo->orderBy('memo_date', 'desc')->take(5)->get();
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
                        $result .= "<img src='" . $file . "' style='max-width:100%; height:auto;'/><br/><br/>";
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
