<?php

class FinanceSyncLog extends Eloquent
{
    protected $table = 'finance_sync_log';

    protected $fillable = [
        'file_id',
        'finance_file_id',
        'data',
        'reference_file_id',
        'reference_finance_file_id', 'status'
    ];

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function finance()
    {
        return $this->belongsTo('Finance', 'finance_file_id');
    }
}
