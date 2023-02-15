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

                        <form id="postpone_agm_form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label">
                                        <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.agm_date') }}
                                        </label>
                                        <label class="input-group">
                                            <input type="text" id="agm_date" name="agm_date"
                                                class="form-control date_picker" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        @include('alert.feedback-ajax', ['field' => 'agm_date'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.new_agm_date') }}
                                        </label>
                                        <label class="input-group">
                                            <input type="text" id="new_agm_date" name="new_agm_date"
                                                class="form-control date_picker" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        @include('alert.feedback-ajax', ['field' => 'new_agm_date'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.reason') }}
                                        </label>
                                        @if ($reasons)
                                        <select id="reason" name="reason" class="form-control select2">
                                            <option value="" selected>{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($reasons as $key => $reason)
                                            <option value="{{ $key }}">
                                                {{ $reason }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'reason'])
                                        @endif                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.other_reason') }} (if have)
                                        </label>
                                        <textarea id="other_reason" name="other_reason" class="form-control" rows="5"
                                            placeholder="{{ trans('app.forms.reason') }}"></textarea>
                                        @include('alert.feedback-ajax', ['field' => 'other_reason'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
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

                            <div class="form-actions">
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('statusAGM.index') }}'">
                                    {{ trans('app.forms.cancel') }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </section>
        </div>

    </section>
</div>

<script>
    $(document).ready( function () {
        var year = new Date().getFullYear();
        var minDate = new Date();
        var maxDate = new Date(year, 11, 31);

        $('.select2').select2();

        $("#agm_date").datetimepicker({
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
            minDate: minDate,
            maxDate: maxDate,
            format: 'YYYY-MM-DD',
            useCurrent: false
        }).on("dp.change", function (e) {
            var days = 90;
            var newMaxDate = new Date(e.date);
            newMaxDate.setDate(newMaxDate.getDate() + days);
            var newYear = new Date(newMaxDate).getFullYear();
            if (newYear != year) {
                $('#new_agm_date').data("DateTimePicker").minDate(e.date).maxDate(maxDate);
            } else {
                $('#new_agm_date').data("DateTimePicker").minDate(e.date).maxDate(newMaxDate);
            }            
        });

        $("#new_agm_date").datetimepicker({
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
            minDate: minDate,
            maxDate: maxDate,
            format: 'YYYY-MM-DD',
            useCurrent: false
        }).on("dp.change", function (e) {
            $('#agm_date').data("DateTimePicker").maxDate(e.date);
        });

        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            let formData = $('form').serialize();
            $.ajax({
                url: "{{ route('statusAGM.store') }}",
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
                            let url = "{{ route('statusAGM.show', [':id']) }}";
                            url = url.replace(":id", res.id);
                            window.location = url;
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
            url: "{{ route('statusAGM.fileUpload') }}",
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