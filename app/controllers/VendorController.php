<?php

class VendorController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

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
                                $btn .= '<a href="' . route('vendors.edit', $model->id) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                                $btn .= '<form action="' . route('vendors.destroy', $model->id) . '" method="POST" id="delete_form_' . $model->id . '" style="display:inline-block;">';
                                $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $model->id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                $btn .= '</form>';

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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
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
                    'rating' => 'required|integer:min:1|max:10',
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
        $model = Vendor::where('id', $id)->where('is_deleted', 0)->first();

        $viewData = array(
            'title' => trans('app.directory.vendors.view'),
            'panel_nav_active' => 'directory_panel',
            'main_nav_active' => 'directory_main',
            'sub_nav_active' => 'vendor_directory_list',
            'model' => $model,
            'image' => ''
        );

        return View::make('vendors.show', $viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
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
                    'rating' => 'required|integer:min:1|max:10',
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

}
