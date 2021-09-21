<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Files;

class DashboardAnalyticController extends BaseController {

    public function getAnalyticData() {
        try {
            
            // $user = JWTAuth::parseToken()->authenticate();
            $user = \Auth::user();
            $items = Files::getAnalyticData();
            $response = [
                'success' => true,
                'data' => $items
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }

    public function getTestAnalyticData() {
        try {
            
            $user = JWTAuth::parseToken()->authenticate();
            // $user = \Auth::user();
            $items = Files::getAnalyticData();
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