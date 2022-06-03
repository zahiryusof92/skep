<?php

namespace Job;

use AuditTrail;
use Exception;
use Files;
use MeetingDocument;
use User;

class AgmMinuteSync
{
    public function fire($job, $data)
    {
        $file_id = $data['file_id'];
        $item = $data['item'];

        if (!empty($file_id) && !empty($item)) {
            $agm_detail = MeetingDocument::where('file_id', $file_id)
                ->where('agm_date', $item['agm_date'])
                ->first();
            if (!$agm_detail) {
                $agm_detail = new MeetingDocument();
                $agm_detail->file_id = $file_id;
            }
            $agm_detail->agm_date = $item['agm_date'];
            $agm_detail->agm = (!empty($item['is_agm']) ? true : false);
            if (!empty($item['is_agm'])) {
                $folder = 'agm_files';
                $path = $this->uploadFile($item['is_agm'], $folder);
                if ($path) {
                    $agm_detail->agm_file_url = $path;
                }
            }
            $agm_detail->egm = (!empty($item['is_egm']) ? true : false);
            if (!empty($item['is_egm'])) {
                $folder = 'egm_files';
                $path = $this->uploadFile($item['is_egm'], $folder);
                if ($path) {
                    $agm_detail->egm_file_url = $path;
                }
            }
            $agm_detail->minit_meeting = (!empty($item['meeting_minutes']) ? true : false);
            if (!empty($item['meeting_minutes'])) {
                $folder = 'minutes_meeting_files';
                $path = $this->uploadFile($item['meeting_minutes'], $folder);
                if ($path) {
                    $agm_detail->minutes_meeting_file_url = $path;
                }
            }
            $agm_detail->jmc_spa = (!empty($item['jmc_spa']) ? true : false);
            if (!empty($item['jmc_spa'])) {
                $folder = 'jmc_files';
                $path = $this->uploadFile($item['jmc_spa'], $folder);
                if ($path) {
                    $agm_detail->jmc_file_url = $path;
                }
            }
            $agm_detail->identity_card = (!empty($item['identity_card_list']) ? true : false);
            if (!empty($item['identity_card_list'])) {
                $folder = 'ic_files';
                $path = $this->uploadFile($item['identity_card_list'], $folder);
                if ($path) {
                    $agm_detail->ic_file_url = $path;
                }
            }
            $agm_detail->attendance = (!empty($item['attendance_list']) ? true : false);
            if (!empty($item['attendance_list'])) {
                $folder = 'attendance_files';
                $path = $this->uploadFile($item['attendance_list'], $folder);
                if ($path) {
                    $agm_detail->attendance_file_url = $path;
                }
            }
            $agm_detail->financial_report = (!empty($item['audited_financial_report']) ? true : false);
            if (!empty($item['audited_financial_report'])) {
                $folder = 'audited_financial_files';
                $path = $this->uploadFile($item['audited_financial_report'], $folder);
                if ($path) {
                    $agm_detail->audited_financial_file_url = $path;
                }
            }
            $agm_detail->audit_start_date = $item['financial_audit_start'];
            $agm_detail->audit_end_date = $item['financial_audit_end'];
            $agm_detail->audit_report = '';
            if (!empty($item['financial_audit_report'])) {
                $folder = 'audit_report_files';
                $path = $this->uploadFile($item['financial_audit_report'], $folder);
                if ($path) {
                    $agm_detail->audit_report_url = $path;
                }
            }
            if (!empty($item['jmc_pledge_letter'])) {
                $folder = 'letter_integrity_files';
                $path = $this->uploadFile($item['jmc_pledge_letter'], $folder);
                if ($path) {
                    $agm_detail->letter_integrity_url = $path;
                }
            }
            if (!empty($item['declaration_letter'])) {
                $folder = 'letter_bankruptcy_files';
                $path = $this->uploadFile($item['declaration_letter'], $folder);
                if ($path) {
                    $agm_detail->letter_bankruptcy_url = $path;
                }
            }
            if (!empty($item['agm_notice'])) {
                $folder = 'notice_agm_egm';
                $path = $this->uploadFile($item['agm_notice'], $folder);
                if ($path) {
                    $agm_detail->notice_agm_egm_url = $path;
                }
            }
            if (!empty($item['agm_minute'])) {
                $folder = 'minutes_agm_egm';
                $path = $this->uploadFile($item['agm_minute'], $folder);
                if ($path) {
                    $agm_detail->minutes_agm_egm_url = $path;
                }
            }
            if (!empty($item['eligible_list'])) {
                $folder = 'eligible_vote';
                $path = $this->uploadFile($item['eligible_list'], $folder);
                if ($path) {
                    $agm_detail->eligible_vote_url = $path;
                }
            }
            if (!empty($item['attendance_list'])) {
                $folder = 'attend_meeting';
                $path = $this->uploadFile($item['attendance_list'], $folder);
                if ($path) {
                    $agm_detail->attend_meeting_url = $path;
                }
            }
            if (!empty($item['proxy_list'])) {
                $folder = 'proksi';
                $path = $this->uploadFile($item['proxy_list'], $folder);
                if ($path) {
                    $agm_detail->proksi_url = $path;
                }
            }
            if (!empty($item['ajk_information'])) {
                $folder = 'ajk_info';
                $path = $this->uploadFile($item['ajk_information'], $folder);
                if ($path) {
                    $agm_detail->ajk_info_url = $path;
                }
            }
            if (!empty($item['ajk_ic'])) {
                $folder = 'ic';
                $path = $this->uploadFile($item['ajk_ic'], $folder);
                if ($path) {
                    $agm_detail->ic_url = $path;
                }
            }
            if (!empty($item['ajk_snp'])) {
                $folder = 'purchase_aggrement';
                $path = $this->uploadFile($item['ajk_snp'], $folder);
                if ($path) {
                    $agm_detail->purchase_aggrement_url = $path;
                }
            }
            if (!empty($item['ajk_strata'])) {
                $folder = 'strata_title';
                $path = $this->uploadFile($item['ajk_strata'], $folder);
                if ($path) {
                    $agm_detail->strata_title_url = $path;
                }
            }
            if (!empty($item['ajk_mf'])) {
                $folder = 'maintenance_statement';
                $path = $this->uploadFile($item['ajk_mf'], $folder);
                if ($path) {
                    $agm_detail->maintenance_statement_url = $path;
                }
            }
            if (!empty($item['ajk_pledge'])) {
                $folder = 'integrity_pledge';
                $path = $this->uploadFile($item['ajk_pledge'], $folder);
                if ($path) {
                    $agm_detail->integrity_pledge_url = $path;
                }
            }
            if (!empty($item['audited_financial_report'])) {
                $folder = 'report_audited_financial';
                $path = $this->uploadFile($item['audited_financial_report'], $folder);
                if ($path) {
                    $agm_detail->report_audited_financial_url = $path;
                }
            }
            if (!empty($item['house_rules'])) {
                $folder = 'house_rules';
                $path = $this->uploadFile($item['house_rules'], $folder);
                if ($path) {
                    $agm_detail->house_rules_url = $path;
                }
            }
            $agm_detail->type = 'JMB';
            $agm_detail->remarks = $item['remarks'];
            $success = $agm_detail->save();

            if ($success) {
                # Audit Trail
                $files = Files::find($agm_detail->file_id);
                $remarks = 'AGM Details (' . $files->file_no . ')' . ' dated ' . date('d/m/Y', strtotime($agm_detail->agm_date)) . 'has been inserted.';

                $auditTrail = new AuditTrail();
                $auditTrail->module = "COB File";
                $auditTrail->remarks = $remarks;
                $auditTrail->audit_by = User::where('email', 'admin@admin.com')->first()->id;
                $auditTrail->save();
            }
        }

        $job->delete();
    }

    public function uploadFile($file, $folder)
    {
        $output = '';

        if (!empty($file)) {
            try {
                $contents = file_get_contents($file);
                $filename = date('YmdHis') . '_' . basename($file);
                $destinationPath = 'uploads/' . $folder;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $output = $destinationPath . '/' . $filename;
                file_put_contents($output, $contents);
            } catch (Exception $e) {
                throw ($e);
            }
        }

        return $output;
    }
}
