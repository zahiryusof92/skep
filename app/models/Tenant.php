<?php

class Tenant extends Eloquent {

    protected $table = 'tenant';
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function race() {
        return $this->belongsTo('Race', 'race_id');
    }

}
