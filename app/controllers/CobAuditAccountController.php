<?php

use Helper\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;

class CobAuditAccountController extends BaseController {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($file_id) {
        $this->checkAvailableAccess();

        if (Request::ajax()) {
            $model = AuditAccount::with(['file'])
                                ->self()
                                ->where('files.id', Helper::decode($file_id));
            
            return Datatables::of($model)
                            ->editColumn('file_id', function($model) {
                                return $model->file->file_no;
                            })
                            ->editColumn('name', function($model) {
                                return ucfirst($model->name);
                            })
                            ->addColumn('action', function ($model) use($file_id) {
                                $btn = '';
                                // if(!empty($model->filename)) {
                                //     $btn .= '<a href="'. asset($model->filename) .'" target="_blank">'
                                //             .'<button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File">'
                                //             .'<i class="icmn-file-download2"></i>'. trans('app.forms.download') 
                                //             .'</button>'
                                //             .'</a>&nbsp;';
                                // }
                                if (AccessGroup::hasUpdateModule('Audit Account')) {
                                    $btn .= '<a href="' . route('cob.audit-account.edit', [Helper::encode($this->module['auditAccount']['name'], $model->id), $file_id]) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                                    $btn .= '<form action="' . route('cob.audit-account.destroy', Helper::encode($this->module['auditAccount']['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->module['auditAccount']['name'], $model->id) . '" style="display:inline-block;">'
                                            . '<input type="hidden" name="_method" value="DELETE">'
                                            . '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->module['auditAccount']['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>'
                                            . '</form>';
                                }
                                return $btn;
                            })
                            ->make(true);
        }
        $file = Files::find(Helper::decode($file_id));

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'files' => $file,
            'image' => ''
        );

        return View::make('page_en.cob.audit-account.index', $viewData);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($file_id) {
        $this->checkAvailableAccess();

        $file = Files::find(Helper::decode($file_id));
        $options = AuditAccount::getCollectionOptions();
        
        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'files' => $file,
            'options' => $options,
            'image' => ""
        );

        return View::make('page_en.cob.audit-account.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $data = Input::all();
        $data['file'] = Helper::decode($data['file'], $this->module['cob']['file']['name']);
        $validator = Validator::make($data, array(
            'file' => 'required|exists:files,id,is_deleted,'. false,
        ));
        if ($validator->fails()) {
            return Response::json([
                'error' => true, 
                'errors' => $validator->errors(), 
                'message' => trans('Validation Fail')
            ]);
        } else {
            $errors = [];
            foreach($data['name'] as $key => $value) {
                if(empty($data['name'][$key])) {
                    $errors['name'][$key] = trans('This name field is required');
                }
                if(empty($data['submission_date'][$key])) {
                    $errors['submission_date'][$key] = trans('This submission date field is required');
                }
                
            }
            if(count($errors) > 0) {
                return Response::json([
                    'error' => true, 
                    'errors' => $errors, 
                    'message' => trans('Validation Fail')
                ]);
            }

            $parent = 0;
            foreach($data['name'] as $key => $value) {
                $model = AuditAccount::create([
                    'file_id' => Helper::decode($data['file'], $this->module['cob']['file']['name']),
                    'parent_id' => $parent,
                    'name' => $data['name'][$key],
                    'submission_date' => $data['submission_date'][$key],
                    'closing_date' => $data['closing_date'][$key],
                    'income_collection' => $data['income_collection'][$key],
                    'expense_collection' => $data['expense_collection'][$key],
                    'filename' => $data['audit_account_url'][$key],
                    'company_id' => Auth::user()->company_id
                ]);
    
                if ($model) {
                    /*
                     * add audit trail
                     */
                    if(!$parent) {
                        $parent = $model->id;
                    }
                    $remarks = 'Audit Account: ' . $model->name . $this->module['audit']['text']['data_inserted'];
                    $this->addAudit($model->file_id, "COB File", $remarks);
                }
            } 

            return Response::json([
                'success' => true, 
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  int  $file_id
     * @return Response
     */
    public function edit($id, $file_id) {
        $this->checkAvailableAccess();
        
        $models = AuditAccount::where('id', Helper::decode($id, $this->module['auditAccount']['name']))
                            ->orWhere('parent_id', Helper::decode($id, $this->module['auditAccount']['name']))
                            ->where('is_deleted', false)
                            ->get();
        $options = AuditAccount::getCollectionOptions();
        if ($models->count()) {
            $file = Files::find(Helper::decode($file_id));

            $viewData = array(
                'title' => trans('app.menus.cob.update_cob_file'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
                'files' => $file,
                'models' => $models,
                'options' => $options,
                'id' => $id,
                'image' => ""
            );

            return View::make('page_en.cob.audit-account.edit', $viewData);
        }
        
        return Redirect::route('cob.audit-account.index', [Helper::encode($file_id)])->with('error', trans('app.errors.occurred'));

    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $data = Input::all();
        $data['file'] = Helper::decode($data['file'], $this->module['cob']['file']['name']);
        $validator = Validator::make($data, array(
            'file' => 'required|exists:files,id,is_deleted,'. false,
        ));
        if ($validator->fails()) {
            return Response::json([
                'error' => true, 
                'errors' => $validator->errors(), 
                'message' => trans('Validation Fail')
            ]);
        } else {
            $errors = [];
            foreach($data['name'] as $key => $value) {
                if(empty($data['name'][$key])) {
                    $errors['name'][$key] = trans('This name field is required');
                }
                if(empty($data['submission_date'][$key])) {
                    $errors['submission_date'][$key] = trans('This submission date field is required');
                }
                
            }
            if(count($errors) > 0) {
                return Response::json([
                    'error' => true, 
                    'errors' => $errors, 
                    'message' => trans('Validation Fail')
                ]);
            }

            $parent = 0;
            $delete = AuditAccount::where('id', Helper::decode($id, $this->module['auditAccount']['name']))
                                ->orWhere('parent_id', Helper::decode($id, $this->module['auditAccount']['name']))
                                ->delete();
            foreach($data['name'] as $key => $value) {
                $model = AuditAccount::create([
                    'file_id' => Helper::decode($data['file'], $this->module['cob']['file']['name']),
                    'parent_id' => $parent,
                    'name' => $data['name'][$key],
                    'submission_date' => $data['submission_date'][$key],
                    'closing_date' => $data['closing_date'][$key],
                    'income_collection' => $data['income_collection'][$key],
                    'expense_collection' => $data['expense_collection'][$key],
                    'filename' => $data['audit_account_url'][$key],
                    'company_id' => Auth::user()->company_id
                ]);

                if ($model) {
                    if(!$parent) {
                        $parent = $model->id;
                    }
                }
            }
            /*
             * add audit trail
             */
            $remarks = 'Audit Account: '. $model->name . $this->module['audit']['text']['data_updated'];
            $this->addAudit($model->file_id, "COB File", $remarks);

            return Response::json([
                'success' => true, 
                'message' => trans('app.successes.updated_successfully')
            ]);
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
    public function destroy($id) {
        $models = AuditAccount::where('id', Helper::decode($id, $this->module['auditAccount']['name']))
                            ->orWhere('parent_id', Helper::decode($id, $this->module['auditAccount']['name']))
                            ->get();
        if ($models) {
            $file_id = 0;
            foreach($models as $model) {
                $success = $model->update([
                    'is_deleted' => true
                ]);
                $file_id = $model->file_id;
    
                if ($success) {
                    /*
                     * add audit trail
                     */
                    $remarks = 'Audit Account : ' . $model->name . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($model->file_id, "COB File", $remarks);
    
                }
            }
            return Redirect::route('cob.audit-account.index', [Helper::encode($file_id)])->with('success', trans('app.successes.deleted_successfully'));
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function fileUpload() {
        if(Request::ajax()) {
            $files = Request::file();
            foreach($files as $file) {
                $destinationPath = Config::get('constant.file_directory.audit_accounts');
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);
    
                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        }
        return Response::json(['error' => true, 'message' => "Fail"]);
    }

    private function checkAvailableAccess() {
        if(!AccessGroup::hasAccessModule('Audit Account')) {
            App::abort(404);
        }
    }
}