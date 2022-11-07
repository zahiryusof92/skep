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
                    <div class="col-lg-12">

                        <div class="margin-bottom-30">
                            <a href="{{ route('epksStatement.create') }}" class="btn btn-own">
                                {{ trans('app.buttons.add_epks_statement') }}
                            </a>
                        </div>

                        <table class="table table-hover nowrap table-own table-striped" id="epks_statement_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:10%;">{{ trans('app.forms.month') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.created_at') }}</th>
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

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('#epks_statement_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('epksStatement.index') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[0, 'desc'], [1, 'desc']],
            responsive: true,
            columns: [
                {data: 'month', name: 'month'},
                {data: 'year', name: 'year'},
                {data: 'file_id', name: 'file_id'},
                {data: 'strata_id', name: 'strata_id'},                
                {data: 'created_at', name: ' created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}]
        });
    });

    $('body').on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        let formId = $(this).data('id');

        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        }, function () {
            $('#' + formId).submit();
        });
    });
</script>
@endsection