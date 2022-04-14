<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Strata extends Eloquent {

    protected $table = 'strata';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id'
    ];

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function parliment() {
        return $this->belongsTo('Parliment', 'parliament');
    }

    public function duns() {
        return $this->belongsTo('Dun', 'dun');
    }

    public function parks() {
        return $this->belongsTo('Park', 'park');
    }

    public function countries() {
        return $this->belongsTo('Country', 'country');
    }

    public function states() {
        return $this->belongsTo('State', 'state');
    }

    public function cities() {
        return $this->belongsTo('City', 'city');
    }

    public function towns() {
        return $this->belongsTo('City', 'town');
    }

    public function areas() {
        return $this->belongsTo('Area', 'area');
    }

    public function areaUnit() {
        return $this->belongsTo('UnitMeasure', 'land_area_unit');
    }

    public function landTitle() {
        return $this->belongsTo('LandTitle', 'land_title');
    }

    public function categories() {
        return $this->belongsTo('Category', 'category');
    }

    public function perimeters() {
        return $this->belongsTo('Perimeter', 'perimeter');
    }

    public function strataName() {
        if ($this->name) {
            return $this->name;
        }

        return "(Not Set)";
    }
    
    public function draft() {
        return $this->hasOne('StrataDraft', 'reference_id');
    }
    
    public function residential() {
        return $this->hasOne('Residential', 'strata_id')->latest();
    }
    
    public function commercial() {
        return $this->hasOne('Commercial', 'strata_id')->latest();
    }
    
    public function residentialExtra() {
        return $this->hasMany('ResidentialExtra', 'strata_id');
    }
    
    public function commercialExtra() {
        return $this->hasMany('CommercialExtra', 'strata_id');
    }
    
    public function facility() {
        return $this->hasOne('Facility', 'strata_id');
    }

    public function scopeSelf(Builder $builder) {
        $builder = $builder
                    ->join('files', 'strata.file_id', '=', 'files.id');
        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $builder = $builder->where('strata.file_id', Auth::user()->file_id);
            } else {
                $file_ids = array_pluck(Files::file()->get()->toArray(), 'id');
                $builder = $builder->whereIn('strata.file_id', $file_ids);
            }
        }
        return $builder;
    }

    public static function getStratasData($request = []) {
        $query = self::join('files', 'strata.file_id','=','files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('parliment', 'strata.parliament','=','parliment.id')
                    ->leftjoin('dun', 'strata.dun','=','dun.id')
                    ->leftjoin('park', 'strata.park','=','park.id')
                    ->leftjoin('city', 'strata.city','=','city.id')
                    ->leftjoin('state', 'strata.state','=','state.id')
                    ->where('files.is_deleted', 0);
                    // ->where('parliment.is_deleted', 0)
                    // ->where('dun.is_deleted', 0)
                    // ->where('park.is_deleted', 0)
                    
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, strata.file_id, files.file_no,
                    strata.created_at, strata.name as name, city.description as city, state.name as state,
                    parliment.description as parliment, dun.description as dun, park.description as park')
                    ->get();
                    
        return $items;
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files','strata.file_id','=','files.id')
                      ->join('company','files.company_id','=','company.id')
                      ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $stratas = $query->selectRaw('company.short_name, count(strata.id) as total_id')
                      ->groupBy('company.short_name')
                      ->get();
                          
        $query1 = self::join('files','strata.file_id','=','files.id')
                            ->join('company','files.company_id','=','company.id')
                            ->leftjoin('residential_block','residential_block.strata_id','=','strata.id')
                            ->leftjoin('residential_block_extra','residential_block_extra.strata_id','=','strata.id')
                            ->leftjoin('commercial_block','commercial_block.strata_id','=','strata.id')
                            ->leftjoin('commercial_block_extra','commercial_block_extra.strata_id','=','strata.id')
                            ->where($active);
        if(!empty($request['cob'])) {
            $query1 = $query1->where('company.short_name',$request['cob']);
        }
        $strata_summary = $query1->selectRaw('company.short_name, sum(residential_block.unit_no) as total_unit_residential_block, sum(residential_block_extra.unit_no) as total_unit_residential_block_extra,
                                sum(residential_block.maintenance_fee) as residential_block_mf, sum(residential_block_extra.maintenance_fee) as residential_block_extra_mf,
                                sum(residential_block.sinking_fund) as residential_block_sf, sum(residential_block_extra.sinking_fund) as residential_block_extra_sf,
                                sum(commercial_block.unit_no) as total_unit_commercial_block, sum(commercial_block_extra.unit_no) as total_unit_commercial_block_extra,
                                sum(commercial_block.maintenance_fee) as commercial_block_mf, sum(commercial_block_extra.maintenance_fee) as commercial_block_extra_mf,
                                sum(commercial_block.sinking_fund) as commercial_block_sf, sum(commercial_block_extra.sinking_fund) as commercial_block_extra_sf'
                            )
                            ->groupBy('company.short_name')
                            ->get();
        $query2 =  self::join('files','strata.file_id','=','files.id')
                            ->join('company','files.company_id','=','company.id')
                            ->leftjoin('facility','facility.strata_id','=','strata.id')
                            ->where($active);
        if(!empty($request['cob'])) {
            $query2 = $query2->where('company.short_name',$request['cob']);
        }
        $strata_facility = $query2->selectRaw('company.short_name, sum(facility.management_office_unit) as total_management_office_unit, sum(facility.swimming_pool_unit) as total_swimming_pool_unit,
                                    sum(facility.surau_unit) as total_surau_unit, sum(facility.multipurpose_hall_unit) as total_multipurpose_hall_unit,
                                    sum(facility.gym_unit) as total_gym_unit, sum(facility.playground_unit) as total_playground_unit, sum(facility.guardhouse_unit) as total_guardhouse_unit,
                                    sum(facility.kindergarten_unit) as total_kindergarten_unit, sum(facility.open_space_unit) as total_open_space_unit,
                                    sum(facility.lift_unit) as total_lift_unit, sum(facility.rubbish_room_unit) as total_rubbish_room_unit,
                                    sum(facility.gated_unit) as total_gated_unit'
                            )
                            ->groupBy('company.short_name')
                            ->get();
                                        
        $strata_data = [];
        $strata_name = [];
        $strata_chart_data = [];
        $facility_data = [];
        $category_data = [
            'rd_unit' => [],
            'cm_unit' => [],
            'rd_mf' => [],
            'rd_sf' => [],
            'cm_mf' => [],
            'cm_sf' => [],
        ];
        $categories = Config::get('constant.analytic.strata_categories');
        $facility_type = Config::get('constant.analytic.facility_type');
        $i = 1;
        $ii = 0.3;
        foreach($stratas as $key => $strata) {
            $data = ['name' => $strata->short_name,'slug' => $strata->short_name, 'y' => $strata->total_id];
            array_push($strata_data, $data);
            array_push($strata_name, $strata->short_name);
            /** MF / SF Data */
            if(in_array($strata->short_name, array_pluck($strata_summary,'short_name'))) {
                $array = array_first($strata_summary, function($key, $value) use ($strata)
                {
                    return $value->short_name === $strata->short_name;
                });
                
                array_push($category_data['rd_unit'], [(intval($array->total_unit_residential_block, 19) + intval($array->total_unit_residential_block_extra, 19))]);
                array_push($category_data['cm_unit'], [(intval($array->total_unit_commercial_block, 19) + intval($array->total_unit_commercial_block_extra, 19))]);
                array_push($category_data['rd_mf'], [(floatval($array->residential_block_mf) + floatval($array->residential_block_extra_mf))]);
                array_push($category_data['rd_sf'], [(floatval($array->residential_block_sf) + floatval($array->residential_block_extra_sf))]);
                array_push($category_data['cm_mf'], [(floatval($array->commercial_block_mf) + floatval($array->commercial_block_extra_mf))]);
                array_push($category_data['cm_sf'], [(floatval($array->commercial_block_sf) + floatval($array->commercial_block_extra_sf))]);
            }
            /** Rearrange Facility Data */
            if(in_array($strata->short_name, array_pluck($strata_facility,'short_name'))) {
                $array = array_first($strata_facility, function($key, $value) use ($strata)
                {
                    return $value->short_name === $strata->short_name;
                });
                $new_facility_data = [
                    'name' => $strata->short_name,
                    'data' => [
                        intval($array->total_management_office_unit,19), intval($array->total_swimming_pool_unit,19), intval($array->total_surau_unit,19),
                        intval($array->total_multipurpose_hall_unit,19), intval($array->total_gym_unit,19), intval($array->total_playground_unit,19),
                        intval($array->total_guardhouse_unit,19), intval($array->total_kindergarten_unit,19), intval($array->total_open_space_unit,19),
                        intval($array->total_lift_unit,19), intval($array->total_rubbish_room_unit,19), intval($array->total_gated_unit,19)
                    ]
                ];
                array_push($facility_data, $new_facility_data);
            }
        }

        /** MF / SF Report */
        foreach($categories as $key => $val) {
            if(strpos($key, '_mf') || strpos($key, '_sf')) {
                $new_strata_chart_data = [
                    'name' => $val,
                    'color' => "rgba(220,". $i .",40,". $i .")",
                    'data' => $category_data[$key],
                    'tooltip' => [
                        'valuePrefix' => "MYR"
                    ],
                    'pointPadding' =>  $ii,
                    'pointPlacement' => 0.2,
                    'yAxis' => 1
                ];
            } else {
                $new_strata_chart_data = [
                    'name' => $val,
                    'color' => "rgba(220,". $i .",50,". $i .")",
                    'data' => $category_data[$key],
                    'pointPadding' =>  $ii,
                    'pointPlacement' => -0.2,
                ];
            }
            array_push($strata_chart_data, $new_strata_chart_data);
            $i += 100;
            $ii += 0.1;
        }

        $result = array(
            'strata_data' => $strata_data,
            'strata_name' => $strata_name,
            'strata_chart_data' => $strata_chart_data,
            'facility_type' => $facility_type,
            'facility_data' => $facility_data,
        );
        
        return $result;
    }

}
