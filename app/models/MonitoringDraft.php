<?php

class MonitoringDraft extends Eloquent {

    protected $table = 'monitoring_draft';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'reference_id',
    ];

}
