<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EServiceOrder extends Eloquent
{
    use SoftDeletingTrait;

    const DRAFT = 'draft';
    const PENDING = 'pending';
    const INPROGRESS = 'inprogress';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const FPX = 'FPX';
    const CARD = 'card';

    protected $table = 'eservices_orders';

    protected $fillable = [
        'company_id',
        'file_id',
        'strata_id',
        'category_id',
        'user_id',
        'order_no',
        'status',
        'approval_by',
        'approval_date',
        'approval_remark',
    ];

    public function scopeSelf(Builder $builder)
    {
        $builder = self::join('users', 'eservices_orders.user_id', '=', 'users.id')
            ->join('strata', 'eservices_orders.strata_id', '=', 'strata.id')
            ->leftjoin('files', 'users.file_id', '=', 'files.id')
            ->leftjoin('company', 'users.company_id', '=', 'company.id')
            ->select(['eservices_orders.*']);
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

    public function scopeApproval(Builder $builder)
    {
        return $builder->whereIn('eservices_orders.status', [self::APPROVED, self::REJECTED]);
    }

    public function scopeDraft(Builder $builder)
    {
        return $builder->where('eservices_orders.user_id', Auth::user()->id)->where('eservices_orders.status', self::DRAFT);
    }

    public function scopeNotDraft(Builder $builder)
    {
        return $builder->whereNotIn('eservices_orders.status', [self::DRAFT, self::APPROVED, self::REJECTED]);
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

    public function company()
    {
        return $this->belongsTo('Company', 'company_id');
    }

    public function file()
    {
        return $this->belongsTo('Files', 'file_id');
    }

    public function strata()
    {
        return $this->belongsTo('Strata', 'strata_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo('User', 'approval_by');
    }

    public function details()
    {
        return $this->hasOne('EServiceOrderDetail', 'eservice_order_id');
    }

    public function transaction()
    {
        return $this->hasOne('EServiceOrderTransaction', 'eservice_order_id');
    }
}
