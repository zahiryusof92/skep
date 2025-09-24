<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AGMMinute extends Eloquent {
    protected $table = 'agm_minutes';

    protected $fillable = [
        'file_id',
        'company_id',
        'type',
        'agm_type',
        'is_first',
        'agm_date',
        'description',
        'remarks',
        'is_deleted'
    ];

    public function scopeSelf() {
        $query = self::join('files', 'agm_minutes.file_id', '=', 'files.id')
                        ->select(['agm_minutes.*']);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('agm_minutes.file_id', Auth::user()->file_id)
                                ->where('agm_minutes.company_id', Auth::user()->company_id);
            } else {
                $query = $query->where('agm_minutes.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('agm_minutes.company_id', Session::get('admin_cob'));
            }
        }
        return $query->where('agm_minutes.is_deleted', false)
                     ->where('files.is_deleted', false);
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
}