<?php

class Finance extends Eloquent {

    protected $table = 'finance_file';

    public static function monthList() {
        $month = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MAY',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AUG',
            '09' => 'SEP',
            '10' => 'OCT',
            '11' => 'NOV',
            '12' => 'DEC'
        ];

        return $month;
    }

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function financeSummary() {
        return $this->hasMany('FinanceSummary', 'finance_file_id');
    }

    public function financeAdmin() {
        return $this->hasMany('FinanceAdmin', 'finance_file_id');
    }

    public function financeCheck() {
        return $this->hasMany('FinanceCheck', 'finance_file_id');
    }

    public function financeContract() {
        return $this->hasMany('FinanceContract', 'finance_file_id');
    }

    public function financeIncome() {
        return $this->hasMany('FinanceIncome', 'finance_file_id');
    }

    public function financeRepair() {
        return $this->hasMany('FinanceRepair', 'finance_file_id');
    }

    public function financeReportPerbelanjaan() {
        return $this->hasMany('FinanceReportPerbelanjaan', 'finance_file_id');
    }

    public function financeReport() {
        return $this->hasMany('FinanceReport', 'finance_file_id');
    }

    public function financeReportExtra() {
        return $this->hasMany('FinanceReportExtra', 'finance_file_id');
    }

    public function financeStaff() {
        return $this->hasMany('FinanceStaff', 'finance_file_id');
    }

    public function financeUtility() {
        return $this->hasMany('FinanceUtility', 'finance_file_id');
    }

    public function financeVandal() {
        return $this->hasMany('FinanceVandal', 'finance_file_id');
    }

    public function monthName() {
        if ($this->month) {
            $dateObj = DateTime::createFromFormat('!m', $this->month);
            $monthName = $dateObj->format('M'); // March

            return strtoupper($monthName);
        } else {
            return "<i>(not set)</i>";
        }
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public static function getAdminAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_admin','finance_file_admin.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name,
                    sum(finance_file_admin.tunggakan) as tunggakan, sum(finance_file_admin.semasa) as semasa, 
                    sum(finance_file_admin.hadapan) as hadapan, sum(finance_file_admin.tertunggak) as tertunggak')
                    ->groupBy('company.short_name')
                    ->get();
        $finance_categories = Config::get('constant.analytic.finance.file.four');
        $data = [];
        foreach($items as $item) {
            $new_data = [
                'name' => $item->short_name,  
                'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                            (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                            floatval($item->tertunggak)]
            ];
            array_push($data, $new_data);
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'data' => $data,
        );
        
        return $result;
    }

    public static function getContractAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_contract','finance_file_contract.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name,
                    sum(finance_file_contract.tunggakan) as tunggakan, sum(finance_file_contract.semasa) as semasa, 
                    sum(finance_file_contract.hadapan) as hadapan, sum(finance_file_contract.tertunggak) as tertunggak')
                    ->groupBy('company.short_name')
                    ->get();
        $finance_categories = Config::get('constant.analytic.finance.file.four');
        $data = [];
        foreach($items as $item) {
            $new_data = [
                'name' => $item->short_name,  
                'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                            (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                            floatval($item->tertunggak)]
            ];
            array_push($data, $new_data);
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'data' => $data,
        );
        
        return $result;
    }

    public static function getIncomeAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_income','finance_file_income.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name,
                    sum(finance_file_income.tunggakan) as tunggakan, sum(finance_file_income.semasa) as semasa, sum(finance_file_income.hadapan) as hadapan')
                    ->groupBy('company.short_name')
                    ->get();
        $finance_categories = Config::get('constant.analytic.finance.file.three');
        $data = [];
        foreach($items as $item) {
            $new_data = [
                'name' => $item->short_name,  
                'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                            (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan))]
            ];
            array_push($data, $new_data);
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'data' => $data,
        );
        
        return $result;
    }

    public static function getRepairAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_repair','finance_file_repair.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, finance_file_repair.type,
                    sum(finance_file_repair.tunggakan) as tunggakan, sum(finance_file_repair.semasa) as semasa, 
                    sum(finance_file_repair.hadapan) as hadapan, sum(finance_file_repair.tertunggak) as tertunggak')
                    ->groupBy('finance_file_repair.type','company.short_name')
                    ->get();
                    
        $finance_categories = Config::get('constant.analytic.finance.file.four');
        $mf_data = $sf_data = [];
        foreach($items as $item) {
            if($item->type == "MF") {
                $new_mf_data = [
                    'name' => $item->short_name,  
                    'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                                (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                                floatval($item->tertunggak)]
                ];
                array_push($mf_data, $new_mf_data);
            } else {
                $new_sf_data = [
                    'name' => $item->short_name,  
                    'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                                (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                                floatval($item->tertunggak)]
                ];
                array_push($sf_data, $new_sf_data);
            }
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'mf_data' => $mf_data,
            'sf_data' => $sf_data,
        );
        
        return $result;
    }

    public static function getReportAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_report','finance_file_report.finance_file_id', '=', 'finance_file.id')
                    ->leftjoin('finance_file_report_extra','finance_file_report_extra.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, finance_file_report.type,
                    sum(finance_file_report.fee_sebulan) as sebulan, sum(finance_file_report.fee_semasa) as semasa, 
                    sum(finance_file_report.baki_bank_akhir) as baki_bank_akhir, sum(finance_file_report.baki_bank_awal) as baki_bank_awal,
                    sum(finance_file_report.fee_sebulan) as extra_sebulan, sum(finance_file_report.fee_semasa) as extra_semasa')
                    ->groupBy('finance_file_report.type', 'company.short_name')
                    ->get();
        $finance_categories = Config::get('constant.analytic.finance.file.six');
        $mf_data = $sf_data = [];
        foreach($items as $item) {
            if($item->type == "MF") {
                $new_mf_data = [
                    'name' => $item->short_name,  
                    'data' => [floatval($item->sebulan), floatval($item->semasa), floatval($item->baki_bank_akhir),
                                floatval($item->baki_bank_awal), floatval($item->extra_sebulan), floatval($item->extra_semasa)]
                ];
                array_push($mf_data, $new_mf_data);
            } else {
                $new_sf_data = [
                    'name' => $item->short_name,  
                    'data' => [floatval($item->sebulan), floatval($item->semasa), floatval($item->baki_bank_akhir),
                                floatval($item->baki_bank_awal), floatval($item->extra_sebulan), floatval($item->extra_semasa)]
                ];
                array_push($sf_data, $new_sf_data);
            }
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'mf_data' => $mf_data,
            'sf_data' => $sf_data,
        );
        
        return $result;
    }

    public static function getStaffAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_staff','finance_file_staff.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, 
                    sum(finance_file_staff.tunggakan) as tunggakan, sum(finance_file_staff.semasa) as semasa, 
                    sum(finance_file_staff.hadapan) as hadapan, sum(finance_file_staff.tertunggak) as tertunggak')
                    ->groupBy('company.short_name')
                    ->get();
                    
        $finance_categories = Config::get('constant.analytic.finance.file.five');
        $data = [];
        foreach($items as $item) {
            $new_data = [
                'name' => $item->short_name,  
                'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                            (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                            floatval($item->tertunggak)]
            ];
            array_push($data, $new_data);
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'data' => $data,
        );
        
        return $result;
    }

    public static function getUtilityAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_utility','finance_file_utility.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name,
                    sum(finance_file_utility.tunggakan) as tunggakan, sum(finance_file_utility.semasa) as semasa, 
                    sum(finance_file_utility.hadapan) as hadapan, sum(finance_file_utility.tertunggak) as tertunggak')
                    ->groupBy('company.short_name')
                    ->get();
        $finance_categories = Config::get('constant.analytic.finance.file.four');
        $data = [];
        foreach($items as $item) {
            $new_data = [
                'name' => $item->short_name,  
                'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                            (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                            floatval($item->tertunggak)]
            ];
            array_push($data, $new_data);
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'data' => $data,
        );
        
        return $result;
    }

    public static function getVandalismeAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('finance_file.is_deleted', 0);
            $query->where('finance_file.is_active', 1);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'finance_file.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->join('finance_file_vandalisme','finance_file_vandalisme.finance_file_id', '=', 'finance_file.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, finance_file_vandalisme.type,
                    sum(finance_file_vandalisme.tunggakan) as tunggakan, sum(finance_file_vandalisme.semasa) as semasa, 
                    sum(finance_file_vandalisme.hadapan) as hadapan, sum(finance_file_vandalisme.tertunggak) as tertunggak')
                    ->groupBy('finance_file_vandalisme.type','company.short_name')
                    ->get();
                    
        $finance_categories = Config::get('constant.analytic.finance.file.four');
        $mf_data = $sf_data = [];
        foreach($items as $item) {
            if($item->type == "MF") {
                $new_mf_data = [
                    'name' => $item->short_name,  
                    'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                                (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                                floatval($item->tertunggak)]
                ];
                array_push($mf_data, $new_mf_data);
            } else {
                $new_sf_data = [
                    'name' => $item->short_name,  
                    'data' => [floatval($item->tunggakan), floatval($item->semasa), floatval($item->hadapan),
                                (floatval($item->tunggakan) + floatval($item->semasa) + floatval($item->hadapan)),
                                floatval($item->tertunggak)]
                ];
                array_push($sf_data, $new_sf_data);
            }
        }
        
        $result = array(
            'name' => array_values($finance_categories),
            'mf_data' => $mf_data,
            'sf_data' => $sf_data,
        );

        return $result;
    }

}
