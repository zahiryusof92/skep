<?php

class Vendor extends Eloquent {

    protected $table = 'vendors';
    
    const PENDING = 'pending';
    const INPROGRESS = 'inprogress';
    const COMPLETE = 'complete';

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }
    
    public static function status($status) {
        $label = '-';
        
        if ($status == self::PENDING) {
            $label = '<span class="label label-danger">Pending</span>';
        } else if ($status == self::INPROGRESS) {
            $label = '<span class="label label-warning">In Progress</span>';
        } else if ($status == self::COMPLETE) {
            $label = '<span class="label label-success">Complete</span>';
        }
        
        return $label;
    }

}
