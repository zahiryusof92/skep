<?php

class Commercial extends Eloquent {
    protected $table = 'commercial_block';
    
    public function mfUnit() {
        return $this->belongsTo('UnitOption', 'maintenance_fee_option');
    }
    
    public function sfUnit() {
        return $this->belongsTo('UnitOption', 'sinking_fund_option');
    }
}