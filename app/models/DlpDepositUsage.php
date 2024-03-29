<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class DlpDepositUsage extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'dlp_deposit_usages';

    protected $fillable = [
        'dlp_deposit_id',
        'description',
        'amount',
        'amount_before',
        'amount_after',
        'attachment',
    ];

    public function dlpDeposit()
    {
        return $this->belongsTo('DlpDeposit', 'dlp_deposit_id');
    }
}
