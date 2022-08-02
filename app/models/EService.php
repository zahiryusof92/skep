<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EService extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'eservices';

    protected $fillable = [
        'company_id',
        'type',
        'date',
        'value',
        'causer_by'
    ];

    public function scopeSelf(Builder $builder)
    {
        if (!Auth::user()->getAdmin()) {
            $builder = $builder->where('company_id', Auth::user()->company_id);
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder = $builder->where('company_id', Session::get('admin_cob'));
            }
        }
        return $builder;
    }

    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'causer_by');
    }
}
