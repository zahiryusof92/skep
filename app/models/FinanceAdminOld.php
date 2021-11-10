<?php

class FinanceAdminOld extends Eloquent {
    protected $table = 'finance_file_admin_old';

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
        'tertunggak',
        'sort_no',
        'is_custom'
    ];
}