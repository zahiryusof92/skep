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
}
