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

                        <form action="{{ route('epksStatement.store') }}" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: red; font-style: italic;">
                                            * {{ trans('app.forms.mandatory_fields') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('file_id') ? 'has-danger' : '' }}">
                                        <label for="file_id">
                                            <span style="color: red;">* </span>
                                            {{ trans('app.forms.file_no') }}
                                        </label>
                                        <select id="file_id" name="file_id" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($file_no as $files)
                                            <option value="{{ $files->id }}" {{ (Input::old('file_id') == $files->id ? 'selected' : '') }}>{{ $files->file_no }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback', ['field' => 'file_id'])
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('year') ? 'has-danger' : '' }}">
                                        <label for="year">
                                            <span style="color: red;">* </span>
                                            {{ trans('app.forms.year') }}
                                        </label>
                                        <select id="year" name="year" class="form-control select2">
                                            @foreach ($year as $value => $years)
                                            <option value="{{ $value }}" {{ (Input::old('year') == $value ? 'selected' : '') }}>{{ $years }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback', ['field' => 'year'])
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('month') ? 'has-danger' : '' }}">
                                        <label for="month">
                                            <span style="color: red;">* </span>
                                            {{ trans('app.forms.month') }}
                                        </label>
                                        <select id="month" name="month" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($month as $value => $months)
                                            <option value="{{ $value }}" {{ (Input::old('month') == $value ? 'selected' : '') }}>{{ $months }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback', ['field' => 'month'])
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.submit') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('epksStatement.index') }}'">
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