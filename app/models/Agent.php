<?php

class Agent extends Eloquent {
    protected $table = 'agent';

    public static function getData() {
        $query = self::where('is_deleted', 0)
                     ->where('is_active', 1);
        $items = $query->selectRaw('name, phone_no, is_active, created_at')
                    ->get();
        return $items;
    }
}