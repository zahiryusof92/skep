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
                        <h6>{{ trans('app.forms.file_no') }}: {{ $files->file_no }}</h6>
                        <div id="update_files_lists">
                            @include('page_en.nav.cob_file')
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="file_movement_tab" role="tabpanel">
                                    <section class="panel panel-pad">
                                        <div class="row padding-vertical-20">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    @if (AccessGroup::hasInsertModule('File Movement'))
                                                        <div class="col-lg-6">
                                                            <div class="margin-bottom-30">
                                                                <a href="{{ route('cob.file-movement.create', [\Helper\Helper::encode($files->id)]) }}"
                                                                    class="btn btn-own">
                                                                    {{ trans('app.buttons.add_file_movement') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="col-lg-6">
                                                        <div class="margin-bottom-30">
                                                            <a href="{{ route('cob.file-movement.print', [\Helper\Helper::encode($files->id)]) }}" target="_blank"
                                                                class="btn btn-own margin-inline pull-right">
                                                                <i class="fa fa-print" style="margin-right: 5px;"></i>
                                                                {{ trans('Print') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <table class="table table-hover nowrap table-own table-striped"
                                                    id="file_movement_table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:20%;">{{ trans('app.forms.name') }}</th>
                                                            <th style="width:20%;">{{ trans('app.forms.title') }}</th>
                                                            <th style="width:30%;">{{ trans('app.forms.assigned_to') }}</th>
                                                            <th style="width:30%;">{{ trans('app.forms.remarks') }}</th>
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
        $(document).ready(function() {
            $('#file_movement_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('cob.file-movement.index', [\Helper\Helper::encode($files->id)]) }}",
                lengthMenu: [
                    [5, 10, 50, -1],
                    [5, 10, 50, "All"]
                ],
                pageLength: 10,
                order: [
                    [0, "asc"]
                ],
                responsive: true,
                columns: [{
                        data: 'strata',
                        name: 'strata'
                    },
                    {
                        data: 'title',
                        name: 'file_movements.title'
                    },
                    {
                        data: 'file_movement_users',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'remarks',
                        name: 'file_movements.remarks'
                    },
                ],
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
