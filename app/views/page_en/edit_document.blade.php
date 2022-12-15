@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 3) {
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
                            <div class="tab-pane active" id="document_tab" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <form id="documentSubmit" class="form-horizontal" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if (Auth::user()->getCOB && Auth::user()->getCOB->short_name == "MBPJ")
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label style="color: red;">
                                                                * {{ trans('app.forms.mandatory_elements') }}    
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

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
                                                            &nbsp;<input type="file" name="document_file" id="document_file" accept="application/pdf" />
                                                            <div>
                                                                <small>* Accept PDF only. Maximum size: 10MB.</small>
                                                            </div>
                                                            <div id="validation-errors_document_file"></div>
                                                            @if ($document->file_url != "")
                                                            <a href="{{asset($document->file_url)}}" target="_blank"><button button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> {{ trans("app.forms.download") }}</button></a>
                                                            <?php if ($update_permission == 1) { ?>
                                                                <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteDocumentFile('{{ \Helper\Helper::encode($document->id) }}')"><i class="fa fa-times"></i></button>
                                                            <?php } ?>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <form>
                                                @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.status') }}</label>
                                                            <select id="status" class="form-control" name="status">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                <option value="{{ Document::PENDING }}" {{ $document->status == Document::PENDING ? 'selected' : '' }}>
                                                                    {{ trans("Pending") }}
                                                                </option>
                                                                <option value="{{ Document::APPROVED }}" {{ $document->status == Document::APPROVED ? 'selected' : '' }}>
                                                                    {{ trans("Approved") }}
                                                                </option>
                                                                <option value="{{ Document::REJECTED }}" {{ $document->status == Document::REJECTED ? 'selected' : '' }}>
                                                                    {{ trans("Rejected") }}
                                                                </option>
                                                            </select>
                                                            <div id="status_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ trans('Approval Remarks') }}</label>
                                                            <textarea class="form-control" id="approval_remark" name="approval_remark" rows="3">{{ $document->approval_remark }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($document->approvalBy)
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ trans('Approval By') }}</label>
                                                            <p>{{ $document->approvalBy->full_name }} ({{ $document->approvalBy->email }})</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if (!empty($document->approval_date))
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ trans('Approval Date') }}</label>
                                                            <p>{{ Helper\Helper::getFormattedDateTime($document->approval_date) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @else
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ trans('app.forms.status') }}</label>
                                                            <select class="form-control" disabled>
                                                                @if ($document->status == Document::PENDING)
                                                                <option value="{{ $document->status }}">
                                                                    {{ trans("Pending") }}
                                                                </option>
                                                                @elseif ($document->status == Document::APPROVED)
                                                                <option value="{{ $document->status }}">
                                                                    {{ trans("Approved") }}
                                                                </option>
                                                                @elseif ($document->status == Document::REJECTED)
                                                                <option value="{{ $document->status }}">
                                                                    {{ trans("Rejected") }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $document->status }}">
                                                                    -
                                                                </option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if (!empty($document->approval_remark))
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ trans('Approval Remarks') }}</label>
                                                            <p>{{ $document->approval_remark }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if ($document->approvalBy)
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ trans('Approval By') }}</label>
                                                            <p>{{ $document->approvalBy->full_name }} ({{ $document->approvalBy->email }})</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if (!empty($document->approval_date))
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ trans('Approval Date') }}</label>
                                                            <p>{{ Helper\Helper::getFormattedDateTime($document->approval_date) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endif

                                                <div class="form-actions">
                                                    <?php if ($update_permission == 1) { ?>
                                                        <input type="hidden" id="document_file_url" value="{{$document->file_url}}"/>
                                                        <button type="button" class="btn btn-own" id="submit_button" onclick="submitEditDocument()">{{ trans('app.forms.submit') }}</button>
                                                    <?php } ?>
                                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@document', \Helper\Helper::encode($document->file_id)) }}'">{{ trans('app.forms.cancel') }}</button>
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
                    $("#validation-errors_document_file").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
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
        changes = false;
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");

        var document_type = $("#document_type").val(),
                name = $("#name").val(),
                remarks = $("#remarks").val(),
                document_url = $("#document_file_url").val(),
                status = $('#status').val(),
                approval_remark = $('#approval_remark').val();

        var error = 0;

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

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitEditDocument') }}",
                type: "POST",
                data: {
                    document_type: document_type,
                    name: name,
                    remarks: remarks,
                    document_url: document_url,
                    status: status,
                    approval_remark: approval_remark,
                    id: "{{ \Helper\Helper::encode($document->id) }}"
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.documents.update') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@document", \Helper\Helper::encode($document->file_id)) }}';
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
                url: "{{ URL::action('AdminController@deleteDocumentFile') }}",
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
