<?php

class AgmController extends BaseController {

    public function getDesignationRemainder() {
        $currentMonth = strtotime(date('Y-m-d'));

        $ajk_detail = AJKDetails::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($ajk_detail) > 0) {
            $data = array();

            foreach ($ajk_detail as $ajk_details) {
                if (!empty($ajk_details->year) && !empty($ajk_details->month)) {
                    $raw_month = $ajk_details->year . '-' . $ajk_details->month . '-' . '01';

                    $designation_time = strtotime($raw_month);
                    $designation_final = strtotime('+1 month', $designation_time);

                    if ($designation_final <= $currentMonth) {
                        if (!Auth::user()->getAdmin()) {
                            if (!empty(Auth::user()->file_id)) {
                                if ($ajk_details->files->id == Auth::user()->file_id) {
                                    $designation = Designation::find($ajk_details->designation);

                                    $button = "";
                                    $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', $ajk_details->id) . '\'">';
                                    $button .= '<i class="fa fa-pencil"></i>';
                                    $button .= '</button>&nbsp;';
                                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . $ajk_details->id . '\')">';
                                    $button .= '<i class="fa fa-trash"></i>';
                                    $button .= '</button>&nbsp';

                                    $data_raw = array(
                                        $ajk_details->files->company->short_name,
                                        $ajk_details->files->file_no,
                                        $designation->description,
                                        $ajk_details->name,
                                        $ajk_details->phone_no,
                                        $ajk_details->monthName(),
                                        $ajk_details->year,
//                                    $button
                                    );

                                    array_push($data, $data_raw);
                                }
                            } else {
                                if ($ajk_details->files->company->id == Auth::user()->company_id) {
                                    $designation = Designation::find($ajk_details->designation);

                                    $button = "";
                                    $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', $ajk_details->id) . '\'">';
                                    $button .= '<i class="fa fa-pencil"></i>';
                                    $button .= '</button>&nbsp;';
                                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . $ajk_details->id . '\')">';
                                    $button .= '<i class="fa fa-trash"></i>';
                                    $button .= '</button>&nbsp';

                                    $data_raw = array(
                                        $ajk_details->files->company->short_name,
                                        $ajk_details->files->file_no,
                                        $designation->description,
                                        $ajk_details->name,
                                        $ajk_details->phone_no,
                                        $ajk_details->monthName(),
                                        $ajk_details->year,
//                                    $button
                                    );

                                    array_push($data, $data_raw);
                                }
                            }
                        } else {
                            if (empty(Session::get('admin_cob'))) {

                                $designation = Designation::find($ajk_details->designation);

                                $button = "";
                                $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', $ajk_details->id) . '\'">';
                                $button .= '<i class="fa fa-pencil"></i>';
                                $button .= '</button>&nbsp;';
                                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . $ajk_details->id . '\')">';
                                $button .= '<i class="fa fa-trash"></i>';
                                $button .= '</button>&nbsp';

                                $data_raw = array(
                                    $ajk_details->files->company->short_name,
                                    $ajk_details->files->file_no,
                                    $designation->description,
                                    $ajk_details->name,
                                    $ajk_details->phone_no,
                                    $ajk_details->monthName(),
                                    $ajk_details->year,
//                                    $button
                                );

                                array_push($data, $data_raw);
                            } else {
                                if ($ajk_details->files->company->id == Session::get('admin_cob')) {
                                    $designation = Designation::find($ajk_details->designation);

                                    $button = "";
                                    $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', $ajk_details->id) . '\'">';
                                    $button .= '<i class="fa fa-pencil"></i>';
                                    $button .= '</button>&nbsp;';
                                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . $ajk_details->id . '\')">';
                                    $button .= '<i class="fa fa-trash"></i>';
                                    $button .= '</button>&nbsp';

                                    $data_raw = array(
                                        $ajk_details->files->company->short_name,
                                        $ajk_details->files->file_no,
                                        $designation->description,
                                        $ajk_details->name,
                                        $ajk_details->phone_no,
                                        $ajk_details->monthName(),
                                        $ajk_details->year,
//                                        $button
                                    );

                                    array_push($data, $data_raw);
                                }
                            }
                        }
                    }
                }
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

    public function AJK() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();

            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('status', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('status', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('is_deleted', 0)->orderBy('status', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('status', 'asc')->get();
            }
        }

        $month = [
            1 => 'JAN',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'APR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AUG',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DEC'
        ];

        $viewData = array(
            'title' => trans('app.menus.agm.designation'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'cob' => $cob,
            'month' => $month,
            'image' => ''
        );

        return View::make('agm_en.ajk', $viewData);
    }

    public function getAJK() {
        if (!empty(Auth::user()->file_id)) {
            $ajk_detail = AJKDetails::where('file_id', Auth::user()->file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        } else {
            $ajk_detail = AJKDetails::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        }

        if (count($ajk_detail) > 0) {
            $data = Array();
            foreach ($ajk_detail as $ajk_details) {
                $designation = Designation::find($ajk_details->designation);

                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', $ajk_details->id) . '\'">
                                <i class="fa fa-pencil"></i>
                            </button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . $ajk_details->id . '\')">
                                <i class="fa fa-trash"></i>
                            </button>&nbsp';

                $data_raw = array(
                    $ajk_details->files->company->short_name,
                    $ajk_details->files->file_no,
                    $designation->description,
                    $ajk_details->name,
                    $ajk_details->phone_no,
                    $ajk_details->monthName(),
                    $ajk_details->year,
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

    public function addAJK() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        $month = [
            1 => 'JAN',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'APR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AUG',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DEC'
        ];

        $viewData = array(
            'title' => trans('app.menus.agm.add_designation'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'designation' => $designation,
            'month' => $month,
            'image' => ''
        );

        return View::make('agm_en.add_ajk', $viewData);
    }

    public function submitAddAJK() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $designation = $data['designation'];
            $name = $data['name'];
            $phone_no = $data['phone_no'];
            $month = $data['month'];
            $year = $data['year'];
            $remarks = $data['remarks'];

            $ajk_detail = new AJKDetails();
            $ajk_detail->file_id = $file_id;
            $ajk_detail->designation = $designation;
            $ajk_detail->name = $name;
            $ajk_detail->phone_no = $phone_no;
            $ajk_detail->month = $month;
            $ajk_detail->year = $year;
            $ajk_detail->remarks = $remarks;
            $success = $ajk_detail->save();

            if ($success) {
                # Audit Trail
                $file_name = Files::find($ajk_detail->file_id);
                $remarks = 'AJK Details (' . $file_name->file_no . ') ' . $ajk_detail->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function editAJK($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        $month = [
            1 => 'JAN',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'APR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AUG',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DEC'
        ];

        $ajk_details = AJKDetails::find($id);

        $viewData = array(
            'title' => trans('app.menus.agm.edit_designation'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'designation' => $designation,
            'month' => $month,
            'ajk_details' => $ajk_details,
            'image' => ''
        );

        return View::make('agm_en.edit_ajk', $viewData);
    }

    public function submitEditAJK() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];
            $file_id = $data['file_id'];
            $designation = $data['designation'];
            $name = $data['name'];
            $phone_no = $data['phone_no'];
            $month = $data['month'];
            $year = $data['year'];
            $remarks = $data['remarks'];

            $ajk_detail = AJKDetails::find($id);
            if ($ajk_detail) {
                $ajk_detail->file_id = $file_id;
                $ajk_detail->designation = $designation;
                $ajk_detail->name = $name;
                $ajk_detail->phone_no = $phone_no;
                $ajk_detail->month = $month;
                $ajk_detail->year = $year;
                $ajk_detail->remarks = $remarks;
                $success = $ajk_detail->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($ajk_detail->file_id);
                    $remarks = 'AJK Details (' . $file_name->file_no . ') ' . $ajk_detail->name . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function deleteAJK() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $ajk_details = AJKDetails::find($id);
            $ajk_details->is_deleted = 1;
            $deleted = $ajk_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($ajk_details->file_id);
                $remarks = 'AJK Details (' . $file_name->file_no . ') ' . $ajk_details->name . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    /*
     * Purchaser
     */

    public function purchaser() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();

            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        $viewData = array(
            'title' => trans('app.menus.agm.purchaser'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'cob' => $cob,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => ''
        );

        return View::make('agm_en.purchaser', $viewData);
    }

    public function getPurchaser() {
        $data = array();
        $requestData = Request::input();

        $columns = array(
            0 => 'company.short_name',
            1 => 'files.file_no',
            2 => 'buyer.unit_no',
            3 => 'buyer.unit_share',
            4 => 'buyer.owner_name',
            5 => 'buyer.phone_no',
            6 => 'buyer.email',
            7 => 'race.name_en',
            8 => 'action'
        );

        $limit = $requestData['length'];
        $start = $requestData['start'];
        $order = $columns[$requestData['order'][0]['column']];
        $dir = $requestData['order'][0]['dir'];
        $search = $requestData['search']['value'];
        $company_id = $requestData['columns'][0]['search']['value'];
        $file_id = $requestData['columns'][1]['search']['value'];

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $totalData = DB::table('buyer')
                        ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('buyer.is_deleted', 0)
                        ->count();
            } else {
                $totalData = DB::table('buyer')
                        ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('buyer.is_deleted', 0)
                        ->count();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $totalData = DB::table('buyer')
                        ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                        ->where('buyer.is_deleted', 0)
                        ->count();
            } else {
                $totalData = DB::table('buyer')
                        ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('buyer.is_deleted', 0)
                        ->count();
            }
        }

        if ($limit == -1) {
            if ($totalData != 0) {
                $limit = $totalData;
            } else {
                $limit = 1;
            }
        } else {
            $limit = $limit;
        }

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                if (empty($search)) {
                    $posts = DB::table('buyer')
                            ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                            ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('buyer.is_deleted', 0)
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('buyer')
                            ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('buyer.is_deleted', 0)
                            ->count();
                } else {
                    $posts = DB::table('buyer')
                            ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                            ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('buyer.is_deleted', 0)
                            ->where(function($query) use ($search) {
                                $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('buyer')
                            ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('buyer.is_deleted', 0)
                            ->where(function($query) use ($search) {
                                $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                            })
                            ->count();
                }
            } else {
                if (empty($search)) {
                    if (!empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    } else {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    }
                } else {
                    if (!empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                if (empty($search)) {
                    if (!empty($company_id) && !empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    } else if (!empty($company_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    } else if (!empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    } else {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    }
                } else {
                    if (!empty($company_id) && !empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else if (!empty($company_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else if (!empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            } else {
                if (empty($search)) {
                    if (!empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    } else {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('buyer.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('buyer.is_deleted', 0)
                                ->count();
                    }
                } else {
                    if (!empty($file_id)) {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->select('buyer.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('buyer')
                                ->leftJoin('files', 'buyer.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'buyer.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('buyer.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.unit_share', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.owner_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('buyer.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            }
        }

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AgmController@editPurchaser', $post->id) . '\'">
                                <i class="fa fa-pencil"></i>
                            </button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deletePurchaser(\'' . $post->id . '\')">
                                <i class="fa fa-trash"></i>
                            </button>&nbsp';

                $nestedData['cob'] = $post->short_name;
                $nestedData['file_no'] = $post->file_no;
                $nestedData['unit_no'] = $post->unit_no;
                $nestedData['unit_share'] = $post->unit_share;
                $nestedData['owner_name'] = $post->owner_name;
                $nestedData['phone_no'] = $post->phone_no;
                $nestedData['email'] = $post->email;
                $nestedData['race'] = $post->race_name;
                $nestedData['action'] = $button;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval(Request::input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function addPurchaser() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        }

        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.agm.add_purchaser'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'race' => $race,
            'nationality' => $nationality,
            'image' => ''
        );

        return View::make('agm_en.add_purchaser', $viewData);
    }

    public function submitPurchaser() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $unit_no = $data['unit_no'];
            $unit_share = $data['unit_share'];
            $owner_name = $data['owner_name'];
            $ic_company_no = $data['ic_company_no'];
            $address = $data['address'];
            $phone_no = $data['phone_no'];
            $email = $data['email'];
            $race = $data['race'];
            $nationality = $data['nationality'];
            $remark = $data['remarks'];
            $no_petak = $data['no_petak'];
            $no_petak_aksesori = $data['no_petak_aksesori'];
            $keluasan_lantai_petak = $data['keluasan_lantai_petak'];
            $keluasan_lantai_petak_aksesori = $data['keluasan_lantai_petak_aksesori'];
            $jenis_kegunaan = $data['jenis_kegunaan'];
            $nama2 = $data['nama2'];
            $ic_no2 = $data['ic_no2'];
            $alamat_surat_menyurat = $data['alamat_surat_menyurat'];
            $caj_penyelenggaraan = $data['caj_penyelenggaraan'];
            $sinking_fund = $data['sinking_fund'];

            $checkFile = Files::find($file_id);

            if (count($checkFile) > 0) {
                $buyer = new Buyer();
                $buyer->file_id = $file_id;
                $buyer->unit_no = $unit_no;
                $buyer->unit_share = $unit_share;
                $buyer->owner_name = $owner_name;
                $buyer->ic_company_no = $ic_company_no;
                $buyer->address = $address;
                $buyer->phone_no = $phone_no;
                $buyer->email = $email;
                $buyer->race_id = $race;
                $buyer->nationality_id = $nationality;
                $buyer->remarks = $remark;
                $buyer->no_petak = $no_petak;
                $buyer->no_petak_aksesori = $no_petak_aksesori;
                $buyer->keluasan_lantai_petak = $keluasan_lantai_petak;
                $buyer->keluasan_lantai_petak_aksesori = $keluasan_lantai_petak_aksesori;
                $buyer->jenis_kegunaan = $jenis_kegunaan;
                $buyer->nama2 = $nama2;
                $buyer->ic_no2 = $ic_no2;
                $buyer->alamat_surat_menyurat = $alamat_surat_menyurat;
                $buyer->caj_penyelenggaraan = $caj_penyelenggaraan;
                $buyer->sinking_fund = $sinking_fund;
                $success = $buyer->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($buyer->file_id);
                    $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit' . $buyer->unit_no . ' has been inserted.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function editPurchaser($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $buyer = Buyer::find($id);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        }

        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.agm.edit_purchaser'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'buyer' => $buyer,
            'race' => $race,
            'nationality' => $nationality,
            'image' => ''
        );

        return View::make('agm_en.edit_purchaser', $viewData);
    }

    public function submitEditPurchaser() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $unit_no = $data['unit_no'];
            $unit_share = $data['unit_share'];
            $owner_name = $data['owner_name'];
            $ic_company_no = $data['ic_company_no'];
            $address = $data['address'];
            $phone_no = $data['phone_no'];
            $email = $data['email'];
            $race = $data['race'];
            $nationality = $data['nationality'];
            $remark = $data['remarks'];
            $no_petak = $data['no_petak'];
            $no_petak_aksesori = $data['no_petak_aksesori'];
            $keluasan_lantai_petak = $data['keluasan_lantai_petak'];
            $keluasan_lantai_petak_aksesori = $data['keluasan_lantai_petak_aksesori'];
            $jenis_kegunaan = $data['jenis_kegunaan'];
            $nama2 = $data['nama2'];
            $ic_no2 = $data['ic_no2'];
            $alamat_surat_menyurat = $data['alamat_surat_menyurat'];
            $caj_penyelenggaraan = $data['caj_penyelenggaraan'];
            $sinking_fund = $data['sinking_fund'];
            $id = $data['id'];

            $checkFile = Files::find($file_id);

            if (count($checkFile) > 0) {
                $buyer = Buyer::find($id);
                if (count($buyer) > 0) {
                    $buyer->file_id = $file_id;
                    $buyer->unit_no = $unit_no;
                    $buyer->unit_share = $unit_share;
                    $buyer->owner_name = $owner_name;
                    $buyer->ic_company_no = $ic_company_no;
                    $buyer->address = $address;
                    $buyer->phone_no = $phone_no;
                    $buyer->email = $email;
                    $buyer->race_id = $race;
                    $buyer->nationality_id = $nationality;
                    $buyer->remarks = $remark;
                    $buyer->no_petak = $no_petak;
                    $buyer->no_petak_aksesori = $no_petak_aksesori;
                    $buyer->keluasan_lantai_petak = $keluasan_lantai_petak;
                    $buyer->keluasan_lantai_petak_aksesori = $keluasan_lantai_petak_aksesori;
                    $buyer->jenis_kegunaan = $jenis_kegunaan;
                    $buyer->nama2 = $nama2;
                    $buyer->ic_no2 = $ic_no2;
                    $buyer->alamat_surat_menyurat = $alamat_surat_menyurat;
                    $buyer->caj_penyelenggaraan = $caj_penyelenggaraan;
                    $buyer->sinking_fund = $sinking_fund;
                    $success = $buyer->save();

                    if ($success) {
                        # Audit Trail
                        $file_name = Files::find($buyer->file_id);
                        $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit ' . $buyer->unit_no . ' has been updated.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB File";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = Auth::user()->id;
                        $auditTrail->save();

                        print "true";
                    } else {
                        print "false";
                    }
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function deletePurchaser() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $buyer = Buyer::find($id);
            $buyer->is_deleted = 1;
            $deleted = $buyer->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($buyer->file_id);
                $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit ' . $buyer->unit_no . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function importPurchaser() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.agm.import_purchaser'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'user_permission' => $user_permission,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => ''
        );

        return View::make('agm_en.import_purchaser', $viewData);
    }

    public function submitUploadPurchaser() {
        $data = Input::all();
        if (Request::ajax()) {

            $getAllBuyer = $data['getAllBuyer'];

            foreach ($getAllBuyer as $buyerList) {

                $check_file_id = Files::where('file_no', $buyerList[0])->first();
                if ($check_file_id) {
                    $files_id = $check_file_id->id;

                    $check_buyer = Buyer::where('file_id', $files_id)->where('unit_no', $buyerList[1])->where('is_deleted', 0)->first();
                    if ($check_buyer) {
                        $race = '';
                        if (isset($buyerList[8]) && !empty($buyerList[8])) {
                            $race_raw = trim($buyerList[8]);

                            if (!empty($race_raw)) {
                                $race_query = Race::where('name', $race_raw)->where('is_deleted', 0)->first();
                                if ($race_query) {
                                    $race = $race_query->id;
                                } else {
                                    $race_query = new Race();
                                    $race_query->name = $race_raw;
                                    $race_query->is_active = 1;
                                    $race_query->save();

                                    $race = $race_query->id;
                                }
                            }
                        }

                        $nationality = '';
                        if (isset($buyerList[9]) && !empty($buyerList[9])) {
                            $nationality_raw = trim($buyerList[9]);

                            if (!empty($nationality_raw)) {
                                $nationality_query = Nationality::where('name', $nationality_raw)->where('is_deleted', 0)->first();
                                if ($nationality_query) {
                                    $nationality = $nationality_query->id;
                                } else {
                                    $nationality_query = new Nationality();
                                    $nationality_query->name = $nationality_raw;
                                    $nationality_query->is_active = 1;
                                    $nationality_query->save();

                                    $nationality = $nationality_query->id;
                                }
                            }
                        }

                        $buyer = new Buyer();
                        $buyer->file_id = $files_id;
                        $buyer->unit_no = $buyerList[1];
                        $buyer->unit_share = $buyerList[2];
                        $buyer->owner_name = $buyerList[3];
                        $buyer->ic_company_no = $buyerList[4];
                        $buyer->address = $buyerList[5];
                        $buyer->phone_no = $buyerList[6];
                        $buyer->email = $buyerList[7];
                        $buyer->race_id = $race;
                        $buyer->nationality_id = $nationality;
                        $buyer->remarks = $buyerList[10];
                        $success = $buyer->save();

                        if ($success) {
                            # Audit Trail
                            $file_name = Files::find($buyer->file_id);
                            $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit ' . $buyer->unit_no . ' has been inserted.';
                            $auditTrail = new AuditTrail();
                            $auditTrail->module = "COB File";
                            $auditTrail->remarks = $remarks;
                            $auditTrail->audit_by = Auth::user()->id;
                            $auditTrail->save();
                        }
                    }
                }
            }

            # Audit Trail
            $file_name = Files::find($buyer->file_id);
            $remarks = 'COB Owner List (' . $file_name->file_no . ') has been imported.';
            $auditTrail = new AuditTrail();
            $auditTrail->module = "COB File";
            $auditTrail->remarks = $remarks;
            $auditTrail->audit_by = Auth::user()->id;
            $auditTrail->save();

            print "true";
        } else {
            print "false";
        }
    }

    /*
     * Tenant
     */

    public function tenant() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();

            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            }
        }

        $viewData = array(
            'title' => trans('app.menus.agm.tenant'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmtenantsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'cob' => $cob,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => ''
        );

        return View::make('agm_en.tenant', $viewData);
    }

    public function getTenant() {
        $data = array();
        $requestData = Request::input();

        $columns = array(
            0 => 'company.short_name',
            1 => 'files.file_no',
            2 => 'tenant.unit_no',
            3 => 'tenant.tenant_name',
            4 => 'tenant.phone_no',
            5 => 'tenant.email',
            6 => 'race.name_en',
            7 => 'action'
        );

        $limit = $requestData['length'];
        $start = $requestData['start'];
        $order = $columns[$requestData['order'][0]['column']];
        $dir = $requestData['order'][0]['dir'];
        $search = $requestData['search']['value'];
        $company_id = $requestData['columns'][0]['search']['value'];
        $file_id = $requestData['columns'][1]['search']['value'];

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $totalData = DB::table('tenant')
                        ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('tenant.is_deleted', 0)
                        ->count();
            } else {
                $totalData = DB::table('tenant')
                        ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('tenant.is_deleted', 0)
                        ->count();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $totalData = DB::table('tenant')
                        ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                        ->where('tenant.is_deleted', 0)
                        ->count();
            } else {
                $totalData = DB::table('tenant')
                        ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                        ->leftJoin('company', 'files.company_id', '=', 'company.id')
                        ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('tenant.is_deleted', 0)
                        ->count();
            }
        }

        if ($limit == -1) {
            if ($totalData != 0) {
                $limit = $totalData;
            } else {
                $limit = 1;
            }
        } else {
            $limit = $limit;
        }

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                if (empty($search)) {
                    $posts = DB::table('tenant')
                            ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                            ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('tenant.is_deleted', 0)
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('tenant')
                            ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('tenant.is_deleted', 0)
                            ->count();
                } else {
                    $posts = DB::table('tenant')
                            ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                            ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('tenant.is_deleted', 0)
                            ->where(function($query) use ($search) {
                                $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('tenant')
                            ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                            ->leftJoin('company', 'files.company_id', '=', 'company.id')
                            ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                            ->where('files.id', Auth::user()->file_id)
                            ->where('tenant.is_deleted', 0)
                            ->where(function($query) use ($search) {
                                $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                            })
                            ->count();
                }
            } else {
                if (empty($search)) {
                    if (!empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    } else {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    }
                } else {
                    if (!empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Auth::user()->company_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                if (empty($search)) {
                    if (!empty($company_id) && !empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    } else if (!empty($company_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    } else if (!empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    } else {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    }
                } else {
                    if (!empty($company_id) && !empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else if (!empty($company_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', $company_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', $company_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else if (!empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            } else {
                if (empty($search)) {
                    if (!empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    } else {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('tenant.is_deleted', 0)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('tenant.is_deleted', 0)
                                ->count();
                    }
                } else {
                    if (!empty($file_id)) {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('files.id', $file_id)
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->select('tenant.*', 'files.file_no as file_no', 'company.short_name as short_name', 'race.name_en as race_name')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('tenant')
                                ->leftJoin('files', 'tenant.file_id', '=', 'files.id')
                                ->leftJoin('company', 'files.company_id', '=', 'company.id')
                                ->leftJoin('race', 'tenant.race_id', '=', 'race.id')
                                ->where('files.company_id', Session::get('admin_cob'))
                                ->where('tenant.is_deleted', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('company.short_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('files.file_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.unit_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.tenant_name', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.phone_no', 'LIKE', "%" . $search . "%")
                                    ->orWhere('tenant.email', 'LIKE', "%" . $search . "%")
                                    ->orWhere('race.name_en', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            }
        }

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AgmController@editTenant', $post->id) . '\'">
                                <i class="fa fa-pencil"></i>
                            </button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteTenant(\'' . $post->id . '\')">
                                <i class="fa fa-trash"></i>
                            </button>&nbsp';

                $nestedData['cob'] = $post->short_name;
                $nestedData['file_no'] = $post->file_no;
                $nestedData['unit_no'] = $post->unit_no;
                $nestedData['tenant_name'] = $post->tenant_name;
                $nestedData['phone_no'] = $post->phone_no;
                $nestedData['email'] = $post->email;
                $nestedData['race'] = $post->race_name;
                $nestedData['action'] = $button;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval(Request::input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function addTenant() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        }

        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.agm.add_tenant'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmtenantsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'race' => $race,
            'nationality' => $nationality,
            'image' => ''
        );

        return View::make('agm_en.add_tenant', $viewData);
    }

    public function submitTenant() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $unit_no = $data['unit_no'];
            $tenant_name = $data['tenant_name'];
            $ic_company_no = $data['ic_company_no'];
            $address = $data['address'];
            $phone_no = $data['phone_no'];
            $email = $data['email'];
            $race = $data['race'];
            $nationality = $data['nationality'];
            $remark = $data['remarks'];
            $no_petak = $data['no_petak'];
            $no_petak_aksesori = $data['no_petak_aksesori'];
            $keluasan_lantai_petak = $data['keluasan_lantai_petak'];
            $keluasan_lantai_petak_aksesori = $data['keluasan_lantai_petak_aksesori'];
            $jenis_kegunaan = $data['jenis_kegunaan'];
            $nama2 = $data['nama2'];
            $ic_no2 = $data['ic_no2'];
            $alamat_surat_menyurat = $data['alamat_surat_menyurat'];
            $caj_penyelenggaraan = $data['caj_penyelenggaraan'];
            $sinking_fund = $data['sinking_fund'];

            $checkFile = Files::find($file_id);

            if (count($checkFile) > 0) {
                $tenant = new Tenant();
                $tenant->file_id = $file_id;
                $tenant->unit_no = $unit_no;
                $tenant->tenant_name = $tenant_name;
                $tenant->ic_company_no = $ic_company_no;
                $tenant->address = $address;
                $tenant->phone_no = $phone_no;
                $tenant->email = $email;
                $tenant->race_id = $race;
                $tenant->nationality_id = $nationality;
                $tenant->remarks = $remark;
                $tenant->no_petak = $no_petak;
                $tenant->no_petak_aksesori = $no_petak_aksesori;
                $tenant->keluasan_lantai_petak = $keluasan_lantai_petak;
                $tenant->keluasan_lantai_petak_aksesori = $keluasan_lantai_petak_aksesori;
                $tenant->jenis_kegunaan = $jenis_kegunaan;
                $tenant->nama2 = $nama2;
                $tenant->ic_no2 = $ic_no2;
                $tenant->alamat_surat_menyurat = $alamat_surat_menyurat;
                $tenant->caj_penyelenggaraan = $caj_penyelenggaraan;
                $tenant->sinking_fund = $sinking_fund;
                $success = $tenant->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($tenant->file_id);
                    $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit' . $tenant->unit_no . ' has been inserted.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function editTenant($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $tenant = Tenant::find($id);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        }

        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.agm.add_tenant'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmtenantsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'tenant' => $tenant,
            'race' => $race,
            'nationality' => $nationality,
            'image' => ''
        );

        return View::make('agm_en.edit_tenant', $viewData);
    }

    public function submitEditTenant() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $unit_no = $data['unit_no'];
            $tenant_name = $data['tenant_name'];
            $ic_company_no = $data['ic_company_no'];
            $address = $data['address'];
            $phone_no = $data['phone_no'];
            $email = $data['email'];
            $race = $data['race'];
            $nationality = $data['nationality'];
            $remark = $data['remarks'];
            $no_petak = $data['no_petak'];
            $no_petak_aksesori = $data['no_petak_aksesori'];
            $keluasan_lantai_petak = $data['keluasan_lantai_petak'];
            $keluasan_lantai_petak_aksesori = $data['keluasan_lantai_petak_aksesori'];
            $jenis_kegunaan = $data['jenis_kegunaan'];
            $nama2 = $data['nama2'];
            $ic_no2 = $data['ic_no2'];
            $alamat_surat_menyurat = $data['alamat_surat_menyurat'];
            $caj_penyelenggaraan = $data['caj_penyelenggaraan'];
            $sinking_fund = $data['sinking_fund'];
            $id = $data['id'];

            $checkFile = Files::find($file_id);

            if (count($checkFile) > 0) {
                $tenant = Tenant::find($id);
                if (count($tenant) > 0) {
                    $tenant->file_id = $file_id;
                    $tenant->unit_no = $unit_no;
                    $tenant->tenant_name = $tenant_name;
                    $tenant->ic_company_no = $ic_company_no;
                    $tenant->address = $address;
                    $tenant->phone_no = $phone_no;
                    $tenant->email = $email;
                    $tenant->race_id = $race;
                    $tenant->nationality_id = $nationality;
                    $tenant->remarks = $remark;
                    $tenant->no_petak = $no_petak;
                    $tenant->no_petak_aksesori = $no_petak_aksesori;
                    $tenant->keluasan_lantai_petak = $keluasan_lantai_petak;
                    $tenant->keluasan_lantai_petak_aksesori = $keluasan_lantai_petak_aksesori;
                    $tenant->jenis_kegunaan = $jenis_kegunaan;
                    $tenant->nama2 = $nama2;
                    $tenant->ic_no2 = $ic_no2;
                    $tenant->alamat_surat_menyurat = $alamat_surat_menyurat;
                    $tenant->caj_penyelenggaraan = $caj_penyelenggaraan;
                    $tenant->sinking_fund = $sinking_fund;
                    $success = $tenant->save();

                    if ($success) {
                        # Audit Trail
                        $file_name = Files::find($tenant->file_id);
                        $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit ' . $tenant->unit_no . ' has been updated.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB File";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = Auth::user()->id;
                        $auditTrail->save();

                        print "true";
                    } else {
                        print "false";
                    }
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function deleteTenant() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $tenant = Tenant::find($id);
            $tenant->is_deleted = 1;
            $deleted = $tenant->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($tenant->file_id);
                $remarks = 'COB Owner List (' . $file_name->file_no . ') for Unit ' . $tenant->unit_no . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function importTenant() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.agm.import_tenant'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmtenantsub_list',
            'user_permission' => $user_permission,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => ''
        );

        return View::make('agm_en.import_tenant', $viewData);
    }

    public function submitUploadTenant() {
        $data = Input::all();
        if (Request::ajax()) {

            $getAllTenant = $data['getAllTenant'];

            foreach ($getAllTenant as $tenantList) {

                $check_file_id = Files::where('file_no', $tenantList[0])->first();
                if (count($check_file_id) > 0) {
                    $files_id = $check_file_id->id;

                    $check_tenant = Tenant::where('file_id', $files_id)->where('unit_no', $tenantList[1])->where('is_deleted', 0)->first();
                    if (count($check_tenant) <= 0) {
                        $race = '';
                        if (isset($tenantList[7]) && !empty($tenantList[7])) {
                            $race_raw = trim($tenantList[7]);

                            if (!empty($race_raw)) {
                                $race_query = Race::where('name', $race_raw)->where('is_deleted', 0)->first();
                                if ($race_query) {
                                    $race = $race_query->id;
                                } else {
                                    $race_query = new Race();
                                    $race_query->name = $race_raw;
                                    $race_query->is_active = 1;
                                    $race_query->save();

                                    $race = $race_query->id;
                                }
                            }
                        }

                        $nationality = '';
                        if (isset($tenantList[8]) && !empty($tenantList[8])) {
                            $nationality_raw = trim($tenantList[8]);

                            if (!empty($nationality_raw)) {
                                $nationality_query = Nationality::where('name', $nationality_raw)->where('is_deleted', 0)->first();
                                if ($nationality_query) {
                                    $nationality = $nationality_query->id;
                                } else {
                                    $nationality_query = new Nationality();
                                    $nationality_query->name = $nationality_raw;
                                    $nationality_query->is_active = 1;
                                    $nationality_query->save();

                                    $nationality = $nationality_query->id;
                                }
                            }
                        }

                        $tenant = new Tenant();
                        $tenant->file_id = $files_id;
                        $tenant->unit_no = $tenantList[1];
                        $tenant->tenant_name = $tenantList[2];
                        $tenant->ic_company_no = $tenantList[3];
                        $tenant->address = $tenantList[4];
                        $tenant->phone_no = $tenantList[5];
                        $tenant->email = $tenantList[6];
                        $tenant->race_id = $race;
                        $tenant->nationality_id = $nationality;
                        $tenant->remarks = $tenantList[9];
                        $success = $tenant->save();

                        if ($success) {
                            # Audit Trail
                            $file_name = Files::find($tenant->file_id);
                            $remarks = 'COB Tenant List (' . $file_name->file_no . ') for Unit ' . $tenant->unit_no . ' has been inserted.';
                            $auditTrail = new AuditTrail();
                            $auditTrail->module = "COB File";
                            $auditTrail->remarks = $remarks;
                            $auditTrail->audit_by = Auth::user()->id;
                            $auditTrail->save();
                        }
                    }
                }
            }

            # Audit Trail
            $file_name = Files::find($tenant->file_id);
            $remarks = 'COB Tenant List (' . $file_name->file_no . ') has been imported.';
            $auditTrail = new AuditTrail();
            $auditTrail->module = "COB File";
            $auditTrail->remarks = $remarks;
            $auditTrail->audit_by = Auth::user()->id;
            $auditTrail->save();

            print "true";
        } else {
            print "false";
        }
    }

    public function viewBuyer($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find($id);
        $image = OtherDetails::where('file_id', $files->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'cob_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_buyer', $viewData);
    }

    /*
     * Upload Minutes
     */

    public function minutes() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        }

        $viewData = array(
            'title' => trans('app.menus.agm.upload_of_minutes'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmminutesub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'image' => ""
        );

        return View::make('agm_en.minutes', $viewData);
    }

    public function getMinutes() {
        if (!empty(Auth::user()->file_id)) {
            $agm_detail = MeetingDocument::where('file_id', Auth::user()->file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        } else {
            $agm_detail = MeetingDocument::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        }

        if (count($agm_detail) > 0) {
            $data = Array();
            foreach ($agm_detail as $agm_details) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit" onclick="window.location=\'' . URL::action('AgmController@editMinutes', $agm_details->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAGMDetails(\'' . $agm_details->id . '\')"><i class="fa fa-trash""></i></button>';

                if ($agm_details->agm_date == "0000-00-00") {
                    $date_agm = '';
                } else {
                    $date_agm = date('d-M-Y', strtotime($agm_details->agm_date));
                }
                if ($agm_details->audit_start_date == "0000-00-00") {
                    $date_audit_start = '';
                } else {
                    $date_audit_start = date('d-M-Y', strtotime($agm_details->audit_start_date));
                }
                if ($agm_details->audit_end_date == "0000-00-00") {
                    $date_audit_end = '';
                } else {
                    $date_audit_end = date('d-M-Y', strtotime($agm_details->audit_end_date));
                }
                if ($agm_details->agm == 0 || $agm_details->agm == "") {
                    $status1 = '<i class="icmn-cross"></i>';
                } else {
                    $status1 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->egm == 0 || $agm_details->egm == "") {
                    $status2 = '<i class="icmn-cross"></i>';
                } else {
                    $status2 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->minit_meeting == 0 || $agm_details->minit_meeting == "") {
                    $status3 = '<i class="icmn-cross"></i>';
                } else {
                    $status3 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->letter_integrity_url == "") {
                    $status4 = '<i class="icmn-cross"></i>';
                } else {
                    $status4 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->letter_bankruptcy_url == "") {
                    $status5 = '<i class="icmn-cross"></i>';
                } else {
                    $status5 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->jmc_spa == 0 || $agm_details->jmc_spa == "") {
                    $status6 = '<i class="icmn-cross"></i>';
                } else {
                    $status6 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->identity_card == 0 || $agm_details->identity_card == "") {
                    $status7 = '<i class="icmn-cross"></i>';
                } else {
                    $status7 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->attendance == 0 || $agm_details->attendance == "") {
                    $status8 = '<i class="icmn-cross"></i>';
                } else {
                    $status8 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->financial_report == 0 || $agm_details->financial_report == "") {
                    $status9 = '<i class="icmn-cross"></i>';
                } else {
                    $status9 = '<i class="icmn-checkmark"></i>';
                }
                if ($agm_details->audit_report_url == "") {
                    $status10 = '<i class="icmn-cross"></i>';
                } else {
                    $status10 = '<i class="icmn-checkmark"></i>';
                }

                $data_raw = array(
                    $date_agm,
                    trans('app.forms.annual_general_meeting') . '<br/>'
                    . trans('app.forms.extra_general_meeting') . '<br/>'
                    . trans('app.forms.meeting_minutes') . '<br/>'
                    . trans('app.forms.pledge_letter_of_integrity') . '<br>'
                    . trans('app.forms.declaration_letter_of_non_bankruptcy'),
                    $status1 . '<br/>' . $status2 . '<br/>' . $status3 . '<br/>' . $status4 . '<br/>' . $status5,
                    trans('app.forms.jmc_spa_copy') . '<br/>'
                    . trans('app.forms.identity_card_list') . '<br/>'
                    . trans('app.forms.attendance_list'),
                    $status6 . '<br/>' . $status7 . '<br/>' . $status8,
                    trans('app.forms.audited_financial_report') . '<br/>'
                    . trans('app.forms.financial_audit_start_date') . '<br/>'
                    . trans('app.forms.financial_audit_end_date') . '<br/>'
                    . trans('app.forms.financial_audit_report'),
                    $status9 . '<br/>' . $date_audit_start . '<br/>' . $date_audit_end . '<br/>' . $status10,
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

    public function addMinutes() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('year', 'desc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'desc')->get();
            }
        }

        $viewData = array(
            'title' => trans('app.menus.agm.add_minutes'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmminutesub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'image' => ""
        );

        return View::make('agm_en.add_minutes', $viewData);
    }

    public function submitAddMinutes() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $agm_date = $data['agm_date'];
            $agm = $data['agm'];
            $agm_file_url = $data['agm_file_url'];
            $egm = $data['egm'];
            $egm_file_url = $data['egm_file_url'];
            $minit_meeting = $data['minit_meeting'];
            $minutes_meeting_file_url = $data['minutes_meeting_file_url'];
            $jmc_copy = $data['jmc_copy'];
            $jmc_file_url = $data['jmc_file_url'];
            $ic_list = $data['ic_list'];
            $ic_file_url = $data['ic_file_url'];
            $attendance_list = $data['attendance_list'];
            $attendance_file_url = $data['attendance_file_url'];
            $audited_financial_report = $data['audited_financial_report'];
            $audited_financial_file_url = $data['audited_financial_file_url'];
            $audit_report = $data['audit_report'];
            $audit_start = $data['audit_start'];
            $audit_end = $data['audit_end'];
            $audit_report_file_url = $data['audit_report_file_url'];
            $letter_integrity_url = $data['letter_integrity_url'];
            $letter_bankruptcy_url = $data['letter_bankruptcy_url'];
            $remarks = $data['remarks'];

            $agm_detail = new MeetingDocument();
            $agm_detail->file_id = $file_id;
            $agm_detail->agm_date = $agm_date;
            $agm_detail->agm = $agm;
            if (!empty($agm_file_url)) {
                $agm_detail->agm_file_url = $agm_file_url;
            }
            $agm_detail->egm = $egm;
            if (!empty($egm_file_url)) {
                $agm_detail->egm_file_url = $egm_file_url;
            }
            $agm_detail->minit_meeting = $minit_meeting;
            if (!empty($minutes_meeting_file_url)) {
                $agm_detail->minutes_meeting_file_url = $minutes_meeting_file_url;
            }
            $agm_detail->jmc_spa = $jmc_copy;
            if (!empty($jmc_file_url)) {
                $agm_detail->jmc_file_url = $jmc_file_url;
            }
            $agm_detail->identity_card = $ic_list;
            if (!empty($ic_file_url)) {
                $agm_detail->ic_file_url = $ic_file_url;
            }
            $agm_detail->attendance = $attendance_list;
            if (!empty($attendance_file_url)) {
                $agm_detail->attendance_file_url = $attendance_file_url;
            }
            $agm_detail->financial_report = $audited_financial_report;
            if (!empty($audited_financial_file_url)) {
                $agm_detail->audited_financial_file_url = $audited_financial_file_url;
            }
            $agm_detail->audit_report = $audit_report;
            if (!empty($audit_report_file_url)) {
                $agm_detail->audit_report_url = $audit_report_file_url;
            }
            if (!empty($letter_integrity_url)) {
                $agm_detail->letter_integrity_url = $letter_integrity_url;
            }
            if (!empty($letter_bankruptcy_url)) {
                $agm_detail->letter_bankruptcy_url = $letter_bankruptcy_url;
            }
            $agm_detail->audit_start_date = $audit_start;
            $agm_detail->audit_end_date = $audit_end;
            $agm_detail->remarks = $remarks;
            $success = $agm_detail->save();

            if ($success) {
                # Audit Trail
                $file_name = Files::find($agm_detail->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_detail->agm_date)) . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function editMinutes($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $meeting_doc = MeetingDocument::find($id);
        if ($meeting_doc) {
            if (!Auth::user()->getAdmin()) {
                if (!empty(Auth::user()->file_id)) {
                    $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
                } else {
                    $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'desc')->get();
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $files = Files::where('is_deleted', 0)->orderBy('year', 'desc')->get();
                } else {
                    $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'desc')->get();
                }
            }

            $viewData = array(
                'title' => trans('app.menus.agm.edit_minutes'),
                'panel_nav_active' => 'agm_panel',
                'main_nav_active' => 'agm_main',
                'sub_nav_active' => 'agmminutesub_list',
                'user_permission' => $user_permission,
                'meeting_doc' => $meeting_doc,
                'files' => $files,
                'image' => ""
            );

            return View::make('agm_en.edit_minutes', $viewData);
        }
    }

    public function submitEditMinutes() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];
            $file_id = $data['file_id'];
            $agm_date = $data['agm_date'];
            $agm = $data['agm'];
            $agm_file_url = $data['agm_file_url'];
            $egm = $data['egm'];
            $egm_file_url = $data['egm_file_url'];
            $minit_meeting = $data['minit_meeting'];
            $minutes_meeting_file_url = $data['minutes_meeting_file_url'];
            $jmc_copy = $data['jmc_copy'];
            $jmc_file_url = $data['jmc_file_url'];
            $ic_list = $data['ic_list'];
            $ic_file_url = $data['ic_file_url'];
            $attendance_list = $data['attendance_list'];
            $attendance_file_url = $data['attendance_file_url'];
            $audited_financial_report = $data['audited_financial_report'];
            $audited_financial_file_url = $data['audited_financial_file_url'];
            $audit_report = $data['audit_report'];
            $audit_start = $data['audit_start'];
            $audit_end = $data['audit_end'];
            $audit_report_file_url = $data['audit_report_file_url'];
            $letter_integrity_url = $data['letter_integrity_url'];
            $letter_bankruptcy_url = $data['letter_bankruptcy_url'];
            $remarks = $data['remarks'];

            $agm_detail = MeetingDocument::find($id);
            if ($agm_detail) {
                $agm_detail->file_id = $file_id;
                $agm_detail->agm_date = $agm_date;
                $agm_detail->agm = $agm;
                if (!empty($agm_file_url)) {
                    $agm_detail->agm_file_url = $agm_file_url;
                }
                $agm_detail->egm = $egm;
                if (!empty($egm_file_url)) {
                    $agm_detail->egm_file_url = $egm_file_url;
                }
                $agm_detail->minit_meeting = $minit_meeting;
                if (!empty($minutes_meeting_file_url)) {
                    $agm_detail->minutes_meeting_file_url = $minutes_meeting_file_url;
                }
                $agm_detail->jmc_spa = $jmc_copy;
                if (!empty($jmc_file_url)) {
                    $agm_detail->jmc_file_url = $jmc_file_url;
                }
                $agm_detail->identity_card = $ic_list;
                if (!empty($ic_file_url)) {
                    $agm_detail->ic_file_url = $ic_file_url;
                }
                $agm_detail->attendance = $attendance_list;
                if (!empty($attendance_file_url)) {
                    $agm_detail->attendance_file_url = $attendance_file_url;
                }
                $agm_detail->financial_report = $audited_financial_report;
                if (!empty($audited_financial_file_url)) {
                    $agm_detail->audited_financial_file_url = $audited_financial_file_url;
                }
                $agm_detail->audit_report = $audit_report;
                if (!empty($audit_report_file_url)) {
                    $agm_detail->audit_report_url = $audit_report_file_url;
                }
                if (!empty($letter_integrity_url)) {
                    $agm_detail->letter_integrity_url = $letter_integrity_url;
                }
                if (!empty($letter_bankruptcy_url)) {
                    $agm_detail->letter_bankruptcy_url = $letter_bankruptcy_url;
                }
                $agm_detail->audit_start_date = $audit_start;
                $agm_detail->audit_end_date = $audit_end;
                $agm_detail->remarks = $remarks;
                $success = $agm_detail->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($agm_detail->file_id);
                    $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_detail->agm_date)) . ' has been inserted.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function deleteMinutes() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->is_deleted = 1;
            $deleted = $agm_details->save();

            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //document
    public function document() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.agm.upload_document'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdocumentsub_list',
            'user_permission' => $user_permission,
            'documentType' => $documentType,
            'image' => ""
        );

        return View::make('agm_en.document', $viewData);
    }

    public function getDocument() {
        if (!empty(Auth::user()->file_id)) {
            $document = Document::where('file_id', Auth::user()->file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        } else {
            $document = Document::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        }

        if (count($document) > 0) {
            $data = Array();
            foreach ($document as $documents) {
                $button = "";
                if ($documents->is_hidden == 1) {
                    $is_hidden = 'Yes';
                } else {
                    $is_hidden = trans('app.forms.no');
                }

                if ($documents->is_readonly == 1) {
                    $is_readonly = 'Yes';
                } else {
                    $is_readonly = trans('app.forms.no');
                }

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@updateDocument', $documents->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDocument(\'' . $documents->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    ($documents->file ? $documents->file->file_no : '-'),
                    $documents->type->name,
                    $documents->name,
                    $is_hidden,
                    $is_readonly,
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

    public function deleteDocument() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $document = Document::find($id);
            if ($document) {
                $document->is_deleted = 1;
                $deleted = $document->save();
                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Document: ' . $document->name_en . ' has been deleted.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "Document";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function deleteDocumentFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $document = Document::find($id);
            if ($document) {
                $document->file_url = "";
                $deleted = $document->save();

                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Document: ' . $document->name_en . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "Document";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function addDocument() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();

        $viewData = array(
            'title' => trans('app.menus.agm.add_document'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdocumentsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'documentType' => $documentType,
            'image' => ""
        );

        return View::make('agm_en.add_document', $viewData);
    }

    public function submitAddDocument() {
        $data = Input::all();
        if (Request::ajax()) {

            $document = new Document();
            $document->file_id = $data['file_id'];
            $document->document_type_id = $data['document_type'];
            $document->name = $data['name'];
            $document->remarks = $data['remarks'];
            $document->is_hidden = $data['is_hidden'];
            $document->is_readonly = $data['is_readonly'];
            $document->file_url = $data['document_url'];
            $success = $document->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Document: ' . $document->name_en . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Document";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateDocument($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $document = Document::find($id);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $files = Files::where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.agm.edit_document'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdocumentsub_list',
            'user_permission' => $user_permission,
            'document' => $document,
            'files' => $files,
            'documentType' => $documentType,
            'image' => ""
        );

        return View::make('agm_en.edit_document', $viewData);
    }

    public function submitUpdateDocument() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $document = Document::find($id);
            if ($document) {
                $document->file_id = $data['file_id'];
                $document->document_type_id = $data['document_type'];
                $document->name = $data['name'];
                $document->remarks = $data['remarks'];
                $document->is_hidden = $data['is_hidden'];
                $document->is_readonly = $data['is_readonly'];
                $document->file_url = $data['document_url'];
                $success = $document->save();

                if ($success) {
                    # Audit Trail
                    $remarks = $document->id . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "Document";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return 'false';
            }
        } else {
            return "false";
        }
    }

    //------------------------------------- RONALDO -------------------------------------------//
    //AGM Design Submission
    public function agmDesignSub() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('agm_design_sub.title'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'image' => ''
        );

        return View::make('page.agm_design_sub.index', $viewData);
    }

    public function addAgmDesignSub() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::where('is_active', 1)->where('is_deleted', 0)->get();
        $design = Designation::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('agm_design_sub.title_add'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'file' => $file,
            'design' => $design,
            'image' => ''
        );

        return View::make('page.agm_design_sub.add', $viewData);
    }

    public function submitAgmDesignSub() {
        $data = Input::all();
        if (Request::ajax()) {

            $agmDesignSub = new AgmDesignSub();
            $agmDesignSub->file_id = $data['file_id'];
            $agmDesignSub->design_id = $data['design_id'];
            $agmDesignSub->name = $data['name'];
            $agmDesignSub->phone_number = $data['phone_number'];
            $agmDesignSub->email = $data['email'];
            $agmDesignSub->ajk_year = $data['ajk_year'];
            $agmDesignSub->remark = $data['remark'];
            $success = $agmDesignSub->save();

            if ($success) {
                # Audit Trail
                $remarks = 'New AGM Designation Submission has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Design Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getAgmDesignSub() {
        $agmDesignSub = AgmDesignSub::get();
        if (count($agmDesignSub) > 0) {
            $data = Array();
            foreach ($agmDesignSub as $x) {

                $button = '';
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@updateAgmDesignSub', $x->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';

                $data_raw = array(
                    $x->design->description,
                    $x->name,
                    $x->phone_number,
                    $x->email,
                    $x->ajk_year,
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

    public function inactiveAgmDesignSub() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agmDesignSub = AgmDesignSub::find($id);
            $agmDesignSub->is_active = 0;
            $updated = $agmDesignSub->save();
            if ($updated) {
                # Audit Trail
                $remarks = $agmDesignSub->id . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Design Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeAgmDesignSub() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agmDesignSub = AgmDesignSub::find($id);
            $agmDesignSub->is_active = 1;
            $updated = $agmDesignSub->save();
            if ($updated) {
                # Audit Trail
                $remarks = $agmDesignSub->id . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Design Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteAgmDesignSub() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agmDesignSub = AgmDesignSub::find($id);
            $agmDesignSub->is_deleted = 1;
            $deleted = $agmDesignSub->save();
            if ($deleted) {
                # Audit Trail
                $remarks = $agmDesignSub->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Design Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateAgmDesignSub($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $agmDesignSub = AgmDesignSub::find($id);
        $file = Files::where('is_active', 1)->where('is_deleted', 0)->get();
        $design = Designation::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('agm_design_sub.title_edit'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'agmDesignSub' => $agmDesignSub,
            'file' => $file,
            'design' => $design,
            'image' => ""
        );

        return View::make('page.agm_design_sub.edit', $viewData);
    }

    public function submitUpdateAgmDesignSub() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $agmDesignSub = AgmDesignSub::find($id);
            $agmDesignSub->file_id = $data['file_id'];
            $agmDesignSub->design_id = $data['design_id'];
            $agmDesignSub->name = $data['name'];
            $agmDesignSub->phone_number = $data['phone_number'];
            $agmDesignSub->email = $data['email'];
            $agmDesignSub->ajk_year = $data['ajk_year'];
            $agmDesignSub->remark = $data['remark'];
            $success = $agmDesignSub->save();

            if ($success) {
                # Audit Trail
                $remarks = $agmDesignSub->id . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Design Submission Update";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    // AGM Puchaser Submission
    public function agmPurchaseSub() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('agm_purchase_sub.title'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'user_permission' => $user_permission,
            'image' => ''
        );

        return View::make('page.agm_purchase_sub.index', $viewData);
    }

    public function addAgmPurchaseSub() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('agm_purchase_sub.title_add'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'user_permission' => $user_permission,
            'file' => $file,
            'image' => ''
        );

        return View::make('page.agm_purchase_sub.add', $viewData);
    }

    public function submitAgmPurchaseSub() {
        $data = Input::all();
        $fields = [
            'file_id',
            'unit_no',
            'share_unit',
            'buyer',
            'nric',
            'address1',
            'address2',
            'address3',
            'address4',
            'postcode',
            'phone_number',
            'email',
            'remark',
        ];
        if (Request::ajax()) {

            $agmPurchaseSub = new AgmPurchaseSub();
            foreach ($fields as $field) {
                $agmPurchaseSub->$field = $data[$field];
            }
            $success = $agmPurchaseSub->save();

            if ($success) {
                # Audit Trail
                $remarks = 'New AGM Purchaser Submission has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Purchaser Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getAgmPurchaseSub() {
        $agmPurchaseSub = AgmPurchaseSub::get();
        if (count($agmPurchaseSub) > 0) {
            $data = Array();
            foreach ($agmPurchaseSub as $x) {

                $button = '';
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@updateAgmPurchaseSub', $x->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';

                $data_raw = array(
                    $x->unit_no,
                    $x->share_unit,
                    $x->buyer,
                    $x->nric,
                    $x->phone_number,
                    $x->email,
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

    public function inactiveAgmPurchaseSub() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agmPurchaseSub = AgmPurchaseSub::find($id);
            $agmPurchaseSub->is_active = 0;
            $updated = $agmPurchaseSub->save();
            if ($updated) {
                # Audit Trail
                $remarks = $agmPurchaseSub->id . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Purchaser Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeAgmPurchaseSub() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agmPurchaseSub = AgmPurchaseSub::find($id);
            $agmPurchaseSub->is_active = 1;
            $updated = $agmPurchaseSub->save();
            if ($updated) {
                # Audit Trail
                $remarks = $agmPurchaseSub->id . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Purchaser Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteAgmPurchaseSub() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agmPurchaseSub = AgmPurchaseSub::find($id);
            $agmPurchaseSub->is_deleted = 1;
            $deleted = $agmPurchaseSub->save();
            if ($deleted) {
                # Audit Trail
                $remarks = $agmPurchaseSub->id . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Purchaser Submission";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateAgmPurchaseSub($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $agmPurchaseSub = AgmPurchaseSub::find($id);
        $file = Files::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('agm_purchase_sub.title_edit'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmpurchasesub_list',
            'user_permission' => $user_permission,
            'agmPurchaseSub' => $agmPurchaseSub,
            'file' => $file,
            'image' => ""
        );
        return View::make('page.agm_purchase_sub.edit', $viewData);
    }

    public function submitUpdateAgmPurchaseSub() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $fields = [
                'file_id',
                'unit_no',
                'share_unit',
                'buyer',
                'nric',
                'address1',
                'address2',
                'address3',
                'address4',
                'postcode',
                'phone_number',
                'email',
                'remark',
            ];

            $agmPurchaseSub = AgmPurchaseSub::find($id);

            foreach ($fields as $field) {
                $agmPurchaseSub->$field = $data[$field];
            }
            $success = $agmPurchaseSub->save();

            if ($success) {
                # Audit Trail
                $remarks = $agmPurchaseSub->id . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "AGM Purchaser Submission Update";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getFileListByCOB() {
        $result = '';
        $data = Input::all();
        if (Request::ajax()) {
            $company_id = $data['company'];

            if ($company_id) {
                $files = Files::where('company_id', $company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
            } else {
                if (!Auth::user()->getAdmin()) {
                    if (!empty(Auth::user()->file_id)) {
                        $files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
                    } else {
                        $files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
                    }
                } else {
                    if (empty(Session::get('admin_cob'))) {
                        $files = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
                    } else {
                        $files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
                    }
                }
            }

            $result = "<option value=''>" . trans('app.forms.please_select') . "</option>";
            if ($files) {
                foreach ($files as $file) {
                    $result .= "<option value='" . $file->id . "'>" . $file->file_no . "</option>";
                }
            }
        }

        return $result;
    }

}
