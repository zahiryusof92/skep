<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class EpksStatement extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'epks_statements';

    protected $fillable = [
        'file_id',
        'strata_id',
        'epks_id',
        'month',
        'year',
        'profit',
        'prepared_by',
        'position_prepared_by',
        'approved_by',
        'position_approved_by'
    ];

    public function monthName()
    {
        if ($this->month) {
            $dateObj = DateTime::createFromFormat('!m', $this->month);
            $monthName = $dateObj->format('M'); // March

            return strtoupper($monthName);
        } else {
            return "<i>(not set)</i>";
        }
    }

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function strata()
    {
        return $this->belongsTo('Strata', 'strata_id');
    }

    public function epks()
    {
        return $this->belongsTo('Epks', 'epks_id');
    }
}