<?php

class Orders extends Eloquent {

    protected $table = 'orders';

    const RELOAD = 'reload';
    const SUMMON = 'summon';
    const REFUND = 'refund';
    const FPX = 'FPX';
    const CARD = 'card';
    const POINT = 'point';
    const PENDING = 0;
    const PROCESSING = 1;
    const APPROVED = 2;
    const REJECTED = 3;

    public function package() {
        return $this->belongsTo('PointPackage', 'reference_id');
    }

    public function summon() {
        return $this->belongsTo('Summon', 'reference_id');
    }

}
