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

                            <form class="form-horizontal" method="POST"
                                action="{{ route('complaintCategory.update', \Helper\Helper::encode($model->id)) }}">
                                <input type="hidden" name="_method" value="PUT">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="form-control-label">
                                            <span style="color: red;">*
                                                {{ trans('app.forms.mandatory_fields') }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.name') }}
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                value="{{ Input::old('name') ? Input::old('name') : $model->name }}" />
                                            @include('alert.feedback', ['field' => 'name'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="form-group {{ $errors->has('description') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.description') }}
                                            </label>
                                            <textarea id="description" name="description" class="form-control" rows="5">{{ Input::old('description') ? Input::old('description') : $model->description }}</textarea>
                                            @include('alert.feedback', ['field' => 'description'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group {{ $errors->has('color_code') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.color_code') }}
                                            </label>
                                            <input type="text" id="color_code" name="color_code" class="form-control"
                                                value="{{ Input::old('color_code') ? Input::old('color_code') : $model->color_code }}" />
                                            @include('alert.feedback', ['field' => 'color_code'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group {{ $errors->has('sort_no') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                {{ trans('app.forms.sort_no') }}
                                            </label>
                                            <input type="number" id="sort_no" name="sort_no" class="form-control"
                                                value="{{ Input::old('sort_no') ? Input::old('sort_no') : $model->sort_no }}" />
                                            @include('alert.feedback', ['field' => 'sort_no'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group {{ $errors->has('is_active') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.is_active') }}
                                            </label>
                                            <select id="is_active" name="is_active" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="1"
                                                    {{ Input::old('is_active') == '1' || $model->is_active == '1' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.yes') }}
                                                </option>
                                                <option value="0"
                                                    {{ Input::old('is_active') == '0' || $model->is_active == '0' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.no') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'is_active'])
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    @if (AccessGroup::hasInsertModule('Complaint Category'))
                                        <button type="submit" class="btn btn-own" id="submit_button">
                                            {{ trans('app.forms.save') }}
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-default" id="cancel_button"
                                        onclick="window.location ='{{ route('complaintCategory.index') }}'">
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
