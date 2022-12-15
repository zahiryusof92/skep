@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>

        <div class="panel-body">
            <section class="panel panel-pad">

                @include('alert.bootbox')

                @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <div class="widget widget-four background-transparent">
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-sm-6 col-xs-12">
                                    <a href="#">
                                        <div class="step-block step-success">
                                            <span class="step-digit">

                                            </span>
                                            <div class="step-desc">
                                                <span class="step-title">
                                                    {{ trans('Today Submission') }}
                                                </span>
                                                <p>
                                                    {{ EServiceOrder::todaySubmission() }}
                                                    {{ trans('record(s)') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-sm-6 col-xs-12">
                                    <a href="#">
                                        <div class="step-block" style="background: #fbdd51;">
                                            <span class="step-digit">

                                            </span>
                                            <div class="step-desc" style="color: #514d6a;">
                                                <span class="step-title">
                                                    {{ trans('Yesterday Submission') }}
                                                </span>
                                                <p>
                                                    {{ EServiceOrder::yesterdaySubmission() }}
                                                    {{ trans('record(s)') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-sm-6 col-xs-12">
                                    <a href="#">
                                        <div class="step-block step-warning">
                                            <span class="step-digit">

                                            </span>
                                            <div class="step-desc">
                                                <span class="step-title">
                                                    {{ trans('< 7 Days Submission') }}
                                                </span>
                                                <p>
                                                    {{ EServiceOrder::lessThan7DaysSubmission() }}
                                                    {{ trans('record(s)') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-sm-6 col-xs-12">
                                    <a href="#">
                                        <div class="step-block step-danger">
                                            <span class="step-digit">

                                            </span>
                                            <div class="step-desc">
                                                <span class="step-title">
                                                    {{ trans('> 7 Days Submission') }}
                                                </span>
                                                <p>
                                                    {{ EServiceOrder::moreThan7DaysSubmission() }}
                                                    {{ trans('record(s)') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row padding-vertical-20">
                    <div class="col-lg-12 text-center">
                        <form>
                            <div class="row">
                                @if (Auth::user()->getAdmin())
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select id="company" name="company" class="form-control select2">
                                            <option value="">{{ trans('app.forms.all') }}</option>
                                            @foreach ($company as $companies)
                                            <option value="{{ $companies->short_name }}">
                                                {{ $companies->name }} ({{ $companies->short_name }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.created_at') }} </label><br>
                                        <input id="start_date" name="start_date" data-column="0" type="text"
                                            style="width: 46%;" class="form-control display-inline-block"
                                            placeholder="From" />
                                        <span style="padding-right: 2%;padding-left: 2%;">&dash;</span>
                                        <input id="end_date" name="end_date" data-column="0" type="text"
                                            style="width: 46%;" class="form-control display-inline-block"
                                            placeholder="To" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.type') }}</label>
                                        <select id="letter_type" name="letter_type" class="form-control select2">
                                            <option value="">{{ trans('app.forms.all') }}</option>
                                            @foreach ($types as $type)
                                            <option value="{{ $type['id'] }}">
                                                {{ $type['text'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr />

                    </div>

                    <div class="col-lg-12">
                        @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                        <form action="{{ route('eservice.review') }}" method="POST">
                            <table class="table table-hover nowrap table-own table-striped" id="eservice_table"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">
                                            <div class="checkbox">
                                                <label for="selectAll">
                                                    <input type="checkbox" id="selectAll" />All
                                                </label>
                                            </div>
                                        </th>
                                        <th style="width:20%;">{{ trans('app.forms.created_at') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.order_no') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.type') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.strata') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <div class="form-actions">
                                <input type="submit" class="btn btn-success" name="approve"
                                    value="{{ trans('app.forms.approve') }}" />
                                <input type="submit" class="btn btn-danger" name="reject"
                                    value="{{ trans('app.forms.reject') }}" />
                            </div>
                        </form>

                        <script>
                            $(document).ready(function () {
                                $('#selectAll').click(function (e) {
                                    var table = $('#eservice_table').closest('table');
                                    $('td input:checkbox', table).prop('checked', this.checked);
                                });
                        
                                let oTable = $('#eservice_table').DataTable({
                                    processing: true,
                                    serverSide: true,
                                    ajax: {
                                        'url' : "{{ route('eservice.index') }}",
                                        'data': function(data) {
                                            var company = $('#company').val();
                                            var start_date = $('#start_date').val();
                                            var end_date = $('#end_date').val();
                                            var letter_type = $('#letter_type').val();

                                            // Append to data
                                            data.company = company;
                                            data.start_date = start_date;
                                            data.end_date = end_date;
                                            data.letter_type = letter_type;
                                        }
                                    },
                                    lengthMenu: [[15, 30, 50], [15, 30, 50]],
                                    pageLength: 15,
                                    order: [[1, "desc"]],
                                    responsive: true,
                                    columns: [
                                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                                        {data: 'created_at', name: 'eservices_orders.created_at'},
                                        {data: 'order_no', name: 'eservices_orders.order_no'},
                                        {data: 'type', name: 'eservices_orders.type'},
                                        {data: 'strata_id', name: 'strata.name'},
                                        {data: 'status', name: 'eservices_orders.status'},                
                                        {data: 'action', name: 'action', orderable: false, searchable: false}
                                    ],
                                    columnDefs: [{"targets": -1, "className": "text-center"}],
                                    responsive: false,
                                    scrollX: true,
                                });

                                $('#company').on('change', function () {
                                    oTable.draw();
                                });

                                $('#letter_type').on('change', function () {
                                    oTable.draw();
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
                                }).on('dp.change', function () {
                                    oTable.draw();                                    
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
                                }).on('dp.change', function () {
                                    oTable.draw();                                    
                                });
                            });
                        </script>
                        @else
                        <table class="table table-hover nowrap table-own table-striped" id="eservice_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.created_at') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.order_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.type') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.strata') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <script>
                            $(document).ready(function () {
                                let oTable = $('#eservice_table').DataTable({
                                    processing: true,
                                    serverSide: true,
                                    ajax: {
                                        'url' : "{{ route('eservice.index') }}",
                                        'data': function(data) {
                                            var company = $('#company').val();
                                            var start_date = $('#start_date').val();
                                            var end_date = $('#end_date').val();
                                            var letter_type = $('#letter_type').val();

                                            // Append to data
                                            data.company = company;
                                            data.start_date = start_date;
                                            data.end_date = end_date;
                                            data.letter_type = letter_type;
                                        }
                                    },
                                    lengthMenu: [[15, 30, 50], [15, 30, 50]],
                                    pageLength: 15,
                                    order: [[0, "desc"]],
                                    responsive: true,
                                    columns: [
                                        {data: 'created_at', name: 'eservices_orders.created_at'},
                                        {data: 'order_no', name: 'eservices_orders.order_no'},
                                        {data: 'type', name: 'eservices_orders.type'},
                                        {data: 'strata_id', name: 'strata.name'},
                                        {data: 'status', name: 'eservices_orders.status'},                
                                        {data: 'action', name: 'action', orderable: false, searchable: false}
                                    ],
                                    columnDefs: [{"targets": -1, "className": "text-center"}],
                                    responsive: false,
                                    scrollX: true,
                                });

                                $('#company').on('change', function () {
                                    oTable.draw();
                                });

                                $('#letter_type').on('change', function () {
                                    oTable.draw();
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
                                }).on('dp.change', function () {
                                    oTable.draw();                                    
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
                                }).on('dp.change', function () {
                                    oTable.draw();                                    
                                });
                            });
                        </script>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection