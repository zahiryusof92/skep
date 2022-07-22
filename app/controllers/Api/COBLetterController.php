<?php

namespace Api;

use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class COBLetterController extends BaseController {

    /**
     * Get sepcified options base on company short name
     *
     * @param  string  $type
     * @return Response
     */
    public function getTypeOptions() {
        if(Request::ajax()) {
            $cob = Request::get('cob');
            if(!empty($cob)) {
                $options = [];
                $types = (!empty($this->module['cob_letter']['cob'][Str::lower($cob)]))? $this->module['cob_letter']['cob'][Str::lower($cob)]['type'] : '';
                if(!empty($types)) {
                    foreach($types as $type) {
                        array_push($options, ['id' => $type['name'], 'text' => $type['title']]);
                    }
                }
                return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);
            }
        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}