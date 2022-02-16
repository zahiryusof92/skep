@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 35) {
        $insert_permission = $permission->insert_permission;
        $update_permission = $permission->update_permission;
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
                    <?php if ($insert_permission == 1) { ?>
                    <button onclick="window.location = '{{ URL::action('AgmController@addAgmDesignSub') }}'" type="button" class="btn btn-own">
                        {{ trans('general.label_create') }}
                    </button>
                    <br/><br/>
                    <?php } ?>
                    <table class="table table-hover nowrap" id="form" width="100%">
                        <thead>
                            <tr>
                                <th style="width:70%;">{{ trans('agm_design_sub.table.designation') }}</th>
                                <th style="width:70%;">{{ trans('agm_design_sub.table.name') }}</th>
                                <th style="width:20%;">{{ trans('agm_design_sub.table.phone_number') }}</th>
                                <th style="width:20%;">{{ trans('agm_design_sub.table.email') }}</th>
                                <th style="width:20%;">{{ trans('agm_design_sub.table.ajk_year') }}</th>
                                <?php if ($update_permission == 1) { ?>
                                <th style="width:10%;">{{ trans('general.label_action') }}</th>
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
    $(document).ready(function () {
        oTable = $('#form').DataTable({
            "sAjaxSource": "{{URL::action('AgmController@getAgmDesignSub')}}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[ 0, "asc" ]],
            "responsive": true
        });
    });

    function inactiveAgmDesignSub(id) {
        $.ajax({
            url: "{{ URL::action('AgmController@inactiveAgmDesignSub') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function() {
                        window.location = "{{URL::action('AgmController@agmDesignSub')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activeAgmDesignSub(id) {
        $.ajax({
            url: "{{ URL::action('AgmController@activeAgmDesignSub') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function() {
                        window.location = "{{URL::action('AgmController@agmDesignSub')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deleteAgmDesignSub (id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
        function(){
            $.ajax({
                url: "{{ URL::action('AgmController@deleteAgmDesignSub') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
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
