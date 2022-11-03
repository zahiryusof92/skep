<?php

use Helper\Helper;
use Illuminate\Support\Facades\Request;
use yajra\Datatables\Facades\Datatables;

class EpksStatementController extends \BaseController
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
			$model = EpksStatement::query();

			return Datatables::of($model)
				->editColumn('month', function ($model) {
					return $model->monthName();
				})
				->editColumn('file_id', function ($model) {
					return $model->file_id ? "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file->id)) . "'>" . $model->file->file_no . "</a>" : "-";
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})
				->editColumn('created_at', function ($model) {
					$created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

					return $created_at;
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('epksStatement.show', Helper::encode($this->moduleName(), $model->id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';
					$btn .= '<form action="' . route('epksStatement.destroy', Helper::encode($this->moduleName(), $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->moduleName(), $model->id) . '" style="display:inline-block;">';
					$btn .= '<input type="hidden" name="_method" value="DELETE">';
					$btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->moduleName(), $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
					$btn .= '</form>';

					return $btn;
				})
				->make(true);
		}

		$viewData = array(
			'title' => trans('app.menus.epks_statement'),
			'panel_nav_active' => 'epks_panel',
			'main_nav_active' => 'epks_main',
			'sub_nav_active' => 'epks_statement',
			'image' => ""
		);

		return View::make('epks_statement.index', $viewData);
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
		$this->checkAvailableAccess();

		$request = Request::all();

		return '<pre>' . print_r($request, true) . '</pre>';
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->checkAvailableAccess();

		$model = EpksStatement::findOrFail(Helper::decode($id, $this->moduleName()));
		if ($model) {
			$viewData = array(
				'title' => trans('app.menus.epks_statement'),
				'panel_nav_active' => 'epks_panel',
				'main_nav_active' => 'epks_main',
				'sub_nav_active' => 'epks_statement',
				'model' => $model,
				'image' => ""
			);
	
			return View::make('epks_statement.show', $viewData);
		}

		App::abort(404);
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
		$this->checkAvailableAccess();

		$model = EpksStatement::findOrFail(Helper::decode($id, $this->moduleName()));
		if ($model) {
			$success = $model->delete();

			if ($success) {
				/*
                 * add audit trail
                 */
				$module = Str::upper($this->moduleName());
				$remarks = $module . ': ' . $model->id . $this->module['audit']['text']['data_deleted'];
				$this->addAudit($model->file_id, $module, $remarks);

				return Redirect::to('epksStatement')->with('success', trans('app.successes.deleted_successfully'));
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
	}

	private function moduleName()
	{
		return 'EPKS Statement';
	}

	private function checkAvailableAccess($model = '')
	{
		if (!Auth::user()->hasEpks()) {
			App::abort(404);
		}
	}
}
