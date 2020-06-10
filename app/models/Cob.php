<?php

class Cob extends Eloquent {
    protected $table = 'cob';

    public function document(){
        return $this->belongsTo('Documenttype', 'document_id');
    }
}