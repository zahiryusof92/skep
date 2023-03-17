<?php

class Liquidator extends Eloquent {
    protected $table = 'liquidators';

    public function state() {
        return $this->belongsTo('State', 'state');
    }

    public function country() {
        return $this->belongsTo('Country', 'country');
    }

    public static function getData() {
        $query = self::where('is_deleted', 0)
                     ->where('is_active', 1);
        $items = $query->selectRaw('name, is_active, created_at')
                    ->get();
        return $items;
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('files.is_deleted',0);
        };

        $query = DB::table('house_scheme')
                    ->join('files', 'house_scheme.file_id', '=', 'files.id')
                    ->join('company','files.company_id','=','company.id')
                    ->join('liquidator','house_scheme.liquidator', '=', 'liquidator.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, count(house_scheme.id) as total_id')
                      ->groupBy('company.short_name')
                      ->get();
        $data = [];
        foreach($items as $item) {
            $new_data = [
                'name' => $item->short_name,
                'y' => $item->total_id
            ];
            array_push($data, $new_data);

        }
        
        $result = array(
            'data' => $data,
        );
        
        return $result;
    }
}