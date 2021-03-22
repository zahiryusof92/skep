@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 45) {
        $update_permission = $permissions->update_permission;
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
                                        <option value="{{$file_no->id}}" {{ $defect->file_id == $file_no->id ? 'selected' : '' }}>{{$file_no->file_no}}</option>
                                        @endforeach
                                    </select>
                                    <div id="file_id_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.defect_category') }}</label>
                                    <select id="defect_category" class="form-control select2" name="defect_category">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($defectCategory as $dc)
                                        <option value="{{$dc->id}}" {{ $defect->defect_category_id == $dc->id ? 'selected' : '' }}>{{$dc->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="defect_category_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.defect_name') }}</label>
                                    <input id="name" name="name" class="form-control" type="text" placeholder="{{ trans('app.forms.defect_name') }}" value="{{ $defect->name }}">
                                    <div id="name_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.defect_description') }}</label>
                                    <textarea id="description" name="description" rows="5" class="form-control" placeholder="{{ trans('app.forms.defect_description') }}">{{ $defect->description }}</textarea>
                                    <div id="description_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="upload_defect_attachment" enctype="multipart/form-data" method="post" action="{{ url('uploadDefectAttachment') }}" autocomplete="off">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.upload_defect_attachment') }}</label>
                                    <br/>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <button type="button" id="clear_defect_attachment" class="btn btn-xs btn-danger" onclick="clearDefectAttachment()" style="display: none;"><i class="fa fa-times"></i></button>
                                    &nbsp;
                                    <input type="file" name="defect_attachment" id="defect_attachment" />
                                    <div id="validation-errors_defect_attachment"></div>
                                    @if ($defect->attachment_url != "")
                                    <a href="{{asset($defect->attachment_url)}}" target="_blank"><button button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> {{ trans("app.forms.download") }}</button></a>
                                    <?php if ($update_permission) { ?>
                                        <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteDefectAttachment('{{$defect->id}}')"><i class="fa fa-times"></i></button>
                                    <?php } ?>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    <form>
                        @if ((Auth::user()->getAdmin() || Auth::user()->isCOB()) || Auth::user()->isCOBManager())
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.status') }}</label>
                                    <select id="status" class="form-control" name="status">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="0" {{ $defect->status == '0' ? 'selected' : '' }}>{{ trans('app.forms.pending') }}</option>
                                        <option value="1" {{ $defect->status == '1' ? 'selected' : '' }}>{{ trans('app.forms.resolved') }}</option>
                                    </select>
                                    <div id="status_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        @else
                        <input type="hidden" id="status" name="status" value="{{$defect->status}}"/>
                        @endif
                        
                        <div class="form-actions">
                            <?php if ($update_permission) { ?>
                                <input type="hidden" id="defect_attachment_url" value="{{$defect->attachment_url}}"/>
                                <button type="button" class="btn btn-own" id="submit_button" onclick="submitEditDefect()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@defect') }}'">{{ trans('app.forms.cancel') }}</button>
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
    $(document).ready(function () {
        $('body').delegate('#defect_attachment', 'change', function () {
            $('#upload_defect_attachment').ajaxForm({
                dataType: 'json',
                beforeSubmit: function () {
                    $("#validation-errors_defect_attachment").hide().empty();
                    return true;
                },
                success: function (response) {
                    if (response.success == false) {
                        var arr = response.errors;
                        $.each(arr, function (index, value) {
                            if (value.length != 0) {
                                $("#validation-errors_defect_attachment").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                            }
                        });
                        $("#validation-errors_defect_attachment").show();
                        $("#defect_attachment").css("color", "red");
                    } else {
                        $("#clear_defect_attachment").show();
                        $("#validation-errors_defect_attachment").html("<i class='fa fa-check' id='check_defect_attachment' style='color:green;'></i>");
                        $("#validation-errors_defect_attachment").show();
                        $("#defect_attachment").css("color", "green");
                        $("#defect_attachment_url").val(response.file);
                    }
                }
            }).submit();
        });
    });

    function clearDefectAttachment() {
        $("#defect_attachment").val("");
        $("#clear_defect_attachment").hide();
        $("#defect_attachment").css("color", "grey");
        $("#check_defect_attachment").hide();
    }

    function submitEditDefect() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");

        var file_id = $("#file_id").val(),
                defect_category = $("#defect_category").val(),
                name = $("#name").val(),
                description = $("#description").val(),
                defect_attachment = $("#defect_attachment_url").val(),
                status = $("#status").val();

        var error = 0;

        if (file_id.trim() == "") {
            $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
            $("#file_id_error").css("display", "block");
            error = 1;
        }
        if (defect_category.trim() == "") {
            $("#defect_category_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Complaint Category"]) }}</span>');
            $("#defect_category_error").css("display", "block");
            error = 1;
        }
        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Complaint Name"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }
        if (description.trim() == "") {
            $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Description"]) }}</span>');
            $("#description_error").css("display", "block");
            error = 1;
        }
        if (defect_attachment.trim() == "") {
            $("#validation-errors_defect_attachment").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"Complaint Attachment"]) }}</span>');
            $("#validation-errors_defect_attachment").css("display", "block");
            error = 1;
        }
        if (status.trim() == "") {
            $("#status_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#status_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateDefect') }}",
                type: "POST",
                data: {
                    file_id: file_id,
                    defect_category: defect_category,
                    name: name,
                    description: description,
                    defect_attachment: defect_attachment,
                    status: status,
                    id: "{{ $defect->id }}"
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.updated_successfully') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@defect") }}';
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

    function deleteDefectAttachment(id) {
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
                url: "{{ URL::action('AdminController@deleteDefectAttachment') }}",
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
