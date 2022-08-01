@extends('layout.english_layout.default_custom')

@section('content')

<?php
$insert_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 46) {
        $insert_permission = $permissions->insert_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <form class="form-horizontal" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.file_no') }}</label>
                                        <select id="file_id" name="file_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'file_id'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> {{ trans('app.forms.strata') }}</label>
                                        <select id="strata_id" name="strata_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'strata_id'])
                                    </div>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.insurance_provider') }}</label>
                                        <select id="insurance_provider" class="form-control select2" name="insurance_provider">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($insuranceProvider as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'insurance_provider'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.public_liability_coverage') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.public_liability_coverage') }}" id="public_liability_coverage" name="public_liability_coverage"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.premium_per_year') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.premium_per_year') }}" id="plc_premium_per_year" name="plc_premium_per_year"/>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.validity') }}</label>
                                        <label class="input-group">
                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.from") }}" id="plc_validity_from_raw"/>
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="plc_validity_from" name="plc_validity_from">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label">&nbsp;</label>
                                        <label class="input-group">
                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.to") }}" id="plc_validity_to_raw"/>
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="plc_validity_to" name="plc_validity_to">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.fire_insurance_coverage') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.fire_insurance_coverage') }}" id="fire_insurance_coverage" name="fire_insurance_coverage"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.premium_per_year') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.premium_per_year') }}" id="fic_premium_per_year" name="fic_premium_per_year"/>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.validity') }}</label>
                                        <label class="input-group">
                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.from") }}" id="fic_validity_from_raw"/>
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="fic_validity_from" name="fic_validity_from">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label">&nbsp;</label>
                                        <label class="input-group">
                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.to") }}" id="fic_validity_to_raw"/>
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="fic_validity_to" name="fic_validity_to">
                                    </div>
                                </div>
                            </div>
                        </form>

                            <form id="upload_insurance_file" enctype="multipart/form-data" method="post" action="{{ route('cob.file.insurance.file.upload') }}" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">{{ trans('app.forms.upload_file') }}</label>
                                            <br/>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <button type="button" id="clear_insurance_file" class="btn btn-xs btn-danger" onclick="clearFile()" style="display: none;"><i class="fa fa-times"></i></button>
                                            &nbsp;<input type="file" name="insurance_file" id="insurance_file" />
                                            <div id="validation-errors_insurance_file"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('app.forms.remarks') }}</label>
                                        <textarea id="remarks" name="remarks" rows="5" class="form-control" placeholder="{{ trans('app.forms.remarks') }}"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <?php if ($insert_permission) { ?>
                                    <input type="hidden" id="filename" name="filename" value=""/>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="submitAddInsurance()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@insurance', ['All']) }}'">{{ trans('app.forms.cancel') }}</button>
                                <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        //upload
        var options = {
            beforeSubmit: showRequest,
            success: showResponse,
            dataType: 'json'
        };

        $('body').delegate('#insurance_file', 'change', function () {
            $('#upload_insurance_file').ajaxForm(options).submit();
        });
        $('.select2').select2();
        $("#strata_id").select2({
            ajax: {
                url: "{{ route('v3.api.strata.getOption') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                cache: true,
                allowClear: true,
                data: function(params) {
                    
                    return {
                        term: params.term, // search term
                        file_id: $('#file_id').val(),
                        type: 'id',
                    };
                },
                processResults: function(response) {
                    return {
                        results: response.results
                    };
                }
            }
        });
        $("#file_id").select2({
            ajax: {
                url: "{{ route('v3.api.files.getOption') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                cache: true,
                allowClear: true,
                data: function(params) {
                    return {
                        term: params.term, // search term
                        strata: $('#strata_id').val(),
                        type: 'encode_id',
                    };
                },
                processResults: function(response) {
                    return {
                        results: response.results
                    };
                }
            }
        });
    });

    //upload document file
    function showRequest(formData, jqForm, options) {
        $("#validation-errors_insurance_file").hide().empty();
        return true;
    }
    function showResponse(response, statusText, xhr, $form) {
            console.log('aa');
        if (response.success == false) {
            var arr = response.errors;
            $.each(arr, function (index, value) {
                if (value.length != 0) {
                    $("#validation-errors_insurance_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_insurance_file").show();
            $("#insurance_file").css("color", "red");
        } else {
            $("#clear_insurance_file").show();
            $("#validation-errors_insurance_file").html("<i class='fa fa-check' id='check_insurance_file' style='color:green;'></i>");
            $("#validation-errors_insurance_file").show();
            $("#insurance_file").css("color", "green");
            $("#filename").val(response.file);
        }
    }

    function clearFile() {
        $("#insurance_file").val("");
        $("#clear_insurance_file").hide();
        $("#insurance_file").css("color", "grey");
        $("#check_insurance_file").hide();
    }
    
    $(function () {
        $("#plc_validity_from_raw").datetimepicker({
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
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#plc_validity_from").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $("#plc_validity_to_raw").datetimepicker({
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
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#plc_validity_to").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $("#fic_validity_from_raw").datetimepicker({
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
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#fic_validity_from").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $("#fic_validity_to_raw").datetimepicker({
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
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#fic_validity_to").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });

    function submitAddInsurance() {
        let formData = $('form').serializeArray();
        $.ajax({
            url: "{{ URL::action('AdminController@submitAddInsurance') }}",
            type: "POST",
            data: formData,
            beforeSend: function() {
                $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                $("#loading").css("display", "inline-block");
                $("#submit_button").attr("disabled", "disabled");
                $("#cancel_button").attr("disabled", "disabled");
                $.each(formData, function (key, value) {
                    $("#" + value['name'] + "_error").children("strong").text("");
                });
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>", function () {
                        window.location = '{{URL::action("AdminController@insurance", ["All"]) }}';
                    });
                }
            },
            error: function (err) {
                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                if(err.responseJSON.errors) {
                    $.each(err.responseJSON.errors, function (key, value) {
                        $("#" + key + "_error").children("strong").text(value);
                    });
                }
            },
            complete: function() {
                $.unblockUI();
                $("#loading").css("display", "none");
                $("#submit_button").removeAttr("disabled");
                $("#cancel_button").removeAttr("disabled");
            },
        });
    }
</script>
<!-- End Page Scripts-->

@stop
