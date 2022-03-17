<?php

namespace Helper;

use Exception;
use Hashids\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Helper
{
    public static function isAllow($file_id = 0, $company_id = 0, $has_access = 0) {
        $disallow = false;
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                if(($company_id > 0 && $company_id != Auth::user()->company_id) || ($file_id > 0 && $file_id != Auth::user()->file_id) || $has_access) {
                    $disallow = true;
                }
            } else {
                if(($company_id > 0 && $company_id != Auth::user()->company_id) || $has_access) {
                    $disallow = true;
                }
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                if(($company_id > 0 && $company_id != Session::get('admin_cob'))) {
                    $disallow = true;
                }
            }
        }

        return $disallow;
    }

    public static function encode(...$args)
    {
        return (new Hashids('', 16))->encode(...$args);
    }

    public static function decode($enc)
    {
        if (is_int($enc)) {
            return $enc;
        }
        
        return (new Hashids('', 16))->decode($enc)[0];
    }
}