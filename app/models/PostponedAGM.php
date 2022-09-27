<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class PostponedAGM extends Eloquent
{
    use SoftDeletingTrait;

    const DRAFT = 'draft';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

    protected $table = 'postponed_agms';

    protected $fillable = [
        'company_id',
        'file_id',
        'strata_id',
        'user_id',
        'application_no',
        'agm_date',
        'new_agm_date',
        'postponed_agm_reason_id',
        'reason',
        'attachment',
        'status',
        'approval_by',
        'approval_date',
        'approval_remark',
        'approval_attachment',
    ];

    public function getStatusBadge()
    {
        $status = '<span class="label label-pill label-secondary" style="font-size:12px;">' . trans('app.agm_postpone.draft') . '</span>';

        if ($this->status == self::PENDING) {
            $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.agm_postpone.pending') . '</span>';
        } else if ($this->status == self::APPROVED) {
            $status = '<span class="label label-pill label-success" style="font-size:12px;">' . trans('app.agm_postpone.approved') . '</span>';
        } else if ($this->status == self::REJECTED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.agm_postpone.rejected') . '</span>';
        }

        return $status;
    }

    public function getStatusText()
    {
        $status = 'Draft';
        if ($this->status == self::PENDING) {
            $status = "Pending";
        } else if ($this->status == self::APPROVED) {
            $status = "Approved";
        } else if ($this->status == self::REJECTED) {
            $status = "Rejected";
        }

        return $status;
    }

    public static function getStatusOption()
    {
        $options = [
            '' => trans('- Please Select -'),
            self::PENDING => trans('app.agm_postpone.pending'),
            self::APPROVED => trans('app.agm_postpone.approved'),
            self::REJECTED => trans('app.agm_postpone.rejected'),
        ];

        return $options;
    }

    public function scopeSelf(Builder $builder)
    {
        $builder = self::join('users', 'postponed_agms.user_id', '=', 'users.id')
            ->join('files', 'postponed_agms.file_id', '=', 'files.id')
            ->join('company', 'postponed_agms.company_id', '=', 'company.id')
            ->select(['postponed_agms.*']);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $builder = $builder->where('postponed_agms.file_id', Auth::user()->file_id)
                    ->where('postponed_agms.company_id', Auth::user()->company_id);
            } else {
                $builder = $builder->where('postponed_agms.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder = $builder->where('postponed_agms.company_id', Session::get('admin_cob'));
            }
        }

        return $builder;
    }

    public function scopeDraft(Builder $builder)
    {
        return $builder->where('postponed_agms.file_id', Auth::user()->file_id)->where('postponed_agms.status', self::DRAFT);
    }

    public function scopeNotDraft(Builder $builder)
    {
        return $builder->whereNotIn('postponed_agms.status', [self::DRAFT, self::APPROVED, self::REJECTED]);
    }

    public function scopeApproved(Builder $builder)
    {
        return $builder->where('postponed_agms.status', self::APPROVED);
    }

    public function scopeRejected(Builder $builder)
    {
        return $builder->where('postponed_agms.status', self::REJECTED);
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

    public function postponedAGMReason()
    {
        return $this->belongsTo('PostponedAGMReason', 'postponed_agm_reason_id');
    }
}
