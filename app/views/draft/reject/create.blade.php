
@extends('components.modal', ['modal_id' => 'file-reject', 'modal_form_id' => 'file-reject-form', 'show_submit' => true]) 

@section('modal_title')
{{ trans('app.forms.reject') }}
@endsection

@section('modal_submit_text')
{{ trans('app.forms.submit') }}
@endsection

@section('modal_content')
<div class="form-group row">
    <div class="col-md-121" id="status-message"></div>
</div>
<input id="file_id" name="file_id" value="{{ $file_id }}" hidden>
<input id="type" name="type" value="{{ $type }}" hidden>
<div class="form-group row">
    <div class="col-md-5">
        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.remarks') }}</label>
    </div>
    <div class="col-md-7">
        <textarea class="form-control" name="remarks" rows="3"></textarea>
        @include('alert.feedback-ajax', ['field' => 'remarks'])
    </div>
</div>
@endsection

@section('modal_script')
<script>
    $(document).ready( function () {
        $("#file-reject-form").submit(function (e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            let route = "{{ route('file.draft.reject.store') }}";
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
                            $('#file-reject').modal('hide');
                            $.notify({
                                message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.submit_successfully") }}</p>',
                            }, {
                                type: 'success',
                                placement: {
                                    align: "center"
                                }
                            });
                            let location = "{{ route('file.draft.reject.index') }}";
                            window.location = location;
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                $("#" + key + "_error").children("strong").text(value);
                            });
                        }
                        
                        if(res.message != "Validation Fail") {
                            $('#status-message').html("<span style='color:red;'>" + res.message + "</span>");
                        } else {
                            $('#status-message').html("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
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