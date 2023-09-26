<?php

use Helper\Helper;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class EServicePriceController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			if (!Auth::user()->getAdmin()) {
				$prices = EServicePrice::select('eservices_prices.*')
					->join('category', 'eservices_prices.category_id', '=', 'category.id')
					->join('company', 'eservices_prices.company_id', '=', 'company.id')
					->where('category.is_deleted', 0)
					->where('company.is_active', 1)
					->where('company.id', Auth::user()->company_id)
					->orderBy('eservices_prices.company_id')
					->get();
			} else {
				if (empty(Session::get('admin_cob'))) {
					$prices = EServicePrice::select('eservices_prices.*')
						->join('category', 'eservices_prices.category_id', '=', 'category.id')
						->where('category.is_deleted', 0)
						->orderBy('eservices_prices.company_id')
						->get();
				} else {
					$prices = EServicePrice::select('eservices_prices.*')
						->join('category', 'eservices_prices.category_id', '=', 'category.id')
						->join('company', 'eservices_prices.company_id', '=', 'company.id')
						->where('category.is_deleted', 0)
						->where('company.is_active', 1)
						->where('company.id', Session::get('admin_cob'))
						->orderBy('eservices_prices.company_id')
						->get();
				}
			}

			if (count($prices) > 0) {
				$data = array();
				foreach ($prices as $price) {
					$button = "";
					$button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('EServicePriceController@edit', $this->encodeID($price->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';

					$data_raw = array(
						$price->company->name,
						$price->category->description,
						$price->type,
						number_format($price->price, 2),
						$button
					);

					array_push($data, $data_raw);
				}
				$output_raw = array(
					"aaData" => $data
				);

				$output = json_encode($output_raw);
				return $output;
			} else {
				$output_raw = array(
					"aaData" => []
				);

				$output = json_encode($output_raw);
				return $output;
			}
		}

		$councils = $this->module['eservice']['cob'];
		foreach ($councils as $council => $company) {
			$cob = Company::where('short_name', Str::lower($council))->first();
			if ($cob) {
				$categories = Category::where('is_deleted', 0)->get();
				if ($categories) {
					foreach ($categories as $category) {
						$validType = [];
						if (isset($this->module['eservice']['cob'][Str::lower($council)]['type'])) {
							foreach ($this->module['eservice']['cob'][Str::lower($council)]['type'] as $type) {

								$price = EServicePrice::updateOrCreate(
									[
										'company_id' => $cob->id,
										'category_id' => $category->id,
										'slug' => $type['name'],
									],
									[
										'type' => $type['title'],
									]
								);

								$validType[] = $type['name'];
							}
						}

						if (!empty($validType)) {
							EServicePrice::where('company_id', $cob->id)
								->where('category_id', $category->id)
								->whereNotIn('slug', $validType)
								->delete();
						}
					}
				}
			}
		}

		$viewData = array(
			'title' => trans('app.menus.eservice_price.list'),
			'panel_nav_active' => 'master_panel',
			'main_nav_active' => 'master_main',
			'sub_nav_active' => 'eservice_price_list',
			'image' => ''
		);

		return View::make('eservice_price.index', $viewData);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		App::abort(404);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$this->checkAvailableAccess();

		$model = EServicePrice::with(['company', 'category'])->find($this->decodeID($id));

		if ($model) {
			$viewData = array(
				'title' => trans('app.menus.eservice_price.edit'),
				'panel_nav_active' => 'master_panel',
				'main_nav_active' => 'master_main',
				'sub_nav_active' => 'eservice_price_list',
				'image' => '',
				'model' => $model,
				'id' => $this->encodeID($model->id),
			);

			return View::make('eservice_price.edit', $viewData);
		}

		App::abort(404);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$this->checkAvailableAccess();

		$request = Request::all();

		if (Request::ajax()) {
			$validation_rules = [
				'price' => 'required|numeric',
			];

			$validator = Validator::make($request, $validation_rules, []);

			if ($validator->fails()) {
				return [
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				];
			} else {
				$model = EServicePrice::find($this->decodeID($id));
				if ($model) {
					$update = $model->update([
						'price' => $request['price'],
					]);

					if ($update) {
						return [
							'success' => true,
							'message' => trans('app.successes.saved_successfully')
						];
					}
				}
			}
		}

		return [
			'error' => true,
			'message' => trans('app.errors.occurred')
		];
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	private function getModule()
	{
		return $this->module['eservice'];
	}

	private function encodeID($id)
	{
		return Helper::encode($this->getModule()['name'], $id);
	}

	private function decodeID($id)
	{
		return Helper::decode($id, $this->getModule()['name']);
	}

	private function checkAvailableAccess($model = '')
	{
		if (!AccessGroup::hasAccessModule('e-Service') && !AccessGroup::hasAccessModule('e-Service Pricing')) {
			App::abort(404);
		}
	}
}
