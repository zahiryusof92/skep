<?php

class LandTitle extends Eloquent {

    protected $table = 'land_title';

    public static function codeList() {
        $list = [
            "R" => "Kediaman",
            "C" => "Komersial",
            "I" => "Industri"
        ];

        return $list;
    }

    public static function getData() {
        $query = self::where('is_deleted', 0)
                     ->where('is_active', 1);
        $items = $query->selectRaw('description as name, code, is_active, created_at')
                    ->get();
        return $items;
    }

}
