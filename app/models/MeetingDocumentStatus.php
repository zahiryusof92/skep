<?php

class MeetingDocumentStatus extends Eloquent
{

    protected $table = 'meeting_document_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'meeting_document_id',
        'user_id',
        'status',
        'reason',
        'endorsed_by',
        'endorsed_email',
        'is_deleted',
    ];

    public function files()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function meetingDocument()
    {
        return $this->belongsTo('MeetingDocument', 'meeting_document_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}
