<?php

class StrataDraft extends Eloquent {

    protected $table = 'strata_draft';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id'
    ];

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function parliment() {
        return $this->belongsTo('Parliment', 'parliament');
    }

    public function duns() {
        return $this->belongsTo('Dun', 'dun');
    }

    public function parks() {
        return $this->belongsTo('Park', 'park');
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

    public function towns() {
        return $this->belongsTo('City', 'town');
    }

    public function areas() {
        return $this->belongsTo('Area', 'area');
    }

    public function areaUnit() {
        return $this->belongsTo('UnitMeasure', 'land_area_unit');
    }

    public function landTitle() {
        return $this->belongsTo('LandTitle', 'land_title');
    }

    public function categories() {
        return $this->belongsTo('Category', 'category');
    }

    public function perimeters() {
        return $this->belongsTo('Perimeter', 'perimeter');
    }

    public function strataName() {
        if ($this->name) {
            return $this->name;
        }

        return "(Not Set)";
    }
    
    public function residential() {
        return $this->hasOne('ResidentialDraft', 'strata_id')->latest();
    }
    
    public function commercial() {
        return $this->hasOne('CommercialDraft', 'strata_id')->latest();
    }
    
    public function residentialExtra() {
        return $this->hasMany('ResidentialExtraDraft', 'strata_id');
    }
    
    public function commercialExtra() {
        return $this->hasMany('CommercialExtraDraft', 'strata_id');
    }
    
    public function facility() {
        return $this->hasOne('FacilityDraft', 'strata_id');
    }

}
