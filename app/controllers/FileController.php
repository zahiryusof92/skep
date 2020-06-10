<?php

class FileController extends BaseController {

    public function uploadFormFile() {
        $file = Input::file('form_file');
        if ($file) {
            $destinationPath = 'uploads/form_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            Input::file('form_file')->move($destinationPath, $filename);
            return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
        }
    }

    public function uploadDocumentFile() {
        $file = Input::file('document_file');
        if ($file) {
            $destinationPath = 'uploads/document_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            Input::file('document_file')->move($destinationPath, $filename);
            return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
        }
    }

    public function uploadStrataFile() {
        $file = Input::file('strata_file');
        $destinationPath = 'uploads/strata_files';
        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
        Input::file('strata_file')->move($destinationPath, $filename);
        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
    }

    public function uploadAuditReportFile() {
        $file = Input::file('audit_report_file');
        $destinationPath = 'uploads/audit_report_files';
        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
        Input::file('audit_report_file')->move($destinationPath, $filename);
        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
    }

    public function uploadLetterIntegrity() {
        $file = Input::file('letter_integrity');
        $destinationPath = 'uploads/letter_integrity_files';
        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
        Input::file('letter_integrity')->move($destinationPath, $filename);
        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
    }

    public function uploadLetterBankruptcy() {
        $file = Input::file('letter_bankruptcy');
        $destinationPath = 'uploads/letter_bankruptcy_files';
        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
        Input::file('letter_bankruptcy')->move($destinationPath, $filename);
        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
    }

    public function uploadAuditReportFileEdit() {
        $file = Input::file('audit_report_file_edit');
        $destinationPath = 'uploads/audit_report_files';
        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
        Input::file('audit_report_file_edit')->move($destinationPath, $filename);
        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
    }

    public function uploadLetterIntegrityEdit() {
        $file = Input::file('letter_integrity_edit');
        $destinationPath = 'uploads/letter_integrity_files';
        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
        Input::file('letter_integrity_edit')->move($destinationPath, $filename);
        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
    }

    public function uploadLetterBankruptcyEdit() {
        $file = Input::file('letter_bankruptcy_edit');
        $destinationPath = 'uploads/letter_bankruptcy_files';
        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
        Input::file('letter_bankruptcy_edit')->move($destinationPath, $filename);
        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
    }

    public function uploadBuyerCSVAction($id) {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
        $files = Files::find($id);
        $image = OtherDetails::where('file_id', $files->id)->first();

        $file = Input::file('uploadedCSV');

        if ($file) {
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();

            $file_ext = explode(".", $filename);
            $ext = end($file_ext);

            if (strtolower($ext) == "csv") {
                if ($file->move('files', $filename)) {
                    if (($handle = fopen(url('/') . '/files/' . $filename, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 200000, ",")) !== FALSE) {
                            $file_check = Files::where('file_no', $data[0])->where('id', $files->id)->get();
                            if (count($file_check) > 0) {
                                array_push($data, "Success");
                                $csvData[] = $data;
                            } else {
                                array_push($data, "Error");
                                $csvData[] = "";
                            }
                        }
                        fclose($handle);
                    }

                    if (!empty($csvData)) {
                        $viewData = array(
                            'title' => trans('app.menus.cob.update_cob_file'),
                            'panel_nav_active' => 'cob_panel',
                            'main_nav_active' => 'cob_main',
                            'sub_nav_active' => 'cob_list',
                            'user_permission' => $user_permission,
                            'files' => $files,
                            'Uploadmessage' => 'success',
                            'csvData' => $csvData,
                            'upload' => "true",
                            'image' => (!empty($image->image_url) ? $image->image_url : '')
                        );
                        return View::make('page_en.import_buyer', $viewData);
                    } else {
                        $viewData = array(
                            'title' => trans('app.menus.cob.update_cob_file'),
                            'panel_nav_active' => 'cob_panel',
                            'main_nav_active' => 'cob_main',
                            'sub_nav_active' => 'cob_list',
                            'user_permission' => $user_permission,
                            'files' => $files,
                            'Uploadmessage' => 'success',
                            'csvData' => "No Data",
                            'upload' => "true",
                            'image' => (!empty($image->image_url) ? $image->image_url : '')
                        );
                        return View::make('page_en.import_buyer', $viewData);
                    }
                } else {
                    $viewData = array(
                        'title' => trans('app.menus.cob.update_cob_file'),
                        'panel_nav_active' => 'cob_panel',
                        'main_nav_active' => 'cob_main',
                        'sub_nav_active' => 'cob_list',
                        'user_permission' => $user_permission,
                        'files' => $files,
                        'Uploadmessage' => 'error',
                        'upload' => "true",
                        'image' => (!empty($image->image_url) ? $image->image_url : '')
                    );

                    return View::make('page_en.import_buyer', $viewData);
                }
            } else {
                $viewData = array(
                    'title' => trans('app.menus.cob.update_cob_file'),
                    'panel_nav_active' => 'cob_panel',
                    'main_nav_active' => 'cob_main',
                    'sub_nav_active' => 'cob_list',
                    'user_permission' => $user_permission,
                    'files' => $files,
                    'Uploadmessage' => trans('app.errors.please_upload_csv_file'),
                    'upload' => "true",
                    'image' => (!empty($image->image_url) ? $image->image_url : '')
                );

                return View::make('page_en.import_buyer', $viewData);
            }
        } else {
            $viewData = array(
                'title' => trans('app.menus.cob.update_cob_file'),
                'panel_nav_active' => 'cob_panel',
                'main_nav_active' => 'cob_main',
                'sub_nav_active' => 'cob_list',
                'user_permission' => $user_permission,
                'files' => $files,
                'Uploadmessage' => trans('app.errors.please_upload_csv_file'),
                'upload' => "true",
                'image' => (!empty($image->image_url) ? $image->image_url : '')
            );

            return View::make('page_en.import_buyer', $viewData);
        }
    }

    public function uploadPurchaserCSVAction() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $file = Input::file('uploadedCSV');

        if ($file) {
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();

            $file_ext = explode(".", $filename);
            $ext = end($file_ext);

            if (strtolower($ext) == "csv") {
                if ($file->move('files', $filename)) {
                    if (($handle = fopen(url('/') . '/files/' . $filename, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 200000, ",")) !== FALSE) {
                            $file_check = Files::where('file_no', $data[0])->get();
                            if (count($file_check) > 0) {
                                array_push($data, "Success");
                                $csvData[] = $data;
                            } else {
                                array_push($data, "Error");
                                $csvData[] = "";
                            }
                        }
                        fclose($handle);
                    }

                    if (!empty($csvData)) {
                        $viewData = array(
                            'title' => trans('app.menus.agm.import_purchaser'),
                            'panel_nav_active' => 'agm_panel',
                            'main_nav_active' => 'agm_main',
                            'sub_nav_active' => 'agmpurchasesub_list',
                            'user_permission' => $user_permission,
                            'Uploadmessage' => 'success',
                            'csvData' => $csvData,
                            'upload' => "true",
                            'image' => ""
                        );
                        return View::make('agm_en.import_purchaser', $viewData);
                    } else {
                        $viewData = array(
                            'title' => trans('app.menus.agm.import_purchaser'),
                            'panel_nav_active' => 'agm_panel',
                            'main_nav_active' => 'agm_main',
                            'sub_nav_active' => 'agmpurchasesub_list',
                            'user_permission' => $user_permission,
                            'Uploadmessage' => 'success',
                            'csvData' => "No Data",
                            'upload' => "true",
                            'image' => ""
                        );
                        return View::make('agm_en.import_purchaser', $viewData);
                    }
                } else {
                    $viewData = array(
                        'title' => trans('app.menus.agm.import_purchaser'),
                        'panel_nav_active' => 'agm_panel',
                        'main_nav_active' => 'agm_main',
                        'sub_nav_active' => 'agmpurchasesub_list',
                        'user_permission' => $user_permission,
                        'Uploadmessage' => 'error',
                        'upload' => "true",
                        'image' => ""
                    );

                    return View::make('agm_en.import_purchaser', $viewData);
                }
            } else {
                $viewData = array(
                    'title' => trans('app.menus.agm.import_purchaser'),
                    'panel_nav_active' => 'agm_panel',
                    'main_nav_active' => 'agm_main',
                    'sub_nav_active' => 'agmpurchasesub_list',
                    'user_permission' => $user_permission,
                    'Uploadmessage' => trans('app.errors.please_upload_csv_file'),
                    'upload' => "true",
                    'image' => ""
                );

                return View::make('agm_en.import_purchaser', $viewData);
            }
        } else {
            $viewData = array(
                'title' => trans('app.menus.agm.import_purchaser'),
                'panel_nav_active' => 'agm_panel',
                'main_nav_active' => 'agm_main',
                'sub_nav_active' => 'agmpurchasesub_list',
                'user_permission' => $user_permission,
                'Uploadmessage' => trans('app.errors.please_upload_csv_file'),
                'upload' => "true",
                'image' => ""
            );

            return View::make('agm_en.import_purchaser', $viewData);
        }
    }

    public function uploadTenantCSVAction() {
        //get user permission
        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        $file = Input::file('uploadedCSV');

        if ($file) {
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();

            $file_ext = explode(".", $filename);
            $ext = end($file_ext);

            if (strtolower($ext) == "csv") {
                if ($file->move('files', $filename)) {
                    if (($handle = fopen(url('/') . '/files/' . $filename, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 200000, ",")) !== FALSE) {
                            $file_check = Files::where('file_no', $data[0])->get();
                            if (count($file_check) > 0) {
                                array_push($data, "Success");
                                $csvData[] = $data;
                            } else {
                                array_push($data, "Error");
                                $csvData[] = "";
                            }
                        }
                        fclose($handle);
                    }

                    if (!empty($csvData)) {
                        $viewData = array(
                            'title' => trans('app.menus.agm.import_tenant'),
                            'panel_nav_active' => 'agm_panel',
                            'main_nav_active' => 'agm_main',
                            'sub_nav_active' => 'agmtenantsub_list',
                            'user_permission' => $user_permission,
                            'Uploadmessage' => 'success',
                            'csvData' => $csvData,
                            'upload' => "true",
                            'image' => ""
                        );
                        return View::make('agm_en.import_tenant', $viewData);
                    } else {
                        $viewData = array(
                            'title' => trans('app.menus.agm.import_tenant'),
                            'panel_nav_active' => 'agm_panel',
                            'main_nav_active' => 'agm_main',
                            'sub_nav_active' => 'agmtenantsub_list',
                            'user_permission' => $user_permission,
                            'Uploadmessage' => 'success',
                            'csvData' => "No Data",
                            'upload' => "true",
                            'image' => ""
                        );
                        return View::make('agm_en.import_tenant', $viewData);
                    }
                } else {
                    $viewData = array(
                        'title' => trans('app.menus.agm.import_tenant'),
                        'panel_nav_active' => 'agm_panel',
                        'main_nav_active' => 'agm_main',
                        'sub_nav_active' => 'agmtenantsub_list',
                        'user_permission' => $user_permission,
                        'Uploadmessage' => 'error',
                        'upload' => "true",
                        'image' => ""
                    );

                    return View::make('agm_en.import_tenant', $viewData);
                }
            } else {
                $viewData = array(
                    'title' => trans('app.menus.agm.import_tenant'),
                    'panel_nav_active' => 'agm_panel',
                    'main_nav_active' => 'agm_main',
                    'sub_nav_active' => 'agmtenantsub_list',
                    'user_permission' => $user_permission,
                    'Uploadmessage' => trans('app.errors.please_upload_csv_file'),
                    'upload' => "true",
                    'image' => ""
                );

                return View::make('agm_en.import_tenant', $viewData);
            }
        } else {
            $viewData = array(
                'title' => trans('app.menus.agm.import_tenant'),
                'panel_nav_active' => 'agm_panel',
                'main_nav_active' => 'agm_main',
                'sub_nav_active' => 'agmtenantsub_list',
                'user_permission' => $user_permission,
                'Uploadmessage' => trans('app.errors.please_upload_csv_file'),
                'upload' => "true",
                'image' => ""
            );

            return View::make('agm_en.import_tenant', $viewData);
        }
    }

    public function uploadAGMFile() {
        $file = Input::file('agm_file');
        if ($file) {
            $destinationPath = 'uploads/agm_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $output = $destinationPath . "/" . $filename;

            return Response::json(['success' => true, 'file' => $output, 'filename' => $filename]);
        } else {
            return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
        }
    }

    public function uploadEGMFile() {
        $file = Input::file('egm_file');
        if ($file) {
            $destinationPath = 'uploads/egm_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $output = $destinationPath . "/" . $filename;

            return Response::json(['success' => true, 'file' => $output, 'filename' => $filename]);
        } else {
            return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
        }
    }

    public function uploadMinutesMeetingFile() {
        $file = Input::file('minutes_meeting_file');
        if ($file) {
            $destinationPath = 'uploads/minutes_meeting_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $output = $destinationPath . "/" . $filename;

            return Response::json(['success' => true, 'file' => $output, 'filename' => $filename]);
        } else {
            return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
        }
    }

    public function uploadJMCFile() {
        $file = Input::file('jmc_file');
        if ($file) {
            $destinationPath = 'uploads/jmc_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $output = $destinationPath . "/" . $filename;

            return Response::json(['success' => true, 'file' => $output, 'filename' => $filename]);
        } else {
            return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
        }
    }

    public function uploadICFile() {
        $file = Input::file('ic_file');
        if ($file) {
            $destinationPath = 'uploads/ic_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $output = $destinationPath . "/" . $filename;

            return Response::json(['success' => true, 'file' => $output, 'filename' => $filename]);
        } else {
            return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
        }
    }

    public function uploadAttendanceFile() {
        $file = Input::file('attendance_file');
        if ($file) {
            $destinationPath = 'uploads/attendance_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $output = $destinationPath . "/" . $filename;

            return Response::json(['success' => true, 'file' => $output, 'filename' => $filename]);
        } else {
            return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
        }
    }

    public function uploadAuditedFinancialFile() {
        $file = Input::file('audited_financial_file');
        if ($file) {
            $destinationPath = 'uploads/audited_financial_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $output = $destinationPath . "/" . $filename;

            return Response::json(['success' => true, 'file' => $output, 'filename' => $filename]);
        } else {
            return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
        }
    }
}
