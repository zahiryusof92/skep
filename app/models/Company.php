<?php

class Company extends Eloquent {

    protected $table = 'company';

    public function files() {
        return $this->hasMany('Files', 'company_id');
    }

}
