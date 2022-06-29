@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">                
                <div class="row padding-vertical-10">
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover table-own table-striped" id="reject-list" width="100%">
                            <thead>
                                <tr>                                
                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.section') }}</th>
                                    <th style="width:45%;">{{ trans('app.forms.remarks') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.created_at') }}</th>
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
    var oTable;
    $(document).ready(function () {
        oTable = $('#reject-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('file.draft.reject.index') }}",
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[2, "asc"], [1, 'asc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'file_no', name: 'files.file_no'},
                {data: 'type', name: 'type'},
                {data: 'remarks', name: 'remarks'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });

    function show(id) {
        let route = "{{ route('file.draft.reject.show', ':id') }}";
        route = route.replace(':id', id);
        $.ajax({
            url: route,
            type: "GET",
            success: function (data) {
                $("#modal-content").html(data);
                $("#file-reject").modal("show");
            }
        });
    }
</script>

@stop