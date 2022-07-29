<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Repositories\ReportRepo;

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

    public function reporting() {
        $files = Files::with(['company','strata','managementDeveloper','managementJMB','managementMC','managementAgent',
                                'managementOthers','ajk_details'])
                        // ->whereIn('files.id',[8,19])
                        // ->whereHas('company', function($query) {
                        //     return $query->where('short_name','MPS');
                        // })
                        ->orderBy('company_id','asc')
                        ->get();
        
        $types = ['Developer','JMB','MC','Agent','Others'];
        $data = [];
        $delay = 0;
        $incrementDelay = 2;
        foreach($files as $key => $file) {
            // $queue = new GetExportData;
            // $queue_data = Queue::later(Carbon::now()->addSeconds($delay), GetExportData::class, array('item' => $file));
            //         $delay += $incrementDelay;
            
            // array_merge($queue_data, $data);
            if(empty($file->strata->name) == false) {
                $data[$key]['cob'] = $file->company->short_name;
                $data[$key]['file_no'] = $file->file_no;
                $data[$key]['file_name'] = $file->strata->name;
    
                foreach($types as $key1 => $type) {
                    $data[$key]['management'][$key1]['type'] = $type;
                    $type = 'management'. $type;
                    if($type == 'managementAgent') {
                        if(empty($file->$type) == false) {
                            $management_name = $file->$type->agent;
                        } else {
                            $management_name = '';
                        }
                    } else {
                        if(empty($file->$type) == false) {
                            $management_name = $file->$type->name;
                        } else {
                            $management_name = '';
                        }
                    }
                    $data[$key]['management'][$key1]['name'] = $management_name;
                    $data[$key]['management'][$key1]['address'] = (empty($file->$type) == false)? $file->$type->address1 : '';
                    $data[$key]['management'][$key1]['email'] = (empty($file->$type) == false)? $file->$type->email : '';
                    $data[$key]['management'][$key1]['phone_no'] = (empty($file->$type) == false)? $file->$type->phone_no : '';
                }
                if(count($file->ajk_details) > 0) {
                    foreach($file->ajk_details as $key2 => $ajk) {
                        $data[$key]['ajk'][$key2]['name'] = $ajk->name;
                        $data[$key]['ajk'][$key2]['designation'] = $ajk->designations->description;
                        $data[$key]['ajk'][$key2]['phone_no'] = $ajk->phone_no;
                        $data[$key]['ajk'][$key2]['start_year'] = $ajk->start_year;
                        $data[$key]['ajk'][$key2]['end_year'] = $ajk->end_year;
                    }
                }

            }
        }
    
        // Queue::later(Carbon::now()->addSeconds($delay), GetExportData::class, array('items' => $data));
        // return \Response::json(array('data'=> $data));
        // return \View::make('exports.reporting',array('datas' => $data));
        return Excel::create('reporting', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->loadView('exports.reporting', array('datas' => $data));
                
            });
        })->download();
    }

    public function JMBMCSignByCouncil() {
        $items = DB::table('users')
                    ->join('audit_trail','audit_trail.audit_by','=','users.id')
                    ->join('company','users.company_id','=','company.id')
                    ->join('files','users.file_id','=','files.id')
                    ->join('house_scheme','house_scheme.file_id','=','files.id')
                    ->where('audit_trail.remarks','like','%signed%')
                    ->selectRaw('users.username, users.full_name, files.file_no, house_scheme.name as house_scheme, 
                                 files.id as file_id, company.short_name')
                    ->where('users.is_deleted', 0)
                    ->whereIn('users.role', [3,24])
                    ->whereIn('company.short_name', ['MBPJ'])
                    ->groupBy(['users.username'])
                    ->get();
        $data = [];
        $i = 0;
        foreach($items as $key => $item) {
            $is_update = false;
            $finance_file = DB::table('finance_file')
                                ->join('finance_file_summary','finance_file_summary.finance_file_id','=','finance_file.id')
                                ->selectRaw('finance_file.created_at, finance_file_summary.created_at as summary_created')
                                ->where('finance_file.is_deleted', 0)
                                ->where('finance_file.file_id', $item->file_id)
                                ->where('finance_file_summary.summary_key','bill_elektrik')
                                ->get();
                                
            foreach($finance_file as $finance) {
                if($finance->summary_created > $finance->created_at) {
                    $is_update = true;
                }
            }
            if($is_update) {
                $new_data[$i]['username'] = $item->username;
                $new_data[$i]['full name'] = $item->full_name;
                $new_data[$i]['file no'] = (is_array($item->file_no))? implode(',', $item->file_no) : $item->file_no;
                $new_data[$i]['scheme name'] = (empty($item->house_scheme))? '-': $item->house_scheme;
                
                $data = array_merge($data, $new_data);
            }
            
        }
        return Excel::create('jmbmc_logon_report', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {

                $sheet->fromArray($data);
                
            });
        })->download();
    }

    public function ownerByCouncil() {
        $items = DB::table('files')
                    ->join('buyer', 'buyer.file_id', '=', 'files.id')
                    ->join('house_scheme', 'house_scheme.file_id', '=', 'files.id')
                    ->join('company', 'files.company_id', '=', 'company.id')
                    ->selectRaw('count(buyer.id) as total, files.file_no, company.short_name, house_scheme.name,
                            house_scheme.address1, house_scheme.address2, house_scheme.address3, house_scheme.address4,
                            house_scheme.poscode, house_scheme.city, house_scheme.state')
                    ->where('files.is_deleted', 0)
                    ->where('buyer.is_deleted', 0)
                    ->groupBy(['house_scheme.name'])
                    ->get();
                    
        $data = [];
        $i = 0;
        $total = 0;
        foreach($items as $key => $item) {
            $city = City::where('id', $item->city)->first();                   
            $state = State::where('id', $item->state)->first();                   
            $new_data[$i]['no of owners'] = $item->total;
            $new_data[$i]['file no'] = $item->file_no;
            $new_data[$i]['scheme name'] = $item->name;
            $new_data[$i]['council'] = $item->short_name;
            $new_data[$i]['address'] = $item->address1 .', '. $item->address2 .', '. $item->address3
                                        .', '. $item->address4 .', '. $item->poscode .', ';
            $new_data[$i]['address'] .= (empty($city))? '' : $city->description;
            $new_data[$i]['address'] .= (empty($state))? '' : (' '. $state->name);
            
            $data = array_merge($data, $new_data);
            $total += $item->total;
        }
        $new_data[$i]['no of owners'] = $total;
        $new_data[$i]['file no'] = '';
        $new_data[$i]['scheme name'] = '';
        $new_data[$i]['council'] = '';
        $new_data[$i]['address'] = '';
        
        $data = array_merge($data, $new_data);

        return Excel::create('owner_by_council', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
                
            });
        })->download();
    }

    public function JMBActivity() { 
        $items = DB::table('users')
                    ->join('audit_trail', 'users.id', '=', 'audit_trail.audit_by')
                    ->join('company', 'users.company_id', '=', 'company.id')
                    ->selectRaw('users.email, users.full_name, audit_trail.remarks as activity, audit_trail.created_at,
                                company.short_name')
                    ->where('users.role', 3)
                    ->where('users.is_deleted', 0)
                    ->whereBetween('audit_trail.created_at', ["2021-08-06 00:00:00", "2021-09-03 23:59:59"])
                    ->orderBy('users.full_name', 'asc')
                    ->get();
                    
        $data = [];
        $i = 0;
        foreach($items as $key => $item) {                 
            $new_data[$i]['email'] = $item->email;
            $new_data[$i]['full name'] = $item->full_name;
            $new_data[$i]['activity'] = $item->activity;
            $new_data[$i]['council'] = $item->short_name;
            $new_data[$i]['created at'] = $item->created_at;
            
            $data = array_merge($data, $new_data);
        }
        
        $data = array_merge($data, $new_data);

        return Excel::create('jmb_activity', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
                
            });
        })->download();
    }

    public function strataName() { 
        $items = Strata::join('files', 'strata.file_id', '=', 'files.id')
                        ->join('company', 'files.company_id', '=', 'company.id')
                        ->where('company.short_name', 'MBPJ')
                        ->select(['strata.*', 'files.file_no as file_no'])
                        ->get();
                    
        $data = [];
        $i = 0;
        foreach($items as $key => $item) {                 
            $new_data[$i]['file'] = $item->file_no;
            $new_data[$i]['name'] = $item->name;

            $data = array_merge($data, $new_data);
        }
        
        return Excel::create('strata-data-'. date('YmdHis'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
                
            });
        })->download();
    }

    public function tunggakanFinance() {
        $items = Finance::with(['financeReport', 'financeIncome', 'file.strata', 'company'])
                        ->where('is_deleted', 0)
                        ->where('is_active', 1)
                        ->whereBetween('month', [3, 5])
                        ->where('year', 2022)
                        ->get();
                    
        $data = [];
        $i = 0;
        foreach($items as $key => $item) {                 
            $new_data[$i]['cob'] = $item->company->short_name;
            $new_data[$i]['strata'] = $item->file->strata->name;
            $new_data[$i]['file'] = $item->file->file_no;
            $new_data[$i]['year'] = $item->year;
            $new_data[$i]['month'] = $item->month;
            $new_data[$i]['MF Tunggakan'] = $item->financeReport()->where('type', 'mf')->first()? $item->financeReport()->where('type', 'mf')->first()->tunggakan_belum_dikutip : 0;
            $new_data[$i]['SF Tunggakan'] = $item->financeReport()->where('type', 'sf')->first()? $item->financeReport()->where('type', 'sf')->first()->tunggakan_belum_dikutip : 0;
            $new_data[$i]['Income MF Tunggakan'] = $item->financeIncome()->where('name', 'maintenance fee')->first()? $item->financeIncome()->where('name', 'maintenance fee')->first()->tunggakan : 0;
            $new_data[$i]['Income SF Tunggakan'] = $item->financeIncome()->where('name', 'sinking fund')->first()? $item->financeIncome()->where('name', 'sinking fund')->first()->tunggakan : 0;

            $data = array_merge($data, $new_data);
        }
        
        return Excel::create('tunggakan finance-data-'. date('YmdHis'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
                
            });
        })->download();
    }

    public function fileDetails() {
        $commercial_files = Commercial::where('unit_no', '>=', 500)
                                    ->get();
        $residential_files = Residential::where('unit_no', '>=', 500)
                                    ->get();
        $file_ids = array_merge(array_pluck($commercial_files, 'file_id'), array_pluck($residential_files, 'file_id'));
        
        $files = Files::with(['company','strata','managementDeveloper','managementJMB','managementMC','managementAgent',
                                'managementOthers','ajk_details', 'resident', 'commercial'])
                        ->whereIn('id', $file_ids)
                        ->where('files.is_deleted', false)
                        ->orderBy('company_id','asc')
                        ->take(10)
                        ->get();
        
        $types = ['Developer','JMB','MC','Agent','Others'];
        $data = [];
        foreach($files as $key => $file) {
            if(empty($file->strata->name) == false) {
                $data[$key]['cob'] = $file->company->short_name;
                $data[$key]['file_no'] = $file->file_no;
                $data[$key]['strata'] = $file->strata->name;
                $data[$key]['residential_unit_no'] = $file->resident? $file->resident->unit_no : '-';
                $data[$key]['commercial_unit_no'] = $file->commercial? $file->commercial->unit_no : '-';
    
                foreach($types as $key1 => $type) {
                    $data[$key]['management'][$key1]['type'] = $type;
                    $type = 'management'. $type;
                    if($type == 'managementAgent') {
                        if(empty($file->$type) == false) {
                            $management_name = $file->$type->agent;
                        } else {
                            $management_name = '';
                        }
                    } else {
                        if(empty($file->$type) == false) {
                            $management_name = $file->$type->name;
                        } else {
                            $management_name = '';
                        }
                    }
                    $data[$key]['management'][$key1]['name'] = $management_name;
                    $data[$key]['management'][$key1]['address'] = (empty($file->$type) == false)? $file->$type->address1 : '';
                    $data[$key]['management'][$key1]['email'] = (empty($file->$type) == false)? $file->$type->email : '';
                    $data[$key]['management'][$key1]['phone_no'] = (empty($file->$type) == false)? $file->$type->phone_no : '';
                }
                if(count($file->ajk_details) > 0) {
                    foreach($file->ajk_details as $key2 => $ajk) {
                        $data[$key]['ajk'][$key2]['name'] = $ajk->name;
                        $data[$key]['ajk'][$key2]['designation'] = $ajk->designations->description;
                        $data[$key]['ajk'][$key2]['phone_no'] = $ajk->phone_no;
                        $data[$key]['ajk'][$key2]['start_year'] = $ajk->start_year;
                        $data[$key]['ajk'][$key2]['end_year'] = $ajk->end_year;
                    }
                }

            }
        }
        return Excel::create('file-details-'. date('YmdHis'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->loadView('exports.file-detail', array('datas' => $data));
                
            });
        })->download();
    }

    public function generateReport() {
        $request = Request::all();
        $items = (new ReportRepo())->generateReport($request);
        $i = 0;
        $data = [];
        foreach($items as $key => $item) { 
            $new_data[$i]['No'] = ($key + 1);
            if(in_array('file_no', $request['selected'])) {
                $new_data[$i]['NO FAIL'] = $item->file_no;
            }
            
            if(in_array('city', $request['selected'])) {
                $new_data[$i]['BANDAR'] = $item->strata->town? Str::upper($item->strata->towns->description) : '-';
            }
            if(in_array('strata', $request['selected'])) {
                $new_data[$i]['KAWASAN PEMAJUAN'] = $item->strata->name;
            }
            if(in_array('category', $request['selected'])) {
                $new_data[$i]['KATEGORI'] = $item->strata->category? $item->strata->categories->description : "-";
            }
            if(in_array('file_draft_latest_date', $request['selected'])) {
                $new_data[$i]['Tarikh VP'] = !empty($item->draft)? $item->draft->created_at->toDateTimeString() : '-';
            }
            if(in_array('latest_agm_date', $request['selected'])) {
                $new_data[$i]['Tarikh mesyuarat AGM Terkini'] = $item->latestMeetingDocument? $item->latestMeetingDocument->created_at->toDateTimeString() : "-";
            }
            if(in_array('latest_insurance_date', $request['selected'])) {
                $new_data[$i]['Tarikh Insurans Terkini'] = $item->insurance->count()? $item->insurance()->latest()->first()->created_at->toDateTimeString() : "-";
            }
            if(in_array('jmb_date_formed', $request['selected'])) {
                $new_data[$i]['Tarikh Sijil JMB'] = $item->management->is_jmb? $item->managementJMB->date_formed : '-';
            }
            if(in_array('mc_date_formed', $request['selected'])) {
                $new_data[$i]['Tarikh Sijil MC'] = $item->management->is_mc? $item->managementMC->date_formed : '-';
            }
            if(in_array('total_floor', $request['selected'])) {
                $new_data[$i]['Tingkat'] = $item->strata->total_floor;
            }
            $residential = Residential::where('file_id', $item->id)->sum('unit_no');
            $residential_extra = ResidentialExtra::where('file_id', $item->id)->sum('unit_no');
            
            if(in_array('residential_block', $request['selected'])) {
                $new_data[$i]['Rumah'] = $residential + $residential_extra;
            }
            $commercial = Commercial::where('file_id', $item->id)->sum('unit_no');
            $commercial_extra = CommercialExtra::where('file_id', $item->id)->sum('unit_no');
            
            if(in_array('commercial_block', $request['selected'])) {
                $new_data[$i]['Kedai'] = $commercial + $commercial_extra;
            }
            if(in_array('block', $request['selected'])) {
                $new_data[$i]['Blok'] = $item->strata->block_no;
            }
            $management = "-";
            if($item->management->is_jmb) {
                $management = "JMB";
            } else if($item->management->is_mc) {
                $management = "MC";
            } else if($item->management->is_developer) {
                $management = "Developer";
            } else if($item->management->liquidator) {
                $management = "Liquidator";
            } else if($item->management->under_10_units) {
                $management = "Strata < 10 unit";
            } else if($item->management->bankruptcy) {
                $management = "Bankruptcy";
            }
            
            if(in_array('management', $request['selected'])) {
                $new_data[$i]['MANAGEMENT'] = $management; //MANAGEMENT
            }
            if(in_array('is_active', $request['selected'])) {
                $new_data[$i]['IS ACTIVE'] = $item->is_active ? trans('app.forms.yes') : trans('app.forms.no');
            }
            
            $data = array_merge($data, $new_data);
        }

        return Excel::create('listing-report-'. date('YmdHis'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
                
            });
        })->download();
    }

    public function ajk() {
        $items = Files::with(['company', 'houseScheme', 'managementDeveloper', 'managementJMB', 'managementMC', 'managementAgent', 'managementOthers', 'ajk_details'])
                        ->file()
                        ->where('company_id', 9)
                        // ->take(10)
                        ->get();
                    
        $data = [];
        $i = 0;
        foreach($items as $key => $item) {                 
            $data[$key]['cob'] = $item->company->short_name;
            $data[$key]['file_no'] = $item->file_no;
            $data[$key]['file_name'] = $item->houseScheme->name;
            $data[$key]['management'] = [];
            $data[$key]['ajk'] = [];
            if($item->managementDeveloper) {
                $developer_data = [
                    'type' => 'Developer',
                    'name' => $item->managementDeveloper->name,
                    'address' => $item->managementDeveloper->address_1 . " " . $item->managementDeveloper->address_2 . " " . $item->managementDeveloper->address_3 . " " . $item->managementDeveloper->address_4,
                    'email' => $item->managementDeveloper->email,
                    'phone_no' => $item->managementDeveloper->phone_no,
                ];
                array_push($data[$key]['management'], $developer_data);
            }
            if($item->managementJMB) {
                $jmb_data = [
                    'type' => 'JMB',
                    'name' => $item->managementJMB->name,
                    'address' => $item->managementJMB->address_1 . " " . $item->managementJMB->address_2 . " " . $item->managementJMB->address_3 . " " . $item->managementJMB->address_4,
                    'email' => $item->managementJMB->email,
                    'phone_no' => $item->managementJMB->phone_no,
                ];
                array_push($data[$key]['management'], $jmb_data);
            }
            if($item->managementMC) {
                $mc_data = [
                    'type' => 'MC',
                    'name' => $item->managementMC->name,
                    'address' => $item->managementMC->address_1 . " " . $item->managementMC->address_2 . " " . $item->managementMC->address_3 . " " . $item->managementMC->address_4,
                    'email' => $item->managementMC->email,
                    'phone_no' => $item->managementMC->phone_no,
                ];
                array_push($data[$key]['management'], $mc_data);
            }
            if($item->managementAgent) {
                $agent_data = [
                    'type' => 'Agent',
                    'name' => $item->managementAgent->name,
                    'address' => $item->managementAgent->address_1 . " " . $item->managementAgent->address_2 . " " . $item->managementAgent->address_3 . " " . $item->managementAgent->address_4,
                    'email' => $item->managementAgent->email,
                    'phone_no' => $item->managementAgent->phone_no,
                ];
                array_push($data[$key]['management'], $agent_data);
            }
            if($item->managementOthers) {
                $others_data = [
                    'type' => 'Others',
                    'name' => $item->managementOthers->name,
                    'address' => $item->managementOthers->address_1 . " " . $item->managementOthers->address_2 . " " . $item->managementOthers->address_3 . " " . $item->managementOthers->address_4,
                    'email' => $item->managementOthers->email,
                    'phone_no' => $item->managementOthers->phone_no,
                ];
                array_push($data[$key]['management'], $others_data);
            }
            foreach($item->ajk_details as $ajk) {
                $ajk_data = [
                    'name' => $ajk->name,
                    'designation' => $ajk->designations->description,
                    'phone_no' => $ajk->phone_no,
                    'email' => $ajk->email,
                    'month' => $ajk->month,
                    'start_year' => $ajk->start_year,
                    'end_year' => $ajk->end_year,
                ];
                array_push($data[$key]['ajk'], $ajk_data);
            }
        }
        
        return Excel::create('ajk-details-'. date('YmdHis'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->loadView('exports.ajk-detail', array('datas' => $data));
                
            });
        })->download();
    }
}