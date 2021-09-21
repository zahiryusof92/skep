<?php

class InsuranceProvider extends Eloquent {
    protected $table = 'insurance_provider';
    
    public static function getTotalInsurance($provider, $cob_id = NULL) {
        $condition = function ($query) use ($provider) {
            $query->where('insurance_provider_id', $provider->id);
            $query->where('is_deleted', 0);
        };

        if ($cob_id) {
            $total = Files::where('company_id', $cob_id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->whereHas('insurance', $condition)
                    ->count();
        } else {
            $total = Files::where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->whereHas('insurance', $condition)
                    ->count();
        }

        return $total;
    }

    public static function getData() {
        $query = self::where('is_deleted', 0)
                    ->where('is_active', 1);
        $items = $query->selectRaw('name, is_active, created_at')
                    ->get();
        return $items;
    }

    public static function getAnalyticData() {
        $active = function ($query) {
            $query->where('insurance.is_deleted', 0);
            $query->where('files.is_deleted', 0);
        };

        $items = DB::table('insurance')
                    ->join('files', 'insurance.file_id', '=', 'files.id')
                    ->join('insurance_provider', 'insurance.insurance_provider_id','=','insurance_provider.id')
                    ->where($active)
                    ->selectRaw('insurance_provider.name, count(insurance.id) as total_insurance')
                    ->groupBy('insurance_provider.name')
                    ->get();

        $data = [];
        foreach($items as $item) {
            /** Data */
            $new_data = [
                'name' => $item->name,  
                'y' => intval($item->total_insurance)
            ];
            array_push($data, $new_data);
        }
        
        $result = array(
            'data' => $data
        );
        
        return $result;
    }
}
