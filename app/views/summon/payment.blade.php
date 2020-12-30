@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            
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
                            <label class="form-control-label">{{ trans('app.summon.amount') }}</label>
                            <input type="text" class="form-control" name="amount" id="amount" value="{{ $model->amount }}" readonly=""/>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submit_button" {{ (!$eligible_pay ? 'disabled' : '') }}>{{ trans('app.summon.pay_now') }}</button>
                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('summon.index') }}'">{{ trans('app.forms.cancel') }}</button>
                </div>
            </form>
            
        </div>
    </section>
</div>
@endsection