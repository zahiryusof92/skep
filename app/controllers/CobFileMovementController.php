<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;

class CobFileMovementController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($file_id)
	{
		if (Request::ajax()) {
			$model = FileMovement::with(['file', 'user'])
				->self()
				->where('files.id', Helper::decode($file_id, $this->module['cob']['file']['name']));

			return Datatables::of($model)
				->editColumn('file_id', function ($model) {
					return $model->file->file_no;
				})
				->editColumn('assigned_to', function ($model) {
					$content = '<ul>';
					foreach (unserialize($model->assigned_to) as $assigned) {
						$user = User::find($assigned['value']);
						$content .= "<li>" . $user->full_name . " (" . $assigned['created_at'] . ")</li>";
					}
					$content .= "</ul>";
					return $content;
				})
				->addColumn('action', function ($model) use ($file_id) {
					$btn = '<a href="' . route('cob.file-movement.edit', [Helper::encode($this->module['file_movement']['name'], $model->id), $file_id]) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;'
						. '<form action="' . route('cob.file-movement.destroy', Helper::encode($this->module['file_movement']['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->module['file_movement']['name'], $model->id) . '" style="display:inline-block;">'
						. '<input type="hidden" name="_method" value="DELETE">'
						. '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->module['file_movement']['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>'
						. '</form>';

					return $btn;
				})
				->make(true);
		}

		$file = Files::find(Helper::decode($file_id, $this->module['cob']['file']['name']));

		$viewData = array(
			'title' => trans('app.menus.cob.update_cob_file'),
			'panel_nav_active' => 'cob_panel',
			'main_nav_active' => 'cob_main',
			'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
			'files' => $file,
			'image' => ''
		);

		return View::make('page_en.cob.file-movement.index', $viewData);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($file_id)
	{
		$file = Files::find(Helper::decode($file_id, $this->module['cob']['file']['name']));
		$userList = User::self()->whereNotIn('role', [1, 2, 24])->get();

		$viewData = array(
			'title' => trans('app.menus.cob.update_cob_file'),
			'panel_nav_active' => 'cob_panel',
			'main_nav_active' => 'cob_main',
			'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
			'files' => $file,
			'userList' => $userList,
			'image' => ""
		);

		return View::make('page_en.cob.file-movement.create', $viewData);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$data['file'] = Helper::decode($data['file'], $this->module['cob']['file']['name']);
		$validator = Validator::make($data, array(
			'file' => 'required|exists:files,id,is_deleted,' . false,
			'remarks' => 'required'
		));

		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$assigned_arr = [];
			$error_assigned = [];
			foreach ($data['assigned_to'] as $key => $val) {
				if ($val == '') {
					$error_assigned['assigned_to_' . $key] = trans('This field is required');
				}
				$assigned_arr[$key] = [
					'value' => $val,
					'created_at' => Carbon::now()->format('Y-m-d'),
				];
			}
			if (count($error_assigned) > 0) {
				return Response::json([
					'error' => true,
					'errors' => $error_assigned,
					'message' => trans('Validation Fail')
				]);
			}
			$model = FileMovement::create([
				'file_id' => Helper::decode($data['file'], $this->module['cob']['file']['name']),
				'strata' => $data['strata'],
				'assigned_to' => serialize($assigned_arr),
				'remarks' => $data['remarks'],
				'company_id' => Auth::user()->company_id
			]);

			if ($model) {
				/*
                 * add audit trail
                 */
				$remarks = 'File Movement: ' . $model->id . $this->module['audit']['text']['data_inserted'];
				$this->addAudit($model->file_id, "COB File", $remarks);

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
	public function edit($id, $file_id)
	{
		$model = FileMovement::find(Helper::decode($id, $this->module['file_movement']['name']));
		if ($model) {
			$file = Files::find(Helper::decode($file_id, $this->module['cob']['file']['name']));
			$userList = User::self()->whereNotIn('role', [1, 2, 24])->get();

			$viewData = array(
				'title' => trans('app.menus.cob.update_cob_file'),
				'panel_nav_active' => 'cob_panel',
				'main_nav_active' => 'cob_main',
				'sub_nav_active' => ($file->is_active == 2 ? 'cob_before_vp_list' : 'cob_list'),
				'files' => $file,
				'userList' => $userList,
				'model' => $model,
				'image' => ""
			);

			return View::make('page_en.cob.file-movement.edit', $viewData);
		}

		return Redirect::route('cob.file-movement.index', [Helper::encode($this->module['cob']['file']['name'], $file_id)])->with('error', trans('app.errors.occurred'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$data = Input::all();
		$data['file'] = Helper::decode($data['file'], $this->module['cob']['file']['name']);

		$validator = Validator::make($data, array(
			'file' => 'required|exists:files,id,is_deleted,' . false,
			'remarks' => 'required'
		));

		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$model = FileMovement::find(Helper::decode($id, $this->module['file_movement']['name']));
			$assigned_arr = [];
			$error_assigned = [];
			foreach ($data['assigned_to'] as $key => $val) {
				if ($val == '') {
					$error_assigned['assigned_to_' . $key] = trans('This field is required');
				}
				// $collection = new ArrayCollection(unserialize($model->assigned_to));
				// $filter = $collection->filter(function($element) use($val){
				//     return $val == $element['value'];
				// });
				// if(!$filter->isEmpty()) {
				//     $item = $filter->first();
				//     $assigned_arr[$key] = [
				//         'value' => $item['value'],
				//         'created_at' => $item['created_at'],
				//     ];
				$collection = unserialize($model->assigned_to);
				if (!empty($collection[$key]) && $val == $collection[$key]['value']) {
					$assigned_arr[$key] = [
						'value' => $collection[$key]['value'],
						'created_at' => $collection[$key]['created_at'],
					];
				} else {
					$assigned_arr[$key] = [
						'value' => $val,
						'created_at' => Carbon::now()->format('Y-m-d'),
					];
				}
			}
			if (count($error_assigned) > 0) {
				return Response::json([
					'error' => true,
					'errors' => $error_assigned,
					'message' => trans('Validation Fail')
				]);
			}
			if ($model) {
				/** Arrange audit fields changes */
				$audit_fields_changed = '';
				$new_line = '';
				$new_line .= Helper::decode($data['file'], $this->module['cob']['file']['name']) != $model->file_id ? "file id, " : "";
				$new_line .= $data['strata'] != $model->strata ? "strata, " : "";
				$new_line .= serialize($assigned_arr) != $model->assigned_to ? "assigned to, " : "";
				$new_line .= $data['remarks'] != $model->remarks ? "remarks, " : "";
				if (!empty($new_line)) {
					$audit_fields_changed .= "<br/><ul><li> Fields : (";
					$audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
				}
				/** End Arrange audit fields changes */

				$success = $model->update([
					'file_id' => Helper::decode($data['file'], $this->module['cob']['file']['name']),
					'strata' => $data['strata'],
					'assigned_to' => serialize($assigned_arr),
					'remarks' => $data['remarks']
				]);

				if ($success) {
					/*
                     * add audit trail
                     */
					if (!empty($audit_fields_changed)) {
						$remarks = 'File Movement: ' . $model->id . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
						$this->addAudit($model->file_id, "COB File", $remarks);
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
		$model = FileMovement::find(Helper::decode($id, $this->module['file_movement']['name']));
		if ($model) {
			$success = $model->update([
				'is_deleted' => true
			]);

			if ($success) {
				/*
                 * add audit trail
                 */
				$remarks = 'File Movement : ' . $model->id . $this->module['audit']['text']['data_deleted'];
				$this->addAudit($model->file_id, "COB File", $remarks);

				return Redirect::route('cob.file-movement.index', [Helper::encode($this->module['cob']['file']['name'], $model->file_id)])->with('success', trans('app.successes.deleted_successfully'));
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
	}
}