@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 37) {
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
                        <form id="add_fileprefix">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.file_no') }}</label>
                                        <select id="file_id" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($file_no as $files)
                                            <option value="{{ $files->id }}">{{ $files->file_no }}</option>
                                            @endforeach
                                        </select>
                                        <div id="file_no_error" style="display:none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.year') }}</label>
                                        <select id="year" class="form-control select2">
                                            @foreach ($year as $value => $years)
                                            <option value="{{ $value }}">{{ $years }}</option>
                                            @endforeach
                                        </select>
                                        <div id="year_error" style="display:none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.month') }}</label>
                                        <select id="month" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($month as $value => $months)
                                            <option value="{{ $value }}">{{ $months }}</option>
                                            @endforeach
                                        </select>
                                        <div id="month_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div id="file_already_exists_error" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($insert_permission) { ?>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="submitAddFinanceFile()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('FinanceController@financeList')}}'">{{ trans('app.forms.cancel') }}</button>
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
    function submitAddFinanceFile() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        $("#file_no_error").css("display", "none");
        $("#month_error").css("display", "none");
        $("#year_error").css("display", "none");
        $("#file_already_exists_error").css("display", "none");

        var file_no = $("#file_id").val(),
                month = $("#month").val(),
                year = $("#year").val();

        var error = 0;

        if (file_no.trim() == "") {
            $("#file_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
            $("#file_no_error").css("display", "block");
            error = 1;
        }

        if (month.trim() == "") {
            $("#month_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Month"]) }}</span>');
            $("#month_error").css("display", "block");
            error = 1;
        }

        if (year.trim() == "") {
            $("#year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Year"]) }}</span>');
            $("#year_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('FinanceController@submitAddFinanceFile') }}",
                type: "POST",
                data: {
                    file_id: file_no,
                    month: month,
                    year: year
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");

                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.finance_file.store') }}</span>", function () {
                            window.location = '{{URL::action("FinanceController@financeList") }}';
                        });
                    } else if (data.trim() == "file_already_exists") {
                        $("#file_already_exists_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.exist2", ["attribute"=>"file"]) }}</span>');
                        $("#file_already_exists_error").css("display", "block");
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }
</script>

@stop
