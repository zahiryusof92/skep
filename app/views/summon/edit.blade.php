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

                    <form class="form-horizontal" method="POST" action="{{ route('summon.update', $model->id) }}" enctype="multipart/form-data" novalidate="">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.category') }}</label>
                                    <input type="text" class="form-control" value="{{ $category }}" readonly=""/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.summon.unit_no') }}</label>
                                    <input type="text" class="form-control" value="{{ $model->unit_no }}" readonly=""/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.name') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.summon.name') }}" id="name" name="name" value="{{ Input::old('name') ? Input::old('name') : $model->name }}"/>
                                    @include('alert.feedback', ['field' => 'name'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('ic_no') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.ic_no') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.summon.ic_no') }}" id="ic_no" name="ic_no" value="{{ Input::old('ic_no') ? Input::old('ic_no') : $model->ic_no }}"/>
                                    @include('alert.feedback', ['field' => 'ic_no'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('phone_no') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.phone_no') }}</label>
                                    <input type="tel" class="form-control" placeholder="{{ trans('app.summon.phone_no') }}" id="phone_no" name="phone_no" value="{{ Input::old('phone_no') ? Input::old('phone_no') : $model->phone_no }}"/>
                                    @include('alert.feedback', ['field' => 'phone_no'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('email') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.email') }}</label>
                                    <input type="email" class="form-control" placeholder="{{ trans('app.summon.email') }}" id="email" name="email" value="{{ Input::old('email') ? Input::old('email') : $model->email }}"/>
                                    @include('alert.feedback', ['field' => 'email'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('address') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.address') }}</label>
                                    <textarea class="form-control" placeholder="{{ trans('app.summon.address') }}" id="address" name="address" rows="3">{{ Input::old('address') ? Input::old('address') : $model->address }}</textarea>
                                    @include('alert.feedback', ['field' => 'address'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('mailing_address') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.mailing_address') }}</label>
                                    <textarea class="form-control" placeholder="{{ trans('app.summon.mailing_address') }}" id="mailing_address" name="mailing_address" rows="3">{{ Input::old('mailing_address') ? Input::old('mailing_address') : $model->mailing_address }}</textarea>
                                    @include('alert.feedback', ['field' => 'mailing_address'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('duration_overdue') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.duration_overdue') }}</label>
                                    <select class="form-control select2" id="duration_overdue" name="duration_overdue">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @if ($durationOverdue)
                                        @foreach ($durationOverdue as $value => $name)
                                        <option value="{{ $value }}" {{ ($model->duration_overdue == $value ? 'selected' : '') }}>{{ $name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @include('alert.feedback', ['field' => 'duration_overdue'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group {{ $errors->has('total_overdue') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.total_overdue') }}</label>
                                    <input type="number" class="form-control" placeholder="0" id="total_overdue" name="total_overdue" value="{{ Input::old('total_overdue') ? Input::old('total_overdue') : $model->total_overdue }}" min="1">
                                    @include('alert.feedback', ['field' => 'total_overdue'])
                                </div>
                            </div>
                        </div>
                        
                        @if ($model->type == Summon::LETTER_OF_DEMAND)
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('lawyer') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.lawyer') }}</label>
                                    <select class="form-control select2" id="lawyer" name="lawyer">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @if ($lawyer)
                                        @foreach ($lawyer as $lawyers)
                                        <option value="{{ $lawyers->id }}" {{ ((Input::old('lawyer') && Input::old('lawyer') == $lawyers->id) || $model->lawyer_id == $lawyers->id) ? 'selected' : '' }}>{{ $lawyers->full_name }} ({{ $lawyers->username }})</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @include('alert.feedback', ['field' => 'lawyer'])
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ ($errors->has('attachment') || $errors->has('attachment.*')) ? 'has-danger' : '' }}">
                                    <label class="form-control-label">{{ trans('app.summon.attachment') }}</label>
                                    <small class="text-help muted">{{ trans('app.summon.attachment_help') }}</small>
                                    <input type="file" class="form-control-file" id="attachment" name="attachment[]" multiple="multiple" {{ (!empty($model->attachment) && count(json_decode($model->attachment)) >= 3) ? 'disabled' : '' }}>
                                    @include('alert.feedback', ['field' => 'attachment'])
                                </div>

                                @if ($model->attachment)
                                @foreach (json_decode($model->attachment) as $attachment)
                                <a href="{{ asset($attachment) }}"><i class="fa fa-file-pdf-o margin-inline"></i>{{ str_replace('attachment/', '', $attachment) }}</a><br/>
                                @endforeach
                                @endif

                            </div>
                        </div>

                        <div class="form-actions">
                            <input type="hidden" id="buyer" name="buyer"/>
                            <input type="hidden" id="type" name="type" value="{{ $type }}"/>
                            <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.submit') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ route('summon.index') }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<script>
    $(document).on("change", "#unit_no", function () {
        var id = $(this).val();

        $.ajax({
            type: "POST",
            url: "{{ url('summon/purchaser') }}",
            data: {
                "id": id
            },
            dataType: "json",
            success: function (result) {
//                console.log(result);
                $("#buyer").val(result.id);
                $("#name").val(result.name);
                $("#ic_no").val(result.ic_no);
                $("#phone_no").val(result.phone_no);
                $("#email").val(result.email);
                $("#address").val(result.address);
                $("#mailing_address").val(result.mailing_address);
            }
        });
    });
</script>
@endsection