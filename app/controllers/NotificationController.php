<?php

use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
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
            $model = Notification::with(['user'])
                                    ->self();
            return Datatables::of($model)
                            ->editColumn('file_no', function($model) {
                                $tab = 'others';
                                if($model->module == 'House Scheme') {
                                    $tab = 'house';
                                } else if($model->module == 'Strata') {
                                    $tab = 'strata';
                                } else if($model->module == 'Management') {
                                    $tab = 'management';
                                }
                                return "<a style='text-decoration:underline;' href='" . $model->route . "'>" . $model->file->file_no . "</a>";
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y') : '');

                                return $created_at;
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
            false;
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
            if(!Auth::user()->getAdmin()) {
                $notification->update([
                    'is_view' => true,
                ]);
            }
            
            if ($notification) {
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
        }

        return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ], 406);
    }

    /**
     * markReadAll the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function markAll() {
        if(Request::ajax()) {
            if(!Auth::user()->getAdmin()) {
                $notifications = Notification::notView()
                ->where('user_id', Auth::user()->id)                            
                ->update([
                    'is_view' => true
                ]);
                if ($notifications) {
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
        }

        return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ], 406);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getSome() {
        $notifications = Notification::with(['user'])
                                ->self()
                                ->take(10);
        return View::make('notification.show', compact('notifications'));
    }
}