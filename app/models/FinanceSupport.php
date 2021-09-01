<?php

class FinanceSupport extends Eloquent {
    protected $table = 'finance_support';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public static function getSupportData($request = []) {
        $query = self::join('files', 'finance_support.file_id','=','files.id')
                    ->leftjoin('company', 'finance_support.company_id','=','company.id')
                    ->where('files.is_deleted', 0)
                    ->where('finance_support.is_deleted', 0)
                    ->where('finance_support.is_active', 1);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, finance_support.file_id, files.file_no, finance_support.name,
                                    finance_support.amount, finance_support.is_active,
                                    finance_support.created_at')
                    ->get();
        return $items;
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('files.is_deleted', 0)
                ->where('finance_support.is_deleted', 0)
                ->where('finance_support.is_active', 1);
        };

        $query = self::join('files','finance_support.file_id','=','files.id')
                      ->join('company','files.company_id','=','company.id')
                      ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, count(finance_support.id) as total_id')
                      ->groupBy('company.short_name')
                      ->get();

        $finance_support_data = [];
        foreach($items as $key => $item) {
            /** Data */
            $new_finance_support_data = [
                'name' => $item->short_name,  
                'y' => $item->total_id
            ];
            array_push($finance_support_data, $new_finance_support_data);
        }
        $result = array(
            'data' => $finance_support_data,
        );
        
        return $result;
    }
}