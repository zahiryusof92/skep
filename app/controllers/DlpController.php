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
	public function createDeposit()
	{
		if (!Auth::user()->getAdmin()) {
			if (!empty(Auth::user()->file_id)) {
				$files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
			} else {
				$files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
			}
		} else {
			if (empty(Session::get('admin_cob'))) {
				$files = Files::where('is_deleted', 0)->orderBy('id', 'asc')->get();
			} else {
				$files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
			}
		}

		$checklists = Config::get('constant.dlp_checklist');

		// dd($checklists);

		$viewData = array(
			'title' => trans('app.menus.dlp.deposit'),
			'panel_nav_active' => 'dlp_panel',
			'main_nav_active' => 'dlp_main',
			'sub_nav_active' => 'dlp_deposit',
			'image' => '',
			'files' => $files,
			'model' => null,
			'checklists' => $checklists,
		);

		return View::make('dlp.deposit.create', $viewData);
	}

	public function deposit()
	{
		$this->checkAvailableAccess();

		if (Auth::user()->getFile) {
			if (!Auth::user()->getAdmin()) {
				if (!empty(Auth::user()->file_id)) {
					$files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
				} else {
					$files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('id', 'asc')->get();
				}
			} else {
				if (empty(Session::get('admin_cob'))) {
					$files = Files::where('is_deleted', 0)->orderBy('id', 'asc')->get();
				} else {
					$files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
				}
			}

			$checklists = Config::get('constant.dlp_checklist');
			$model = DlpDeposit::where('file_id', Auth::user()->getFile->id)->first();

			$viewData = array(
				'title' => trans('app.menus.dlp.deposit'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_deposit',
				'image' => '',
				'files' => $files,
				'model' => (!empty($model) ? $model : null),
				'checklists' => $checklists,
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
				$filename = date('YmdHis') . "_" . Helper::sanitizeFilename($file->getClientOriginalName());
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
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'file' => 'required',
				'type' => 'required',
				'development_cost' => 'required|numeric',
				'amount' => 'required|numeric',
				'start_date' => 'required|date',
				'maturity_date' => 'required|date',
				'date_vp' => 'required|date',
				'checklist' => '',
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
				} else if (isset($request['file']) && !empty($request['file'])) {
					$file = Files::find($request['file']);
				} else {
					return Response::json([
						'error' => true,
						'message' => trans('app.errors.occurred')
					]);
				}

				if ($file) {
					$exist = DlpDeposit::where('file_id', $file->id)->first();
					if (!$exist) {
						$application = DlpDeposit::create([
							'company_id' => ($file->company ? $file->company->id : null),
							'file_id' => $file->id,
							'strata_id' => ($file->strata ? $file->strata->id : null),
							'user_id' => Auth::user()->id,
							'type' => $request['type'],
							'development_cost' => $request['development_cost'],
							'amount' => $request['amount'],
							'balance' => $request['amount'],
							'start_date' => $request['start_date'],
							'maturity_date' => $request['maturity_date'],
							'vp_date' => $request['date_vp'],
							'checklist' => ((isset($request['checklist']) && !empty($request['checklist'])) ? json_encode($request['checklist']) : null),
							'attachment' => (!empty($request['attachment']) ? $request['attachment'] : null),
							'status' => DlpDeposit::APPROVED,
						]);

						if ($application) {
							/**
							 * Send an email to Developer and copy to COB
							 */
							// if (Config::get('mail.driver') != '') {
							// 	$delay = 0;

							// 	if (!empty($application->user->email) && Helper::validateEmail($application->user->email)) {
							// 		// Mail::later(Carbon::now()->addSeconds($delay), 'emails.dlp.deposit.new_application', array('model' => $application, 'status' => $application->getStatusText()), function ($message) use ($application) {
							// 		// 	$message->to($application->user->email, $application->user->full_name)->subject('New DLP Deposit');
							// 		// });

							// 		Mail::send('emails.dlp.deposit.new_application', array('model' => $application, 'status' => $application->getStatusText()), function ($message) use ($application) {
							// 			$message->to($application->user->email, $application->user->full_name)->subject('New DLP Deposit');
							// 		});
							// 	}

							// 	if ($application->user->isDeveloper()) {
							// 		// Mail::later(Carbon::now()->addSeconds($delay), 'emails.dlp.deposit.new_application_cob', array('model' => $application, 'date' => $application->created_at->toDayDateTimeString(), 'status' => $application->getStatusText()), function ($message) {
							// 		// 	$message->to('cob@mbpj.gov.my', 'COB')->subject('New DLP Deposit');
							// 		// });

							// 		Mail::send('emails.dlp.deposit.new_application_cob', array('model' => $application, 'date' => $application->created_at->toDayDateTimeString(), 'status' => $application->getStatusText()), function ($message) {
							// 			$message->to('cob@mbpj.gov.my', 'COB')->subject('New DLP Deposit');
							// 		});
							// 	}
							// }

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
					} else {
						return Response::json([
							'error' => true,
							'message' => trans('app.errors.already_exist')
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

	public function returnDeposit($id)
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'return_amount' => 'required|numeric',
				'return_checklist' => '',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
				$deposit = DlpDeposit::find($this->decodeID($id));
				if ($deposit) {
					$update = $deposit->update([
						'status' => DlpDeposit::RETURNED,
						'return_checklist' => ((isset($request['return_checklist']) && !empty($request['return_checklist'])) ? json_encode($request['return_checklist']) : null),
					]);

					if ($update) {
						/**
						 * add audit trail
						 */
						$module = Str::upper($this->getModule());
						$remarks = $module . ': Deposit has been updated.';
						$this->addAudit($deposit->file->id, $module, $remarks);

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

	public function listDeposit()
	{
		$this->checkAvailableAccess();

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

	public function usageDeposit($id)
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$model = DlpDepositUsage::with('dlpDeposit')->where('dlp_deposit_id', $this->decodeID($id));

			return Datatables::of($model)
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? $model->created_at->format('d-M-Y H:i A') : "-";

					return $created_at;
				})
				->editColumn('description', function ($model) {
					return $model->description;
				})
				->editColumn('amount', function ($model) {
					return $model->amount;
				})
				->editColumn('attachment', function ($model) {
					$attachment = '';
					if (!empty($model->attachment)) {
						$attachment .= '<a href="' . asset($model->attachment) . '" target="_blank">';
						$attachment .= '<button type="button" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="bottom" title="' . trans('app.forms.attachment') . '">';
						$attachment .= '<i class="fa fa-file-pdf-o" style="margin-right: 2px;"></i>' . trans('app.forms.attachment') . '</button>';
						$attachment .= '</a>';
					}

					return $attachment;
				})
				->addColumn('action', function ($model) {
					$btn = '';
					if ($model->dlpDeposit->status == DlpDeposit::APPROVED) {
						$btn .= '<a href="#" class="btn btn-xs btn-danger" title="Delete" onclick="deleteUsage(\'' . \Helper\Helper::encode($model->id) . '\')"><i class="fa fa-trash"></i></a>';
					}

					return $btn;
				})
				->make(true);
		}
	}

	public function createUsageDeposit($id)
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'description' => 'required|string|max:255',
				'amount' => 'required|numeric',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
				$deposit = DlpDeposit::find($this->decodeID($id));
				if ($deposit) {
					$before = $deposit->balance;
					$balance = $before - $request['amount'];

					$usage = DlpDepositUsage::create([
						'dlp_deposit_id' => $deposit->id,
						'description' => $request['description'],
						'amount' => $request['amount'],
						'amount_before' => $before,
						'amount_after' => $balance,
						'attachment' => (!empty($request['attachment']) ? $request['attachment'] : null),
					]);

					if ($usage) {
						$deposit->update([
							'balance' => $balance
						]);

						/**
						 * add audit trail
						 */
						$module = Str::upper($this->getModule());
						$remarks = $module . ': Deposit Usage has been submitted.';
						$this->addAudit($deposit->file->id, $module, $remarks);

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

	public function deleteUsageDeposit($id)
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$usage = DlpDepositUsage::find($this->decodeID($id));
			if ($usage) {
				$deposit = DlpDeposit::find($usage->dlp_deposit_id);
				if ($deposit) {
					$balance = $deposit->balance + $usage->amount;

					$deposit->update([
						'balance' => $balance,
					]);
				}

				$usage->delete();

				/**
				 * add audit trail
				 */
				$module = Str::upper($this->getModule());
				$remarks = $module . ': Deposit Usage has been deleted.';
				$this->addAudit($deposit->file->id, $module, $remarks);

				return Response::json([
					'success' => true,
					'message' => trans('app.successes.deleted_successfully')
				]);
			}
		}

		return Response::json([
			'error' => true,
			'message' => trans('app.errors.occurred')
		]);
	}

	public function fileUploadUsageDeposit()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$files = Request::file();

			foreach ($files as $file) {
				$destinationPath = Config::get('constant.file_directory.dlp_deposit_usage');
				$filename = date('YmdHis') . "_" . Helper::sanitizeFilename($file->getClientOriginalName());
				$upload = $file->move($destinationPath, $filename);

				if ($upload) {
					return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
				}
			}
		}

		return Response::json(['error' => true, 'message' => "Fail"]);
	}

	public function showDeposit($id)
	{
		$this->checkAvailableAccess();

		$model = DlpDeposit::find($this->decodeID($id));

		if ($model) {
			$checklists = Config::get('constant.dlp_checklist');
			$returnChecklists = Config::get('constant.dlp_return_checklist');
			$statusOptions = DlpDeposit::getStatusOption();

			$viewData = array(
				'title' => trans('app.menus.dlp.deposit'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_deposit',
				'image' => '',
				'model' => $model,
				'checklists' => $checklists,
				'returnChecklists' => $returnChecklists,
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
		if (!AccessGroup::hasAccessModule('Defect Liability Period')) {
			App::abort(404);
		}

		if ((!Auth::user()->getAdmin() && !Auth::user()->isCOB()) && !Auth::user()->isDeveloper()) {
			App::abort(404);
		}
	}
}
