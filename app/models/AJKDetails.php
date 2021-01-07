<?php

class AJKDetails extends Eloquent {

    protected $table = 'ajk_details';

    public static function monthList() {
        $month = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MAY',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AUG',
            '09' => 'SEP',
            '10' => 'OCT',
            '11' => 'NOV',
            '12' => 'DEC'
        ];

        return $month;
    }

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
