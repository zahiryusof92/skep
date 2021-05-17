<?php


class PointTransaction extends Eloquent {

    protected $table = 'point_transaction';
    
    public function paidBy() {
        return $this->belongsTo('User', 'user_id');
    }
}
