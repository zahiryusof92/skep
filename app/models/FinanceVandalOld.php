<?php

class FinanceVandalOld extends Eloquent {
    protected $table = 'finance_file_vandalisme_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finance_file_id',
        'type',
        'name',
        'tunggakan',
        'semasa',
        'hadapan',
        'tertunggak',
        'sort_no',
        'is_custom'
    ];
}