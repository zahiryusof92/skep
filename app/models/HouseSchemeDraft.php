<?php

class HouseSchemeDraft extends Eloquent {

    protected $table = 'house_scheme_draft';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'reference_id',
    ];

    public function files() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function countries() {
        return $this->belongsTo('Country', 'country');
    }

    public function states() {
        return $this->belongsTo('State', 'state');
    }

    public function cities() {
        return $this->belongsTo('City', 'city');
    }

    public function developers() {
        return $this->belongsTo('Developer', 'developer');
    }

}
