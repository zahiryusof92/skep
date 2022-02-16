@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 3) {
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
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$file->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file', ['files' => $file])
                        
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="update_insurance" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">   
                                            <?php if ($update_permission) { ?>
                                                <button onclick="window.location = '{{ URL::action('AdminController@addInsurance', [$file->id]) }}'" type="button" class="btn btn-own margin-bottom-25">
                                                    {{ trans('app.buttons.add_insurance') }}
                                                </button>
                                            <?php } ?>                 
                                            <table class="table table-hover nowrap table-own table-striped" id="insurance" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width:25%;">{{ trans('app.forms.file_no') }}</th>
                                                        <th style="width:25%;">{{ trans('app.forms.insurance_provider') }}</th>
                                                        <th style="width:40%;">{{ trans('app.forms.remarks') }}</th>
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
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    var changes = false;
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });

    var oTable;
    $(insurance).ready(function () {
        oTable = $('#insurance').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getInsurance', $file->id)}}",
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
<!-- End Page Scripts-->

@stop
