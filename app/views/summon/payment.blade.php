@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        
        <div class="panel-body">
            @if ($model->payment_method == Orders::POINT)
                @if (!$eligible_pay) 
                <p class="text-danger">
                    <strong>{{ trans('app.my_point.not_enough') }}</strong>
                    <br/>
                    <a href="{{ url('myPoint/reload') }}">{{ trans('app.my_point.reload_point') }}</a>
                </p>
                @endif

                <form method="POST" action="{{ url('summon/submitPay') }}">
                    <input type="hidden" name="order_id" value="{{ $model->id }}"/>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">{{ trans('app.summon.amount') }} ({{ trans('app.summon.points') }})</label>
                                <input type="text" class="form-control" name="amount" id="amount" value="{{ $total_amount }}" readonly=""/>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-own" id="submit_button" {{ (!$eligible_pay ? 'disabled' : '') }}>{{ trans('app.summon.pay_now') }}</button>
                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('summon.index') }}'">{{ trans('app.forms.cancel') }}</button>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ url('summon/submitPay') }}">
                    <input type="hidden" name="order_id" value="{{ $model->id }}"/>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">{{ trans('app.summon.amount') }}</label>
                                <input type="text" class="form-control" name="amount" id="amount" value="{{ $total_amount }}" readonly=""/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group {{ $errors->has('payment_gateway') ? 'has-danger' : '' }} row">
                                <div class="col-md-12">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.my_point.payment_gateway') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" id="payment_gateway" name="payment_gateway" value="paydibs"> 
                                    <img src="{{asset('assets/common/img/payment_gateway/paydibs.png')}}" width="150">
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" id="payment_gateway" name="payment_gateway" value="revenue">
                                    <img src="{{asset('assets/common/img/payment_gateway/rm.png')}}" width="150">
                                </div>
                                <div class="col-md-12">
                                    @include('alert.feedback', ['field' => 'payment_gateway'])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group {{ $errors->has('payment_method') ? 'has-danger' : '' }}">
                                <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.my_point.payment_method') }}</label>
                                <select id="payment_method" name="payment_method" class="form-control select2">
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    <option value="{{ Orders::FPX }}" {{ Input::old('payment_method') == Orders::FPX ? 'selected' : '' }}>{{ ucwords(Orders::FPX) }}</option>
                                    <option value="{{ Orders::CARD }}" {{ Input::old('payment_method') == Orders::CARD ? 'selected' : '' }}>{{ ucwords(Orders::CARD) }}</option>
                                </select>
                                @include('alert.feedback', ['field' => 'payment_method'])
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group {{ $errors->has('terms') ? 'has-danger' : '' }}">
                                <div class="checkbox">
                                    <span style="color: red;">* </span>
                                    <label>
                                        <input type="checkbox" value="1" name="terms" id="terms">
                                        Agree with the <a href="https://odesi.tech" target="_blank">terms and conditions</a>
                                    </label>
                                </div>
                                <br/>
                                @include('alert.feedback', ['field' => 'terms'])
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-own" id="submit_button" {{ (!$eligible_pay ? 'disabled' : '') }}>{{ trans('app.summon.pay_now') }}</button>
                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('summon.index') }}'">{{ trans('app.forms.cancel') }}</button>
                    </div>
                </form>
            @endif



        </div>
    </section>
</div>
@endsection