<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class EpksLedger extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'epks_ledgers';

    protected $fillable = [
        'file_id',
        'strata_id',
        'epks_id',
        'epks_statement_id',
        'name',
        'amount',
    ];

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

    public function epksStement()
    {
        return $this->belongsTo('EpksStatement', 'epks_statement_id');
    }
}
