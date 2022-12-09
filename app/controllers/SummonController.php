<?php

use Helper\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class SummonController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $this->checkAvailableAccess();
        if (Auth::user()->isJMB() || Auth::user()->isDeveloper()) {
            if (Request::ajax()) {
                $model = Summon::where('user_id', Auth::user()->id)->where('is_deleted', 0)->orderBy('created_at','desc');

                return Datatables::of($model)
                                ->editColumn('status', function($model) {
                                    return $model->status();
                                })
                                ->editColumn('created_at', function($model) {
                                    $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                    return $created_at;
                                })
                                ->editColumn('type', function ($model) {
                                    if ($model->type == Summon::LETTER_OF_REMINDER) {
                                        $title = trans('app.summon.letter_of_reminder');
                                    } else if ($model->type == Summon::LETTER_OF_DEMAND) {
                                        $title = trans('app.summon.letter_of_demand');
                                    }

                                    return $title;
                                })
                                ->addColumn('action', function ($model) {
                                    $btn = '';
                                    if ($model->status == Summon::DRAFT) {
                                        $btn .= '<a href="' . route('summon.show', Helper::encode($model->id)) . '" class="btn btn-xs btn-success-outline margin-right-5">' . trans('app.summon.pay_now') . '</a>';
                                    }
                                    $btn .= '<a href="' . route('summon.show', Helper::encode($model->id)) . '" class="btn btn-xs btn-primary margin-right-5" title="View"><i class="fa fa-eye"></i></a>';
                                    if ($model->status == Summon::DRAFT || $model->status == Summon::PENDING) {
                                        $btn .= '<form action="' . route('summon.destroy', Helper::encode($model->id)) . '" method="POST" id="delete_form_' . Helper::encode($model->id) . '" style="display:inline-block;">';
                                        $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                        $btn .= '<button type="submit" class="btn btn-xs btn-danger margin-right-5 confirm-delete" data-id="delete_form_' . Helper::encode($model->id) . '" title="Cancel">Cancel</button>';
                                        $btn .= '</form>';
                                    }

                                    return $btn;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.summon.title'),
                'panel_nav_active' => 'summon_panel',
                'main_nav_active' => 'summon_main',
                'sub_nav_active' => 'summon_list',
                'visible' => (Auth::user()->isHR())? 'false' : 'true',
                'image' => ''
            );

            return View::make('summon.index', $viewData);
        } else if (Auth::user()->isLawyer()) {
            if (Request::ajax()) {
                $model = Summon::where('lawyer_id', Auth::user()->id)
                        ->where(function($query) {
                            $query->where('status', Summon::PENDING)
                            ->orWhere('status', Summon::APPROVED)
                            ->orWhere('status', Summon::REJECTED);
                        })
                        ->where('type', Summon::LETTER_OF_DEMAND)
                        ->where('is_deleted', 0);

                return Datatables::of($model)
                                ->editColumn('status', function($model) {
                                    return $model->status();
                                })
                                ->editColumn('created_at', function($model) {
                                    $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                    return $created_at;
                                })
                                ->editColumn('type', function ($model) {
                                    if ($model->type == Summon::LETTER_OF_REMINDER) {
                                        $title = trans('app.summon.letter_of_reminder');
                                    } else if ($model->type == Summon::LETTER_OF_DEMAND) {
                                        $title = trans('app.summon.letter_of_demand');
                                    }

                                    return $title;
                                })
                                ->addColumn('action', function ($model) {
                                    $btn = '';
                                    $btn .= '<a href="' . route('summon.show', Helper::encode($model->id)) . '" class="btn btn-xs btn-primary margin-inline" title="View"><i class="fa fa-eye"></i></a>';

                                    return $btn;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.summon.title'),
                'panel_nav_active' => 'summon_panel',
                'main_nav_active' => 'summon_main',
                'sub_nav_active' => 'summon_list',
                'visible' => (Auth::user()->isHR()),
                'image' => ''
            );

            return View::make('summon.index', $viewData);
        } else if (Auth::user()->isCOBManager()) {
            if (Request::ajax()) {
                $model = Summon::where('company_id', Auth::user()->company_id)
                        ->where(function($query) {
                            $query->where('status', Summon::PENDING)
                            ->orWhere('status', Summon::APPROVED)
                            ->orWhere('status', Summon::INPROGRESS)
                            ->orWhere('status', Summon::REJECTED);
                        })
                        ->where('type', Summon::LETTER_OF_REMINDER)
                        ->where('is_deleted', 0);

                return Datatables::of($model)
                                ->editColumn('status', function($model) {
                                    return $model->status();
                                })
                                ->editColumn('created_at', function($model) {
                                    $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                    return $created_at;
                                })
                                ->editColumn('type', function ($model) {
                                    if ($model->type == Summon::LETTER_OF_REMINDER) {
                                        $title = trans('app.summon.letter_of_reminder');
                                    } else if ($model->type == Summon::LETTER_OF_DEMAND) {
                                        $title = trans('app.summon.letter_of_demand');
                                    }

                                    return $title;
                                })
                                ->addColumn('action', function ($model) {
                                    $btn = '';
                                    $btn .= '<a href="' . route('summon.show', Helper::encode($model->id)) . '" class="btn btn-xs btn-primary margin-inline" title="View"><i class="fa fa-eye"></i></a>';

                                    return $btn;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.summon.title'),
                'panel_nav_active' => 'summon_panel',
                'main_nav_active' => 'summon_main',
                'sub_nav_active' => 'summon_list',
                'visible' => (Auth::user()->isHR()),
                'image' => ''
            );

            return View::make('summon.index', $viewData);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function councilSummonList() {
        if (Auth::user()->isHR()) {
            if (Request::ajax()) {
                $model = Summon::join('company', 'summon.company_id', '=', 'company.id')
                                ->select(['summon.*'])
                                ->where('summon.type',Summon::LETTER_OF_REMINDER)
                                ->where('summon.status', SUMMON::PENDING)
                                ->where('summon.is_deleted', 0)
                                ->orderBy('summon.created_at','desc');

                return Datatables::of($model)
                                ->editColumn('status', function($model) {
                                    return $model->status();
                                })
                                ->editColumn('created_at', function($model) {
                                    $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                    return $created_at;
                                })
                                ->editColumn('type', function ($model) {
                                    if ($model->type == Summon::LETTER_OF_REMINDER) {
                                        $title = trans('app.summon.letter_of_reminder');
                                    } else if ($model->type == Summon::LETTER_OF_DEMAND) {
                                        $title = trans('app.summon.letter_of_demand');
                                    }

                                    return $title;
                                })
                                ->addColumn('action', function ($model) {
                                    $btn = '';

                                    return $btn;
                                })
                                ->addColumn('cob', function ($model) {
                                    $title = ($model->company_id ? $model->company->short_name : '-');

                                    return $title;
                                })
                                ->addColumn('checkbox', function ($model) {
                                    $checkbox = '<div class="form-check">';
                                    $checkbox .= '<input type="checkbox" class="form-check-input" id="select_id" name="select_id.' . $model->id . '">';
                                    $checkbox .= '</div>';
                                    if($model->type == SUMMON::LETTER_OF_DEMAND) {
                                        $checkbox = '';
                                    }
                                    return $checkbox;
                                })
                                ->addColumn('action', function ($model) {
                                    $btn = '';
                                    $btn .= '<a href="' . route('summon.show', Helper::encode($model->id)) . '" class="btn btn-xs btn-primary margin-inline" title="View"><i class="fa fa-eye"></i></a>';

                                    return $btn;
                                })
                                ->make(true);
            }
            $cob = '';

            if(Auth::user()->isHR()) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }

            $viewData = array(
                'title' => trans('app.summon.title'),
                'panel_nav_active' => 'summon_panel',
                'main_nav_active' => 'summon_main',
                'sub_nav_active' => 'summon_list',
                'visible' => (Auth::user()->isHR()),
                'cob' => $cob,
                'image' => ''
            );

            return View::make('summon.index_all', $viewData);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }
    
    /**
     * Display a paid listing of the resource.
     *
     * @return Response
     */
    public function paidListing() {
        $this->checkAvailableAccess();
        if (Auth::user()->isHR() || Auth::user()->isCOBManager() || Auth::user()->getAdmin()) {
            if (Request::ajax()) {
                $model = SummonConfirmed::join('company', 'summon_confirmed.company_id', '=', 'company.id')
                                        ->select(['summon_confirmed.*']);
                if(Auth::user()->isCOBManager()) {
                    $model = $model->where('summon_confirmed.company_id', Auth::user()->company_id);
                }
                $model = $model                                        
                                ->where('summon_confirmed.is_deleted', 0)
                                ->orderBy('summon_confirmed.created_at','desc');

                return Datatables::of($model)
                                ->editColumn('created_at', function($model) {
                                    $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                    return $created_at;
                                })
                                ->editColumn('amount', function($model) {
                                    $amount = 'MYR '. $model->amount;

                                    return $amount;
                                })
                                ->addColumn('reference_no', function ($model) {
                                    $ids = explode(',', $model->selected_id);
                                    $summons = Summon::whereIn('id', $ids)->get();

                                    $text = '';

                                    foreach($summons as $summon) {
                                        $link = '- <u><a href="' . route('summon.show', Helper::encode($summon->id)) . '" target="_blank">' . $summon->unit_no .'-'. $summon->id . '</a></u>';
                                        $text .= $link . '<br>';
                                    }
                                    
                                    return $text;
                                })
                                ->addColumn('attachment', function ($model) {
                                  
                                    $files = json_decode($model->attachment);

                                    $text = '';

                                    foreach($files as $file) {
                                        $filename = explode("attachment/summon/confirm/$model->id/", $file);
                                        $link = '- <u><a href="' . asset($file) . '" download>' . $filename[1] . '</a></u>';
                                        $text .= $link . '<br>';
                                    }

                                    return $text;
                                })
                                ->addColumn('cob', function ($model) {
                                    $title = ($model->company_id ? $model->company->short_name : '-');

                                    return $title;
                                })
                                ->make(true);
            }
            $cob = '';

            if(Auth::user()->isHR()) {
                $cob = Company::where('is_active', 1)->where('is_main', 0)->where('is_deleted', 0)->orderBy('name')->get();
            }

            $viewData = array(
                'title' => trans('app.summon.paid'),
                'panel_nav_active' => 'summon_panel',
                'main_nav_active' => 'summon_main',
                'sub_nav_active' => 'summon_paid',
                'cob' => $cob,
                'image' => ''
            );

            return View::make('summon.paid', $viewData);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($type) {
        $this->checkAvailableAccess();
        if ((Auth::user()->isJMB() || Auth::user()->isDeveloper()) && Auth::user()->file_id) {
            $file = Files::find(Auth::user()->file_id);
            if ($file) {
                $unit_no = Buyer::unitNoList($file->id);
                $category = $file->strata->categories;
                if(empty($category)) {
                    App::abort(404);
                }

                if ($type == Summon::LETTER_OF_REMINDER) {
                    $durationOverdue = Summon::durationOverdue();
                    $title = trans('app.summon.letter_of_reminder');
                    $sub_nav_active = 'letter_of_reminder_list';
                    $lawyer = '';
                } else if ($type == Summon::LETTER_OF_DEMAND) {
                    $durationOverdue = Summon::durationOverdueLOD();
                    $title = trans('app.summon.letter_of_demand');
                    $sub_nav_active = 'letter_of_demand_list';
                    $lawyer = User::getLawyer(Auth::user()->company_id);
                } else {
                    return Redirect::to('summon')->with('error', trans('app.errors.occurred'));
                }

                $viewData = array(
                    'title' => $title,
                    'panel_nav_active' => 'summon_panel',
                    'main_nav_active' => 'summon_main',
                    'sub_nav_active' => $sub_nav_active,
                    'image' => '',
                    'unit_no' => $unit_no,
                    'durationOverdue' => $durationOverdue,
                    'category' => $category,
                    'type' => $type,
                    'lawyer' => $lawyer
                );

                return View::make('summon.create', $viewData);
            }
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $this->checkAvailableAccess();
        $data = Input::all();

        $rules = array(
            'unit_no' => 'required',
            'name' => 'required|min:3',
            'ic_no' => 'required|min:6|max:12',
            'phone_no' => 'required|min:6|max:12',
            'email' => 'required|email',
            'address' => 'required|min:6',
            'mailing_address' => 'required|min:6',
            'duration_overdue' => 'required',
            'total_overdue' => 'required|numeric|min:1',
            'attachment1' => 'required|mimes:pdf',
            'attachment2' => 'required|mimes:pdf',
            'attachment3' => 'required|mimes:pdf',
            'lawyer' => 'required_if:type,==,' . Summon::LETTER_OF_DEMAND
        );

        $messages = array(
            'required_if' => 'The :attribute field is required.',
            'attachment1.required' => 'The ' . trans('app.summon.attachment1') . ' is required.',
            'attachment1.mimes' => 'The ' . trans('app.summon.attachment1') . ' must be a file of type :values.',
            'attachment2.required' => 'The ' . trans('app.summon.attachment2') . ' is required.',
            'attachment2.mimes' => 'The ' . trans('app.summon.attachment2') . ' must be a file of type :values.',
            'attachment3.required' => 'The ' . trans('app.summon.attachment3') . ' is required.',
            'attachment3.mimes' => 'The ' . trans('app.summon.attachment3') . ' must be a file of type :values.',
        );

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $buyer = Buyer::find($data['unit_no']);

            if ($buyer) {
                $buyer->owner_name = ($buyer->owner_name != $data['name']) ? $data['name'] : $buyer->owner_name;
                $buyer->ic_company_no = ($buyer->ic_company_no != $data['ic_no']) ? $data['ic_no'] : $buyer->ic_company_no;
                $buyer->phone_no = ($buyer->phone_no != $data['phone_no']) ? $data['phone_no'] : $buyer->phone_no;
                $buyer->email = ($buyer->email != $data['email']) ? $data['email'] : $buyer->email;
                $buyer->address = ($buyer->address != $data['address']) ? $data['address'] : $buyer->address;
                $buyer->alamat_surat_menyurat = ($buyer->alamat_surat_menyurat != $data['mailing_address']) ? $data['mailing_address'] : $buyer->alamat_surat_menyurat;
                $buyer->save();

                $model = new Summon();
                $model->type = $data['type'];
                $model->user_id = Auth::user()->id;
                $model->file_id = Auth::user()->file_id;
                $model->category_id = $data['category'];
                $model->buyer_id = $buyer->id;
                $model->company_id = Auth::user()->company_id;
                if ($data['type'] == Summon::LETTER_OF_DEMAND) {
                    $model->lawyer_id = $data['lawyer'];
                }
                $model->unit_no = $buyer->unit_no;
                $model->name = $data['name'];
                $model->ic_no = $data['ic_no'];
                $model->phone_no = $data['phone_no'];
                $model->email = $data['email'];
                $model->address = $data['address'];
                $model->mailing_address = $data['mailing_address'];
                $model->duration_overdue = $data['duration_overdue'];
                $model->total_overdue = $data['total_overdue'];
                $model->status = Summon::DRAFT;
                $create = $model->save();

                if ($create) {
                    $attachment = '';
                    $destinationPath = 'attachment/' . $model->id;

                    if (Input::hasFile('attachment1')) {
                        if (!empty(Input::file('attachment1'))) {
                            $file = Input::file('attachment1');
                            $filename = trans('app.summon.attachment1') . '.' . $file->getClientOriginalExtension();
                            $file->move($destinationPath, $filename);
                            $attachment[] = $destinationPath . "/" . $filename;
                        }
                    }
                    if (Input::hasFile('attachment2')) {
                        if (!empty(Input::file('attachment2'))) {
                            $file = Input::file('attachment2');
                            $filename = trans('app.summon.attachment2') . '.' . $file->getClientOriginalExtension();
                            $file->move($destinationPath, $filename);
                            $attachment[] = $destinationPath . "/" . $filename;
                        }
                    }
                    if (Input::hasFile('attachment3')) {
                        if (!empty(Input::file('attachment3'))) {
                            $file = Input::file('attachment3');
                            $filename = trans('app.summon.attachment3') . '.' . $file->getClientOriginalExtension();
                            $file->move($destinationPath, $filename);
                            $attachment[] = $destinationPath . "/" . $filename;
                        }
                    }

                    if (!empty($attachment)) {
                        $model->attachment = json_encode($attachment);
                        $success = $model->save();

                        # Audit Trail
                        $remarks = 'Summon: ' . $model->id . $this->module['audit']['text']['data_inserted'];
                        $this->addAudit(Auth::user()->file_id, "Summon", $remarks);

                        if ($success) {
                            return Redirect::to('summon/' . Helper::encode($model->id))->with('success', trans('app.successes.saved_successfully'));
                        }
                    } else {
                        //revert
                        $model->delete();
                    }
                }
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $this->checkAvailableAccess();
        if ((Auth::user()->isJMB() || Auth::user()->isDeveloper()) && Auth::user()->file_id) {
            $file = Files::find(Auth::user()->file_id);
            if ($file) {
                $model = Summon::findOrFail(Helper::decode($id));
                if ($model) {
                    $unit_no = Buyer::unitNoList($file->id);
                    $category = ($model->category_id ? $model->category->description : '');
                    $type = $model->type;
                    $durationOverdue = $model->durationTitle();

                    $cash = 0;
                    $amount = 0;
                    $eligible_pay = false;

                    $available_point = Auth::user()->getTotalPoint();
                    if ($model->status == Summon::DRAFT) {
                        if ($model->category_id && $model->company_id) {
                            $cat = Category::find($model->category_id);
                            if ($cat) {
                                $cash = $cat->getSummonCash($model->company_id);
                                $amount = $cat->getSummonAmount($model->company_id);
                                if ($available_point >= $amount) {
                                    $eligible_pay = true;
                                }
                            }
                        }
                    }

                    if ($type == Summon::LETTER_OF_REMINDER) {
                        $type = Summon::LETTER_OF_REMINDER;
                        $title = trans('app.summon.letter_of_reminder');
                        $sub_nav_active = 'letter_of_reminder_list';
                    } else if ($type == Summon::LETTER_OF_DEMAND) {
                        $type = Summon::LETTER_OF_REMINDER;
                        $title = trans('app.summon.letter_of_demand');
                        $sub_nav_active = 'letter_of_demand_list';
                    } else {
                        return Redirect::to('summon')->with('error', trans('app.errors.occurred'));
                    }

                    $viewData = array(
                        'title' => $title,
                        'panel_nav_active' => 'summon_panel',
                        'main_nav_active' => 'summon_main',
                        'sub_nav_active' => $sub_nav_active,
                        'image' => '',
                        'unit_no' => $unit_no,
                        'durationOverdue' => $durationOverdue,
                        'category' => $category,
                        'type' => $type,
                        'model' => $model,
                        'attachment' => json_decode($model->attachment),
                        'cash' => $cash,
                        'amount' => $amount,
                        'eligible_pay' => $eligible_pay
                    );

                    return View::make('summon.show', $viewData);
                }
            }
        } else if (Auth::user()->isLawyer()) {
            $model = Summon::findOrFail(Helper::decode($id));
            if ($model && $model->type == Summon::LETTER_OF_DEMAND) {
                $category = ($model->category_id ? $model->category->description : '');
                $type = $model->type;
                $durationOverdue = $model->durationTitle();

                $viewData = array(
                    'title' => trans('app.summon.letter_of_demand'),
                    'panel_nav_active' => 'summon_panel',
                    'main_nav_active' => 'summon_main',
                    'sub_nav_active' => 'summon_list',
                    'image' => '',
                    'durationOverdue' => $durationOverdue,
                    'category' => $category,
                    'attachment' => json_decode($model->attachment),
                    'model' => $model
                );

                return View::make('summon.show', $viewData);
            }
        } else if (Auth::user()->isCOBManager()) {
            $model = Summon::findOrFail(Helper::decode($id));
            if ($model && $model->type == Summon::LETTER_OF_REMINDER) {
                $category = ($model->category_id ? $model->category->description : '');
                $durationOverdue = $model->durationTitle();

                $viewData = array(
                    'title' => trans('app.summon.letter_of_reminder'),
                    'panel_nav_active' => 'summon_panel',
                    'main_nav_active' => 'summon_main',
                    'sub_nav_active' => 'summon_list',
                    'image' => '',
                    'durationOverdue' => $durationOverdue,
                    'category' => $category,
                    'type' => $model->type,
                    'attachment' => json_decode($model->attachment),
                    'model' => $model
                );

                return View::make('summon.show', $viewData);
            }
        } else if (Auth::user()->isHR() || Auth::user()->getAdmin()) {
            $model = Summon::findOrFail(Helper::decode($id));
            if ($model && $model->type == Summon::LETTER_OF_REMINDER) {
                $category = ($model->category_id ? $model->category->description : '');
                $durationOverdue = $model->durationTitle();

                $viewData = array(
                    'title' => trans('app.summon.letter_of_reminder'),
                    'panel_nav_active' => 'summon_panel',
                    'main_nav_active' => 'summon_main',
                    'sub_nav_active' => 'summon_list',
                    'image' => '',
                    'durationOverdue' => $durationOverdue,
                    'category' => $category,
                    'type' => $model->type,
                    'attachment' => json_decode($model->attachment),
                    'model' => $model
                );

                return View::make('summon.show', $viewData);
            }
        }

        return Redirect::to('/')->with('error', trans('app.errors.occurred'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $this->checkAvailableAccess();
        $data = Input::all();

        if (Auth::user()->isLawyer() || Auth::user()->isCOBManager()) {
            $rules = array(
                'uploaded_file' => 'required_if:status,==,' . Summon::APPROVED . '|mimes:pdf',
                'status' => 'required'
            );
        } else {
            $rules = array();
        }

        if (Auth::user()->isLawyer()) {
            $messages = array(
                'uploaded_file.required_if' => 'The ' . trans('app.summon.letter_of_demand') . ' is required.',
                'uploaded_file.mimes' => 'The ' . trans('app.summon.letter_of_demand') . ' must be a file of type :values.',
            );
        } else {
            $messages = array(
                'uploaded_file.required_if' => 'The ' . trans('app.summon.letter_of_reminder') . ' is required.',
                'uploaded_file.mimes' => 'The ' . trans('app.summon.letter_of_reminder') . ' must be a file of type :values.',
            );
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $model = Summon::findOrFail(Helper::decode($id));
            if ($model) {
                $attachment = '';
                $destinationPath = 'attachment/' . $model->id;

                if (Input::hasFile('uploaded_file')) {
                    if (!empty(Input::file('uploaded_file'))) {
                        $file = Input::file('uploaded_file');
                        if ($model->type == Summon::LETTER_OF_DEMAND) {
                            $filename = trans('app.summon.letter_of_demand') . '.' . $file->getClientOriginalExtension();
                        } else {
                            $filename = trans('app.summon.letter_of_reminder') . '.' . $file->getClientOriginalExtension();
                        }
                        $file->move($destinationPath, $filename);
                        $attachment = $destinationPath . "/" . $filename;
                    }
                }

                /** Arrange audit fields changes */
                $status_field = $data['status'] == $model->status? "": "status";
                $action_file_field = $attachment == $model->action_file? "": "attachment";

                $audit_fields_changed = "";
                if(!empty($status_field) || !empty($action_file_field)) {
                    $audit_fields_changed .= "<br><ul>";
                    $audit_fields_changed .= !empty($status_field)? "<li>$status_field</li>" : "";
                    $audit_fields_changed .= !empty($action_file_field)? "<li>$action_file_field</li>" : "";
                    $audit_fields_changed .= "</ul>";
                }
                /** End Arrange audit fields changes */

                $model->status = $data['status'];
                if (!empty($attachment)) {
                    $model->action_file = $attachment;
                }
                $model->action_by = Auth::user()->id;
                $model->action_date = date('Y-m-d H:i:s');
                $success = $model->save();

                if ($success) {
                    # Audit Trail
                    if(!empty($audit_fields_changed)) {
                        $remarks = 'Summon: ' . $model->id . $this->module['audit']['text']['data_updated'] . $audit_fields_changed;
                        $this->addAudit($model->file_id, "Summon", $remarks);
                    }

                    return Redirect::to('summon')->with('success', trans('app.successes.updated_successfully'));
                }
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $this->checkAvailableAccess();
        $model = Summon::findOrFail(Helper::decode($id));
        if ($model) {
            $model->status = Summon::CANCELED;
            $success = $model->save();

            # Audit Trail
            $remarks = 'Summon: ' . $model->id . "has been cancelled";
            $this->addAudit($model->file_id, "Summon", $remarks);
            
            if ($success) {
                $order = Orders::where('reference_id', $model->id)->where('payment_method', Orders::POINT)->where('type', Orders::SUMMON)->where('status', Orders::APPROVED)->first();
                if ($order) {
                    $old_transaction = PointTransaction::where('order_id', $order->id)->first();
                    if ($old_transaction) {
                        // refund
                        $ref_no = date('YmdHis') . $old_transaction->order_id;

                        $transaction = new PointTransaction();
                        $transaction->user_id = $old_transaction->user_id;
                        $transaction->order_id = $old_transaction->order_id;
                        $transaction->reference_no = $ref_no;
                        $transaction->type = Orders::REFUND;
                        $transaction->is_debit = true;
                        $transaction->point_availabe = Auth::user()->getTotalPoint();
                        $transaction->point_usage = $old_transaction->point_usage;
                        $transaction->point_balance = Auth::user()->getTotalPoint() + $old_transaction->point_usage;
                        $transaction->description = 'Refund Summon for ' . $model->ic_no;
                        $refund = $transaction->save();


                        if ($refund) {
                            # Audit Trail
                            $remarks = 'Point Transaction ref no: ' . $ref_no . "has been refunded";
                            $this->addAudit(Auth::user()->file_id, "Point", $remarks);

                            return Redirect::to('summon')->with('success', trans('app.successes.updated_successfully'));
                        }
                    }
                }

                return Redirect::to('summon')->with('success', trans('app.successes.updated_successfully'));
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function purchaser() {
        $this->checkAvailableAccess();
        $result = array();

        if (Request::ajax()) {
            $data = Input::all();

            $model = Buyer::find($data['id']);
            if ($model) {
                $result = array(
                    'id' => $model->id,
                    'unit_no' => $model->unit_no,
                    'name' => $model->owner_name,
                    'ic_no' => $model->ic_company_no,
                    'address' => $model->address,
                    'mailing_address' => $model->alamat_surat_menyurat,
                    'phone_no' => $model->phone_no,
                    'email' => $model->email,
                    'usage_type' => $model->jenis_kegunaan,
                    'nationality' => $model->nationality_id,
                );

                return json_encode($result);
            }
        }

        return $result;
    }

    public function orders() {
        $this->checkAvailableAccess();
        $data = Input::all();

        $rules = array(
            'payment_method' => 'required'
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $summon = Summon::findOrFail(Helper::decode($data['summon']));
            if ($summon) {
                $description = 'Created Summon for ' . $summon->ic_no;
                $ref_no = date('YmdHis') . $summon->id;
                
                $model = new Orders();
                $model->user_id = Auth::user()->id;
                $model->reference_id = $summon->id;
                $model->reference_no = $ref_no;
                $model->type = Orders::SUMMON;
                $model->description = $description;
                $model->amount = $data['amount'];
                $model->payment_method = $data['payment_method'];
                $success = $model->save();

                if ($success) {
                    # Audit Trail
                    $remarks = 'New order ref no: ' . $ref_no . $this->module['audit']['text']['data_inserted'];
                    $this->addAudit(Auth::user()->file_id, "Order", $remarks);

                    return Redirect::to('summon/payment')->with('orderID', $model->id);
                }
            }

            return Redirect::back()->with('error', trans('app.errors.occurred'));
        }
    }

    public function payment() {
        $this->checkAvailableAccess();
        $eligible_pay = true;

        $model = Orders::find(Session::get('orderID'));

        if ($model) {
            if ($model->payment_method == Orders::POINT) {
                $available_point = Auth::user()->getTotalPoint();
                $total_amount =  $model->getSummonPoint();
                if ($available_point < $total_amount) {
                    $eligible_pay = false;
                }
            } else {
                $total_amount = $model->amount;
            }

            $viewData = array(
                'title' => trans('app.summon.payment'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => 'summon_list',
                'model' => $model,
                'image' => '',
                'eligible_pay' => $eligible_pay,
                'total_amount' => $total_amount
            );

            return View::make('summon.payment', $viewData);
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'));
    }

    public function submitPay() {
        $this->checkAvailableAccess();
        $data = Input::all();

        $model = Orders::findOrFail(Helper::decode($data['order_id']));
        
        if ($model) {
            $total_amount = $data['amount'];

            if ($model->payment_method == Orders::POINT) {
                $available_point = Auth::user()->getTotalPoint();
                if ($available_point >= $total_amount) {
                    $model->status = Summon::APPROVED;
                    $success = $model->save();

                    if ($success) {
                        if ($model->status == Summon::APPROVED) {
                            $summon = Summon::find($model->reference_id);
                            if ($summon) {
                                $summon->status = Summon::PENDING;
                                $summon->save();
                            }

                            $ref_no = date('YmdHis') . $model->id;

                            $transaction = new PointTransaction();
                            $transaction->user_id = $model->user_id;
                            $transaction->order_id = $model->id;
                            $transaction->reference_no = $ref_no;
                            $transaction->type = $model->type;
                            $transaction->is_debit = false;
                            $transaction->point_availabe = Auth::user()->getTotalPoint();
                            $transaction->point_usage = $total_amount;
                            $transaction->point_balance = (Auth::user()->getTotalPoint() - $total_amount);
                            $transaction->description = $model->description;
                            $transaction->amount = $model->amount;
                            $transaction->rate = $model->getSummonRate();
                            $transaction->save();
                            
                            /** Transaction Data Save */
                            $item = (new PaymentTransaction());
                            $item->user_id = Auth::user()->getKey();
                            $item->moduleable_id = $model->reference_id;
                            $item->moduleable_type = get_class($summon);
                            $item->reference_no = $model->reference_no;
                            $item->transaction_type = 'PAY';
                            $item->payment_gateway = 'my_point';
                            $item->pay_for = ($summon->type == 1)? 'letter_of_reminder' : 'letter_of_demand';
                            $item->description = 'summon paid by point';
                            $item->amount = $total_amount;
                            $item->cust_ip = \Request::ip();
                            /** Payment Method : OB (online banking), CC (credit card), WA (ewallet) */
                            $item->payment_method = "Point";
                            $item->status  = PaymentTransaction::SUCCESS;
                            $item->save();
                          
                            /** send success email to payer */
                            // Mail::send('emails.point.payment_success', array('model' => $model), function($message) use ($model) {
                            //     $message->to($model->user->email, $model->user->full_name)->subject('Payment Success');
                            // });
                            
                            # Audit Trail
                            $remarks = 'Summon:'. $summon->id .' was payment successful the order ref no: '. $model->reference_no;
                            $this->addAudit($summon->file_id, "Summon", $remarks);
                        }

                        return Redirect::to('summon')->with('success', trans('app.successes.payment_successfully'));
                    }
                } else {
                    return Redirect::to('summon/' . Helper::encode($model->reference_id))->with('error', trans('app.my_point.not_enough'))->withInput($data);
                }
            } else {
                $data['payment_gateway'] = 'revenue';
                $rules = array(
                    'payment_gateway' => 'required',
                    'payment_method' => 'required',
                    'terms' => 'required'
                );

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    return Redirect::to('summon/payment')->with('orderID', $model->id)->withErrors($validator)->withInput($data);
                } else {
                    $model->status = Summon::REJECTED;
                    $success = $model->save();

                    /** This need to be in transaction controller */
                    if ($success) {
                        // if ($model->status == Summon::APPROVED) {
                        $summon = Summon::find($model->reference_id);
                        //     if ($summon) {
                        //         $summon->status = Summon::PENDING;
                        //         $summon->save();
                        //     }

                        //     Mail::send('emails.summon.payment_success', array('model' => $model), function($message) use ($model) {
                        //         $message->to($model->user->email, $model->user->full_name)->subject('Payment Success');
                        //     });
                        // }

                        // return Redirect::to('summon')->with('success', trans('app.successes.payment_successfully'));
                        
                        /** Payment Process */
                        $payment_data['module_id'] = $summon->getKey();
                        $payment_data['module'] = 'summon';
                        $payment_data['reference_no'] = $model->reference_no;
                        $payment_data['pay_for'] = ($summon->type == SUMMON::LETTER_OF_REMINDER)? 'letter_of_reminder' : 'letter_of_demand';
                        $payment_data['payment_gateway'] = $data['payment_gateway'];
                        $payment_data['payment_method'] = $data['payment_method'];
                        $payment_data['description'] = $model->description;
                        $payment_data['amount'] = $model->amount;
                        $payment_data['order_id'] = $model->getKey();
                        
                        $payment_params = (new TransactionController())->paymentProcess($payment_data);
                        
                        if($data['payment_gateway'] == Config::get('constant.module.payment.gateway.paydibs.slug')) {
                            return Redirect::to(Config::get('constant.module.payment.gateway.paydibs.pay_request_url') .'?'. $payment_params);
                        } else {
                            // direct sent to success function
                            if(getenv('payment_gateway')) {
                                // dd($payment_params);
                                $redirect_url = $payment_params->item->url;
                                return Redirect::to($redirect_url);
                            } else {
                                if($payment_params->status == PaymentTransaction::SUCCESS) {
                                    return Redirect::to('summon')->with('success', trans('app.successes.payment_successfully'));
                                }
                            }
                            
                        }
                    }
                }
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
    }

    public function uploadPayment() {
        $this->checkAvailableAccess();
        $data = Input::all();

        $rules = array(
            'selected_id' => 'required',
            'amount' => 'required',
            // 'upload_file' => 'required|mimes:pdf'
        );
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            return "false";
        } else {
            $selected_ids = explode("&",$data['selected_id']);
            $array_id = [];
            $company_id = '';
            foreach($selected_ids as $selected_id) {
                preg_match_all('!\d+!', $selected_id, $matches);
                $id = $matches[0][0];
                array_push($array_id, $id);
                
                /** Update Summon Status */
                $summon = Summon::find($id);
                $summon->status = Summon::INPROGRESS;
                $summon->save();

                $company_id = $summon->company_id;
            }

            $model = new SummonConfirmed();
            $model->selected_id = implode(',',$array_id);
            $model->company_id = $company_id;
            $model->amount = $data['amount'];
            $model->save();

            
            /** Process Upload File */
            $attachment = '';
            foreach($data['upload_file'] as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                if($ext != 'pdf') {
                    $model->delete();
                    return 'valid_file';
                }

                $destinationPath = 'attachment/summon/confirm/' . $model->id;
    
                $filename = $file->getClientOriginalName();
                // $filename = $file->getClientOriginalName() . '.' . $ext;
                $file->move($destinationPath, $filename);
                $attachment[] = $destinationPath . "/" . $filename;
        
            }

            if (!empty($attachment)) {
                # Audit Trail
                $remarks = 'Summon :'. $summon->id .' has upload a file.';
                $this->addAudit($summon->file_id, "Summon", $remarks);

                $model->attachment = json_encode($attachment);
                $success = $model->save();

                return "true";
            } else {
                //revert
                $model->delete();

                return "false";
            }
            
        }

    }

    private function checkAvailableAccess() {
        if(!Auth::user()->getAdmin() && (!Auth::user()->getAdmin() && !in_array(Auth::user()->getCOB->short_name, ['MPS', 'MPAJ']))) {
            App::abort(404);
        }
    }

}
