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
                                {{ ($model->company ? $model->company->name : '-') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.file_no') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->file ? $model->file->file_no : '-') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.strata') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->strata ? (!empty($model->strata->name) ? $model->strata->name : '-') : '-') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.type') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ucwords($model->type) }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.development_cost') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->development_cost }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.amount') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->amount }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.date_start') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ (!empty($model->start_date) ? $model->start_date : '-') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.maturity_date') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ (!empty($model->maturity_date) ? $model->maturity_date : '-') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.date_vp') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ (!empty($model->vp_date) ? $model->vp_date : '-') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.balance') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->balance }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.checklist') }}
                            </dt>
                            <dd class="col-sm-9">
                                @foreach ($checklists as $key => $value)
                                <div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="checklist[]" value="{{ $key }}" {{
                                                ((!empty($model->checklist) ? in_array($key, json_decode($model->checklist, true)) ? 'checked' : '' : ''))
                                            }} disabled>
                                            {{ $value }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </dd>
                            @if ($model->status == DlpDeposit::RETURNED)
                            <dt class="col-sm-3">
                                {{ trans('app.forms.return_checklist') }}
                            </dt>
                            <dd class="col-sm-9">
                                @foreach ($returnChecklists as $key => $value)
                                <div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="return_checklist[]" value="{{ $key }}" {{
                                                ((!empty($model->return_checklist) ? in_array($key, json_decode($model->return_checklist, true)) ? 'checked' : '' : ''))
                                            }} disabled>
                                            {{ $value }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </dd>
                            @endif
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
                                {{ trans('app.forms.status') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->getStatusBadge() }}
                            </dd>
                            @if ($model->status == DlpDeposit::APPROVED)
                            <dt class="col-sm-3" style="margin-top: 20px;">
                                &nbsp;
                            </dt>
                            <dd class="col-sm-9" style="margin-top: 20px;">
                                <button class="btn btn-sm btn-own" data-toggle="modal" data-target="#returnForm">
                                    {{ trans('Return the deposit') }}
                                </button>
                            </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </section>

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        <div>
                            <h3>{{ trans('Usage of Deposit') }}</h3>
                        </div>

                        @if ($model->status == DlpDeposit::APPROVED)
                        <div class="margin-bottom-30">
                            <button class="btn btn-own" data-toggle="modal" data-target="#createForm">
                                {{ trans('app.buttons.add_new') }}
                            </button>
                        </div>
                        @endif

                        <table class="table table-hover nowrap table-own table-striped" id="dlp_deposit_usage_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.created_at') }}</th>
                                    <th style="width:45%;">{{ trans('app.forms.description') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.amount') }} (RM)</th>
                                    <th style="width:10%;">{{ trans('app.forms.attachment') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-default" id="cancel_button"
                        onclick="window.location ='{{ route('dlp.deposit') }}'">
                        {{ trans('app.forms.back') }}
                    </button>
                </div>
            </section>

        </div>
    </section>
</div>

@if ($model->status == DlpDeposit::APPROVED)
<div class="modal fade" id="createForm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id="form_create" enctype="multipart/form-data" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('Add New Usage') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="color: red; font-style: italic;">*
                                    {{ trans('app.forms.mandatory_fields') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">
                                    <span style="color: red;">*</span>
                                    {{ trans('app.forms.description') }}
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="5"></textarea>
                                @include('alert.feedback-ajax', ['field' => 'description'])
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">
                                    <span style="color: red;">*</span>
                                    {{ trans('app.forms.amount') }} (RM)
                                </label>
                                <input type="text" id="amount" name="amount" class="form-control" />
                                @include('alert.feedback-ajax', ['field' => 'amount'])
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">
                                    {{ trans('app.forms.attachment') }}
                                </label>
                                <br />
                                <input type="file" id="attachment_tmp" name="attachment_tmp"
                                    onChange="onUpload(this)" />
                                <input hidden id="attachment" name="attachment" />
                                <br />
                                <div id="attachment_preview"></div>
                                @include('alert.feedback-ajax', ['field' => 'attachment'])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}" />
                    <button id="submit_button" class="btn btn-own" type="submit">
                        {{ trans('app.forms.submit') }}
                    </button>
                    <button data-dismiss="modal" id="cancel_button" class="btn btn-default" type="button">
                        {{ trans('app.forms.cancel') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="returnForm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id="form_return" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('Return Deposit') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="color: red; font-style: italic;">*
                                    {{ trans('app.forms.mandatory_fields') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">
                                    <span style="color: red;">*</span>
                                    {{ trans('app.forms.amount') }}
                                </label>
                                <input type="text" id="return_amount" name="return_amount" value="{{ $model->balance }}"
                                    class="form-control" readonly />
                                @include('alert.feedback-ajax', ['field' => 'return_amount'])
                            </div>
                        </div>
                    </div>

                    @if ($returnChecklists)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">
                                    {{ trans('app.forms.checklist') }}
                                </label>
                                @foreach ($returnChecklists as $key => $value)
                                <div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="return_checklist[]" value="{{ $key }}" />
                                            {{ $value }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <img id="loading_return" style="display:none;"
                        src="{{asset('assets/common/img/input-spinner.gif')}}" />
                    <button id="submit_button_return" class="btn btn-own" type="submit">
                        {{ trans('app.forms.submit') }}
                    </button>
                    <button data-dismiss="modal" id="cancel_button_return" class="btn btn-default" type="button">
                        {{ trans('app.forms.cancel') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- modal -->
@endif

<script>
    $(document).ready( function () {
        let oTable = $('#dlp_deposit_usage_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dlp.deposit.usage', \Helper\Helper::encode($model->id)) }}",
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 15,
            order: [[0, "desc"]],
            columns: [         
                {data: 'created_at', name: 'created_at'},   
                {data: 'description', name: 'description'}, 
                {data: 'amount', name: 'amount'},
                {data: 'attachment', name: 'attachment', orderable: false, searchable: false},           
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}],
            responsive: false,
            scrollX: true,
        });

        $("#submit_button").click(function (e) {
            e.preventDefault();
            let formData = $('#form_create').serialize();
            $.ajax({
                url: "{{ route('dlp.deposit.usage.create', \Helper\Helper::encode($model->id)) }}",
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
                        $("#createForm").modal("hide");
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
                            $("#createForm").modal("hide");
                            bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
                        }
                    }
                },
                complete: function() {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                },
            });
        });

        $("#submit_button_return").click(function (e) {
            e.preventDefault();
            let formData = $('#form_return').serialize();
            $.ajax({
                url: "{{ route('dlp.deposit.return', \Helper\Helper::encode($model->id)) }}",
                type: "POST",
                data: formData,
                dataType: 'JSON',
                beforeSend: function() {
                    $('.help-block').text("");
                    $("#loading_return").css("display", "inline-block");
                    $("#submit_button_return").attr("disabled", "disabled");
                    $("#cancel_button_return").attr("disabled", "disabled");
                },
                success: function (res) {
                    console.log(res);                    
                    if (res.success == true) {
                        $("#returnForm").modal("hide");
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
                            $("#createForm").modal("hide");
                            bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
                        }
                    }
                },
                complete: function() {
                    $("#loading_return").css("display", "none");
                    $("#submit_button_return").removeAttr("disabled");
                    $("#cancel_button_return").removeAttr("disabled");
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
            url: "{{ route('dlp.deposit.usage.fileUpload') }}",
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

    function deleteUsage(id) {
        let route = "{{ route('dlp.deposit.usage.delete', ':id') }}";
            route = route.replace(':id', id);

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
                url: route,
                type: "POST",
                success: function (res) {
                    console.log(res);                    
                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>" + res.message + "</span>", function () {
                            location.reload();
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
                    }
                }
            });
        });
    }
</script>
@endsection