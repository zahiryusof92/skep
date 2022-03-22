<?php

use Helper\Helper;
use Illuminate\Support\Facades\View;

class CategoryController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Request::ajax()) {
            $model = Category::where('is_deleted', 0);

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
                                    $btn .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveCategory(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                                } else {
                                    $btn .= '<button type="button" class="btn btn-xs btn-info" onclick="activeCategory(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                                }
                                $btn .= '<a href="' . route('category.edit', Helper::encode($model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                                $btn .= '<form action="' . route('category.destroy', Helper::encode($model->id)) . '" method="POST" id="delete_form_' . Helper::encode($model->id) . '" style="display:inline-block;">';
                                $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                $btn .= '</form>';

                                return $btn;
                            })
                            ->make(true);
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(12));

        $viewData = array(
            'title' => trans('app.menus.master.category_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'category_list',
            'image' => ''
        );

        return View::make('category.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(12));

        $viewData = array(
            'title' => trans('app.menus.master.add_category'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'category_list',
            'cob' => $cob,
            'image' => ""
        );

        return View::make('category.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $data = Input::all();
        $rules = [];
        $messages = [];

        if (Auth::user()->isSuperadmin()) {
            $rules = array(
                'description' => 'required',
                'is_active' => 'required',
            );

            foreach ($data['amount'] as $key => $val) {
                $rules['amount.' . $key] = 'required|numeric|min:0';
                $messages['amount.' . $key . '.required'] = 'The amount field is required.';
                $messages['amount.' . $key . '.min'] = 'The amount must be at least :min.';
            }
        } else {
            $rules = array(
                'description' => 'required',
                'is_active' => 'required'
            );
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = new Category();
            $model->description = $data['description'];
            $model->is_active = $data['is_active'];
            $success = $model->save();

            if ($success) {
                if (Auth::user()->isSuperadmin()) {
                    /*
                     * create rate
                     */
                    if (Input::has('company') && Input::has('amount')) {
                        for ($x = 0; $x < count($data['company']); $x++) {
                            $rate = new SummonRate();
                            $rate->company_id = $data['company'][$x];
                            $rate->category_id = $model->id;
                            if (array_key_exists($x, $data['amount'])) {
                                $rate->amount = $data['amount'][$x];
                            }
                            $rate->save();
                        }
                    }
                }

                /*
                 * add audit trail
                 */
                $remarks = 'Category: ' . $model->description . ' has been inserted.';
                $module = 'Master Setup';
                $this->createAuditTrail($remarks, $module);

                return Redirect::to('category')->with('success', trans('app.successes.saved_successfully'));
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
        $model = Category::find(Helper::decode($id));
        if ($model) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdate(12));
            if($disallow) {
                $viewData = array(
                    'title' => trans('app.errors.page_not_found'),
                    'panel_nav_active' => '',
                    'main_nav_active' => '',
                    'sub_nav_active' => '',
                    'image' => ""
                );
                return View::make('404_en', $viewData);
            }

            $viewData = array(
                'title' => trans('app.menus.master.edit_category'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'category_list',
                'cob' => $cob,
                'model' => $model,
                'summonRate' => $model->summonRate->toArray(),
                'image' => ""
            );

            return View::make('category.edit', $viewData);
        }
        
        return Redirect::to('category')->with('error', trans('app.errors.occurred'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $data = Input::all();
        $rules = [];
        $messages = [];

        if (Auth::user()->isSuperadmin()) {
            $rules = array(
                'description' => 'required',
                'is_active' => 'required',
            );

            foreach ($data['amount'] as $key => $val) {
                $rules['amount.' . $key] = 'required|numeric|min:0';
                $messages['amount.' . $key . '.required'] = 'The amount field is required.';
                $messages['amount.' . $key . '.min'] = 'The amount must be at least :min.';
            }
        } else {
            $rules = array(
                'description' => 'required',
                'is_active' => 'required'
            );
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = Category::find(Helper::decode($id));
            if ($model) {
                $model->description = $data['description'];
                $model->is_active = $data['is_active'];
                $success = $model->save();

                if ($success) {
                    if (Auth::user()->isSuperadmin()) {
                        /*
                         * create rate
                         */
                        if (Input::has('company') && Input::has('amount')) {
                            for ($x = 0; $x < count($data['company']); $x++) {
                                $rate = SummonRate::where('company_id', $data['company'][$x])->where('category_id', $model->id)->first();
                                if (!$rate) {
                                    $rate = new SummonRate();
                                }
                                $rate->company_id = $data['company'][$x];
                                $rate->category_id = $model->id;
                                if (array_key_exists($x, $data['amount'])) {
                                    $rate->amount = $data['amount'][$x];
                                }
                                $rate->save();
                            }
                        }
                    }

                    /*
                     * add audit trail
                     */
                    $remarks = 'Category: ' . $model->description . ' has been updated.';
                    $module = 'Master Setup';
                    $this->createAuditTrail($remarks, $module);

                    return Redirect::to('category')->with('success', trans('app.successes.updated_successfully'));
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
        $model = Category::find(Helper::decode($id));
        if ($model) {
            $model->is_deleted = 1;
            $success = $model->save();

            if ($success) {
                /*
                 * add audit trail
                 */
                $remarks = 'Category: ' . $model->description . ' has been deleted.';
                $module = 'Master Setup';
                $this->createAuditTrail($remarks, $module);

                return Redirect::to('category')->with('success', trans('app.successes.deleted_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function inactive() {
        $data = Input::all();
        if (Request::ajax()) {
            $model = Category::find(Helper::decode($data['id']));
            if ($model) {
                $model->is_active = 0;
                $success = $model->save();
                if ($success) {
                    /*
                     * add audit trail
                     */
                    $remarks = 'Category: ' . $model->description . ' has been deactivated.';
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
            $model = Category::find(Helper::decode($data['id']));
            if ($model) {
                $model->is_active = 1;
                $success = $model->save();
                if ($success) {
                    /*
                     * add audit trail
                     */
                    $remarks = 'Category: ' . $model->description . ' has been activated.';
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
