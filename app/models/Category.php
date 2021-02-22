<?php

class Category extends Eloquent {

    protected $table = 'category';

    public static function codeList() {
        $list = [
            "L" => "Low",
            "ML" => "Medium Low",
            "MH" => "Medium High",
            "H" => "High"
        ];

        return $list;
    }

    public function strata() {
        return $this->belongsTo('Strata', 'category');
    }

    public static function getStrata($strata) {
        $condition = function ($query) use ($strata) {
            $query->where('defect_category_id', $strata->id);
            $query->where('is_deleted', 0);
        };

        $total = Files::where('company_id', $cob_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->whereHas('strata', $condition)
                ->count();
    }

    public static function getFiles($cat_id) {
        $stratas = Strata::where('category', $cat_id)->get();

        if ($stratas) {
            $file = '';
            foreach ($stratas as $strata) {
                $condition = function ($query) use ($strata) {
                    $query->where('id', $strata->id);
                };

                if (!Auth::user()->getAdmin()) {
                    if (!empty(Auth::user()->file_id)) {
                        $file[] = Files::where('id', Auth::user()->file_id)
                                ->where('company_id', Auth::user()->company_id)
                                ->where('is_active', 1)
                                ->where('is_deleted', 0)
                                ->whereHas('strata', $condition)
                                ->first();
                    } else {
                        $file[] = Files::where('company_id', Auth::user()->company_id)
                                ->where('is_active', 1)
                                ->where('is_deleted', 0)
                                ->whereHas('strata', $condition)
                                ->first();
                    }
                } else {
                    if (empty(Session::get('admin_cob'))) {
                        $file[] = Files::where('is_active', 1)
                                ->where('is_deleted', 0)
                                ->whereHas('strata', $condition)
                                ->first();
                    } else {
                        $file[] = Files::where('company_id', Session::get('admin_cob'))
                                ->where('is_active', 1)
                                ->where('is_deleted', 0)
                                ->whereHas('strata', $condition)
                                ->first();
                    }
                }
            }
        }

        return $file;
    }

}
