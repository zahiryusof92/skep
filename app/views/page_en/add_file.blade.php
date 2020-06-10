@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 2) {
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
                    <form id="add_fileprefix">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if (Auth::user()->getAdmin())
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.cob') }}</label>
                                    <select id="company" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($cob as $companies)
                                        <option value="{{ $companies->id }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                        @endforeach
                                    </select>
                                    <div id="company_error" style="display:none;"></div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" id="company" value="{{ Auth::user()->company_id }}">
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.file_number') }}</label>
                                    <select id="file_no" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($file_no as $files)
                                        <option value="{{$files->description}}">{{$files->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="file_no_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.description') }}</label>
                                    <input id="description" class="form-control" placeholder="{{ trans('app.forms.description') }}" type="text">
                                    <div id="description_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="file_already_exists_error" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <?php if ($insert_permission == 1) { ?>
                                <button type="button" class="btn btn-primary" id="submit_button" onclick="addFile()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@addFile')}}'">{{ trans('app.forms.cancel') }}</button>
                            <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
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

    function addFile() {
        $("#loading").css("display", "inline-block");
        $("#company_error").css("display", "none");
        $("#file_no_error").css("display", "none");
        $("#description_error").css("display", "none");

        var company_id = $("#company").val(),
                file_no = $("#file_no").val(),
                description = $("#description").val();

        var error = 0;

        if (company_id.trim() == "") {
            $("#company_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"COB"]) }}</span>');
            $("#company_error").css("display", "block");
            error = 1;
        }
        if (file_no.trim() == "") {
            $("#file_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File Number"]) }}</span>');
            $("#file_no_error").css("display", "block");
            error = 1;
        }

        if (description.trim() == "") {
            $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Description"]) }}</span>');
            $("#description_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitFile') }}",
                type: "POST",
                data: {
                    company_id: company_id,
                    file_no: file_no,
                    description: description
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.files.store') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@fileList") }}';
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
            $("#file_no").focus();
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }
</script>

@stop
