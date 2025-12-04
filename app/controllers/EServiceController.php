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
	public function view()
	{
		$this->checkAvailableAccess();

		$cob = Auth::user()->getCOB->short_name;
		if ($cob == 'MBSJ' && Auth::user()->getAdmin() || Auth::user()->isCOB()) {
			if (Request::ajax()) {
				$cob = Company::where('company.short_name', $cob)->first();
				if ($cob) {
					$model = EServiceOrder::notDraft()
						->join('files', 'eservices_orders.file_id', '=', 'files.id')
						->join('strata', 'files.id', '=', 'strata.file_id')
						->select(DB::raw('eservices_orders.file_id, files.file_no, strata.name, COUNT(*) as total_orders, MAX(eservices_orders.created_at) as latest_order'))
						->where('eservices_orders.company_id', $cob->id)
						->whereNull('eservices_orders.deleted_at')
						->groupBy('eservices_orders.file_id', 'files.file_no')
						->orderByRaw('MAX(eservices_orders.created_at) desc');

					return Datatables::of($model)
						->editColumn('latest_order', function ($data) {
							return date('d/m/Y', strtotime($data->latest_order));
						})
						->addColumn('action', function ($model) {
							$btn = '';
							$btn .= '<a href="' . route('eservice.index', 'file_id=' . $this->encodeID($model->file_id)) . '" class="btn btn-xs btn-warning" title="View"><i class="fa fa-eye"></i></a>&nbsp;';
							
							return $btn;
						})
						->make(true);
				}
			}
		} else {
			return Redirect::route('eservice.index');
		}

		$viewData = array(
			'title' => trans('app.menus.eservice.review'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_list',
			'image' => ''
		);

		return View::make('eservice.view', $viewData);

		App::abort(404);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function view()
	{
		$this->checkAvailableAccess();

		$cob = Auth::user()->getCOB->short_name;
		if ($cob == 'MBSJ' && Auth::user()->getAdmin() || Auth::user()->isCOB()) {
			if (Request::ajax()) {
				$cob = Company::where('company.short_name', $cob)->first();
				if ($cob) {
					$model = EServiceOrder::notDraft()
						->join('files', 'eservices_orders.file_id', '=', 'files.id')
						->join('strata', 'files.id', '=', 'strata.file_id')
						->select(DB::raw('eservices_orders.file_id, files.file_no, strata.name, COUNT(*) as total_orders, MAX(eservices_orders.created_at) as latest_order'))
						->where('eservices_orders.company_id', $cob->id)
						->whereNull('eservices_orders.deleted_at')
						->groupBy('eservices_orders.file_id', 'files.file_no')
						->orderByRaw('MAX(eservices_orders.created_at) desc');

					return Datatables::of($model)
						->editColumn('latest_order', function ($data) {
							return date('d/m/Y', strtotime($data->latest_order));
						})
						->addColumn('action', function ($model) {
							$btn = '';
							$btn .= '<a href="' . route('eservice.index', 'file_id=' . $this->encodeID($model->file_id)) . '" class="btn btn-xs btn-warning" title="View"><i class="fa fa-eye"></i></a>&nbsp;';
							
							return $btn;
						})
						->make(true);
				}
			}
		} else {
			return Redirect::route('eservice.index');
		}

		$viewData = array(
			'title' => trans('app.menus.eservice.review'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_list',
			'image' => ''
		);

		return View::make('eservice.view', $viewData);

		App::abort(404);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->checkAvailableAccess();

		$cob = Auth::user()->getCOB->short_name;
		if ($cob == 'MBSJ' && Auth::user()->getAdmin() || Auth::user()->isCOB()) {
			if (empty(Input::get('file_id'))) {
				return Redirect::route('eservice.view');
			}
		}

		if (Request::ajax()) {
			$model = EServiceOrder::self()->notDraft();

			if (!empty(Input::get('file_id'))) {
				$model = $model->where('eservices_orders.file_id', $this->decodeID(Input::get('file_id')));
			}

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

		if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
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

		if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
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
							} else if ($cob->short_name == 'MBSJ') {
								$prefix = 'MBSJ-eCOB-';
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

		if (Request::ajax()) {
			$request = Request::all();
			$data = EServiceOrder::getGraphData($request);

			return Response::json([
				'data' => $data,
				'success' => true,
			]);
		}

		if (empty(Session::get('admin_cob'))) {
			$company = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
		} else {
			$company = Company::where('id', Session::get('admin_cob'))->get();
		}

		$data = EServiceOrder::getGraphData();

		$viewData = array(
			'title' => trans('app.menus.eservice.report'),
			'panel_nav_active' => 'eservice_panel',
			'main_nav_active' => 'eservice_main',
			'sub_nav_active' => 'eservice_report',
			'company' => $company,
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

						if ($order->user->isJMB() || $order->user->isMC() || $order->user->isDeveloper()) {
							if ($order->company && $order->company->short_name == 'MBPJ') {
								if (!empty(Config::get('payment.mbpj.email_cob'))) {
									Mail::send('emails.eservice.new_application_cob', array('model' => $order, 'date' => $order->created_at->toDayDateTimeString(), 'status' => $order->getStatusText()), function ($message) {
										$message->to(Config::get('payment.mbpj.email_cob'), 'COB')->subject('New Application for e-Perkhidmatan');
									});
								}
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
					if ($order->company) {
						if ($order->company->short_name == 'MBPJ') {
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
						} else if ($order->company->short_name == 'MBSJ') {
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

									if ($order->user->isJMB() || $order->user->isMC() || $order->user->isDeveloper()) {
										// send email
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

							if ($order->user->isJMB() || $order->user->isMC() || $order->user->isDeveloper()) {
								if ($order->company && $order->company->short_name == 'MBPJ') {
									if (!empty(Config::get('payment.mbpj.email_cob'))) {
										Mail::send('emails.eservice.new_application_cob', array('model' => $order, 'date' => $order->created_at->toDayDateTimeString(), 'status' => $order->getStatusText()), function ($message) {
											$message->to(Config::get('payment.mbpj.email_cob'), 'COB')->subject('New Application for e-Perkhidmatan');
										});
									}
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
				if ($order->company && $order->company->short_name == 'MBPJ') {
					if (!empty($request)) {
						if (Arr::get($request, 'pg_ref_id')) {
							$order->update([
								'reference_id' => Arr::get($request, 'pg_ref_id'),
							]);
						}
						
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

										if ($order->user->isJMB() || $order->user->isMC() || $order->user->isDeveloper()) {
											if ($order->company && $order->company->short_name == 'MBPJ') {
												if (!empty(Config::get('payment.mbpj.email_cob'))) {
													Mail::send('emails.eservice.new_application_cob', array('model' => $order, 'date' => $order->created_at->toDayDateTimeString(), 'status' => $order->getStatusText()), function ($message) {
														$message->to(Config::get('payment.mbpj.email_cob'), 'COB')->subject('New Application for e-Perkhidmatan');
													});
												}
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

		if (Auth::user()->getCOB && Auth::user()->getCOB->short_name == 'MBSJ') {
			$rules['hijri_date'] = 'required';
		}

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
			$hijri_date = isset($request['hijri_date']) ? $request['hijri_date'] : '';

			foreach ($request['bill_no'] as $id => $bill_no) {
				$this->approvedByID($date, $hijri_date, $bill_no, $this->encodeID($id));
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

		$order = EServiceOrder::find($this->decodeID($id));
		if ($order) {
			$status = $request['status'];

			$rules = array(
				'status' => 'required',
			);

			if ($status == EServiceOrder::REJECTED) {
				$rules['approval_remark'] = 'required';;
			} else if ($status == EServiceOrder::APPROVED) {
				$rules['bill_no'] = 'required';
				$rules['date'] = 'required';

				if ($order->company && $order->company->short_name == 'MBSJ') {
					$rules['hijri_date'] = 'required';
				}
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
					$hijri_date = isset($request['hijri_date']) ? $request['hijri_date'] : '';
					$bill_no = $request['bill_no'];

					$this->approvedByID($date, $hijri_date, $bill_no, $id);
				} else if ($status == EServiceOrder::REJECTED) {
					$approval_remark = (isset($request['approval_remark']) ? $request['approval_remark'] : null);

					$this->rejectedByID($approval_remark, $id);
				}

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

			$pdf = PDF::loadView('eservice.' . Str::lower($order->company->short_name) . '.pdf.' . $type, $viewData, [], [
				'isHtml5ParserEnabled' => true,
				'isPhpEnabled' => true
			])->setPaper('A4', 'portrait');

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
				$filename = date('YmdHis') . "_" . Helper::sanitizeFilename($file->getClientOriginalName());
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

		if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) {
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
							'email' => $file->managementMC->email,
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
							'email' => $file->managementJMB->email,
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
							'email' => '',
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
		$data['fields'] = $this->getFormFields($cob);

		if (!empty($order)) {
			$data['model'] = json_decode($order->value, true);
		}

		if (!empty($management)) {
			$data['management'] = $management;
		}

		return $data;
	}

	public function getFormFields($cob)
	{
		$module_config = $this->getModule();
		$data = $module_config['cob'][Str::lower($cob)]['fields'];

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

	private function approvedByID($date, $hijri_date, $bill_no, $id)
	{
		$order = EServiceOrder::with(['user'])->find($this->decodeID($id));
		if ($order) {
			$old_status = $order->status;

			$success = $order->update([
				'bill_no' => $bill_no,
				'date' => $date,
				'hijri_date' => $hijri_date,
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
				if (empty($order->approval_date)) {
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
		}

		return $response;
	}

	public function callbackReconcile($id, $request)
	{
		if (!empty($id)) {
			$order = EServiceOrder::with(['user'])->find($this->decodeID($id));
			if ($order) {
				if ($order->company && $order->company->short_name == 'MBPJ') {
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
							} else if ($transaction->status == EServiceOrderTransaction::FAILED || $transaction->status == EServiceOrderTransaction::REJECTED) {
								$order->update([
									'status' => EServiceOrder::REJECTED,
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

		if (!Auth::user()->hasAccessEservice()) {
			App::abort(404);
		}

		if (!empty($model) && $model->status != EServiceOrder::DRAFT) {
			App::abort(404);
		}
	}
}
