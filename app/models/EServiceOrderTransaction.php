<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class EServiceOrderTransaction extends Eloquent
{
    use SoftDeletingTrait;

    CONST PENDING = 'pending';
    CONST INPROGRESS = 'inprogress';
    CONST APPROVED = 'approved';
    CONST REJECTED = 'rejected';

    protected $table = 'eservices_order_transactions';

    protected $fillable = [
        'eservice_order_id',
        'payment_method',
        'total_price',
        'status',
    ];

    public static function getStatusOption()
    {
        $options = [
            '' => trans('- Please Select -'),
            self::PENDING => trans('app.eservice.pending'),
            self::INPROGRESS => trans('app.eservice.inprogress'),
            self::APPROVED => trans('app.eservice.approved'),
            self::REJECTED => trans('app.eservice.rejected'),
        ];
        return $options;
    }

    public function getStatusBadge()
    {
        $status = '<span class="label label-pill label-secondary" style="font-size:12px;">' . trans('app.eservice.draft') . '</span>';

        if ($this->status == self::PENDING) {
            $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.eservice.pending') . '</span>';
        } else if ($this->status == self::INPROGRESS) {
            $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.eservice.inprogress') . '</span>';
        } else if ($this->status == self::APPROVED) {
            $status = '<span class="label label-pill label-success" style="font-size:12px;">' . trans('app.eservice.approved') . '</span>';
        } else if ($this->status == self::REJECTED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.eservice.rejected') . '</span>';
        }

        return $status;
    }

    public function getStatusText()
    {
        $status = 'Draft';
        if ($this->status == self::PENDING) {
            $status = "Pending";
        } else if ($this->status == self::INPROGRESS) {
            $status = "In-Progress";
        } else if ($this->status == self::APPROVED) {
            $status = "Approved";
        } else if ($this->status == self::REJECTED) {
            $status = "Rejected";
        }

        return $status;
    }

    public function order()
    {
        return $this->belongsTo('EservicesOrder', 'eservices_order_id');
    }
}
