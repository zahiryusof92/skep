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
                        <form id="documentSubmit" class="form-horizontal" enctype="multipart/form-data">
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
                                        <select id="file_id" class="form-control select2" name="file_id">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $file_no)
                                            <option value="{{$file_no->id}}" {{ $document->file_id == $file_no->id ? 'selected' : '' }}>{{$file_no->file_no}}</option>
                                            @endforeach
                                        </select>
                                        <div id="file_id_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.document_type') }}</label>
                                        <select id="document_type" class="form-control select2" name="document_type">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($documentType as $dt)
                                            <option value="{{$dt->id}}" {{ $document->document_type_id == $dt->id ? 'selected' : '' }}>{{$dt->name}}</option>
                                            @endforeach
                                        </select>
                                        <div id="document_type_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.document_name') }}</label>
                                        <input id="name" name="name" class="form-control" type="text" placeholder="{{ trans('app.forms.document_name') }}" value="{{ $document->name }}">
                                        <div id="name_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('app.forms.remarks') }}</label>
                                        <textarea id="remarks" name="remarks" rows="5" class="form-control" placeholder="{{ trans('app.forms.remarks') }}">{{ $document->remarks }}</textarea>
                                        <div id="name_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form id="upload_document_file" enctype="multipart/form-data" method="post" action="{{ url('uploadDocumentFile') }}" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.upload_file') }}</label>
                                        <br/>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <button type="button" id="clear_document_file" class="btn btn-xs btn-danger" onclick="clearDocumentFile()" style="display: none;"><i class="fa fa-times"></i></button>
                                        &nbsp;<input type="file" name="document_file" id="document_file" />
                                        <div id="validation-errors_document_file"></div>
                                        @if ($document->file_url != "")
                                        <a href="{{asset($document->file_url)}}" target="_blank"><button button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> {{ trans("app.forms.download") }}</button></a>
                                        <?php if ($update_permission == 1) { ?>
                                            <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteDocumentFile('{{$document->id}}')"><i class="fa fa-times"></i></button>
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
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.hidden') }}</label>
                                        <select id="is_hidden" class="form-control" name="is_hidden">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            <option value="1" {{ $document->is_hidden == '1' ? 'selected' : '' }}>{{ trans("app.forms.yes") }}</option>
                                            <option value="0" {{ $document->is_hidden == '0' ? 'selected' : '' }}>{{ trans("app.forms.no") }}</option>
                                        </select>
                                        <div id="is_hidden_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.read_only') }}</label>
                                        <select id="is_readonly" class="form-control" name="is_readonly">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            <option value="1" {{ $document->is_readonly == '1' ? 'selected' : '' }}>{{ trans("app.forms.yes") }}</option>
                                            <option value="0" {{ $document->is_readonly == '0' ? 'selected' : '' }}>{{ trans("app.forms.no") }}</option>
                                        </select>
                                        <div id="is_readonly_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <?php if ($update_permission == 1) { ?>
                                    <input type="hidden" id="document_file_url" value="{{$document->file_url}}"/>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="submitEditDocument()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AgmController@document') }}'">{{ trans('app.forms.cancel') }}</button>
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

        $('body').delegate('#document_file', 'change', function () {
            $('#upload_document_file').ajaxForm(options).submit();
        });
    });

    //upload document file
    function showRequest(formData, jqForm, options) {
        $("#validation-errors_document_file").hide().empty();
        return true;
    }
    function showResponse(response, statusText, xhr, $form) {
        if (response.success == false) {
            var arr = response.errors;
            $.each(arr, function (index, value) {
                if (value.length != 0) {
                    $("#validation-errors_document_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_document_file").show();
            $("#document_file").css("color", "red");
        } else {
            $("#clear_document_file").show();
            $("#validation-errors_document_file").html("<i class='fa fa-check' id='check_document_file' style='color:green;'></i>");
            $("#validation-errors_document_file").show();
            $("#document_file").css("color", "green");
            $("#document_file_url").val(response.file);
        }
    }

    function clearDocumentFile() {
        $("#document_file").val("");
        $("#clear_document_file").hide();
        $("#document_file").css("color", "grey");
        $("#check_document_file").hide();
    }

    function submitEditDocument() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");

        var file_id = $("#file_id").val(),
                document_type = $("#document_type").val(),
                name = $("#name").val(),
                remarks = $("#remarks").val(),
                document_url = $("#document_file_url").val(),
                is_hidden = $("#is_hidden").val(),
                is_readonly = $("#is_readonly").val();

        var error = 0;

        if (file_id.trim() == "") {
            $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
            $("#file_id_error").css("display", "block");
            error = 1;
        }
        if (document_type.trim() == "") {
            $("#document_type_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Document Type"]) }}</span>');
            $("#document_type_error").css("display", "block");
            error = 1;
        }
        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Document Name"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }
        if (document_url.trim() == "") {
            $("#validation-errors_document_file").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"File"]) }}</span>');
            $("#validation-errors_document_file").css("display", "block");
            error = 1;
        }
        if (is_hidden.trim() == "") {
            $("#is_hidden_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Sort No"]) }}</span>');
            $("#is_hidden_error").css("display", "block");
            error = 1;
        }
        if (is_readonly.trim() == "") {
            $("#is_readonly_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#is_readonly_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AgmController@submitUpdateDocument') }}",
                type: "POST",
                data: {
                    file_id: file_id,
                    document_type: document_type,
                    name: name,
                    remarks: remarks,
                    document_url: document_url,
                    is_hidden: is_hidden,
                    is_readonly: is_readonly,
                    id: "{{ $document->id }}"
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.documents.update') }}</span>", function () {
                            window.location = '{{URL::action("AgmController@document") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#file_id").focus();
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }

    function deleteDocumentFile(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        }, function () {
            $.ajax({
                url: "{{ URL::action('AgmController@deleteDocumentFile') }}",
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
</script>
<!-- End Page Scripts-->

@stop
