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

                        <form id="dlp_form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label">
                                        <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.file_no') }}
                                        </label>
                                        <select class="form-control select2" id="file" name="file" {{ (!empty($model)
                                            ? 'disabled' : '' ) }}>
                                            @if (empty($model))
                                            <option value="">
                                                {{ trans('app.forms.please_select') }}
                                            </option>
                                            @foreach ($files as $file_no)
                                            <option value="{{$file_no->id}}">
                                                {{ $file_no->file_no }}
                                            </option>
                                            @endforeach
                                            @else
                                            <option value="{{ $model->file_id }}">{{ $model->file->file_no }}</option>
                                            @endif
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'file'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.type') }}
                                        </label>
                                        <select class="form-control select2" id="type" name="type"
                                            onchange="getType(this.value)" {{ (!empty($model) ? 'disabled' : '' ) }}>
                                            @if (empty($model))
                                            <option value="">
                                                {{ trans('app.forms.please_select') }}
                                            </option>
                                            <option value="commercial">
                                                {{ trans('app.forms.commercial') }}
                                            </option>
                                            <option value="residential">
                                                {{ trans('app.forms.residential') }}
                                            </option>
                                            @else
                                            <option value="commercial" {{ ($model->type == 'commercial' ? 'selected' :
                                                '') }}>
                                                {{ trans('app.forms.commercial') }}
                                            </option>
                                            <option value="residential" {{ ($model->type == 'commercial' ? 'residential'
                                                : '') }}>
                                                {{ trans('app.forms.residential') }}
                                            </option>
                                            @endif
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'type'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.development_cost') }} (RM)
                                        </label>
                                        @if (empty($model))
                                        <input type="text" id="development_cost" name="development_cost"
                                            class="form-control" onchange="calcAmount(this.value)" />
                                        @include('alert.feedback-ajax', ['field' => 'development_cost'])
                                        @else
                                        <input type="text" class="form-control" value="{{ $model->development_cost }}"
                                            readonly />
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.amount') }} (RM)
                                        </label>
                                        @if (empty($model))
                                        <input type="text" id="amount" name="amount" class="form-control" />
                                        @include('alert.feedback-ajax', ['field' => 'amount'])
                                        @else
                                        <input type="text" class="form-control" value="{{ $model->amount }}" readonly />
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.date_start') }}
                                        </label>
                                        @if (empty($model))
                                        <label class="input-group">
                                            <input type="text" id="start_date" name="start_date"
                                                class="form-control date_picker" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        @include('alert.feedback-ajax', ['field' => 'start_date'])
                                        @else
                                        <input type="text" class="form-control" value="{{ $model->start_date }}"
                                            readonly />
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.maturity_date') }}
                                        </label>
                                        @if (empty($model))
                                        <label class="input-group">
                                            <input type="text" id="maturity_date" name="maturity_date"
                                                class="form-control date_picker" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        @include('alert.feedback-ajax', ['field' => 'maturity_date'])
                                        @else
                                        <input type="text" class="form-control" value="{{ $model->maturity_date }}"
                                            readonly />
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($checklists)
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.checklist') }}
                                        </label>
                                        @if (empty($model))
                                        @foreach ($checklists as $key => $value)
                                        <div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="checklist[]" value="{{ $key }}">
                                                    {{ $value }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                        @include('alert.feedback-ajax', ['field' => 'checklist'])
                                        @else
                                        {{ print_r(json_decode($model->checklist), true) }}
                                        @foreach ($checklists as $key => $value)
                                        <div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="checklist[]" value="{{ $key }}" {{ (array_key_exists($key, json_decode($model->checklist)) ? 'checked' : '') }}>
                                                    {{ $value }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.attachment') }}
                                        </label>
                                        <br />
                                        @if (empty($model))
                                        <input type="file" id="attachment_tmp" name="attachment_tmp"
                                            onChange="onUpload(this)" />
                                        <input hidden id="attachment" name="attachment" />
                                        <br />
                                        <div id="attachment_preview"></div>
                                        @include('alert.feedback-ajax', ['field' => 'attachment'])
                                        @else
                                        @if (!empty($model->attachment))
                                        <a href="{{ asset($model->attachment) }}" target="_blank">
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="tooltip"
                                                data-placement="bottom" title="{{ trans('app.forms.attachment') }}">
                                                <i class="fa fa-file-pdf-o" style="margin-right: 2px;"></i>
                                                {{ trans('app.forms.attachment') }}
                                            </button>
                                        </a>
                                        @else
                                        -
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

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

                            <script>
                                $(document).ready( function () {
                                    $("#start_date").datetimepicker({
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
                                    }).on("dp.change", function (e) {
                                        var months = 12;
                                        var minDate = new Date(e.date);
                                        minDate.setMonth(minDate.getMonth() + months);
                                        $('#maturity_date').data("DateTimePicker").minDate(minDate);          
                                    });

                                    $("#maturity_date").datetimepicker({
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
                                    })
                            
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

                                function calcAmount(value) {
                                    var amount = '';

                                    if (value > 0 && value != '') {
                                        amount = value * 0.5;            
                                    }

                                    $('#amount').val(amount);
                                }

                                function getType(value) {                                    
                                    var months = 24;
                                    if (value == 'commercial') {
                                        months = 12;
                                    }
                                }
                            </script>
                            @endif

                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection