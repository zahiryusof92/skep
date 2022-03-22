@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 41) {
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
                        <form id="formSubmit" class="form-horizontal" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                    </div>
                                </div>
                            </div>

                            @if (Auth::user()->getAdmin())
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.cob') }}</label>
                                        <select id="company" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($cob as $companies)
                                            <option value="{{ $companies->id }}" {{ ($form->company_id == $companies->id ? 'selected' : '') }}>{{ $companies->name }} ({{ $companies->short_name }})</option>
                                            @endforeach
                                        </select>
                                        <div id="company_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" id="company" value="{{ Auth::user()->company_id }}">
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.form_type') }}</label>
                                        <select id="form_type" class="form-control" name="form_type">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($formtype as $ft)
                                            <option value="{{$ft->id}}" {{ $form->form_type_id == $ft->id ? 'selected' : '' }}>{{$ft->name_en}}</option>
                                            @endforeach
                                        </select>
                                        <div id="form_type_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.form_name_bi') }}</label>
                                        <input id="name_en" name="name_en" class="form-control" type="text" value="{{ $form->name_en }}">
                                        <div id="name_en_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.form_name_bm') }}</label>
                                        <input id="name_my" name="name_my" class="form-control" type="text"  value="{{ $form->name_my }}">
                                        <div id="name_my_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.sort_no') }}</label>
                                        <input id="sort_no" class="form-control" name="sort_no" type="text"  value="{{ $form->sort_no }}">
                                        <div id="sort_no_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form id="upload_form_file" enctype="multipart/form-data" method="post" action="{{ url('uploadFormFile') }}" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.upload_file') }}</label>
                                        <br/>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <button type="button" id="clear_form_file" class="btn btn-xs btn-danger" onclick="clearFormFile()" style="display: none;"><i class="fa fa-times"></i></button>
                                        &nbsp;<input type="file" name="form_file" id="form_file" />
                                        <div id="validation-errors_form_file"></div>
                                        @if ($form->file_url != "")
                                        <a href="{{asset($form->file_url)}}" target="_blank"><button button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}</button></a>
                                        <?php if ($update_permission == 1) { ?>
                                            <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteFormFile('{{ \Helper\Helper::encode($form->id) }}')"><i class="fa fa-times"></i></button>
                                        <?php } ?>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.status') }}</label>
                                        <select id="is_active" class="form-control" name="is_active">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            <option value="1" {{ $form->is_active == '1' ? 'selected' : '' }}>{{ trans('app.forms.active') }}</option>
                                            <option value="0" {{ $form->is_active == '0' ? 'selected' : '' }}>{{ trans('app.forms.inactive') }}</option>
                                        </select>
                                        <div id="is_active_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <?php if ($update_permission == 1) { ?>
                                    <input type="hidden" id="form_file_url" value="{{$form->file_url}}"/>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="submitEditForm()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@form') }}'">{{ trans('app.forms.cancel') }}</button>
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

        $('body').delegate('#form_file', 'change', function () {
            $('#upload_form_file').ajaxForm(options).submit();
        });
    });

    //upload form file
    function showRequest(formData, jqForm, options) {
        $("#validation-errors_form_file").hide().empty();
        return true;
    }
    function showResponse(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_form_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_form_file").show();
            $("#form_file").css("color", "red");
        } else {
            $("#clear_form_file").show();
            $("#validation-errors_form_file").html("<i class='fa fa-check' id='check_form_file' style='color:green;'></i>");
            $("#validation-errors_form_file").show();
            $("#form_file").css("color", "green");
            $("#form_file_url").val(response.file);
        }
    }

    function clearFormFile() {
        $("#form_file").val("");
        $("#clear_form_file").hide();
        $("#form_file").css("color", "grey");
        $("#check_form_file").hide();
    }

    function submitEditForm() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        $("#company_error").css("display", "none");

        var company_id = $("#company").val(),
                form_type = $("#form_type").val(),
                name_en = $("#name_en").val(),
                name_my = $("#name_my").val(),
                form_url = $("#form_file_url").val(),
                sort_no = $("#sort_no").val(),
                is_active = $("#is_active").val();

        var error = 0;

        if (company_id.trim() == "") {
            $("#company_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"COB"]) }}</span>');
            $("#company_error").css("display", "block");
            error = 1;
        }

        if (form_type.trim() == "") {
            $("#form_type_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Form Type"]) }}</span>');
            $("#form_type_error").css("display", "block");
            error = 1;
        }

        if (name_en.trim() == "") {
            $("#name_en_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Form Name (BI)"]) }}</span>');
            $("#name_en_error").css("display", "block");
            error = 1;
        }

        if (name_my.trim() == "") {
            $("#name_my_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Form Name (BM)"]) }}</span>');
            $("#name_my_error").css("display", "block");
            error = 1;
        }

        if (sort_no.trim() == "") {
            $("#sort_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Sort No"]) }}</span>');
            $("#sort_no_error").css("display", "block");
            error = 1;
        }

        if (form_url.trim() == "") {
            $("#validation-errors_form_file").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"File"]) }}</span>');
            $("#validation-errors_form_file").css("display", "block");
            error = 1;
        }

        if (is_active.trim() == "") {
            $("#is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#is_active_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateForm') }}",
                type: "POST",
                data: {
                    company_id: company_id,
                    form_type: form_type,
                    name_en: name_en,
                    name_my: name_my,
                    sort_no: sort_no,
                    form_url: form_url,
                    is_active: is_active,
                    id: '{{ \Helper\Helper::encode($form->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.forms.update') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@form") }}';
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

    function deleteFormFile(id) {
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
        function(){
            $.ajax({
                url: "{{ URL::action('AdminController@deleteFormFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
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
</script>
<!-- End Page Scripts-->

@stop
