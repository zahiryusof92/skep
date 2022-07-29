@extends('layout.english_layout.default_custom')

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
                                        <select id="file_id" name="file_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'file_id'])
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.strata') }}</label>
                                        <select id="strata_id" name="strata_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'strata_id'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.year') }}</label>
                                        <select id="year" name="year" class="form-control select2">
                                            @foreach ($year as $value => $years)
                                            <option value="{{ $value }}">{{ $years }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'year'])
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.month') }}</label>
                                        <select id="month" name="month" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($month as $value => $months)
                                            <option value="{{ $value }}">{{ $months }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'month'])
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
    $(function() {
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
    });
    function submitAddFinanceFile() {
        let formData = $('form').serializeArray();
        $.ajax({
            url: "{{ URL::action('FinanceController@submitAddFinanceFile') }}",
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
                        window.location = '{{URL::action("FinanceController@financeList") }}';
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
