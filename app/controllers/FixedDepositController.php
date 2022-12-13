<?php

use Helper\Helper;

class FixedDepositController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
		//get user permission
		$user_permission = AccessGroup::getAccessPermission(Auth::user()->id);
		$files = Files::findOrFail(Helper::decode($id));
		$fixedDeposit = FixedDeposit::where('file_id', $files->id)->first();
		$image = OtherDetails::where('file_id', $files->id)->first();

		$viewData = array(
			'title' => trans('app.menus.cob.update_cob_file'),
			'panel_nav_active' => 'cob_panel',
			'main_nav_active' => 'cob_main',
			'sub_nav_active' => ($files->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
			'user_permission' => $user_permission,
			'file' => $files,
			'fixedDeposit' => $fixedDeposit,
			'image' => (!empty($image->image_url) ? $image->image_url : '')
		);

		return View::make('fixed_deposit.edit', $viewData);
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
