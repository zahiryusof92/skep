@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        @include('alert.bootbox')

                        <form method="POST" action="{{ url('eservice/submitPayment') }}">
                            <dl class="row">
                                <dt class="col-sm-3">
                                    {{ trans('app.forms.order_no') }}
                                </dt>
                                <dd class="col-sm-9">
                                    #{{ $order->order_no }}
                                </dd>

                                <dt class="col-sm-3">
                                    {{ trans('app.forms.created_at') }}
                                </dt>
                                <dd class="col-sm-9">
                                    {{ $order->created_at }}
                                </dd>

                                <dt class="col-sm-3">
                                    {{ trans('app.forms.file_no') }}
                                </dt>
                                <dd class="col-sm-9">
                                    {{ $order->file->file_no }}
                                </dd>

                                <dt class="col-sm-3">
                                    {{ trans('app.forms.strata') }}
                                </dt>
                                <dd class="col-sm-9">
                                    {{ $order->strata->name }}
                                </dd>

                                <dt class="col-sm-3">
                                    {{ trans('app.forms.status') }}
                                </dt>
                                <dd class="col-sm-9">
                                    {{ $order->getStatusText() }}
                                </dd>

                                <dt class="col-sm-3">
                                    {{ trans('app.forms.amount') }} (RM)
                                </dt>
                                <dd class="col-sm-9">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control" name="amount" id="amount"
                                                value="{{ number_format($total_amount, 2) }}" readonly="" />
                                        </div>
                                    </div>
                                </dd>

                                <dt class="col-sm-3">
                                    &nbsp;
                                </dt>
                                <dd class="col-sm-9">
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <div class="{{ $errors->has('terms') ? 'has-danger' : '' }}">
                                                <div class="checkbox">
                                                    <span style="color: red;">* </span>
                                                    <label>
                                                        <input type="checkbox" value="1" name="terms" id="terms">
                                                        Agree with the <a href="https://odesi.tech"
                                                            target="_blank">terms and conditions</a>
                                                    </label>
                                                </div>
                                                <br />
                                                @include('alert.feedback', ['field' => 'terms'])
                                            </div>
                                        </div>
                                    </div>
                                </dd>
                            </dl>

                            <div class="form-actions">
                                <input type="hidden" name="order_id" value="{{ \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id) }}" />
                                <button type="submit" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.eservice.pay_now') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('eservice.draft') }}'">
                                    {{ trans('app.forms.cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

    </section>
</div>
@endsection