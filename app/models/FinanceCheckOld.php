<?php

class FinanceCheckOld extends Eloquent {
    protected $table = 'finance_check_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finance_file_id',
        'name',
        'position',
        'remarks',
        'is_active',
    ];
    
}