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

}
