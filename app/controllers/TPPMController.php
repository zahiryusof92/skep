<?php

use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;
use Barryvdh\DomPDF\Facade as PDF;

class TPPMController extends \BaseController
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
            $model = TPPM::self();

            return Datatables::of($model)
                ->editColumn('file_id', function ($model) {
                    return $model->file_id ? $model->file->file_no : "-";
                })
                ->editColumn('strata_id', function ($model) {
                    return $model->strata ? $model->strata->name : '-';
                })
                ->editColumn('status', function ($model) {
                    return method_exists($model, 'getStatusBadge') ? $model->getStatusBadge() : $model->status;
                })
                ->editColumn('created_at', function ($model) {
                    return ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');
                })
                ->addColumn('applicant', function ($model) {
                    return $model->applicant_name ?: '-';
                })
                ->addColumn('action', function ($model) {
                    $btn = '';
                    $btn .= '<a href="' . route('tppm.show', Helper::encode('tppm', $model->id)) . '" class="btn btn-xs btn-warning" title="View"><i class="fa fa-eye"></i></a>&nbsp;';
                    // $btn .= '<a href="' . route('tppm.edit', Helper::encode('tppm', $model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                    // Show delete only if NOT approval page and status is PENDING
                    if (strpos(Request::fullUrl(), 'approval') === false && $model->status == TPPM::PENDING) {
                        $btn .= '<form action="' . route('tppm.destroy', Helper::encode('tppm', $model->id)) . '" method="POST" id="delete_form_' . Helper::encode('tppm', $model->id) . '" style="display:inline-block;">';
                        $btn .= '<input type="hidden" name="_method" value="DELETE">';
                        $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode('tppm', $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                        $btn .= '</form>';
                    }
                    return $btn;
                })
                ->make(true);
        }

        $viewData = array(
            'title' => trans('app.menus.tppm.index'),
            'panel_nav_active' => 'tppm_panel',
            'main_nav_active' => 'tppm_main',
            'sub_nav_active' => 'tppm_index',
            'image' => ''
        );

        return View::make('tppm.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->checkAvailableAccess();
        $viewData = array(
            'title' => trans('app.menus.tppm.create'),
            'panel_nav_active' => 'tppm_panel',
            'main_nav_active' => 'tppm_main',
            'sub_nav_active' => 'tppm_create',
            'image' => ''
        );

        return View::make('tppm.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->checkAvailableAccess();
        $data = Request::all();

        $rules = array(
            'strata_id' => 'required',
            'applicant_name' => 'required|string|max:255',
            'applicant_phone' => 'required|string|max:20',
            'applicant_email' => 'required|email|max:255',
            'organization_name' => 'required|string|max:255',
            'applicant_position' => 'required|string|max:255',
            'organization_address_1' => 'required|string|max:255',
            'parliament_id' => 'required',
            'dun_id' => 'required',
            'district_id' => 'required',
            'first_purchase_price' => 'required|numeric|min:0',
            'year_built' => 'required|integer|min:1900|max:' . date('Y'),
            'year_occupied' => 'required|integer|min:1900|max:' . date('Y'),
            'num_blocks' => 'required|integer|min:1',
            'num_units' => 'required|integer|min:1',
            'num_units_occupied' => 'required|integer|min:0',
            'num_units_owner' => 'required|integer|min:0',
            'num_units_malaysian' => 'required|integer|min:0',
            'num_storeys' => 'required|integer|min:1',
            'num_residents' => 'required|integer|min:0',
            'num_units_vacant' => 'required|integer|min:0',
            'num_units_tenant' => 'required|integer|min:0',
            'num_units_non_malaysian' => 'required|integer|min:0',
            'requested_block_name' => 'required|string|max:255',
            'requested_block_no' => 'required|integer',
            'cost_category' => 'required|in:low_cost,low_medium_cost',
            'scope' => 'required|string',
            'spa_copy' => 'required|string',
            'detail_report' => 'required|string',
            'meeting_minutes' => 'required|string',
            'cost_estimate' => 'required|string',
        );
        $messages = array(
            'strata_id.required' => trans('app.validation.tppm.strata_id_required'),
            'applicant_name.required' => trans('app.validation.tppm.applicant_name_required'),
            'applicant_name.max' => trans('app.validation.tppm.applicant_name_max'),
            'applicant_phone.required' => trans('app.validation.tppm.applicant_phone_required'),
            'applicant_phone.max' => trans('app.validation.tppm.applicant_phone_max'),
            'applicant_email.required' => trans('app.validation.tppm.applicant_email_required'),
            'applicant_email.email' => trans('app.validation.tppm.applicant_email_email'),
            'applicant_email.max' => trans('app.validation.tppm.applicant_email_max'),
            'organization_name.required' => trans('app.validation.tppm.organization_name_required'),
            'organization_name.max' => trans('app.validation.tppm.organization_name_max'),
            'applicant_position.required' => trans('app.validation.tppm.applicant_position_required'),
            'applicant_position.max' => trans('app.validation.tppm.applicant_position_max'),
            'organization_address_1.required' => trans('app.validation.tppm.organization_address_1_required'),
            'organization_address_1.max' => trans('app.validation.tppm.organization_address_1_max'),
            'parliament_id.required' => trans('app.validation.tppm.parliament_id_required'),
            'dun_id.required' => trans('app.validation.tppm.dun_id_required'),
            'district_id.required' => trans('app.validation.tppm.district_id_required'),
            'first_purchase_price.required' => trans('app.validation.tppm.first_purchase_price_required'),
            'first_purchase_price.numeric' => trans('app.validation.tppm.first_purchase_price_numeric'),
            'first_purchase_price.min' => trans('app.validation.tppm.first_purchase_price_min'),
            'year_built.required' => trans('app.validation.tppm.year_built_required'),
            'year_built.integer' => trans('app.validation.tppm.year_built_integer'),
            'year_built.min' => trans('app.validation.tppm.year_built_min'),
            'year_built.max' => trans('app.validation.tppm.year_built_max'),
            'year_occupied.required' => trans('app.validation.tppm.year_occupied_required'),
            'year_occupied.integer' => trans('app.validation.tppm.year_occupied_integer'),
            'year_occupied.min' => trans('app.validation.tppm.year_occupied_min'),
            'year_occupied.max' => trans('app.validation.tppm.year_occupied_max'),
            'num_blocks.required' => trans('app.validation.tppm.num_blocks_required'),
            'num_blocks.integer' => trans('app.validation.tppm.num_blocks_integer'),
            'num_blocks.min' => trans('app.validation.tppm.num_blocks_min'),
            'num_units.required' => trans('app.validation.tppm.num_units_required'),
            'num_units.integer' => trans('app.validation.tppm.num_units_integer'),
            'num_units.min' => trans('app.validation.tppm.num_units_min'),
            'num_units_occupied.required' => trans('app.validation.tppm.num_units_occupied_required'),
            'num_units_occupied.integer' => trans('app.validation.tppm.num_units_occupied_integer'),
            'num_units_occupied.min' => trans('app.validation.tppm.num_units_occupied_min'),
            'num_units_owner.required' => trans('app.validation.tppm.num_units_owner_required'),
            'num_units_owner.integer' => trans('app.validation.tppm.num_units_owner_integer'),
            'num_units_owner.min' => trans('app.validation.tppm.num_units_owner_min'),
            'num_units_malaysian.required' => trans('app.validation.tppm.num_units_malaysian_required'),
            'num_units_malaysian.integer' => trans('app.validation.tppm.num_units_malaysian_integer'),
            'num_units_malaysian.min' => trans('app.validation.tppm.num_units_malaysian_min'),
            'num_storeys.required' => trans('app.validation.tppm.num_storeys_required'),
            'num_storeys.integer' => trans('app.validation.tppm.num_storeys_integer'),
            'num_storeys.min' => trans('app.validation.tppm.num_storeys_min'),
            'num_residents.required' => trans('app.validation.tppm.num_residents_required'),
            'num_residents.integer' => trans('app.validation.tppm.num_residents_integer'),
            'num_residents.min' => trans('app.validation.tppm.num_residents_min'),
            'num_units_vacant.required' => trans('app.validation.tppm.num_units_vacant_required'),
            'num_units_vacant.integer' => trans('app.validation.tppm.num_units_vacant_integer'),
            'num_units_vacant.min' => trans('app.validation.tppm.num_units_vacant_min'),
            'num_units_tenant.required' => trans('app.validation.tppm.num_units_tenant_required'),
            'num_units_tenant.integer' => trans('app.validation.tppm.num_units_tenant_integer'),
            'num_units_tenant.min' => trans('app.validation.tppm.num_units_tenant_min'),
            'num_units_non_malaysian.required' => trans('app.validation.tppm.num_units_non_malaysian_required'),
            'num_units_non_malaysian.integer' => trans('app.validation.tppm.num_units_non_malaysian_integer'),
            'num_units_non_malaysian.min' => trans('app.validation.tppm.num_units_non_malaysian_min'),
            'requested_block_name.required' => trans('app.validation.tppm.requested_block_name_required'),
            'requested_block_name.string' => trans('app.validation.tppm.requested_block_name_string'),
            'requested_block_name.max' => trans('app.validation.tppm.requested_block_name_max'),
            'requested_block_no.required' => trans('app.validation.tppm.requested_block_no_required'),
            'requested_block_no.integer' => trans('app.validation.tppm.requested_block_no_integer'),
            'cost_category.required' => trans('app.validation.tppm.cost_category_required'),
            'cost_category.in' => trans('app.validation.tppm.cost_category_in'),
            'scope.required' => trans('app.validation.tppm.scope_required'),
            'scope.string' => trans('app.validation.tppm.scope_string'),
            'spa_copy.required' => trans('app.validation.tppm.spa_copy_required'),
            'spa_copy.string' => trans('app.validation.tppm.spa_copy_string'),
            'detail_report.required' => trans('app.validation.tppm.detail_report_required'),
            'detail_report.string' => trans('app.validation.tppm.detail_report_string'),
            'meeting_minutes.required' => trans('app.validation.tppm.meeting_minutes_required'),
            'meeting_minutes.string' => trans('app.validation.tppm.meeting_minutes_string'),
            'cost_estimate.required' => trans('app.validation.tppm.cost_estimate_required'),
            'cost_estimate.string' => trans('app.validation.tppm.cost_estimate_string'),
        );

        $validator = Validator::make($data, $rules, $messages);
        $errors = [];

        // Add regular validation errors
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
        }

        // Custom validation for scope
        if (isset($data['scope']) && !empty($data['scope'])) {
            $scope = json_decode($data['scope'], true);
            if (!$scope || !isset($scope['items']) || empty($scope['items'])) {
                $errors['scope'] = [trans('app.validation.tppm.scope_required')];
            } else {
                // Validate additional fields for specific scope items

                // Validate lift_avas fields
                if (in_array('lift_avas', $scope['items'])) {
                    if (!isset($scope['lift_count']) || empty($scope['lift_count'])) {
                        $errors['lift_count'] = [trans('app.validation.tppm.lift_count_required')];
                    }
                    if (!isset($scope['lift_type']) || empty($scope['lift_type'])) {
                        $errors['lift_type'] = [trans('app.validation.tppm.lift_type_required')];
                    }
                }

                // Validate water_tank fields
                if (in_array('water_tank', $scope['items'])) {
                    if (!isset($scope['water_tank_count']) || empty($scope['water_tank_count'])) {
                        $errors['water_tank_count'] = [trans('app.validation.tppm.water_tank_count_required')];
                    }
                    if (!isset($scope['water_tank_type']) || empty($scope['water_tank_type'])) {
                        $errors['water_tank_type'] = [trans('app.validation.tppm.water_tank_type_required')];
                    }
                }

                // Validate sanitary_pipe fields
                if (in_array('sanitary_pipe', $scope['items'])) {
                    if (!isset($scope['sanitary_pipe_type']) || empty($scope['sanitary_pipe_type'])) {
                        $errors['sanitary_pipe_type'] = [trans('app.validation.tppm.sanitary_pipe_type_required')];
                    }
                }

                // Validate roof fields
                if (in_array('roof', $scope['items'])) {
                    if (!isset($scope['roof_type']) || empty($scope['roof_type'])) {
                        $errors['roof_type'] = [trans('app.validation.tppm.roof_type_required')];
                    }
                }

                // Validate stair_handrail fields
                if (in_array('stair_handrail', $scope['items'])) {
                    if (!isset($scope['stair_handrail_type']) || empty($scope['stair_handrail_type'])) {
                        $errors['stair_handrail_type'] = [trans('app.validation.tppm.stair_handrail_type_required')];
                    }
                }

                // Validate painting fields (at least one required)
                if (in_array('painting', $scope['items'])) {
                    $paintingFields = ['i', 'ii', 'iii', 'iv'];
                    $hasPaintingField = false;
                    foreach ($paintingFields as $field) {
                        if (isset($scope['painting'][$field]) && !empty($scope['painting'][$field])) {
                            $hasPaintingField = true;
                            break;
                        }
                    }
                    if (!$hasPaintingField) {
                        $errors['painting_i'] = [trans('app.validation.tppm.painting_at_least_one_required')];
                    }
                }

                // Validate electrical fields
                if (in_array('electrical', $scope['items'])) {
                    if (!isset($scope['electrical_type']) || empty($scope['electrical_type'])) {
                        $errors['electrical_type'] = [trans('app.validation.tppm.electrical_type_required')];
                    }
                }

                // Validate public_infrastructure fields (at least one required)
                if (in_array('public_infrastructure', $scope['items'])) {
                    $publicInfraFields = ['i', 'ii', 'iii', 'iv'];
                    $hasPublicInfraField = false;
                    foreach ($publicInfraFields as $field) {
                        if (isset($scope['public_infrastructure'][$field]) && !empty($scope['public_infrastructure'][$field])) {
                            $hasPublicInfraField = true;
                            break;
                        }
                    }
                    if (!$hasPublicInfraField) {
                        $errors['public_infrastructure_i'] = [trans('app.validation.tppm.public_infrastructure_at_least_one_required')];
                    }
                }

                // Validate fence fields
                if (in_array('fence', $scope['items'])) {
                    if (!isset($scope['fence_type']) || empty($scope['fence_type'])) {
                        $errors['fence_type'] = [trans('app.validation.tppm.fence_type_required')];
                    }
                }

                // Validate slope fields - slope only requires checkbox selection, no additional fields needed
            }
        } else {
            $errors['scope'] = [trans('app.validation.tppm.scope_required')];
        }

        // Return all errors if any
        if (!empty($errors)) {
            return Response::json([
                'error' => true,
                'errors' => $errors,
                'message' => trans('Validation Fail')
            ]);
        }

        $strata = Strata::find(array_get($data, 'strata_id', 0));
        if ($strata) {
            if ($strata->file_id) {
                $file = Files::find($strata->file_id);
                if ($file) {
                    $model = TPPM::create([
                        'company_id' => $file->company_id,
                        'file_id' => $file->id,
                        'strata_id' => $strata->id,
                        'cost_category' => array_get($data, 'cost_category'),
                        'applicant_name' => array_get($data, 'applicant_name'),
                        'applicant_position' => array_get($data, 'applicant_position'),
                        'applicant_phone' => array_get($data, 'applicant_phone'),
                        'applicant_email' => array_get($data, 'applicant_email'),
                        'organization_name' => array_get($data, 'organization_name'),
                        'organization_address_1' => array_get($data, 'organization_address_1'),
                        'organization_address_2' => array_get($data, 'organization_address_2'),
                        'organization_address_3' => array_get($data, 'organization_address_3'),
                        'parliament_id' => array_get($data, 'parliament_id', 0),
                        'dun_id' => array_get($data, 'dun_id', 0),
                        'district_id' => array_get($data, 'district_id', 0),
                        'first_purchase_price' => array_get($data, 'first_purchase_price', 0),
                        'year_built' => array_get($data, 'year_built'),
                        'year_occupied' => array_get($data, 'year_occupied'),
                        'num_blocks' => array_get($data, 'num_blocks', 0),
                        'num_units' => array_get($data, 'num_units', 0),
                        'num_units_occupied' => array_get($data, 'num_units_occupied', 0),
                        'num_units_owner' => array_get($data, 'num_units_owner', 0),
                        'num_units_malaysian' => array_get($data, 'num_units_malaysian', 0),
                        'num_storeys' => array_get($data, 'num_storeys', 0),
                        'num_residents' => array_get($data, 'num_residents', 0),
                        'num_units_vacant' => array_get($data, 'num_units_vacant', 0),
                        'num_units_tenant' => array_get($data, 'num_units_tenant', 0),
                        'num_units_non_malaysian' => array_get($data, 'num_units_non_malaysian', 0),
                        'requested_block_name' => array_get($data, 'requested_block_name'),
                        'requested_block_no' => array_get($data, 'requested_block_no'),
                        'scope' => array_get($data, 'scope'),
                        'spa_copy' => array_get($data, 'spa_copy'),
                        'detail_report' => array_get($data, 'detail_report'),
                        'meeting_minutes' => array_get($data, 'meeting_minutes'),
                        'cost_estimate' => array_get($data, 'cost_estimate'),
                        'status' => TPPM::PENDING,
                        'created_by' => Auth::user()->id,
                    ]);

                    if ($model) {
                        $module = 'TPPM';
                        $remarks = $module . ': ' . $model->id . ' created.';
                        $this->addAudit($model->file_id, $module, $remarks);

                        return Response::json([
                            'success' => true,
                            'id' => Helper::encode('tppm', $model->id),
                            'message' => trans('app.successes.saved_successfully')
                        ]);
                    }
                }
            }
        }

        return Response::json(['error' => true, 'message' => trans('app.errors.occurred')]);
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
        $model = TPPM::findOrFail(Helper::decode($id, 'tppm'));

        $viewData = array(
            'title' => trans('app.menus.tppm.show'),
            'panel_nav_active' => 'tppm_panel',
            'main_nav_active' => 'tppm_main',
            'sub_nav_active' => 'tppm_index',
            'model' => $model,
            'image' => ''
        );

        return View::make('tppm.show', $viewData);
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
        $model = TPPM::findOrFail(Helper::decode($id, 'tppm'));

        $viewData = array(
            'title' => trans('app.menus.tppm.edit'),
            'panel_nav_active' => 'tppm_panel',
            'main_nav_active' => 'tppm_main',
            'sub_nav_active' => 'tppm_list',
            'model' => $model,
            'image' => ''
        );

        return View::make('tppm.edit', $viewData);
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
        $data = Request::all();
        $model = TPPM::findOrFail(Helper::decode($id, 'tppm'));

        // Validation rules for approval section
        $rules = [
            'status' => 'required|in:' . implode(',', [TPPM::PENDING, TPPM::APPROVED, TPPM::REJECTED]),
            'approval_remark' => 'required_if:status,' . TPPM::APPROVED . ',' . TPPM::REJECTED . '|string|max:1000',
        ];

        $messages = [
            'status.required' => trans('app.validation.tppm.status_required'),
            'status.in' => trans('app.validation.tppm.status_in'),
            'approval_remark.required_if' => trans('app.validation.tppm.approval_remark_required_if'),
            'approval_remark.max' => trans('app.validation.tppm.approval_remark_max'),
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'errors' => $validator->errors()->toArray(),
                'message' => trans('Validation Fail'),
            ]);
        }

        // Only allow updating approval-related fields from this endpoint
        $updateData = [
            'status' => array_get($data, 'status', $model->status),
            'approval_remark' => array_get($data, 'approval_remark', $model->approval_remark),
            'updated_by' => Auth::user()->id,
        ];

        if (in_array($updateData['status'], [TPPM::APPROVED, TPPM::REJECTED])) {
            $updateData['approval_by'] = Auth::user()->id;
            $updateData['approval_date'] = date('Y-m-d H:i:s');
        } else {
            $updateData['approval_by'] = null;
            $updateData['approval_date'] = null;
        }

        $success = $model->update($updateData);
        if ($success) {
            $module = 'TPPM';
            $remarks = $module . ': ' . $model->id . ' updated.';
            $this->addAudit($model->file_id, $module, $remarks);

            return Response::json([
                'success' => true,
                'id' => Helper::encode('tppm', $model->id),
                'message' => trans('app.successes.updated_successfully')
            ]);
        }

        return Response::json(['error' => true, 'message' => trans('app.errors.occurred')]);
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
        $model = TPPM::findOrFail(Helper::decode($id, 'tppm'));
        // Allow deletion only when status is PENDING
        if ($model->status != TPPM::PENDING) {
            return Redirect::back()->with('error', trans('app.errors.delete_not_allowed'));
        }
        $success = $model->delete();
        if ($success) {
            $module = 'TPPM';
            $remarks = $module . ': ' . $model->id . ' deleted.';
            $this->addAudit($model->file_id, $module, $remarks);

            return Redirect::to('tppm')->with('success', trans('app.successes.deleted_successfully'));
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function fileUpload()
    {
        if (Request::ajax()) {
            $files = Request::file();
            foreach ($files as $file) {
                $destinationPath = public_path('uploads/tppm');
                if (!file_exists($destinationPath)) {
                    @mkdir($destinationPath, 0755, true);
                }
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);

                if ($upload) {
                    $publicPath = 'uploads/tppm/' . $filename;
                    return Response::json(['success' => true, 'file' => $publicPath, 'filename' => $filename]);
                }
            }
        }
        return Response::json(['error' => true, 'message' => "Fail"]);
    }

    /**
     * Generate PDF for the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getPDF($id)
    {
        $this->checkAvailableAccess();
        $model = TPPM::findOrFail(Helper::decode($id, 'tppm'));

        $viewData = array(
            'model' => $model,
            'title' => trans('app.forms.tppm.borang_permohonan') . ' ' . trans('app.forms.tppm.tabung_penyenggaraan_perumahan_malaysia')
        );

        $filename = 'TPPM_Form_' . $model->id . '_' . date('YmdHis') . '.pdf';
        
        $pdf = PDF::loadView('tppm.pdf', $viewData, [], [
            'isHtml5ParserEnabled' => false,
            'isPhpEnabled' => false,
            'isRemoteEnabled' => false
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }

    public function getPreview($id)
    {
        $this->checkAvailableAccess();
        
        $model = TPPM::findOrFail(Helper::decode($id, 'tppm'));
        if (!$model) {
            App::abort(404);
        }

        $viewData = [
            'model' => $model,
            'title' => 'TPPM Application Preview'
        ];

        return View::make('tppm.pdf', $viewData);
    }

    private function checkAvailableAccess()
    {
        if (!Auth::user()->getAdmin() && (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name != "MPS")) {
            App::abort(404);
        }
    }
}
