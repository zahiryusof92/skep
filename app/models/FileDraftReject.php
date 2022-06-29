<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FileDraftReject extends Eloquent {

    protected $table = 'file_draft_rejects';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'type',
        'remarks',
    ];

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
}