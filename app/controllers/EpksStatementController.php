<?php

use Helper\Helper;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
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
			'title' => trans('app.menus.epks_statement.name'),
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
		$this->checkAvailableAccess();

		if (!Auth::user()->getAdmin()) {
			if (!empty(Auth::user()->file_id)) {
				$file_no = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
			} else {
				$file_no = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('year', 'asc')->get();
			}
		} else {
			if (empty(Session::get('admin_cob'))) {
				$file_no = Files::where('is_deleted', 0)->orderBy('year', 'asc')->get();
			} else {
				$file_no = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('year', 'asc')->get();
			}
		}
		$year = Files::getVPYear();
		$month = EpksStatement::monthList();

		$viewData = array(
			'title' => trans('app.menus.epks_statement.create'),
			'panel_nav_active' => 'epks_panel',
			'main_nav_active' => 'epks_main',
			'sub_nav_active' => 'epks_statement',
			'image' => '',
			'file_no' => $file_no,
			'year' => $year,
			'month' => $month,
		);

		return View::make('epks_statement.create', $viewData);
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
			'file_id' => 'required',
			'year' => 'required',
			'month' => 'required',
		];

		$validator = Validator::make($request, $rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator);
		} else {
			if (Auth::user()->myEpks()) {
				$epks = Auth::user()->myEpks();

				$model = Epks::find($epks->id);
				if ($model) {
					$existing = EpksStatement::where('file_id', $model->file->id)
						->where('month', $request['month'])
						->where('year', $request['year'])
						->count();

					if ($existing <= 0) {
						$statement = EpksStatement::create([
							'file_id' => $model->file->id,
							'strata_id' => $model->file->strata->id,
							'epks_id' => $model->id,
							'month' => $request['month'],
							'year' => $request['year'],
						]);

						return Redirect::route('epksStatement.show', Helper::encode($this->moduleName(), $statement->id))->with('success', trans('app.successes.submit_successfully'));
					}
					return Redirect::back()->with('error', trans('app.errors.exist2', ['attribute' => 'record']));
				}
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
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
			$sells = EpksTrade::where('epks_statement_id', $model->id)
				->where('debit', false)
				->orderBy('id', 'asc')
				->get();

			$buys = EpksTrade::where('epks_statement_id', $model->id)
				->where('debit', true)
				->orderBy('id', 'asc')
				->get();

			$others_income = EpksLedger::where('epks_statement_id', $model->id)
				->where('name', 'others_income')
				->first();

			$salary = EpksLedger::where('epks_statement_id', $model->id)
				->where('name', 'salary')
				->first();

			$general = EpksLedger::where('epks_statement_id', $model->id)
				->where('name', 'general')
				->first();

			$viewData = array(
				'title' => trans('app.menus.epks_statement.name'),
				'panel_nav_active' => 'epks_panel',
				'main_nav_active' => 'epks_main',
				'sub_nav_active' => 'epks_statement',
				'model' => $model,
				'sells' => $sells,
				'buys' => $buys,
				'others_income' => $others_income,
				'salary' => $salary,
				'general' => $general,
				'module' => $this->moduleName(),
				'image' => ''
			);

			// return '<pre>' . print_r($ledgers->toArray(), true) . '</pre>';

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
		$this->checkAvailableAccess();

		$request = Request::all();

		$model = EpksStatement::find(Helper::decode($id, $this->moduleName()));
		if ($model) {
			if (!empty($request['buy_date'])) {
				EpksTrade::where('epks_statement_id', $model->id)
					->where('debit', true)
					->forceDelete();

				for ($i = 0; $i < count($request['buy_date']); $i++) {
					if (!empty($request['buy_date'][$i])) {
						EpksTrade::create([
							'file_id' => $model->file->id,
							'strata_id' => $model->strata->id,
							'epks_id' => $model->epks->id,
							'epks_statement_id' => $model->id,
							'date' => $request['buy_date'][$i],
							'amount' => $request['buy_amount'][$i],
							'debit' => true,
						]);
					}
				}
			}

			if (!empty($request['sell_date'])) {
				EpksTrade::where('epks_statement_id', $model->id)
					->where('debit', false)
					->forceDelete();

				for ($i = 0; $i < count($request['sell_date']); $i++) {
					if (!empty($request['sell_date'][$i])) {
						EpksTrade::create([
							'file_id' => $model->file->id,
							'strata_id' => $model->strata->id,
							'epks_id' => $model->epks->id,
							'epks_statement_id' => $model->id,
							'date' => $request['sell_date'][$i],
							'amount' => $request['sell_amount'][$i],
							'debit' => false,
						]);
					}
				}
			}

			if (!empty($request['ledger'])) {
				EpksLedger::where('epks_statement_id', $model->id)->forceDelete();

				foreach ($request['ledger'] as $name => $value) {
					EpksLedger::create([
						'file_id' => $model->file->id,
						'strata_id' => $model->strata->id,
						'epks_id' => $model->epks->id,
						'epks_statement_id' => $model->id,
						'name' => $name,
						'amount' => $value,
					]);

					if ($name == 'nett_profit') {
						$model->update([
							'profit' => $value,
						]);
					}
				}
			}

			// return '<pre>' . print_r($request, true) . '</pre>';

			return Redirect::back()->with('success', trans('app.successes.submit_successfully'));
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
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

		$model = EpksStatement::find(Helper::decode($id, $this->moduleName()));
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
