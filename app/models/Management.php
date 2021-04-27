<?php

class Management extends Eloquent {

    protected $table = 'management';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
    ];
    
    public function draft() {
        return $this->hasOne('ManagementDraft', 'reference_id');
    }

}
