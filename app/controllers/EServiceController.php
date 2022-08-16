<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use yajra\Datatables\Facades\Datatables;

class EServiceController extends \BaseController
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
			$model = EServiceOrder::self()->notDraft();
			if (Str::contains(Request::fullUrl(), 'approval')) {
				$model = EServiceOrder::self()->approval();
			} else if (Str::contains(Request::fullUrl(), 'draft')) {
				$model = EServiceOrder::self()->draft();
			}

			return Datatables::of($model)
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('eservice.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";
					// $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

					return $created_at;
				})
				->editColumn('file_id', function ($model) {
					return $model->file_id ? "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file->id)) . "'>" . $model->file->file_no . "</a>" : "-";
				})
				->editColumn('strata_id', function ($model) {
					return $model->strata->name;
				})

				->editColumn('order_no', function ($model) {
					return $model->order_no;
				})
				->editColumn('status', function ($model) {
					return $model->getStatusBadge();
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('eservice.show', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';
					if (!Str::contains(Request::fullUrl(), 'approval') && !in_array($model->status, [EServiceOrder::APPROVED, EServiceOrder::REJECTED, EServiceOrder::PENDING, EServiceOrder::INPROGRESS])) {
						$btn .= '<a href="' . route('eservice.edit', $this->encodeID($model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
						$btn .= '<form action="' . route('eservice.destroy', $this->encodeID($model->id)) . '" method="POST" id="delete_form_' . $this->encodeID($model->id) . '" style="display:inline-block;">';
						$btn .= '<input type="hidden" name="_method" value="DELETE">';
						$btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $this->encodeID($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
						$btn .= '</form>';
					}

					return $btn;
				})
				->make(true);
		}

		if (Str::contains(Request::fullUrl(), 'approval')) {
			$viewData = array(
				'title' => trans('app.menus.eservice.approval'),
				'panel_nav_active' => 'eservice_panel',
				'main_nav_active' => 'eservice_main',
				'sub_nav_active' => 'eservice_approval',
				'table_route' => route('eservice.index', ['type' => 'approval']),
				'image' => ''
			);

			return View::make('eservice.list', $viewData);
		} else if (Str::contains(Request::fullUrl(), 'draft')) {
			$viewData = array(
				'title' => trans('app.menus.eservice.draft'),
				'panel_nav_active' => 'eservice_panel',
				'main_nav_active' => 'eservice_main',
				'sub_nav_active' => 'eservice_draft',
				'table_route' => route('eservice.index', ['type' => 'draft']),
				'image' => ''
			);

			return View::make('eservice.list', $viewData);
		} else {
			$viewData = array(
				'title' => trans('app.menus.eservice.review'),
				'panel_nav_active' => 'eservice_panel',
				'main_nav_active' => 'eservice_main',
				'sub_nav_active' => 'eservice_list',
				'table_route' => route('eservice.index'),
				'image' => ''
			);

			return View::make('eservice.list', $viewData);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($type = '')
	{
		$this->checkAvailableAccess();

		$cob = Auth::user()->getCOB->short_name;
		if (!empty($cob)) {
			if (!empty($type)) {
				$title = $this->validateType($cob, $type);
				$form = $this->getFormView($cob, $type, $order_details = null);

				$viewData = array(
					'title' => trans('app.menus.eservice.create') .  ' - ' . $title,
					'panel_nav_active' => 'eservice_panel',
					'main_nav_active' => 'eservice_main',
					'sub_nav_active' => 'eservice_create',
					'type' => $type,
					'form' => $form,
					'image' => ""
				);

				return View::make('eservice.create', $viewData);
			} else {
				$options = [];
				$cob = Auth::user()->getCOB->short_name;
				if (!empty($cob)) {
					$types = (!empty($this->getModule()['cob'][Str::lower($cob)])) ? $this->getModule()['cob'][Str::lower($cob)]['type'] : '';
					if (!empty($types)) {
						foreach ($types as $type) {
							array_push($options, ['id' => $type['name'], 'text' => $type['title']]);
						}
					}
				}

				$viewData = array(
					'title' => trans('app.menus.eservice.create'),
					'panel_nav_active' => 'eservice_panel',
					'main_nav_active' => 'eservice_main',
					'sub_nav_active' => 'eservice_create',
					'options' => $options,
					'image' => ""
				);

				return View::make('eservice.index', $viewData);
			}
		}
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

		$form = $this->getForm($request['cob'], $request['type'], null);
		if (!empty($form)) {
			/** validate form */
			$validate = $this->getFormValidation($form);
			if (!empty($validate)) {
				$validator = Validator::make($request, $validate['rules'], $validate['messages'], $validate['attributes']);
				if ($validator->fails()) {
					return Response::json([
						'error' => true,
						'errors' => $validator->errors(),
						'message' => trans('Validation Fail')
					]);
				}
			}

			$cob = Company::where('short_name', $request['cob'])->first();
			if ($cob) {
				$type = $request['type'];

				$strata = Strata::with(['file', 'categories'])->find($request['scheme_name']);
				if ($strata) {
					if ($strata->file && $strata->categories) {
						$pricing = EServicePrice::where('company_id', $cob->id)
							->where('category_id', $strata->categories->id)
							->where('slug', $type)
							->first();

						if ($pricing) {
							$order_no = date('YmdHis') . Auth::user()->id;

							$order = EServiceOrder::create([
								'company_id' => $cob->id,
								'file_id' => $strata->file->id,
								'strata_id' => $strata->id,
								'category_id' => $strata->categories->id,
								'user_id' => Auth::user()->id,
								'order_no' => $order_no,
								'status' => EServiceOrder::DRAFT,
							]);

							if ($order) {
								unset($request['cob']);
								unset($request['type']);

								$success = EServiceOrderDetail::create([
									'eservice_order_id' => $order->id,
									'type' => $type,
									'value' => json_encode($request),
									'price' => $pricing->price,
									'status' => EServiceOrderDetail::DRAFT,
								]);

								if ($success) {
									/**
									 * add audit trail 
									 */
									$module = Str::upper($this->getModule()['name']);
									$remarks = $module . ': New application #' . $order->order_no . ' has deen drafted.';
									$this->addAudit($strata->file->id, $module, $remarks);

									return Response::json([
										'success' => true,
										'id' => $this->encodeID($order->id),
										'message' => trans('app.successes.saved_successfully')
									]);
								}
							}
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->checkAvailableAccess();

		$order = EServiceOrder::with(['file', 'strata', 'user'])->find($this->decodeID($id));
		$statusOptions = EServiceOrder::getStatusOption();

		if ($order) {
			if ($order->details) {
				$cob = $order->company->short_name;
				$order_details = $order->details;
				$type = $order_details->type;

				if (!empty($type)) {
					$title = $this->validateType($cob, $type);
					$form = $this->getFormViewReadOnly($cob, $type, $order_details);

					if ($order->status == EServiceOrder::DRAFT) {
						$sub_nav_active = 'eservice_draft';
					} else if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS])) {
						$sub_nav_active = 'eservice_list';
					} else {
						$sub_nav_active = 'eservice_approval';
					}

					$viewData = array(
						'title' => trans('app.menus.eservice.show') .  ' - ' . $title,
						'panel_nav_active' => 'eservice_panel',
						'main_nav_active' => 'eservice_main',
						'sub_nav_active' => $sub_nav_active,
						'type' => $type,
						'letter_type' => $title,
						'form' => $form,
						'order' => $order,
						'order_details' => $order_details,
						"statusOptions" => $statusOptions,
						'image' => ""
					);

					return View::make('eservice.show', $viewData);
				}
			}
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
		$this->checkAvailableAccess();

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			if ($order->status == EServiceOrder::DRAFT) {
				if ($order->details) {
					$cob = $order->company->short_name;
					$order_details = $order->details;
					$type = $order_details->type;

					if (!empty($type)) {
						$title = $this->validateType($cob, $type);
						$form = $this->getFormView($cob, $type, $order_details);

						$viewData = array(
							'title' => trans('app.menus.eservice.edit') .  ' - ' . $title,
							'panel_nav_active' => 'eservice_panel',
							'main_nav_active' => 'eservice_main',
							'sub_nav_active' => 'eservice_create',
							'type' => $type,
							'form' => $form,
							'order' => $order,
							'order_details' => $order_details,
							'image' => ""
						);

						return View::make('eservice.edit', $viewData);
					}
				}
			}
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

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			if ($order->status == EServiceOrder::DRAFT) {
				if ($order->details) {
					$cob = $order->company->short_name;
					$order_details = $order->details;
					$type = $order_details->type;

					if (!empty($type)) {
						$form = $this->getForm($cob, $type, null);
						if (!empty($form)) {

							/** validate form */
							$validate = $this->getFormValidation($form);
							if (!empty($validate)) {
								$validator = Validator::make($request, $validate['rules'], $validate['messages'], $validate['attributes']);
								if ($validator->fails()) {
									return Response::json([
										'error' => true,
										'errors' => $validator->errors(),
										'message' => trans('Validation Fail')
									]);
								}
							}

							unset($request['cob']);
							unset($request['type']);

							$success = $order_details->update([
								'value' => json_encode($request),
							]);

							if ($success) {
								/**
								 * add audit trail
								 */
								$module = Str::upper($this->getModule()['name']);
								$remarks = $module . ': Application #' . $order->order_no . ' has been updated.';
								$this->addAudit($order->file_id, $module,  $remarks);

								return Response::json([
									'success' => true,
									'id' => $this->encodeID($order->id),
									'message' => trans('app.successes.saved_successfully')
								]);
							}
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
		$this->checkAvailableAccess();

		$model = EServiceOrder::find($this->decodeID($id));
		if ($model) {
			$success = $model->delete();

			if ($success) {
				/*
                 * add audit trail
                 */
				$module = Str::upper($this->getModule(['name']));
				$remarks = $module . ': ' . $model->id . $this->module['audit']['text']['data_deleted'];
				$this->addAudit($model->file_id, $module, $remarks);

				return Redirect::route('eservice')->with('success', trans('app.successes.deleted_successfully'));
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
	}

	public function payment($id)
	{
		$this->checkAvailableAccess();

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			if ($order->status == EServiceOrder::DRAFT) {
				if ($order->details) {
					$total_amount = $order->details->price;

					$viewData = array(
						'title' => trans('app.menus.eservice.payment'),
						'panel_nav_active' => 'eservice_panel',
						'main_nav_active' => 'eservice_main',
						'sub_nav_active' => 'eservice_create',
						'order' => $order,
						'order_details' => $order->details,
						'total_amount' => $total_amount,
						'image' => ""
					);

					return View::make('eservice.payment', $viewData);
				}
			}
		}

		App::abort(404);
	}

	public function submitPayment()
	{
		$request = Input::all();

		$order = EServiceOrder::with(['user'])->find($this->decodeID($request['order_id']));
		if ($order) {
			$rules = array(
				'amount' => 'required',
				'payment_method' => 'required',
				'terms' => 'required'
			);

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			} else {
				$amount = $request['amount'];

				if (!empty($amount)) {
					$order->status = EServiceOrder::INPROGRESS;
					$success = $order->save();

					if ($success) {
						if ($order->details) {
							$order->details->update([
								'status' => EServiceOrderDetail::INPROGRESS,
							]);
						}

						$transaction = EServiceOrderTransaction::create([
							'eservice_order_id' => $order->id,
							'payment_method' => $request['payment_method'],
							'total_price' => $amount,
							'status' => EServiceOrderTransaction::APPROVED,

						]);

						if ($transaction) {
							/**
							 * Send an email to JMB / MC and copy to COB
							 */
							if (Config::get('mail.driver') != '') {
								$delay = 0;
								$incrementDelay = 2;

								if (!empty($order->user->email) && Helper::validateEmail($order->user->email)) {
									Mail::later(Carbon::now()->addSeconds($delay), 'emails.eservice.new_application', array('model' => $order, 'status' => $order->getStatusText()), function ($message) use ($order) {
										$message->to($order->user->email, $order->user->full_name)->subject('New Application for e-Perkhidmatan');
									});
								}

								if ($order->user->isJMB() || $order->user->isMC()) {
									Mail::later(Carbon::now()->addSeconds($delay), 'emails.eservice.new_application_cob', array('model' => $order, 'date' => $order->created_at->toDayDateTimeString(), 'status' => $order->getStatusText()), function ($message) {
										$message->to('cob@mbpj.gov.my', 'COB')->subject('New Application for e-Perkhidmatan');
									});
								}
							}

							/**
							 * add audit trail
							 */
							$module = Str::upper($this->getModule()['name']);
							$remarks = $module . ': Application #' . $order->order_no . ' has been submitted.';
							$remarks = $module . ': ' . $order->email . " has submitted a new application";
							$this->addAudit($order->file_id, $module, $remarks);

							return Redirect::route('eservice.index')->with('success', trans('app.successes.payment_successfully'));
						}
					}
				}
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput();
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

		if ($status == EServiceOrder::REJECTED) {
			$rules['approval_remark'] = 'required';;
		} else if ($status == EServiceOrder::APPROVED) {
			$rules['bill_no'] = 'required';
			$rules['date'] = 'required';
		}

		$validator = Validator::make($request, $rules);

		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$order = EServiceOrder::with(['user'])->find($this->decodeID($id));
			if ($order) {
				$success = $order->update([
					'status' => $status,
					'approval_by' => Auth::user()->id,
					'approval_date' => date('Y-m-d'),
					'approval_remark' => (isset($request['approval_remark']) ? $request['approval_remark'] : null),
				]);

				if ($success) {
					if ($order->details) {
						if ($status == EServiceOrder::APPROVED) {
							$order->details->update([
								'bill_no' => $request['bill_no'],
								'date' => $request['date'],
								'status' => $status,
							]);
						} else {
							$order->details->update([
								'status' => $status,
							]);
						}
					}

					/**
					 * If status rejected or success send an email to JMB / MC
					 */
					if (Config::get('mail.driver') != '') {
						$delay = 0;

						if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS, EServiceOrder::APPROVED, EServiceOrder::REJECTED])) {
							if (!empty($order->user->email) && Helper::validateEmail($order->user->email)) {
								Mail::later(Carbon::now()->addSeconds($delay), 'emails.eservice.status_update', array('model' => $order, 'status' => $order->getStatusText()), function ($message) use ($order) {
									$message->to($order->user->email, $order->user->full_name)->subject("Your Application e-Perkhidmatan has been " . $order->getStatusText());
								});
							}
						}
					}

					/**
					 * add audit trail
					 */
					$module = Str::upper($this->getModule()['name']);
					$status = ($request['status'] == $order->status ? '' : 'status');
					if (!empty($status)) {
						$audit_fields_changed = $order->getStatusText();
						$remarks = $module . ': ' . $order->id . $this->module['audit']['text']['status_updated'] . $audit_fields_changed;

						$this->addAudit($order->file_id, $module, $remarks);
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

	public function getLetterPDF($id)
	{
		$order = EServiceOrder::find($this->decodeID($id));
		if ($order && $order->details) {
			if ($order->details) {
				$details = $order->details;
				$type = $order->details->type;
				$filename = $type . "_" . date('YmdHis');
				$content = json_decode($order->details->value, true);

				$viewData = [
					'details' => $details,
					'content' => $content,
					'filename' => $filename,
					'order' => $order,
				];

				$pdf = PDF::loadView('eservice.' . Str::lower($order->company->short_name) . '.pdf.' . $type, $viewData)->setPaper('A4', 'portrait');
				return $pdf->stream($filename);
			}
		}

		App::abort(404);
	}

	public function getLetterWord($id)
	{
		$order = EServiceOrder::find($this->decodeID($id));
		if ($order && $order->details) {
			if ($order->details) {
				$details = $order->details;
				$type = $order->details->type;
				$filename = $type . "_" . date('YmdHis');
				$content = json_decode($order->details->value, true);

				$viewData = [
					'details' => $details,
					'content' => $content,
					'filename' => $filename,
					'order' => $order,
				];

				return View::make('eservice.' . Str::lower($order->company->short_name) . '.word.' . $type, $viewData);
			}
		}

		App::abort(404);
	}

	public function fileUpload()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$files = Request::file();

			foreach ($files as $file) {
				$destinationPath = Config::get('constant.file_directory.eservice');
				$filename = date('YmdHis') . "_" . $file->getClientOriginalName();
				$upload = $file->move($destinationPath, $filename);

				if ($upload) {
					return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
				}
			}
		}

		return Response::json(['error' => true, 'message' => "Fail"]);
	}

	public function getFormValidation($form)
	{
		$data = [];
		$rules = [];
		$messages = [];
		$attributes  = [];

		if (!empty($form)) {
			if (isset($form['fields']) && !empty($form['fields'])) {
				$fields = $form['fields'];

				if (!empty($fields)) {
					$rules['scheme_name'] = 'required';
					if (isset($form['attributes']) && !empty($form['attributes'])) {
						foreach ($form['attributes'] as $attribute) {
							$rules[$attribute] = ($fields[$attribute]['required'] ? 'required' : '');
							$attributes[$attribute] = $fields[$attribute]['label'];
						}
					}
				}
			}
		}

		$data = [
			'rules' => $rules,
			'messages' => $messages,
			'attributes' => $attributes,
		];

		return $data;
	}

	public function getFormViewReadOnly($cob, $type, $order_details)
	{
		$data = $this->getForm($cob, $type, $order_details);

		return View::make('eservice.partial.read_only', $data);
	}

	public function getFormView($cob, $type, $order_details)
	{
		$management = [];

		if (Auth::user()->isJMB()) {
			if (!empty(Auth::user()->file_id)) {
				$file = Files::find(Auth::user()->file_id);
				if ($file && $file->strata) {
					if ($file->managementMC && !empty($file->managementMC->name)) {
						$management = [
							'strata' => $file->strata->name,
							'name' => $file->managementMC->name,
							'address1' => $file->managementMC->address1,
							'address2' => $file->managementMC->address2,
							'address3' => $file->managementMC->address3,
							'postcode' =>  $file->managementMC->poscode,
							'city' => (!empty($file->managementMC->city) ? $file->managementMC->cities->description : ''),
							'state' => (!empty($file->managementMC->state) ? $file->managementMC->states->name : ''),
							'phone_no' => $file->managementMC->phone_no,
						];
					} else if ($file->managementJMB && !empty($file->managementJMB->name)) {
						$management = [
							'strata' => $file->strata->name,
							'name' => $file->managementJMB->name,
							'address1' => $file->managementJMB->address1,
							'address2' => $file->managementJMB->address2,
							'address3' => $file->managementJMB->address3,
							'postcode' =>  $file->managementJMB->poscode,
							'city' => (!empty($file->managementJMB->city) ? $file->managementJMB->cities->description : ''),
							'state' => (!empty($file->managementJMB->state) ? $file->managementJMB->states->name : ''),
							'phone_no' => $file->managementJMB->phone_no,
						];
					} else if ($file->managementDeveloper && !empty($file->managementDeveloper->name)) {
						$management = [
							'strata' => $file->strata->name,
							'name' => $file->managementDeveloper->name,
							'address1' => $file->managementDeveloper->address1,
							'address2' => $file->managementDeveloper->address2,
							'address3' => $file->managementDeveloper->address3,
							'postcode' =>  $file->managementDeveloper->poscode,
							'city' => (!empty($file->managementDeveloper->city) ? $file->managementDeveloper->cities->description : ''),
							'state' => (!empty($file->managementDeveloper->state) ? $file->managementDeveloper->states->name : ''),
							'phone_no' => $file->managementDeveloper->phone_no,
						];
					}
				}
			}
		}

		$data = $this->getForm($cob, $type, $order_details, $management);

		return View::make('eservice.partial.form', $data);
	}

	public function getForm($cob, $type, $order_details, $management = '')
	{
		$data = [];
		$data['attributes'] = $this->getFormAttributes($cob, $type);
		$data['fields'] = $this->getFormFields();

		if (!empty($order_details)) {
			$data['model'] = json_decode($order_details->value, true);
		}

		if (!empty($management)) {
			$data['management'] = $management;
		}

		return $data;
	}

	public function getFormFields()
	{
		$module_config = $this->getModule();
		$data = $module_config['fields'];

		return $data;
	}

	public function getFormAttributes($cob, $type)
	{
		$module_config = $this->getModule();
		$data = $module_config['cob'][Str::lower($cob)]['type'][$type]['only'];

		return $data;
	}

	public function validateType($cob, $type)
	{
		$module_config = $this->getModule();
		if (isset($module_config['cob'][Str::lower($cob)])) {
			if (isset($module_config['cob'][Str::lower($cob)]['type'][$type])) {
				return $module_config['cob'][Str::lower($cob)]['type'][$type]['title'];
			}
		}

		App::abort(404);
	}

	private function getModule()
	{
		return $this->module['eservice'];
	}

	private function encodeID($id)
	{
		return Helper::encode($this->getModule()['name'], $id);
	}

	private function decodeID($id)
	{
		return Helper::decode($id, $this->getModule()['name']);
	}

	private function checkAvailableAccess($model = '')
	{
		if (!Auth::user()->getAdmin() && (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name != "MBPJ")) {
			App::abort(404);
		}

		if (!empty($model) && $model->status != EServiceOrder::DRAFT) {
			App::abort(404);
		}
	}
}
