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

    /**
     * Sanitize filename by removing/replacing special characters
     * @param string $originalName
     * @return string
     */
    public static function sanitizeFilename($originalName) {
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $sanitizedFilename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        return $sanitizedFilename . '.' . $extension;
    }

    public static function getChangedFields($input, $model = null)
    {
        $new_line = '';

        if (!$model) {
            return '<br/><ul><li>Fields: (all fields created)</li></ul>';
        }

        $new_line .= (isset($input['file_id']) && $input['file_id'] != $model->file_id) ? "file id, " : "";
        $new_line .= (isset($input['complaint_category_id']) && $input['complaint_category_id'] != $model->complaint_category_id) ? "complaint category id, " : "";
        $new_line .= (isset($input['complaint_type_id']) && $input['complaint_type_id'] != $model->complaint_type_id) ? "complaint type id, " : "";
        $new_line .= (isset($input['name']) && $input['name'] != $model->name) ? "name, " : "";
        $new_line .= (isset($input['description']) && $input['description'] != $model->description) ? "description, " : "";
        $new_line .= (isset($input['attachment_url']) && $input['attachment_url'] != $model->attachment_url) ? "attachment, " : "";
        $new_line .= (isset($input['letter_ref_no']) && $input['letter_ref_no'] != $model->letter_ref_no) ? "letter ref no, " : "";
        $new_line .= (isset($input['date_received']) && $input['date_received'] != $model->date_received) ? "date received, " : "";
        $new_line .= (isset($input['scheme_address']) && $input['scheme_address'] != $model->scheme_address) ? "scheme address, " : "";
        $new_line .= (isset($input['scheme_zone']) && $input['scheme_zone'] != $model->scheme_zone) ? "scheme zone, " : "";

        $receiver_now = !empty($input['scheme_receiver']) ? (is_array($input['scheme_receiver']) ? implode(',', $input['scheme_receiver']) : $input['scheme_receiver']) : null;
        $new_line .= $receiver_now != $model->scheme_receiver ? "scheme receiver, " : "";

        $new_line .= (isset($input['complainant_phone_no']) && $input['complainant_phone_no'] != $model->complainant_phone_no) ? "complainant phone no, " : "";
        $new_line .= (isset($input['complainant_email']) && $input['complainant_email'] != $model->complainant_email) ? "complainant email, " : "";
        $new_line .= (isset($input['complainant_ic_no']) && $input['complainant_ic_no'] != $model->complainant_ic_no) ? "complainant IC no, " : "";
        $new_line .= (isset($input['complainant_type']) && $input['complainant_type'] != $model->complainant_type) ? "complainant type, " : "";
        $new_line .= (isset($input['complainant_type_others']) && $input['complainant_type_others'] != $model->complainant_type_others) ? "complainant type others, " : "";
        $new_line .= (isset($input['complaint_category']) && $input['complaint_category'] != $model->complaint_category) ? "complaint category, " : "";
        $new_line .= (isset($input['complaint_complication']) && $input['complaint_complication'] != $model->complaint_complication) ? "complaint complication, " : "";
        $new_line .= (isset($input['action_duration']) && $input['action_duration'] != $model->action_duration) ? "action duration, " : "";
        $new_line .= (isset($input['status']) && $input['status'] != $model->status) ? "status, " : "";
        $new_line .= (isset($input['officer_review']) && $input['officer_review'] != $model->officer_review) ? "officer review, " : "";

        if (!empty($new_line)) {
            return "<br/><ul><li>Fields: (" . self::str_replace_last(', ', '', $new_line) . ")</li></ul>";
        }

        return '';
    }

    /**
     * Active COB for feature gating: Change COB (admin_cob) wins over user's company.
     * Cached per-request to avoid repeated Company queries from navigation.
     *
     * @return \Company|null
     */
    public static function activeCompany()
    {
        static $resolved = false;
        static $company = null;

        if ($resolved) {
            return $company;
        }

        $resolved = true;

        if (Auth::check() && Auth::user()->getAdmin() && !empty(Session::get('admin_cob'))) {
            $company = \Company::where('id', Session::get('admin_cob'))
                ->where('is_active', 1)
                ->where('is_hidden', false)
                ->where('is_deleted', 0)
                ->first();

            return $company;
        }

        if (Auth::check() && Auth::user()->getCOB) {
            $company = Auth::user()->getCOB;

            return $company;
        }

        return null;
    }

    /**
     * Superadmin / admin with no specific COB selected (All COB view).
     *
     * @return bool
     */
    public static function isAllCobContext()
    {
        return Auth::check()
            && Auth::user()->getAdmin()
            && empty(Session::get('admin_cob'));
    }

    /**
     * True when the active COB context is MPKL.
     *
     * @return bool
     */
    public static function isMPKLContext()
    {
        $company = self::activeCompany();

        return $company && strtoupper($company->short_name) === 'MPKL';
    }

    /**
     * True when company (id, short_name, or Company) is MPKL.
     *
     * @param mixed $company Company model, id, or short_name
     * @return bool
     */
    public static function isMPKL($company = null)
    {
        if ($company === null) {
            return self::isMPKLContext();
        }

        if ($company instanceof \Company) {
            return strtoupper((string) $company->short_name) === 'MPKL';
        }

        if (is_numeric($company)) {
            $model = \Company::find($company);
            return $model && strtoupper((string) $model->short_name) === 'MPKL';
        }

        return strtoupper((string) $company) === 'MPKL';
    }

    /**
     * Show MPKL-only menus (Complaint module, eStrata minutes): MPKL context or All COB.
     *
     * @return bool
     */
    public static function showMPKLModules()
    {
        return self::isMPKLContext() || self::isAllCobContext();
    }

    /**
     * Show legacy Defect menu: All COB, or specific non-MPKL COB (not when MPKL-only context).
     *
     * @return bool
     */
    public static function showLegacyDefectMenu()
    {
        if (self::isMPKLContext()) {
            return false;
        }

        return true;
    }
}
