<?php

class SummonController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->isJMB()) {
            if (Request::ajax()) {
                $model = Summon::where('user_id', Auth::user()->id)->where('is_deleted', 0);

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
                                        $btn .= '<a href="' . route('summon.show', $model->id) . '" class="btn btn-xs btn-success-outline margin-right-5">' . trans('app.summon.pay_now') . '</a>';
                                    }
                                    $btn .= '<a href="' . route('summon.show', $model->id) . '" class="btn btn-xs btn-primary margin-right-5" title="View"><i class="fa fa-eye"></i></a>';
                                    if ($model->status == Summon::DRAFT || $model->status == Summon::PENDING) {
                                        $btn .= '<form action="' . route('summon.destroy', $model->id) . '" method="POST" id="delete_form_' . $model->id . '" style="display:inline-block;">';
                                        $btn .= '<input type="hidden" name="_method" value="DELETE">';
                                        $btn .= '<button type="submit" class="btn btn-xs btn-danger margin-right-5 confirm-delete" data-id="delete_form_' . $model->id . '" title="Cancel">Cancel</button>';
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
                                    $btn .= '<a href="' . route('summon.show', $model->id) . '" class="btn btn-xs btn-primary margin-inline" title="View"><i class="fa fa-eye"></i></a>';

                                    return $btn;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.summon.title'),
                'panel_nav_active' => 'summon_panel',
                'main_nav_active' => 'summon_main',
                'sub_nav_active' => 'summon_list',
                'image' => ''
            );

            return View::make('summon.index', $viewData);
        } else if (Auth::user()->isCOBManager()) {
            if (Request::ajax()) {
                $model = Summon::where('company_id', Auth::user()->company_id)
                        ->where(function($query) {
                            $query->where('status', Summon::PENDING)
                            ->orWhere('status', Summon::APPROVED)
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
                                    $btn .= '<a href="' . route('summon.show', $model->id) . '" class="btn btn-xs btn-primary margin-inline" title="View"><i class="fa fa-eye"></i></a>';

                                    return $btn;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.summon.title'),
                'panel_nav_active' => 'summon_panel',
                'main_nav_active' => 'summon_main',
                'sub_nav_active' => 'summon_list',
                'image' => ''
            );

            return View::make('summon.index', $viewData);
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
        if (Auth::user()->isJMB() && Auth::user()->file_id) {
            $file = Files::find(Auth::user()->file_id);
            if ($file) {
                $unit_no = Buyer::unitNoList($file->id);
                $category = $file->strata->categories;

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

                        if ($success) {
                            return Redirect::to('summon/' . $model->id)->with('success', trans('app.successes.saved_successfully'));
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
        if (Auth::user()->isJMB() && Auth::user()->file_id) {
            $file = Files::find(Auth::user()->file_id);
            if ($file) {
                $model = Summon::find($id);
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
            $model = Summon::find($id);
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
            $model = Summon::find($id);
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
            $model = Summon::find($id);
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

                $model->status = $data['status'];
                if (!empty($attachment)) {
                    $model->action_file = $attachment;
                }
                $model->action_by = Auth::user()->id;
                $model->action_date = date('Y-m-d H:i:s');
                $success = $model->save();

                if ($success) {
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
        $model = Summon::find($id);
        if ($model) {
            $model->status = Summon::CANCELED;
            $success = $model->save();

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
        $data = Input::all();

        $rules = array(
            'payment_method' => 'required'
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($data);
        } else {
            $summon = Summon::find($data['summon']);
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
                    return Redirect::to('summon/payment')->with('orderID', $model->id);
                }
            }

            return Redirect::back()->with('error', trans('app.errors.occurred'));
        }
    }

    public function payment() {
        $eligible_pay = true;

        $model = Orders::find(Session::get('orderID'));

        if ($model) {
            if ($model->payment_method == Orders::POINT) {
                $available_point = Auth::user()->getTotalPoint();
                $total_amount = $model->getSummonPoint();
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
        $data = Input::all();

        $model = Orders::find($data['order_id']);

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
                            $transaction->point_balance = Auth::user()->getTotalPoint() - $total_amount;
                            $transaction->description = $model->description;
                            $transaction->amount = $model->amount;
                            $transaction->rate = $model->getSummonRate();
                            $transaction->save();
                        }

                        return Redirect::to('summon')->with('success', trans('app.successes.payment_successfully'));
                    }
                } else {
                    return Redirect::to('summon/' . $model->reference_id)->with('error', trans('app.my_point.not_enough'))->withInput($data);
                }
            } else {
                $rules = array(
                    'payment_method' => 'required',
                    'terms' => 'required'
                );

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    return Redirect::to('summon/payment')->with('orderID', $model->id)->withErrors($validator)->withInput($data);
                } else {
                    $model->status = Summon::APPROVED;
                    $success = $model->save();

                    if ($success) {
                        if ($model->status == Summon::APPROVED) {
                            $summon = Summon::find($model->reference_id);
                            if ($summon) {
                                $summon->status = Summon::PENDING;
                                $summon->save();
                            }

                            Mail::send('emails.summon.payment_success', array('model' => $model), function($message) use ($model) {
                                $message->to($model->user->email, $model->user->full_name)->subject('Payment Success');
                            });
                        }

                        return Redirect::to('summon')->with('success', trans('app.successes.payment_successfully'));
                    }
                }
            }
        }

        return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
    }

}
