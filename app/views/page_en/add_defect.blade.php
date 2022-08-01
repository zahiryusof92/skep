@extends('layout.english_layout.default_custom')

@section('content')

<?php
$insert_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 45) {
        $insert_permission = $permissions->insert_permission;
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
                                        <select id="file_id" name="file_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'file_id'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
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
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.defect_category') }}</label>
                                        <select id="defect_category" name="defect_category" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($defectCategory as $dc)
                                            <option value="{{$dc->id}}">{{$dc->name}}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'defect_category'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.defect_name') }}</label>
                                        <input id="name" name="name" class="form-control" type="text" placeholder="{{ trans('app.forms.defect_name') }}">
                                        @include('alert.feedback-ajax', ['field' => 'name'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.defect_description') }}</label>
                                        <textarea id="description" name="description" rows="5" class="form-control" placeholder="{{ trans('app.forms.defect_description') }}"></textarea>
                                        @include('alert.feedback-ajax', ['field' => 'description'])
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
                                        <input type="file" id="defect_attachment" name="defect_attachment" />
                                        @include('alert.feedback-ajax', ['field' => 'defect_attachment'])
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form>
                            <div class="form-actions">
                                <?php if ($insert_permission) { ?>
                                    <input type="hidden" id="defect_attachment_url" name="defect_attachment_url" value=""/>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="submitAddDefect()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@defect') }}'">{{ trans('app.forms.cancel') }}</button>
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
        $('body').delegate('#defect_attachment', 'change', function () {
            $('#upload_defect_attachment').ajaxForm({
                dataType: 'json',
                beforeSubmit: function () {
                    $("#defect_attachment_error").hide().empty();
                    return true;
                },
                success: function (response) {
                    if (response.success == false) {
                        var arr = response.errors;
                        $.each(arr, function (index, value) {
                            if (value.length != 0) {
                                $("#defect_attachment_error").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                            }
                        });
                        $("#defect_attachment_error").show();
                        $("#defect_attachment").css("color", "red");
                    } else {
                        $("#clear_defect_attachment").show();
                        $("#defect_attachment_error").html("<i class='fa fa-check' id='check_defect_attachment' style='color:green;'></i>");
                        $("#defect_attachment_error").show();
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

    function submitAddDefect() {
        // $("#loading").css("display", "inline-block");
        // $("#submit_button").attr("disabled", "disabled");
        // $("#cancel_button").attr("disabled", "disabled");

        // var file_id = $("#file_id").val(),
        //         defect_category = $("#defect_category").val(),
        //         name = $("#name").val(),
        //         description = $("#description").val(),
        //         defect_attachment = $("#defect_attachment_url").val();

        // var error = 0;

        // if (file_id.trim() == "") {
        //     $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
        //     $("#file_id_error").css("display", "block");
        //     error = 1;
        // }
        // if (defect_category.trim() == "") {
        //     $("#defect_category_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Complaint Category"]) }}</span>');
        //     $("#defect_category_error").css("display", "block");
        //     error = 1;
        // }
        // if (name.trim() == "") {
        //     $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Complaint Name"]) }}</span>');
        //     $("#name_error").css("display", "block");
        //     error = 1;
        // }
        // if (description.trim() == "") {
        //     $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Description"]) }}</span>');
        //     $("#description_error").css("display", "block");
        //     error = 1;
        // }
        // if (defect_attachment.trim() == "") {
        //     $("#defect_attachment_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"Complaint Attachment"]) }}</span>');
        //     $("#defect_attachment_error").css("display", "block");
        //     error = 1;
        // }

        let formData = $('form').serializeArray();
        $.ajax({
            url: "{{ URL::action('AdminController@submitAddDefect') }}",
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
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>", function () {
                        window.location = '{{URL::action("AdminController@defect") }}';
                    });
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
                beforeSend: function() {
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
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
                },
                complete: function() {
                    $.unblockUI();
                },
            });
        });
    }
</script>
<!-- End Page Scripts-->

@stop
