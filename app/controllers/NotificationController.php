<?php

use Helper\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;

class NotificationController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("Notification"));
        if (Request::ajax()) {
            $model = Notification::self();
            return Datatables::of($model)
                            ->editColumn('file_no', function($model) {
                                $fileNo = e($model->file_no ?: '-');
                                return "<a style='text-decoration:underline;' href='javascript:void(0)' onclick='updateNotification(" . (int) $model->id . ")'>" . $fileNo . "</a>";
                            })
                            ->editColumn('description', function($model) {
                                $description = e($model->description);
                                if (!$model->is_view) {
                                    return '<span class="notification-unread-label">' . e(trans('app.forms.notification_new')) . '</span> ' . $description;
                                }
                                return $description;
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y') : '');

                                return $created_at;
                            })
                            ->editColumn('is_view', function($model) {
                                return $model->is_view ? 1 : 0;
                            })
                            ->make(true);
        }
        $viewData = array(
            'title' => trans('app.menus.reporting.notification'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'notification_list',
            'image' => ''
        );

        return View::make('notification.index', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($data) {
        $rules = array(
            'user_id' => 'required',
            'company_id' => 'required',
            'file_id' => 'required',
            'module' => 'required',
            'route' => 'required',
            'description' => 'required',
        );
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return false;
        }

        $notification = Notification::create([
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'file_id' => $data['file_id'],
            'module' => $data['module'],
            'route' => $data['route'],
            'description' => $data['description'],
        ]);

        if ($notification) {
            /*
                * add audit trail
                */
            $audit_name = "$notification->description";
            $remarks = $audit_name;
            $this->addAudit($notification->file_id, "Notification", $remarks);
            
            return true;
        }

        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if(Request::ajax()) {
            $notification = Notification::findOrFail($id);

            if (!$this->canAccessNotification($notification)) {
                return Response::json([
                    'error' => true,
                    'message' => trans('app.errors.occurred')
                ], 403);
            }

            $notification->update([
                'is_view' => true,
            ]);

            /*
                * add audit trail
                */
            $audit_name = "$notification->description, view";
            $remarks = $audit_name . $this->module['audit']['text']['data_updated'];
            $this->addAudit($notification->file_id, "Notification", $remarks);
            
            return Response::json([
                'success' => true, 
                'route' => $notification->route,
                'message' => trans('app.successes.updated_successfully')
            ]);
        }

        return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ], 406);
    }

    /**
     * markReadAll the specified resource in storage.
     *
     * @return Response
     */
    public function markAll() {
        if(Request::ajax()) {
            if(!Auth::user()->getAdmin()) {
                Notification::notView()
                    ->where('user_id', Auth::user()->id)                            
                    ->update([
                        'is_view' => true
                    ]);

                /*
                * add audit trail
                */
                $audit_name = Auth::user()->fullname . " has mark all notifications";
                $remarks = $audit_name;
                $file_id = ((Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) ? Auth::user()->file_id : 0);
                $this->addAudit($file_id, "Notification", $remarks);
                
                return Response::json([
                    'success' => true, 
                    'message' => trans('app.successes.updated_successfully')
                ]);
            }
        }

        return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ], 406);
    }

    /**
     * Non-admin: own notification only.
     * Admin: same company (or selected admin_cob).
     *
     * @param Notification $notification
     * @return bool
     */
    protected function canAccessNotification($notification) {
        $user = Auth::user();

        if (!$user->getAdmin()) {
            return (int) $notification->user_id === (int) $user->id;
        }

        $companyId = Session::get('admin_cob') ?: $user->company_id;

        return (int) $notification->company_id === (int) $companyId;
    }
}
