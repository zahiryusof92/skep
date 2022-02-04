<?php

class Company extends Eloquent {

    protected $table = 'company';

    public function files() {
        return $this->hasMany('Files', 'company_id')->orderBy('files.id');
    }

    public function users() {
        return $this->hasMany('User', 'company_id');
    }

}
