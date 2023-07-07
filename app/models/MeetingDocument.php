<?php

class MeetingDocument extends Eloquent
{

    protected $table = 'meeting_document';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id'
    ];

    public function files()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function draft()
    {
        return $this->hasOne('MeetingDocumentDraft', 'reference_id');
    }

    public function meetingDocumentStatus()
    {
        return $this->hasOne('MeetingDocumentStatus', 'meeting_document_id')->latest();
    }

    public function noticeAgmEgmOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'notice_agm_egm')->latest();
    }

    public function minutesAgmEgmOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'minutes_agm_egm')->latest();
    }

    public function minutesAjkOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'minutes_ajk')->latest();
    }

    public function ajkInfoOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'ajk_info')->latest();
    }

    public function reportAuditedFinancialOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'report_audited_financial')->latest();
    }

    public function houseRulesOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'house_rules')->latest();
    }
}
