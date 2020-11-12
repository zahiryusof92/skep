<?php

class HouseScheme extends Eloquent {
    protected $table = 'house_scheme';
    
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