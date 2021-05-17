<?php

class SummonConfirmed extends Eloquent {

    protected $table = 'summon_confirmed';
    

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }
}