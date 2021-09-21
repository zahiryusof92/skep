<?php

namespace Api;

use Exception;
use BaseController;
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
}