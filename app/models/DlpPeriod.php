<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class DlpPeriod extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'dlp_periods';

    protected $fillable = [
        'company_id',
        'file_id',
        'strata_id',
        'user_id',
        'duration',
    ];

    public function scopeSelf(Builder $builder)
    {
        $builder = self::with(['company', 'file', 'strata', 'user'])->select(['dlp_periods.*']);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $builder = $builder->where('dlp_periods.file_id', Auth::user()->file_id)
                    ->where('dlp_periods.company_id', Auth::user()->company_id);
            } else {
                $builder = $builder->where('dlp_periods.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder = $builder->where('dlp_periods.company_id', Session::get('admin_cob'));
            }
        }

        return $builder;
    }

    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function strata()
    {
        return $this->belongsTo('Strata', 'strata_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}
