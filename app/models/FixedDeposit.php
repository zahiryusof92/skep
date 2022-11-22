<?php

class FixedDeposit extends Eloquent
{

    protected $table = 'fixed_deposits';

    public function files()
    {
        return $this->belongsTo('Files', 'file_id');
    }
}
