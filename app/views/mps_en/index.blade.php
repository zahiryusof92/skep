@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-md-6">
                        <button class="btn btn-primary" id="btn_sync" onclick="syncMPSFiles()" title="Sync">
                            {{ trans('Sync MPS Files') }} &nbsp;<i class="fa fa-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="row padding-vertical-10">
                    <div class="col-lg-12">
                        <h5>{{ trans('File Sync Log') }}</h5>
                        <table class="table table-hover nowrap table-own table-striped" id="files_list"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('Date Synced') }}</th>
                                    <th style="width:60%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:20%;">{{ trans('Sync Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr/>

                <div class="row padding-vertical-10">
                    <div class="col-lg-12">
                        <h5>{{ trans('Finance Sync Log') }}</h5>
                        <table class="table table-hover nowrap table-own table-striped" id="finances_list"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('Date Synced') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.finance_management') }}</th>
                                    <th style="width:20%;">{{ trans('Sync Status') }}</th>
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
    function syncMPSFiles() {
        bootbox.confirm("{{ trans('app.confirmation.are_you_sure_submit') }}", function (result) {
            if (result) {
                $("#btn_sync").prop("disabled", true);
                $.ajax({
                    url: "{{ URL::action('Api\FileController@submitSync') }}",
                    type: "POST",
                    success: function (data) {
                        console.log(data);
                        $("#btn_sync").removeAttr("disabled");
                        if (data.trim() === "true") {
                            bootbox.alert("<span style='color:green;'>{{ trans('app.successes.file_sync.store') }}</span>", function () {
                                window.location.reload();
                            });
                        } else {
                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    },
                });
            }
        });
    }
                    
    var oTable;
    $(document).ready(function () {
        oTable = $('#files_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ URL::action('MPSSyncController@getFileList') }}",
            },
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 30,
            order: [[0, "desc"]],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'created_at', name: 'file_sync_log.created_at'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'status', name: 'file_sync_log.status'},                
            ]
        });

        oTable = $('#finances_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ URL::action('MPSSyncController@getFinanceList') }}",
            },
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 30,
            order: [[0, "desc"]],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'created_at', name: 'finance_sync_log.created_at'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'finance_file_no', name: 'finance_file.finance_file_no'},
                {data: 'status', name: 'finance_sync_log.status'},                
            ]
        });
    });
</script>
@stop