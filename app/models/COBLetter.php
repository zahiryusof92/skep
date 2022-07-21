<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class COBLetter extends Eloquent {
    use SoftDeletingTrait;

    protected $table = 'cob_letters';

    protected $fillable = [
        'company_id',
        'type',
        'date',
        'bill_no',
        'building_name',
        'receiver_name',
        'unit_name',
        'receiver_address_1',
        'receiver_address_2',
        'receiver_address_3',
        'receiver_address_4',
        'receiver_address_5',
        'management_address_1',
        'management_address_2',
        'management_address_3',
        'management_address_4',
        'management_address_5',
        'from_address_1',
        'from_address_2',
        'from_address_3',
        'from_address_4',
        'from_address_5',
        'causer_by'
    ];

    public function scopeSelf(Builder $builder) {
        if (!Auth::user()->getAdmin()) {
            $builder = $builder->where('company_id', Auth::user()->company_id);
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder = $builder->where('company_id', Session::get('admin_cob'));
            }
        }
        return $builder;
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function user() {
        return $this->belongsTo('User', 'causer_by');
    }
}