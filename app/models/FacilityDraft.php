<?php

class FacilityDraft extends Eloquent {

    protected $table = 'facility_draft';

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
