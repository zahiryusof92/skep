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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.date') }}
                                        </label>
                                        <label class="input-group">
                                            <input type="text" id="date" name="date"
                                                class="form-control date_picker" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        @include('alert.feedback-ajax', ['field' => 'date'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.percentage') }} (%)
                                        </label>
                                        <input type="text" id="percentage" name="percentage" class="form-control" />
                                        @include('alert.feedback-ajax', ['field' => 'percentage'])
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('dlp.deposit') }}'">
                                    {{ trans('app.forms.cancel') }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </section>

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <table class="table table-hover nowrap table-own table-striped" id="dlp_progress_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:30%;">{{ trans('app.forms.date') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.percentage') }} (%)</th>
                                    <th style="width:30%;">{{ trans('app.forms.created_at') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
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
                url: "{{ route('dlp.progress.store') }}",
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

        let oTable = $('#dlp_progress_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ route('dlp.progress.list') }}",
            },
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 15,
            order: [[2, "desc"]],
            columns: [
                {data: 'date', name: 'dlp_progresses.date'},
                {data: 'percentage', name: 'dlp_progresses.percentage'},
                {data: 'created_at', name: 'dlp_progresses.created_at'},               
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}],
            responsive: false,
            scrollX: true,
        });
    });
</script>
@endsection