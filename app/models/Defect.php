<?php

class Defect extends Eloquent {

    protected $table = 'defect';

    protected $fillable = [
        'file_id',
        'strata_id',
    ];
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
    
    public function strata() {
        return $this->belongsTo('Strata', 'strata_id');
    }

    public function category() {
        return $this->belongsTo('DefectCategory', 'defect_category_id');
    }

}
