<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class DlpDeposit extends Eloquent
{
    use SoftDeletingTrait;

    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const RETURNED = 'returned';

    protected $table = 'dlp_deposits';

    protected $fillable = [
        'company_id',
        'file_id',
        'strata_id',
        'user_id',
        'type',
        'development_cost',
        'amount',
        'balance',
        'start_date',
        'maturity_date',
        'vp_date',
        'checklist',
        'return_checklist',
        'attachment',
        'status',
        'approval_by',
        'approval_date',
        'approval_remark',
    ];

    public function getStatusText()
    {
        $status = "Pending";

        if ($this->status == self::APPROVED) {
            $status = trans('app.dlp.deposit.approved');
        } else if ($this->status == self::REJECTED) {
            $status = trans('app.dlp.deposit.rejected');
        }

        return $status;
    }

    public static function getStatusOption()
    {
        $options = [
            '' => trans('- Please Select -'),
            self::PENDING => Str::upper(trans('app.dlp.deposit.pending')),
            self::APPROVED => Str::upper(trans('app.dlp.deposit.approved')),
            self::REJECTED => Str::upper(trans('app.dlp.deposit.rejected')),
        ];

        return $options;
    }

    public function getStatusBadge()
    {
        $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.dlp.deposit.pending') . '</span>';

        if ($this->status == self::APPROVED) {
            $status = '<span class="label label-pill label-success" style="font-size:12px;">' . trans('app.dlp.deposit.received') . '</span>';
        } else if ($this->status == self::REJECTED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.dlp.deposit.rejected') . '</span>';
        } else if ($this->status == self::RETURNED) {
            $status = '<span class="label label-pill label-info" style="font-size:12px;">' . trans('app.dlp.deposit.returned') . '</span>';
        }

        return $status;
    }

    public function scopeSelf(Builder $builder)
    {
        $builder = self::with(['company', 'file', 'strata', 'user'])->select(['dlp_deposits.*']);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $builder = $builder->where('dlp_deposits.file_id', Auth::user()->file_id)
                    ->where('dlp_deposits.company_id', Auth::user()->company_id);
            } else {
                $builder = $builder->where('dlp_deposits.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder = $builder->where('dlp_deposits.company_id', Session::get('admin_cob'));
            }
        }

        return $builder;
    }

    public function scopePending(Builder $builder)
    {
        return $builder->where('dlp_deposits.file_id', Auth::user()->file_id)
            ->where('dlp_deposits.status', self::PENDING);
    }

    public function scopeNotPending(Builder $builder)
    {
        return $builder->whereNotIn('dlp_deposits.status', [self::APPROVED, self::REJECTED]);
    }

    public function scopeApproved(Builder $builder)
    {
        return $builder->where('dlp_deposits.status', self::APPROVED);
    }

    public function scopeRejected(Builder $builder)
    {
        return $builder->where('dlp_deposits.status', self::REJECTED);
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

    public function usages() {
        return $this->hasMany('DlpDeposit', 'dlp_deposit_id');
    }
}
