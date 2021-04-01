@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 48) {
        $insert_permission = $permissions->insert_permission;
    }
}

$update_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 48) {
        $update_permission = $permissions->update_permission;
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
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <?php if ($insert_permission) { ?>
                            <button onclick="window.location = '{{ URL::action('SettingController@addInsuranceProvider') }}'" type="button" class="btn btn-own">
                                {{ trans('app.buttons.add_insurance_provider') }}
                            </button>
                            <br/><br/>
                        <?php } ?>
                        <table class="table table-hover nowrap table-own table-striped" id="insurance_provider" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:70%;">{{ trans('app.forms.insurance_provider') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.sort_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.status') }}</th>
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
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#insurance_provider').DataTable({
            "sAjaxSource": "{{URL::action('SettingController@getInsuranceProvider')}}",
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[1, "asc"]],
            responsive: true
        });
    });

    function inactiveInsuranceProvider(id) {
        $.ajax({
            url: "{{ URL::action('SettingController@inactiveInsuranceProvider') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('SettingController@defectCategory')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activeInsuranceProvider(id) {
        $.ajax({
            url: "{{ URL::action('SettingController@activeInsuranceProvider') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('SettingController@defectCategory')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deleteInsuranceProvider(id) {
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
                url: "{{ URL::action('SettingController@deleteInsuranceProvider') }}",
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
