<?php

class Defect extends Eloquent {

    protected $table = 'defect';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function category() {
        return $this->belongsTo('DefectCategory', 'defect_category_id');
    }

}
