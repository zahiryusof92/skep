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
                                {{ trans('app.forms.duration') }} ({{ trans('Years') }})
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->duration }}
                            </dd>
                        </dl>

                        @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                        <div class="form-actions">
                            <button type="button" class="btn btn-default" id="back_button"
                                onclick="window.location ='{{ route('dlp.period') }}'">
                                {{ trans('app.forms.back') }}
                            </button>
                        </div>
                        @endif

                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection