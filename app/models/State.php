<?php

class State extends Eloquent {

    protected $table = 'state';

    public static function codeList() {
        $list = [
            "J" => "Johor",
            "K" => "Kedah",
            "D" => "Kelantan",
            "M" => "Melaka",
            "N" => "Negeri Sembilan",
            "C" => "Pahang",
            "A" => "Perak",
            "R" => "Perlis",
            "P" => "Pulau Pinang",
            "S" => "Sabah",
            "Q" => "Sarawak",
            "B" => "Selangor",
            "T" => "Terengganu",
            "WKL" => "WP Kuala Lumpur",
            "WPL" => "WP Labuan",
            "WPJ" => "WP Putrajaya"
        ];

        return $list;
    }

    public static function getData() {
        $query = self::where('is_deleted', 0)
                    ->where('is_active', 1);
                    
        $items = $query->selectRaw('name, code, is_active,
                    created_at')
                    ->get();
        return $items;
    }

}
