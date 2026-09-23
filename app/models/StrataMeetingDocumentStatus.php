<?php

class StrataMeetingDocumentStatus extends Eloquent
{
    protected $table = 'strata_meeting_document_statuses';

    protected $fillable = array(
        'strata_meeting_document_id',
        'user_id',
        'status',
        'reason',
        'endorsed_by',
        'endorsed_email',
        'attachment',
        'is_deleted',
    );

    public function strataMeetingDocument()
    {
        return $this->belongsTo('StrataMeetingDocument', 'strata_meeting_document_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}
