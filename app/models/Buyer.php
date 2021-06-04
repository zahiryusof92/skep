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

    public static function getCompositionByRace($file_id, $race) {
        $race_id = Race::where('name_en',$race)->where('is_deleted',0)->first()->id;
        $total = self::where('file_id',$file_id)->where('is_deleted',0)->where('race_id', $race_id)->count();

        return $total;
    }

    public static function getForeignerComposition($file_id) {
        $n_ids = Nationality::whereNotIn('name',['Malaysian','Malaysia'])->lists('id');
        $total = self::where('file_id',$file_id)->where('is_deleted',0)->whereIn('nationality_id', $n_ids)->count();
        
        return $total;
    }

}
