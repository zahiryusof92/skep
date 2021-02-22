@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">

                    @include('alert.bootbox')

                    <form class="form-horizontal" method="POST" action="{{ route('pointPackage.store') }}">

                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.point_package.name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('app.point_package.name') }}" value="{{ Input::old('name') }}">
                                    @include('alert.feedback', ['field' => 'name'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('points') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.point_package.points') }}</label>
                                    <input type="text" class="form-control" id="points" name="points" placeholder="{{ trans('app.point_package.points') }}" value="{{ Input::old('points') }}">
                                    @include('alert.feedback', ['field' => 'points'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('price') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.point_package.price') }}</label>
                                    <input type="text" class="form-control" id="price" name="price" placeholder="{{ trans('app.point_package.price') }}" value="{{ Input::old('price') }}">
                                    @include('alert.feedback', ['field' => 'price'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('is_active') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.status') }}</label>
                                    <select id="is_active" name="is_active" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" {{ Input::old('is_active') == '1' ? 'selected' : '' }}>{{ trans('app.forms.active') }}</option>
                                        <option value="0" {{ Input::old('is_active') == '0' ? 'selected' : '' }}>{{ trans('app.forms.inactive') }}</option>
                                    </select>
                                    @include('alert.feedback', ['field' => 'is_active'])
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submit_button">{{ trans('app.forms.save') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('pointPackage.index') }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>
@endsection