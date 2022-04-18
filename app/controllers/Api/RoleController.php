<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Role;

class RoleController extends BaseController {

    public function getOption() {
        if(Request::ajax()) {
            $request = Request::all();
            $options = [];
            $models = Role::self()
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('name', "like", "%". $request['term'] ."%");
                                }
                            })
                            ->get();
                            
            foreach($models as $model) {
                array_push($options, ['id' => $model->id, 'text' => $model->name]);
            }
            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);

        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}