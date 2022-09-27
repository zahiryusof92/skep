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

                    <div class="col-lg-12">

                        <a href="{{ route('postponeAGMReason.create') }}">
                            <button type="button" class="btn btn-own">
                                {{ trans('app.buttons.postpone_agm_reason') }}
                            </button>
                        </a>

                        <br /><br />

                        <table class="table table-hover nowrap table-own table-striped" id="postponed_agm_reason_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:50%;">{{ trans('app.forms.reason') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.sort_no') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.active') }}</th>
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
    $(document).ready(function () {
        let oTable = $('#postponed_agm_reason_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ route('postponeAGMReason.index') }}",
            },
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 15,
            order: [[1, "asc"]],
            columns: [
                {data: 'name', name: 'name'}, 
                {data: 'sort', name: 'sort'},
                {data: 'active', name: 'active'}, 
                {data: 'created_at', name: 'created_at'},              
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}],
            responsive: false,
            scrollX: true,
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