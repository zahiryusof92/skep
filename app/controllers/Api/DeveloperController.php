<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Developer;

class DeveloperController extends BaseController {

    public function getListing() {
        try {
            $request = Request::all();
            $items = Developer::getData($request);
            $response = [
                'success' => true,
                'data' => $items
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }

    public function getOption() {
        if(Request::ajax()) {
            $request = Request::all();
            $options = [];
            $developers = Developer::self()
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('name', "like", "%". $request['term'] ."%");
                                }
                            })
                            ->chunk(200, function($models) use(&$options)
                            {
                                foreach ($models as $model)
                                {
                                    array_push($options, ['id' => $model->id, 'text' => $model->name]);
                                }
                            });
                            
            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);

        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }

    public function getAnalyticData() {
        try {
            $request = Request::all();
            $data = Developer::getAnalyticData($request);
            $response = [
                'success' => true,
                'data' => $data
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }
}