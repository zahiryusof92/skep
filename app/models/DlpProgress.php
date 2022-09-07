<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class DlpProgress extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'dlp_progresses';

    protected $fillable = [
        'file_id',
        'date',
        'percentage',
    ];

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }
}
