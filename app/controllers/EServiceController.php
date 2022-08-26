<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
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

			if (!empty(Input::get('company'))) {
				$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
				if ($cob) {
					$model = $model->where('eservices_orders.company_id', $cob->id);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
				$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

				$model = $model->whereBetween('eservices_orders.created_at', [$start_date, $end_date]);
			}

			if (!empty(Input::get('letter_type'))) {
				$model = $model->where('eservices_orders.type', Input::get('letter_type'));
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
					$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('eservice.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";

					return $created_at;
				})
				->editColumn('type', function ($model) {
					return $model->getTypeText();
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

		if (empty(Session::get('admin_cob'))) {
			$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
		} else {
			$company = Company::where('id', Session::get('admin_cob'))->get();
		}

		$types = [];
		$cob = Auth::user()->getCOB->short_name;
		if (!empty($cob)) {
			$options = (!empty($this->getModule()['cob'][Str::lower($cob)])) ? $this->getModule()['cob'][Str::lower($cob)]['type'] : '';
			if (!empty($options)) {
				foreach ($options as $type) {
					array_push($types, ['id' => $type['name'], 'text' => $type['title']]);
				}
			}
		}
		$viewData = array(
			'title' => trans('app.menus.eservice.review'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_list',
			'company' => $company,
			'types' => $types,
			'image' => ''
		);

		return View::make('eservice.list', $viewData);

		App::abort(404);
	}

	public function draft()
	{
		$this->checkAvailableAccess();

		if (Auth::user()->isJMB()) {
			if (Request::ajax()) {
				$model = EServiceOrder::self()->draft();

				if (!empty(Input::get('company'))) {
					$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
					if ($cob) {
						$model = $model->where('eservices_orders.company_id', $cob->id);
					}
				}

				if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
					$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
					$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

					$model = $model->whereBetween('eservices_orders.created_at', [$start_date, $end_date]);
				}

				if (!empty(Input::get('letter_type'))) {
					$model = $model->where('eservices_orders.type', Input::get('letter_type'));
				}

				return Datatables::of($model)
					->editColumn('created_at', function ($model) {
						$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('eservice.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";

						return $created_at;
					})
					->editColumn('type', function ($model) {
						return $model->getTypeText();
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

						return $btn;
					})
					->make(true);
			}

			if (empty(Session::get('admin_cob'))) {
				$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
			} else {
				$company = Company::where('id', Session::get('admin_cob'))->get();
			}

			$types = [];
			$cob = Auth::user()->getCOB->short_name;
			if (!empty($cob)) {
				$options = (!empty($this->getModule()['cob'][Str::lower($cob)])) ? $this->getModule()['cob'][Str::lower($cob)]['type'] : '';
				if (!empty($options)) {
					foreach ($options as $type) {
						array_push($types, ['id' => $type['name'], 'text' => $type['title']]);
					}
				}
			}

			$viewData = array(
				'title' => trans('app.menus.eservice.draft'),
				'panel_nav_active' => 'eservice_panel',
				'main_nav_active' => 'eservice_main',
				'sub_nav_active' => 'eservice_draft',
				'company' => $company,
				'types' => $types,
				'image' => ''
			);

			return View::make('eservice.draft_list', $viewData);
		}

		App::abort(404);
	}

	public function approved()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$model = EServiceOrder::self()->approved();

			if (!empty(Input::get('company'))) {
				$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
				if ($cob) {
					$model = $model->where('eservices_orders.company_id', $cob->id);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
				$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

				$model = $model->whereBetween('eservices_orders.created_at', [$start_date, $end_date]);
			}

			if (!empty(Input::get('letter_type'))) {
				$model = $model->where('eservices_orders.type', Input::get('letter_type'));
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
					$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('eservice.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";

					return $created_at;
				})
				->editColumn('type', function ($model) {
					return $model->getTypeText();
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

					return $btn;
				})
				->make(true);
		}

		if (empty(Session::get('admin_cob'))) {
			$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
		} else {
			$company = Company::where('id', Session::get('admin_cob'))->get();
		}

		$types = [];
		$cob = Auth::user()->getCOB->short_name;
		if (!empty($cob)) {
			$options = (!empty($this->getModule()['cob'][Str::lower($cob)])) ? $this->getModule()['cob'][Str::lower($cob)]['type'] : '';
			if (!empty($options)) {
				foreach ($options as $type) {
					array_push($types, ['id' => $type['name'], 'text' => $type['title']]);
				}
			}
		}

		$viewData = array(
			'title' => trans('app.menus.eservice.approved'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_approved',
			'company' => $company,
			'types' => $types,
			'image' => ''
		);

		return View::make('eservice.approved_list', $viewData);
	}

	public function rejected()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$model = EServiceOrder::self()->rejected();

			if (!empty(Input::get('company'))) {
				$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
				if ($cob) {
					$model = $model->where('eservices_orders.company_id', $cob->id);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
				$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

				$model = $model->whereBetween('eservices_orders.created_at', [$start_date, $end_date]);
			}

			if (!empty(Input::get('letter_type'))) {
				$model = $model->where('eservices_orders.type', Input::get('letter_type'));
			}

			return Datatables::of($model)
				->editColumn('created_at', function ($model) {
					$created_at =  $model->created_at ? "<a style='text-decoration:underline;' href='" . route('eservice.show', $this->encodeID($model->id)) . "'>" . $model->created_at->format('d-M-Y H:i A') . "</a>" : "-";

					return $created_at;
				})
				->editColumn('type', function ($model) {
					return $model->getTypeText();
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

					return $btn;
				})
				->make(true);
		}

		if (empty(Session::get('admin_cob'))) {
			$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
		} else {
			$company = Company::where('id', Session::get('admin_cob'))->get();
		}

		$types = [];
		$cob = Auth::user()->getCOB->short_name;
		if (!empty($cob)) {
			$options = (!empty($this->getModule()['cob'][Str::lower($cob)])) ? $this->getModule()['cob'][Str::lower($cob)]['type'] : '';
			if (!empty($options)) {
				foreach ($options as $type) {
					array_push($types, ['id' => $type['name'], 'text' => $type['title']]);
				}
			}
		}

		$viewData = array(
			'title' => trans('app.menus.eservice.rejected'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_rejected',
			'company' => $company,
			'types' => $types,
			'image' => ''
		);

		return View::make('eservice.rejected_list', $viewData);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($type = '')
	{
		$this->checkAvailableAccess();

		if (Auth::user()->isJMB()) {
			$cob = Auth::user()->getCOB->short_name;
			if (!empty($cob)) {
				if (!empty($type)) {
					$title = $this->validateType($cob, $type);
					$form = $this->getFormView($cob, $type, $order = null);

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
					$options = EServiceOrder::getTypeOption();

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

		App::abort(404);
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

				$file = Files::with(['strata'])->find(Auth::user()->file_id);
				if ($file) {
					$strata = Strata::with(['categories'])->where('file_id', $file->id)->first();
					if ($strata && $strata->categories) {
						$pricing = EServicePrice::where('company_id', $cob->id)
							->where('category_id', $strata->categories->id)
							->where('slug', $type)
							->first();

						if ($pricing) {
							unset($request['cob']);
							unset($request['type']);

							$order_no = Auth::user()->id . date('YmdHis');

							$order = EServiceOrder::create([
								'company_id' => $cob->id,
								'file_id' => $strata->file->id,
								'strata_id' => $strata->id,
								'category_id' => $strata->categories->id,
								'user_id' => Auth::user()->id,
								'order_no' => $order_no,
								'type' => $type,
								'value' => json_encode($request),
								'price' => $pricing->price,
								'status' => EServiceOrder::DRAFT,
							]);

							if ($order) {
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
			$cob = $order->company->short_name;
			$type = $order->type;

			if (!empty($type)) {
				$title = $order->getTypeText();
				$form = $this->getFormViewReadOnly($cob, $type, $order);

				if ($order->status == EServiceOrder::DRAFT) {
					$sub_nav_active = 'eservice_draft';
				} else if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS])) {
					$sub_nav_active = 'eservice_list';
				} else if ($order->status == EServiceOrder::APPROVED) {
					$sub_nav_active = 'eservice_approved';
				} else {
					$sub_nav_active = 'eservice_rejected';
				}

				$viewData = array(
					'title' => trans('app.menus.eservice.show') .  ' - ' . $title,
					'panel_nav_active' => 'eservice_panel',
					'main_nav_active' => 'eservice_main',
					'sub_nav_active' => $sub_nav_active,
					'type' => $type,
					'form' => $form,
					'order' => $order,
					"statusOptions" => $statusOptions,
					'image' => ""
				);

				return View::make('eservice.show', $viewData);
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
				$cob = $order->company->short_name;
				$type = $order->type;

				if (!empty($type)) {
					$title = $order->getTypeText();
					$form = $this->getFormView($cob, $type, $order);

					$viewData = array(
						'title' => trans('app.menus.eservice.edit') .  ' - ' . $title,
						'panel_nav_active' => 'eservice_panel',
						'main_nav_active' => 'eservice_main',
						'sub_nav_active' => 'eservice_create',
						'type' => $type,
						'form' => $form,
						'order' => $order,
						'image' => ""
					);

					return View::make('eservice.edit', $viewData);
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
				$cob = $order->company->short_name;
				$type = $order->type;

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

						$success = $order->update([
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

	public function report()
	{
		$this->checkAvailableAccess();

		$data = EServiceOrder::getGraphData();

		// $types = EServiceOrder::getTypeList();

		// return '<pre>'. print_r($types, true) . '</pre>';

		$viewData = array(
			'title' => trans('app.menus.eservice.report'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_report',
			'data' => $data,
			'image' => ""
		);

		return View::make('eservice.report', $viewData);
	}

	public function payment($id)
	{
		$this->checkAvailableAccess();

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			if ($order->status == EServiceOrder::DRAFT) {
				$total_amount = $order->price;

				$viewData = array(
					'title' => trans('app.menus.eservice.payment'),
					'panel_nav_active' => 'eservice_panel',
					'main_nav_active' => 'eservice_main',
					'sub_nav_active' => 'eservice_create',
					'order' => $order,
					'total_amount' => $total_amount,
					'image' => ""
				);

				return View::make('eservice.payment', $viewData);
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

	public function review()
	{
		$this->checkAvailableAccess();

		$request = Input::all();

		if (isset($request['selected'])) {
			$selected = $request['selected'];

			if (!empty($selected)) {
				$orders = EServiceOrder::with(['file', 'strata'])
					->whereIn('id', $selected)
					->where('status', EServiceOrder::INPROGRESS)
					->get();

				if ($orders) {
					if (isset($request['approve'])) {
						$viewData = array(
							'title' => trans('app.menus.eservice.approve'),
							'panel_nav_active' => 'eservice_panel',
							'main_nav_active' => 'eservice_main',
							'sub_nav_active' => 'eservice_list',
							'orders' => $orders,
							'image' => ""
						);

						return View::make('eservice.approve', $viewData);
					} else {
						$viewData = array(
							'title' => trans('app.menus.eservice.reject'),
							'panel_nav_active' => 'eservice_panel',
							'main_nav_active' => 'eservice_main',
							'sub_nav_active' => 'eservice_list',
							'orders' => $orders,
							'image' => ""
						);

						return View::make('eservice.reject', $viewData);
					}
				}
			}
		}

		return Redirect::back()->with('error', trans('Fail, no orders have been selected!'));
	}

	public function verify()
	{
		$this->checkAvailableAccess();

		$request = Request::all();

		$rules = array(
			'password' => 'required',
		);

		$validator = Validator::make($request, $rules);

		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			if (Hash::check($request['password'], Auth::user()->password)) {
				return Response::json([
					'success' => true,
					'message' => trans('app.successes.updated_successfully')
				]);
			}
		}

		return Response::json([
			'error' => true,
			'message' => trans('app.errors.occurred')
		]);
	}

	public function submitApprove()
	{
		$this->checkAvailableAccess();

		$request = Request::all();

		$rules = array(
			'date' => 'required'
		);

		if (isset($request['bill_no'])) {
			foreach ($request['bill_no'] as $key => $bill_no) {
				$rules['bill_no.' . $key] = 'required';
				$attribute['bill_no.' . $key] = trans('app.forms.bill_no');
			}
		}

		$validator = Validator::make($request, $rules, [], $attribute);
		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$date = $request['date'];

			foreach ($request['bill_no'] as $id => $bill_no) {
				$this->approvedByID($date, $bill_no, $this->encodeID($id));
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

	public function submitReject()
	{
		$this->checkAvailableAccess();

		$request = Request::all();

		$rules = array(
			'approval_remark' => 'required'
		);

		$validator = Validator::make($request, $rules);
		if ($validator->fails()) {
			return Response::json([
				'error' => true,
				'errors' => $validator->errors(),
				'message' => trans('Validation Fail')
			]);
		} else {
			$approval_remark = (isset($request['approval_remark']) ? $request['approval_remark'] : null);

			foreach ($request['bill_no'] as $id => $bill_no) {
				$this->rejectedByID($approval_remark, $this->encodeID($id));
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
			if ($status == EServiceOrder::APPROVED) {
				$date = $request['date'];
				$bill_no = $request['bill_no'];

				$this->approvedByID($date, $bill_no, $id);
			} else if ($status == EServiceOrder::REJECTED) {
				$approval_remark = (isset($request['approval_remark']) ? $request['approval_remark'] : null);

				$this->rejectedByID($approval_remark, $id);
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

	public function getLetterPDF($id)
	{
		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			$type = $order->type;
			$filename = $type . "_" . date('YmdHis');
			$content = json_decode($order->value, true);

			$viewData = [
				'content' => $content,
				'filename' => $filename,
				'order' => $order,
			];

			$pdf = PDF::loadView('eservice.' . Str::lower($order->company->short_name) . '.pdf.' . $type, $viewData)->setPaper('A4', 'portrait');
			return $pdf->stream($filename);
		}

		App::abort(404);
	}

	public function getLetterWord($id)
	{
		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			$type = $order->type;
			$filename = $type . "_" . date('YmdHis');
			$content = json_decode($order->value, true);

			$viewData = [
				'content' => $content,
				'filename' => $filename,
				'order' => $order,
			];

			return View::make('eservice.' . Str::lower($order->company->short_name) . '.word.' . $type, $viewData);
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

	public function getFormViewReadOnly($cob, $type, $order)
	{
		$data = $this->getForm($cob, $type, $order);

		return View::make('eservice.partial.read_only', $data);
	}

	public function getFormView($cob, $type, $order)
	{
		$management = [];

		if (Auth::user()->isJMB()) {
			if (!empty(Auth::user()->file_id)) {
				$file = Files::with(['strata'])->find(Auth::user()->file_id);
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

		$data = $this->getForm($cob, $type, $order, $management);

		return View::make('eservice.partial.form', $data);
	}

	public function getForm($cob, $type, $order, $management = '')
	{
		$data = [];
		$data['attributes'] = $this->getFormAttributes($cob, $type);
		$data['fields'] = $this->getFormFields();

		if (!empty($order)) {
			$data['model'] = json_decode($order->value, true);
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

	private function approvedByID($date, $bill_no, $id)
	{
		$order = EServiceOrder::with(['user'])->find($this->decodeID($id));
		if ($order) {
			$old_status = $order->status;

			$success = $order->update([
				'bill_no' => $bill_no,
				'date' => $date,
				'status' => EServiceOrder::APPROVED,
				'approval_by' => Auth::user()->id,
				'approval_date' => date('Y-m-d'),
				'approval_remark' => null,
			]);

			if ($success) {
				/**
				 * If status rejected or success send an email to JMB / MC
				 */
				if (Config::get('mail.driver') != '') {
					$delay = 0;

					if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS, EServiceOrder::APPROVED, EServiceOrder::REJECTED])) {
						if (!empty($order->user->email) && Helper::validateEmail($order->user->email)) {
							Mail::later(Carbon::now()->addSeconds($delay), 'emails.eservice.status_update', array('model' => $order, 'status' => $order->getStatusText()), function ($message) use ($order) {
								$message->to($order->user->email, $order->user->full_name)->subject("Your Application e-Perkhidmatan has been " . Str::upper($order->getStatusText()));
							});
						}
					}
				}

				/**
				 * add audit trail
				 */
				$module = Str::upper($this->getModule()['name']);
				$status = ($old_status == $order->status ? '' : 'status');
				if (!empty($status)) {
					$audit_fields_changed = $order->getStatusText();
					$remarks = $module . ': ' . $order->id . $this->module['audit']['text']['status_updated'] . $audit_fields_changed;

					$this->addAudit($order->file_id, $module, $remarks);
				}
			}
		}
	}

	private function rejectedByID($approval_remark, $id)
	{
		$order = EServiceOrder::with(['user'])->find($this->decodeID($id));
		if ($order) {
			$old_status = $order->status;

			$success = $order->update([
				'status' => EServiceOrder::REJECTED,
				'approval_by' => Auth::user()->id,
				'approval_date' => date('Y-m-d'),
				'approval_remark' => (!empty($approval_remark) ? $approval_remark : null),
			]);

			if ($success) {
				/**
				 * If status rejected or success send an email to JMB / MC
				 */
				if (Config::get('mail.driver') != '') {
					$delay = 0;

					if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS, EServiceOrder::APPROVED, EServiceOrder::REJECTED])) {
						if (!empty($order->user->email) && Helper::validateEmail($order->user->email)) {
							Mail::later(Carbon::now()->addSeconds($delay), 'emails.eservice.status_update', array('model' => $order, 'status' => $order->getStatusText()), function ($message) use ($order) {
								$message->to($order->user->email, $order->user->full_name)->subject("Your Application e-Perkhidmatan has been " . Str::upper($order->getStatusText()));
							});
						}
					}
				}

				/**
				 * add audit trail
				 */
				$module = Str::upper($this->getModule()['name']);
				$status = ($old_status == $order->status ? '' : 'status');
				if (!empty($status)) {
					$audit_fields_changed = $order->getStatusText();
					$remarks = $module . ': ' . $order->id . $this->module['audit']['text']['status_updated'] . $audit_fields_changed;

					$this->addAudit($order->file_id, $module, $remarks);
				}
			}
		}
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
