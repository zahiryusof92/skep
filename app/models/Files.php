<?php

class Files extends Eloquent {

    protected $table = 'files';

    public function owner() {
        return $this->hasMany('Buyer', 'file_id');
    }

    public function tenant() {
        return $this->hasMany('Tenant', 'file_id');
    }

    public function strata() {
        return $this->hasOne('Strata', 'file_id');
    }

    public function finance() {
        return $this->hasMany('Finance', 'file_id');
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function resident() {
        return $this->hasOne('Residential', 'file_id');
    }

    public function commercial() {
        return $this->hasOne('Commercial', 'file_id');
    }

    public function facility() {
        return $this->hasOne('Facility', 'file_id');
    }

    public function other() {
        return $this->hasOne('OtherDetails', 'file_id');
    }

    public function financeSupport() {
        return $this->hasMany('FinanceSupport', 'file_id');
    }
}
