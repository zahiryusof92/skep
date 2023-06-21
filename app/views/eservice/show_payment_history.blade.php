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
                        <dl class="row">
                            <dt class="col-sm-4">
                                {{ trans('app.forms.created_at') }}
                            </dt>
                            <dd class="col-sm-8">
                                {{ $transaction->payment_created_at }}
                            </dd>

                            <dt class="col-sm-4">
                                {{ trans('app.forms.order_no') }}
                            </dt>
                            <dd class="col-sm-8">
                                #{{ ($transaction->order ? $transaction->order->order_no : '-') }}
                            </dd>

                            <dt class="col-sm-4">
                                {{ trans('app.forms.payment_method') }}
                            </dt>
                            <dd class="col-sm-8">
                                @if (!empty($transaction->payment_method))
                                @if ($transaction->payment_method == 'cc')
                                {{ trans('Credit Card') }}
                                @else
                                {{ strtoupper($transaction->payment_method) }}
                                @endif
                                @else
                                {{ trans('-') }}
                                @endif
                            </dd>

                            <dt class="col-sm-4">
                                {{ trans('app.forms.amount') }} (RM)
                            </dt>
                            <dd class="col-sm-8">
                                {{ number_format($transaction->payment_amount, 2) }}
                            </dd>

                            <dt class="col-sm-4">
                                {{ trans('app.forms.status') }}
                            </dt>
                            <dd class="col-sm-8">
                                {{ $transaction->getStatusBadge() }}
                            </dd>

                            @if ($transaction->status != EServiceOrderTransaction::APPROVED)
                            <dt class="col-sm-4">
                                {{ trans('app.forms.message') }}
                            </dt>
                            <dd class="col-sm-8">
                                {{ (isset(json_decode($transaction->payment_response, true)['pg_desc']) ?
                                json_decode($transaction->payment_response, true)['pg_desc'] : '-') }}
                            </dd>
                            @endif

                        </dl>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-default" id="cancel_button"
                        onclick="window.location ='{{ route('eservice.paymentHistory') }}'">
                        {{ trans('app.forms.cancel') }}
                    </button>
                </div>

            </section>
        </div>
    </section>
</div>
@endsection