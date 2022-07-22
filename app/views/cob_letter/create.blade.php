
@extends('components.modal', ['modal_id' => 'cob-letter', 'modal_form_id' => 'cob-letter-form', 'show_submit' => true]) 

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
@if(Auth::user()->company_id > 1)
<input id="cob" name="cob" value="{{ Auth::user()->getCOB->short_name }}" hidden>
@else
<div class="form-group row">
    <div class="col-md-5">
        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.cob') }}</label>
    </div>
    <div class="col-md-7">
        <select id="cob" name="cob" class="form-control select2" data-ajax--url="{{ route('v3.api.company.getNameOption') }}" data-ajax--cache="true">
        </select>
        @include('alert.feedback-ajax', ['field' => 'cob'])
    </div>
</div>
@endif
<div class="form-group row">
    <div class="col-md-5">
        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.type') }}</label>
    </div>
    <div class="col-md-7">
        <select id="type" name="type" class="form-control select3">
        </select>
        @include('alert.feedback-ajax', ['field' => 'type'])
    </div>
</div>

<div id="form_field_container">
</div>
@endsection

@section('modal_script')
<script>
    $(document).ready( function () {
        $('.select2').select2();
        let cob = $('#cob').val();
        if(cob != '') {
            getTypeOptions(cob);
        }
    
        $('#cob').on('select2:select', function (e) {
            getTypeOptions(this.value);
            $("#form_field_container").html("");
            $("#type").val("").trigger('change');
        });
        $('#type').on('select2:select', function (e) {
            getFields(this.value);
        });

        $("#cob-letter-form").submit(function (e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            let route = "{{ route('cob_letter.store') }}";
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
                            $('#cob-letter').modal('hide');
                            $.notify({
                                message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                            }, {
                                type: 'success',
                                placement: {
                                    align: "center"
                                }
                            });
                            let location = "{{ route('cob_letter.show', ':id') }}";
                            location = location.replace(':id', res.id);
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

    function getTypeOptions(cob) {
        let route = "{{ route('v3.api.cob_letter.getTypeOptions') }}";
        route = route + '?cob=' + cob;
        $('#type').select2({
            ajax: {
                url: route,
                processResults: function (data, params) {
                    return {
                        results: data.results,
                    };
                }
            }
        });
    }

    function getFields(type) {
        let route = "{{ route('cob_letter.getForm') }}";
        $.ajax({
            url: route,
            type: "GET",
            data: {
                cob: $('#cob').val(),
                type: type
            },
            beforeSend: function() {
                $("#loading").css("display", "inline-block");
                $("#modal_submit_button").attr("disabled", "disabled");
                $("#modal_cancel_button").attr("disabled", "disabled");
            },
            success: function (result) {
                if (result) {
                    $("#form_field_container").html(result);
                } else {
                    $("#form_field_container").html("");
                }
            },
            complete: function() {
                $("#loading").css("display", "none");
                $("#modal_submit_button").removeAttr("disabled");
                $("#modal_cancel_button").removeAttr("disabled");
            },
        });
    }
</script>
@endsection