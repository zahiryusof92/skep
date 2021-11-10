<?php

class FinanceSummaryOld extends Eloquent {
    protected $table = 'finance_file_summary_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finance_file_id',
        'name',
        'summary_key',
        'amount',
        'sort_no',
    ];
}