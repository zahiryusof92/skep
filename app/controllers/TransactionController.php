<?php

use Helper\Paydibs;
use Helper\Revenue;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


class TransactionController extends BaseController {

    public function __construct()
    {
        $this->config = Config::get('constant.module.payment');
    }

    /**
     * Index page of transaction
     */
    public function index() {
        
        $viewData = array(
            'title' => trans('app.transaction.title'),
            'panel_nav_active' => '',
            'main_nav_active' => '',
            'sub_nav_active' => 'transaction_list',
            'image' => ""
        );
        return View::make('transaction.index', $viewData);
    }

    /**
     * Get Transaction List
     */
    public function getTransaction() {
        if (\Request::ajax()) {
            if(Auth::user()->isSuperadmin() || Auth::user()->isHR() || Auth::user()->getAdmin()) {
                $model = PaymentTransaction::with('moduleable.paidBy')
                                ->where('is_deleted', 0)
                                ->where('moduleable_type','Summon')
                                ->where('pay_for','!=',Config::get('constant.module.payment.pay_for.letter_of_demand.slug'));

            } else if(Auth::user()->isLawyer()) {
                $model = PaymentTransaction::with('moduleable.paidBy')
                                ->where('is_deleted', 0)
                                ->where('moduleable_type','Summon')
                                ->where('pay_for',Config::get('constant.module.payment.pay_for.letter_of_demand.slug'));

            } else {
                $model = PaymentTransaction::with('moduleable.paidBy')->where('user_id', Auth::user()->id)->where('is_deleted', 0);
            }
            
            return Datatables::of($model)
                                ->editColumn('status', function($model) {
                                    $label_text = ucfirst(Config::get('constant.module.payment.status.'. $model->status));
                                    if($label_text == 'Fail') {
                                        $label_class = 'danger';
                                    } else if($label_text == 'Pending') {
                                        $label_class = 'warning';
                                    } else {
                                        $label_class = 'success';
                                    }
                                    
                                    return "<span class='label label-$label_class'>$label_text</span>";
                                })
                                ->editColumn('reference_no', function($model) {
                                    $url = ($model->moduleable_type == 'Summon')? url("summon/$model->moduleable_id") : url('/myPoint');
                                    return "<a href='" . $url . "' target='_blank'><u>$model->reference_no</u></a>";
                                })
                                ->editColumn('pay_for', function($model) {
                                    return ucfirst(Config::get('constant.module.payment.pay_for.'. $model->pay_for .'.title'));
                                })
                                ->editColumn('created_at', function($model) {
                                    $created_at = ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '');

                                    return $created_at;
                                })
                                ->editColumn('payment_method', function($model) {
                                    if($model->payment_method == 'CC') {
                                        $label_text = 'Credit Card';
                                    } else if($model->payment_method == 'OB') {
                                        $label_text = 'Online Banking';
                                    } else {
                                        $label_text = 'Point';
                                    }
                                    
                                    return "$label_text";
                                })
                                ->addColumn('user', function ($model) {
                                    
                                    return ucwords($model->moduleable->paidBy->username);
                                })
                                ->make(true);
        } else {
            return Redirect::to('/')->with('error', trans('app.errors.occurred'));
        }

    }

    /**
     * Process the payment and redirect to payment gateway
     */
    public function paymentProcess($data) {
        $response = '';

        // $data = Input::all();
        $validation_rules = [
            'module_id' => 'required',
            'module' => 'required',
            'pay_for' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'payment_gateway' => 'required',
            'payment_method' => 'required',
            'reference_no' => 'required',
            'order_id' => 'required',
        ];

        $validator = \Validator::make($data, $validation_rules, []);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return Redirect::back()->with('error', trans('app.errors.occurred'))->withErrors($validator)->withInput($data);
        }
        
        DB::transaction(function () use(&$response, $data){
            
            /** Data Save */
            $item = (new PaymentTransaction());
            $item->user_id = Auth::user()->getKey();
            $item->moduleable_id = $data['module_id'];
            $item->moduleable_type = ($data['module'] == 'summon')? get_class(new Summon) : get_class(new PointTransaction);
            $item->transaction_type = 'PAY';
            $item->payment_gateway = Config::get('constant.module.payment.gateway.'. $data['payment_gateway'] .'.slug');
            $item->pay_for = Config::get('constant.module.payment.pay_for.'. $data['pay_for'] .'.slug');
            $item->description = $data['description'];
            $item->amount = $data['amount'];
            $item->cust_ip = \Request::ip();
            /** Payment Method : OB (online banking), CC (credit card), WA (ewallet) */
            $item->payment_method = (empty($data['payment_method']) == false)? ($data['payment_method'] == 'card')? 'CC' : 'OB' :'OB';
            $item->status  = PaymentTransaction::FAIL;
            $item->save();

            
    
            /** Generate reference no */
            // $reference_no = (empty($data['reference_no']) == false)? $data['reference_no'] : (new Hashids())->encode($item->id,100);
            // $reference_no = date('YmdHis') . $data['order_id'];
    

            /** Create Array Data for Payment Gateway */
            // $user = User::find($item->moduleable->user_id);
            // $payment_gateway_data['payment_id'] = $reference_no;
            // $payment_gateway_data['order_id'] = $data['order_id'];
            // $payment_gateway_data['description'] = $item->description;
            // $payment_gateway_data['amount'] = $item->amount;
            // $payment_gateway_data['redirect_url'] = url('transaction/success');
            // $payment_gateway_data['customer_ip'] = $item->cust_ip;
            // $payment_gateway_data['customer_name'] = $user->full_name;
            // $payment_gateway_data['customer_email'] = $user->email;
            // $payment_gateway_data['customer_phone'] = $user->phone_no;
            // $payment_gateway_data['callback_url'] = url('transaction/success');
            // /** Payment Method : OB (online banking), CC (credit card), WA (ewallet) */
            // $payment_gateway_data['payment_method'] = $item->payment_method;
            
            // /** Update Payment Transaction info */
            // $item->reference_no = $reference_no;
            // $item->sign = (new Paydibs())->generateSign($payment_gateway_data, $item->transaction_type);
    
            // $item->save();
    
            // $payment_gateway_data['sign'] = $item->sign;

            // // return $payment_gateway_data;
            
            // $response = (new Paydibs())->payRequest($payment_gateway_data);
            if($data['payment_gateway'] == $this->config['gateway']['paydibs']['slug']) {
                $response = $this->processPaydibs($data['order_id'], $item);
            } else {
                $response = $this->processRevenueMonster($data['order_id'], $item);

            }
            
        });
        return $response;

    }


    public function success() {
        $request = Input::all();
        Log::info($request);
        if(empty($request['MerchantPymtID']) == false) {
            $item = PaymentTransaction::where('reference_no',$request['MerchantPymtID'])->first();
            $item->status = $request['PTxnStatus'];
        } else {
            $orderId = $request['orderId'];
            $getOrder = $this->getRevenueTransactionStatus($orderId);
            $item = PaymentTransaction::where('reference_no',$getOrder->item->order->id)->first();
            $item->status = ($getOrder->item->status == 'SUCCESS')? 0 : 1;
        }
        $item->save();
        if($item->moduleable_type == get_class(new PointTransaction)) {
            $model = Orders::find($item->moduleable->order_id);
            if($item->status == PaymentTransaction::FAIL) {
                $model->status = Orders::REJECTED;
                $success = $model->save();

                $point_transaction = $item->moduleable;
                // $point_transaction->point_balance = $point_transaction->point_balance;
                $point_transaction->point_usage = 0;
                $point_transaction->save();

                /** send rejected email to payer */
                Mail::send('emails.point.payment_fail', array('model' => $model), function($message) use ($model) {
                    $message->to($model->user->email, $model->user->full_name)->subject('Payment Fail');
                });

                return Redirect::to('myPoint')->with('error', trans('app.errors.payment_failed'));

            } else {
                $model->status = Orders::APPROVED;
                $success = $model->save();

                $point_transaction = $item->moduleable;
                $point_transaction->point_balance = $point_transaction->point_balance + $point_transaction->point_usage;
                $point_transaction->save();
                
                /** send success email to payer */
                Mail::send('emails.point.payment_success', array('model' => $model), function($message) use ($model) {
                    $message->to($model->user->email, $model->user->full_name)->subject('Payment Success');
                });
                
                return Redirect::to('myPoint')->with('success', trans('app.successes.payment_successfully'));

            }
        } else {

            $model = Orders::where('reference_id',$item->moduleable->id)->first();
            
            if($item->status == PaymentTransaction::FAIL) {
                $model->status = Orders::REJECTED;
                $model->save();

                $summon = Summon::find($model->reference_id);
                if ($summon) {
                    $summon->status = Summon::REJECTED;
                    $summon->save();
                }

                /** send rejected email to payer */

                Mail::send('emails.summon.payment_fail', array('model' => $model), function($message) use ($model) {
                    $message->to($model->user->email, $model->user->full_name)->subject('Payment Fail');
                });

                return Redirect::to('summon')->with('error', trans('app.errors.payment_failed'));
            } else {
                
                if ($model->status == Summon::REJECTED || $model->status == Summon::PENDING) {
                    $summon = Summon::find($model->reference_id);
                    if ($summon) {
                        $summon->status = Summon::PENDING;
                        $summon->save();
                    }
                    $model->status = Orders::PENDING;
                    $model->save();

                    Mail::send('emails.summon.payment_success', array('model' => $model), function($message) use ($model) {
                        $message->to($model->user->email, $model->user->full_name)->subject('Payment Success');
                    });

                    /** Send to lawyer or jmb */
                    if($summon->type == Summon::LETTER_OF_REMINDER) {
                        /** to JMB */
                        $jmbs = User::where('company_id', $summon->company_id)->whereIn('role', [Role::COB, Role::COB_MANAGER])->get();
                        
                        foreach($jmbs as $val) {
                            // Mail::send('emails.summon.success_to_jmb_lawyer', array('model' => $val, 'order' => $model), function($message) use ($val) {
                            //     $message->to($val->email, $val->full_name)->subject('Payment Success');
                            // });

                        }
                    } else {
                        /** to Lawyer */
                        $user = User::find($summon->lawyer_id);
                        Mail::send('emails.summon.success_to_jmb_lawyer', array('model' => $user, 'order' => $model), function($message) use ($user) {
                            $message->to($user->email, $user->full_name)->subject('Payment Success');
                        });
                    }
                }

                return Redirect::to('summon')->with('success', trans('app.successes.payment_successfully'));

            }
        }

    }

    /**
     * Paydibs Process
     */
    public function processPaydibs($orderId, $item) {
            /** Generate reference no */
            // $reference_no = (empty($data['reference_no']) == false)? $data['reference_no'] : (new Hashids())->encode($item->id,100);
            $reference_no = date('YmdHis') . $orderId;

            /** Create Array Data for Payment Gateway */
            $user = User::find($item->moduleable->user_id);
            $payment_gateway_data['payment_id'] = $reference_no;
            $payment_gateway_data['order_id'] = $orderId;
            $payment_gateway_data['description'] = $item->description;
            $payment_gateway_data['amount'] = $item->amount;
            $payment_gateway_data['redirect_url'] = url('transaction/success');
            $payment_gateway_data['customer_ip'] = $item->cust_ip;
            $payment_gateway_data['customer_name'] = $user->full_name;
            $payment_gateway_data['customer_email'] = $user->email;
            $payment_gateway_data['customer_phone'] = $user->phone_no;
            $payment_gateway_data['callback_url'] = url('transaction/success');
            /** Payment Method : OB (online banking), CC (credit card), WA (ewallet) */
            $payment_gateway_data['payment_method'] = $item->payment_method;
            
            /** Update Payment Transaction info */
            $item->reference_no = $reference_no;
            $item->sign = (new Paydibs())->generateSign($payment_gateway_data, $item->transaction_type);
    
            $item->save();
    
            $payment_gateway_data['sign'] = $item->sign;

            $response = (new Paydibs())->payRequest($payment_gateway_data);

            return $response;
    }

    /**
     * Process Revenue payment
     */
    public function processRevenueMonster($orderId, $item) {
        $reference_no = date('YmdHis') . $orderId;
        $data['transaction_id'] = $reference_no;
        $data['amount'] = round($item->amount,2);
        // $data['transaction_id'] = date('YmdHis') . '123';
        // $data['amount'] = round('1.00',2);
        $data['redirect_url'] = url('transaction/success');
        $data['notify_url'] = url('revenue/transaction/success');
        
        $item->reference_no = $reference_no;
        $item->save();
        
        $response = (new Revenue())->paymentOnline($data);
        
        return $response;
    }

    public function revenueSuccess() {
        return 'done';
    }

    public function getRevenueTransactionStatus($orderId) {
        // public function getRevenueTransactionStatus() {
        // $response = (new Revenue())->getStatusByOrderID('2021051117364685');
        $response = (new Revenue())->getStatusByOrderID($orderId);
        
        return ($response);
    }
}