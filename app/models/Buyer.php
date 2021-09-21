<?php

class Buyer extends Eloquent {

    protected $table = 'buyer';

    public function file() {
        return $this->belongsTo('Files', 'file_id');
    }

    public function race() {
        return $this->belongsTo('Race', 'race_id');
    }

    public function nationality() {
        return $this->belongsTo('Nationality', 'nationality_id');
    }

    public static function unitNoList($file_id) {
        $model = self::where('file_id', $file_id)->where('is_deleted', 0)->orderBy('unit_no', 'asc')->lists('unit_no', 'id');

        return $model;
    }

    public static function getCompositionByRace($file_id, $race) {
        $race = Race::where('name_en',$race)->where('is_deleted',0)->first();
        if($race) {
            $total = self::where('file_id',$file_id)->where('is_deleted',0)->where('race_id', $race->id)->count();
        } else {
            $total = 0;
        }

        return $total;
    }

    public static function getForeignerComposition($file_id) {
        $n_ids = Nationality::whereNotIn('name',['Malaysian','Malaysia'])->lists('id');
        $total = self::where('file_id',$file_id)->where('is_deleted',0)->whereIn('nationality_id', $n_ids)->count();
        
        return $total;
    }

    public static function getBuyersData($request = []) {
        $query = self::join('files', 'buyer.file_id','=','files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('parliment', 'strata.parliament','=','parliment.id')
                    ->leftjoin('dun', 'strata.dun','=','dun.id')
                    ->leftjoin('park', 'strata.park','=','park.id')
                    ->leftjoin('race', 'buyer.race_id','=','race.id')
                    ->leftjoin('nationality', 'buyer.nationality_id','=','nationality.id')
                    ->where('buyer.is_deleted', 0)
                    ->where('files.is_deleted', 0);
                    // ->where('parliment.is_deleted', 0)
                    // ->where('dun.is_deleted', 0)
                    // ->where('park.is_deleted', 0);
                    
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        if(!empty($request['search'])) {
            $query = $query->where('files.file_no', 'like', '%'. $request['search'] .'%')
                           ->orWhere('company.short_name', 'like', '%'. $request['search'] .'%')
                           ->orWhere('buyer.unit_no', 'like', '%'. $request['search'] .'%')
                           ->orWhere('buyer.owner_name', 'like', '%'. $request['search'] .'%')
                           ->orWhere('buyer.created_at', 'like', '%'. $request['search'] .'%');
        }
        $items = $query->selectRaw('company.short_name, buyer.file_id, files.file_no, buyer.unit_no,
                    buyer.created_at, buyer.owner_name, race.name_en as race, nationality.name as nationality,
                    parliment.description as parliment, dun.description as dun, park.description as park')
                    // ->limit(500)
                    ;
        return $items;
    }

    public static function getBuyerByParlimentAnaylticData($request = []) {
        $query = self::join('files', 'buyer.file_id', '=', 'files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('parliment', 'strata.parliament','=','parliment.id')
                    ->where('files.is_deleted', 0)
                    ->where('buyer.is_deleted', 0);
                    // ->where('parliment.is_deleted', 0)
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('parliment.description as name, count(buyer.id) as total_owner')
                    ->groupBy('parliment.description')
                    ->get();
        
        return $items;
    }

    public static function getBuyerByDunAnaylticData($request = []) {
        $query = self::join('files', 'buyer.file_id', '=', 'files.id')
                    ->leftjoin('company', 'files.company_id','=','company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('dun', 'strata.parliament','=','dun.id')
                    ->where('files.is_deleted', 0)
                    ->where('buyer.is_deleted', 0);
                    // ->where('dun.is_deleted', 0)
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('dun.description as name, count(buyer.id) as total_owner')
                    ->groupBy('dun.description')
                    ->get();
        
        return $items;
    }

    public static function getBuyerByParkAnaylticData($request = []) {
        $query = self::join('files', 'buyer.file_id', '=', 'files.id')
                    ->leftjoin('company','files.company_id', '=', 'company.id')
                    ->leftjoin('strata', 'strata.file_id','=','files.id')
                    ->leftjoin('park', 'strata.parliament','=','park.id')
                    ->where('files.is_deleted', 0)
                    ->where('buyer.is_deleted', 0);
                    // ->where('park.is_deleted', 0)
                    
        if(!empty($request['cob'])) {
            $query = $query->where('company.short_name',$request['cob']);
        }
        $items = $query->selectRaw('park.description as name, count(buyer.id) as total_owner')
                        ->groupBy('park.description')
                        ->get();
        
        return $items;
    }

}
