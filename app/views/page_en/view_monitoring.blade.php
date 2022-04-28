@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 3) {
        $update_permission = $permission->update_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$file->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file', ['files' => $file, 'is_view' => true])

                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="monitoring" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <form id="monitoring">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h4>{{ trans('app.forms.detail') }}</h4>
                                                        <h6>1. {{ trans('app.forms.delivery_document_of_development_area') }}</h6>
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <label class="form-control-label">{{ trans('app.forms.pre_calculate_plan') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="precalculate_plan" name="precalculate_plan" value="1" {{($monitoring->pre_calculate == 1 ? " checked" : "")}} disabled>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="precalculate_plan" name="precalculate_plan" value="0" {{($monitoring->pre_calculate == 0 ? " checked" : "")}} disabled>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <label class="form-control-label">{{ trans('app.forms.buyer_registration') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="buyer_registration" name="buyer_registration" value="1" {{($monitoring->buyer_registration == 1 ? " checked" : "")}} disabled>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="buyer_registration" name="buyer_registration" value="0" {{($monitoring->buyer_registration == 0 ? " checked" : "")}} disabled>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <label class="form-control-label">{{ trans('app.forms.certificate_series_number') }}</label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="certificate_series_no" value="{{$monitoring->certificate_no}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h6>2. {{ trans('app.forms.delivery_document_of_each_meeting') }}</h6>
                                                        <div class="table-responsive">
                                                            <div class="form-group row">
                                                                <div class="col-md-3">
                                                                    <label class="form-control-label">{{ trans('app.forms.financial_report_start_month') }}</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="form-control" id="commercial_sinking_fund_option">
                                                                        <option value="0" selected="">{{ trans('app.forms.all') }}</option>
                                                                        <option value="1">{{ trans('app.forms.january') }}</option>
                                                                        <option value="2">{{ trans('app.forms.february') }}</option>
                                                                        <option value="3">{{ trans('app.forms.march') }}</option>
                                                                        <option value="4">{{ trans('app.forms.april') }}</option>
                                                                        <option value="5">{{ trans('app.forms.may') }}</option>
                                                                        <option value="6">{{ trans('app.forms.june') }}</option>
                                                                        <option value="7">{{ trans('app.forms.july') }}</option>
                                                                        <option value="8">{{ trans('app.forms.august') }}</option>
                                                                        <option value="9">{{ trans('app.forms.september') }}</option>
                                                                        <option value="10">{{ trans('app.forms.october') }}</option>
                                                                        <option value="11">{{ trans('app.forms.november') }}</option>
                                                                        <option value="12">{{ trans('app.forms.december') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <table class="table table-hover nowrap" id="financial_report_list" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width:15%;text-align: center !important;">{{ trans('app.forms.agm_date') }}</th>
                                                                        <th style="width:20%;">{{ trans('app.forms.meeting') }}</th>
                                                                        <th style="width:5%;"></th>
                                                                        <th style="width:20%;">{{ trans('app.forms.copy_list') }}</th>
                                                                        <th style="width:5%;"></th>
                                                                        <th style="width:20%;">{{ trans('app.forms.financial_report') }}</th>
                                                                        <th style="width:5%;"></th>
    
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h6>{{ trans('app.forms.additional_info') }}</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover nowrap" id="ajk_details_list" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width:30%;text-align: center !important;">{{ trans('app.forms.designation') }}</th>
                                                                        <th style="width:15%;">{{ trans('app.forms.name') }}</th>
                                                                        <th style="width:15%;">{{ trans('app.forms.email') }}</th>
                                                                        <th style="width:20%;">{{ trans('app.forms.phone_number') }}</th>
                                                                        <th style="width:5%;">{{ trans('app.forms.start_year') }}</th>
                                                                        <th style="width:5%;">{{ trans('app.forms.end_year') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <textarea class="form-control" rows="3" id="monitoring_remarks" readonly="">{{$monitoring->remarks}}</textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    function getAGMDetails(id) {
        $.ajax({
            url: "{{ URL::action('AdminController@getAGMDetails') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                $("#agmEdit").html(data);
                $("#edit_agm_details").modal("show");
            }
        });
    }

    $(function () {
        $('#agm_date').datetimepicker({
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
        $('#agm_date_edit').datetimepicker({
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
        $('#audit_start').datetimepicker({
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
        $('#audit_start_edit').datetimepicker({
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
        $('#audit_end').datetimepicker({
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
        $('#audit_end_edit').datetimepicker({
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
    });

    $(document).ready(function () {
        $('#financial_report_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAGM',  \Helper\Helper::encode($file->id) )}}",
            "order": [[0, "desc"]]
        });
        $('#ajk_details_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAJK',  \Helper\Helper::encode($file->id) )}}",
            "order": [[0, "desc"]]
        });
    });

    $(document).ready(function () {
        //add
        var options1 = {
            beforeSubmit: showRequest1,
            success: showResponse1,
            dataType: 'json'
        };
        var options2 = {
            beforeSubmit: showRequest2,
            success: showResponse2,
            dataType: 'json'
        };
        var options3 = {
            beforeSubmit: showRequest3,
            success: showResponse3,
            dataType: 'json'
        };
        //edit
        var options4 = {
            beforeSubmit: showRequest4,
            success: showResponse4,
            dataType: 'json'
        };
        var options5 = {
            beforeSubmit: showRequest5,
            success: showResponse5,
            dataType: 'json'
        };
        var options6 = {
            beforeSubmit: showRequest6,
            success: showResponse6,
            dataType: 'json'
        };


        //add
        $('body').delegate('#audit_report_file', 'change', function () {
            $('#upload_audit_report_file').ajaxForm(options1).submit();
        });
        $('body').delegate('#letter_integrity', 'change', function () {
            $('#upload_letter_integrity').ajaxForm(options2).submit();
        });
        $('body').delegate('#letter_bankruptcy', 'change', function () {
            $('#upload_letter_bankruptcy').ajaxForm(options3).submit();
        });
        //edit
        $('body').delegate('#audit_report_file_edit', 'change', function () {
            $('#upload_audit_report_file_edit').ajaxForm(options4).submit();
        });
        $('body').delegate('#letter_integrity_edit', 'change', function () {
            $('#upload_letter_integrity_edit').ajaxForm(options5).submit();
        });
        $('body').delegate('#letter_bankruptcy_edit', 'change', function () {
            $('#upload_letter_bankruptcy_edit').ajaxForm(options6).submit();
        });
    });

    //upload audit report file
    function showRequest1(formData, jqForm, options1) {
        $("#validation-errors_audit_report_file").hide().empty();
        return true;
    }
    function showResponse1(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_audit_report_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_audit_report_file").show();
            $("#audit_report_file").css("color", "red");
        } else {
            $("#validation-errors_audit_report_file").html("<i class='fa fa-check' id='check_audit_report_file' style='color:green;'></i>");
            $("#clear_audit_report_file").show();
            $("#validation-errors_audit_report_file").show();
            $("#audit_report_file").css("color", "green");
            $("#audit_report_file_url").val(response.file);
        }
    }

    //upload letter integrity
    function showRequest2(formData, jqForm, options2) {
        $("#validation-errors_letter_integrity").hide().empty();
        return true;
    }
    function showResponse2(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_integrity").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_integrity").show();
            $("#letter_integrity").css("color", "red");
        } else {
            $("#validation-errors_letter_integrity").html("<i class='fa fa-check' id='check_letter_integrity' style='color:green;'></i>");
            $("#clear_letter_integrity").show();
            $("#validation-errors_letter_integrity").show();
            $("#letter_integrity").css("color", "green");
            $("#letter_integrity_url").val(response.file);
        }
    }

    //upload letter bankruptcy
    function showRequest3(formData, jqForm, options3) {
        $("#validation-errors_letter_bankruptcy").hide().empty();
        return true;
    }
    function showResponse3(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_bankruptcy").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_bankruptcy").show();
            $("#letter_bankruptcy").css("color", "red");
        } else {
            $("#validation-errors_letter_bankruptcy").html("<i class='fa fa-check' id='check_letter_bankruptcy' style='color:green;'></i>");
            $("#clear_letter_bankruptcy").show();
            $("#validation-errors_letter_bankruptcy").show();
            $("#letter_bankruptcy").css("color", "green");
            $("#letter_bankruptcy_url").val(response.file);
        }
    }

    //upload audit report file edit
    function showRequest4(formData, jqForm, options4) {
        $("#validation-errors_audit_report_file_edit").hide().empty();
        return true;
    }
    function showResponse4(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_audit_report_file_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_audit_report_file_edit").show();
            $("#audit_report_file_edit").css("color", "red");
        } else {
            $("#report_edit").hide();
            $("#validation-errors_audit_report_file_edit").html("<i class='fa fa-check' id='check_audit_report_file_edit' style='color:green;'></i>");
            $("#clear_audit_report_file_edit").show();
            $("#validation-errors_audit_report_file_edit").show();
            $("#audit_report_file_edit").css("color", "green");
            $("#audit_report_file_url_edit").val(response.file);
        }
    }

    //upload letter integrity edit
    function showRequest5(formData, jqForm, options5) {
        $("#validation-errors_letter_integrity_edit").hide().empty();
        return true;
    }
    function showResponse5(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_integrity_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_integrity_edit").show();
            $("#letter_integrity_edit").css("color", "red");
        } else {
            $("#integrity_edit").hide();
            $("#validation-errors_letter_integrity_edit").html("<i class='fa fa-check' id='check_letter_integrity_edit' style='color:green;'></i>");
            $("#clear_letter_integrity_edit").show();
            $("#validation-errors_letter_integrity_edit").show();
            $("#letter_integrity_edit").css("color", "green");
            $("#letter_integrity_url_edit").val(response.file);
        }
    }

    //upload letter bankruptcy edit
    function showRequest6(formData, jqForm, options6) {
        $("#validation-errors_letter_bankruptcy_edit").hide().empty();
        return true;
    }
    function showResponse6(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_bankruptcy_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_bankruptcy_edit").show();
            $("#letter_bankruptcy_edit").css("color", "red");
        } else {
            $("#bankruptcy_edit").hide();
            $("#validation-errors_letter_bankruptcy_edit").html("<i class='fa fa-check' id='check_letter_bankruptcy_edit' style='color:green;'></i>");
            $("#clear_letter_bankruptcy_edit").show();
            $("#validation-errors_letter_bankruptcy_edit").show();
            $("#letter_bankruptcy_edit").css("color", "green");
            $("#letter_bankruptcy_url_edit").val(response.file);
        }
    }

    $(document).on("click", '.edit_agm', function (e) {
        var agm_id = $(this).data('agm_id');
        var agm_date = $(this).data('agm_date');
        var audit_start_date = $(this).data('audit_start_date');
        var audit_end_date = $(this).data('audit_end_date');
        var audit_report_file_url = $(this).data('audit_report_file_url');
        var letter_integrity_url = $(this).data('letter_integrity_url');
        var letter_bankruptcy_url = $(this).data('letter_bankruptcy_url');

        $("#agm_id_edit").val(agm_id);
        if (agm_date == "0000-00-00 00:00:00") {
            $("#agm_date_edit").val("");
        } else {
            $("#agm_date_edit").val(agm_date);
        }
        if (audit_start_date == "0000-00-00 00:00:00") {
            $("#audit_start_edit").val("");
        } else {
            $("#audit_start_edit").val(audit_start_date);
        }
        if (audit_end_date == "0000-00-00 00:00:00") {
            $("#audit_end_edit").val("");
        } else {
            $("#audit_end_edit").val(audit_end_date);
        }
        $("#audit_report_file_url_edit").val(audit_report_file_url);
        $("#letter_integrity_url_edit").val(letter_integrity_url);
        $("#letter_bankruptcy_url_edit").val(letter_bankruptcy_url);
    });

    $(document).on("click", '.edit_ajk', function (e) {
        var ajk_id = $(this).data('ajk_id');
        var designation = $(this).data('designation');
        var name = $(this).data('name');
        var phone_no = $(this).data('phone_no');
        var start_year = $(this).data('start_year');
        var end_year = $(this).data('end_year');

        $("#ajk_id_edit").val(ajk_id);
        $("#ajk_designation_edit").val(designation);
        $("#ajk_name_edit").val(name);
        $("#ajk_phone_no_edit").val(phone_no);
        $("#ajk_start_year_edit").val(start_year);
        $("#ajk_end_year_edit").val(end_year);
    });

    function addAGMDetails() {
        $("#add_agm_details").modal("show");
    }
    function editAGMDetails() {
        $("#edit_agm_details").modal("show");
    }

    function addAJKDetails() {
        $("#add_ajk_details").modal("show");
    }
    function editAJKDetails() {
        $("#edit_ajk_details").modal("show");
    }

    function updateMonitoring() {
        $("#loading").css("display", "inline-block");

        var precalculate_plan;
        var buyer_registration;

        if (document.getElementById('precalculate_plan').checked) {
            precalculate_plan = 1;
        } else {
            precalculate_plan = 0;
        }
        if (document.getElementById('buyer_registration').checked) {
            buyer_registration = 1;
        } else {
            buyer_registration = 0;
        }

        var certificate_series_no = $("#certificate_series_no").val(),
                monitoring_remarks = $("#monitoring_remarks").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateMonitoring') }}",
                type: "POST",
                data: {
                    precalculate_plan: precalculate_plan,
                    buyer_registration: buyer_registration,
                    certificate_series_no: certificate_series_no,
                    monitoring_remarks: monitoring_remarks,
                    id: '{{$monitoring->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        window.location = "{{URL::action('AdminController@others',  \Helper\Helper::encode($file->id) )}}";
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function addAGMDetail() {
        $("#loading").css("display", "inline-block");

        var agm,
                egm,
                minit_meeting,
                jmc_copy,
                ic_list,
                attendance_list,
                audited_financial_report;

        if (document.getElementById('agm').checked) {
            agm = $("#agm").val();
        } else {
            agm = 0;
        }
        if (document.getElementById('egm').checked) {
            egm = $("#egm").val();
        } else {
            egm = 0;
        }
        if (document.getElementById('minit_meeting').checked) {
            minit_meeting = $("#minit_meeting").val();
        } else {
            minit_meeting = 0;
        }
        if (document.getElementById('jmc_copy').checked) {
            jmc_copy = $("#jmc_copy").val();
        } else {
            jmc_copy = 0;
        }
        if (document.getElementById('ic_list').checked) {
            ic_list = $("#ic_list").val();
        } else {
            ic_list = 0;
        }
        if (document.getElementById('attendance_list').checked) {
            attendance_list = $("#attendance_list").val();
        } else {
            attendance_list = 0;
        }
        if (document.getElementById('audited_financial_report').checked) {
            audited_financial_report = $("#audited_financial_report").val();
        } else {
            audited_financial_report = 0;
        }

        var agm_date = $("#agm_date").val(),
                audit_report = $("#audit_report").val(),
                audit_start = $("#audit_start").val(),
                audit_end = $("#audit_end").val(),
                audit_report_file_url = $("#audit_report_file_url").val(),
                letter_integrity_url = $("#letter_integrity_url").val(),
                letter_bankruptcy_url = $("#letter_bankruptcy_url").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@addAGMDetails') }}",
                type: "POST",
                data: {
                    agm_date: agm_date,
                    agm: agm,
                    egm: egm,
                    minit_meeting: minit_meeting,
                    jmc_copy: jmc_copy,
                    ic_list: ic_list,
                    attendance_list: attendance_list,
                    audited_financial_report: audited_financial_report,
                    audit_report: audit_report,
                    audit_start: audit_start,
                    audit_end: audit_end,
                    audit_report_file_url: audit_report_file_url,
                    letter_integrity_url: letter_integrity_url,
                    letter_bankruptcy_url: letter_bankruptcy_url,
                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#add_agm_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function editAGMDetail() {
        $("#loading").css("display", "inline-block");

        var agm,
                egm,
                minit_meeting,
                jmc_copy,
                ic_list,
                attendance_list,
                audited_financial_report;

        if (document.getElementById('agm_edit').checked) {
            agm = $("#agm_edit").val();
        } else {
            agm = 0;
        }
        if (document.getElementById('egm_edit').checked) {
            egm = $("#egm_edit").val();
        } else {
            egm = 0;
        }
        if (document.getElementById('minit_meeting_edit').checked) {
            minit_meeting = $("#minit_meeting_edit").val();
        } else {
            minit_meeting = 0;
        }
        if (document.getElementById('jmc_copy_edit').checked) {
            jmc_copy = $("#jmc_copy_edit").val();
        } else {
            jmc_copy = 0;
        }
        if (document.getElementById('ic_list_edit').checked) {
            ic_list = $("#ic_list_edit").val();
        } else {
            ic_list = 0;
        }
        if (document.getElementById('attendance_list_edit').checked) {
            attendance_list = $("#attendance_list_edit").val();
        } else {
            attendance_list = 0;
        }
        if (document.getElementById('audited_financial_report_edit').checked) {
            audited_financial_report = $("#audited_financial_report_edit").val();
        } else {
            audited_financial_report = 0;
        }

        var agm_id_edit = $("#agm_id_edit").val(),
                agm_date = $("#agm_date_edit").val(),
                audit_report = $("#audit_report_edit").val(),
                audit_start = $("#audit_start_edit").val(),
                audit_end = $("#audit_end_edit").val(),
                audit_report_file_url = $("#audit_report_file_url_edit").val(),
                letter_integrity_url = $("#letter_integrity_url_edit").val(),
                letter_bankruptcy_url = $("#letter_bankruptcy_url_edit").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@editAGMDetails') }}",
                type: "POST",
                data: {
                    agm_date: agm_date,
                    agm: agm,
                    egm: egm,
                    minit_meeting: minit_meeting,
                    jmc_copy: jmc_copy,
                    ic_list: ic_list,
                    attendance_list: attendance_list,
                    audited_financial_report: audited_financial_report,
                    audit_report: audit_report,
                    audit_start: audit_start,
                    audit_end: audit_end,
                    audit_report_file_url: audit_report_file_url,
                    letter_integrity_url: letter_integrity_url,
                    letter_bankruptcy_url: letter_bankruptcy_url,
                    id: agm_id_edit
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#edit_agm_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function addAJKDetail() {
        $("#loading").css("display", "inline-block");

        var ajk_designation = $("#ajk_designation").val(),
                ajk_name = $("#ajk_name").val(),
                ajk_phone_no = $("#ajk_phone_no").val(),
                ajk_start_year = $("#ajk_start_year").val(),
                ajk_end_year = $("#ajk_end_year").val();

        var error = 0;

        if (ajk_designation.trim() == "") {
            $("#ajk_designation_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Designation"]) }}</span>');
            $("#ajk_designation_error").css("display", "block");
            error = 1;
        }

        if (ajk_name.trim() == "") {
            $("#ajk_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#ajk_name_error").css("display", "block");
            error = 1;
        }

        if (ajk_phone_no.trim() == "") {
            $("#ajk_phone_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_error").css("display", "block");
            $("#ajk_phone_no_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_phone_no)) {
            $("#ajk_phone_no_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_invalid_error").css("display", "block");
            $("#ajk_phone_no_error").css("display", "none");
            error = 1;
        }

        if (ajk_start_year.trim() == "") {
            $("#ajk_start_year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_error").css("display", "block");
            $("#ajk_start_year_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_start_year)) {
            $("#ajk_start_year_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_invalid_error").css("display", "block");
            $("#ajk_start_year_error").css("display", "none");
            error = 1;
        }

        if (ajk_end_year.trim() == "") {
            $("#ajk_end_year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"End Year"]) }}</span>');
            $("#ajk_end_year_error").css("display", "block");
            $("#ajk_end_year_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_end_year)) {
            $("#ajk_end_year_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"End Year"]) }}</span>');
            $("#ajk_end_year_invalid_error").css("display", "block");
            $("#ajk_end_year_error").css("display", "none");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@addAJKDetails') }}",
                type: "POST",
                data: {
                    ajk_designation: ajk_designation,
                    ajk_name: ajk_name,
                    ajk_phone_no: ajk_phone_no,
                    ajk_start_year: ajk_start_year,
                    ajk_end_year: ajk_end_year,
                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#add_ajk_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function editAJK() {
        $("#loading").css("display", "inline-block");

        var ajk_id_edit = $("#ajk_id_edit").val(),
                ajk_designation = $("#ajk_designation_edit").val(),
                ajk_name = $("#ajk_name_edit").val(),
                ajk_phone_no = $("#ajk_phone_no_edit").val(),
                ajk_start_year = $("#ajk_start_year_edit").val(),
                ajk_end_year = $("#ajk_end_year_edit").val();

        var error = 0;

        if (ajk_designation.trim() == "") {
            $("#ajk_designation_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Designation"]) }}</span>');
            $("#ajk_designation_edit_error").css("display", "block");
            error = 1;
        }

        if (ajk_name.trim() == "") {
            $("#ajk_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#ajk_name_error").css("display", "block");
            error = 1;
        }

        if (ajk_phone_no.trim() == "") {
            $("#ajk_phone_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_error").css("display", "block");
            $("#ajk_phone_no_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_phone_no)) {
            $("#ajk_phone_no_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_invalid_error").css("display", "block");
            $("#ajk_phone_no_error").css("display", "none");
            error = 1;
        }

        if (ajk_start_year.trim() == "") {
            $("#ajk_start_year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_error").css("display", "block");
            $("#ajk_start_year_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_start_year)) {
            $("#ajk_start_year_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_invalid_error").css("display", "block");
            $("#ajk_start_year_error").css("display", "none");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@editAJKDetails') }}",
                type: "POST",
                data: {
                    ajk_designation: ajk_designation,
                    ajk_name: ajk_name,
                    ajk_phone_no: ajk_phone_no,
                    ajk_start_year: ajk_start_year,
                    ajk_end_year: ajk_end_year,
                    ajk_id_edit: ajk_id_edit
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#edit_ajk_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function deleteAGMDetails(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
                function () {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteAGMDetails') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.trim() == "true") {
                                $.notify({
                                    message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.deleted_successfully") }}</p>'
                                }, {
                                    type: 'success',
                                    placement: {
                                        align: "center"
                                    }
                                });
                                location.reload();
                            } else {
                                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                            }
                        }
                    });
                });
    }

    function deleteAJKDetails(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
                function () {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteAJKDetails') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.trim() == "true") {
                                $.notify({
                                    message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.deleted_successfully") }}</p>'
                                }, {
                                    type: 'success',
                                    placement: {
                                        align: "center"
                                    }
                                });
                                location.reload();
                            } else {
                                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                            }
                        }
                    });
                });
    }

    function deleteAuditReport(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
                function () {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteAuditReport') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.trim() == "true") {
                                swal({
                                    title: "{{ trans('app.successes.deleted_title') }}",
                                    text: "{{ trans('app.successes.deleted_text_file') }}",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    closeOnConfirm: false
                                });
                                location.reload();
                            } else {
                                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                            }
                        }
                    });
                });
    }

    function deleteLetterIntegrity(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
                function () {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteLetterIntegrity') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.trim() == "true") {
                                swal({
                                    title: "{{ trans('app.successes.deleted_title') }}",
                                    text: "{{ trans('app.successes.deleted_text_file') }}",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    closeOnConfirm: false
                                });
                                location.reload();
                            } else {
                                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                            }
                        }
                    });
                });
    }

    function deleteLetterBankruptcy(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
                function () {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteLetterBankruptcy') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.trim() == "true") {
                                swal({
                                    title: "{{ trans('app.successes.deleted_title') }}",
                                    text: "{{ trans('app.successes.deleted_text_file') }}",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    closeOnConfirm: false
                                });
                                location.reload();
                            } else {
                                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                            }
                        }
                    });
                });
    }

    function clearAuditFile() {
        $("#audit_report_file").val("");
        $("#audit_report_file_url").val("");
        $("#audit_report_file").css("color", "grey");
        $("#clear_audit_report_file").hide();
        $("#check_audit_report_file").hide();
    }

    function clearLetterIntegrity() {
        $("#letter_integrity").val("");
        $("#letter_integrity_url").val("");
        $("#letter_integrity").css("color", "grey");
        $("#clear_letter_integrity").hide();
        $("#check_letter_integrity").hide();
    }

    function clearLetterBankruptcy() {
        $("#letter_bankruptcy").val("");
        $("#letter_bankruptcy_url").val("");
        $("#letter_bankruptcy").css("color", "grey");
        $("#clear_letter_bankruptcy").hide();
        $("#check_letter_bankruptcy").hide();
    }
    function clearAuditFileEdit() {
        $("#audit_report_file_edit").val("");
        $("#audit_report_file_url_edit").val("");
        $("#audit_report_file_edit").css("color", "grey");
        $("#clear_audit_report_file_edit").hide();
        $("#check_audit_report_file_edit").hide();
    }

    function clearLetterIntegrityEdit() {
        $("#letter_integrity_edit").val("");
        $("#letter_integrity_url_edit").val("");
        $("#letter_integrity_edit").css("color", "grey");
        $("#clear_letter_integrity_edit").hide();
        $("#check_letter_integrity_edit").hide();
    }

    function clearLetterBankruptcyEdit() {
        $("#letter_bankruptcy_edit").val("");
        $("#letter_bankruptcy_url_edit").val("");
        $("#letter_bankruptcy_edit").css("color", "grey");
        $("#clear_letter_bankruptcy_edit").hide();
        $("#check_letter_bankruptcy_edit").hide();
    }
</script>


<!-- End Page Scripts-->

@stop
