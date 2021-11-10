<?php

class FinanceReportExtraOld extends Eloquent {
    protected $table = 'finance_file_report_extra_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finance_file_id',
        'type',
        'fee_sebulan',
        'unit',
        'fee_semasa',
    ];
}

