<?php

class Strata extends Eloquent {

    protected $table = 'strata';
    
    public function file() {
        return $this->belongsTo('File', 'file_id');
    }

    public function strataName() {
        if ($this->name) {
            return $this->name;
        }

        return "(Not Set)";
    }

}
