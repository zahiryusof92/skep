<?php

namespace Api;

use BaseController;
use Company;
use Files;
use Helper\KCurl;
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

				for ($page; $page <= $last_page; $page++) {
					$data = [
						'council_code' => $council_code,
						'page' => $page
					];

					Queue::push(MPSSync::class, $data);
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
