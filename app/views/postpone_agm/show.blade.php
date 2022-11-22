@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <?php 
            $readonly = ((Auth::user()->getAdmin() || Auth::user()->isCOB()) && in_array($model->status, [PostponedAGM::PENDING])) ? "" : "readonly";
        ?>

        <div class="panel-body">

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <form id="postponed_agm_form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <dl class="row">
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.application_no') }}
                                </dt>
                                <dd class="col-sm-8">
                                    #{{ $model->application_no }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.created_at') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ (!empty($model->created_at) ? \Helper\Helper::getFormattedDateTime($model->created_at) : '-') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.file_no') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ ($model->file ? $model->file->file_no : '') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.strata') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ ($model->strata ? (!empty($model->strata->name) ? $model->strata->name : '-') : '-') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.agm_date') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ (!empty($model->agm_date) ? \Helper\Helper::getFormattedDate($model->agm_date) : '-') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.new_agm_date') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ (!empty($model->new_agm_date) ? \Helper\Helper::getFormattedDate($model->new_agm_date) : '-') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.reason') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ ($model->postponedAGMReason ? $model->postponedAGMReason->name : '-') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.other_reason') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ (!empty($model->reason) ? $model->reason : '-') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.attachment') }}
                                </dt>
                                <dd class="col-sm-8">
                                    @if (!empty($model->attachment))
                                    <a href="{{ asset($model->attachment) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-success" data-toggle="tooltip"
                                            data-placement="bottom" title="{{ trans('app.forms.attachment') }}">
                                            <i class="fa fa-file-pdf-o" style="margin-right: 2px;"></i>
                                            {{ trans('app.forms.attachment') }}
                                        </button>
                                    </a>
                                    @else
                                    -
                                    @endif
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.submit_by') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->user->full_name }} ({{ $model->user->email }})
                                </dd>

                                @if ($model->status == PostponedAGM::PENDING && (Auth::user()->getAdmin() ||
                                Auth::user()->isCOB()))
                                <dt class="col-sm-4">
                                    <span style="color: red;">*</span>
                                    {{ trans('app.forms.status') }}
                                </dt>
                                <dd class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select
                                                class="form-control select2"
                                                id="status" name="status" onchange="statusChange(this.value)" {{
                                                $readonly ? "disabled" : "" }}>
                                                @foreach($statusOptions as $key => $option)
                                                <option value="{{ $key }}" {{ ($model->status == $key ? "selected" : "") }}>
                                                    {{ $option }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('alert.feedback-ajax', ['field' => "status"])
                                        </div>
                                    </div>
                                </dd>

                                <div id="reject_field"
                                    style="display: none;">
                                    <dt class="col-sm-4">
                                        <span style="color: red;">*</span>
                                        {{ trans('app.forms.approval_remark') }}
                                    </dt>
                                    <dd class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <textarea id="approval_remark" name="approval_remark"
                                                    class="form-control"
                                                    rows="5"></textarea>
                                                @include('alert.feedback-ajax', ['field' => "approval_remark"])
                                            </div>
                                        </div>
                                    </dd>
                                </div>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_attachment') }}
                                </dt>
                                <dd class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="file" id="approval_attachment_tmp" name="approval_attachment_tmp"
                                                onChange="onUpload(this)" />
                                            <input hidden id="approval_attachment" name="approval_attachment" />
                                            <br />
                                            <div id="approval_attachment_preview"></div>
                                            @include('alert.feedback-ajax', ['field' => 'approval_attachment'])
                                        </div>
                                    </div>
                                </dd>

                                @else
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.status') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->getStatusText() }}
                                </dd>

                                @if ($model->approver)
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_by') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->approver->full_name }}
                                </dd>
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_date') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ (!empty($model->approval_date) ? \Helper\Helper::getFormattedDateTime($model->approval_date) : '-') }}
                                </dd>
                                @if (!empty($model->approval_remark))
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_remark') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->approval_remark }}
                                </dd>
                                @endif
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_attachment') }}
                                </dt>
                                <dd class="col-sm-8">
                                    @if (!empty($model->approval_attachment))
                                    <a href="{{ asset($model->approval_attachment) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-success" data-toggle="tooltip"
                                            data-placement="bottom" title="{{ trans('app.forms.attachment') }}">
                                            <i class="fa fa-file-pdf-o" style="margin-right: 2px;"></i>
                                            {{ trans('app.forms.attachment') }}
                                        </button>
                                    </a>
                                    @else
                                    -
                                    @endif
                                </dd>
                                @endif

                                @endif
                            </dl>


                            @if ($model->status == PostponedAGM::PENDING && (Auth::user()->getAdmin() ||
                            Auth::user()->isCOB()))
                            <div class="form-actions">
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                            </div>
                            @endif
                        </form>

                    </div>

                </div>
            </section>

            <div class="form-actions">
                @if ($model->status == PostponedAGM::APPROVED)
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('statusAGM.index') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @elseif ($model->status == PostponedAGM::APPROVED)
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('statusAGM.approved') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @elseif ($model->status == PostponedAGM::REJECTED)
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('statusAGM.rejected') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @else
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('statusAGM.index') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @endif
            </div>

        </div>

    </section>
    <!-- End -->
</div>

<script>
    $(document).ready( function () {
        $('.select2').select2();

        $(".date_picker").datetimepicker({
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

        $("#submit_button").click(function (e) {
            e.preventDefault();

            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            let route = "{{ route('statusAGM.submitByCOB', [':id']) }}";
            route = route.replace(':id', "{{ \Helper\Helper::encode($model->id) }}");
            let formData = $('#postponed_agm_form').serialize();
            $.ajax({
                url: route,
                type: "POST",
                data: formData,
                dataType: 'JSON',
                beforeSend: function() {
                    $('.help-block').text("");
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                },
                success: function (res) {
                    console.log(res);
                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>" + res.message + "</span>", function () {
                            location.reload();
                        });
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                if(key.includes('_tmp')) {
                                    let myId = key.replace(/_tmp/g, '');
                                    $("#" + myId + "_error").children("strong").text(value);
                                } else {
                                    $("#" + key + "_error").children("strong").text(value);
                                }
                            });
                        }
                        
                        if (res.message != "Validation Fail") {
                            bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
                        } else {
                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    }
                },
                complete: function() {
                    $.unblockUI();
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                },
            });                          
        });
    });

    function statusChange(value) {
        if (value == '{{ PostponedAGM::REJECTED }}') {
            $('#reject_field').show();
        }  else {
            $('#reject_field').hide();
        }
    }

    function onUpload(e) {
        let id = e.getAttribute('id');
        let myId = id.replace(/_tmp/g, '');
        let data = new FormData();
        if (e.files.length > 0) {
            data.append(myId, e.files[0]);
        }
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{ route('statusAGM.approvalUpload') }}",
            data: data,
            async: true,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            beforeSubmit: function() {
                console.log(myId);
                $("#"+ myId + "_error").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#"+ myId + "_error").html(value);
                        }
                    });
                    $("#"+ myId + "_error").show();
                    $("#"+ myId + "_tmp").css("color", "red");
                } else {
                    if(response.error == true) {
                        bootbox.alert("<span style='color:red;'>" + response.message + "</span>");
                    } else {
                        $("#" + myId + "_preview").html("<button id='clear_" + id + "' class='btn btn-xs btn-danger' onclick='clearFile(this)'><i class='fa fa-times'></i> Delete</button>");
                        $("#clear_" + myId).show();
                        $("#" + myId + "_preview").show();
                        $("#" + myId + "_tmp").css("color", "green");
                        $("#" + myId).val(response.file);
                    }
                }
            }
        });
    }

    function clearFile(e) {
        let id = e.getAttribute('id');
        let name = e.getAttribute('name');
        id = id.replace("clear_", "");
        let myId = id.replace(/_tmp/g, '');;;
        
        $("#" + myId).val("");
        $("#" + myId + "_tmp").val("");
        $("#" + myId + "_tmp").css("color", "");
        $("#" + myId + "_preview").empty().hide();
    }
</script>
@endsection