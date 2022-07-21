<?php

use Carbon\Carbon;
use Enums\ActiveStatus;
use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Facades\Datatables;

class APIBuildingController extends \BaseController {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("API Building"));
        if (Request::ajax()) {
            $model = APIBuilding::with(['client', 'file', 'strata'])
                                ->self();
            return Datatables::of($model)
                            ->editColumn('client_id', function ($model) {
                                return $model->client->name;
                            })
                            ->editColumn('status', function($model) {
                                // {{ Str::ucfirst(collect(App\Enums\StatusEnum::toArray())[$row->status]) }}
                                return $model->status;
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                return $created_at;
                            })
                            ->addColumn('file', function ($model) {
                                return $model->file->file_no;
                            })
                            ->addColumn('strata', function ($model) {
                                return $model->strata->name? $model->strata->name : '-';
                            })
                            ->addColumn('action', function ($model) {
                                $btn = '';
                                if(AccessGroup::hasUpdateModule("API Building")) {
                                    $id = Helper::encode($this->getModule()['name'], $model->id);
                                    if($model->status) {
                                        $btn .= '<a href="' . route('clients.building.status.inactive', $id) . '" class="btn btn-xs btn-secondary" title="Edit">'. trans("app.forms.inactive") .'</a>&nbsp;';
                                    } else {
                                        $btn .= '<a href="' . route('clients.building.status.active', $id) . '" class="btn btn-xs btn-warning" title="Edit">'. trans("app.forms.active") .'</a>&nbsp;';
                                    }
                                }

                                return $btn;
                            })
                            ->make(true);
        }
        $viewData = array(
            'title' => trans('app.menus.api_building.name'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'api_building_list',
            'image' => ''
        );

        return View::make('api_building.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsertModule("API Building"));
        $statusOptions = ActiveStatus::toArray();
        return View::make('api_building.create', compact('statusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($data) {
        $data = Request::all();
        $rules = array(
            'cient_id' => 'required',
            'file_id' => 'required',
        );
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => true, 
                'errors' => $validator->errors(), 
                'message' => trans('Validation Fail')
            ], 422);
        }

        $apiBuilding = APIBuilding::create([
            'cient_id' => $data['cient_id'],
            'file_id' => $data['file_id'],
        ]);

        if ($apiBuilding) {
            /*
            * add audit trail
            */
            $audit_name = $apiBuilding->client->name;
            $remarks = $audit_name . $this->module['audit']['text']['building_inserted'];
            $this->addAudit(0, "API Building", $remarks);
            
            return $apiBuilding;
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Building"));
        $apiBuilding = APIBuilding::findOrFail(Helper::decode($id, $this->getModule()['name']));
        $statusOptions = ActiveStatus::toArray();
        return View::make("api_building.edit", compact('apiL$apiBuilding', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Building"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateActive($id) {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Building"));
        $apiBuilding = APIBuilding::findOrFail($id);
        $apiBuilding->update([
            'status' => true
        ]);
        /*
        * add audit trail
        */
        $audit_name = $apiBuilding->file->strata->name .'in '. $apiBuilding->client->name;
        $remarks = $audit_name . $this->module['audit']['text']['status_activate'];
        $this->addAudit(0, "API Client", $remarks);
        
        return Redirect::back()->with('success', trans('app.successes.updated_successfully'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateInactive($id) {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Building"));
        $apiBuilding = APIBuilding::findOrFail($id);
        $apiBuilding->update([
            'status' => false
        ]);
        /*
        * add audit trail
        */
        $audit_name = $apiBuilding->file->strata->name .'in '. $apiBuilding->client->name;
        $remarks = $audit_name . $this->module['audit']['text']['status_deactivate'];
        $this->addAudit(0, "API Client", $remarks);
        
        return Redirect::back()->with('success', trans('app.successes.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Building"));
        $apiBuilding = APIBuilding::findOrFail(Helper::decode($id, $this->getModule()['name']));
        $apiBuilding->delete();
        if($apiBuilding) {
            if ($apiBuilding->buildings->count() > 0) {
                foreach ($apiBuilding->buildings as $building) {
                    if($building->logs->count()) {
                        foreach($building->logs as $log) {
                            $log->delete();
                        }
                    }
                    $building->delete();
                }
            }
            return Redirect::back()->with('success', trans('app.successes.deleted_successfully'));
        }
        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function log() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("API Building"));
        if (Request::ajax()) {
            $model = APIBuildingLog::with(['building.file', 'building.strata'])
                                ->self();
            return Datatables::of($model)
                            ->editColumn('description', function ($model) {
                                return $model->description;
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                return $created_at;
                            })
                            ->addColumn('file', function ($model) {
                                return $model->building->file->file_no;
                            })
                            ->addColumn('strata', function ($model) {
                                return $model->building->strata->name? $model->building->strata->name : '-';
                            })
                            ->addColumn('action', function ($model) {
                                $btn = '';
                                if(AccessGroup::hasUpdateModule("API Building")) {
                                    $id = Helper::encode($this->getModule()['name'], $model->id);
                                    if($model->status) {
                                        $btn .= '<a href="' . route('clients.building.status.inactive', $id) . '" class="btn btn-xs btn-secondary" title="Edit">'. trans("app.forms.inactive") .'</a>&nbsp;';
                                    } else {
                                        $btn .= '<a href="' . route('clients.building.status.active', $id) . '" class="btn btn-xs btn-warning" title="Edit">'. trans("app.forms.active") .'</a>&nbsp;';
                                    }
                                }

                                return $btn;
                            })
                            ->make(true);
        }
        $viewData = array(
            'title' => trans('app.menus.api_building.name'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'api_building_list',
            'image' => ''
        );

        return View::make('api_building.index', $viewData);
    }



    private function getModule() {
        return $this->module['api_building'];
    }
}