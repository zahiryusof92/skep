@extends('layout.english_layout.default')

@section('content')
    <?php
    $company = Company::find(Auth::user()->company_id);
    ?>

    <div class="page-content-inner">
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">
                <div class="invoice-block">

                    <div class="row">
                        <table width="100%">
                            <tr>
                                <td class="text-center">
                                    <h4 class="margin-bottom-0">
                                        <img src="{{ asset($company->image_url) }}" height="100px;" alt="">
                                    </h4>
                                </td>
                                <td>
                                    <h5 class="margin-bottom-10">
                                        {{ $company->name }}
                                    </h5>
                                    <h6 class="margin-bottom-0">
                                        {{ $title }}
                                    </h6>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <hr />

                    <section class="panel panel-pad">
                        <div class="row margin-top-30 margin-bottom-30">
                            <div class="col-lg-6">
                                <span style="font-size: 12px;">
                                    <b>
                                        {{ trans('app.forms.date') }}:
                                    </b>
                                </span>
                                &nbsp;
                                <input style="font-size: 12px;" id="date_from" data-column="0" type="text"
                                    class="form-control width-250 display-inline-block datetimepicker" placeholder="From" />
                                <span style="font-size: 12px;">&nbsp; &dash; &nbsp;</span>
                                <input style="font-size: 12px;" id="date_to" data-column="0" type="text"
                                    class="form-control width-250 display-inline-block datetimepicker" placeholder="To" />
                            </div>
                            <div class="col-lg-6">
                                <form target="_blank" action="{{ route('export.fileMovement') }}" method="POST">
                                    <div class="text-right">
                                        <input type="hidden" name="export_date_from" id="export_date_from">
                                        <input type="hidden" name="export_date_to" id="export_date_to">
                                        <button type="submit" class="btn btn-own" data-toggle="tooltip"
                                            data-placement="top" title="Export">
                                            <i class="fa fa-file-excel-o"></i>&nbsp; {{ trans('Export') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-hover table-own table-striped" id="file_movement_table"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:15%;">{{ trans('app.forms.date') }}</th>
                                            <th style="width:25%;">{{ trans('app.forms.file_no') }}</th>
                                            <th style="width:25%;">{{ trans('app.forms.name') }}</th>
                                            <th style="width:35%;">{{ trans('app.forms.assigned_to') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            oTable = $('#file_movement_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('report.fileMovement.index') }}",
                    'data': function(data) {
                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();

                        $('#export_date_from').val(date_from);
                        $('#export_date_to').val(date_to);

                        // Append to data
                        data.date_from = date_from;
                        data.date_to = date_to;
                    }
                },
                "order": [
                    [0, "desc"]
                ],
                "lengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                "pageLength": 25,
                "scrollX": true,
                "responsive": false,
                "columns": [{
                        data: 'movement_date',
                        name: 'file_movement_users.created_at',
                    },
                    {
                        data: 'file_no',
                        name: 'files.file_no',
                    },
                    {
                        data: 'strata_name',
                        name: 'strata.name',
                    },
                    {
                        data: 'appointed_name',
                        name: 'users.full_name',
                    },
                ],
                "fnDrawCallback": function(oSettings) {
                    $.unblockUI();
                }
            });

            $('.datetimepicker').datetimepicker({
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
                format: 'DD-MM-YYYY'
            }).on('dp.change', function() {
                getData();
            });
        });

        function getData() {
            $.blockUI({
                message: '{{ trans('app.confirmation.please_wait') }}'
            });
            $.ajax({
                url: "{{ route('report.fileMovement.index') }}",
                type: "GET",
                data: {
                    date_from: $('#date_from').val(),
                    date_to: $('#date_to').val(),
                    filter: true,
                },
                success: function(res) {
                    oTable.draw();
                }
            });
        }
    </script>
@endsection
