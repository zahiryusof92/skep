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
                        @if (AccessGroup::hasInsert(63) && !Str::contains(Request::url(), 'approval'))
                        <div class="margin-bottom-30">
                            <a href="{{ route('epks.create') }}" class="btn btn-own">
                                {{ trans('app.buttons.add_new_application') }}
                            </a>
                        </div>
                        @endif

                        <table class="table table-hover nowrap table-own table-striped" id="epks_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.address') }}</th>
                                    <th style="width:5%;">{{ trans('app.forms.status') }}</th>
                                    <th style="width:5%;">{{ trans('app.forms.created_at') }}</th>
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
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('#epks_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ $table_route }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[4, "desc"]],
            responsive: true,
            columns: [
                {data: 'file_id', name: 'files.file_no'},
                {data: 'strata_id', name: 'strata.name'},
                {data: 'address', name: 'epks.address_1'},
                {data: 'status', name: 'epks.status'},
                {data: 'created_at', name: 'epks.created_at'},
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