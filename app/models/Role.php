<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Role extends Eloquent {

    protected $table = 'role';

    const SUPERADMIN = 'SUPERADMIN';
    const ADMINISTRATOR = 'ADMINISTRATOR';
    const COB_MANAGER = 'COB MANAGER';
    const COB = 'COB';
    const JMB = 'JMB';
    const MC = 'MC';
    const LAWYER = 'LAWYER';
    const HR = 'HR';
    const COB_BASIC	 = 'COB BASIC';
    const COB_BASIC_ADMIN = 'COB BASIC ADMIN';
    const COB_PREMIUM = 'COB PREMIUM';
    const COB_PREMIUM_ADMIN = 'COB PREMIUM ADMIN';
    const MPS = 'MPS';
    const PRE_SALE = 'PRE-SALE';
    const DEVELOPER = 'DEVELOPER';
    const LPHS = 'LPHS';

    public function scopeSelf($query) {
        if (!Auth::user()->getAdmin() && !Auth::user()->isCOBManager()) {
            $query = $query->where('id', Auth::user()->role);
        }
        if(!Auth::user()->getAdmin()) {
            $query = $query->whereNotIn('name', [Role::SUPERADMIN, Role::ADMINISTRATOR]);
        }
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}
