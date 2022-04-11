<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Epks extends Eloquent {
    use SoftDeletingTrait;

    protected $table = 'epks';

    protected $fillable = [
        'file_id',
        'strata_id',
        'email',
        'address_1',
        'address_2',
        'address_3',
        'place_proposal',
        'sketch_proposal',
        'filename',
        'remarks',
        'status',
        'causer_by'
    ];

    CONST DRAFT = 0;
    CONST PENDING = 1;
    CONST INPROGRESS = 2;
    CONST APPROVED = 3;
    CONST REJECTED = 4;

    public function scopeSelf(Builder $builder) {
        $builder = self::join('users', 'epks.causer_by', '=', 'users.id')
                        ->join('strata', 'epks.strata_id', '=', 'strata.id')
                        ->leftjoin('files', 'users.file_id', '=', 'files.id')
                        ->leftjoin('company', 'users.company_id', '=', 'company.id')
                        ->select(['epks.*']);
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

    public function scopeApproval(Builder $builder) {
        return $builder->where('epks.status', self::APPROVED);
    }

    public function scopeDraft(Builder $builder) {
        return $builder->where('epks.causer_by', Auth::user()->id)->where('epks.status', self::DRAFT);
    }

    public function scopeNotDraft(Builder $builder) {
        return $builder->whereNotIn('epks.status', [self::DRAFT, self::APPROVED]);
    }

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function strata() {
        return $this->belongsTo('Strata', 'strata_id');
    }

    public function user() {
        return $this->belongsTo('User', 'causer_by');
    }

    public function status() {
        $status = '<span class="label label-pill label-secondary" style="font-size:12px;">' . trans('app.epks.draft') . '</span>';

        if ($this->status == self::PENDING) {
            $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.epks.pending') . '</span>';
        } else if ($this->status == self::INPROGRESS) {
            $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.epks.inprogress') . '</span>';
        } else if ($this->status == self::APPROVED) {
            $status = '<span class="label label-pill label-success" style="font-size:12px;">' . trans('app.epks.approved') . '</span>';
        } else if ($this->status == self::REJECTED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.epks.rejected') . '</span>';
        }

        return $status;
    }

    public function getStatusText() {
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

    public static function getStatusOption() {
        $options = [
            '' => trans('- Please Select -'),
            self::PENDING => trans('app.epks.pending'),
            self::INPROGRESS => trans('app.epks.inprogress'),
            self::APPROVED => trans('app.epks.approved'),
            self::REJECTED => trans('app.epks.rejected'),
        ];
        return $options;
    }

    public static function getAnalyticData($request = []) {
        $start_month = Carbon::now()->startOfMonth()->subMonth(11);
        $end_month = Carbon::now()->endOfMonth();
        
        $models = self::self()
                    ->where('epks.status', '!=', self::DRAFT)
                    ->where(function($query) use($request, $start_month, $end_month) {
                        if(!empty($request['file_id'])) {
                            $query->where('epks.file_id', $request['file_id']);
                        }
                        
                        if(!empty($request['date_from'])) {
                            $start_month = Carbon::createFromFormat('Y-m-d', $request['date_from'])->startOfDay();
                        }
                        if(!empty($request['date_to'])) {
                            $end_month = Carbon::createFromFormat('Y-m-d', $request['date_to'])->endOfDay();
                        }
                        $query->whereBetween('epks.created_at', [$start_month->toDateTimeString(), $end_month->toDateTimeString()]);
                    })
                    ->selectRaw('count(epks.id) as total, epks.status as status')
                    ->groupBy(['status'])
                    ->get();
        $models1 = self::self()
                    ->where('epks.status', '!=', self::DRAFT)
                    ->where(function($query) use($request, $start_month, $end_month) {
                        if(!empty($request['file_id'])) {
                            $query->where('epks.file_id', $request['file_id']);
                        }
                        
                        if(!empty($request['date_from'])) {
                            $start_month = Carbon::createFromFormat('Y-m-d', $request['date_from'])->startOfDay();
                        }
                        if(!empty($request['date_to'])) {
                            $end_month = Carbon::createFromFormat('Y-m-d', $request['date_to'])->endOfDay();
                        }
                        $query->whereBetween('epks.created_at', [$start_month->toDateTimeString(), $end_month->toDateTimeString()]);
                    })
                    ->selectRaw("count(epks.id) as total, epks.status as status, DATE_FORMAT(epks.created_at, '%Y-%m') AS column_date")
                    ->groupBy(['column_date'])
                    ->get();
                 
        $statusOption = self::getStatusOption();
        $data_status = [
            'categories' => [],
            'data' => [],
        ];
        $data_monthly = [
            'categories' => [],
            'data' => [],
        ];
        
        foreach($statusOption as $key => $value) {
            if($key > 0) {
                $item = $models->filter(function($carry) use($key)
                {
                    return $carry->status == $key;
                });
                if(empty($item->first())) {
                    array_push($data_status['data'], 0);
                } else {
                    array_push($data_status['data'], $item->first()->total);
                }
                array_push($data_status['categories'], $value);
            }
        }
        
        if(!empty($request['date_from'])) {
            $start_month = Carbon::createFromFormat('Y-m-d', $request['date_from'])->startOfMonth();
        }
        if(!empty($request['date_to'])) {
            $end_month = Carbon::createFromFormat('Y-m-d', $request['date_to'])->endOfMonth();
        }
        for($i = $start_month; $i <= $end_month; $i->addMonth(1)) {
            $date = $i->format('Y-m');
            $item = $models1->filter(function($carry) use($date)
            {
                return $carry->column_date == $date;
            });
            if(empty($item->first())) {
                array_push($data_monthly['data'], 0);
            } else {
                array_push($data_monthly['data'], $item->first()->total);
            }
            array_push($data_monthly['categories'], $date);
        }

        return [
            'data_status' => $data_status,
            'data_monthly' => $data_monthly,
        ];
    }
}