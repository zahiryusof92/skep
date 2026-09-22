<?php

use Carbon\Carbon;
use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;

class ComplaintController extends \BaseController
{
	private $module;

	public function __construct()
	{
		Helper::isAllow(0, 0, !AccessGroup::hasAccessModule('Complaint') || !Helper::showMPKLModules());
		$this->module = Config::get('constant.module');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Request::ajax()) {
			if (!Auth::user()->getAdmin()) {
				if (!empty(Auth::user()->file_id)) {
					$complaints = Complaint::with(['category', 'type'])
						->join('files', 'complaints.file_id', '=', 'files.id')
						->join('company', 'files.company_id', '=', 'company.id')
						->join('strata', 'files.id', '=', 'strata.file_id')
						->select(['complaints.*'])
						->where('files.id', Auth::user()->file_id)
						->where('files.company_id', Auth::user()->company_id)
						->where('files.is_deleted', 0);
				} else {
					$complaints = Complaint::with(['category', 'type'])
						->join('files', 'complaints.file_id', '=', 'files.id')
						->join('company', 'files.company_id', '=', 'company.id')
						->join('strata', 'files.id', '=', 'strata.file_id')
						->select(['complaints.*'])
						->where('files.company_id', Auth::user()->company_id)
						->where('files.is_deleted', 0);
				}
			} else {
				if (empty(Session::get('admin_cob'))) {
					$complaints = Complaint::with(['category', 'type'])
						->join('files', 'complaints.file_id', '=', 'files.id')
						->join('company', 'files.company_id', '=', 'company.id')
						->join('strata', 'files.id', '=', 'strata.file_id')
						->select(['complaints.*'])
						->where('files.is_deleted', 0);
				} else {
					$complaints = Complaint::with(['category', 'type'])
						->join('files', 'complaints.file_id', '=', 'files.id')
						->join('company', 'files.company_id', '=', 'company.id')
						->join('strata', 'files.id', '=', 'strata.file_id')
						->select(['complaints.*'])
						->where('files.company_id', Session::get('admin_cob'))
						->where('files.is_deleted', 0);
				}
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = Input::get('start_date') ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 1);
				$end_date = Input::get('end_date') ? Carbon::parse(Input::get('end_date')) : Carbon::now();

				$complaints = $complaints->whereBetween('complaints.date_received', [$start_date, $end_date]);
			}

			if (!empty(Input::get('file_id'))) {
				$complaints = $complaints->where('complaints.file_id', Input::get('file_id'));
			}

			return Datatables::of($complaints)
				->editColumn('date_received', function ($model) {
					return !empty($model->date_received) ? date('d-M-Y', strtotime($model->date_received)) : '-';
				})
				->editColumn('complaint_category_id', function ($model) {
					return $model->category ? $model->category->name : '-';
				})
				->editColumn('complaint_type_id', function ($model) {
					return $model->type ? $model->type->name : '-';
				})
				->editColumn('status', function ($model) {
					$status = trans('app.forms.complaint.under_investigation_1');

					if ($model->status == 1) {
						$status = trans('app.forms.complaint.under_investigation_1');
					} else if ($model->status == 2) {
						$status = trans('app.forms.complaint.under_investigation_2');
					} else if ($model->status == 3) {
						$status = trans('app.forms.complaint.resolved');
					} else if ($model->status == 4) {
						$status = trans('app.forms.complaint.received');
					}

					return $status;
				})
				->addColumn('action', function ($model) {
					$btn = '';
					if (AccessGroup::hasUpdateModule('Complaint')) {
						$btn = '<a href="' . route('complaint.edit', $this->encodeID($model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;'
							. '<form action="' . route('complaint.destroy', $this->encodeID($model->id)) . '" method="POST" id="delete_form_' . $this->encodeID($model->id) . '" style="display:inline-block;">'
							. '<input type="hidden" name="_method" value="DELETE">'
							. '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $this->encodeID($model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>'
							. '</form>';
					}

					return $btn;
				})
				->make(true);
		}

		if (!Auth::user()->getAdmin()) {
			if (!empty(Auth::user()->file_id)) {
				$files = Files::where('id', Auth::user()->file_id)->where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('file_no', 'asc')->get();
			} else {
				$files = Files::where('company_id', Auth::user()->company_id)->where('is_deleted', 0)->orderBy('file_no', 'asc')->get();
			}
		} else {
			if (empty(Session::get('admin_cob'))) {
				$files = Files::where('is_deleted', 0)->orderBy('file_no', 'asc')->get();
			} else {
				$files = Files::where('company_id', Session::get('admin_cob'))->where('is_deleted', 0)->orderBy('file_no', 'asc')->get();
			}
		}

		$viewData = array(
			'title' => trans('app.menus.complaint.name') . ' (MPKL)',
			'panel_nav_active' => '',
			'main_nav_active' => '',
			'sub_nav_active' => 'complaint_list',
			'image' => "",
			'files' => $files
		);

		return View::make('complaint.index', $viewData);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$files = $this->getComplaintFileOptions();

		$complaintCategory = ComplaintCategory::with('types')
			->where('is_active', true)
			->orderBy('sort_no')
			->get();

		$viewData = array(
			'title' => trans('app.menus.complaint.name') . ' (MPKL)',
			'panel_nav_active' => '',
			'main_nav_active' => '',
			'sub_nav_active' => 'complaint_list',
			'image' => '',
			'files' => $files,
			'complaintCategory' => $complaintCategory,
		);

		return View::make('complaint.create', $viewData);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$rules = [
			'file_id' => 'required',
			'complaint_category_id' => 'required',
			'name' => 'required',
			'description' => 'required',
			'attachment' => 'required|mimes:jpg,jpeg,png,pdf|max:10240',
			'letter_ref_no' => 'required',
			'date_received' => 'required|date',
			'scheme_address' => 'required',
			'scheme_zone' => 'required',
			'scheme_receiver' => 'required',
			'complainant_phone_no' => 'required',
			'complainant_email' => 'required|email',
			'complainant_ic_no' => 'required',
			'complainant_type' => 'required',
			'complainant_type_others' => 'required_if:complainant_type,others',
			'complaint_category' => 'required',
			'complaint_complication' => 'required',
			'action_duration' => 'required',
			'status' => 'required',
			'officer_review' => 'required',
		];

		$categoryId = Input::get('complaint_category_id');
		$category = ComplaintCategory::with('types')->find($categoryId);

		if ($category) {
			if (strtolower($category->name) == 'lain-lain') {
				$rules['complaint_type_text'] = 'required';
			} elseif ($category->types->count() > 0) {
				$rules['complaint_type_id'] = 'required';
			} else {
				$rules['complaint_type_text'] = 'required';
			}
		}

		$messages = array(
			'file_id.required' => 'The ' . trans('app.forms.complaint.file_no') . ' field is required.',
			'complaint_category_id.required' => 'The ' . trans('app.forms.complaint.category') . ' field is required.',
			'complaint_type_id.required' => 'The ' . trans('app.forms.complaint.type') . ' field is required.',
			'complaint_type_text.required' => 'The ' . trans('app.forms.complaint.type') . ' field is required.',
			'name.required' => 'The ' . trans('app.forms.complaint.name') . ' field is required.',
			'description.required' => 'The ' . trans('app.forms.complaint.description') . ' field is required.',
			'attachment.required' => 'The ' . trans('app.forms.complaint.attachment') . ' field is required.',
			'letter_ref_no.required' => 'The ' . trans('app.forms.complaint.letter_ref_no') . ' field is required.',
			'date_received.required' => 'The ' . trans('app.forms.complaint.date_received') . ' field is required.',
			'scheme_address.required' => 'The ' . trans('app.forms.complaint.scheme_address') . ' field is required.',
			'scheme_zone.required' => 'The ' . trans('app.forms.complaint.scheme_zone') . ' field is required.',
			'complainant_phone_no.required' => 'The ' . trans('app.forms.complaint.complainant_phone_no') . ' field is required.',
			'complainant_email.required' => 'The ' . trans('app.forms.complaint.complainant_email') . ' field is required.',
			'complainant_ic_no.required' => 'The ' . trans('app.forms.complaint.complainant_ic_no') . ' field is required.',
			'scheme_receiver.required' => 'The ' . trans('app.forms.complaint.scheme_receiver') . ' field is required.',
			'complainant_type.required' => 'The ' . trans('app.forms.complaint.complainant_type') . ' field is required.',
			'complaint_category.required' => 'The ' . trans('app.forms.complaint.category') . ' field is required.',
			'complaint_complication.required' => 'The ' . trans('app.forms.complaint.complication') . ' field is required.',
			'action_duration.required' => 'The ' . trans('app.forms.complaint.action_duration') . ' field is required.',
			'status.required' => 'The ' . trans('app.forms.status') . ' field is required.',
			'officer_review.required' => 'The ' . trans('app.forms.complaint.officer_review') . ' field is required.',
			'complainant_type_others.required_if' => 'Please state your answer.',
		);

		$validator = Validator::make($input, $rules, $messages);
		if (!$validator->fails()) {
			$fileModel = Files::with('company')->find($input['file_id']);
			if (!$fileModel || !Helper::isMPKL($fileModel->company)) {
				return Redirect::back()->withInput()->with('error', 'Complaint module is only available for MPKL files.');
			}

			if (empty($input['complaint_type_id']) && !empty($input['complaint_type_text'])) {
				$input['complaint_type_id'] = $this->resolveComplaintTypeId($categoryId, $input['complaint_type_text']);
			}

			$input['attachment_url'] = null;
			if (Input::hasFile('attachment')) {
				$file = Input::file('attachment');
				$filename = time() . '_' . $file->getClientOriginalName();
				$destinationPath = 'uploads/complaint_attachment';

				// Create directory if not exists
				if (!File::exists($destinationPath)) {
					File::makeDirectory($destinationPath, 0755, true);
				}

				$file->move($destinationPath, $filename);

				$input['attachment_url'] = $destinationPath . "/" . $filename;
			}

			Complaint::create([
				'file_id' => $input['file_id'],
				'complaint_category_id' => $input['complaint_category_id'],
				'complaint_type_id' => $input['complaint_type_id'],
				'name' => $input['name'],
				'description' => $input['description'],
				'attachment_url' => $input['attachment_url'],
				'letter_ref_no' => $input['letter_ref_no'],
				'date_received' => $input['date_received'],
				'scheme_address' => $input['scheme_address'],
				'scheme_zone' => $input['scheme_zone'],
				'scheme_receiver' => (!empty($input['scheme_receiver']) ? implode(',', $input['scheme_receiver']) : null),
				'complainant_phone_no' => $input['complainant_phone_no'],
				'complainant_email' => $input['complainant_email'],
				'complainant_ic_no' => $input['complainant_ic_no'],
				'complainant_type' => $input['complainant_type'],
				'complainant_type_others' => $input['complainant_type_others'],
				'complaint_category' => $input['complaint_category'],
				'complaint_complication' => $input['complaint_complication'],
				'action_duration' => $input['action_duration'],
				'status' => $input['status'],
				'officer_review' => $input['officer_review'],				
			]);

			// Log audit
			$remarks = 'Complaint: ' . $input['name'] . $this->module['audit']['text']['data_inserted'];
			$this->addAudit($input['file_id'], "Complaint", $remarks);

			return Redirect::route('complaint.index')->with('success', trans('app.successes.saved_successfully'));
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
	public function edit($id)
	{
		$files = $this->getComplaintFileOptions();

		$complaintCategory = ComplaintCategory::with('types')
			->where('is_active', true)
			->orderBy('sort_no')
			->get();

		$model = Complaint::with(['category', 'type'])->find($this->decodeID($id));
		if (!$model) {
			App::abort(404);
		}

		$viewData = array(
			'title' => trans('app.menus.complaint.name') . ' (MPKL)',
			'panel_nav_active' => '',
			'main_nav_active' => '',
			'sub_nav_active' => 'complaint_list',
			'image' => '',
			'files' => $files,
			'complaintCategory' => $complaintCategory,
			'model' => $model,
		);

		return View::make('complaint.edit', $viewData);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();

		$rules = [
			'file_id' => 'required',
			'complaint_category_id' => 'required',
			'name' => 'required',
			'description' => 'required',
			'letter_ref_no' => 'required',
			'date_received' => 'required|date',
			'scheme_address' => 'required',
			'scheme_zone' => 'required',
			'scheme_receiver' => 'required',
			'complainant_phone_no' => 'required',
			'complainant_email' => 'required|email',
			'complainant_ic_no' => 'required',
			'complainant_type' => 'required',
			'complainant_type_others' => 'required_if:complainant_type,others',
			'complaint_category' => 'required',
			'complaint_complication' => 'required',
			'action_duration' => 'required',
			'status' => 'required',
			'officer_review' => 'required',
		];

		$categoryId = Input::get('complaint_category_id');
		$category = ComplaintCategory::with('types')->find($categoryId);
		if ($category) {
			if (strtolower($category->name) == 'lain-lain') {
				$rules['complaint_type_text'] = 'required';
			} elseif ($category->types->count() > 0) {
				$rules['complaint_type_id'] = 'required';
			} else {
				$rules['complaint_type_text'] = 'required';
			}
		}

		$messages = array(
			'file_id.required' => 'The ' . trans('app.forms.complaint.file_no') . ' field is required.',
			'complaint_category_id.required' => 'The ' . trans('app.forms.complaint.category') . ' field is required.',
			'complaint_type_id.required' => 'The ' . trans('app.forms.complaint.type') . ' field is required.',
			'complaint_type_text.required' => 'The ' . trans('app.forms.complaint.type') . ' field is required.',
			'name.required' => 'The ' . trans('app.forms.complaint.name') . ' field is required.',
			'description.required' => 'The ' . trans('app.forms.complaint.description') . ' field is required.',
			'attachment.required' => 'The ' . trans('app.forms.complaint.attachment') . ' field is required.',
			'letter_ref_no.required' => 'The ' . trans('app.forms.complaint.letter_ref_no') . ' field is required.',
			'date_received.required' => 'The ' . trans('app.forms.complaint.date_received') . ' field is required.',
			'scheme_address.required' => 'The ' . trans('app.forms.complaint.scheme_address') . ' field is required.',
			'scheme_zone.required' => 'The ' . trans('app.forms.complaint.scheme_zone') . ' field is required.',
			'complainant_phone_no.required' => 'The ' . trans('app.forms.complaint.complainant_phone_no') . ' field is required.',
			'complainant_email.required' => 'The ' . trans('app.forms.complaint.complainant_email') . ' field is required.',
			'complainant_ic_no.required' => 'The ' . trans('app.forms.complaint.complainant_ic_no') . ' field is required.',
			'scheme_receiver.required' => 'The ' . trans('app.forms.complaint.scheme_receiver') . ' field is required.',
			'complainant_type.required' => 'The ' . trans('app.forms.complaint.complainant_type') . ' field is required.',
			'complaint_category.required' => 'The ' . trans('app.forms.complaint.category') . ' field is required.',
			'complaint_complication.required' => 'The ' . trans('app.forms.complaint.complication') . ' field is required.',
			'action_duration.required' => 'The ' . trans('app.forms.complaint.action_duration') . ' field is required.',
			'status.required' => 'The ' . trans('app.forms.status') . ' field is required.',
			'officer_review.required' => 'The ' . trans('app.forms.complaint.officer_review') . ' field is required.',
			'complainant_type_others.required_if' => 'Please state your answer.',
		);

		$validator = Validator::make($input, $rules, $messages);
		if (!$validator->fails()) {
			$model = Complaint::with(['category', 'type'])->find($this->decodeID($id));
			if ($model) {
				$originalModel = clone $model;

				if (empty($input['complaint_type_id']) && !empty($input['complaint_type_text'])) {
					$input['complaint_type_id'] = $this->resolveComplaintTypeId($categoryId, $input['complaint_type_text']);
				}

				if (Input::hasFile('attachment')) {
					$file = Input::file('attachment');
					$filename = time() . '_' . $file->getClientOriginalName();
					$destinationPath = 'uploads/complaint_attachment';

					if (!File::exists($destinationPath)) {
						File::makeDirectory($destinationPath, 0755, true);
					}

					$file->move($destinationPath, $filename);
					$input['attachment_url'] = $destinationPath . "/" . $filename;
				} else {
					$input['attachment_url'] = $model->attachment_url;
				}

				$model->update([
					'file_id' => $input['file_id'],
					'complaint_category_id' => $input['complaint_category_id'],
					'complaint_type_id' => $input['complaint_type_id'],
					'name' => $input['name'],
					'description' => $input['description'],
					'attachment_url' => $input['attachment_url'],
					'letter_ref_no' => $input['letter_ref_no'],
					'date_received' => $input['date_received'],
					'scheme_address' => $input['scheme_address'],
					'scheme_zone' => $input['scheme_zone'],
					'scheme_receiver' => (!empty($input['scheme_receiver']) ? implode(',', $input['scheme_receiver']) : null),
					'complainant_phone_no' => $input['complainant_phone_no'],
					'complainant_email' => $input['complainant_email'],
					'complainant_ic_no' => $input['complainant_ic_no'],
					'complainant_type' => $input['complainant_type'],
					'complainant_type_others' => $input['complainant_type_others'],
					'complaint_category' => $input['complaint_category'],
					'complaint_complication' => $input['complaint_complication'],
					'action_duration' => $input['action_duration'],
					'status' => $input['status'],
					'officer_review' => $input['officer_review'],					
				]);

				// Log audit
				$audit_fields_changed = Helper::getChangedFields($input, $originalModel);
				$remarks = 'Complaint: ' . $model->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
				$this->addAudit($model->file_id, 'Complaint', $remarks);

				return Redirect::route('complaint.index')->with('success', trans('app.successes.updated_successfully'));
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
	public function destroy($id)
	{
		$model = Complaint::find($this->decodeID($id));
		if ($model) {
			$model->delete();

			return Redirect::to(route('complaint.index'))->with('success', trans('app.successes.deleted_successfully'));
		} else {
			return Redirect::back()->with('error', trans('app.errors.occurred'));
		}
	}

	private function resolveComplaintTypeId($categoryId, $typeText)
	{
		$existing = ComplaintType::where('complaint_category_id', $categoryId)
			->whereRaw('LOWER(name) = ?', [strtolower($typeText)])
			->where('is_active', true)
			->first();

		if ($existing) return $existing->id;

		$lastSortNo = ComplaintType::where('complaint_category_id', $categoryId)->max('sort_no');

		$type = new ComplaintType();
		$type->complaint_category_id = $categoryId;
		$type->name = $typeText;
		$type->sort_no = $lastSortNo ? $lastSortNo + 1 : 1;
		$type->is_active = true;
		$type->save();

		return $type->id;
	}

	/**
	 * File dropdown for complaint forms — MPKL files only (All COB still restricted to MPKL).
	 */
	private function getComplaintFileOptions()
	{
		$query = Files::join('company', 'files.company_id', '=', 'company.id')
			->where('files.is_deleted', 0)
			->where('company.short_name', 'MPKL')
			->where('company.is_deleted', 0)
			->select('files.*')
			->orderBy('files.file_no', 'asc');

		if (!Auth::user()->getAdmin()) {
			if (!empty(Auth::user()->file_id)) {
				$query->where('files.id', Auth::user()->file_id)->where('files.company_id', Auth::user()->company_id);
			} else {
				$query->where('files.company_id', Auth::user()->company_id);
			}
		} else if (!empty(Session::get('admin_cob'))) {
			$query->where('files.company_id', Session::get('admin_cob'));
		}

		return $query->get();
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
