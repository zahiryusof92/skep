<?php

class AdminForm extends Eloquent {

    protected $table = 'form';

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

}
