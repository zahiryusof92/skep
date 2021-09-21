<?php

namespace Api;

use Exception;
use BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use Finance;

class FinanceFileController extends BaseController {

    public function getAnalyticData() {
        try {
            $request = Request::all();
            $response = [
                'success' => true,
                'data' => [
                    'admin' => Finance::getAdminAnalyticData($request),
                    'contract' => Finance::getContractAnalyticData($request),
                    'income' => Finance::getIncomeAnalyticData($request),
                    'repair' => Finance::getRepairAnalyticData($request),
                    'report' => Finance::getReportAnalyticData($request),
                    'staff' => Finance::getStaffAnalyticData($request),
                    'utility' => Finance::getUtilityAnalyticData($request),
                    'vandalisme' => Finance::getVandalismeAnalyticData($request),
                ]
            ];

            return Response::json($response);
        } catch(Exception $e) {
            throw($e);
        }
    }
}