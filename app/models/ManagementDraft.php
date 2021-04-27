<?php

class ManagementDraft extends Eloquent {

    protected $table = 'management_draft';

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
