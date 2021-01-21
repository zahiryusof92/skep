<?php

class VendorController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (AccessGroup::hasAccess(58)) {
            if (Request::ajax()) {
                if (!Auth::user()->getAdmin()) {
                    $company = array(Auth::user()->company_id);
                    $model = Vendor::whereIn('company_id', $company)->where('is_deleted', 0);
                } else {
                    $model = Vendor::where('is_deleted', 0);
                }

                return Datatables::of($model)
                                ->editColumn('rating', function ($model) {
                                    $star = '';
                                    if ($model->rating) {
                                        for ($x = 1; $x <= $model->rating; $x++) {
                                            $star .= '<span class="fa fa-star star-checked"></span>';
                                        }
                                    }
                                    return $star;
                                })
                                ->editColumn('council', function ($model) {
                                    $council_id = json_decode($model->company_id);
                                    $company = Company::whereIn('id', $council_id)->orderBy('name', 'asc')->get();
                                    foreach ($company as $cob) {
                                        $council[] = $cob->name;
                                    }
                                    return implode('<br/>', $council);
                                })
                                ->addColumn('action', function ($model) {
                                    $btn = '';
                                    $btn .= '<a href="' . route('vendors.show', $model->id) . '" class="btn btn-xs btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;';
                                    if (AccessGroup::hasUpdate(58)) {
                                        $btn .= '<a href="' . route('vendors.edit', $model->id) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                                        $btn .= '<form action="' . route('vendors.destroy', $model->id) . '" method="POST" id="delete_form_' . $model->id . '" style="display:inline-block;">';
                                        $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                        $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $model->id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                        $btn .= '</form>';
                                    }

                                    return $btn;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.directory.vendors.title'),
                'panel_nav_active' => 'directory_panel',
                'main_nav_active' => 'directory_main',
                'sub_nav_active' => 'vendor_directory_list',
                'image' => ''
            );

            return View::make('vendors.index', $viewData);
        } else {
            $viewData = array(
                'title' => trans('app.errors.page_not_found'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'image' => ""
            );

            return View::make('404_en', $viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (AccessGroup::hasInsert(58)) {
            $council = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');

            $viewData = array(
                'title' => trans('app.directory.vendors.create'),
                'panel_nav_active' => 'directory_panel',
                'main_nav_active' => 'directory_main',
                'sub_nav_active' => 'vendor_directory_list',
                'council' => $council,
                'image' => ''
            );

            return View::make('vendors.create', $viewData);
        } else {
            $viewData = array(
                'title' => trans('app.errors.page_not_found'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'image' => ""
            );

            return View::make('404_en', $viewData);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $data = Input::all();

        $validator = Validator::make($data, array(
                    'name' => 'required|min:3',
                    'address' => 'required|min:5',
                    'council' => 'required|array',
                    'rating' => 'required|integer|min:1|max:5',
                    'remarks' => 'sometimes|string|min:3',
        ));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = new Vendor();
            $model->name = $data['name'];
            $model->address = $data['address'];
            $model->company_id = json_encode($data['council']);
            $model->rating = $data['rating'];
            $model->remarks = $data['remarks'];
            $success = $model->save();

            if ($success) {
                return Redirect::to('vendors')->with('success', trans('app.successes.saved_successfully'));
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
        if (AccessGroup::hasAccess(58)) {
            $model = Vendor::where('id', $id)->where('is_deleted', 0)->first();

            if ($model) {
                $council = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');
                $category = ProjectCategory::where('is_deleted', 0)->orderBy('name')->lists('name', 'id');

                if (Request::ajax()) {
                    $project = VendorProject::where('vendor_id', $model->id)->where('is_deleted', 0);

                    return Datatables::of($project)
                                    ->editColumn('company_id', function ($project) {
                                        $council = '';
                                        if ($project->company_id) {
                                            $council = $project->company->short_name;
                                        }

                                        return $council;
                                    })
                                    ->editColumn('project_category_id', function ($project) {
                                        $category = '';
                                        if ($project->project_category_id) {
                                            $category = $project->category->name;
                                        }

                                        return $category;
                                    })
                                    ->editColumn('status', function ($project) {
                                        return $project->status();
                                    })
                                    ->addColumn('action', function ($model) {
                                        $btn = '';
                                        if (AccessGroup::hasUpdate(58)) {
                                            $btn .= '<button type="button" class="btn btn-xs btn-warning-outline modal-update-status" data-toggle="modal" data-target="#updateStatusForm" data-id="' . $model->id . '" data-status="' . $model->status . '">' . trans('app.directory.vendors.project.update_status') . '</button>&nbsp;';
                                            $btn .= '<button type="button" class="btn btn-xs btn-info modal-update-project" data-toggle="modal" data-target="#updateProjectForm" data-id="' . $model->id . '" data-name="' . $model->name . '" data-category="' . $model->project_category_id . '"  data-council="' . $model->company_id . '" data-address="' . $model->address . '" data-latitude="' . $model->latitude . '" data-longitude="' . $model->longitude . '"  data-status="' . $model->status . '"><i class="fa fa-pencil" title="' . trans('app.forms.update') . '"></i></button>&nbsp;';
                                            $btn .= '<form action="' . url('vendors/project/destroy/' . $model->id) . '" method="GET" id="delete_form_' . $model->id . '" style="display:inline-block;">';
                                            $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                            $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $model->id . '" title="Delete"><i class="fa fa-trash" title="' . trans('app.forms.delete') . '"></i></button>';
                                            $btn .= '</form>';
                                        }

                                        return $btn;
                                    })
                                    ->make(true);
                }

                $viewData = array(
                    'title' => trans('app.directory.vendors.view'),
                    'panel_nav_active' => 'directory_panel',
                    'main_nav_active' => 'directory_main',
                    'sub_nav_active' => 'vendor_directory_list',
                    'model' => $model,
                    'council' => $council,
                    'category' => $category,
                    'data' => $model->project,
                    'review' => $model->review,
                    'image' => ''
                );

                return View::make('vendors.show', $viewData);
            }
        }

        $viewData = array(
            'title' => trans('app.errors.page_not_found'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => '',
            'image' => ""
        );

        return View::make('404_en', $viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (AccessGroup::hasUpdate(58)) {
            $model = Vendor::where('id', $id)->where('is_deleted', 0)->first();
            $council = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');

            $viewData = array(
                'title' => trans('app.directory.vendors.edit'),
                'panel_nav_active' => 'directory_panel',
                'main_nav_active' => 'directory_main',
                'sub_nav_active' => 'vendor_directory_list',
                'model' => $model,
                'council' => $council,
                'image' => ''
            );

            return View::make('vendors.edit', $viewData);
        } else {
            $viewData = array(
                'title' => trans('app.errors.page_not_found'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'image' => ""
            );

            return View::make('404_en', $viewData);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $data = Input::all();

        $validator = Validator::make($data, array(
                    'name' => 'required|min:3',
                    'address' => 'required|min:5',
                    'council' => 'required|array',
                    'rating' => 'required|integer|min:1|max:5',
                    'remarks' => 'sometimes|string|min:3',
        ));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = Vendor::where('id', $id)->where('is_deleted', 0)->first();
            if ($model) {
                $model->name = $data['name'];
                $model->address = $data['address'];
                $model->company_id = json_encode($data['council']);
                $model->rating = $data['rating'];
                $model->remarks = $data['remarks'];
                $success = $model->save();

                if ($success) {
                    return Redirect::to('vendors')->with('success', trans('app.successes.updated_successfully'));
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
        $model = Vendor::find($id);

        if ($model) {
            $model->is_deleted = 1;
            $deleted = $model->save();

            if ($deleted) {
                return Redirect::back()->with('delete', trans('app.successes.deleted_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function review() {
        $data = Input::all();

        $validator = Validator::make($data, array(
                    'review' => 'required|min:3',
        ));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            if (!empty($data['id'])) {
                $model = new VendorReview();
                $model->vendor_id = $data['id'];
                $model->user_id = Auth::user()->id;
                $model->description = $data['review'];
                $model->is_deleted = 0;
                $success = $model->save();

                if ($success) {
                    return Redirect::to('vendors/' . $model->vendor_id)->with('success', trans('app.successes.saved_successfully'));
                }
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
    }

    public function project() {
        $data = Input::all();

        if (!empty($data['id'])) {
            $model = new VendorProject();
            $model->vendor_id = $data['id'];
            $model->company_id = $data['council'];
            $model->name = $data['name'];
            $model->project_category_id = $data['category'];
            $model->address = $data['address'];
            $model->latitude = $data['latitude'];
            $model->longitude = $data['longitude'];
            $model->status = $data['status'];
            $model->is_deleted = 0;
            $success = $model->save();

            if ($success) {
                return "true";
            }
        }

        return "false";
    }

    public function updateProject() {
        $data = Input::all();

        if (!empty($data['id'])) {
            $model = VendorProject::find($data['id']);
            if ($model) {
                $model->name = $data['name'];
                $model->company_id = $data['council'];
                $model->project_category_id = $data['category'];
                $model->address = $data['address'];
                $model->latitude = $data['latitude'];
                $model->longitude = $data['longitude'];
                $success = $model->save();

                if ($success) {
                    return "true";
                }
            }
        }

        return "false";
    }

    public function status() {
        $data = Input::all();

        if (!empty($data['id'])) {
            $model = VendorProject::find($data['id']);
            if ($model) {
                $model->status = $data['status'];
                $success = $model->save();

                if ($success) {
                    return "true";
                }
            }
        }

        return "false";
    }

    public function destroyProject($id) {
        $model = VendorProject::find($id);

        if ($model) {
            $model->is_deleted = 1;
            $deleted = $model->save();

            if ($deleted) {
                return Redirect::back()->with('delete', trans('app.successes.deleted_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

}
