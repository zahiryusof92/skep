<?php

use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Services\NotificationService;
use yajra\Datatables\Facades\Datatables;

class DraftRejectController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $this->checkAvailableAccess();
        if (Request::ajax()) {
            $model = FileDraftReject::with(['file'])
                                    ->self()
                                    ->select('file_draft_rejects.*');
            return Datatables::of($model)
                            ->editColumn('type', function($model) {
                                return $this->module['file_draft_reject']['type'][$model->type]['title'];
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                return $created_at;
                            })
                            ->addColumn('strata', function($model) {
                                return $model->file->strata? $model->file->strata->name : "Non-Set";
                            })
                            ->addColumn('file_no', function($model) {
                                $tab = 'others';
                                if($model->type == 'house_scheme') {
                                    $tab = 'house';
                                } else if($model->type == 'strata') {
                                    $tab = 'strata';
                                } else if($model->type == 'management') {
                                    $tab = 'management';
                                }
                                if(Auth::user()->isJMB()) {
                                    return "<a style='text-decoration:underline;' href='" . route("cob.file.$tab.edit", Helper::encode($model->file->id)) . "'>" . $model->file->file_no . "</a>";
                                }
                                return "<a style='text-decoration:underline;' href='" . route("cob.file.draft.$tab.edit", Helper::encode($model->file->id)) . "'>" . $model->file->file_no . "</a>";
                            })
                            ->addColumn('action', function ($model) {
                                $btn = '';
                                $btn .= '<a href="javascript:void(0)" class="btn btn-xs btn-warning" title="Edit" onclick="show('. $model->id .')"><i class="fa fa-eye"></i></a>&nbsp;';

                                return $btn;
                            })
                            ->make(true);
        }
        $viewData = array(
            'title' => trans('app.menus.cob.file_reject_list'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'cob_draft_reject_list',
            'image' => ''
        );

        return View::make('draft.reject.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $type = Request::get('type');
        $file_id = Request::get('file_id');

        return View::make('draft.reject.create', compact('type', 'file_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if(Request::ajax()) {
            $data = Request::all();
            $rules = array(
                'file_id' => 'required|exists:files,id',
                'type' => 'required',
                'remarks' => 'required',
            );
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return Response::json([
                    'error' => true, 
                    'errors' => $validator->errors(), 
                    'message' => trans('Validation Fail')
                ]);
            }

            $draft_reject = FileDraftReject::create([
                'file_id' => $data['file_id'],
                'type' => $data['type'],
                'remarks' => $data['remarks'],
            ]);

            $name = $this->module['file_draft_reject']['type'][$draft_reject->type]['title'];
            $remarks = $draft_reject->file->file_no ." ". $name . $this->module['audit']['text']['data_rejected'];
            $this->addAudit(0, "File Draft Reject", $remarks);
            
            /**
             * Add Notification & send email to COB and JMB
             */
            $tab = 'others';
            if($this->module['file_draft_reject']['type'][$draft_reject->type]['name'] == 'house_scheme') {
                $tab = 'house';
            } else if($this->module['file_draft_reject']['type'][$draft_reject->type]['name'] == 'strata') {
                $tab = 'strata';
            } else if($this->module['file_draft_reject']['type'][$draft_reject->type]['name'] == 'management') {
                $tab = 'management';
            }
            $strata = $draft_reject->file->strata;
            $notify_data['file_id'] = $draft_reject->file->id;
            $notify_data['route'] = route("cob.file.$tab.edit", Helper::encode(Request::get('file_id')));
            $notify_data['strata'] = "your";
            $notify_data['strata_name'] = $strata->name != ""? $strata->name : $draft_reject->file->file_no;
            $notify_data['title'] = "COB File $name";
            $notify_data['module'] = "$name";
            
            (new NotificationService())->sendJMB($notify_data, 'rejected');
            
            return Response::json([
                'success' => true, 
                'id' => Helper::encode($this->module['file_draft_reject']['name'], $draft_reject->id), 
                'message' => trans('app.successes.saved_successfully')
            ]);
        }

        return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ]);
        
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $draft_reject = FileDraftReject::with(['file'])->findOrFail($id);
        
        return View::make("draft.reject.show", compact('draft_reject'));
    }


    private function checkAvailableAccess() {
        // if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
        //     return true;
        // }
        // App::abort(404);
    }
}