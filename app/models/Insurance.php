<?php

class Insurance extends Eloquent {

    protected $table = 'insurance';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function provider() {
        return $this->belongsTo('InsuranceProvider', 'insurance_provider_id');
    }

    public static function getInsuranceData($request = []) {
        $query = self::join('files', 'insurance.file_id','=','files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('insurance_provider', 'insurance.insurance_provider_id','=','insurance_provider.id')
                    ->where('files.is_deleted', 0)
                    ->where('insurance.is_deleted', 0);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, insurance.file_id, files.file_no, insurance.created_at,
                                    insurance_provider.name as provider')
                    ->get();
        return $items;
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('insurance.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files', 'insurance.file_id', '=', 'files.id')
                    ->join('company','files.company_id', '=', 'company.id')
                    ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $insurances = $query->selectRaw('company.short_name, count(insurance.id) as total_insurance')
                    ->groupBy('company.short_name')
                    ->get();

        $insurance_pie_data = [];
        foreach($insurances as $insurance) {
            /** Data */
            $new_insurance_data = [
                'name' => $insurance->short_name,  
                'y' => intval($insurance->total_insurance)
            ];
            array_push($insurance_pie_data, $new_insurance_data);
        }
        
        $result = array(
            'insurance_pie_data' => $insurance_pie_data
        );
        
        return $result;
    }

}
