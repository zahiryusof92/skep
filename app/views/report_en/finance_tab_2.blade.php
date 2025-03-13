@extends('layout.english_layout.default')

@section('content')
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

                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link custom-tab" href="{{ route('report.finance.index') }}">
                                {{ trans('app.report.finance.tab_1') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active custom-tab">
                                {{ trans('app.report.finance.tab_2') }}
                            </a>
                        </li>
                    </ul>

                    <section class="panel panel-pad">
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" role="tabpanel">

                                <div class="row">
                                    @if (Auth::user()->getAdmin())
                                        @if (empty(Session::get('admin_cob')))
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ trans('app.forms.cob') }}</label>
                                                    <select id="cob" class="form-control select2">
                                                        <option value="">
                                                            {{ trans('app.forms.please_select') }}
                                                        </option>
                                                        @foreach ($cob as $companies)
                                                            <option value="{{ $companies->id }}">
                                                                {{ $companies->name }} ({{ $companies->short_name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ trans('app.forms.files') }}</label>
                                                    <select id="file" class="form-control select2">
                                                        <option value="">
                                                            {{ trans('app.forms.please_select') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ trans('app.forms.files') }}</label>
                                                    <select id="file" class="form-control select2">
                                                        <option value="">
                                                            {{ trans('app.forms.please_select') }}
                                                        </option>
                                                        @foreach ($files as $file)
                                                            <option value="{{ $file->id }}">
                                                                {{ $file->file_no }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        @if (empty(Auth::user()->file_id))
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ trans('app.forms.files') }}</label>
                                                    <select id="file" class="form-control select2">
                                                        <option value="">
                                                            {{ trans('app.forms.please_select') }}
                                                        </option>
                                                        @foreach ($files as $file)
                                                            <option value="{{ $file->id }}">
                                                                {{ $file->file_no }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group float-left">
                                            <label>{{ trans('app.forms.submission_date') }} </label><br>
                                            <input type="text" id="start_date"
                                                class="form-control width-150 display-inline-block" placeholder="From" />
                                            <span class="margin-right-10">&nbsp; —</span>
                                            <input type="text" id="end_date"
                                                class="form-control width-150 display-inline-block" placeholder="To" />
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-hover table-own table-striped" id="finance_table"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th style="width:5%;">{{ trans('app.forms.cob') }}</th>
                                                    <th style="width:10%;">{{ trans('app.forms.submission_date') }}</th>
                                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                                    <th style="width:20%;">{{ trans('app.forms.strata') }}</th>
                                                    <th style="width:10%;">{{ trans('app.forms.month') }}</th>
                                                    <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                                    <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </section>
    </div>

    <script>
        var oTable;

        $(document).ready(function() {
            oTable = $('#finance_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('report.finance.index') }}",
                    'data': function(data) {
                        var cob = $('#cob').val();
                        var file = $('#file').val();
                        var start_date = $('#start_date').val();
                        var end_date = $('#end_date').val();

                        // Append to data
                        data.cob = cob;
                        data.file = file;
                        data.start_date = start_date;
                        data.end_date = end_date;
                    }
                },
                lengthMenu: [
                    [15, 30, 50],
                    [15, 30, 50]
                ],
                pageLength: 30,
                order: [
                    [0, "asc"],
                    [1, 'desc'],
                ],
                bFilter: false,
                bInfo: false,
                responsive: false,
                scrollX: true,
                columns: [{
                        data: 'cob',
                        name: 'company.short_name'
                    },
                    {
                        data: 'created_at',
                        name: 'finance_file.created_at'
                    },
                    {
                        data: 'file_no',
                        name: 'files.file_no'
                    },
                    {
                        data: 'strata_name',
                        name: 'strata.name'
                    },
                    {
                        data: 'month',
                        name: 'finance_file.month'
                    },
                    {
                        data: 'year',
                        name: 'finance_file.year'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        orderable: false,
                    },

                ],
                "fnDrawCallback": function(oSettings) {
                    $.unblockUI();
                }
            });

            $('#cob').on('change', function() {
                findFile();
                getData();
            });
            $('#file').on('change', function() {
                getData();
            });
            $('#start_date').datetimepicker({
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
                format: 'YYYY-MM-DD',
            }).on('dp.change', function() {
                getData();
            });
            $('#end_date').datetimepicker({
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
                format: 'YYYY-MM-DD',
            }).on('dp.change', function() {
                getData();
            });
        });

        function getData() {
            $.blockUI({
                message: '{{ trans('app.confirmation.please_wait') }}'
            });
            oTable.draw();
        }

        function findFile() {
            $.ajax({
                url: "{{ URL::action('AdminController@findFile') }}",
                type: "POST",
                data: {
                    cob: $("#cob").val()
                },
                success: function(data) {
                    $("#file").html(data);
                }
            });
        }
    </script>
@endsection
