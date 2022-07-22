
@extends('components.modal', ['modal_id' => 'client', 'modal_form_id' => 'client-form', 'show_submit' => true]) 

@section('modal_title')
{{ trans('app.forms.create') }}
@endsection

@section('modal_submit_text')
{{ trans('app.forms.submit') }}
@endsection

@section('modal_content')
<div class="form-group row">
    <div class="col-md-12" id="status-message"></div>
</div>
<div class="form-group row">
    <div class="col-md-5">
        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.name') }}</label>
    </div>
    <div class="col-md-7">
        <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="name" name="name" value="{{ Input::old('name') }}">
        @include('alert.feedback-ajax', ['field' => 'name'])
    </div>
</div>
<div class="form-group row">
    <div class="col-md-5">
        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.expired_date') }}</label>
    </div>
    <div class="col-md-7">
        <label class="input-group datepicker-only-init">
            <input type="text" class="form-control" placeholder="{{ trans('app.forms.expired_date') }}" id="expiry_date" name="expiry_date"/>
            <span class="input-group-addon">
                <i class="icmn-calendar"></i>
            </span>
        </label>
        @include('alert.feedback-ajax', ['field' => 'expiry_date'])
    </div>
</div>

@endsection

@section('modal_script')
<script>
    $(document).ready( function () {
        $('#expiry_date').datetimepicker({
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
        });

        $("#client-form").submit(function (e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            let route = "{{ route('clients.store') }}";
            $.ajax({
                url: route,
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $("#loading").css("display", "inline-block");
                    $("#modal_submit_button").attr("disabled", "disabled");
                    $("#modal_cancel_button").attr("disabled", "disabled");
                    $.each(formData, function (key, value) {
                        $("#" + value['name'] + "_error").children("strong").text("");
                    });
                },
                success: function (res) {
                    if (res.success == true) {
                            $('#client').modal('hide');
                            $.notify({
                                message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                            }, {
                                type: 'success',
                                placement: {
                                    align: "center"
                                }
                            });
                            location.reload();
                    }
                },
                error: function(xhr, status, error){
                    var err_arr = JSON.parse(xhr.responseText);
                    if(err_arr.errors !== undefined) {
                        $.each(err_arr.errors, function (key, value) {
                            $("#" + key + "_error").children("strong").text(value);
                        });
                    }
                    
                    if(err_arr.message != "Validation Fail") {
                        $('#status-message').html("<span style='color:red;'>" + err_arr.message + "</span>");
                    } else {
                        $('#status-message').html("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                },
                complete: function() {
                    $("#loading").css("display", "none");
                    $("#modal_submit_button").removeAttr("disabled");
                    $("#modal_cancel_button").removeAttr("disabled");
                },
            });
        });
    });
</script>
@endsection