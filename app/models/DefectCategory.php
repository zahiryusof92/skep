<?php

class DefectCategory extends Eloquent {

    protected $table = 'defect_category';

    public static function getTotalDefect($category, $cob_id = NULL) {
        $condition = function ($query) use ($category) {
            $query->where('defect_category_id', $category->id);
            $query->where('is_deleted', 0);
        };

        if ($cob_id) {
            $total = Files::where('company_id', $cob_id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->whereHas('defect', $condition)
                    ->count();
        } else {
            $total = Files::where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->whereHas('defect', $condition)
                    ->count();
        }

        return $total;
    }

}
