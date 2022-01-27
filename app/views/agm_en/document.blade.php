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
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-15">
                    <div class="col-lg-12">
                        <?php if ($insert_permission == 1) { ?>
                            <button onclick="window.location = '{{ URL::action('AgmController@addDocument') }}'" type="button" class="btn btn-own margin-bottom-25">
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
                                        <select id="file_no" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $files_no)
                                            <option value="{{ $files_no->file_no }}">{{ $files_no->file_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <hr/>
                
                <div class="row">
                    <div class="col-lg-12">                    
                        <table class="table table-hover table-own table-striped" id="document" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.document_type') }}</th>
                                    <th style="width:35%;">{{ trans('app.forms.document_name') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.hidden') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.read_only') }}</th>
                                    <?php if ($update_permission == 1) { ?>
                                        <th style="width:10%;">{{ trans('app.forms.action') }}</th>
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
    $(document).ready(function () {
        oTable = $('#document').DataTable({
            "sAjaxSource": "{{URL::action('AgmController@getDocument')}}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[0, "asc"]],
            "responsive": true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
        
        $('#file_no').on('change', function () {
            oTable.columns(0).search(this.value).draw();
        });
    });

    function deleteDocument(id) {
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
            $.ajax({
                url: "{{ URL::action('AgmController@deleteDocument') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
                    if (data.trim() == "true") {
                        swal({
                            title: "{{ trans('app.successes.deleted_title') }}",
                            text: "{{ trans('app.successes.deleted_text_file') }}",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            closeOnConfirm: false
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        });
    }
</script>

@stop
