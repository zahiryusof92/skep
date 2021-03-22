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

    public function summonRate() {
        return $this->hasMany('SummonRate', 'category_id');
    }

    public function getSummonAmount($company_id) {
        $total = 0;

        if ($company_id) {
            $category = SummonRate::where('category_id', $this->id)->where('company_id', $company_id)->first();
            if ($category) {
                $total_myr = $category->amount;
                if ($total_myr > 0) {
                    $conversion = Conversion::first();
                    if ($conversion && $conversion->rate > 0) {
                        /*
                         * MYR to COIN
                         */
                        $total = $total_myr * $conversion->rate;

                        return round($total);
                    }
                }
            }
        }

        return $total;
    }

    public function getSummonCash($company_id) {
        $total = 0;

        if ($company_id) {
            $category = SummonRate::where('category_id', $this->id)->where('company_id', $company_id)->first();
            if ($category) {
                $total = $category->amount;
                if ($total > 0) {
                    return $total;
                }
            }
        }

        return $total;
    }

    public static function findWhere($array, $matching) {
        foreach ($array as $item) {
            $is_match = true;
            foreach ($matching as $key => $value) {

                if (is_object($item)) {
                    if (!isset($item->$key)) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if (!isset($item[$key])) {
                        $is_match = false;
                        break;
                    }
                }

                if (is_object($item)) {
                    if ($item->$key != $value) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if ($item[$key] != $value) {
                        $is_match = false;
                        break;
                    }
                }
            }

            if ($is_match) {
                return $item;
            }
        }

        return false;
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
