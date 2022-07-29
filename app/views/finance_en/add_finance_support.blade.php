@extends('layout.english_layout.default_custom')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 39) {
        $access_permission = $permission->access_permission;
        $insert_permission = $permission->insert_permission;
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
                        <form id="add_finance_support" class="form-horizontal" name="add_fileprefix">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.file_no') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select id="file_id" name="file_id" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                    </select>
                                    @include('alert.feedback-ajax', ['field' => 'file_id'])
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label>{{ trans('app.forms.strata') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select id="strata_id" name="strata_id" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                    </select>
                                    @include('alert.feedback-ajax', ['field' => 'strata_id'])
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input id="name" name="name" class="form-control" type="text">
                                    @include('alert.feedback-ajax', ['field' => 'name'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label><span style="color: red;">*</span> {{ trans("app.forms.date") }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input id="mirror_date" class="form-control" type="text">
                                    <input type="hidden" id="date" name="date">
                                    @include('alert.feedback-ajax', ['field' => 'date'])
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label><span style="color: red;">*</span> {{ trans("app.forms.amount") }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input id="amount" name="amount" class="form-control" placeholder="0.00" type="text">
                                    @include('alert.feedback-ajax', ['field' => 'amount'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label><span style="color: red;">*</span> {{ trans("app.forms.remarks") }}</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea id="remark" name="remark" class="form-control" rows="5"></textarea>
                                    @include('alert.feedback-ajax', ['field' => 'remark'])
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($insert_permission == 1) { ?>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="submitFinanceSupport()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('FinanceController@financeSupport')}}'">{{ trans('app.forms.cancel') }}</button>
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
    $(function () {
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
                        strata: $('#strata_id').val()
                    };
                },
                processResults: function(response) {
                    return {
                        results: response.results
                    };
                }
            }
        });
        $('#mirror_date').datetimepicker({
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
            format: 'DD/MM/YYYY',
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('/');
            console.log(currentDate);
            $("#date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });

    function submitFinanceSupport() {
        let formData = $('form').serializeArray();
        $.ajax({
            url: "{{ URL::action('FinanceController@submitFinanceSupport') }}",
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
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.finance_file.store') }}</span>", function () {
                        window.location = '{{URL::action("FinanceController@financeSupport") }}';
                    });
                } else if (data.trim() == "file_already_exists") {
                    $("#file_already_exists_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.exist2", ["attribute"=>"file"]) }}</span>');
                    $("#file_already_exists_error").css("display", "block");
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

@stop
