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
        $dummy = '';
        $review = '';

        if (AccessGroup::hasAccess(58)) {
            $model = Vendor::where('id', $id)->where('is_deleted', 0)->first();

            if ($model) {
                if ($model->id == 1 || $model->id == 2) {
                    $review = array(
                        array(
                            'content' => 'Responsible vendor. Nice work!',
                            'author' => 'Zahir Yusof',
                        ),
                        array(
                            'content' => 'Good vendor!',
                            'author' => 'Ali',
                        )
                    );

                    $dummy = array(
                        array(
                            'name' => 'Apartment Kelana Idaman',
                            'category' => 'Electrical Wiring',
                            'council' => 'MBPJ',
                            'address' => 'Jalan PJU 1a/4a, Kelana Idaman, 47310 Petaling Jaya, Selangor',
                            'status' => 'inprogress',
                            'latitude' => '3.110805',
                            'longitude' => '101.5880721',
                        ),
                        array(
                            'name' => 'Apartment Seri Tulip',
                            'category' => 'Plumbing Repair',
                            'council' => 'MDHS',
                            'address' => 'Jalan Tulip, Bukit Sentosa 2, 48000 Rawang, Selangor',
                            'status' => 'complete',
                            'latitude' => '3.3977034',
                            'longitude' => '101.5604832',
                        ),
                        array(
                            'name' => 'Eristana Townhouse',
                            'category' => 'House Cleaning',
                            'council' => 'MDKS',
                            'address' => 'Eristana, PT 20608, mukim Ijok, Seri Pristana, 47000 Sungai Buloh, Selangor',
                            'status' => 'pending',
                            'latitude' => '3.2134182',
                            'longitude' => '101.469923',
                        ),
                    );
                } else if ($model->id == 3) {
                    $review = array(
                        array(
                            'content' => 'Excellent work!',
                            'author' => 'Abu',
                        ),
                    );

                    $dummy = array(
                        array(
                            'name' => '20 Trees, Taman Melawati',
                            'category' => 'Wall Driling',
                            'council' => 'MPAJ',
                            'address' => 'Taman Melawati, 68000 Kuala Lumpur',
                            'status' => 'inprogress',
                            'latitude' => '3.2195545',
                            'longitude' => '101.7496264',
                        ),
                        array(
                            'name' => '162 Residency',
                            'category' => 'Aircond Servicing',
                            'council' => 'MPS',
                            'address' => 'KM 12, Jalan Ipoh Rawang, One Selayang, 68100 Batu Caves, Selangor',
                            'status' => 'complete',
                            'latitude' => '3.2479752',
                            'longitude' => '101.648426',
                        ),
                    );
                } else if ($model->id == 4) {
                    $review = array(
                        array(
                            'content' => 'Excellent work!',
                            'author' => 'Abu',
                        ),
                    );

                    $dummy = array(
                        array(
                            'name' => 'Apartment Impian Seri Setia @ Park 51',
                            'category' => 'Cutting Grass',
                            'council' => 'MBPJ',
                            'address' => 'No, 2, Jalan 51A/241, Seksyen 51a, 46100 Petaling Jaya, Selangor',
                            'status' => 'pending',
                            'latitude' => '3.0880476',
                            'longitude' => '101.6162902',
                        ),
                        array(
                            'name' => '19 Residency',
                            'category' => 'Plumbing Repair',
                            'council' => 'MBSJ',
                            'address' => '47110, Lebuh Bukit Puchong, Bandar Bukit Puchong, 47100 Puchong, Selangor',
                            'status' => 'inprogress',
                            'latitude' => '2.988620',
                            'longitude' => '101.6241564',
                        ),
                    );
                }

                $viewData = array(
                    'title' => trans('app.directory.vendors.view'),
                    'panel_nav_active' => 'directory_panel',
                    'main_nav_active' => 'directory_main',
                    'sub_nav_active' => 'vendor_directory_list',
                    'model' => $model,
                    'data' => $dummy,
                    'review' => $review,
                    'image' => ''
                );

//                return '<pre>' . print_r($viewData, true) . '</pre>';

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
