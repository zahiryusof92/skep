@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ trans('app.forms.api_client') }}</h3>
        </div>
        <div class="panel-body">
            
            @include('alert.bootbox')
            
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        @if (AccessGroup::hasInsertModule("API Client"))
                        <div class="margin-bottom-30">
                            <a href="javascript:void(0)" class="btn btn-own" onclick="create()">
                                {{ trans('app.buttons.add_new') }}
                            </a>
                        </div>
                        @endif

                        <table class="table table-hover nowrap table-own table-striped" id="api_client_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:45%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.status') }}</th>
                                    <th style="width:25%;">{{ trans('app.forms.expired_date') }}</th>
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

<div id="modal-content"></div>
<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('#api_client_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('clients.index') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[0, "desc"]],
            responsive: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: 'status', name: 'status'},
                {data: 'expiry', name: 'expiry'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });
    });

    function create() {
        $.ajax({
            url: "{{ route('clients.create') }}",
            type: "GET",
            success: function (data) {
                $("#modal-content").html(data);
                $("#client").modal("show");
            }
        });
    }

    $('body').on('click', '.edit-btn', function (e) {
        let route = "{{ route('clients.edit', ':id') }}";
        route = route.replace(':id', $(this).data('id'));
        $.ajax({
            url: route,
            type: "GET",
            success: function (data) {
                $("#modal-content").html(data);
                $("#client").modal("show");
            }
        });
    })

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