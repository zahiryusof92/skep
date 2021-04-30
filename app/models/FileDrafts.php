<?php

class FileDrafts extends Eloquent {

    protected $table = 'file_drafts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
    ];
}
