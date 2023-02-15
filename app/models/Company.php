<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Company extends Eloquent {

    protected $table = 'company';

    public function scopeself($query) {
        if (!Auth::user()->getAdmin()) {
            $query = $query->where('id', Auth::user()->company_id);
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('id', Session::get('admin_cob'));
            }
        }
        return $query->where('is_active', 1)->where('is_deleted', 0);
    }

    public function checkIfExistEService()
    {
        $module = Config::get('constant.module.eservice');

        if (isset($module['cob'][Str::lower($this->short_name)])) {
            return true;
        }

        return false;
    }

    public function files() {
        return $this->hasMany('Files', 'company_id')->orderBy('files.id');
    }

    public function users() {
        return $this->hasMany('User', 'company_id');
    }

    public function states() {
        return $this->belongsTo('State', 'state');
    }
}
