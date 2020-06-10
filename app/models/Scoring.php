<?php

class Scoring extends Eloquent {

    protected $table = 'scoring_quality_index';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

}
