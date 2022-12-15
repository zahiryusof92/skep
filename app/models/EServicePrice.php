<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EServicePrice extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'eservices_prices';

    protected $fillable = [
        'company_id',
        'category_id',
        'type',
        'slug',
        'price',
    ];

    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }

    public function category()
    {
        return $this->belongsTo('Category', 'category_id');
    }
}
