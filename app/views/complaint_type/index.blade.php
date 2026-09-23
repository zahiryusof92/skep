@extends('layout.english_layout.default')

@section('content')
    <style>
        .wrap-text {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        #complaint_type_table td {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>

    <div class="page-content-inner">
        <section class="panel panel-style">

            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-10">
                        <h3>{{ $title }}</h3>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-own pull-right"
                            onclick="window.location ='{{ route('complaintCategory.index') }}'">
                            {{ trans('app.forms.back') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="panel-body">

                @include('alert.bootbox')

                <section class="panel panel-pad">
                    <div class="row padding-vertical-20">
                        <div class="col-lg-12">
                            @if (AccessGroup::hasInsertModule('Complaint Category'))
                                <div class="margin-bottom-30">
                                    <a href="{{ route('complaintCategory.complaintType.create', \Helper\Helper::encode($parent->id)) }}"
                                        class="btn btn-own">
                                        {{ trans('app.buttons.add_complaint_type') }}
                                    </a>
                                </div>
                            @endif

                            <table class="table table-hover nowrap table-own table-striped" id="complaint_type_table"
                                style="table-layout: fixed; width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width:70%;">
                                            {{ trans('app.forms.name') }}
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
            $('#complaint_type_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('complaintCategory.complaintType.index', \Helper\Helper::encode($parent->id)) }}",
                lengthMenu: [
                    [5, 10, 50, -1],
                    [5, 10, 50, "All"]
                ],
                pageLength: 10,
                order: [
                    [1, "asc"]
                ],
                responsive: false,
                columns: [{
                        data: 'name',
                        name: 'name'
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
