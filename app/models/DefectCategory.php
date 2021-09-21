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

    public static function getData() {
        $query = self::where('is_deleted', 0)
                    ->where('is_active', 1);
        $items = $query->selectRaw('name, is_active, created_at')
                    ->get();
        return $items;
    }

    public static function getAnalyticData() {
        $active = function ($query) {
            $query->where('defect_category.is_deleted', 0)
                ->where('defect_category.is_active', 1)
                ->where('defect.is_deleted', 0)
                ->where('files.is_deleted', 0);
        };

        $items = DB::table('defect')
                    ->join('files', 'defect.file_id', '=', 'files.id')
                    ->join('defect_category', 'defect.defect_category_id','=','defect_category.id')
                    ->where($active)
                    ->selectRaw('defect_category.name, count(defect.id) as total_item')
                    ->groupBy('defect_category.name')
                    ->get();

        $data = [];
        foreach($items as $item) {
            /** Data */
            $new_data = [
                'name' => $item->name,  
                'y' => intval($item->total_item)
            ];
            array_push($data, $new_data);
        }
        
        $result = array(
            'data' => $data
        );
        
        return $result;
    }

}
