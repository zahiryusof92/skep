<?php

class Document extends Eloquent {
    protected $table = 'document';
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
    
    public function type() {
        return $this->belongsTo('Documenttype', 'document_type_id');
    }
}