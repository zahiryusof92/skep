<?php

class Buyer extends Eloquent {

    protected $table = 'buyer';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function race() {
        return $this->belongsTo('Race', 'race_id');
    }

    public function nationality() {
        return $this->belongsTo('Nationality', 'nationality_id');
    }

    public static function unitNoList($file_id) {
        $model = self::where('file_id', $file_id)->where('is_deleted', 0)->orderBy('unit_no', 'asc')->lists('unit_no', 'id');

        return $model;
    }

}
