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
                @if (AccessGroup::hasInsert(32))
                <div class="row padding-vertical-10">
                    <div class="col-md-2">
                        <a href="{{ route('strata-meeting-document.create') }}" class="btn btn-own">
                            {{ trans('app.forms.add') }}
                        </a>
                    </div>
                </div>
                @endif

                <h4>{{ trans('app.strata_meeting_documents.list_estrata') }}</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.file_no') }}</label>
                                    <select id="file_id" name="file_id" class="form-control select2" data-ajax--url="{{ route('v3.api.files.getOption') }}" data-ajax--cache="true"
                                            data-placeholder="{{ trans('app.forms.please_select') }}" data-allow-clear="true">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table class="table table-hover nowrap table-own table-striped" id="strata_meeting_documents_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ trans('app.forms.file_no') }}</th>
                                <th>{{ trans('app.forms.name') }}</th>
                                <th>{{ trans('app.forms.strata_meeting_timing') }}</th>
                                <th>{{ trans('app.forms.agm_type') }}</th>
                                <th>{{ trans('app.forms.agm_date') }}</th>
                                <th>{{ trans('app.forms.description') }}</th>
                                <th></th>
                                <th>{{ trans('app.forms.status') }}</th>
                                <th>{{ trans('app.forms.action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <hr/>
                <h4>{{ trans('app.strata_meeting_documents.list_legacy') }}</h4>
                <div class="table-responsive">
                    <table class="table table-hover nowrap table-own table-striped" id="legacy_meeting_documents_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ trans('app.forms.file_no') }}</th>
                                <th>{{ trans('app.forms.name') }}</th>
                                <th>{{ trans('app.forms.agm_date') }}</th>
                                <th>{{ trans('app.forms.meeting') }}</th>
                                <th></th>
                                <th>{{ trans('app.forms.copy_list') }}</th>
                                <th></th>
                                <th>{{ trans('app.forms.financial_report') }}</th>
                                <th></th>
                                <th>{{ trans('app.forms.recent_update') }}</th>
                                <th>{{ trans('app.forms.status') }}</th>
                                <th>{{ trans('app.forms.action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
        var oTable = $('#strata_meeting_documents_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('strata-meeting-document.index') }}",
                data: function (data) {
                    data.file_id = $('#file_id').val();
                }
            },
            pageLength: 10,
            order: [[4, 'desc']],
            columns: [
                {data: 'file_id', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'type', name: 'meeting_documents.type'},
                {data: 'agm_type', name: 'meeting_documents.agm_type'},
                {data: 'agm_date', name: 'meeting_documents.agm_date'},
                {data: 'description', name: 'description', orderable: false},
                {data: 'check_status', orderable: false, searchable: false},
                {data: 'status', name: 'strata_meeting_document_statuses.status'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{targets: -1, className: 'text-center'}]
        });

        $('#file_id').on('select2:select select2:unselect', function () {
            oTable.draw();
        });

        $('#legacy_meeting_documents_table').DataTable({
            sAjaxSource: "{{ URL::action('AgmController@getMinutes') }}",
            lengthMenu: [[10, 25, 50], [10, 25, 50]],
            order: [[2, 'desc']],
            aoColumnDefs: [{bSortable: false, aTargets: [3, 4, 5, 6, 7, 8, -1]}]
        });
    });

    $('body').on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        var formId = $(this).data('id');
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
