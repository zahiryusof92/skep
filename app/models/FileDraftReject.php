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

    public function scopeSelf($query) {
        $query = $query->join('files', 'file_draft_rejects.file_id', '=', 'files.id');
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('files.id', Auth::user()->file_id);
            } else {
                $query = $query->where('files.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('files.company_id', Session::get('admin_cob'));
            }
        }
        return $query->where('files.is_deleted', 0);
    }
}