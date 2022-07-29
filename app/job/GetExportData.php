<?php

namespace Job;

use Files;
use Maatwebsite\Excel\Facades\Excel;

class GetExportData
{

    public function fire($job, $data)
    {
        // $types = ['Developer','JMB','MC','Agent','Others'];
        // // $item = $data['item'];
        // $item = Files::with(['company','strata','managementDeveloper','managementJMB','managementMC','managementAgent',
        // 'managementOthers','ajk_details'])
        // ->where('id',$data['item']['id'])
        // ->first();
        // $response = [];
        // $response['cob'] = $item->company->short_name;
        // $response['file_no'] = $item->file_no;
        // $response['file_name'] = $item->strata->name;
        
        // foreach($types as $key1 => $type) {
        //     $response['management'][$key1]['type'] = $type;
        //     $type = 'management'. $type;
        //     if($type == 'managementAgent') {
        //         if(empty($item->$type) == false) {
        //             $management_name = $item->$type->agent;
        //         } else {
        //             $management_name = '';
        //         }
        //     } else {
        //         if(empty($item->$type) == false) {
        //             $management_name = $item->$type->name;
        //         } else {
        //             $management_name = '';
        //         }
        //     }
        //     $response['management'][$key1]['name'] = $management_name;
        //     $response['management'][$key1]['address'] = (empty($item->$type) == false)? $item->$type->address1 : '';
        //     $response['management'][$key1]['email'] = (empty($item->$type) == false)? $item->$type->email : '';
        //     $response['management'][$key1]['phone_no'] = (empty($item->$type) == false)? $item->$type->phone_no : '';
        // }
        // if(count($item->ajk_details) > 0) {
        //     foreach($item->ajk_details as $key2 => $ajk) {
        //         $response['ajk'][$key2]['name'] = $ajk->name;
        //         $response['ajk'][$key2]['designation'] = $ajk->designations->description;
        //         $response['ajk'][$key2]['phone_no'] = $ajk->phone_no;
        //         $response['ajk'][$key2]['start_year'] = $ajk->start_year;
        //         $response['ajk'][$key2]['end_year'] = $ajk->end_year;
        //     }
        // }
        
        // $this->response =  $response;
        $items = $data['items'];
        return Excel::create('reporting', function($excel) use ($items) {
            $excel->sheet('mySheet', function($sheet) use ($items)
            {
                $sheet->loadView('exports.reporting', array('datas' => $items));
                
            });
        })->download();
    }

}