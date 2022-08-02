<?php

use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class EServiceController extends \BaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->checkAvailableAccess();

		$viewData = array(
			'title' => trans('app.menus.eservice.list'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_list',
			'image' => ''
		);

		return View::make('eservice.index', $viewData);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->checkAvailableAccess();

		$viewData = array(
			'title' => trans('app.menus.eservice.create'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_create',
			'image' => ""
		);

		return View::make('eservice.create', $viewData);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$this->checkAvailableAccess();
		
		if (Request::ajax()) {
			$data = Request::all();
			$validate_fields = array(
				'cob' => 'required',
				'type' => 'required',
				'date' => 'required',
			);

			$custom_messages = [];
			$extraValidation = $this->getFormFields($data);
			$fields = $this->getModule()['fields'];
			if (count($extraValidation)) {
				foreach ($extraValidation['attributes'] as $attribute) {
					if ($fields[$attribute]['required']) {
						$validate_fields[$attribute] = 'required';
						$custom_messages[$attribute . ".required"] = "This " . $fields[$attribute]['label'] . " field is requied";
					}
				}
			}

			$validator = Validator::make($data, $validate_fields, $custom_messages);

			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
				$cob = Company::where('short_name', $data['cob'])->first();
			}
		}

		return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ]);
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

	/**
	 * Display the specified resource.
	 *
	 * @param Request $request
	 * @return View
	 */
	public function getForm()
	{
		if (Request::ajax()) {
			$request = Request::all();
			$data = $this->getFormFields($request);
			$data['fields'] = $this->getModule()['fields'];
			if (!empty($request['id'])) {
				$data['model'] = EService::findOrFail(Helper::decode($request['id'], $this->getModule()['name']));
			}
			return View::make('eservice.form', $data);
		}
	}

	public function getFormFields($request)
	{
		$module_config = $this->getModule();
		$data['attributes'] = $module_config['cob'][Str::lower($request['cob'])]['type'][$request['type']]['only'];

		return $data;
	}

	private function getModule()
	{
		return $this->module['eservice'];
	}

	private function checkAvailableAccess($model = '')
	{
		if (!Auth::user()->isJMB()) {
			App::abort(404);
		}
	}
}
