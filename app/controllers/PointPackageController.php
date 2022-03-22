<?php

use Helper\Helper;

class PointPackageController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Request::ajax()) {
            $model = PointPackage::where('is_deleted', 0);

            return Datatables::of($model)
                            ->editColumn('is_active', function($model) {
                                $status = trans('app.forms.inactive');
                                if ($model->is_active) {
                                    $status = trans('app.forms.active');
                                }

                                return $status;
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                return $created_at;
                            })
                            ->addColumn('action', function ($model) {
                                $btn = '';
                                if ($model->is_active) {
                                    $btn .= '<button type="button" class="btn btn-xs btn-default" onclick="inactive(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                                } else {
                                    $btn .= '<button type="button" class="btn btn-xs btn-info" onclick="active(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                                }
                                $btn .= '<a href="' . route('pointPackage.edit', Helper::encode($model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                                $btn .= '<form action="' . route('pointPackage.destroy', Helper::encode($model->id)) . '" method="POST" id="delete_form_' . Helper::encode($model->id) . '" style="display:inline-block;">';
                                $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                $btn .= '</form>';

                                return $btn;
                            })
                            ->make(true);
        }

        $viewData = array(
            'title' => trans('app.point_package.title'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'point_package_list',
            'image' => ''
        );

        return View::make('point_package.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $viewData = array(
            'title' => trans('app.point_package.add_package'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'point_package_list',
            'image' => ''
        );

        return View::make('point_package.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $data = Input::all();

        $rules = array(
            'name' => 'required|string|min:3',
            'points' => 'required|numeric',
            'price' => 'required|numeric',
            'is_active' => 'required',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = new PointPackage();
            $model->name = $data['name'];
            $model->points = $data['points'];
            $model->price = $data['price'];
            $model->is_active = $data['is_active'];
            $success = $model->save();

            if ($success) {
                /*
                 * add audit trail
                 */
                $remarks = 'Point Package: ' . $model->name . ' has been inserted.';
                $module = 'Master Setup';
                $this->createAuditTrail($remarks, $module);

                return Redirect::to('pointPackage')->with('success', trans('app.successes.saved_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
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
     * @return Response
     */
    public function edit($id) {
        $model = PointPackage::findOrFail(Helper::decode($id));
        if ($model) {
            $viewData = array(
                'title' => trans('app.point_package.edit_package'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'point_package_list',
                'model' => $model,
                'image' => ''
            );

            return View::make('point_package.edit', $viewData);
        }

        return Redirect::to('pointPackage')->with('error', trans('app.errors.occurred'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $data = Input::all();

        $rules = array(
            'name' => 'required|string|min:3',
            'points' => 'required|numeric',
            'price' => 'required|numeric',
            'is_active' => 'required',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = PointPackage::findOrFail(Helper::decode($id));
            if ($model) {
                $model->name = $data['name'];
                $model->points = $data['points'];
                $model->price = $data['price'];
                $model->is_active = $data['is_active'];
                $success = $model->save();

                if ($success) {
                    /*
                     * add audit trail
                     */
                    $remarks = 'Point Package: ' . $model->name . ' has been updated.';
                    $module = 'Master Setup';
                    $this->createAuditTrail($remarks, $module);

                    return Redirect::to('pointPackage')->with('success', trans('app.successes.updated_successfully'));
                }
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $model = PointPackage::findOrFail(Helper::decode($id));
        if ($model) {
            $model->is_deleted = 1;
            $success = $model->save();

            if ($success) {
                /*
                 * add audit trail
                 */
                $remarks = 'Point Package: ' . $model->name . ' has been deleted.';
                $module = 'Master Setup';
                $this->createAuditTrail($remarks, $module);

                return Redirect::to('pointPackage')->with('success', trans('app.successes.deleted_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function inactive() {
        $data = Input::all();
        if (Request::ajax()) {
            $model = PointPackage::findOrFail(Helper::decode($data['id']));
            if ($model) {
                $model->is_active = 0;
                $success = $model->save();
                if ($success) {
                    /*
                     * add audit trail
                     */
                    $remarks = 'Point Package: ' . $model->name . ' has been deactivated.';
                    $module = 'Master Setup';
                    $this->createAuditTrail($remarks, $module);

                    return 'true';
                }
            }
        }

        return 'false';
    }

    public function active() {
        $data = Input::all();
        if (Request::ajax()) {
            $model = PointPackage::findOrFail(Helper::decode($data['id']));
            if ($model) {
                $model->is_active = 1;
                $success = $model->save();
                if ($success) {
                    /*
                     * add audit trail
                     */
                    $remarks = 'Point Package: ' . $model->name . ' has been activated.';
                    $module = 'Master Setup';
                    $this->createAuditTrail($remarks, $module);

                    return 'true';
                }
            }
        }

        return 'false';
    }

    public function createAuditTrail($remarks, $module) {
        # Audit Trail        
        $auditTrail = new AuditTrail();
        $auditTrail->module = $module;
        $auditTrail->remarks = $remarks;
        $auditTrail->audit_by = Auth::user()->id;
        $auditTrail->save();
    }

}
