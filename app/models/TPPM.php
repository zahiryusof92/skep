<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * @property string $status
 * @mixin \Eloquent
 */
class TPPM extends Eloquent
{
    use SoftDeletingTrait;

    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

    protected $table = 'tppms';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'approval_date'
    ];

    protected $fillable = [
        'company_id',
        'file_id',
        'strata_id',
        'reference_no',
        'cost_category',
        'applicant_name',
        'applicant_position',
        'applicant_phone',
        'applicant_email',
        'organization_name',
        'organization_address_1',
        'organization_address_2',
        'organization_address_3',
        'parliament_id',
        'dun_id',
        'district_id',
        'first_purchase_price',
        'year_built',
        'year_occupied',
        'num_blocks',
        'num_units',
        'num_units_occupied',
        'num_units_owner',
        'num_units_malaysian',
        'num_storeys',
        'num_residents',
        'num_units_vacant',
        'num_units_tenant',
        'num_units_non_malaysian',
        'requested_block_name',
        'requested_block_no',
        'scope',
        'spa_copy',
        'detail_report',
        'meeting_minutes',
        'cost_estimate',
        'status',
        'approval_by',
        'approval_date',
        'approval_remark',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function scopeSelf(Builder $builder)
    {
        $builder = self::join('users', 'tppms.created_by', '=', 'users.id')
            ->leftJoin('files', 'tppms.file_id', '=', 'files.id')
            ->leftJoin('company', 'tppms.company_id', '=', 'company.id')
            ->select(['tppms.*']);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $builder = $builder->where('tppms.file_id', Auth::user()->file_id)
                    ->where('tppms.company_id', Auth::user()->company_id);
            } else {
                $builder = $builder->where('tppms.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $builder = $builder->where('tppms.company_id', Session::get('admin_cob'));
            }
        }

        return $builder;
    }

    public function scopePending(Builder $builder)
    {
        return $builder->where('tppms.created_by', Auth::user()->id)->where('tppms.status', self::PENDING);
    }

    public function scopeNotPending(Builder $builder)
    {
        return $builder->whereNotIn('tppms.status', [self::APPROVED, self::REJECTED]);
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

    public function parliament()
    {
        return $this->belongsTo('Parliment', 'parliament_id');
    }

    public function dun()
    {
        return $this->belongsTo('Dun', 'dun_id');
    }

    public function district()
    {
        return $this->belongsTo('Area', 'district_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo('User', 'approval_by');
    }

    public function getStatusBadge()
    {
        $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.forms.tppm.status.pending') . '</span>';

        if ($this->status == self::APPROVED) {
            $status = '<span class="label label-pill label-success" style="font-size:12px;">' . trans('app.forms.tppm.status.approved') . '</span>';
        } else if ($this->status == self::REJECTED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.forms.tppm.status.rejected') . '</span>';
        }

        return $status;
    }

    public function getStatusText()
    {
        $status = trans('app.forms.tppm.status.pending');

        if ($this->status == self::APPROVED) {
            $status = trans('app.forms.tppm.status.approved');
        } else if ($this->status == self::REJECTED) {
            $status = trans('app.forms.tppm.status.rejected');
        }
        return $status;
    }

    public static function getStatusOption()
    {
        $options = [
            '' => trans('app.forms.please_select'),
            self::APPROVED => trans('app.forms.tppm.approval.approved'),
            self::REJECTED => trans('app.forms.tppm.approval.rejected'),
        ];

        return $options;
    }
}
