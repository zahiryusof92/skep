<?php

class SettingController extends BaseController {

    public function showView($name) {
        if (View::exists($name)) {
            return View::make($name);
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

// --- Master Setup --- //
    //area
    public function area() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.area_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'area_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.area', $viewData);
    }

    public function addArea() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_area'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'area_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_area', $viewData);
    }

    public function submitArea() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];

            $area = new Area();
            $area->description = $description;
            $area->is_active = $is_active;
            $success = $area->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Area: ' . $area->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getArea() {
        $area = Area::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($area) > 0) {
            $data = Array();
            foreach ($area as $areas) {
                $button = "";
                if ($areas->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveArea(\'' . $areas->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeArea(\'' . $areas->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateArea', $areas->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteArea(\'' . $areas->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $areas->description,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveArea() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $area = Area::find($id);
            $area->is_active = 0;
            $updated = $area->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Area: ' . $area->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeArea() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $area = Area::find($id);
            $area->is_active = 1;
            $updated = $area->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Area: ' . $area->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteArea() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $area = Area::find($id);
            $area->is_deleted = 1;
            $deleted = $area->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Area: ' . $area->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateArea($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $area = Area::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_area'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'area_list',
            'user_permission' => $user_permission,
            'area' => $area,
            'image' => ""
        );

        return View::make('setting_en.update_area', $viewData);
    }

    public function submitUpdateArea() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];
            $id = $data['id'];

            $area = Area::find($id);
            $area->description = $description;
            $area->is_active = $is_active;
            $success = $area->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Area: ' . $area->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //city
    public function city() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.city_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'city_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.city', $viewData);
    }

    public function addCity() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_city'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'city_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_city', $viewData);
    }

    public function submitCity() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];

            $city = new City();
            $city->description = $description;
            $city->is_active = $is_active;
            $success = $city->save();

            if ($success) {
                # Audit Trail
                $remarks = 'City: ' . $city->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getCity() {
        $city = City::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($city) > 0) {
            $data = Array();
            foreach ($city as $cities) {
                $button = "";
                if ($cities->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveCity(\'' . $cities->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeCity(\'' . $cities->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateCity', $cities->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteCity(\'' . $cities->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $cities->description,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveCity() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $city = City::find($id);
            $city->is_active = 0;
            $updated = $city->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'City: ' . $city->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeCity() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $city = City::find($id);
            $city->is_active = 1;
            $updated = $city->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'City: ' . $city->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteCity() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $city = City::find($id);
            $city->is_deleted = 1;
            $deleted = $city->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'City: ' . $city->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateCity($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $city = City::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_city'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'city_list',
            'user_permission' => $user_permission,
            'city' => $city,
            'image' => ""
        );

        return View::make('setting_en.update_city', $viewData);
    }

    public function submitUpdateCity() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];
            $id = $data['id'];

            $city = City::find($id);
            $city->description = $description;
            $city->is_active = $is_active;
            $success = $city->save();

            if ($success) {
                # Audit Trail
                $remarks = 'City: ' . $city->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //country
    public function country() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.country_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'country_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.country', $viewData);
    }

    public function addCountry() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_country'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'country_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_country', $viewData);
    }

    public function submitCountry() {
        $data = Input::all();
        if (Request::ajax()) {
            $is_active = $data['is_active'];

            $country = new Country();
            $country->name = $data['name'];
            $country->sort_no = $data['sort_no'];
            $country->is_active = $is_active;
            $success = $country->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Country: ' . $country->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getCountry() {
        $country = Country::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($country) > 0) {
            $data = Array();
            foreach ($country as $cities) {
                $button = "";
                if ($cities->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveCountry(\'' . $cities->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeCountry(\'' . $cities->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateCountry', $cities->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteCountry(\'' . $cities->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $cities->name,
                    $cities->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveCountry() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $country = Country::find($id);
            $country->is_active = 0;
            $updated = $country->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Country: ' . $country->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeCountry() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $country = Country::find($id);
            $country->is_active = 1;
            $updated = $country->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Country: ' . $country->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteCountry() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $country = Country::find($id);
            $country->is_deleted = 1;
            $deleted = $country->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Country: ' . $country->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateCountry($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $country = Country::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_country'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'country_list',
            'user_permission' => $user_permission,
            'country' => $country,
            'image' => ""
        );

        return View::make('setting_en.update_country', $viewData);
    }

    public function submitUpdateCountry() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $country = Country::find($id);
            $country->name = $data['name'];
            $country->sort_no = $data['sort_no'];
            $country->is_active = $data['is_active'];
            $success = $country->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Country: ' . $country->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //formtype
    public function formtype() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.form_type_master'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'formtype_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.formtype', $viewData);
    }

    public function addFormType() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_form_type'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'formtype_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_formtype', $viewData);
    }

    public function submitFormType() {
        $data = Input::all();
        if (Request::ajax()) {

            $formtype = new FormType();
            $formtype->name_en = $data['bi_type'];
            $formtype->name_my = $data['bm_type'];
            $formtype->sort_no = $data['sort_no'];
            $formtype->is_active = $data['is_active'];
            $success = $formtype->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Form Type: ' . $formtype->name_en . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getFormType() {
        $formtype = FormType::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($formtype) > 0) {
            $data = Array();
            foreach ($formtype as $ft) {
                $button = "";
                if ($ft->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="inactiveFormtype(\'' . $ft->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeFormtype(\'' . $ft->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateFormtype', $ft->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteFormtype(\'' . $ft->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $ft->name_en,
                    $ft->name_my,
                    $ft->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveFormType() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $formtype = FormType::find($id);
            $formtype->is_active = 0;
            $updated = $formtype->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'FormType: ' . $formtype->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeFormType() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $formtype = FormType::find($id);
            $formtype->is_active = 1;
            $updated = $formtype->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'FormType: ' . $formtype->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteFormType() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $formtype = FormType::find($id);
            $formtype->is_deleted = 1;
            $deleted = $formtype->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'FormType: ' . $formtype->name_en . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateFormType($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $formtype = FormType::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_form_type'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'formtype_list',
            'user_permission' => $user_permission,
            'formtype' => $formtype,
            'image' => ""
        );

        return View::make('setting_en.update_formtype', $viewData);
    }

    public function submitUpdateFormType() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $formtype = FormType::find($id);
            $formtype->name_en = $data['bi_type'];
            $formtype->name_my = $data['bm_type'];
            $formtype->sort_no = $data['sort_no'];
            $formtype->is_active = $data['is_active'];
            $success = $formtype->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Form Type: ' . $formtype->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //state
    public function state() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.state_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'state_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.state', $viewData);
    }

    public function addState() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_state'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'state_list',
            'user_permission' => $user_permission,
            'codeList' => State::codeList(),
            'image' => ""
        );

        return View::make('setting_en.add_state', $viewData);
    }

    public function submitState() {
        $data = Input::all();
        if (Request::ajax()) {
            $is_active = $data['is_active'];

            $state = new State();
            $state->name = $data['name'];
            $state->code = $data['code'];
            $state->sort_no = $data['sort_no'];
            $state->is_active = $is_active;
            $success = $state->save();

            if ($success) {
                # Audit Trail
                $remarks = 'State: ' . $state->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getState() {
        $state = State::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($state) > 0) {
            $data = Array();
            foreach ($state as $states) {
                $button = "";
                if ($states->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveState(\'' . $states->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeState(\'' . $states->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateState', $states->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteState(\'' . $states->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $states->name,
                    $states->code,
                    $states->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveState() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $state = State::find($id);
            $state->is_active = 0;
            $updated = $state->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'State: ' . $state->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeState() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $state = State::find($id);
            $state->is_active = 1;
            $updated = $state->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'State: ' . $state->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteState() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $state = State::find($id);
            $state->is_deleted = 1;
            $deleted = $state->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'State: ' . $state->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateState($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $state = State::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_state'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'state_list',
            'user_permission' => $user_permission,
            'state' => $state,
            'codeList' => State::codeList(),
            'image' => ""
        );

        return View::make('setting_en.update_state', $viewData);
    }

    public function submitUpdateState() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $state = State::find($id);
            $state->name = $data['name'];
            $state->code = $data['code'];
            $state->sort_no = $data['sort_no'];
            $state->is_active = $data['is_active'];
            $success = $state->save();

            if ($success) {
                # Audit Trail
                $remarks = 'State: ' . $state->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    // Document Type
    public function documenttype() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.document_type_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'documenttype_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.documenttype', $viewData);
    }

    public function addDocumenttype() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_document_type'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'documenttype_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_documenttype', $viewData);
    }

    public function submitDocumenttype() {
        $data = Input::all();
        if (Request::ajax()) {
            $is_active = $data['is_active'];

            $documenttype = new Documenttype();
            $documenttype->name = $data['name'];
            $documenttype->sort_no = $data['sort_no'];
            $documenttype->is_active = $is_active;
            $documenttype->is_deleted = 0;
            $success = $documenttype->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Document Type: ' . $documenttype->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getDocumenttype() {
        $documenttype = Documenttype::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($documenttype) > 0) {
            $data = Array();
            foreach ($documenttype as $cities) {
                $button = "";
                if ($cities->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveDocumenttype(\'' . $cities->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeDocumenttype(\'' . $cities->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateDocumenttype', $cities->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDocumenttype(\'' . $cities->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $cities->name,
                    $cities->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveDocumenttype() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $documenttype = Documenttype::find($id);
            $documenttype->is_active = 0;
            $updated = $documenttype->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Document Type: ' . $documenttype->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeDocumenttype() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $documenttype = Documenttype::find($id);
            $documenttype->is_active = 1;
            $updated = $documenttype->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Document Type: ' . $documenttype->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteDocumenttype() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $documenttype = Documenttype::find($id);
            $documenttype->is_deleted = 1;
            $deleted = $documenttype->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Document Type: ' . $documenttype->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateDocumenttype($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $documenttype = Documenttype::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_document_type'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'documenttype_list',
            'user_permission' => $user_permission,
            'documenttype' => $documenttype,
            'image' => ""
        );

        return View::make('setting_en.update_documenttype', $viewData);
    }

    public function submitUpdateDocumenttype() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $documenttype = Documenttype::find($id);
            $documenttype->name = $data['name'];
            $documenttype->sort_no = $data['sort_no'];
            $documenttype->is_active = $data['is_active'];
            $success = $documenttype->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Document Type: ' . $documenttype->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //category
    public function category() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.category_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'category_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.category', $viewData);
    }

    public function addCategory() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_category'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'category_list',
            'user_permission' => $user_permission,
            'codeList' => Category::codeList(),
            'image' => ""
        );

        return View::make('setting_en.add_category', $viewData);
    }

    public function submitCategory() {
        $data = Input::all();
        if (Request::ajax()) {
            $category = new Category();
            $category->description = $data['description'];
            $category->code = $data['code'];
            $category->is_active = $data['is_active'];
            $success = $category->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Category: ' . $category->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getCategory() {
        $category = Category::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($category) > 0) {
            $data = Array();
            foreach ($category as $categories) {
                $button = "";
                if ($categories->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveCategory(\'' . $categories->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeCategory(\'' . $categories->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateCategory', $categories->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteCategory(\'' . $categories->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $categories->description,
                    $categories->code,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveCategory() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $category = Category::find($id);
            $category->is_active = 0;
            $updated = $category->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Category: ' . $category->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeCategory() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $category = Category::find($id);
            $category->is_active = 1;
            $updated = $category->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Category: ' . $category->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteCategory() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $category = Category::find($id);
            $category->is_deleted = 1;
            $deleted = $category->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Category: ' . $category->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateCategory($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $category = Category::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_category'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'category_list',
            'user_permission' => $user_permission,
            'category' => $category,
            'codeList' => Category::codeList(),
            'image' => ""
        );

        return View::make('setting_en.update_category', $viewData);
    }

    public function submitUpdateCategory() {
        $data = Input::all();
        if (Request::ajax()) {
            $category = Category::find($data['id']);
            $category->description = $data['description'];
            $category->code = $data['code'];
            $category->is_active = $data['is_active'];
            $success = $category->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Category: ' . $category->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //land title
    public function landTitle() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.land_title_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'land_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.land', $viewData);
    }

    public function addLandTitle() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_land_title'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'land_list',
            'user_permission' => $user_permission,
            'codeList' => LandTitle::codeList(),
            'image' => ""
        );

        return View::make('setting_en.add_land', $viewData);
    }

    public function submitLandTitle() {
        $data = Input::all();
        if (Request::ajax()) {
            $land = new LandTitle();
            $land->description = $data['description'];
            $land->code = $data['code'];
            $land->is_active = $data['is_active'];
            $success = $land->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Land Title: ' . $land->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getLandTitle() {
        $land = LandTitle::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($land) > 0) {
            $data = Array();
            foreach ($land as $lands) {
                $button = "";
                if ($lands->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveLandTitle(\'' . $lands->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeLandTitle(\'' . $lands->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateLandTitle', $lands->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteLandTitle(\'' . $lands->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $lands->description,
                    $lands->code,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveLandTitle() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $land = LandTitle::find($id);
            $land->is_active = 0;
            $updated = $land->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Land Title: ' . $land->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeLandTitle() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $land = LandTitle::find($id);
            $land->is_active = 1;
            $updated = $land->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Land Title: ' . $land->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteLandTitle() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $land = LandTitle::find($id);
            $land->is_deleted = 1;
            $deleted = $land->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Land Title: ' . $land->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateLandTitle($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $land = LandTitle::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_land_title'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'land_list',
            'user_permission' => $user_permission,
            'land' => $land,
            'codeList' => LandTitle::codeList(),
            'image' => ""
        );

        return View::make('setting_en.update_land', $viewData);
    }

    public function submitUpdateLandTitle() {
        $data = Input::all();
        if (Request::ajax()) {
            $land = LandTitle::find($data['id']);
            $land->description = $data['description'];
            $land->code = $data['code'];
            $land->is_active = $data['is_active'];
            $success = $land->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Land Title: ' . $land->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //developer
    public function developer() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.developer_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'developer_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.developer', $viewData);
    }

    public function addDeveloper() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.master.add_developer'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'developer_list',
            'user_permission' => $user_permission,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'image' => ""
        );

        return View::make('setting_en.add_developer', $viewData);
    }

    public function submitDeveloper() {
        $data = Input::all();
        if (Request::ajax()) {
            $name = $data['name'];
            $address1 = $data['address1'];
            $address2 = $data['address2'];
            $address3 = $data['address3'];
            $city = $data['city'];
            $poscode = $data['poscode'];
            $state = $data['state'];
            $country = $data['country'];
            $phone_no = $data['phone_no'];
            $fax_no = $data['fax_no'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $developer = new Developer();
            $developer->name = $name;
            $developer->address1 = $address1;
            $developer->address2 = $address2;
            $developer->address3 = $address3;
            $developer->city = $city;
            $developer->poscode = $poscode;
            $developer->state = $state;
            $developer->country = $country;
            $developer->phone_no = $phone_no;
            $developer->fax_no = $fax_no;
            $developer->remarks = $remarks;
            $developer->is_active = $is_active;
            $success = $developer->save();

            if ($success) {
                # Audit Trail
                $remarks = $developer->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getDeveloper() {
        $developer = Developer::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($developer) > 0) {
            $data = Array();
            foreach ($developer as $developers) {
                $button = "";
                if ($developers->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveDeveloper(\'' . $developers->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeDeveloper(\'' . $developers->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateDeveloper', $developers->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDeveloper(\'' . $developers->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $developers->name,
                    $developers->phone_no,
                    $developers->fax_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveDeveloper() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $developer = Developer::find($id);
            $developer->is_active = 0;
            $updated = $developer->save();
            if ($updated) {
                # Audit Trail
                $remarks = $developer->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeDeveloper() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $developer = Developer::find($id);
            $developer->is_active = 1;
            $updated = $developer->save();
            if ($updated) {
                # Audit Trail
                $remarks = $developer->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteDeveloper() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $developer = Developer::find($id);
            $developer->is_deleted = 1;
            $deleted = $developer->save();
            if ($deleted) {
                # Audit Trail
                $remarks = $developer->name . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateDeveloper($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $developer = Developer::find($id);
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.master.edit_developer'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'developer_list',
            'user_permission' => $user_permission,
            'developer' => $developer,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'image' => ""
        );

        return View::make('setting_en.update_developer', $viewData);
    }

    public function submitUpdateDeveloper() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $name = $data['name'];
            $address1 = $data['address1'];
            $address2 = $data['address2'];
            $address3 = $data['address3'];
            $city = $data['city'];
            $poscode = $data['poscode'];
            $state = $data['state'];
            $country = $data['country'];
            $phone_no = $data['phone_no'];
            $fax_no = $data['fax_no'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $developer = Developer::find($id);
            $developer->name = $name;
            $developer->address1 = $address1;
            $developer->address2 = $address2;
            $developer->address3 = $address3;
            $developer->city = $city;
            $developer->poscode = $poscode;
            $developer->state = $state;
            $developer->country = $country;
            $developer->phone_no = $phone_no;
            $developer->fax_no = $fax_no;
            $developer->remarks = $remarks;
            $developer->is_active = $is_active;
            $success = $developer->save();

            if ($success) {
                # Audit Trail
                $remarks = $developer->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //agent
    public function agent() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.agent_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'agent_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.agent', $viewData);
    }

    public function addAgent() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.master.add_agent'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'agent_list',
            'user_permission' => $user_permission,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'image' => ""
        );

        return View::make('setting_en.add_agent', $viewData);
    }

    public function submitAgent() {
        $data = Input::all();
        if (Request::ajax()) {
            $name = $data['name'];
            $address1 = $data['address1'];
            $address2 = $data['address2'];
            $address3 = $data['address3'];
            $city = $data['city'];
            $poscode = $data['poscode'];
            $state = $data['state'];
            $country = $data['country'];
            $phone_no = $data['phone_no'];
            $fax_no = $data['fax_no'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $agent = new Agent();
            $agent->name = $name;
            $agent->address1 = $address1;
            $agent->address2 = $address2;
            $agent->address3 = $address3;
            $agent->city = $city;
            $agent->poscode = $poscode;
            $agent->state = $state;
            $agent->country = $country;
            $agent->phone_no = $phone_no;
            $agent->fax_no = $fax_no;
            $agent->remarks = $remarks;
            $agent->is_active = $is_active;
            $success = $agent->save();

            if ($success) {
                # Audit Trail
                $remarks = $agent->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getAgent() {
        $agent = Agent::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($agent) > 0) {
            $data = Array();
            foreach ($agent as $agents) {
                $button = "";
                if ($agents->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveAgent(\'' . $agents->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeAgent(\'' . $agents->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateAgent', $agents->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteAgent(\'' . $agents->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $agents->name,
                    $agents->phone_no,
                    $agents->fax_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveAgent() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agent = Agent::find($id);
            $agent->is_active = 0;
            $updated = $agent->save();
            if ($updated) {
                # Audit Trail
                $remarks = $agent->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeAgent() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agent = Agent::find($id);
            $agent->is_active = 1;
            $updated = $agent->save();
            if ($updated) {
                # Audit Trail
                $remarks = $agent->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteAgent() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agent = Agent::find($id);
            $agent->is_deleted = 1;
            $deleted = $agent->save();
            if ($deleted) {
                # Audit Trail
                $remarks = $agent->name . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateAgent($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $agent = Agent::find($id);
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.master.edit_agent'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'agent_list',
            'user_permission' => $user_permission,
            'agent' => $agent,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'image' => ""
        );

        return View::make('setting_en.update_agent', $viewData);
    }

    public function submitUpdateAgent() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $name = $data['name'];
            $address1 = $data['address1'];
            $address2 = $data['address2'];
            $address3 = $data['address3'];
            $city = $data['city'];
            $poscode = $data['poscode'];
            $state = $data['state'];
            $country = $data['country'];
            $phone_no = $data['phone_no'];
            $fax_no = $data['fax_no'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $agent = Agent::find($id);
            $agent->name = $name;
            $agent->address1 = $address1;
            $agent->address2 = $address2;
            $agent->address3 = $address3;
            $agent->city = $city;
            $agent->poscode = $poscode;
            $agent->state = $state;
            $agent->country = $country;
            $agent->phone_no = $phone_no;
            $agent->fax_no = $fax_no;
            $agent->remarks = $remarks;
            $agent->is_active = $is_active;
            $success = $agent->save();

            if ($success) {
                # Audit Trail
                $remarks = $agent->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //parliment
    public function parliment() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.parliament_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'parliament_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.parliment', $viewData);
    }

    public function addParliment() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_parliament'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'parliament_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_parliment', $viewData);
    }

    public function submitParliment() {
        $data = Input::all();
        if (Request::ajax()) {
            $parliment = new Parliment();
            $parliment->description = $data['description'];
            $parliment->code = $data['code'];
            $parliment->is_active = $data['is_active'];
            $success = $parliment->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Parliament: ' . $parliment->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getParliment() {
        $parliment = Parliment::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($parliment) > 0) {
            $data = Array();
            foreach ($parliment as $parliments) {
                $button = "";
                if ($parliments->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveParliment(\'' . $parliments->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeParliment(\'' . $parliments->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateParliment', $parliments->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteParliment(\'' . $parliments->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $parliments->description,
                    $parliments->code,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveParliment() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $parliment = Parliment::find($id);
            $parliment->is_active = 0;
            $updated = $parliment->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Parliament: ' . $parliment->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeParliment() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $parliment = Parliment::find($id);
            $parliment->is_active = 1;
            $updated = $parliment->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Parliament: ' . $parliment->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteParliment() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $parliment = Parliment::find($id);
            $parliment->is_deleted = 1;
            $deleted = $parliment->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Parliament: ' . $parliment->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateParliment($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $parliment = Parliment::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_parliament'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'parliament_list',
            'user_permission' => $user_permission,
            'parliment' => $parliment,
            'image' => ""
        );

        return View::make('setting_en.update_parliment', $viewData);
    }

    public function submitUpdateParliment() {
        $data = Input::all();
        if (Request::ajax()) {
            $parliment = Parliment::find($data['id']);
            $parliment->description = $data['description'];
            $parliment->code = $data['code'];
            $parliment->is_active = $data['is_active'];
            $success = $parliment->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Parliament: ' . $parliment->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //DUN
    public function dun() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.master.dun_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'dun_list',
            'user_permission' => $user_permission,
            'parliament' => $parliament,
            'image' => ""
        );

        return View::make('setting_en.dun', $viewData);
    }

    public function addDun() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.master.add_dun'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'dun_list',
            'user_permission' => $user_permission,
            'parliament' => $parliament,
            'image' => ""
        );

        return View::make('setting_en.add_dun', $viewData);
    }

    public function submitDun() {
        $data = Input::all();
        if (Request::ajax()) {
            $dun = new Dun();
            $dun->parliament = $data['parliament'];
            $dun->description = $data['description'];
            $dun->code = $data['code'];
            $dun->is_active = $data['is_active'];
            $success = $dun->save();

            if ($success) {
                # Audit Trail
                $remarks = 'DUN: ' . $dun->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getDun() {
        $dun = Dun::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($dun) > 0) {
            $data = Array();
            foreach ($dun as $duns) {
                $parliament = Parliment::find($duns->parliament);
                $button = "";
                if ($duns->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveDun(\'' . $duns->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeDun(\'' . $duns->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateDun', $duns->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDun(\'' . $duns->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $duns->description,
                    ($duns->parliament ? $parliament->description : ''),
                    $duns->code,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveDun() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $dun = Dun::find($id);
            $dun->is_active = 0;
            $updated = $dun->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'DUN: ' . $dun->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeDun() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $dun = Dun::find($id);
            $dun->is_active = 1;
            $updated = $dun->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'DUN: ' . $dun->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteDun() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $dun = Dun::find($id);
            $dun->is_deleted = 1;
            $deleted = $dun->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'DUN: ' . $dun->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateDun($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $dun = Dun::find($id);
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.master.edit_dun'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'dun_list',
            'user_permission' => $user_permission,
            'dun' => $dun,
            'parliament' => $parliament,
            'image' => ""
        );

        return View::make('setting_en.update_dun', $viewData);
    }

    public function submitUpdateDun() {
        $data = Input::all();
        if (Request::ajax()) {
            $dun = Dun::find($data['id']);
            $dun->parliament = $data['parliament'];
            $dun->description = $data['description'];
            $dun->code = $data['code'];
            $dun->is_active = $data['is_active'];
            $success = $dun->save();

            if ($success) {
                # Audit Trail
                $remarks = 'DUN: ' . $dun->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //Park
    public function park() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $dun = Dun::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        $viewData = array(
            'title' => trans('app.menus.master.park_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'park_list',
            'user_permission' => $user_permission,
            'dun' => $dun,
            'image' => ""
        );

        return View::make('setting_en.park', $viewData);
    }

    public function addPark() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $dun = Dun::where('is_active', 1)->where('is_deleted', 0)->orderBy('description')->get();

        $viewData = array(
            'title' => trans('app.menus.master.add_park'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'park_list',
            'user_permission' => $user_permission,
            'dun' => $dun,
            'image' => ""
        );

        return View::make('setting_en.add_park', $viewData);
    }

    public function submitPark() {
        $data = Input::all();
        if (Request::ajax()) {
            $dun = $data['dun'];
            $description = $data['description'];
            $is_active = $data['is_active'];

            $park = new Park();
            $park->dun = $dun;
            $park->description = $description;
            $park->is_active = $is_active;
            $success = $park->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Park: ' . $park->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getPark() {
        $park = Park::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($park) > 0) {
            $data = Array();
            foreach ($park as $parks) {
                $dun = Dun::find($parks->dun);
                $button = "";
                if ($parks->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactivePark(\'' . $parks->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activePark(\'' . $parks->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updatePark', $parks->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deletePark(\'' . $parks->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $parks->description,
                    ($parks->dun ? $dun->description : ''),
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactivePark() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $park = Park::find($id);
            $park->is_active = 0;
            $updated = $park->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Park: ' . $park->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activePark() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $park = Park::find($id);
            $park->is_active = 1;
            $updated = $park->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Park: ' . $park->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deletePark() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $park = Park::find($id);
            $park->is_deleted = 1;
            $deleted = $park->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Park: ' . $park->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updatePark($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $park = Park::find($id);
        $dun = Dun::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.master.edit_park'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'park_list',
            'user_permission' => $user_permission,
            'park' => $park,
            'dun' => $dun,
            'image' => ""
        );

        return View::make('setting_en.update_park', $viewData);
    }

    public function submitUpdatePark() {
        $data = Input::all();
        if (Request::ajax()) {
            $dun = $data['dun'];
            $description = $data['description'];
            $is_active = $data['is_active'];
            $id = $data['id'];

            $park = Park::find($id);
            $park->dun = $dun;
            $park->description = $description;
            $park->is_active = $is_active;
            $success = $park->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Park: ' . $park->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //memo type
    public function memoType() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.memo_type_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'memo_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.memotype', $viewData);
    }

    public function addMemoType() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_memo_type'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'memo_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_memotype', $viewData);
    }

    public function submitMemoType() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];

            $memotype = new MemoType();
            $memotype->description = $description;
            $memotype->is_active = $is_active;
            $success = $memotype->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Memo Type: ' . $memotype->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getMemoType() {
        $memotype = MemoType::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($memotype) > 0) {
            $data = Array();
            foreach ($memotype as $memotypes) {
                $button = "";
                if ($memotypes->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveMemoType(\'' . $memotypes->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeMemoType(\'' . $memotypes->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateMemoType', $memotypes->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteMemoType(\'' . $memotypes->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $memotypes->description,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveMemoType() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $memotype = MemoType::find($id);
            $memotype->is_active = 0;
            $updated = $memotype->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Memo Type: ' . $memotype->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeMemoType() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $memotype = MemoType::find($id);
            $memotype->is_active = 1;
            $updated = $memotype->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Memo Type: ' . $memotype->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteMemoType() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $memotype = MemoType::find($id);
            $memotype->is_deleted = 1;
            $deleted = $memotype->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Memo Type: ' . $memotype->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateMemoType($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $memoType = MemoType::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_memo_type'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'memo_list',
            'user_permission' => $user_permission,
            'memoType' => $memoType,
            'image' => ""
        );

        return View::make('setting_en.update_memotype', $viewData);
    }

    public function submitUpdateMemoType() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];
            $id = $data['id'];

            $memotype = MemoType::find($id);
            $memotype->description = $description;
            $memotype->is_active = $is_active;
            $success = $memotype->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Memo Type: ' . $memotype->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //Designation
    public function designation() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.designation_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'designation_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.designation', $viewData);
    }

    public function addDesignation() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_designation'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'designation_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_designation', $viewData);
    }

    public function submitDesignation() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];

            $designation = new Designation();
            $designation->description = $description;
            $designation->is_active = $is_active;
            $success = $designation->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Designation: ' . $designation->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getDesignation() {
        $designation = Designation::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($designation) > 0) {
            $data = Array();
            foreach ($designation as $designations) {
                $button = "";
                if ($designations->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveDesignation(\'' . $designations->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeDesignation(\'' . $designations->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateDesignation', $designations->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDesignation(\'' . $designations->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $designations->description,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveDesignation() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $designation = Designation::find($id);
            $designation->is_active = 0;
            $updated = $designation->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Designation: ' . $designation->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeDesignation() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $designation = Designation::find($id);
            $designation->is_active = 1;
            $updated = $designation->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Designation: ' . $designation->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteDesignation() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $designation = Designation::find($id);
            $designation->is_deleted = 1;
            $deleted = $designation->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Designation: ' . $designation->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateDesignation($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $designation = Designation::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_designation'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'designation_list',
            'user_permission' => $user_permission,
            'designation' => $designation,
            'image' => ""
        );

        return View::make('setting_en.update_designation', $viewData);
    }

    public function submitUpdateDesignation() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];
            $id = $data['id'];

            $designation = Designation::find($id);
            $designation->description = $description;
            $designation->is_active = $is_active;
            $success = $designation->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Designation: ' . $designation->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //Unit Measure
    public function unitMeasure() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.unit_of_measure_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'unit_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.unitmeasure', $viewData);
    }

    public function addUnitMeasure() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_unit_of_measure'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'unit_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_unitmeasure', $viewData);
    }

    public function submitUnitMeasure() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];

            $unitmeasure = new UnitMeasure();
            $unitmeasure->description = $description;
            $unitmeasure->is_active = $is_active;
            $success = $unitmeasure->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Unit of Measure: ' . $unitmeasure->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getUnitMeasure() {
        $unitmeasure = UnitMeasure::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($unitmeasure) > 0) {
            $data = Array();
            foreach ($unitmeasure as $unitmeasures) {
                $button = "";
                if ($unitmeasures->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveUnitMeasure(\'' . $unitmeasures->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeUnitMeasure(\'' . $unitmeasures->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateUnitMeasure', $unitmeasures->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteUnitMeasure(\'' . $unitmeasures->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $unitmeasures->description,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveUnitMeasure() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $unitmeasure = UnitMeasure::find($id);
            $unitmeasure->is_active = 0;
            $updated = $unitmeasure->save();

            if ($updated) {
                # Audit Trail
                $remarks = 'Unit of Measure: ' . $unitmeasure->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeUnitMeasure() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $unitmeasure = UnitMeasure::find($id);
            $unitmeasure->is_active = 1;
            $updated = $unitmeasure->save();

            if ($updated) {
                # Audit Trail
                $remarks = 'Unit of Measure: ' . $unitmeasure->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteUnitMeasure() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $unitmeasure = UnitMeasure::find($id);
            $unitmeasure->is_deleted = 1;
            $deleted = $unitmeasure->save();

            if ($deleted) {
                # Audit Trail
                $remarks = 'Unit of Measure: ' . $unitmeasure->description . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateUnitMeasure($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $unitmeasure = UnitMeasure::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_unit_of_measure'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'unit_list',
            'user_permission' => $user_permission,
            'unitmeasure' => $unitmeasure,
            'image' => ""
        );

        return View::make('setting_en.update_unitmeasure', $viewData);
    }

    public function submitUpdateUnitMeasure() {
        $data = Input::all();
        if (Request::ajax()) {
            $description = $data['description'];
            $is_active = $data['is_active'];
            $id = $data['id'];

            $unitmeasure = UnitMeasure::find($id);
            $unitmeasure->description = $description;
            $unitmeasure->is_active = $is_active;
            $success = $unitmeasure->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Unit of Measure: ' . $unitmeasure->description . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    // race
    public function race() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.race_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'race_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.race', $viewData);
    }

    public function addRace() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_race'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'race_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_race', $viewData);
    }

    public function submitRace() {
        $data = Input::all();
        if (Request::ajax()) {
            $is_active = $data['is_active'];

            $race = new Race();
            $race->name_en = $data['name_en'];
            $race->name_my = $data['name_my'];
            $race->sort_no = $data['sort_no'];
            $race->is_active = $is_active;
            $success = $race->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Race: ' . $race->name_en . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getRace() {
        $race = Race::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($race) > 0) {
            $data = Array();
            foreach ($race as $races) {
                $button = "";
                if ($races->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="inactiveRace(\'' . $races->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeRace(\'' . $races->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateRace', $races->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteRace(\'' . $races->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $races->name_en,
                    $races->name_my,
                    $races->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveRace() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $race = Race::find($id);
            $race->is_active = 0;
            $updated = $race->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Race: ' . $race->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeRace() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $race = Race::find($id);
            $race->is_active = 1;
            $updated = $race->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Race: ' . $race->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteRace() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $race = Race::find($id);
            $race->is_deleted = 1;
            $deleted = $race->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Race: ' . $race->name_en . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateRace($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $race = Race::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_race'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'race_list',
            'user_permission' => $user_permission,
            'race' => $race,
            'image' => ""
        );

        return View::make('setting_en.update_race', $viewData);
    }

    public function submitUpdateRace() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $race = Race::find($id);
            $race->name_en = $data['name_en'];
            $race->name_my = $data['name_my'];
            $race->sort_no = $data['sort_no'];
            $race->is_active = $data['is_active'];
            $success = $race->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Race: ' . $race->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    // nationality
    public function nationality() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.nationality_maintenance'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'nationality_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.nationality', $viewData);
    }

    public function addNationality() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.master.add_nationality'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'nationality_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('setting_en.add_nationality', $viewData);
    }

    public function submitNationality() {
        $data = Input::all();
        if (Request::ajax()) {
            $is_active = $data['is_active'];

            $nationality = new Nationality();
            $nationality->name = $data['name'];
            $nationality->sort_no = $data['sort_no'];
            $nationality->is_active = $is_active;
            $success = $nationality->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Nationality: ' . $nationality->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getNationality() {
        $nationality = Nationality::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($nationality) > 0) {
            $data = Array();
            foreach ($nationality as $nationalities) {
                $button = "";
                if ($nationalities->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveNationality(\'' . $nationalities->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeNationality(\'' . $nationalities->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateNationality', $nationalities->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteNationality(\'' . $nationalities->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $nationalities->name,
                    $nationalities->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveNationality() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $nationality = Nationality::find($id);
            $nationality->is_active = 0;
            $updated = $nationality->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Nationality: ' . $nationality->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeNationality() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $nationality = Nationality::find($id);
            $nationality->is_active = 1;
            $updated = $nationality->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Nationality: ' . $nationality->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteNationality() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $nationality = Nationality::find($id);
            $nationality->is_deleted = 1;
            $deleted = $nationality->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Nationality: ' . $nationality->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateNationality($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $nationality = Nationality::find($id);

        $viewData = array(
            'title' => trans('app.menus.master.edit_nationality'),
            'panel_nav_active' => 'master_panel',
            'main_nav_active' => 'master_main',
            'sub_nav_active' => 'nationality_list',
            'user_permission' => $user_permission,
            'nationality' => $nationality,
            'image' => ""
        );

        return View::make('setting_en.update_nationality', $viewData);
    }

    public function submitUpdateNationality() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $nationality = Nationality::find($id);
            $nationality->name = $data['name'];
            $nationality->sort_no = $data['sort_no'];
            $nationality->is_active = $data['is_active'];
            $success = $nationality->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Nationality: ' . $nationality->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    // defect category
    public function defectCategory() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $permission = false;
        foreach ($user_permission as $permissions) {
            if ($permissions->submodule_id == 47) {
                $permission = $permissions->access_permission;
            }
        }

        if ($permission) {
            $viewData = array(
                'title' => trans('app.menus.master.defect_category'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'defect_category_list',
                'user_permission' => $user_permission,
                'image' => ""
            );

            return View::make('setting_en.defect_category', $viewData);
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

    public function addDefectCategory() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $permission = false;
        foreach ($user_permission as $permissions) {
            if ($permissions->submodule_id == 47) {
                $permission = $permissions->insert_permission;
            }
        }

        if ($permission) {
            $viewData = array(
                'title' => trans('app.menus.master.add_defect_category'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'defect_category_list',
                'user_permission' => $user_permission,
                'image' => ""
            );

            return View::make('setting_en.add_defect_category', $viewData);
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

    public function submitDefectCategory() {
        $data = Input::all();
        if (Request::ajax()) {
            $is_active = $data['is_active'];

            $defectCategory = new DefectCategory();
            $defectCategory->name = $data['name'];
            $defectCategory->sort_no = $data['sort_no'];
            $defectCategory->is_active = $is_active;
            $success = $defectCategory->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $defectCategory->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getDefectCategory() {
        $defectCategory = DefectCategory::where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        if (count($defectCategory) > 0) {
            $data = Array();
            foreach ($defectCategory as $categories) {
                $button = "";
                if ($categories->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveDefectCategory(\'' . $categories->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeDefectCategory(\'' . $categories->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateDefectCategory', $categories->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDefectCategory(\'' . $categories->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $categories->name,
                    $categories->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveDefectCategory() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $defectCategory = DefectCategory::find($id);
            $defectCategory->is_active = 0;
            $updated = $defectCategory->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $defectCategory->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeDefectCategory() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $defectCategory = DefectCategory::find($id);
            $defectCategory->is_active = 1;
            $updated = $defectCategory->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $defectCategory->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteDefectCategory() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $defectCategory = DefectCategory::find($id);
            $defectCategory->is_deleted = 1;
            $deleted = $defectCategory->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $defectCategory->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateDefectCategory($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $defectCategory = DefectCategory::find($id);

        $permission = false;
        foreach ($user_permission as $permissions) {
            if ($permissions->submodule_id == 47) {
                $permission = $permissions->update_permission;
            }
        }

        if ($permission) {
            $viewData = array(
                'title' => trans('app.menus.master.edit_defect_category'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'defect_category_list',
                'user_permission' => $user_permission,
                'defectCategory' => $defectCategory,
                'image' => ""
            );

            return View::make('setting_en.update_defect_category', $viewData);
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

    public function submitUpdateDefectCategory() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $defectCategory = DefectCategory::find($id);
            $defectCategory->name = $data['name'];
            $defectCategory->sort_no = $data['sort_no'];
            $defectCategory->is_active = $data['is_active'];
            $success = $defectCategory->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $defectCategory->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    // insurance provider
    public function insuranceProvider() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $permission = false;
        foreach ($user_permission as $permissions) {
            if ($permissions->submodule_id == 48) {
                $permission = $permissions->access_permission;
            }
        }

        if ($permission) {
            $viewData = array(
                'title' => trans('app.menus.master.insurance_provider'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'insurance_provider_list',
                'user_permission' => $user_permission,
                'image' => ""
            );

            return View::make('setting_en.insurance_provider', $viewData);
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

    public function addInsuranceProvider() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $permission = false;
        foreach ($user_permission as $permissions) {
            if ($permissions->submodule_id == 48) {
                $permission = $permissions->insert_permission;
            }
        }

        if ($permission) {
            $viewData = array(
                'title' => trans('app.menus.master.add_insurance_provider'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'insurance_provider_list',
                'user_permission' => $user_permission,
                'image' => ""
            );

            return View::make('setting_en.add_insurance_provider', $viewData);
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

    public function submitInsuranceProvider() {
        $data = Input::all();
        if (Request::ajax()) {
            $is_active = $data['is_active'];

            $insuranceProvider = new InsuranceProvider();
            $insuranceProvider->name = $data['name'];
            $insuranceProvider->sort_no = $data['sort_no'];
            $insuranceProvider->is_active = $is_active;
            $success = $insuranceProvider->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $insuranceProvider->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getInsuranceProvider() {
        $insuranceProvider = InsuranceProvider::where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        if (count($insuranceProvider) > 0) {
            $data = Array();
            foreach ($insuranceProvider as $providers) {
                $button = "";
                if ($providers->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveInsuranceProvider(\'' . $providers->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeInsuranceProvider(\'' . $providers->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('SettingController@updateInsuranceProvider', $providers->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteInsuranceProvider(\'' . $providers->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $providers->name,
                    $providers->sort_no,
                    $status,
                    $button
                );

                array_push($data, $data_raw);
            }
            $output_raw = array(
                "aaData" => $data
            );

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

    public function inactiveInsuranceProvider() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $insuranceProvider = InsuranceProvider::find($id);
            $insuranceProvider->is_active = 0;
            $updated = $insuranceProvider->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $insuranceProvider->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeInsuranceProvider() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $insuranceProvider = InsuranceProvider::find($id);
            $insuranceProvider->is_active = 1;
            $updated = $insuranceProvider->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $insuranceProvider->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteInsuranceProvider() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $insuranceProvider = InsuranceProvider::find($id);
            $insuranceProvider->is_deleted = 1;
            $deleted = $insuranceProvider->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $insuranceProvider->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateInsuranceProvider($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $insuranceProvider = InsuranceProvider::find($id);

        $permission = false;
        foreach ($user_permission as $permissions) {
            if ($permissions->submodule_id == 48) {
                $permission = $permissions->update_permission;
            }
        }

        if ($permission) {
            $viewData = array(
                'title' => trans('app.menus.master.edit_insurance_provider'),
                'panel_nav_active' => 'master_panel',
                'main_nav_active' => 'master_main',
                'sub_nav_active' => 'insurance_provider_list',
                'user_permission' => $user_permission,
                'insuranceProvider' => $insuranceProvider,
                'image' => ""
            );

            return View::make('setting_en.update_insurance_provider', $viewData);
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

    public function submitUpdateInsuranceProvider() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $insuranceProvider = InsuranceProvider::find($id);
            $insuranceProvider->name = $data['name'];
            $insuranceProvider->sort_no = $data['sort_no'];
            $insuranceProvider->is_active = $data['is_active'];
            $success = $insuranceProvider->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Defect Category: ' . $insuranceProvider->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

}
