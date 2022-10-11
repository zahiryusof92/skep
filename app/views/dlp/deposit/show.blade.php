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

                        <dl class="row">
                            <dt class="col-sm-3">
                                {{ trans('app.forms.cob') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->company ? $model->company->name : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.file_no') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->file ? $model->file->file_no : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.strata') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->strata ? $model->strata->name : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.type') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ucwords($model->type) }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.development_cost') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->development_cost }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.amount') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->amount }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.date_start') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->start_date }}
                            </dd>                         
                            <dt class="col-sm-3">
                                {{ trans('app.forms.maturity_date') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->maturity_date }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.attachment') }}
                            </dt>
                            <dd class="col-sm-9">
                                @if (!empty($model->attachment))
                                <a href="{{ asset($model->attachment) }}" target="_blank">
                                    <button type="button" class="btn btn-xs btn-success" data-toggle="tooltip"
                                        data-placement="bottom" title="{{ trans('app.forms.attachment') }}">
                                        <i class="fa fa-file-pdf-o" style="margin-right: 2px;"></i>
                                        {{ trans('app.forms.attachment') }}
                                    </button>
                                </a>
                                @else
                                -
                                @endif
                            </dd>
                        </dl>

                        <div class="form-actions">
                            <button type="button" class="btn btn-default" id="cancel_button"
                                onclick="window.location ='{{ route('dlp.deposit') }}'">
                                {{ trans('app.forms.cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection