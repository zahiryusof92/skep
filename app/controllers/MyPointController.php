<?php

class MyPointController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->isJMB()) {
            if (Request::ajax()) {
                $model = PointTransaction::where('user_id', Auth::user()->id);

                return Datatables::of($model)
                                ->editColumn('type', function($model) {
                                    return ucwords($model->type);
                                })
                                ->editColumn('point_usage', function($model) {
                                    if ($model->is_debit) {
                                        $amount = '+' . $model->point_usage;
                                    } else {
                                        $amount = '-' . $model->point_usage;
                                    }

                                    return $amount;
                                })
                                ->editColumn('created_at', function($model) {
                                    $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i:s A') : '');

                                    return $created_at;
                                })
                                ->make(true);
            }

            $viewData = array(
                'title' => trans('app.my_point.title'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => 'my_point_list',
                'total_point' => Auth::user()->getTotalPoint(),
                'image' => ''
            );

            return View::make('my_point.index', $viewData);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    public function reload() {
        if (Auth::user()->isJMB()) {
            $package = PointPackage::where('is_active', 1)->where('is_deleted', 0)->orderBy('name')->get();
            $viewData = array(
                'title' => trans('app.my_point.reload'),
                'panel_nav_active' => '',
                'main_nav_active' => '',
                'sub_nav_active' => 'my_point_list',
                'package' => $package,
                'image' => ''
            );

            return View::make('my_point.reload', $viewData);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }

    public function orders() {
        if (Auth::user()->isJMB()) {
            $data = Input::all();

            $rules = array(
                'package' => 'required',
            );

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput($data);
            } else {
                $package = PointPackage::find($data['package']);
                if ($package) {
                    $description = 'Reload using package ' . $package->name;
                    $ref_no = date('YmdHis') . $package->id;

                    $model = new Orders();
                    $model->user_id = Auth::user()->id;
                    $model->reference_id = $package->id;
                    $model->reference_no = $ref_no;
                    $model->type = Orders::RELOAD;
                    $model->description = $description;
                    $model->amount = $package->price;
                    $success = $model->save();

                    if ($success) {
                        return Redirect::to('myPoint/payment')->with('orderID', $model->id);
                    }
                }
            }

            return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }

    public function payment() {
        if (Auth::user()->isJMB()) {
            $model = Orders::find(Session::get('orderID'));

            if (!empty($model)) {
                $viewData = array(
                    'title' => trans('app.my_point.payment'),
                    'panel_nav_active' => '',
                    'main_nav_active' => '',
                    'sub_nav_active' => 'my_point_list',
                    'model' => $model,
                    'image' => ''
                );

                return View::make('my_point.payment', $viewData);
            }

            return Redirect::to('/myPoint/reload')->with('error', trans('app.errors.occurred'));
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }

    public function submitPay() {
        if (Auth::user()->isJMB()) {
            $data = Input::all();

            $model = Orders::find($data['order_id']);
            if ($model) {
                $rules = array(
                    'payment_method' => 'required',
                    'terms' => 'required'
                );

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    return Redirect::to('myPoint/payment')->with('orderID', $model->id)->withErrors($validator)->withInput($data);
                } else {
                    $model->payment_method = $data['payment_method'];
                    $model->status = Orders::APPROVED;
                    $success = $model->save();

                    if ($success) {
                        if ($model->status == Orders::APPROVED) {
                            $ref_no = date('YmdHis') . $model->id;

                            $transaction = new PointTransaction();
                            $transaction->user_id = $model->user_id;
                            $transaction->order_id = $model->id;
                            $transaction->reference_no = $ref_no;
                            $transaction->type = $model->type;
                            $transaction->is_debit = true;
                            $transaction->point_availabe = Auth::user()->getTotalPoint();
                            $transaction->point_usage = $model->package->points;
                            $transaction->point_balance = Auth::user()->getTotalPoint() + $model->package->points;
                            $transaction->description = $model->description;
                            $transaction->amount = $model->package->price;
                            $transaction->rate = ($model->package->points / $model->package->price);
                            $transaction->save();
                        }

                        Mail::send('emails.summon.payment_success', array('model' => $model), function($message) use ($model) {
                            $message->to($model->user->email, $model->user->full_name)->subject('Payment Success');
                        });

                        return Redirect::to('myPoint')->with('success', trans('app.successes.payment_successfully'));
                    }
                }
            }

            return Redirect::back()->with('error', trans('app.errors.occurred'))->withInput($data);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }
    }

}
