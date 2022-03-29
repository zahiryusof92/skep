<?php

namespace Helper;

use Exception;
use Hashids\Hashids;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class Helper
{
    public static function isAllow($file_id = 0, $company_id = 0, $has_access = 0) {
        $disallow = false;
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                if(($company_id > 0 && $company_id != Auth::user()->company_id) || ($file_id > 0 && $file_id != Auth::user()->file_id) || $has_access) {
                    App::abort(404);
                }
            } else {
                if(($company_id > 0 && $company_id != Auth::user()->company_id) || $has_access) {
                    App::abort(404);
                }
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                if(($company_id > 0 && $company_id != Session::get('admin_cob'))) {
                    App::abort(404);
                }
            }
        }

        return $disallow;
    }

    public static function encode(...$args)
    {
        if(count($args) == 1) {
            return (new Hashids('', 16))->encode(...$args);
        }
        return (new Hashids($args[0], 16))->encode($args[1]);
    }

    public static function decode($enc, $module = '')
    {
        if (is_int($enc)) {
            return $enc;
        }
        $id = (new Hashids($module, 16))->decode($enc);
        if(empty($id)) {
            App::abort(404);
        }
        return $id[0];
    }
}