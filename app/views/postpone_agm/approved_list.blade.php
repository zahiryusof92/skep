@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <div class="panel-body">
            <section class="panel panel-pad">

                @include('alert.bootbox')

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
                            </div>
                        </form>

                        <hr />

                    </div>

                    <div class="col-lg-12">
                        <table class="table table-hover nowrap table-own table-striped" id="postponed_agm_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.created_at') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.application_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.strata') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
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
    $(document).ready(function () {
        let oTable = $('#postponed_agm_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ route('postponeAGM.approved') }}",
                'data': function(data) {
                    var company = $('#company').val();
                    var start_date = $('#start_date').val();
                    var end_date = $('#end_date').val();
                    // Append to data
                    data.company = company;
                    data.start_date = start_date;
                    data.end_date = end_date;
                }
            },
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 15,
            order: [[0, "desc"]],
            responsive: true,
            columns: [
                {data: 'created_at', name: 'postponed_agms.created_at'},
                {data: 'application_no', name: 'postponed_agms.application_no'},
                {data: 'strata_id', name: 'strata.name'},
                {data: 'status', name: 'postponed_agms.status'},                
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}],
            responsive: false,
            scrollX: true,
        });
        $('#company').on('change', function () {
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
@endsection