<?php

class Document extends Eloquent
{
    protected $table = 'document';

    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

    protected $fillable = [
        'file_id',
        'strata_id',
        'document_type_id',
        'name',
        'remarks',
        'file_url',
        'is_hidden',
        'is_readonly',
        'status',
        'approval_by',
        'approval_date',
        'approval_remark',
        'is_deleted',
    ];

    public function getStatusText()
    {
        $status = "<i>(not set)</i>";

        if ($this->status == self::PENDING) {
            $status = trans('Pending');
        } else if ($this->status == self::APPROVED) {
            $status = trans('Approved');
        } else if ($this->status == self::REJECTED) {
            $status = trans('Rejected');
        }

        return $status;
    }

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function type()
    {
        return $this->belongsTo('Documenttype', 'document_type_id');
    }

    public function approvalBy()
    {
        return $this->belongsTo('User', 'approval_by');
    }
}
