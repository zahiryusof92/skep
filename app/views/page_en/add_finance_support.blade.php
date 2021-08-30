@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 39) {
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
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file')

                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="finance_support_tab" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <form id="documentSubmit" class="form-horizontal" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                        </div>
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
                                                    <?php if ($update_permission == 1) { ?>
                                                        <button type="button" class="btn btn-own" id="submit_button" onclick="submitFinanceSupport()">{{ trans('app.forms.submit') }}</button>
                                                    <?php } ?>
                                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@financeSupport', $files->id) }}'">{{ trans('app.forms.cancel') }}</button>
                                                    <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
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
    var changes = false;
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "Data you have entered may not be saved, do you really want to leave?";
        }
    });
    
    $(document).ready(function () {
        $('#date').datetimepicker({
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
            $("#mirror_date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });

    function submitFinanceSupport() {
        changes = false;
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
                url: "{{ URL::action('AdminController@submitAddFinanceSupport') }}",
                type: "POST",
                data: {
                    file_id: "{{$files->id}}",
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
                            window.location = '{{URL::action("AdminController@financeSupport",[$files->id]) }}';
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
<!-- End Page Scripts-->

@stop
