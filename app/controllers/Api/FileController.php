<?php

namespace Api;

use Area;
use BaseController;
use Carbon\Carbon;
use City;
use Company;
use Country;
use Developer;
use Dun;
use Exception;
use Files;
use Helper\KCurl;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Job\MPSSync;
use LandTitle;
use Park;
use Parliment;
use State;
use Strata;
use UnitMeasure;

class FileController extends BaseController
{
	public $api_domain;

	public function __construct()
	{
		$this->api_domain = 'https://ecob.mps.gov.my/api/v4/';
	}

	public function get()
	{
		// $client = $this->validateSecret();

		if (!empty($client['error'])) {
			return Response::json([
				'response' => $client
			], 404);
		}

		$request = Request::all();
		$rules = array(
			'council' => 'required',
		);
		$validator = Validator::make($request, $rules);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			], 422);
		}

		$cob = Company::where('short_name', $request['council'])->first();

		if ($cob) {
			$options = [];
			$files = Files::with(['strata'])
				->join('strata', 'strata.file_id', '=', 'files.id')
				->where(function ($query) use ($request) {
					if (!empty($request['strata'])) {
						$query->where('strata.name', 'like', "%" . $request['strata'] . "%");
					}
				})
				->where('files.company_id', $cob->id)
				->where('files.is_deleted', false)
				->orderBy('files.file_no', 'asc')
				->selectRaw('files.*, strata.name as strata_name')
				->chunk(300, function ($models) use (&$options) {
					foreach ($models as $key => $model) {
						array_push($options, [
							'id' => $model->id,
							'strata' => $model->strata->name ? $model->strata->name : "-",
							'file_no' => $model->file_no
						]);
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

	public function filesHouseScheme()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('houseScheme')
				->whereHas('houseScheme', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->houseScheme->count() > 0) {
				if (!empty($file->houseScheme->developer)) {
					$developer = Developer::with('city', 'state', 'country')->find($file->houseScheme->developer);
					if ($developer) {
						$file->houseScheme->developer = $developer;
					}
				}

				if (!empty($file->houseScheme->city)) {
					$city = City::find($file->houseScheme->city);
					if ($city) {
						$file->houseScheme->city = $city;
					}
				}

				if (!empty($file->houseScheme->state)) {
					$state = State::find($file->houseScheme->state);
					if ($state) {
						$file->houseScheme->state = $state;
					}
				}

				if (!empty($file->houseScheme->country)) {
					$country = Country::find($file->houseScheme->country);
					if ($country) {
						$file->houseScheme->country = $country;
					}
				}

				if (!empty($file->houseScheme->liquidator_city)) {
					$liquidator_city = City::find($file->houseScheme->liquidator_city);
					if ($liquidator_city) {
						$file->houseScheme->liquidator_city = $liquidator_city;
					}
				}

				if (!empty($file->houseScheme->liquidator_state)) {
					$liquidator_state = State::find($file->houseScheme->liquidator_state);
					if ($liquidator_state) {
						$file->houseScheme->liquidator_state = $liquidator_state;
					}
				}

				if (!empty($file->houseScheme->liquidator_country)) {
					$liquidator_country = Country::find($file->houseScheme->liquidator_country);
					if ($liquidator_country) {
						$file->houseScheme->liquidator_country = $liquidator_country;
					}
				}

				$response = [
					'success' => true,
					'data' => $file->houseScheme
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesStrata()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('strata')
				->whereHas('strata', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->strata->count() > 0) {
				if (!empty($file->strata->parliament)) {
					$parliament = Parliment::find($file->strata->parliament);
					if ($parliament) {
						$file->strata->parliament = $parliament;
					}
				}

				if (!empty($file->strata->dun)) {
					$dun = Dun::find($file->strata->dun);
					if ($dun) {
						$file->strata->dun = $dun;
					}
				}

				if (!empty($file->strata->park)) {
					$park = Park::find($file->strata->park);
					if ($park) {
						$file->strata->park = $park;
					}
				}

				if (!empty($file->strata->city)) {
					$city = City::find($file->strata->city);
					if ($city) {
						$file->strata->city = $city;
					}
				}

				if (!empty($file->strata->state)) {
					$state = State::find($file->strata->state);
					if ($state) {
						$file->strata->state = $state;
					}
				}

				if (!empty($file->strata->country)) {
					$country = Country::find($file->strata->country);
					if ($country) {
						$file->strata->country = $country;
					}
				}

				if (!empty($file->strata->town)) {
					$town = City::find($file->strata->town);
					if ($town) {
						$file->strata->town = $town;
					}
				}

				if (!empty($file->strata->area)) {
					$area = Area::find($file->strata->area);
					if ($area) {
						$file->strata->area = $area;
					}
				}

				if (!empty($file->strata->land_area_unit)) {
					$land_area_unit = UnitMeasure::find($file->strata->land_area_unit);
					if ($land_area_unit) {
						$file->strata->land_area_unit = $land_area_unit;
					}
				}

				if (!empty($file->strata->land_title)) {
					$land_title = LandTitle::find($file->strata->land_title);
					if ($land_title) {
						$file->strata->land_title = $land_title;
					}
				}

				if (!empty($file->strata->category)) {
					$category = LandTitle::find($file->strata->category);
					if ($category) {
						$file->strata->category = $category;
					}
				}

				if (!empty($file->strata->perimeter)) {
					$perimeter = LandTitle::find($file->strata->perimeter);
					if ($perimeter) {
						$file->strata->perimeter = $perimeter;
					}
				}

				$response = [
					'success' => true,
					'data' => $file->strata
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesFacility()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('facility')
				->whereHas('facility', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->facility->count() > 0) {
				$response = [
					'success' => true,
					'data' => $file->facility
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesManagement()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('management')
				->whereHas('management', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->management->count() > 0) {
				$response = [
					'success' => true,
					'data' => $file->management
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesManagementJMB()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('managementJMB')
				->whereHas('managementJMB', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->managementJMB->count() > 0) {
				if (!empty($file->managementJMB->city)) {
					$city = City::find($file->managementJMB->city);
					if ($city) {
						$file->managementJMB->city = $city;
					}
				}

				if (!empty($file->managementJMB->state)) {
					$state = State::find($file->managementJMB->state);
					if ($state) {
						$file->managementJMB->state = $state;
					}
				}

				if (!empty($file->managementJMB->country)) {
					$country = Country::find($file->managementJMB->country);
					if ($country) {
						$file->managementJMB->country = $country;
					}
				}

				$response = [
					'success' => true,
					'data' => $file->managementJMB
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesManagementMC()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('managementMC')
				->whereHas('managementMC', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->managementMC->count() > 0) {
				if (!empty($file->managementMC->city)) {
					$city = City::find($file->managementMC->city);
					if ($city) {
						$file->managementMC->city = $city;
					}
				}

				if (!empty($file->managementMC->state)) {
					$state = State::find($file->managementMC->state);
					if ($state) {
						$file->managementMC->state = $state;
					}
				}

				if (!empty($file->managementMC->country)) {
					$country = Country::find($file->managementMC->country);
					if ($country) {
						$file->managementMC->country = $country;
					}
				}

				$response = [
					'success' => true,
					'data' => $file->managementMC
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesManagementAgent()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('managementAgent')
				->whereHas('managementAgent', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->managementAgent->count() > 0) {
				if (!empty($file->managementAgent->city)) {
					$city = City::find($file->managementAgent->city);
					if ($city) {
						$file->managementAgent->city = $city;
					}
				}

				if (!empty($file->managementAgent->state)) {
					$state = State::find($file->managementAgent->state);
					if ($state) {
						$file->managementAgent->state = $state;
					}
				}

				if (!empty($file->managementAgent->country)) {
					$country = Country::find($file->managementAgent->country);
					if ($country) {
						$file->managementAgent->country = $country;
					}
				}

				$response = [
					'success' => true,
					'data' => $file->managementAgent
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesManagementOthers()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('managementOthers')
				->whereHas('managementOthers', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->managementOthers->count() > 0) {
				if (!empty($file->managementOthers->city)) {
					$city = City::find($file->managementOthers->city);
					if ($city) {
						$file->managementOthers->city = $city;
					}
				}

				if (!empty($file->managementOthers->state)) {
					$state = State::find($file->managementOthers->state);
					if ($state) {
						$file->managementOthers->state = $state;
					}
				}

				if (!empty($file->managementOthers->country)) {
					$country = Country::find($file->managementOthers->country);
					if ($country) {
						$file->managementOthers->country = $country;
					}
				}

				$response = [
					'success' => true,
					'data' => $file->managementOthers
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesManagementDeveloper()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('managementDeveloper')
				->whereHas('managementDeveloper', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->managementDeveloper->count() > 0) {
				if (!empty($file->managementDeveloper->city)) {
					$city = City::find($file->managementDeveloper->city);
					if ($city) {
						$file->managementDeveloper->city = $city;
					}
				}

				if (!empty($file->managementDeveloper->state)) {
					$state = State::find($file->managementDeveloper->state);
					if ($state) {
						$file->managementDeveloper->state = $state;
					}
				}

				if (!empty($file->managementDeveloper->country)) {
					$country = Country::find($file->managementDeveloper->country);
					if ($country) {
						$file->managementDeveloper->country = $country;
					}
				}

				$response = [
					'success' => true,
					'data' => $file->managementDeveloper
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesMonitoring()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('monitoring')
				->whereHas('monitoring', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->monitoring->count() > 0) {
				$response = [
					'success' => true,
					'data' => $file->monitoring
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesMonitoringDocument()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('meetingDocument')
				->whereHas('meetingDocument', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->meetingDocument->count() > 0) {
				$response = [
					'success' => true,
					'data' => $file->meetingDocument
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesOther()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('other')
				->whereHas('other', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->other->count() > 0) {
				$response = [
					'success' => true,
					'data' => $file->other
				];

				return Response::json($response);
			}
		}

		$response = [
			'success' => false,
		];

		return Response::json($response);
	}

	public function filesRating()
	{
		$request = Request::all();

		if (isset($request['file_id']) && !empty($request['file_id'])) {
			$file = Files::with('ratings')
				->whereHas('ratings', function ($q) {
					//
				})
				->where('files.id', $request['file_id'])
				->where('files.is_deleted', 0)
				->first();

			if ($file && $file->ratings->count() > 0) {
				$response = [
					'success' => true,
					'data' => $file->ratings
				];

				return Response::json($response);
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
