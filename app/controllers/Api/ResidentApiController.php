<?php

namespace Api;

use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Files;
use Strata;
use Defect;
use DefectCategory;
use MeetingDocument;
use AJKDetails;

class ResidentApiController extends BaseController {

    public function agmEgm() {
        $result = array();
        $meetingList = array();

        $strata_name = Request::get('strata_name');
//        $current_page = (Request::has('page')) ? Request::get('page') : 1;
//        $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
//        $from = ($current_page - 1) * $per_page;

        if (!empty($strata_name)) {
            $strata = Strata::where('name', $strata_name)->first();
            if (count($strata) > 0) {
                $file = Files::find($strata->file_id);
                if ($file) {
                    $total_meetings = MeetingDocument::where('file_id', $file->id)
                            ->where('is_deleted', 0)
                            ->count();

                    $meetings = MeetingDocument::where('file_id', $file->id)
                            ->where('is_deleted', 0)
//                            ->skip($from)
//                            ->take($per_page)
                            ->get();

                    if ($meetings) {
                        foreach ($meetings as $meeting) {
                            $meetingList[] = array(
                                'id' => $meeting->id,
                                'agm_date' => ($meeting->agm_date ? $meeting->agm_date : ''),
                                'agm' => ($meeting->agm ? true : false),
                                'agm_file_url' => ($meeting->agm_file_url ? asset($meeting->agm_file_url) : ''),
                                'egm' => ($meeting->egm ? true : false),
                                'egm_file_url' => ($meeting->egm_file_url ? asset($meeting->egm_file_url) : ''),
                                'minit_meeting' => ($meeting->minit_meeting ? true : false),
                                'minutes_meeting_file_url' => ($meeting->minutes_meeting_file_url ? asset($meeting->minutes_meeting_file_url) : ''),
                                'jmc_spa' => ($meeting->jmc_spa ? true : false),
                                'jmc_file_url' => ($meeting->jmc_file_url ? asset($meeting->jmc_file_url) : ''),
                                'identity_card' => ($meeting->identity_card ? true : false),
                                'ic_file_url' => ($meeting->ic_file_url ? asset($meeting->ic_file_url) : ''),
                                'attendance' => ($meeting->attendance ? true : false),
                                'attendance_file_url' => ($meeting->attendance_file_url ? asset($meeting->attendance_file_url) : ''),
                                'financial_report' => ($meeting->financial_report ? true : false),
                                'audited_financial_file_url' => ($meeting->audited_financial_file_url ? asset($meeting->audited_financial_file_url) : ''),
                                'audit_report' => ($meeting->audit_report ? $meeting->audit_report : ''),
                                'audit_report_url' => ($meeting->audit_report_url ? asset($meeting->audit_report_url) : ''),
                                'letter_integrity_url' => ($meeting->letter_integrity_url ? asset($meeting->letter_integrity_url) : ''),
                                'letter_bankruptcy_url' => ($meeting->letter_bankruptcy_url ? asset($meeting->letter_bankruptcy_url) : ''),
                                'notice_agm_egm_url' => ($meeting->notice_agm_egm_url ? asset($meeting->notice_agm_egm_url) : ''),
                                'minutes_agm_egm_url' => ($meeting->minutes_agm_egm_url ? asset($meeting->minutes_agm_egm_url) : ''),
                                'minutes_ajk_url' => ($meeting->minutes_ajk_url ? asset($meeting->minutes_ajk_url) : ''),
                                'eligible_vote_url' => ($meeting->eligible_vote_url ? asset($meeting->eligible_vote_url) : ''),
                                'attend_meeting_url' => ($meeting->attend_meeting_url ? asset($meeting->attend_meeting_url) : ''),
                                'proksi_url' => ($meeting->proksi_url ? asset($meeting->proksi_url) : ''),
                                'ajk_info_url' => ($meeting->ajk_info_url ? asset($meeting->ajk_info_url) : ''),
                                'ic_url' => ($meeting->ic_url ? asset($meeting->ic_url) : ''),
                                'purchase_aggrement_url' => ($meeting->purchase_aggrement_url ? asset($meeting->purchase_aggrement_url) : ''),
                                'strata_title_url' => ($meeting->strata_title_url ? asset($meeting->strata_title_url) : ''),
                                'maintenance_statement_url' => ($meeting->maintenance_statement_url ? asset($meeting->maintenance_statement_url) : ''),
                                'integrity_pledge_url' => ($meeting->maintenance_statement_url ? asset($meeting->maintenance_statement_url) : ''),
                                'sworn_statement_url' => ($meeting->sworn_statement_url ? asset($meeting->sworn_statement_url) : ''),
                                'report_audited_financial_url' => ($meeting->report_audited_financial_url ? asset($meeting->report_audited_financial_url) : ''),
                                'house_rules_url' => ($meeting->house_rules_url ? asset($meeting->house_rules_url) : ''),
                                'audit_start_date' => ($meeting->audit_start_date ? $meeting->audit_start_date : ''),
                                'audit_end_date' => ($meeting->audit_end_date ? $meeting->audit_end_date : ''),
                                'remarks' => ($meeting->remarks ? $meeting->remarks : ''),
                                'created_at' => ($meeting->created_at ? $meeting->created_at->format('Y-m-d H:i:s') : ''),
                                'updated_at' => ($meeting->updated_at ? $meeting->updated_at->format('Y-m-d H:i:s') : '')
                            );
                        }

                        $result[] = array(
                            'total' => $total_meetings,
//                            'per_page' => $per_page,
//                            'page' => ceil($current_page),
//                            'last_page' => ceil($total_meetings / $per_page),
//                            'from' => $from + 1,
//                            'to' => ($current_page * $per_page) < $total_meetings ? ($current_page * $per_page) : $total_meetings,
                            'data' => $meetingList
                        );
                    }

                    $response = array(
                        'error' => false,
                        'message' => 'Success',
                        'result' => $result,
                    );

                    return Response::json($response);
                }
            } else {
                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function designation() {
        $result = array();
        $designationList = array();

        $strata_name = Request::get('strata_name');
//        $current_page = (Request::has('page')) ? Request::get('page') : 1;
//        $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
//        $from = ($current_page - 1) * $per_page;

        if (!empty($strata_name)) {
            $strata = Strata::where('name', $strata_name)->first();
            if (count($strata) > 0) {
                $file = Files::find($strata->file_id);
                if ($file) {
                    $total_designations = AJKDetails::where('file_id', $file->id)
                            ->where('is_deleted', 0)
                            ->count();

                    $designations = AJKDetails::where('file_id', $file->id)
                            ->where('is_deleted', 0)
                            ->get();

                    if ($designations) {
                        foreach ($designations as $designation) {
                            $designationList[] = array(
                                'id' => $designation->id,
                                'designation_id' => $designation->designation,
                                'designation' => ($designation->designation ? $designation->designations->description : ''),
                                'name' => ($designation->name ? $designation->name : ''),
                                'phone_no' => ($designation->phone_no ? $designation->phone_no : ''),
                                'month' => ($designation->month ? $designation->month : ''),
                                'start_year' => ($designation->start_year ? $designation->start_year : ''),
                                'end_year' => ($designation->end_year ? $designation->end_year : ''),
                                'remarks' => ($designation->remarks ? $designation->remarks : ''),
                                'created_at' => ($designation->created_at ? $designation->created_at->format('Y-m-d H:i:s') : ''),
                                'updated_at' => ($designation->updated_at ? $designation->updated_at->format('Y-m-d H:i:s') : '')
                            );
                        }

                        $result[] = array(
                            'total' => $total_designations,
//                            'per_page' => $total_designations,
//                            'page' => ceil($current_page),
//                            'last_page' => ceil($total_designations / $per_page),
//                            'from' => $from + 1,
//                            'to' => ($current_page * $per_page) < $total_designations ? ($current_page * $total_designations) : $total_designations,
                            'data' => $designationList
                        );
                    }

                    $response = array(
                        'error' => false,
                        'message' => 'Success',
                        'result' => $result,
                    );

                    return Response::json($response);
                }
            } else {
                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function complaint() {
        $result = array();
        $defectList = array();

        $strata_name = Request::get('strata_name');
        $current_page = (Request::has('page')) ? Request::get('page') : 1;
        $per_page = (Request::has('per_page')) ? Request::get('per_page') : 10;
        $from = ($current_page - 1) * $per_page;

        if (!empty($strata_name)) {
            $strata = Strata::where('name', $strata_name)->first();
            if (count($strata) > 0) {
                $file = Files::find($strata->file_id);
                if ($file) {
                    $total_defects = Defect::where('file_id', $file->id)
                            ->where('is_deleted', 0)
                            ->count();

                    $defects = Defect::where('file_id', $file->id)
                            ->where('is_deleted', 0)
                            ->get();

                    if ($defects) {
                        foreach ($defects as $defect) {
                            $defectList[] = array(
                                'id' => $defect->id,
                                'defect_category_id' => $defect->defect_category_id,
                                'defect_category' => ($defect->defect_category_id ? $defect->category->name : ''),
                                'name' => $defect->name,
                                'description' => $defect->description,
                                'attachment_url' => (!empty($defect->attachment_url) ? asset($defect->attachment_url) : ''),
                                'status' => ($defect->status ? 'Resolved' : 'Pending'),
                                'reference_key' => $defect->reference_key,
                                'created_at' => ($defect->created_at ? $defect->created_at->format('Y-m-d H:i:s') : ''),
                                'updated_at' => ($defect->updated_at ? $defect->updated_at->format('Y-m-d H:i:s') : '')
                            );
                        }

                        $result[] = array(
                            'total' => $total_defects,
                            'per_page' => $total_defects,
                            'page' => ceil($current_page),
                            'last_page' => ceil($total_defects / $per_page),
                            'from' => $from + 1,
                            'to' => ($current_page * $per_page) < $total_defects ? ($current_page * $total_defects) : $total_defects,
                            'data' => $defectList
                        );
                    }

                    $response = array(
                        'error' => false,
                        'message' => 'Success',
                        'result' => $result,
                    );

                    return Response::json($response);
                }
            } else {
                $response = array(
                    'error' => false,
                    'message' => 'Success',
                    'result' => $result,
                );

                return Response::json($response);
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function complaintCategory() {
        $result = array();

        $categoryList = DefectCategory::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        if (count($categoryList) > 0) {
            foreach ($categoryList as $category) {
                $result[] = array(
                    'id' => $category->id,
                    'name' => $category->name,
                );
            }

            $response = array(
                'error' => false,
                'message' => 'Success',
                'result' => $result,
            );

            return Response::json($response);
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function addComplaint() {
        $result = array();

        $strata_name = Request::get('strata_name');
        $reference_key = Request::get('reference_key');
        $defect_category = Request::get('defect_category');
        $name = Request::get('defect_name');
        $description = Request::get('defect_description');
        $attachment = Request::file('defect_attachment');

        if (!empty($strata_name) && !empty($reference_key)) {
            $strata = Strata::where('name', $strata_name)->first();
            if (count($strata) > 0) {
                $file = Files::find($strata->file_id);
                if ($file) {
                    $attachment_url = '';
                    if ($attachment) {
                        $destinationPath = 'uploads/defect_attachment';
                        $filename = date('YmdHis') . "_" . $attachment->getClientOriginalName();
                        $upload = $attachment->move($destinationPath, $filename);

                        if ($upload) {
                            $attachment_url = $destinationPath . "/" . $filename;
                        }
                    }

                    $defect = new Defect();
                    $defect->file_id = $file->id;
                    $defect->defect_category_id = $defect_category;
                    $defect->name = $name;
                    $defect->description = $description;
                    $defect->attachment_url = $attachment_url;
                    $defect->reference_key = $reference_key;
                    $success = $defect->save();

                    if ($success) {
                        $result = array(
                            'id' => $defect->id,
                            'defect_category_id' => $defect->defect_category_id,
                            'defect_category' => ($defect->defect_category_id ? $defect->category->name : ''),
                            'name' => $defect->name,
                            'description' => $defect->description,
                            'attachment_url' => (!empty($defect->attachment_url) ? asset($defect->attachment_url) : ''),
                            'status' => ($defect->status ? 'Resolved' : 'Pending'),
                            'reference_key' => $defect->reference_key,
                            'created_at' => ($defect->created_at ? $defect->created_at->format('Y-m-d H:i:s') : ''),
                            'updated_at' => ($defect->updated_at ? $defect->updated_at->format('Y-m-d H:i:s') : '')
                        );

                        $response = array(
                            'error' => false,
                            'message' => 'Success',
                            'result' => $result,
                        );

                        return Response::json($response);
                    }
                }
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail',
            'result' => false,
        );

        return Response::json($response);
    }

    public function deleteComplaint() {
        $id = Request::get('id');
        $reference_key = Request::get('reference_key');

        if (!empty($id) && !empty($reference_key)) {
            $defect = Defect::where('id', $id)->where('reference_key', $reference_key)->first();
            if (count($defect) > 0) {
                $defect->is_deleted = 1;
                $success = $defect->save();

                if ($success) {
                    $response = array(
                        'error' => false,
                        'message' => 'Success'
                    );

                    return Response::json($response);
                }
            }
        }

        $response = array(
            'error' => true,
            'message' => 'Fail'
        );

        return Response::json($response);
    }

}
