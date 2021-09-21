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
}