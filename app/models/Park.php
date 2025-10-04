<?php

class Park extends Eloquent {
    protected $table = 'park';

    public static function getData($request = []) {
        $query = self::join('dun', 'park.dun','=','dun.id')
                    ->where('park.is_deleted', 0)
                    ->where('park.is_active', 1)
                    ->where('dun.is_deleted', 0)
                    ->where('dun.is_active', 1);
        if(!empty($request['dun'])) {
            $query = $query->where('dun.description',$request['dun']);
        }
        $items = $query->selectRaw('dun.description as dun, park.description as name, park.is_active,
                    park.created_at')
                    ->get();
        return $items;
    }

    public function scopeSelf()
    {
        return self::where('is_active', true)->where('is_deleted', false);
    }
}