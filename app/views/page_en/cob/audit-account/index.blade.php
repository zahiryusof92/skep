@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            
            @include('alert.bootbox')
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file')
                        
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="audit_account_tab" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            @if (AccessGroup::hasInsertModule('Audit Account'))
                                            <div class="margin-bottom-30">
                                                <a href="{{ route('cob.audit-account.create', [\Helper\Helper::encode($files->id)]) }}" class="btn btn-own">
                                                    {{ trans('app.buttons.add_audit_account') }}
                                                </a>
                                            </div>
                                            @endif

                                            <table class="table table-hover nowrap table-own table-striped" id="audit_account_table" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30%;">{{ trans('app.forms.name') }}</th>
                                                        <th style="width:20%;">{{ trans('app.forms.submission_date') }}</th>
                                                        <th style="width:20%;">{{ trans('app.forms.closing_date') }}</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('#audit_account_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('cob.audit-account.index', [\Helper\Helper::encode($files->id)]) }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[0, "asc"]],
            responsive: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: 'submission_date', name: 'submission_date'},
                {data: 'closing_date', name: 'closing_date'},
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