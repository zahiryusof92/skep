<?php

namespace Api;

use BaseController;
use Carbon\Carbon;
use Company;
use Exception;
use Files;
use Helper\KCurl;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Job\MPSSync;

class FileController extends BaseController
{
	public $api_domain;

	public function __construct()
	{
		$this->api_domain = 'https://ecob.mps.gov.my/api/v4/';
	}

	public function get() {
		$request = Request::all();
		$cob = Company::where('short_name', $request['council'])->first();
		if($cob) {
			$options = [];
			$files = Files::with(['strata'])
						->where('company_id', $cob->id)
						->where('is_deleted', false)
						->orderBy('file_no', 'asc')
						->chunk(300, function($models) use(&$options)
						{
							foreach ($models as $key => $model)
							{
								array_push($options, [
									'id' => $key + 1, 
									'strata' => $model->strata->name? $model->strata->name : "-", 
									'file_no' => $model->file_no]);
							}
						});
			
			$response = [
				'status' => true,
				'data' => $options
			];
			
			return Response::json($response);
		}

		$response = [
			'status' => false,
			'message' => "Council Not Found!"
		];

		return Response::json($response, 404);
	}

	public function files()
	{
		$request = Request::all();

		if (isset($request['council_code']) && !empty($request['council_code'])) {
			$council = Company::where('short_name', $request['council_code'])
				->where('is_deleted', 0)
				->first();

			if ($council) {
				$files = Files::with('strata')
					->where('files.company_id', $council->id)
					->where('files.is_deleted', 0)
					->paginate(30);

				if ($files) {
					$response = [
						'success' => true,
						'data' => $files->toArray()
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

	public function syncFile()
	{
		$request = Request::all();

		if (isset($request['council_code']) && !empty($request['council_code'])) {
			$start_page = 1;
			$path = 'files?council_code=' . $request['council_code'] . '&page=' . $start_page;;
			$files = json_decode($this->curl($path));

			if (!empty($files)) {
				$page = $files->current_page;
				$last_page = $files->last_page;

				for ($page; $page <= $last_page; $page++) {
					$data = [
						'council_code' => $request['council_code'],
						'page' => $page
					];

					Queue::push(MPSSync::class, $data);
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
			$council_code = 'mps';
			$start_page = 1;

			// curl to get data
			$path = 'files?council_code=' . $council_code . '&page=' . $start_page;
			$files = json_decode($this->curl($path));

			if (!empty($files)) {
				$page = $files->current_page;
				$last_page = $files->last_page;
				$delay = 1;
				$incrementDelay = 2;

				for ($page; $page <= $last_page; $page++) {
					$data = [
						'council_code' => $council_code,
						'page' => $page
					];

					try {
						Queue::later(Carbon::now()->addSeconds($delay), MPSSync::class, $data);
					} catch (Exception $e) {
						Log::error($e);
					}

					$delay += $incrementDelay;
				}
			}

			return "true";
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
		// curl to get data
		$url = $this->api_domain . $path;
		$response = json_decode((string) ((new KCurl())->requestGET($this->getHeader(), $url)));

		if (empty($response->success) == false && $response->success == true) {
			$items = $response->data;

			return json_encode($items);
		}

		return false;
	}
}
