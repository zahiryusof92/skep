<?php

class FinanceReportPerbelanjaanOld extends Eloquent {
    protected $table = 'finance_file_report_perbelanjaan_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finance_file_id',
        'type',
        'name',
        'report_key',
        'amount',
        'sort_no',
        'is_custom',
    ];
}