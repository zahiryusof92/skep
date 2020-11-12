<?php

class ManagementJMB extends Eloquent {

    protected $table = 'management_jmb';

    public function countries() {
        return $this->belongsTo('Country', 'country');
    }

    public function states() {
        return $this->belongsTo('State', 'state');
    }

    public function cities() {
        return $this->belongsTo('City', 'city');
    }

}
