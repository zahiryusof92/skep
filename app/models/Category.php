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

    public function scopeSelf() {
        return self::where('is_active', true)->where('is_deleted', false);
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

    public static function getData() {
        $query = self::where('is_deleted', 0)
                     ->where('is_active', 1);
        $items = $query->selectRaw('description as name, code, is_active, created_at')
                    ->get();
        return $items;
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('files.is_deleted',0);
        };

        $query = DB::table('strata')
                    ->join('files', 'strata.file_id', '=', 'files.id')
                    ->join('company','files.company_id','=','company.id')
                    ->join('category','strata.category', '=', 'category.id')
                    ->where($active);
        $query2 = Company::where('is_deleted', 0)->where('is_active', 1)->where('short_name','!=','LPHS');
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
            $query2 = $query2->where('short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, count(strata.id) as total_id, category.description as category')
                      ->groupBy('category.description', 'company.short_name')
                      ->get();
        $councils = $query2->get();
        $category = self::where('is_deleted', 0)->where('is_active', 1)->get();
        $data = [];
        $names = array_pluck($category,'description');
        foreach($councils as $council) {
            $search_by_council = array_where($items, function($key1, $val1) use($council) {
                return $val1->short_name == $council->short_name;
            });
            $new_data = [
                'name' => $council->short_name,
                'data' => []
            ];
            if(!empty($search_by_council)) {
                foreach($names as $name) {
                    $search_by_category = array_first($search_by_council, function($key, $val) use($name) {
                        return $val->category == $name;
                    });
                    $new_value = 0;
                    if(!empty($search_by_category)) {
                        $new_value = $search_by_category->total_id;
                    }
                    array_push($new_data['data'],[$new_value]);
                }
            } else {
                foreach($names as $name) {
                    $new_value = 0;
                    array_push($new_data['data'],[$new_value]);
                }
            }
            array_push($data, $new_data);

        }
        
        $result = array(
            'name' => $names,
            'data' => $data,
        );
        
        return $result;
    }

}
