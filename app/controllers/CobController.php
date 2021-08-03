<?php

class CobController extends BaseController {

    public function get($id) {
        $company = Company::find($id);

        if ($company) {
            $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

            $viewData = array(
                'title' => $company->name,
                'panel_nav_active' => 'change_cob_panel',
                'main_nav_active' => 'change_cob_main',
                'sub_nav_active' => $company->short_name . '_list',
                'user_permission' => $user_permission,
                'image' => '',
                'company' => $company
            );

            return View::make('page.cob.index', $viewData);
        }
    }

    public function getData($name = '') {
        $cob = Cob::where('type', $name)->get();
        if (count($cob) > 0) {
            $data = Array();
            foreach ($cob as $x) {
                $button = '';
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('CobController@edit', $x->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $data_raw = array(
                    $x->document->name,
                    $x->name,
                    $button
                );
                array_push($data, $data_raw);
            }
            $output_raw = array("aaData" => $data);
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

    public function add($name) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::where('is_active', 1)->where('is_deleted', 0)->get();
        $documents = Documenttype::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('cob.title_add') . strtoupper($name),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_mail',
            'sub_nav_active' => 'cob_list_' . strtoupper($name),
            'user_permission' => $user_permission,
            'files' => $files,
            'documents' => $documents,
            'image' => '',
            'name' => $name
        );

        return View::make('page.cob.add', $viewData);
    }

    public function edit($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $cob = Cob::find($id);
        $files = Files::where('is_active', 1)->where('is_deleted', 0)->get();
        $documents = Documenttype::where('is_active', 1)->where('is_deleted', 0)->get();
        $viewData = array(
            'title' => trans('cob.title_edit') . strtoupper($cob->type),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_mail',
            'sub_nav_active' => 'cob_list_' . strtoupper($cob->type),
            'user_permission' => $user_permission,
            'files' => $files,
            'documents' => $documents,
            'image' => '',
            'name' => $cob->type,
            'cob' => $cob
        );

        return View::make('page.cob.edit', $viewData);
    }

    public function store() {
        $data = Input::all();
        if (Request::ajax()) {
            $cob = new Cob();
            $cob->file_id = $data['file_id'];
            $cob->document_id = $data['document_id'];
            $cob->type = $data['type'];
            $cob->name = $data['name'];
            $cob->is_readonly = $data['is_readonly'];
            $cob->is_hidden = $data['is_hidden'];
            $cob->remark = $data['remark'];
            $success = $cob->save();

            if ($success) {
                # Audit Trail
                $remarks = "New COB {$data['name']} has been inserted.";
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB {$data['name']} Insert";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function update() {
        $data = Input::all();
        if (Request::ajax()) {
            $cob = Cob::find($data['id']);
            $cob->file_id = $data['file_id'];
            $cob->document_id = $data['document_id'];
            $cob->type = $data['type'];
            $cob->name = $data['name'];
            $cob->is_readonly = $data['is_readonly'];
            $cob->is_hidden = $data['is_hidden'];
            $cob->remark = $data['remark'];
            $success = $cob->save();

            if ($success) {
                # Audit Trail
                $remarks = "New COB {$data['name']} has been updated.";
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB {$data['name']} Update";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    /**
     * get files options
     * @param short_name
     */
    public function getOption() {
        $data = Input::all();
        
        if (Request::ajax()) {
            $validation_rules = [
                'short_name' => 'required|exists:company,short_name',
            ];

            $validator = \Validator::make($data, $validation_rules, []);

            if ($validator->fails()) {
                $errors = $validator->errors();

                return [
                    'status' => 422,
                    'data' => $errors->toJson(),
                    'message' => 'Validation Error'
                ];
            }
            $company = Company::where('short_name', $data['short_name'])->first();
            $files = Files::where('company_id', $company->id)
                            ->where('status', true)
                            ->where('is_deleted', false)
                            ->where('approved_at', '!=', '')
                            ->get();
            $data = [];
            if(count($files) > 0) {
                foreach($files as $file) {
                    $new_data = [
                        'key' => $file->id,
                        'title' => $file->file_no
                    ];

                    array_push($data, $new_data);
                }
            }

            return [
                'status' => true,
                'data' => $data
            ];
        }

    }
}
