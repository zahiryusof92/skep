<?php

class Document extends Eloquent {
    protected $table = 'document';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'strata_id',
    ];
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
    
    public function type() {
        return $this->belongsTo('Documenttype', 'document_type_id');
    }
}