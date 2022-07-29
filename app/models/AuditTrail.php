<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuditTrail extends Eloquent {
    protected $table = 'audit_trail';

    protected $fillable = [
        'file_id',
        'strata_id',
        'company_id',
        'module',
        'remarks',
        'agent',
        'audit_by'
    ];
    
    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'audit_by');
    }

    public function scopeself(Builder $builder) {
        $builder = $builder
                    ->join('users', 'audit_trail.audit_by', '=', 'users.id')
                    ->join('role', 'users.role', '=', 'role.id')
                    ->leftjoin('company', 'audit_trail.company_id', '=', 'company.id')
                    ->leftjoin('files', 'audit_trail.file_id', '=', 'files.id')
                    // ->leftjoin('strata', 'strata.file_id', '=', 'files.id')
                    ;
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $builder = $builder->where('users.file_id', Auth::user()->file_id)
                                ->where('users.company_id', Auth::user()->company_id)
                                ->where('audit_trail.audit_by', Auth::user()->id);
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

    public static function getAnalyticData($request = []) {
        $query = self::self()
                    ->where('role.name', Role::JMB)
                    // ->where('audit_trail.module', '!=', 'System Administration')
                    // ->where('audit_trail.file_id', '!=', 0)
                    // ->where('audit_trail.company_id', '!=', 0)
                    ->where(function($query) use($request){
                        if(!empty($request['company_id'])) {
                            $company_id = $request['company_id'];
                            if(is_array($request['company_id'])) {
                                $company_id = Company::where('short_name', $request['company_id'][0])->first()->getKey();
                            }
                            $query->where('users.company_id', $company_id);
                        }
                        if(!empty($request['role_id'])) {
                            $query->where('users.role', $request['role_id']);
                        }
                        if(!empty($request['module'])) {
                            $query->where('audit_trail.module', $request['module']);
                        }
                        if(!empty($request['description'])) {
                            $query->where('audit_trail.remarks', "LIKE", "%". $request['description'] ."%");
                        }
                        if(!empty($request['file_id'])) {
                            $query->where('users.file_id', $request['file_id']);
                        }
                        // if(!empty($request['strata'])) {
                        //     $query->where('strata.id', $request['strata']);
                        // }
                        if(!empty($request['date_from']) && empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $query->where('audit_trail.created_at', '>=', $date_from);
                        }
                        if(!empty($request['date_to']) && empty($request['date_from'])) {
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->where('audit_trail.created_at', '<=', $date_to . " 23:59:59");
                        }
                        if(!empty($request['date_from']) && !empty($request['date_to'])) {
                            $date_from = date('Y-m-d H:i:s', strtotime($request['date_from']));
                            $date_to = date('Y-m-d', strtotime($request['date_to']));
                            $query->whereBetween('audit_trail.created_at', [$date_from, $date_to . ' 23:59:59']);
                        }
                    });
        // $total_files = $query
        //                 ->selectRaw('count(DISTINCT(audit_trail.file_id)) as total, audit_trail.company_id')
        //                 ->groupBy(['audit_trail.company_id'])
        //                 ->get();
        // $total_jmb = $query
        //                 ->selectRaw('count(DISTINCT(audit_trail.audit_by)) as total, audit_trail.company_id')
        //                 ->groupBy(['audit_trail.company_id'])
        //                 ->get();
        $total_files = $query
                        ->selectRaw('count(DISTINCT(users.file_id)) as total, users.company_id')
                        ->groupBy(['users.company_id'])
                        ->get();
        $total_jmb = $query
                        ->selectRaw('count(DISTINCT(audit_trail.audit_by)) as total, users.company_id')
                        ->groupBy(['users.company_id'])
                        ->get();
        $cobs = Company::self()
                        ->where('is_main', 0)
                        ->where(function($query) use($request) {
                            if(!empty($request['company_id'])) {
                                $query->where('id', $request['company_id']);
                            }
                        })
                        ->get();
        $data_files = [
            'categories' => [],
            'data' => [],
        ];
        $data_jmb = [
            'categories' => [],
            'data' => [],
        ];
        foreach($cobs as $key => $cob) {
            $total_j = 0;
            $total_f = 0;
            foreach($total_files as $file) {
                if($cob->id == $file->company_id) {
                    $total_f = $file->total;
                }
            }
            foreach($total_jmb as $jmb) {
                if($cob->id == $jmb->company_id) {
                    $total_j = $jmb->total;
                }
            }
            array_push($data_files['categories'], [$cob->short_name]);
            array_push($data_files['data'], [$total_f]);
            array_push($data_jmb['categories'], [$cob->short_name]);
            array_push($data_jmb['data'], [$total_j]);
        }
        
        return [
            'data_files' => $data_files,
            'data_jmb' => $data_jmb,
        ];
    }
}