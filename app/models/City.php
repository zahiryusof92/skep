<?php

class City extends Eloquent {
    protected $table = 'city';

    public static function getData() {
        $query = self::where('is_deleted', 0)
                    ->where('is_active', 1);
                    
        $items = $query->selectRaw('description as name, is_active,
                    created_at')
                    ->get();
        return $items;
    }
}