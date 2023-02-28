@extends('layout.english_layout.default')

@section('content')
    <div class="page-content-inner">
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">
                <section class="panel panel-pad">
                    <div class="row padding-vertical-10">
                        <div class="col-lg-12">

                            @include('alert.bootbox')

                            <form class="form-horizontal" action="{{ url('submitExportCOBFile') }}" method="POST">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="form-control-label" style="color: red; font-style: italic;">
                                            * {{ trans('app.forms.mandatory_fields') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.cob') }}
                                            </label>
                                            <select id="company" name="company" class="form-control select2 {{ $errors->has('company') ? 'has-danger' : '' }}">
                                                @if (count($cob) > 1)
                                                    <option value="">
                                                        {{ trans('app.forms.please_select') }}
                                                    </option>
                                                @endif
                                                @foreach ($cob as $companies)
                                                    <option value="{{ $companies->id }}">
                                                        {{ $companies->name }} ({{ $companies->short_name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @include('alert.feedback', ['field' => 'company'])
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-own" id="submit_button">
                                        {{ trans('app.forms.submit') }}
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
