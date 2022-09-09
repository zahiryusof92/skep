<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use yajra\Datatables\Facades\Datatables;

class PostponeAGMController extends \BaseController
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
			$model = PostponedAGM::self()->notDraft();

			if (!empty(Input::get('company'))) {
				$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
				if ($cob) {
					$model = $model->where('postponed_agms.company_id', $cob->id);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
				$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

				$model = $model->whereBetween('postponed_agms.created_at', [$start_date, $end_date]);
			}

			if (!empty(Input::get('letter_type'))) {
				$model = $model->where('postponed_agms.type', Input::get('letter_type'));
			}

			return Datatables::of($model)
				->addColumn('checkbox', function ($model) {
					$html = '';
					$html .= '<div class="checkbox">';
					$html .= '<label for="selected_' . $model->id . '">';
					$html .= '<input type="checkbox" id="selected_' . $model->id . '" name="selected[]" value="' . $model->id . '"/>';
					$html .= '</label>';
					$html .= '</div>';

					return $html;
				})
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('postponeAGM.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";

					return $created_at;
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})

				->editColumn('application_no', function ($model) {
					return $model->application_no;
				})
				->editColumn('status', function ($model) {
					return $model->getStatusBadge();
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('postponeAGM.show', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';
					if (!Str::contains(Request::fullUrl(), 'approval') && !in_array($model->status, [PostponedAGM::APPROVED, PostponedAGM::REJECTED, PostponedAGM::PENDING])) {
						$btn .= '<a href="' . route('postponeAGM.edit', $this->encodeID($model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
						// $btn .= '<form action="' . route('postponeAGM.destroy', $this->encodeID($model->id)) . '" method="POST" id="delete_form_' . $this->encodeID($model->id) . '" style="display:inline-block;">';
						// $btn .= '<input type="hidden" name="_method" value="DELETE">';
						// $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $this->encodeID($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
						// $btn .= '</form>';
					}

					return $btn;
				})
				->make(true);
		}

		if (empty(Session::get('admin_cob'))) {
			$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
		} else {
			$company = Company::where('id', Session::get('admin_cob'))->get();
		}

		$viewData = array(
			'title' => trans('app.menus.agm_postpone.review'),
			'panel_nav_active' => 'agm_postpone_panel',
			'main_nav_active' => 'agm_postpone_main',
			'sub_nav_active' => 'agm_postpone_list',
			'company' => $company,
			'image' => ''
		);

		return View::make('postpone_agm.index', $viewData);
	}

	public function approved()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$model = PostponedAGM::self()->approved();

			if (!empty(Input::get('company'))) {
				$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
				if ($cob) {
					$model = $model->where('postponed_agms.company_id', $cob->id);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
				$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

				$model = $model->whereBetween('postponed_agms.created_at', [$start_date, $end_date]);
			}

			if (!empty(Input::get('letter_type'))) {
				$model = $model->where('postponed_agms.type', Input::get('letter_type'));
			}

			return Datatables::of($model)
				->addColumn('checkbox', function ($model) {
					$html = '';
					$html .= '<div class="checkbox">';
					$html .= '<label for="selected_' . $model->id . '">';
					$html .= '<input type="checkbox" id="selected_' . $model->id . '" name="selected[]" value="' . $model->id . '"/>';
					$html .= '</label>';
					$html .= '</div>';

					return $html;
				})
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('postponeAGM.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";

					return $created_at;
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})

				->editColumn('application_no', function ($model) {
					return $model->application_no;
				})
				->editColumn('status', function ($model) {
					return $model->getStatusBadge();
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('postponeAGM.show', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';

					return $btn;
				})
				->make(true);
		}

		if (empty(Session::get('admin_cob'))) {
			$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
		} else {
			$company = Company::where('id', Session::get('admin_cob'))->get();
		}

		$viewData = array(
			'title' => trans('app.menus.agm_postpone.approved'),
			'panel_nav_active' => 'agm_postpone_panel',
			'main_nav_active' => 'agm_postpone_main',
			'sub_nav_active' => 'agm_postpone_approved',
			'company' => $company,
			'image' => ''
		);

		return View::make('postpone_agm.approved_list', $viewData);
	}

	public function rejected()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$model = PostponedAGM::self()->rejected();

			if (!empty(Input::get('company'))) {
				$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
				if ($cob) {
					$model = $model->where('postponed_agms.company_id', $cob->id);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
				$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

				$model = $model->whereBetween('postponed_agms.created_at', [$start_date, $end_date]);
			}

			if (!empty(Input::get('letter_type'))) {
				$model = $model->where('postponed_agms.type', Input::get('letter_type'));
			}

			return Datatables::of($model)
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('postponeAGM.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";

					return $created_at;
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})
				->editColumn('application_no', function ($model) {
					return $model->application_no;
				})
				->editColumn('status', function ($model) {
					return $model->getStatusBadge();
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('postponeAGM.show', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';

					return $btn;
				})
				->make(true);
		}

		if (empty(Session::get('admin_cob'))) {
			$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
		} else {
			$company = Company::where('id', Session::get('admin_cob'))->get();
		}

		$viewData = array(
			'title' => trans('app.menus.agm_postpone.rejected'),
			'panel_nav_active' => 'agm_postpone_panel',
			'main_nav_active' => 'agm_postpone_main',
			'sub_nav_active' => 'agm_postpone_rejected',
			'company' => $company,
			'image' => ''
		);

		return View::make('postpone_agm.rejected_list', $viewData);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$viewData = array(
			'title' => trans('app.menus.agm_postpone.create'),
			'panel_nav_active' => 'agm_postpone_panel',
			'main_nav_active' => 'agm_postpone_main',
			'sub_nav_active' => 'agm_postpone_create',
			'image' => ''
		);

		return View::make('postpone_agm.create', $viewData);
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
			'reason' => 'required',
		];

		$validator = Validator::make($request, $rules);
		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$file = Files::with(['company', 'strata'])->find(Auth::user()->file_id);
			if ($file) {
				$application_no = Auth::user()->id . date('YmdHis');

				$application = PostponedAGM::create([
					'company_id' => $file->company->id,
					'file_id' => $file->id,
					'strata_id' => $file->strata->id,
					'user_id' => Auth::user()->id,
					'application_no' => $application_no,
					'reason' => $request['reason'],
					'attachment' => (!empty($request['attachment']) ? $request['attachment'] : null),
					'status' => PostponedAGM::PENDING,
				]);

				if ($application) {
					/**
					 * Send an email to JMB / MC and copy to COB
					 */
					if (Config::get('mail.driver') != '') {
						$delay = 0;

						if (!empty($application->user->email) && Helper::validateEmail($application->user->email)) {
							// Mail::later(Carbon::now()->addSeconds($delay), 'emails.postpone_agm.new_application', array('model' => $application, 'status' => $application->getStatusText()), function ($message) use ($application) {
							// 	$message->to($application->user->email, $application->user->full_name)->subject('New Application');
							// });

							Mail::send('emails.postpone_agm.new_application', array('model' => $application, 'status' => $application->getStatusText()), function ($message) use ($application) {
								$message->to($application->user->email, $application->user->full_name)->subject('New Application');
							});
						}

						if ($application->user->isJMB() || $application->user->isMC()) {
							// Mail::later(Carbon::now()->addSeconds($delay), 'emails.postpone_agm.new_application_cob', array('model' => $application, 'date' => $application->created_at->toDayDateTimeString(), 'status' => $application->getStatusText()), function ($message) {
							// 	$message->to('cob@mbpj.gov.my', 'COB')->subject('New Application');
							// });

							Mail::send('emails.postpone_agm.new_application_cob', array('model' => $application, 'date' => $application->created_at->toDayDateTimeString(), 'status' => $application->getStatusText()), function ($message) {
								$message->to('cob@mbpj.gov.my', 'COB')->subject('New Application');
							});
						}
					}

					/**
					 * add audit trail
					 */
					$module = Str::upper($this->getModule());
					$remarks = $module . ': New Application #' . $application->application_no . ' has been submitted.';
					$this->addAudit($application->file_id, $module, $remarks);

					return Response::json([
						'success' => true,
						'id' => $this->encodeID($application->id),
						'message' => trans('app.successes.saved_successfully')
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->checkAvailableAccess();

		$model = PostponedAGM::with(['file', 'strata', 'user'])->find($this->decodeID($id));

		if ($model) {
			$statusOptions = PostponedAGM::getStatusOption();

			if ($model->status == PostponedAGM::PENDING) {
				$sub_nav_active = 'agm_postpone_list';
			} else if ($model->status == PostponedAGM::APPROVED) {
				$sub_nav_active = 'agm_postpone_approved';
			} else {
				$sub_nav_active = 'agm_postpone_rejected';
			}

			$viewData = array(
				'title' => trans('app.menus.agm_postpone.show'),
				'panel_nav_active' => 'agm_postpone_panel',
				'main_nav_active' => 'agm_postpone_main',
				'sub_nav_active' => $sub_nav_active,
				'model' => $model,
				"statusOptions" => $statusOptions,
				'image' => ""
			);

			return View::make('postpone_agm.show', $viewData);
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
		//
	}

	public function fileUpload()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$files = Request::file();

			foreach ($files as $file) {
				$destinationPath = Config::get('constant.file_directory.postponed_agm');
				$filename = date('YmdHis') . "_" . $file->getClientOriginalName();
				$upload = $file->move($destinationPath, $filename);

				if ($upload) {
					return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
				}
			}
		}

		return Response::json(['error' => true, 'message' => "Fail"]);
	}

	public function submitByCOB($id)
	{
		$this->checkAvailableAccess();

		$request = Request::all();
		$rules = [];

		$status = $request['status'];

		$rules = array(
			'status' => 'required',
		);

		if ($status == PostponedAGM::REJECTED) {
			$rules['approval_remark'] = 'required';;
		}

		$validator = Validator::make($request, $rules);

		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			if ($status == PostponedAGM::APPROVED) {
				$this->approvedByID($id);
			} else if ($status == PostponedAGM::REJECTED) {
				$approval_remark = (isset($request['approval_remark']) ? $request['approval_remark'] : null);

				$this->rejectedByID($id, $approval_remark);
			}

			return Response::json([
				'success' => true,
				'message' => trans('app.successes.updated_successfully')
			]);
		}

		return Response::json([
			'error' => true,
			'message' => trans('app.errors.occurred')
		]);
	}

	private function approvedByID($id)
	{
		$model = PostponedAGM::with(['user'])->find($this->decodeID($id));
		if ($model) {
			$old_status = $model->status;

			$success = $model->update([
				'status' => PostponedAGM::APPROVED,
				'approval_by' => Auth::user()->id,
				'approval_date' => Carbon::now(),
				'approval_remark' => null,
			]);

			if ($success) {
				/**
				 * If status rejected or success send an email to JMB / MC
				 */
				if (Config::get('mail.driver') != '') {
					$delay = 0;

					if (in_array($model->status, [PostponedAGM::PENDING, PostponedAGM::APPROVED, PostponedAGM::REJECTED])) {
						if (!empty($model->user->email) && Helper::validateEmail($model->user->email)) {
							// Mail::later(Carbon::now()->addSeconds($delay), 'emails.postpone_agm.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
							// 	$message->to($model->user->email, $model->user->full_name)->subject("Your Application has been " . Str::upper($model->getStatusText()));
							// });

							Mail::send('emails.postpone_agm.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
								$message->to($model->user->email, $model->user->full_name)->subject("Your Application has been " . Str::upper($model->getStatusText()));
							});
						}
					}
				}

				/**
				 * add audit trail
				 */
				$module = Str::upper($this->getModule());
				$status = ($old_status == $model->status ? '' : 'status');
				if (!empty($status)) {
					$audit_fields_changed = $model->getStatusText();
					$remarks = $module . ': ' . $model->id . $this->module['audit']['text']['status_updated'] . $audit_fields_changed;

					$this->addAudit($model->file_id, $module, $remarks);
				}
			}
		}
	}

	private function rejectedByID($id, $approval_remark)
	{
		$model = PostponedAGM::with(['user'])->find($this->decodeID($id));
		if ($model) {
			$old_status = $model->status;

			$success = $model->update([
				'status' => PostponedAGM::REJECTED,
				'approval_by' => Auth::user()->id,
				'approval_date' => Carbon::now(),
				'approval_remark' => (!empty($approval_remark) ? $approval_remark : null),
			]);

			if ($success) {
				/**
				 * If status rejected or success send an email to JMB / MC
				 */
				if (Config::get('mail.driver') != '') {
					$delay = 0;

					if (in_array($model->status, [PostponedAGM::PENDING, PostponedAGM::APPROVED, PostponedAGM::REJECTED])) {
						if (!empty($model->user->email) && Helper::validateEmail($model->user->email)) {
							// Mail::later(Carbon::now()->addSeconds($delay), 'emails.postpone_agm.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
							// 	$message->to($model->user->email, $model->user->full_name)->subject("Your Application has been " . Str::upper($model->getStatusText()));
							// });

							Mail::send('emails.postpone_agm.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
								$message->to($model->user->email, $model->user->full_name)->subject("Your Application has been " . Str::upper($model->getStatusText()));
							});
						}
					}
				}

				/**
				 * add audit trail
				 */
				$module = Str::upper($this->getModule());
				$status = ($old_status == $model->status ? '' : 'status');
				if (!empty($status)) {
					$audit_fields_changed = $model->getStatusText();
					$remarks = $module . ': ' . $model->id . $this->module['audit']['text']['status_updated'] . $audit_fields_changed;

					$this->addAudit($model->file_id, $module, $remarks);
				}
			}
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

	private function getModule()
	{
		return 'Postpone AGM';
	}

	private function checkAvailableAccess()
	{
		if ((!Auth::user()->getAdmin() && !Auth::user()->isCOB()) && !Auth::user()->isJMB()) {
			App::abort(404);
		}
	}
}
