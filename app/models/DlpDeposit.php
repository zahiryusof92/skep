<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class DlpDeposit extends Eloquent
{
    use SoftDeletingTrait;

    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

    protected $table = 'dlp_deposits';

    protected $fillable = [
        'file_id',
        'amount',
        'maturity_date',
        'attachment',
        'status',
        'approval_by',
        'approval_date',
        'approval_remark',
    ];

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function approver()
    {
        return $this->belongsTo('User', 'approval_by');
    }
}
