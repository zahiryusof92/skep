@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        @include('alert.bootbox')

                        <form id="minute-form" class="form-horizontal" method="POST" action="{{ route('agm-minute.store') }}">

                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.type_jmb_mc') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select id="type" name="type" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>   
                                        <option value="jmb" {{ Input::old('type') == 'jmb' ? 'selected' : '' }}>{{ strtoupper(trans('jmb')) }}</option>
                                        <option value="mc" {{ Input::old('type') == 'mc' ? 'selected' : '' }}>{{ strtoupper(trans('mc')) }}</option>
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
                                        <option value="agm" {{ Input::old('agm_type') == 'agm' ? 'selected' : '' }}>{{ strtoupper(trans('agm')) }}</option>
                                        <option value="egm" {{ Input::old('agm_type') == 'egm' ? 'selected' : '' }}>{{ strtoupper(trans('egm')) }}</option>
                                    </select>
                                    @include('alert.feedback-ajax', ['field' => 'agm_type'])
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.file_no') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('file_no') ? 'has-danger' : '' }}">
                                        <select id="file_no" name="file_no" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach($fileList as $file)
                                            <option value="{{ \Helper\Helper::encode(Config::get('constant.module.cob.file.name'), $file->id) }}" {{ Input::old('file_no') == $file->id ? 'selected' : '' }}>{{ $file->file_no }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'file_no'])
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.agm_date') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('agm_date') ? 'has-danger' : '' }}">
                                        <label class="input-group">
                                            <input type="text" class="form-control datepicker-only-init" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date_raw" name="agm_date" value="{{ Input::old('agm_date') }}"/>
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        @include('alert.feedback-ajax', ['field' => 'agm_date'])
                                    </div>
                                </div>
                            </div>

                            <div id="form_container">
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('remarks') ? 'has-danger' : '' }}">
                                        <textarea class="form-control" rows="4" placeholder="{{ trans('app.forms.remarks') }}" id="remarks" name="remarks">{{ Input::old('remarks') }}</textarea>
                                        @include('alert.feedback', ['field' => 'remarks'])
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                @if (AccessGroup::hasInsert(32))
                                <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.save') }}</button>
                                @endif
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('agm-minute.index') }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>
<script>
    $(function() {
        $('.datepicker-only-init').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'YYYY-MM-DD'
        }).on('dp.change', function() {
            let id = this.getAttribute('id');
            id = id.replace("_raw", "");
            let currentDate = $(this).val().split('-');
            $(id).val(`${currentDate[0]}-${currentDate[1]}-${currentDate[2]}`);
        });
        $('#agm_type').change(function() {
            getForm();
        });
        $('#type').change(function() {
            getForm();
        });
        $("#minute-form").submit(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            let formData = $(this).serializeArray();
            $.ajax({
                url: "{{ route('agm-minute.store') }}",
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                    $.each(formData, function (key, value) {
                        if(value['name'].includes('question')) {
                            $("#" + value['name'] + "_file_url_error").children("strong").text("");
                        } else {
                            $("#" + value['name'] + "_error").children("strong").text("");
                        }
                    });
                },
                success: function (res) {
                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.minutes.store') }}</span>", function () {
                            window.location = "{{ route('agm-minute.index') }}";
                        });
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                $("#" + key + "_error").children("strong").text(value);
                            });
                        }
                        
                        if(res.message != "Validation Fail") {
                            bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
                        } else {
                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.validation_fail') }}</span>");
                        }
                    }
                },
                complete: function() {
                    $.unblockUI();
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                },
            });
        });
    });

    function getForm() {
        $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
        $.ajax({
            url: "{{ route('agm-minute.getForm') }}",
            type: "POST",
            data: {
                type: $('#type').val(),
                agm_type: $('#agm_type').val(),
            },
            success: function (result) {
                $.unblockUI();
                if (result) {
                    $('#form_container').html(result);
                } else {
                    $("#form_container").html('');
                }
            }
        });
    }
</script>
@endsection