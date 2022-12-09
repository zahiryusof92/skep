<?php

class Memo extends Eloquent {

    protected $table = 'memo';

    public function type() {
        return $this->belongsTo('MemoType', 'memo_type_id');
    }
    
    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

}
