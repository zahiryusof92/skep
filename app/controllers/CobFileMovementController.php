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
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$model = FileMovement::with('fileMovementUsers')
				->where('is_deleted', 0)
				->where('file_id', Helper::decode($file_id));

			return Datatables::of($model)
				->editColumn('file_id', function ($model) {
					return $model->file->file_no;
				})
				->editColumn('file_movement_users', function ($model) {
					$content = '';

					if ($model->fileMovementUsers->count() > 0) {
						$content .= '<div class="row"><ul>';
						foreach ($model->fileMovementUsers as $fileMovementUser) {
							$content .= '<li>' . ($fileMovementUser->user ? $fileMovementUser->user->full_name . ' (' . $fileMovementUser->created_at->format('Y-m-d') .')' : '') . '</li>';
						}
						$content .= '</ul></div>';
					}

					return $content;
				})
				->addColumn('action', function ($model) use ($file_id) {
					$btn = '';

					// if (AccessGroup::hasUpdateModule('File Movement')) {
					// $btn .= '<a href="' . route('cob.file-movement.edit', [Helper::encode($this->module['file_movement']['name'], $model->id), $file_id]) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
					// $btn .= '<form action="' . route('cob.file-movement.destroy', Helper::encode($this->module['file_movement']['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->module['file_movement']['name'], $model->id) . '" style="display:inline-block;">'
					// 	. '<input type="hidden" name="_method" value="DELETE">'
					// 	. '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->module['file_movement']['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>'
					// 	. '</form>';
					// }

					return $btn;
				})
				->make(true);
		}

		$file = Files::find(Helper::decode($file_id));

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
		$this->checkAvailableAccess();

		$file = Files::find(Helper::decode($file_id));
		$userList = User::leftJoin('role', 'users.role', '=', 'role.id')
                ->leftJoin('company', 'users.company_id', '=', 'company.id')
                ->select(['users.*', 'role.name as role'])
                ->where('company.id', $file->company_id)
				->whereIn('role.name', [Role::COB, Role::COB_BASIC, Role::COB_BASIC_ADMIN, Role::COB_MANAGER, Role::COB_PREMIUM, Role::COB_PREMIUM_ADMIN])
                ->where('users.is_active', 1)
				->where('users.is_deleted', 0)
				->get();

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
			'title' => 'required|string|max:255',
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

			if (!empty($data['assigned_to'])) {
				foreach ($data['assigned_to'] as $key => $val) {
					if ($val == '') {
						$error_assigned['assigned_to_' . $key] = trans('This field is required');
					}
				}

				if (count($error_assigned) > 0) {
					return Response::json([
						'error' => true,
						'errors' => $error_assigned,
						'message' => trans('Validation Fail')
					]);
				} else {
					$model = FileMovement::create([
						'file_id' => Helper::decode($data['file'], $this->module['cob']['file']['name']),
						'strata' => $data['strata'],
						'title' => $data['title'],
						'remarks' => $data['remarks'],
						'company_id' => Auth::user()->company_id
					]);
	
					if ($model) {
						foreach ($data['assigned_to'] as $key => $val) {
							$user = User::find($val);
							if ($user) {
								array_push($assigned_arr, new FileMovementUser(['file_movement_id' => $model->id, 'user_id' => $user->id]));
							}
						}

						if (!empty($assigned_arr)) {
							$model->fileMovementUsers()->delete();
							$model->fileMovementUsers()->saveMany($assigned_arr);
						}

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
		$this->checkAvailableAccess();

		$model = FileMovement::find(Helper::decode($id, $this->module['file_movement']['name']));
		if ($model) {
			$file = Files::find(Helper::decode($file_id));
			$userList = User::leftJoin('role', 'users.role', '=', 'role.id')
                ->leftJoin('company', 'users.company_id', '=', 'company.id')
                ->select(['users.*', 'role.name as role'])
                ->where('company.id', $file->company_id)
				->whereIn('role.name', [Role::COB, Role::COB_BASIC, Role::COB_BASIC_ADMIN, Role::COB_MANAGER, Role::COB_PREMIUM, Role::COB_PREMIUM_ADMIN])
                ->where('users.is_active', 1)
				->where('users.is_deleted', 0)
				->get();

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

		return Redirect::route('cob.file-movement.index', [Helper::encode($file_id)])->with('error', trans('app.errors.occurred'));
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
			'title' => 'required|string|max:255',
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

			if ($model) {
				if (!empty($data['assigned_to'])) {
					foreach ($data['assigned_to'] as $key => $val) {
						if ($val == '') {
							$error_assigned['assigned_to_' . $key] = trans('This field is required');
						}

						$user = User::find($val);
						if ($user) {
							array_push($assigned_arr, new FileMovementUser(['file_movement_id' => $model->id, 'user_id' => $user->id]));
						}
					}

					if (count($error_assigned) > 0) {
						return Response::json([
							'error' => true,
							'errors' => $error_assigned,
							'message' => trans('Validation Fail')
						]);
					} else {
						/** Arrange audit fields changes */
						$audit_fields_changed = '';
						$new_line = '';
						$new_line .= Helper::decode($data['file'], $this->module['cob']['file']['name']) != $model->file_id ? "file id, " : "";
						$new_line .= $data['strata'] != $model->strata ? "strata, " : "";
						$new_line .= $data['title'] != $model->title ? "title, " : "";
						$new_line .= $data['remarks'] != $model->remarks ? "remarks, " : "";
						if (!empty($new_line)) {
							$audit_fields_changed .= "<br/><ul><li> Fields : (";
							$audit_fields_changed .= Helper::str_replace_last(', ', '', $new_line) . ")</li></ul>";
						}
						/** End Arrange audit fields changes */

						$success = $model->update([
							'file_id' => Helper::decode($data['file'], $this->module['cob']['file']['name']),
							'strata' => $data['strata'],
							'title' => $data['title'],
							'remarks' => $data['remarks']
						]);

						if ($success) {
							$model->fileMovementUsers()->delete();
							$model->fileMovementUsers()->saveMany($assigned_arr);

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

				return Redirect::route('cob.file-movement.index', [Helper::encode($model->file_id)])->with('success', trans('app.successes.deleted_successfully'));
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
	}

	private function checkAvailableAccess() {
        if(!AccessGroup::hasAccessModule('File Movement')) {
            App::abort(404);
        }
    }
}
