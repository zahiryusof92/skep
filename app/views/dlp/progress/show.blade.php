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
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label">
                                    <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">
                                        <span style="color: red;">*</span>
                                        {{ trans('app.forms.date') }}
                                    </label>
                                    <input type="text" class="form-control" value="{{ $model->date }}"
                                        readonly />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">
                                        <span style="color: red;">*</span>
                                        {{ trans('app.forms.percentage') }} (%)
                                    </label>
                                    <input type="text" class="form-control" value="{{ $model->percentage }}"
                                        readonly />
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                        <div class="form-actions">
                            <button type="button" class="btn btn-default" id="cancel_button"
                                onclick="window.location ='{{ route('dlp.progress') }}'">
                                {{ trans('app.forms.cancel') }}
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