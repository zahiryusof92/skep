<?php

class FinanceStaffOld extends Eloquent {
    protected $table = 'finance_file_staff_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finance_file_id',
        'name',
        'gaji_per_orang',
        'bil_perkerja',
        'tunggakan',
        'semasa',
        'hadapan',
        'tertunggak',
        'sort_no',
        'is_custom'
    ];
}