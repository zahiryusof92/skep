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
}
