@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            
            @if (AccessGroup::hasInsert(58))
            <div class="row">
                <div class="col-lg-12">
                    <button onclick="window.location = '{{ URL::action('VendorController@create') }}'" type="button" class="btn btn-primary margin-bottom-25">
                        {{ trans('app.directory.vendors.create') }}
                    </button>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-12">                    
                    <table class="table table-hover" id="vendors_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:25%;">{{ trans('app.directory.vendors.name') }}</th>
                                <th style="width:25%;">{{ trans('app.directory.vendors.address') }}</th>
                                <th style="width:25%;">{{ trans('app.directory.vendors.council') }}</th>
                                <th style="width:15%;">{{ trans('app.directory.vendors.rating') }}</th>
                                <th style="width:10%;">{{ trans('app.directory.vendors.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- End  -->
</div>

@include('alert.bootbox')

<script>
    $(document).ready(function () {
        $('#vendors_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vendors.index') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[0, "asc"]],
            responsive: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: 'address', name: 'address'},
                {data: 'council', name: 'council', orderable: false, searchable: false},
                {data: 'rating', name: 'rating'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
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