<?php

class ResidentialExtraDraft extends Eloquent {

    protected $table = 'residential_block_extra_draft';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'strata_id',
        'unit_no',
        'maintenance_fee',
        'maintenance_fee_option',
        'sinking_fund',
        'sinking_fund_option',
        'is_deleted',
    ];

    public function mfUnit() {
        return $this->belongsTo('UnitOption', 'maintenance_fee_option');
    }

    public function sfUnit() {
        return $this->belongsTo('UnitOption', 'sinking_fund_option');
    }

}
