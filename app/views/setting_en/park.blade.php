@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 18) {
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
                    <button onclick="window.location = '{{ URL::action('SettingController@addPark') }}'" type="button" class="btn btn-primary">
                        {{ trans('app.buttons.add_park') }}
                    </button>
                    <br/><br/>
                    <?php } ?>
                    <div class="row">
                        <div class="form-group">
                            <label  class="col-md-offset-2 col-md-1 control-label">{{ trans('app.forms.dun') }}:</label>
                            <div class="col-sm-3">
                                <select id="duns" class="form-control">
                                    <option value="">{{ trans('app.forms.all') }}</option>
                                    @foreach ($dun as $duns)
                                    <option value="{{$duns->description}}">{{$duns->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <table class="table table-hover nowrap" id="park" width="100%">
                        <thead>
                            <tr>
                                <th style="width:40%;">{{ trans('app.forms.park') }}</th>
                                <th style="width:40%;">{{ trans('app.forms.dun') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.status') }}</th>
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
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#park').DataTable({
            "sAjaxSource": "{{URL::action('SettingController@getPark')}}",
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[ 0, "asc" ]],
            responsive: true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
    });

    $('#duns').on('change', function (){
        oTable.columns(1).search(this.value).draw();
    });

    function inactivePark(id) {
        $.ajax({
            url: "{{ URL::action('SettingController@inactivePark') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function() {
                        window.location = "{{URL::action('SettingController@park')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activePark(id) {
        $.ajax({
            url: "{{ URL::action('SettingController@activePark') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function() {
                        window.location = "{{URL::action('SettingController@park')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deletePark(id) {
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
                url: "{{ URL::action('SettingController@deletePark') }}",
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
