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

    public function minutesMeetingOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'minutes_meeting')->latest();
    }

    public function copyOfSpaOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'copy_of_spa')->latest();
    }

    public function attendanceOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'attendance')->latest();
    }

    public function auditedFinancialOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'audited_financial')->latest();
    }

    public function eligibleVoteOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'eligible_vote')->latest();
    }

    public function houseRulesOcr() {
        return $this->hasOne('Ocr', 'meeting_document_id')->where('type', 'house_rules')->latest();
    }
}
