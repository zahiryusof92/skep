<?php

class OtherDetails extends Eloquent {

    protected $table = 'others_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
    ];
    
    public function draft() {
        return $this->hasOne('OtherDetailsDraft', 'reference_id');
    }

    public static function getOtherData($request = []) {
        $query = self::join('files', 'others_details.file_id','=','files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->where('files.is_deleted', 0);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, others_details.file_id, files.file_no, others_details.malay_composition as malay,
                                    others_details.chinese_composition as chinese, others_details.indian_composition as indian,
                                    others_details.others_composition as others, others_details.foreigner_composition as foreigner,
                                    others_details.created_at')
                    ->get();
        return $items;
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files','others_details.file_id','=','files.id')
                      ->join('company','files.company_id','=','company.id')
                      ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, sum(others_details.owner_occupied) as total_owner,
                            sum(others_details.rented) as total_rented, sum(others_details.bantuan_lphs) as total_bantuan_lphs,
                            sum(others_details.bantuan_others) as total_bantuan_others, 
                            sum(others_details.malay_composition) as total_malay, sum(others_details.chinese_composition) as total_chinese,
                            sum(others_details.indian_composition) as total_indian, sum(others_details.others_composition) as total_others,
                            sum(others_details.foreigner_composition) as total_foreigner')
                      ->groupBy('company.short_name')
                      ->get();
        $others_details_area_data = [];
        $others_details_pie_data = [
            'malay' => 0,
            'chinese' => 0,
            'indian' => 0,
            'others' => 0,
            'foreigner' => 0,
        ];
        $others_type = Config::get('constant.analytic.others');
        foreach($items as $key => $item) {
            /** Data */
            $new_area_data = [
                'name' => $item->short_name,  
                'data' => [
                    intval($item->total_owner), intval($item->total_rented), intval($item->total_bantuan_lphs), intval($item->total_bantuan_others), 
                    (intval($item->total_malay) + intval($item->total_chinese) + intval($item->total_indian) + intval($item->total_others) + intval($item->total_foreigner))
                ]
            ];
            $others_details_pie_data['malay'] += intval($item->total_malay);
            $others_details_pie_data['chinese'] += intval($item->total_chinese);
            $others_details_pie_data['indian'] += intval($item->total_indian);
            $others_details_pie_data['others'] += intval($item->total_others);
            $others_details_pie_data['foreigner'] += intval($item->total_foreigner);
            
            array_push($others_details_area_data, $new_area_data);
        }
        
        $others_details_pie_data = array(
            ['name' => 'Malay', 'slug' => 'malay', 'y' => $others_details_pie_data['malay']],
            ['name' => 'Chinese', 'slug' => 'chinese', 'y' => $others_details_pie_data['chinese']],
            ['name' => 'Indian', 'slug' => 'indian', 'y' => $others_details_pie_data['indian']],
            ['name' => 'Others', 'slug' => 'others', 'y' => $others_details_pie_data['others']],
            ['name' => 'Foreigner', 'slug' => 'foreigner', 'y' => $others_details_pie_data['foreigner']],
            
        );
        $result = array(
            'other_area' => $others_type['area'],
            'others_details_area_data' => $others_details_area_data,
            'others_details_pie_data' => $others_details_pie_data,
        );
        
        return $result;
    }

}
