<?php

class VendorReview extends Eloquent {

    protected $table = 'vendor_reviews';

    public function vendor() {
        return $this->belongsTo('Vendors', 'vendor_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
