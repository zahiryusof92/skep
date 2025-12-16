<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Parliment;

class ParlimentController extends BaseController {

    public function getListing() {
        try {
            $request = Request::all();
            $items = Parliment::getData($request);
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
            $data = Parliment::getAnalyticData($request);
            $response = [
                'success' => true,
                'data' => $data
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }

    public function option() {
        try {
            $request = Request::all();
            $data = Parliment::getOptions($request);
            $response = [
                'success' => true,
                'data' => $data
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }

    public function getOption()
    {
        if (Request::ajax()) {
            $request = Request::all();
            $options = [];
            $parliments = Parliment::self()
                ->where(function ($query) use ($request) {
                    if (!empty($request['term'])) {
                        $query->where('description', "like", "%" . $request['term'] . "%");
                    }
                })
                ->chunk(200, function ($models) use (&$options) {
                    foreach ($models as $model) {
                        array_push($options, ['id' => $model->id, 'text' => $model->description]);
                    }
                });

            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);
        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}