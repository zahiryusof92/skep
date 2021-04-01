<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends BaseController {
    public function exportCouncilFacility() {
        $config_facilities = Config::get('constant.module.cob.facility');
        $facilities = Company::join('files','company.id','=','files.company_id')
                        ->join('facility','files.id','=','facility.file_id')
                        ->select(['files.file_no','facility.*'])
                        ->where('company.short_name','=',"MPAJ")
                        ->where('files.is_active', '!=', "2")
                        ->where('files.is_deleted','=',"0")
                        ->get();

        $data = [];
        foreach($facilities as $facility) {
            foreach($config_facilities as $key => $val) {
                
                if($facility->$key == true) {
                    if(empty($data[$key])) {
                        $data[$key]['title'] = $val['title'];
                        $data[$key]['total_facility'] = intval($facility[$key.'_unit']);
                        $data[$key]['total_files'] = 0;
                    } else {
                        $data[$key]['total_facility'] += intval($facility[$key.'_unit']);
                        $data[$key]['total_files'] += 1;

                    }

                }
            }
            
        }
        
        return Excel::create('CouncilFacility', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download();
    }
    
    public function exportCouncilFacilityByStrata() {
        $config_facilities = Config::get('constant.module.cob.facility');
        $facilities = Company::join('files','company.id','=','files.company_id')
                        ->join('facility','files.id','=','facility.file_id')
                        ->join('strata','files.id','=','strata.file_id')
                        ->select(['files.file_no','facility.*','strata.name'])
                        ->where('company.short_name','=',"MPAJ")
                        ->where('files.is_active', '!=', "2")
                        ->where('files.is_deleted','=',"0")
                        ->get();

        $data = [];
        $i = 0;
        foreach($facilities as $key => $facility) {
            $new_data[$i]['file_no'] = $facility->file_no;
            $new_data[$i]['strata_name'] = $facility->name;
            $new_data[$i]['total_facility'] = 0;
            
            foreach($config_facilities as $key1 => $val) {
                $new_data[$i]['total_facility'] += $facility[$key1.'_unit'];
                $new_data[$i][$val['title']] = (empty($facility[$key1.'_unit']))? 0 : $facility[$key1.'_unit'];
            }
            if($new_data[$i]['total_facility'] > 0) {
                $data = array_merge($data, $new_data);
                
            }
            
        }
        
        return Excel::create('CouncilFacilityByStrata', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {

                $sheet->fromArray($data);
                
            });
        })->download();
    }
}