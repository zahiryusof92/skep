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

                        <form id="minute-form" class="form-horizontal" method="POST" action="{{ route('strata-meeting-document.update', Helper\Helper::encode(Config::get('constant.module.agm.strata_meeting_document.name'), $model->id)) }}">
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.strata_meeting_timing') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select id="type" name="type" class="form-control select2">
                                        <option value="1" {{ (int) $model->type === 1 ? 'selected' : '' }}>{{ trans('app.forms.first_agm') }}</option>
                                        <option value="2" {{ (int) $model->type === 2 ? 'selected' : '' }}>{{ trans('app.forms.strata_meeting_subsequent_agm') }}</option>
                                    </select>
                                    @include('alert.feedback-ajax', ['field' => 'type'])
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.agm_type') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select id="agm_type" name="agm_type" class="form-control select2">
                                        <option value="1" {{ (int) $model->agm_type === 1 ? 'selected' : '' }}>AGM</option>
                                        <option value="2" {{ (int) $model->agm_type === 2 ? 'selected' : '' }}>EGM</option>
                                    </select>
                                    @include('alert.feedback-ajax', ['field' => 'agm_type'])
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.file_no') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select id="file_no" name="file_no" class="form-control select2">
                                        @foreach ($fileList as $file)
                                        <option value="{{ $file->id }}" {{ $model->file_id == $file->id ? 'selected' : '' }}>
                                            {{ $file->file_no }}@if($file->strata && $file->strata->name) — {{ Str::limit($file->strata->name, 40) }}@endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @include('alert.feedback-ajax', ['field' => 'file_no'])
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.agm_date') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-group">
                                        <input type="text" class="form-control datepicker-only-init" name="agm_date" id="agm_date_raw" value="{{ $model->agm_date }}" autocomplete="off" placeholder="{{ trans('app.forms.agm_date') }}"/>
                                        <span class="input-group-addon"><i class="icmn-calendar"></i></span>
                                    </label>
                                    @include('alert.feedback-ajax', ['field' => 'agm_date'])
                                </div>
                            </div>

                            <div id="form_container">
                                @include('strata-meeting-document.form', array('grouped' => $grouped, 'model' => $model))
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="remarks" rows="4">{{ $model->remarks }}</textarea>
                                </div>
                            </div>

                            @if ($endorse)
                                @include('strata-meeting-document.partials.endorsement', array('documentStatus' => $documentStatus))
                            @else
                                @include('strata-meeting-document.partials.endorsement-readonly', array('documentStatus' => $documentStatus))
                            @endif

                            <div class="form-actions">
                                @if (AccessGroup::hasUpdate(32))
                                <button type="submit" class="btn btn-own">{{ trans('app.forms.save') }}</button>
                                @endif
                                <button type="button" class="btn btn-default" onclick="window.location='{{ route('strata-meeting-document.index') }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
<script>
    $(function () {
        $('.select2').select2({ width: '100%' });
        $('#agm_date_raw').datetimepicker({
            widgetPositioning: { horizontal: 'left' },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right"
            },
            format: 'YYYY-MM-DD'
        });
        $('#agm_date_raw').closest('.input-group').find('.input-group-addon').on('click', function () {
            $('#agm_date_raw').data('DateTimePicker').show();
        });
        $('#type, #agm_type').change(function () {
            $.blockUI({ message: '{{ trans("app.confirmation.please_wait") }}' });
            $.post("{{ route('strata-meeting-document.getForm') }}", {
                type: $('#type').val(),
                agm_type: $('#agm_type').val(),
                id: "{{ Helper\Helper::encode(Config::get('constant.module.agm.strata_meeting_document.name'), $model->id) }}"
            }, function (html) {
                $.unblockUI();
                $('#form_container').html(html);
            }).fail(function () { $.unblockUI(); });
        });
        $('#minute-form').submit(function (e) {
            e.preventDefault();
            $.blockUI({ message: '{{ trans("app.confirmation.please_wait") }}' });
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serializeArray(),
                success: function (res) {
                    if (res.success) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.updated_successfully') }}</span>", function () {
                            window.location.reload();
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>" + (res.message || '{{ trans("app.errors.validation_fail") }}') + "</span>");
                    }
                },
                complete: function () { $.unblockUI(); }
            });
        });
    });
</script>
@endsection
