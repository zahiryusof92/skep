<?php

use Carbon\Carbon;
use Helper\Helper;
use Helper\KCurl;
use Illuminate\Support\Facades\Auth;
use Services\NotificationService;

class AdminController extends BaseController
{

    public function showView($name)
    {
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

    // --- COB Maintenance --- //
    //file prefix
    public function filePrefix()
    {
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

    public function addFilePrefix()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_hidden', false)->where('is_deleted', 0)->orderBy('name')->get();
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

    public function submitFilePrefix()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['prefix']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

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
                $remarks = 'COB File Prefix: ' . $fileprefix->description . $this->module['audit']['text']['data_inserted'];
                $this->addAudit(0, "Master Setup", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function getFilePrefix()
    {
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
            $data = array();
            foreach ($prefix as $fileprefixs) {
                $button = "";
                if ($fileprefixs->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveFilePrefix(\'' . Helper::encode($fileprefixs->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeFilePrefix(\'' . Helper::encode($fileprefixs->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateFilePrefix', Helper::encode($fileprefixs->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteFilePrefix(\'' . Helper::encode($fileprefixs->id) . '\')"><i class="fa fa-trash"></i></button>';
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

    public function inactiveFilePrefix()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['prefix']['status_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $prefix = FilePrefix::findOrFail($id);
            $prefix->is_active = 0;
            $updated = $prefix->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $prefix->description . $this->module['audit']['text']['status_inactive'];
                $this->addAudit(0, "Master Setup", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function activeFilePrefix()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['prefix']['status_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $prefix = FilePrefix::findOrFail($id);
            $prefix->is_active = 1;
            $updated = $prefix->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $prefix->description . $this->module['audit']['text']['status_active'];
                $this->addAudit(0, "Master Setup", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteFilePrefix()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['prefix']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $prefix = FilePrefix::findOrFail($id);
            $prefix->is_deleted = 1;
            $deleted = $prefix->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'COB File Prefix: ' . $prefix->description . $this->module['audit']['text']['data_deleted'];
                $this->addAudit(0, "Master Setup", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function updateFilePrefix($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $prefix = FilePrefix::findOrFail(Helper::decode($id));

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

    public function submitUpdateFilePrefix()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['prefix']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);
            $description = $data['description'];
            $is_active = $data['is_active'];
            $sort_no = $data['sort_no'];

            $fileprefix = FilePrefix::findOrFail($id);

            /** Arrange audit fields changes */
            $description_field = $data['description'] == $fileprefix->description ? "" : "description";
            $is_active_field = $data['is_active'] == $fileprefix->is_active ? "" : "status";
            $sort_no_field = $data['sort_no'] == $fileprefix->sort_no ? "" : "sort_no";

            $audit_fields_changed = "";
            if (!empty($description_field) || !empty($is_active_field) || !empty($sort_no_field)) {
                $audit_fields_changed .= "<br><ul>";
                $audit_fields_changed .= !empty($description_field) ? "<li>$description_field</li>" : "";
                $audit_fields_changed .= !empty($is_active_field) ? "<li>$is_active_field</li>" : "";
                $audit_fields_changed .= !empty($sort_no_field) ? "<li>$sort_no_field</li>" : "";
                $audit_fields_changed .= "</ul>";
            }
            /** End Arrange audit fields changes */

            $fileprefix->description = $description;
            $fileprefix->sort_no = $sort_no;
            $fileprefix->is_active = $is_active;
            $success = $fileprefix->save();

            if ($success) {
                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'COB File Prefix: ' . $fileprefix->description . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit(0, "Master Setup", $remarks);
                }

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    // add file
    public function addFile()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        if (!Auth::user()->getAdmin()) {
            $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            $file_no = FilePrefix::where('is_active', 1)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_hidden', false)->where('is_deleted', 0)->orderBy('name')->get();
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

    public function getLatestFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            if (!empty($data['city']) || !empty($data['file_id'])) {
                $file = Files::file()
                    ->join('strata', 'strata.file_id', '=', 'files.id')
                    // ->where('strata.town', $data['city'])
                    ->where(function ($query) use ($data) {
                        if (!empty($data['city'])) {
                            $query->where('strata.town', $data['city']);
                        }
                        if (!empty($data['file_id'])) {
                            $query->where('files.id', Helper::decode($data['file_id'], $this->module['cob']['file']['name']));
                        }
                    })
                    ->select(['files.*', 'strata.name as strata_name'])
                    ->orderBy('id', 'desc')
                    ->first();
                if ($file) {
                    $latest_file = Files::orderBy('created_at', 'desc')
                        ->first();
                    return [
                        'status' => true,
                        'strata_name' => $file->strata_name,
                        'file_name' => $file->name,
                        'ref_no' => $latest_file->ref_no,
                        'bil_no' => $file->bil_no,
                    ];
                }
            }
        }
    }

    public function submitFile()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

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
                    $house_scheme->save();

                    $strata = new Strata();
                    $strata->file_id = $files->id;
                    $strata->save();

                    $facility = new Facility();
                    $facility->file_id = $files->id;
                    $facility->strata_id = $strata->id;
                    $facility->save();

                    $management = new Management();
                    $management->file_id = $files->id;
                    $management->save();

                    $monitor = new Monitoring();
                    $monitor->file_id = $files->id;
                    $monitor->save();

                    $others = new OtherDetails();
                    $others->file_id = $files->id;
                    $others->save();

                    # Audit Trail
                    $remarks = $files->file_no . $this->module['audit']['text']['data_inserted'];
                    $this->addAudit($files->id, "COB File", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "file_already_exists";
            }

            // } else {
            //     return "false";
            // }
        } else {
            return "false";
        }
    }

    // file list
    public function fileList()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }

        $file = Files::where('is_deleted', 0)->get();
        $year = Files::getVPYear();
        $parkList = Files::parkList();
        $categoryList = Files::categoryList();

        $viewData = array(
            'title' => trans('app.menus.cob.file_list'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'cob_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'file' => $file,
            'year' => $year,
            'parkList' => $parkList,
            'categoryList' => $categoryList,
            'image' => ""
        );

        return View::make('page_en.file_list', $viewData);
    }

    public function getFileList()
    {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id', 'company.short_name'])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', '!=', 2)
                    ->where('files.is_deleted', 0);
            } else {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id', 'company.short_name'])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', '!=', 2)
                    ->where('files.is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id', 'company.short_name'])
                    ->where('files.is_active', '!=', 2)
                    ->where('files.is_deleted', 0);
            } else {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id', 'company.short_name'])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('files.is_active', '!=', 2)
                    ->where('files.is_deleted', 0);
            }
        }

        if (!empty(Input::get('company'))) {
            $file = $file->where('company.short_name', Input::get('company'));
        }

        return Datatables::of($file)
            ->addColumn('cob', function ($model) {
                return ($model->company_id ? $model->company->short_name : '-');
            })
            ->editColumn('file_no', function ($model) {
                return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->id)) . "'>" . $model->file_no . "</a>";
            })
            ->addColumn('strata', function ($model) {
                return ($model->strata_id ? $model->strata->name : '-');
            })
            ->addColumn('year', function ($model) {
                return ($model->strata->year != '0' ? $model->strata->year : '');
            })
            ->addColumn('park', function ($model) {
                return ($model->strata->park > '0' ? $model->strata->parks->description : '');
            })
            ->addColumn('category', function ($model) {
                return ($model->strata->category > '0' ? $model->strata->categories->description : '');
            })
            ->addColumn('active', function ($model) {
                if ($model->is_active == 1) {
                    $is_active = trans('app.forms.yes');
                } else {
                    $is_active = trans('app.forms.no');
                }

                return $is_active;
            })
            ->addColumn('action', function ($model) {
                $button = '';
                if (AccessGroup::hasUpdate(9)) {
                    if ($model->is_active == 1) {
                        $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveFileList(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                        // $button .= '<a href="#" class="" onclick="inactiveFileList(\'' . Helper::encode($model->id) . '\')"><img src='. asset("assets/common/img/icon/disable-eye.png") .' width="20px"></a>&nbsp;';
                    } else {
                        $button .= '<button type="button" class="btn btn-xs btn-default" onclick="activeFileList(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                        // $button .= '<a href="#" class="" onclick="activeFileList(\'' . Helper::encode($model->id) . '\')"><img src='. asset("assets/common/img/icon/eye.png") .' width="28px"></a>&nbsp;';
                    }
                    if (Auth::user()->role == 1) {
                        // $button .= '<a class="modal-update-file-no" data-toggle="modal" data-target="#updateFileNoForm" data-id="' . Helper::encode($model->id) . '" data-file_no="' . $model->file_no . '"><img src='. asset("assets/common/img/icon/edit.png") .' width="20px"></a>&nbsp;';
                        $button .= '<button type="button" class="btn btn-xs btn-warning modal-update-file-no" data-toggle="modal" data-target="#updateFileNoForm" data-id="' . Helper::encode($model->id) . '" data-file_no="' . $model->file_no . '">' . trans('app.forms.update_file_no') . '</button>&nbsp;';
                    }
                    // $button .= '<a class="" onclick="deleteFileList(\'' . Helper::encode($model->id) . '\')" title="Delete"><img src='. asset("assets/common/img/icon/trash.png") .' width="20px"></a>';
                    $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFileList(\'' . Helper::encode($model->id) . '\')" title="Delete"><i class="fa fa-trash"></i></button>';
                }

                return $button;
            })
            ->make(true);
    }

    // file list
    public function fileListBeforeVP()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_hidden', false)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->get();
        }

        $file = Files::where('is_deleted', 0)->get();
        $year = Files::getVPYear();
        $parkList = Files::parkList();
        $categoryList = Files::categoryList();

        $viewData = array(
            'title' => trans('app.menus.cob.file_list_before_vp'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => 'cob_before_vp_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'file' => $file,
            'year' => $year,
            'parkList' => $parkList,
            'categoryList' => $categoryList,
            'image' => ""
        );

        return View::make('page_en.file_list_before_vp', $viewData);
    }

    public function getFileListBeforeVP()
    {
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id'])
                    ->where('files.id', Auth::user()->file_id)
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', 2)
                    ->where('files.is_deleted', 0);
            } else {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id'])
                    ->where('files.company_id', Auth::user()->company_id)
                    ->where('files.is_active', 2)
                    ->where('files.is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id'])
                    ->where('files.is_active', 2)
                    ->where('files.is_deleted', 0)
                    ->where('company.is_hidden', false);
            } else {
                $file = Files::join('company', 'files.company_id', '=', 'company.id')
                    ->join('strata', 'files.id', '=', 'strata.file_id')
                    ->leftJoin('park', 'strata.park', '=', 'park.id')
                    ->leftJoin('category', 'strata.category', '=', 'category.id')
                    ->select(['files.*', 'strata.id as strata_id'])
                    ->where('files.company_id', Session::get('admin_cob'))
                    ->where('files.is_active', 2)
                    ->where('files.is_deleted', 0);
            }
        }

        return Datatables::of($file)
            ->addColumn('cob', function ($model) {
                return ($model->company_id ? $model->company->short_name : '-');
            })
            ->editColumn('file_no', function ($model) {
                return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->id)) . "'>" . $model->file_no . "</a>";
            })
            ->addColumn('strata', function ($model) {
                return ($model->strata_id ? $model->strata->name : '-');
            })
            ->addColumn('year', function ($model) {
                return ($model->strata->year != '0' ? $model->strata->year : '');
            })
            ->addColumn('park', function ($model) {
                return ($model->strata->parks > '0' ? $model->strata->parks->description : '');
            })
            ->addColumn('category', function ($model) {
                return ($model->strata->category > '0' ? $model->strata->categories->description : '');
            })
            ->addColumn('active', function ($model) {
                if ($model->is_active == 1) {
                    $is_active = trans('app.forms.yes');
                } else {
                    $is_active = trans('app.forms.no');
                }

                return $is_active;
            })
            ->addColumn('action', function ($model) {
                $button = '';
                if (AccessGroup::hasUpdate(9)) {
                    if ($model->is_active == 1) {
                        $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveFileList(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                    } else {
                        $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeFileList(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                    }
                    if (Auth::user()->role == 1) {
                        $button .= '<button type="button" class="btn btn-xs btn-warning modal-update-file-no" data-toggle="modal" data-target="#updateFileNoForm" data-id="' . Helper::encode($model->id) . '" data-file_no="' . $model->file_no . '">' . trans('app.forms.update_file_no') . '</button>&nbsp;';
                    }
                    $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFileList(\'' . Helper::encode($model->id) . '\')" title="Delete"><i class="fa fa-trash"></i></button>';
                }

                return $button;
            })
            ->make(true);
    }

    public function inactiveFileList()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['status_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $id = Helper::decode($data['id']);

            $files = Files::findOrFail($id);
            $files->is_active = 0;
            $updated = $files->save();
            if ($updated) {
                # Audit Trail
                $remarks = $files->file_no . $this->module['audit']['text']['status_inactive'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function activeFileList()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['status_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $id = Helper::decode($data['id']);

            $files = Files::findOrFail($id);
            $files->is_active = 1;
            $updated = $files->save();
            if ($updated) {
                # Audit Trail
                $remarks = $files->file_no . $this->module['audit']['text']['status_active'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }

            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteFileList()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $files = Files::findOrFail($id);
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
                    # File Draft Reject List
                    $fileDraftRejectList = FileDraftReject::where('file_id', $files->id)->delete();

                    # Audit Trail
                    $remarks = $files->file_no . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($files->id, "COB File", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        } else {
            return "false";
        }
    }

    public function updateFileNo()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['fileNo_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['file_id']);
            $file_no = $data['file_no'];

            $check_exist = Files::where('file_no', $file_no)->where('id', '!=', $id)->where('is_deleted', 0)->count();
            if ($check_exist <= 0) {
                $files = Files::findOrFail($id);
                if ($files) {
                    /** Arrange audit fields changes */
                    $file_no_field = $data['file_no'] == $files->file_no ? "" : "file_no";

                    $audit_fields_changed = "";
                    if (!empty($file_no_field)) {
                        $audit_fields_changed .= "<br><ul>";
                        $audit_fields_changed .= !empty($file_no_field) ? "<li>$file_no_field</li>" : "";
                        $audit_fields_changed .= "</ul>";
                    }
                    /** End Arrange audit fields changes */

                    $files->file_no = $data['file_no'];
                    $updated = $files->save();
                    if ($updated) {
                        # Audit Trail
                        if (!empty($audit_fields_changed)) {
                            $remarks = $files->file_no . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                            $this->addAudit($files->id, "COB File", $remarks);
                        }

                        return "true";
                    } else {
                        return "false";
                    }
                } else {
                    return "false";
                }
            } else {
                return "exist";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function viewHouse($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::findOrFail(Helper::decode($id));
        $house_scheme = HouseScheme::where('file_id', $file->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $developer = Developer::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $liquidator = Liquidator::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $disallow = Helper::isAllow($file->id, $file->company_id);

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'developer' => $developer,
            'liquidator' => $liquidator,
            'house_scheme' => $house_scheme,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'file' => $file,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_house_scheme', $viewData);
    }

    public function house($id)
    {
        //get user permission
        if (Auth::user()->isMPS()) {
            return Redirect::to('update/strata/' . $id);
        }
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));

        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            $house_scheme = HouseSchemeDraft::where('file_id', $files->id)->first();
            $image = OtherDetailsDraft::where('file_id', $files->id)->first();
            if (count($house_scheme) <= 0) {
                $house_scheme = HouseScheme::where('file_id', $files->id)->first();
            }
            if (count($image) <= 0) {
                $image = OtherDetails::where('file_id', $files->id)->first();
            }
        } else {
            $house_scheme = HouseScheme::where('file_id', $files->id)->first();
            $image = OtherDetails::where('file_id', $files->id)->first();
        }

        $developer = Developer::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $liquidator = Liquidator::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $users = User::where('company_id', $files->company_id)->where('is_active', 1)->where('status', 1)->where('is_deleted', 0)->orderBy('full_name', 'asc')->get();
        $disallow = Helper::isAllow($files->id, $files->company_id);

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'developer' => $developer,
            'liquidator' => $liquidator,
            'house_scheme' => $house_scheme,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'file' => $files,
            'users' => $users,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_house_scheme', $viewData);
    }

    public function submitUpdateHouseScheme()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['house']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id']));
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                $house_scheme = HouseSchemeDraft::firstOrNew(array('file_id' => $files->id));
                $house_scheme->reference_id = $data['reference_id'];

                $this->createOrUpdateFileDraft($files);

                /**
                 * Add Notification & send email to COB and JMB
                 */
                $strata = $files->strata;
                $notify_data['file_id'] = $files->id;
                $notify_data['route'] = route('cob.file.house.edit', Request::get('file_id'));
                $notify_data['cob_route'] = route('cob.file.draft.house.edit', Request::get('file_id'));
                $notify_data['strata'] = "You";
                $notify_data['strata_name'] = $strata->name != "" ? $strata->name : $files->file_no;
                $notify_data['title'] = "COB File House Scheme";
                $notify_data['module'] = "House Scheme";

                (new NotificationService())->store($notify_data);
            } else {
                $house_scheme = HouseScheme::firstOrNew(array('file_id' => $files->id));
            }

            $name = $data['name'];
            $developer = $data['developer'];
            $liquidator = $data['liquidator'];
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
            /** Liquidator Info */
            $liquidator_name = $data['liquidator_name'];
            $liquidator_address1 = $data['liquidator_address1'];
            $liquidator_address2 = $data['liquidator_address2'];
            $liquidator_address3 = $data['liquidator_address3'];
            $liquidator_address4 = $data['liquidator_address4'];
            $liquidator_city = $data['liquidator_city'];
            $liquidator_poscode = $data['liquidator_poscode'];
            $liquidator_state = $data['liquidator_state'];
            $liquidator_country = $data['liquidator_country'];
            $liquidator_phone_no = $data['liquidator_phone_no'];
            $liquidator_fax_no = $data['liquidator_fax_no'];
            $liquidator_remarks = $data['liquidator_remarks'];
            $liquidator_is_active = $data['liquidator_is_active'];
            $is_liquidator = ($data['is_liquidator'] == 'yes') ? 1 : 0;

            /** Arrange audit fields changes */
            $file_status_field = $data['is_active'] == $files->is_active ? "" : "status";
            $developer_name_field = $data['name'] == $house_scheme->name ? "" : "name";
            $developer_company_field = $data['developer'] == $house_scheme->developer ? "" : "developer company";
            $developer_address1_field = $data['address1'] == $house_scheme->address1 ? "" : "developer address1";
            $developer_address2_field = $data['address2'] == $house_scheme->address2 ? "" : "developer address2";
            $developer_address3_field = $data['address3'] == $house_scheme->address3 ? "" : "developer address3";
            $developer_address4_field = $data['address4'] == $house_scheme->address4 ? "" : "developer address4";
            $developer_city_field = $data['city'] == $house_scheme->city ? "" : "developer city";
            $developer_poscode_field = $data['poscode'] == $house_scheme->poscode ? "" : "developer poscode";
            $developer_state_field = $data['state'] == $house_scheme->state ? "" : "developer state";
            $developer_country_field = $data['country'] == $house_scheme->country ? "" : "developer country";
            $developer_phone_no_field = $data['phone_no'] == $house_scheme->phone_no ? "" : "developer phon no";
            $developer_fax_no_field = $data['fax_no'] == $house_scheme->fax_no ? "" : "developer fax no";
            $developer_remarks_field = $data['remarks'] == $house_scheme->remarks ? "" : "developer remarks";
            $developer_is_active_field = $data['is_active'] == $house_scheme->is_active ? "" : "developer status";

            $liquidator_name_field = $data['liquidator_name'] == $house_scheme->liquidator_name ? "" : "liquidator name";
            $liquidator_company_field = $data['liquidator'] == $house_scheme->liquidator ? "" : "liquidator company";
            $liquidator_address1_field = $data['liquidator_address1'] == $house_scheme->liquidator_address1 ? "" : "liquidator address1";
            $liquidator_address2_field = $data['liquidator_address2'] == $house_scheme->liquidator_address2 ? "" : "liquidator address2";
            $liquidator_address3_field = $data['liquidator_address3'] == $house_scheme->liquidator_address3 ? "" : "liquidator address3";
            $liquidator_address4_field = $data['liquidator_address4'] == $house_scheme->liquidator_address4 ? "" : "liquidator address4";
            $liquidator_city_field = $data['liquidator_city'] == $house_scheme->liquidator_city ? "" : "liquidator city";
            $liquidator_state_field = $data['liquidator_state'] == $house_scheme->liquidator_state ? "" : "liquidator state";
            $liquidator_country_field = $data['liquidator_country'] == $house_scheme->liquidator_country ? "" : "liquidator country";
            $liquidator_phone_no_field = $data['liquidator_phone_no'] == $house_scheme->liquidator_phone_no ? "" : "liquidator phone_no";
            $liquidator_fax_no_field = $data['liquidator_fax_no'] == $house_scheme->liquidator_fax_no ? "" : "liquidator fax_no";
            $liquidator_remarks_field = $data['liquidator_remarks'] == $house_scheme->liquidator_remarks ? "" : "liquidator remarks";
            $liquidator_is_active_field = $data['liquidator_is_active'] == $house_scheme->liquidator_is_active ? "" : "liquidator is_active";
            $is_liquidator_field = $data['is_liquidator'] == $house_scheme->is_liquidator ? "" : "is_liquidator";

            $audit_fields_changed = "";
            if (
                !empty($file_status_field) || !empty($developer_name_field) || !empty($developer_company_field) || !empty($developer_address1_field)
                || !empty($developer_address2_field) || !empty($developer_address3_field) || !empty($developer_address4_field) || !empty($developer_city_field)
                || !empty($developer_poscode_field) || !empty($developer_state_field) || !empty($developer_country_field) || !empty($developer_phone_no_field)
                || !empty($developer_fax_no_field) || !empty($developer_remarks_field) || !empty($developer_is_active_field) || !empty($liquidator_name_field)
                || !empty($liquidator_company_field) || !empty($liquidator_address1_field) || !empty($liquidator_address2_field) || !empty($liquidator_address3_field)
                || !empty($liquidator_address4_field) || !empty($liquidator_city_field) || !empty($liquidator_state_field) || !empty($liquidator_country_field)
                || !empty($liquidator_phone_no_field) || !empty($liquidator_fax_no_field) || !empty($liquidator_remarks_field) || !empty($liquidator_is_active_field)
                || !empty($is_liquidator_field)
            ) {
                $audit_fields_changed .= "<br><ul>";
                $audit_fields_changed .= !empty($file_status_field) ? "<li> file: $file_status_field</li>" : "";
                if (
                    !empty($developer_name_field) || !empty($developer_company_field) || !empty($developer_address1_field) || !empty($developer_address2_field)
                    || !empty($developer_address3_field) || !empty($developer_address4_field) || !empty($developer_city_field) || !empty($developer_poscode_field)
                    || !empty($developer_state_field) || !empty($developer_country_field) || !empty($developer_phone_no_field)
                    || !empty($developer_fax_no_field) || !empty($developer_remarks_field) || !empty($developer_is_active_field)
                ) {
                    $audit_fields_changed .= "<li> developer: (";
                    $audit_fields_changed .= !empty($developer_name_field) ? "$developer_name_field, " : "";
                    $audit_fields_changed .= !empty($developer_company_field) ? "$developer_company_field, " : "";
                    $audit_fields_changed .= !empty($developer_address1_field) ? "$developer_address1_field, " : "";
                    $audit_fields_changed .= !empty($developer_address2_field) ? "$developer_address2_field, " : "";
                    $audit_fields_changed .= !empty($developer_address3_field) ? "$developer_address3_field, " : "";
                    $audit_fields_changed .= !empty($developer_address4_field) ? "$developer_address4_field, " : "";
                    $audit_fields_changed .= !empty($developer_city_field) ? "$developer_city_field, " : "";
                    $audit_fields_changed .= !empty($developer_poscode_field) ? "$developer_poscode_field, " : "";
                    $audit_fields_changed .= !empty($developer_state_field) ? "$developer_state_field, " : "";
                    $audit_fields_changed .= !empty($developer_country_field) ? "$developer_country_field, " : "";
                    $audit_fields_changed .= !empty($developer_phone_no_field) ? "$developer_phone_no_field, " : "";
                    $audit_fields_changed .= !empty($developer_fax_no_field) ? "$developer_fax_no_field, " : "";
                    $audit_fields_changed .= !empty($developer_remarks_field) ? "$developer_remarks_field, " : "";
                    $audit_fields_changed .= !empty($developer_is_active_field) ? "$developer_is_active_field" : "";
                    $audit_fields_changed .= ")</li>";
                }
                if (
                    !empty($liquidator_name_field) || !empty($liquidator_company_field) || !empty($liquidator_address1_field) || !empty($liquidator_address2_field)
                    || !empty($liquidator_address3_field) || !empty($liquidator_address4_field) || !empty($liquidator_city_field) || !empty($liquidator_state_field)
                    || !empty($liquidator_country_field) || !empty($liquidator_phone_no_field) || !empty($liquidator_fax_no_field) || !empty($liquidator_remarks_field)
                    || !empty($liquidator_is_active_field) || !empty($is_liquidator_field)
                ) {
                    $audit_fields_changed .= "<li> liquidator: (";
                    $audit_fields_changed .= !empty($liquidator_name_field) ? "$liquidator_name_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_company_field) ? "$liquidator_company_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_address1_field) ? "$liquidator_address1_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_address2_field) ? "$liquidator_address2_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_address3_field) ? "$liquidator_address3_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_address4_field) ? "$liquidator_address4_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_city_field) ? "$liquidator_city_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_state_field) ? "$liquidator_state_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_country_field) ? "$liquidator_country_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_phone_no_field) ? "$liquidator_phone_no_field, " : "";
                    $audit_fields_changed .= !empty($developer_phone_no_field) ? "$developer_phone_no_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_fax_no_field) ? "$liquidator_fax_no_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_remarks_field) ? "$liquidator_remarks_field, " : "";
                    $audit_fields_changed .= !empty($liquidator_is_active_field) ? "$liquidator_is_active_field" : "";
                    $audit_fields_changed .= !empty($is_liquidator_field) ? "$is_liquidator_field" : "";
                    $audit_fields_changed .= ")</li>";
                }
                $audit_fields_changed .= "</ul>";
            }
            /** End Arrange audit fields changes */

            if (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper()) {
                $files->is_active = $is_active;
                $files->save();
            }

            $house_scheme->name = $name;
            $house_scheme->developer = $developer;
            $house_scheme->liquidator = $liquidator;
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
            /** Liquidator Info */
            $house_scheme->liquidator_name = $liquidator_name;
            $house_scheme->liquidator_address1 = $liquidator_address1;
            $house_scheme->liquidator_address2 = $liquidator_address2;
            $house_scheme->liquidator_address3 = $liquidator_address3;
            $house_scheme->liquidator_address4 = $liquidator_address4;
            $house_scheme->liquidator_city = $liquidator_city;
            $house_scheme->liquidator_poscode = $liquidator_poscode;
            $house_scheme->liquidator_state = $liquidator_state;
            $house_scheme->liquidator_country = $liquidator_country;
            $house_scheme->liquidator_phone_no = $liquidator_phone_no;
            $house_scheme->liquidator_fax_no = $liquidator_fax_no;
            $house_scheme->liquidator_remarks = $liquidator_remarks;
            $house_scheme->is_liquidator = $is_liquidator;
            if ($is_active != 2) {
                $house_scheme->is_active = $is_active;
            }
            if ($liquidator_is_active != 2) {
                $house_scheme->liquidator_is_active = $liquidator_is_active;
            }
            $house_scheme->save();

            # Audit Trail
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                $remarks = 'House Info (' . $files->file_no . ')' . $this->module['audit']['text']['jmb_submit_updated'];
                $this->addAudit($files->id, "COB File", $remarks);
            } else {
                if (!empty($audit_fields_changed)) {
                    $remarks = 'House Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->id, "COB File", $remarks);
                }
            }

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function viewStrata($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::findOrFail(Helper::decode($id));
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
        $disallow = Helper::isAllow($file->id, $file->company_id);

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

    public function strata($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));

        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            $strata = StrataDraft::where('file_id', $files->id)->first();
            $residential = ResidentialDraft::where('file_id', $files->id)->first();
            $residential_extra = ResidentialExtra::where('file_id', $files->id)->get();
            $commercial = CommercialDraft::where('file_id', $files->id)->first();
            $commercial_extra = CommercialExtra::where('file_id', $files->id)->get();
            $facility = FacilityDraft::where('file_id', $files->id)->first();

            if (count($strata) <= 0) {
                $strata = Strata::where('file_id', $files->id)->first();
            }
            if (count($residential) <= 0) {
                $residential = Residential::where('file_id', $files->id)->first();
            }
            if (count($commercial) <= 0) {
                $commercial = Commercial::where('file_id', $files->id)->first();
            }
            if (count($facility) <= 0) {
                $facility = Facility::where('file_id', $files->id)->first();
            }
        } else {
            $strata = Strata::where('file_id', $files->id)->first();
            $residential = Residential::where('file_id', $files->id)->first();
            $residential_extra = ResidentialExtra::where('file_id', $files->id)->get();
            $commercial = Commercial::where('file_id', $files->id)->first();
            $commercial_extra = CommercialExtra::where('file_id', $files->id)->get();
            $facility = Facility::where('file_id', $files->id)->first();
        }

        $image = OtherDetails::where('file_id', $files->id)->first();
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
        $disallow = Helper::isAllow($files->id, $files->company_id);

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
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
            'file' => $files,
            'unitoption' => $unitoption,
            'residential' => $residential,
            'residential_extra' => $residential_extra,
            'commercial' => $commercial,
            'commercial_extra' => $commercial_extra,
            'designation' => $designation,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_strata', $viewData);
    }

    public function findDUN()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $parliament_id = $data['parliament_id'];
            $dun = Dun::where('is_deleted', 0)->where('parliament', $parliament_id)->orderBy('description', 'asc')->get();
            if (count($dun) > 0) {
                $result = "<option value=''>" . trans('app.forms.please_select') . "</option>";

                foreach ($dun as $duns) {
                    $result .= "<option value='" . $duns->id . "'>" . $duns->description . "</option>";
                }

                return $result;
            } else {
                return "<option value=''>" . trans('app.forms.please_select') . "</option>";
            }
        }
    }

    public function findPark()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $dun_id = $data['dun_id'];
            $park = Park::where('is_deleted', 0)->where('dun', $dun_id)->orderBy('description', 'asc')->get();
            if (count($park) > 0) {
                $result = "<option value=''>" . trans('app.forms.please_select') . "</option>";

                foreach ($park as $parks) {
                    $result .= "<option value='" . $parks->id . "'>" . $parks->description . "</option>";
                }
                return $result;
            } else {
                return "<option value=''>" . trans('app.forms.please_select') . "</option>";
            }
        }
    }

    public function submitUpdateStrata()
    {
        $data = Input::all();
        if (Request::ajax()) {
            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['strata']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id']));
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                $strata = StrataDraft::firstOrNew(array('file_id' => $files->id));
                $commercial = CommercialDraft::firstOrNew(array('file_id' => $files->id));
                $residential = ResidentialDraft::firstOrNew(array('file_id' => $files->id));
                $facility = FacilityDraft::firstOrNew(array('file_id' => $files->id));

                $strata->reference_id = $data['strata_reference_id'];
                $facility->reference_id = $data['facility_reference_id'];

                $this->createOrUpdateFileDraft($files);

                /**
                 * Add Notification & send email to COB and JMB
                 */
                $not_draft_strata = $files->strata;
                $notify_data['file_id'] = $files->id;
                $notify_data['route'] = route('cob.file.strata.edit', Request::get('file_id'));
                $notify_data['cob_route'] = route('cob.file.draft.strata.edit', Request::get('file_id'));
                $notify_data['strata'] = "You";
                $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $files->file_no;
                $notify_data['title'] = "COB File Strata";
                $notify_data['module'] = "Strata";

                (new NotificationService())->store($notify_data);
            } else {
                $strata = Strata::firstOrNew(array('file_id' => $files->id));
                $commercial = Commercial::firstOrNew(array('file_id' => $files->id));
                $residential = Residential::firstOrNew(array('file_id' => $files->id));
                $facility = Facility::firstOrNew(array('file_id' => $files->id));
            }
            $residentialExtra = ResidentialExtra::where('file_id', $files->id)->get();
            $commercialExtra = CommercialExtra::where('file_id', $files->id)->get();

            // strata
            $name = $data['strata_name'];
            $title = $data['strata_title'];
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


            /** Arrange audit fields changes */
            if (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper()) {
                $audit_fields_changed = "";
                $strata_title_field = $title == $strata->title ? "" : "strata title";
                $strata_name_field = $name == $strata->name ? "" : "strata name";
                $strata_parliament_field = $parliament == $strata->parliament ? "" : "strata parliament";
                $strata_dun_field = $dun == $strata->dun ? "" : "strata dun";
                $strata_park_field = $park == $strata->park ? "" : "strata park";
                $strata_address1_field = $address1 == $strata->address1 ? "" : "strata address1";
                $strata_address2_field = $address2 == $strata->address2 ? "" : "strata address2";
                $strata_address3_field = $address3 == $strata->address3 ? "" : "strata address3";
                $strata_address4_field = $address4 == $strata->address4 ? "" : "strata address4";
                $strata_poscode_field = $poscode == $strata->poscode ? "" : "strata poscode";
                $strata_city_field = $city == $strata->city ? "" : "strata city";
                $strata_state_field = $state == $strata->state ? "" : "strata state";
                $strata_country_field = $country == $strata->country ? "" : "strata country";
                $strata_block_no_field = $block_no == $strata->block_no ? "" : "strata block no";
                $strata_total_floor_field = $floor == $strata->total_floor ? "" : "strata total floor";
                $strata_year_field = $year == $strata->year ? "" : "strata year";
                $strata_town_field = $town == $strata->town ? "" : "strata town";
                $strata_area_field = $area == $strata->area ? "" : "strata area";
                $strata_land_area_field = $land_area == $strata->land_area ? "" : "strata land area";
                $strata_total_share_unit_field = $total_share_unit == $strata->total_share_unit ? "" : "strata total share unit";
                $strata_land_area_unit_field = $land_area_unit == $strata->land_area_unit ? "" : "strata land area unit";
                $strata_lot_no_field = $lot_no == $strata->lot_no ? "" : "strata lot no";
                $strata_ownership_no_field = $ownership_no == $strata->ownership_no ? "" : "strata ownership no";
                $strata_date_field = $date == $strata->date ? "" : "strata date";
                $strata_land_title_field = $land_title == $strata->land_title ? "" : "strata land title";
                $strata_category_field = $category == $strata->category ? "" : "strata category";
                $strata_perimeter_field = $perimeter == $strata->perimeter ? "" : "strata perimeter";
                $strata_ccc_no_field = $ccc_no == $strata->ccc_no ? "" : "strata ccc_no";
                $strata_ccc_date_field = $ccc_date == $strata->ccc_date ? "" : "strata ccc_date";
                $strata_file_url_field = $stratafile == $strata->file_url ? "" : "strata file";
                $strata_is_residential_field = $is_residential == $strata->is_residential ? "" : "strata is residential";
                $strata_is_commercial_field = $is_commercial == $strata->is_commercial ? "" : "strata is commercial";
                $residential_text = '';
                $residential_unit_no_field = '';
                $residential_maintenance_fee_field = '';
                $residential_maintenance_fee_option_field = '';
                $residential_sinking_fund_field = '';
                $residential_sinking_fund_option_field = '';
                $commercial_text = '';
                $commercial_unit_no_field = '';
                $commercial_maintenance_fee_field = '';
                $commercial_maintenance_fee_option_field = '';
                $commercial_sinking_fund_field = '';
                $commercial_sinking_fund_option_field = '';
                if ($strata->is_residential && $is_residential) {
                    $residential_unit_no_field = $residential_unit_no == $residential->unit_no ? "" : "strata residential unit no";
                    $residential_maintenance_fee_field = $residential_maintenance_fee == $residential->maintenance_fee ? "" : "strata residential maintenance fee";
                    $residential_maintenance_fee_option_field = $residential_maintenance_fee_option == $residential->maintenance_fee_option ? "" : "strata residential maintenance fee option";
                    $residential_sinking_fund_field = $residential_sinking_fund == $residential->sinking_fund ? "" : "strata residential sinking fund";
                    $residential_sinking_fund_option_field = $residential_sinking_fund_option == $residential->sinking_fund_option ? "" : "strata residential sinking fund option";
                } else {
                    if ($strata->is_residential != $is_residential) {
                        if ($is_residential) {
                            $residential_text = 'new residential data';
                        } else {
                            $residential_text = 'remove residential data';
                        }
                    }
                }
                if ($strata->is_commercial && $is_commercial) {
                    $commercial_unit_no_field = $commercial_unit_no == $commercial->unit_no ? "" : "strata commercial unit no";
                    $commercial_maintenance_fee_field = $commercial_maintenance_fee == $commercial->maintenance_fee ? "" : "strata commercial maintenance fee";
                    $commercial_maintenance_fee_option_field = $commercial_maintenance_fee_option == $commercial->maintenance_fee_option ? "" : "strata commercial maintenance fee option";
                    $commercial_sinking_fund_field = $commercial_sinking_fund == $commercial->sinking_fund ? "" : "strata commercial sinking fund";
                    $commercial_sinking_fund_option_field = $commercial_sinking_fund_option == $commercial->sinking_fund_option ? "" : "strata commercial sinking fund option";
                } else {
                    if ($strata->is_commercial != $is_commercial) {
                        if ($is_commercial) {
                            $commercial_text = 'new commercial data';
                        } else {
                            $commercial_text = 'remove commercial data';
                        }
                    }
                }
                $facility_management_office_field = $management_office == $facility->management_office ? "" : "strata facility management office";
                $facility_management_office_unit_field = $management_office_unit == $facility->management_office_unit ? "" : "strata facility management office unit";
                $facility_swimming_pool_field = $swimming_pool == $facility->swimming_pool ? "" : "strata facility swimming pool";
                $facility_swimming_pool_unit_field = $swimming_pool_unit == $facility->swimming_pool_unit ? "" : "strata facility swimming pool unit";
                $facility_surau_field = $surau == $facility->surau ? "" : "strata facility surau";
                $facility_surau_unit_field = $surau_unit == $facility->surau_unit ? "" : "strata facility surau unit";
                $facility_multipurpose_hall_field = $multipurpose_hall == $facility->multipurpose_hall ? "" : "strata facility multipurpose hall";
                $facility_multipurpose_hall_unit_field = $multipurpose_hall_unit == $facility->multipurpose_hall_unit ? "" : "strata facility multipurpose hall unit";
                $facility_gym_field = $gym == $facility->gym ? "" : "strata facility gym";
                $facility_gym_unit_field = $gym_unit == $facility->gym_unit ? "" : "strata facility gym unit";
                $facility_playground_field = $playground == $facility->playground ? "" : "strata facility playground";
                $facility_playground_unit_field = $playground_unit == $facility->playground_unit ? "" : "strata facility playground unit";
                $facility_guardhouse_field = $guardhouse == $facility->guardhouse ? "" : "strata facility guardhouse";
                $facility_guardhouse_unit_field = $guardhouse_unit == $facility->guardhouse_unit ? "" : "strata facility guardhouse unit";
                $facility_kindergarten_field = $kindergarten == $facility->kindergarten ? "" : "strata facility kindergarten";
                $facility_kindergarten_unit_field = $kindergarten_unit == $facility->kindergarten_unit ? "" : "strata facility kindergarten unit";
                $facility_open_space_field = $open_space == $facility->open_space ? "" : "strata facility open space";
                $facility_open_space_unit_field = $open_space_unit == $facility->open_space_unit ? "" : "strata facility open space unit";
                $facility_lift_field = $lift == $facility->lift ? "" : "strata facility lift";
                $facility_lift_unit_field = $lift_unit == $facility->lift_unit ? "" : "strata facility lift unit";
                $facility_rubbish_room_field = $rubbish_room == $facility->rubbish_room ? "" : "strata facility rubbish room";
                $facility_rubbish_room_unit_field = $rubbish_room_unit == $facility->rubbish_room_unit ? "" : "strata facility rubbish room unit";
                $facility_gated_field = $gated == $facility->gated ? "" : "strata facility gated";
                $facility_gated_unit_field = $gated_unit == $facility->gated_unit ? "" : "strata facility gated unit";
                $facility_others_field = $others == $facility->others ? "" : "strata facility others";

                $audit_fields_changed .= "<br><ul>";
                /** Strata */
                if (
                    !empty($strata_title_field) || !empty($strata_name_field) || !empty($strata_parliament_field) || !empty($strata_dun_field)
                    || !empty($strata_park_field) || !empty($strata_address1_field) || !empty($strata_address2_field) || !empty($strata_address3_field)
                    || !empty($strata_address4_field) || !empty($strata_poscode_field) || !empty($strata_city_field) || !empty($strata_state_field)
                    || !empty($strata_country_field) || !empty($strata_block_no_field) || !empty($strata_total_floor_field) || !empty($strata_year_field)
                    || !empty($strata_town_field) || !empty($strata_area_field) || !empty($strata_land_area_field) || !empty($strata_total_share_unit_field)
                    || !empty($strata_land_area_unit_field) || !empty($strata_lot_no_field) || !empty($strata_ownership_no_field) || !empty($strata_date_field)
                    || !empty($strata_land_title_field) || !empty($strata_category_field) || !empty($strata_perimeter_field) || !empty($strata_ccc_no_field)
                    || !empty($strata_ccc_date_field) || !empty($strata_file_url_field) || !empty($strata_is_residential_field) || !empty($strata_is_commercial_field)
                ) {
                    $audit_fields_changed .= "<li> Strata : (";
                    $new_line = '';
                    $new_line .= !empty($strata_title_field) ? "$strata_title_field, " : "";
                    $new_line .= !empty($strata_name_field) ? "$strata_name_field, " : "";
                    $new_line .= !empty($strata_parliament_field) ? "$strata_parliament_field, " : "";
                    $new_line .= !empty($strata_dun_field) ? "$strata_dun_field, " : "";
                    $new_line .= !empty($strata_park_field) ? "$strata_park_field, " : "";
                    $new_line .= !empty($strata_address1_field) ? "$strata_address1_field, " : "";
                    $new_line .= !empty($strata_address2_field) ? "$strata_address2_field, " : "";
                    $new_line .= !empty($strata_address3_field) ? "$strata_address3_field, " : "";
                    $new_line .= !empty($strata_address4_field) ? "$strata_address4_field, " : "";
                    $new_line .= !empty($strata_poscode_field) ? "$strata_poscode_field, " : "";
                    $new_line .= !empty($strata_city_field) ? "$strata_city_field, " : "";
                    $new_line .= !empty($strata_state_field) ? "$strata_state_field, " : "";
                    $new_line .= !empty($strata_country_field) ? "$strata_country_field, " : "";
                    $new_line .= !empty($strata_block_no_field) ? "$strata_block_no_field, " : "";
                    $new_line .= !empty($strata_total_floor_field) ? "$strata_total_floor_field, " : "";
                    $new_line .= !empty($strata_year_field) ? "$strata_year_field, " : "";
                    $new_line .= !empty($strata_town_field) ? "$strata_town_field, " : "";
                    $new_line .= !empty($strata_area_field) ? "$strata_area_field, " : "";
                    $new_line .= !empty($strata_land_area_field) ? "$strata_land_area_field, " : "";
                    $new_line .= !empty($strata_total_share_unit_field) ? "$strata_total_share_unit_field, " : "";
                    $new_line .= !empty($strata_land_area_unit_field) ? "$strata_land_area_unit_field, " : "";
                    $new_line .= !empty($strata_lot_no_field) ? "$strata_lot_no_field, " : "";
                    $new_line .= !empty($strata_ownership_no_field) ? "$strata_ownership_no_field, " : "";
                    $new_line .= !empty($strata_date_field) ? "$strata_date_field, " : "";
                    $new_line .= !empty($strata_land_title_field) ? "$strata_land_title_field, " : "";
                    $new_line .= !empty($strata_category_field) ? "$strata_category_field, " : "";
                    $new_line .= !empty($strata_perimeter_field) ? "$strata_perimeter_field, " : "";
                    $new_line .= !empty($strata_ccc_no_field) ? "$strata_ccc_no_field, " : "";
                    $new_line .= !empty($strata_ccc_date_field) ? "$strata_ccc_date_field, " : "";
                    $new_line .= !empty($strata_file_url_field) ? "$strata_file_url_field, " : "";
                    $new_line .= !empty($strata_is_residential_field) ? "$strata_is_residential_field, " : "";
                    $new_line .= !empty($strata_is_commercial_field) ? "$strata_is_commercial_field, " : "";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                }
                /** End Strata */
                /** Residential */
                if (empty($residential_text)) {
                    if (
                        !empty($residential_unit_no_field) || !empty($residential_maintenance_fee_field) || !empty($residential_maintenance_fee_option_field) || !empty($residential_sinking_fund_field)
                        || !empty($residential_sinking_fund_option_field)
                    ) {
                        $audit_fields_changed .= "<li> Residential : (";
                        $new_line = '';
                        $new_line .= !empty($residential_unit_no_field) ? "$residential_unit_no_field, " : "";
                        $new_line .= !empty($residential_maintenance_fee_field) ? "$residential_maintenance_fee_field, " : "";
                        $new_line .= !empty($residential_maintenance_fee_option_field) ? "$residential_maintenance_fee_option_field, " : "";
                        $new_line .= !empty($residential_sinking_fund_field) ? "$residential_sinking_fund_field, " : "";
                        $new_line .= !empty($residential_sinking_fund_option_field) ? "$residential_sinking_fund_option_field, " : "";
                        $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    }
                } else {
                    $audit_fields_changed .= "<li> Residential : (" . $residential_text . ")</li>";
                }
                if (!empty($data['residential_maintenance_fee_is_custom'])) {
                    if (($residentialExtra->count() > 0 && count($data['residential_maintenance_fee_is_custom']) > 0) || ($residentialExtra->count() == 0 && count($data['residential_maintenance_fee_is_custom']) > 0)
                        || ($residentialExtra->count() > 0 && count($data['residential_maintenance_fee_is_custom']) == 0)
                    ) {
                        $audit_fields_changed .= "<li>Residential Extra : (";
                        if (($residentialExtra->count() > 0 && count($data['residential_maintenance_fee_is_custom']) > 0)) {
                            $audit_fields_changed .= "residential extra data updated";
                        } else if (($residentialExtra->count() == 0 && count($data['residential_maintenance_fee_is_custom']) > 0)) {
                            $audit_fields_changed .= "new data";
                        } else {
                            $audit_fields_changed .= "removed residential extra data";
                        }
                        $audit_fields_changed .= ")</li>";
                    }
                } else {
                    if ($residentialExtra->count() > 0) {
                        $audit_fields_changed .= "<li>Residential Extra : (removed residential extra data) </li>";
                    }
                }
                /** End Residential */
                /** Commercial */
                if (empty($commercial_text)) {
                    if (
                        !empty($commercial_unit_no_field) || !empty($commercial_maintenance_fee_field) || !empty($commercial_maintenance_fee_option_field)
                        || !empty($commercial_sinking_fund_field) || !empty($commercial_sinking_fund_option_field)
                    ) {
                        $audit_fields_changed .= "<li> Commercial : (";
                        $new_line = '';
                        $new_line .= !empty($commercial_unit_no_field) ? "$commercial_unit_no_field, " : "";
                        $new_line .= !empty($commercial_maintenance_fee_field) ? "$commercial_maintenance_fee_field, " : "";
                        $new_line .= !empty($commercial_maintenance_fee_option_field) ? "$commercial_maintenance_fee_option_field, " : "";
                        $new_line .= !empty($commercial_sinking_fund_field) ? "$commercial_sinking_fund_field, " : "";
                        $new_line .= !empty($commercial_sinking_fund_option_field) ? "$commercial_sinking_fund_option_field, " : "";
                        $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                    }
                } else {
                    $audit_fields_changed .= "<li> Commercial : (" . $commercial_text . ")</li>";
                }
                if (!empty($data['commercial_maintenance_fee_is_custom'])) {
                    if (($commercialExtra->count() > 0 && count($data['commercial_maintenance_fee_is_custom']) > 0) || ($commercialExtra->count() == 0 && count($data['commercial_maintenance_fee_is_custom']) > 0)
                        || ($commercialExtra->count() > 0 && count($data['commercial_maintenance_fee_is_custom']) == 0)
                    ) {
                        $audit_fields_changed .= "<li>Commercial Extra : (";
                        if (($commercialExtra->count() > 0 && count($data['commercial_maintenance_fee_is_custom']) > 0)) {
                            $audit_fields_changed .= "commercial extra data updated";
                        } else if (($commercialExtra->count() == 0 && count($data['commercial_maintenance_fee_is_custom']) > 0)) {
                            $audit_fields_changed .= "new data";
                        } else {
                            $audit_fields_changed .= "removed commercial extra data";
                        }
                        $audit_fields_changed .= ")</li>";
                    }
                } else {
                    if ($commercialExtra->count() > 0) {
                        $audit_fields_changed .= "<li>Commercial Extra : (removed commercial extra data) </li>";
                    }
                }
                /** End Commercial */
                /** Facility */
                if (
                    !empty($facility_management_office_field) || !empty($facility_management_office_unit_field) || !empty($facility_swimming_pool_field)
                    || !empty($facility_swimming_pool_unit_field) || !empty($facility_surau_field) || !empty($facility_surau_unit_field)
                    || !empty($facility_multipurpose_hall_field) || !empty($facility_multipurpose_hall_unit_field)
                    || !empty($facility_gym_field) || !empty($facility_gym_unit_field) || !empty($facility_playground_field)
                    || !empty($facility_playground_unit_field) || !empty($facility_guardhouse_field) || !empty($facility_guardhouse_unit_field)
                    || !empty($facility_kindergarten_field) || !empty($facility_kindergarten_unit_field) || !empty($facility_open_space_field)
                    || !empty($facility_open_space_unit_field) || !empty($facility_lift_field) || !empty($facility_lift_unit_field)
                    || !empty($facility_rubbish_room_field) || !empty($facility_rubbish_room_unit_field) || !empty($facility_gated_field)
                    || !empty($facility_gated_unit_field) || !empty($facility_others_field)
                ) {
                    $audit_fields_changed .= "<li> Facility : (";
                    $new_line = '';
                    $new_line .= !empty($facility_management_office_field) ? "$facility_management_office_field, " : "";
                    $new_line .= !empty($facility_management_office_unit_field) ? "$facility_management_office_unit_field, " : "";
                    $new_line .= !empty($facility_swimming_pool_field) ? "$facility_swimming_pool_field, " : "";
                    $new_line .= !empty($facility_swimming_pool_unit_field) ? "$facility_swimming_pool_unit_field, " : "";
                    $new_line .= !empty($facility_surau_field) ? "$facility_surau_field, " : "";
                    $new_line .= !empty($facility_surau_unit_field) ? "$facility_surau_unit_field, " : "";
                    $new_line .= !empty($facility_multipurpose_hall_field) ? "$facility_multipurpose_hall_field, " : "";
                    $new_line .= !empty($facility_multipurpose_hall_unit_field) ? "$facility_multipurpose_hall_unit_field, " : "";
                    $new_line .= !empty($facility_gym_field) ? "$facility_gym_field, " : "";
                    $new_line .= !empty($facility_gym_unit_field) ? "$facility_gym_unit_field, " : "";
                    $new_line .= !empty($facility_playground_field) ? "$facility_playground_field, " : "";
                    $new_line .= !empty($facility_playground_unit_field) ? "$facility_playground_unit_field, " : "";
                    $new_line .= !empty($facility_guardhouse_field) ? "$facility_guardhouse_field, " : "";
                    $new_line .= !empty($facility_guardhouse_unit_field) ? "$facility_guardhouse_unit_field, " : "";
                    $new_line .= !empty($facility_kindergarten_field) ? "$facility_kindergarten_field, " : "";
                    $new_line .= !empty($facility_kindergarten_unit_field) ? "$facility_kindergarten_unit_field, " : "";
                    $new_line .= !empty($facility_open_space_field) ? "$facility_open_space_field, " : "";
                    $new_line .= !empty($facility_open_space_unit_field) ? "$facility_open_space_unit_field, " : "";
                    $new_line .= !empty($facility_lift_field) ? "$facility_lift_field, " : "";
                    $new_line .= !empty($facility_lift_unit_field) ? "$facility_lift_unit_field, " : "";
                    $new_line .= !empty($facility_rubbish_room_field) ? "$facility_rubbish_room_field, " : "";
                    $new_line .= !empty($facility_rubbish_room_unit_field) ? "$facility_rubbish_room_unit_field, " : "";
                    $new_line .= !empty($facility_gated_field) ? "$facility_gated_field, " : "";
                    $new_line .= !empty($facility_gated_unit_field) ? "$facility_gated_unit_field, " : "";
                    $new_line .= !empty($facility_others_field) ? "$facility_others_field, " : "";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                }
                /** End Facility */
                $audit_fields_changed .= "</ul>";
            }
            /** End Arrange audit fields changes */

            if (!empty($year)) {
                $files->year = $year;
                $files->save();
            }

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
            $strata->save();

            if ($strata->is_residential) {
                $residential->strata_id = $strata->id;
                $residential->unit_no = $residential_unit_no;
                $residential->maintenance_fee = $residential_maintenance_fee;
                $residential->maintenance_fee_option = $residential_maintenance_fee_option;
                $residential->sinking_fund = $residential_sinking_fund;
                $residential->sinking_fund_option = $residential_sinking_fund_option;
                $residential->save();

                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    $delete_old_residential_extra = ResidentialExtraDraft::where('file_id', $residential->file_id)->where('strata_id', $residential->strata_id)->delete();
                } else {
                    $delete_old_residential_extra = ResidentialExtra::where('file_id', $residential->file_id)->where('strata_id', $residential->strata_id)->delete();
                }
                if (empty($data['residential_maintenance_fee_is_custom']) == false) {
                    $total_residential_custom = count($data['residential_maintenance_fee_is_custom']);
                    if ($total_residential_custom > 0) {
                        for ($i = 0; $i < $total_residential_custom; $i++) {
                            $custom_residential =  new ResidentialExtra();
                            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                                $custom_residential =  new ResidentialExtraDraft();
                            }
                            $custom_residential->file_id = $residential->file_id;
                            $custom_residential->strata_id = $residential->strata_id;
                            $custom_residential->unit_no = $data['residential_unit_no_is_custom'][$i];
                            $custom_residential->maintenance_fee = $data['residential_maintenance_fee_is_custom'][$i];
                            $custom_residential->maintenance_fee_option = $data['residential_maintenance_fee_option_is_custom'][$i];
                            $custom_residential->sinking_fund = $data['residential_sinking_fund_is_custom'][$i];
                            $custom_residential->sinking_fund_option = $data['residential_sinking_fund_option_is_custom'][$i];
                            $custom_residential->save();
                        }
                    }
                }
            } else {
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    ResidentialDraft::where('file_id', $files->id)->delete();
                } else {
                    Residential::where('file_id', $files->id)->delete();
                }
            }

            if ($strata->is_commercial) {
                $commercial->strata_id = $strata->id;
                $commercial->unit_no = $commercial_unit_no;
                $commercial->maintenance_fee = $commercial_maintenance_fee;
                $commercial->maintenance_fee_option = $commercial_maintenance_fee_option;
                $commercial->sinking_fund = $commercial_sinking_fund;
                $commercial->sinking_fund_option = $commercial_sinking_fund_option;
                $commercial->save();

                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    $delete_old_commercial_extra = CommercialExtraDraft::where('file_id', $commercial->file_id)->where('strata_id', $commercial->strata_id)->delete();
                } else {
                    $delete_old_commercial_extra = CommercialExtra::where('file_id', $commercial->file_id)->where('strata_id', $commercial->strata_id)->delete();
                }
                if (empty($data['commercial_maintenance_fee_is_custom']) == false) {
                    $total_commercial_custom = count($data['commercial_maintenance_fee_is_custom']);
                    if ($total_commercial_custom > 0) {
                        for ($i = 0; $i < $total_commercial_custom; $i++) {
                            $custom_commercial =  new CommercialExtra();
                            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                                $custom_commercial =  new CommercialExtraDraft();
                            }
                            $custom_commercial->file_id = $commercial->file_id;
                            $custom_commercial->strata_id = $commercial->strata_id;
                            $custom_commercial->unit_no = $data['commercial_unit_no_is_custom'][$i];
                            $custom_commercial->maintenance_fee = $data['commercial_maintenance_fee_is_custom'][$i];
                            $custom_commercial->maintenance_fee_option = $data['commercial_maintenance_fee_option_is_custom'][$i];
                            $custom_commercial->sinking_fund = $data['commercial_sinking_fund_is_custom'][$i];
                            $custom_commercial->sinking_fund_option = $data['commercial_sinking_fund_option_is_custom'][$i];
                            $custom_commercial->save();
                        }
                    }
                }
            } else {
                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    CommercialDraft::where('file_id', $files->id)->delete();
                } else {
                    Commercial::where('file_id', $files->id)->delete();
                }
            }

            $facility->strata_id = $strata->id;
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
            $facility->save();

            # Audit Trail
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                $remarks = 'Strata Info (' . $files->file_no . ')' . $this->module['audit']['text']['jmb_submit_updated'];
                $this->addAudit($files->id, "COB File", $remarks);
            } else {
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Strata Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->id, "COB File", $remarks);
                }
            }

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function deleteStrataFile()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['strata']['file_delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $strata = Strata::findOrFail($id);
            $strata->file_url = "";
            $deleted = $strata->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($strata->file_id);
                $remarks = 'Strata Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }

            // } else {
            //     return "false";
            // }
        }
    }

    public function viewManagement($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::findOrFail(Helper::decode($id));
        $management = Management::where('file_id', $file->id)->first();
        $management_developer = ManagementDeveloper::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_jmb = ManagementJMB::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_mc = ManagementMC::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_agent = ManagementAgent::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $management_others = ManagementOthers::where('management_id', $management->id)->where('file_id', $file->id)->first();
        $image = OtherDetails::where('file_id', $file->id)->first();

        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $agent = Agent::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $disallow = Helper::isAllow($file->id, $file->company_id);

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
            'management_developer' => $management_developer,
            'management_jmb' => $management_jmb,
            'management_mc' => $management_mc,
            'management_agent' => $management_agent,
            'management_others' => $management_others,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.view_management', $viewData);
    }

    public function management($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));

        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            $house_scheme = HouseSchemeDraft::where('file_id', $files->id)->first();
            $management = ManagementDraft::where('file_id', $files->id)->first();
            $image = OtherDetailsDraft::where('file_id', $files->id)->first();

            if (count($house_scheme) <= 0) {
                $house_scheme = HouseScheme::where('file_id', $files->id)->first();
            }
            if (count($management) <= 0) {
                $management = Management::where('file_id', $files->id)->first();
            }
            if (count($image) <= 0) {
                $image = OtherDetails::where('file_id', $files->id)->first();
            }
        } else {
            $house_scheme = HouseScheme::where('file_id', $files->id)->first();
            $management = Management::where('file_id', $files->id)->first();
            $image = OtherDetails::where('file_id', $files->id)->first();
        }

        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $agent = Agent::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $disallow = Helper::isAllow($files->id, $files->company_id);

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'city' => $city,
            'country' => $country,
            'state' => $state,
            'file' => $files,
            'agent' => $agent,
            'house_scheme' => $house_scheme,
            'management' => $management,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_management', $viewData);
    }

    public function submitUpdateManagement()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['management']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id'], $this->module['cob']['file']['name']));
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                $management = ManagementDraft::firstOrNew(array('file_id' => $files->id));
                $management->reference_id = $data['reference_id'];

                $developer = ManagementDeveloperDraft::firstOrNew(array('file_id' => $files->id));
                $jmb = ManagementJMBDraft::firstOrNew(array('file_id' => $files->id));
                $mc = ManagementMCDraft::firstOrNew(array('file_id' => $files->id));
                $agent = ManagementAgentDraft::firstOrNew(array('file_id' => $files->id));
                $others = ManagementOthersDraft::firstOrNew(array('file_id' => $files->id));

                $this->createOrUpdateFileDraft($files);
                /**
                 * Add Notification & send email to COB and JMB
                 */
                $strata = $files->strata;
                $notify_data['file_id'] = $files->id;
                $notify_data['route'] = route('cob.file.management.edit', Request::get('file_id'));
                $notify_data['cob_route'] = route('cob.file.draft.management.edit', Request::get('file_id'));
                $notify_data['strata'] = "You";
                $notify_data['strata_name'] = $strata->name != "" ? $strata->name : $files->file_no;
                $notify_data['title'] = "COB File Management";
                $notify_data['module'] = "Management";

                (new NotificationService())->store($notify_data);
            } else {
                $management = Management::firstOrNew(array('file_id' => $files->id));
                $current_developer = ManagementDeveloper::where('file_id', $files->id)->get();
                $current_liquidator = ManagementLiquidator::where('file_id', $files->id)->get();
                $current_jmb = ManagementJMB::where('file_id', $files->id)->get();
                $current_mc = ManagementMC::where('file_id', $files->id)->get();
                $current_agent = ManagementAgent::where('file_id', $files->id)->get();
                $current_others = ManagementOthers::where('file_id', $files->id)->get();
            }

            /** Arrange audit fields changes */
            $audit_fields_changed = '';
            if (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper()) {
                $is_developer_field = ((!empty($data['is_developer']) && !$management->is_developer) || (empty($data['is_developer']) && $management->is_developer)) ? "management developer" : "";
                $liquidator_field = ((!empty($data['liquidator']) && !$management->liquidator) || (empty($data['liquidator']) && $management->liquidator)) ? "management liquidator" : "";
                $is_jmb_field = ((!empty($data['is_jmb']) && !$management->is_jmb) || (empty($data['is_jmb']) && $management->is_jmb)) ? "management jmb" : "";
                $is_mc_field = ((!empty($data['is_mc']) && !$management->is_mc) || (empty($data['is_mc']) && $management->is_mc)) ? "management mc" : "";
                $is_agent_field = ((!empty($data['is_agent']) && !$management->is_agent) || (empty($data['is_agent']) && $management->is_agent)) ? "management agent" : "";
                $is_others_field = ((!empty($data['is_others']) && !$management->is_others) || (empty($data['is_others']) && $management->is_others)) ? "management others" : "";
                $is_no_management_field = ((!empty($data['no_management']) && !$management->no_management) || (empty($data['no_management']) && $management->no_management)) ? "management no management" : "";
                $under_10_units_field = ((!empty($data['under_10_units']) && !$management->under_10_units) || (empty($data['under_10_units']) && $management->under_10_units)) ? "management under 10 units" : "";
                $bankruptcy_field = ((!empty($data['bankruptcy']) && !$management->bankruptcy) || (empty($data['bankruptcy']) && $management->bankruptcy)) ? "management bankruptcy" : "";

                if (
                    !empty($is_developer_field) || !empty($liquidator_field) || !empty($is_jmb_field) || !empty($is_mc_field) || !empty($is_agent_field) || !empty($is_others_field)
                    || !empty($is_no_management_field) || !empty($under_10_units_field) || !empty($bankruptcy_field)
                ) {
                    $audit_fields_changed .= "<br><ul>";
                }
                /** No Management */
                if (!empty($is_no_management_field)) {
                    if (!empty($data['no_management'])) {
                        $audit_fields_changed .= "<li> No Management : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> No Management : data removed </li>";
                    }
                } else {
                    if ($management->no_management) {
                        /** Data Updated */
                        $start_date = !empty($data['no_management_date_start']) ? Carbon::createFromFormat('d-m-Y', $data['no_management_date_start'])->format('Y-m-d') : "";
                        $end_date = !empty($data['no_management_date_end']) ? Carbon::createFromFormat('d-m-Y', $data['no_management_date_end'])->format('Y-m-d') : "";
                        $new_line = '';
                        if (!empty($start_date)) {
                            $new_line .= $management->start != $start_date ? "start date, " : "";
                        }
                        if (!empty($end_date)) {
                            $new_line .= $management->end != $end_date ? "end date, " : "";
                        }
                        if (!empty($new_line)) {
                            $audit_fields_changed .= "<li> No Management : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** Strata Under 10 unit */
                if (!empty($under_10_units_field)) {
                    if (!empty($data['under_10_units'])) {
                        $audit_fields_changed .= "<li> Strata Under 10 unit : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Strata Under 10 unit : data removed </li>";
                    }
                } else {
                    if ($management->no_management) {
                        /** Data Updated */
                        $under_10_units_remarks_field = ((!empty($data['under_10_units_remarks']) && !$management->under_10_units_remarks) || (empty($data['under_10_units_remarks']) && $management->under_10_units_remarks)) ? "management strata under 10 units remarks" : "";
                        $new_line = '';
                        if (!empty($under_10_units_remarks_field)) {
                            $new_line .= $management->start != $start_date ? "start date, " : "";
                        }
                        if (!empty($new_line)) {
                            $audit_fields_changed .= "<li> Strata Under 10 unit : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** Bankruptcy */
                if (!empty($bankruptcy_field)) {
                    if (!empty($data['bankruptcy'])) {
                        $audit_fields_changed .= "<li> Bankruptcy : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Bankruptcy : data removed </li>";
                    }
                } else {
                    if ($management->no_management) {
                        /** Data Updated */
                        $bankruptcy_remarks_field = ((!empty($data['bankruptcy_remarks']) && !$management->bankruptcy_remarks) || (empty($data['bankruptcy_remarks']) && $management->bankruptcy_remarks)) ? "management bankruptcys remarks" : "";
                        $new_line = '';
                        if (!empty($bankruptcy_remarks_field)) {
                            $new_line .= $management->start != $start_date ? "start date, " : "";
                        }
                        if (!empty($new_line)) {
                            $audit_fields_changed .= "<li> Bankruptcy : (";
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** Developer */
                if (!empty($is_developer_field)) {
                    if (!empty($data['is_developer'])) {
                        $audit_fields_changed .= "<li> Developer : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Developer : data removed </li>";
                    }
                } else {
                    if ($management->is_developer) {
                        for ($i = 0; $i < count($data['developer_name']); $i++) {
                            $data['developer'][$i]['name'] = $data['developer_name'][$i];
                            $data['developer'][$i]['address_1'] = $data['developer_address1'][$i];
                            $data['developer'][$i]['address_2'] = $data['developer_address2'][$i];
                            $data['developer'][$i]['address_3'] = $data['developer_address3'][$i];
                            $data['developer'][$i]['address_4'] = $data['developer_address4'][$i];
                            $data['developer'][$i]['city'] = $data['developer_city'][$i];
                            $data['developer'][$i]['poscode'] = $data['developer_poscode'][$i];
                            $data['developer'][$i]['state'] = $data['developer_state'][$i];
                            $data['developer'][$i]['country'] = $data['developer_country'][$i];
                            $data['developer'][$i]['phone_no'] = $data['developer_phone_no'][$i];
                            $data['developer'][$i]['fax_no'] = $data['developer_fax_no'][$i];
                            $data['developer'][$i]['remarks'] = $data['developer_remarks'][$i];
                        }
                        $management_developer_differents = Helper::check_diff_multi($current_developer->toArray(), $data['developer']);
                        /** Data Updated */
                        if (count($management_developer_differents)) {
                            $audit_fields_changed .= "<li>Developer : (";
                            $new_line = '';
                            foreach ($management_developer_differents as $mdd_key => $mdd) {
                                if (is_array($mdd) && count($mdd)) {
                                    foreach ($mdd as $mdd_data_key => $mdd_data) {
                                        if (!in_array($mdd_data_key, ['id', 'file_id', 'management_id'])) {
                                            $name = str_replace("_", " ", $mdd_data_key);
                                            $new_line .= $name . '=' . $mdd_data . ', ';
                                        }
                                    }
                                } else {
                                    if (!empty($mdd)) {
                                        $name = str_replace("_", " ", $mdd_key);
                                        $new_line .= $name . '=' . $mdd . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** Liquidator */
                if (!empty($liquidator_field)) {
                    if (!empty($data['liquidator'])) {
                        $audit_fields_changed .= "<li> Liquidator : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Liquidator : data removed </li>";
                    }
                } else {
                    if ($management->liquidator) {
                        for ($i = 0; $i < count($data['liquidator_name']); $i++) {
                            $data['data_liquidator'][$i]['name'] = $data['liquidator_name'][$i];
                            $data['data_liquidator'][$i]['address_1'] = $data['liquidator_address1'][$i];
                            $data['data_liquidator'][$i]['address_2'] = $data['liquidator_address2'][$i];
                            $data['data_liquidator'][$i]['address_3'] = $data['liquidator_address3'][$i];
                            $data['data_liquidator'][$i]['address_4'] = $data['liquidator_address4'][$i];
                            $data['data_liquidator'][$i]['city'] = $data['liquidator_city'][$i];
                            $data['data_liquidator'][$i]['poscode'] = $data['liquidator_poscode'][$i];
                            $data['data_liquidator'][$i]['state'] = $data['liquidator_state'][$i];
                            $data['data_liquidator'][$i]['country'] = $data['liquidator_country'][$i];
                            $data['data_liquidator'][$i]['phone_no'] = $data['liquidator_phone_no'][$i];
                            $data['data_liquidator'][$i]['fax_no'] = $data['liquidator_fax_no'][$i];
                            $data['data_liquidator'][$i]['remarks'] = $data['liquidator_remarks'][$i];
                        }
                        $management_liquidator_differents = Helper::check_diff_multi($current_liquidator->toArray(), $data['data_liquidator']);
                        /** Data Updated */
                        if (count($management_liquidator_differents)) {
                            $audit_fields_changed .= "<li>Liquidator : (";
                            $new_line = '';
                            foreach ($management_liquidator_differents as $mdl_key => $mdl) {
                                if (is_array($mdl) && count($mdl)) {
                                    foreach ($mdl as $mdl_data_key => $mdl_data) {
                                        if (!in_array($mdl_data_key, ['id', 'file_id', 'management_id'])) {
                                            $name = str_replace("_", " ", $mdl_data_key);
                                            $new_line .= $name . '=' . $mdl_data . ', ';
                                        }
                                    }
                                } else {
                                    if (!empty($mdl)) {
                                        $name = str_replace("_", " ", $mdl_key);
                                        $new_line .= $name . '=' . $mdl . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** JMB */
                if (!empty($is_jmb_field)) {
                    if (!empty($data['is_jmb'])) {
                        $audit_fields_changed .= "<li> JMB : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> JMB : data removed </li>";
                    }
                } else {
                    if ($management->is_jmb) {
                        for ($i = 0; $i < count($data['jmb_name']); $i++) {
                            $data['jmb'][$i]['date_formed'] = !empty($data['jmb_date_formed'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['jmb_date_formed'][$i])->format('Y-m-d') : "";
                            $data['jmb'][$i]['certificate_no'] = $data['jmb_certificate_no'][$i];
                            $data['jmb'][$i]['name'] = $data['jmb_name'][$i];
                            $data['jmb'][$i]['address_1'] = $data['jmb_address1'][$i];
                            $data['jmb'][$i]['address_2'] = $data['jmb_address2'][$i];
                            $data['jmb'][$i]['address_3'] = $data['jmb_address3'][$i];
                            $data['jmb'][$i]['city'] = $data['jmb_city'][$i];
                            $data['jmb'][$i]['poscode'] = $data['jmb_poscode'][$i];
                            $data['jmb'][$i]['state'] = $data['jmb_state'][$i];
                            $data['jmb'][$i]['country'] = $data['jmb_country'][$i];
                            $data['jmb'][$i]['phone_no'] = $data['jmb_phone_no'][$i];
                            $data['jmb'][$i]['fax_no'] = $data['jmb_fax_no'][$i];
                            $data['jmb'][$i]['email'] = $data['jmb_email'][$i];
                        }
                        $management_jmb_differents = Helper::check_diff_multi($current_jmb->toArray(), $data['jmb']);
                        /** Data Updated */
                        if (count($management_jmb_differents)) {
                            $audit_fields_changed .= "<li>JMB : (";
                            $new_line = '';
                            foreach ($management_jmb_differents as $mjd_key => $mjd) {
                                if (is_array($mjd) && count($mjd)) {
                                    foreach ($mjd as $mjd_data_key => $mjd_data) {
                                        if (!in_array($mjd_data_key, ['id', 'file_id', 'management_id'])) {
                                            $name = str_replace("_", " ", $mjd_data_key);
                                            $new_line .= $name . '=' . $mjd_data . ', ';
                                        }
                                    }
                                } else {
                                    if (!empty($mjd)) {
                                        $name = str_replace("_", " ", $mjd_key);
                                        $new_line .= $name . '=' . $mjd . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** MC */
                if (!empty($is_mc_field)) {
                    if (!empty($data['is_mc'])) {
                        $audit_fields_changed .= "<li> MC : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> MC : data removed </li>";
                    }
                } else {
                    if ($management->is_mc) {
                        for ($i = 0; $i < count($data['mc_name']); $i++) {
                            $data['mc'][$i]['date_formed'] = !empty($data['mc_date_formed'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['mc_date_formed'][$i])->format('Y-m-d') : "";
                            $data['mc'][$i]['certificate_no'] = $data['mc_certificate_no'][$i];
                            $data['mc'][$i]['first_agm'] = !empty($data['mc_first_agm'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['mc_first_agm'][$i])->format('Y-m-d') : "";
                            $data['mc'][$i]['name'] = $data['mc_name'][$i];
                            $data['mc'][$i]['address_1'] = $data['mc_address1'][$i];
                            $data['mc'][$i]['address_2'] = $data['mc_address2'][$i];
                            $data['mc'][$i]['address_3'] = $data['mc_address3'][$i];
                            $data['mc'][$i]['city'] = $data['mc_city'][$i];
                            $data['mc'][$i]['poscode'] = $data['mc_poscode'][$i];
                            $data['mc'][$i]['state'] = $data['mc_state'][$i];
                            $data['mc'][$i]['country'] = $data['mc_country'][$i];
                            $data['mc'][$i]['phone_no'] = $data['mc_phone_no'][$i];
                            $data['mc'][$i]['fax_no'] = $data['mc_fax_no'][$i];
                            $data['mc'][$i]['email'] = $data['mc_email'][$i];
                        }
                        $management_mc_differents = Helper::check_diff_multi($current_mc->toArray(), $data['mc']);
                        /** Data Updated */
                        if (count($management_mc_differents)) {
                            $audit_fields_changed .= "<li>MC : (";
                            $new_line = '';
                            foreach ($management_mc_differents as $mcd_key => $mcd) {
                                if (is_array($mcd) && count($mcd)) {
                                    foreach ($mcd as $mcd_data_key => $mcd_data) {
                                        if (!in_array($mcd_data_key, ['id', 'file_id', 'management_id'])) {
                                            $name = str_replace("_", " ", $mcd_data_key);
                                            $new_line .= $name . '=' . $mcd_data . ', ';
                                        }
                                    }
                                } else {
                                    if (!empty($mcd)) {
                                        $name = str_replace("_", " ", $mcd_key);
                                        $new_line .= $name . '=' . $mcd . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** Agent */
                if (!empty($is_agent_field)) {
                    if (!empty($data['is_agent'])) {
                        $audit_fields_changed .= "<li> Agent : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Agent : data removed </li>";
                    }
                } else {
                    if ($management->is_agent) {
                        for ($i = 0; $i < count($data['agent_name']); $i++) {
                            $data['agent'][$i]['selected_by'] = $data['agent_selected_by'][$i];
                            $data['agent'][$i]['name'] = $data['agent_name'][$i];
                            $data['agent'][$i]['address_1'] = $data['agent_address1'][$i];
                            $data['agent'][$i]['address_2'] = $data['agent_address2'][$i];
                            $data['agent'][$i]['address_3'] = $data['agent_address3'][$i];
                            $data['agent'][$i]['city'] = $data['agent_city'][$i];
                            $data['agent'][$i]['poscode'] = $data['agent_poscode'][$i];
                            $data['agent'][$i]['state'] = $data['agent_state'][$i];
                            $data['agent'][$i]['country'] = $data['agent_country'][$i];
                            $data['agent'][$i]['phone_no'] = $data['agent_phone_no'][$i];
                            $data['agent'][$i]['fax_no'] = $data['agent_fax_no'][$i];
                            $data['agent'][$i]['email'] = $data['agent_email'][$i];
                        }
                        $management_agent_differents = Helper::check_diff_multi($current_agent->toArray(), $data['agent']);
                        /** Data Updated */
                        if (count($management_agent_differents)) {
                            $audit_fields_changed .= "<li>Agent : (";
                            $new_line = '';
                            foreach ($management_agent_differents as $mad_key => $mad) {
                                if (is_array($mad) && count($mad)) {
                                    foreach ($mad as $mad_data_key => $mad_data) {
                                        if (!in_array($mad_data_key, ['id', 'file_id', 'management_id'])) {
                                            $name = str_replace("_", " ", $mad_data_key);
                                            $new_line .= $name . '=' . $mad_data . ', ';
                                        }
                                    }
                                } else {
                                    if (!empty($mad)) {
                                        $name = str_replace("_", " ", $mad_key);
                                        $new_line .= $name . '=' . $mad . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                /** Others */
                if (!empty($is_others_field)) {
                    if (!empty($data['is_others'])) {
                        $audit_fields_changed .= "<li> Others : new data inserted </li>";
                    } else {
                        $audit_fields_changed .= "<li> Others : data removed </li>";
                    }
                } else {
                    if ($management->is_others) {
                        for ($i = 0; $i < count($data['others_name']); $i++) {
                            $data['others'][$i]['name'] = $data['others_name'][$i];
                            $data['others'][$i]['address_1'] = $data['others_address1'][$i];
                            $data['others'][$i]['address_2'] = $data['others_address2'][$i];
                            $data['others'][$i]['address_3'] = $data['others_address3'][$i];
                            $data['others'][$i]['city'] = $data['others_city'][$i];
                            $data['others'][$i]['poscode'] = $data['others_poscode'][$i];
                            $data['others'][$i]['state'] = $data['others_state'][$i];
                            $data['others'][$i]['country'] = $data['others_country'][$i];
                            $data['others'][$i]['phone_no'] = $data['others_phone_no'][$i];
                            $data['others'][$i]['fax_no'] = $data['others_fax_no'][$i];
                            $data['others'][$i]['email'] = $data['others_email'][$i];
                        }
                        $management_other_differents = Helper::check_diff_multi($current_others->toArray(), $data['others']);
                        /** Data Updated */
                        if (count($management_other_differents)) {
                            $audit_fields_changed .= "<li>Others : (";
                            $new_line = '';
                            foreach ($management_other_differents as $mod_key => $mod) {
                                if (is_array($mod) && count($mod)) {
                                    foreach ($mod as $mod_data_key => $mod_data) {
                                        if (!in_array($mod_data_key, ['id', 'file_id', 'management_id'])) {
                                            $name = str_replace("_", " ", $mod_data_key);
                                            $new_line .= $name . '=' . $mod_data . ', ';
                                        }
                                    }
                                } else {
                                    if (!empty($mod)) {
                                        $name = str_replace("_", " ", $mod_key);
                                        $new_line .= $name . '=' . $mod . ', ';
                                    }
                                }
                            }
                            $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li>";
                        }
                    }
                }
                if (!empty($is_developer_field) || !empty($is_jmb_field) || !empty($is_mc_field) || !empty($is_agent_field) || !empty($is_others_field) || !empty($is_no_management_field)) {
                    $audit_fields_changed .= "</ul>";
                }
            }
            /** End Arrange audit fields changes */

            // management
            $management->is_developer = !empty($data['is_developer']) ? true : false;
            $management->liquidator = !empty($data['liquidator']) ? true : false;
            $management->is_jmb = !empty($data['is_jmb']) ? true : false;
            $management->is_mc = !empty($data['is_mc']) ? true : false;
            $management->is_agent = !empty($data['is_agent']) ? true : false;
            $management->is_others = !empty($data['is_others']) ? true : false;
            $management->no_management = !empty($data['no_management']) ? true : false;
            $management->under_10_units = !empty($data['under_10_units']) ? true : false;
            $management->bankruptcy = !empty($data['bankruptcy']) ? true : false;
            $management->save();

            /** No Management */
            if ($management->no_management) {
                $management->start = !empty($data['no_management_date_start']) ? Carbon::createFromFormat('d-m-Y', $data['no_management_date_start'])->format('Y-m-d') : "";
                $management->end = !empty($data['no_management_date_end']) ? Carbon::createFromFormat('d-m-Y', $data['no_management_date_end'])->format('Y-m-d') : "";
                $management->save();
            }

            /** Strata under 10 units */
            if ($management->under_10_units) {
                $management->under_10_units_remarks = !empty($data['under_10_units_remarks']) ? $data['under_10_units_remarks'] : "";
                $management->save();
            }

            /** Bankruptcy */
            if ($management->bankruptcy) {
                $management->bankruptcy_remarks = !empty($data['bankruptcy_remarks']) ? $data['bankruptcy_remarks'] : "";
                $management->save();
            }

            // developer
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                ManagementDeveloperDraft::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['developer_name']); $i++) {
                    $developer = new ManagementDeveloperDraft;
                    $developer->file_id = $files->id;
                    $developer->management_id = $management->id;
                    $developer->name = $data['developer_name'][$i];
                    $developer->address_1 = $data['developer_address1'][$i];
                    $developer->address_2 = $data['developer_address2'][$i];
                    $developer->address_3 = $data['developer_address3'][$i];
                    $developer->address_4 = $data['developer_address4'][$i];
                    $developer->city = $data['developer_city'][$i];
                    $developer->poscode = $data['developer_poscode'][$i];
                    $developer->state = $data['developer_state'][$i];
                    $developer->country = $data['developer_country'][$i];
                    $developer->phone_no = $data['developer_phone_no'][$i];
                    $developer->fax_no = $data['developer_fax_no'][$i];
                    $developer->remarks = $data['developer_remarks'][$i];
                    $developer->save();
                }
            } else {
                ManagementDeveloper::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['developer_name']); $i++) {
                    $developer = new ManagementDeveloper;
                    $developer->file_id = $files->id;
                    $developer->management_id = $management->id;
                    $developer->name = $data['developer_name'][$i];
                    $developer->address_1 = $data['developer_address1'][$i];
                    $developer->address_2 = $data['developer_address2'][$i];
                    $developer->address_3 = $data['developer_address3'][$i];
                    $developer->address_4 = $data['developer_address4'][$i];
                    $developer->city = $data['developer_city'][$i];
                    $developer->poscode = $data['developer_poscode'][$i];
                    $developer->state = $data['developer_state'][$i];
                    $developer->country = $data['developer_country'][$i];
                    $developer->phone_no = $data['developer_phone_no'][$i];
                    $developer->fax_no = $data['developer_fax_no'][$i];
                    $developer->remarks = $data['developer_remarks'][$i];
                    $developer->save();
                }
            }

            // liquidator
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                ManagementLiquidatorDraft::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['liquidator_name']); $i++) {
                    $liquidator = new ManagementLiquidatorDraft;
                    $liquidator->file_id = $files->id;
                    $liquidator->management_id = $management->id;
                    $liquidator->name = $data['liquidator_name'][$i];
                    $liquidator->address_1 = $data['liquidator_address1'][$i];
                    $liquidator->address_2 = $data['liquidator_address2'][$i];
                    $liquidator->address_3 = $data['liquidator_address3'][$i];
                    $liquidator->address_4 = $data['liquidator_address4'][$i];
                    $liquidator->city = $data['liquidator_city'][$i];
                    $liquidator->poscode = $data['liquidator_poscode'][$i];
                    $liquidator->state = $data['liquidator_state'][$i];
                    $liquidator->country = $data['liquidator_country'][$i];
                    $liquidator->phone_no = $data['liquidator_phone_no'][$i];
                    $liquidator->fax_no = $data['liquidator_fax_no'][$i];
                    $liquidator->remarks = $data['liquidator_remarks'][$i];
                    $liquidator->save();
                }
            } else {
                ManagementLiquidator::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['liquidator_name']); $i++) {
                    $liquidator = new ManagementLiquidator;
                    $liquidator->file_id = $files->id;
                    $liquidator->management_id = $management->id;
                    $liquidator->name = $data['liquidator_name'][$i];
                    $liquidator->address_1 = $data['liquidator_address1'][$i];
                    $liquidator->address_2 = $data['liquidator_address2'][$i];
                    $liquidator->address_3 = $data['liquidator_address3'][$i];
                    $liquidator->address_4 = $data['liquidator_address4'][$i];
                    $liquidator->city = $data['liquidator_city'][$i];
                    $liquidator->poscode = $data['liquidator_poscode'][$i];
                    $liquidator->state = $data['liquidator_state'][$i];
                    $liquidator->country = $data['liquidator_country'][$i];
                    $liquidator->phone_no = $data['liquidator_phone_no'][$i];
                    $liquidator->fax_no = $data['liquidator_fax_no'][$i];
                    $liquidator->remarks = $data['liquidator_remarks'][$i];
                    $liquidator->save();
                }
            }

            // jmb
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                ManagementJMBDraft::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['jmb_name']); $i++) {
                    $jmb = new ManagementJMBDraft;
                    $jmb->file_id = $files->id;
                    $jmb->management_id = $management->id;
                    $jmb->date_formed = !empty($data['jmb_date_formed'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['jmb_date_formed'][$i])->format('Y-m-d') : "";
                    $jmb->certificate_no = $data['jmb_certificate_no'][$i];
                    $jmb->name = $data['jmb_name'][$i];
                    $jmb->address1 = $data['jmb_address1'][$i];
                    $jmb->address2 = $data['jmb_address2'][$i];
                    $jmb->address3 = $data['jmb_address3'][$i];
                    $jmb->city = $data['jmb_city'][$i];
                    $jmb->poscode = $data['jmb_poscode'][$i];
                    $jmb->state = $data['jmb_state'][$i];
                    $jmb->country = $data['jmb_country'][$i];
                    $jmb->phone_no = $data['jmb_phone_no'][$i];
                    $jmb->fax_no = $data['jmb_fax_no'][$i];
                    $jmb->email = $data['jmb_email'][$i];
                    $jmb->save();
                }
            } else {
                ManagementJMB::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['jmb_name']); $i++) {
                    $jmb = new ManagementJMB;
                    $jmb->file_id = $files->id;
                    $jmb->management_id = $management->id;
                    $jmb->date_formed = !empty($data['jmb_date_formed'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['jmb_date_formed'][$i])->format('Y-m-d') : "";
                    $jmb->certificate_no = $data['jmb_certificate_no'][$i];
                    $jmb->name = $data['jmb_name'][$i];
                    $jmb->address1 = $data['jmb_address1'][$i];
                    $jmb->address2 = $data['jmb_address2'][$i];
                    $jmb->address3 = $data['jmb_address3'][$i];
                    $jmb->city = $data['jmb_city'][$i];
                    $jmb->poscode = $data['jmb_poscode'][$i];
                    $jmb->state = $data['jmb_state'][$i];
                    $jmb->country = $data['jmb_country'][$i];
                    $jmb->phone_no = $data['jmb_phone_no'][$i];
                    $jmb->fax_no = $data['jmb_fax_no'][$i];
                    $jmb->email = $data['jmb_email'][$i];
                    $jmb->save();
                }
            }

            // mc
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                ManagementMCDraft::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['mc_name']); $i++) {
                    $mc = new ManagementMCDraft;
                    $mc->file_id = $files->id;
                    $mc->management_id = $management->id;
                    $mc->date_formed = !empty($data['mc_date_formed'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['mc_date_formed'][$i])->format('Y-m-d') : "";
                    $mc->certificate_no = $data['mc_certificate_no'][$i];
                    $mc->first_agm = !empty($data['mc_first_agm'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['mc_first_agm'][$i])->format('Y-m-d') : "";
                    $mc->name = $data['mc_name'][$i];
                    $mc->address1 = $data['mc_address1'][$i];
                    $mc->address2 = $data['mc_address2'][$i];
                    $mc->address3 = $data['mc_address3'][$i];
                    $mc->city = $data['mc_city'][$i];
                    $mc->poscode = $data['mc_poscode'][$i];
                    $mc->state = $data['mc_state'][$i];
                    $mc->country = $data['mc_country'][$i];
                    $mc->phone_no = $data['mc_phone_no'][$i];
                    $mc->fax_no = $data['mc_fax_no'][$i];
                    $mc->email = $data['mc_email'][$i];
                    $mc->save();
                }
            } else {
                ManagementMC::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['mc_name']); $i++) {
                    $mc = new ManagementMC;
                    $mc->file_id = $files->id;
                    $mc->management_id = $management->id;
                    $mc->date_formed = !empty($data['mc_date_formed'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['mc_date_formed'][$i])->format('Y-m-d') : "";
                    $mc->certificate_no = $data['mc_certificate_no'][$i];
                    $mc->first_agm = !empty($data['mc_first_agm'][$i]) ? Carbon::createFromFormat('d-m-Y', $data['mc_first_agm'][$i])->format('Y-m-d') : "";
                    $mc->name = $data['mc_name'][$i];
                    $mc->address1 = $data['mc_address1'][$i];
                    $mc->address2 = $data['mc_address2'][$i];
                    $mc->address3 = $data['mc_address3'][$i];
                    $mc->city = $data['mc_city'][$i];
                    $mc->poscode = $data['mc_poscode'][$i];
                    $mc->state = $data['mc_state'][$i];
                    $mc->country = $data['mc_country'][$i];
                    $mc->phone_no = $data['mc_phone_no'][$i];
                    $mc->fax_no = $data['mc_fax_no'][$i];
                    $mc->email = $data['mc_email'][$i];
                    $mc->save();
                }
            }

            // agent
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                ManagementAgentDraft::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['agent_name']); $i++) {
                    $agent = new ManagementAgentDraft;
                    $agent->file_id = $files->id;
                    $agent->management_id = $management->id;
                    $agent->selected_by = $data['agent_selected_by'][$i];
                    $agent->agent = $data['agent_name'][$i];
                    $agent->address1 = $data['agent_address1'][$i];
                    $agent->address2 = $data['agent_address2'][$i];
                    $agent->address3 = $data['agent_address3'][$i];
                    $agent->city = $data['agent_city'][$i];
                    $agent->poscode = $data['agent_poscode'][$i];
                    $agent->state = $data['agent_state'][$i];
                    $agent->country = $data['agent_country'][$i];
                    $agent->phone_no = $data['agent_phone_no'][$i];
                    $agent->fax_no = $data['agent_fax_no'][$i];
                    $agent->email = $data['agent_email'][$i];
                    $agent->save();
                }
            } else {
                ManagementAgent::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['agent_name']); $i++) {
                    $agent = new ManagementAgent;
                    $agent->file_id = $files->id;
                    $agent->management_id = $management->id;
                    $agent->selected_by = $data['agent_selected_by'][$i];
                    $agent->agent = $data['agent_name'][$i];
                    $agent->address1 = $data['agent_address1'][$i];
                    $agent->address2 = $data['agent_address2'][$i];
                    $agent->address3 = $data['agent_address3'][$i];
                    $agent->city = $data['agent_city'][$i];
                    $agent->poscode = $data['agent_poscode'][$i];
                    $agent->state = $data['agent_state'][$i];
                    $agent->country = $data['agent_country'][$i];
                    $agent->phone_no = $data['agent_phone_no'][$i];
                    $agent->fax_no = $data['agent_fax_no'][$i];
                    $agent->email = $data['agent_email'][$i];
                    $agent->save();
                }
            }

            // others
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                ManagementOthersDraft::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['others_name']); $i++) {
                    $others = new ManagementOthersDraft;
                    $others->file_id = $files->id;
                    $others->management_id = $management->id;
                    $others->name = $data['others_name'][$i];
                    $others->address1 = $data['others_address1'][$i];
                    $others->address2 = $data['others_address2'][$i];
                    $others->address3 = $data['others_address3'][$i];
                    $others->city = $data['others_city'][$i];
                    $others->poscode = $data['others_poscode'][$i];
                    $others->state = $data['others_state'][$i];
                    $others->country = $data['others_country'][$i];
                    $others->phone_no = $data['others_phone_no'][$i];
                    $others->fax_no = $data['others_fax_no'][$i];
                    $others->email = $data['others_email'][$i];
                    $others->save();
                }
            } else {
                ManagementOthers::where('file_id', $files->id)->delete();
                for ($i = 0; $i < count($data['others_name']); $i++) {
                    $others = new ManagementOthers;
                    $others->file_id = $files->id;
                    $others->management_id = $management->id;
                    $others->name = $data['others_name'][$i];
                    $others->address1 = $data['others_address1'][$i];
                    $others->address2 = $data['others_address2'][$i];
                    $others->address3 = $data['others_address3'][$i];
                    $others->city = $data['others_city'][$i];
                    $others->poscode = $data['others_poscode'][$i];
                    $others->state = $data['others_state'][$i];
                    $others->country = $data['others_country'][$i];
                    $others->phone_no = $data['others_phone_no'][$i];
                    $others->fax_no = $data['others_fax_no'][$i];
                    $others->email = $data['others_email'][$i];
                    $others->save();
                }
            }

            # Audit Trail
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                $remarks = 'Management Info (' . $files->file_no . ')' . $this->module['audit']['text']['jmb_submit_updated'];
                $this->addAudit($files->id, "COB File", $remarks);
            } else {
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Management Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->id, "COB File", $remarks);
                }
            }

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function viewMonitoring($id)
    {
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            return Redirect::to('updateFile/others/' . $id);
        }
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));
        $monitoring = Monitoring::where('file_id', $files->id)->first();
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $image = OtherDetails::where('file_id', $files->id)->first();
        $disallow = Helper::isAllow($files->id, $files->company_id);

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

    public function monitoring($id)
    {
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            return Redirect::to('updateFile/others/' . $id);
        }

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));
        $monitoring = Monitoring::where('file_id', $files->id)->first();
        $image = OtherDetails::where('file_id', $files->id)->first();
        $designation = Designation::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $disallow = Helper::isAllow($files->id, $files->company_id);

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

        if ($files->company && $files->company->short_name == 'MPKJ') {
            return View::make('page_en.update_monitoring_new', $viewData);
        }

        return View::make('page_en.update_monitoring', $viewData);
    }

    public function submitUpdateMonitoring()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $monitor = Monitoring::firstOrNew(array('file_id' => $files->id));

            $precalculate_plan = $data['precalculate_plan'];
            $buyer_registration = $data['buyer_registration'];
            $certificate_series_no = $data['certificate_series_no'];
            $monitoring_remarks = $data['monitoring_remarks'];

            /** Arrange audit fields changes */
            $precalculate_plan = $data['precalculate_plan'] == $monitor->pre_calculate ? "" : "precalculate plan";
            $buyer_registration = $data['buyer_registration'] == $monitor->buyer_registration ? "" : "buyer registration";
            $certificate_series_no = $data['certificate_series_no'] == $monitor->certificate_no ? "" : "certificate no";
            $monitoring_remarks = $data['monitoring_remarks'] == $monitor->remarks ? "" : "remarks";

            $audit_fields_changed = "";
            if (!empty($precalculate_plan) || !empty($monitoring_remarks) || !empty($buyer_registration) || !empty($certificate_series_no)) {
                $audit_fields_changed .= "<br><ul>";
                $audit_fields_changed .= !empty($precalculate_plan) ? "<li>$precalculate_plan</li>" : "";
                $audit_fields_changed .= !empty($buyer_registration) ? "<li>$buyer_registration</li>" : "";
                $audit_fields_changed .= !empty($monitoring_remarks) ? "<li>$monitoring_remarks</li>" : "";
                $audit_fields_changed .= !empty($certificate_series_no) ? "<li>$certificate_series_no</li>" : "";
                $audit_fields_changed .= "</ul>";
            }
            /** End Arrange audit fields changes */

            $monitor->pre_calculate = $precalculate_plan;
            $monitor->buyer_registration = $buyer_registration;
            $monitor->certificate_no = $certificate_series_no;
            $monitor->remarks = $monitoring_remarks;
            $monitor->save();

            # Audit Trail
            if (!empty($audit_fields_changed)) {
                $remarks = 'Monitoring Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                $this->addAudit($files->id, "COB File", $remarks);
            }

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function addAGMDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['agm_detail']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $agm_detail = new MeetingDocument();

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
            $notice_agm_egm_url = $data['notice_agm_egm_url'];
            $minutes_agm_egm_url = $data['minutes_agm_egm_url'];
            $minutes_ajk_url = $data['minutes_ajk_url'];
            $eligible_vote_url = $data['eligible_vote_url'];
            $attend_meeting_url = $data['attend_meeting_url'];
            $proksi_url = $data['proksi_url'];
            $ajk_info_url = $data['ajk_info_url'];
            $ic_url = $data['ic_url'];
            $purchase_aggrement_url = $data['purchase_aggrement_url'];
            $strata_title_url = $data['strata_title_url'];
            $maintenance_statement_url = $data['maintenance_statement_url'];
            $integrity_pledge_url = $data['integrity_pledge_url'];
            $sworn_statement_url = $data['sworn_statement_url'];
            $report_audited_financial_url = $data['report_audited_financial_url'];
            $house_rules_url = $data['house_rules_url'];
            $type = $data['type'];

            $agm_detail->file_id = $files->id;
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
            $agm_detail->notice_agm_egm_url = $notice_agm_egm_url;
            $agm_detail->minutes_agm_egm_url = $minutes_agm_egm_url;
            $agm_detail->minutes_ajk_url = $minutes_ajk_url;
            $agm_detail->eligible_vote_url = $eligible_vote_url;
            $agm_detail->attend_meeting_url = $attend_meeting_url;
            $agm_detail->proksi_url = $proksi_url;
            $agm_detail->ajk_info_url = $ajk_info_url;
            $agm_detail->ic_url = $ic_url;
            $agm_detail->purchase_aggrement_url = $purchase_aggrement_url;
            $agm_detail->strata_title_url = $strata_title_url;
            $agm_detail->maintenance_statement_url = $maintenance_statement_url;
            $agm_detail->integrity_pledge_url = $integrity_pledge_url;
            $agm_detail->sworn_statement_url = $sworn_statement_url;
            $agm_detail->report_audited_financial_url = $report_audited_financial_url;
            $agm_detail->house_rules_url = $house_rules_url;
            $agm_detail->type = $type;
            $agm_detail->save();

            # Audit Trail
            $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_detail->agm_date)) . $this->module['audit']['text']['data_inserted'];
            $this->addAudit($files->id, "COB File", $remarks);

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function editAGMDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['agm_detail']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $agm_detail = MeetingDocument::findOrFail(Helper::decode($data['id']));

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
            $notice_agm_egm_url = $data['notice_agm_egm_url'];
            $minutes_agm_egm_url = $data['minutes_agm_egm_url'];
            $minutes_ajk_url = $data['minutes_ajk_url'];
            $eligible_vote_url = $data['eligible_vote_url'];
            $attend_meeting_url = $data['attend_meeting_url'];
            $proksi_url = $data['proksi_url'];
            $ajk_info_url = $data['ajk_info_url'];
            $ic_url = $data['ic_url'];
            $purchase_aggrement_url = $data['purchase_aggrement_url'];
            $strata_title_url = $data['strata_title_url'];
            $maintenance_statement_url = $data['maintenance_statement_url'];
            $integrity_pledge_url = $data['integrity_pledge_url'];
            $sworn_statement_url = $data['sworn_statement_url'];
            $report_audited_financial_url = $data['report_audited_financial_url'];
            $house_rules_url = $data['house_rules_url'];

            /** Arrange audit fields changes */
            $audit_fields_changed = '';
            $new_line = '';
            $new_line .= $agm_date != $agm_detail->agm_date ? "agm date, " : "";
            $new_line .= $agm != $agm_detail->agm ? "agm, " : "";
            $new_line .= $egm != $agm_detail->egm ? "egm, " : "";
            $new_line .= $minit_meeting != $agm_detail->minit_meeting ? "minit meeting, " : "";
            $new_line .= $jmc_copy != $agm_detail->jmc_spa ? "jmc copy, " : "";
            $new_line .= $ic_list != $agm_detail->identity_card ? "ic, " : "";
            $new_line .= $attendance_list != $agm_detail->attendance ? "attendance, " : "";
            $new_line .= $audited_financial_report != $agm_detail->financial_report ? "audited financial report, " : "";
            $new_line .= $audit_report != $agm_detail->audit_report ? "audit report, " : "";
            $new_line .= !empty($audit_report_file_url) ? "audit report file, " : "";
            $new_line .= !empty($letter_integrity_url) ? "letter integrity, " : "";
            $new_line .= !empty($letter_bankruptcy_url) ? "letter bankruptcy, " : "";
            $new_line .= $audit_start != $agm_detail->audit_start_date ? "audit start, " : "";
            $new_line .= $audit_end != $agm_detail->audit_end_date ? "audit end, " : "";
            $new_line .= $notice_agm_egm_url != $agm_detail->notice_agm_egm_url ? "notice agm egm, " : "";
            $new_line .= $minutes_agm_egm_url != $agm_detail->minutes_agm_egm_url ? "minutes agm egm, " : "";
            $new_line .= $minutes_ajk_url != $agm_detail->minutes_ajk_url ? "minutes ajk, " : "";
            $new_line .= $eligible_vote_url != $agm_detail->eligible_vote_url ? "eligible vote, " : "";
            $new_line .= $attend_meeting_url != $agm_detail->attend_meeting_url ? "attend meeting, " : "";
            $new_line .= $proksi_url != $agm_detail->proksi_url ? "proksi, " : "";
            $new_line .= $ajk_info_url != $agm_detail->ajk_info_url ? "ajk info, " : "";
            $new_line .= $ic_url != $agm_detail->ic_url ? "ic, " : "";
            $new_line .= $purchase_aggrement_url != $agm_detail->purchase_aggrement_url ? "purchase aggrement, " : "";
            $new_line .= $strata_title_url != $agm_detail->strata_title_url ? "strata title, " : "";
            $new_line .= $maintenance_statement_url != $agm_detail->maintenance_statement_url ? "maintenance statement, " : "";
            $new_line .= $integrity_pledge_url != $agm_detail->integrity_pledge_url ? "integrity pledge, " : "";
            $new_line .= $sworn_statement_url != $agm_detail->sworn_statement_url ? "sworn statement, " : "";
            $new_line .= $report_audited_financial_url != $agm_detail->report_audited_financial_url ? "report audited financial, " : "";
            $new_line .= $house_rules_url != $agm_detail->house_rules_url ? "house rules, " : "";
            if (!empty($new_line)) {
                $audit_fields_changed .= "<br/><ul><li> AGM Detail : (";
                $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
            }
            /** End Arrange audit fields changes */

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
            $agm_detail->notice_agm_egm_url = $notice_agm_egm_url;
            $agm_detail->minutes_agm_egm_url = $minutes_agm_egm_url;
            $agm_detail->minutes_ajk_url = $minutes_ajk_url;
            $agm_detail->eligible_vote_url = $eligible_vote_url;
            $agm_detail->attend_meeting_url = $attend_meeting_url;
            $agm_detail->proksi_url = $proksi_url;
            $agm_detail->ajk_info_url = $ajk_info_url;
            $agm_detail->ic_url = $ic_url;
            $agm_detail->purchase_aggrement_url = $purchase_aggrement_url;
            $agm_detail->strata_title_url = $strata_title_url;
            $agm_detail->maintenance_statement_url = $maintenance_statement_url;
            $agm_detail->integrity_pledge_url = $integrity_pledge_url;
            $agm_detail->sworn_statement_url = $sworn_statement_url;
            $agm_detail->report_audited_financial_url = $report_audited_financial_url;
            $agm_detail->house_rules_url = $house_rules_url;
            $agm_detail->save();

            # Audit Trail
            $file_name = Files::find($agm_detail->file_id);
            if (!empty($audit_fields_changed)) {
                $remarks = 'AGM Details (' . $file_name->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_detail->agm_date)) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                $this->addAudit($file_name->id, "COB File", $remarks);
            }

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function getAGMDetails()
    {
        $output = [];
        $data = Input::all();
        if (Request::ajax()) {

            $result = "";
            $result_new = "";

            $agm = MeetingDocument::findOrFail(Helper::decode($data['id']));

            if (count($agm) > 0) {
                $result .= '<form>';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.annual_general_meeting') . '</label></div>';
                if ($agm->agm) {
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.extra_general_meeting') . '</label></div>';
                if ($agm->egm) {
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="egm_edit" name="egm_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.meeting_minutes') . '</label></div>';
                if ($agm->minit_meeting) {
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.jmc_spa_copy') . '</label></div>';
                if ($agm->jmc_spa) {
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="1" checked>' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="0">' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="1">' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="0" checked>' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.identity_card_list') . '</label></div>';
                if ($agm->identity_card) {
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="1" checked> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="0"> ' . trans('app.forms.no') . '</div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="1"> ' . trans('app.forms.yes') . '</div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="ic_list_edit" name="ic_list_edit" value="0" checked> ' . trans('app.forms.no') . '</div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.attendance_list') . '</label></div>';
                if ($agm->attendance) {
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="1" checked> ' . trans('app.forms.yes') . ' </div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="0"> ' . trans('app.forms.no') . ' </div>';
                } else {
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="1"> ' . trans('app.forms.yes') . ' </div>';
                    $result .= '<div class="col-md-2"><input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="0" checked> ' . trans('app.forms.no') . ' </div>';
                }
                $result .= '</div>';

                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.audited_financial_report') . '</label></div>';
                if ($agm->financial_report) {
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

                $result .= '<form id="upload_audit_report_file_edit" enctype="multipart/form-data" method="post" action="' . url("uploadAuditReportFile") . '" autocomplete="off">';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">&nbsp;</label></div>';
                $result .= '<div class="col-md-6">';
                $result .= '<button type="button" id="clear_audit_report_file_edit" class="btn btn-xs btn-danger" onclick="clearAuditFileEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result .= '<input type="file" name="audit_report_file_edit" id="audit_report_file_edit">';
                $result .= '<div id="validation-errors_audit_report_file_edit"></div><div id="view_audit_report_file_edit"></div>';
                if ($agm->audit_report_url != "") {
                    $result .= '<div id="report_edit"><a href="' . asset($agm->audit_report_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAuditReport(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result .= '</div>';
                $result .= '</div>';
                $result .= '</form>';

                $result .= '<form id="upload_letter_integrity_edit" enctype="multipart/form-data" method="post" action="' . url("uploadLetterIntegrity") . '" autocomplete="off">';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.pledge_letter_of_integrity') . '</label></div>';
                $result .= '<div class="col-md-6">';
                $result .= '<button type="button" id="clear_letter_integrity_edit" class="btn btn-xs btn-danger" onclick="clearLetterIntegrityEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result .= '<input type="file" name="letter_integrity_edit" id="letter_integrity_edit">';
                $result .= '<div id="validation-errors_letter_integrity_edit"></div>';
                if ($agm->letter_integrity_url != "") {
                    $result .= '<div id="integrity_edit"><a href="' . asset($agm->letter_integrity_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterIntegrity(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result .= '</div>';
                $result .= '</div>';
                $result .= '</form>';

                $result .= '<form id="upload_letter_bankruptcy_edit" enctype="multipart/form-data" method="post" action="' . url("uploadLetterBankruptcy") . '" autocomplete="off">';
                $result .= '<div class="form-group row">';
                $result .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.declaration_letter_of_non_bankruptcy') . '</label></div>';
                $result .= '<div class="col-md-6">';
                $result .= '<button type="button" id="clear_letter_bankruptcy_edit" class="btn btn-xs btn-danger" onclick="clearLetterBankruptcyEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result .= '<input type="file" name="letter_bankruptcy_edit" id="letter_bankruptcy_edit">';
                $result .= '<div id="validation-errors_letter_bankruptcy_edit"></div>';
                if ($agm->letter_bankruptcy_url != "") {
                    $result .= '<div id="bankruptcy_edit"><a href="' . asset($agm->letter_bankruptcy_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterBankruptcy(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result .= '</div>';
                $result .= '</div>';
                $result .= '</form>';

                $result_new .= '<hr/>';
                $result_new .= '<form id="upload_notice_agm_egm_edit" enctype="multipart/form-data" method="post" action="' . url("uploadNoticeAgmEgm") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_notice_agm_egm') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_notice_agm_egm_edit" class="btn btn-xs btn-danger" onclick="clearNoticeAgmEgmEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="notice_agm_egm_edit" id="notice_agm_egm_edit">';
                $result_new .= '<div id="validation-notice_agm_egm_edit"></div>';
                if ($agm->notice_agm_egm_url != "") {
                    $result_new .= '<div id="btn_notice_agm_egm_edit"><a href="' . asset($agm->notice_agm_egm_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteNoticeAgmEgm(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_minutes_agm_egm_edit" enctype="multipart/form-data" method="post" action="' . url("uploadMinutesAgmEgm") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_minutes_agm_egm') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_minutes_agm_egm_edit" class="btn btn-xs btn-danger" onclick="clearMinutesAgmEgmEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="minutes_agm_egm_edit" id="minutes_agm_egm_edit">';
                $result_new .= '<div id="validation-minutes_agm_egm_edit"></div>';
                if ($agm->minutes_agm_egm_url != "") {
                    $result_new .= '<div id="btn_minutes_agm_egm_edit"><a href="' . asset($agm->minutes_agm_egm_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesAgmEgm(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_minutes_ajk_edit" enctype="multipart/form-data" method="post" action="' . url("uploadMinutesAjk") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_minutes_ajk') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_minutes_ajk_edit" class="btn btn-xs btn-danger" onclick="clearMinutesAjkEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="minutes_ajk_edit" id="minutes_ajk_edit">';
                $result_new .= '<div id="validation-minutes_ajk_edit"></div>';
                if ($agm->minutes_ajk_url != "") {
                    $result_new .= '<div id="btn_minutes_ajk_edit"><a href="' . asset($agm->minutes_ajk_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesAjk(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_eligible_vote_edit" enctype="multipart/form-data" method="post" action="' . url("uploadEligibleVote") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_eligible_vote') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_eligible_vote_edit" class="btn btn-xs btn-danger" onclick="clearEligbleVoteEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="eligible_vote_edit" id="eligible_vote_edit">';
                $result_new .= '<div id="validation-eligible_vote_edit"></div>';
                if ($agm->eligible_vote_url != "") {
                    $result_new .= '<div id="btn_eligible_vote_edit"><a href="' . asset($agm->eligible_vote_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteEligibleVote(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_attend_meeting_edit" enctype="multipart/form-data" method="post" action="' . url("uploadAttendMeeting") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_attend_meeting') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_attend_meeting_edit" class="btn btn-xs btn-danger" onclick="clearAttendMeetingEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="attend_meeting_edit" id="attend_meeting_edit">';
                $result_new .= '<div id="validation-attend_meeting_edit"></div>';
                if ($agm->attend_meeting_url != "") {
                    $result_new .= '<div id="btn_attend_meeting_edit"><a href="' . asset($agm->attend_meeting_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAttendMeeting(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_proksi_edit" enctype="multipart/form-data" method="post" action="' . url("uploadProksi") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_proksi') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_proksi_edit" class="btn btn-xs btn-danger" onclick="clearProksiEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="proksi_edit" id="proksi_edit">';
                $result_new .= '<div id="validation-proksi_edit"></div>';
                if ($agm->proksi_url != "") {
                    $result_new .= '<div id="btn_proksi_edit"><a href="' . asset($agm->proksi_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteProksi(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_ajk_info_edit" enctype="multipart/form-data" method="post" action="' . url("uploadAjkInfo") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_ajk_info') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_ajk_info_edit" class="btn btn-xs btn-danger" onclick="clearAjkInfoEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="ajk_info_edit" id="ajk_info_edit">';
                $result_new .= '<div id="validation-ajk_info_edit"></div>';
                if ($agm->ajk_info_url != "") {
                    $result_new .= '<div id="btn_ajk_info_edit"><a href="' . asset($agm->ajk_info_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAjkInfo(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_ic_edit" enctype="multipart/form-data" method="post" action="' . url("uploadIc") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_ic') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_ic_edit" class="btn btn-xs btn-danger" onclick="clearIcEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="ic_edit" id="ic_edit">';
                $result_new .= '<div id="validation-ic_edit"></div>';
                if ($agm->ic_url != "") {
                    $result_new .= '<div id="btn_ic_edit"><a href="' . asset($agm->ic_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteIc(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                if ($agm->type == 'jmb') {
                    $result_new .= '<form id="upload_purchase_aggrement_edit" enctype="multipart/form-data" method="post" action="' . url("uploadPurchaseAggrement") . '" autocomplete="off">';
                    $result_new .= '<div class="form-group row">';
                    $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_purchase_aggrement') . '</label></div>';
                    $result_new .= '<div class="col-md-6">';
                    $result_new .= '<button type="button" id="clear_purchase_aggrement_edit" class="btn btn-xs btn-danger" onclick="clearPurchaseAggrementEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                    $result_new .= '<input type="file" name="purchase_aggrement_edit" id="purchase_aggrement_edit">';
                    $result_new .= '<div id="validation-purchase_aggrement_edit"></div>';
                    if ($agm->purchase_aggrement_url != "") {
                        $result_new .= '<div id="btn_purchase_aggrement_edit"><a href="' . asset($agm->purchase_aggrement_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                        $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deletePurchaseAggrement(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                    }
                    $result_new .= '</div>';
                    $result_new .= '</div>';
                    $result_new .= '</form>';
                }

                if ($agm->type == 'mc') {
                    $result_new .= '<form id="upload_strata_title_edit" enctype="multipart/form-data" method="post" action="' . url("uploadStrataTitle") . '" autocomplete="off">';
                    $result_new .= '<div class="form-group row">';
                    $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_strata_title') . '</label></div>';
                    $result_new .= '<div class="col-md-6">';
                    $result_new .= '<button type="button" id="clear_strata_title_edit" class="btn btn-xs btn-danger" onclick="clearStrataTitleEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                    $result_new .= '<input type="file" name="strata_title_edit" id="strata_title_edit">';
                    $result_new .= '<div id="validation-strata_title_edit"></div>';
                    if ($agm->strata_title_url != "") {
                        $result_new .= '<div id="btn_strata_title_edit"><a href="' . asset($agm->strata_title_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                        $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deletePurchaseAggrement(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                    }
                    $result_new .= '</div>';
                    $result_new .= '</div>';
                    $result_new .= '</form>';
                }

                $result_new .= '<form id="upload_maintenance_statement_edit" enctype="multipart/form-data" method="post" action="' . url("uploadMaintenanceStatement") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_maintenance_statement') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_maintenance_statement_edit" class="btn btn-xs btn-danger" onclick="clearMaintenanceStatementEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="maintenance_statement_edit" id="maintenance_statement_edit">';
                $result_new .= '<div id="validation-maintenance_statement_edit"></div>';
                if ($agm->maintenance_statement_url != "") {
                    $result_new .= '<div id="btn_maintenance_statement_edit"><a href="' . asset($agm->maintenance_statement_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMaintenanceStatement(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_integrity_pledge_edit" enctype="multipart/form-data" method="post" action="' . url("uploadIntegrityPledge") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_integrity_pledge') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_integrity_pledge_edit" class="btn btn-xs btn-danger" onclick="clearIntegrityPledgeEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="integrity_pledge_edit" id="integrity_pledge_edit">';
                $result_new .= '<div id="validation-integrity_pledge_edit"></div>';
                if ($agm->integrity_pledge_url != "") {
                    $result_new .= '<div id="btn_integrity_pledge_edit"><a href="' . asset($agm->integrity_pledge_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteIntegrityPledge(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_sworn_statement_edit" enctype="multipart/form-data" method="post" action="' . url("uploadSwornStatement") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_sworn_statement') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_sworn_statement_edit" class="btn btn-xs btn-danger" onclick="clearReportAuditedFinancialEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="sworn_statement_edit" id="sworn_statement_edit">';
                $result_new .= '<div id="validation-sworn_statement_edit"></div>';
                if ($agm->sworn_statement_url != "") {
                    $result_new .= '<div id="btn_sworn_statement_edit"><a href="' . asset($agm->sworn_statement_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteSwornStatement(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_report_audited_financial_edit" enctype="multipart/form-data" method="post" action="' . url("uploadReportAuditedFinancial") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_report_audited_financial') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_report_audited_financial_edit" class="btn btn-xs btn-danger" onclick="clearReportAuditedFinancialEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="report_audited_financial_edit" id="report_audited_financial_edit">';
                $result_new .= '<div id="validation-report_audited_financial_edit"></div>';
                if ($agm->report_audited_financial_url != "") {
                    $result_new .= '<div id="btn_report_audited_financial_edit"><a href="' . asset($agm->report_audited_financial_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteReportAuditedFinancial(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';

                $result_new .= '<form id="upload_house_rules_edit" enctype="multipart/form-data" method="post" action="' . url("uploadHouseRules") . '" autocomplete="off">';
                $result_new .= '<div class="form-group row">';
                $result_new .= '<div class="col-md-6"><label class="form-control-label">' . trans('app.forms.upload_house_rules') . '</label></div>';
                $result_new .= '<div class="col-md-6">';
                $result_new .= '<button type="button" id="clear_house_rules_edit" class="btn btn-xs btn-danger" onclick="clearHouseRulesEdit()" style="display: none;"><i class="fa fa-times"></i></button>&nbsp;';
                $result_new .= '<input type="file" name="house_rules_edit" id="house_rules_edit">';
                $result_new .= '<div id="validation-house_rules_edit"></div>';
                if ($agm->house_rules_url != "") {
                    $result_new .= '<div id="btn_house_rules_edit"><a href="' . asset($agm->house_rules_url) . '" target="_blank"><button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> ' . trans('app.forms.download') . '</button></a>&nbsp;';
                    $result_new .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteHouseRules(\'' . Helper::encode($agm->id) . '\')"><i class="fa fa-times"></i></button></div>';
                }
                $result_new .= '</div>';
                $result_new .= '</div>';
                $result_new .= '</form>';
            } else {
                $result = trans('app.errors.no_data_found');
            }

            $output = array(
                'result' => $result,
                'result_new' => $result_new
            );

            return $output;
        }
    }

    public function getAGM($file_id)
    {
        $file = Files::find(Helper::decode($file_id));

        if ($file->company && $file->company->short_name == 'MPKJ') {
            if (Request::ajax()) {
                $condition = '';
                if (!Auth::user()->getAdmin()) {
                    if (!empty(Auth::user()->file_id)) {
                        $condition = function ($query) {
                            $query->where('agm_minutes.file_id', Auth::user()->file_id);
                        };
                    } else if (!empty($file_id)) {
                        $condition = function ($query) use ($file_no) {
                            $query->where('agm_minutes.file_id', $file->id);
                        };
                    }
                } else {
                    if (!empty($file_id)) {
                        $condition = function ($query) use ($file) {
                            $query->where('agm_minutes.file_id', $file->id);
                        };
                    }
                }
                $model = AGMMinute::join('files', 'agm_minutes.file_id', '=', 'files.id')
                    ->where(function ($query) use ($condition) {
                        if (!empty($condition)) {
                            $query->where($condition);
                        }
                    })
                    ->selectRaw("agm_minutes.*, files.file_no")
                    ->where('agm_minutes.type', 'jmb')
                    ->where('agm_minutes.is_deleted', 0)
                    ->orderBy('agm_minutes.created_at', "desc");

                return Datatables::of($model)
                    ->editColumn('file_id', function ($model) {
                        return $model->file_no;
                    })
                    ->editColumn('agm_type', function ($model) {
                        return strtoupper($model->agm_type);
                    })
                    ->editColumn('description', function ($model) {
                        $questions = unserialize($model->description);
                        $configs = (new AGMMinuteController())->getFormFields($model);
                        $content = '';
                        foreach ($questions as $key => $val) {
                            if (str_contains($key, '_url')) {
                                $content .= $configs['questions'][str_replace("_file_url", "", $key)]['label'] . "<br/>";
                            }
                        }
                        return $content;
                    })
                    ->editColumn('updated_at', function ($model) {
                        return date('d-M-Y', strtotime($model->updated_at));
                    })
                    ->addColumn('check_status', function ($model) {
                        $questions = unserialize($model->description);
                        $configs = (new AGMMinuteController())->getFormFields($model);
                        $content = '';
                        foreach ($questions as $key => $val) {
                            if (str_contains($key, '_url')) {
                                if ($val == "") {
                                    $status = '<i class="icmn-cross"></i>';
                                } else {
                                    $status = '<i class="icmn-checkmark"></i>';
                                }
                                if (!empty($configs['questions'][str_replace("_file_url", "", $key)])) {
                                    $content .= "$status<br/>";
                                }
                            }
                        }
                        return $content;
                    })
                    ->addColumn('action', function ($model) {
                        $btn = '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit"' .
                            'data-id="' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" data-type="' . $model->type . '" data-agm_type="' . $model->agm_type . '" data-agm_date="' . $model->agm_date . '" ' .
                            'data-is_first="' . $model->is_first . '" data-remarks="' . $model->remarks . '"><i class="fa fa-pencil"></i></button>&nbsp;&nbsp;';
                        $btn .= '<form action="' . route('agm-minute.destroy', Helper::encode($this->module['agm']['minute']['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" style="display:inline-block;">';
                        $btn .= '<input type="hidden" name="_method" value="DELETE">';
                        $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                        $btn .= '</form>';

                        return $btn;
                    })
                    ->make(true);
            }
        } else {
            $agm_detail = MeetingDocument::where('file_id', $file->id)->where('type', 'jmb')->where('is_deleted', 0)->orderBy('id', 'desc')->get();

            if (count($agm_detail) > 0) {
                $data = array();
                foreach ($agm_detail as $agm_details) {
                    $button = "";
                    $button .= '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit" onclick="getAGMDetails(\'' . Helper::encode($agm_details->id) . '\')"
                                data-agm_id="' . Helper::encode($agm_details->id) . '" data-agm_date="' . ($agm_details->agm_date != '0000-00-00' ? $agm_details->agm_date : '') . '"
                                data-agm_date_raw="' . ($agm_details->agm_date != '0000-00-00' ? date('d-m-Y', strtotime($agm_details->agm_date)) : '') . '"
                                data-audit_start_date="' . $agm_details->audit_start_date . '" data-audit_end_date="' . $agm_details->audit_end_date . '"
                                data-audit_start_date_raw="' . ($agm_details->audit_start_date != '0000-00-00' ? date('d-m-Y', strtotime($agm_details->audit_start_date)) : '') . '"
                                data-audit_end_date_raw="' . ($agm_details->audit_end_date != '0000-00-00' ? date('d-m-Y', strtotime($agm_details->audit_end_date)) : '') . '"
                                data-audit_report_file_url="' . $agm_details->audit_report_url . '" data-letter_integrity_url="' . $agm_details->letter_integrity_url . '" data-letter_bankruptcy_url="' . $agm_details->letter_bankruptcy_url . '">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                &nbsp;';
                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAGMDetails(\'' . Helper::encode($agm_details->id) . '\')">
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
                        date('d-M-Y', strtotime($agm_details->updated_at)),
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
    }

    public function getAGMByMC($file_id)
    {
        $file = Files::find(Helper::decode($file_id));

        if ($file->company && $file->company->short_name == 'MPKJ') {
            if (Request::ajax()) {
                $condition = '';
                if (!Auth::user()->getAdmin()) {
                    if (!empty(Auth::user()->file_id)) {
                        $condition = function ($query) {
                            $query->where('agm_minutes.file_id', Auth::user()->file_id);
                        };
                    } else if (!empty($file_id)) {
                        $condition = function ($query) use ($file) {
                            $query->where('agm_minutes.file_id', $file->id);
                        };
                    }
                } else {
                    if (!empty($file_id)) {
                        $condition = function ($query) use ($file) {
                            $query->where('agm_minutes.file_id', $file->id);
                        };
                    }
                }
                $model = AGMMinute::join('files', 'agm_minutes.file_id', '=', 'files.id')
                    ->where(function ($query) use ($condition) {
                        if (!empty($condition)) {
                            $query->where($condition);
                        }
                    })
                    ->selectRaw("agm_minutes.*, files.file_no")
                    ->where('agm_minutes.type', 'mc')
                    ->where('agm_minutes.is_deleted', 0)
                    ->orderBy('agm_minutes.created_at', "desc");

                return Datatables::of($model)
                    ->editColumn('file_id', function ($model) {
                        return $model->file_no;
                    })
                    ->editColumn('agm_type', function ($model) {
                        return strtoupper($model->agm_type);
                    })
                    ->editColumn('description', function ($model) {
                        $questions = unserialize($model->description);
                        $configs = (new AGMMinuteController())->getFormFields($model);
                        $content = '';
                        foreach ($questions as $key => $val) {
                            if (str_contains($key, '_url')) {
                                $content .= $configs['questions'][str_replace("_file_url", "", $key)]['label'] . "<br/>";
                            }
                        }
                        return $content;
                    })
                    ->editColumn('updated_at', function ($model) {
                        return date('d-M-Y', strtotime($model->updated_at));
                    })
                    ->addColumn('check_status', function ($model) {
                        $questions = unserialize($model->description);
                        $configs = (new AGMMinuteController())->getFormFields($model);
                        $content = '';
                        foreach ($questions as $key => $val) {
                            if (str_contains($key, '_url')) {
                                if ($val == "") {
                                    $status = '<i class="icmn-cross"></i>';
                                } else {
                                    $status = '<i class="icmn-checkmark"></i>';
                                }
                                if (!empty($configs['questions'][str_replace("_file_url", "", $key)])) {
                                    $content .= "$status<br/>";
                                }
                            }
                        }
                        return $content;
                    })
                    ->addColumn('action', function ($model) {
                        $btn = '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit"' .
                            'data-id="' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" data-type="' . $model->type . '" data-agm_type="' . $model->agm_type . '" data-agm_date="' . $model->agm_date . '" ' .
                            'data-is_first="' . $model->is_first . '" data-remarks="' . $model->remarks . '"><i class="fa fa-pencil"></i></button>&nbsp;&nbsp;';
                        $btn .= '<form action="' . route('agm-minute.destroy', Helper::encode($this->module['agm']['minute']['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" style="display:inline-block;">';
                        $btn .= '<input type="hidden" name="_method" value="DELETE">';
                        $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->module['agm']['minute']['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                        $btn .= '</form>';

                        return $btn;
                    })
                    ->make(true);
            }
        } else {
            $agm_detail = MeetingDocument::where('file_id', $file->id)->where('type', 'mc')->where('is_deleted', 0)->orderBy('id', 'desc')->get();

            if (count($agm_detail) > 0) {
                $data = array();
                foreach ($agm_detail as $agm_details) {
                    $button = "";
                    $button .= '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit" onclick="getAGMDetails(\'' . Helper::encode($agm_details->id) . '\')"
                            data-agm_id="' . Helper::encode($agm_details->id) . '" data-agm_date="' . ($agm_details->agm_date != '0000-00-00' ? $agm_details->agm_date : '') . '"
                            data-agm_date_raw="' . ($agm_details->agm_date != '0000-00-00' ? date('d-m-Y', strtotime($agm_details->agm_date)) : '') . '"
                            data-audit_start_date="' . $agm_details->audit_start_date . '" data-audit_end_date="' . $agm_details->audit_end_date . '"
                            data-audit_start_date_raw="' . ($agm_details->audit_start_date != '0000-00-00' ? date('d-m-Y', strtotime($agm_details->audit_start_date)) : '') . '"
                            data-audit_end_date_raw="' . ($agm_details->audit_end_date != '0000-00-00' ? date('d-m-Y', strtotime($agm_details->audit_end_date)) : '') . '"
                            data-audit_report_file_url="' . $agm_details->audit_report_url . '" data-letter_integrity_url="' . $agm_details->letter_integrity_url . '" data-letter_bankruptcy_url="' . $agm_details->letter_bankruptcy_url . '">
                                <i class="fa fa-pencil"></i>
                            </button>
                            &nbsp;';
                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAGMDetails(\'' . Helper::encode($agm_details->id) . '\')">
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
                        date('d-M-Y', strtotime($agm_details->updated_at)),
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
    }

    public function deleteAGMDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['agm_detail']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->is_deleted = 1;
            $deleted = $agm_details->save();

            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteAuditReport()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['auditReportFile'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->audit_report_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' audit report file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteLetterIntegrity()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['letterIntegrity'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->letter_integrity_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' letter integrity file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteLetterBankruptcy()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['letterBankruptcy'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->letter_bankruptcy_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' letter bankruptcy file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteAGMFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->agm_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' AGM file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteEGMFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->egm_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' EGM file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteMinutesMeetingFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->minutes_meeting_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' minute meeting file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteJMCFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->jmc_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' JMC file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteICFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->ic_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::findOrFail($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' IC File' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteAttendanceFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->attendance_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::findOrFail($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' attendance file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteAuditedFinancialFile()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $agm_details = MeetingDocument::findOrFail(Helper::decode($data['id']));
            $agm_details->audited_financial_file_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' audited financial file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function addAJKDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['ajk_detail']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $ajk_detail = new AJKDetails();

            $designation = $data['ajk_designation'];
            $name = $data['ajk_name'];
            $email = $data['ajk_email'];
            $phone_no = $data['ajk_phone_no'];
            $start_year = $data['ajk_start_year'];
            $end_year = $data['ajk_end_year'];

            $ajk_detail->file_id = $files->id;
            $ajk_detail->designation = $designation;
            $ajk_detail->name = $name;
            $ajk_detail->email = $email;
            $ajk_detail->phone_no = $phone_no;
            $ajk_detail->start_year = $start_year;
            $ajk_detail->end_year = $end_year;
            $ajk_detail->save();

            # Audit Trail
            $remarks = 'AJK Details (' . $files->file_no . ') ' . $ajk_detail->name . $this->module['audit']['text']['data_inserted'];
            $this->addAudit($files->id, "COB File", $remarks);

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function editAJKDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['ajk_detail']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id']));
            $ajk_detail = AJKDetails::firstOrNew(array('file_id' => $files->id, 'id' => $data['ajk_id_edit']));

            $designation = $data['ajk_designation'];
            $name = $data['ajk_name'];
            $email = $data['ajk_email'];
            $phone_no = $data['ajk_phone_no'];
            $start_year = $data['ajk_start_year'];
            $end_year = $data['ajk_end_year'];

            /** Arrange audit fields changes */
            $audit_fields_changed = '';
            $new_line = '';
            $new_line .= $designation != $ajk_detail->designation ? "designation, " : "";
            $new_line .= $name != $ajk_detail->name ? "name, " : "";
            $new_line .= $email != $ajk_detail->email ? "email, " : "";
            $new_line .= $phone_no != $ajk_detail->phone_no ? "phone no, " : "";
            $new_line .= $start_year != $ajk_detail->start_year ? "start year, " : "";
            $new_line .= $end_year != $ajk_detail->end_year ? "end year, " : "";
            if (!empty($new_line)) {
                $audit_fields_changed .= "<br/><ul><li> AJK Detail : (";
                $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
            }
            /** End Arrange audit fields changes */

            $ajk_detail->designation = $designation;
            $ajk_detail->name = $name;
            $ajk_detail->email = $email;
            $ajk_detail->phone_no = $phone_no;
            $ajk_detail->start_year = $start_year;
            $ajk_detail->end_year = $end_year;
            $ajk_detail->save();

            # Audit Trail
            if (!empty($audit_fields_changed)) {
                $remarks = 'AJK Details (' . $files->file_no . ') ' . $ajk_detail->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                $this->addAudit($files->id, "COB File", $remarks);
            }

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function getAJK($file_id)
    {
        $ajk_detail = AJKDetails::where('file_id', Helper::decode($file_id))->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($ajk_detail) > 0) {
            $data = array();
            foreach ($ajk_detail as $ajk_details) {
                $designation = Designation::find($ajk_details->designation);

                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit" data-toggle="modal" data-target="#edit_ajk_details"
                            data-ajk_id="' . $ajk_details->id . '" data-designation="' . $ajk_details->designation . '" data-name="' . $ajk_details->name . '" data-email="' . $ajk_details->email . '" data-phone_no="' . $ajk_details->phone_no . '" data-start_year="' . $ajk_details->start_year . '" data-end_year="' . $ajk_details->end_year . '">
                                <i class="fa fa-pencil"></i>
                            </button>
                            &nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . Helper::encode($ajk_details->id) . '\')">
                                <i class="fa fa-trash"></i>
                            </button>
                            &nbsp';

                $data_raw = array(
                    $designation->description,
                    $ajk_details->name,
                    $ajk_details->email,
                    $ajk_details->phone_no,
                    $ajk_details->start_year,
                    $ajk_details->end_year,
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

    public function deleteAJKDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['ajk_detail']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $ajk_details = AJKDetails::findOrFail($id);
            $ajk_details->is_deleted = 1;
            $deleted = $ajk_details->save();
            if ($deleted) {
                # Audit Trail
                $file = Files::find($ajk_details->file_id);
                $remarks = 'AJK Details (' . $file->file_no . ') ' . $ajk_details->name . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($file->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function viewOthers($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find(Helper::decode($id));
        $other_details = OtherDetails::where('file_id', $files->id)->first();
        $image = OtherDetails::where('file_id', $files->id)->first();
        $disallow = Helper::isAllow($files->id, $files->company_id);

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

    public function others($id)
    {
        if (Auth::user()->isPreSale()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::with(['buyer'])->findOrFail(Helper::decode($id));

        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            $other_details = OtherDetailsDraft::where('file_id', $files->id)->first();
            $image = OtherDetailsDraft::where('file_id', $files->id)->first();
            if (count($other_details) <= 0) {
                $other_details = OtherDetails::where('file_id', $files->id)->first();
            }
            if (count($image) <= 0) {
                $image = OtherDetails::where('file_id', $files->id)->first();
            }
        } else {
            $other_details = OtherDetails::where('file_id', $files->id)->first();
            $image = OtherDetails::where('file_id', $files->id)->first();
        }
        $tnbLists = OtherDetails::tnbLists();
        $disallow = Helper::isAllow($files->id, $files->company_id);

        if ($disallow) {
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
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_panel',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'file' => $files,
            'other_details' => $other_details,
            'image' => (!empty($image->image_url) ? $image->image_url : ''),
            'tnbLists' => $tnbLists
        );

        return View::make('page_en.update_others', $viewData);
    }

    public function submitUpdateOtherDetails()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['others']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $files = Files::findOrFail(Helper::decode($data['file_id']));
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                $others = OtherDetailsDraft::firstOrNew(array('file_id' => $files->id));
                $others->reference_id = $data['reference_id'];

                $this->createOrUpdateFileDraft($files);
                /**
                 * Add Notification & send email to COB and JMB
                 */
                $strata = $files->strata;
                $notify_data['file_id'] = $files->id;
                $notify_data['route'] = route('cob.file.others.edit', Request::get('file_id'));
                $notify_data['cob_route'] = route('cob.file.draft.others.edit', Request::get('file_id'));
                $notify_data['strata'] = "You";
                $notify_data['strata_name'] = $strata->name != "" ? $strata->name : $files->file_no;
                $notify_data['title'] = "COB File Others";
                $notify_data['module'] = "Other Details";

                (new NotificationService())->store($notify_data);
            } else {
                $others = OtherDetails::firstOrNew(array('file_id' => $files->id));
            }

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
            $original_price = $data['original_price'];
            $water_meter = $data['water_meter'];
            $tnb = $data['tnb'];
            $parking_bay = $data['parking_bay'];
            $parking_area = $data['parking_area'];
            $malay_composition = $data['malay_composition'];
            $chinese_composition = $data['chinese_composition'];
            $indian_composition = $data['indian_composition'];
            $others_composition = $data['others_composition'];
            $foreigner_composition = $data['foreigner_composition'];

            /** Arrange audit fields changes */
            $audit_fields_changed = '';
            $new_line = '';
            $new_line .= $other_details_name != $others->name ? "name, " : "";
            $new_line .= $others_image_url != $others->image_url ? "image_url, " : "";
            $new_line .= $latitude != $others->latitude ? "latitude, " : "";
            $new_line .= $longitude != $others->longitude ? "longitude, " : "";
            $new_line .= $other_details_description != $others->description ? "description, " : "";
            $new_line .= $pms_system != $others->pms_system ? "pms system, " : "";
            $new_line .= $owner_occupied != $others->owner_occupied ? "owner occupied, " : "";
            $new_line .= $rented != $others->rented ? "rented, " : "";
            $new_line .= $bantuan_lphs != $others->bantuan_lphs ? "bantuan lphs, " : "";
            $new_line .= $bantuan_others != $others->bantuan_others ? "bantuan others, " : "";
            $new_line .= $rsku != $others->rsku ? "rsku, " : "";
            $new_line .= $original_price != $others->original_price ? "original price, " : "";
            $new_line .= $water_meter != $others->water_meter ? "water meter, " : "";
            $new_line .= $tnb != $others->tnb ? "tnb, " : "";
            $new_line .= $parking_bay != $others->parking_bay ? "parking bay, " : "";
            $new_line .= $parking_area != $others->parking_area ? "parking area, " : "";
            $new_line .= $malay_composition != $others->malay_composition ? "malay composition, " : "";
            $new_line .= $chinese_composition != $others->chinese_composition ? "chinese composition, " : "";
            $new_line .= $indian_composition != $others->indian_composition ? "indian composition, " : "";
            $new_line .= $others_composition != $others->others_composition ? "others composition, " : "";
            $new_line .= $foreigner_composition != $others->foreigner_composition ? "foreigner composition, " : "";
            if (!empty($new_line)) {
                $audit_fields_changed .= "<br/><ul><li> Others : (";
                $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
            }
            /** End Arrange audit fields changes */

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
            $others->original_price = $original_price;
            $others->water_meter = $water_meter;
            $others->tnb = $tnb;
            $others->parking_bay = $parking_bay;
            $others->parking_area = $parking_area;
            $others->malay_composition = $malay_composition;
            $others->chinese_composition = $chinese_composition;
            $others->indian_composition = $indian_composition;
            $others->others_composition = $others_composition;
            $others->foreigner_composition = $foreigner_composition;
            $others->save();

            # Audit Trail
            if (!empty($audit_fields_changed)) {
                $remarks = 'Others Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                $this->addAudit($files->id, "COB File", $remarks);
            }

            return "true";

            // } else {
            //     return "false";
            // }
        }

        return "false";
    }

    public function submitAddHousingScheme()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['house']['add_user'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $file_id = Helper::decode($data['file_id']);
            $housing_scheme = $data['housing_scheme'];

            if (!empty($file_id)) {
                $check_exist = HousingSchemeUser::where('file_id', $file_id)->where('user_id', $housing_scheme)->where('is_deleted', 0)->count();
                if ($check_exist > 0) {
                    return "data_exist";
                } else {
                    $hs_user = new HousingSchemeUser();
                    $hs_user->file_id = $file_id;
                    $hs_user->user_id = $housing_scheme;
                    $hs_user->is_deleted = 0;
                    $success = $hs_user->save();

                    if ($success) {
                        # Audit Trail
                        $files = Files::find($hs_user->file_id);
                        $remarks = 'House Scheme User Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_inserted'];
                        $this->addAudit($files->id, "COB File", $remarks);

                        return "true";
                    } else {
                        return "false";
                    }
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function getHousingScheme($file_id)
    {
        $user = HousingSchemeUser::where('file_id', Helper::decode($file_id))->where('is_deleted', 0)->get();

        if (count($user) > 0) {
            $data = array();
            foreach ($user as $users) {
                $hs_user = User::find($users->user_id);

                if ($hs_user) {
                    $button = "";
                    $button .= '<button class="btn btn-xs btn-danger" onclick="deleteHousingScheme(\'' . Helper::encode($users->id) . '\')" title="Delete"><i class="fa fa-trash"></i></button>';

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

    public function deleteHousingScheme()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['house']['delete_user'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);
            $hs_user = HousingSchemeUser::findOrFail($id);

            if ($hs_user) {
                $hs_user->is_deleted = 1;
                $success = $hs_user->save();

                if ($success) {
                    $files = Files::find($hs_user->file_id);
                    $remarks = 'House Scheme User Info (' . $files->file_no . ')' . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($files->id, "COB File", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteImageOthers()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['others']['image_delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $others = OtherDetails::findOrFail($id);
            $others->image_url = "";
            $deleted = $others->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($others->file_id);
                $remarks = 'Others Info (' . $files->file_no . ') image file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function viewScoring($id)
    {
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        if (Auth::user()->isPreSale()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));
        $image = OtherDetails::where('file_id', $files->id)->first();
        $disallow = Helper::isAllow($files->id, $files->company_id);

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

    public function scoring($id)
    {
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        if (Auth::user()->isPreSale()) {
            return Redirect::to('update/monitoring/' . $id);
        }

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::findOrFail(Helper::decode($id));
        $image = OtherDetails::where('file_id', $file->id)->first();
        $disallow = Helper::isAllow($file->id, $file->company_id);


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

    public function addScoring()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['scoring']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $file_id = Helper::decode($data['file_id']);
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

            $scorings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 25) * 25);
            $scorings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 25) * 25);
            $scorings_C = ((($score11 + $score12 + $score13 + $score14) / 20) * 20);
            $scorings_D = ((($score15 + $score16 + $score17 + $score18) / 20) * 20);
            $scorings_E = ((($score19 + $score20 + $score21) / 15) * 10);

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
                $files = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $files->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->created_at)) . $this->module['audit']['text']['data_inserted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function editScoring()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['scoring']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);
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

            $scorings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 25) * 25);
            $scorings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 25) * 25);
            $scorings_C = ((($score11 + $score12 + $score13 + $score14) / 20) * 20);
            $scorings_D = ((($score15 + $score16 + $score17 + $score18) / 20) * 20);
            $scorings_E = ((($score19 + $score20 + $score21) / 15) * 10);

            $total_score = $scorings_A + $scorings_B + $scorings_C + $scorings_D + $scorings_E;

            $scoring = Scoring::findOrFail($id);
            if ($scoring) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $date != $scoring->date ? "date, " : "";
                $new_line .= $score1 != $scoring->score1 ? "score1, " : "";
                $new_line .= $score2 != $scoring->score2 ? "score2, " : "";
                $new_line .= $score3 != $scoring->score3 ? "score3, " : "";
                $new_line .= $score4 != $scoring->score4 ? "score4, " : "";
                $new_line .= $score5 != $scoring->score5 ? "score5, " : "";
                $new_line .= $score6 != $scoring->score6 ? "score6, " : "";
                $new_line .= $score7 != $scoring->score7 ? "score7, " : "";
                $new_line .= $score8 != $scoring->score8 ? "score8, " : "";
                $new_line .= $score9 != $scoring->score9 ? "score9, " : "";
                $new_line .= $score10 != $scoring->score10 ? "score10, " : "";
                $new_line .= $score11 != $scoring->score11 ? "score11, " : "";
                $new_line .= $score12 != $scoring->score12 ? "score12, " : "";
                $new_line .= $score13 != $scoring->score13 ? "score13, " : "";
                $new_line .= $score14 != $scoring->score14 ? "score14, " : "";
                $new_line .= $score15 != $scoring->score15 ? "score15, " : "";
                $new_line .= $score16 != $scoring->score16 ? "score16, " : "";
                $new_line .= $score17 != $scoring->score17 ? "score17, " : "";
                $new_line .= $score18 != $scoring->score18 ? "score18, " : "";
                $new_line .= $score19 != $scoring->score19 ? "score19, " : "";
                $new_line .= $score20 != $scoring->score20 ? "score20, " : "";
                $new_line .= $score21 != $scoring->score21 ? "score21, " : "";
                $new_line .= $total_score != $scoring->total_score ? "total score, " : "";
                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Scoring : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

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
                    $files = Files::find($scoring->file_id);
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'COB Rating (' . $files->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->created_at)) . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit($files->id, "COB File", $remarks);
                    }

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function getScoring($id)
    {
        $scoring = Scoring::where('file_id', Helper::decode($id))->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($scoring) > 0) {
            $data = array();
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
                    . 'data-id="' . Helper::encode($scorings->id) . '"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="deleteScoring(\'' . Helper::encode($scorings->id) . '\')"><i class="fa fa-trash"></i></button>';

                $scorings_A = ((($scorings->score1 + $scorings->score2 + $scorings->score3 + $scorings->score4 + $scorings->score5) / 25) * 25);
                $scorings_B = ((($scorings->score6 + $scorings->score7 + $scorings->score8 + $scorings->score9 + $scorings->score10) / 25) * 25);
                $scorings_C = ((($scorings->score11 + $scorings->score12 + $scorings->score13 + $scorings->score14) / 20) * 20);
                $scorings_D = ((($scorings->score15 + $scorings->score16 + $scorings->score17 + $scorings->score18) / 20) * 20);
                $scorings_E = ((($scorings->score19 + $scorings->score20 + $scorings->score21) / 15) * 10);

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

    public function deleteScoring()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['scoring']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $scoring = Scoring::findOrFail($id);
            $scoring->is_deleted = 1;
            $deleted = $scoring->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $files->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->created_at)) . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function viewBuyer($id)
    {
        //get user permission
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        if (Auth::user()->isPreSale()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));
        $image = OtherDetails::where('file_id', $files->id)->first();
        $disallow = Helper::isAllow($files->id, $files->company_id);

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

    public function buyer($id)
    {
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        if (Auth::user()->isPreSale()) {
            return Redirect::to('update/monitoring/' . $id);
        }

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::findOrFail(Helper::decode($id));
        $image = OtherDetails::where('file_id', $file->id)->first();
        $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasAccess(31));

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

    public function addBuyer($id)
    {
        $file = Files::findOrFail(Helper::decode($id));
        $image = OtherDetails::where('file_id', $file->id)->first();
        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasInsert(31));

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

    public function submitBuyer()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['buyer']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $file_id = Helper::decode($data['file_id']);
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
            $email2 = $data['email2'];
            $phone_no2 = $data['phone_no2'];
            $nama3 = $data['nama3'];
            $ic_no3 = $data['ic_no3'];
            $email3 = $data['email3'];
            $phone_no3 = $data['phone_no3'];
            $alamat_surat_menyurat = $data['alamat_surat_menyurat'];
            $caj_penyelenggaraan = $data['caj_penyelenggaraan'];
            $sinking_fund = $data['sinking_fund'];
            $lawyer_name = $data['lawyer_name'];
            $lawyer_address = $data['lawyer_address'];
            $lawyer_fail_ref_no = $data['lawyer_fail_ref_no'];

            $checkFile = Files::findOrFail($file_id);

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
                $buyer->email2 = $email2;
                $buyer->phone_no2 = $phone_no2;
                $buyer->nama3 = $nama3;
                $buyer->ic_no3 = $ic_no3;
                $buyer->email3 = $email3;
                $buyer->phone_no3 = $phone_no3;
                $buyer->alamat_surat_menyurat = $alamat_surat_menyurat;
                $buyer->caj_penyelenggaraan = $caj_penyelenggaraan;
                $buyer->sinking_fund = $sinking_fund;
                $buyer->lawyer_name = $lawyer_name;
                $buyer->lawyer_address = $lawyer_address;
                $buyer->lawyer_fail_ref_no = $lawyer_fail_ref_no;
                
                $success = $buyer->save();

                if ($success) {
                    # Audit Trail
                    $files = Files::find($buyer->file_id);
                    $remarks = 'COB Owner List (' . $files->file_no . ') for Unit' . $buyer->unit_no . $this->module['audit']['text']['data_inserted'];
                    $this->addAudit($files->id, "COB File", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function editBuyer($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $buyer = Buyer::findOrFail(Helper::decode($id));
        $files = Files::find($buyer->file_id);
        $image = OtherDetails::where('file_id', $files->id)->first();
        $race = Race::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $nationality = Nationality::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no', 'asc')->get();
        $disallow = Helper::isAllow($files->id, $files->company_id, !AccessGroup::hasUpdate(31));

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

    public function submitEditBuyer()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['buyer']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $file_id = Helper::decode($data['file_id']);
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
            $email2 = $data['email2'];
            $phone_no2 = $data['phone_no2'];
            $nama3 = $data['nama3'];
            $ic_no3 = $data['ic_no3'];
            $email3 = $data['email3'];
            $phone_no3 = $data['phone_no3'];
            $alamat_surat_menyurat = $data['alamat_surat_menyurat'];
            $caj_penyelenggaraan = $data['caj_penyelenggaraan'];
            $sinking_fund = $data['sinking_fund'];
            $lawyer_name = $data['lawyer_name'];
            $lawyer_address = $data['lawyer_address'];
            $lawyer_fail_ref_no = $data['lawyer_fail_ref_no'];
            $id = $data['id'];

            $checkFile = Files::findOrFail($file_id);

            if (count($checkFile) > 0) {
                $buyer = Buyer::find($id);
                if (count($buyer) > 0) {
                    /** Arrange audit fields changes */
                    $audit_fields_changed = '';
                    $new_line = '';
                    $new_line .= $unit_no != $buyer->unit_no ? "unit no, " : "";
                    $new_line .= $unit_share != $buyer->unit_share ? "unit share, " : "";
                    $new_line .= $owner_name != $buyer->owner_name ? "owner name, " : "";
                    $new_line .= $ic_company_no != $buyer->ic_company_no ? "ic company no, " : "";
                    $new_line .= $address != $buyer->address ? "address, " : "";
                    $new_line .= $phone_no != $buyer->phone_no ? "phone no, " : "";
                    $new_line .= $email != $buyer->email ? "email, " : "";
                    $new_line .= $race != $buyer->race_id ? "race, " : "";
                    $new_line .= $nationality != $buyer->nationality_id ? "nationality, " : "";
                    $new_line .= $remark != $buyer->remarks ? "remark, " : "";
                    $new_line .= $no_petak != $buyer->no_petak ? "no petak, " : "";
                    $new_line .= $no_petak_aksesori != $buyer->no_petak_aksesori ? "no petak aksesori, " : "";
                    $new_line .= $keluasan_lantai_petak != $buyer->keluasan_lantai_petak ? "keluasan lantai petak, " : "";
                    $new_line .= $keluasan_lantai_petak_aksesori != $buyer->keluasan_lantai_petak_aksesori ? "keluasan lantai petak aksesori, " : "";
                    $new_line .= $jenis_kegunaan != $buyer->jenis_kegunaan ? "jenis kegunaan, " : "";
                    $new_line .= $nama2 != $buyer->nama2 ? "nama2, " : "";
                    $new_line .= $ic_no2 != $buyer->ic_no2 ? "ic no2, " : "";
                    $new_line .= $email2 != $buyer->email2 ? "email2, " : "";
                    $new_line .= $phone_no2 != $buyer->phone_no2 ? "phone no2, " : "";
                    $new_line .= $nama3 != $buyer->nama3 ? "nama3, " : "";
                    $new_line .= $ic_no3 != $buyer->ic_no3 ? "ic no3, " : "";
                    $new_line .= $email3 != $buyer->email3 ? "email3, " : "";
                    $new_line .= $phone_no3 != $buyer->phone_no3 ? "phone_no3, " : "";
                    $new_line .= $alamat_surat_menyurat != $buyer->alamat_surat_menyurat ? "alamat surat menyurat, " : "";
                    $new_line .= $caj_penyelenggaraan != $buyer->caj_penyelenggaraan ? "caj penyelenggaraan, " : "";
                    $new_line .= $sinking_fund != $buyer->sinking_fund ? "sinking fund, " : "";
                    $new_line .= $lawyer_name != $buyer->lawyer_name ? "lawyer name, " : "";
                    $new_line .= $lawyer_address != $buyer->lawyer_address ? "lawyer address, " : "";
                    $new_line .= $lawyer_fail_ref_no != $buyer->lawyer_fail_ref_no ? "lawyer fail ref no, " : "";
                    if (!empty($new_line)) {
                        $audit_fields_changed .= "<br/><ul><li> Purchaser : (";
                        $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                    }
                    /** End Arrange audit fields changes */

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
                    $buyer->email2 = $email2;
                    $buyer->phone_no2 = $phone_no2;
                    $buyer->nama3 = $nama3;
                    $buyer->ic_no3 = $ic_no3;
                    $buyer->email3 = $email3;
                    $buyer->phone_no3 = $phone_no3;
                    $buyer->alamat_surat_menyurat = $alamat_surat_menyurat;
                    $buyer->caj_penyelenggaraan = $caj_penyelenggaraan;
                    $buyer->sinking_fund = $sinking_fund;
                    $buyer->lawyer_name = $lawyer_name;
                    $buyer->lawyer_address = $lawyer_address;
                    $buyer->lawyer_fail_ref_no = $lawyer_fail_ref_no;
                    $success = $buyer->save();

                    if ($success) {
                        # Audit Trail
                        $files = Files::find($buyer->file_id);
                        if (!empty($audit_fields_changed)) {
                            $remarks = 'COB Owner List (' . $files->file_no . ') for Unit ' . $buyer->unit_no . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                            $this->addAudit($files->id, "COB File", $remarks);
                        }

                        return "true";
                    } else {
                        return "false";
                    }
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function getBuyerList($file_id)
    {
        $buyer_list = Buyer::where('file_id', Helper::decode($file_id))->where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($buyer_list) > 0) {
            $data = array();
            $no = 1;
            foreach ($buyer_list as $buyer_lists) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AdminController@editBuyer', Helper::encode($buyer_lists->id)) . '\'">
                                <i class="fa fa-pencil"></i>
                            </button>
                            &nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteBuyer(\'' . Helper::encode($buyer_lists->id) . '\')">
                                <i class="fa fa-trash"></i>
                            </button>
                            &nbsp';

                $data_raw = array(
                    $no++,
                    $buyer_lists->unit_no,
                    $buyer_lists->unit_share,
                    $buyer_lists->owner_name,
                    $buyer_lists->ic_company_no,
                    (!empty($buyer_lists->race_id) ? $buyer_lists->race->name_en : ''),
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

    public function deleteBuyer()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['buyer']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $buyer = Buyer::findOrFail($id);
            $buyer->is_deleted = 1;
            $deleted = $buyer->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($buyer->file_id);
                $remarks = 'COB Owner List (' . $files->file_no . ') for Unit ' . $buyer->unit_no . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function importBuyer($id)
    {
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

    public function submitUploadBuyer($id)
    {
        $data = Input::all();
        if (Request::ajax()) {

            $files = Files::findOrFail(Helper::decode($id));
            if ($files) {
                $getAllBuyer = $data['getAllBuyer'];

                if (!empty($getAllBuyer)) {
                    foreach ($getAllBuyer as $buyerList) {

                        // 1. File Number
                        $file_no = '';
                        if (isset($buyerList[1]) && !empty($buyerList[1])) {
                            $file_no = trim($buyerList[1]);
                        }

                        if (!empty($file_no)) {
                            $check_file_id = Files::where('file_no', $file_no)->where('id', $files->id)->first();
                            if ($check_file_id) {
                                $files_id = $check_file_id->id;

                                // 2. NO.UNIT
                                $unit_no = '';
                                if (isset($buyerList[2]) && !empty($buyerList[2])) {
                                    $unit_no = trim($buyerList[2]);
                                }

                                if (!empty($unit_no)) {
                                    $check_buyer = Buyer::where('file_id', $files_id)->where('unit_no', $unit_no)->where('is_deleted', 0)->first();
                                    if (!$check_buyer) {
                                        $race = '';
                                        // 15. BANGSA
                                        if (isset($buyerList[15]) && !empty($buyerList[15])) {
                                            $race_raw = trim($buyerList[15]);

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
                                        // 16. KEWARGANEGARAAN
                                        if (isset($buyerList[16]) && !empty($buyerList[16])) {
                                            $nationality_raw = trim($buyerList[16]);

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
                                        $buyer->file_id = $files_id; // 1. File Number
                                        $buyer->unit_no = $unit_no; // 2. NO.UNIT
                                        $buyer->no_petak = $buyerList[3]; // 3. NO.PETAK
                                        $buyer->no_petak_aksesori = $buyerList[4]; // 4. NO.PETAK AKSESORI (JIKA ADA)
                                        $buyer->keluasan_lantai_petak = $buyerList[7]; // 5. KELUASAN LANTAI PETAK (SQ.M)
                                        $buyer->keluasan_lantai_petak_aksesori = $buyerList[6]; // 6. KELUASAN LANTAI PETAK AKSESORI (SQ.M)
                                        $buyer->unit_share = $buyerList[7]; // 7. UNIT SHARE
                                        $buyer->jenis_kegunaan = $buyerList[8]; // 8. JENIS KEGUNAAN
                                        $buyer->owner_name = $buyerList[9]; // 9. NAMA PEMILIK                                        
                                        $buyer->ic_company_no = $buyerList[10]; // 10. NO.KAD PENGENALAN
                                        $buyer->email = $buyerList[11]; // 11. EMEL
                                        $buyer->phone_no = $buyerList[12]; // 12. NO.TELEFON BIMBIT
                                        $buyer->address = $buyerList[13]; // 13. ALAMAT
                                        $buyer->alamat_surat_menyurat = $buyerList[14]; // 14. ALAMAT SURAT MENYURAT
                                        $buyer->race_id = $race; // 15. BANGSA
                                        $buyer->nationality_id = $nationality; // 16. KEWARGANEGARAAN
                                        // 17. STATUS PENGHUNIAN (PEMILIK,PENYEWA,KOSONG)
                                        $buyer->caj_penyelenggaraan = $buyerList[18]; // 18. CAJ PENYENGGARAAN (RM)
                                        $buyer->sinking_fund = $buyerList[19]; // 19. SINKING FUND (RM)
                                        $buyer->remarks = $buyerList[20]; // 20. CATATAN
                                        $buyer->nama2 = $buyerList[21]; // 21. NAMA PEMILIK 2
                                        $buyer->ic_no2 = $buyerList[22]; // 22. NO.KAD PENGENALAN PEMILIK 2
                                        $buyer->email2 = $buyerList[23]; // 23. EMEL PEMILIK 2
                                        $buyer->phone_no2 = $buyerList[24]; // 24. NO.TELEFON BIMBIT PEMILIK 2
                                        $buyer->nama3 = $buyerList[25]; // 25. NAMA PEMILIK 3
                                        $buyer->ic_no3 = $buyerList[26]; // 26. NO.KAD PENGENALAN PEMILIK 3
                                        $buyer->email3 = $buyerList[27]; // 27. EMEL PEMILIK 3
                                        $buyer->phone_no3 = $buyerList[28]; // 28. NO.TELEFON BIMBIT PEMILIK 3
                                        $buyer->lawyer_name = $buyerList[29]; // 29. NAMA PEGUAMCARA
                                        $buyer->lawyer_address = $buyerList[30]; // 30. ALAMAT PEGUAMCARA
                                        $buyer->lawyer_fail_ref_no = $buyerList[31]; // 31. NO RUJ FAIL PEGUAMCARA
                                        $success = $buyer->save();

                                        if ($success) {
                                            # Audit Trail
                                            $files = Files::find($buyer->file_id);
                                            $remarks = 'COB Owner List (' . $files->file_no . ') for Unit ' . $buyer->unit_no . $this->module['audit']['text']['data_inserted'];
                                            $this->addAudit($buyer->file_id, "COB File", $remarks);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    # Audit Trail
                    $files = Files::find($buyer->file_id);
                    $remarks = 'COB Owner List (' . $files->file_no . ')' . $this->module['audit']['text']['data_imported'];
                    $this->addAudit($buyer->file_id, "COB File", $remarks);

                    return "true";
                } else {
                    return "empty";
                }
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }

    //document
    public function document($id)
    {
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            return Redirect::to('update/monitoring/' . $id);
        }

        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::findOrFail(Helper::decode($id));
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();
        $image = OtherDetails::where('file_id', $file->id)->first();
        $disallow = Helper::isAllow($file->id, $file->company_id);

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

    public function getDocument($id)
    {
        if (Auth::user()->isPreSale()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        $files = Files::findOrFail(Helper::decode($id));

        $document = Document::where('file_id', $files->id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        if (count($document) > 0) {
            $data = array();
            foreach ($document as $documents) {
                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@editDocument', Helper::encode($documents->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDocument(\'' . Helper::encode($documents->id) . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $documents->type->name,
                    $documents->name,
                    $documents->getStatusText(),
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

    public function deleteDocument()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['document']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $id = Helper::decode($data['id']);

            $document = Document::findOrFail($id);
            if ($document) {
                $document->is_deleted = 1;
                $deleted = $document->save();
                if ($deleted) {
                    # Audit Trail
                    $remarks = 'COB Document: ' . $document->name_en . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($document->file_id, "COB File", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteDocumentFile()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['document']['file_delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $document = Document::findOrFail($id);
            if ($document) {
                $document->file_url = "";
                $deleted = $document->save();

                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Document: ' . $document->name_en . ' file' . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($document->file_id, "COB File", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function addDocument($id)
    {
        $file = Files::findOrFail(Helper::decode($id));
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        $image = OtherDetails::where('file_id', $file->id)->first();
        $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasInsert(33));

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

    public function submitAddDocument()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['document']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $document = new Document();
            $document->file_id = Helper::decode($data['file_id']);
            $document->document_type_id = $data['document_type'];
            $document->name = $data['name'];
            $document->remarks = $data['remarks'];
            $document->is_hidden = false;
            $document->is_readonly = false;
            $document->file_url = $data['document_url'];

            if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
                $document->status = Document::APPROVED;
                $document->approval_by = Auth::user()->id;
                $document->approval_date = Carbon::now();
            } else {
                if (Auth::user()->getCOB && Auth::user()->getCOB->short_name == "MBPJ") {
                    $document->status = Document::PENDING;
                } else {
                    $document->status = Document::APPROVED;
                }
            }

            $success = $document->save();

            if ($success) {
                # Audit Trail
                $files = Files::find($document->file_id);
                $remarks = 'COB Document (' . $files->file_no . ')' . $this->module['audit']['text']['data_inserted'];
                $this->addAudit($document->file_id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function editDocument($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $document = Document::findOrFail(Helper::decode($id));
        $file = Files::find($document->file_id);
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->get();
        $image = OtherDetails::where('file_id', $file->id)->first();
        $disallow = Helper::isAllow($document->file_id, $document->file->company_id, !AccessGroup::hasUpdate(33));


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

    public function submitEditDocument()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['document']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $document = Document::findOrFail($id);
            if ($document) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $data['name'] != $document->name ? "name, " : "";
                $new_line .= $data['document_type'] != $document->document_type ? "document type, " : "";
                $new_line .= $data['remarks'] != $document->remarks ? "remarks, " : "";
                $new_line .= $data['document_url'] != $document->document_url ? "document file, " : "";

                if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
                    if (isset($data['status']) && $document->status != $data['status']) {
                        $new_line .= $data['status'] != $document->status ? "status, " : "";
                        $new_line .= Auth::user()->id != $document->status ? "approval by, " : "";
                        $new_line .= Carbon::now() != $document->status ? "approval date, " : "";
                    }
                    if (isset($data['approval_remark']) && $document->approval_remark != $data['approval_remark']) {
                        $new_line .= $data['approval_remark'] != $document->status ? "approval remark, " : "";
                    }
                }

                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> COB Document : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

                $document->document_type_id = $data['document_type'];
                $document->name = $data['name'];
                $document->remarks = $data['remarks'];
                $document->is_hidden = false;
                $document->is_readonly = false;
                $document->file_url = $data['document_url'];

                if (Auth::user()->getAdmin() || Auth::user()->isCOB()) {
                    if (isset($data['status']) && $document->status != $data['status']) {
                        $document->status = $data['status'];
                        $document->approval_by = Auth::user()->id;
                        $document->approval_date = Carbon::now();
                    }
                    if (isset($data['approval_remark']) && $document->approval_remark != $data['approval_remark']) {
                        $document->approval_remark = $data['approval_remark'];
                    }
                }

                $success = $document->save();

                if ($success) {
                    # Audit Trail
                    $files = Files::find($document->file_id);
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'COB Document (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit($document->file_id, "COB File", $remarks);
                    }

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return 'false';
            }
            // } else {
            //     return "false";
            // }
        } else {
            return "false";
        }
    }

    public function fileApproval($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::findOrFail(Helper::decode($id));
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

    public function submitFileApproval()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $id = Helper::decode($data['id']);
            $status = $data['approval_status'];
            $remarks = $data['approval_remarks'];

            if ($status == 1) {
                $is_active = 1;
            } else {
                $is_active = 0;
            }

            $files = Files::findOrFail($id);
            if (count($files) > 0) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $data['approval_status'] != $files->status ? "status, " : "";
                $new_line .= $data['approval_remarks'] != $files->remarks ? "remarks, " : "";
                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Files : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

                $files->is_active = $is_active;
                $files->status = $status;
                $files->remarks = $remarks;
                $files->approved_by = Auth::user()->id;
                $files->approved_at = date('Y-m-d H:i:s');
                $success = $files->save();

                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'File Appproval (' . $files->file_no . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit($files->id, "COB File", $remarks);
                }

                if ($success) {
                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }

    // --- Administrator --- //
    public function company()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(4));

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

    public function getCompany()
    {
        if (!Auth::user()->getAdmin()) {
            $company = Company::where('id', Auth::user()->company_id)->where('is_deleted', 0)->get();
        } else {
            $company = Company::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        }

        if (count($company) > 0) {
            $data = array();
            foreach ($company as $companies) {
                $button = "";
                if ($companies->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveCompany(\'' . Helper::encode($companies->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeCompany(\'' . Helper::encode($companies->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

                $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="window.location=\'' . URL::action('AdminController@editCompany', Helper::encode($companies->id)) . '\'">' . trans('app.forms.edit') . ' <i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteCompany(\'' . Helper::encode($companies->id) . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>';

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

    public function inactiveCompany()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $company = Company::findOrFail($id);
            $company->is_active = 0;
            $updated = $company->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Company :' . $company->name . $this->module['audit']['text']['status_inactive'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function activeCompany()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $company = Company::findOrFail($id);
            $company->is_active = 1;
            $updated = $company->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Company :' . $company->name . $this->module['audit']['text']['status_active'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteCompany()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $company = Company::findOrFail($id);
            $company->is_deleted = 1;
            $deleted = $company->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Company :' . $company->name . $this->module['audit']['text']['data_deleted'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function addCompany()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
        $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(4));

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

    public function submitAddCompany()
    {
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
            $is_hidden = $data['is_hidden'];

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
            $company->is_hidden = $is_hidden;
            $success = $company->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Organization Profile :' . $company->name . $this->module['audit']['text']['data_inserted'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function editCompany($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $company = Company::findOrFail(Helper::decode($id));

        if ($company) {
            $city = City::where('is_active', 1)->where('is_deleted', 0)->orderBy('description', 'asc')->get();
            $country = Country::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $state = State::where('is_active', 1)->where('is_deleted', 0)->orderBy('name', 'asc')->get();
            $disallow = Helper::isAllow(0, $company->id, !AccessGroup::hasUpdate(4));

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

    public function submitEditCompany()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);
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
            $is_hidden = $data['is_hidden'];

            $company = Company::findOrFail($id);
            if (count($company) > 0) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $name != $company->name ? "name, " : "";
                $new_line .= $short_name != $company->short_name ? "short name, " : "";
                $new_line .= $rob_roc_no != $company->rob_roc_no ? "rob roc no, " : "";
                $new_line .= $address1 != $company->address1 ? "address1, " : "";
                $new_line .= $address2 != $company->address2 ? "address2, " : "";
                $new_line .= $address3 != $company->address3 ? "address3, " : "";
                $new_line .= $city != $company->city ? "city, " : "";
                $new_line .= $poscode != $company->poscode ? "poscode, " : "";
                $new_line .= $state != $company->state ? "state, " : "";
                $new_line .= $country != $company->country ? "country, " : "";
                $new_line .= $phone_no != $company->phone_no ? "phone no, " : "";
                $new_line .= $fax_no != $company->fax_no ? "fax no, " : "";
                $new_line .= $email != $company->email ? "email, " : "";
                $new_line .= $image_url != $company->image_url ? "image, " : "";
                $new_line .= $nav_image_url != $company->nav_image_url ? "nav image, " : "";
                $new_line .= $is_hidden != $company->is_hidden ? "is hidden, " : "";
                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Organization : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

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
                $company->is_hidden = $is_hidden;
                $success = $company->save();

                if ($success) {
                    # Audit Trail
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'Organization Profile :' . $company->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit(0, "System Administration", $remarks);
                    }

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        }
    }

    //Access Group
    public function accessGroups()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(5));

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

    public function addAccessGroup()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $module = Module::get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(5));

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

    public function submitAccessGroup()
    {

        $data = Input::all();
        if (Request::ajax()) {

            $description = $data['description'];
            $is_paid = $data['is_paid'];
            $is_admin = $data['is_admin'];
            $is_active = $data['is_active'];
            $remarks = $data['remarks'];

            $role = new Role();
            $role->name = $description;
            $role->is_paid = $is_paid;
            $role->is_admin = $is_admin;
            $role->is_active = $is_active;
            $role->remarks = $remarks;
            $success = $role->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Role :' . $role->name . $this->module['audit']['text']['data_inserted'];
                $this->addAudit(0, "System Administration", $remarks);

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
                    if ($saved) {
                        # Audit Trail
                        $remarks = 'Access Permission for :' . $role->name . $this->module['audit']['text']['data_inserted'];
                        $this->addAudit(0, "System Administration", $remarks);

                        return "true";
                    } else {
                        return "false";
                    }
                }

                return "true";
            }
        }
    }

    public function getAccessGroups()
    {
        $accessgroup = Role::where('is_deleted', 0)->orderBy('id', 'desc')->get();

        if (count($accessgroup) > 0) {
            $data = array();
            foreach ($accessgroup as $accessgroups) {
                $button = "";
                $is_paid = trans('app.forms.no');
                if ($accessgroups->is_paid == 1) {
                    $is_paid = trans('app.forms.yes');
                }

                $is_admin = trans('app.forms.no');
                if ($accessgroups->is_admin == 1) {
                    $is_admin = trans('app.forms.yes');
                }

                if ($accessgroups->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveAccessGroup(\'' . Helper::encode($accessgroups->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeAccessGroup(\'' . Helper::encode($accessgroups->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }
                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateAccessGroup', Helper::encode($accessgroups->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteAccessGroup(\'' . Helper::encode($accessgroups->id) . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    $accessgroups->name,
                    $accessgroups->remarks,
                    $is_paid,
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

    public function inactiveAccessGroup()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $role = Role::findOrFail($id);
            if (count($role) > 0) {
                $role->is_active = 0;
                $updated = $role->save();
                if ($updated) {
                    # Audit Trail
                    $remarks = 'Role :' . $role->name . $this->module['audit']['text']['status_inactive'];
                    $this->addAudit(0, "System Administration", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        }
    }

    public function activeAccessGroup()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $role = Role::findOrFail($id);
            if (count($role) > 0) {
                $role->is_active = 1;
                $updated = $role->save();
                if ($updated) {
                    # Audit Trail
                    $remarks = 'Role :' . $role->name . $this->module['audit']['text']['status_active'];
                    $this->addAudit(0, "System Administration", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        }
    }

    public function deleteAccessGroup()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $role = Role::findOrFail($id);
            if (count($role) > 0) {
                $role->is_deleted = 1;
                $deleted = $role->save();
                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Role :' . $role->name . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit(0, "System Administration", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        }
    }

    public function updateAccessGroup($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $accessgroup = Role::findOrFail(Helper::decode($id));
        $module = Module::get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdate(5));

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

    public function submitUpdateAccessGroup()
    {
        $data = Input::all();

        if (Request::ajax()) {

            $role_id = Helper::decode($data['role_id']);
            $description = $data['description'];
            $is_paid = $data['is_paid'];
            $is_admin = $data['is_admin'];
            $is_active = $data['is_active'];
            $remarks = $data['remarks'];

            $role = Role::findOrFail($role_id);
            /** Arrange audit fields changes */
            $audit_fields_changed = '';
            $new_line = '';
            $new_line .= $description != $role->name ? "description, " : "";
            $new_line .= $is_paid != $role->is_paid ? "is paid, " : "";
            $new_line .= $is_admin != $role->is_admin ? "is admin, " : "";
            $new_line .= $is_active != $role->is_active ? "is active, " : "";
            $new_line .= $remarks != $role->remarks ? "remarks, " : "";
            if (!empty($new_line)) {
                $audit_fields_changed .= "<br/><ul><li> Fields : (";
                $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
            }
            /** End Arrange audit fields changes */

            $role->name = $description;
            $role->is_paid = $is_paid;
            $role->is_admin = $is_admin;
            $role->is_active = $is_active;
            $role->remarks = $remarks;
            $success = $role->save();

            if ($success) {
                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = 'Role :' . $role->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit(0, "System Administration", $remarks);
                }

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
                    $remarks = 'Access Permission :' . $role->name . $this->module['audit']['text']['data_updated'];
                    $this->addAudit(0, "System Administration", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            }
        }
    }

    //user
    public function user()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(6));

        if (empty(Session::get('admin_cob'))) {
            $role = Role::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');
            $cob = Company::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            $role = Role::where('is_admin', 0)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');
            $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        }

        $viewData = array(
            'title' => trans('app.menus.administration.user_management'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'user_list',
            'user_permission' => $user_permission,
            'role' => $role,
            'cob' => $cob,
            'image' => ""
        );

        return View::make('admin_en.user', $viewData);
    }

    public function addUser()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(6));

        if (!Auth::user()->getAdmin()) {
            if (Auth::user()->isCOB()) {
                if (Auth::user()->getRole->is_paid) {
                    $role = Role::where(function ($query) {
                        $query->where('name', 'LIKE', Role::JMB)->orWhere('name', 'LIKE', Role::MC)->orWhere('name', 'LIKE', Role::DEVELOPER);
                    })
                        ->orWhere(function ($query) {
                            $query->where('name', 'LIKE', Role::COB . '%')->where('is_paid', 1);
                        })
                        ->where('is_admin', 0)
                        ->where('is_active', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('name')
                        ->lists('name', 'id');
                } else {
                    $role = Role::where(function ($query) {
                        $query->where('name', 'LIKE', Role::JMB)->orWhere('name', 'LIKE', Role::MC)->orWhere('name', 'LIKE', Role::DEVELOPER);
                    })
                        ->orWhere(function ($query) {
                            $query->where('name', 'LIKE', Role::COB . '%')->where('is_paid', 0);
                        })
                        ->where('is_admin', 0)
                        ->where('is_active', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('name')
                        ->lists('name', 'id');
                }
            } else {
                $role = Role::where(function ($query) {
                    $query->where('name', '!=', 'LPHS')->where('name', '!=', 'Administrator');
                })
                    ->where('is_admin', 0)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->orderBy('name')
                    ->lists('name', 'id');
            }

            $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            if (empty(Session::get('admin_cob'))) {
                $role = Role::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');
                $company = Company::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            } else {
                $role = Role::where('is_admin', 0)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->lists('name', 'id');
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

    public function submitUser()
    {
        $data = Input::all();
        if (Request::ajax()) {
            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['user']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

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
            $receive_mail = $data['receive_mail'];
            $receive_notify = $data['receive_notify'];
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
                    if (($getRole->name == Role::JMB || $getRole->name == Role::MC) || $getRole->name == Role::DEVELOPER) {
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
                    $user->receive_mail = $receive_mail;
                    $user->receive_notify = $receive_notify;
                    $user->status = 1;
                    $user->approved_by = Auth::user()->id;
                    $user->approved_at = date('Y-m-d H:i:s');
                    $user->is_deleted = 0;
                    $success = $user->save();

                    if ($success) {
                        # Audit Trail
                        $remarks = 'User :' . $user->username . $this->module['audit']['text']['data_inserted'];
                        $this->addAudit(0, "System Administration", $remarks);

                        return "true";
                    } else {
                        return "false";
                    }
                } else {
                    return "false";
                }
            } else {
                return "username_in_use";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function getUser()
    {
        if (!Auth::user()->getAdmin()) {
            $users = User::leftJoin('role', 'users.role', '=', 'role.id')
                ->leftJoin('company', 'users.company_id', '=', 'company.id')
                ->leftJoin('files', 'users.file_id', '=', 'files.id')
                ->select(['users.*', 'role.name as role', 'company.name as council', 'files.file_no as file_no'])
                ->where('company.id', Auth::user()->company_id)
                ->where('users.is_deleted', 0);
        } else {
            if (empty(Session::get('admin_cob'))) {
                $users = User::join('role', 'users.role', '=', 'role.id')
                    ->leftJoin('company', 'users.company_id', '=', 'company.id')
                    ->leftJoin('files', 'users.file_id', '=', 'files.id')
                    ->select(['users.*', 'role.name as role', 'company.name as council', 'files.file_no as file_no'])
                    ->where('users.is_deleted', 0);
            } else {
                $users = User::leftJoin('role', 'users.role', '=', 'role.id')
                    ->leftJoin('company', 'users.company_id', '=', 'company.id')
                    ->leftJoin('files', 'users.file_id', '=', 'files.id')
                    ->select(['users.*', 'role.name as role', 'company.name as council', 'files.file_no as file_no'])
                    ->where('company.id', Session::get('admin_cob'))
                    ->where('users.is_deleted', 0);
            }
        }

        if ($users) {
            return Datatables::of($users)
                ->editColumn('is_active', function ($model) {
                    if ($model->is_active) {
                        return trans('app.forms.yes');
                    }

                    return trans('app.forms.no');
                })
                ->editColumn('status', function ($model) {
                    if ($model->status == 0) {
                        return trans('app.forms.pending');
                    } else if ($model->status == 1) {
                        return trans('app.forms.approved');
                    }

                    return trans('app.forms.rejected');
                })
                ->addColumn('action', function ($model) {
                    $button = '';
                    if ($model->is_active) {
                        $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveUser(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                    } else {
                        $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeUser(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                    }
                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateUser', Helper::encode($model->id)) . '\'" title="Edit"><i class="fa fa-pencil"></i></button>&nbsp;';
                    $button .= '<button type="button" class="btn btn-xs btn-warning" onclick="window.location=\'' . URL::action('AdminController@getUserDetails', Helper::encode($model->id)) . '\'" title="View"><i class="fa fa-eye"></i></button>&nbsp;';
                    $button .= '<button class="btn btn-xs btn-danger" onclick="deleteUser(\'' . Helper::encode($model->id) . '\')" title="Delete"><i class="fa fa-trash"></i></button>';

                    return $button;
                })
                ->make(true);
        }
    }

    public function getUserDetails($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $user = User::findOrFail(Helper::decode($id));
        $company = Company::find($user->company_id);
        $disallow = Helper::isAllow(0, $user->company_id, !AccessGroup::hasAccess(6));

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

    public function submitApprovedUser()
    {
        $data = Input::all();

        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['user']['approve'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);
            $status = $data['status'];
            $remark = $data['remarks'];

            $user = User::findOrFail($id);
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
                $remarks = 'User :' . $user->username . $this->module['audit']['text']['data_approved'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function inactiveUser()
    {
        $data = Input::all();
        if (Request::ajax()) {
            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['user']['status_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $user = User::findOrFail($id);
            $user->is_active = 0;
            $updated = $user->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'User :' . $user->username . $this->module['audit']['text']['status_inactive'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function activeUser()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['user']['status_update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $user = User::findOrFail($id);
            $user->is_active = 1;
            $updated = $user->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'User :' . $user->username . $this->module['audit']['text']['status_active'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteUser()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['user']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $user = User::findOrFail($id);
            $user->is_deleted = 1;
            $deleted = $user->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'User :' . $user->username . $this->module['audit']['text']['data_deleted'];
                $this->addAudit(0, "System Administration", $remarks);

                return "true";
            } else {
                return "false";
            }

            // } else {
            //     return "false";
            // }
        }
    }

    public function findFile()
    {
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

                    return $result;
                } else {
                    return "<option value=''>" . trans('app.forms.please_select') . "</option>";
                }
            } else {
                return "<option value=''>" . trans('app.forms.please_select') . "</option>";
            }
        }
    }

    public function updateUser($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $user = User::findOrFail(Helper::decode($id));
        $query_role = Role::where('is_active', 1)
            ->where('is_deleted', 0);
        if (!Auth::user()->getAdmin()) {
            if (Auth::user()->isCOB()) {
                if (Auth::user()->getRole->is_paid) {
                    $query_role = $query_role->where(function ($query) {
                        $query->where('name', 'LIKE', Role::JMB)->orWhere('name', 'LIKE', Role::MC)->orWhere('name', 'LIKE', Role::DEVELOPER);
                    })
                        ->orWhere(function ($query) {
                            $query->where('name', 'LIKE', Role::COB . '%')->where('is_paid', 1);
                        })
                        ->where('is_admin', 0);
                } else {
                    $query_role = $query_role->where(function ($query) {
                        $query->where('name', 'LIKE', Role::JMB)->orWhere('name', 'LIKE', Role::MC)->orWhere('name', 'LIKE', Role::DEVELOPER);
                    })
                        ->orWhere(function ($query) {
                            $query->where('name', 'LIKE', Role::COB . '%')->where('is_paid', 0);
                        })
                        ->where('is_admin', 0);
                }
            } else {
                $query_role = $query_role->where(function ($query) {
                    $query->where('name', '!=', 'LPHS')->where('name', '!=', 'Administrator');
                })
                    ->where('is_admin', 0);
            }

            // $company = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        } else {
            // if (empty(Session::get('admin_cob'))) {
            //     // $company = Company::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            // } else {
            //     $query_role = $query_role->where('is_admin', 0);
            //     // $company = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            // }
            if (!empty(Session::get('admin_cob'))) {
                $query_role = $query_role->where('is_admin', 0);
            }
        }

        $role = $query_role->orderBy('name')
            ->lists('name', 'id');
        $company = Company::self()->orderBy('name')->get();
        $files = Files::where('company_id', $user->company_id)->where('is_deleted', 0)->orderBy('file_no')->get();
        $disallow = Helper::isAllow(0, $user->company_id, !AccessGroup::hasUpdate(6));

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

    public function submitUpdateUser()
    {
        $data = Input::all();
        if (Request::ajax()) {
            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['user']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $id = Helper::decode($data['id']);
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
            $receive_mail = $data['receive_mail'];
            $receive_notify = $data['receive_notify'];

            $user = User::findOrFail($id);
            if ($user) {
                $getRole = Role::where('name', $role)->first();

                if ($getRole) {
                    /** Arrange audit fields changes */
                    $audit_fields_changed = '';
                    $new_line = '';
                    $new_line .= $name != $user->full_name ? "full name, " : "";
                    $new_line .= $email != $user->email ? "email, " : "";
                    $new_line .= $phone_no != $user->phone_no ? "phone no, " : "";
                    $new_line .= $is_active != $user->is_active ? "is active, " : "";
                    $new_line .= $getRole->id != $user->role ? "role, " : "";
                    if (($getRole->name == Role::JMB || $getRole->name == Role::MC) || $getRole->name == Role::DEVELOPER) {
                        $new_line .= (!empty($start_date) && ($start_date != $user->start_date)) ? "start date, " : "";
                        $new_line .= (!empty($end_date) && ($end_date != $user->end_date)) ? "end date, " : "";
                        $new_line .= (!empty($file_id) && ($file_id != $user->file_id)) ? "file id, " : "";
                    }
                    $new_line .= !empty($password) ? "password, " : "";
                    $new_line .= $company != $user->company_id ? "company, " : "";
                    $new_line .= $remarks != $user->remarks ? "remarks, " : "";
                    $new_line .= $receive_mail != $user->receive_mail ? "receive mail, " : "";
                    $new_line .= $receive_notify != $user->receive_notify ? "receive notify, " : "";
                    if (!empty($new_line)) {
                        $audit_fields_changed .= "<br/><ul><li> Fields : (";
                        $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                    }
                    /** End Arrange audit fields changes */

                    $user->full_name = $name;
                    $user->email = $email;
                    $user->phone_no = $phone_no;
                    $user->role = $getRole->id;
                    if (($getRole->name == Role::JMB || $getRole->name == Role::MC)  || $getRole->name == Role::DEVELOPER) {
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
                    $user->receive_mail = $receive_mail;
                    $user->receive_notify = $receive_notify;
                    $success = $user->save();

                    if ($success) {
                        # Audit Trail
                        if (!empty($audit_fields_changed)) {
                            $remarks = 'User :' . $user->username . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                            $this->addAudit(0, "System Administration", $remarks);
                        }

                        return "true";
                    } else {
                        return "false";
                    }
                } else {
                    return "false";
                }
            } else {
                return "false";
            }

            // } else {
            //     return "false";
            // }
        }
    }

    //memo
    public function memo()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = array(99 => trans('app.forms.all'));
            $cob += Company::where('is_active', 1)->where('is_main', 0)->orderBy('name', 'asc')->lists('name', 'id');
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->lists('name', 'id');
        }
        $memotype = MemoType::where('is_active', 1)->where('is_deleted', 0)->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(7));

        $viewData = array(
            'title' => trans('app.menus.administration.memo_management'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'memo_maintenence_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'memotype' => $memotype,
            'image' => ""
        );

        return View::make('page_en.memo', $viewData);
    }

    public function addMemo()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = array(99 => trans('app.forms.all'));
            $cob += Company::where('is_active', 1)->where('is_main', 0)->orderBy('name', 'asc')->lists('name', 'id');
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->lists('name', 'id');
        }

        $fileList = Files::fileList();
        $memotype = MemoType::where('is_active', 1)->where('is_deleted', 0)->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(7));

        $viewData = array(
            'title' => trans('app.buttons.add_memo'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'memo_maintenence_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'fileList' => $fileList,
            'memotype' => $memotype,
            'image' => ""
        );

        return View::make('page_en.add_memo', $viewData);
    }

    public function submitMemo()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $company = $data['company'];
            $file_id = $data['file'];
            $memo_type = $data['memo_type'];
            $memo_date = $data['memo_date'];
            $publish_date = $data['publish_date'];
            $expired_date = $data['expired_date'];
            $subject = $data['subject'];
            $description = $data['description'];
            $document_file_url = $data['document_file_url'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $memo = new Memo();
            $memo->company_id = $company;
            $memo->file_id = (!empty($file_id) ? $file_id : null);
            $memo->memo_type_id = $memo_type;
            $memo->memo_date = $memo_date;
            $memo->publish_date = $publish_date;
            $memo->expired_date = (!empty($expired_date) ? $expired_date : null);
            $memo->subject = $subject;
            $memo->description = $description;
            $memo->document_file = $document_file_url;
            $memo->remarks = $remarks;
            $memo->is_active = $is_active;
            $success = $memo->save();

            if ($success) {
                # Audit Trail
                $remarks = $memo->subject . $this->module['audit']['text']['data_inserted'];
                $this->addAudit(0, "Memo", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function getMemo()
    {
        if (!Auth::user()->getAdmin()) {
            $memo = Memo::join('memo_type', 'memo.memo_type_id', '=', 'memo_type.id')
                ->select('memo.*')
                ->where('memo.company_id', Auth::user()->company_id)
                ->where('memo.is_deleted', 0)
                ->where('memo_type.is_deleted', 0);
        } else {
            if (empty(Session::get('admin_cob'))) {
                $memo = Memo::join('memo_type', 'memo.memo_type_id', '=', 'memo_type.id')
                    ->select('memo.*')
                    ->where('memo.is_deleted', 0)
                    ->where('memo_type.is_deleted', 0);
            } else {
                $memo = Memo::join('memo_type', 'memo.memo_type_id', '=', 'memo_type.id')
                    ->select('memo.*')
                    ->where('memo.company_id', Session::get('admin_cob'))
                    ->where('memo.is_deleted', 0)
                    ->where('memo_type.is_deleted', 0);
            }
        }

        if ($memo) {
            return Datatables::of($memo)
                ->editColumn('description', function ($model) {
                    return ($model->description ? $model->description : '');
                })
                ->editColumn('subject', function ($model) {
                    return ($model->subject ? $model->subject : '');
                })
                ->editColumn('memo_date', function ($model) {
                    return ($model->memo_date ? date('d-M-Y', strtotime($model->memo_date)) : '');
                })
                ->editColumn('publish_date', function ($model) {
                    return ($model->publish_date ? date('d-M-Y', strtotime($model->publish_date)) : '');
                })
                ->editColumn('expired_date', function ($model) {
                    return (($model->expired_date && $model->expired_date != "0000-00-00") ? date('d-M-Y', strtotime($model->expired_date)) : '');
                })
                ->addColumn('memo_type', function ($model) {
                    return ($model->memo_type_id ? $model->type->description : '');
                })
                ->addColumn('company', function ($model) {
                    return (($model->company_id && $model->company_id != 99) ? $model->company->short_name : trans('app.forms.all'));
                })
                ->addColumn('file_no', function ($model) {
                    return ($model->file ? $model->file->file_no : '-');
                })
                ->editColumn('is_active', function ($model) {
                    $status = trans('app.forms.inactive');
                    if ($model->is_active) {
                        $status = trans('app.forms.active');
                    }
                    return $status;
                })
                ->addColumn('action', function ($model) {
                    $button = "";
                    if ($model->is_active) {
                        $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="inactiveMemo(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                    } else {
                        $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeMemo(\'' . Helper::encode($model->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                    }
                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateMemo', Helper::encode($model->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                    $button .= '<button class="btn btn-xs btn-danger" onclick="deleteMemo(\'' . Helper::encode($model->id) . '\')"><i class="fa fa-trash"></i></button>';

                    return $button;
                })
                ->make(true);
        }
    }

    public function inactiveMemo()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $memo = Memo::findOrFail($id);
            $memo->is_active = 0;
            $updated = $memo->save();
            if ($updated) {
                # Audit Trail
                $remarks = $memo->subject . $this->module['audit']['text']['status_inactive'];
                $this->addAudit(0, "Memo", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function activeMemo()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $memo = Memo::findOrFail($id);
            $memo->is_active = 1;
            $updated = $memo->save();
            if ($updated) {
                # Audit Trail
                $remarks = $memo->subject . $this->module['audit']['text']['status_active'];
                $this->addAudit(0, "Memo", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteMemo()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $memo = Memo::findOrFail($id);
            $memo->is_deleted = 1;
            $deleted = $memo->save();
            if ($deleted) {
                # Audit Trail
                $remarks = $memo->subject . $this->module['audit']['text']['data_deleted'];
                $this->addAudit(0, "Memo", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function updateMemo($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        if (empty(Session::get('admin_cob'))) {
            $cob = array(99 => trans('app.forms.all'));
            $cob += Company::where('is_active', 1)->where('is_main', 0)->orderBy('name', 'asc')->lists('name', 'id');
        } else {
            $cob = Company::where('id', Session::get('admin_cob'))->lists('name', 'id');
        }
        $memo = Memo::findOrFail(Helper::decode($id));
        $fileList = Files::fileList();
        $memotype = MemoType::where('is_active', 1)->where('is_deleted', 0)->get();
        $disallow = Helper::isAllow(0, $memo->company_id, !AccessGroup::hasAccess(7));

        $viewData = array(
            'title' => trans('app.buttons.update_memo'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'memo_maintenence_list',
            'user_permission' => $user_permission,
            'cob' => $cob,
            'memo' => $memo,
            'fileList' => $fileList,
            'memotype' => $memotype,
            'image' => ""
        );

        return View::make('page_en.update_memo', $viewData);
    }

    public function submitUpdateMemo()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $id = Helper::decode($data['id']);
            $company = $data['company'];
            $file_id = $data['file'];
            $memo_type = $data['memo_type'];
            $memo_date = $data['memo_date'];
            $publish_date = $data['publish_date'];
            $expired_date = $data['expired_date'];
            $subject = $data['subject'];
            $description = $data['description'];
            $document_file_url = $data['document_file_url'];
            $remarks = $data['remarks'];
            $is_active = $data['is_active'];

            $memo = Memo::findOrFail($id);
            /** Arrange audit fields changes */
            $audit_fields_changed = '';
            $new_line = '';
            $new_line .= $company != $memo->company_id ? "company, " : "";
            $new_line .= $file_id != $memo->file_id ? "file id, " : "";
            $new_line .= $memo_type != $memo->memo_type_id ? "memo type, " : "";
            $new_line .= $memo_date != $memo->memo_date ? "memo date, " : "";
            $new_line .= $publish_date != $memo->publish_date ? "publish date, " : "";
            $new_line .= (!empty($expired_date) && ($expired_date != $memo->expired_date)) ? "expired date, " : "";
            $new_line .= $subject != $memo->subject ? "subject, " : "";
            $new_line .= $description != $memo->description ? "description, " : "";
            $new_line .= $document_file_url != $memo->document_file ? "document file, " : "";
            $new_line .= $remarks != $memo->remarks ? "remarks, " : "";
            $new_line .= $is_active != $memo->is_active ? "is active, " : "";
            if (!empty($new_line)) {
                $audit_fields_changed .= "<br/><ul><li> Fields : (";
                $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
            }
            /** End Arrange audit fields changes */

            $memo->company_id = $company;
            $memo->file_id = (!empty($file_id) ? $file_id : null);
            $memo->memo_type_id = $memo_type;
            $memo->memo_date = $memo_date;
            $memo->publish_date = $publish_date;
            $memo->expired_date = (!empty($expired_date) ? $expired_date : null);
            $memo->subject = $subject;
            $memo->description = $description;
            $memo->document_file = $document_file_url;
            $memo->remarks = $remarks;
            $memo->is_active = $is_active;
            $success = $memo->save();

            if ($success) {
                # Audit Trail
                if (!empty($audit_fields_changed)) {
                    $remarks = $memo->subject . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    $this->addAudit(0, "Memo", $remarks);
                }

                return "true";
            } else {
                return "false";
            }
        }
    }

    //rating
    public function rating()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::file()->orderBy('year', 'asc')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(40));

        $viewData = array(
            'title' => trans('app.menus.administration.rating'),
            'panel_nav_active' => 'admin_panel',
            'main_nav_active' => 'admin_main',
            'sub_nav_active' => 'rating_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'image' => ""
        );

        return View::make('admin_en.rating', $viewData);
    }

    public function getRating()
    {
        $rating = Scoring::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        if (count($rating) > 0) {
            $data = array();
            foreach ($rating as $ratings) {
                $button = "";

                if (!empty($ratings->file_id)) {
                    if (!Auth::user()->getAdmin()) {
                        if (!empty(Auth::user()->company_id)) {
                            if ($ratings->file->company_id != Auth::user()->company_id) {
                                continue;
                            }
                        }
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            if ($ratings->file->company_id != Session::get('admin_cob')) {
                                continue;
                            }
                        }
                    }
                } else {
                    if (!Auth::user()->getAdmin()) {
                        continue;
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            continue;
                        }
                    }
                }

                $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AdminController@updateRating', Helper::encode($ratings->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" title="Delete" onclick="deleteRating(\'' . Helper::encode($ratings->id) . '\')"><i class="fa fa-trash"></i></button>';

                $ratings_A = ((($ratings->score1 + $ratings->score2 + $ratings->score3 + $ratings->score4 + $ratings->score5) / 25) * 25);
                $ratings_B = ((($ratings->score6 + $ratings->score7 + $ratings->score8 + $ratings->score9 + $ratings->score10) / 25) * 25);
                $ratings_C = ((($ratings->score11 + $ratings->score12 + $ratings->score13 + $ratings->score14) / 20) * 20);
                $ratings_D = ((($ratings->score15 + $ratings->score16 + $ratings->score17 + $ratings->score18) / 20) * 20);
                $ratings_E = ((($ratings->score19 + $ratings->score20 + $ratings->score21) / 15) * 10);

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
                    $ratings->file->strata->name,
                    (!empty($ratings->date) ? date('d-M-Y', strtotime($ratings->date)) : '<i>(not set)</i>'),
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

    public function addRating()
    {
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(40));

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

    public function submitAddRating()
    {
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

            $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 25) * 25);
            $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 25) * 25);
            $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 20) * 20);
            $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 20) * 20);
            $ratings_E = ((($score19 + $score20 + $score21) / 15) * 10);

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
                $files = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $files->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . $this->module['audit']['text']['data_inserted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }

    public function updateRating($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::file()->orderBy('id', 'asc')->get();
        $rating = Scoring::findOrFail(Helper::decode($id));
        $disallow = Helper::isAllow(0, $rating->file->company_id, !AccessGroup::hasUpdate(40));
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

    public function submitUpdateRating()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);
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

            $ratings_A = ((($score1 + $score2 + $score3 + $score4 + $score5) / 25) * 25);
            $ratings_B = ((($score6 + $score7 + $score8 + $score9 + $score10) / 25) * 25);
            $ratings_C = ((($score11 + $score12 + $score13 + $score14) / 20) * 20);
            $ratings_D = ((($score15 + $score16 + $score17 + $score18) / 20) * 20);
            $ratings_E = ((($score19 + $score20 + $score21) / 15) * 10);

            $total_score = $ratings_A + $ratings_B + $ratings_C + $ratings_D + $ratings_E;

            $scoring = Scoring::findOrFail($id);
            if ($scoring) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $date != $scoring->date ? "date, " : "";
                $new_line .= $score1 != $scoring->score1 ? "score1, " : "";
                $new_line .= $score2 != $scoring->score2 ? "score2, " : "";
                $new_line .= $score3 != $scoring->score3 ? "score3, " : "";
                $new_line .= $score4 != $scoring->score4 ? "score4, " : "";
                $new_line .= $score5 != $scoring->score5 ? "score5, " : "";
                $new_line .= $score6 != $scoring->score6 ? "score6, " : "";
                $new_line .= $score7 != $scoring->score7 ? "score7, " : "";
                $new_line .= $score8 != $scoring->score8 ? "score8, " : "";
                $new_line .= $score9 != $scoring->score9 ? "score9, " : "";
                $new_line .= $score10 != $scoring->score10 ? "score10, " : "";
                $new_line .= $score11 != $scoring->score11 ? "score11, " : "";
                $new_line .= $score12 != $scoring->score12 ? "score12, " : "";
                $new_line .= $score13 != $scoring->score13 ? "score13, " : "";
                $new_line .= $score14 != $scoring->score14 ? "score14, " : "";
                $new_line .= $score15 != $scoring->score15 ? "score15, " : "";
                $new_line .= $score16 != $scoring->score16 ? "score16, " : "";
                $new_line .= $score17 != $scoring->score17 ? "score17, " : "";
                $new_line .= $score18 != $scoring->score18 ? "score18, " : "";
                $new_line .= $score19 != $scoring->score19 ? "score19, " : "";
                $new_line .= $score20 != $scoring->score20 ? "score20, " : "";
                $new_line .= $score21 != $scoring->score21 ? "score21, " : "";
                $new_line .= $total_score != $scoring->total_score ? "total_score, " : "";
                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Fields : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

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
                    $files = Files::find($scoring->file_id);
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'COB Rating (' . $files->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . $audit_fields_changed;
                        $this->addAudit($files->id, "COB File", $remarks);
                    }

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }

    public function deleteRating()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $scoring = Scoring::findOrFail($id);
            $scoring->is_deleted = 1;
            $deleted = $scoring->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($scoring->file_id);
                $remarks = 'COB Rating (' . $files->file_no . ') dated ' . date('d/m/Y', strtotime($scoring->date)) . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    //form
    public function form()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $formtype = FormType::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(41));

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

    public function getForm()
    {
        $form = AdminForm::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        if (count($form) > 0) {
            $data = array();

            foreach ($form as $forms) {

                if (!empty($forms->company_id)) {
                    if (!Auth::user()->getAdmin()) {
                        if (!empty(Auth::user()->company_id)) {
                            if ($forms->company_id != Auth::user()->company_id) {
                                continue;
                            }
                        }
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            if ($forms->company_id != Session::get('admin_cob')) {
                                continue;
                            }
                        }
                    }
                } else {
                    if (!Auth::user()->getAdmin()) {
                        continue;
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            continue;
                        }
                    }
                }

                $formtype = FormType::find($forms->form_type_id);

                $button = "";
                if ($forms->is_active == 1) {
                    $status = trans('app.forms.active');
                    $button .= '<button type="button" class="btn btn-xs btn-default" onclick="inactiveForm(\'' . Helper::encode($forms->id) . '\')">' . trans('app.forms.inactive') . '</button>&nbsp;';
                } else {
                    $status = trans('app.forms.inactive');
                    $button .= '<button type="button" class="btn btn-xs btn-primary" onclick="activeForm(\'' . Helper::encode($forms->id) . '\')">' . trans('app.forms.active') . '</button>&nbsp;';
                }

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateForm', Helper::encode($forms->id)) . '\'">' . trans('app.forms.edit') . ' <i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteForm(\'' . Helper::encode($forms->id) . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>';

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

    public function inactiveForm()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $form = AdminForm::findOrFail($id);
            $form->is_active = 0;
            $updated = $form->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . $this->module['audit']['text']['status_inactive'];
                $this->addAudit(0, "Form", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function activeForm()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $form = AdminForm::findOrFail($id);
            $form->is_active = 1;
            $updated = $form->save();
            if ($updated) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . $this->module['audit']['text']['status_active'];
                $this->addAudit(0, "Form", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteForm()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $form = AdminForm::findOrFail($id);
            $form->is_deleted = 1;
            $deleted = $form->save();
            if ($deleted) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . $this->module['audit']['text']['data_deleted'];
                $this->addAudit(0, "Form", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function deleteFormFile()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $form = AdminForm::findOrFail($id);
            $form->file_url = "";
            $deleted = $form->save();

            if ($deleted) {
                # Audit Trail
                $remarks = 'Form: ' . $form->name_en . ' file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit(0, "Form", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function addForm()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $cob = Company::self()->orderBy('name')->get();
        $formtype = FormType::where('is_active', 1)->where('is_deleted', 0)->orderBy('sort_no')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(41));

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

    public function submitAddForm()
    {
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
                $remarks = 'Form: ' . $form->name_en . $this->module['audit']['text']['data_inserted'];
                $this->addAudit(0, "Form", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function updateForm($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $form = AdminForm::findOrFail(Helper::decode($id));

        // if (!Auth::user()->getAdmin()) {
        //     $cob = Company::where('id', Auth::user()->company_id)->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        // } else {
        //     if (empty(Session::get('admin_cob'))) {
        //         $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        //     } else {
        //         $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
        //     }
        // }

        $cob = Company::self()->orderBy('name')->get();
        $formtype = FormType::where('is_active', 1)->where('is_deleted', 0)->get();
        $disallow = Helper::isAllow(0, $form->company_id, !AccessGroup::hasUpdate(41));

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

    public function submitUpdateForm()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $id = Helper::decode($data['id']);

            $form = AdminForm::findOrFail($id);
            if ($form) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $data['company_id'] != $form->company_id ? "company, " : "";
                $new_line .= $data['form_type'] != $form->form_type_id ? "form type, " : "";
                $new_line .= $data['name_en'] != $form->name_en ? "name en, " : "";
                $new_line .= $data['name_my'] != $form->name_my ? "name my, " : "";
                $new_line .= $data['sort_no'] != $form->sort_no ? "sort no, " : "";
                $new_line .= $data['is_active'] != $form->is_active ? "status, " : "";
                $new_line .= $data['form_url'] != $form->file_url ? "file, " : "";
                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Fields : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

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
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'Form id: ' . $form->id . ' name: ' . $form->name_en . $audit_fields_changed;
                        $this->addAudit(0, "Form", $remarks);
                    }

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

    //form download
    public function formDownload()
    {
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

    // Sept 2020
    public function deleteNoticeAgmEgm()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['noticeAgmEgm'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->notice_agm_egm_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' notice AGM/EGM file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteMinutesAgmEgm()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['minutesAgmEgm'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->minutes_agm_egm_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' minutes AGM/EGM file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteMinutesAjk()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['minutesAjk'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->minutes_ajk_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' minutes AJK file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteEligibleVote()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['eligibleVote'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->eligible_vote_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' eligible vote file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteAttendMeeting()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['attendMeeting'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->attend_meeting_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' attend meeting file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteProksi()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['proksi'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->proksi_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' proksi file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteAjkInfo()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['ajkInfo'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->ajk_info_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' AJK info file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteIc()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['ic'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->ic_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' IC file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deletePurchaseAggrement()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['purchaseAggrement'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->purchase_aggrement_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' purchase aggrement file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteStrataTitle()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['strataTitle'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->strata_title_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' strata title file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteMaintenanceStatement()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['maintenanceStatement'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->maintenance_statement_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' maintenance statement file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteIntegrityPledge()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['integrityPledge'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->integrity_pledge_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' integrity pledge file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteSwornStatement()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['reportAuditedFinancial'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->sworn_statement_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' report audited financial file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteReportAuditedFinancial()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['reportAuditedFinancial'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->report_audited_financial_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' report audited financial file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function deleteHouseRules()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['delete']['houseRules'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
            $agm_details->house_rules_url = "";
            $deleted = $agm_details->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($agm_details->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_details->agm_date)) . ' house rules file' . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB File", $remarks);

                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    /*
     * 13 October 2020
     */

    //defect
    public function defect()
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
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
        $defectCategory = DefectCategory::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(45));

        $viewData = array(
            'title' => trans('app.menus.agm.defect'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'defect_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'defectCategory' => $defectCategory,
            'image' => ""
        );

        return View::make('page_en.defect', $viewData);
    }

    public function getDefect()
    {
        if (!empty(Auth::user()->file_id)) {
            $defect = Defect::where('file_id', Auth::user()->file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        } else {
            $defect = Defect::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        }

        if (count($defect) > 0) {
            $data = array();
            foreach ($defect as $defects) {
                $button = "";

                if (!empty($defects->file_id)) {
                    if (!Auth::user()->getAdmin()) {
                        if (!empty(Auth::user()->company_id)) {
                            if ($defects->file->company_id != Auth::user()->company_id) {
                                continue;
                            }
                        }
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            if ($defects->file->company_id != Session::get('admin_cob')) {
                                continue;
                            }
                        }
                    }
                } else {
                    if (!Auth::user()->getAdmin()) {
                        continue;
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            continue;
                        }
                    }
                }

                $status = trans('app.forms.pending');
                if ($defects->status == 1) {
                    $status = trans('app.forms.resolved');
                }

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateDefect', Helper::encode($defects->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDefect(\'' . Helper::encode($defects->id) . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    (!empty($defects->file_id) ? $defects->file->file_no : '<i>(not set)</i>'),
                    $defects->category->name,
                    $defects->name,
                    $defects->description,
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

    public function deleteDefect()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $defect = Defect::findOrFail($id);
            if ($defect) {
                $defect->is_deleted = 1;
                $deleted = $defect->save();
                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Defect: ' . $defect->name . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($defect->file_id, "Defect", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        }
    }

    public function deleteDefectAttachment()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $defect = Defect::findOrFail($id);
            if ($defect) {
                $defect->attachment_url = "";
                $deleted = $defect->save();

                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Defect: ' . $defect->name . ' attachement' . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($defect->file_id, "Defect", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        }
    }

    public function addDefect()
    {
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
        $defectCategory = DefectCategory::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(45));

        $viewData = array(
            'title' => trans('app.menus.agm.add_defect'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'defect_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'defectCategory' => $defectCategory,
            'image' => ""
        );

        return View::make('page_en.add_defect', $viewData);
    }

    public function submitAddDefect()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $defect = new Defect();
            $defect->file_id = $data['file_id'];
            $defect->defect_category_id = $data['defect_category'];
            $defect->name = $data['name'];
            $defect->description = $data['description'];
            $defect->attachment_url = $data['defect_attachment'];
            $success = $defect->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Defect: ' . $defect->name . $this->module['audit']['text']['data_inserted'];
                $this->addAudit($defect->file_id, "Defect", $remarks);

                return "true";
            } else {
                return "false";
            }
        }
    }

    public function updateDefect($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $defect = Defect::findOrFail(Helper::decode($id));
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
        $defectCategory = DefectCategory::where('is_active', 1)->where('is_deleted', 0)->get();
        $disallow = Helper::isAllow($defect->file_id, $defect->file->company_id, !AccessGroup::hasUpdate(45));

        $viewData = array(
            'title' => trans('app.menus.agm.edit_defect'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'defect_list',
            'user_permission' => $user_permission,
            'defect' => $defect,
            'files' => $files,
            'defectCategory' => $defectCategory,
            'image' => ""
        );

        return View::make('page_en.edit_defect', $viewData);
    }

    public function submitUpdateDefect()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $id = Helper::decode($data['id']);

            $defect = Defect::findOrFail($id);
            if ($defect) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $data['file_id'] != $defect->file_id ? "file id, " : "";
                $new_line .= $data['defect_category'] != $defect->defect_category_id ? "defect category, " : "";
                $new_line .= $data['name'] != $defect->name ? "name, " : "";
                $new_line .= $data['description'] != $defect->description ? "description, " : "";
                $new_line .= $data['defect_attachment'] != $defect->attachment_url ? "attachment, " : "";
                $new_line .= (!empty($data['status']) && ($data['status'] != $defect->status)) ? "status, " : "";
                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Fields : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

                $defect->file_id = $data['file_id'];
                $defect->defect_category_id = $data['defect_category'];
                $defect->name = $data['name'];
                $defect->description = $data['description'];
                $defect->attachment_url = $data['defect_attachment'];
                if ($data['status']) {
                    $defect->status = $data['status'];
                }
                $success = $defect->save();

                if ($success) {
                    # Audit Trail
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'Defect: ' . $defect->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit($defect->file_id, "Defect", $remarks);
                    }

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

    //insurance
    public function insurance($id)
    {
        //$filename = Files::getFileName();
        //return "<pre>" . return_r($filename, true) . "</pre>";
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $insuranceProvider = InsuranceProvider::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();

        if ($id == 'All') {
            $filename = Files::getFileName();
            $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(46));

            if ($disallow) {
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
                'title' => trans('app.menus.agm.insurance'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => 'insurance_list',
                'user_permission' => $user_permission,
                'filename' => $filename,
                'insuranceProvider' => $insuranceProvider,
                'image' => ""
            );

            return View::make('insurance_en.insurance', $viewData);
        } else {
            $file = Files::findOrFail(Helper::decode($id));
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                return Redirect::to('updateFile/others/' . $id);
            }
            if ($file) {
                $image = OtherDetails::where('file_id', $file->id)->first();
                $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasAccess(3));

                if ($disallow) {
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
                    'title' => trans('app.menus.cob.update_cob_file'),
                    'panel_nav_active' => 'cob_panel',
                    'main_nav_active' => 'cob_main',
                    'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
                    'user_permission' => $user_permission,
                    'file' => $file,
                    'insuranceProvider' => $insuranceProvider,
                    'image' => (!empty($image->image_url) ? $image->image_url : '')
                );

                return View::make('page_en.update_insurance', $viewData);
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
    }

    public function getInsurance($id)
    {
        if ($id == 'All') {
            if (!empty(Auth::user()->file_id)) {
                $insurance = Insurance::has('file')->where('file_id', Auth::user()->file_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            } else {
                $insurance = Insurance::has('file')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }

            if ($insurance) {
                $data = array();
                foreach ($insurance as $insurances) {
                    $button = "";

                    if (!empty($insurances->file_id)) {
                        if (!Auth::user()->getAdmin()) {
                            if (!empty(Auth::user()->company_id)) {
                                if ($insurances->file) {
                                    if ($insurances->file->company_id != Auth::user()->company_id) {
                                        continue;
                                    }
                                }
                            }
                        } else {
                            if (!empty(Session::get('admin_cob'))) {
                                if ($insurances->file) {
                                    if ($insurances->file->company_id != Session::get('admin_cob')) {
                                        continue;
                                    }
                                }
                            }
                        }
                    } else {
                        if (!Auth::user()->getAdmin()) {
                            continue;
                        } else {
                            if (!empty(Session::get('admin_cob'))) {
                                continue;
                            }
                        }
                    }

                    $status = trans('app.forms.pending');
                    if ($insurances->status == 1) {
                        $status = trans('app.forms.resolved');
                    }

                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateInsurance', ['All', Helper::encode($insurances->id)]) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                    $button .= '<button class="btn btn-xs btn-danger" onclick="deleteInsurance(\'' . Helper::encode($insurances->id) . '\')"><i class="fa fa-trash"></i></button>';

                    $data_raw = array(
                        (!empty($insurances->file_id) ? $insurances->file->file_no : '<i>(not set)</i>'),
                        (!empty($insurances->file_id) ? $insurances->file->strata->name : '<i>(not set)</i>'),
                        ($insurances->provider ? $insurances->provider->name : ''),
                        $insurances->remarks,
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
        } else {
            $insurance = Insurance::where('file_id', Helper::decode($id))->where('is_deleted', 0)->orderBy('id', 'desc')->get();

            if ($insurance) {
                $data = array();
                foreach ($insurance as $insurances) {
                    $button = "";

                    $status = trans('app.forms.pending');
                    if ($insurances->status == 1) {
                        $status = trans('app.forms.resolved');
                    }

                    $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AdminController@updateInsurance', [Helper::encode($insurances->file->id), Helper::encode($insurances->id)]) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                    $button .= '<button class="btn btn-xs btn-danger" onclick="deleteInsurance(\'' . Helper::encode($insurances->id) . '\')"><i class="fa fa-trash"></i></button>';

                    $data_raw = array(
                        (!empty($insurances->file_id) ? $insurances->file->file_no : '<i>(not set)</i>'),
                        ($insurances->provider ? $insurances->provider->name : ''),
                        $insurances->remarks,
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
    }

    public function deleteInsurance()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['insurance']['delete'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $id = Helper::decode($data['id']);

            $insurance = Insurance::findOrFail($id);
            if ($insurance) {
                $insurance->is_deleted = 1;
                $deleted = $insurance->save();
                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Insurance (' . $insurance->id . ')' . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($insurance->file->id, "Insurance", $remarks);

                    if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                        /**
                         * Add Notification & send email to COB and JMB
                         */
                        $not_draft_strata = $insurance->file->strata;
                        $notify_data['file_id'] = $insurance->file->id;
                        $notify_data['route'] = route('cob.file.insurance.index', ['id' => 'All']);
                        $notify_data['cob_route'] = route('cob.file.insurance.index', ['id' => 'All']);
                        $notify_data['strata'] = "your";
                        $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $insurance->file->file_no;
                        $notify_data['title'] = "COB File Insurance";
                        $notify_data['module'] = "Insurance";

                        (new NotificationService())->store($notify_data, 'deleted');
                    }

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function addInsurance($id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $insuranceProvider = InsuranceProvider::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();

        if ($id == 'All') {
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
            $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(46));
            if ($disallow) {
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
                'title' => trans('app.menus.agm.add_insurance'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => 'insurance_list',
                'user_permission' => $user_permission,
                'files' => $files,
                'insuranceProvider' => $insuranceProvider,
                'image' => ""
            );

            return View::make('insurance_en.add_insurance', $viewData);
        } else {
            $file = Files::findOrFail(Helper::decode($id));
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                return Redirect::to('updateFile/others/' . $id);
            }
            if ($file) {
                $image = OtherDetails::where('file_id', $file->id)->first();
                $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasUpdate(3));
                if ($disallow) {
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
                    'title' => trans('app.menus.cob.update_cob_file'),
                    'panel_nav_active' => 'cob_panel',
                    'main_nav_active' => 'cob_main',
                    'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
                    'user_permission' => $user_permission,
                    'file' => $file,
                    'insuranceProvider' => $insuranceProvider,
                    'image' => (!empty($image->image_url) ? $image->image_url : '')
                );

                return View::make('page_en.add_insurance', $viewData);
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
    }

    public function submitAddInsurance()
    {
        $data = Input::all();
        if (Request::ajax()) {

            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['insurance']['add'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {
            $insurance = new Insurance();
            $insurance->file_id = Helper::decode($data['file_id']);
            $insurance->insurance_provider_id = $data['insurance_provider'];
            $insurance->public_liability_coverage = $data['public_liability_coverage'];
            $insurance->plc_premium_per_year = $data['plc_premium_per_year'];
            $insurance->plc_validity_from = ($data['plc_validity_from'] ? $data['plc_validity_from'] : null);
            $insurance->plc_validity_to = ($data['plc_validity_to'] ? $data['plc_validity_to'] : null);
            $insurance->fire_insurance_coverage = $data['fire_insurance_coverage'];
            $insurance->fic_premium_per_year = $data['fic_premium_per_year'];
            $insurance->fic_validity_from = ($data['fic_validity_from'] ? $data['fic_validity_from'] : null);
            $insurance->fic_validity_to = ($data['fic_validity_to'] ? $data['fic_validity_to'] : null);
            $insurance->attachment = ($data['attachment'] ? $data['attachment'] : null);
            $insurance->remarks = $data['remarks'];
            $success = $insurance->save();

            if ($success) {
                # Audit Trail
                $remarks = 'Insurance (' . $insurance->id . ')' . $this->module['audit']['text']['data_inserted'];
                $this->addAudit($insurance->file->id, "Insurance", $remarks);

                if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                    /**
                     * Add Notification & send email to COB and JMB
                     */
                    $not_draft_strata = $insurance->file->strata;
                    $notify_data['file_id'] = $insurance->file->id;
                    $notify_data['route'] = route('cob.file.insurance.edit', ['id' => 'All', 'file_id' => Helper::encode($insurance->id)]);
                    $notify_data['cob_route'] = route('cob.file.insurance.edit', ['id' => 'All', 'file_id' => Helper::encode($insurance->id)]);
                    $notify_data['strata'] = "You";
                    $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $insurance->file->file_no;
                    $notify_data['title'] = "COB File Insurance";
                    $notify_data['module'] = "Insurance";

                    (new NotificationService())->store($notify_data);
                }
                return "true";
            } else {
                return "false";
            }
            // } else {
            //     return "false";
            // }
        }
    }

    public function updateInsurance($id, $file_id)
    {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $insuranceProvider = InsuranceProvider::where('is_active', 1)->where('is_deleted', 0)->get();

        if ($id == 'All') {
            $insurance = Insurance::findOrFail(Helper::decode($file_id));
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
            $disallow = Helper::isAllow($insurance->file_id, $insurance->file->company_id, !AccessGroup::hasUpdate(46));
            if ($disallow) {
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
                'title' => trans('app.menus.agm.edit_insurance'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => 'insurance_list',
                'user_permission' => $user_permission,
                'insurance' => $insurance,
                'files' => $files,
                'insuranceProvider' => $insuranceProvider,
                'image' => ""
            );

            return View::make('insurance_en.edit_insurance', $viewData);
        } else {
            $file = Files::findOrFail(Helper::decode($id));
            if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                return Redirect::to('updateFile/others/' . $id);
            }
            if ($file) {
                $insurance = Insurance::where('file_id', $file->id)->first();
                $image = OtherDetails::where('file_id', $file->id)->first();
                $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasUpdate(3));
                if ($disallow) {
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
                    'title' => trans('app.menus.cob.update_cob_file'),
                    'panel_nav_active' => 'cob_panel',
                    'main_nav_active' => 'cob_main',
                    'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
                    'user_permission' => $user_permission,
                    'file' => $file,
                    'insurance' => $insurance,
                    'insuranceProvider' => $insuranceProvider,
                    'image' => (!empty($image->image_url) ? $image->image_url : '')
                );

                return View::make('page_en.edit_insurance', $viewData);
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
    }

    public function submitUpdateInsurance()
    {
        $data = Input::all();
        if (Request::ajax()) {
            ## EAI Call
            // $url = $this->eai_domain . $this->eai_route['file']['cob']['insurance']['update'];
            // $response = json_decode((string) ((new KCurl())->requestPost(null, 
            //                         $url,
            //                         json_encode($data))));
            // if(empty($response->status) == false && $response->status == 200) {

            $id = Helper::decode($data['id']);

            $insurance = Insurance::findOrFail($id);
            if ($insurance) {
                /** Arrange audit fields changes */
                $audit_fields_changed = '';
                $new_line = '';
                $new_line .= $data['insurance_provider'] != $insurance->insurance_provider_id ? "insurance provider, " : "";
                $new_line .= $data['public_liability_coverage'] != $insurance->public_liability_coverage ? "public liability coverage, " : "";
                $new_line .= $data['plc_premium_per_year'] != $insurance->plc_premium_per_year ? "plc premium per year, " : "";
                $new_line .= $data['plc_validity_from'] != $insurance->plc_validity_from ? "plc validity from, " : "";
                $new_line .= $data['plc_validity_to'] != $insurance->plc_validity_to ? "plc validity to, " : "";
                $new_line .= $data['fire_insurance_coverage'] != $insurance->fire_insurance_coverage ? "fire insurance coverage, " : "";
                $new_line .= $data['fic_premium_per_year'] != $insurance->fic_premium_per_year ? "fic premium per year, " : "";
                $new_line .= $data['fic_validity_from'] != $insurance->fic_validity_from ? "fic validity from, " : "";
                $new_line .= $data['fic_validity_to'] != $insurance->fic_validity_to ? "fic validity to, " : "";
                $new_line .= $data['attachment'] != $insurance->attachment ? "attachment, " : "";
                $new_line .= $data['remarks'] != $insurance->remarks ? "remarks, " : "";
                if (!empty($new_line)) {
                    $audit_fields_changed .= "<br/><ul><li> Insurance : (";
                    $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                }
                /** End Arrange audit fields changes */

                $insurance->file_id = Helper::decode($data['file_id']);
                $insurance->insurance_provider_id = $data['insurance_provider'];
                $insurance->public_liability_coverage = $data['public_liability_coverage'];
                $insurance->plc_premium_per_year = $data['plc_premium_per_year'];
                $insurance->plc_validity_from = ($data['plc_validity_from'] ? $data['plc_validity_from'] : null);
                $insurance->plc_validity_to = ($data['plc_validity_to'] ? $data['plc_validity_to'] : null);
                $insurance->fire_insurance_coverage = $data['fire_insurance_coverage'];
                $insurance->fic_premium_per_year = $data['fic_premium_per_year'];
                $insurance->fic_validity_from = ($data['fic_validity_from'] ? $data['fic_validity_from'] : null);
                $insurance->fic_validity_to = ($data['fic_validity_to'] ? $data['fic_validity_to'] : null);
                $insurance->attachment = ($data['attachment'] ? $data['attachment'] : null);
                $insurance->remarks = $data['remarks'];
                $success = $insurance->save();

                if ($success) {
                    # Audit Trail
                    if (!empty($audit_fields_changed)) {
                        $remarks = 'Insurance (' . $insurance->id . ')' . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit($insurance->file->id, "Insurance", $remarks);
                    }

                    if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                        /**
                         * Add Notification & send email to COB and JMB
                         */
                        $not_draft_strata = $insurance->file->strata;
                        $notify_data['file_id'] = $insurance->file->id;
                        $notify_data['route'] = route('cob.file.insurance.edit', ['id' => 'All', 'file_id' => Helper::encode($insurance->id)]);
                        $notify_data['cob_route'] = route('cob.file.insurance.edit', ['id' => 'All', 'file_id' => Helper::encode($insurance->id)]);
                        $notify_data['strata'] = "your";
                        $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $insurance->file->file_no;
                        $notify_data['title'] = "COB File Insurance";
                        $notify_data['module'] = "Insurance";

                        (new NotificationService())->store($notify_data, 'updated');
                    }

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return 'false';
            }
            // } else {
            //     return "false";
            // }
        } else {
            return "false";
        }
    }

    public function deleteInsuranceAttachment()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $id = Helper::decode($data['id']);

            $insurance = Insurance::findOrFail($id);
            if ($insurance) {
                $insurance->attachment = null;
                $deleted = $insurance->save();

                if ($deleted) {
                    # Audit Trail
                    $remarks = 'Insurance Attachment (' . $insurance->id . ')' . $this->module['audit']['text']['data_deleted'];
                    $this->addAudit($insurance->file_id, "COB File", $remarks);

                    return "true";
                } else {
                    return "false";
                }
            } else {
                return "false";
            }
        }
    }

    //finance support
    public function financeSupport($id)
    {
        if ((Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) || Auth::user()->isPreSale()) {
            return Redirect::to('update/monitoring/' . $id);
        }
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $file = Files::findOrFail(Helper::decode($id));
        $image = OtherDetails::where('file_id', $file->id)->first();
        $disallow = Helper::isAllow($file->id, $file->company_id);

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_finance_support',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'file' => $file,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.update_finance_support', $viewData);
    }

    public function getFinanceSupport($id)
    {
        $filelist = FinanceSupport::where('file_id', Helper::decode($id))
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();

        if (count($filelist) > 0) {
            $data = array();
            foreach ($filelist as $filelists) {
                $files = Files::where('id', $filelists->file_id)->first();
                if ($files) {
                    $button = "";
                    $button .= '<button type="button" class="btn btn-xs btn-danger" onclick="deleteFinanceSupport(\'' . Helper::encode($filelists->id) . '\')">' . trans('app.forms.delete') . ' <i class="fa fa-trash"></i></button>&nbsp;';

                    $data_raw = array(
                        ($files->company ? $files->company->short_name : ''),
                        "<a style='text-decoration:underline;' href='" . URL::action('AdminController@updateFinanceSupport', Helper::encode($filelists->id)) . "'>" . (!empty($files) ? $files->file_no : '-') . "</a>",
                        ($files->strata ? $files->strata->strataName() : ''),
                        date('d/m/Y', strtotime($filelists->date)),
                        $filelists->name,
                        number_format($filelists->amount, 2),
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

    public function addFinanceSupport($id)
    {
        $file = Files::findOrFail(Helper::decode($id));
        $image = OtherDetails::where('file_id', $file->id)->first();
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasInsert(39));

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_finance_support',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.add_finance_support', $viewData);
    }

    public function submitAddFinanceSupport()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = Helper::decode($data['file_id']);
            $is_active = $data['is_active'];

            $files = Files::findOrFail($file_id);
            if ($files) {
                $finance = new FinanceSupport();
                $finance->file_id = $files->id;
                $finance->company_id = $files->company_id;
                $finance->date = $data['date'];
                $finance->name = $data['name'];
                $finance->amount = $data['amount'];
                $finance->remark = $data['remark'];
                $finance->is_active = $is_active;
                $success = $finance->save();

                if ($success) {
                    # Audit Trail
                    $files = Files::find($finance->file_id);
                    $remarks = 'COB File (' . $files->file_no . ') has a Finance Support with id : ' . $finance->id .  $this->module['audit']['text']['data_inserted'];
                    $this->addAudit($files->id, "COB Finance Support", $remarks);

                    if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                        /**
                         * Add Notification & send email to COB and JMB
                         */
                        $not_draft_strata = $finance->file->strata;
                        $notify_data['file_id'] = $finance->file->id;
                        $notify_data['route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                        $notify_data['cob_route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                        $notify_data['strata'] = "your";
                        $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance->file->file_no;
                        $notify_data['title'] = "COB File Finance Support";
                        $notify_data['module'] = "Finance Support";

                        (new NotificationService())->store($notify_data);
                    }

                    print "true";
                } else {
                    print "false";
                }
            } else {
                print "false";
            }
        }
    }

    public function updateFinanceSupport($id)
    {
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $item = FinanceSupport::findOrFail(Helper::decode($id));
        $file = Files::find($item->file_id);
        $image = OtherDetails::where('file_id', $file->id)->first();
        $disallow = Helper::isAllow($file->id, $file->company_id, !AccessGroup::hasUpdate(39));

        $viewData = array(
            'title' => trans('app.menus.cob.update_cob_file'),
            'panel_nav_active' => 'cob_finance_support',
            'main_nav_active' => 'cob_main',
            'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
            'user_permission' => $user_permission,
            'files' => $file,
            'item' => $item,
            'image' => (!empty($image->image_url) ? $image->image_url : '')
        );

        return View::make('page_en.edit_finance_support', $viewData);
    }

    public function submitUpdateFinanceSupport()
    {
        $data = Input::all();
        if (Request::ajax()) {
            $file_id = Helper::decode($data['file_id']);
            $id = Helper::decode($data['id']);

            $files = Files::findOrFail($file_id);
            if ($files) {
                $finance = FinanceSupport::findOrFail($id);
                if ($finance) {
                    /** Arrange audit fields changes */
                    $audit_fields_changed = '';
                    $new_line = '';
                    $new_line .= $data['date'] != $finance->date ? "date, " : "";
                    $new_line .= $data['name'] != $finance->name ? "name, " : "";
                    $new_line .= $data['amount'] != $finance->amount ? "amount, " : "";
                    $new_line .= $data['remark'] != $finance->remark ? "remark, " : "";
                    if (!empty($new_line)) {
                        $audit_fields_changed .= "<br/><ul><li> Finance Support : (";
                        $audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
                    }
                    /** End Arrange audit fields changes */

                    $finance->file_id = $files->id;
                    $finance->company_id = $files->company_id;
                    $finance->date = $data['date'];
                    $finance->name = $data['name'];
                    $finance->amount = $data['amount'];
                    $finance->remark = $data['remark'];
                    $finance->is_active = 1;
                    $success = $finance->save();

                    if ($success) {
                        # Audit Trail
                        $files = Files::find($finance->file_id);
                        if (!empty($audit_fields_changed)) {
                            $remarks = 'COB File (' . $files->file_no . ') has a Finance Support with id : ' . $finance->id .  $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                            $this->addAudit($files->id, "COB Finance Support", $remarks);
                        }

                        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
                            /**
                             * Add Notification & send email to COB and JMB
                             */
                            $not_draft_strata = $finance->file->strata;
                            $notify_data['file_id'] = $finance->file->id;
                            $notify_data['route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                            $notify_data['cob_route'] = route('finance_support.edit', ['id' => Helper::encode($finance->id)]);
                            $notify_data['strata'] = "You";
                            $notify_data['strata_name'] = $not_draft_strata->name != "" ? $not_draft_strata->name : $finance->file->file_no;
                            $notify_data['title'] = "COB File Finance Support";
                            $notify_data['module'] = "Finance Support";

                            (new NotificationService())->store($notify_data);
                        }

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

    public function deleteFinanceSupport()
    {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);

            $finance = FinanceSupport::find($id);
            $finance->is_deleted = 1;
            $deleted = $finance->save();
            if ($deleted) {
                # Audit Trail
                $files = Files::find($finance->file_id);
                $remarks = 'COB File (' . $files->file_no . ') has a Finance Support with id : ' . $finance->id .  $this->module['audit']['text']['data_deleted'];
                $this->addAudit($files->id, "COB Finance Support", $remarks);

                print "true";
            } else {
                print "false";
            }
        }
    }

    public function createOrUpdateFileDraft($files)
    {
        if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
            $draft = FileDrafts::firstOrNew(array('file_id' => $files->id));
            $draft->created_by = Auth::user()->id;
            $draft->is_deleted = 0;
            $draft->updated_at = date('Y-m-d H:i:s');
            $draft->save();
        }
    }
}
