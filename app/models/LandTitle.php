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

}
