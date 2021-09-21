<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Paginator;
use yajra\Datatables\Facades\Datatables;
use Buyer;

class OwnerController extends BaseController {

    public function getListing() {
        try {
            $request = Request::all();
            $perPage = 15;
            $items = Buyer::getBuyersData($request)->paginate($perPage);
            
            $response = [
                'success' => true,
                'items' => $items->toJson()
            ];

            return Response::json($response);
            
        } catch(Exception $e) {
            throw($e);
        }
    }

    public function getAnalyticData() {
        try {
            $request = Request::all();
            $parliment = Buyer::getBuyerByParlimentAnaylticData($request);
            $dun = Buyer::getBuyerByDunAnaylticData($request);
            $park = Buyer::getBuyerByParkAnaylticData($request);
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