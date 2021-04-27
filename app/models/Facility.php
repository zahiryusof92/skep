<?php

class Facility extends Eloquent {

    protected $table = 'facility';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
    ];
    
    public function draft() {
        return $this->hasOne('FacilityDraft', 'reference_id');
    }

}
