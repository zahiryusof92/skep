<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class EServiceOrderTransaction extends Eloquent
{
    use SoftDeletingTrait;

    const PENDING = 'pending';
    const INPROGRESS = 'inprogress';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const FAILED = 'failed';

    protected $table = 'eservices_order_transactions';

    protected $fillable = [
        'eservice_order_id',
        'payment_method',
        'payment_amount',
        'payment_receipt_no',
        'payment_response',
        'payment_created_at',
        'status',
    ];

    public function scopeSelf(Builder $builder)
    {
        $builder = self::join('eservices_orders', 'eservices_order_transactions.eservice_order_id', '=', 'eservices_orders.id')
            ->join('users', 'eservices_orders.user_id', '=', 'users.id')
            ->select(['eservices_order_transactions.*', 'eservices_orders.order_no as order_no']);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $builder = $builder->where('users.file_id', Auth::user()->file_id)
                    ->where('users.company_id', Auth::user()->company_id);
            } else {
                $builder = $builder->where('users.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder = $builder->where('users.company_id', Session::get('admin_cob'));
            }
        }

        return $builder;
    }

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
        } else if ($this->status == self::FAILED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.eservice.failed') . '</span>';
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
        return $this->belongsTo('EServiceOrder', 'eservice_order_id');
    }
}
