@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        @include('alert.bootbox')

                        <dl class="row">
                            <dt class="col-sm-3">
                                {{ trans('app.forms.cob') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->company ? $model->company->name : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.file_no') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->file ? $model->file->file_no : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.strata') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->strata ? $model->strata->name : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.amount') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->amount }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.attachment') }}
                            </dt>
                            <dd class="col-sm-9">
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
                            <dt class="col-sm-3">
                                {{ trans('app.forms.maturity_date') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->maturity_date }}
                            </dd>
                        </dl>

                        <form id="dlp_form" class="form-horizontal" onsubmit="event.preventDefault();">

                            @if (!empty($model))
                            @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.status') }}
                                        </label>
                                        <select class="form-control select2" id="status" name="status"
                                            onchange="statusChange(this.value)" {{ ($model->status ==
                                            DlpDeposit::APPROVED ? 'disabled' : null) }}>
                                            @foreach($statusOptions as $key => $option)
                                            <option value="{{ $key }}" {{ ($model->status == $key ? 'selected' : '') }}>
                                                {{ $option }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => "status"])
                                    </div>
                                </div>
                            </div>

                            <div id="reject_field" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.approval_remark') }}
                                            </label>
                                            <textarea id="approval_remark" name="approval_remark" class="form-control"
                                                rows="5"></textarea>
                                            @include('alert.feedback-ajax', ['field' => "approval_remark"])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                @if ($model->status != DlpDeposit::APPROVED)
                                <img id="loading" style="display:none;"
                                    src="{{ asset('assets/common/img/input-spinner.gif') }}" />
                                <button type="button" class="btn btn-own" id="approval_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                @endif
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('dlp.deposit') }}'">
                                    {{ trans('app.forms.cancel') }}
                                </button>
                            </div>

                            <script>
                                function statusChange(value) {
                                    if (value == '{{ PostponedAGM::REJECTED }}') {
                                        $('#reject_field').show();
                                    }  else {
                                        $('#reject_field').hide();
                                    }
                                }

                                $("#approval_button").click(function (e) {
                                    e.preventDefault();
                                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                                    let formData = $('form').serialize();
                                    $.ajax({
                                        url: "{{ route('dlp.deposit.approval', \Helper\Helper::encode($model->id)) }}",
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
                                                
                                                if(res.message != "Validation Fail") {
                                                    bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
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
                            </script>
                            @else
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.status') }}
                                        </label>
                                        <input type="text" class="form-control" value="{{ Str::upper($model->status) }}"
                                            readonly />
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif

                            @if (empty($model))
                            <div class="form-actions">
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('dlp.deposit') }}'">
                                    {{ trans('app.forms.cancel') }}
                                </button>
                            </div>
                            @endif

                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>

</section>
</div>

<script>
    $(document).ready( function () {
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
            let formData = $('form').serialize();
            $.ajax({
                url: "{{ route('dlp.deposit.store') }}",
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
                        
                        if(res.message != "Validation Fail") {
                            bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
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
            url: "{{ route('dlp.fileUpload') }}",
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