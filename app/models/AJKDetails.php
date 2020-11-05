<?php

class AJKDetails extends Eloquent {

    protected $table = 'ajk_details';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
    
    public function designations() {
        return $this->belongsTo('Designation', 'designation');
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
