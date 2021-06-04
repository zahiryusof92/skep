<?php

class ResidentialExtra extends Eloquent {

    protected $table = 'residential_block_extra';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id'
    ];

    public function mfUnit() {
        return $this->belongsTo('UnitOption', 'maintenance_fee_option');
    }

    public function sfUnit() {
        return $this->belongsTo('UnitOption', 'sinking_fund_option');
    }

}
