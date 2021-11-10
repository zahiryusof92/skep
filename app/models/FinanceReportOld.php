<?php

class FinanceReportOld extends Eloquent {
    protected $table = 'finance_file_report_old';

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
        'no_akaun',
        'nama_bank',
        'baki_bank_akhir',
        'baki_bank_awal'
    ];
}

