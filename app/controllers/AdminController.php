<?php

class AdminController extends BaseController {

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

    public function home() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        $stratas = 0;
        $ratings = 0;
        $fiveStars = 0;
        $fourStars = 0;
        $threeStars = 0;
        $twoStars = 0;
        $oneStars = 0;
        $jmbs = 0;
        $mcs = 0;
        $agents = 0;
        $otherss = 0;

        $developer = Developer::where('is_deleted', 0)->count();

        if (count($file) > 0) {
            foreach ($file as $files) {
                $strata = Strata::where('file_id', $files->id)->count();
                $rating = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->count();

                $fiveStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 81)->where('total_score', '<=', 100)->count();
                $fourStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 61)->where('total_score', '<=', 80)->count();
                $threeStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 41)->where('total_score', '<=', 60)->count();
                $twoStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 21)->where('total_score', '<=', 40)->count();
                $oneStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 1)->where('total_score', '<=', 20)->count();

                $stratas += $strata;
                $ratings += $rating;
                $fiveStars += $fiveStar;
                $fourStars += $fourStar;
                $threeStars += $threeStar;
                $twoStars += $twoStar;
                $oneStars += $oneStar;

                $jmb = ManagementJMB::where('file_id', $files->id)->count();
                $mc = ManagementMC::where('file_id', $files->id)->count();
                $agent = ManagementAgent::where('file_id', $files->id)->count();
                $others = ManagementOthers::where('file_id', $files->id)->count();

                $jmbs += $jmb;
                $mcs += $mc;
                $agents += $agent;
                $otherss += $others;
            }
        }

        $viewData = array(
            'title' => trans('app.app_name_short'),
            'panel_nav_active' => 'home_panel',
            'main_nav_active' => 'home_main',
            'sub_nav_active' => 'home',
            'user_permission' => $user_permission,
            'strata' => $stratas,
            'rating' => $ratings,
            'fiveStar' => $fiveStars,
            'fourStar' => $fourStars,
            'threeStar' => $threeStars,
            'twoStar' => $twoStars,
            'oneStar' => $oneStars,
            'developer' => $developer,
            'jmb' => $jmbs,
            'mc' => $mcs,
            'agent' => $agents,
            'others' => $otherss,
            'image' => ""
        );

        return View::make('home_en.index', $viewData);
    }

    public function getAGMRemainder() {
        $oneyear = strtotime("-1 Year");

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        if (count($file) > 0) {
            $data = Array();
            foreach ($file as $files) {
                $agm_remainder = MeetingDocument::where('file_id', $files->id)->where('is_deleted', 0)->orderBy('agm_date', 'desc')->first();
                if (count($agm_remainder) > 0) {
                    if ($agm_remainder->agm_date <= date('Y-m-d', $oneyear) && $agm_remainder->agm_date != "0000-00-00") {
                        $button = "";
                        $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@monitoring', $files->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                        $data_raw = array(
                            $files->company->short_name,
                            $files->file_no,
                            date('d-M-Y', strtotime($agm_remainder->agm_date)),
                            $button
                        );

                        array_push($data, $data_raw);
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

    public function getNeverAGM() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        if (count($file) > 0) {
            $data = Array();
            foreach ($file as $files) {
                $never_agm = MeetingDocument::where('file_id', $files->id)->where('is_deleted', 0)->orderBy('agm_date', 'desc')->first();

                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@monitoring', $files->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                if (count($never_agm) > 0) {
                    if ($never_agm->agm_date == "0000-00-00") {
                        $data_raw = array(
                            $files->company->short_name,
                            $files->file_no,
                            $button
                        );

                        array_push($data, $data_raw);
                    }
                } else {
                    $data_raw = array(
                        $files->company->short_name,
                        $files->file_no,
                        $button
                    );

                    array_push($data, $data_raw);
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

    public function getAGM12Months() {
        $twelveMonths = strtotime("-12 Months");

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        if (count($file) > 0) {
            $data = Array();
            foreach ($file as $files) {
                $agm_more12months = MeetingDocument::where('file_id', $files->id)->where('is_deleted', 0)->orderBy('agm_date', 'desc')->first();
                if (count($agm_more12months) > 0) {
                    if ($agm_more12months->agm_date <= date('Y-m-d', $twelveMonths) && $agm_more12months->agm_date != "0000-00-00") {
                        $button = "";
                        $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@monitoring', $files->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                        $data_raw = array(
                            $files->company->short_name,
                            $files->file_no,
                            date('d-M-Y', strtotime($agm_more12months->agm_date)),
                            $button
                        );

                        array_push($data, $data_raw);
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

    public function getAGM15Months() {
        $fifthteenMonths = strtotime("-15 Months");

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        if (count($file) > 0) {
            $data = Array();
            foreach ($file as $files) {
                $agm_more15months = MeetingDocument::where('file_id', $files->id)->where('is_deleted', 0)->orderBy('agm_date', 'desc')->first();
                if (count($agm_more15months) > 0) {
                    if ($agm_more15months->agm_date <= date('Y-m-d', $fifthteenMonths) && $agm_more15months->agm_date != "0000-00-00") {
                        $button = "";
                        $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@monitoring', $files->id) . '\'">' . trans('app.forms.view') . '</button>&nbsp;';
                        $data_raw = array(
                            $files->company->short_name,
                            $files->file_no,
                            date('d-M-Y', strtotime($agm_more15months->agm_date)),
                            $button
                        );

                        array_push($data, $data_raw);
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

    public function getMemoHome() {
        $memo = Memo::where('is_deleted', 0)->where('is_active', 1)->orderBy('id', 'desc')->get();

        if (count($memo) > 0) {
            $data = Array();
            foreach ($memo as $memos) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="getMemoDetails(\'' . $memos->id . '\')">' . trans('app.forms.view') . '</button>&nbsp;';
                $data_raw = array(
                    $memos->subject,
                    date('d-M-Y', strtotime($memos->memo_date)),
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

    public function getMemoDetails() {
        $data = Input::all();
        if (Request::ajax()) {

            $result = "";
            $id = $data['id'];

            $memo = Memo::find($id);

            if (count($memo) > 0) {

                $result .= "<div class='modal-header'>";
                $result .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
                $result .= "<h4 class='modal-title' id='myModalLabel'>" . ($memo->subject != "" ? $memo->subject : "-") . "</h4>";
                $result .= "<h6 class='modal-title' id=''>" . (date('d-M-Y', strtotime($memo->memo_date)) != "" ? date('d-M-Y', strtotime($memo->memo_date)) : "-") . "</h6>";
                $result .= "</div>";
                $result .= "<div class='modal-body'>";
                $result .= "<p>" . ($memo->description != "" ? $memo->description : "-") . "</p>";
                $result .= "</div>";
            } else {
                $result = trans('app.errors.no_data_found');
            }

            print $result;
        }
    }

    // --- COB Maintenance --- //
    //file prefix
    public function filePrefix() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.cob.file_prefix_maintenance'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'prefix_file',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('cob_en.fileprefix', $viewData);
    }

    public function addFilePrefix() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $viewData = array(
            'title' => trans('app.menus.cob.add_cob_file_prefix'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'prefix_file',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'image' => ""
        );

        return View::make('cob_en.add_fileprefix', $viewData);
    }

    public function submitFilePrefix() {
        $data = Input::all();
        if (Request::ajax()) {
            $company_id = $data['company_id'];
            $description = $data['description'];
            $is_active = $data['is_active'];
            $sort_no = $data['sort_no'];

            $fileprefix = new FilePrefix();
            $fileprefix->company_id = $company_id;
            $fileprefix->description = $description;
            $fileprefix->sort_no = $sort_no;
            $fileprefix->is_active = $is_active;
            $success = $fileprefix->save();

            if ($success) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $fileprefix->description . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Master Setup";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->username;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getFilePrefix() {
        if (!Auth::user()->getAdmin()) {
            $prefix = FilePrefix::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $prefix = FilePrefix::where('is_deleted', 0)->orderBy('id', 'desc')->get();
            } else {
                $prefix = FilePrefix::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }
        }

        if (count($prefix) > 0) {
            $data = Array();
            foreach ($prefix as $fileprefixs) {
                $button = "";
                if ($fileprefixs->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveFilePrefix(\'' . $fileprefixs->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeFilePrefix(\'' . $fileprefixs->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateFilePrefix', $fileprefixs->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteFilePrefix(\'' . $fileprefixs->id . '\')"><i class="fa fa-trash"></i></button>';
                $data_raw = array(
                    $fileprefixs->description,
                    $fileprefixs->sort_no,
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

    public function inactiveFilePrefix() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $prefix = FilePrefix::find($id);
            $prefix->is_active = 0;
            $updated = $prefix->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $prefix->description . ' has been updated.';
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

    public function activeFilePrefix() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $prefix = FilePrefix::find($id);
            $prefix->is_active = 1;
            $updated = $prefix->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $prefix->description . ' has been updated.';
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

    public function deleteFilePrefix() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $prefix = FilePrefix::find($id);
            $prefix->is_deleted = 1;
            $deleted = $prefix->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $prefix->description . ' has been deleted.';
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

    public function updateFilePrefix($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $prefix = FilePrefix::find($id);

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file_prefix'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'prefix_file',
            'user_permission' => $user_permission,
            'prefix' => $prefix,
            'image' => ""
        );

        return View::make('cob_en.update_fileprefix', $viewData);
    }

    public function submitUpdateFilePrefix() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $description = $data['description'];
            $is_active = $data['is_active'];
            $sort_no = $data['sort_no'];

            $fileprefix = FilePrefix::find($id);
            $fileprefix->description = $description;
            $fileprefix->sort_no = $sort_no;
            $fileprefix->is_active = $is_active;
            $success = $fileprefix->save();

            if ($success) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $fileprefix->description . ' has been updated.';
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

    // add file
    public function addFile() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            $file_no = FilePrefix::where('is_active', 1)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $file_no = FilePrefix::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
                $file_no = FilePrefix::where('is_active', 1)->where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
            }
        }

        $viewData = array(
            'title' => trans('app.menus.cob.add_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'add_cob',
            'file_no' => $file_no,
            'cob' => $cob,
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('page_en.add_file', $viewData);
    }

    public function submitFile() {
        $data = Input::all();
        if (Request::ajax()) {
            $company_id = $data['company_id'];
            $file_no = $data['file_no'];
            $description = $data['description'];

            $filename = $file_no . '-' . $description;
            $year = substr($filename, strpos($filename, "/") + 1);

            $check_file = Files::where('company_id', $company_id)->where('file_no', $filename)->where('is_deleted', 0)->count();

            if ($check_file <= 0) {
                $files = new Files();
                $files->company_id = $company_id;
                $files->file_no = $filename;
                if (!empty($year)) {
                    $files->year = $year;
                } else {
                    $files->year = '';
                }
                $files->is_active = 0;
                $files->status = 1;
                $files->approved_by = Auth::user()->id;
                $files->approved_at = date('Y-m-d H:i:s');
                $files->created_by = Auth::user()->id;
                $success = $files->save();

                if ($success) {
                    $house_scheme = new HouseScheme();
                    $house_scheme->file_id = $files->id;
                    $house_scheme->is_active = "-1";
                    $created1 = $house_scheme->save();

                    if ($created1) {
                        $strata = new Strata();
                        $strata->file_id = $files->id;
                        $created2 = $strata->save();

                        if ($created2) {
                            $facility = new Facility();
                            $facility->file_id = $files->id;
                            $facility->strata_id = $strata->id;
                            $created3 = $facility->save();

                            if ($created3) {
                                $management = new Management();
                                $management->file_id = $files->id;
                                $created4 = $management->save();

                                if ($created4) {
                                    $monitor = new Monitoring();
                                    $monitor->file_id = $files->id;
                                    $created5 = $monitor->save();

                                    if ($created5) {
                                        $others = new OtherDetails();
                                        $others->file_id = $files->id;
                                        $created6 = $others->save();

                                        if ($created6) {
                                            # Audit Trail
                                            $remarks = $files->file_no . ' has been inserted.';
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
                            } else {
                                print "false";
                            }
                        } else {
                            print "false";
                        }
                    } else {
                        print "false";
                    }
                } else {
                    print "false";
                }
            } else {
                print "file_already_exists";
            }
        } else {
            print "false";
        }
    }

    // file list
    public function fileList() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }

        $file = Files::where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.cob.file_list'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'cob_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'file' => $file,
            'image' => ""
        );

        return View::make('page_en.file_list', $viewData);
    }

    public function getFileList() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', '!=', 2)->where('is_deleted', 0)->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', '!=', 2)->where('is_deleted', 0)->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', '!=', 2)->where('is_deleted', 0)->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', '!=', 2)->where('is_deleted', 0)->get();
            }
        }

        if (count($file) > 0) {
            $data = Array();
            foreach ($file as $files) {
                $strata = Strata::where('file_id', $files->id)->first();
                if (count($strata) > 0) {
                    $strata_name = $strata->name;
                } else {
                    $strata_name = "";
                }
                $button = "";
                if ($files->is_active == 1) {
                    $is_active = trans('app.forms.yes');
                    $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveFileList(\'' . $files->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $is_active = trans('app.forms.no');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeFileList(\'' . $files->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

                if (Auth::user()->role == 1) {
                    $button .= '<button type="button" class="btn btn-xs btn-warning modal-update-file-no" data-toggle="modal" data-target="#updateFileNoForm" data-id="' . $files->id . '" data-file_no="' . $files->file_no . '">' . trans('app.forms.update_file_no') . '</button>&nbsp;';
                }

//                    $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="window.location=\'' . URL::action('AdminController@viewHouse', $files->id) . '\'">View <i class="fa fa-eye"></i></button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFileList(\'' . $files->id . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', $files->id) . "'>" . $files->file_no . "</a>",
                    $strata_name,
                    $files->company->short_name,
                    $files->year,
                    $is_active,
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

    // file list
    public function fileListBeforeVP() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }

        $file = Files::where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.cob.file_list_before_vp'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'cob_before_vp_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'file' => $file,
            'image' => ""
        );

        return View::make('page_en.file_list_before_vp', $viewData);
    }

    public function getFileListBeforeVP() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 2)->where('is_deleted', 0)->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 2)->where('is_deleted', 0)->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 2)->where('is_deleted', 0)->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 2)->where('is_deleted', 0)->get();
            }
        }

        if (count($file) > 0) {
            $data = Array();
            foreach ($file as $files) {
                $strata = Strata::where('file_id', $files->id)->first();
                if (count($strata) > 0) {
                    $strata_name = $strata->name;
                } else {
                    $strata_name = "";
                }
                $button = "";
                if ($files->is_active == 1) {
                    $is_active = trans('app.forms.yes');
                    $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveFileList(\'' . $files->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $is_active = trans('app.forms.no');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeFileList(\'' . $files->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

//                    $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="window.location=\'' . URL::action('AdminController@viewHouse', $files->id) . '\'">View <i class="fa fa-eye"></i></button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFileList(\'' . $files->id . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', $files->id) . "'>" . $files->file_no . "</a>",
                    $strata_name,
                    $files->company->short_name,
                    $files->year,
                    $is_active,
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

    public function inactiveFileList() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $files = Files::find($id);
            $files->is_active = 0;
            $updated = $files->save();
            if ($updated) {
                # Audit Trail
                $remarks = $files->file_no . ' has been updated.';
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

    public function activeFileList() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $files = Files::find($id);
            $files->is_active = 1;
            $updated = $files->save();
            if ($updated) {
                # Audit Trail
                $remarks = $files->file_no . ' has been updated.';
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

    public function deleteFileList() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $files = Files::find($id);
            if ($files) {
                $deleted = $files->delete();

                if ($deleted) {
                    $house_scheme = HouseScheme::where('file_id', $files->id)->delete();
                    $strata = Strata::where('file_id', $files->id)->delete();
                    $facility = Facility::where('file_id', $files->id)->delete();
                    $management = Management::where('file_id', $files->id)->delete();
                    $monitor = Monitoring::where('file_id', $files->id)->delete();
                    $others = OtherDetails::where('file_id', $files->id)->delete();
                    # Commercial Block
                    $commercial = Commercial::where('file_id', $files->id)->delete();
                    # Residential Block
                    $residential = Residential::where('file_id', $files->id)->delete();
                    # Management JMB
                    $managementjmb = ManagementJMB::where('file_id', $files->id)->delete();
                    # Management MC
                    $managementmc = ManagementMC::where('file_id', $files->id)->delete();
                    # Management Agent
                    $managementagent = ManagementAgent::where('file_id', $files->id)->delete();
                    # Management Other
                    $managementother = ManagementOthers::where('file_id', $files->id)->delete();
                    # Meeting Document
                    $meetingdocument = MeetingDocument::where('file_id', $files->id)->delete();
                    # AJK Detail
                    $ajkdetail = AJKDetails::where('file_id', $files->id)->delete();
                    # Scoring
                    $scoring = Scoring::where('file_id', $files->id)->delete();
                    # Buyer List
                    $buyerlist = Buyer::where('file_id', $files->id)->delete();

                    # Audit Trail
                    $remarks = $files->file_no . ' has been deleted.';
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

    public function updateFileNo() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['file_id'];
            $file_no = $data['file_no'];

            $check_exist = Files::where('file_no', $file_no)->where('id', '!=', $id)->where('is_deleted', 0)->count();
            if ($check_exist <= 0) {
                $files = Files::find($id);
                if ($files) {
                    $files->file_no = $data['file_no'];
                    $updated = $files->save();
                    if ($updated) {
                        # Audit Trail
                        $remarks = $files->file_no . ' has been updated.';
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
            } else {
                print "exist";
            }
        }
    }

    public function viewHouse($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $house_scheme = HouseScheme::where('file_id', $file->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $developer = Developer::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'developer' => $developer,
            'house_scheme' => $house_scheme,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'file' => $file,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_house_scheme', $viewData);
    }

    public function house($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $house_scheme = HouseScheme::where('file_id', $file->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $developer = Developer::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $users = User::where('company_id', $file->company_id)->where('is_active', 1)->where('status', 1)->where('is_deleted', 0)->orderBy('full_name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'developer' => $developer,
            'house_scheme' => $house_scheme,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'file' => $file,
            'users' => $users,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_house_scheme', $viewData);
    }

    public function submitUpdateHouseScheme() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $name = $data['name'];
            $developer = $data['developer'];
            $address1 = $data['address1'];
            $address2 = $data['address2'];
            $address3 = $data['address3'];
            $address4 = $data['address4'];
            $city = $data['city'];
            $poscode = $data['poscode'];
            $state = $data['state'];
            $country = $data['country'];
            $phone_no = $data['phone_no'];
            $fax_no = $data['fax_no'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $house_scheme = HouseScheme::find($id);
            if ($house_scheme) {
                $files = Files::find($house_scheme->file_id);

                if ($files) {
                    $files->is_active = $is_active;
                    $updated = $files->save();

                    if ($updated) {
                        $house_scheme->name = $name;
                        $house_scheme->developer = $developer;
                        $house_scheme->address1 = $address1;
                        $house_scheme->address2 = $address2;
                        $house_scheme->address3 = $address3;
                        $house_scheme->address4 = $address4;
                        $house_scheme->city = $city;
                        $house_scheme->poscode = $poscode;
                        $house_scheme->state = $state;
                        $house_scheme->country = $country;
                        $house_scheme->phone_no = $phone_no;
                        $house_scheme->fax_no = $fax_no;
                        $house_scheme->remarks = $remarks;
                        if ($is_active != 2) {
                            $house_scheme->is_active = $is_active;
                        }
                        $success = $house_scheme->save();

                        if ($success) {
                            # Audit Trail
                            $remarks = 'House Info (' . $files->file_no . ') has been updated.';
                            $auditTrail = new AuditTrail();
                            $auditTrail->module = "COB File";
                            $auditTrail->remarks = $remarks;
                            $auditTrail->audit_by = Auth::user()->id;
                            $auditTrail->save();

                            return "true";
                        }
                    }
                }
            }
        }

        return "false";
    }

    public function viewStrata($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $strata = Strata::where('file_id', $file->id)->first();
        $residential = Residential::where('file_id', $file->id)->where('strata_id', $strata->id)->first();
        $commercial = Commercial::where('file_id', $file->id)->where('strata_id', $strata->id)->first();
        $facility = Facility::where('file_id', $file->id)->where('strata_id', $strata->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

        if ($strata->dun != 0) {
            $dun = Dun::where('parliament', $strata->parliament)->where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        } else {
            $dun = Dun::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        }
        if ($strata->park != 0) {
            $park = Park::where('dun', $strata->dun)->where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        } else {
            $park = Park::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        }
        $area = Area::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $unit = UnitMeasure::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $land_title = LandTitle::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $perimeter = Perimeter::where('is_active', 1)->where('is_deleted', 0)->orderBy('description_en', 'asc')->get();
        $unitoption = UnitOption::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'strata' => $strata,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'parliament' => $parliament,
            'dun' => $dun,
            'park' => $park,
            'area' => $area,
            'unit' => $unit,
            'land_title' => $land_title,
            'category' => $category,
            'perimeter' => $perimeter,
            'facility' => $facility,
            'file' => $file,
            'unitoption' => $unitoption,
            'residential' => $residential,
            'commercial' => $commercial,
            'designation' => $designation,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_strata', $viewData);
    }

    public function strata($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $strata = Strata::where('file_id', $file->id)->first();
        $residential = Residential::where('file_id', $file->id)->where('strata_id', $strata->id)->first();
        $commercial = Commercial::where('file_id', $file->id)->where('strata_id', $strata->id)->first();
        $facility = Facility::where('file_id', $file->id)->where('strata_id', $strata->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $parliament = Parliment::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

        if ($strata->dun != 0) {
            $dun = Dun::where('parliament', $strata->parliament)->where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        } else {
            $dun = Dun::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        }
        if ($strata->park != 0) {
            $park = Park::where('dun', $strata->dun)->where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        } else {
            $park = Park::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        }
        $area = Area::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $unit = UnitMeasure::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $land_title = LandTitle::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $category = Category::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $perimeter = Perimeter::where('is_active', 1)->where('is_deleted', 0)->orderBy('description_en', 'asc')->get();
        $unitoption = UnitOption::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'strata' => $strata,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'parliament' => $parliament,
            'dun' => $dun,
            'park' => $park,
            'area' => $area,
            'unit' => $unit,
            'land_title' => $land_title,
            'category' => $category,
            'perimeter' => $perimeter,
            'facility' => $facility,
            'file' => $file,
            'unitoption' => $unitoption,
            'residential' => $residential,
            'commercial' => $commercial,
            'designation' => $designation,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_strata', $viewData);
    }

    public function findDUN() {
        $data = Input::all();
        if (Request::ajax()) {

            $parliament_id = $data['parliament_id'];
            $dun = Dun::where('is_deleted', 0)->where('parliament', $parliament_id)->orderBy('description', 'asc')->get();
            if (count($dun) > 0) {
                $result = "<option value=''>" . trans('app.forms.please_select') . "</option>";

                foreach ($dun as $duns) {
                    $result .= "<option value='" . $duns->id . "'>" . $duns->description . "</option>";
                }

                print $result;
            } else {
                print "<option value=''>" . trans('app.forms.please_select') . "</option>";
            }
        }
    }

    public function findPark() {
        $data = Input::all();
        if (Request::ajax()) {

            $dun_id = $data['dun_id'];
            $park = Park::where('is_deleted', 0)->where('dun', $dun_id)->orderBy('description', 'asc')->get();
            if (count($park) > 0) {
                $result = "<option value=''>" . trans('app.forms.please_select') . "</option>";

                foreach ($park as $parks) {
                    $result .= "<option value='" . $parks->id . "'>" . $parks->description . "</option>";
                }
                print $result;
            } else {
                print "<option value=''>" . trans('app.forms.please_select') . "</option>";
            }
        }
    }

    public function submitUpdateStrata() {
        $data = Input::all();
        if (Request::ajax()) {

            $strata_id = $data['strata_id'];
            $file_id = $data['file_id'];
            $facility_id = $data['facility_id'];
            $title = $data['strata_title'];
            $name = $data['strata_name'];
            $parliament = $data['strata_parliament'];
            $dun = $data['strata_dun'];
            $park = $data['strata_park'];
            $address1 = $data['strata_address1'];
            $address2 = $data['strata_address2'];
            $address3 = $data['strata_address3'];
            $address4 = $data['strata_address4'];
            $city = $data['strata_city'];
            $poscode = $data['strata_poscode'];
            $state = $data['strata_state'];
            $country = $data['strata_country'];
            $block_no = $data['strata_block_no'];
            $floor = $data['strata_floor'];
            $year = $data['strata_year'];
            $ownership_no = $data['strata_ownership_no'];
            $town = $data['strata_town'];
            $land_area = $data['strata_land_area'];
            $land_area_unit = $data['strata_land_area_unit'];
            $lot_no = $data['strata_lot_no'];
            $date = $data['strata_date'];
            $land_title = $data['strata_land_title'];
            $category = $data['strata_category'];
            $perimeter = $data['strata_perimeter'];
            $area = $data['strata_area'];
            $total_share_unit = $data['strata_total_share_unit'];
            $ccc_no = $data['strata_ccc_no'];
            $ccc_date = $data['strata_ccc_date'];
            $is_residential = $data['is_residential'];
            $is_commercial = $data['is_commercial'];
            $stratafile = $data['strata_file_url'];

            if (!empty($year)) {
                $files = Files::find($file_id);
                if ($files) {
                    $files->year = $year;
                    $files->save();
                }
            }

            $strata = Strata::find($strata_id);
            if ($strata) {
                $strata->title = $title;
                $strata->name = $name;
                $strata->parliament = $parliament;
                $strata->dun = $dun;
                $strata->park = $park;
                $strata->address1 = $address1;
                $strata->address2 = $address2;
                $strata->address3 = $address3;
                $strata->address4 = $address4;
                $strata->poscode = $poscode;
                $strata->city = $city;
                $strata->state = $state;
                $strata->country = $country;
                $strata->block_no = $block_no;
                $strata->total_floor = $floor;
                $strata->year = $year;
                $strata->town = $town;
                $strata->area = $area;
                $strata->land_area = $land_area;
                $strata->total_share_unit = $total_share_unit;
                $strata->land_area_unit = $land_area_unit;
                $strata->lot_no = $lot_no;
                $strata->ownership_no = $ownership_no;
                $strata->date = $date;
                $strata->land_title = $land_title;
                $strata->category = $category;
                $strata->perimeter = $perimeter;
                $strata->ccc_no = $ccc_no;
                $strata->ccc_date = $ccc_date;
                $strata->file_url = $stratafile;
                $strata->is_residential = $is_residential;
                $strata->is_commercial = $is_commercial;
                $success = $strata->save();

                if ($success) {
                    //residential
                    $residential_unit_no = $data['residential_unit_no'];
                    $residential_maintenance_fee = $data['residential_maintenance_fee'];
                    $residential_maintenance_fee_option = $data['residential_maintenance_fee_option'];
                    $residential_sinking_fund = $data['residential_sinking_fund'];
                    $residential_sinking_fund_option = $data['residential_sinking_fund_option'];

                    //commercial
                    $commercial_unit_no = $data['commercial_unit_no'];
                    $commercial_maintenance_fee = $data['commercial_maintenance_fee'];
                    $commercial_maintenance_fee_option = $data['commercial_maintenance_fee_option'];
                    $commercial_sinking_fund = $data['commercial_sinking_fund'];
                    $commercial_sinking_fund_option = $data['commercial_sinking_fund_option'];

                    //facility
                    $management_office = $data['management_office'];
                    $management_office_unit = $data['management_office_unit'];
                    $swimming_pool = $data['swimming_pool'];
                    $swimming_pool_unit = $data['swimming_pool_unit'];
                    $surau = $data['surau'];
                    $surau_unit = $data['surau_unit'];
                    $multipurpose_hall = $data['multipurpose_hall'];
                    $multipurpose_hall_unit = $data['multipurpose_hall_unit'];
                    $gym = $data['gym'];
                    $gym_unit = $data['gym_unit'];
                    $playground = $data['playground'];
                    $playground_unit = $data['playground_unit'];
                    $guardhouse = $data['guardhouse'];
                    $guardhouse_unit = $data['guardhouse_unit'];
                    $kindergarten = $data['kindergarten'];
                    $kindergarten_unit = $data['kindergarten_unit'];
                    $open_space = $data['open_space'];
                    $open_space_unit = $data['open_space_unit'];
                    $lift = $data['lift'];
                    $lift_unit = $data['lift_unit'];
                    $rubbish_room = $data['rubbish_room'];
                    $rubbish_room_unit = $data['rubbish_room_unit'];
                    $gated = $data['gated'];
                    $gated_unit = $data['gated_unit'];
                    $others = $data['others'];

                    $residential_old = Residential::where('file_id', $file_id)->where('strata_id', $strata->id)->first();
                    if ($strata->is_residential == 1) {
                        if (count($residential_old) > 0) {
                            $residential_old->delete();
                        }
                        $residential = new Residential();
                        $residential->file_id = $file_id;
                        $residential->strata_id = $strata->id;
                        $residential->unit_no = $residential_unit_no;
                        $residential->maintenance_fee = $residential_maintenance_fee;
                        $residential->maintenance_fee_option = $residential_maintenance_fee_option;
                        $residential->sinking_fund = $residential_sinking_fund;
                        $residential->sinking_fund_option = $residential_sinking_fund_option;
                        $residential->save();
                    } else {
                        if (count($residential_old) > 0) {
                            $residential_old->delete();
                        }
                    }

                    $commercial_old = Commercial::where('file_id', $file_id)->where('strata_id', $strata->id)->first();
                    if ($strata->is_commercial == 1) {
                        if (count($commercial_old) > 0) {
                            $commercial_old->delete();
                        }
                        $commercial = new Commercial();
                        $commercial->file_id = $file_id;
                        $commercial->strata_id = $strata->id;
                        $commercial->unit_no = $commercial_unit_no;
                        $commercial->maintenance_fee = $commercial_maintenance_fee;
                        $commercial->maintenance_fee_option = $commercial_maintenance_fee_option;
                        $commercial->sinking_fund = $commercial_sinking_fund;
                        $commercial->sinking_fund_option = $commercial_sinking_fund_option;
                        $commercial->save();
                    } else {
                        if (count($commercial_old) > 0) {
                            $commercial_old->delete();
                        }
                    }
                }

                $facility = Facility::find($facility_id);
                if ($facility) {
                    $facility->management_office = $management_office;
                    $facility->management_office_unit = $management_office_unit;
                    $facility->swimming_pool = $swimming_pool;
                    $facility->swimming_pool_unit = $swimming_pool_unit;
                    $facility->surau = $surau;
                    $facility->surau_unit = $surau_unit;
                    $facility->multipurpose_hall = $multipurpose_hall;
                    $facility->multipurpose_hall_unit = $multipurpose_hall_unit;
                    $facility->gym = $gym;
                    $facility->gym_unit = $gym_unit;
                    $facility->playground = $playground;
                    $facility->playground_unit = $playground_unit;
                    $facility->guardhouse = $guardhouse;
                    $facility->guardhouse_unit = $guardhouse_unit;
                    $facility->kindergarten = $kindergarten;
                    $facility->kindergarten_unit = $kindergarten_unit;
                    $facility->open_space = $open_space;
                    $facility->open_space_unit = $open_space_unit;
                    $facility->lift = $lift;
                    $facility->lift_unit = $lift_unit;
                    $facility->rubbish_room = $rubbish_room;
                    $facility->rubbish_room_unit = $rubbish_room_unit;
                    $facility->gated = $gated;
                    $facility->gated_unit = $gated_unit;
                    $facility->others = $others;
                    $saved = $facility->save();

                    if ($saved) {
                        # Audit Trail
                        $file_name = Files::find($strata->file_id);
                        $remarks = 'Strata Info (' . $file_name->file_no . ') has been updated.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "COB File";
                        $auditTrail->remarks = $remarks;
                        $auditTrail->audit_by = Auth::user()->id;
                        $auditTrail->save();

                        return "true";
                    } else {
                        return "false";
                    }
                } else {
                    return "false";
                }
            } else {
                print "false";
            }
        } else {
            print "false";
        }
    }

    public function deleteStrataFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $strata = Strata::find($id);
            $strata->file_url = "";
            $deleted = $strata->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($strata->file_id);
                $remarks = 'Strata Info (' . $file_name->file_no . ') has been deleted.';
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

    public function viewManagement($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $management = Management::where('file_id', $file->id)->first();
        $management_jmb = ManagementJMB::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_mc = ManagementMC::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_agent = ManagementAgent::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_others = ManagementOthers::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $agent = Agent::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'file' => $file,
            'agent' => $agent,
            'management' => $management,
            'management_jmb' => $management_jmb,
            'management_mc' => $management_mc,
            'management_agent' => $management_agent,
            'management_others' => $management_others,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_management', $viewData);
    }

    public function management($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $management = Management::where('file_id', $file->id)->first();
        $management_jmb = ManagementJMB::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_mc = ManagementMC::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_agent = ManagementAgent::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_others = ManagementOthers::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $agent = Agent::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'file' => $file,
            'agent' => $agent,
            'management' => $management,
            'management_jmb' => $management_jmb,
            'management_mc' => $management_mc,
            'management_agent' => $management_agent,
            'management_others' => $management_others,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_management', $viewData);
    }

    public function submitUpdateManagement() {
        $data = Input::all();
        if (Request::ajax()) {

            //jmb
            $is_jmb = $data['is_jmb'];
            $jmb_date_formed = $data['jmb_date_formed'];
            $jmb_certificate_no = $data['jmb_certificate_no'];
            $jmb_name = $data['jmb_name'];
            $jmb_address1 = $data['jmb_address1'];
            $jmb_address2 = $data['jmb_address2'];
            $jmb_address3 = $data['jmb_address3'];
            $jmb_city = $data['jmb_city'];
            $jmb_poscode = $data['jmb_poscode'];
            $jmb_state = $data['jmb_state'];
            $jmb_country = $data['jmb_country'];
            $jmb_phone_no = $data['jmb_phone_no'];
            $jmb_fax_no = $data['jmb_fax_no'];
            $jmb_email = $data['jmb_email'];

            //mc
            $is_mc = $data['is_mc'];
            $mc_date_formed = $data['mc_date_formed'];
            $mc_certificate_no = $data['mc_certificate_no'];
            $mc_first_agm = $data['mc_first_agm'];
            $mc_name = $data['mc_name'];
            $mc_address1 = $data['mc_address1'];
            $mc_address2 = $data['mc_address2'];
            $mc_address3 = $data['mc_address3'];
            $mc_city = $data['mc_city'];
            $mc_poscode = $data['mc_poscode'];
            $mc_state = $data['mc_state'];
            $mc_country = $data['mc_country'];
            $mc_phone_no = $data['mc_phone_no'];
            $mc_fax_no = $data['mc_fax_no'];
            $mc_email = $data['mc_email'];

            //agent
            $is_agent = $data['is_agent'];
            $agent_selected_by = $data['agent_selected_by'];
            $agent_name = $data['agent_name'];
            $agent_address1 = $data['agent_address1'];
            $agent_address2 = $data['agent_address2'];
            $agent_address3 = $data['agent_address3'];
            $agent_city = $data['agent_city'];
            $agent_poscode = $data['agent_poscode'];
            $agent_state = $data['agent_state'];
            $agent_country = $data['agent_country'];
            $agent_phone_no = $data['agent_phone_no'];
            $agent_fax_no = $data['agent_fax_no'];
            $agent_email = $data['agent_email'];

            //others
            $is_others = $data['is_others'];
            $others_name = $data['others_name'];
            $others_address1 = $data['others_address1'];
            $others_address2 = $data['others_address2'];
            $others_address3 = $data['others_address3'];
            $others_city = $data['others_city'];
            $others_poscode = $data['others_poscode'];
            $others_state = $data['others_state'];
            $others_country = $data['others_country'];
            $others_phone_no = $data['others_phone_no'];
            $others_fax_no = $data['others_fax_no'];
            $others_email = $data['others_email'];

            //id
            $file_id = $data['file_id'];
            $management_id = $data['management_id'];

            $management = Management::find($management_id);
            $management->is_jmb = $is_jmb;
            $management->is_mc = $is_mc;
            $management->is_agent = $is_agent;
            $management->is_others = $is_others;
            $success1 = $management->save();

            if ($success1) {
                $jmb_old = ManagementJMB::where('file_id', $file_id)->where('management_id', $management->id)->first();
                $mc_old = ManagementMC::where('file_id', $file_id)->where('management_id', $management->id)->first();
                $agent_old = ManagementAgent::where('file_id', $file_id)->where('management_id', $management->id)->first();
                $others_old = ManagementOthers::where('file_id', $file_id)->where('management_id', $management->id)->first();

                if ($management->is_jmb == 1) {
                    if (count($jmb_old) > 0) {
                        $jmb_old->delete();
                    }
                    $new_jmb = new ManagementJMB();
                    $new_jmb->file_id = $file_id;
                    $new_jmb->management_id = $management->id;
                    $new_jmb->date_formed = $jmb_date_formed;
                    $new_jmb->certificate_no = $jmb_certificate_no;
                    $new_jmb->name = $jmb_name;
                    $new_jmb->address1 = $jmb_address1;
                    $new_jmb->address2 = $jmb_address2;
                    $new_jmb->address3 = $jmb_address3;
                    $new_jmb->city = $jmb_city;
                    $new_jmb->poscode = $jmb_poscode;
                    $new_jmb->state = $jmb_state;
                    $new_jmb->country = $jmb_country;
                    $new_jmb->phone_no = $jmb_phone_no;
                    $new_jmb->fax_no = $jmb_fax_no;
                    $new_jmb->email = $jmb_email;
                    $new_jmb->save();
                } else {
                    if (count($jmb_old) > 0) {
                        $jmb_old->delete();
                    }
                }

                if ($management->is_mc == 1) {
                    if (count($mc_old) > 0) {
                        $mc_old->delete();
                    }
                    $new_mc = new ManagementMC();
                    $new_mc->file_id = $file_id;
                    $new_mc->management_id = $management->id;
                    $new_mc->date_formed = $mc_date_formed;
                    $new_mc->certificate_no = $mc_certificate_no;
                    $new_mc->first_agm = $mc_first_agm;
                    $new_mc->name = $mc_name;
                    $new_mc->address1 = $mc_address1;
                    $new_mc->address2 = $mc_address2;
                    $new_mc->address3 = $mc_address3;
                    $new_mc->city = $mc_city;
                    $new_mc->poscode = $mc_poscode;
                    $new_mc->state = $mc_state;
                    $new_mc->country = $mc_country;
                    $new_mc->phone_no = $mc_phone_no;
                    $new_mc->fax_no = $mc_fax_no;
                    $new_mc->email = $mc_email;
                    $new_mc->save();
                } else {
                    if (count($mc_old) > 0) {
                        $mc_old->delete();
                    }
                }

                if ($management->is_agent == 1) {
                    if (count($agent_old) > 0) {
                        $agent_old->delete();
                    }
                    $new_agent = new ManagementAgent();
                    $new_agent->file_id = $file_id;
                    $new_agent->management_id = $management->id;
                    $new_agent->selected_by = $agent_selected_by;
                    $new_agent->agent = $agent_name;
                    $new_agent->address1 = $agent_address1;
                    $new_agent->address2 = $agent_address2;
                    $new_agent->address3 = $agent_address3;
                    $new_agent->city = $agent_city;
                    $new_agent->poscode = $agent_poscode;
                    $new_agent->state = $agent_state;
                    $new_agent->country = $agent_country;
                    $new_agent->phone_no = $agent_phone_no;
                    $new_agent->fax_no = $agent_fax_no;
                    $new_agent->email = $agent_email;
                    $new_agent->save();
                } else {
                    if (count($agent_old) > 0) {
                        $agent_old->delete();
                    }
                }

                if ($management->is_others == 1) {
                    if (count($others_old) > 0) {
                        $others_old->delete();
                    }
                    $new_others = new ManagementOthers();
                    $new_others->file_id = $file_id;
                    $new_others->management_id = $management->id;
                    $new_others->name = $others_name;
                    $new_others->address1 = $others_address1;
                    $new_others->address2 = $others_address2;
                    $new_others->address3 = $others_address3;
                    $new_others->city = $others_city;
                    $new_others->poscode = $others_poscode;
                    $new_others->state = $others_state;
                    $new_others->country = $others_country;
                    $new_others->phone_no = $others_phone_no;
                    $new_others->fax_no = $others_fax_no;
                    $new_others->email = $others_email;
                    $new_others->save();
                } else {
                    if (count($others_old) > 0) {
                        $others_old->delete();
                    }
                }
                # Audit Trail
                $file_name = Files::find($management->file_id);
                $remarks = 'Management Info (' . $file_name->file_no . ') has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                return "true";
            } else {
                print "false";
            }
        }
    }

    public function viewMonitoring($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find($id);
        $monitoring = Monitoring::where('file_id', $files->id)->first();
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $image = OtherDetails::where('file_id', $files->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'file' => $files,
            'designation' => $designation,
            'monitoring' => $monitoring,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_monitoring', $viewData);
    }

    public function monitoring($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $monitoring = Monitoring::where('file_id', $file->id)->first();
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'file' => $file,
            'designation' => $designation,
            'monitoring' => $monitoring,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_monitoring', $viewData);
    }

    public function submitUpdateMonitoring() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $precalculate_plan = $data['precalculate_plan'];
            $buyer_registration = $data['buyer_registration'];
            $certificate_series_no = $data['certificate_series_no'];
            $monitoring_remarks = $data['monitoring_remarks'];

            $monitor = Monitoring::find($id);
            $monitor->pre_calculate = $precalculate_plan;
            $monitor->buyer_registration = $buyer_registration;
            $monitor->certificate_no = $certificate_series_no;
            $monitor->remarks = $monitoring_remarks;
            $success = $monitor->save();

            if ($success) {
                # Audit Trail
                $file_name = Files::find($monitor->file_id);
                $remarks = 'Monitoring Info (' . $file_name->file_no . ') has been updated.';
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

    public function addAGMDetails() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $agm_date = $data['agm_date'];
            $agm = $data['agm'];
            $egm = $data['egm'];
            $minit_meeting = $data['minit_meeting'];
            $jmc_copy = $data['jmc_copy'];
            $ic_list = $data['ic_list'];
            $attendance_list = $data['attendance_list'];
            $audited_financial_report = $data['audited_financial_report'];
            $audit_report = $data['audit_report'];
            $audit_start = $data['audit_start'];
            $audit_end = $data['audit_end'];
            $audit_report_file_url = $data['audit_report_file_url'];
            $letter_integrity_url = $data['letter_integrity_url'];
            $letter_bankruptcy_url = $data['letter_bankruptcy_url'];

            $agm_detail = new MeetingDocument();
            $agm_detail->file_id = $file_id;
            $agm_detail->agm_date = $agm_date;
            $agm_detail->agm = $agm;
            $agm_detail->egm = $egm;
            $agm_detail->minit_meeting = $minit_meeting;
            $agm_detail->jmc_spa = $jmc_copy;
            $agm_detail->identity_card = $ic_list;
            $agm_detail->attendance = $attendance_list;
            $agm_detail->financial_report = $audited_financial_report;
            $agm_detail->audit_report = $audit_report;
            $agm_detail->audit_start_date = $audit_start;
            $agm_detail->audit_end_date = $audit_end;
            $agm_detail->audit_report_url = $audit_report_file_url;
            $agm_detail->letter_integrity_url = $letter_integrity_url;
            $agm_detail->letter_bankruptcy_url = $letter_bankruptcy_url;
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

    public function editAGMDetails() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];
            $agm_date = $data['agm_date'];
            $agm = $data['agm'];
            $egm = $data['egm'];
            $minit_meeting = $data['minit_meeting'];
            $jmc_copy = $data['jmc_copy'];
            $ic_list = $data['ic_list'];
            $attendance_list = $data['attendance_list'];
            $audited_financial_report = $data['audited_financial_report'];
            $audit_report = $data['audit_report'];
            $audit_start = $data['audit_start'];
            $audit_end = $data['audit_end'];
            $audit_report_file_url = $data['audit_report_file_url'];
            $letter_integrity_url = $data['letter_integrity_url'];
            $letter_bankruptcy_url = $data['letter_bankruptcy_url'];


            $agm_detail = MeetingDocument::find($id);
            $agm_detail->agm_date = $agm_date;
            $agm_detail->agm = $agm;
            $agm_detail->egm = $egm;
            $agm_detail->minit_meeting = $minit_meeting;
            $agm_detail->jmc_spa = $jmc_copy;
            $agm_detail->identity_card = $ic_list;
            $agm_detail->attendance = $attendance_list;
            $agm_detail->financial_report = $audited_financial_report;
            $agm_detail->audit_report = $audit_report;
            $agm_detail->audit_start_date = $audit_start;
            $agm_detail->audit_end_date = $audit_end;
            $agm_detail->audit_report_url = $audit_report_file_url;
            $agm_detail->letter_integrity_url = $letter_integrity_url;
            $agm_detail->letter_bankruptcy_url = $letter_bankruptcy_url;
            $success = $agm_detail->save();

            if ($success) {
                # Audit Trail
                $file_name = Files::find($agm_detail->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_detail->agm_date)) . ' has been updated.';
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

    public function getAGMDetails() {
        $data = Input::all();
        if (Request::ajax()) {

            $result = "";
            $id = $data['id'];

            $agm = MeetingDocument::find($id);

            if (count($agm) > 0) {
                $result .= '<form>';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.annual_general_meeting') . '</label></div>';
                if ($agm->agm == 1) {
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.extra_general_meeting') . '</label></div>';
                if ($agm->egm == 1) {
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.meeting_minutes') . '</label></div>';
                if ($agm->minit_meeting == 1) {
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.jmc_spa_copy') . '</label></div>';
                if ($agm->jmc_spa == 1) {
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="1" checked>' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="0">' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="1">' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="0" checked>' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.identity_card_list') . '</label></div>';
                if ($agm->identity_card == 1) {
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';


                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.attendance_list') . '</label></div>';
                if ($agm->attendance == 1) {
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="1" checked> ' . trans('app.forms.yes') . ' </div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="0"> ' . trans('app.forms.no') . ' </div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="1"> ' . trans('app.forms.yes') . ' </div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="0" checked> ' . trans('app.forms.no') . ' </div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.audited_financial_report') . '</label></div>';
                if ($agm->financial_report == 1) {
                    $result .= '<div class="col-md-2"><input type="radio" id="audited_financial_report_edit" name="audited_financial_report_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="audited_financial_report_edit" name="audited_financial_report_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="audited_financial_report_edit" name="audited_financial_report_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="audited_financial_report_edit" name="audited_financial_report_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.financial_audit_report') . '</label></div>';
                $result .= '<div class="col-md-6"><input type="text" class="form-control" placeholder="' . trans('app.forms.financial_audit_report') . '" id="audit_report_edit" value=' . "$agm->audit_report" . '></div>';
                $result .= '</div>';

                $result .= '</form>';

                $result .= '<form id="upload_audit_report_file_edit" enctype="multipart/form-data" method="post" action="' . url("uploadAuditReportFileEdit") . '" autocomplete="off">';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">&nbsp;</label></div>';
                $result .= '<div class="col-md-6">';
                $result .= '<button type="button" id="clear_audit_report_file_edit" class="btn btn-xs btn-danger" onclick="clearAuditFileEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result .= '<input type="file" name="audit_report_file_edit" id="audit_report_file_edit">';
                $result .= '<div id="validation-errors_audit_report_file_edit"></div><div id="view_audit_report_file_edit"></div>';
                if ($agm->audit_report_url != "") {
                    $result .= '<div id="report_edit"><a href="' . asset($agm->audit_report_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAuditReport(\'' . $agm->id . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result .= '</div>';
                $result .= '</div>';
                $result .= '</form>';

                $result .= '<form id="upload_letter_integrity_edit" enctype="multipart/form-data" method="post" action="' . url("uploadLetterIntegrityEdit") . '" autocomplete="off">';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.pledge_letter_of_integrity') . '</label></div>';
                $result .= '<div class="col-md-6">';
                $result .= '<button type="button" id="clear_letter_integrity_edit" class="btn btn-xs btn-danger" onclick="clearLetterIntegrityEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result .= '<input type="file" name="letter_integrity_edit" id="letter_integrity_edit">';
                $result .= '<div id="validation-errors_letter_integrity_edit"></div>';
                if ($agm->letter_integrity_url != "") {
                    $result .= '<div id="integrity_edit"><a href="' . asset($agm->letter_integrity_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterIntegrity(\'' . $agm->id . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result .= '</div>';
                $result .= '</div>';
                $result .= '</form>';

                $result .= '<form id="upload_letter_bankruptcy_edit" enctype="multipart/form-data" method="post" action="' . url("uploadLetterBankruptcyEdit") . '" autocomplete="off">';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.declaration_letter_of_non_bankruptcy') . '</label></div>';
                $result .= '<div class="col-md-6">';
                $result .= '<button type="button" id="clear_letter_bankruptcy_edit" class="btn btn-xs btn-danger" onclick="clearLetterBankruptcyEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result .= '<input type="file" name="letter_bankruptcy_edit" id="letter_bankruptcy_edit">';
                $result .= '<div id="validation-errors_letter_bankruptcy_edit"></div>';
                if ($agm->letter_bankruptcy_url != "") {
                    $result .= '<div id="bankruptcy_edit"><a href="' . asset($agm->letter_bankruptcy_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterBankruptcy(\'' . $agm->id . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result .= '</div>';
                $result .= '</div>';
                $result .= '</form>';
            } else {
                $result = trans('app.errors.no_data_found');
            }
            print $result;
        }
    }

    public function getAGM($file_id) {
        $agm_detail = MeetingDocument::where('file_id', $file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($agm_detail) > 0) {
            $data = Array();
            foreach ($agm_detail as $agm_details) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit" onclick="getAGMDetails(\'' . $agm_details->id . '\')"
                            data-agm_id="' . $agm_details->id . '" data-agm_date="' . $agm_details->agm_date . '"
                            data-audit_start_date="' . $agm_details->audit_start_date . '" data-audit_end_date="' . $agm_details->audit_end_date . '"
                            data-audit_report_file_url="' . $agm_details->audit_report_url . '" data-letter_integrity_url="' . $agm_details->letter_integrity_url . '" data-letter_bankruptcy_url="' . $agm_details->letter_bankruptcy_url . '">
                                <i class="fa fa-pencil"></i>
                            </button>
                            &nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAGMDetails(\'' . $agm_details->id . '\')">
                                <i class="fa fa-trash""></i>
                            </button>';

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
                    $status1 = '';
                } else {
                    $status1 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->egm == 0 || $agm_details->egm == "") {
                    $status2 = '';
                } else {
                    $status2 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->minit_meeting == 0 || $agm_details->minit_meeting == "") {
                    $status3 = '';
                } else {
                    $status3 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->letter_integrity_url == "") {
                    $status4 = '';
                } else {
                    $status4 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->letter_bankruptcy_url == "") {
                    $status5 = '';
                } else {
                    $status5 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->jmc_spa == 0 || $agm_details->jmc_spa == "") {
                    $status6 = '';
                } else {
                    $status6 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->identity_card == 0 || $agm_details->identity_card == "") {
                    $status7 = '';
                } else {
                    $status7 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->attendance == 0 || $agm_details->attendance == "") {
                    $status8 = '';
                } else {
                    $status8 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->financial_report == 0 || $agm_details->financial_report == "") {
                    $status9 = '';
                } else {
                    $status9 = '<i class="icmn-checkmark4"></i>';
                }
                if ($agm_details->audit_report_url == "") {
                    $status10 = '';
                } else {
                    $status10 = '<i class="icmn-checkmark4"></i>';
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

    public function deleteAGMDetails() {
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

    public function deleteAuditReport() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->audit_report_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteLetterIntegrity() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->letter_integrity_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteLetterBankruptcy() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->letter_bankruptcy_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteAGMFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->agm_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteEGMFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->egm_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteMinutesMeetingFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->minutes_meeting_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteJMCFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->jmc_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteICFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->ic_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteAttendanceFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->attendance_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function deleteAuditedFinancialFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $agm_details = MeetingDocument::find($id);
            $agm_details->audited_financial_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' has been updated.';
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

    public function addAJKDetails() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $designation = $data['ajk_designation'];
            $name = $data['ajk_name'];
            $phone_no = $data['ajk_phone_no'];
            $year = $data['ajk_year'];

            $ajk_detail = new AJKDetails();
            $ajk_detail->file_id = $file_id;
            $ajk_detail->designation = $designation;
            $ajk_detail->name = $name;
            $ajk_detail->phone_no = $phone_no;
            $ajk_detail->year = $year;
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

    public function editAJKDetails() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['ajk_id_edit'];
            $designation = $data['ajk_designation'];
            $name = $data['ajk_name'];
            $phone_no = $data['ajk_phone_no'];
            $year = $data['ajk_year'];

            $ajk_detail = AJKDetails::find($id);
            $ajk_detail->designation = $designation;
            $ajk_detail->name = $name;
            $ajk_detail->phone_no = $phone_no;
            $ajk_detail->year = $year;
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
        }
    }

    public function getAJK($file_id) {
        $ajk_detail = AJKDetails::where('file_id', $file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($ajk_detail) > 0) {
            $data = Array();
            foreach ($ajk_detail as $ajk_details) {
                $designation = Designation::find($ajk_details->designation);

                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit" data-toggle="modal" data-target="#edit_ajk_details"
                            data-ajk_id="' . $ajk_details->id . '" data-designation="' . $ajk_details->designation . '" data-name="' . $ajk_details->name . '" data-phone_no="' . $ajk_details->phone_no . '" data-year="' . $ajk_details->year . '">
                                <i class="fa fa-pencil"></i>
                            </button>
                            &nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . $ajk_details->id . '\')">
                                <i class="fa fa-trash"></i>
                            </button>
                            &nbsp';


                $data_raw = array(
                    $designation->description,
                    $ajk_details->name,
                    $ajk_details->phone_no,
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

    public function deleteAJKDetails() {
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

    public function viewOthers($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find($id);
        $other_details = OtherDetails::where('file_id', $files->id)->first();
        $image = OtherDetails::where('file_id', $files->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'file' => $files,
            'other_details' => $other_details,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_others', $viewData);
    }

    public function others($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $other_details = OtherDetails::where('file_id', $file->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'file' => $file,
            'other_details' => $other_details,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_others', $viewData);
    }

    public function submitUpdateOtherDetails() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $file_id = $data['file_id'];
            $other_details_name = $data['other_details_name'];
            $others_image_url = $data['others_image_url'];
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];
            $other_details_description = $data['other_details_description'];
            $pms_system = $data['pms_system'];
            $owner_occupied = $data['owner_occupied'];
            $rented = $data['rented'];
            $bantuan_lphs = $data['bantuan_lphs'];
            $bantuan_others = $data['bantuan_others'];
            $rsku = $data['rsku'];
            $water_meter = $data['water_meter'];
            $malay_composition = $data['malay_composition'];
            $chinese_composition = $data['chinese_composition'];
            $indian_composition = $data['indian_composition'];
            $others_composition = $data['others_composition'];
            $foreigner_composition = $data['foreigner_composition'];

            if (!empty($id)) {
                $others = OtherDetails::find($id);
            } else {
                $others = new OtherDetails();
                $others->file_id = $file_id;
            }

            if ($others) {
                $others->name = $other_details_name;
                $others->image_url = $others_image_url;
                $others->latitude = $latitude;
                $others->longitude = $longitude;
                $others->description = $other_details_description;
                $others->pms_system = $pms_system;
                $others->owner_occupied = $owner_occupied;
                $others->rented = $rented;
                $others->bantuan_lphs = $bantuan_lphs;
                $others->bantuan_others = $bantuan_others;
                $others->rsku = $rsku;
                $others->water_meter = $water_meter;
                $others->malay_composition = $malay_composition;
                $others->chinese_composition = $chinese_composition;
                $others->indian_composition = $indian_composition;
                $others->others_composition = $others_composition;
                $others->foreigner_composition = $foreigner_composition;
                $success = $others->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($others->file_id);
                    $remarks = 'Others Info (' . $file_name->file_no . ') has been updated.';
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

    public function submitAddHousingScheme() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $housing_scheme = $data['housing_scheme'];

            if (!empty($file_id)) {
                $check_exist = HousingSchemeUser::where('file_id', $file_id)->where('user_id', $housing_scheme)->where('is_deleted', 0)->count();
                if ($check_exist > 0) {
                    print "data_exist";
                } else {
                    $hs_user = new HousingSchemeUser();
                    $hs_user->file_id = $file_id;
                    $hs_user->user_id = $housing_scheme;
                    $hs_user->is_deleted = 0;
                    $success = $hs_user->save();

                    if ($success) {
                        print "true";
                    } else {
                        print "false";
                    }
                }
            } else {
                print "false";
            }
        }
    }

    public function getHousingScheme($file_id) {
        $user = HousingSchemeUser::where('file_id', $file_id)->where('is_deleted', 0)->get();

        if (count($user) > 0) {
            $data = Array();
            foreach ($user as $users) {
                $hs_user = User::find($users->user_id);

                if ($hs_user) {
                    $button = "";
                    $button .= '<button class="btn btn-xs btn-danger" onclick="deleteHousingScheme(\'' . $users->id . '\')" title="Delete"><i class="fa fa-trash"></i></button>';

                    $data_raw = array(
                        $hs_user->full_name,
                        $hs_user->phone_no,
                        $hs_user->email,
                        $button
                    );

                    array_push($data, $data_raw);
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

    public function deleteHousingScheme() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $hs_user = HousingSchemeUser::find($id);

            if ($hs_user) {
                $hs_user->is_deleted = 1;
                $success = $hs_user->save();

                if ($success) {
                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function deleteImageOthers() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $others = OtherDetails::find($id);
            $others->image_url = "";
            $deleted = $others->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($others->file_id);
                $remarks = 'Others Info (' . $file_name->file_no . ') has been updated.';
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

    public function viewScoring($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find($id);
        $image = OtherDetails::where('file_id', $files->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $files,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_scoring', $viewData);
    }

    public function scoring($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_scoring', $viewData);
    }

    public function addScoring() {
        $data = Input::all();
        if (Request::ajax()) {

            $file_id = $data['file_id'];
            $survey = $data['survey'];
            $date = $data['date'];
            $score1 = $data['score1'];
            $score2 = $data['score2'];
            $score3 = $data['score3'];
            $score4 = $data['score4'];
            $score5 = $data['score5'];
            $score6 = $data['score6'];
            $score7 = $data['score7'];
            $score8 = $data['score8'];
            $score9 = $data['score9'];
            $score10 = $data['score10'];
            $score11 = $data['score11'];
            $score12 = $data['score12'];
            $score13 = $data['score13'];
            $score14 = $data['score14'];
            $score15 = $data['score15'];
            $score16 = $data['score16'];
            $score17 = $data['score17'];
            $score18 = $data['score18'];
            $score19 = $data['score19'];
            $score20 = $data['score20'];
            $score21 = $data['score21'];

            $scorings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
            $scorings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
            $scorings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
            $scorings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
            $scorings_E = ((($score19 + $score20 + $score21) / 12) * 10);

            $total_score = $scorings_A + $scorings_B + $scorings_C + $scorings_D + $scorings_E;

            $scoring = new Scoring();
            $scoring->file_id = $file_id;
            $scoring->survey = $survey;
            $scoring->date = $date;
            $scoring->score1 = $score1;
            $scoring->score2 = $score2;
            $scoring->score3 = $score3;
            $scoring->score4 = $score4;
            $scoring->score5 = $score5;
            $scoring->score6 = $score6;
            $scoring->score7 = $score7;
            $scoring->score8 = $score8;
            $scoring->score9 = $score9;
            $scoring->score10 = $score10;
            $scoring->score11 = $score11;
            $scoring->score12 = $score12;
            $scoring->score13 = $score13;
            $scoring->score14 = $score14;
            $scoring->score15 = $score15;
            $scoring->score16 = $score16;
            $scoring->score17 = $score17;
            $scoring->score18 = $score18;
            $scoring->score19 = $score19;
            $scoring->score20 = $score20;
            $scoring->score21 = $score21;
            $scoring->total_score = $total_score;
            $success = $scoring->save();

            if ($success) {
                # Audit Trail
                $file_name = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $file_name->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->created_at)) . ' has been inserted.';
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

    public function editScoring() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];
            $date = $data['date'];
            $score1 = $data['score1'];
            $score2 = $data['score2'];
            $score3 = $data['score3'];
            $score4 = $data['score4'];
            $score5 = $data['score5'];
            $score6 = $data['score6'];
            $score7 = $data['score7'];
            $score8 = $data['score8'];
            $score9 = $data['score9'];
            $score10 = $data['score10'];
            $score11 = $data['score11'];
            $score12 = $data['score12'];
            $score13 = $data['score13'];
            $score14 = $data['score14'];
            $score15 = $data['score15'];
            $score16 = $data['score16'];
            $score17 = $data['score17'];
            $score18 = $data['score18'];
            $score19 = $data['score19'];
            $score20 = $data['score20'];
            $score21 = $data['score21'];

            $scorings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
            $scorings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
            $scorings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
            $scorings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
            $scorings_E = ((($score19 + $score20 + $score21) / 12) * 10);

            $total_score = $scorings_A + $scorings_B + $scorings_C + $scorings_D + $scorings_E;

            $scoring = Scoring::find($id);
            if ($scoring) {
                $scoring->date = $date;
                $scoring->score1 = $score1;
                $scoring->score2 = $score2;
                $scoring->score3 = $score3;
                $scoring->score4 = $score4;
                $scoring->score5 = $score5;
                $scoring->score6 = $score6;
                $scoring->score7 = $score7;
                $scoring->score8 = $score8;
                $scoring->score9 = $score9;
                $scoring->score10 = $score10;
                $scoring->score11 = $score11;
                $scoring->score12 = $score12;
                $scoring->score13 = $score13;
                $scoring->score14 = $score14;
                $scoring->score15 = $score15;
                $scoring->score16 = $score16;
                $scoring->score17 = $score17;
                $scoring->score18 = $score18;
                $scoring->score19 = $score19;
                $scoring->score20 = $score20;
                $scoring->score21 = $score21;
                $scoring->total_score = $total_score;
                $success = $scoring->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($scoring->file_id);
                    $remarks = 'COB Rating (' . $file_name->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->created_at)) . ' has been updated.';
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

    public function getScoring($id) {
        $scoring = Scoring::where('file_id', $id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($scoring) > 0) {
            $data = Array();
            foreach ($scoring as $scorings) {
                $button = "";

                $button .= '<button type="button" class="btn btn-xs btn-success edit_survey" title="Edit" onclick="editSurveyForm(\'' . $scorings->survey . '\')"'
                        . 'data-date="' . (!empty($scorings->date) ? $scorings->date : '') . '" data-score1="' . $scorings->score1 . '" data-score2="' . $scorings->score2 . '" data-score3="' . $scorings->score3 . '"'
                        . 'data-score4="' . $scorings->score4 . '" data-score5="' . $scorings->score5 . '" data-score6="' . $scorings->score6 . '"'
                        . 'data-score7="' . $scorings->score7 . '" data-score8="' . $scorings->score8 . '" data-score9="' . $scorings->score9 . '"'
                        . 'data-score10="' . $scorings->score10 . '" data-score11="' . $scorings->score11 . '" data-score12="' . $scorings->score12 . '"'
                        . 'data-score13="' . $scorings->score13 . '" data-score14="' . $scorings->score14 . '" data-score15="' . $scorings->score15 . '"'
                        . 'data-score16="' . $scorings->score16 . '" data-score17="' . $scorings->score17 . '" data-score18="' . $scorings->score18 . '"'
                        . 'data-score19="' . $scorings->score19 . '" data-score20="' . $scorings->score20 . '" data-score21="' . $scorings->score21 . '"'
                        . 'data-id="' . $scorings->id . '"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="deleteScoring(\'' . $scorings->id . '\')"><i class="fa fa-trash"></i></button>';

                $scorings_A = ((($scorings->score1 + $scorings->score2 + $scorings->score3 + $scorings->score4 + $scorings->score5) / 20) * 25);
                $scorings_B = ((($scorings->score6 + $scorings->score7 + $scorings->score8 + $scorings->score9 + $scorings->score10) / 20) * 25);
                $scorings_C = ((($scorings->score11 + $scorings->score12 + $scorings->score13 + $scorings->score14) / 16) * 20);
                $scorings_D = ((($scorings->score15 + $scorings->score16 + $scorings->score17 + $scorings->score18) / 16) * 20);
                $scorings_E = ((($scorings->score19 + $scorings->score20 + $scorings->score21) / 12) * 10);

                if ($scorings->total_score >= 81) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '</span>';
                } else if ($scorings->total_score >= 61) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else if ($scorings->total_score >= 41) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else if ($scorings->total_score >= 21) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else if ($scorings->total_score >= 1) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                }

                $data_raw = array(
                    (!empty($scorings->date) ? date('d-M-Y', strtotime($scorings->date)) : '(not set)'),
                    number_format($scorings_A, 2),
                    number_format($scorings_B, 2),
                    number_format($scorings_C, 2),
                    number_format($scorings_D, 2),
                    number_format($scorings_E, 2),
                    number_format($scorings->total_score, 2),
                    $rating,
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

    public function deleteScoring() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $scoring = Scoring::find($id);
            $scoring->is_deleted = 1;
            $deleted = $scoring->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $file_name->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->created_at)) . ' has been deleted.';
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

    public function viewBuyer($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find($id);
        $image = OtherDetails::where('file_id', $files->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $files,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_buyer', $viewData);
    }

    public function buyer($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_buyer', $viewData);
    }

    public function addBuyer($id) {
        $file = Files::find($id);
        $image = OtherDetails::where('file_id', $file->id)->first();
        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'files' => $file,
            'race' => $race,
            'nationality' => $nationality,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.add_buyer', $viewData);
    }

    public function submitBuyer() {
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

    public function editBuyer($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $buyer = Buyer::find($id);
        $files = Files::find($buyer->file_id);
        $image = OtherDetails::where('file_id', $files->id)->first();
        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $files,
            'race' => $race,
            'nationality' => $nationality,
            'buyer' => $buyer,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.edit_buyer', $viewData);
    }

    public function submitEditBuyer() {
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

    public function getBuyerList($file_id) {
        $buyer_list = Buyer::where('file_id', $file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($buyer_list) > 0) {
            $data = Array();
            $no = 1;
            foreach ($buyer_list as $buyer_lists) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AdminController@editBuyer', $buyer_lists->id) . '\'">
                                <i class="fa fa-pencil"></i>
                            </button>
                            &nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteBuyer(\'' . $buyer_lists->id . '\')">
                                <i class="fa fa-trash"></i>
                            </button>
                            &nbsp';


                $data_raw = array(
                    $no++,
                    $buyer_lists->unit_no,
                    $buyer_lists->unit_share,
                    $buyer_lists->owner_name,
                    $buyer_lists->ic_company_no,
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

    public function deleteBuyer() {
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

    public function importBuyer($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'Uploadmessage' => '',
            'upload' => "true",
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.import_buyer', $viewData);
    }

    public function submitUploadBuyer($id) {
        $data = Input::all();
        if (Request::ajax()) {

            $files = Files::find($id);

            if ($files) {
                $getAllBuyer = $data['getAllBuyer'];

                foreach ($getAllBuyer as $buyerList) {

                    $check_file_id = Files::where('file_no', $buyerList[0])->where('id', $files->id)->first();
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
        } else {
            print "false";
        }
    }

    //document
    public function document($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::find($id);
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'documentType' => $documentType,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_document', $viewData);
    }

    public function getDocument($id) {
        $files = Files::find($id);
        $document = Document::where('file_id', $files->id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
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

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@editDocument', $documents->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDocument(\'' . $documents->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
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

    public function addDocument($id) {
        $file = Files::find($id);
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'documentType' => $documentType,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.add_document', $viewData);
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
                $file_name = Files::find($document->file_id);
                $remarks = 'COB Document (' . $file_name->file_no . ') has been inserted.';
                $remarks = $document->id . ' has been updated.';
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

    public function editDocument($id) {
        $file = Files::find($id);
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $document = Document::find($id);
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->get();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'document' => $document,
            'documentType' => $documentType,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.edit_document', $viewData);
    }

    public function submitEditDocument() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $document = Document::find($id);
            if ($document) {
                $document->document_type_id = $data['document_type'];
                $document->name = $data['name'];
                $document->remarks = $data['remarks'];
                $document->is_hidden = $data['is_hidden'];
                $document->is_readonly = $data['is_readonly'];
                $document->file_url = $data['document_url'];
                $success = $document->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($document->file_id);
                    $remarks = 'COB Document (' . $file_name->file_no . ') has been updated.';
                    $remarks = $document->id . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "COB File";
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

    public function fileApproval($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find($id);
        if ($files->status == 1) {
            $status = trans('app.forms.approved');
        } else if ($files->status == 2) {
            $status = trans('app.forms.rejected');
        } else {
            $status = trans('app.forms.pending');
        }
        $approveBy = User::find($files->approved_by);
        $image = OtherDetails::where('file_id', $files->id)->first();

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $files,
            'status' => $status,
            'approveBy' => $approveBy,
            'Uploadmessage' => '',
            'upload' => "true",
            'role' => Auth::user()->role,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.file_approval', $viewData);
    }

    public function submitFileApproval() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $status = $data['approval_status'];
            $remarks = $data['approval_remarks'];

            if ($status == 1) {
                $is_active = 1;
            } else {
                $is_active = 0;
            }

            $files = Files::find($id);
            if (count($files) > 0) {
                $files->is_active = $is_active;
                $files->status = $status;
                $files->remarks = $remarks;
                $files->approved_by = Auth::user()->id;
                $files->approved_at = date('Y-m-d H:i:s');
                $success = $files->save();

                if ($success) {
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

    // --- Administrator --- //
    public function company() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.administration.organization_profile'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'profile_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('admin_en.company', $viewData);
    }

    public function getCompany() {
        if (!Auth::user()->getAdmin()) {
            $company = Company::where('id', Auth::user()->company_id)->where('is_deleted', 0)->get();
        } else {
            $company = Company::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        }

        if (count($company) > 0) {
            $data = Array();
            foreach ($company as $companies) {
                $button = "";
                if ($companies->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveCompany(\'' . $companies->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeCompany(\'' . $companies->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

                $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="window.location=\'' . URL::action('AdminController@editCompany', $companies->id) . '\'">' . trans('app.forms.edit') . ' <i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteCompany(\'' . $companies->id . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $companies->name,
                    $companies->short_name,
                    $companies->email,
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

    public function inactiveCompany() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $company = Company::find($id);
            $company->is_active = 0;
            $updated = $company->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Company: ' . $company->description . ' has been updated.';
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

    public function activeCompany() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $company = Company::find($id);
            $company->is_active = 1;
            $updated = $company->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Company: ' . $company->description . ' has been updated.';
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

    public function deleteCompany() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $company = Company::find($id);
            $company->is_deleted = 1;
            $deleted = $company->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Company: ' . $company->description . ' has been deleted.';
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

    public function addCompany() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.administration.add_organization_profile'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'profile_list',
            'user_permission' => $user_permission,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'image' => ""
        );

        return View::make('admin_en.add_company', $viewData);
    }

    public function submitAddCompany() {
        $data = Input::all();
        if (Request::ajax()) {
            $name = $data['name'];
            $short_name = $data['short_name'];
            $rob_roc_no = $data['rob_roc_no'];
            $address1 = $data['address1'];
            $address2 = $data['address2'];
            $address3 = $data['address3'];
            $city = $data['city'];
            $poscode = $data['poscode'];
            $state = $data['state'];
            $country = $data['country'];
            $phone_no = $data['phone_no'];
            $fax_no = $data['fax_no'];
            $email = $data['email'];
            $image_url = $data['image_url'];
            $nav_image_url = $data['nav_image_url'];

            $company = new Company();
            $company->name = $name;
            $company->short_name = $short_name;
            $company->rob_roc_no = $rob_roc_no;
            $company->address1 = $address1;
            $company->address2 = $address2;
            $company->address3 = $address3;
            $company->city = $city;
            $company->poscode = $poscode;
            $company->state = $state;
            $company->country = $country;
            $company->phone_no = $phone_no;
            $company->fax_no = $fax_no;
            $company->email = $email;
            $company->image_url = $image_url;
            $company->nav_image_url = $nav_image_url;
            $success = $company->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Organization Profile has been added.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function editCompany($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $company = Company::find($id);
        if ($company) {
            $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

            $viewData = array(
                'title' => trans('app.menus.administration.edit_organization_profile'),
                'panel_nav_active' => 'admin_panel',
                'main_nav_active' => 'admin_main',
                'sub_nav_active' => 'profile_list',
                'user_permission' => $user_permission,
                'company' => $company,
                'city' => $city,
                'country' => $country,
                'state' => $state,
                'image' => ""
            );

            return View::make('admin_en.edit_company', $viewData);
        }
    }

    public function submitEditCompany() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];
            $name = $data['name'];
            $short_name = $data['short_name'];
            $rob_roc_no = $data['rob_roc_no'];
            $address1 = $data['address1'];
            $address2 = $data['address2'];
            $address3 = $data['address3'];
            $city = $data['city'];
            $poscode = $data['poscode'];
            $state = $data['state'];
            $country = $data['country'];
            $phone_no = $data['phone_no'];
            $fax_no = $data['fax_no'];
            $email = $data['email'];
            $image_url = $data['image_url'];
            $nav_image_url = $data['nav_image_url'];

            $company = Company::find($id);
            if (count($company) > 0) {
                $company->name = $name;
                $company->short_name = $short_name;
                $company->rob_roc_no = $rob_roc_no;
                $company->address1 = $address1;
                $company->address2 = $address2;
                $company->address3 = $address3;
                $company->city = $city;
                $company->poscode = $poscode;
                $company->state = $state;
                $company->country = $country;
                $company->phone_no = $phone_no;
                $company->fax_no = $fax_no;
                $company->email = $email;
                $company->image_url = $image_url;
                $company->nav_image_url = $nav_image_url;
                $success = $company->save();

                if ($success) {
                    # Audit Trail
                    $remarks = 'Organization Profile has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "System Administration";
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

    //Access Group
    public function accessGroups() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.administration.access_group_management'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'access_group_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('page_en.accessgroup', $viewData);
    }

    public function addAccessGroup() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $module = Module::get();

        $viewData = array(
            'title' => trans('app.buttons.add_access_group'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'access_group_list',
            'user_permission' => $user_permission,
            'module' => $module,
            'image' => ""
        );

        return View::make('page_en.add_accessgroup', $viewData);
    }

    public function submitAccessGroup() {

        $data = Input::all();
        if (Request::ajax()) {

            $description = $data['description'];
            $is_admin = $data['is_admin'];
            $is_active = $data['is_active'];
            $remarks = $data['remarks'];

            $role = new Role();
            $role->name = $description;
            $role->is_admin = $is_admin;
            $role->is_active = $is_active;
            $role->remarks = $remarks;
            $success = $role->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Role : ' . $role->name . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                $permission = array();

                $selected_access = array();
                $selected_access_raw = $data['selected_access'];
                if ($selected_access_raw != "") {
                    $selected_access2 = explode('&', $selected_access_raw);
                    foreach ($selected_access2 as $selected_access2) {
                        $selected_access3 = explode('=', $selected_access2);
                        $selected_access = array(
                            "action" => "access",
                            "module_id" => $selected_access3[1]
                        );

                        $permission[] = $selected_access;
                    }
                }

                $selected_insert = array();
                $selected_insert_raw = $data['selected_insert'];
                if ($selected_insert_raw != "") {
                    $selected_insert2 = explode('&', $selected_insert_raw);
                    foreach ($selected_insert2 as $selected_insert2) {
                        $selected_insert3 = explode('=', $selected_insert2);
                        $selected_insert = array(
                            "action" => "insert",
                            "module_id" => $selected_insert3[1]
                        );

                        $permission[] = $selected_insert;
                    }
                }

                $selected_update = array();
                $selected_update_raw = $data['selected_update'];
                if ($selected_update_raw != "") {
                    $selected_update2 = explode('&', $selected_update_raw);
                    foreach ($selected_update2 as $selected_update2) {
                        $selected_update3 = explode('=', $selected_update2);
                        $selected_update = array(
                            "action" => "update",
                            "module_id" => $selected_update3[1]
                        );

                        $permission[] = $selected_update;
                    }
                }

                $tmp = array();
                foreach ($permission as $permission) {
                    $tmp[$permission['module_id']][] = $permission['action'];
                }

                $output2 = array();
                foreach ($tmp as $type => $labels) {
                    $output2[] = array(
                        'action' => $type,
                        'module_id' => $labels
                    );
                }

                $permission_list = array();
                foreach ($output2 as $output2) {
                    $permission_list[] = array(
                        'module_id' => $output2['action'],
                        'action' => $output2['module_id']
                    );
                }

                foreach ($permission_list as $permission_lists) {
                    $new_permission = new AccessGroup();

                    $new_permission->submodule_id = $permission_lists['module_id'];
                    $new_permission->role_id = $role->id;

                    //default value is 0
                    $new_permission->access_permission = 0;
                    $new_permission->insert_permission = 0;
                    $new_permission->update_permission = 0;

                    foreach ($permission_lists['action'] as $action) {
                        if ($action == "access") {
                            $new_permission->access_permission = 1;
                        }
                        if ($action == "insert") {
                            $new_permission->insert_permission = 1;
                        }
                        if ($action == "update") {
                            $new_permission->update_permission = 1;
                        }
                    }
                    $saved = $new_permission->save();
                }
                if ($saved) {
                    # Audit Trail
                    $remarks = 'Access Permission for ' . $role->name . ' has been inserted.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "System Administration";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    return "true";
                } else {
                    return "false";
                }
            }
        }
    }

    public function getAccessGroups() {
        $accessgroup = Role::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($accessgroup) > 0) {
            $data = Array();
            foreach ($accessgroup as $accessgroups) {
                $button = "";
                $is_admin = trans('app.forms.no');
                if ($accessgroups->is_admin == 1) {
                    $is_admin = trans('app.forms.yes');
                }

                if ($accessgroups->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveAccessGroup(\'' . $accessgroups->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeAccessGroup(\'' . $accessgroups->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateAccessGroup', $accessgroups->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteAccessGroup(\'' . $accessgroups->id . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $accessgroups->name,
                    $accessgroups->remarks,
                    $is_admin,
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

    public function inactiveAccessGroup() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $role = Role::find($id);
            if (count($role) > 0) {
                $role->is_active = 0;
                $updated = $role->save();
                if ($updated) {
                    # Audit Trail
                    $remarks = 'Role : ' . $role->name . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "System Administration";
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

    public function activeAccessGroup() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $role = Role::find($id);
            if (count($role) > 0) {
                $role->is_active = 1;
                $updated = $role->save();
                if ($updated) {
                    # Audit Trail
                    $remarks = 'Role : ' . $role->name . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "System Administration";
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

    public function deleteAccessGroup() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $role = Role::find($id);
            if (count($role) > 0) {
                $role->is_deleted = 1;
                $deleted = $role->save();
                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Role : ' . $role->name . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "System Administration";
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

    public function updateAccessGroup($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $accessgroup = Role::find($id);
        $module = Module::get();

        $viewData = array(
            'title' => trans('app.buttons.update_access_group'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'access_group_list',
            'user_permission' => $user_permission,
            'accessgroup' => $accessgroup,
            'module' => $module,
            'image' => ""
        );

        return View::make('page_en.update_accessgroup', $viewData);
    }

    public function submitUpdateAccessGroup() {
        $data = Input::all();

        if (Request::ajax()) {

            $role_id = $data['role_id'];
            $description = $data['description'];
            $is_admin = $data['is_admin'];
            $is_active = $data['is_active'];
            $remarks = $data['remarks'];

            $role = Role::find($role_id);
            $role->name = $description;
            $role->is_admin = $is_admin;
            $role->is_active = $is_active;
            $role->remarks = $remarks;
            $success = $role->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Role : ' . $role->name . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                $permission = array();

                $selected_access = array();
                $selected_access_raw = $data['selected_access'];
                if ($selected_access_raw != "") {
                    $selected_access2 = explode('&', $selected_access_raw);
                    foreach ($selected_access2 as $selected_access2) {
                        $selected_access3 = explode('=', $selected_access2);
                        $selected_access = array(
                            "action" => "access",
                            "module_id" => $selected_access3[1]
                        );

                        $permission[] = $selected_access;
                    }
                }

                $selected_insert = array();
                $selected_insert_raw = $data['selected_insert'];
                if ($selected_insert_raw != "") {
                    $selected_insert2 = explode('&', $selected_insert_raw);
                    foreach ($selected_insert2 as $selected_insert2) {
                        $selected_insert3 = explode('=', $selected_insert2);
                        $selected_insert = array(
                            "action" => "insert",
                            "module_id" => $selected_insert3[1]
                        );

                        $permission[] = $selected_insert;
                    }
                }

                $selected_update = array();
                $selected_update_raw = $data['selected_update'];
                if ($selected_update_raw != "") {
                    $selected_update2 = explode('&', $selected_update_raw);
                    foreach ($selected_update2 as $selected_update2) {
                        $selected_update3 = explode('=', $selected_update2);
                        $selected_update = array(
                            "action" => "update",
                            "module_id" => $selected_update3[1]
                        );

                        $permission[] = $selected_update;
                    }
                }

                $tmp = array();
                foreach ($permission as $permissions) {
                    $tmp[$permissions['module_id']][] = $permissions['action'];
                }

                $output2 = array();
                foreach ($tmp as $type => $labels) {
                    $output2[] = array(
                        'action' => $type,
                        'module_id' => $labels
                    );
                }

                $permission_list = array();
                foreach ($output2 as $output3) {
                    $permission_list[] = array(
                        'module_id' => $output3['action'],
                        'action' => $output3['module_id']
                    );
                }

                //delete the access permission in db before add new
                $deleted = AccessGroup::where('role_id', $role_id)->delete();

                foreach ($permission_list as $permission_lists) {
                    $new_permission = new AccessGroup();

                    $new_permission->submodule_id = $permission_lists['module_id'];
                    $new_permission->role_id = $role->id;

                    //default value is 0
                    $new_permission->access_permission = 0;
                    $new_permission->insert_permission = 0;
                    $new_permission->update_permission = 0;

                    foreach ($permission_lists['action'] as $actions) {
                        if ($actions == "access") {
                            $new_permission->access_permission = 1;
                        }
                        if ($actions == "insert") {
                            $new_permission->insert_permission = 1;
                        }
                        if ($actions == "update") {
                            $new_permission->update_permission = 1;
                        }
                    }
                    $saved = $new_permission->save();
                }
                if ($saved) {
                    # Audit Trail
                    $remarks = 'Access Permission for ' . $role->name . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "System Administration";
                    $auditTrail->remarks = $remarks;
                    $auditTrail->audit_by = Auth::user()->id;
                    $auditTrail->save();

                    return "true";
                } else {
                    return "false";
                }
            }
        }
    }

    //user
    public function user() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.administration.user_management'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'user_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('admin_en.user', $viewData);
    }

    public function addUser() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $role = Role::where('is_admin', 0)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $role = Role::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
                $company = Company::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $role = Role::where('is_admin', 0)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
                $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $viewData = array(
            'title' => trans('app.buttons.add_user'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'user_list',
            'user_permission' => $user_permission,
            'company' => $company,
            'role' => $role,
            'image' => ""
        );

        return View::make('admin_en.add_user', $viewData);
    }

    public function submitUser() {
        $data = Input::all();
        if (Request::ajax()) {

            $username = $data['username'];
            $password = $data['password'];
            $name = $data['name'];
            $email = $data['email'];
            $phone_no = $data['phone_no'];
            $role = $data['role'];
            $start_date = $data['start_date'];
            $end_date = $data['end_date'];
            $file_id = $data['file_id'];
            $company = $data['company'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $check_username = User::where('username', $username)->count();

            if ($check_username <= 0) {
                $getRole = Role::where('name', $role)->first();

                if ($getRole) {
                    $user = new User();
                    $user->username = $username;
                    $user->password = Hash::make($password);
                    $user->full_name = $name;
                    $user->email = $email;
                    $user->phone_no = $phone_no;
                    $user->role = $getRole->id;
                    if ($getRole->name == 'JMB' || $getRole->name == 'MC') {
                        if (!empty($start_date)) {
                            $user->start_date = $start_date;
                        }
                        if (!empty($end_date)) {
                            $user->end_date = $end_date;
                        }
                        if (!empty($file_id)) {
                            $user->file_id = $file_id;
                        }
                    } else {
                        $user->start_date = null;
                        $user->end_date = null;
                        $user->file_id = null;
                    }
                    $user->company_id = $company;
                    $user->remarks = $remarks;
                    $user->is_active = $is_active;
                    $user->status = 1;
                    $user->approved_by = Auth::user()->id;
                    $user->approved_at = date('Y-m-d H:i:s');
                    $user->is_deleted = 0;
                    $success = $user->save();

                    if ($success) {
                        # Audit Trail
                        $remarks = 'User ' . $user->username . ' has been inserted.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "System Administration";
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
                print "username_in_use";
            }
        }
    }

    public function getUser() {
        if (!Auth::user()->getAdmin()) {
            $user = User::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $user = User::where('is_deleted', 0)->orderBy('id', 'desc')->get();
            } else {
                $user = User::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }
        }

        if (count($user) > 0) {
            $data = Array();
            foreach ($user as $users) {
                $role = Role::find($users->role);

                $button = "";
                if ($users->is_active == 1) {
                    $is_active = trans('app.forms.yes');
                    if ($users->status == 1) {
                        $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveUser(\'' . $users->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                    }
                } else {
                    $is_active = trans('app.forms.no');
                    if ($users->status == 1) {
                        $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeUser(\'' . $users->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                    }
                }

                if ($users->status == 0) {
                    $status = trans('app.forms.pending');
                } else if ($users->status == 1) {
                    $status = trans('app.forms.approved');
                } else {
                    $status = trans('app.forms.rejected');
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateUser', $users->id) . '\'" title="Edit"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="window.location=\'' . URL::action('AdminController@getUserDetails', $users->id) . '\'" title="View"><i class="fa fa-eye"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteUser(\'' . $users->id . '\')" title="Delete"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $users->username,
                    $users->full_name,
                    $users->email,
                    $role->name,
                    $is_active,
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

    public function getUserDetails($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $user = User::find($id);
        $company = Company::find($user->company_id);

        $viewData = array(
            'title' => trans('app.menus.administration.user_details'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'user_list',
            'user_permission' => $user_permission,
            'user' => $user,
            'company' => $company,
            'image' => ""
        );

        return View::make('admin_en.user_details', $viewData);
    }

    public function submitApprovedUser() {
        $data = Input::all();

        if (Request::ajax()) {
            $id = $data['id'];
            $status = $data['status'];
            $remark = $data['remarks'];

            $user = User::find($id);
            $user->status = $status;
            $user->approved_by = Auth::user()->id;
            $user->approved_at = date('Y-m-d H:i:s');
            $user->remarks = $remark;
            if ($status == 1) {
                $user->is_active = 1;
            }
            $success = $user->save();

            if ($success) {
                # Audit Trail
                $remarks = 'User ' . $user->username . ' has been approved.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function inactiveUser() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $user = User::find($id);
            $user->is_active = 0;
            $updated = $user->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'User ' . $user->username . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeUser() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $user = User::find($id);
            $user->is_active = 1;
            $updated = $user->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'User ' . $user->username . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteUser() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $user = User::find($id);
            $user->is_deleted = 1;
            $deleted = $user->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'User ' . $user->username . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "System Administration";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function findFile() {
        $data = Input::all();
        if (Request::ajax()) {
            $cob_id = $data['cob'];

            if (!empty($cob_id)) {
                $company = Company::find($cob_id);
                if ($company) {
                    if ($company->is_main) {
                        $files = Files::where('is_deleted', 0)->orderBy('file_no', 'asc')->get();
                    } else {
                        $files = Files::where('company_id', $company->id)->where('is_deleted', 0)->orderBy('file_no', 'asc')->get();
                    }
                }

                if ($files) {
                    $result = "<option value=''>" . trans('app.forms.please_select') . "</option>";

                    foreach ($files as $file) {
                        $result .= "<option value='" . $file->id . "'>" . $file->file_no . "</option>";
                    }

                    print $result;
                } else {
                    print "<option value=''>" . trans('app.forms.please_select') . "</option>";
                }
            } else {
                print "<option value=''>" . trans('app.forms.please_select') . "</option>";
            }
        }
    }

    public function updateUser($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $user = User::find($id);

        if (!Auth::user()->getAdmin()) {
            $role = Role::where('is_admin', 0)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $role = Role::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
                $company = Company::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $role = Role::where('is_admin', 0)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
                $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $files = Files::where('company_id', $user->company_id)->where('is_deleted', 0)->orderBy('file_no')->get();

        $viewData = array(
            'title' => trans('app.menus.administration.update_user'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'user_list',
            'user_permission' => $user_permission,
            'user' => $user,
            'role' => $role,
            'company' => $company,
            'files' => $files,
            'image' => ""
        );

        return View::make('admin_en.update_user', $viewData);
    }

    public function submitUpdateUser() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $name = $data['name'];
            $email = $data['email'];
            $phone_no = $data['phone_no'];
            $remarks = $data['remarks'];
            $role = $data['role'];
            $start_date = $data['start_date'];
            $end_date = $data['end_date'];
            $file_id = $data['file_id'];
            $company = $data['company'];
            $password = $data['password'];
            $is_active = $data['is_active'];

            $user = User::find($id);
            if ($user) {
                $getRole = Role::where('name', $role)->first();

                if ($getRole) {
                    $user->full_name = $name;
                    $user->email = $email;
                    $user->phone_no = $phone_no;
                    $user->role = $getRole->id;
                    if ($getRole->name == 'JMB' || $getRole->name == 'MC') {
                        if (!empty($start_date)) {
                            $user->start_date = $start_date;
                        }
                        if (!empty($end_date)) {
                            $user->end_date = $end_date;
                        }
                        if (!empty($file_id)) {
                            $user->file_id = $file_id;
                        }
                    } else {
                        $user->start_date = null;
                        $user->end_date = null;
                        $user->file_id = null;
                    }
                    if (!empty($password)) {
                        $user->password = Hash::make($password);
                    }
                    $user->company_id = $company;
                    $user->remarks = $remarks;
                    $user->is_active = $is_active;
                    $success = $user->save();

                    if ($success) {
                        # Audit Trail
                        $remarks = 'User ' . $user->username . ' has been updated.';
                        $auditTrail = new AuditTrail();
                        $auditTrail->module = "System Administration";
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

    //memo
    public function memo() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $memotype = MemoType::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.administration.memo_management'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'memo_maintenence_list',
            'user_permission' => $user_permission,
            'memotype' => $memotype,
            'image' => ""
        );

        return View::make('page_en.memo', $viewData);
    }

    public function addMemo() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $memotype = MemoType::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.buttons.add_memo'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'memo_maintenence_list',
            'user_permission' => $user_permission,
            'memotype' => $memotype,
            'image' => ""
        );

        return View::make('page_en.add_memo', $viewData);
    }

    public function submitMemo() {
        $data = Input::all();
        if (Request::ajax()) {

            $memo_type = $data['memo_type'];
            $memo_date = $data['memo_date'];
            $publish_date = $data['publish_date'];
            $expired_date = $data['expired_date'];
            $subject = $data['subject'];
            $description = $data['description'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $memo = new Memo();
            $memo->memo_type_id = $memo_type;
            $memo->memo_date = $memo_date;
            $memo->publish_date = $publish_date;
            $memo->expired_date = $expired_date;
            $memo->subject = $subject;
            $memo->description = $description;
            $memo->remarks = $remarks;
            $memo->is_active = $is_active;
            $success = $memo->save();

            if ($success) {
                # Audit Trail
                $remarks = $memo->subject . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Memo";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function getMemo() {
        $memo = Memo::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($memo) > 0) {
            $data = Array();
            foreach ($memo as $memos) {
                $memotype = MemoType::find($memos->memo_type_id);

                $button = "";
                if ($memos->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveMemo(\'' . $memos->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeMemo(\'' . $memos->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateMemo', $memos->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteMemo(\'' . $memos->id . '\')"><i class="fa fa-trash"></i></button>';

                if ($memos->expired_date != "0000-00-00") {
                    $expired_date = date('d-M-Y', strtotime($memos->expired_date));
                } else {
                    $expired_date = "";
                }

                $data_raw = array(
                    date('d-M-Y', strtotime($memos->memo_date)),
                    $memotype->description,
                    $memos->subject,
                    date('d-M-Y', strtotime($memos->publish_date)),
                    $expired_date,
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

    public function inactiveMemo() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $memo = Memo::find($id);
            $memo->is_active = 0;
            $updated = $memo->save();
            if ($updated) {
                # Audit Trail
                $remarks = $memo->subject . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Memo";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeMemo() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $memo = Memo::find($id);
            $memo->is_active = 1;
            $updated = $memo->save();
            if ($updated) {
                # Audit Trail
                $remarks = $memo->subject . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Memo";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteMemo() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $memo = Memo::find($id);
            $memo->is_deleted = 1;
            $deleted = $memo->save();
            if ($deleted) {
                # Audit Trail
                $remarks = $memo->subject . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Memo";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateMemo($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $memo = Memo::find($id);
        $memotype = MemoType::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.buttons.update_memo'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'memo_maintenence_list',
            'user_permission' => $user_permission,
            'memo' => $memo,
            'memotype' => $memotype,
            'image' => ""
        );

        return View::make('page_en.update_memo', $viewData);
    }

    public function submitUpdateMemo() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];
            $memo_type = $data['memo_type'];
            $memo_date = $data['memo_date'];
            $publish_date = $data['publish_date'];
            $expired_date = $data['expired_date'];
            $subject = $data['subject'];
            $description = $data['description'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $memo = Memo::find($id);
            $memo->memo_type_id = $memo_type;
            $memo->memo_date = $memo_date;
            $memo->publish_date = $publish_date;
            $memo->expired_date = $expired_date;
            $memo->subject = $subject;
            $memo->description = $description;
            $memo->remarks = $remarks;
            $memo->is_active = $is_active;
            $success = $memo->save();

            if ($success) {
                # Audit Trail
                $remarks = $memo->subject . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Memo";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    //rating
    public function rating() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $viewData = array(
            'title' => trans('app.menus.administration.rating'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'rating_list',
            'user_permission' => $user_permission,
            'image' => ""
        );

        return View::make('admin_en.rating', $viewData);
    }

    public function getRating() {
        $rating = Scoring::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        if (count($rating) > 0) {
            $data = Array();
            foreach ($rating as $ratings) {
                $button = "";

                $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AdminController@updateRating', $ratings->id) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="deleteRating(\'' . $ratings->id . '\')"><i class="fa fa-trash"></i></button>';

                $ratings_A = ((($ratings->score1 + $ratings->score2 + $ratings->score3 + $ratings->score4 + $ratings->score5) / 20) * 25);
                $ratings_B = ((($ratings->score6 + $ratings->score7 + $ratings->score8 + $ratings->score9 + $ratings->score10) / 20) * 25);
                $ratings_C = ((($ratings->score11 + $ratings->score12 + $ratings->score13 + $ratings->score14) / 16) * 20);
                $ratings_D = ((($ratings->score15 + $ratings->score16 + $ratings->score17 + $ratings->score18) / 16) * 20);
                $ratings_E = ((($ratings->score19 + $ratings->score20 + $ratings->score21) / 12) * 10);

                if ($ratings->total_score >= 81) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '</span>';
                } else if ($ratings->total_score >= 61) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else if ($ratings->total_score >= 41) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else if ($ratings->total_score >= 21) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else if ($ratings->total_score >= 1) {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                } else {
                    $rating = '<span style="color: orange;">'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '<i class="fa fa-star-o"></i>'
                            . '</span>';
                }

                $data_raw = array(
                    $ratings->file->file_no,
                    (!empty($ratings->date) ? date('d-M-Y', strtotime($ratings->date)) : '(not set)'),
                    number_format($ratings_A, 2),
                    number_format($ratings_B, 2),
                    number_format($ratings_C, 2),
                    number_format($ratings_D, 2),
                    number_format($ratings_E, 2),
                    number_format($ratings->total_score, 2),
                    $rating,
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

    public function addRating() {
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

        $viewData = array(
            'title' => trans('app.menus.administration.add_rating'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'rating_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'image' => ""
        );

        return View::make('admin_en.add_rating', $viewData);
    }

    public function submitAddRating() {
        $data = Input::all();
        if (Request::ajax()) {
            $survey = 'strata_management';
            $file_id = $data['file_id'];
            $date = $data['date'];
            $score1 = $data['score1'];
            $score2 = $data['score2'];
            $score3 = $data['score3'];
            $score4 = $data['score4'];
            $score5 = $data['score5'];
            $score6 = $data['score6'];
            $score7 = $data['score7'];
            $score8 = $data['score8'];
            $score9 = $data['score9'];
            $score10 = $data['score10'];
            $score11 = $data['score11'];
            $score12 = $data['score12'];
            $score13 = $data['score13'];
            $score14 = $data['score14'];
            $score15 = $data['score15'];
            $score16 = $data['score16'];
            $score17 = $data['score17'];
            $score18 = $data['score18'];
            $score19 = $data['score19'];
            $score20 = $data['score20'];
            $score21 = $data['score21'];

            $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
            $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
            $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
            $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
            $ratings_E = ((($score19 + $score20 + $score21) / 12) * 10);

            $total_score = $ratings_A + $ratings_B + $ratings_C + $ratings_D + $ratings_E;

            $scoring = new Scoring();
            $scoring->file_id = $file_id;
            $scoring->date = $date;
            $scoring->survey = $survey;
            $scoring->score1 = $score1;
            $scoring->score2 = $score2;
            $scoring->score3 = $score3;
            $scoring->score4 = $score4;
            $scoring->score5 = $score5;
            $scoring->score6 = $score6;
            $scoring->score7 = $score7;
            $scoring->score8 = $score8;
            $scoring->score9 = $score9;
            $scoring->score10 = $score10;
            $scoring->score11 = $score11;
            $scoring->score12 = $score12;
            $scoring->score13 = $score13;
            $scoring->score14 = $score14;
            $scoring->score15 = $score15;
            $scoring->score16 = $score16;
            $scoring->score17 = $score17;
            $scoring->score18 = $score18;
            $scoring->score19 = $score19;
            $scoring->score20 = $score20;
            $scoring->score21 = $score21;
            $scoring->total_score = $total_score;
            $success = $scoring->save();

            if ($success) {
                # Audit Trail
                $file_name = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $file_name->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . ' has been inserted.';
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

    public function updateRating($id) {
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
        $rating = Scoring::find($id);
        if ($rating) {
            $viewData = array(
                'title' => trans('app.menus.administration.edit_rating'),
                'panel_nav_active' => 'admin_panel',
                'main_nav_active' => 'admin_main',
                'sub_nav_active' => 'rating_list',
                'user_permission' => $user_permission,
                'files' => $files,
                'rating' => $rating,
                'image' => ""
            );

            return View::make('admin_en.edit_rating', $viewData);
        }
    }

    public function submitUpdateRating() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];
            $date = $data['date'];
            $score1 = $data['score1'];
            $score2 = $data['score2'];
            $score3 = $data['score3'];
            $score4 = $data['score4'];
            $score5 = $data['score5'];
            $score6 = $data['score6'];
            $score7 = $data['score7'];
            $score8 = $data['score8'];
            $score9 = $data['score9'];
            $score10 = $data['score10'];
            $score11 = $data['score11'];
            $score12 = $data['score12'];
            $score13 = $data['score13'];
            $score14 = $data['score14'];
            $score15 = $data['score15'];
            $score16 = $data['score16'];
            $score17 = $data['score17'];
            $score18 = $data['score18'];
            $score19 = $data['score19'];
            $score20 = $data['score20'];
            $score21 = $data['score21'];

            $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 20) * 25);
            $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 20) * 25);
            $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 16) * 20);
            $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 16) * 20);
            $ratings_E = ((($score19 + $score20 + $score21) / 12) * 10);

            $total_score = $ratings_A + $ratings_B + $ratings_C + $ratings_D + $ratings_E;

            $scoring = Scoring::find($id);
            if ($scoring) {
                $scoring->date = $date;
                $scoring->score1 = $score1;
                $scoring->score2 = $score2;
                $scoring->score3 = $score3;
                $scoring->score4 = $score4;
                $scoring->score5 = $score5;
                $scoring->score6 = $score6;
                $scoring->score7 = $score7;
                $scoring->score8 = $score8;
                $scoring->score9 = $score9;
                $scoring->score10 = $score10;
                $scoring->score11 = $score11;
                $scoring->score12 = $score12;
                $scoring->score13 = $score13;
                $scoring->score14 = $score14;
                $scoring->score15 = $score15;
                $scoring->score16 = $score16;
                $scoring->score17 = $score17;
                $scoring->score18 = $score18;
                $scoring->score19 = $score19;
                $scoring->score20 = $score20;
                $scoring->score21 = $score21;
                $scoring->total_score = $total_score;
                $success = $scoring->save();

                if ($success) {
                    # Audit Trail
                    $file_name = Files::find($scoring->file_id);
                    $remarks = 'COB Rating (' . $file_name->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . ' has been updated.';
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

    public function deleteRating() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $scoring = Scoring::find($id);
            $scoring->is_deleted = 1;
            $deleted = $scoring->save();
            if ($deleted) {
                # Audit Trail
                $file_name = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $file_name->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . ' has been deleted.';
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

    //form
    public function form() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $formtype = FormType::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.administration.form'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'form_list',
            'user_permission' => $user_permission,
            'formtype' => $formtype,
            'image' => ""
        );

        return View::make('admin_en.form', $viewData);
    }

    public function getForm() {
        $form = AdminForm::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        if (count($form) > 0) {
            $data = Array();

            foreach ($form as $forms) {
                $formtype = FormType::find($forms->form_type_id);

                $button = "";
                if ($forms->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveForm(\'' . $forms->id . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeForm(\'' . $forms->id . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateForm', $forms->id) . '\'">' . trans('app.forms.edit') . ' <i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteForm(\'' . $forms->id . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    ($forms->company ? $forms->company->short_name : '<i>(not set)</i>'),
                    $formtype->name_en,
                    $forms->name_en,
                    $forms->sort_no,
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

    public function inactiveForm() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $form = AdminForm::find($id);
            $form->is_active = 0;
            $updated = $form->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Form";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function activeForm() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $form = AdminForm::find($id);
            $form->is_active = 1;
            $updated = $form->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Form";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteForm() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $form = AdminForm::find($id);
            $form->is_deleted = 1;
            $deleted = $form->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . ' has been deleted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Form";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function deleteFormFile() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = $data['id'];

            $form = AdminForm::find($id);
            $form->file_url = "";
            $deleted = $form->save();

            if ($deleted) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . ' has been updated.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Form";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function addForm() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $formtype = FormType::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();

        $viewData = array(
            'title' => trans('app.menus.administration.add_form'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'form_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'formtype' => $formtype,
            'image' => ""
        );

        return View::make('admin_en.add_form', $viewData);
    }

    public function submitAddForm() {
        $data = Input::all();
        if (Request::ajax()) {

            $form = new AdminForm();
            $form->company_id = $data['company_id'];
            $form->form_type_id = $data['form_type'];
            $form->name_en = $data['name_en'];
            $form->name_my = $data['name_my'];
            $form->sort_no = $data['sort_no'];
            $form->is_active = $data['is_active'];
            $form->file_url = $data['form_url'];
            $success = $form->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . ' has been inserted.';
                $auditTrail = new AuditTrail();
                $auditTrail->module = "Form";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = Auth::user()->id;
                $auditTrail->save();

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function updateForm($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $form = AdminForm::find($id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }
        }

        $formtype = FormType::where('is_active', 1)->where('is_deleted', 0)->get();

        $viewData = array(
            'title' => trans('app.menus.administration.edit_form'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'form_list',
            'user_permission' => $user_permission,
            'form' => $form,
            'cob' => $cob,
            'formtype' => $formtype,
            'image' => ""
        );

        return View::make('admin_en.edit_form', $viewData);
    }

    public function submitUpdateForm() {
        $data = Input::all();
        if (Request::ajax()) {
            $id = $data['id'];

            $form = AdminForm::find($id);
            if ($form) {
                $form->company_id = $data['company_id'];
                $form->form_type_id = $data['form_type'];
                $form->name_en = $data['name_en'];
                $form->name_my = $data['name_my'];
                $form->sort_no = $data['sort_no'];
                $form->is_active = $data['is_active'];
                $form->file_url = $data['form_url'];
                $success = $form->save();

                if ($success) {
                    # Audit Trail
                    $remarks = $form->id . ' has been updated.';
                    $auditTrail = new AuditTrail();
                    $auditTrail->module = "Form";
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

    // --- Reporting --- //
    //audit trail
    public function auditTrail() {

        $viewData = array(
            'title' => trans('app.menus.reporting.audit_trail_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'audit_trail_list',
            'image' => ""
        );

        return View::make('report_en.audit_trail', $viewData);
    }

    public function getAuditTrail() {
        $data = array();
        $requestData = Request::input();

        $columns = array(
            0 => 'audit_trail.created_at',
            1 => 'audit_trail.module',
            2 => 'audit_trail.remarks',
            3 => 'users.full_name'
        );

        if (!Auth::user()->getAdmin()) {
            $totalData = DB::table('audit_trail')
                    ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                    ->where('users.company_id', Auth::user()->company_id)
                    ->count();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $totalData = DB::table('audit_trail')
                        ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                        ->count();
            } else {
                $totalData = DB::table('audit_trail')
                        ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                        ->where('users.company_id', Session::get('admin_cob'))
                        ->count();
            }
        }

        $limit = $requestData['length'];
        $start = $requestData['start'];
        $order = $columns[$requestData['order'][0]['column']];
        $dir = $requestData['order'][0]['dir'];
        $search = $requestData['search']['value'];
        $date = $requestData['columns'][0]['search']['value'];

        if ($limit == -1) {
            if ($totalData != 0) {
                $limit = $totalData;
            } else {
                $limit = 1;
            }
        } else {
            $limit = $limit;
        }

        if (!empty($date)) {
            $new_date = explode("&", $date);

            $from_date2 = $new_date[0];
            if (!empty($from_date2)) {
                $from_date = explode("-", $from_date2);
                $new_from_date = $from_date[2] . "-" . $from_date[1] . "-" . $from_date[0];
            }

            $to_date2 = $new_date[1];
            if (!empty($to_date2)) {
                $to_date = explode("-", $to_date2);
                $new_to_date = $to_date[2] . "-" . $to_date[1] . "-" . $to_date[0];
            }
        }

        if (!Auth::user()->getAdmin()) {
            if (empty($search)) {
                if (!empty($new_from_date) && !empty($new_to_date)) {
                    $posts = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->select('audit_trail.*', 'users.full_name as name')
                            ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                            ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                            ->where('users.company_id', Auth::user()->company_id)
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                            ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                            ->where('users.company_id', Auth::user()->company_id)
                            ->count();
                } else {
                    $posts = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->select('audit_trail.*', 'users.full_name as name')
                            ->where('users.company_id', Auth::user()->company_id)
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->where('users.company_id', Auth::user()->company_id)
                            ->count();
                }
            } else {
                if (!empty($new_from_date) && !empty($new_to_date)) {
                    $posts = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->select('audit_trail.*', 'users.full_name as name')
                            ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                            ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                            ->where('users.company_id', Auth::user()->company_id)
                            ->where(function($query) use ($search) {
                                $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                            ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                            ->where('users.company_id', Auth::user()->company_id)
                            ->where(function($query) use ($search) {
                                $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                            })
                            ->count();
                } else {
                    $posts = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->select('audit_trail.*', 'users.full_name as name')
                            ->where('users.company_id', Auth::user()->company_id)
                            ->where(function($query) use ($search) {
                                $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

                    $totalFiltered = DB::table('audit_trail')
                            ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                            ->where('users.company_id', Auth::user()->company_id)
                            ->where(function($query) use ($search) {
                                $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                            })
                            ->count();
                }
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                if (empty($search)) {
                    if (!empty($new_from_date) && !empty($new_to_date)) {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->count();
                    } else {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->count();
                    }
                } else {
                    if (!empty($new_from_date) && !empty($new_to_date)) {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            } else {
                if (empty($search)) {
                    if (!empty($new_from_date) && !empty($new_to_date)) {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->count();
                    } else {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->count();
                    }
                } else {
                    if (!empty($new_from_date) && !empty($new_to_date)) {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->where('audit_trail.created_at', '>=', $new_from_date . " 00:00:00")
                                ->where('audit_trail.created_at', '<=', $new_to_date . " 23:59:59")
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    } else {
                        $posts = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->select('audit_trail.*', 'users.full_name as name')
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, $dir)
                                ->get();

                        $totalFiltered = DB::table('audit_trail')
                                ->leftJoin('users', 'audit_trail.audit_by', '=', 'users.id')
                                ->where('users.company_id', Session::get('admin_cob'))
                                ->where(function($query) use ($search) {
                                    $query->where('audit_trail.created_at', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.module', 'LIKE', "%" . $search . "%")
                                    ->orWhere('audit_trail.remarks', 'LIKE', "%" . $search . "%")
                                    ->orWhere('users.full_name', 'LIKE', "%" . $search . "%");
                                })
                                ->count();
                    }
                }
            }
        }

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['created_at'] = date('Y-m-d', strtotime($post->created_at));
                $nestedData['module'] = $post->module;
                $nestedData['remarks'] = $post->remarks;
                $nestedData['full_name'] = $post->name;
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

    //file by location
    public function fileByLocation() {
        $strata = Strata::get();

        $viewData = array(
            'title' => trans('app.menus.reporting.file_by_location_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'file_by_location_list',
            'strata' => $strata,
            'image' => ""
        );

        return View::make('report_en.file_by_location', $viewData);
    }

    public function getFileByLocation() {
        $data = Array();

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        if (count($file) > 0) {
            foreach ($file as $files) {
                $strata = Strata::where('file_id', $files->id)->get();

                if (count($strata) > 0) {
                    foreach ($strata as $stratas) {
                        $parliament = Parliment::find($stratas->parliament);
                        $dun = Dun::find($stratas->dun);
                        $park = Park::find($stratas->park);
                        $files = Files::find($stratas->file_id);

                        if (count($parliament) > 0) {
                            $parliament_name = $parliament->description;
                        } else {
                            $parliament_name = "-";
                        }
                        if (count($dun) > 0) {
                            $dun_name = $dun->description;
                        } else {
                            $dun_name = "-";
                        }
                        if (count($park) > 0) {
                            $park_name = $dun->description;
                        } else {
                            $park_name = "-";
                        }
                        if ($stratas->name == "") {
                            $strata_name = "-";
                        } else {
                            $strata_name = $stratas->name;
                        }

                        $data_raw = array(
                            $parliament_name,
                            $dun_name,
                            $park_name,
                            $files->file_no,
                            $strata_name
                        );

                        array_push($data, $data_raw);
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




//        if (count($strata) > 0) {
//            foreach ($strata as $stratas) {
//                $parliament = Parliment::find($stratas->parliament);
//                $dun = Dun::find($stratas->dun);
//                $park = Park::find($stratas->park);
//                $files = Files::find($stratas->file_id);
//
//                if (count($parliament) > 0) {
//                    $parliament_name = $parliament->description;
//                } else {
//                    $parliament_name = "-";
//                }
//                if (count($dun) > 0) {
//                    $dun_name = $dun->description;
//                } else {
//                    $dun_name = "-";
//                }
//                if (count($park) > 0) {
//                    $park_name = $dun->description;
//                } else {
//                    $park_name = "-";
//                }
//                if ($stratas->name == "") {
//                    $strata_name = "-";
//                } else {
//                    $strata_name = $stratas->name;
//                }
//
//                $data_raw = array(
//                    $parliament_name,
//                    $dun_name,
//                    $park_name,
//                    $files->file_no,
//                    $strata_name
//                );
//
//                array_push($data, $data_raw);
//            }
//
//
//            $output_raw = array(
//                "aaData" => $data
//            );
//
//            $output = json_encode($output_raw);
//            return $output;
//        } else {
//            $output_raw = array(
//                "aaData" => []
//            );
//
//            $output = json_encode($output_raw);
//            return $output;
//        }
    }

    //rating summary
    public function ratingSummary() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        $stratas = 0;
        $ratings = 0;
        $fiveStars = 0;
        $fourStars = 0;
        $threeStars = 0;
        $twoStars = 0;
        $oneStars = 0;

        if (count($file) > 0) {
            foreach ($file as $files) {
                $strata = Strata::where('file_id', $files->id)->count();
                $rating = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->count();
                $fiveStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 81)->where('total_score', '<=', 100)->count();
                $fourStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 61)->where('total_score', '<=', 80)->count();
                $threeStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 41)->where('total_score', '<=', 60)->count();
                $twoStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 21)->where('total_score', '<=', 40)->count();
                $oneStar = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->where('total_score', '>=', 1)->where('total_score', '<=', 20)->count();

                $stratas += $strata;
                $ratings += $rating;
                $fiveStars += $fiveStar;
                $fourStars += $fourStar;
                $threeStars += $threeStar;
                $twoStars += $twoStar;
                $oneStars += $oneStar;
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.rating_summary_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'rating_summary_list',
            'strata' => $stratas,
            'rating' => $ratings,
            'fiveStar' => $fiveStars,
            'fourStar' => $fourStars,
            'threeStar' => $threeStars,
            'twoStar' => $twoStars,
            'oneStar' => $oneStars,
            'image' => ""
        );

//            print "<pre>";
//            print_r($viewData);
//            print "</pre>";

        return View::make('report_en.rating_summary', $viewData);
    }

    //management summary
    public function managementSummary() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        $jmbs = 0;
        $mcs = 0;
        $agents = 0;
        $otherss = 0;
        $residentials = 0;
        $residential_less10s = 0;
        $residential_more10s = 0;
        $commercials = 0;
        $commercial_less10s = 0;
        $commercial_more10s = 0;

        $developer = Developer::where('is_deleted', 0)->count();

        if (count($file) > 0) {
            foreach ($file as $files) {
                $jmb = ManagementJMB::where('file_id', $files->id)->count();
                $mc = ManagementMC::where('file_id', $files->id)->count();
                $agent = ManagementAgent::where('file_id', $files->id)->count();
                $others = ManagementOthers::where('file_id', $files->id)->count();
                $residential = Residential::where('file_id', $files->id)->sum('unit_no');
                $residential_less10 = Residential::where('file_id', $files->id)->where('unit_no', '<=', 10)->sum('unit_no');
                $residential_more10 = Residential::where('file_id', $files->id)->where('unit_no', '>', 10)->sum('unit_no');
                $commercial = Commercial::where('file_id', $files->id)->sum('unit_no');
                $commercial_less10 = Commercial::where('file_id', $files->id)->where('unit_no', '<=', 10)->sum('unit_no');
                $commercial_more10 = Commercial::where('file_id', $files->id)->where('unit_no', '>', 10)->sum('unit_no');

                $jmbs += $jmb;
                $mcs += $mc;
                $agents += $agent;
                $otherss += $others;
                $residentials += $residential;
                $residential_less10s += $residential_less10;
                $residential_more10s += $residential_more10;
                $commercials += $commercial;
                $commercial_less10s += $commercial_less10;
                $commercial_more10s += $commercial_more10;
            }
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.management_summary_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'management_summary_list',
            'strata' => $strata,
            'residential' => $residentials,
            'residential_less10' => $residential_less10s,
            'residential_more10' => $residential_more10s,
            'commercial' => $commercials,
            'commercial_less10' => $commercial_less10s,
            'commercial_more10' => $commercial_more10s,
            'developer' => $developer,
            'jmb' => $jmbs,
            'mc' => $mcs,
            'agent' => $agents,
            'others' => $otherss,
            'image' => ""
        );

        return View::make('report_en.management_summary', $viewData);
    }

    //cob file / management
    public function cobFileManagement() {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('company_id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            } else {
                $strata = DB::table('files')
                        ->leftJoin('strata', 'strata.file_id', '=', 'files.id')
                        ->select('strata.*', 'files.id as file_id')
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('files.is_active', 1)
                        ->where('files.is_deleted', 0)
                        ->orderBy('strata.id')
                        ->get();

                $file = Files::where('company_id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            }
        }

        $jmbs = 0;
        $mcs = 0;
        $agents = 0;
        $otherss = 0;
        $residentials = 0;
        $commercials = 0;

        $developer = Developer::where('is_deleted', 0)->count();

        if (count($file) > 0) {
            foreach ($file as $files) {

                $jmb = ManagementJMB::where('file_id', $files->id)->count();
                $mc = ManagementMC::where('file_id', $files->id)->count();
                $agent = ManagementAgent::where('file_id', $files->id)->count();
                $others = ManagementOthers::where('file_id', $files->id)->count();
                $residential = Residential::where('file_id', $files->id)->sum('unit_no');
                $commercial = Commercial::where('file_id', $files->id)->sum('unit_no');

                $jmbs += $jmb;
                $mcs += $mc;
                $agents += $agent;
                $otherss += $others;
                $residentials += $residential;
                $commercials += $commercial;
            }
        }

        $totals = $developer + $jmbs + $mcs + $agents + $otherss;
        if ($totals == 0) {
            $total = 1;
        } else {
            $total = $totals;
        }

        $viewData = array(
            'title' => trans('app.menus.reporting.cob_file_report'),
            'panel_nav_active' => 'reporting_panel',
            'main_nav_active' => 'reporting_main',
            'sub_nav_active' => 'cob_file_management_list',
            'strata' => $strata,
            'developer' => $developer,
            'jmb' => $jmbs,
            'mc' => $mcs,
            'agent' => $agents,
            'others' => $otherss,
            'total' => $total,
            'residential' => $residentials,
            'commercial' => $commercials,
            'image' => ""
        );

        return View::make('report_en.cob_file_management', $viewData);
    }

    //form download
    public function formDownload() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $formtype = FormType::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();

        $viewData = array(
            'title' => trans('app.menus.form.download'),
            'panel_nav_active' => 'form_panel',
            'main_nav_active' => 'form_main',
            'sub_nav_active' => 'form_download_list',
            'user_permission' => $user_permission,
            'formtype' => $formtype,
            'image' => ""
        );

        return View::make('form_en.index', $viewData);
    }

}
