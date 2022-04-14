<?php

namespace Api;

use AuditTrail;
use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class AuditTrailController extends BaseController {

    public function getModuleOption() {
        if(Request::ajax()) {
            $request = Request::all();
            $models = AuditTrail::self()
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('module', "like", "%". $request['term'] ."%");
                                }
                                if(!empty($request['file_id'])) {
                                    $query->where('users.file_id', $request['file_id']);
                                }
                            })
                            ->groupBy(['module'])
                            ->lists('module');
            $options = [];
            foreach($models as $model) {
                array_push($options, ['id' => $model, 'text' => $model]);
            }
            
            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);

        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}