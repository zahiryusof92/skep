<?php

use Helper\Helper;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use yajra\Datatables\Facades\Datatables;

class PostponeAGMReasonController extends \BaseController
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
			$model = PostponedAGMReason::query();

			return Datatables::of($model)
				->editColumn('active', function ($model) {
					$status = trans('app.forms.no');
					if ($model->active) {
						$status = trans('app.forms.yes');
					}

					return $status;
				})
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? $model->created_at->format('d-M-Y H:i A') : "-";

					return $created_at;
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('postponeAGMReason.edit', $this->encodeID($model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
					$btn .= '<form action="' . route('postponeAGMReason.destroy', $this->encodeID($model->id)) . '" method="POST" id="delete_form_' . $this->encodeID($model->id) . '" style="display:inline-block;">';
					$btn .= '<input type="hidden" name="_method" value="DELETE">';
					$btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $this->encodeID($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
					$btn .= '</form>';

					return $btn;
				})
				->make(true);
		}

		$viewData = array(
			'title' => trans('app.menus.postpone_agm_reason.name'),
			'panel_nav_active' => 'master_panel',
			'main_nav_active' => 'master_main',
			'sub_nav_active' => 'postpone_agm_reason_list',
			'image' => ''
		);

		return View::make('postpone_agm_reason.index', $viewData);
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
			'title' => trans('app.menus.postpone_agm_reason.create'),
			'panel_nav_active' => 'master_panel',
			'main_nav_active' => 'master_main',
			'sub_nav_active' => 'postpone_agm_reason_list',
			'image' => ''
		);

		return View::make('postpone_agm_reason.create', $viewData);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$this->checkAvailableAccess();

		$request = Request::all();

		$rules = [
			'reason' => 'required|string',
			'sort' => 'required|integer',
			'active' => 'required',
		];

		$validator = Validator::make($request, $rules);
		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$model = PostponedAGMReason::create([
				'name' => $request['reason'],
				'sort' => (!empty($request['sort']) ? $request['sort'] : 0),
				'active' => ($request['active'] == true ? true : false),
			]);

			if ($model) {
				/**
				 * add audit trail
				 */
				$remarks = $this->getModule() . ': ' . $model->name . $this->module['audit']['text']['data_inserted'];
				$this->addAudit(0, "Master Setup", $remarks);

				return Response::json([
					'success' => true,
					'message' => trans('app.successes.saved_successfully')
				]);
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
		$this->checkAvailableAccess();

		$model = PostponedAGMReason::find($this->decodeID($id));

		if ($model) {
			$viewData = array(
				'title' => trans('app.menus.postpone_agm_reason.edit'),
				'panel_nav_active' => 'master_panel',
				'main_nav_active' => 'master_main',
				'sub_nav_active' => 'postpone_agm_reason_list',
				'model' => $model,
				'image' => ''
			);

			return View::make('postpone_agm_reason.edit', $viewData);
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

		$rules = [
			'reason' => 'required|string',
			'sort' => 'required|integer',
			'active' => 'required',
		];

		$validator = Validator::make($request, $rules);
		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$model = PostponedAGMReason::find($this->decodeID($id));
			if ($model) {
				$model->update([
					'name' => $request['reason'],
					'sort' => (!empty($request['sort']) ? $request['sort'] : 0),
					'active' => ($request['active'] == true ? true : false),
				]);

				if ($model) {
					/** Arrange audit fields changes */
					$name_field = $request['reason'] == $model->name ? "" : "name";
					$sort_field = $request['sort'] == $model->sort ? "" : "sort";
					$active_field = $request['active'] == $model->active ? "" : "active";

					$audit_fields_changed = "";
					if (!empty($name_field) || !empty($sort_field)) {
						$audit_fields_changed .= "<br><ul>";
						$audit_fields_changed .= !empty($name_field) ? "<li>$name_field</li>" : "";
						$audit_fields_changed .= !empty($sort_field) ? "<li>$sort_field</li>" : "";
						$audit_fields_changed .= !empty($active_field) ? "<li>$active_field</li>" : "";
						$audit_fields_changed .= "</ul>";
					}
					/** End Arrange audit fields changes */

					/**
					 * add audit trail
					 */
					if (!empty($audit_fields_changed)) {
						$remarks = $this->getModule() . ': ' . $model->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
						$this->addAudit(0, "Master Setup", $remarks);
					}

					return Response::json([
						'success' => true,
						'message' => trans('app.successes.updated_successfully')
					]);
				}
			}
		}

		return Response::json([
			'error' => true,
			'message' => trans('app.errors.occurred')
		]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$model = PostponedAGMReason::find(Helper::decode($id));
		if ($model) {
			$success = $model->delete();
			if ($success) {
				/*
                 * add audit trail
                 */
				$remarks = $this->getModule() . ': ' . $model->name . $this->module['audit']['text']['data_deleted'];
				$this->addAudit(0, "Master Setup", $remarks);

				return Redirect::route('postponeAGMReason.index')->with('success', trans('app.successes.deleted_successfully'));
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
	}

	private function encodeID($id)
	{
		return Helper::encode($id);
	}

	private function decodeID($id)
	{
		return Helper::decode($id);
	}

	private function getModule()
	{
		return 'Postponed AGM Reason';
	}

	private function checkAvailableAccess()
	{
		if (!AccessGroup::hasAccessModule('Postponed AGM Reason')) {
			App::abort(404);
		}
	}
}
