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

                    @if ($conversion)
                    <form class="form-horizontal" method="POST" action="{{ route('conversion.update', $conversion->id) }}">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('rate') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.conversion') }}</label>
                                    <input type="text" class="form-control number-only" id="rate" name="rate" placeholder="{{ trans('app.forms.rate') }}" value="{{ Input::old('rate') ? Input::old('rate') : $conversion->rate }}">
                                    @include('alert.feedback', ['field' => 'rate'])
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submit_button">{{ trans('app.forms.save') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('category.index') }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                    </form>
                    @else
                    <form class="form-horizontal" method="POST" action="{{ route('conversion.store') }}">

                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('rate') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.conversion') }}</label>
                                    <input type="text" class="form-control number-only" id="rate" name="rate" placeholder="{{ trans('app.forms.rate') }}" value="{{ Input::old('rate') }}">
                                    @include('alert.feedback', ['field' => 'rate'])
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submit_button">{{ trans('app.forms.save') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('category.index') }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<script>
    $('.number-only').keyup(function () {
    this.value = this.value.replace(/[^0-9\.]/g, '');
    });
</script>
@stop