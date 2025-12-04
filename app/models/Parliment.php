<?php

class Parliment extends Eloquent {
    protected $table = 'parliment';

    public function scopeSelf()
    {
        return self::where('is_active', true)->where('is_deleted', false);
    }
    
    public static function getOptions() {
        $items = self::orderBy('description', 'asc')->where('is_deleted',0)->get();

        $model = [
            '' => trans('- Select Parliment -')
        ];
        $model += array_pluck($items,'description','description');

        return $model;
    }

    public static function getData($request = []) {
        $query = self::where('is_deleted', 0);
        $items = $query->selectRaw('description as name, code, is_active,
                    created_at')
                    ->get();

        return $items;
    }

    public static function getAnalyticData($request = []) {
        $query = DB::table('dun')
                    ->join('parliment','dun.parliament','=','parliment.id')
                    ->where('parliment.is_deleted', 0)
                    ->where('parliment.is_active',1)
                    ->where('dun.is_deleted',0);
        if(!empty($request['parliment'])) {
            $query = $query->where('parliment.description',$request['parliment']);
        }
        $items = $query->selectRaw('parliment.description as name, count(dun.id) as total')
                    ->groupBy('parliment.description')
                    ->get();

        $names = $data = [];

        foreach($items as $item) {
            array_push($names, [$item->name]);
            array_push($data, [$item->total]);
        }

        $result = [
            'name' => $names,
            'data' => $data
        ];

        return $result;
    }
}