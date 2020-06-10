<?php

class AJKDetails extends Eloquent {

    protected $table = 'ajk_details';

    public function files() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function monthName() {
        if ($this->month) {
            $dateObj = DateTime::createFromFormat('!m', $this->month);
            $monthName = $dateObj->format('M'); // March

            return strtoupper($monthName);
        } else {
            return "<i>(not set)</i>";
        }
    }

}
