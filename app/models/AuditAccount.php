<?php

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuditAccount extends Eloquent {
    protected $table = 'audit_accounts';

    protected $fillable = [
        'file_id',
        'company_id',
        'parent_id',
        'name',
        'submission_date',
        'closing_date',
        'income_collection',
        'expense_collection',
        'filename',
        'is_deleted'
    ];

    public function scopeSelf() {
        $query = self::join('files', 'audit_accounts.file_id', '=', 'files.id')
                        ->select(['audit_accounts.*']);
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query = $query->where('audit_accounts.file_id', Auth::user()->file_id)
                                ->where('audit_accounts.company_id', Auth::user()->company_id);
            } else {
                $query = $query->where('audit_accounts.company_id', Auth::user()->company_id);
            }
        } else {
            if (!empty(Session::get('admin_cob'))) {
                $query = $query->where('audit_accounts.company_id', Session::get('admin_cob'));
            }
        }
        return $query->where('audit_accounts.is_deleted', false)
                        ->where('files.is_deleted', false)
                        ->where('parent_id', false);
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
    
    public static function getCollectionOptions() {
        $option = [
            '' => trans('- Please Select -'),
            'deficit' => trans('app.forms.deficit'),
            'surplus' => trans('app.forms.surplus')
        ];

        return $option;
    }

    public static function getAnalyticData($request = []) {
        $submission_date = Carbon::now()->startOfMonth()->subMonth(11);
        $closing_date = Carbon::now()->endOfMonth();

        $models = self::self()
                    ->selectRaw("count(audit_accounts.id) as total, audit_accounts.file_id as file_id, DATE_FORMAT(audit_accounts.submission_date, '%Y-%m') AS column_date")
                    ->where('audit_accounts.is_deleted', 0)
                    ->where(function($query) use($request, $submission_date, $closing_date) {
                        if(!empty($request['file_id'])) {
                            $query->whereIn('audit_accounts.file_id', $request['file_id']);
                        }
                        if(!empty($request['submission_date'])) {
                            $query->where('submission_date','>=', $request['submission_date']);
                        }
                        if(!empty($request['closing_date'])) {
                            $query->where('closing_date','<=', $request['closing_date']);
                        }
                    })
                    ->groupBy(['column_date'])
                    ->get();
                    
        $data_arr = [];
        $total_arr = [];
        $date_arr = new ArrayCollection();
        $start_month = Carbon::now()->startOfMonth()->subMonth(11);
        $end_month = Carbon::now()->endOfMonth();
        
        if(!empty($request['submission_date'])) {
            $start_month = Carbon::createFromFormat('Y-m-d', $request['submission_date'])->startOfMonth();
        }
        if(!empty($request['closing_date'])) {
            $end_month = Carbon::createFromFormat('Y-m-d', $request['closing_date'])->endOfMonth();
        }
        for($i = $start_month; $i <= $end_month; $i->addMonth(1)) {
            $date = $i->format('Y-m');
            $item = $models->filter(function($carry) use($date)
            {
                return $carry->column_date == $date;
            });
            if(empty($item->first())) {
                array_push($total_arr, 0);
            } else {
                array_push($total_arr, $item->first()->total);
            }
            
            if(!$date_arr->contains($date)) {
                $date_arr->add($date);
            }
        }
        
        array_push($data_arr,[
            'name' => trans('app.forms.monthly_audit_account'),
            'data' => $total_arr
        ]);
        
        return [
            'date_arr' => $date_arr->toArray(),
            'data_arr' => $data_arr
        ];
    }
}