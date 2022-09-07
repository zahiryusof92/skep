<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class DlpPeriod extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'dlp_periods';

    protected $fillable = [
        'file_id',
        'duration',
    ];

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }
}
