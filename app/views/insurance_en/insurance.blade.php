@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 46) {
        $insert_permission = $permissions->insert_permission;
    }
}

$update_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 46) {
        $update_permission = $permissions->update_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($insert_permission) { ?>
                        <button onclick="window.location = '{{ URL::action('AdminController@addInsurance', ['All']) }}'" type="button" class="btn btn-primary margin-bottom-25">
                            {{ trans('app.buttons.add_insurance') }}
                        </button>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 text-center">
                    <form>
                        <div class="row">
                            @if ($files)
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
                            @endif
                            
                            @if ($filename)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.file_name') }}</label>
                                    <select id="file_name" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($filename as $name)
                                        <option value="{{ $name->name }}">{{ $name->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            
                        </div>
                    </form>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-lg-12">                    
                    <table class="table table-hover" id="insurance" width="100%">
                        <thead>
                            <tr>
                                <th style="width:25%;">{{ trans('app.forms.file_no') }}</th>
                                <th style="width:25%;">{{ trans('app.forms.file_name') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.insurance_provider') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.remarks') }}</th>
                                <?php if ($update_permission) { ?>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                    <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(insurance).ready(function () {
        oTable = $('#insurance').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getInsurance', 'All')}}",
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
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
        $('#file_name').on('change', function () {
            oTable.columns(1).search(this.value).draw();
        });
    });

    function deleteInsurance(id) {
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
                url: "{{ URL::action('AdminController@deleteInsurance') }}",
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
