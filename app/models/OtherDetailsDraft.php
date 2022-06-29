<?php

class OtherDetailsDraft extends Eloquent {

    protected $table = 'others_details_draft';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'reference_id',
        'original_price',
    ];

}
