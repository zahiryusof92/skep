<?php

class Insurance extends Eloquent {

    protected $table = 'insurance';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function provider() {
        return $this->belongsTo('InsuranceProvider', 'insurance_provider_id');
    }

}
