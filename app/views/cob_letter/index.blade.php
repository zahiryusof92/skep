@extends('layout.english_layout.default_custom')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ trans('app.forms.cob_letter') }}</h3>
        </div>
        <div class="panel-body">
            
            @include('alert.bootbox')
            
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        @if (AccessGroup::hasInsertModule("COB Letter"))
                        <div class="margin-bottom-30">
                            <a href="javascript:void(0)" class="btn btn-own" onclick="create()">
                                {{ trans('app.buttons.add_new') }}
                            </a>
                        </div>
                        @endif

                        <table class="table table-hover nowrap table-own table-striped" id="cob_letter_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:45%;">{{ trans('app.forms.type') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.date') }}</th>
                                    <th style="width:25%;">{{ trans('app.forms.created_at') }}</th>
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
        $('#cob_letter_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('cob_letter.index') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[1, "desc"]],
            responsive: true,
            columns: [
                {data: 'type', name: 'type'},
                {data: 'date', name: 'date'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}]
        });
    });

    function create() {
        $.ajax({
            url: "{{ route('cob_letter.create') }}",
            type: "GET",
            success: function (data) {
                $("#modal-content").html(data);
                $("#cob-letter").modal("show");
            }
        });
    }

    $('body').on('click', '.edit-btn', function (e) {
        let route = "{{ route('cob_letter.edit', ':id') }}";
        route = route.replace(':id', $(this).data('id'));
        $.ajax({
            url: route,
            type: "GET",
            success: function (data) {
                $("#modal-content").html(data);
                $("#cob-letter").modal("show");
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