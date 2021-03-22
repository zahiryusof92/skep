@extends('layout.english_layout.default')

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
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
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
                                <select id="file_id" class="form-control select2">
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($file_no as $files)
                                    <option value="{{$files->id}}">{{$files->file_no}}</option>
                                    @endforeach
                                </select>
                                <div id="file_no_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input id="name" class="form-control" type="text">
                                <div id="name_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><span style="color: red;">*</span> {{ trans("app.forms.date") }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="date" class="form-control" type="text">
                                <input type="hidden" name="mirror_date" id="mirror_date">
                                <div id="date_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><span style="color: red;">*</span> {{ trans("app.forms.amount") }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="amount" class="form-control" placeholder="0.00" type="text">
                                <div id="amount_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><span style="color: red;">*</span> {{ trans("app.forms.remarks") }}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="remark" id="remark" class="form-control" rows="5"></textarea>
                                <div id="remark_error" style="display:none;"></div>
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
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(function () {
        $('#date').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD/MM/YYYY',
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('/');
            console.log(currentDate);
            $("#mirror_date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });

    function submitFinanceSupport() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        $("#file_no_error").css("display", "none");
        $("#date_error").css("display", "none");
        $("#name_error").css("display", "none");
        $("#amount_error").css("display", "none");
        $("#remark_error").css("display", "none");

        var file_no = $("#file_id").val(),
                date = $("#mirror_date").val(),
                name = $("#name").val(),
                amount = $("#amount").val(),
                remark = $("#remark").val();

        var error = 0;

        if (file_no.trim() == "") {
            $("#file_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File Number"]) }}</span>');
            $("#file_no_error").css("display", "block");
            error = 1;
        }

        if (date.trim() == "") {
            $("#date_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Date"]) }}</span>');
            $("#date_error").css("display", "block");
            error = 1;
        }

        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }

        if (amount.trim() == "") {
            $("#amount_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Amount"]) }}</span>');
            $("#amount_error").css("display", "block");
            error = 1;
        }

        if (remark.trim() == "") {
            $("#remark_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Remarks"]) }}</span>');
            $("#remark_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('FinanceController@submitFinanceSupport') }}",
                type: "POST",
                data: {
                    file_id: file_no,
                    date: date,
                    name: name,
                    remark: remark,
                    amount: amount,
                    is_active: 1
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");

                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.finance_file.store') }}</span>", function () {
                            window.location = '{{URL::action("FinanceController@financeSupport") }}';
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
