<?php

use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;

class ComplaintTypeController extends \BaseController
{
	public function __construct()
	{
		Helper::isAllow(0, 0, !AccessGroup::hasAccessModule('Complaint Category') || !Helper::showMPKLModules());
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($complaintCategoryId)
	{
		$parent = ComplaintCategory::find($this->decodeID($complaintCategoryId));
		if (!$parent) {
			App::abort(404);
		}

		if (Request::ajax()) {
			$model = ComplaintType::where('complaint_category_id', $parent->id);

			return Datatables::of($model)
				->editColumn('is_active', function ($model) {
					if ($model->is_active) {
						return trans('app.forms.yes');
					}

					return trans('app.forms.no');
				})
				->addColumn('action', function ($model) use ($parent) {
					if (AccessGroup::hasUpdateModule('Complaint Category')) {
						$btn = '<a href="' . route('complaintCategory.complaintType.edit', [$this->encodeID($parent->id), $this->encodeID($model->id)]) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;'
							. '<form action="' . route('complaintCategory.complaintType.destroy', [$this->encodeID($parent->id), $this->encodeID($model->id)]) . '" method="POST" id="delete_form_' . $this->encodeID($model->id) . '" style="display:inline-block;">'
							. '<input type="hidden" name="_method" value="DELETE">'
							. '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $this->encodeID($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>'
							. '</form>';

						return $btn;
					} else {
						return '';
					}
				})
				->make(true);
		}

		$viewData = array(
			'title' => $parent->name,
			'panel_nav_active' => 'master_panel',
			'main_nav_active' => 'master_main',
			'sub_nav_active' => 'complaint_category_list',
			'image' => '',
			'parent' => $parent,
		);

		return View::make('complaint_type.index', $viewData);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($complaintCategoryId)
	{
		$parent = ComplaintCategory::find($this->decodeID($complaintCategoryId));
		if (!$parent) {
			App::abort(404);
		}

		$viewData = array(
			'title' => $parent->name . ' - ' . trans('app.menus.master.add_complaint_type'),
			'panel_nav_active' => 'master_panel',
			'main_nav_active' => 'master_main',
			'sub_nav_active' => 'complaint_category_list',
			'image' => '',
			'parent' => $parent,
		);

		return View::make('complaint_type.create', $viewData);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($complaintCategoryId)
	{
		$input = Input::all();
		$rules = [
			'name' => 'required',
			'color_code' => 'required',
			'is_active' => 'required',
		];

		$validator = Validator::make($input, $rules);
		if (!$validator->fails()) {
			$parent = ComplaintCategory::find($this->decodeID($complaintCategoryId));
			if ($parent) {
				ComplaintType::create([
					'complaint_category_id' => $parent->id,
					'name' => $input['name'],
					'color_code' => $input['color_code'],
					'sort_no' => (!empty($input['sort_no']) ? $input['sort_no'] : null),
					'is_active' => $input['is_active'],
				]);

				return Redirect::route('complaintCategory.complaintType.index', $this->encodeID($parent->id))->with('success', trans('app.successes.saved_successfully'));
			} else {
				return Redirect::back()->with('error', trans('app.errors.occurred'));
			}
		} else {
			return Redirect::back()->withErrors($validator)->withInput($input);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($complaintCategoryId, $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($complaintCategoryId, $id)
	{
		$parent = ComplaintCategory::find($this->decodeID($complaintCategoryId));
		if (!$parent) {
			App::abort(404);
		}

		$model = ComplaintType::find($this->decodeID($id));
		if (!$model) {
			App::abort(404);
		}

		$viewData = array(
			'title' => $parent->name . ' - ' . trans('app.menus.master.edit_complaint_type'),
			'panel_nav_active' => 'master_panel',
			'main_nav_active' => 'master_main',
			'sub_nav_active' => 'complaint_category_list',
			'image' => '',
			'parent' => $parent,
			'model' => $model,
		);

		return View::make('complaint_type.edit', $viewData);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($complaintCategoryId, $id)
	{
		$input = Input::all();
		$rules = [
			'name' => 'required',
			'color_code' => 'required',
			'is_active' => 'required',
		];

		$validator = Validator::make($input, $rules);
		if (!$validator->fails()) {
			$parent = ComplaintCategory::find($this->decodeID($complaintCategoryId));
			if ($parent) {
				$model = ComplaintType::find($this->decodeID($id));
				if ($model) {
					$model->update([
						'name' => $input['name'],
						'color_code' => $input['color_code'],
						'sort_no' => (!empty($input['sort_no']) ? $input['sort_no'] : null),
						'is_active' => $input['is_active'],
					]);

					return Redirect::to(route('complaintCategory.complaintType.index', $this->encodeID($parent->id)))->with('success', trans('app.successes.updated_successfully'));
				} else {
					return Redirect::back()->with('error', trans('app.errors.occurred'));
				}
			} else {
				return Redirect::back()->with('error', trans('app.errors.occurred'));
			}
		} else {
			return Redirect::back()->withErrors($validator)->withInput($input);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($complaintCategoryId, $id)
	{
		$parent = ComplaintCategory::find($this->decodeID($complaintCategoryId));
		if ($parent) {
			$model = ComplaintType::find($this->decodeID($id));
			if ($model) {
				$model->delete();

				return Redirect::to(route('complaintCategory.complaintType.index', $this->encodeID($parent->id)))->with('success', trans('app.successes.deleted_successfully'));
			} else {
				return Redirect::back()->with('error', trans('app.errors.occurred'));
			}
		} else {
			return Redirect::back()->with('error', trans('app.errors.occurred'));
		}
	}

	private function encodeID($id)
	{
		return Helper::encode($id);
	}

	private function decodeID($id)
	{
		return Helper::decode($id);
	}
}
