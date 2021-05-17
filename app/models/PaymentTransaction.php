<?php

class PaymentTransaction extends Eloquent {

    protected $table = 'payment_transaction';
    
    const SUCCESS = 0;
    const FAIL = 1;
    const PENDING = 2;
    const REFUND = 3;

    /**
     * Get the parent moduleable model (user).
     */
    public function moduleable()
    {
        return $this->morphTo();
    }

}
