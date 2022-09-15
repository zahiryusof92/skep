<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use yajra\Datatables\Facades\Datatables;

class DlpController extends \BaseController
{
	public function deposit()
	{
		$this->checkAvailableAccess();

		if (Auth::user()->getFile) {
			$model = DlpDeposit::where('file_id', Auth::user()->getFile->id)->first();

			$viewData = array(
				'title' => trans('app.menus.dlp.deposit'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_deposit',
				'image' => '',
				'model' => (!empty($model) ? $model : null),
			);

			return View::make('dlp.deposit.create', $viewData);
		} else {
			$viewData = array(
				'title' => trans('app.menus.dlp.deposit'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_deposit',
				'image' => '',
			);

			return View::make('dlp.deposit.index', $viewData);
		}
	}

	public function fileUpload()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$files = Request::file();

			foreach ($files as $file) {
				$destinationPath = Config::get('constant.file_directory.dlp_deposit');
				$filename = date('YmdHis') . "_" . $file->getClientOriginalName();
				$upload = $file->move($destinationPath, $filename);

				if ($upload) {
					return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
				}
			}
		}

		return Response::json(['error' => true, 'message' => "Fail"]);
	}

	public function storeDeposit()
	{
		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'amount' => 'required|numeric',
				'maturity_date' => 'required|date',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
				if (Auth::user()->getFile) {
					$file = Files::find(Auth::user()->getFile->id);

					if ($file) {
						$application = DlpDeposit::create([
							'company_id' => ($file->company ? $file->company->id : null),
							'file_id' => $file->id,
							'strata_id' => ($file->strata ? $file->strata->id : null),
							'user_id' => Auth::user()->id,
							'amount' => $request['amount'],
							'maturity_date' => $request['maturity_date'],
							'attachment' => (!empty($request['attachment']) ? $request['attachment'] : null),
							'status' => DlpDeposit::PENDING,
						]);

						if ($application) {
							/**
							 * Send an email to Developer and copy to COB
							 */
							if (Config::get('mail.driver') != '') {
								$delay = 0;

								if (!empty($application->user->email) && Helper::validateEmail($application->user->email)) {
									// Mail::later(Carbon::now()->addSeconds($delay), 'emails.dlp.deposit.new_application', array('model' => $application, 'status' => $application->getStatusText()), function ($message) use ($application) {
									// 	$message->to($application->user->email, $application->user->full_name)->subject('New DLP Deposit');
									// });

									Mail::send('emails.dlp.deposit.new_application', array('model' => $application, 'status' => $application->getStatusText()), function ($message) use ($application) {
										$message->to($application->user->email, $application->user->full_name)->subject('New DLP Deposit');
									});
								}

								if ($application->user->isDeveloper()) {
									// Mail::later(Carbon::now()->addSeconds($delay), 'emails.dlp.deposit.new_application_cob', array('model' => $application, 'date' => $application->created_at->toDayDateTimeString(), 'status' => $application->getStatusText()), function ($message) {
									// 	$message->to('cob@mbpj.gov.my', 'COB')->subject('New DLP Deposit');
									// });

									Mail::send('emails.dlp.deposit.new_application_cob', array('model' => $application, 'date' => $application->created_at->toDayDateTimeString(), 'status' => $application->getStatusText()), function ($message) {
										$message->to('cob@mbpj.gov.my', 'COB')->subject('New DLP Deposit');
									});
								}
							}

							/**
							 * add audit trail
							 */
							$module = Str::upper($this->getModule());
							$remarks = $module . ': Deposit has been submitted.';
							$this->addAudit($file->id, $module, $remarks);

							return Response::json([
								'success' => true,
								'message' => trans('app.successes.saved_successfully')
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

	public function listDeposit()
	{
		if (Request::ajax()) {
			$model = DlpDeposit::self();

			return Datatables::of($model)
				->editColumn('company_id', function ($model) {
					return $model->company->name;
				})
				->editColumn('file_id', function ($model) {
					return $model->file->file_no;
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})
				->editColumn('status', function ($model) {
					return $model->getStatusBadge();
				})
				->editColumn('maturity_date', function ($model) {
					$maturity_date =  $model->maturity_date ? date('d-M-Y', strtotime($model->maturity_date)) : "-";

					return $maturity_date;
				})
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? $model->created_at->format('d-M-Y H:i A') : "-";

					return $created_at;
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('dlp.deposit.show', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Show"><i class="fa fa-eye"></i></a>&nbsp;';

					return $btn;
				})
				->make(true);
		}
	}

	public function showDeposit($id)
	{
		$this->checkAvailableAccess();

		$model = DlpDeposit::find($this->decodeID($id));

		if ($model) {
			$statusOptions = DlpDeposit::getStatusOption();

			$viewData = array(
				'title' => trans('app.menus.dlp.deposit'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_deposit',
				'image' => '',
				'model' => $model,
				'statusOptions' => $statusOptions,
			);

			return View::make('dlp.deposit.show', $viewData);
		}

		App::abort(404);
	}

	public function approvalDeposit($id)
	{
		$this->checkAvailableAccess();

		$model = DlpDeposit::find($this->decodeID($id));

		if ($model) {
			if (Request::ajax()) {
				$request = Request::all();

				$rules = [
					'status' => 'required',
				];

				if ($request['status'] == DlpDeposit::REJECTED) {
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
					if ($request['status'] == DlpDeposit::APPROVED) {
						$this->approvedByID($id);
					} else if ($request['status'] == DlpDeposit::REJECTED) {
						$approval_remark = (isset($request['approval_remark']) ? $request['approval_remark'] : null);

						$this->rejectedByID($id, $approval_remark);
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

	private function approvedByID($id)
	{
		$model = DlpDeposit::with(['user'])->find($this->decodeID($id));
		if ($model) {
			$old_status = $model->status;

			$success = $model->update([
				'status' => DlpDeposit::APPROVED,
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

					if (in_array($model->status, [DlpDeposit::APPROVED, DlpDeposit::REJECTED])) {
						if (!empty($model->user->email) && Helper::validateEmail($model->user->email)) {
							// Mail::later(Carbon::now()->addSeconds($delay), 'emails.dlp.deposit.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
							// 	$message->to($model->user->email, $model->user->full_name)->subject("Your DLP Deposit has been " . Str::upper($model->getStatusText()));
							// });

							Mail::send('emails.dlp.deposit.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
								$message->to($model->user->email, $model->user->full_name)->subject("Your DLP Deposit has been " . Str::upper($model->getStatusText()));
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
		$model = DlpDeposit::with(['user'])->find($this->decodeID($id));
		if ($model) {
			$old_status = $model->status;

			$success = $model->update([
				'status' => DlpDeposit::REJECTED,
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

					if (in_array($model->status, [DlpDeposit::APPROVED, DlpDeposit::REJECTED])) {
						if (!empty($model->user->email) && Helper::validateEmail($model->user->email)) {
							// Mail::later(Carbon::now()->addSeconds($delay), 'emails.dlp.deposit.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
							// 	$message->to($model->user->email, $model->user->full_name)->subject("Your DLP Deposit has been " . Str::upper($model->getStatusText()));
							// });

							Mail::send('emails.dlp.deposit.status_update', array('model' => $model, 'status' => $model->getStatusText()), function ($message) use ($model) {
								$message->to($model->user->email, $model->user->full_name)->subject("Your DLP Deposit has been " . Str::upper($model->getStatusText()));
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

	public function progress()
	{
		$this->checkAvailableAccess();

		if (Auth::user()->getFile) {
			$viewData = array(
				'title' => trans('app.menus.dlp.progress'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_progress',
				'image' => ''
			);

			return View::make('dlp.progress.create', $viewData);
		} else {
			$viewData = array(
				'title' => trans('app.menus.dlp.progress'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_progress',
				'image' => ''
			);

			return View::make('dlp.progress.index', $viewData);
		}
	}

	public function storeProgress()
	{
		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'date' => 'required|date',
				'percentage' => 'required|numeric',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
				if (Auth::user()->getFile) {
					$file = Files::find(Auth::user()->getFile->id);

					if ($file) {
						$created = DlpProgress::create([
							'company_id' => ($file->company ? $file->company->id : null),
							'file_id' => $file->id,
							'strata_id' => ($file->strata ? $file->strata->id : null),
							'user_id' => Auth::user()->id,
							'date' => $request['date'],
							'percentage' => $request['percentage'],
						]);

						if ($created) {
							/**
							 * add audit trail
							 */
							$module = Str::upper($this->getModule());
							$remarks = $module . ': Progress has been submitted.';
							$this->addAudit($file->id, $module, $remarks);

							return Response::json([
								'success' => true,
								'message' => trans('app.successes.saved_successfully')
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

	public function listProgress()
	{
		if (Request::ajax()) {
			$model = DlpProgress::self();

			return Datatables::of($model)
				->editColumn('company_id', function ($model) {
					return $model->company->name;
				})
				->editColumn('file_id', function ($model) {
					return $model->file->file_no;
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})
				->editColumn('date', function ($model) {
					return ($model->date ? date('d-M-Y', strtotime($model->date)) : '');
				})
				->editColumn('created_at', function ($model) {
					return ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : "-");
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('dlp.progress.show', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Show"><i class="fa fa-eye"></i></a>&nbsp;';

					// if (Auth::user()->isDeveloper()) {
					// 	$btn .= '<form action="' . route('dlp.progress.destroy', $this->encodeID($model->id)) . '" method="POST" id="delete_form_' . $this->encodeID($model->id) . '" style="display:inline-block;">';
					// 	$btn .= '<input type="hidden" name="_method" value="DELETE">';
					// 	$btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $this->encodeID($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
					// 	$btn .= '</form>';
					// }

					return $btn;
				})
				->make(true);
		}
	}

	public function showProgress($id)
	{
		$this->checkAvailableAccess();

		$model = DlpProgress::find($this->decodeID($id));

		if ($model) {
			$viewData = array(
				'title' => trans('app.menus.dlp.progress'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_progress',
				'image' => '',
				'model' => $model,
			);

			return View::make('dlp.progress.show', $viewData);
		}

		App::abort(404);
	}

	public function destroyProgress($id)
	{
		$this->checkAvailableAccess();

		$model = DlpProgress::find($this->decodeID($id));

		if ($model) {
			$success = $model->delete();

			if ($success) {
				/*
                 * add audit trail
                 */
				$module = Str::upper($this->getModule());
				$remarks = $module . ': Progress has been deleted.';
				$this->addAudit($model->file_id, $module, $remarks);

				return Redirect::back()->with('success', trans('app.successes.deleted_successfully'));
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
	}

	public function period()
	{
		$this->checkAvailableAccess();

		if (Auth::user()->getFile) {
			$model = DlpPeriod::where('file_id', Auth::user()->getFile->id)->first();

			$viewData = array(
				'title' => trans('app.menus.dlp.period'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_period',
				'image' => '',
				'model' => (!empty($model) ? $model : null),
			);

			return View::make('dlp.period.create', $viewData);
		} else {
			$viewData = array(
				'title' => trans('app.menus.dlp.period'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_period',
				'image' => ''
			);

			return View::make('dlp.period.index', $viewData);
		}
	}

	public function storePeriod()
	{
		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'duration' => 'required',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
				if (Auth::user()->getFile) {
					$file = Files::find(Auth::user()->getFile->id);

					if ($file) {
						$created = DlpPeriod::create([
							'company_id' => ($file->company ? $file->company->id : null),
							'file_id' => $file->id,
							'strata_id' => ($file->strata ? $file->strata->id : null),
							'user_id' => Auth::user()->id,
							'duration' => $request['duration'],
						]);

						if ($created) {
							/**
							 * add audit trail
							 */
							$module = Str::upper($this->getModule());
							$remarks = $module . ': Period has been submitted.';
							$this->addAudit($file->id, $module, $remarks);

							return Response::json([
								'success' => true,
								'message' => trans('app.successes.saved_successfully')
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

	public function listPeriod()
	{
		if (Request::ajax()) {
			$model = DlpPeriod::self();

			return Datatables::of($model)
				->editColumn('company_id', function ($model) {
					return $model->company->name;
				})
				->editColumn('file_id', function ($model) {
					return $model->file->file_no;
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})
				->editColumn('duration', function ($model) {
					return $model->duration;
				})
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? $model->created_at->format('d-M-Y H:i A') : "-";

					return $created_at;
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('dlp.period.show', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Show"><i class="fa fa-eye"></i></a>&nbsp;';

					return $btn;
				})
				->make(true);
		}
	}

	public function showPeriod($id)
	{
		$this->checkAvailableAccess();

		$model = DlpPeriod::find($this->decodeID($id));

		if ($model) {
			$viewData = array(
				'title' => trans('app.menus.dlp.period'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_period',
				'image' => '',
				'model' => $model,
			);

			return View::make('dlp.period.show', $viewData);
		}

		App::abort(404);
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
		return 'Defect Liability Period';
	}

	private function checkAvailableAccess()
	{
		if ((!Auth::user()->getAdmin() && !Auth::user()->isCOB()) && !Auth::user()->isDeveloper()) {
			App::abort(404);
		}
	}
}
