<?php

namespace Api;

use BaseController;
use Carbon\Carbon;
use Company;
use Exception;
use Files;
use Finance;
use Helper\KCurl;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Job\FinanceSync;

class FinanceController extends BaseController
{
	public $api_domain;

	public function __construct()
	{
		$this->api_domain = 'https://ecob.mps.gov.my/api/v4/';
	}

	public function financeFile()
	{
		$request = Request::all();

		if (!empty($request['council_code']) && !empty($request['file_no'])) {
			$council = Company::where('short_name', $request['council_code'])
				->where('is_deleted', 0)
				->first();

			if ($council) {
				$finance = Files::with('finance')
					->whereHas('finance', function ($q) {
						$q->where('finance_file.is_deleted', 0);
					})
					->where('files.company_id', $council->id)
					->where('files.file_no', $request['file_no'])
					->where('files.is_deleted', 0)
					->first();

				if ($finance && $finance->finance->count() > 0) {
					$response = [
						'success' => true,
						'data' => $finance->finance
					];

					return Response::json($response);
				}
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeCheck()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('FinanceCheck')
				->whereHas('FinanceCheck', function ($q) {
					//
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_check->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_check
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeSummary()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeSummary')
				->whereHas('financeSummary', function ($q) {
					$q->orderBy('finance_file_summary.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_summary->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_summary
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeReport()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeReport')
				->whereHas('financeReport', function ($q) {
					$q->orderBy('finance_file_report.type');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_report->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_report
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeReportExtra()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeReportExtra')
				->whereHas('financeReportExtra', function ($q) {
					$q->orderBy('finance_file_report_extra.type');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_report_extra->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_report_extra
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeReportPerbelanjaan()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeReportPerbelanjaan')
				->whereHas('financeReportPerbelanjaan', function ($q) {
					$q->orderBy('finance_file_report_perbelanjaan.type');
					$q->orderBy('finance_file_report_perbelanjaan.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_report_perbelanjaan->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_report_perbelanjaan
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeIncome()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeIncome')
				->whereHas('financeIncome', function ($q) {
					$q->orderBy('finance_file_income.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_income->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_income
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeUtility()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeUtility')
				->whereHas('financeUtility', function ($q) {
					$q->orderBy('finance_file_utility.type');
					$q->orderBy('finance_file_utility.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_utility->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_utility
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeContract()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeContract')
				->whereHas('financeContract', function ($q) {
					$q->orderBy('finance_file_contract.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_contract->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_contract
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeRepair()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeRepair')
				->whereHas('financeRepair', function ($q) {
					$q->orderBy('finance_file_repair.type');
					$q->orderBy('finance_file_repair.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_repair->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_repair
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeVandalisme()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeVandal')
				->whereHas('financeVandal', function ($q) {
					$q->orderBy('finance_file_vandalisme.type');
					$q->orderBy('finance_file_vandalisme.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_vandal->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_vandal
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeStaff()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeStaff')
				->whereHas('financeStaff', function ($q) {
					$q->orderBy('finance_file_staff.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_staff->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_staff
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function financeAdmin()
	{
		$request = Request::all();

		if (isset($request['finance_id']) && !empty($request['finance_id'])) {
			$finance = Finance::with('financeAdmin')
				->whereHas('financeAdmin', function ($q) {
					$q->orderBy('finance_file_admin.sort_no');
				})
				->where('finance_file.id', $request['finance_id'])
				->where('finance_file.is_deleted', 0)
				->first();

			if ($finance && $finance->finance_admin->count() > 0) {
				$response = [
					'success' => true,
					'data' => $finance->finance_admin
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function syncFinance()
	{
		$request = Request::all();

		if (!empty($request['council_code']) && !empty($request['file_no'])) {
			$path = 'financeFile?council_code=' . $request['council_code'] . '&file_no=' . $request['file_no'];
			$finances = json_decode($this->curl($path));

			if (!empty($finances)) {
				$delay = 0;
				$incrementDelay = 2;

				foreach ($finances as $finance) {
					$data = [
						'council_code' => $request['council_code'],
						'file_no' => $request['file_no'],
						'finance' => $finance
					];

					Queue::later(Carbon::now()->addSeconds($delay), FinanceSync::class, $data);

					$delay += $incrementDelay;
				}

				$response = [
					'success' => true,
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function submitSync()
	{
		if (Request::ajax()) {
			// return "true";
			$council_code = 'mps';

			$council = Company::where('short_name', $council_code)
				->where('is_deleted', 0)
				->first();

			if ($council) {
				$files = Files::where('files.company_id', $council->id)
					->where('files.is_deleted', 0)
					->get();

				if ($files) {
					foreach ($files as $file) {
						// curl to get data
						$path = 'financeFile?council_code=' . $council->short_name . '&file_no=' . urlencode($file->file_no);
						$finances = json_decode($this->curl($path));

						if (!empty($finances)) {
							// $delay = 0;
							// $incrementDelay = 2;

							foreach ($finances as $finance) {
								$data = [
									'council_code' => $council->short_name,
									'file_no' => $file->file_no,
									'finance' => $finance
								];

								Queue::push(FinanceSync::class, $data);

								// $delay += $incrementDelay;
							}
						}
					}

					return "true";
				}
			}
		}

		return "false";
	}

	public function getHeader()
	{
		return [
			"Accept: application/json",
		];
	}

	public function curl($path)
	{
		try {
			// curl to get data
			$url = $this->api_domain . $path;
			$response = json_decode((string) ((new KCurl())->requestGET($this->getHeader(), $url)));

			if (empty($response->success) == false && $response->success == true) {
				$items = $response->data;

				return json_encode($items);
			}

			return false;
		} catch (Exception $e) {
			throw ($e);
		}
	}
}
