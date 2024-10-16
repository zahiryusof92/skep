<?php

use Carbon\Carbon;
use Helper\Epay;
use Helper\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use yajra\Datatables\Facades\Datatables;

class EServiceController extends BaseController
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
		if (!empty($cob) && $cob != 'LPHS') {
			$options = (!empty($this->getModule()['cob'][Str::lower($cob)])) ? $this->getModule()['cob'][Str::lower($cob)]['type'] : '';
			if (!empty($options)) {
				foreach ($options as $type) {
					array_push($types, ['id' => $type['name'], 'text' => $type['title']]);
				}
			}
		} else {
			$cobs = Company::where('is_deleted', 0)->get();
			if ($cobs) {
				foreach ($cobs as $cob) {
					$options = (!empty($this->getModule()['cob'][Str::lower($cob->short_name)])) ? $this->getModule()['cob'][Str::lower($cob->short_name)]['type'] : '';
					if (!empty($options)) {
						foreach ($options as $type) {
							array_push($types, ['id' => $type['name'], 'text' => $type['title']]);
						}
					}
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

							$prefix = '';
							if ($cob->short_name == 'MBPJ') {
								$prefix = 'MBPJ-eCOB-';
							}

							$order_no = Auth::user()->id . date('YmdHis');

							$order = EServiceOrder::create([
								'company_id' => $cob->id,
								'file_id' => $strata->file->id,
								'strata_id' => $strata->id,
								'category_id' => $strata->categories->id,
								'user_id' => Auth::user()->id,
								'order_no' => $prefix . $order_no,
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
								$this->addAudit($order->file->id, $module, $remarks);

								return Response::json([
									'success' => true,
									'id' => $this->encodeID($order->id),
									'message' => trans('app.successes.saved_successfully')
								]);
							}
						} else {
							return Response::json([
								'error' => true,
								'message' => trans('Pricing have not been set')
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
		$edit = false;

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			if ((Auth::user()->getAdmin() || Auth::user()->isCOB()) && in_array($order->status, [EServiceOrder::INPROGRESS, EServiceOrder::REJECTED])) {
				$edit = true;
				$sub_nav_active = 'eservice_list';
			} else if ($order->status == EServiceOrder::DRAFT) {
				$edit = true;
				$sub_nav_active = 'eservice_create';
			}

			if ($edit) {
				$cob = $order->company->short_name;
				$type = $order->type;

				if (!empty($type)) {
					$title = $order->getTypeText();
					$form = $this->getFormView($cob, $type, $order);

					$viewData = array(
						'title' => trans('app.menus.eservice.edit') .  ' - ' . $title,
						'panel_nav_active' => 'eservice_panel',
						'main_nav_active' => 'eservice_main',
						'sub_nav_active' => $sub_nav_active,
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
		$update = false;

		$request = Request::all();

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			if ((Auth::user()->getAdmin() || Auth::user()->isCOB()) && in_array($order->status, [EServiceOrder::INPROGRESS, EServiceOrder::REJECTED])) {
				$update = true;
			} else if ($order->status == EServiceOrder::DRAFT) {
				$update = true;
			}

			if ($update) {
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
							'status' => ($order->status == EServiceOrder::REJECTED ? EServiceOrder::INPROGRESS : $order->status),
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

	public function submit($id)
	{
		$this->checkAvailableAccess();

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order && $order->status == EServiceOrder::DRAFT) {
			$total_amount = $order->price;

			if (!empty($order->price) && $order->price > 0) {
				$update = $order->update([
					'status' => EServiceOrder::INPROGRESS,
				]);

				if ($update) {
					/**
					 * Send an email to JMB / MC and copy to COB
					 */
					if (Config::get('mail.driver') != '') {
						if (!empty($order->user->email) && Helper::validateEmail($order->user->email)) {
							Mail::send('emails.eservice.new_application', array('model' => $order, 'status' => $order->getStatusText()), function ($message) use ($order) {
								$message->to($order->user->email, $order->user->full_name)->subject('New Application for e-Perkhidmatan');
							});
						}

						if ($order->user->isJMB() || $order->user->isMC()) {
							if (!empty(Config::get('payment.mbpj.email_cob'))) {
								Mail::send('emails.eservice.new_application_cob', array('model' => $order, 'date' => $order->created_at->toDayDateTimeString(), 'status' => $order->getStatusText()), function ($message) {
									$message->to(Config::get('payment.mbpj.email_cob'), 'COB')->subject('New Application for e-Perkhidmatan');
								});
							}
						}
					}

					/**
					 * add audit trail
					 */
					$module = Str::upper($this->getModule()['name']);
					$remarks = $module . ': Application #' . $order->order_no . ' has been submitted.';
					$remarks = $module . ': ' . $order->email . " has submitted a new application";
					$this->addAudit($order->file_id, $module, $remarks);

					return Redirect::route('eservice.index')->with('success', trans('app.successes.saved_successfully'));
				}
			}
		}

		App::abort(404);
	}

	public function payment($id)
	{
		$this->checkAvailableAccess();

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order && $order->status == EServiceOrder::DRAFT) {
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

		App::abort(404);
	}

	public function submitPayment()
	{
		$request = Input::all();
		$proceed = false;

		$order = EServiceOrder::with(['user', 'company'])->find($this->decodeID($request['order_id']));
		if ($order) {
			$rules = array(
				'amount' => 'required',
				'terms' => 'required'
			);

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			} else {
				if (!empty($order->price) && $order->price > 0) {
					if ($order->company && $order->company->short_name = 'MBPJ') {
						if (!empty($order->jana_bil_no_akaun)) {
							$proceed = true;
						} else {
							$content = (!empty($order->value) ? json_decode($order->value, true) : []);
							if (!empty($content)) {
								$cob = strtolower($order->company->short_name);

								$kodjabatan = Config::get('payment.' . $cob . '.kod_jabatan');
								$perkara = $order->order_no . ' - ' . str_replace('_', ' ', $order->type);
								$amaun = number_format($order->price, 2);
								$kodhasil = Config::get('payment.' . $cob . '.kod_hasil');
								$namapelanggan = Arr::get($content, 'management_name');
								$alamat1 = Arr::get($content, 'management_address1');
								$alamat2 = Arr::get($content, 'management_address2');
								$alamat3 = Arr::get($content, 'management_address3');
								$nokp = Config::get('payment.' . $cob . '.no_kp');
								$pengguna = Config::get('payment.' . $cob . '.pengguna');
								$sumber = Config::get('payment.' . $cob . '.sumber');

								$params = [
									'kodjabatan' => $kodjabatan,
									'perkara' => strtoupper($perkara),
									'amaun' => $amaun,
									'kodhasil' => $kodhasil,
									'namapelanggan' => strtoupper($namapelanggan),
									'alamat1' => strtoupper($alamat1),
									'alamat2' => strtoupper($alamat2),
									'alamat3' => strtoupper($alamat3),
									'nokp' => $nokp,
									'pengguna' => $pengguna,
									'sumber' => $sumber
								];

								$res_janabil = (new Epay())->generateBil($params);
								if ($res_janabil) {
									if (isset($res_janabil->status) && $res_janabil->status == 1) {
										if (!empty($res_janabil->noakaun)) {
											$update = $order->update([
												'jana_bil_no_akaun' => $res_janabil->noakaun,
												'jana_bil_response' => json_encode($res_janabil),
												'jana_bil_created_at' => (!empty($res_janabil->timeres) ? date('Y-m-d H:i:s', strtotime($res_janabil->timeres)) : date('Y-m-d H:i:s')),
											]);

											if ($update) {
												$proceed = true;
											}
										}
									} else {
										return Redirect::back()->with('error', 'Fail! ' . isset($res_janabil->message) ? $res_janabil->message : '');
									}
								}
							}
						}

						/** proceed payment */
						if ($proceed) {
							$payment = (new Epay())->paymentOnline($order->jana_bil_no_akaun, $this->encodeID($order->id));
							if (!empty($payment)) {
								return Redirect::to($payment);
							}
						}
					}
				} else {
					// free letter
					$update = $order->update([
						'status' => EServiceOrder::INPROGRESS,
					]);

					if ($update) {
						/**
						 * Send an email to JMB / MC and copy to COB
						 */
						if (Config::get('mail.driver') != '') {
							if (!empty($order->user->email) && Helper::validateEmail($order->user->email)) {
								Mail::send('emails.eservice.new_application', array('model' => $order, 'status' => $order->getStatusText()), function ($message) use ($order) {
									$message->to($order->user->email, $order->user->full_name)->subject('New Application for e-Perkhidmatan');
								});
							}

							if ($order->user->isJMB() || $order->user->isMC()) {
								if (!empty(Config::get('payment.mbpj.email_cob'))) {
									Mail::send('emails.eservice.new_application_cob', array('model' => $order, 'date' => $order->created_at->toDayDateTimeString(), 'status' => $order->getStatusText()), function ($message) {
										$message->to(Config::get('payment.mbpj.email_cob'), 'COB')->subject('New Application for e-Perkhidmatan');
									});
								}
							}
						}

						/**
						 * add audit trail
						 */
						$module = Str::upper($this->getModule()['name']);
						$remarks = $module . ': Application #' . $order->order_no . ' has been submitted.';
						$remarks = $module . ': ' . $order->email . " has submitted a new application";
						$this->addAudit($order->file_id, $module, $remarks);

						return Redirect::route('eservice.index')->with('success', trans('app.successes.saved_successfully'));
					}
				}
			}
		}

		return Redirect::back()->with('error', trans('app.errors.occurred'));
	}

	public function callbackPayment($id)
	{
		$request = Request::all();

		$message = '';

		if (!empty($id)) {
			$order = EServiceOrder::with(['user'])->find($this->decodeID($id));
			if ($order) {
				if ($order->company && $order->company->short_name = 'MBPJ') {
					if (!empty($request)) {
						$order->update([
							'reference_id' => Arr::get($request, 'pg_ref_id'),
						]);
						
						$status = EServiceOrderTransaction::PENDING;
						if (Arr::get($request, 'pg_status') == 1) {
							$status = EServiceOrderTransaction::APPROVED;
						} else if (Arr::get($request, 'pg_status') == 2) {
							$status = EServiceOrderTransaction::FAILED;
						} else if (Arr::get($request, 'pg_status') == 3) {
							$status = EServiceOrderTransaction::REJECTED;
						}

						if (Arr::get($request, 'pg_desc')) {
							$message = $request['pg_desc'];
						}

						if (Arr::get($request, 'pg_payment_type')) {
							$transaction = EServiceOrderTransaction::updateOrCreate(
								[
									'eservice_order_id' => $order->id,
									'payment_method' => Arr::get($request, 'pg_payment_type'),
									'payment_amount' => Arr::get($request, 'pg_amount'),
									'payment_receipt_no' => Arr::get($request, 'pg_receipt_no'),
                                    'payment_created_at' => (!empty(Arr::get($request, 'pg_payment_date')) ? date('Y-m-d H:i:s', strtotime(Arr::get($request, 'pg_payment_date'))) : date('Y-m-d H:i:s')),
								],
								[
									'payment_response' => json_encode($request),
									'status' => $status,
								]
							);

							if ($transaction->status == EServiceOrderTransaction::APPROVED) {
								$update = $order->update([
									'status' => EServiceOrder::INPROGRESS,
								]);

								if ($update) {
									/**
									 * Send an email to JMB / MC and copy to COB
									 */
									if (Config::get('mail.driver') != '') {
										if (!empty($order->user->email) && Helper::validateEmail($order->user->email)) {
											Mail::send('emails.eservice.new_application', array('model' => $order, 'status' => $order->getStatusText()), function ($message) use ($order) {
												$message->to($order->user->email, $order->user->full_name)->subject('New Application for e-Perkhidmatan');
											});
										}

										if ($order->user->isJMB() || $order->user->isMC()) {
											if (!empty(Config::get('payment.mbpj.email_cob'))) {
												Mail::send('emails.eservice.new_application_cob', array('model' => $order, 'date' => $order->created_at->toDayDateTimeString(), 'status' => $order->getStatusText()), function ($message) {
													$message->to(Config::get('payment.mbpj.email_cob'), 'COB')->subject('New Application for e-Perkhidmatan');
												});
											}
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
			}

			return Redirect::route('eservice.paymentHistory')->with('error', (!empty($message) ? 'Fail! ' . $message : trans('app.errors.occurred')));
		}
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
		if ($order && $order->status == EServiceOrder::APPROVED) {
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
		if ($order && $order->status == EServiceOrder::APPROVED) {
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

	public function paymentHistory()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$model = EServiceOrderTransaction::self();

			if (!empty(Input::get('company'))) {
				$cob = Company::where('company.short_name', Str::lower(Input::get('company')))->first();
				if ($cob) {
					$model = $model->where('eservices_order_transactions.company_id', $cob->id);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = !empty(Input::get('start_date')) ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 35, 13, 0, 0);
				$end_date = !empty(Input::get('end_date')) ? Carbon::parse(Input::get('end_date'))->addDay() : Carbon::now();

				$model = $model->whereBetween('eservices_order_transactions.payment_created_at', [$start_date, $end_date]);
			}

			return Datatables::of($model)
				->editColumn('payment_created_at', function ($model) {
					$payment_created_at =  (!empty($model->payment_created_at) ? date('d-M-Y h:i A', strtotime($model->payment_created_at)) : '-');

					return $payment_created_at;
				})
				->editColumn('order_no', function ($model) {
					$order_no =  $model->order_no ? "<a style='text-decoration:underline;' href='" . route('eservice.show', $this->encodeID($model->eservice_order_id)) . "'>" . $model->order_no . "</a>" : "-";

					return $order_no;
				})
				->editColumn('payment_method', function ($model) {
					if ($model->payment_method == 'cc') {
						$payment_method = trans('Credit Card');
					} else {
						$payment_method = strtoupper($model->payment_method);
					}

					return $payment_method;
				})
				->editColumn('payment_amount', function ($model) {
					return number_format($model->payment_amount, 2);
				})
				->editColumn('status', function ($model) {
					return $model->getStatusBadge();
				})
				->addColumn('action', function ($model) {
					$btn = '';
					$btn .= '<a href="' . route('eservice.showPaymentHistory', $this->encodeID($model->id)) . '" class="btn btn-xs btn-warning" title="Show"><i class="fa fa-eye"></i></a>';

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
			'title' => trans('app.menus.eservice.payment_history'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_payment_history',
			'company' => $company,
			'image' => ''
		);

		return View::make('eservice.payment_history', $viewData);
	}

	public function showPaymentHistory($id)
	{
		$transaction = EServiceOrderTransaction::with(['order'])->find($this->decodeID($id));

		if ($transaction) {
			$viewData = array(
				'title' => trans('app.menus.eservice.payment_history'),
				'panel_nav_active' => 'eservice_panel',
				'main_nav_active' => 'eservice_main',
				'sub_nav_active' => 'eservice_payment_history',
				'image' => '',
				'transaction' => $transaction
			);

			return View::make('eservice.show_payment_history', $viewData);
		}

		App::abort(404);
	}

    public function reconcile()
	{
		ini_set('max_execution_time', -1);

		$response = [];
		$request = Request::all();

		$limit = (isset($request['limit']) ? $request['limit'] : null);

		$orders = EServiceOrder::whereNotIn('status', [EServiceOrder::INPROGRESS, EServiceOrder::APPROVED])
			->orderBy('id', 'desc')
			->limit($limit)
			->get();

		if ($orders) {
			foreach ($orders as $order) {
				$params = [];
				$requestArray = [];

				$orderId = $this->encodeID($order->id);

				$reconcile = (new Epay())->reconcile($orderId);
				
				if (Arr::get($reconcile, 'status') && !empty(Arr::get($reconcile, 'response'))) {
					$queryString = Arr::get($reconcile, 'response');
					parse_str($queryString, $requestArray);

					$callback = $this->callbackReconcile($orderId, $requestArray);

					Arr::set($params, 'order_id', $order->id);
                    Arr::set($params, 'status', $callback);
                    Arr::set($params, 'response', $requestArray);

					array_push($response, $params);
				}
			}
		}

		return $response;
	}

	public function callbackReconcile($id, $request)
	{
		if (!empty($id)) {
			$order = EServiceOrder::with(['user'])->find($this->decodeID($id));
			if ($order) {
				if ($order->company && $order->company->short_name = 'MBPJ') {
					if (!empty($request)) {
						$status = EServiceOrderTransaction::PENDING;
						if (Arr::get($request, 'pg_status') == 1) {
							$status = EServiceOrderTransaction::APPROVED;
						} else if (Arr::get($request, 'pg_status') == 2) {
							$status = EServiceOrderTransaction::FAILED;
						} else if (Arr::get($request, 'pg_status') == 3) {
							$status = EServiceOrderTransaction::REJECTED;
						}

						if (Arr::get($request, 'pg_payment_type')) {
							$transaction = EServiceOrderTransaction::updateOrCreate(
								[
									'eservice_order_id' => $order->id,
									'payment_method' => Arr::get($request, 'pg_payment_type'),
									'payment_amount' => Arr::get($request, 'pg_amount'),
									'payment_receipt_no' => Arr::get($request, 'pg_receipt_no'),
                                    'payment_created_at' => (!empty(Arr::get($request, 'pg_payment_date')) ? date('Y-m-d H:i:s', strtotime(Arr::get($request, 'pg_payment_date'))) : date('Y-m-d H:i:s')),
								],
								[
									'payment_response' => json_encode($request),
									'status' => $status,
								]
							);

							if ($transaction->status == EServiceOrderTransaction::APPROVED) {
								$order->update([
									'status' => EServiceOrder::INPROGRESS,
								]);
							}
						}
					}
				}

				return $order->status;
			}
		}

		return false;
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
		if (!AccessGroup::hasAccessModule('e-Service')) {
			App::abort(404);
		}

		if (!Auth::user()->getAdmin() && (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name != "MBPJ")) {
			App::abort(404);
		}

		if (!empty($model) && $model->status != EServiceOrder::DRAFT) {
			App::abort(404);
		}
	}
=======
                $others_name = 0;
                $others_address = 0;
                $others_city = 0;
                $others_poscode = 0;
                $others_state = 0;
                $others_phone_no = 0;
                $others_fax_no = 0;
                $others_email = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * Management
                         */
                        if ($files->management) {
                            /*
                             * Others
                             */
                            if ($files->management->is_others) {
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->name) || $files->managementOthers->name == null) {
                                        $others_name++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->address1) || $files->managementOthers->address1 == null) {
                                        $others_address++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->city) || $files->managementOthers->city == 0) {
                                        $others_city++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->poscode) || $files->managementOthers->poscode == null) {
                                        $others_poscode++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->state) || $files->managementOthers->state == 0) {
                                        $others_state++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->phone_no) || $files->managementOthers->phone_no == null) {
                                        $others_phone_no++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->fax_no) || $files->managementOthers->fax_no == null) {
                                        $others_fax_no++;
                                    }
                                }
                                if ($files->managementOthers) {
                                    if (empty($files->managementOthers->email) || $files->managementOthers->email == null) {
                                        $others_email++;
                                    }
                                }

                                $others++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total Others') => $others,
                    trans('app.forms.name') => ($others - $others_name),
                    trans('app.forms.address') => ($others - $others_address),
                    trans('app.forms.city') => ($others - $others_city),
                    trans('app.forms.postcode') => ($others - $others_poscode),
                    trans('app.forms.state') => ($others - $others_state),
                    trans('app.forms.phone_number') => ($others - $others_phone_no),
                    trans('app.forms.fax_number') => ($others - $others_fax_no),
                    trans('app.forms.email') => ($others - $others_email),
                ];
            }
        }

        return $this->result($result, $filename = 'Others');
    }

    public function agm($cob)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $total_agm = 0;
                $agm_date = 0;
                $agm = 0;
                $agm_file_url = 0;
                $egm = 0;
                $egm_file_url = 0;
                $minit_meeting = 0;
                $minutes_meeting_file_url = 0;
                $jmc_spa = 0;
                $jmc_file_url = 0;
                $identity_card = 0;
                $ic_file_url = 0;
                $attendance = 0;
                $attendance_file_url = 0;
                $financial_report = 0;
                $audited_financial_file_url = 0;
                $audit_report = 0;
                $audit_report_url = 0;
                $letter_integrity_url = 0;
                $letter_bankruptcy_url = 0;
                $notice_agm_egm_url = 0;
                $minutes_agm_egm_url = 0;
                $minutes_ajk_url = 0;
                $eligible_vote_url = 0;
                $attend_meeting_url = 0;
                $proksi_url = 0;
                $ajk_info_url = 0;
                $ic_url = 0;
                $purchase_aggrement_url = 0;
                $strata_title_url = 0;
                $maenance_statement_url = 0;
                $integrity_pledge_url = 0;
                $sworn_statement_url = 0;
                $report_audited_financial_url = 0;
                $house_rules_url = 0;
                $audit_start_date = 0;
                $audit_end_date = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * AGM
                         */
                        if ($files->meetingDocument) {
                            foreach ($files->meetingDocument as $meetingDocument) {
                                if (empty($meetingDocument->agm_date) || $meetingDocument->agm_date <= 0) {
                                    $agm_date++;
                                }
                                if (empty($meetingDocument->agm) || $meetingDocument->agm == 0) {
                                    $agm++;
                                }
                                if (empty($meetingDocument->agm_file_url) || $meetingDocument->agm_file_url == null) {
                                    $agm_file_url++;
                                }
                                if (empty($meetingDocument->egm) || $meetingDocument->egm <= 0) {
                                    $egm++;
                                }
                                if (empty($meetingDocument->egm_file_url) || $meetingDocument->egm_file_url == null) {
                                    $egm_file_url++;
                                }
                                if (empty($meetingDocument->minit_meeting) || $meetingDocument->minit_meeting == 0) {
                                    $minit_meeting++;
                                }
                                if (empty($meetingDocument->minutes_meeting_file_url) || $meetingDocument->minutes_meeting_file_url == null) {
                                    $minutes_meeting_file_url++;
                                }
                                if (empty($meetingDocument->jmc_spa) || $meetingDocument->jmc_spa == 0) {
                                    $jmc_spa++;
                                }
                                if (empty($meetingDocument->jmc_file_url) || $meetingDocument->jmc_file_url == null) {
                                    $jmc_file_url++;
                                }
                                if (empty($meetingDocument->identity_card) || $meetingDocument->identity_card == 0) {
                                    $identity_card++;
                                }
                                if (empty($meetingDocument->ic_file_url) || $meetingDocument->ic_file_url == null) {
                                    $ic_file_url++;
                                }
                                if (empty($meetingDocument->attendance) || $meetingDocument->attendance == 0) {
                                    $attendance++;
                                }
                                if (empty($meetingDocument->attendance_file_url) || $meetingDocument->attendance_file_url == null) {
                                    $attendance_file_url++;
                                }
                                if (empty($meetingDocument->financial_report) || $meetingDocument->attendance == 0) {
                                    $financial_report++;
                                }
                                if (empty($meetingDocument->audited_financial_file_url) || $meetingDocument->audited_financial_file_url == null) {
                                    $audited_financial_file_url++;
                                }
                                if (empty($meetingDocument->audit_report) || $meetingDocument->audit_report == null) {
                                    $audit_report++;
                                }
                                if (empty($meetingDocument->audit_report_url) || $meetingDocument->audit_report_url == null) {
                                    $audit_report_url++;
                                }
                                if (empty($meetingDocument->letter_integrity_url) || $meetingDocument->letter_integrity_url == null) {
                                    $letter_integrity_url++;
                                }
                                if (empty($meetingDocument->letter_bankruptcy_url) || $meetingDocument->letter_bankruptcy_url == null) {
                                    $letter_bankruptcy_url++;
                                }
                                if (empty($meetingDocument->notice_agm_egm_url) || $meetingDocument->notice_agm_egm_url == null) {
                                    $notice_agm_egm_url++;
                                }
                                if (empty($meetingDocument->minutes_agm_egm_url) || $meetingDocument->minutes_agm_egm_url == null) {
                                    $minutes_agm_egm_url++;
                                }
                                if (empty($meetingDocument->minutes_ajk_url) || $meetingDocument->minutes_ajk_url == null) {
                                    $minutes_ajk_url++;
                                }
                                if (empty($meetingDocument->eligible_vote_url) || $meetingDocument->eligible_vote_url == null) {
                                    $eligible_vote_url++;
                                }
                                if (empty($meetingDocument->attend_meeting_url) || $meetingDocument->attend_meeting_url == null) {
                                    $attend_meeting_url++;
                                }
                                if (empty($meetingDocument->proksi_url) || $meetingDocument->proksi_url == null) {
                                    $proksi_url++;
                                }
                                if (empty($meetingDocument->ajk_info_url) || $meetingDocument->ajk_info_url == null) {
                                    $ajk_info_url++;
                                }
                                if (empty($meetingDocument->ic_url) || $meetingDocument->ic_url == null) {
                                    $ic_url++;
                                }
                                if (empty($meetingDocument->purchase_aggrement_url) || $meetingDocument->purchase_aggrement_url == null) {
                                    $purchase_aggrement_url++;
                                }
                                if (empty($meetingDocument->strata_title_url) || $meetingDocument->strata_title_url == null) {
                                    $strata_title_url++;
                                }
                                if (empty($meetingDocument->maenance_statement_url) || $meetingDocument->maenance_statement_url == null) {
                                    $maenance_statement_url++;
                                }
                                if (empty($meetingDocument->integrity_pledge_url) || $meetingDocument->integrity_pledge_url == null) {
                                    $integrity_pledge_url++;
                                }
                                if (empty($meetingDocument->sworn_statement_url) || $meetingDocument->sworn_statement_url == null) {
                                    $sworn_statement_url++;
                                }
                                if (empty($meetingDocument->report_audited_financial_url) || $meetingDocument->report_audited_financial_url == null) {
                                    $report_audited_financial_url++;
                                }
                                if (empty($meetingDocument->house_rules_url) || $meetingDocument->house_rules_url == null) {
                                    $house_rules_url++;
                                }
                                if (empty($meetingDocument->audit_start_date) || $meetingDocument->audit_start_date <= 0) {
                                    $audit_start_date++;
                                }
                                if (empty($meetingDocument->audit_end_date) || $meetingDocument->audit_end_date <= 0) {
                                    $audit_end_date++;
                                }

                                $total_agm++;
                            }
                        }
                    }
                }

                $total_council = count($council->files);

                $result[$council->short_name] = [
                    trans('Council') => $council->short_name,
                    trans('Total Files') => $total_council,
                    trans('Total AGM') => $total_agm,
                    trans('app.forms.agm_date') => ($total_agm - $agm_date),
                    trans('app.forms.annual_general_meeting') => ($total_agm - $agm),
                    trans('app.forms.upload_notice_agm_egm') => ($total_agm - $agm_file_url),
                    trans('app.forms.extra_general_meeting') => ($total_agm - $egm),
                    trans('app.forms.upload_minutes_agm_egm') => ($total_agm - $egm_file_url),
                    trans('app.forms.meeting_minutes') => ($total_agm - $minit_meeting),
                    trans('app.forms.upload_minutes_ajk') => ($total_agm - $minutes_meeting_file_url),
                    trans('app.forms.jmc_spa_copy') => ($total_agm - $jmc_spa),
                    trans('app.forms.pledge_letter_of_integrity') => ($total_agm - $jmc_file_url),
                    trans('app.forms.identity_card_list') => ($total_agm - $identity_card),
                    trans('Identity Card List File') => ($total_agm - $ic_file_url),
                    trans('app.forms.attendance_list') => ($total_agm - $attendance),
                    trans('Attendance List File') => ($total_agm - $attendance_file_url),
                    trans('app.forms.audited_financial_report') => ($total_agm - $financial_report),
                    trans('Audited Financial Report File') => ($total_agm - $audited_financial_file_url),
                    trans('app.forms.financial_audit_report') => ($total_agm - $audit_report),
                    trans('Financial Audit Report File') => ($total_agm - $audit_report_url),
                    trans('app.forms.pledge_letter_of_integrity') => ($total_agm - $letter_integrity_url),
                    trans('app.forms.declaration_letter_of_non_bankruptcy') => ($total_agm - $letter_bankruptcy_url),
                    trans('app.forms.upload_notice_agm_egm') => ($total_agm - $notice_agm_egm_url),
                    trans('app.forms.upload_minutes_agm_egm') => ($total_agm - $minutes_agm_egm_url),
                    trans('app.forms.upload_minutes_ajk') => ($total_agm - $minutes_ajk_url),
                    trans('app.forms.upload_eligible_vote') => ($total_agm - $eligible_vote_url),
                    trans('app.forms.upload_attend_meeting') => ($total_agm - $attend_meeting_url),
                    trans('app.forms.upload_proksi') => ($total_agm - $proksi_url),
                    trans('app.forms.upload_ajk_info') => ($total_agm - $ajk_info_url),
                    trans('app.forms.upload_ic') => ($total_agm - $ic_url),
                    trans('app.forms.upload_purchase_aggrement') => ($total_agm - $purchase_aggrement_url),
                    trans('app.forms.upload_strata_title') => ($total_agm - $strata_title_url),
                    trans('app.forms.upload_maenance_statement') => ($total_agm - $maenance_statement_url),
                    trans('app.forms.upload_integrity_pledge') => ($total_agm - $integrity_pledge_url),
                    trans('app.forms.upload_sworn_statement') => ($total_agm - $sworn_statement_url),
                    trans('app.forms.upload_report_audited_financial') => ($total_agm - $report_audited_financial_url),
                    trans('app.forms.upload_house_rules') => ($total_agm - $house_rules_url),
                    trans('app.forms.financial_audit_start_date') => ($total_agm - $audit_start_date),
                    trans('app.forms.financial_audit_end_date') => ($total_agm - $audit_end_date),
                ];
            }
        }

        return $this->result($result, $filename = 'AGM');
    }

    public function owner($cob)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $total_owner = 0;
                $name = 0;
                $unit_no = 0;
                $unit_share = 0;
                $ic_company_no = 0;
                $address = 0;
                $phone_no = 0;
                $email = 0;
                $race = 0;
                $nationality = 0;
                $no_petak = 0;
                $no_petak_aksesori = 0;
                $keluasan_lantai_petak = 0;
                $keluasan_lantai_petak_aksesori = 0;
                $jenis_kegunaan = 0;
                $nama2 = 0;
                $ic_no2 = 0;
                $alamat_surat_menyurat = 0;
                $caj_penyelenggaraan = 0;
                $sinking_fund = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * AGM
                         */
                        if ($files->owner) {
                            foreach ($files->owner as $owner) {
                                if (empty($owner->owner_name) || $owner->owner_name == null) {
                                    $name++;
                                }
                                if (empty($owner->unit_no) || $owner->unit_no == null) {
                                    $unit_no++;
                                }
                                if (empty($owner->unit_share) || $owner->unit_share == null) {
                                    $unit_share++;
                                }
                                if (empty($owner->ic_company_no) || $owner->ic_company_no == null) {
                                    $ic_company_no++;
                                }
                                if (empty($owner->address) || $owner->address == null) {
                                    $address++;
                                }
                                if (empty($owner->phone_no) || $owner->phone_no == null) {
                                    $phone_no++;
                                }
                                if (empty($owner->email) || $owner->email == null) {
                                    $email++;
                                }
                                if (empty($owner->race_id) || $owner->race_id == 0) {
                                    $race++;
                                }
                                if (empty($owner->nationality_id) || $owner->nationality_id == 0) {
                                    $nationality++;
                                }
                                if (empty($owner->no_petak) || $owner->no_petak == null) {
                                    $no_petak++;
                                }
                                if (empty($owner->no_petak_aksesori) || $owner->no_petak_aksesori == null) {
                                    $no_petak_aksesori++;
                                }
                                if (empty($owner->keluasan_lantai_petak) || $owner->keluasan_lantai_petak == null) {
                                    $keluasan_lantai_petak++;
                                }
                                if (empty($owner->keluasan_lantai_petak_aksesori) || $owner->keluasan_lantai_petak_aksesori == 0) {
                                    $keluasan_lantai_petak_aksesori++;
                                }
                                if (empty($owner->jenis_kegunaan) || $owner->jenis_kegunaan == null) {
                                    $jenis_kegunaan++;
                                }
                                if (empty($owner->nama2) || $owner->nama2 == 0) {
                                    $nama2++;
                                }
                                if (empty($owner->ic_no2) || $owner->ic_no2 == 0) {
                                    $ic_no2++;
                                }
                                if (empty($owner->alamat_surat_menyurat) || $owner->alamat_surat_menyurat == 0) {
                                    $alamat_surat_menyurat++;
                                }
                                if (empty($owner->caj_penyelenggaraan) || $owner->caj_penyelenggaraan == 0) {
                                    $caj_penyelenggaraan++;
                                }
                                if (empty($owner->sinking_fund) || $owner->sinking_fund == 0) {
                                    $sinking_fund++;
                                }

                                $total_owner++;
                            }
                        }
                    }

                    $total_council = count($council->files);

                    $result[$council->short_name] = [
                        trans('Council') => $council->short_name,
                        trans('Total Files') => $total_council,
                        trans('Total Owner') => $total_owner,
                        trans('app.forms.name') => ($total_owner - $name),
                        trans('app.forms.unit_number') => ($total_owner - $unit_no),
                        trans('app.forms.unit_share') => ($total_owner - $unit_share),
                        trans('app.forms.ic_company_number') => ($total_owner - $ic_company_no),
                        trans('app.forms.address') => ($total_owner - $address),
                        trans('app.forms.phone_number') => ($total_owner - $phone_no),
                        trans('app.forms.email') => ($total_owner - $email),
                        trans('app.forms.race') => ($total_owner - $race),
                        trans('app.forms.nationality') => ($total_owner - $nationality),
                        trans('app.forms.no_petak') => ($total_owner - $no_petak),
                        trans('app.forms.no_petak_aksesori') => ($total_owner - $no_petak_aksesori),
                        trans('app.forms.keluasan_lantai_petak') => ($total_owner - $keluasan_lantai_petak),
                        trans('app.forms.keluasan_lantai_petak_aksesori') => ($total_owner - $keluasan_lantai_petak_aksesori),
                        trans('app.forms.jenis_kegunaan') => ($total_owner - $jenis_kegunaan),
                        trans('app.forms.nama2') => ($total_owner - $nama2),
                        trans('app.forms.ic_no2') => ($total_owner - $ic_no2),
                        trans('app.forms.alamat_surat_menyurat') => ($total_owner - $alamat_surat_menyurat),
                        trans('app.forms.caj_penyelenggaraan') => ($total_owner - $caj_penyelenggaraan),
                        trans('app.forms.sinking_fund') => ($total_owner - $sinking_fund),
                    ];
                }
            }
        }

        return $this->result($result, $filename = 'Owner');
    }

    public function tenant($cob)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $total_tenant = 0;
                $name = 0;
                $unit_no = 0;
                $unit_share = 0;
                $ic_company_no = 0;
                $address = 0;
                $phone_no = 0;
                $email = 0;
                $race = 0;
                $nationality = 0;
                $no_petak = 0;
                $no_petak_aksesori = 0;
                $keluasan_lantai_petak = 0;
                $keluasan_lantai_petak_aksesori = 0;
                $jenis_kegunaan = 0;
                $nama2 = 0;
                $ic_no2 = 0;
                $alamat_surat_menyurat = 0;
                $caj_penyelenggaraan = 0;
                $sinking_fund = 0;

                if ($council->files) {
                    foreach ($council->files as $files) {
                        /*
                         * AGM
                         */
                        if ($files->owner) {
                            foreach ($files->tenant as $tenant) {
                                if (empty($tenant->tenant_name) || $tenant->tenant_name == null) {
                                    $name++;
                                }
                                if (empty($tenant->unit_no) || $tenant->unit_no == null) {
                                    $unit_no++;
                                }
                                if (empty($tenant->unit_share) || $tenant->unit_share == null) {
                                    $unit_share++;
                                }
                                if (empty($tenant->ic_company_no) || $tenant->ic_company_no == null) {
                                    $ic_company_no++;
                                }
                                if (empty($tenant->address) || $tenant->address == null) {
                                    $address++;
                                }
                                if (empty($tenant->phone_no) || $tenant->phone_no == null) {
                                    $phone_no++;
                                }
                                if (empty($tenant->email) || $tenant->email == null) {
                                    $email++;
                                }
                                if (empty($tenant->race_id) || $tenant->race_id == 0) {
                                    $race++;
                                }
                                if (empty($tenant->nationality_id) || $tenant->nationality_id == 0) {
                                    $nationality++;
                                }
                                if (empty($tenant->no_petak) || $tenant->no_petak == null) {
                                    $no_petak++;
                                }
                                if (empty($tenant->no_petak_aksesori) || $tenant->no_petak_aksesori == null) {
                                    $no_petak_aksesori++;
                                }
                                if (empty($tenant->keluasan_lantai_petak) || $tenant->keluasan_lantai_petak == null) {
                                    $keluasan_lantai_petak++;
                                }
                                if (empty($tenant->keluasan_lantai_petak_aksesori) || $tenant->keluasan_lantai_petak_aksesori == 0) {
                                    $keluasan_lantai_petak_aksesori++;
                                }
                                if (empty($tenant->jenis_kegunaan) || $tenant->jenis_kegunaan == null) {
                                    $jenis_kegunaan++;
                                }
                                if (empty($tenant->nama2) || $tenant->nama2 == 0) {
                                    $nama2++;
                                }
                                if (empty($tenant->ic_no2) || $tenant->ic_no2 == 0) {
                                    $ic_no2++;
                                }
                                if (empty($tenant->alamat_surat_menyurat) || $tenant->alamat_surat_menyurat == 0) {
                                    $alamat_surat_menyurat++;
                                }
                                if (empty($tenant->caj_penyelenggaraan) || $tenant->caj_penyelenggaraan == 0) {
                                    $caj_penyelenggaraan++;
                                }
                                if (empty($tenant->sinking_fund) || $tenant->sinking_fund == 0) {
                                    $sinking_fund++;
                                }

                                $total_tenant++;
                            }
                        }
                    }

                    $total_council = count($council->files);

                    $result[$council->short_name] = [
                        trans('Council') => $council->short_name,
                        trans('Total Files') => $total_council,
                        trans('Total Tenant') => $total_tenant,
                        trans('app.forms.name') => ($total_tenant - $name),
                        trans('app.forms.unit_number') => ($total_tenant - $unit_no),
                        trans('app.forms.unit_share') => ($total_tenant - $unit_share),
                        trans('app.forms.ic_company_number') => ($total_tenant - $ic_company_no),
                        trans('app.forms.address') => ($total_tenant - $address),
                        trans('app.forms.phone_number') => ($total_tenant - $phone_no),
                        trans('app.forms.email') => ($total_tenant - $email),
                        trans('app.forms.race') => ($total_tenant - $race),
                        trans('app.forms.nationality') => ($total_tenant - $nationality),
                        trans('app.forms.no_petak') => ($total_tenant - $no_petak),
                        trans('app.forms.no_petak_aksesori') => ($total_tenant - $no_petak_aksesori),
                        trans('app.forms.keluasan_lantai_petak') => ($total_tenant - $keluasan_lantai_petak),
                        trans('app.forms.keluasan_lantai_petak_aksesori') => ($total_tenant - $keluasan_lantai_petak_aksesori),
                        trans('app.forms.jenis_kegunaan') => ($total_tenant - $jenis_kegunaan),
                        trans('app.forms.nama2') => ($total_tenant - $nama2),
                        trans('app.forms.ic_no2') => ($total_tenant - $ic_no2),
                        trans('app.forms.alamat_surat_menyurat') => ($total_tenant - $alamat_surat_menyurat),
                        trans('app.forms.caj_penyelenggaraan') => ($total_tenant - $caj_penyelenggaraan),
                        trans('app.forms.sinking_fund') => ($total_tenant - $sinking_fund),
                    ];
                }
            }
        }

        return $this->result($result, $filename = 'Tenant');
    }

    public function updateJMBExpiration($cob = null, $date = null)
    {
        $councils = $this->council($cob);
        if ($councils) {
            foreach ($councils as $council) {
                $jmb_role = Role::where('name', Role::JMB)->pluck('id');
                $users = User::where('role', $jmb_role)
                    ->where('company_id', $council->id)
                    // ->where('remarks', 'Created by System')
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->get();

                foreach ($users as $user) {
                    $user->end_date = (!empty($date) ? $date : date('Y') . '-12-31');
                    $user->save();
                }
            }
        }

        return 'update done';
    }

    public function updateRatingSummary()
    {
        if (Auth::check() && Auth::user()->getAdmin()) {
            $items = Scoring::where('is_deleted', 0)->get();

            foreach ($items as $item) {
                $item_date = date('Y-m-d', strtotime($item->updated_at));
                if ($item_date < date('Y-m-d') && date('Y-m-d') == '2021-09-22') {
                    $item->score1 = ($item->score1 > 0) ? (($item->score1 == 5) ? $item->score1 : $item->score1 + 1) : 1;
                    $item->score2 = ($item->score2 > 0) ? (($item->score2 == 5) ? $item->score2 : $item->score2 + 1) : 1;
                    $item->score3 = ($item->score3 > 0) ? (($item->score3 == 5) ? $item->score3 : $item->score3 + 1) : 1;
                    $item->score4 = ($item->score4 > 0) ? (($item->score4 == 5) ? $item->score4 : $item->score4 + 1) : 1;
                    $item->score5 = ($item->score5 > 0) ? (($item->score5 == 5) ? $item->score5 : $item->score5 + 1) : 1;
                    $item->score6 = ($item->score6 > 0) ? (($item->score6 == 5) ? $item->score6 : $item->score6 + 1) : 1;
                    $item->score7 = ($item->score7 > 0) ? (($item->score7 == 5) ? $item->score7 : $item->score7 + 1) : 1;
                    $item->score8 = ($item->score8 > 0) ? (($item->score8 == 5) ? $item->score8 : $item->score8 + 1) : 1;
                    $item->score9 = ($item->score9 > 0) ? (($item->score9 == 5) ? $item->score9 : $item->score9 + 1) : 1;
                    $item->score10 = ($item->score10 > 0) ? (($item->score10 == 5) ? $item->score10 : $item->score10 + 1) : 1;
                    $item->score11 = ($item->score11 > 0) ? (($item->score11 == 5) ? $item->score11 : $item->score11 + 1) : 1;
                    $item->score12 = ($item->score12 > 0) ? (($item->score12 == 5) ? $item->score12 : $item->score12 + 1) : 1;
                    $item->score13 = ($item->score13 > 0) ? (($item->score13 == 5) ? $item->score13 : $item->score13 + 1) : 1;
                    $item->score14 = ($item->score14 > 0) ? (($item->score14 == 5) ? $item->score14 : $item->score14 + 1) : 1;
                    $item->score15 = ($item->score15 > 0) ? (($item->score15 == 5) ? $item->score15 : $item->score15 + 1) : 1;
                    $item->score16 = ($item->score16 > 0) ? (($item->score16 == 5) ? $item->score16 : $item->score16 + 1) : 1;
                    $item->score17 = ($item->score17 > 0) ? (($item->score17 == 5) ? $item->score17 : $item->score17 + 1) : 1;
                    $item->score18 = ($item->score18 > 0) ? (($item->score18 == 5) ? $item->score18 : $item->score18 + 1) : 1;
                    $item->score19 = ($item->score19 > 0) ? (($item->score19 == 5) ? $item->score19 : $item->score19 + 1) : 1;
                    $item->score20 = ($item->score20 > 0) ? (($item->score20 == 5) ? $item->score20 : $item->score20 + 1) : 1;
                    $item->score21 = ($item->score21 > 0) ? (($item->score21 == 5) ? $item->score21 : $item->score21 + 1) : 1;
                    $scorings_A = ((($item->score1 + $item->score2 + $item->score3 + $item->score4 + $item->score5) / 25) * 25);
                    $scorings_B = ((($item->score6 + $item->score7 + $item->score8 + $item->score9 + $item->score10) / 25) * 25);
                    $scorings_C = ((($item->score11 + $item->score12 + $item->score13 + $item->score14) / 20) * 20);
                    $scorings_D = ((($item->score15 + $item->score16 + $item->score17 + $item->score18) / 20) * 20);
                    $scorings_E = ((($item->score19 + $item->score20 + $item->score21) / 15) * 10);
                    $item->total_score = $scorings_A + $scorings_B + $scorings_C + $scorings_D + $scorings_E;
                    $item->save();
                }
            }

            return [
                'success' => true,
                'message' => 'done'
            ];
        } else {
            $viewData = array(
                'title' => trans('app.errors.page_not_found'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => '',
                'image' => ""
            );
            return View::make('404_en', $viewData);
        }
    }

    public function odesiLife($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $files) {
                        if ($files->strata) {
                            $total_unit = 0;
                            if ($files->strata->residential) {
                                if ($files->strata->residential->unit_no > 0) {
                                    $total_unit = $total_unit + $files->strata->residential->unit_no;
                                }
                            }
                            if ($files->strata->commercial) {
                                if ($files->strata->commercial->unit_no > 0) {
                                    $total_unit = $total_unit + $files->strata->commercial->unit_no;
                                }
                            }

                            $developer_name = '';
                            $developer_address1 = '';
                            $developer_address2 = '';
                            $developer_address3 = '';
                            $developer_address4 = '';
                            $developer_postcode = '';
                            $developer_city = '';
                            $developer_state = '';
                            $developer_phone_no = '';
                            $developer_email = '';
                            if ($files->managementDeveloper) {
                                if ($files->managementDeveloper->name) {
                                    $developer_name = $files->managementDeveloper->name;
                                }
                                if ($files->managementDeveloper->address_1) {
                                    $developer_address1 = $files->managementDeveloper->address_1;
                                }
                                if ($files->managementDeveloper->address_2) {
                                    $developer_address2 = $files->managementDeveloper->address_2;
                                }
                                if ($files->managementDeveloper->address_3) {
                                    $developer_address3 = $files->managementDeveloper->address_3;
                                }
                                if ($files->managementDeveloper->address_4) {
                                    $developer_address4 = $files->managementDeveloper->address_4;
                                }
                                if ($files->managementDeveloper->poscode) {
                                    $developer_postcode = $files->managementDeveloper->poscode;
                                }
                                if ($files->managementDeveloper->city) {
                                    $developer_city = $files->managementDeveloper->cities->description;
                                }
                                if ($files->managementDeveloper->state) {
                                    $developer_state = $files->managementDeveloper->states->name;
                                }
                                if ($files->managementDeveloper->phone_no) {
                                    $developer_phone_no = $files->managementDeveloper->phone_no;
                                }
                                if ($files->managementDeveloper->email) {
                                    $developer_email = $files->managementDeveloper->email;
                                }
                            }

                            $jmb_name = '';
                            $jmb_address1 = '';
                            $jmb_address2 = '';
                            $jmb_address3 = '';
                            $jmb_address4 = '';
                            $jmb_postcode = '';
                            $jmb_city = '';
                            $jmb_state = '';
                            $jmb_phone_no = '';
                            $jmb_email = '';
                            if ($files->managementJMB) {
                                if ($files->managementJMB->name) {
                                    $jmb_name = $files->managementJMB->name;
                                }
                                if ($files->managementJMB->address1) {
                                    $jmb_address1 = $files->managementJMB->address1;
                                }
                                if ($files->managementJMB->address2) {
                                    $jmb_address2 = $files->managementJMB->address2;
                                }
                                if ($files->managementJMB->address3) {
                                    $jmb_address3 = $files->managementJMB->address3;
                                }
                                if ($files->managementJMB->address4) {
                                    $jmb_address4 = $files->managementJMB->address4;
                                }
                                if ($files->managementJMB->poscode) {
                                    $jmb_postcode = $files->managementJMB->poscode;
                                }
                                if ($files->managementJMB->city) {
                                    $jmb_city = $files->managementJMB->cities->description;
                                }
                                if ($files->managementJMB->state) {
                                    $jmb_state = $files->managementJMB->states->name;
                                }
                                if ($files->managementJMB->phone_no) {
                                    $jmb_phone_no = $files->managementJMB->phone_no;
                                }
                                if ($files->managementJMB->email) {
                                    $jmb_email = $files->managementJMB->email;
                                }
                            }

                            $mc_name = '';
                            $mc_address1 = '';
                            $mc_address2 = '';
                            $mc_address3 = '';
                            $mc_address4 = '';
                            $mc_postcode = '';
                            $mc_city = '';
                            $mc_state = '';
                            $mc_phone_no = '';
                            $mc_email = '';
                            if ($files->managementMC) {
                                if ($files->managementMC->name) {
                                    $mc_name = $files->managementMC->name;
                                }
                                if ($files->managementMC->address1) {
                                    $mc_address1 = $files->managementMC->address1;
                                }
                                if ($files->managementMC->address2) {
                                    $mc_address2 = $files->managementMC->address2;
                                }
                                if ($files->managementMC->address3) {
                                    $mc_address3 = $files->managementMC->address3;
                                }
                                if ($files->managementMC->address4) {
                                    $mc_address4 = $files->managementMC->address4;
                                }
                                if ($files->managementMC->poscode) {
                                    $mc_postcode = $files->managementMC->poscode;
                                }
                                if ($files->managementMC->city) {
                                    $mc_city = $files->managementMC->cities->description;
                                }
                                if ($files->managementMC->state) {
                                    $mc_state = $files->managementMC->states->name;
                                }
                                if ($files->managementMC->phone_no) {
                                    $mc_phone_no = $files->managementMC->phone_no;
                                }
                                if ($files->managementMC->email) {
                                    $mc_email = $files->managementMC->email;
                                }
                            }

                            $agent_name = '';
                            $agent_address1 = '';
                            $agent_address2 = '';
                            $agent_address3 = '';
                            $agent_address4 = '';
                            $agent_postcode = '';
                            $agent_city = '';
                            $agent_state = '';
                            $agent_phone_no = '';
                            $agent_email = '';
                            if ($files->managementAgent) {
                                if ($files->managementAgent->name) {
                                    $agent_name = $files->managementAgent->name;
                                }
                                if ($files->managementAgent->address1) {
                                    $agent_address1 = $files->managementAgent->address1;
                                }
                                if ($files->managementAgent->address2) {
                                    $agent_address2 = $files->managementAgent->address2;
                                }
                                if ($files->managementAgent->address3) {
                                    $agent_address3 = $files->managementAgent->address3;
                                }
                                if ($files->managementAgent->address4) {
                                    $agent_address4 = $files->managementAgent->address4;
                                }
                                if ($files->managementAgent->poscode) {
                                    $agent_postcode = $files->managementAgent->poscode;
                                }
                                if ($files->managementAgent->city) {
                                    $agent_city = $files->managementAgent->cities->description;
                                }
                                if ($files->managementAgent->state) {
                                    $agent_state = $files->managementAgent->states->name;
                                }
                                if ($files->managementAgent->phone_no) {
                                    $agent_phone_no = $files->managementAgent->phone_no;
                                }
                                if ($files->managementAgent->email) {
                                    $agent_email = $files->managementAgent->email;
                                }
                            }

                            $others_name = '';
                            $others_address1 = '';
                            $others_address2 = '';
                            $others_address3 = '';
                            $others_address4 = '';
                            $others_postcode = '';
                            $others_city = '';
                            $others_state = '';
                            $others_phone_no = '';
                            $others_email = '';
                            if ($files->managementOthers) {
                                if ($files->managementOthers->name) {
                                    $others_name = $files->managementOthers->name;
                                }
                                if ($files->managementOthers->address1) {
                                    $others_address1 = $files->managementOthers->address1;
                                }
                                if ($files->managementOthers->address2) {
                                    $others_address2 = $files->managementOthers->address2;
                                }
                                if ($files->managementOthers->address3) {
                                    $others_address3 = $files->managementOthers->address3;
                                }
                                if ($files->managementOthers->address4) {
                                    $others_address4 = $files->managementOthers->address4;
                                }
                                if ($files->managementOthers->poscode) {
                                    $others_postcode = $files->managementOthers->poscode;
                                }
                                if ($files->managementOthers->city) {
                                    $others_city = $files->managementOthers->cities->description;
                                }
                                if ($files->managementOthers->state) {
                                    $others_state = $files->managementOthers->states->name;
                                }
                                if ($files->managementOthers->phone_no) {
                                    $others_phone_no = $files->managementOthers->phone_no;
                                }
                                if ($files->managementOthers->email) {
                                    $others_email = $files->managementOthers->email;
                                }
                            }

                            $pic_name = '';
                            $pic_phone_no = '';
                            $pic_email = '';
                            if ($files->personInCharge) {
                                foreach ($files->personInCharge as $pic) {
                                    if ($pic->user->full_name) {
                                        $pic_name = $pic->user->full_name;
                                    }
                                    if ($pic->user->phone_no) {
                                        $pic_phone_no = $pic->user->phone_no;
                                    }
                                    if ($pic->user->email) {
                                        $pic_email = $pic->user->email;
                                    }
                                }
                            }

                            $result[] = [
                                trans('Council') => $council->name . ' (' . $council->short_name . ')',
                                trans('File No') => $files->file_no,
                                trans('Building Name') => $files->strata->name,
                                trans('Address 1') => $files->strata->address1,
                                trans('Address 2') => $files->strata->address2,
                                trans('Address 3') => $files->strata->address3,
                                trans('Address 4') => $files->strata->address4,
                                trans('Postcode') => $files->strata->poscode,
                                trans('City') => ($files->strata->city ? $files->strata->cities->description : ''),
                                trans('State') => ($files->strata->state ? $files->strata->states->name : ''),
                                trans('Land Title') => ($files->strata->landTitle ? $files->strata->landTitle->description : ''),
                                trans('Category') => ($files->strata->categories ? $files->strata->categories->description : ''),
                                trans('No of Block') => $files->strata->block_no,
                                trans('Total Floor') => $files->strata->total_floor,
                                trans('Total Unit') => $total_unit,

                                trans('PIC Name') => $pic_name,
                                trans('PIC Phone No') => $pic_phone_no,
                                trans('PIC E-mail') => $pic_email,

                                trans('Developer Name') => $developer_name,
                                trans('Developer Address 1') => $developer_address1,
                                trans('Developer Address 2') => $developer_address2,
                                trans('Developer Address 3') => $developer_address3,
                                trans('Developer Address 4') => $developer_address4,
                                trans('Developer Postcode') => $developer_postcode,
                                trans('Developer City') => $developer_city,
                                trans('Developer State') => $developer_state,
                                trans('Developer Phone No') => $developer_phone_no,
                                trans('Developer E-mail') => $developer_email,

                                trans('JMB Name') => $jmb_name,
                                trans('JMB Address 1') => $jmb_address1,
                                trans('JMB Address 2') => $jmb_address2,
                                trans('JMB Address 3') => $jmb_address3,
                                trans('JMB Address 4') => $jmb_address4,
                                trans('JMB Postcode') => $jmb_postcode,
                                trans('JMB City') => $jmb_city,
                                trans('JMB State') => $jmb_state,
                                trans('JMB Phone No') => $jmb_phone_no,
                                trans('JMB E-mail') => $jmb_email,

                                trans('MC Name') => $mc_name,
                                trans('MC Address 1') => $mc_address1,
                                trans('MC Address 2') => $mc_address2,
                                trans('MC Address 3') => $mc_address3,
                                trans('MC Address 4') => $mc_address4,
                                trans('MC Postcode') => $mc_postcode,
                                trans('MC City') => $mc_city,
                                trans('MC State') => $mc_state,
                                trans('MC Phone No') => $mc_phone_no,
                                trans('MC E-mail') => $mc_email,

                                trans('Agent Name') => $agent_name,
                                trans('Agent Address 1') => $agent_address1,
                                trans('Agent Address 2') => $agent_address2,
                                trans('Agent Address 3') => $agent_address3,
                                trans('Agent Address 4') => $agent_address4,
                                trans('Agent Postcode') => $agent_postcode,
                                trans('Agent City') => $agent_city,
                                trans('Agent State') => $agent_state,
                                trans('Agent Phone No') => $agent_phone_no,
                                trans('Agent E-mail') => $agent_email,

                                trans('Others Name') => $others_name,
                                trans('Others Address 1') => $others_address1,
                                trans('Others Address 2') => $others_address2,
                                trans('Others Address 3') => $others_address3,
                                trans('Others Address 4') => $others_address4,
                                trans('Others Postcode') => $others_postcode,
                                trans('Others City') => $others_city,
                                trans('Others State') => $others_state,
                                trans('Others Phone No') => $others_phone_no,
                                trans('Others E-mail') => $others_email,
                            ];
                        }
                    }
                }
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = strtoupper($cob));
    }

    public function JMBMCSignIn($cob = null)
    {
        $result = [];

        $council = Company::where('short_name', $cob)->first();
        if ($council) {
            $users = User::where('company_id', $council->id)
                ->where('file_id', '!=', '')
                ->orderBy('file_id')
                ->get();

            if ($users) {
                foreach ($users as $user) {
                    if ($user->hasSignedIn) {
                        $file = Files::find($user->file_id);
                        if ($file) {
                            $auditTrails = AuditTrail::where('audit_by', $user->id)
                                ->where('remarks', 'like', '%' . $file->file_no . '%')
                                ->get();

                            $result[] = [
                                trans('Username') => $user->username,
                                trans('Name') => $user->full_name,
                                trans('E-mail') => $user->email,
                                trans('Phone') => $user->phone_no,
                                trans('Role') => ($user->isJMB() ? 'JMB' : 'MC'),
                                trans('Start Date') => $user->start_date,
                                trans('End Date') => $user->end_date,
                                trans('Remarks') => $user->remarks,
                                trans('Login At') => $user->hasSignedIn->created_at->format('Y-m-d H:i:s'),
                                trans('File No.') => $file->file_no,
                                trans('Strata') => ($file->strata ? $file->strata->name : ''),
                                trans('Self Update') => ($auditTrails->count() > 0 ? 'Yes' : 'No')
                            ];
                        }
                    }
                }
            }
        }

        return $this->result($result, strtoupper($cob), 'excel');
    }

    public function updateByUser($username = null)
    {
        $result = [];

        $user = User::where('username', $username)->first();
        if ($user) {
            $auditTrails = AuditTrail::where('audit_by', $user->id)->get();

            $result[] = [
                trans('Username') => $user->username,
                trans('Name') => $user->full_name,
                trans('E-mail') => $user->email,
                trans('Phone') => $user->phone_no,
                trans('Role') => ($user->isJMB() ? 'JMB' : 'MC'),
                trans('Start Date') => $user->start_date,
                trans('End Date') => $user->end_date,
                trans('Remarks') => $user->remarks,
                trans('Audit Trail') => $auditTrails->toArray()
            ];
        }

        return $this->result($result, strtoupper($username), '');
    }

    public function neverHasAGM($cob = null)
    {
        $result = [];

        $query = Files::join('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('strata', 'files.id', '=', 'strata.file_id')
            ->where(function ($query) {
                $query->whereDoesntHave('meetingDocument');
                $query->orWhereHas('meetingDocument', function ($query2) {
                    $query2->where('meeting_document.agm_date', '0000-00-00');
                    $query2->where('meeting_document.is_deleted', 0);
                });
            })
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('files.file_no');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'strata.name as strata_name')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $pic = HousingSchemeUser::join('users', 'housing_scheme_user.user_id', '=', 'users.id')
                    ->where('housing_scheme_user.file_id', $item->file_id)
                    ->select('users.full_name as pic_name', 'users.phone_no as pic_phone', 'users.email as pic_email')
                    ->orderBy('users.id', 'desc')
                    ->first();

                $pic_name = '';
                $pic_phone = '';
                $pic_email = '';
                if ($pic) {
                    $pic_name = $pic->pic_name;
                    $pic_phone = $pic->pic_phone;
                    $pic_email = $pic->pic_email;
                }

                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Strata Name') => $item->strata_name,
                    trans('Person In Charge (PIC)') => $pic_name,
                    trans('PIC Phone No') => $pic_phone,
                    trans('PIC E-mail') => $pic_email,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Never Has AGM - ' . strtoupper($cob));
    }

    public function due12MonthsAGM($cob = null)
    {
        $result = [];

        $query = Files::join('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('strata', 'files.id', '=', 'strata.file_id')
            ->leftjoin('meeting_document', 'files.id', '=', 'meeting_document.file_id')
            ->where('meeting_document.agm_date', '!=', '0000-00-00')
            ->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-12 Months')))
            ->where('meeting_document.is_deleted', 0)
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('meeting_document.agm_date', 'desc');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'strata.name as strata_name', 'meeting_document.agm_date as agm_date')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $pic = HousingSchemeUser::join('users', 'housing_scheme_user.user_id', '=', 'users.id')
                    ->where('housing_scheme_user.file_id', $item->file_id)
                    ->select('users.full_name as pic_name', 'users.phone_no as pic_phone', 'users.email as pic_email')
                    ->orderBy('users.id', 'desc')
                    ->first();

                $pic_name = '';
                $pic_phone = '';
                $pic_email = '';
                if ($pic) {
                    $pic_name = $pic->pic_name;
                    $pic_phone = $pic->pic_phone;
                    $pic_email = $pic->pic_email;
                }

                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Strata Name') => $item->strata_name,
                    trans('Last AGM Date') => $item->agm_date,
                    trans('Person In Charge (PIC)') => $pic_name,
                    trans('PIC Phone No') => $pic_phone,
                    trans('PIC E-mail') => $pic_email,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Due 12 months AGM - ' . strtoupper($cob));
    }

    public function due15MonthsAGM($cob = null)
    {
        $result = [];

        $query = Files::join('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('strata', 'files.id', '=', 'strata.file_id')
            ->leftjoin('meeting_document', 'files.id', '=', 'meeting_document.file_id')
            ->where('meeting_document.agm_date', '!=', '0000-00-00')
            ->where('meeting_document.agm_date', '<=', date('Y-m-d', strtotime('-15 Months')))
            ->where('meeting_document.is_deleted', 0)
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('meeting_document.agm_date', 'desc');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'strata.name as strata_name', 'meeting_document.agm_date as agm_date')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $pic = HousingSchemeUser::join('users', 'housing_scheme_user.user_id', '=', 'users.id')
                    ->where('housing_scheme_user.file_id', $item->file_id)
                    ->select('users.full_name as pic_name', 'users.phone_no as pic_phone', 'users.email as pic_email')
                    ->orderBy('users.id', 'desc')
                    ->first();

                $pic_name = '';
                $pic_phone = '';
                $pic_email = '';
                if ($pic) {
                    $pic_name = $pic->pic_name;
                    $pic_phone = $pic->pic_phone;
                    $pic_email = $pic->pic_email;
                }

                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Strata Name') => $item->strata_name,
                    trans('Last AGM Date') => $item->agm_date,
                    trans('Person In Charge (PIC)') => $pic_name,
                    trans('PIC Phone No') => $pic_phone,
                    trans('PIC E-mail') => $pic_email,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Due 15 months AGM - ' . strtoupper($cob));
    }

    public function insurance($cob = null)
    {
        $result = [];

        $query = Insurance::join('files', 'insurance.file_id', '=', 'files.id')
            ->leftjoin('company', 'files.company_id', '=', 'company.id')
            ->leftjoin('insurance_provider', 'insurance.insurance_provider_id', '=', 'insurance_provider.id')
            ->where('files.is_active', 1)
            ->where('files.is_deleted', 0)
            ->where('insurance.is_deleted', 0)
            ->orderBy('company.short_name')
            ->orderBy('files.file_no');

        if (!empty($cob) && $cob != 'all') {
            $query = $query->where('company.short_name', $cob);
        }

        $items = $query->select('company.name as cob_name', 'company.short_name as cob_short_name', 'files.id as file_id', 'files.file_no as file_no', 'insurance_provider.name as provider', 'insurance.*')->get();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $result[] = [
                    trans('Council') => $item->cob_name . ' (' . $item->cob_short_name . ')',
                    trans('File No') => $item->file_no,
                    trans('Insurance Provider') => $item->provider,
                    trans('Public Liability Coverage (PLC)') => $item->public_liability_coverage,
                    trans('PLC Premium Per Year') => $item->plc_premium_per_year,
                    trans('PLC Validity From') => $item->plc_validity_from,
                    trans('PLC Validity To') => $item->plc_validity_to,
                    trans('Fire Insurance Coverage (FIC)') => $item->fire_insurance_coverage,
                    trans('FIC Premium Per Year') => $item->fic_premium_per_year,
                    trans('FIC Validity From') => $item->fic_validity_from,
                    trans('FIC Validity To') => $item->fic_validity_to,
                    trans('Remarks') => $item->remarks,
                ];
            }
        }

        // return '<pre>' . print_r($result, true) . '</pre>';

        return $this->result($result, $filename = 'Insurance - ' . strtoupper($cob));
    }

    public function financeOutstanding($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        if ($file->financeLatest) {
                            $finance = $file->financeLatest;

                            $mf_sepatut_dikutip = $finance->financeReport()->where('type', 'MF')->sum('fee_semasa');
                            $mf_extra_sepatut_dikutip = $finance->financeReportExtra()->where('type', 'MF')->sum('fee_semasa');

                            $sf_sepatut_dikutip = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                            $sf_extra_sepatut_dikutip = $finance->financeReportExtra()->where('type', 'SF')->sum('fee_semasa');

                            $mf_sf_sepatut_dikutip = $finance->financeReport()->sum('fee_semasa');
                            $mf_sf_extra_sepatut_dikutip = $finance->financeReportExtra()->sum('fee_semasa');

                            $total_mf_sepatut_dikutip = $mf_sepatut_dikutip + $mf_extra_sepatut_dikutip;
                            $total_sf_sepatut_dikutip = $sf_sepatut_dikutip + $sf_extra_sepatut_dikutip;
                            $total_mf_sf_sepatut_dikutip = $mf_sf_sepatut_dikutip + $mf_sf_extra_sepatut_dikutip;

                            $total_mf_berjaya_dikutip = $finance->financeIncome()->where('name', 'MAINTENANCE FEE')->sum('semasa');
                            $total_sf_berjaya_dikutip = $finance->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                            $total_mf_sf_berjaya_dikutip = $total_mf_berjaya_dikutip + $total_sf_berjaya_dikutip;

                            $total_mf_outstanding = $total_mf_sepatut_dikutip - $total_mf_berjaya_dikutip;
                            $total_sf_outstanding = $total_sf_sepatut_dikutip - $total_sf_berjaya_dikutip;
                            $total_mf_sf_outstanding = $total_mf_sf_sepatut_dikutip - $total_mf_sf_berjaya_dikutip;

                            $developer_name = '';
                            $developer_phone = '';
                            if ($file->managementDeveloper) {
                                $developer_name = $file->managementDeveloper->name;
                                $developer_phone = $file->managementDeveloper->phone_no;
                            }

                            $jmb_name = '';
                            $jmb_phone = '';
                            if ($file->managementJMB) {
                                $jmb_name = $file->managementJMB->name;
                                $jmb_phone = $file->managementJMB->phone_no;
                            }

                            $mc_name = '';
                            $mc_phone = '';
                            if ($file->managementMC) {
                                $mc_name = $file->managementMC->name;
                                $mc_phone = $file->managementMC->phone_no;
                            }

                            $agent_name = '';
                            $agent_phone = '';
                            if ($file->managementAgent) {
                                $agent_name = $file->managementAgent->name;
                                $agent_phone = $file->managementAgent->phone_no;
                            }

                            $other_name = '';
                            $other_phone = '';
                            if ($file->managementOthers) {
                                $other_name = $file->managementOthers->name;
                                $other_phone = $file->managementOthers->phone_no;
                            }

                            $result[$file->id] = [
                                'Council' => $council->short_name,
                                'File No' => $file->file_no,
                                'Strata Name' => $file->strata->name,
                                'Developer Name' => $developer_name,
                                'Developer Phone No.' => $developer_phone,
                                'JMB Name' => $jmb_name,
                                'JMB Phone No.' => $jmb_phone,
                                'MC Name' => $mc_name,
                                'MC Phone No.' => $mc_phone,
                                'Agent Name' => $agent_name,
                                'Agent Phone No.' => $agent_phone,
                                'Other Name' => $other_name,
                                'Other Phone No.' => $other_phone,
                                'Finance Last Updated' => strtoupper($finance->monthName()) . ' - ' . $finance->year,
                                'MF Amount (RM)' => number_format($total_mf_sepatut_dikutip, 2),
                                'SF Amount (RM)' => number_format($total_sf_sepatut_dikutip, 2),
                                'MF & SF Amount (RM)' => number_format($total_mf_sf_sepatut_dikutip, 2),
                                'Total MF Collected (RM)' => number_format($total_mf_berjaya_dikutip, 2),
                                'Total SF Collected (RM)' => number_format($total_sf_berjaya_dikutip, 2),
                                'Total MF & SF Collected (RM)' => number_format($total_mf_sf_berjaya_dikutip, 2),
                                'Total MF Outstanding (RM)' => number_format($total_mf_outstanding, 2),
                                'Total SF Outstanding (RM)' => number_format($total_sf_outstanding, 2),
                                'Total MF & SF Outstanding (RM)' => number_format($total_mf_sf_outstanding, 2),
                            ];
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Finance_Outstanding_' . strtoupper($cob));
    }

    public function strataByCategory($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        $strata = ($file->strata ? $file->strata->name : '');
                        $category = ($file->strata->categories ? $file->strata->categories->description : '');
                        $resident = ($file->resident ? $file->resident->unit_no : 0);
                        $commercial = ($file->commercial ? $file->commercial->unit_no : 0);

                        $developer_name = '';
                        $developer_phone = '';
                        if ($file->managementDeveloper) {
                            $developer_name = $file->managementDeveloper->name;
                            $developer_phone = $file->managementDeveloper->phone_no;
                        }

                        $jmb_name = '';
                        $jmb_phone = '';
                        if ($file->managementJMB) {
                            $jmb_name = $file->managementJMB->name;
                            $jmb_phone = $file->managementJMB->phone_no;
                        }

                        $mc_name = '';
                        $mc_phone = '';
                        if ($file->managementMC) {
                            $mc_name = $file->managementMC->name;
                            $mc_phone = $file->managementMC->phone_no;
                        }

                        $agent_name = '';
                        $agent_phone = '';
                        if ($file->managementAgent) {
                            $agent_name = $file->managementAgent->name;
                            $agent_phone = $file->managementAgent->phone_no;
                        }

                        $other_name = '';
                        $other_phone = '';
                        if ($file->managementOthers) {
                            $other_name = $file->managementOthers->name;
                            $other_phone = $file->managementOthers->phone_no;
                        }

                        $result[$file->id] = [
                            'Council' => $file->company->short_name,
                            'File No' => $file->file_no,
                            'Strata Name' => $strata,
                            'Category' => $category,
                            'Resident' => $resident,
                            'Commercial' => $commercial,
                            'Total Unit' => $resident + $commercial,
                            'Developer Name' => $developer_name,
                            'Developer Phone No.' => $developer_phone,
                            'JMB Name' => $jmb_name,
                            'JMB Phone No.' => $jmb_phone,
                            'MC Name' => $mc_name,
                            'MC Phone No.' => $mc_phone,
                            'Agent Name' => $agent_name,
                            'Agent Phone No.' => $agent_phone,
                            'Other Name' => $other_name,
                            'Other Phone No.' => $other_phone,
                        ];
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Strata_By_Category_' . strtoupper($cob));
    }

    public function electricity($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        $result[$file->id] = [];

                        Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                        Arr::set($result[$file->id], 'File No', $file->file_no);
                        Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));

                        $designations = Designation::where('is_deleted', 0)->orderBy('description')->get();
                        if ($designations) {
                            foreach ($designations as $designation) {
                                $ajk_detail = AJKDetails::where('file_id', $file->id)
                                    ->where('designation', $designation->id)
                                    ->where('is_deleted', 0)
                                    ->orderBy('start_year', 'desc')
                                    ->orderBy('month', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->first();

                                Arr::set($result[$file->id], $designation->description . ' Name', ($ajk_detail ? $ajk_detail->name : ''));
                                Arr::set($result[$file->id], $designation->description . ' E-mail', ($ajk_detail ? $ajk_detail->email : ''));
                                Arr::set($result[$file->id], $designation->description . ' Phone No', ($ajk_detail ? $ajk_detail->phone_no : ''));
                            }
                        }

                        // tnb bill
                        $tnb_bill = 0;
                        if ($finance = $file->financeLatest) {
                            $summary = FinanceSummary::where('finance_file_id', $finance->id)
                            ->where('summary_key', 'bill_elektrik')
                            ->first();

                            if ($summary) {
                                $tnb_bill = $summary->amount;
                            }
                        }

                        Arr::set($result[$file->id], 'TNB Bill (RM)', number_format($tnb_bill, 2));

                        // total residential unit
                        $total_residential_unit = 0;
                        if ($resident = $file->resident) {
                            $total_residential_unit = (!empty($resident->unit_no) ? $resident->unit_no : 0);
                        }

                        $total_residential_unit_extra = 0;
                        if ($residentExtra = $file->residentExtra) {
                            $total_residential_unit_extra = (!empty($residentExtra->unit_no) ? $residentExtra->unit_no : 0);
                        }

                        Arr::set($result[$file->id], 'Total Residential Unit', $total_residential_unit + $total_residential_unit_extra);

                        // total commercial unit
                        $total_commercial_unit = 0;
                        if ($commercial = $file->commercial) {
                            $total_commercial_unit = (!empty($commercial->unit_no) ? $commercial->unit_no : 0);
                        }

                        $total_commercial_unit_extra = 0;
                        if ($commercialExtra = $file->commercialExtra) {
                            $total_commercial_unit_extra = (!empty($commercialExtra->unit_no) ? $commercialExtra->unit_no : 0);
                        }

                        Arr::set($result[$file->id], 'Total Commercial Unit', $total_commercial_unit + $total_commercial_unit_extra);

                        // total unit
                        $total_unit = $total_residential_unit + $total_residential_unit_extra + $total_commercial_unit + $total_commercial_unit_extra;

                        Arr::set($result[$file->id], 'Total Unit', $total_unit);

                        // total floor
                        $total_floor = 0;
                        if ($strata = $file->strata) {
                            $total_floor = (!empty($strata->total_floor) ? $strata->total_floor : 0);
                        }
                        Arr::set($result[$file->id], 'Total Floor', $total_floor);

                        // mf details
                        $mf_fee = 0;
                        $all_mf_fee_extra = '';
                        if ($finance = $file->financeLatest) {
                            $mf_report = FinanceReport::where('finance_file_id', $finance->id)
                                ->where('type', 'MF')
                                ->first();

                            if ($mf_report) {
                                $mf_fee = $mf_report->unit . ' unit(s) x RM' . $mf_report->fee_sebulan;
                            }

                            $mf_report_extras = FinanceReportExtra::where('finance_file_id', $finance->id)
                                ->where('type', 'MF')
                                ->get();

                            if ($mf_report_extras) {
                                foreach ($mf_report_extras as $mf_report_extra) {
                                    $mf_fee_extra = $mf_report_extra->unit . ' unit(s) x RM' . $mf_report_extra->fee_sebulan;

                                    $all_mf_fee_extra = $all_mf_fee_extra . '; ' . $mf_fee_extra;
                                }
                            }
                        }
                        Arr::set($result[$file->id], 'MF Fee', $mf_fee . $all_mf_fee_extra);

                        // sf details
                        $sf_fee = 0;
                        $all_sf_fee_extra = '';
                        if ($finance = $file->financeLatest) {
                            $sf_report = FinanceReport::where('finance_file_id', $finance->id)
                                ->where('type', 'SF')
                                ->first();

                            if ($sf_report) {
                                $sf_fee = $sf_report->unit . ' unit(s) x RM' . $sf_report->fee_sebulan;
                            }

                            $sf_report_extras = FinanceReportExtra::where('finance_file_id', $finance->id)
                                ->where('type', 'SF')
                                ->get();

                            if ($sf_report_extras) {
                                foreach ($sf_report_extras as $sf_report_extra) {
                                    $sf_fee_extra = $sf_report_extra->unit . ' unit(s) x RM' . $sf_report_extra->fee_sebulan;

                                    $all_sf_fee_extra = $all_sf_fee_extra . '; ' . $sf_fee_extra;
                                }
                            }
                        }
                        Arr::set($result[$file->id], 'SF Fee', $sf_fee . $all_sf_fee_extra);
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Electricity_' . strtoupper($cob));
    }

    public function uploadOCR($cob = null)
    {
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        $meetings = $file->meetingDocument;
                        if ($meetings->count() > 0) {
                            foreach ($meetings as $meeting) {
                                $ocrs = $meeting->ocrs;
                                if ($ocrs->count() > 0) {
                                    $result[$meeting->id] = [];

                                    Arr::set($result[$meeting->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                                    Arr::set($result[$meeting->id], 'File No', $file->file_no);
                                    Arr::set($result[$meeting->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));
                                    Arr::set($result[$meeting->id], 'AGM Date', ($meeting->agm_date && $meeting->agm_date != '0000-00-00' ? $meeting->agm_date : ''));

                                    $notice_agm_egm = '';
                                    $minutes_agm_egm = '';
                                    $minutes_ajk = '';
                                    $ajk_info = '';
                                    $report_audited_financial = '';
                                    $house_rules = '';

                                    foreach ($ocrs as $ocr) {
                                        if ($ocr->type == 'notice_agm_egm' && !empty($ocr->url)) {
                                            $notice_agm_egm = 'Uploaded';
                                        }
                                        if ($ocr->type == 'minutes_agm_egm' && !empty($ocr->url)) {
                                            $minutes_agm_egm = 'Uploaded';
                                        }
                                        if ($ocr->type == 'minutes_ajk' && !empty($ocr->url)) {
                                            $minutes_ajk = 'Uploaded';
                                        }
                                        if ($ocr->type == 'ajk_info' && !empty($ocr->url)) {
                                            $ajk_info = 'Uploaded';
                                        }
                                        if ($ocr->type == 'report_audited_financial' && !empty($ocr->url)) {
                                            $report_audited_financial = 'Uploaded';
                                        }
                                        if ($ocr->type == 'house_rules' && !empty($ocr->url)) {
                                            $house_rules = 'Uploaded';
                                        }
                                    }

                                    Arr::set($result[$meeting->id], 'Salinan notis AGM/EGM OCR', $notice_agm_egm);
                                    Arr::set($result[$meeting->id], 'Salinan minit AGM/EGM OCR', $minutes_agm_egm);
                                    Arr::set($result[$meeting->id], 'Salinan minit mesyuarat 1st JMC OCR', $minutes_ajk);
                                    Arr::set($result[$meeting->id], 'Maklumat Anggota Jawatankuasa (Lampiran A) OCR', $ajk_info);
                                    Arr::set($result[$meeting->id], 'Laporan Akaun Teraudit OCR', $report_audited_financial);
                                    Arr::set($result[$meeting->id], 'Salinan kaedah-kaedah dalam yang diluluskan (House Rules) OCR', $house_rules);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Upload_OCR_' . strtoupper($cob));
    }

    public function commercial($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {
                        if ($file->commercial) {
                            $result[$file->id] = [];

                            Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                            Arr::set($result[$file->id], 'File No', $file->file_no);
                            Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));

                            $designations = Designation::where('is_deleted', 0)->orderBy('description')->get();
                            if ($designations) {
                                foreach ($designations as $designation) {
                                    $ajk_detail = AJKDetails::where('file_id', $file->id)
                                        ->where('designation', $designation->id)
                                        ->where('is_deleted', 0)
                                        ->orderBy('start_year', 'desc')
                                        ->orderBy('month', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                                    Arr::set($result[$file->id], $designation->description . ' Name', ($ajk_detail ? $ajk_detail->name : ''));
                                    Arr::set($result[$file->id], $designation->description . ' E-mail', ($ajk_detail ? $ajk_detail->email : ''));
                                    Arr::set($result[$file->id], $designation->description . ' Phone No', ($ajk_detail ? $ajk_detail->phone_no : ''));
                                }
                            }

                            // total residential unit
                            $total_residential_unit = 0;
                            if ($resident = $file->resident) {
                                $total_residential_unit = (!empty($resident->unit_no) ? $resident->unit_no : 0);
                            }

                            $total_residential_unit_extra = 0;
                            if ($residentExtra = $file->residentExtra) {
                                $total_residential_unit_extra = (!empty($residentExtra->unit_no) ? $residentExtra->unit_no : 0);
                            }

                            Arr::set($result[$file->id], 'Total Residential Unit', $total_residential_unit + $total_residential_unit_extra);

                            // total commercial unit
                            $total_commercial_unit = 0;
                            if ($commercial = $file->commercial) {
                                $total_commercial_unit = (!empty($commercial->unit_no) ? $commercial->unit_no : 0);
                            }

                            $total_commercial_unit_extra = 0;
                            if ($commercialExtra = $file->commercialExtra) {
                                $total_commercial_unit_extra = (!empty($commercialExtra->unit_no) ? $commercialExtra->unit_no : 0);
                            }

                            Arr::set($result[$file->id], 'Total Commercial Unit', $total_commercial_unit + $total_commercial_unit_extra);

                            // total unit
                            $total_unit = $total_residential_unit + $total_residential_unit_extra + $total_commercial_unit + $total_commercial_unit_extra;

                            Arr::set($result[$file->id], 'Total Unit', $total_unit);
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Commercial_' . strtoupper($cob));
    }

    public function extractData($cob = null, $year)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils && $year) {
            foreach ($councils as $council) {
                foreach ($council->files as $file) {
                    $result[$file->id] = [];

                    Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                    Arr::set($result[$file->id], 'File No', $file->file_no);
                    Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));
                    Arr::set($result[$file->id], 'Year', $year);

                    /**
                     * Finance
                     */
                    for ($month = 1; $month <= 12; $month++) {
                        $finance = DB::table('finance_file')
                            ->join('files', 'finance_file.file_id', '=', 'files.id')
                            ->where('finance_file.month', $month)
                            ->where('finance_file.year', $year)
                            ->where('files.company_id', $council->id)
                            ->where('files.id', $file->id)
                            ->where('files.is_deleted', 0)
                            ->where('finance_file.company_id', $council->id)
                            ->where('finance_file.is_deleted', 0)
                            ->count();

                        $dateObj = DateTime::createFromFormat('!m', $month);
                        $monthName = $dateObj->format('F');
                        Arr::set($result[$file->id], trans('Finance') . ' ' . $monthName . ' ' . $year, $finance ? 'Yes' : '');
                    }

                    /**
                     * AGM
                     */
                    $agm = DB::table('meeting_document')
                        ->join('files', 'meeting_document.file_id', '=', 'files.id')
                        ->where('files.company_id', $council->id)
                        ->where('files.id', $file->id)
                        ->where('files.is_deleted', 0)
                        ->whereYear('meeting_document.agm_date', '=', $year)
                        ->where('meeting_document.is_deleted', 0)
                        ->count();

                    Arr::set($result[$file->id], trans('AGM') . ' ' . $year, $agm ? 'Yes' : '');

                    /**
                     * AJK
                     */
                    $ajk = DB::table('ajk_details')
                        ->join('files', 'ajk_details.file_id', '=', 'files.id')
                        ->where('files.company_id', $council->id)
                        ->where('files.id', $file->id)
                        ->where('files.is_deleted', 0)
                        ->where('ajk_details.start_year', $year)
                        ->where('ajk_details.is_deleted', 0)
                        ->count();

                    Arr::set($result[$file->id], trans('AJK') . ' ' . $year, $ajk ? 'Yes' : '');

                    /**
                     * Document
                     */
                    $document = DB::table('document')
                        ->join('files', 'document.file_id', '=', 'files.id')
                        ->where('files.company_id', $council->id)
                        ->where('files.id', $file->id)
                        ->where('files.is_deleted', 0)
                        ->whereYear('document.created_at', '=', $year)
                        ->where('document.is_deleted', 0)
                        ->count();

                    Arr::set($result[$file->id], trans('Document') . ' ' . $year, $document ? 'Yes' : '');
                
                }
            }
        }

        return $this->result($result, $filename = 'Extract_Data_' . $year . '_' . strtoupper($cob));
    }

    public function agmHasBeenApproved($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->files) {
                    foreach ($council->files as $file) {

                        $meetingDocuments = DB::table('files')
                            ->select(
                                'meeting_document.*',
                                'meeting_document_statuses.user_id as approved_by',
                                'meeting_document_statuses.endorsed_by as endorsed_by',
                                'meeting_document_statuses.endorsed_email as endorsed_email',
                                'meeting_document_statuses.created_at as endorsed_date'
                            )
                            ->join('meeting_document', 'files.id', '=', 'meeting_document.file_id')
                            ->join('meeting_document_statuses', 'meeting_document.id', '=', 'meeting_document_statuses.meeting_document_id')
                            ->where('files.id', $file->id)
                            ->where('files.is_deleted', false)
                            ->where('meeting_document.is_deleted', false)
                            ->where('meeting_document_statuses.status', 'approved')
                            ->where('meeting_document_statuses.is_deleted', false)
                            ->orderBy('meeting_document.agm_date')
                            ->get();

                        if ($meetingDocuments) {
                            foreach ($meetingDocuments as $meetingDocument) {
                                $result[$meetingDocument->id] = [];

                                $approver = User::find($meetingDocument->approved_by);

                                Arr::set($result[$meetingDocument->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                                Arr::set($result[$meetingDocument->id], 'File No', $file->file_no);
                                Arr::set($result[$meetingDocument->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));
                                Arr::set($result[$meetingDocument->id], 'AGM Date', (!empty($meetingDocument->agm_date) ? $meetingDocument->agm_date : ''));
                                Arr::set($result[$meetingDocument->id], 'Endorsed By', (!empty($meetingDocument->endorsed_by) ? $meetingDocument->endorsed_by : ''));
                                Arr::set($result[$meetingDocument->id], 'Endorsed E-mail', (!empty($meetingDocument->endorsed_email) ? $meetingDocument->endorsed_email : ''));
                                Arr::set($result[$meetingDocument->id], 'Approved By', ($approver ? $approver->full_name : ''));
                                Arr::set($result[$meetingDocument->id], 'Approved Date', (!empty($meetingDocument->endorsed_date) ? $meetingDocument->endorsed_date : ''));
                            }
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = 'AGM_Has_Been_Approved_' . strtoupper($cob));
    }

    public function exportOwner($cob = null, $category = 'all', $page = 'all')
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $query = Buyer::select(
                        'buyer.*',
                        'files.file_no as file_no',
                        'company.short_name as company_name',
                        'strata.name as strata_name',
                        'category.description as category_name'
                    )
                    ->join('files', 'files.id', '=', 'buyer.file_id')
                    ->join('company', 'company.id', '=', 'files.company_id')
                    ->join('strata', 'strata.file_id', '=', 'files.id')
                    ->join('category', 'category.id', '=', 'strata.category')
                    ->where('buyer.is_deleted', false)
                    ->where('files.is_deleted', false)
                    ->where('company.is_deleted', false)
                    ->where('category.is_deleted', false)
                    ->where('company.id', $council->id)
                    ->orderBy('category.description')
                    ->orderBy('files.file_no');

                if (!empty($category) && $category != 'all') {
                    $query->where('category.description', $category);
                }

                if ($page != 'all') {
                    $limit = 3000;

                    if ($page == 1) {
                        $skip = 0 * $limit;
                    } else if ($page == 1) {
                        $skip = 1 * $limit;
                    } else if ($page == 2) {
                        $skip = 2 * $limit;
                    } else if ($page == 3) {
                        $skip = 3 * $limit;
                    } else if ($page == 4) {
                        $skip = 4 * $limit;
                    } else if ($page == 5) {
                        $skip = 5 * $limit;
                    } else if ($page == 6) {
                        $skip = 6 * $limit;
                    } else if ($page == 7) {
                        $skip = 7 * $limit;
                    } else if ($page == 8) {
                        $skip = 8 * $limit;
                    } else if ($page == 9) {
                        $skip = 9 * $limit;
                    } else if ($page == 10) {
                        $skip = 10 * $limit;
                    } else {
                        $skip = 0 * $limit;
                    }

                    $query->skip($skip)->take($limit);
                }

                $owners = $query->get();

                if ($owners) {
                    foreach ($owners as $owner) {
                        Arr::set($result[$owner->id], 'Council', (!empty($owner->company_name) ? $owner->company_name : ''));
                        Arr::set($result[$owner->id], 'File No', (!empty($owner->file_no) ? $owner->file_no : ''));
                        Arr::set($result[$owner->id], 'Strata Name', (!empty($owner->strata_name) ? $owner->strata_name : ''));
                        Arr::set($result[$owner->id], 'Category', (!empty($owner->category_name) ? $owner->category_name : ''));
                        Arr::set($result[$owner->id], 'Unit No', (!empty($owner->unit_no) ? $owner->unit_no : ''));
                        Arr::set($result[$owner->id], 'No Petak', (!empty($owner->no_petak) ? $owner->no_petak : ''));
                        Arr::set($result[$owner->id], 'No Petak Aksesori ', (!empty($owner->no_petak_aksesori) ? $owner->no_petak_aksesori : ''));
                        Arr::set($result[$owner->id], 'Keluasan Lantai Petak', (!empty($owner->keluasan_lantai_petak) ? $owner->keluasan_lantai_petak : ''));
                        Arr::set($result[$owner->id], 'Keluasan Lantai Petak Aksesori', (!empty($owner->keluasan_lantai_petak_aksesori) ? $owner->keluasan_lantai_petak_aksesori : ''));
                        Arr::set($result[$owner->id], 'Unit Share', (!empty($owner->unit_share) ? $owner->unit_share : ''));
                        Arr::set($result[$owner->id], 'Jenis Kegunaan', (!empty($owner->jenis_kegunaan) ? $owner->jenis_kegunaan : ''));
                        Arr::set($result[$owner->id], 'Owner Name', (!empty($owner->owner_name) ? $owner->owner_name : ''));
                        Arr::set($result[$owner->id], 'Owner IC No', (!empty($owner->ic_company_no) ? $owner->ic_company_no : ''));
                        Arr::set($result[$owner->id], 'Owner Phone No', (!empty($owner->phone_no) ? $owner->phone_no : ''));
                        Arr::set($result[$owner->id], 'Owner E-mail', (!empty($owner->email) ? $owner->email : ''));
                        Arr::set($result[$owner->id], 'Owner Race', ($owner->race ? $owner->race->name_en : ''));
                        Arr::set($result[$owner->id], 'Owner Address', (!empty($owner->address) ? $owner->address : ''));
                        Arr::set($result[$owner->id], 'Owner Alamat Surat Menyurat', (!empty($owner->alamat_surat_menyurat) ? $owner->alamat_surat_menyurat : ''));
                        Arr::set($result[$owner->id], 'Owner Nationality', ($owner->nationality ? $owner->nationality->name : ''));
                        Arr::set($result[$owner->id], 'Caj Penyelenggaraan (RM)', (!empty($owner->caj_penyelenggaraan) ? $owner->caj_penyelenggaraan : ''));
                        Arr::set($result[$owner->id], 'Sinking Fund (RM)', (!empty($owner->sinking_fund) ? $owner->sinking_fund : ''));
                        Arr::set($result[$owner->id], 'Owner 2 Name', (!empty($owner->nama2) ? $owner->nama2 : ''));
                        Arr::set($result[$owner->id], 'Owner 2 IC No', (!empty($owner->ic_no2) ? $owner->ic_no2 : ''));
                        Arr::set($result[$owner->id], 'Owner 2 Phone No', (!empty($owner->phone_no2) ? $owner->phone_no2 : ''));
                        Arr::set($result[$owner->id], 'Owner 2 E-mail', (!empty($owner->email2) ? $owner->email2 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 Name', (!empty($owner->nama3) ? $owner->nama3 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 IC No', (!empty($owner->ic_no3) ? $owner->ic_no3 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 Phone No', (!empty($owner->phone_no3) ? $owner->phone_no3 : ''));
                        Arr::set($result[$owner->id], 'Owner 3 E-mail', (!empty($owner->email3) ? $owner->email3 : ''));
                        Arr::set($result[$owner->id], 'Lawyer Name', (!empty($owner->lawyer_name) ? $owner->lawyer_name : ''));
                        Arr::set($result[$owner->id], 'Lawyer Address', (!empty($owner->lawyer_address) ? $owner->lawyer_address : ''));
                        Arr::set($result[$owner->id], 'Lawyer Fail Ref No', (!empty($owner->lawyer_fail_ref_no) ? $owner->lawyer_fail_ref_no : ''));
                        Arr::set($result[$owner->id], 'Remarks', (!empty($owner->remarks) ? $owner->remarks : ''));
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Owner_' . strtoupper($cob) . '_Page_' . $page);
    }

    public function activeStrata($cob = null)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->activeFiles) {
                    foreach ($council->activeFiles as $file) {
                        $result[$file->id] = [];

                        Arr::set($result[$file->id], 'Council', ($file->company ? $file->company->short_name : $council->short_name));
                        Arr::set($result[$file->id], 'File No', $file->file_no);
                        Arr::set($result[$file->id], 'Strata Name', ($file->strata ? $file->strata->name : ''));

                        $designations = Designation::where('is_deleted', 0)->orderBy('description')->get();
                        if ($designations) {
                            foreach ($designations as $designation) {
                                $ajk_detail = AJKDetails::where('file_id', $file->id)
                                    ->where('designation', $designation->id)
                                    ->where('is_deleted', 0)
                                    ->orderBy('start_year', 'desc')
                                    ->orderBy('month', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->first();

                                Arr::set($result[$file->id], $designation->description . ' Name', ($ajk_detail ? $ajk_detail->name : ''));
                                Arr::set($result[$file->id], $designation->description . ' E-mail', ($ajk_detail ? $ajk_detail->email : ''));
                                Arr::set($result[$file->id], $designation->description . ' Phone No', ($ajk_detail ? $ajk_detail->phone_no : ''));
                            }
                        }

                        $pic_name = '';
                        $pic_phone_no = '';
                        $pic_email = '';
                        
                        if ($file->personInCharge) {
                            foreach ($file->personInCharge as $pic) {
                                if ($pic->user->full_name) {
                                    $pic_name = $pic->user->full_name;
                                }
                                if ($pic->user->phone_no) {
                                    $pic_phone_no = $pic->user->phone_no;
                                }
                                if ($pic->user->email) {
                                    $pic_email = $pic->user->email;
                                }
                            }
                        }

                        Arr::set($result[$file->id], 'PIC Name', $pic_name);
                        Arr::set($result[$file->id], 'PIC Phone No', $pic_phone_no);
                        Arr::set($result[$file->id], 'PIC E-mail', $pic_email);
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Active_Strata_' . strtoupper($cob));
    }

    public function exportFiles($cob = null, $start = 0, $total = 500)
    {
        ini_set('max_execution_time', -1);

        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                $files = Files::where('company_id', $council->id)
                    ->where('is_deleted', 0)
                    ->offset($start) // Starting position of records
                    ->limit($total) // Number of records to retrieve
                    ->get();

                if ($files) {
                    $count = 1;

                    foreach ($files as $file) {
                        $houseScheme = HouseScheme::where('file_id', $file->id)->first();
                        if ($houseScheme) {
                            $developer = Developer::find($houseScheme->developer);
                            if ($developer) {
                                $developer_city = City::find($developer->city);
                                $developer_state = State::find($developer->state);
                                $developer_country = Country::find($developer->country);
                            }
                        } else {
                            $developer = '';
                        }

                        $strata = Strata::where('file_id', $file->id)->first();
                        if ($strata) {
                            $strata_parliament = Parliment::find($strata->parliament);
                            $strata_dun = Dun::find($strata->dun);
                            $strata_park = Park::find($strata->park);
                            $strata_city = City::find($strata->city);
                            $strata_state = State::find($strata->state);
                            $strata_country = Country::find($strata->country);
                            $strata_town = City::find($strata->town);
                            $strata_area = Area::find($strata->area);
                            $strata_land_area_UOM = UnitMeasure::find($strata->land_area_unit);
                            $strata_land_title = LandTitle::find($strata->land_title);
                            $strata_category = Category::find($strata->category);
                            $strata_perimeter = Perimeter::find($strata->perimeter);

                            $strata_residential = Residential::where('strata_id', $strata->id)->first();
                            if ($strata->is_residential && $strata_residential) {
                                $strata_residential_mf_uom = UnitOption::find($strata_residential->maintenance_fee_option);
                                $strata_residential_sf_uom = UnitOption::find($strata_residential->sinking_fund_option);
                            } else {
                                $strata_residential_mf_uom = '';
                                $strata_residential_sf_uom = '';
                            }

                            $strata_commercial = Commercial::where('strata_id', $strata->id)->first();
                            if ($strata->is_commercial && $strata_commercial) {
                                $strata_commercial_mf_uom = UnitOption::find($strata_commercial->maintenance_fee_option);
                                $strata_commercial_sf_uom = UnitOption::find($strata_commercial->sinking_fund_option);
                            } else {
                                $strata_commercial_mf_uom = '';
                                $strata_commercial_sf_uom = '';
                            }
                        } else {
                            $strata_residential = '';
                            $strata_commercial = '';
                        }

                        $management = Management::where('file_id', $file->id)->first();
                        if ($management) {
                            $management_jmb = ManagementJMB::where('file_id', $file->id)->first();
                            if ($management->is_jmb && $management_jmb) {
                                $management_jmb_city = City::find($management_jmb->city);
                                $management_jmb_state = State::find($management_jmb->state);
                                $management_jmb_country = Country::find($management_jmb->country);
                            } else {
                                $management_jmb_city = '';
                                $management_jmb_state = '';
                                $management_jmb_country = '';
                            }

                            $management_mc = ManagementMC::where('file_id', $file->id)->first();
                            if ($management->is_mc && $management_mc) {
                                $management_mc_city = City::find($management_mc->city);
                                $management_mc_state = State::find($management_mc->state);
                                $management_mc_country = Country::find($management_mc->country);
                            } else {
                                $management_mc_city = '';
                                $management_mc_state = '';
                                $management_mc_country = '';
                            }

                            $management_agent = ManagementAgent::where('file_id', $file->id)->first();
                            if ($management->is_agent && $management_agent) {
                                $management_agent_city = City::find($management_agent->city);
                                $management_agent_state = State::find($management_agent->state);
                                $management_agent_country = Country::find($management_agent->country);
                            } else {
                                $management_agent_city = '';
                                $management_agent_state = '';
                                $management_agent_country = '';
                            }

                            $management_others = ManagementOthers::where('file_id', $file->id)->first();
                            if ($management->is_others && $management_others) {
                                $management_others_city = City::find($management_others->city);
                                $management_others_state = State::find($management_others->state);
                                $management_others_country = Country::find($management_others->country);
                            } else {
                                $management_others_city = '';
                                $management_others_state = '';
                                $management_others_country = '';
                            }
                        }

                        $monitoring = Monitoring::where('file_id', $file->id)->first();
                        $others_details = OtherDetails::where('file_id', $file->id)->first();

                        $result[] = array(
                            'Bil' => $count++,
                            'File No.' => $file->file_no,
                            'Cob File ID' => '',
                            'Year' => (!empty($file->year) ? $file->year : ''),

                            /**
                             * Housing Scheme
                             */
                            'Name' => ($houseScheme ? $houseScheme->name : ''),
                            'Housing Scheme Name' => ($houseScheme ? $houseScheme->name : ''),
                            'Developer' => ($developer ? $developer->name : ''),
                            'Developer Address 1' => ($developer ? $developer->address1 : ''),
                            'Developer Address 2' => ($developer ? $developer->address2 : ''),
                            'Developer Address 3' => ($developer ? $developer->address3 : ''),
                            'Developer Address 4' => ($developer ? $developer->address4 : ''),
                            'Developer Postcode' => ($developer ? $developer->poscode : ''),
                            'Developer City' => ($developer ? ($developer_city ? $developer_city->description : '') : ''),
                            'Developer State' => ($developer ? ($developer_state ? $developer_state->name : '') : ''),
                            'Developer Country' => ($developer ? ($developer_country ? $developer_country->name : '') : ''),
                            'Developer Office No.' => ($developer ? $developer->phone_no : ''),
                            'Developer Fax No.' => ($developer ? $developer->fax_no : ''),
                            'Developer Status' => ($developer ? ($developer->is_active ? 'Active' : '') : ''),

                            /**
                             * Strata
                             */
                            'Strata Title' => ($strata ? ($strata->title ? 'Y' : '') : ''),
                            'Strata' => ($strata ? $strata->name : ''),
                            'Strata Parliament' => ($strata ? ($strata_parliament ? $strata_parliament->description : '') : ''),
                            'Strata DUN' => ($strata ? ($strata_dun ? $strata_dun->description : '') : ''),
                            'Strata Park' => ($strata ? ($strata_park ? $strata_park->description : '') : ''),
                            'Strata Address 1' => ($strata ? $strata->address1 : ''),
                            'Strata Address 2' => ($strata ? $strata->address2 : ''),
                            'Strata Address 3' => ($strata ? $strata->address3 : ''),
                            'Strata Address 4' => ($strata ? $strata->address4 : ''),
                            'Strata Postcode' => ($strata ? $strata->poscode : ''),
                            'Strata City' => ($strata_city ? $strata_city->description : ''),
                            'Strata State' => ($strata_state ? $strata_state->name : ''),
                            'Strata Country' => ($strata_country ? $strata_country->name : ''),
                            'Strata Total Block' => ($strata ? $strata->block_no : ''),
                            'Strata Floor' => ($strata ? $strata->total_floor : ''),
                            'Strata Year' => ($strata ? $strata->year : ''),
                            'Strata Ownership No' => ($strata ? $strata->ownership_no : ''),
                            'Strata District' => ($strata_town ? $strata_town->description : ''),
                            'Strata Area' => ($strata_area ? $strata_area->description : ''),
                            'Strata Total Land Area' => ($strata ? $strata->land_area : ''),
                            'Strata Total Land Area UOM' => ($strata_land_area_UOM ? $strata_land_area_UOM->description : ''),
                            'Strata Lot No.' => ($strata ? $strata->lot_no : ''),
                            'Strata Vacant Possession Date' => ($strata ? ($strata->date > 0 ? $strata->date : '') : ''),
                            'Strata Date CCC' => ($strata ? ($strata->ccc_date > 0 ? $strata->ccc_date : '') : ''),
                            'Strata CCC No.' => ($strata ? $strata->ccc_no : ''),
                            'Strata Land Title' => ($strata_land_title ? $strata_land_title->description : ''),
                            'Strata Category' => ($strata_category ? $strata_category->description : ''),
                            'Strata Perimeter' => ($strata_perimeter ? $strata_perimeter->description_en : ''),
                            'Strata Total Share Unit' => ($strata ? $strata->total_share_unit : ''),
                            'Strata Residential' => ($strata ? ($strata->is_residential ? 'Yes' : '') : ''),
                            'Strata Residential Total Unit' => ($strata_residential ? $strata_residential->unit_no : ''),
                            'Strata Residential Maintenance Fee' => ($strata_residential ? $strata_residential->maintenance_fee : ''),
                            'Strata Residential Maintenance Fee UOM' => ($strata_residential ? ($strata_residential_mf_uom ? $strata_residential_mf_uom->description : '') : ''),
                            'Strata Residential Singking Fund' => ($strata_residential ? $strata_residential->sinking_fund : ''),
                            'Strata Residential Singking Fund UOM' => ($strata_residential ? ($strata_residential_sf_uom ? $strata_residential_sf_uom->description : '') : ''),
                            'Strata Commercial' => ($strata ? ($strata->is_commercial ? 'Yes' : '') : ''),
                            'Strata Commercial Total Unit' => ($strata_commercial ? $strata_commercial->unit_no : ''),
                            'Strata Commercial Maintenance Fee' => ($strata_commercial ? $strata_commercial->maintenance_fee : ''),
                            'Strata Commercial Maintenance Fee UOM' => ($strata_commercial ? ($strata_commercial_mf_uom ? $strata_commercial_mf_uom->description : '') : ''),
                            'Strata Commercial Singking Fund' => ($strata_commercial ? $strata_commercial->sinking_fund : ''),
                            'Strata Commercial Singking Fund UOM' => ($strata_commercial ? ($strata_commercial_sf_uom ? $strata_commercial_sf_uom->description : '') : ''),
                            'Strata Others' => '',

                            /**
                             * Management JMB
                             */
                            'Management JMB' => ($management_jmb ? 'Yes' : ''),
                            'Management JMB Date Formed' => ($management_jmb ? ($management_jmb->date_formed > 0 ? $management_jmb->date_formed : '') : ''),
                            'Management JMB Certificate Series No' => ($management_jmb ? $management_jmb->certificate_no : ''),
                            'Management JMB Name' => ($management_jmb ? $management_jmb->name : ''),
                            'Management JMB Address 1'  => ($management_jmb ? $management_jmb->address1 : ''),
                            'Management JMB Address 2'  => ($management_jmb ? $management_jmb->address2 : ''),
                            'Management JMB Address 3'  => ($management_jmb ? $management_jmb->address3 : ''),
                            'Management JMB Address 4'  => ($management_jmb ? $management_jmb->address4 : ''),
                            'Management JMB Postcode'  => ($management_jmb ? $management_jmb->poscode : ''),
                            'Management JMB City'  => ($management_jmb ? ($management_jmb_city ? $management_jmb_city->description : '') : ''),
                            'Management JMB State'  => ($management_jmb ? ($management_jmb_state ? $management_jmb_state->name : '') : ''),
                            'Management JMB Country'  => ($management_jmb ? ($management_jmb_country ? $management_jmb_country->name : '') : ''),
                            'Management JMB Office No.' => ($management_jmb ? $management_jmb->phone_no : ''),
                            'Management JMB Fax No.' => ($management_jmb ? $management_jmb->fax_no : ''),
                            'Management JMB Email' => ($management_jmb ? $management_jmb->email : ''),

                            /**
                             * Management MC
                             */
                            'Management MC' => ($management_mc ? 'Yes' : ''),
                            'Management MC Date Formed'  => ($management_mc ? ($management_mc->date_formed > 0 ? $management_mc->date_formed : '') : ''),
                            'Management MC First AGM Date'  => ($management_mc ? ($management_mc->date_formed > 0 ? $management_mc->date_formed : '') : ''),
                            'Management MC Name' => ($management_mc ? $management_mc->name : ''),
                            'Management MC Address 1' => ($management_mc ? $management_mc->address1 : ''),
                            'Management MC Address 2' => ($management_mc ? $management_mc->address2 : ''),
                            'Management MC Address 3' => ($management_mc ? $management_mc->address3 : ''),
                            'Management MC Address 4' => ($management_mc ? $management_mc->address4 : ''),
                            'Management MC Postcode' => ($management_mc ? $management_mc->poscode : ''),
                            'Management MC City'  => ($management_mc ? ($management_mc_city ? $management_mc_city->description : '') : ''),
                            'Management MC State'  => ($management_mc ? ($management_mc_state ? $management_mc_state->name : '') : ''),
                            'Management MC Country'  => ($management_mc ? ($management_mc_country ? $management_mc_country->name : '') : ''),
                            'Management MC Office No.' => ($management_mc ? $management_mc->phone_no : ''),
                            'Management MC Fax No.' => ($management_mc ? $management_mc->fax_no : ''),
                            'Management MC Email' => ($management_mc ? $management_mc->email : ''),

                            /**
                             * Management Agent
                             */
                            'Management Agent' => ($management_agent ? 'Yes' : ''),
                            'Management Agent Selected By' => ($management_agent ? $management_agent->selected_by : ''),
                            'Management Agent Name' => ($management_agent ? $management_agent->name : ''),
                            'Management Agent Address 1' => ($management_agent ? $management_agent->address1 : ''),
                            'Management Agent Address 2' => ($management_agent ? $management_agent->address2 : ''),
                            'Management Agent Address 3' => ($management_agent ? $management_agent->address3 : ''),
                            'Management Agent Address 4' => ($management_agent ? $management_agent->address4 : ''),
                            'Management Agent Postcode' => ($management_agent ? $management_agent->poscode : ''),
                            'Management Agent City'  => ($management_agent ? ($management_agent_city ? $management_agent_city->description : '') : ''),
                            'Management Agent State'  => ($management_agent ? ($management_agent_state ? $management_agent_state->name : '') : ''),
                            'Management Agent Country'  => ($management_agent ? ($management_agent_country ? $management_agent_country->name : '') : ''),
                            'Management Agent Office No.' => ($management_agent ? $management_agent->phone_no : ''),
                            'Management Agent Fax No.' => ($management_agent ? $management_agent->fax_no : ''),
                            'Management Agent Email' => ($management_agent ? $management_agent->email : ''),

                            /**
                             * Management Other
                             */
                            'Management Other' => ($management_others ? 'Yes' : ''),
                            'Management Other Name' => ($management_others ? $management_others->name : ''),
                            'Management Other Address 1' => ($management_others ? $management_others->address1 : ''),
                            'Management Other Address 2' => ($management_others ? $management_others->address2 : ''),
                            'Management Other Address 3' => ($management_others ? $management_others->address3 : ''),
                            'Management Other Address 4' => ($management_others ? $management_others->address4 : ''),
                            'Management Other Postcode' => ($management_others ? $management_others->poscode : ''),
                            'Management Other City'  => ($management_others ? ($management_others_city ? $management_others_city->description : '') : ''),
                            'Management Other State'  => ($management_others ? ($management_others_state ? $management_others_state->name : '') : ''),
                            'Management Other Country'  => ($management_others ? ($management_others_country ? $management_others_country->name : '') : ''),
                            'Management Other Office No.' => ($management_others ? $management_others->phone_no : ''),
                            'Management Other Fax No.' => ($management_others ? $management_others->fax_no : ''),
                            'Management Other Email' => ($management_others ? $management_others->email : ''),

                            /**
                             * No Management
                             */
                            'No Management' => ($management ? ($management->no_management ? 'Yes' : '') : ''),
                            'Management Date Start' => ($management ? ($management->start > 0 ? $management->start : '') : ''),
                            'Management Date End' => ($management ? ($management->end > 0 ? $management->end : '') : ''),

                            /**
                             * Monitoring
                             */
                            'Monitoring Precalculate Plan' => ($monitoring ? ($monitoring->pre_calculate ? 'Yes' : '') : ''),
                            'Monitoring Buyer Registration' => ($monitoring ? ($monitoring->buyer_registration ? 'Yes' : '') : ''),
                            'Monitoring Certificate No' => ($monitoring ? $monitoring->certificate_no : ''),
                            'Monitoring Financial Report Start Month' => '',

                            /**
                             * Others
                             */
                            'Others Name' => ($others_details ? $others_details->name : ''),
                            'Others Latitude' => ($others_details ? ($others_details->latitude > 0 ? $others_details->latitude : '') : ''),
                            'Others Longitude' => ($others_details ? ($others_details->longitude > 0 ? $others_details->longitude : '') : ''),

                            'Status' => ($file ? ($file->is_active ? 'Active' : '') : ''),
                            'Certificate No' => '',
                            'New File No.' => '',
                        );
                    }
                }
            }
        }

        return $this->result($result, $filename = 'Export_Files_' . strtoupper($cob));
    }

    public function fileInfo($cob = null)
    {
        ini_set('max_execution_time', -1);
        
        $result = [];

        $councils = $this->council($cob);

        if ($councils) {
            foreach ($councils as $council) {
                if ($council->activeFiles) {
                    foreach ($council->activeFiles as $files) {
                        if ($files->strata) {
                            $total_unit = 0;
                            if ($files->strata->residential) {
                                if ($files->strata->residential->unit_no > 0) {
                                    $total_unit = $total_unit + $files->strata->residential->unit_no;
                                }
                            }
                            if ($files->strata->commercial) {
                                if ($files->strata->commercial->unit_no > 0) {
                                    $total_unit = $total_unit + $files->strata->commercial->unit_no;
                                }
                            }

                            $developer_name = '';
                            $developer_address1 = '';
                            $developer_address2 = '';
                            $developer_address3 = '';
                            $developer_address4 = '';
                            $developer_postcode = '';
                            $developer_city = '';
                            $developer_state = '';
                            $developer_phone_no = '';
                            $developer_email = '';
                            if ($files->managementDeveloper) {
                                if ($files->managementDeveloper->name) {
                                    $developer_name = $files->managementDeveloper->name;
                                }
                                if ($files->managementDeveloper->address_1) {
                                    $developer_address1 = $files->managementDeveloper->address_1;
                                }
                                if ($files->managementDeveloper->address_2) {
                                    $developer_address2 = $files->managementDeveloper->address_2;
                                }
                                if ($files->managementDeveloper->address_3) {
                                    $developer_address3 = $files->managementDeveloper->address_3;
                                }
                                if ($files->managementDeveloper->address_4) {
                                    $developer_address4 = $files->managementDeveloper->address_4;
                                }
                                if ($files->managementDeveloper->poscode) {
                                    $developer_postcode = $files->managementDeveloper->poscode;
                                }
                                if ($files->managementDeveloper->city) {
                                    $developer_city = $files->managementDeveloper->cities->description;
                                }
                                if ($files->managementDeveloper->state) {
                                    $developer_state = $files->managementDeveloper->states->name;
                                }
                                if ($files->managementDeveloper->phone_no) {
                                    $developer_phone_no = $files->managementDeveloper->phone_no;
                                }
                                if ($files->managementDeveloper->email) {
                                    $developer_email = $files->managementDeveloper->email;
                                }
                            }

                            $jmb_name = '';
                            $jmb_address1 = '';
                            $jmb_address2 = '';
                            $jmb_address3 = '';
                            $jmb_address4 = '';
                            $jmb_postcode = '';
                            $jmb_city = '';
                            $jmb_state = '';
                            $jmb_phone_no = '';
                            $jmb_email = '';
                            if ($files->managementJMB) {
                                if ($files->managementJMB->name) {
                                    $jmb_name = $files->managementJMB->name;
                                }
                                if ($files->managementJMB->address1) {
                                    $jmb_address1 = $files->managementJMB->address1;
                                }
                                if ($files->managementJMB->address2) {
                                    $jmb_address2 = $files->managementJMB->address2;
                                }
                                if ($files->managementJMB->address3) {
                                    $jmb_address3 = $files->managementJMB->address3;
                                }
                                if ($files->managementJMB->address4) {
                                    $jmb_address4 = $files->managementJMB->address4;
                                }
                                if ($files->managementJMB->poscode) {
                                    $jmb_postcode = $files->managementJMB->poscode;
                                }
                                if ($files->managementJMB->city) {
                                    $jmb_city = $files->managementJMB->cities->description;
                                }
                                if ($files->managementJMB->state) {
                                    $jmb_state = $files->managementJMB->states->name;
                                }
                                if ($files->managementJMB->phone_no) {
                                    $jmb_phone_no = $files->managementJMB->phone_no;
                                }
                                if ($files->managementJMB->email) {
                                    $jmb_email = $files->managementJMB->email;
                                }
                            }

                            $mc_name = '';
                            $mc_address1 = '';
                            $mc_address2 = '';
                            $mc_address3 = '';
                            $mc_address4 = '';
                            $mc_postcode = '';
                            $mc_city = '';
                            $mc_state = '';
                            $mc_phone_no = '';
                            $mc_email = '';
                            if ($files->managementMC) {
                                if ($files->managementMC->name) {
                                    $mc_name = $files->managementMC->name;
                                }
                                if ($files->managementMC->address1) {
                                    $mc_address1 = $files->managementMC->address1;
                                }
                                if ($files->managementMC->address2) {
                                    $mc_address2 = $files->managementMC->address2;
                                }
                                if ($files->managementMC->address3) {
                                    $mc_address3 = $files->managementMC->address3;
                                }
                                if ($files->managementMC->address4) {
                                    $mc_address4 = $files->managementMC->address4;
                                }
                                if ($files->managementMC->poscode) {
                                    $mc_postcode = $files->managementMC->poscode;
                                }
                                if ($files->managementMC->city) {
                                    $mc_city = $files->managementMC->cities->description;
                                }
                                if ($files->managementMC->state) {
                                    $mc_state = $files->managementMC->states->name;
                                }
                                if ($files->managementMC->phone_no) {
                                    $mc_phone_no = $files->managementMC->phone_no;
                                }
                                if ($files->managementMC->email) {
                                    $mc_email = $files->managementMC->email;
                                }
                            }

                            $agent_name = '';
                            $agent_address1 = '';
                            $agent_address2 = '';
                            $agent_address3 = '';
                            $agent_address4 = '';
                            $agent_postcode = '';
                            $agent_city = '';
                            $agent_state = '';
                            $agent_phone_no = '';
                            $agent_email = '';
                            if ($files->managementAgent) {
                                if ($files->managementAgent->name) {
                                    $agent_name = $files->managementAgent->name;
                                }
                                if ($files->managementAgent->address1) {
                                    $agent_address1 = $files->managementAgent->address1;
                                }
                                if ($files->managementAgent->address2) {
                                    $agent_address2 = $files->managementAgent->address2;
                                }
                                if ($files->managementAgent->address3) {
                                    $agent_address3 = $files->managementAgent->address3;
                                }
                                if ($files->managementAgent->address4) {
                                    $agent_address4 = $files->managementAgent->address4;
                                }
                                if ($files->managementAgent->poscode) {
                                    $agent_postcode = $files->managementAgent->poscode;
                                }
                                if ($files->managementAgent->city) {
                                    $agent_city = $files->managementAgent->cities->description;
                                }
                                if ($files->managementAgent->state) {
                                    $agent_state = $files->managementAgent->states->name;
                                }
                                if ($files->managementAgent->phone_no) {
                                    $agent_phone_no = $files->managementAgent->phone_no;
                                }
                                if ($files->managementAgent->email) {
                                    $agent_email = $files->managementAgent->email;
                                }
                            }

                            $others_name = '';
                            $others_address1 = '';
                            $others_address2 = '';
                            $others_address3 = '';
                            $others_address4 = '';
                            $others_postcode = '';
                            $others_city = '';
                            $others_state = '';
                            $others_phone_no = '';
                            $others_email = '';
                            if ($files->managementOthers) {
                                if ($files->managementOthers->name) {
                                    $others_name = $files->managementOthers->name;
                                }
                                if ($files->managementOthers->address1) {
                                    $others_address1 = $files->managementOthers->address1;
                                }
                                if ($files->managementOthers->address2) {
                                    $others_address2 = $files->managementOthers->address2;
                                }
                                if ($files->managementOthers->address3) {
                                    $others_address3 = $files->managementOthers->address3;
                                }
                                if ($files->managementOthers->address4) {
                                    $others_address4 = $files->managementOthers->address4;
                                }
                                if ($files->managementOthers->poscode) {
                                    $others_postcode = $files->managementOthers->poscode;
                                }
                                if ($files->managementOthers->city) {
                                    $others_city = $files->managementOthers->cities->description;
                                }
                                if ($files->managementOthers->state) {
                                    $others_state = $files->managementOthers->states->name;
                                }
                                if ($files->managementOthers->phone_no) {
                                    $others_phone_no = $files->managementOthers->phone_no;
                                }
                                if ($files->managementOthers->email) {
                                    $others_email = $files->managementOthers->email;
                                }
                            }

                            $pic_name = '';
                            $pic_phone_no = '';
                            $pic_email = '';
                            if ($files->personInCharge) {
                                foreach ($files->personInCharge as $pic) {
                                    if ($pic->user->full_name) {
                                        $pic_name = $pic->user->full_name;
                                    }
                                    if ($pic->user->phone_no) {
                                        $pic_phone_no = $pic->user->phone_no;
                                    }
                                    if ($pic->user->email) {
                                        $pic_email = $pic->user->email;
                                    }
                                }
                            }

                            Arr::set($result[$files->id], trans('Council'), $council->name . ' (' . $council->short_name . ')');
                            Arr::set($result[$files->id], trans('File No'), $files->file_no);
                            Arr::set($result[$files->id], trans('Building Name'), $files->strata->name);
                            Arr::set($result[$files->id], trans('Address 1'), $files->strata->address1);
                            Arr::set($result[$files->id], trans('Address 2'), $files->strata->address2);
                            Arr::set($result[$files->id], trans('Address 3'), $files->strata->address3);
                            Arr::set($result[$files->id], trans('Address 4'), $files->strata->address4);
                            Arr::set($result[$files->id], trans('Postcode'), $files->strata->poscode);
                            Arr::set($result[$files->id], trans('City'), ($files->strata->city ? $files->strata->cities->description : ''));
                            Arr::set($result[$files->id], trans('State'), ($files->strata->state ? $files->strata->states->name : ''));
                            Arr::set($result[$files->id], trans('Land Title'), ($files->strata->landTitle ? $files->strata->landTitle->description : ''));
                            Arr::set($result[$files->id], trans('Category'), ($files->strata->categories ? $files->strata->categories->description : ''));
                            Arr::set($result[$files->id], trans('No of Block'), $files->strata->block_no);
                            Arr::set($result[$files->id], trans('Total Floor'), $files->strata->total_floor);
                            Arr::set($result[$files->id], trans('Total Unit'), $total_unit);

                            Arr::set($result[$files->id], trans('PIC Name'), $pic_name);
                            Arr::set($result[$files->id], trans('PIC Phone No'), $pic_phone_no);
                            Arr::set($result[$files->id], trans('PIC E-mail'), $pic_email);

                            Arr::set($result[$files->id], trans('Developer Name'), $developer_name);
                            Arr::set($result[$files->id], trans('Developer Address 1'), $developer_address1);
                            Arr::set($result[$files->id], trans('Developer Address 2'), $developer_address2);
                            Arr::set($result[$files->id], trans('Developer Address 3'), $developer_address3);
                            Arr::set($result[$files->id], trans('Developer Address 4'), $developer_address4);
                            Arr::set($result[$files->id], trans('Developer Postcode'), $developer_postcode);
                            Arr::set($result[$files->id], trans('Developer City'), $developer_city);
                            Arr::set($result[$files->id], trans('Developer State'), $developer_state);
                            Arr::set($result[$files->id], trans('Developer Phone No'), $developer_phone_no);
                            Arr::set($result[$files->id], trans('Developer E-mail'), $developer_email);

                            Arr::set($result[$files->id], trans('JMB Name'), $jmb_name);
                            Arr::set($result[$files->id], trans('JMB Address 1'), $jmb_address1);
                            Arr::set($result[$files->id], trans('JMB Address 2'), $jmb_address2);
                            Arr::set($result[$files->id], trans('JMB Address 3'), $jmb_address3);
                            Arr::set($result[$files->id], trans('JMB Address 4'), $jmb_address4);
                            Arr::set($result[$files->id], trans('JMB Postcode'), $jmb_postcode);
                            Arr::set($result[$files->id], trans('JMB City'), $jmb_city);
                            Arr::set($result[$files->id], trans('JMB State'), $jmb_state);
                            Arr::set($result[$files->id], trans('JMB Phone No'), $jmb_phone_no);
                            Arr::set($result[$files->id], trans('JMB E-mail'), $jmb_email);

                            Arr::set($result[$files->id], trans('MC Name'), $mc_name);
                            Arr::set($result[$files->id], trans('MC Address 1'), $mc_address1);
                            Arr::set($result[$files->id], trans('MC Address 2'), $mc_address2);
                            Arr::set($result[$files->id], trans('MC Address 3'), $mc_address3);
                            Arr::set($result[$files->id], trans('MC Address 4'), $mc_address4);
                            Arr::set($result[$files->id], trans('MC Postcode'), $mc_postcode);
                            Arr::set($result[$files->id], trans('MC City'), $mc_city);
                            Arr::set($result[$files->id], trans('MC State'), $mc_state);
                            Arr::set($result[$files->id], trans('MC Phone No'), $mc_phone_no);
                            Arr::set($result[$files->id], trans('MC E-mail'), $mc_email);

                            Arr::set($result[$files->id], trans('Agent Name'), $agent_name);
                            Arr::set($result[$files->id], trans('Agent Address 1'), $agent_address1);
                            Arr::set($result[$files->id], trans('Agent Address 2'), $agent_address2);
                            Arr::set($result[$files->id], trans('Agent Address 3'), $agent_address3);
                            Arr::set($result[$files->id], trans('Agent Address 4'), $agent_address4);
                            Arr::set($result[$files->id], trans('Agent Postcode'), $agent_postcode);
                            Arr::set($result[$files->id], trans('Agent City'), $agent_city);
                            Arr::set($result[$files->id], trans('Agent State'), $agent_state);
                            Arr::set($result[$files->id], trans('Agent Phone No'), $agent_phone_no);
                            Arr::set($result[$files->id], trans('Agent E-mail'), $agent_email);

                            Arr::set($result[$files->id], trans('Others Name'), $others_name);
                            Arr::set($result[$files->id], trans('Others Address 1'), $others_address1);
                            Arr::set($result[$files->id], trans('Others Address 2'), $others_address2);
                            Arr::set($result[$files->id], trans('Others Address 3'), $others_address3);
                            Arr::set($result[$files->id], trans('Others Address 4'), $others_address4);
                            Arr::set($result[$files->id], trans('Others Postcode'), $others_postcode);
                            Arr::set($result[$files->id], trans('Others City'), $others_city);
                            Arr::set($result[$files->id], trans('Others State'), $others_state);
                            Arr::set($result[$files->id], trans('Others Phone No'), $others_phone_no);
                            Arr::set($result[$files->id], trans('Others E-mail'), $others_email);

                            $designations = Designation::where('is_deleted', 0)->orderBy('description')->get();
                            if ($designations) {
                                foreach ($designations as $designation) {
                                    $ajk_detail = AJKDetails::where('file_id', $files->id)
                                        ->where('designation', $designation->id)
                                        ->where('is_deleted', 0)
                                        ->orderBy('start_year', 'desc')
                                        ->orderBy('month', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                                    Arr::set($result[$files->id], $designation->description . ' Name', ($ajk_detail ? $ajk_detail->name : ''));
                                    Arr::set($result[$files->id], $designation->description . ' E-mail', ($ajk_detail ? $ajk_detail->email : ''));
                                    Arr::set($result[$files->id], $designation->description . ' Phone No', ($ajk_detail ? $ajk_detail->phone_no : ''));
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->result($result, $filename = strtoupper($cob));
    }
}
