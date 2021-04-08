@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">

            @include('alert.bootbox')

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <dl class="row">
                            <dt class="col-lg-3">
                                {{ trans('app.forms.category') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $category }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.unit_no') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->unit_no }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.name') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->name }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.ic_no') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->ic_no }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.phone_no') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->phone_no }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.email') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->email }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.address') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->address }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.mailing_address') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->mailing_address }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.duration_overdue') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $durationOverdue }}
                            </dd>
                            <dt class="col-lg-3">
                                {{ trans('app.summon.total_overdue') }}
                            </dt>
                            <dd class="col-lg-9">
                                RM {{ number_format($model->total_overdue, 2) }}
                            </dd>

                            @if ($model->type == Summon::LETTER_OF_DEMAND)
                            <dt class="col-lg-3">
                                {{ trans('app.summon.lawyer') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->lawyer->full_name }}
                            </dd>
                            @endif

                            @if ($attachment && array_key_exists(0, $attachment))
                            <dt class="col-lg-3">
                                {{ trans('app.summon.attachment1') }}
                            </dt>
                            <dd class="col-lg-9">
                                <a href="{{ asset($attachment[0]) }}"><i class="fa fa-file-pdf-o margin-inline"></i>{{ str_replace('attachment/' . $model->id . '/', '', $attachment[0]) }}</a><br/>
                            </dd>
                            @endif

                            @if ($attachment && array_key_exists(1, $attachment))
                            <dt class="col-lg-3">
                                {{ trans('app.summon.attachment2') }}
                            </dt>
                            <dd class="col-lg-9">
                                <a href="{{ asset($attachment[1]) }}"><i class="fa fa-file-pdf-o margin-inline"></i>{{ str_replace('attachment/' . $model->id . '/', '', $attachment[1]) }}</a><br/>
                            </dd>
                            @endif

                            @if ($attachment && array_key_exists(2, $attachment))
                            <dt class="col-lg-3">
                                {{ trans('app.summon.attachment3') }}
                            </dt>
                            <dd class="col-lg-9">
                                <a href="{{ asset($attachment[2]) }}"><i class="fa fa-file-pdf-o margin-inline"></i>{{ str_replace('attachment/' . $model->id . '/', '', $attachment[2]) }}</a><br/>
                            </dd>
                            @endif

                            <dt class="col-lg-3">
                                {{ trans('app.summon.created_at') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ ($model->created_at ? $model->created_at->format('d-M-Y H:i A') : '') }}
                            </dd>

                            <dt class="col-lg-3">
                                {{ trans('app.summon.status') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ $model->status() }}
                            </dd>

                            @if ($model->status == Summon::APPROVED || $model->status == Summon::REJECTED)
                            @if ($model->action_file)
                            <dt class="col-lg-3">
                                @if ($model->type == Summon::LETTER_OF_DEMAND)
                                {{ trans('app.summon.letter_of_demand') }}
                                @else
                                {{ trans('app.summon.letter_of_reminder') }}
                                @endif
                            </dt>
                            <dd class="col-lg-9">
                                <a href="{{ asset($model->action_file) }}"><i class="fa fa-file-pdf-o margin-inline"></i>{{ str_replace('attachment/' . $model->id . '/', '', $model->action_file) }}</a><br/>
                            </dd>
                            @endif

                            <dt class="col-lg-3">
                                {{ trans('app.summon.action_by') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ ($model->action_by ? $model->user->full_name : '') }}
                            </dd>

                            <dt class="col-lg-3">
                                {{ trans('app.summon.action_date') }}
                            </dt>
                            <dd class="col-lg-9">
                                {{ ($model->action_date ? date('d-M-Y H:i A', strtotime($model->action_date)) : '') }}
                            </dd>
                            @endif
                        </dl>

                        @if (Auth::user()->isLawyer() || Auth::user()->isCOBManager())                        
                        @if ($model->status == Summon::PENDING)                    
                        <form method="POST" action="{{ route('summon.update', $model->id) }}" enctype="multipart/form-data" novalidate="">
                            <input type="hidden" name="_method" value="PUT">

                            <h5>{{ trans('app.forms.action') }}</h5>
                            <dl class="row">
                                <dt class="col-lg-3">
                                    @if ($model->type == Summon::LETTER_OF_DEMAND)
                                    {{ trans('app.summon.upload_letter_of_demand') }}
                                    @else
                                    {{ trans('app.summon.upload_letter_of_reminder') }}
                                    @endif
                                </dt>
                                <dd class="col-lg-9">
                                    <input type="file" class="form-control-file" id="uploaded_file" name="uploaded_file">
                                    <small class="text-help muted">{{ trans('app.summon.attachment_help') }}</small><br/>
                                    @include('alert.feedback', ['field' => 'uploaded_file'])
                                </dd>
                                <dt class="col-lg-3">
                                    {{ trans('app.summon.status') }}
                                </dt>
                                <dd class="col-lg-4">
                                    <div class="form-group {{ $errors->has('status') ? 'has-danger' : '' }}">
                                        <select class="form-control" id="status" name="status">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            <option value="{{ Summon::APPROVED }}" {{ (Input::old('status') == Summon::APPROVED ? 'selected' : '') }}>{{ trans('app.summon.approved') }}</option>
                                            <option value="{{ Summon::REJECTED }}" {{ (Input::old('status') == Summon::REJECTED ? 'selected' : '') }}>{{ trans('app.summon.rejected') }}</option>
                                        </select>
                                        @include('alert.feedback', ['field' => 'status'])
                                    </div>
                                </dd>
                                <button type="submit" class="btn btn-own">
                                    {{ trans('app.forms.submit') }}
                                </button>
                            </dl>
                        </form>                    
                        @endif                    
                        @endif
                        </dl>

                        <div class="form-actions">
                            @if (Auth::user()->isJMB() && $model->status == Summon::DRAFT)
                            <form method="POST" action="{{ url('summon/orders') }}">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <p class="page-invoice-amount">
                                                <input type="hidden" name="summon" value="{{ $model->id }}"/>
                                                <input type="hidden" name="amount" value="{{ $cash }}"/>
                                                <strong>Amount to Pay</strong><br/>
                                                RM {{ $cash }}
                                                or
                                                {{ $amount }} points
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group {{ $errors->has('payment_method') ? 'has-danger' : '' }}">
                                            <label><strong>Pay With</strong></label>
                                            <select class="form-control" id="payment_method" name="payment_method">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                <option value="{{ Orders::BANK_TRANSFER }}" {{ (Input::old('status') == Orders::BANK_TRANSFER ? 'selected' : '') }}>{{ trans('app.summon.bank_transfer') }}</option>
                                                @if ($eligible_pay)
                                                <option value="{{ Orders::POINT }}" {{ (Input::old('status') == Orders::POINT ? 'selected' : '') }}>{{ trans('app.summon.points') }}</option>
                                                @endif
                                            </select>
                                            @include('alert.feedback', ['field' => 'payment_method'])
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-own">
                                            {{ trans('app.summon.payment') }}
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="window.location = '{{ route('summon.index') }}'">                                
                                            {{ trans('app.forms.back') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @else
                            <div class="pull-right">
                                <button type="button" class="btn btn-danger" onclick="window.location = '{{ route('summon.index') }}'">                                
                                    {{ trans('app.forms.back') }}
                                </button>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>
@endsection