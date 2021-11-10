<?php

class FinanceIncomeOld extends Eloquent {
    protected $table = 'finance_file_income_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finance_file_id',
        'name',
        'tunggakan',
        'semasa',
        'hadapan',
        'sort_no',
        'is_custom'
    ];
}