<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use yajra\Datatables\Facades\Datatables;

class CobLetterController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("COB Letter"));
        if (Request::ajax()) {
            $model = COBLetter::with(['company'])
                                ->self();
            return Datatables::of($model)
                            ->editColumn('type', function($model) {
                                return $this->getModule()['cob'][Str::lower($model->company->short_name)]['type'][$model->type]['title'];
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                return $created_at;
                            })
                            ->addColumn('action', function ($model) {
                                $btn = '';
                                $btn .= '<a href="' . route('cob_letter.show', Helper::encode($this->getModule()['name'], $model->id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';
                                if(AccessGroup::hasUpdateModule("COB Letter")) {
                                    $id = Helper::encode($this->getModule()['name'], $model->id);
                                    $btn .= '<button class="btn btn-xs btn-success edit-btn" title="Edit" data-id="'. $id .'"><i class="fa fa-pencil"></i></button>&nbsp;';
                                    $btn .= '<form action="' . route('cob_letter.destroy', Helper::encode($this->getModule()['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->getModule()['name'], $model->id) . '" style="display:inline-block;">';
                                    $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                    $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->getModule()['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                    $btn .= '</form>';
                                }

                                return $btn;
                            })
                            ->make(true);
        }
        $viewData = array(
            'title' => trans('app.menus.cob_letter.name'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'cob_letter_list',
            'image' => ''
        );

        return View::make('cob_letter.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsertModule("COB Letter"));
        
        return View::make('cob_letter.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsertModule("COB Letter"));
        if(Request::ajax()) {
            $data = Request::all();
            $rules = array(
                'cob' => 'required',
                'type' => 'required',
                'date' => 'required',
            );
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return Response::json([
                    'error' => true, 
                    'errors' => $validator->errors(), 
                    'message' => trans('Validation Fail')
                ]);
            }

            $custom_messages = [];
            $extraValidation = $this->getFormFields($data);
            $fields = $this->getModule()['fields'];
            if(count($extraValidation)) {
                foreach($extraValidation['attributes'] as $attribute) {
                    if($fields[$attribute]['required']) {
                        $validate_fields[$attribute] = 'required';
                        $custom_messages[$attribute . ".required"] = "This ". $fields[$attribute]['label'] ." field is requied";
                    }
                }
            }
            
            $validator = Validator::make($data, $validate_fields, $custom_messages);
    
            if ($validator->fails()) {
                return Response::json([
                    'error' => true, 
                    'errors' => $validator->errors(), 
                    'message' => trans('Validation Fail')
                ]);
            } else {
                $cob = Company::where('short_name', $data['cob'])->first();
                $cobLetter = COBLetter::create([
                    'company_id' => $cob->id,
                    'type' => $data['type'],
                    'date' => Carbon::createFromTimestamp(strtotime($data['date']))->toDateString(),
                    'bill_no' => $data['bill_no'],
                    'building_name' => !empty($data['building_name']) ? $data['building_name'] : null,
                    'receiver_name' => !empty($data['receiver_name']) ? $data['receiver_name'] : null,
                    'unit_name' => !empty($data['unit_name']) ? $data['unit_name'] : null,
                    'receiver_address_1' => !empty($data['receiver_address_1']) ? $data['receiver_address_1'] : null,
                    'receiver_address_2' => !empty($data['receiver_address_2']) ? $data['receiver_address_2'] : null,
                    'receiver_address_3' => !empty($data['receiver_address_3']) ? $data['receiver_address_3'] : null,
                    'receiver_address_4' => !empty($data['receiver_address_4']) ? $data['receiver_address_4'] : null,
                    'receiver_address_5' => !empty($data['receiver_address_5']) ? $data['receiver_address_5'] : null,
                    'management_address_1' => !empty($data['management_address_1']) ? $data['management_address_1'] : null,
                    'management_address_2' => !empty($data['management_address_2']) ? $data['management_address_2'] : null,
                    'management_address_3' => !empty($data['management_address_3']) ? $data['management_address_3'] : null,
                    'management_address_4' => !empty($data['management_address_4']) ? $data['management_address_4'] : null,
                    'management_address_5' => !empty($data['management_address_5']) ? $data['management_address_5'] : null,
                    'from_address_1' => !empty($data['from_address_1']) ? $data['from_address_1'] : null,
                    'from_address_2' => !empty($data['from_address_2']) ? $data['from_address_2'] : null,
                    'from_address_3' => !empty($data['from_address_3']) ? $data['from_address_3'] : null,
                    'from_address_4' => !empty($data['from_address_4']) ? $data['from_address_4'] : null,
                    'from_address_5' => !empty($data['from_address_5']) ? $data['from_address_5'] : null,
                    'causer_by' => Auth::user()->id
                ]);

                if ($cobLetter) {
                    /*
                     * add audit trail
                     */
                    $letter_name = $this->getModule()['cob'][Str::lower($cobLetter->company->short_name)]['type'][$cobLetter->type]['title'];
                    $remarks = $letter_name. $this->module['audit']['text']['data_inserted'];
                    $this->addAudit(0, "COB Letter", $remarks);
                    
                    return Response::json([
                        'success' => true, 
                        'id' => Helper::encode($this->getModule()['name'], $cobLetter->id), 
                        'message' => trans('app.successes.saved_successfully')
                    ]);
                    
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
    public function show($id) {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("COB Letter"));
        $cobLetter = COBLetter::with(['company'])->findOrFail(Helper::decode($id, $this->getModule()['name']));
        $filename = $cobLetter->type . "_" . date('YmdHis');
        return View::make("cob_letter.". Str::lower($cobLetter->company->short_name) .".$cobLetter->type", compact('cobLetter', 'filename'));
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("COB Letter"));
        $cobLetter = COBLetter::with(['company'])->findOrFail(Helper::decode($id, $this->getModule()['name']));
        $types = (!empty($this->getModule()['cob'][Str::lower($cobLetter->company->short_name)]))? $this->getModule()['cob'][Str::lower($cobLetter->company->short_name)]['type'] : '';
        return View::make("cob_letter.edit", compact('cobLetter', 'types'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("COB Letter"));
        if(Request::ajax()) {
            $data = Request::all();
            $rules = array(
                'cob' => 'required',
                'type' => 'required',
                'date' => 'required',
            );
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return Response::json([
                    'error' => true, 
                    'errors' => $validator->errors(), 
                    'message' => trans('Validation Fail')
                ]);
            }

            $custom_messages = [];
            $extraValidation = $this->getFormFields($data);
            $fields = $this->getModule()['fields'];
            
            if(count($extraValidation)) {
                foreach($extraValidation['attributes'] as $attribute) {
                    if($fields[$attribute]['required']) {
                        $validate_fields[$attribute] = 'required';
                        $custom_messages[$attribute . ".required"] = "This ". $fields[$attribute]['label'] ." field is requied";
                    }
                }
            }
            
            $validator = Validator::make($data, $validate_fields, $custom_messages);
    
            if ($validator->fails()) {
                return Response::json([
                    'error' => true, 
                    'errors' => $validator->errors(), 
                    'message' => trans('Validation Fail')
                ]);
            } else {
                $cobLetter = COBLetter::findOrFail(Helper::decode($id, $this->getModule()['name']));
                $cobLetter->update([
                    'type' => $data['type'],
                    'date' => Carbon::createFromTimestamp(strtotime($data['date']))->toDateString(),
                    'bill_no' => $data['bill_no'],
                    'building_name' => !empty($data['building_name']) ? $data['building_name'] : null,
                    'receiver_name' => !empty($data['receiver_name']) ? $data['receiver_name'] : null,
                    'unit_name' => !empty($data['unit_name']) ? $data['unit_name'] : null,
                    'receiver_address_1' => !empty($data['receiver_address_1']) ? $data['receiver_address_1'] : null,
                    'receiver_address_2' => !empty($data['receiver_address_2']) ? $data['receiver_address_2'] : null,
                    'receiver_address_3' => !empty($data['receiver_address_3']) ? $data['receiver_address_3'] : null,
                    'receiver_address_4' => !empty($data['receiver_address_4']) ? $data['receiver_address_4'] : null,
                    'receiver_address_5' => !empty($data['receiver_address_5']) ? $data['receiver_address_5'] : null,
                    'management_address_1' => !empty($data['management_address_1']) ? $data['management_address_1'] : null,
                    'management_address_2' => !empty($data['management_address_2']) ? $data['management_address_2'] : null,
                    'management_address_3' => !empty($data['management_address_3']) ? $data['management_address_3'] : null,
                    'management_address_4' => !empty($data['management_address_4']) ? $data['management_address_4'] : null,
                    'management_address_5' => !empty($data['management_address_5']) ? $data['management_address_5'] : null,
                    'from_address_1' => !empty($data['from_address_1']) ? $data['from_address_1'] : null,
                    'from_address_2' => !empty($data['from_address_2']) ? $data['from_address_2'] : null,
                    'from_address_3' => !empty($data['from_address_3']) ? $data['from_address_3'] : null,
                    'from_address_4' => !empty($data['from_address_4']) ? $data['from_address_4'] : null,
                    'from_address_5' => !empty($data['from_address_5']) ? $data['from_address_5'] : null,
                ]);

                if ($cobLetter) {
                    /*
                     * add audit trail
                     */
                    $letter_name = $this->getModule()['cob'][Str::lower($cobLetter->company->short_name)]['type'][$cobLetter->type]['title'];
                    $remarks = $letter_name. $this->module['audit']['text']['data_updated'];
                    $this->addAudit(0, "COB Letter", $remarks);
                    
                    return Response::json([
                        'success' => true, 
                        'id' => Helper::encode($this->getModule()['name'], $cobLetter->id), 
                        'message' => trans('app.successes.saved_successfully')
                    ]);
                    
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
    public function destroy($id) {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("COB Letter"));
        $cobLetter = COBLetter::findOrFail(Helper::decode($id, $this->getModule()['name']));
        $cobLetter->delete();
        if($cobLetter) {
            return Redirect::back()->with('success', trans('app.successes.deleted_successfully'));
        }
        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return View
     */
    public function getForm() {
        if(Request::ajax()) {
            $request = Request::all();
            $data = $this->getFormFields($request);
            $data['fields'] = $this->getModule()['fields'];   
            if(!empty($request['id'])) {
                $data['model'] = COBLetter::findOrFail(Helper::decode($request['id'], $this->getModule()['name']));
            }
            return View::make('cob_letter.form', $data);
        }
    }

    public function getFormFields($request) { 
        $module_config = $this->getModule();
        $data['attributes'] = $module_config['cob'][Str::lower($request['cob'])]['type'][$request['type']]['only'];

        return $data;
    }

    private function checkAvailableAccess() {
        if(!AccessGroup::hasAccessModule('Postponed AGM')) {
            App::abort(404);
        }

        if (!Auth::user()->getAdmin() && !Auth::user()->isCOB()) {
            App::abort(404);
        }
    }

    private function getModule() {
        return $this->module['cob_letter'];
    }

}