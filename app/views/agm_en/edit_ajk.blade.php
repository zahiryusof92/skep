@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 33) {
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
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <form class="form-horizontal">
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
                                            @foreach ($files as $file)
                                            <option value="{{$file->id}}" {{ ($ajk_details->file_id == $file->id ? 'selected' : '' ) }}>{{$file->file_no}}</option>
                                            @endforeach
                                        </select>
                                        <div id="file_id_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.designation') }}</label>
                                        <select id="designation" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($designation as $designations)
                                            <option value="{{$designations->id}}" {{ ($ajk_details->designation == $designations->id ? 'selected' : '' ) }}>{{$designations->description}}</option>
                                            @endforeach
                                        </select>
                                        <div id="designation_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.name') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="name" value="{{ $ajk_details->name }}"/>
                                        <div id="name_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.phone_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no" value="{{ $ajk_details->phone_no }}"/>
                                        <div id="phone_no_error" style="display:none;"></div>
                                        <div id="phone_no_invalid_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.month') }}</label>
                                        <select id="month" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($month as $value => $months)
                                            <option value="{{ $value }}" {{ ($ajk_details->month == $value ? 'selected' : '' ) }}>{{ $months }}</option>
                                            @endforeach
                                        </select>
                                        <div id="month_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.year') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.year') }}" id="year" value="{{ $ajk_details->year }}"/>
                                        <div id="year_error" style="display:none;"></div>
                                        <div id="year_invalid_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.remarks') }}" id="remarks" rows="5">{{ $ajk_details->remarks }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <?php if ($update_permission == 1) { ?>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="editAJKDetail()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AgmController@AJK') }}'">{{ trans('app.forms.cancel') }}</button>
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
    function editAJKDetail() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        $("#file_id_error").css("display", "none");
        $("#designation_error").css("display", "none");
        $("#name_error").css("display", "none");
        $("#phone_no_error").css("display", "none");
        $("#phone_no_invalid_error").css("display", "none");
        $("#month_error").css("display", "none");
        $("#year_error").css("display", "none");
        $("#year_invalid_error").css("display", "none");

        var file_id = $("#file_id").val(),
                designation = $("#designation").val(),
                name = $("#name").val(),
                phone_no = $("#phone_no").val(),
                month = $("#month").val(),
                year = $("#year").val(),
                remarks = $("#remarks").val();

        var error = 0;

        if (file_id.trim() == "") {
            $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
            $("#file_id_error").css("display", "block");
            error = 1;
        }

        if (designation.trim() == "") {
            $("#designation_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Designation"]) }}</span>');
            $("#designation_error").css("display", "block");
            error = 1;
        }

        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }

        if (phone_no.trim() == "") {
            $("#phone_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Phone Number"]) }}</span>');
            $("#phone_no_error").css("display", "block");
            $("#phone_no_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(phone_no)) {
            $("#phone_no_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Phone Number"]) }}</span>');
            $("#phone_no_invalid_error").css("display", "block");
            $("#phone_no_error").css("display", "none");
            error = 1;
        }

        if (month.trim() == "") {
            $("#month_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Month"]) }}</span>');
            $("#month_error").css("display", "block");
            error = 1;
        }

        if (year.trim() == "") {
            $("#year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Year"]) }}</span>');
            $("#year_error").css("display", "block");
            $("#year_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(year)) {
            $("#year_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Year"]) }}</span>');
            $("#year_invalid_error").css("display", "block");
            $("#year_error").css("display", "none");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AgmController@submitEditAJK') }}",
                type: "POST",
                data: {
                    file_id: file_id,
                    designation: designation,
                    name: name,
                    phone_no: phone_no,
                    month: month,
                    year: year,
                    remarks: remarks,
                    id: "{{ $ajk_details->id }}"
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.ajk.update') }}</span>", function () {
                            window.location = '{{URL::action("AgmController@AJK") }}';
                        });
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
