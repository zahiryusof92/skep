<?php

namespace Api;

use Exception;
use BaseController;
use Files;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Strata;

class StrataController extends BaseController {

    public function getListing() {
        try {
            $request = Request::all();
            $items = Strata::getStratasData($request);
            $response = [
                'success' => true,
                'data' => $items
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }

    public function getAnalyticData() {
        try {
            $request = Request::all();
            $data = Strata::getAnalyticData($request);
            $response = [
                'success' => true,
                'data' => $data
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
            $models = Strata::self()
                            ->where(function($query) use($request) {
                                if(!empty($request['term'])) {
                                    $query->where('strata.name', "like", "%". $request['term'] ."%");
                                }
                                if(!empty($request['file_id'])) {
                                    $query->where('strata.file_id', $request['file_id']);
                                }
                                if(!empty($request['company_id'])) {
                                    $file_ids = [];
                                    if(is_array($request['company_id']))  {
                                        Files::whereIn('company_id', $request['company_id'])
                                                ->chunk(500, function($models) use(&$file_ids) {
                                                    foreach ($models as $model)
                                                    {
                                                        array_push($file_ids, $model->id);
                                                    }
                                                });
                                    } else {
                                        Files::where('company_id', $request['company_id'])
                                                ->chunk(500, function($models) use(&$file_ids) {
                                                    foreach ($models as $model)
                                                    {
                                                        array_push($file_ids, $model->id);
                                                    }
                                                });
                                    }
                                    $query->whereIn('strata.file_id', $file_ids);
                                }
                            })
                            ->groupBy(['strata.name'])
                            ->chunk(200, function($models) use(&$options, $request)
                            {
                                foreach ($models as $model)
                                {
                                    if(!empty($request['type']) && $request['type'] == 'id') {
                                        if(!empty($model->name)) array_push($options, ['id' => $model->id, 'text' => $model->name]);
                                    } else {
                                        if(!empty($model->name)) array_push($options, ['id' => $model->name, 'text' => $model->name]);
                                    }
                                }
                            });

            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);

        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}