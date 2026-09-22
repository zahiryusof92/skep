<?php

use Helper\Helper;
use Helper\StrataMeetingDocumentHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;
use Services\NotificationService;

class StrataMeetingDocumentController extends BaseController
{
    protected function ensureAccess()
    {
        if (!Helper::showMPKLModules()) {
            App::abort(404);
        }
    }

    protected function moduleName()
    {
        return $this->module['agm']['strata_meeting_document']['name'];
    }

    public function index()
    {
        $this->ensureAccess();

        if (Request::ajax()) {
            return $this->datatableResponse();
        }

        $user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

        return View::make('strata-meeting-document.index', array(
            'title' => trans('app.menus.agm.upload_of_minutes') . ' (MPKL)',
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmminutesub_mpkl_list',
            'user_permission' => $user_permission,
            'image' => '',
        ));
    }

    public function monitoringDatatableResponse($file)
    {
        if (!Request::ajax()) {
            return Response::json(array('error' => true), 400);
        }

        $model = StrataMeetingDocument::join('files', 'meeting_documents.file_id', '=', 'files.id')
            ->where('meeting_documents.file_id', $file->id)
            ->where('meeting_documents.is_deleted', 0)
            ->leftJoin('strata_meeting_document_statuses', function ($join) {
                $join->on('meeting_documents.id', '=', 'strata_meeting_document_statuses.strata_meeting_document_id')
                    ->where('strata_meeting_document_statuses.is_deleted', '=', 0);
            })
            ->selectRaw('meeting_documents.*, files.file_no, strata_meeting_document_statuses.status as endorsement_status')
            ->orderBy('meeting_documents.created_at', 'desc');

        return $this->formatDatatable($model, true);
    }

    protected function datatableResponse()
    {
        $query = StrataMeetingDocument::join('files', 'meeting_documents.file_id', '=', 'files.id')
            ->join('strata', 'strata.file_id', '=', 'files.id')
            ->join('company', 'meeting_documents.company_id', '=', 'company.id')
            ->where('meeting_documents.is_deleted', 0)
            ->where('company.short_name', 'MPKL')
            ->where('company.is_deleted', 0);

        if (!Auth::user()->getAdmin()) {
            if (!empty(Auth::user()->file_id)) {
                $query->where('meeting_documents.file_id', Auth::user()->file_id);
            } else {
                $query->where('meeting_documents.company_id', Auth::user()->company_id);
            }
        } elseif (!empty(Session::get('admin_cob'))) {
            $query->where('meeting_documents.company_id', Session::get('admin_cob'));
        }

        if (Request::has('file_id') && !empty(Request::get('file_id'))) {
            $query->where('files.id', Request::get('file_id'));
        }

        if (!empty(Request::get('search')) && !empty(Request::get('search')['value'])) {
            $term = Request::get('search')['value'];
            $query->where(function ($q) use ($term) {
                $q->where('files.file_no', 'like', '%' . $term . '%')
                    ->orWhere('strata.name', 'like', '%' . $term . '%')
                    ->orWhere('meeting_documents.agm_date', 'like', '%' . $term . '%');
            });
        }

        $model = $query->leftJoin('strata_meeting_document_statuses', function ($join) {
            $join->on('meeting_documents.id', '=', 'strata_meeting_document_statuses.strata_meeting_document_id')
                ->where('strata_meeting_document_statuses.is_deleted', '=', 0);
        })->selectRaw('meeting_documents.*, files.file_no, strata.name as strata_name, strata_meeting_document_statuses.status as endorsement_status');

        return $this->formatDatatable($model, false);
    }

    protected function formatDatatable($model, $monitoringMode)
    {
        return Datatables::of($model)
            ->addColumn('strata', function ($row) {
                return isset($row->strata_name) ? $row->strata_name : '';
            })
            ->editColumn('file_id', function ($row) {
                return $row->file_no;
            })
            ->editColumn('type', function ($row) {
                return StrataMeetingDocumentHelper::timingTypeLabel($row->type);
            })
            ->editColumn('agm_type', function ($row) {
                return StrataMeetingDocumentHelper::agmTypeLabel($row->agm_type);
            })
            ->editColumn('agm_date', function ($row) {
                return $row->agm_date ? date('d-M-Y', strtotime($row->agm_date)) : '';
            })
            ->editColumn('description', function ($row) {
                return $this->buildDocumentListHtml($row, false);
            })
            ->addColumn('check_status', function ($row) {
                return $this->buildDocumentListHtml($row, true);
            })
            ->addColumn('status', function ($row) {
                $status = isset($row->endorsement_status) ? $row->endorsement_status : 'pending';
                if ($status === 'approved') {
                    $status = 'accepted';
                }
                $label = trans('app.forms.' . $status);

                return $label ? $label : strtoupper($status);
            })
            ->addColumn('action', function ($row) use ($monitoringMode) {
                $module = $this->moduleName();
                $btn = '<button type="button" class="btn btn-xs btn-success edit_agm" title="Edit" onclick="window.location=\'' . route('strata-meeting-document.edit', Helper::encode($module, $row->id)) . '\'"><i class="fa fa-pencil"></i></button>';
                $status = isset($row->endorsement_status) ? $row->endorsement_status : null;
                if ($status === 'approved') {
                    $status = 'accepted';
                }
                $allowDelete = !$status || !in_array($status, array('accepted', 'rejected'));
                if (!$monitoringMode && $allowDelete) {
                    $btn .= '&nbsp;&nbsp;<form action="' . route('strata-meeting-document.destroy', Helper::encode($module, $row->id)) . '" method="POST" id="delete_form_' . Helper::encode($module, $row->id) . '" style="display:inline-block;">';
                    $btn .= '<input type="hidden" name="_method" value="DELETE">';
                    $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($module, $row->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                    $btn .= '</form>';
                }

                return $btn;
            })
            ->make(true);
    }

    protected function buildDocumentListHtml($model, $iconsOnly)
    {
        $docs = StrataMeetingDocumentHelper::getVisibleDocuments($model->type, $model->agm_type);
        $html = '';
        foreach ($docs as $doc) {
            $flag = $doc['is_field'];
            $urlField = $doc['url_field'];
            if (empty($model->{$flag})) {
                continue;
            }
            if ($iconsOnly) {
                $hasFile = !empty($model->{$urlField});
                $html .= ($hasFile ? '<i class="icmn-checkmark"></i>' : '<i class="icmn-cross"></i>') . '<br/>';
            } else {
                $html .= $doc['label'] . '<br/>';
            }
        }

        return $html;
    }

    public function create()
    {
        $this->ensureAccess();

        if (!empty(Session::get('admin_cob'))) {
            $cob = Company::where('id', Session::get('admin_cob'))->where('is_active', 1)->where('is_hidden', false)->where('is_deleted', 0)->first();
            if ($cob && !Helper::isMPKL($cob)) {
                return Redirect::route('strata-meeting-document.index');
            }
        }

        $mpklIds = Company::where('short_name', 'MPKL')->where('is_deleted', 0)->lists('id');
        if (empty($mpklIds)) {
            $mpklIds = array(0);
        }

        $fileList = Files::with(array('strata', 'company'))
            ->file()
            ->whereIn('company_id', $mpklIds)
            ->orderBy('file_no')
            ->get();

        return View::make('strata-meeting-document.create', array(
            'title' => trans('app.menus.agm.upload_of_minutes') . ' (MPKL)',
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmminutesub_mpkl_list',
            'fileList' => $fileList,
            'image' => '',
        ));
    }

    public function store()
    {
        $this->ensureAccess();

        if (!Request::ajax()) {
            return Response::json(array('error' => true, 'message' => trans('app.errors.occurred')));
        }

        $data = Input::all();
        $validator = Validator::make($data, array(
            'type' => 'required|in:1,2',
            'agm_type' => 'required|in:1,2',
            'file_no' => 'required|exists:files,id,is_deleted,' . false,
            'agm_date' => 'required',
        ));

        if ($validator->fails()) {
            return Response::json(array(
                'error' => true,
                'errors' => $validator->errors(),
                'message' => trans('Validation Fail'),
            ));
        }

        $file = Files::find($data['file_no']);
        if (!$file || !Helper::isMPKL($file->company_id)) {
            return Response::json(array(
                'error' => true,
                'message' => trans('app.errors.occurred'),
            ));
        }

        if (!$this->hasUploadedDocument($data, (int) $data['type'], (int) $data['agm_type'])) {
            return Response::json(array(
                'error' => true,
                'message' => trans('app.errors.file_required'),
            ));
        }

        $payload = $this->buildDocumentPayload($data, (int) $data['type'], (int) $data['agm_type']);
        $payload['file_id'] = $file->id;
        $payload['company_id'] = $file->company_id;
        $payload['type'] = (int) $data['type'];
        $payload['agm_type'] = (int) $data['agm_type'];
        $payload['agm_date'] = $data['agm_date'];
        $payload['remarks'] = isset($data['remarks']) ? $data['remarks'] : null;
        $payload['is_deleted'] = 0;

        $model = StrataMeetingDocument::create($payload);
        if ($model) {
            $remarks = 'Strata Meeting Document: (' . $file->file_no . ') dated ' . date('d/m/Y', strtotime($model->agm_date)) . $this->module['audit']['text']['data_inserted'];
            $this->addAudit($model->file_id, 'COB File', $remarks);
            $this->notifyJmb($file, $model, 'created');

            return Response::json(array(
                'success' => true,
                'message' => trans('app.successes.saved_successfully'),
            ));
        }

        return Response::json(array('error' => true, 'message' => trans('app.errors.occurred')));
    }

    public function show($id)
    {
        return Redirect::route('strata-meeting-document.edit', $id);
    }

    public function edit($id)
    {
        $this->ensureAccess();

        $model = StrataMeetingDocument::find(Helper::decode($id, $this->moduleName()));
        if (!$model) {
            return Redirect::route('strata-meeting-document.index')->with('error', trans('app.errors.occurred'));
        }

        $mpklIds = Company::where('short_name', 'MPKL')->where('is_deleted', 0)->lists('id');
        if (empty($mpklIds)) {
            $mpklIds = array(0);
        }

        $fileList = Files::with(array('strata', 'company'))
            ->file()
            ->whereIn('company_id', $mpklIds)
            ->orderBy('file_no')
            ->get();
        $grouped = StrataMeetingDocumentHelper::groupedFormDocuments($model->type, $model->agm_type);
        $endorse = (Auth::user()->isSuperadmin() || Auth::user()->isCOB());

        return View::make('strata-meeting-document.edit', array(
            'title' => trans('app.menus.agm.upload_of_minutes') . ' (MPKL)',
            'panel_nav_active' => 'agm_panel',
            'main_nav_active' => 'agm_main',
            'sub_nav_active' => 'agmminutesub_mpkl_list',
            'fileList' => $fileList,
            'grouped' => $grouped,
            'model' => $model,
            'documentStatus' => $model->strataMeetingDocumentStatus,
            'endorse' => $endorse,
            'image' => '',
        ));
    }

    public function update($id)
    {
        $this->ensureAccess();

        if (!Request::ajax()) {
            return Response::json(array('error' => true, 'message' => trans('app.errors.occurred')));
        }

        $data = Input::all();
        $rules = array(
            'type' => 'required|in:1,2',
            'agm_type' => 'required|in:1,2',
            'file_no' => 'required|exists:files,id,is_deleted,' . false,
            'agm_date' => 'required',
        );
        $custom_messages = array();
        if (Auth::user()->isSuperadmin() || Auth::user()->isCOB()) {
            $rules['status'] = 'required|in:pending,accepted,rejected';
            $rules['endorsed_by'] = 'required';
            $rules['endorsed_email'] = 'required|email';
        }

        $validator = Validator::make($data, $rules, $custom_messages);
        if ($validator->fails()) {
            return Response::json(array(
                'error' => true,
                'errors' => $validator->errors(),
                'message' => trans('Validation Fail'),
            ));
        }

        $model = StrataMeetingDocument::find(Helper::decode($id, $this->moduleName()));
        if (!$model) {
            return Response::json(array('error' => true, 'message' => trans('app.errors.occurred')));
        }

        $file = Files::find($data['file_no']);
        if (!$file || !Helper::isMPKL($file->company_id)) {
            return Response::json(array('error' => true, 'message' => trans('app.errors.occurred')));
        }

        $payload = $this->buildDocumentPayload($data, (int) $data['type'], (int) $data['agm_type']);
        $payload['file_id'] = $file->id;
        $payload['type'] = (int) $data['type'];
        $payload['agm_type'] = (int) $data['agm_type'];
        $payload['agm_date'] = $data['agm_date'];
        $payload['remarks'] = isset($data['remarks']) ? $data['remarks'] : null;

        $model->update($payload);

        if (Auth::user()->isSuperadmin() || Auth::user()->isCOB()) {
            $statusData = array(
                'user_id' => Auth::user()->id,
                'status' => isset($data['status']) ? $data['status'] : 'pending',
                'reason' => isset($data['reason']) ? $data['reason'] : null,
                'endorsed_by' => isset($data['endorsed_by']) ? $data['endorsed_by'] : null,
                'endorsed_email' => isset($data['endorsed_email']) ? $data['endorsed_email'] : null,
                'attachment' => isset($data['endorsement_letter_url']) ? $data['endorsement_letter_url'] : null,
                'is_deleted' => 0,
            );
            $existing = StrataMeetingDocumentStatus::where('strata_meeting_document_id', $model->id)->where('is_deleted', 0)->first();
            if ($existing) {
                $existing->update($statusData);
            } else {
                $statusData['strata_meeting_document_id'] = $model->id;
                StrataMeetingDocumentStatus::create($statusData);
            }
        }

        $remarks = 'Strata Meeting Document: (' . $file->file_no . ') dated ' . date('d/m/Y', strtotime($model->agm_date)) . $this->module['audit']['text']['data_updated'];
        $this->addAudit($model->file_id, 'COB File', $remarks);
        $this->notifyJmb($file, $model, 'updated');

        return Response::json(array(
            'success' => true,
            'message' => trans('app.successes.updated_successfully'),
        ));
    }

    public function destroy($id)
    {
        $this->ensureAccess();

        $model = StrataMeetingDocument::find(Helper::decode($id, $this->moduleName()));
        if (!$model) {
            return Redirect::back()->with('error', trans('app.errors.occurred'));
        }

        $status = $model->strataMeetingDocumentStatus ? $model->strataMeetingDocumentStatus->status : null;
        if ($status === 'approved') {
            $status = 'accepted';
        }
        if ($status && in_array($status, array('accepted', 'rejected'))) {
            return Redirect::back()->with('error', trans('app.errors.delete_not_allowed'));
        }

        if ($model->update(array('is_deleted' => true))) {
            $file = Files::find($model->file_id);
            $remarks = 'Strata Meeting Document: (' . $file->file_no . ') dated ' . date('d/m/Y', strtotime($model->agm_date)) . $this->module['audit']['text']['data_deleted'];
            $this->addAudit($model->file_id, 'COB File', $remarks);
            $this->notifyJmb($file, $model, 'deleted');

            return Redirect::back()->with('success', trans('app.successes.deleted_successfully'));
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function getForm()
    {
        $this->ensureAccess();

        if (!Request::ajax()) {
            App::abort(404);
        }

        $request = Request::all();
        $type = isset($request['type']) ? (int) $request['type'] : 0;
        $agmType = isset($request['agm_type']) ? (int) $request['agm_type'] : 0;

        if (!$type || !$agmType) {
            return View::make('strata-meeting-document.form', array('grouped' => array(), 'model' => null));
        }

        $model = null;
        if (!empty($request['id'])) {
            $model = StrataMeetingDocument::find(Helper::decode($request['id'], $this->moduleName()));
            if ($model && ((int) $model->agm_type !== $agmType || (int) $model->type !== $type)) {
                $model = null;
            }
        }

        $grouped = StrataMeetingDocumentHelper::groupedFormDocuments($type, $agmType);

        return View::make('strata-meeting-document.form', array(
            'grouped' => $grouped,
            'model' => $model,
        ));
    }

    public function fileUpload()
    {
        $this->ensureAccess();

        if (!Request::ajax()) {
            return Response::json(array('error' => true, 'message' => 'Fail'));
        }

        $request = Request::all();
        $type = isset($request['type']) ? $request['type'] : '1';
        $agmType = isset($request['agm_type']) ? $request['agm_type'] : '1';
        $folderKey = 'strata_meeting_' . $agmType . '_' . $type;
        $destinationPath = Config::get('constant.file_directory.' . $folderKey);
        if (empty($destinationPath)) {
            $destinationPath = 'uploads/agm/strata_meeting/' . $agmType . '_' . $type;
        }

        foreach ($request as $key => $val) {
            if (!is_object($val) || !method_exists($val, 'getClientOriginalName')) {
                continue;
            }
            $filename = date('YmdHis') . '_' . Helper::sanitizeFilename($val->getClientOriginalName());
            $upload = $val->move($destinationPath, $filename);
            if ($upload) {
                return Response::json(array(
                    'success' => true,
                    'file' => $destinationPath . '/' . $filename,
                    'filename' => $filename,
                ));
            }
        }

        return Response::json(array('error' => true, 'message' => 'Fail'));
    }

    protected function buildDocumentPayload($data, $type, $agmType)
    {
        $payload = array();
        $visible = StrataMeetingDocumentHelper::getVisibleDocuments($type, $agmType);
        $visibleIds = array();
        foreach ($visible as $doc) {
            $visibleIds[$doc['id']] = $doc;
        }

        foreach (StrataMeetingDocument::documentSlugs() as $slug) {
            $payload['is_' . $slug] = 0;
            $payload[$slug . '_url'] = null;
        }

        foreach ($visibleIds as $slug => $doc) {
            $flag = $doc['is_field'];
            $urlField = $doc['url_field'];
            $payload[$flag] = !empty($data[$flag]) ? (int) $data[$flag] : 0;
            $payload[$urlField] = isset($data[$urlField]) ? $data[$urlField] : null;
        }

        return $payload;
    }

    protected function hasUploadedDocument($data, $type, $agmType)
    {
        foreach (StrataMeetingDocumentHelper::getVisibleDocuments($type, $agmType) as $doc) {
            if (!empty($data[$doc['is_field']]) && !empty($data[$doc['url_field']])) {
                return true;
            }
        }

        return false;
    }

    protected function notifyJmb($file, $model, $action)
    {
        if (!Auth::user()->isJMB()) {
            return;
        }
        $not_draft_strata = $file->strata;
        $notify_data = array(
            'file_id' => $file->id,
            'route' => route('strata-meeting-document.edit', Helper::encode($this->moduleName(), $model->id)),
            'cob_route' => route('strata-meeting-document.edit', Helper::encode($this->moduleName(), $model->id)),
            'strata' => $action === 'updated' ? 'your' : 'You',
            'strata_name' => $not_draft_strata->name != '' ? $not_draft_strata->name : $file->file_no,
            'title' => 'COB File AGM Minutes',
            'module' => 'AGM Minutes',
        );
        (new NotificationService())->store($notify_data, $action === 'created' ? null : $action);
    }
}
