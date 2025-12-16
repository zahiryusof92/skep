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
                            <table class="table table-hover nowrap table-own table-striped" id="eservice_table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:20%;">{{ trans('app.forms.eservice.last_orders') }}</th>
                                        <th style="width:30%;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.strata') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.eservice.total_orders') }}</th>
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
        $(document).ready(function() {
            let oTable = $('#eservice_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('eservice.view') }}",
                },
                lengthMenu: [
                    [15, 30, 50],
                    [15, 30, 50]
                ],
                pageLength: 15,
                order: [
                    [0, "desc"]
                ],
                responsive: true,
                columns: [
                    {
                        data: 'latest_order',
                        name: 'latest_order'
                    },
                    {
                        data: 'file_no',
                        name: 'files.file_no'
                    },
                    {
                        data: 'name',
                        name: 'strata.name'
                    },
                    {
                        data: 'total_orders',
                        name: 'total_orders'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    "targets": -1,
                    "className": "text-center"
                }],
                responsive: false,
                scrollX: true,
            });
        });
    </script>
@endsection
