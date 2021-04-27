<?php

class ManagementDeveloper extends Eloquent {

    protected $table = 'management_developer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id'
    ];

    public function countries() {
        return $this->belongsTo('Country', 'country');
    }

    public function states() {
        return $this->belongsTo('State', 'state');
    }

    public function cities() {
        return $this->belongsTo('City', 'city');
    }

}
