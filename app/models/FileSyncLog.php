<?php

class FileSyncLog extends Eloquent
{
    protected $table = 'file_sync_log';

    protected $fillable = [
        'file_id',
        'data',
        'reference_file_id',
        'status'
    ];

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }
}
