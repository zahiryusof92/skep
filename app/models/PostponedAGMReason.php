<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class PostponedAGMReason extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'postponed_agm_reasons';

    protected $fillable = [
        'name',
        'sort',
        'active',
    ];

    public static function getData()
    {
        $items = self::where('active', true)
            ->orderBy('sort', 'asc')
            ->lists('name', 'id');

        return $items;
    }
}
