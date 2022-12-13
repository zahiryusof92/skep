<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use City;

class CityController extends BaseController
{

    public function getListing()
    {
        try {
            $request = Request::all();
            $items = City::getData($request);
            $response = [
                'success' => true,
                'data' => $items
            ];

            return Response::json($response);
        } catch (Exception $e) {
            throw ($e);
        }
    }

    public function getOption()
    {
        if (Request::ajax()) {
            $request = Request::all();
            $options = [];
            $cities = City::self()
                ->where(function ($query) use ($request) {
                    if (!empty($request['term'])) {
                        $query->where('description', "like", "%" . $request['term'] . "%");
                    }
                })
                ->get();

            foreach ($cities as $city) {
                array_push($options, ['id' => $city->id, 'text' => $city->description]);
            }
            return Response::json(['success' => true, 'message' => trans('Success'), 'results' => $options]);
        }

        return Response::json(['error' => true, 'message' => trans('Fail')]);
    }
}
