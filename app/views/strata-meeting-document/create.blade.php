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

                        <form id="minute-form" class="form-horizontal" method="POST" action="{{ route('strata-meeting-document.store') }}">

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
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" {{ Input::old('type') == '1' ? 'selected' : '' }}>{{ trans('app.forms.first_agm') }}</option>
                                        <option value="2" {{ Input::old('type') == '2' ? 'selected' : '' }}>{{ trans('app.forms.strata_meeting_subsequent_agm') }}</option>
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
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" {{ Input::old('agm_type') == '1' ? 'selected' : '' }}>AGM</option>
                                        <option value="2" {{ Input::old('agm_type') == '2' ? 'selected' : '' }}>EGM</option>
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
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($fileList as $file)
                                            <option value="{{ $file->id }}" {{ Input::old('file_no') == $file->id ? 'selected' : '' }}>
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
                                        <input type="text" class="form-control datepicker-only-init" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date_raw" name="agm_date" value="{{ Input::old('agm_date') }}" autocomplete="off"/>
                                        <span class="input-group-addon">
                                            <i class="icmn-calendar"></i>
                                        </span>
                                    </label>
                                    @include('alert.feedback-ajax', ['field' => 'agm_date'])
                                </div>
                            </div>

                            <div id="form_container"></div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="4" placeholder="{{ trans('app.forms.remarks') }}" id="remarks" name="remarks">{{ Input::old('remarks') }}</textarea>
                                    @include('alert.feedback-ajax', ['field' => 'remarks'])
                                </div>
                            </div>

                            <div class="form-actions">
                                @if (AccessGroup::hasInsert(32))
                                <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.save') }}</button>
                                @endif
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('strata-meeting-document.index') }}'">{{ trans('app.forms.cancel') }}</button>
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
        $('.select2').select2({
            width: '100%',
            placeholder: "{{ trans('app.forms.please_select') }}",
            allowClear: true
        });

        $('#agm_date_raw').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
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

        $('#type, #agm_type').on('change', function () {
            getStrataForm();
        });

        $('#minute-form').submit(function (e) {
            e.preventDefault();
            $('.has-error').removeClass('has-error');
            $('[id$=_error] strong').text('');
            $.blockUI({ message: '{{ trans("app.confirmation.please_wait") }}' });
            $.ajax({
                url: "{{ route('strata-meeting-document.store') }}",
                type: 'POST',
                data: $(this).serializeArray(),
                success: function (res) {
                    if (res.success) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>", function () {
                            window.location = "{{ route('strata-meeting-document.index') }}";
                        });
                    } else {
                        if (res.errors) {
                            $.each(res.errors, function (key, value) {
                                $('#' + key + '_error').closest('.form-group').addClass('has-error');
                                $('#' + key + '_error strong').text(value);
                            });
                        }
                        bootbox.alert("<span style='color:red;'>" + (res.message || '{{ trans("app.errors.validation_fail") }}') + "</span>");
                    }
                },
                complete: function () { $.unblockUI(); }
            });
        });
    });

    function getStrataForm() {
        if (!$('#type').val() || !$('#agm_type').val()) {
            $('#form_container').html('');
            return;
        }
        $.blockUI({ message: '{{ trans("app.confirmation.please_wait") }}' });
        $.post("{{ route('strata-meeting-document.getForm') }}", {
            type: $('#type').val(),
            agm_type: $('#agm_type').val()
        }, function (html) {
            $.unblockUI();
            $('#form_container').html(html);
        }).fail(function () {
            $.unblockUI();
            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
        });
    }
</script>
@endsection
