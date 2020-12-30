@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ url('myPoint/submitPay') }}">
                <input type="hidden" name="order_id" value="{{ $model->id }}"/>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">{{ trans('app.my_point.amount') }}</label>
                            <input type="text" class="form-control" name="amount" id="amount" value="{{ $model->amount }}" readonly=""/>
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

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submit_button">{{ trans('app.my_point.pay_now') }}</button>
                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('myPoint.index') }}'">{{ trans('app.forms.cancel') }}</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection