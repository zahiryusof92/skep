<?php

class Management extends Eloquent {

    protected $table = 'management';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'is_jmb',
        'is_mc',
        'is_agent',
        'is_others',
        'is_developer',
        'no_management',
        'start',
        'end',
        'liquidator',
        'under_10_units',
        'under_10_units_remarks',
        'bankruptcy',
        'bankruptcy_remarks',
    ];

    public function developer() {
        return $this->hasOne('ManagementDeveloper', 'management_id');
    }

    public function jmb() {
        return $this->hasOne('ManagementJMB', 'management_id');
    }

    public function mc() {
        return $this->hasOne('ManagementMC', 'management_id');
    }
    
    public function agent() {
        return $this->hasOne('ManagementAgent', 'management_id');
    }

    public function others() {
        return $this->hasOne('ManagementOthers', 'management_id');
    }

    public function liquidators() {
        return $this->hasMany('ManagementLiquidator', 'management_id');
    }

    public function draft() {
        return $this->hasOne('ManagementDraft', 'reference_id');
    }

    public static function getManagementData($request = []) {
        $query = self::join('files', 'management.file_id','=','files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->where('files.is_deleted', 0);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, management.file_id, files.file_no, management.is_jmb,
                                    management.is_mc, management.is_agent, management.is_others, management.is_developer,
                                    management.created_at')
                    ->get();
        return $items;
    }

    public static function getAnalyticData($request = []) {
        $active = function ($query) {
            $query->where('files.is_deleted', 0);
        };

        $query = self::join('files','management.file_id','=','files.id')
                      ->join('company','files.company_id','=','company.id')
                      ->where($active);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, count(management.id) as total_id, sum(management.is_jmb) as total_jmb,
                            sum(management.is_mc) as total_mc, sum(management.is_agent) as total_agent,
                            sum(management.is_others) as total_others, sum(management.is_developer) as total_developer')
                      ->groupBy('company.short_name')
                      ->get();

        $management_data = [];
        $management_type = Config::get('constant.analytic.management_type');
        foreach($items as $key => $item) {
            /** Data */
            $new_management_data = [
                'name' => $item->short_name,  
                'data' => [
                    intval($item->total_developer), intval($item->total_jmb), intval($item->total_mc), intval($item->total_agent),
                    intval($item->total_others)
                ]
            ];
            array_push($management_data, $new_management_data);
        }
        $result = array(
            'management_type' => $management_type,
            'management_data' => $management_data,
        );
        
        return $result;
    }

}
