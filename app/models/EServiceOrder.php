<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EServiceOrder extends Eloquent
{
    use SoftDeletingTrait;

    const DRAFT = 'draft';
    const PENDING = 'pending';
    const INPROGRESS = 'inprogress';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const INCOMPLETE = 'incomplete';
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
        'type',
        'value',
        'bill_no',
        'date',
        'hijri_date',
        'price',
        'reference_id',
        'jana_bil_no_akaun',
        'jana_bil_response',
        'jana_bil_created_at',
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

    public function scopeDraft(Builder $builder)
    {
        return $builder->where('eservices_orders.user_id', Auth::user()->id)->where('eservices_orders.status', self::DRAFT);
    }

    public function scopeNotDraft(Builder $builder)
    {
        return $builder->whereNotIn('eservices_orders.status', [self::DRAFT, self::APPROVED, self::REJECTED, self::INCOMPLETE]);
    }

    public function scopeApproved(Builder $builder)
    {
        return $builder->where('eservices_orders.status', self::APPROVED);
    }

    public function scopeRejected(Builder $builder)
    {
        return $builder->where('eservices_orders.status', self::REJECTED);
    }

    public function scopeIncomplete(Builder $builder)
    {
        return $builder->where('eservices_orders.status', self::INCOMPLETE);
    }

    public static function getTypeList($request = [])
    {
        $options = [];

        if (!empty($request['company'])) {
            $cob = Company::find($request['company']);
            if ($cob) {
                $types = (!empty(self::module()['cob'][Str::lower($cob->short_name)])) ? self::module()['cob'][Str::lower($cob->short_name)]['type'] : '';

                if (!empty($types)) {
                    foreach ($types as $type) {
                        $options[$type['name']] = $type['title'];
                    }
                }
            }
        } else {
            if (!Auth::user()->getAdmin()) {
                if (Auth::user()->getCOB) {
                    $cob = Auth::user()->getCOB->short_name;
                    if (!empty($cob)) {
                        $types = (!empty(self::module()['cob'][Str::lower($cob)])) ? self::module()['cob'][Str::lower($cob)]['type'] : '';

                        if (!empty($types)) {
                            foreach ($types as $type) {
                                $options[$type['name']] = $type['title'];
                            }
                        }
                    }
                }
            } else {
                if (empty(Session::get('admin_cob'))) {
                    $types = [];
                    $companies = [
                        'MBPJ',
                    ];

                    foreach ($companies as $cob) {
                        $types = (!empty(self::module()['cob'][Str::lower($cob)])) ? self::module()['cob'][Str::lower($cob)]['type'] : '';

                        if (!empty($types)) {
                            foreach ($types as $type) {
                                $options[$type['name']] = $type['title'];
                            }
                        }
                    }
                } else {
                    $cob = Company::find(Session::get('admin_cob'));
                    if ($cob) {
                        $types = (!empty(self::module()['cob'][Str::lower($cob->short_name)])) ? self::module()['cob'][Str::lower($cob->short_name)]['type'] : '';

                        if (!empty($types)) {
                            foreach ($types as $type) {
                                $options[$type['name']] = $type['title'];
                            }
                        }
                    }
                }
            }
        }

        return $options;
    }

    public static function module()
    {
        return Config::get('constant.module.eservice');
    }

    public static function getTypeOption()
    {
        $options = [];

        if (Auth::user()->getCOB) {
            $cob = Auth::user()->getCOB->short_name;
            if (!empty($cob)) {
                $types = (!empty(self::module()['cob'][Str::lower($cob)])) ? self::module()['cob'][Str::lower($cob)]['type'] : '';

                if (!empty($types)) {
                    foreach ($types as $type) {
                        array_push($options, ['id' => $type['name'], 'text' => $type['title']]);
                    }
                }
            }
        }

        return $options;
    }

    public static function getStatusList()
    {
        $list = [
            // self::DRAFT => trans('app.eservice.draft'),
            self::INPROGRESS => trans('app.eservice.inprogress'),
            self::INCOMPLETE => trans('app.eservice.incomplete'),
            self::APPROVED => trans('app.eservice.approved'),
            self::REJECTED => trans('app.eservice.rejected'),
        ];

        return $list;
    }

    public static function getStatusOption()
    {
        $options = [
            '' => trans('- Please Select -'),
            // self::PENDING => trans('app.eservice.pending'),
            self::INPROGRESS => trans('app.eservice.inprogress'),
            self::INCOMPLETE => trans('app.eservice.incomplete'),
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
        } else if ($this->status == self::INCOMPLETE) {
            $status = '<span class="label label-pill label-info" style="font-size:12px;">' . trans('app.eservice.incomplete') . '</span>';
        } else if ($this->status == self::APPROVED) {
            $status = '<span class="label label-pill label-success" style="font-size:12px;">' . trans('app.eservice.approved') . '</span>';
            if (Auth::user()->getAdmin()) {
                $status .= '<br/><span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.eservice.email_sent') . '</span>&nbsp;<i class="fa fa-check text-success"></i>';
            }
        } else if ($this->status == self::REJECTED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.eservice.rejected') . '</span>';
        }

        return $status;
    }

    public function getStatusText()
    {
        $status = trans('app.eservice.draft');
        if ($this->status == self::PENDING) {
            $status = trans('app.eservice.pending');
        } else if ($this->status == self::INPROGRESS) {
            $status = trans('app.eservice.inprogress');
        } else if ($this->status == self::INCOMPLETE) {
            $status = trans('app.eservice.incomplete');
        } else if ($this->status == self::APPROVED) {
            $status = trans('app.eservice.approved');
        } else if ($this->status == self::REJECTED) {
            $status = trans('app.eservice.rejected');
        }

        return $status;
    }

    public function getTypeText()
    {
        $cob = $this->company->short_name;
        $type = $this->type;

        if (isset(self::module()['cob'][Str::lower($cob)])) {
            if (isset(self::module()['cob'][Str::lower($cob)]['type'][$type])) {
                return self::module()['cob'][Str::lower($cob)]['type'][$type]['title'];
            }
        }

        return '<i>(not set)</i>';
    }

    public static function getGraphData($request = [])
    {
        $data = [];
       
        $statuses = EServiceOrder::getStatusList();
        $types = EServiceOrder::getTypeList($request);

        if ($statuses && $types) {
            foreach ($statuses as $status) {
                $data['categories'][] = $status;
            }

            foreach ($types as $type_key => $type) {
                $value = [];
                $value['name'] = $type;

                foreach ($statuses as $status_key => $status) {
                    $query = EServiceOrder::where('eservices_orders.status', $status_key)
                        ->where('eservices_orders.type', $type_key);

                    if (!Auth::user()->getAdmin()) {
                        if (Auth::user()->getCOB) {
                            $cobId = Auth::user()->getCOB->id;
                            $query->where('eservices_orders.company_id', $cobId);
                        }
                    } else {
                        if (!empty(Session::get('admin_cob'))) {
                            $query->where('eservices_orders.company_id', Session::get('admin_cob'));
                        } else {
                            if (empty($request['company'])) {
                                $mbpj = Company::where('short_name', 'MBPJ')->first();
                                if ($mbpj) {
                                    $query->where('eservices_orders.company_id', $mbpj->id);
                                }
                            }
                        }
                    }

                    if (!empty($request['company'])) {
                        $query->where('eservices_orders.company_id', $request['company']);
                    }

                    if (!empty($request['date_from']) && empty($request['date_to'])) {
                        $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                        $query->where('eservices_orders.created_at', '>=', $date_from);
                    }

                    if (!empty($request['date_to']) && empty($request['date_from'])) {
                        $date_to = date('Y-m-d', strtotime($request['date_to']));
                        $query->where('eservices_orders.created_at', '<=', $date_to . " 23:59:59");
                    }

                    if (!empty($request['date_from']) && !empty($request['date_to'])) {
                        $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                        $date_to = date('Y-m-d', strtotime($request['date_to']));
                        $query->whereBetween('eservices_orders.created_at', [$date_from, $date_to . ' 23:59:59']);
                    }

                    $query = $query->count();

                    $value['data'][] = $query;
                }

                $data['series'][] = $value;
            }
        }

        return $data;
    }

    public static function todaySubmission()
    {
        return self::where('status', self::INPROGRESS)->where('created_at', '>=', Carbon::today())->count();
    }

    public static function yesterdaySubmission()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        return self::where('status', self::INPROGRESS)->whereBetween('created_at', [$yesterday, $today])->count();
    }

    public static function lessThan7DaysSubmission()
    {
        $yesterday = Carbon::yesterday();
        $week = Carbon::today()->subWeek();

        return self::where('status', self::INPROGRESS)->whereBetween('created_at', [$week, $yesterday])->count();
    }

    public static function moreThan7DaysSubmission()
    {
        $week = Carbon::today()->subWeek();

        return self::where('status', self::INPROGRESS)->where('created_at', '<', $week)->count();
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

    public function transaction()
    {
        return $this->hasOne('EServiceOrderTransaction', 'eservice_order_id')->latest();
    }
}
