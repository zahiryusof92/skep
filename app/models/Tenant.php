<?php

class Tenant extends Eloquent {

    protected $table = 'tenant';
    
    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function race() {
        return $this->belongsTo('Race', 'race_id');
    }

    public static function getTenantsData($request = []) {
        $query = self::join('files', 'tenant.file_id','=','files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('parliment', 'strata.parliament','=','parliment.id')
                    ->leftjoin('dun', 'strata.dun','=','dun.id')
                    ->leftjoin('park', 'strata.park','=','park.id')
                    ->leftjoin('race', 'tenant.race_id','=','race.id')
                    ->leftjoin('nationality', 'tenant.nationality_id','=','nationality.id')
                    ->where('tenant.is_deleted', 0)
                    ->where('files.is_deleted', 0)
                    ->where('parliment.is_deleted', 0)
                    ->where('dun.is_deleted', 0)
                    ->where('park.is_deleted', 0);
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('company.short_name, tenant.file_id, files.file_no, tenant.unit_no,
                    tenant.created_at, tenant.tenant_name as full_name, race.name_en as race, nationality.name as nationality,
                    parliment.description as parliment, dun.description as dun, park.description as park')
                    ->get();
        return $items;
    }

    public static function getTenantByParlimentAnaylticData($request = []) {
        $query = self::join('files', 'tenant.file_id', '=', 'files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('parliment', 'strata.parliament','=','parliment.id')
                    ->where('files.is_deleted', 0)
                    ->where('tenant.is_deleted', 0);
                    // ->where('parliment.is_deleted', 0)                    
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('parliment.description as name, count(tenant.id) as total_tenant')
                    ->groupBy('parliment.description')
                    ->get();
        
        return $items;
    }

    public static function getTenantByDunAnaylticData($request = []) {
        $query = self::join('files', 'tenant.file_id', '=', 'files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('dun', 'strata.parliament','=','dun.id')
                    ->where('files.is_deleted', 0)
                    ->where('tenant.is_deleted', 0);
                    // ->where('dun.is_deleted', 0)
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('dun.description as name, count(tenant.id) as total_tenant')
                    ->groupBy('dun.description')
                    ->get();
        
        return $items;
    }

    public static function getTenantByParkAnaylticData($request = []) {
        $query = self::join('files', 'tenant.file_id', '=', 'files.id')
                    ->leftjoin('company','files.company_id', '=', 'company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('park', 'strata.parliament','=','park.id')
                    ->where('files.is_deleted', 0)
                    ->where('tenant.is_deleted', 0);
                    // ->where('park.is_deleted', 0)
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('park.description as name, count(tenant.id) as total_tenant')
                    ->groupBy('park.description')
                    ->get();
        
        return $items;
    }

}
