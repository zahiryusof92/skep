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

                        <form class="form-horizontal" method="POST" action="{{ url('myPoint/orders') }}">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group {{ $errors->has('package') ? 'has-danger' : '' }}">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.my_point.reload_package') }}</label>
                                        <select id="package" name="package" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @if ($package)
                                            @foreach ($package as $reload)
                                            <option value="{{ $reload->id }}" {{ Input::old('package') == $reload->id ? 'selected' : '' }}>{{ $reload->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @include('alert.feedback', ['field' => 'package'])
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.my_point.reload_proceed') }}</button>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('myPoint.index') }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>
@endsection