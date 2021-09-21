<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Tenant;

class TenantController extends BaseController {

    public function getListing() {
        try {
            $request = Request::all();
            $items = Tenant::getTenantsData($request);
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
            $parliment = Tenant::getTenantByParlimentAnaylticData($request);
            $dun = Tenant::getTenantByDunAnaylticData($request);
            $park = Tenant::getTenantByParkAnaylticData($request);
            $response = [
                'success' => true,
                'data' => [
                    'parliment' => $parliment,
                    'dun' => $dun,
                    'park' => $park,
                ]
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }
}