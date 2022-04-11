<?php

class SubModule extends Eloquent {
    protected $table = 'sub_module';

    protected $fillable = [
        'module_id',
        'name_en',
        'name_my',
        'sort_no'
    ];
}