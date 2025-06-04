<?php

use Helper\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;
use Services\NotificationService;

class AGMMinuteController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!empty(Session::get('admin_cob'))) {
            $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_hidden', false)->where('is_deleted', 0)->first();
            if ($cob && $cob->short_name != 'MPKJ') {
                \Log::debug("Session: " . $cob->short_name);
                return Redirect::to('/minutes');
            }
        }

        if (Request::ajax()) {
            $condition = '';
            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $condition = function ($query) {
                        $query->where('agm_minutes.file_id', Auth::user()->file_id)
                            ->where('agm_minutes.type', '!=', '');
                    };
                } else {
                    if (strtoupper(Auth::user()->getRole->name) == 'JMB') {
                        $condition = function ($query) {
                            $query->where('agm_minutes.type', 'jmb');
                        };
                    } else if (strtoupper(Auth::user()->getRole->name) == 'MC') {
                        $condition = function ($query) {
                            $query->where('agm_minutes.type', 'mc');
                        };
                    } else {
                        $condition = function ($query) {
                            $query->where('agm_minutes.type', '!=', '');
                        };
                    }
                }
            } else {
                $condition = function ($query) {
                    $query->where('agm_minutes.type', '!=', '');
                };
            }

            $model = AGMMinute::join('files', 'agm_minutes.file_id', '=', 'files.id')
                ->join('strata', 'strata.file_id', '=', 'files.id')
                ->where(function ($query) use ($condition) {
                    if (!empty($condition)) {
                        $query->where($condition);
                    }
                })
                ->where(function ($query) {
                    if (!empty(Request::get('search'))) {
                        if (!empty(Request::get('search')['value'])) {
                            $query->where('files.file_no', 'like', '%' . Request::get('search')['value'] . '%')
                                ->orWhere('strata.name', 'like', '%' . Request::get('search')['value'] . '%')
                                ->orWhere('agm_minutes.type', 'like', '%' . Request::get('search')['value'] . '%')
                                ->orWhere('agm_minutes.agm_type', 'like', '%' . Request::get('search')['value'] . '%')
                                ->orWhere('agm_minutes.agm_date', 'like', '%' . Request::get('search')['value'] . '%');
                        }
                    }
                })
                ->where('agm_minutes.is_deleted', 0)
                ->selectRaw("agm_minutes.*, files.file_no, strata.name as strata_name");

            return Datatables::of($model)
                ->addColumn('strata', function ($model) {
                    return $model->strata_name;
                })
                ->editColumn('file_id', function ($model) {
                    return $model->file_no;
                })
                ->editColumn('type', function ($model) {
                    return strtoupper($model->type);
                })
                ->editColumn('agm_type', function ($model) {
                    return strtoupper($model->agm_type);
                })
                ->editColumn('description', function ($model) {
                    $questions = unserialize($model->description);
                    $configs = $this->getFormFields($model);
                    $content = '';
                    foreach ($questions as $key => $val) {
                        if (str_contains($key, '_url')) {
                            if (!empty($configs['questions'][str_replace("_file_url", "", $key)])) {
                                $content .= $configs['questions'][str_replace("_file_url", "", $key)]['label'] . "<br/>";
                            }
                        }
                    }
                    return $content;
                })
                ->editColumn('updated_at', function ($model) {
                    return date('d-M-Y', strtotime($model->updated_at));
                })
                ->addColumn('check_status', function ($model) {
                    $questions = unserialize($model->description);
                    $configs = $this->getFormFields($model);
                    $content = '';
                    foreach ($questions as $key => $val) {
                        if (str_contains($key, '_url')) {
                            if ($val == "") {
                                $status = '<i class="icmn-cross"></i>';
                            } else {
                                $status = '<i class="icmn-checkmark"></i>';
                            }
                            if (!empty($configs['questions'][str_replace("_file_url", "", $key)])) {
                                $content .= "$status<br/>";
                            }
                        }
                    }
                    return $content;
                })
                ->addColumn('action', function ($model) {
                    $btn = '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit" onclick="window.location=\'' . route('agm-minute.edit', Helper::encode($this->module['agm']['minute']['name'], $model->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;&nbsp;';
                    $btn .= '<form action="' . route('agm-minute.destroy', Helper::encode($this->module['agm']['minute']['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" style="display:inline-block;">';
                    $btn .= '<input type="hidden" name="_method" value="DELETE">';
                    $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                    $btn .= '</form>';

                    return $btn;
                })
                ->filter(function ($query) {
                    if (Request::has('file_id') && !empty(Request::get('file_id'))) {
                        $query->where('files.id', Request::get('file_id'));
                    }
                })
                ->make(true);
        }
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::file()
            ->orderBy('created_at', 'desc')
            ->get();

        $viewData = array(
            'title' => trans('app.menus.agm.upload_of_minutes'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmminutesub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'image' => ""
        );

        return View::make('agm-minute.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!empty(Session::get('admin_cob'))) {
            $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_hidden', false)->where('is_deleted', 0)->first();
            if ($cob && $cob->short_name != 'MPKJ') {
                \Log::debug("Session: " . $cob->short_name);
                return Redirect::to('/minutes');
            }
        }
        
        $fileList = Files::file()->get();
        $userList = User::self()->whereNotIn('role', [1, 2])->get();

        $viewData = array(
            'title' => trans('app.menus.agm.upload_of_minutes'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmminutesub_list',
            'fileList' => $fileList,
            'userList' => $userList,
            'image' => ""
        );

        return View::make('agm-minute.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (Request::ajax()) {
            $data = Input::all();
                       
            $validate_fields = array(
                'type' => 'required',
                'agm_type' => 'required',
                'file_no' => 'required|exists:files,id,is_deleted,' . false,
                'agm_date' => 'required'
            );
            $custom_messages = [];
            $extraValidation = $this->getExtraFieldValidate();
            // if(count($extraValidation)) {
            //     foreach($this->getExtraFieldValidate() as $question) {
            //         $validate_fields[$question['field'][1] . "_url"] = 'required_if:'. $question['field'][0] .',1';
            //         $custom_messages[$question['field'][1] . "_url.required_if"] = "This field is requied";
            //     }
            // }            

            $validator = Validator::make($data, $validate_fields, $custom_messages);

            if ($validator->fails()) {
                return Response::json([
                    'error' => true,
                    'errors' => $validator->errors(),
                    'message' => trans('Validation Fail')
                ]);
            } else {
                /** Validation for at least one file to be upload */
                $file_exist = false;
                foreach ($data as $key => $val) {
                    if (str_contains($key, '_url') && !empty($val)) {
                        $file_exist = true;
                    }
                }
                if (!$file_exist) {
                    return Response::json([
                        'error' => true,
                        'message' => trans('app.errors.file_required')
                    ]);
                }

                $description = [];
                /** store all question and files to an array */
                foreach ($data as $key => $val) {
                    if (str_contains($key, 'question_')) {
                        $description[$key] = $val;
                    }
                }

                $file = Files::find($data['file_no']);
                if ($file) {
                    $model = AGMMinute::create([
                        'file_id' => $file->id,
                        'company_id' => Auth::user()->company_id,
                        'type' => $data['type'],
                        'agm_type' => $data['agm_type'],
                        'agm_date' => $data['agm_date'],
                        'description' => serialize($description),
                        'remarks' => $data['remarks'],
                    ]);
    
                    if ($model) {
                        /*
                         * add audit trail
                         */
                        $remarks = 'AGM Minute: (' . $file->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($model->agm_date)) . $this->module['audit']['text']['data_inserted'];
                        $this->addAudit($model->file_id, "COB File", $remarks);
    
                        if (Auth::user()->isJMB()) {
                            /**
                             * Add Notification & send email to COB and JMB
                             */
                            $not_draft_strata = $file->strata;
                            $notify_data['file_id'] = $file->id;
                            $notify_data['route'] = route('agm-minute.edit', Helper::encode($this->module['agm']['minute']['name'], $model->id));
                            $notify_data['cob_route'] = route('agm-minute.edit', Helper::encode($this->module['agm']['minute']['name'], $model->id));
                            $notify_data['strata'] = "You";
                            $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $file->file_no;
                            $notify_data['title'] = "COB File AGM Minutes";
                            $notify_data['module'] = "AGM Minutes";
    
                            (new NotificationService())->store($notify_data);
                        }
                        return Response::json([
                            'success' => true,
                            'message' => trans('app.successes.saved_successfully')
                        ]);
                    }
                }
            }
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if (!empty(Session::get('admin_cob'))) {
            $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_hidden', false)->where('is_deleted', 0)->first();
            if ($cob && $cob->short_name != 'MPKJ') {
                \Log::debug("Session: " . $cob->short_name);
                return Redirect::to('/minutes');
            }
        }
        
        $model = AGMMinute::find(Helper::decode($id, $this->module['agm']['minute']['name']));
        if ($model) {
            $fileList = Files::file()->get();
            $userList = User::self()->whereNotIn('role', [1, 2])->get();
            $configs = $this->getFormFields($model);

            $viewData = array(
                'title' => trans('app.menus.agm.upload_of_minutes'),
                'panel_nav_active' => 'agm_panel',
                'main_nav_active' => 'agm_main',
                'sub_nav_active' => 'agmminutesub_list',
                'fileList' => $fileList,
                'userList' => $userList,
                'questions' => $configs['questions'],
                'model' => $model,
                'image' => ""
            );

            return View::make('agm-minute.edit', $viewData);
        }

        return Redirect::route('agm-minute.index')->with('error', trans('app.errors.occurred'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        if (Request::ajax()) {
            $data = Input::all();
           
            $validate_fields = array(
                'type' => 'required',
                'agm_type' => 'required',
                'file_no' => 'required|exists:files,id,is_deleted,' . false,
                'agm_date' => 'required'
            );

            $custom_messages = [];
            $extraValidation = $this->getExtraFieldValidate();
            // if(count($extraValidation)) {
            //     foreach($this->getExtraFieldValidate() as $question) {
            //         $validate_fields[$question['field'][1] . "_url"] = 'required_if:'. $question['field'][0] .',1';
            //         $custom_messages[$question['field'][1] . "_url.required_if"] = "This field is requied";
            //     }
            // }

            $validator = Validator::make($data, $validate_fields, $custom_messages);

            if ($validator->fails()) {
                return Response::json([
                    'error' => true,
                    'errors' => $validator->errors(),
                    'message' => trans('Validation Fail')
                ]);
            } else {
                /** Validation for at least one file to be upload */
                // $file_exist = false;
                // foreach($data as $key => $val) {
                //     if(str_contains($key, '_url') && !empty($val)) {
                //         $file_exist = true;
                //     }
                // }
                // if(!$file_exist) {
                //     return Response::json([
                //         'error' => true, 
                //         'message' => trans('app.errors.file_required')
                //     ]);
                // }
                $model = AGMMinute::find(Helper::decode($id, $this->module['agm']['minute']['name']));

                if ($model) {
                    $description = [];
                    /** store all question and files to an array */
                    foreach ($data as $key => $val) {
                        if (str_contains($key, 'question_')) {
                            $description[$key] = $val;
                        }
                    }

                    $file = Files::find($data['file_no']);
                    if ($file) {
                        /** Arrange audit fields changes */
                        $audit_fields_changed = '';
                        $new_line = '';
                        $new_line .= $file->id != $model->file_id ? "file no, " : "";
                        $new_line .= $data['type'] != $model->type ? "type, " : "";
                        $new_line .= $data['agm_type'] != $model->agm_type ? "agm type, " : "";
                        $new_line .= $data['agm_date'] != $model->agm_date ? "agm date, " : "";
                        $new_line .= serialize($description) != $model->description ? "agm files, " : "";
                        $new_line .= $data['remarks'] != $model->remarks ? "remarks, " : "";
                        if (!empty($new_line)) {
                            $audit_fields_changed .= "<br/><ul><li> Fields : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                        }
                        /** End Arrange audit fields changes */
    
                        $model->update([
                            'file_id' => $file->id,
                            'type' => $data['type'],
                            'agm_type' => $data['agm_type'],
                            'agm_date' => $data['agm_date'],
                            'description' => serialize($description),
                            'remarks' => $data['remarks'],
                        ]);
    
                        /*
                         * add audit trail
                         */
                        $file = Files::find($model->file_id);
                        if (!empty($audit_fields_changed)) {
                            $remarks = 'AGM Minute: (' . $file->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($model->agm_date)) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                            $this->addAudit($model->file_id, "COB File", $remarks);
                        }
                        if (Auth::user()->isJMB()) {
                            /**
                             * Add Notification & send email to COB and JMB
                             */
                            $not_draft_strata = $file->strata;
                            $notify_data['file_id'] = $file->id;
                            $notify_data['route'] = route('agm-minute.edit', Helper::encode($this->module['agm']['minute']['name'], $model->id));
                            $notify_data['cob_route'] = route('agm-minute.edit', Helper::encode($this->module['agm']['minute']['name'], $model->id));
                            $notify_data['strata'] = "your";
                            $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $file->file_no;
                            $notify_data['title'] = "COB File AGM Minutes";
                            $notify_data['module'] = "AGM Minutes";
    
                            (new NotificationService())->store($notify_data, 'updated');
                        }
    
                        return Response::json([
                            'success' => true,
                            'message' => trans('app.successes.updated_successfully')
                        ]);
                    }
                }
            }
        }

        return Response::json([
            'error' => true,
            'message' => trans('app.errors.occurred')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $model = AGMMinute::find(Helper::decode($id, $this->module['agm']['minute']['name']));
        if ($model) {
            $success = $model->update([
                'is_deleted' => true
            ]);

            if ($success) {
                /*
                 * add audit trail
                 */
                $file = Files::find($model->file_id);
                $remarks = 'AGM Minute: (' . $model->file->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($model->agm_date)) . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($model->file_id, "COB File", $remarks);
                if (Auth::user()->isJMB()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $file->strata;
                    $notify_data['file_id'] = $file->id;
                    $notify_data['route'] = route('minutes.index');
                    $notify_data['cob_route'] = route('minutes.index');
                    $notify_data['strata'] = "your";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $file->file_no;
                    $notify_data['title'] = "COB File AGM Minutes";
                    $notify_data['module'] = "AGM Minutes";

                    (new NotificationService())->store($notify_data, 'deleted');
                }

                return Redirect::back()->with('success', trans('app.successes.deleted_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return View
     */
    public function getForm()
    {
        if (Request::ajax()) {
            $request = Request::all();
            $request['session_check'] = true;
            $data = $this->getFormFields($request);
            if (!empty($request['id'])) {
                $data['model'] = AGMMinute::find(Helper::decode($request['id'], $this->module['agm']['minute']['name']));
                if ($data['model']->agm_type != $request['agm_type']) {
                    $data['model'] = '';
                }
            }

            return View::make('agm-minute.form', $data);
        }
    }

    public function getFormFields($request)
    {
        $data['questions'] = [];

        if ($request['type'] == 'jmb') {
            if ($request['agm_type'] == 'agm') {
                /** return agm view */
                $data['questions'] = Config::get('constant.module.agm.minute.agm.jmb');
                if (!empty($request['session_check']) && $request['session_check']) {
                    $this->setExtraFieldValidate($data['questions']);
                }
            } else if ($request['agm_type'] == 'egm') {
                /** return egm view */
                $data['questions'] = Config::get('constant.module.agm.minute.egm.jmb');
                if (!empty($request['session_check']) && $request['session_check']) {
                    $this->setExtraFieldValidate($data['questions']);
                }
            }
        } else if ($request['type'] == 'mc') {
            if ($request['agm_type'] == 'agm') {
                /** return agm view */
                $data['questions'] = Config::get('constant.module.agm.minute.agm.mc');
                if (!empty($request['session_check']) && $request['session_check']) {
                    $this->setExtraFieldValidate($data['questions']);
                }
            } else if ($request['agm_type'] == 'egm') {
                /** return egm view */
                $data['questions'] = Config::get('constant.module.agm.minute.egm.mc');
                if (!empty($request['session_check']) && $request['session_check']) {
                    $this->setExtraFieldValidate($data['questions']);
                }
            }
        }

        return $data;
    }

    public function fileUpload()
    {
        if (Request::ajax()) {
            $request = Request::all();
            foreach ($request as $key => $val) {
                if (str_contains($key, '_file')) {
                    if (!empty($val)) {
                        $file = $val;
                        $filePath = $request['type'] . '_' . $request['agm_type'];
                        $destinationPath = Config::get('constant.file_directory.' . $filePath);
                        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                        $upload = $file->move($destinationPath, $filename);

                        if ($upload) {
                            return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                        }
                    }
                }
            }
        }
        return Response::json(['error' => true, 'message' => "Fail"]);
    }

    private function setExtraFieldValidate($questions)
    {
        Session::reflash();
        Session::put('extraValidation', $questions);
    }

    private function getExtraFieldValidate()
    {
        return Session::get('extraValidation');
    }

    public static function pageRedirect()
    {
        
    }
}
