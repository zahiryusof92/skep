<?php

class Buyer extends Eloquent {

    protected $table = 'buyer';
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function race() {
        return $this->belongsTo('Race', 'race_id');
    }

}
