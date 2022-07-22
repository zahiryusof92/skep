<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Company;

class CompanyController extends BaseController {

    public function getOption() {
        if(Request::ajax()) {
            $request = Request::all();
            $options = [];
            $models = Company::self()
                            ->where('is_main', 0)
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('name', "like", "%". $request['term'] ."%")
                                        ->orWhere('short_name', "like", "%". $request['term'] ."%");
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

    public function getNameOption() {
        if(Request::ajax()) {
            $request = Request::all();
            $options = [];
            $models = Company::self()
                            ->where('is_main', 0)
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('name', "like", "%". $request['term'] ."%")
                                        ->orWhere('short_name', "like", "%". $request['term'] ."%");
                                }
                            })
                            ->get();

            foreach($models as $model) {
                array_push($options, ['id' => $model->short_name, 'text' => $model->name]);
            }
            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);

        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}