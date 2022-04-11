<?php

use Carbon\Carbon;
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

class EPKSController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $this->checkAvailableAccess();
        if (Request::ajax()) {
            $model = Epks::self()
                        ->notDraft();
            if(Str::contains(Request::fullUrl(), 'approval')) {
                $model = Epks::self()
                            ->approval();

            } else if(Str::contains(Request::fullUrl(), 'draft')) {
                $model = Epks::self()
                            ->draft();
            }
            return Datatables::of($model)
                            ->editColumn('file_id', function($model) {
                                return $model->file_id? "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file->id)) . "'>" . $model->file->file_no . "</a>" : "-";
                            })
                            ->editColumn('strata_id', function($model) {
                                return $model->strata->name;
                            })
                            ->editColumn('status', function($model) {
                                return $model->status();
                            })
                            ->editColumn('created_at', function($model) {
                                $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                return $created_at;
                            })
                            ->addColumn('address', function($model) {
                                $address = $model->address_1;
                                if(!empty($model->address_2)) {
                                    $address .= ", $model->address_2";
                                }
                                if(!empty($model->address_3)) {
                                    $address .= ", $model->address_3";
                                }

                                return $address;
                            })
                            ->addColumn('action', function ($model) {
                                $btn = '';
                                $btn .= '<a href="' . route('epks.show', Helper::encode($this->module['epks']['name'], $model->id)) . '" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';
                                if(AccessGroup::hasUpdate(63) && !Str::contains(Request::fullUrl(), 'approval') && !in_array($model->status, [Epks::APPROVED, Epks::REJECTED, Epks::PENDING, Epks::INPROGRESS])) {
                                    $btn .= '<a href="' . route('epks.edit', Helper::encode($this->module['epks']['name'], $model->id)) . '" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
                                    $btn .= '<form action="' . route('epks.destroy', Helper::encode($this->module['epks']['name'], $model->id)) . '" method="POST" id="delete_form_' . Helper::encode($this->module['epks']['name'], $model->id) . '" style="display:inline-block;">';
                                    $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                    $btn .= '<button type="submit" class="btn btn-xs btn-danger confirm-delete" data-id="delete_form_' . Helper::encode($this->module['epks']['name'], $model->id) . '" title="Delete"><i class="fa fa-trash"></i></button>';
                                    $btn .= '</form>';
                                }

                                return $btn;
                            })
                            ->make(true);
        }
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasAccess(63));

        if(Str::contains(Request::fullUrl(), 'approval')) {
            $viewData = array(
                'title' => trans('app.menus.epks.approval'),
                'panel_nav_active' => 'epks_panel',
                'main_nav_active' => 'epks_main',
                'sub_nav_active' => 'epks_approval',
                'table_route' => route('epks.index', ['type' => 'approval']),
                'image' => ''
            );
    
            return View::make('epks.index', $viewData);
        } else if(Str::contains(Request::fullUrl(), 'draft')) {
            $viewData = array(
                'title' => trans('app.menus.epks.draft'),
                'panel_nav_active' => 'epks_panel',
                'main_nav_active' => 'epks_main',
                'sub_nav_active' => 'epks_draft',
                'table_route' => route('epks.index', ['type' => 'draft']),
                'image' => ''
            );
    
            return View::make('epks.index', $viewData);
        } else {
            $viewData = array(
                'title' => trans('app.menus.epks.list'),
                'panel_nav_active' => 'epks_panel',
                'main_nav_active' => 'epks_main',
                'sub_nav_active' => 'epks_list',
                'table_route' => route('epks.index'),
                'image' => ''
            );
    
            return View::make('epks.index', $viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $this->checkAvailableAccess();
        $disallow = Helper::isAllow(0, 0, !AccessGroup::hasInsert(63));

        $viewData = array(
            'title' => trans('app.menus.epks.create'),
            'panel_nav_active' => 'epks_panel',
            'main_nav_active' => 'epks_main',
            'sub_nav_active' => 'epks_create',
            'image' => ""
        );

        return View::make('epks.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $this->checkAvailableAccess();
        $data = Request::all();
        $rules = [];
        $messages = [];

        $rules = array(
            'scheme_name' => 'required',
            'email' => 'required',
            'address1' => 'required',
        );
        
        /** filename_url, location, remarks */
        foreach ($data['place_proposal'] as $key => $val) {
            $rules['place_proposal.'. $key .'.filename_url'] = 'required';
            $rules['place_proposal.'. $key .'.location'] = 'required';
            $messages['place_proposal.'. $key .'.filename_url.required'] = "The file field is required.";
            // $messages['place_proposal.'. $key .'.filename_url.image'] = "The file must be an image (jpeg, png, bmp, or gif)";
            $messages['place_proposal.'. $key .'.location.required'] = "The location field is required.";
        }

        /** filename_url, remarks */
        foreach ($data['sketch_proposal'] as $key => $val) {
            $rules['sketch_proposal.'. $key .'.filename_url'] = 'required';
            $messages['sketch_proposal.'. $key .'.filename_url.required'] = "The file field is required.";
            // $messages['sketch_proposal.'. $key .'.filename_url.image'] = "The file must be an image (jpeg, png, bmp, or gif)";
        }

        $validator = Validator::make($data, $rules, $messages);
        
        if ($validator->fails()) {
            return Response::json([
                'error' => true, 
                'errors' => $validator->errors(), 
                'message' => trans('Validation Fail')
            ]);
        } else {
            $model = Epks::create([
                'file_id' => Auth::user()->file_id? Auth::user()->file_id : 0,
                'strata_id' => $data['scheme_name'],
                'email' => $data['email'],
                'address_1' => $data['address1'],
                'address_2' => $data['address2'],
                'address_3' => $data['address3'],
                'place_proposal' => json_encode($data['place_proposal']),
                'sketch_proposal' => json_encode($data['sketch_proposal']),
                'causer_by' => Auth::user()->id
            ]);

            if ($model) {
                /*
                 * add audit trail
                 */
                $module = Str::upper($this->module['epks']['name']);
                $remarks = $module . ': ' . $model->email . ' has draft a new application.';
                $this->addAudit($model->file_id, $module,  $remarks);

                return Response::json([
                    'success' => true, 
                    'id' => Helper::encode($this->module['epks']['name'], $model->id), 
                    'message' => trans('app.successes.saved_successfully')
                ]);
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
    public function show($id) {
        $this->checkAvailableAccess();
        $model = Epks::findOrFail(Helper::decode($id, $this->module['epks']['name']));
        $statusOptions = Epks::getStatusOption();
        $strataOptions = Strata::self()->get();

        if ($model) {
            $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdate(63));

            $viewData = array(
                'title' => trans('app.menus.epks.edit'),
                'panel_nav_active' => 'epks_panel',
                'main_nav_active' => 'epks_main',
                'sub_nav_active' => 'epks_list',
                'model' => $model,
                'strataOptions' => $strataOptions,
                "statusOptions" => $statusOptions,
                'image' => ""
            );

            return View::make('epks.show', $viewData);
        }
        
        return Redirect::to('epks')->with('error', trans('app.errors.occurred'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $model = Epks::findOrFail(Helper::decode($id, $this->module['epks']['name']));
        $this->checkAvailableAccess($model);
        if ($model) {
            $disallow = Helper::isAllow(0, 0, !AccessGroup::hasUpdate(63));
            $strataOptions = Strata::self()->get();

            $viewData = array(
                'title' => trans('app.menus.epks.edit'),
                'panel_nav_active' => 'epks_panel',
                'main_nav_active' => 'epks_main',
                'sub_nav_active' => 'epks_list',
                'model' => $model,
                'strataOptions' => $strataOptions,
                'image' => ""
            );

            return View::make('epks.edit', $viewData);
        }
        
        return Redirect::to('epks')->with('error', trans('app.errors.occurred'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $this->checkAvailableAccess();
        $data = Request::all();
        $messages = [];

        $rules = array(
            'scheme_name' => 'required',
            'email' => 'required',
            'address1' => 'required',
        );
        
        /** filename_url, location, remarks */
        foreach ($data['place_proposal'] as $key => $val) {
            $rules['place_proposal.'. $key .'.filename_url'] = 'required';
            $rules['place_proposal.'. $key .'.location'] = 'required';
            $messages['place_proposal.'. $key .'.filename_url.required'] = "The file field is required.";
            // $messages['place_proposal.'. $key .'.filename_url.image'] = "The file must be an image (jpeg, png, bmp, or gif)";
            $messages['place_proposal.'. $key .'.location.required'] = "The location field is required.";
        }

        /** filename_url, remarks */
        foreach ($data['sketch_proposal'] as $key => $val) {
            $rules['sketch_proposal.'. $key .'.filename_url'] = 'required';
            $messages['sketch_proposal.'. $key .'.filename_url.required'] = "The file field is required.";
            // $messages['sketch_proposal.'. $key .'.filename_url.image'] = "The file must be an image (jpeg, png, bmp, or gif)";
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'error' => true, 
                'errors' => $validator->errors(), 
                'message' => trans('Validation Fail')
            ]);
        } else {
            $model = Epks::findorFail(Helper::decode($id, $this->module['epks']['name']));
            
            if ($model) {
                /** Arrange audit fields changes */
                $scheme_name = $data['scheme_name'] == $model->strata_id? "": "scheme_name";
                $email = $data['email'] == $model->email? "": "email";
                $address1 = $data['address1'] == $model->address_1? "": "address 1";
                $address2 = $data['address2'] == $model->address_2? "": "address 2";
                $address3 = $data['address3'] == $model->address_3? "": "address 3";
                $place_proposal_fields = '';
                $sketch_proposal_fields = '';
                $place_proposal_data = json_decode($model->place_proposal);
                $sketch_proposal_data = json_decode($model->sketch_proposal);
                foreach($data['place_proposal'] as $key => $place_proposal) {
                    if(empty($place_proposal_data[$key]->filename_url)) {
                        $place_proposal_fields .= 'new place proposal '. ($key + 1) . ' : location (' . $place_proposal_data[$key]['location'] . ")";
                    } else {
                        $place_text = '';
                        if($place_proposal['filename_url'] != $place_proposal_data[$key]->filename_url) {
                            $place_text .= !empty($place_text)? ', file' : 'file';
                        }
                        if($place_proposal['location'] != $place_proposal_data[$key]->location) {
                            $place_text .= !empty($place_text)? ', location' : 'location';
                        }
                        // if(!empty($place_proposal['remarks'])) {
                            if($place_proposal['remarks'] != $place_proposal_data[$key]->remarks) {
                                $place_text .= !empty($place_text)? ', remarks' : 'remarks';
                            }
                        // }
                        if(!empty($place_text)) {
                            $place_proposal_fields .= 'place proposal '. ($key + 1) . ' : (' . $place_text . ")";
                        }
                    }
                    if(!empty($place_proposal_fields) && !empty($data['place_proposal'][$key + 1])) {
                        $place_proposal_fields .= ', ';
                    }
                }
                foreach($data['sketch_proposal'] as $key => $sketch_proposal) {
                    if(empty($sketch_proposal_data[$key]->filename_url)) {
                        $sketch_proposal_fields .= 'new sketch proposal '. ($key + 1);
                    } else {
                        $sketch_text = '';
                        if($sketch_proposal['filename_url'] != $sketch_proposal_data[$key]->filename_url) {
                            $sketch_text .= !empty($sketch_text)? ', file' : 'file';
                        }
                        if(!empty($sketch_proposal['remarks'])) {
                            if($sketch_proposal['remarks'] != $sketch_proposal_data[$key]->remarks) {
                                $sketch_text .= !empty($sketch_text)? ', remarks' : 'remarks';
                            }
                        }
                        if(!empty($sketch_text)) {
                            $sketch_proposal_fields .= 'sketch proposal '. ($key + 1) . ' : (' . $sketch_text . ")";
                        }
                    }
                    if(!empty($sketch_proposal_fields) && !empty($data['sketch_proposal'][$key + 1])) {
                        $sketch_proposal_fields .= ', ';
                    }
                }
                $audit_fields_changed = "";
                if(!empty($scheme_name) || !empty($email) || !empty($address1) || !empty($address2) || !empty($address3) || !empty($place_proposal_fields) || !empty($sketch_proposal_fields)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= !empty($scheme_name)? "<li>$scheme_name</li>" : "";
                    $audit_fields_changed .= !empty($email)? "<li>$email</li>" : "";
                    $audit_fields_changed .= !empty($address1)? "<li>$address1</li>" : "";
                    $audit_fields_changed .= !empty($address2)? "<li>$address2</li>" : "";
                    $audit_fields_changed .= !empty($address3)? "<li>$address3</li>" : "";
                    $audit_fields_changed .= !empty($place_proposal_fields)? "<li>$place_proposal_fields</li>" : "";
                    $audit_fields_changed .= !empty($sketch_proposal_fields)? "<li>$sketch_proposal_fields</li>" : "";
                }

                $success = $model->update([
                    'strata_id' => $data['scheme_name'],
                    'email' => $data['email'],
                    'address_1' => $data['address1'],
                    'address_2' => $data['address2'],
                    'address_3' => $data['address3'],
                    'place_proposal' => json_encode($data['place_proposal']),
                    'sketch_proposal' => json_encode($data['sketch_proposal']),
                ]);

                if ($success) {
                    /*
                     * add audit trail
                     */
                    if(!empty($audit_fields_changed)) {
                        $module = Str::upper($this->module['epks']['name']);
                        $remarks = $module . ': ' . $model->id . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit($model->file_id, $module, $remarks);
                    }

                    return Response::json([
                        'success' => true, 
                        'id' => Helper::encode($this->module['epks']['name'], $model->id), 
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $this->checkAvailableAccess();
        $model = Epks::findOrFail(Helper::decode($id, $this->module['epks']['name']));
        if ($model) {
            $success = $model->delete();

            if ($success) {
                /*
                 * add audit trail
                 */
                $module = Str::upper($this->module['epks']['name']);
                $remarks = $module . ': ' . $model->id . $this->module['audit']['text']['data_deleted'];
                $this->addAudit($model->file_id, $module, $remarks);

                return Redirect::to('epks')->with('success', trans('app.successes.deleted_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function imageUpload() {
        $this->checkAvailableAccess();
        if(Request::ajax()) {
            $files = Request::file();
            foreach($files as $file) {
                $destinationPath = Config::get('constant.file_directory.epks');
                if(!in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'bmp', 'gif'])) {
                    return Response::json(['error' => true, 'message' => "Invalid File, ext must be (jpg, jpeg, png, bmp or gif)"]);
                }
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);
    
                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        }
        return Response::json(['error' => true, 'message' => "Fail"]);
    }

    public function fileUpload() {
        $this->checkAvailableAccess();
        if(Request::ajax()) {
            $files = Request::file();
            foreach($files as $file) {
                $destinationPath = Config::get('constant.file_directory.epks');
                $filename = date('YmdHis') . "_" . $file->getClientOriginalName();
                $upload = $file->move($destinationPath, $filename);
    
                if ($upload) {
                    return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
                }
            }
        }
        return Response::json(['error' => true, 'message' => "Fail"]);
    }

    public function submitConfirm($id) {
        $this->checkAvailableAccess();
        $model = Epks::with(['user'])->findOrFail(Helper::decode($id, $this->module['epks']['name']));
        if ($model) {
            $success = $model->update([
                'status' => Epks::PENDING
            ]);

            if ($success) {
                /**
                 * Send an email to JMB / MC and copy to COB
                 */
                if(Config::get('mail.driver') != '') {
                    $delay = 0;
                    $incrementDelay = 2;
                    if(!empty($model->user->email)) {
                        Mail::later(Carbon::now()->addSeconds($delay), 'emails.epks.new_application', array('model' => $model, 'status' => $model->getStatusText()), function($message) use ($model)
                        {
                            $message->to($model->user->email, $model->user->full_name)->subject('New Application for e-Pusat Kitar Strata');
                        });
                    }
                    if($model->user->isJMB() || $model->user->isMC()) {
                        // $role = array_pluck(Role::where('name', 'like', "%". Role::COB ."%")->get(), 'id');
                        // $getCOB = User::active()->where('company_id', $model->user->company_id)->whereIn('role', $role)->->get();
                        // foreach($getCOB as $cob) {
                        //     if(!empty($cob->email)) {
                                Mail::later(Carbon::now()->addSeconds($delay), 'emails.epks.new_application_cob', array('model' => $model, 'date' => $model->created_at->toDayDateTimeString(), 'status' => $model->getStatusText()), function($message)
                                {
                                    $message->to('cob@mps.gov.my', 'COB')->subject('New Application for e-Pusat Kitar Strata');
                                });
                        //     }
                        // }
                    }
                }
                /**
                 * Testing send mail
                 */
                // $role = array_pluck(Role::where('name', 'like', "%". Role::COB ."%")->get(), 'id');
                // $cob = User::active()->where('company_id', $model->user->company_id)->whereIn('role', $role)->get();
                // Mail::later(Carbon::now()->addSeconds($delay), 'emails.epks.new_application', array('model' => $model, 'status' => $model->getStatusText()), function($message) use ($model)
                // {
                //     $message->to("patrick@odesi.tech", "Patrick Wan")->subject('New Application for e-Pusat Kitar Strata');
                // });
                // $delay += $incrementDelay;
                // foreach($cob as $key => $val) {
                //     Mail::later(Carbon::now()->addSeconds($delay), 'emails.epks.new_application_cob', array('model' => $model, 'date' => $model->created_at->toDayDateTimeString(), 'status' => $model->getStatusText(), 'cob' => $val), function($message) use ($cob)
                //     {
                //         $message->to("patrick@odesi.tech", "Patrick Wan")->subject('New Application for e-Pusat Kitar Strata');
                //     });
                //     $delay += $incrementDelay;
                //     if(getenv('email_host') == 'smtp.mailtrap.io'){
                //         sleep(3); //use usleep(500000) for half a second or less
                //     }
                // }

                /*
                * add audit trail
                */
                $module = Str::upper($this->module['epks']['name']);
                $remarks = $module . ': ' . $model->email . " has submitted a new application";
                $this->addAudit($model->file_id, $module, $remarks);

                return Redirect::to('epks')->with('success', trans('app.successes.submit_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function submitByCOB($id) {
        $this->checkAvailableAccess();
        $data = Request::all();
        $rules = [];
        $messages = [
            'filename_url' => 'The file field is required.'
        ];

        $rules = array(
            // 'filename_url' => 'required',
            'status' => 'required',
            // 'remarks' => 'required',
        );

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'error' => true, 
                'errors' => $validator->errors(), 
                'message' => trans('Validation Fail')
            ]);
        } else {
            $model = Epks::with(['user'])->findOrFail(Helper::decode($id, $this->module['epks']['name']));
            $audit_fields_changed = '';
            $filename = $data['filename_url'] == $model->filename? "": " upload file";
            $status = $data['status'] == $model->status? "": "status";
            $remarks = $data['remarks'] == $model->remarks? "": "remarks";
            
            $success = $model->update([
                'filename' => $data['filename_url'],
                'status' => $data['status'],
                'remarks' => $data['remarks'],
            ]);

            if(!empty($filename) || !empty($remarks)) {
                $audit_fields_changed .= "<br><ul>";
                $audit_fields_changed .= !empty($filename)? "<li>$filename</li>" : "";
                $audit_fields_changed .= !empty($status)? "<li>$status</li>" : "";
                $audit_fields_changed .= !empty($remarks)? "<li>$remarks</li>" : "";
            }
            if(!empty($status)) {
                $audit_fields_changed = $model->getStatusText();
            }

            if ($success) {
                /**
                 * If status rejected or success send an email to JMB / MC
                 */
                if(Config::get('mail.driver') != '') {
                    if(in_array($model->status, [Epks::PENDING, Epks::INPROGRESS, Epks::APPROVED, Epks::REJECTED])) {
                        Mail::queueOn('application-update', 'emails.epks.status_update', array('model' => $model, 'status' => $model->getStatusText()), function($message) use ($model)
                        {
                            $message->to($model->user->email, $model->user->full_name)->subject("Your Application e-Pusat Kitar Strata has been ". $model->getStatusText());
                        });
                        /** Testing send mail */
                        // Mail::queueOn('application-update', 'emails.epks.status_update', array('model' => $model, 'status' => $model->getStatusText()), function($message) use ($model)
                        // {
                        //     $message->to("patrick@odesi.tech", "Patrick Wan")->subject("Your Application e-Pusat Kitar Strata has been ". $model->getStatusText());
                        // });
                    }
                }

                /*
                 * add audit trail
                 */
                $module = Str::upper($this->module['epks']['name']);
                $remarks = "";
                if(!empty($filename) || !empty($remarks) || !empty($status)) {
                    if(!empty($filename) || !empty($remarks)) {
                        $remarks = $module . ': ' . $model->id . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                    } else {
                        $remarks = $module . ': ' . $model->id . $this->module['audit']['text']['status_updated'] . $audit_fields_changed;
                    }
                    $this->addAudit($model->file_id, $module, $remarks);
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

    private function checkAvailableAccess($model = '') {
        if(!Auth::user()->getAdmin() && (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name != "MPS")) {
            App::abort(404);
        }
        if(!empty($model) && $model->status != Epks::DRAFT) {
            App::abort(404);
        }
    }
}
