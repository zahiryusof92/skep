@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 7) {
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
                        <!-- Vertical Form -->
                        <div id="add_memo">
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
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.cob') }}</label>
                                        <select id="company" name="company" class="form-control select2" required="">
                                            @if (count($cob) > 1)
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @endif
                                            @foreach ($cob as $id => $companies)
                                            <option value="{{ $id }}">{{ $companies }}</option>
                                            @endforeach
                                        </select>
                                        <div id="company_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" id="company" name="company" value="{{ Auth::user()->company_id }}"/>
                            @endif

                            @if ($fileList)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.file_no') }}</label>
                                        <select id="file_id" name="file_id" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($fileList as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="file_id_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.memo_type') }}</label>
                                        <select id="memo_type" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($memotype as $memotypes)
                                            <option value="{{$memotypes->id}}">{{$memotypes->description}}</option>
                                            @endforeach
                                        </select>
                                        <div id="memo_type_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans("app.forms.memo_date") }}</label>
                                        <label class="input-group datepicker-only-init">
                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.memo_date") }}" id="memo_date" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <div id="memo_date_error" style="display:block;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans("app.forms.publish_date") }}</label>
                                        <label class="input-group datepicker-only-init">
                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.publish_date") }}" id="publish_date" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <div id="publish_date_error" style="display:block;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans("app.forms.expired_date") }}</label>
                                        <label class="input-group datepicker-only-init">
                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.expired_date") }}" id="expired_date" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.subject') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.subject') }}" id="subject">
                                        <div id="subject_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.description') }}</label>
                                        <textarea id="description"></textarea>
                                        <div id="description_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <form id="upload_document_file" enctype="multipart/form-data" method="post" action="{{ url('uploadMemoFile') }}" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label"> {{ trans('app.forms.upload_file') }}</label>
                                            <br/>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <button type="button" id="clear_document_file" class="btn btn-xs btn-danger" onclick="clearDocumentFile()" style="display: none;" accept=""><i class="fa fa-times"></i></button>
                                            &nbsp;<input type="file" name="document_file[]" id="document_file" multiple accept="image/*"/>
                                            <div id="validation-errors_document_file"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.status') }}</label>
                                        <select id="is_active" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            <option value="1">{{ trans('app.forms.active') }}</option>
                                            <option value="0">{{ trans('app.forms.inactive') }}</option>
                                        </select>
                                        <div id="is_active_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.remarks') }}</label>
                                        <textarea class="form-control" rows="3" id="remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($insert_permission == 1) { ?>
                                    <input type="hidden" id="document_file_url" value=""/>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="addMemo()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@memo')}}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </div>
                        <!-- End Vertical Form -->
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(function () {
        $('#memo_date').datetimepicker({
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
            format: 'YYYY-MM-DD'
        });
        $('#publish_date').datetimepicker({
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
            format: 'YYYY-MM-DD'
        });
        $('#expired_date').datetimepicker({
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
            format: 'YYYY-MM-DD'
        });
        $('#description').summernote({
            height: 250
        });

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
                    $("#validation-errors_document_file").append('<div class="alert alert-error" style="color:red;"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_document_file").show();
            $("#document_file").css("color", "red");
            $("#clear_document_file").hide();
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

    function addMemo() {
        $("#loading").css("display", "inline-block");

        var company = $("#company").val(),
                file = $("#file_id").val(),
                memo_type = $("#memo_type").val(),
                memo_date = $("#memo_date").val(),
                publish_date = $("#publish_date").val(),
                expired_date = $("#expired_date").val(),
                subject = $("#subject").val(),
                description = $("#description").val(),
                document_file_url = $("#document_file_url").val(),
                remarks = $("#remarks").val(),
                is_active = $("#is_active").val();

        var error = 0;

        if (company.trim() == "") {
            $("#company_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"COB"]) }}</span>');
            $("#company_error").css("display", "block");
            error = 1;
        }
        if (memo_type.trim() == "") {
            $("#memo_type_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Memo Type"]) }}</span>');
            $("#memo_type_error").css("display", "block");
            error = 1;
        }
        if (memo_date.trim() == "") {
            $("#memo_date_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Memo Date"]) }}</span>');
            $("#memo_date_error").css("display", "block");
            error = 1;
        }
        if (publish_date.trim() == "") {
            $("#publish_date_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Publish Date"]) }}</span>');
            $("#publish_date_error").css("display", "block");
            error = 1;
        }
        if (subject.trim() == "") {
            $("#subject_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Subject"]) }}</span>');
            $("#subject_error").css("display", "block");
            error = 1;
        }
        if (description.trim() == "") {
            $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Description"]) }}</span>');
            $("#description_error").css("display", "block");
            error = 1;
        }
        if (is_active.trim() == "") {
            $("#is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#is_active_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {

            $.ajax({
                url: "{{ URL::action('AdminController@submitMemo') }}",
                type: "POST",
                data: {
                    company: company,
                    file: file,
                    memo_type: memo_type,
                    memo_date: memo_date,
                    publish_date: publish_date,
                    expired_date: expired_date,
                    subject: subject,
                    description: description,
                    document_file_url: document_file_url,
                    remarks: remarks,
                    is_active: is_active
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.memos.store') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@memo") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }
</script>
<!-- End Page Scripts-->

@stop
