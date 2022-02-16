@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">

            @include('alert.bootbox')

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <h6 class="margin-inline text-uppercase">{{ trans('app.my_point.available_point') }}</h6>
                            </div>
                            <div class="card-block">
                                <h1 class="margin-inline">{{ $total_point }}</h1>
                            </div>
                            <div class="card-footer">
                                <a href="{{ url('myPoint/reload') }}">{{ trans('app.my_point.reload_point') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-30">
                    <div class="col-md-12">
                        <table class="table table-hover nowrap table-own table-striped" id="point_transaction_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.my_point.created_at') }}</th>
                                    <th style="width:20%;">{{ trans('app.my_point.reference_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.my_point.type') }}</th>                                
                                    <th style="width:20%;">{{ trans('app.my_point.description') }}</th>
                                    <th style="width:20%;">{{ trans('app.my_point.point_usage') }}</th>
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

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('#point_transaction_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('myPoint.index') }}",
            lengthMenu: [[10, 25, 50], [10, 25, 50]],
            pageLength: 25,
            order: [[0, "desc"]],
            responsive: true,
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'reference_no', name: 'reference_no'},
                {data: 'type', name: 'type'},
                {data: 'description', name: 'description'},
                {data: 'point_usage', name: 'point_usage', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-right"}]
        });
    });
</script>
@endsection