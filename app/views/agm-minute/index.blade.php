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
                        <a href="{{ route('agm-minute.create') }}" class="btn btn-own">
                            {{ trans('app.forms.add') }}
                        </a>
                    </div>
                </div>
                @endif
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

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">    
                            <table class="table table-hover nowrap table-own table-striped" id="agm_minutes_table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.name') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.type_jmb_mc') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.agm_type') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.agm_date') }}</th>
                                        <th style="width:25%;">{{ trans('app.forms.description') }}</th>
                                        <th style="width:5%;"></th>
                                        <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
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
        var oTable = $('#agm_minutes_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ route('agm-minute.index') }}",
                'data': function(data) {
                    var file_id = $('#file_id').val();

                    // Append to data
                    data.file_id = file_id;
                }
            },
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[4, "desc"]],
            responsive: false,
            columns: [
                {data: 'file_id', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'type', name: 'type'},
                {data: 'agm_type', name: 'agm_type'},
                {data: 'agm_date', name: 'agm_date'},
                {data: 'description', name: 'description'},
                {data: 'check_status', name: 'description', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}]
        });

        $('select').on('select2:select', function (e) {
            oTable.draw();
        });

        $('select').on('select2:unselect', function (e) {
            $('#file_id').val("");
            oTable.draw();
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