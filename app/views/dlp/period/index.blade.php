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
                        <table class="table table-hover nowrap table-own table-striped" id="dlp_period_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.cob') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.strata') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.duration') }} ({{ trans('Years') }})</th>
                                    <th style="width:20%;">{{ trans('app.forms.created_at') }}</th>
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
    $(document).ready( function () {
        let oTable = $('#dlp_period_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ route('dlp.period.list') }}",
            },
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 15,
            order: [[4, "desc"]],
            columns: [            
                {data: 'company_id', name: 'company.name'},    
                {data: 'file_id', name: 'files.file_no'},
                {data: 'strata_id', name: 'strata.name'},
                {data: 'duration', name: 'dlp_periods.duration'},
                {data: 'created_at', name: 'dlp_periods.created_at'},               
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}],
            responsive: false,
            scrollX: true,
        });
    });
</script>
@endsection