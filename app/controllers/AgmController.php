<?php

use Helper\Helper;
use Helper\KCurl;
use Illuminate\Support\Facades\View;

class AgmController extends BaseController {

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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(30));
        
        $viewData = array(
            'title' => trans('app.menus.agm.designation'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'cob' => $cob,
            'month' => AJKDetails::monthList(),
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

                if (!empty($ajk_details->file_id)) {
                    if (!Auth::user()->getAdmin()) {
                        if (!empty(Auth::user()->company_id)) {
                            if ($ajk_details->file_id && $ajk_details->file->company_id != Auth::user()->company_id) {
                                continue;
                            }
                        }
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            if ($ajk_details->file_id && $ajk_details->file->company_id != Session::get('admin_cob')) {
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

                $designation = Designation::find($ajk_details->designation);

                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success edit_ajk" title="Edit"  onclick="window.location=\'' . URL::action('AgmController@editAJK', Helper::encode($ajk_details->id)) . '\'">
                                <i class="fa fa-pencil"></i>
                            </button>&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAJKDetails(\'' . Helper::encode($ajk_details->id) . '\')">
                                <i class="fa fa-trash"></i>
                            </button>&nbsp';

                $data_raw = array(
                    $ajk_details->file->company->short_name,
                    $ajk_details->file->file_no,
                    $designation->description,
                    $ajk_details->name,
                    $ajk_details->phone_no,
                    $ajk_details->monthName(),
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(30));

        $viewData = array(
            'title' => trans('app.menus.agm.add_designation'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'designation' => $designation,
            'month' => AJKDetails::monthList(),
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
            $start_year = $data['start_year'];
            $end_year = $data['end_year'];
            $remarks = $data['remarks'];

            $ajk_detail = new AJKDetails();
            $ajk_detail->file_id = $file_id;
            $ajk_detail->designation = $designation;
            $ajk_detail->name = $name;
            $ajk_detail->phone_no = $phone_no;
            $ajk_detail->month = $month;
            $ajk_detail->start_year = $start_year;
            $ajk_detail->end_year = $end_year;
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

        $ajk_details = AJKDetails::findOrFail(Helper::decode($id));
        $disallow = Helper::isAllow($ajk_details->file_id, $ajk_details->file->company_id, !AccessGroup::hasUpdate(30));

        $viewData = array(
            'title' => trans('app.menus.agm.edit_designation'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdesignsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
            'designation' => $designation,
            'month' => AJKDetails::monthList(),
            'ajk_details' => $ajk_details,
            'image' => ''
        );

        return View::make('agm_en.edit_ajk', $viewData);
    }

    public function submitEditAJK() {
        $data = Input::all();
        if (Request::ajax()) {

            $id = Helper::decode($data['id']);
            $file_id = $data['file_id'];
            $designation = $data['designation'];
            $name = $data['name'];
            $phone_no = $data['phone_no'];
            $month = $data['month'];
            $start_year = $data['start_year'];
            $end_year = $data['end_year'];
            $remarks = $data['remarks'];

            $ajk_detail = AJKDetails::findOrFail($id);
            if ($ajk_detail) {
                $ajk_detail->file_id = $file_id;
                $ajk_detail->designation = $designation;
                $ajk_detail->name = $name;
                $ajk_detail->phone_no = $phone_no;
                $ajk_detail->month = $month;
                $ajk_detail->start_year = $start_year;
                $ajk_detail->end_year = $end_year;
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

            $id = Helper::decode($data['id']);

            $ajk_details = AJKDetails::findOrFail($id);
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(31));

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
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $posts = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['buyer.*'])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('buyer.is_deleted', 0);
            } else {
                $posts = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['buyer.*'])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('buyer.is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $posts = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['buyer.*'])
                        ->where('buyer.is_deleted', 0);
            } else {
                $posts = Buyer::join('files', 'buyer.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['buyer.*'])
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('buyer.is_deleted', 0);
            }
        }

        if ($posts) {
            return Datatables::of($posts)
                            ->addColumn('cob', function ($model) {
                                $cob = '';
                                if ($model->file_id) {
                                    $cob = $model->file->company->short_name;
                                }
                                return $cob;
                            })
                            ->addColumn('files', function ($model) {
                                $files = '';
                                if ($model->file_id) {
                                    $files = $model->file->file_no;
                                }
                                return $files;
                            })
                            ->addColumn('strata', function ($model) {
                                $race = '';
                                if ($model->file_id) {
                                    $race = $model->file->strata->name;
                                }
                                return $race;
                            })
                            ->addColumn('race', function ($model) {
                                $race = '';
                                if ($model->race_id) {
                                    $race = $model->race->name_en;
                                }
                                return $race;
                            })
                            ->addColumn('action', function ($model) {
                                $button = "";
                                if (AccessGroup::hasUpdate(31)) {
                                    $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AgmController@editPurchaser', Helper::encode($model->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deletePurchaser(\'' . Helper::encode($model->id) . '\')"><i class="fa fa-trash"></i></button>&nbsp';
                                }

                                return $button;
                            })
                            ->make(true);
        }
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(31));

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
        $buyer = Buyer::findOrFail(Helper::decode($id));

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
        $disallow = Helper::isAllow($buyer->file_id, $buyer->file->company_id, !AccessGroup::hasUpdate(31));

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
            $id = Helper::decode($data['id']);

            $checkFile = Files::find($file_id);

            if (count($checkFile) > 0) {
                $buyer = Buyer::findOrFail($id);
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

            $id = Helper::decode($data['id']);

            $buyer = Buyer::findOrFail($id);
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(43));

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
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $posts = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['tenant.*'])
                        ->where('files.id', Auth::user()->file_id)
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('tenant.is_deleted', 0);
            } else {
                $posts = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['tenant.*'])
                        ->where('files.company_id', Auth::user()->company_id)
                        ->where('tenant.is_deleted', 0);
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $posts = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['tenant.*'])
                        ->where('tenant.is_deleted', 0);
            } else {
                $posts = Tenant::join('files', 'tenant.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->join('strata', 'files.id', '=', 'strata.file_id')
                        ->select(['tenant.*'])
                        ->where('files.company_id', Session::get('admin_cob'))
                        ->where('tenant.is_deleted', 0);
            }
        }

        if ($posts) {
            return Datatables::of($posts)
                            ->addColumn('cob', function ($model) {
                                $cob = '';
                                if ($model->file_id) {
                                    $cob = $model->file->company->short_name;
                                }
                                return $cob;
                            })
                            ->addColumn('files', function ($model) {
                                $files = '';
                                if ($model->file_id) {
                                    $files = $model->file->file_no;
                                }
                                return $files;
                            })
                            ->addColumn('strata', function ($model) {
                                $race = '';
                                if ($model->file_id) {
                                    $race = $model->file->strata->name;
                                }
                                return $race;
                            })
                            ->addColumn('race', function ($model) {
                                $race = '';
                                if ($model->race_id) {
                                    $race = $model->race->name_en;
                                }
                                return $race;
                            })
                            ->addColumn('action', function ($model) {
                                $button = "";
                                if (AccessGroup::hasUpdate(43)) {
                                    $button .= '<button type="button" class="btn btn-xs btn-success" title="Edit" onclick="window.location=\'' . URL::action('AgmController@editTenant', Helper::encode($model->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                                    $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteTenant(\'' . Helper::encode($model->id) . '\')"><i class="fa fa-trash"></i></button>&nbsp';
                                }

                                return $button;
                            })
                            ->make(true);
        }
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(43));

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
        $tenant = Tenant::findOrFail(Helper::decode($id));

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
        $disallow = Helper::isAllow($tenant->file_id, $tenant->file->company_id, !AccessGroup::hasUpdate(43));

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
            $id = Helper::decode($data['id']);

            $checkFile = Files::find($file_id);

            if (count($checkFile) > 0) {
                $tenant = Tenant::findOrFail($id);
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

            $id = Helper::decode($data['id']);

            $tenant = Tenant::findOrFail($id);
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(32));

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
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $agm_detail = MeetingDocument::where('file_id', Auth::user()->file_id)->where('type', '!=', '')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            } else {
                if (strtoupper(Auth::user()->getRole->name) == 'JMB') {
                    $agm_detail = MeetingDocument::where('type', 'jmb')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
                } else if (strtoupper(Auth::user()->getRole->name) == 'MC') {
                    $agm_detail = MeetingDocument::where('type', 'mc')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
                } else {
                    $agm_detail = MeetingDocument::where('type', '!=', '')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
                }
            }
        } else {
            if (empty(Session::get('admin_cob'))) {
                $agm_detail = MeetingDocument::where('type', '!=', '')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            } else {
                $agm_detail = MeetingDocument::where('type', '!=', '')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }
        }

        if (count($agm_detail) > 0) {
            $data = Array();
            foreach ($agm_detail as $agm_details) {
                $files = Files::find($agm_details->file_id);
                if ($files) {
                    if (!Auth::user()->getAdmin()) {
                        if ($files->company_id != Auth::user()->company_id) {
                            continue;
                        }
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            if ($files->company_id != Session::get('admin_cob')) {
                                continue;
                            }
                        }
                    }
                } else {
                    if (Auth::user()->getAdmin()) {
                        if (!empty(Session::get('admin_cob'))) {
                            continue;
                        }
                    }
                }

                $button = "";
                $button .= '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit" onclick="window.location=\'' . URL::action('AgmController@editMinutes', Helper::encode($agm_details->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;&nbsp;';
                $button .= '<button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="deleteAGMDetails(\'' . Helper::encode($agm_details->id) . '\')"><i class="fa fa-trash""></i></button>';

                if ($agm_details->file_id) {
                    if ($files) {
                        $file_no = $files->file_no;
                    } else {
                        $file_no = '<i>(not available)</i>';
                    }
                } else {
                    $file_no = '<i>(not set)</i>';
                }
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
                    $file_no,
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(32));

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
            $report_audited_financial_url = $data['report_audited_financial_url'];
            $house_rules_url = $data['house_rules_url'];
            $type = $data['type'];
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
            $agm_detail->report_audited_financial_url = $report_audited_financial_url;
            $agm_detail->house_rules_url = $house_rules_url;
            $agm_detail->type = $type;
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
        $meeting_doc = MeetingDocument::findOrFail(Helper::decode($id));
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
            $disallow = Helper::isAllow($meeting_doc->file_id, $meeting_doc->files->company_id, !AccessGroup::hasUpdate(32));
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

            $id = Helper::decode($data['id']);
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
            $report_audited_financial_url = $data['report_audited_financial_url'];
            $house_rules_url = $data['house_rules_url'];
            $remarks = $data['remarks'];

            $agm_detail = MeetingDocument::findOrFail($id);
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
                $agm_detail->report_audited_financial_url = $report_audited_financial_url;
                $agm_detail->house_rules_url = $house_rules_url;
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

            $id = Helper::decode($data['id']);

            $agm_details = MeetingDocument::findOrFail($id);
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
        $documentType = Documenttype::where('is_active', 1)->where('is_deleted', 0)->orderby('sort_no', 'asc')->get();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(33));

        $viewData = array(
            'title' => trans('app.menus.agm.upload_document'),
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmdocumentsub_list',
            'user_permission' => $user_permission,
            'files' => $files,
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

                if (!empty($documents->file_id)) {
                    if (!Auth::user()->getAdmin()) {
                        if (!empty(Auth::user()->company_id)) {
                            if ($documents->file->company_id != Auth::user()->company_id) {
                                continue;
                            }
                        }
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            if ($documents->file->company_id != Session::get('admin_cob')) {
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

                $button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('AgmController@updateDocument', Helper::encode($documents->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
                $button .= '<button class="btn btn-xs btn-danger" onclick="deleteDocument(\'' . Helper::encode($documents->id) . '\')"><i class="fa fa-trash"></i></button>';

                $data_raw = array(
                    (!empty($documents->file_id) ? $documents->file->file_no : '<i>(not set)</i>'),
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
            // } else {
            //     print "false";
            // }
        }
    }

    public function deleteDocumentFile() {
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
            // } else {
            //     print "false";
            // }
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(33));

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
            // } else {
            //     print "false";
            // }
            
        }
    }

    public function updateDocument($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $document = Document::findOrFail(Helper::decode($id));
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
        $disallow = Helper::isAllow($document->file_id, $document->file->company_id, !AccessGroup::hasUpdate(33));

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
            $id = Helper::decode($data['id']);

            $document = Document::findOrFail($id);
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
            $agmDesignSub->ajk_start_year = $data['ajk_start_year'];
            $agmDesignSub->ajk_end_year = $data['ajk_end_year'];
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
                    $x->ajk_start_year,
                    $x->ajk_end_year,
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
            $agmDesignSub->ajk_start_year = $data['ajk_start_year'];
            $agmDesignSub->ajk_end_year = $data['ajk_end_year'];
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
            $company_name = $data['company'];

            if ($company_name) {
                $cob = Company::where('short_name', $company_name)->first();
                if ($cob) {
                    $files = Files::where('company_id', $cob->id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
                } else {
                    $files = array();
                }
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
                    $result .= "<option value='" . $file->file_no . "'>" . $file->file_no . "</option>";
                }
            }
        }

        return $result;
    }

}
