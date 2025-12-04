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
                    <div class="row padding-vertical-20">
                        <div class="col-lg-12">
                            @if (AccessGroup::hasInsertModule('TPPM'))
                                <div class="margin-bottom-30">
                                    <a href="{{ route('tppm.create') }}" class="btn btn-own">
                                        {{ trans('app.buttons.add_new_application') }}
                                    </a>
                                </div>
                            @endif

                            <table class="table table-hover nowrap table-own table-striped" id="tppm_table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:20%;">{{ trans('app.forms.tppm.file_no') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.tppm.scheme_name') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.tppm.applicant_name') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.tppm.first_purchase_price') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.tppm.status_name') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.tppm.created_at') }}</th>
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
        $(document).ready(function() {
            $('#tppm_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tppm.index') }}",
                lengthMenu: [
                    [5, 10, 50, -1],
                    [5, 10, 50, "All"]
                ],
                pageLength: 10,
                order: [
                    [5, "desc"]
                ],
                responsive: true,
                columns: [{
                        data: 'file_id',
                        name: 'files.file_no'
                    },
                    {
                        data: 'strata_id',
                        name: 'strata.name'
                    },
                    {
                        data: 'applicant',
                        name: 'tppms.applicant_name'
                    },
                    {
                        data: 'first_purchase_price',
                        name: 'tppms.first_purchase_price',
                        render: $.fn.dataTable.render.number(',', '.', 2, 'RM '),
                        className: 'text-right'
                    },
                    {
                        data: 'status',
                        name: 'tppms.status'
                    },
                    {
                        data: 'created_at',
                        name: 'tppms.created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                @if (AccessGroup::hasUpdateModule('TPPM'))
                rowCallback: function(row, data) {
                    var raw = (data && data.first_purchase_price != null) ? String(data
                        .first_purchase_price) : '';
                    var numeric = parseFloat(raw.replace(/[^0-9.\-]/g, ''));
                    var $row = $(row);
                    // reset previous state on redraw
                    $row.removeClass('bg-danger');
                    if (!isNaN(numeric) && numeric > 300000) {
                        // add common danger classes and force a brighter red background for visibility
                        $row.addClass('bg-danger');
                    }
                },
                @endif
                columnDefs: [{
                    "targets": -1,
                    "className": "text-center"
                }]
            });
        });

        $('body').on('click', '.confirm-delete', function(e) {
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
            }, function() {
                $('#' + formId).submit();
            });
        });
    </script>
@endsection
