
@extends('components.modal', ['modal_id' => 'file-reject', 'modal_form_id' => 'file-reject-form', 'show_submit' => false]) 

@section('modal_title')
{{ trans('app.forms.reject') }}
@endsection

@section('modal_content')
<div class="form-group row">
    <div class="col-md-5">
        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.remarks') }}</label>
    </div>
    <div class="col-md-7">
        <textarea class="form-control" name="remarks" rows="3" disabled>{{ $draft_reject->remarks }}</textarea>
        @include('alert.feedback-ajax', ['field' => 'remarks'])
    </div>
</div>
@endsection