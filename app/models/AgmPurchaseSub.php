<?php

class AgmPurchaseSub extends Eloquent {
    protected $table = 'agm_purchase_sub';

    public function file(){
        return $this->belongsTo('File', 'file_id');
    }
}

