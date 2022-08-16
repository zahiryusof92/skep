<?php

use Helper\Helper;

class EServicePriceController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Request::ajax()) {
			$prices = EServicePrice::orderBy('company_id')->get();

			if (count($prices) > 0) {
				$data = array();
				foreach ($prices as $price) {
					$button = "";
					$button .= '<button type="button" class="btn btn-xs btn-success" onclick="window.location=\'' . URL::action('EServicePriceController@edit', Helper::encode($price->id)) . '\'"><i class="fa fa-pencil"></i></button>&nbsp;';
					$button .= '<button class="btn btn-xs btn-danger" onclick="deletePrice(\'' . Helper::encode($price->id) . '\')"><i class="fa fa-trash"></i></button>';

					$data_raw = array(
						$price->company->name,
						$price->category->description,
						$price->type,
						$price->price,
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
				foreach ($categories as $category) {
					if (isset($this->module['eservice']['cob'][Str::lower($council)]['type'])) {
						foreach ($this->module['eservice']['cob'][Str::lower($council)]['type'] as $type) {
							$exist = EServicePrice::where('company_id', $cob->id)
								->where('category_id', $category->id)
								->where('slug', $type['name'])
								->first();

							if (!$exist) {
								$price = new EServicePrice();
								$price->company_id = $cob->id;
								$price->category_id = $category->id;
								$price->type = $type['title'];
								$price->slug = $type['name'];
								$price->price = 0;
								$price->save();
							} else {
								$exist->price = 10;
								$exist->save();
							}
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
		$viewData = array(
			'title' => trans('app.menus.eservice_price.create'),
			'panel_nav_active' => 'master_panel',
			'main_nav_active' => 'master_main',
			'sub_nav_active' => 'eservice_price_list',
			'image' => ''
		);

		return View::make('eservice_price.create', $viewData);
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
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
}
