<?php

class Vendor extends Eloquent {

    protected $table = 'vendors';

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }
    
    public function review() {
        return $this->hasMany('VendorReview', 'vendor_id')->orderBy('id', 'desc');
    }
    
    public function project() {
        return $this->hasMany('VendorProject', 'vendor_id')->orderBy('id', 'desc');
    }

}
