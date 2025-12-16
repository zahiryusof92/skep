@extends('layout.english_layout.default')

@section('content')

    <?php
    $insert_permission = 0;
    $update_permission = 0;
    
    foreach ($user_permission as $permission) {
        if ($permission->submodule_id == 33) {
            $insert_permission = $permission->insert_permission;
            $update_permission = $permission->update_permission;
        }
    }
    ?>

    <div class="page-content-inner">
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">

                @include('alert.bootbox')

                <section class="panel panel-pad">
                    <div class="row padding-vertical-15">
                        <div class="col-lg-12">
                            <?php if ($insert_permission == 1) { ?>
                            <button onclick="window.location = '{{ URL::action('AgmController@addDocument') }}'"
                                type="button" class="btn btn-own margin-bottom-25">
                                {{ trans('app.buttons.add_document') }}
                            </button>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ trans('app.forms.file_no') }}</label>
                                            <select id="file_id" name="file_id" class="form-control select2">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @foreach ($files as $files_no)
                                                    <option value="{{ $files_no->id }}">
                                                        {{ $files_no->file_no }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-hover table-own table-striped" id="document_list" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:20%;">
                                            {{ trans('app.forms.file_no') }}
                                        </th>
                                        <th style="width:25%;">
                                            {{ trans('app.forms.document_type') }}
                                        </th>
                                        <th style="width:35%;">
                                            {{ trans('app.forms.document_name') }}
                                        </th>
                                        <th style="width:10%;">
                                            {{ trans('app.forms.status') }}
                                        </th>
                                        <?php if ($update_permission == 1) { ?>
                                        <th style="width:10%;">
                                            {{ trans('app.forms.action') }}
                                        </th>
                                        <?php } ?>
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
        var oTable;
        $(document).ready(function() {
            var oTable = $('#document_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ URL::action('AgmController@getDocument') }}",
                    'data': function(data) {
                        var file_id = $('#file_id').val();

                        // Append to data
                        data.file_id = file_id;
                    }
                },
                lengthMenu: [
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                pageLength: 10,
                order: [
                    [0, "asc"]
                ],
                responsive: false,
                scrollX: true,
                columns: [{
                        data: 'file_id',
                        name: 'files.file_no'
                    },
                    {
                        data: 'document_type_id',
                        name: 'document_type.name'
                    },
                    {
                        data: 'name',
                        name: 'document.name'
                    },
                    {
                        data: 'status',
                        name: 'document.status'
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

            $('#file_id').on('change', function() {
                oTable.draw();
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
@stop
