<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use City;

class CityController extends BaseController {

    public function getListing() {
        try {
            $request = Request::all();
            $items = City::getData($request);
            $response = [
                'success' => true,
                'data' => $items
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }
}