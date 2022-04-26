<?php

use Illuminate\Support\Facades\Redirect;

class PropertyAgentController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (AccessGroup::hasAccess(59)) {
            if (Request::ajax()) {
                if (!Auth::user()->getAdmin()) {
                    $company = array(Auth::user()->company_id);
                    $model = PropertyAgent::whereIn('company_id', $company)->where('is_deleted', 0);
                } else {
                    $model = PropertyAgent::where('is_deleted', 0);
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
                                    $btn .= '<a href="' . route('propertyAgents.show', $model->id) . '" class="btn btn-xs btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;';
                                    if (AccessGroup::hasUpdate(59)) {
                                        $btn .= '<a href="' . route('propertyAgents.edit', $model->id) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                                        $btn .= '<form action="' . route('propertyAgents.destroy', $model->id) . '" method="POST" id="delete_form_' . $model->id . '" style="display:inline-block;">';
                                        $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                        $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $model->id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                        $btn .= '</form>';
                                    }

                                    return $btn;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.directory.property_agents.title'),
                'panel_nav_active' => 'directory_panel',
                'main_nav_active' => 'directory_main',
                'sub_nav_active' => 'property_agent_directory_list',
                'image' => ''
            );

            return View::make('property_agents.index', $viewData);
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
        if (AccessGroup::hasInsert(59)) {
            $council = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');

            $viewData = array(
                'title' => trans('app.directory.property_agents.create'),
                'panel_nav_active' => 'directory_panel',
                'main_nav_active' => 'directory_main',
                'sub_nav_active' => 'property_agent_directory_list',
                'council' => $council,
                'image' => ''
            );

            return View::make('property_agents.create', $viewData);
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
                    'company' => 'required|min:3',
                    'name' => 'required|min:3',
                    'address' => 'required|min:5',
                    'council' => 'required|array',
                    'rating' => 'required|integer:min:1|max:10',
                    'remarks' => 'sometimes|string|min:3',
        ));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = new PropertyAgent();
            $model->company = $data['company'];
            $model->name = $data['name'];
            $model->address = $data['address'];
            $model->company_id = json_encode($data['council']);
            $model->rating = $data['rating'];
            $model->remarks = $data['remarks'];
            $success = $model->save();

            if ($success) {
                $remarks = 'Property Agent: '. $model->company . $this->module['audit']['text']['data_inserted'];
                $this->addAudit(0, "Property Agent", $remarks);

                return Redirect::to('propertyAgents')->with('success', trans('app.successes.saved_successfully'));
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
        if (AccessGroup::hasAccess(59)) {
            $model = PropertyAgent::where('id', $id)->where('is_deleted', 0)->first();

            $viewData = array(
                'title' => trans('app.directory.property_agents.view'),
                'panel_nav_active' => 'directory_panel',
                'main_nav_active' => 'directory_main',
                'sub_nav_active' => 'property_agent_directory_list',
                'model' => $model,
                'image' => ''
            );

            return View::make('property_agents.show', $viewData);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (AccessGroup::hasUpdate(59)) {
            $model = PropertyAgent::where('id', $id)->where('is_deleted', 0)->first();
            $council = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');

            $viewData = array(
                'title' => trans('app.directory.property_agents.edit'),
                'panel_nav_active' => 'directory_panel',
                'main_nav_active' => 'directory_main',
                'sub_nav_active' => 'property_agent_directory_list',
                'model' => $model,
                'council' => $council,
                'image' => ''
            );

            return View::make('property_agents.edit', $viewData);
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
                    'company' => 'required|min:3',
                    'name' => 'required|min:3',
                    'address' => 'required|min:5',
                    'council' => 'required|array',
                    'rating' => 'required|integer:min:1|max:10',
                    'remarks' => 'sometimes|string|min:3',
        ));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = PropertyAgent::where('id', $id)->where('is_deleted', 0)->first();
            if ($model) {
                /** Arrange audit fields changes */
                $name_field = $data['name'] == $model->name? "": "name";
                $company_field = $data['company'] == $model->company? "": "company";
                $address_field = $data['address'] == $model->address? "": "address";
                $council_field = json_encode($data['council']) == $model->company_id? "": "council";
                $rating_field = $data['rating'] == $model->rating? "": "rating";
                $remarks_field = $data['remarks'] == $model->remarks? "": "remarks";
    
                $audit_fields_changed = "";
                if(!empty($name_field) || !empty($company_field) || !empty($address_field) || !empty($council_field) || !empty($rating_field)
                || !empty($remarks_field)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= !empty($name_field)? "<li>$name_field</li>" : "";
                    $audit_fields_changed .= !empty($company_field)? "<li>$company_field</li>" : "";
                    $audit_fields_changed .= !empty($address_field)? "<li>$address_field</li>" : "";
                    $audit_fields_changed .= !empty($council_field)? "<li>$council_field</li>" : "";
                    $audit_fields_changed .= !empty($rating_field)? "<li>$rating_field</li>" : "";
                    $audit_fields_changed .= !empty($remarks_field)? "<li>$remarks_field</li>" : "";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                $model->company = $data['company'];
                $model->name = $data['name'];
                $model->address = $data['address'];
                $model->company_id = json_encode($data['council']);
                $model->rating = $data['rating'];
                $model->remarks = $data['remarks'];
                $success = $model->save();

                if ($success) {
                    /*
                     * add audit trail
                     */
                    if(!empty($audit_fields_changed)) {
                        $remarks = 'Property Agent: '. $model->company . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit(0, "Property Agent", $remarks);
                    }

                    return Redirect::to('propertyAgents')->with('success', trans('app.successes.updated_successfully'));
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
        $model = PropertyAgent::find($id);

        if ($model) {
            $model->is_deleted = 1;
            $deleted = $model->save();

            if ($deleted) {
                # Audit Trail
                $remarks = 'Property Agent: '. $model->company . $this->module['audit']['text']['data_deleted'];
                $this->addAudit(0, "Property Agent", $remarks);

                return Redirect::back()->with('delete', trans('app.successes.deleted_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

}
