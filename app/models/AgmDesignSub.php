<?php

class AgmDesignSub extends Eloquent {
    protected $table = 'agm_design_sub';

    public function design(){
        return $this->belongsTo('Designation', 'design_id');
    }

    public function file(){
        return $this->belongsTo('File', 'file_id');
    }
}

