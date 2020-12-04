<?php

class Vendor extends Eloquent {

    protected $table = 'vendors';
    
    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

}
