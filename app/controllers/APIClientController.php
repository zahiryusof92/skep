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

class APIClientController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccessModule("API Client"));
        if (Request::ajax()) {
            $model = APIClient::with(['buildings']);
            
            return Datatables::of($model)
                            ->editColumn('status', function($model) {
                                // {{ Str::ucfirst(collect(App\Enums\StatusEnum::toArray())[$row->status]) }}

                                return ActiveStatus::toArray()[$model->status];
                            })
                            ->editColumn('expiry', function($model) {
                                $expiry = Carbon::createFromTimestamp(strtotime($model->expiry))->toDateString();
                                return $expiry;
                            })
                            ->addColumn('action', function ($model) {
                                $btn = '';
                                if(AccessGroup::hasUpdateModule("API Client")) {
                                    $id = Helper::encode($this->getModule()['name'], $model->id);
                                    $btn .= '<button class="btn btn-xs btn-success edit-btn" title="Edit" data-id="'. $id .'"><i class="fa fa-pencil"></i></button>&nbsp;';
                                    $btn .= '<form action="' . route('clients.destroy', $id) . '" method="POST" id="delete_form_' . $id . '" style="display:inline-block;">';
                                    $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                    $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . $id . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                    $btn .= '</form>';
                                }

                                return $btn;
                            })
                            ->make(true);
        }
        $viewData = array(
            'title' => trans('app.menus.api_client.name'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'api_client_list',
            'image' => ''
        );

        return View::make('api_client.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsertModule("API Client"));
        $statusOptions = ActiveStatus::toArray();
        return View::make('api_client.create', compact('statusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsertModule("API Client"));
        if(Request::ajax()) {
            $data = Request::all();
            $rules = array(
                'name' => 'required',
                'expiry_date' => 'required',
            );
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return Response::json([
                    'error' => true, 
                    'errors' => $validator->errors(), 
                    'message' => trans('Validation Fail')
                ], 422);
            }
    
            $token = Str::random(35);
            $apiClient = APIClient::create([
                'name' => $data['name'],
                'secret' => $token,
                'expiry' => $data['expiry_date'],
            ]);

            if ($apiClient) {
                /*
                    * add audit trail
                    */
                $audit_name = "$apiClient->name";
                $remarks = $audit_name . $this->module['audit']['text']['data_inserted'];
                $this->addAudit(0, "API Client", $remarks);
                
                return Response::json([
                    'success' => true, 
                    'message' => trans('app.successes.saved_successfully')
                ]);
                
            }
        }

        return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ], 406);
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
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Client"));
        $apiClient = APIClient::findOrFail(Helper::decode($id, $this->getModule()['name']));
        $statusOptions = ActiveStatus::toArray();
        return View::make("api_client.edit", compact('apiClient', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Client"));
        if(Request::ajax()) {
            $data = Request::all();
            $rules = array(
                'name' => 'required',
                'expiry_date' => 'required',
            );
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return Response::json([
                    'error' => true, 
                    'errors' => $validator->errors(), 
                    'message' => trans('Validation Fail')
                ], 422);
            }

            $apiClient = APIClient::findOrFail(Helper::decode($id, $this->getModule()['name']));
    
            $apiClient->update([
                'name' => $data['name'],
                'expiry' => $data['expiry_date'],
                'status' => $data['status'],
            ]);
            
            if ($apiClient) {
                /*
                    * add audit trail
                    */
                $audit_name = "$apiClient->name";
                $remarks = $audit_name . $this->module['audit']['text']['data_updated'];
                $this->addAudit(0, "API Client", $remarks);
                
                return Response::json([
                    'success' => true, 
                    'message' => trans('app.successes.saved_successfully')
                ]);
                
            }
        }

        return Response::json([
            'error' => true, 
            'message' => trans('app.errors.occurred')
        ], 406);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdateModule("API Client"));
        $apiClient = APIClient::findOrFail(Helper::decode($id, $this->getModule()['name']));
        $apiClient->delete();
        if($apiClient) {
            if ($apiClient->buildings->count() > 0) {
                foreach ($apiClient->buildings as $building) {
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

    private function getModule() {
        return $this->module['api_client'];
    }
}