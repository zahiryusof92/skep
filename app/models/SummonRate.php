<?php

class SummonRate extends Eloquent {

    protected $table = 'summon_rate';

    public function category() {
        return $this->belongsTo('Category', 'category_id');
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

}
