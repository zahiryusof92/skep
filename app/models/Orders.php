<?php

class Orders extends Eloquent {

    protected $table = 'orders';

    const RELOAD = 'reload';
    const SUMMON = 'summon';
    const REFUND = 'refund';
    const FPX = 'FPX';
    const CARD = 'card';
    const POINT = 'point';
    const BANK_TRANSFER = 'bank_transfer';
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
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function getSummonPoint() {
        $total = 0;

        $summon = Summon::find($this->reference_id);
        if ($summon) {
            $category = SummonRate::where('category_id', $summon->category_id)->where('company_id', $summon->company_id)->first();
            if ($category) {
                $total_myr = $category->amount;
                if ($total_myr > 0) {
                    $conversion = Conversion::first();
                    if ($conversion && $conversion->rate > 0) {
                        /*
                         * MYR to COIN
                         */
                        $total = $total_myr * $conversion->rate;

                        return round($total);
                    }
                }
            }
        }

        return $total;
    }
    
    public function getSummonRate() {
        $rate = 0;

        $conversion = Conversion::first();
        if ($conversion && $conversion->rate > 0) {
            $rate = $conversion->rate;
        }

        return $rate;
    }

}
