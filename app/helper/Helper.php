<?php

namespace Helper;

use Carbon\Carbon;
use Exception;
use Hashids\Hashids;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class Helper
{
    public static function isAllow($file_id = 0, $company_id = 0, $has_access = 0)
    {
        $disallow = false;
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                if (($company_id > 0 && $company_id != Auth::user()->company_id) || ($file_id > 0 && $file_id != Auth::user()->file_id) || $has_access) {
                    App::abort(404);
                }
            } else {
                if (($company_id > 0 && $company_id != Auth::user()->company_id) || $has_access) {
                    App::abort(404);
                }
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                if (($company_id > 0 && $company_id != Session::get('admin_cob'))) {
                    App::abort(404);
                }
            }
        }

        return $disallow;
    }

    public static function encode(...$args)
    {
        if (count($args) == 1) {
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
        if (empty($id)) {
            App::abort(404);
        }
        return $id[0];
    }

    /**
     * Difference with 2 array and find out the differences
     */
    public static function check_diff_multi($array1, $array2)
    {
        $result = array();
        foreach ($array1 as $key => $val) {
            if (is_array($val) && isset($array2[$key])) {
                $tmp = self::check_diff_multi($val, $array2[$key]);
                if ($tmp) {
                    $result[$key] = $tmp;
                }
            } elseif (!isset($array2[$key])) {
                $result[$key] = null;
            } elseif ($val !== $array2[$key]) {
                if (!in_array($key, ['id', 'created_at', 'updated_at'])) {
                    $result[$key] = $array2[$key];
                }
            }

            if (isset($array2[$key])) {
                unset($array2[$key]);
            }
        }

        $result = array_merge($result, $array2);

        return $result;
    }

    /**
     * Replace the search last character
     */
    public static function str_replace_last($search, $replace, $str)
    {
        if (($pos = strrpos($str, $search)) !== false) {
            $search_length  = strlen($search);
            $str    = substr_replace($str, $replace, $pos, $search_length);
        }
        return $str;
    }

    public static function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }

    public static function localizedDate($dateTime)
    {
        $date = Carbon::createFromTimestamp(strtotime($dateTime))->format('Y-n-d');
        if (!empty($date)) {
            list($year, $month, $date) = explode("-", $date);
            $month = trans("app.months.{$month}");

            return "{$date} {$month} {$year}";
        }

        return '';
    }

    public static function getFormattedDateTime($datetime)
    {
        if ($datetime == '') {
            return null;
        }

        $dt['datetime'] = date('Y-m-d H:i:s', strtotime($datetime));
        $dt['formatted'] = date('d-M-Y, h:i:s A', strtotime($datetime));

        return $dt['formatted'];
    }

    public static function getFormattedDate($date)
    {
        if ($date == '') {
            return null;
        }

        $dt['date'] = date('Y-m-d', strtotime($date));
        $dt['formatted'] = date('d-M-Y', strtotime($date));

        return $dt['formatted'];
    }

    public static function getFormattedTime($time)
    {
        if ($time == '') {
            return null;
        }

        $dt['time'] = date('H:i:s', strtotime($time));
        $dt['formatted'] = date('h:i:s A', strtotime($time));

        return $dt['formatted'];
    }

    public static function getDueDate($date)
    {
        if ($date == '') {
            return null;
        }

        $dt = date('d/m/Y', strtotime($date));

        return $dt;
    }
}
