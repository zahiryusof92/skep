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

}
