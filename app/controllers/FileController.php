<?php

use Helper\KCurl;

class FileController extends BaseController {

    public function uploadFormFile() {
        $file = Input::file('form_file');
        if ($file) {
            $destinationPath = 'uploads/form_files';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $upload = $file->move($destinationPath, $filename);

            if ($upload) {
                return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
            }
        }
    }

    public function uploadDocumentFile() {
        $file = Input::file('document_file');

        $allowedFile = ['pdf'];
        $allowedSize = '10000000';
        
        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['document']['file_upload'];
        $data['document_file'] = curl_file_create($_FILES['document_file']['tmp_name'], $_FILES['document_file']['type'], $_FILES['document_file']['name']);
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         ($data), true)));
                                
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file) {
                if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                    if ($file->getClientSize() <= $allowedSize) {
                        $destinationPath = 'uploads/document_files';
                        $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                        $upload = $file->move($destinationPath, $filename);
            
                        if ($upload) {
                            return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                        }
                    } else {
                        return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['Please upload only PDF file!']]);
                }
            }
        
        // }
    }

    public function uploadInsuranceAttachment()
    {
        $file = Input::file('attachment');

        $allowedFile = ['pdf'];
        $allowedSize = '10000000';

        if ($file) {
            if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                if ($file->getClientSize() <= $allowedSize) {
                    $destinationPath = 'uploads/insurance_attachment';
                    $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $filename);

                    if ($upload) {
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['Please upload only PDF file!']]);
            }
        }
    }
    
    public function uploadDefectAttachment() {
        $file = Input::file('defect_attachment');
        if ($file) {
            $destinationPath = 'uploads/defect_attachment';
            $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
            $upload = $file->move($destinationPath, $filename);

            if ($upload) {
                return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
            }
        }
    }

    public function uploadStrataFile() {
        $file = Input::file('strata_file');

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['strata']['file_upload'];
        $data['strata_file'] = curl_file_create($_FILES['strata_file']['tmp_name'], $_FILES['strata_file']['type'], $_FILES['strata_file']['name']);
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         ($data), true)));
                                
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/strata_files';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);
    
                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadAuditReportFile() {
        $file = (!empty(Input::file('audit_report_file')) ? Input::file('audit_report_file') : Input::file('audit_report_file_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['auditReportFile'];
        if(!empty(Input::file('audit_report_file'))) {
            $data['audit_report_file'] = curl_file_create($_FILES['audit_report_file']['tmp_name'], $_FILES['audit_report_file']['type'], $_FILES['audit_report_file']['name']);
        } else {
            $data['audit_report_file_edit'] = curl_file_create($_FILES['audit_report_file_edit']['tmp_name'], $_FILES['audit_report_file_edit']['type'], $_FILES['audit_report_file_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/audit_report_files';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);
    
                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }

        // } 
    }

    public function uploadLetterIntegrity() {
        $file = (!empty(Input::file('letter_integrity')) ? Input::file('letter_integrity') : Input::file('letter_integrity_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['letterIntegrity'];
        if(!empty(Input::file('letter_integrity'))) {
            $data['letter_integrity'] = curl_file_create($_FILES['letter_integrity']['tmp_name'], $_FILES['letter_integrity']['type'], $_FILES['letter_integrity']['name']);
        } else {
            $data['letter_integrity_edit'] = curl_file_create($_FILES['letter_integrity_edit']['tmp_name'], $_FILES['letter_integrity_edit']['type'], $_FILES['letter_integrity_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/letter_integrity_files';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadLetterBankruptcy() {
        $file = (!empty(Input::file('letter_bankruptcy')) ? Input::file('letter_bankruptcy') : Input::file('letter_bankruptcy_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['letterBankruptcy'];
        if(!empty(Input::file('letter_bankruptcy'))) {
            $data['letter_bankruptcy'] = curl_file_create($_FILES['letter_bankruptcy']['tmp_name'], $_FILES['letter_bankruptcy']['type'], $_FILES['letter_bankruptcy']['name']);
        } else {
            $data['letter_bankruptcy_edit'] = curl_file_create($_FILES['letter_bankruptcy_edit']['tmp_name'], $_FILES['letter_bankruptcy_edit']['type'], $_FILES['letter_bankruptcy_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/letter_bankruptcy_files';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadNoticeAgmEgm() {
        $file = (!empty(Input::file('notice_agm_egm')) ? Input::file('notice_agm_egm') : Input::file('notice_agm_egm_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['noticeAgmEgm'];
        if(!empty(Input::file('notice_agm_egm'))) {
            $data['notice_agm_egm'] = curl_file_create($_FILES['notice_agm_egm']['tmp_name'], $_FILES['notice_agm_egm']['type'], $_FILES['notice_agm_egm']['name']);
        } else {
            $data['notice_agm_egm_edit'] = curl_file_create($_FILES['notice_agm_egm_edit']['tmp_name'], $_FILES['notice_agm_egm_edit']['type'], $_FILES['notice_agm_egm_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/notice_agm_egm';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadMinutesAgmEgm() {
        $file = (!empty(Input::file('minutes_agm_egm')) ? Input::file('minutes_agm_egm') : Input::file('minutes_agm_egm_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['minutesAgmEgm'];
        if(!empty(Input::file('minutes_agm_egm'))) {
            $data['minutes_agm_egm'] = curl_file_create($_FILES['minutes_agm_egm']['tmp_name'], $_FILES['minutes_agm_egm']['type'], $_FILES['minutes_agm_egm']['name']);
        } else {
            $data['minutes_agm_egm_edit'] = curl_file_create($_FILES['minutes_agm_egm_edit']['tmp_name'], $_FILES['minutes_agm_egm_edit']['type'], $_FILES['minutes_agm_egm_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/minutes_agm_egm';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadMinutesAjk() {
        $file = (!empty(Input::file('minutes_ajk')) ? Input::file('minutes_ajk') : Input::file('minutes_ajk_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['minutesAjk'];
        if(!empty(Input::file('minutes_ajk'))) {
            $data['minutes_ajk'] = curl_file_create($_FILES['minutes_ajk']['tmp_name'], $_FILES['minutes_ajk']['type'], $_FILES['minutes_ajk']['name']);
        } else {
            $data['minutes_ajk_edit'] = curl_file_create($_FILES['minutes_ajk_edit']['tmp_name'], $_FILES['minutes_ajk_edit']['type'], $_FILES['minutes_ajk_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/minutes_ajk';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadEligibleVote() {
        $file = (!empty(Input::file('eligible_vote')) ? Input::file('eligible_vote') : Input::file('eligible_vote_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['eligibleVote'];
        if(!empty(Input::file('eligible_vote'))) {
            $data['eligible_vote'] = curl_file_create($_FILES['eligible_vote']['tmp_name'], $_FILES['eligible_vote']['type'], $_FILES['eligible_vote']['name']);
        } else {
            $data['eligible_vote_edit'] = curl_file_create($_FILES['eligible_vote_edit']['tmp_name'], $_FILES['eligible_vote_edit']['type'], $_FILES['eligible_vote_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/eligible_vote';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadAttendMeeting() {
        $file = (!empty(Input::file('attend_meeting')) ? Input::file('attend_meeting') : Input::file('attend_meeting_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['attendMeeting'];
        if(!empty(Input::file('attend_meeting'))) {
            $data['attend_meeting'] = curl_file_create($_FILES['attend_meeting']['tmp_name'], $_FILES['attend_meeting']['type'], $_FILES['attend_meeting']['name']);
        } else {
            $data['attend_meeting_edit'] = curl_file_create($_FILES['attend_meeting_edit']['tmp_name'], $_FILES['attend_meeting_edit']['type'], $_FILES['attend_meeting_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/attend_meeting';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadProksi() {
        $file = (!empty(Input::file('proksi')) ? Input::file('proksi') : Input::file('proksi_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['proksi'];
        if(!empty(Input::file('proksi'))) {
            $data['proksi'] = curl_file_create($_FILES['proksi']['tmp_name'], $_FILES['proksi']['type'], $_FILES['proksi']['name']);
        } else {
            $data['proksi_edit'] = curl_file_create($_FILES['proksi_edit']['tmp_name'], $_FILES['proksi_edit']['type'], $_FILES['proksi_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/proksi';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadAjkInfo() {
        $file = (!empty(Input::file('ajk_info')) ? Input::file('ajk_info') : Input::file('ajk_info_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['ajkInfo'];
        if(!empty(Input::file('ajk_info'))) {
            $data['ajk_info'] = curl_file_create($_FILES['ajk_info']['tmp_name'], $_FILES['ajk_info']['type'], $_FILES['ajk_info']['name']);
        } else {
            $data['ajk_info_edit'] = curl_file_create($_FILES['ajk_info_edit']['tmp_name'], $_FILES['ajk_info_edit']['type'], $_FILES['ajk_info_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/ajk_info';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadIc() {
        $file = (!empty(Input::file('ic')) ? Input::file('ic') : Input::file('ic_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['ic'];
        if(!empty(Input::file('ic'))) {
            $data['ic'] = curl_file_create($_FILES['ic']['tmp_name'], $_FILES['ic']['type'], $_FILES['ic']['name']);
        } else {
            $data['ic_edit'] = curl_file_create($_FILES['ic_edit']['tmp_name'], $_FILES['ic_edit']['type'], $_FILES['ic_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/ic';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadPurchaseAggrement() {
        $file = (!empty(Input::file('purchase_aggrement')) ? Input::file('purchase_aggrement') : Input::file('purchase_aggrement_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['purchaseAggrement'];
        if(!empty(Input::file('purchase_aggrement'))) {
            $data['purchase_aggrement'] = curl_file_create($_FILES['purchase_aggrement']['tmp_name'], $_FILES['purchase_aggrement']['type'], $_FILES['purchase_aggrement']['name']);
        } else {
            $data['purchase_aggrement_edit'] = curl_file_create($_FILES['purchase_aggrement_edit']['tmp_name'], $_FILES['purchase_aggrement_edit']['type'], $_FILES['purchase_aggrement_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/purchase_aggrement';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }
    
    public function uploadStrataTitle() {
        $file = (!empty(Input::file('strata_title')) ? Input::file('strata_title') : Input::file('strata_title_edit'));
        
        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['strataTitle'];
        if(!empty(Input::file('strata_title'))) {
            $data['strata_title'] = curl_file_create($_FILES['strata_title']['tmp_name'], $_FILES['strata_title']['type'], $_FILES['strata_title']['name']);
        } else {
            $data['strata_title_edit'] = curl_file_create($_FILES['strata_title_edit']['tmp_name'], $_FILES['strata_title_edit']['type'], $_FILES['strata_title_edit']['name']);
        }

        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));

        // if(empty($response->status) == false && $response->status == 200) {

            if ($file && !empty($file)) {
                $destinationPath = 'uploads/strata_title';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadMaintenanceStatement() {
        $file = (!empty(Input::file('maintenance_statement')) ? Input::file('maintenance_statement') : Input::file('maintenance_statement_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['maintenanceStatement'];
        if(!empty(Input::file('maintenance_statement'))) {
            $data['maintenance_statement'] = curl_file_create($_FILES['maintenance_statement']['tmp_name'], $_FILES['maintenance_statement']['type'], $_FILES['maintenance_statement']['name']);
        } else {
            $data['maintenance_statement_edit'] = curl_file_create($_FILES['maintenance_statement_edit']['tmp_name'], $_FILES['maintenance_statement_edit']['type'], $_FILES['maintenance_statement_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/maintenance_statement';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadIntegrityPledge() {
        $file = (!empty(Input::file('integrity_pledge')) ? Input::file('integrity_pledge') : Input::file('integrity_pledge_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['integrityPledge'];
        if(!empty(Input::file('integrity_pledge'))) {
            $data['integrity_pledge'] = curl_file_create($_FILES['integrity_pledge']['tmp_name'], $_FILES['integrity_pledge']['type'], $_FILES['integrity_pledge']['name']);
        } else {
            $data['integrity_pledge_edit'] = curl_file_create($_FILES['integrity_pledge_edit']['tmp_name'], $_FILES['integrity_pledge_edit']['type'], $_FILES['integrity_pledge_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/integrity_pledge';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadReportAuditedFinancial() {
        $file = (!empty(Input::file('report_audited_financial')) ? Input::file('report_audited_financial') : Input::file('report_audited_financial_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['reportAuditedFinancial'];
        if(!empty(Input::file('report_audited_financial'))) {
            $data['report_audited_financial'] = curl_file_create($_FILES['report_audited_financial']['tmp_name'], $_FILES['report_audited_financial']['type'], $_FILES['report_audited_financial']['name']);
        } else {
            $data['report_audited_financial_edit'] = curl_file_create($_FILES['report_audited_financial_edit']['tmp_name'], $_FILES['report_audited_financial_edit']['type'], $_FILES['report_audited_financial_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/report_audited_financial';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
    }

    public function uploadHouseRules() {
        $file = (!empty(Input::file('house_rules')) ? Input::file('house_rules') : Input::file('house_rules_edit'));

        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['monitoring']['upload']['houseRules'];
        if(!empty(Input::file('house_rules'))) {
            $data['house_rules'] = curl_file_create($_FILES['house_rules']['tmp_name'], $_FILES['house_rules']['type'], $_FILES['house_rules']['name']);
        } else {
            $data['house_rules_edit'] = curl_file_create($_FILES['house_rules_edit']['tmp_name'], $_FILES['house_rules_edit']['type'], $_FILES['house_rules_edit']['name']);
        }
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         $data, true)));
        
        // if(empty($response->status) == false && $response->status == 200) {
            if ($file && !empty($file)) {
                $destinationPath = 'uploads/house_rules';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        // }
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
                            if (!empty($data[0])) {
                                $file_check = Files::where('file_no', $data[0])->where('id', $files->id)->get();
                                if (count($file_check) > 0) {
                                    array_push($data, "Success");
                                    $csvData[] = $data;
                                } else {
                                    array_push($data, "Error");
                                    $csvData[] = "";
                                }
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

    public function uploadMemoFile() {
        $files = Input::file('document_file');
        ## EAI Call
        // $url = $this->eai_domain . $this->eai_route['file']['cob']['document']['file_upload'];
        // $data['document_file'] = curl_file_create($_FILES['document_file']['tmp_name'], $_FILES['document_file']['type'], $_FILES['document_file']['name']);
        
        // $response = json_decode((string) ((new KCurl())->requestPost(null, 
        //                         $url,
        //                         ($data), true)));
                                
        // if(empty($response->status) == false && $response->status == 200) {
            if ($files) {
                $filename = '';
                $count = 0;

                foreach($files as $file) {
                    $data['file'] = $file;
                    $validation_rules = [
                        'file' => 'image',
                    ];
        
                    $validator = \Validator::make($data, $validation_rules, []);
        
                    if ($validator->fails()) {
                        $errors = $validator->errors();
        
                        return [
                            'success' => false,
                            'errors' => $errors,
                            'message' => 'Validation Error'
                        ];
                    }
                    $destinationPath = 'uploads/memo_files';
                    $file_name = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $file_name);
                    $filename .= $destinationPath . "/" . $file_name;
                    if(!empty($files[$count + 1])) {
                        $filename .= ',';
                    }
                    $count++;
                } 
                // $destinationPath = 'uploads/memo_files';
                // $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                // $upload = $file->move($destinationPath, $filename);
    
                if ($upload) {
                    return Response::json(['success' => true, 'file' => $filename, 'filename' => $filename]);
                }
            } else {
                return Response::json(['success' => false, 'msg' => trans('app.errors.please_upload_valid_file')]);
            }
        
        // }
    }

    public function uploadOcr()
    {
        $files = Input::file();

        $allowedFile = ['txt'];
        $allowedSize = '10000000';

        if (isset($files['notice_agm_egm_ocr'])) {
            $file = $files['notice_agm_egm_ocr'];
            if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                if ($file->getClientSize() <= $allowedSize) {
                    $destinationPath = 'uploads/ocr_files';
                    $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $filename);

                    if ($upload) {
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['Please upload only TXT file!']]);
            }
        } else if (isset($files['minutes_agm_egm_ocr'])) {
            $file = $files['minutes_agm_egm_ocr'];
            if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                if ($file->getClientSize() <= $allowedSize) {
                    $destinationPath = 'uploads/ocr_files';
                    $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $filename);

                    if ($upload) {
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['Please upload only TXT file!']]);
            }
        } else if (isset($files['minutes_ajk_ocr'])) {
            $file = $files['minutes_ajk_ocr'];
            if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                if ($file->getClientSize() <= $allowedSize) {
                    $destinationPath = 'uploads/ocr_files';
                    $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $filename);

                    if ($upload) {
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['Please upload only TXT file!']]);
            }
        } else if (isset($files['ajk_info_ocr'])) {
            $file = $files['ajk_info_ocr'];
            if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                if ($file->getClientSize() <= $allowedSize) {
                    $destinationPath = 'uploads/ocr_files';
                    $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $filename);

                    if ($upload) {
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['Please upload only TXT file!']]);
            }
        } else if (isset($files['report_audited_financial_ocr'])) {
            $file = $files['report_audited_financial_ocr'];
            if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                if ($file->getClientSize() <= $allowedSize) {
                    $destinationPath = 'uploads/ocr_files';
                    $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $filename);

                    if ($upload) {
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['Please upload only TXT file!']]);
            }
        } else if (isset($files['house_rules_ocr'])) {
            $file = $files['house_rules_ocr'];
            if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
                if ($file->getClientSize() <= $allowedSize) {
                    $destinationPath = 'uploads/ocr_files';
                    $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                    $upload = $file->move($destinationPath, $filename);

                    if ($upload) {
                        return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                    }
                } else {
                    return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['Please upload only TXT file!']]);
            }
        }

        return Response::json(['success' => false, 'errors' => ['Please upload file!']]);
    }

    public function uploadEndorsementLetter()
    {
        $file = Input::file('endorsement_letter');

        $allowedFile = ['pdf'];
        $allowedSize = '10000000';

        if (in_array($file->getClientOriginalExtension(), $allowedFile)) {
            if ($file->getClientSize() <= $allowedSize) {
                $destinationPath = 'uploads/endorsement_letter';
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            } else {
                return Response::json(['success' => false, 'errors' => ['File size exceeds the maximum limit!']]);
            }
        } else {
            return Response::json(['success' => false, 'errors' => ['Please upload only PDF file!']]);
        }

        return Response::json(['success' => false, 'errors' => ['Please upload file!']]);
    }

}
