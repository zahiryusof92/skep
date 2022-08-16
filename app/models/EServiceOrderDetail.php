<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class EServiceOrderDetail extends Eloquent
{
    use SoftDeletingTrait;

    CONST DRAFT = 'draft';
    CONST PENDING = 'pending';
    CONST INPROGRESS = 'inprogress';
    CONST APPROVED = 'approved';
    CONST REJECTED = 'rejected';

    protected $table = 'eservices_order_details';

    protected $fillable = [
        'eservice_order_id',        
        'type',
        'bill_no',
        'date',
        'value',
        'price',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo('EserviceOrder', 'eservice_order_id');
    }
}
