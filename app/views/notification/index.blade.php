@extends('layout.english_layout.default')

@section('content')
<style>
    #notification_table tbody tr.notification-unread td {
        font-weight: 600;
    }
    #notification_table tbody tr.notification-unread {
        box-shadow: inset 3px 0 0 #2c7be5;
    }
    #notification_table .notification-unread-label {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #fff;
        background: #2c7be5;
        padding: 1px 6px;
        border-radius: 3px;
        margin-right: 6px;
        vertical-align: middle;
    }
</style>
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ trans('app.forms.notification') }}</h3>
        </div>
        <div class="panel-body">
            
            @include('alert.bootbox')
            
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        @if(!Auth::user()->getAdmin())
                        <div class="margin-bottom-30">
                            <a href="javascript:void(0)" class="btn btn-own" onclick="markAll()">
                                {{ trans('app.forms.mark_all') }}
                            </a>
                        </div>
                        @endif

                        <table class="table table-hover table-own table-striped" id="notification_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:15%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.module') }}</th>
                                    <th style="width:25%;">{{ trans('app.forms.strata') }}</th>
                                    <th style="width:35%;">{{ trans('app.forms.description') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.created_at') }}</th>
                                    <th></th>
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
        $('#notification_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('notification.index') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[4, "desc"]],
            responsive: true,
            columns: [
                {data: 'file_no', name: 'files.file_no'},
                {data: 'module', name: 'notifications.module'},
                {data: 'strata', name: 'strata.name'},
                {data: 'description', name: 'notifications.description'},
                {data: 'created_at', name: 'notifications.created_at'},
                {data: 'is_view', name: 'notifications.is_view', visible: false, searchable: false},
            ],
            createdRow: function (row, data) {
                if (data.is_view == 0 || data.is_view === false || data.is_view === '0') {
                    $(row).addClass('notification-unread');
                }
            },
        });
    });
    function markAll() {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Confirm",
            closeOnConfirm: true
        }, function () {
            let route = "{{ route('notification.markAll') }}";
            $.ajax({
                url: route,
                type: "GET",
                beforeSend: function() {
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                },
                success: function (res) {
                    swal({
                        title: "{{ trans('app.successes.updated_successfully') }}",
                        text: "",
                        type: "success",
                        confirmButtonClass: "btn-success",
                        closeOnConfirm: false
                    });
                    if (res.success == true) {
                        location.reload();
                    }
                },
                complete: function() {
                    $.unblockUI();
                },
            });
        });
    }
</script>
@endsection