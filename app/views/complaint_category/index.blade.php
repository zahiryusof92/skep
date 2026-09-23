@extends('layout.english_layout.default')

@section('content')
    <style>
        .wrap-text {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        #complaint_category_table td {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>

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
                            @if (AccessGroup::hasInsertModule('Complaint Category'))
                                <div class="margin-bottom-30">
                                    <a href="{{ route('complaintCategory.create') }}" class="btn btn-own">
                                        {{ trans('app.buttons.add_complaint_category') }}
                                    </a>
                                </div>
                            @endif

                            <table class="table table-hover nowrap table-own table-striped" id="complaint_category_table"
                                style="table-layout: fixed; width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width:20%;">
                                            {{ trans('app.forms.name') }}
                                        </th>
                                        <th class="wrap-text" style="width:50%;">
                                            {{ trans('app.forms.description') }}
                                        </th>
                                        <th style="width:10%;">
                                            {{ trans('app.forms.sort_no') }}
                                        </th>
                                        <th style="width:10%;">
                                            {{ trans('app.forms.is_active') }}
                                        </th>
                                        <th style="width:10%;">
                                            {{ trans('app.forms.action') }}
                                        </th>
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
        $(document).ready(function() {
            $('#complaint_category_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('complaintCategory.index') }}",
                lengthMenu: [
                    [5, 10, 50, -1],
                    [5, 10, 50, "All"]
                ],
                pageLength: 10,
                order: [
                    [2, "asc"]
                ],
                responsive: false,
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        orderable: false,
                    },
                    {
                        data: 'sort_no',
                        name: 'sort_no',
                        searchable: false
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
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
