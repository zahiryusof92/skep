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
		Helper::isAllow(0, 0, !AccessGroup::hasAccessModule('Complaint') || (!Helper::showMPKLModules() && !Helper::showMBSJModules()));
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
			$complaints = Complaint::with(['category', 'type'])
				->join('files', 'complaints.file_id', '=', 'files.id')
				->join('company', 'files.company_id', '=', 'company.id')
				->join('strata', 'files.id', '=', 'strata.file_id')
				->select(['complaints.*', 'company.short_name as cob'])
				->where('files.is_deleted', 0)
				->where('company.is_deleted', 0)
				->where(function ($q) {
					if (Helper::isMPKLContext()) {
						$q->where('company.short_name', 'MPKL');
					} elseif (Helper::isMBSJContext()) {
						$q->where('company.short_name', 'MBSJ');
					} else {
						$q->whereIn('company.short_name', ['MPKL', 'MBSJ']);
					}
				});

			if (!Auth::user()->getAdmin()) {
				if (!empty(Auth::user()->file_id)) {
					$complaints->where('files.id', Auth::user()->file_id)
						->where('files.company_id', Auth::user()->company_id);
				} else {
					$complaints->where('files.company_id', Auth::user()->company_id);
				}
			} else if (!empty(Session::get('admin_cob'))) {
				$complaints->where('files.company_id', Session::get('admin_cob'));
			}

			if (!empty(Input::get('start_date')) || !empty(Input::get('end_date'))) {
				$start_date = Input::get('start_date') ? Carbon::parse(Input::get('start_date')) : Carbon::create(1984, 1, 1);
				$end_date = Input::get('end_date') ? Carbon::parse(Input::get('end_date')) : Carbon::now();

				$complaints = $complaints->whereBetween('complaints.date_received', [$start_date, $end_date]);
			}

			if (!empty(Input::get('file_id'))) {
				$complaints = $complaints->where('complaints.file_id', Input::get('file_id'));
			}

			if (!empty(Input::get('planning_block'))) {
				$complaints = $complaints->where('complaints.planning_block', Input::get('planning_block'));
			}

			if (!empty(Input::get('cob'))) {
				$complaints = $complaints->where('company.short_name', strtoupper(Input::get('cob')));
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
				->editColumn('planning_block', function ($model) {
					return !empty($model->planning_block) ? $model->planning_block : '-';
				})
				->editColumn('mpp_zone', function ($model) {
					return !empty($model->mpp_zone) ? $model->mpp_zone : '-';
				})
				->editColumn('cob', function ($model) {
					return !empty($model->cob) ? strtoupper($model->cob) : '-';
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

		$viewData = array(
			'title' => trans('app.menus.complaint.name') . ' (' . $this->getActiveCompanyLabel() . ')',
			'panel_nav_active' => '',
			'main_nav_active' => '',
			'sub_nav_active' => 'complaint_list',
			'image' => "",
			'files' => $this->getComplaintFileOptions()
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
		$complaintCategory = $this->getComplaintCategories();

		$viewData = array(
			'title' => trans('app.menus.complaint.name') . ' (' . $this->getActiveCompanyLabel() . ')',
			'panel_nav_active' => '',
			'main_nav_active' => '',
			'sub_nav_active' => 'complaint_list',
			'image' => '',
			'files' => $files,
			'complaintCategory' => $complaintCategory,
			'planningBlocks' => Helper::mbsjPlanningBlocks(),
			'planningBlockZones' => Helper::mbsjPlanningBlockZones(),
			'resolutionMethods' => Helper::mbsjResolutionMethods(),
			'mbsjComplainantTypes' => Helper::mbsjComplainantTypes(),
			'isAllCob' => Helper::isAllCobContext(),
			'cobOptions' => array('MPKL', 'MBSJ'),
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

		$fileModel = !empty($input['file_id']) ? Files::with('company')->find($input['file_id']) : null;
		$isMbsj = $fileModel && Helper::isMBSJ($fileModel->company);

		$rules = $this->buildValidationRules($isMbsj, false, isset($input['complaint_category_id']) ? $input['complaint_category_id'] : null);
		$messages = $this->buildValidationMessages();

		$validator = Validator::make($input, $rules, $messages);
		if (!$validator->fails()) {
			if (!$fileModel || (!Helper::isMPKL($fileModel->company) && !Helper::isMBSJ($fileModel->company))) {
				return Redirect::back()->withInput()->with('error', 'Complaint module is only available for MPKL or MBSJ files.');
			}

			if (!$this->cobMatchesFile($input, $fileModel)) {
				return Redirect::back()->withInput()->with('error', 'The selected COB does not match the selected file.');
			}

			if (!$this->categoryBelongsToCob($input['complaint_category_id'], $isMbsj ? 'MBSJ' : 'MPKL')) {
				return Redirect::back()->withInput()->with('error', 'The selected complaint category is not available for this COB.');
			}

			$categoryId = $input['complaint_category_id'];

			if (empty($input['complaint_type_id']) && !empty($input['complaint_type_text'])) {
				$input['complaint_type_id'] = $this->resolveComplaintTypeId($categoryId, $input['complaint_type_text'], $isMbsj ? 'MBSJ' : 'MPKL');
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

			$data = $this->buildComplaintData($input, $isMbsj, null);
			Complaint::create($data);

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
		$complaintCategory = $this->getComplaintCategories();

		$model = Complaint::with(['category', 'type', 'file.company'])->find($this->decodeID($id));
		if (!$model) {
			App::abort(404);
		}

		$modelCob = '';
		if ($model->file && $model->file->company) {
			$modelCob = strtoupper($model->file->company->short_name);
		}

		$viewData = array(
			'title' => trans('app.menus.complaint.name') . ' (' . $this->getActiveCompanyLabel() . ')',
			'panel_nav_active' => '',
			'main_nav_active' => '',
			'sub_nav_active' => 'complaint_list',
			'image' => '',
			'files' => $files,
			'complaintCategory' => $complaintCategory,
			'planningBlocks' => Helper::mbsjPlanningBlocks(),
			'planningBlockZones' => Helper::mbsjPlanningBlockZones(),
			'resolutionMethods' => Helper::mbsjResolutionMethods(),
			'mbsjComplainantTypes' => Helper::mbsjComplainantTypes(),
			'model' => $model,
			'isAllCob' => Helper::isAllCobContext(),
			'cobOptions' => array('MPKL', 'MBSJ'),
			'modelCob' => $modelCob,
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

		$model = Complaint::with(['category', 'type'])->find($this->decodeID($id));
		if (!$model) {
			return Redirect::back()->with('error', trans('app.errors.occurred'));
		}

		$fileModel = !empty($input['file_id']) ? Files::with('company')->find($input['file_id']) : null;
		$isMbsj = $fileModel && Helper::isMBSJ($fileModel->company);

		$rules = $this->buildValidationRules($isMbsj, true, isset($input['complaint_category_id']) ? $input['complaint_category_id'] : null);
		$messages = $this->buildValidationMessages();

		$validator = Validator::make($input, $rules, $messages);
		if (!$validator->fails()) {
			if (!$fileModel || (!Helper::isMPKL($fileModel->company) && !Helper::isMBSJ($fileModel->company))) {
				return Redirect::back()->withInput()->with('error', 'Complaint module is only available for MPKL or MBSJ files.');
			}

			if (!$this->cobMatchesFile($input, $fileModel)) {
				return Redirect::back()->withInput()->with('error', 'The selected COB does not match the selected file.');
			}

			if (!$this->categoryBelongsToCob($input['complaint_category_id'], $isMbsj ? 'MBSJ' : 'MPKL')) {
				return Redirect::back()->withInput()->with('error', 'The selected complaint category is not available for this COB.');
			}

			$originalModel = clone $model;
			$categoryId = $input['complaint_category_id'];

			if (empty($input['complaint_type_id']) && !empty($input['complaint_type_text'])) {
				$input['complaint_type_id'] = $this->resolveComplaintTypeId($categoryId, $input['complaint_type_text'], $isMbsj ? 'MBSJ' : 'MPKL');
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

			$data = $this->buildComplaintData($input, $isMbsj, $model);
			$model->update($data);

			// Log audit
			$audit_fields_changed = Helper::getChangedFields($input, $originalModel);
			$remarks = 'Complaint: ' . $model->name . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
			$this->addAudit($model->file_id, 'Complaint', $remarks);

			return Redirect::route('complaint.index')->with('success', trans('app.successes.updated_successfully'));
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

	/**
	 * Build the validation rules for MPKL and MBSJ complaints.
	 *
	 * @param bool $isMbsj
	 * @param bool $isUpdate
	 * @param int|null $categoryId
	 * @return array
	 */
	private function buildValidationRules($isMbsj, $isUpdate, $categoryId)
	{
		if ($isMbsj) {
			$rules = [
				'file_id' => 'required',
				'complaint_category_id' => 'required',
				'date_received' => 'required|date',
				'planning_block' => 'required|in:BP1,BP2,BP3,BP4,BP5,BP6,BP7',
				'mpp_zone' => 'required',
				'date_minuted' => 'required|date',
				'complainant_name' => 'required',
				'name' => 'required',
				'description' => 'required',
				'scheme_receiver' => 'required',
				'complainant_type' => 'required',
				'complainant_type_others' => 'required_if:complainant_type,others',
				'action_date' => 'required|date',
				'resolution_method' => 'required',
				'complainant_email' => 'email',
				'attachment' => 'mimes:jpg,jpeg,png,pdf|max:10240',
			];
		} else {
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

			if (!$isUpdate) {
				$rules['attachment'] = 'required|mimes:jpg,jpeg,png,pdf|max:10240';
			}
		}

		if (Helper::isAllCobContext()) {
			$rules['cob'] = 'required|in:MPKL,MBSJ';
		}

		$category = ComplaintCategory::with('types')->find($categoryId);
		if ($category) {
			if ($category->types->count() > 0) {
				$rules['complaint_type_id'] = 'required';
			} else {
				$rules['complaint_type_text'] = 'required';
			}
		}

		return $rules;
	}

	/**
	 * Build the validation messages shared by store and update.
	 *
	 * @return array
	 */
	private function buildValidationMessages()
	{
		return array(
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
			'planning_block.required' => 'The ' . trans('app.forms.complaint.planning_block') . ' field is required.',
			'mpp_zone.required' => 'The ' . trans('app.forms.complaint.mpp_zone') . ' field is required.',
			'date_minuted.required' => 'The ' . trans('app.forms.complaint.date_minuted') . ' field is required.',
			'complainant_name.required' => 'The ' . trans('app.forms.complaint.complainant_name') . ' field is required.',
			'action_date.required' => 'The ' . trans('app.forms.complaint.action_date') . ' field is required.',
			'resolution_method.required' => 'The ' . trans('app.forms.complaint.resolution_method') . ' field is required.',
			'cob.required' => 'The COB field is required.',
			'cob.in' => 'The selected COB is invalid.',
		);
	}

	/**
	 * Build the array persisted to the complaints table.
	 *
	 * @param array $input
	 * @param bool $isMbsj
	 * @param Complaint|null $model
	 * @return array
	 */
	private function buildComplaintData($input, $isMbsj, $model)
	{
		$data = [
			'file_id' => $input['file_id'],
			'complaint_category_id' => $input['complaint_category_id'],
			'complaint_type_id' => !empty($input['complaint_type_id']) ? $input['complaint_type_id'] : null,
			'name' => $input['name'],
			'description' => $input['description'],
			'attachment_url' => $input['attachment_url'],
			'letter_ref_no' => !empty($input['letter_ref_no']) ? $input['letter_ref_no'] : null,
			'date_received' => $input['date_received'],
			'scheme_address' => !empty($input['scheme_address']) ? $input['scheme_address'] : null,
			'scheme_zone' => !empty($input['scheme_zone']) ? $input['scheme_zone'] : null,
			'scheme_receiver' => !empty($input['scheme_receiver']) ? implode(',', $input['scheme_receiver']) : null,
			'complainant_phone_no' => !empty($input['complainant_phone_no']) ? $input['complainant_phone_no'] : null,
			'complainant_email' => !empty($input['complainant_email']) ? $input['complainant_email'] : null,
			'complainant_ic_no' => !empty($input['complainant_ic_no']) ? $input['complainant_ic_no'] : null,
			'complainant_type' => $input['complainant_type'],
			'complainant_type_others' => !empty($input['complainant_type_others']) ? $input['complainant_type_others'] : null,
			'complaint_category' => !empty($input['complaint_category']) ? $input['complaint_category'] : null,
			'officer_review' => !empty($input['officer_review']) ? $input['officer_review'] : null,
		];

		if ($isMbsj) {
			// Fields removed from the MBSJ form (Kategori, Komplikasi, Tempoh, Status).
			$data['complaint_category'] = null;
			$data['complaint_complication'] = null;
			$data['action_duration'] = null;
			$data['status'] = ($model && !empty($model->status)) ? $model->status : 4;

			$data['planning_block'] = $input['planning_block'];
			$data['mpp_zone'] = !empty($input['mpp_zone']) ? $input['mpp_zone'] : Helper::mbsjZoneForBlock($input['planning_block']);
			$data['date_minuted'] = $input['date_minuted'];
			$data['complainant_name'] = $input['complainant_name'];
			$data['action_date'] = $input['action_date'];
			$data['resolution_method'] = $input['resolution_method'];
		} else {
			$data['complaint_complication'] = $input['complaint_complication'];
			$data['action_duration'] = $input['action_duration'];
			$data['status'] = $input['status'];

			$data['planning_block'] = null;
			$data['mpp_zone'] = null;
			$data['date_minuted'] = null;
			$data['complainant_name'] = null;
			$data['action_date'] = null;
			$data['resolution_method'] = null;
		}

		return $data;
	}

	/**
	 * Verify the submitted COB matches the selected file's company.
	 *
	 * @param array $input
	 * @param \Files|null $fileModel
	 * @return bool
	 */
	private function cobMatchesFile($input, $fileModel)
	{
		if (empty($input['cob']) || !$fileModel || !$fileModel->company) {
			return true;
		}

		return strtoupper((string) $input['cob']) === strtoupper((string) $fileModel->company->short_name);
	}

	/**
	 * Ensure the chosen category belongs to the file's COB (or is shared).
	 *
	 * @param int $categoryId
	 * @param string $cob
	 * @return bool
	 */
	private function categoryBelongsToCob($categoryId, $cob)
	{
		$category = ComplaintCategory::find($categoryId);
		if (!$category) {
			return false;
		}

		if (empty($category->cob)) {
			return true;
		}

		return strtoupper($category->cob) === strtoupper($cob);
	}

	private function resolveComplaintTypeId($categoryId, $typeText, $cob = null)
	{
		$query = ComplaintType::where('complaint_category_id', $categoryId)
			->whereRaw('LOWER(name) = ?', [strtolower($typeText)])
			->where('is_active', true);

		if ($cob) {
			$query->forCob($cob);
		}

		$existing = $query->first();

		if ($existing) return $existing->id;

		$lastSortNo = ComplaintType::where('complaint_category_id', $categoryId)->max('sort_no');

		$type = new ComplaintType();
		$type->complaint_category_id = $categoryId;
		$type->name = $typeText;
		$type->sort_no = $lastSortNo ? $lastSortNo + 1 : 1;
		$type->is_active = true;
		$type->cob = $cob;
		$type->save();

		return $type->id;
	}

	/**
	 * Categories for the active COB. In All-COB context all categories are
	 * returned and the form filters them by the selected file's COB.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	private function getComplaintCategories()
	{
		$query = ComplaintCategory::with('types')
			->where('is_active', true)
			->orderBy('sort_no');

		if (Helper::isMBSJContext()) {
			$query->forCob('MBSJ');
		} elseif (Helper::isMPKLContext()) {
			$query->forCob('MPKL');
		}

		return $query->get();
	}

	/**
	 * Label for the active COB shown in the page title.
	 */
	private function getActiveCompanyLabel()
	{
		if (Helper::isMPKLContext()) return 'MPKL';
		if (Helper::isMBSJContext()) return 'MBSJ';
		return 'MPKL / MBSJ';
	}

	/**
	 * File dropdown for complaint forms — MPKL / MBSJ files only.
	 */
	private function getComplaintFileOptions()
	{
		$query = Files::with(['strata', 'company'])
			->join('company', 'files.company_id', '=', 'company.id')
			->where('files.is_deleted', 0)
			->where('company.is_deleted', 0)
			->where(function ($q) {
				if (Helper::isMPKLContext()) {
					$q->where('company.short_name', 'MPKL');
				} elseif (Helper::isMBSJContext()) {
					$q->where('company.short_name', 'MBSJ');
				} else {
					$q->whereIn('company.short_name', ['MPKL', 'MBSJ']);
				}
			})
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
