@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 17) {
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
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <?php if ($insert_permission == 1) { ?>
                        <button onclick="window.location = '{{ URL::action('SettingController@addDun') }}'" type="button" class="btn btn-own">
                            {{ trans('app.buttons.add_dun') }}
                        </button>
                        <br/><br/>
                        <?php } ?>
                        <div class="row text-center">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.parliament') }}:</label>
                                    <select id="parliaments" class="form-control">
                                        <option value="">{{ trans('app.forms.all') }}</option>
                                        @foreach ($parliament as $parliaments)
                                        <option value="{{$parliaments->description}}">{{$parliaments->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover nowrap table-own table-striped" id="dun" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:35%;">{{ trans('app.forms.dun') }}</th>                                
                                    <th style="width:30%;">{{ trans('app.forms.parliament') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.code') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                    <?php if ($update_permission == 1) { ?>
                                    <th style="width:15%;">{{ trans('app.forms.action') }}</th>
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
        oTable = $('#dun').DataTable({
            "sAjaxSource": "{{URL::action('SettingController@getDun')}}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[ 0, "asc" ]],
            "responsive": true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
    });

    $('#parliaments').on('change', function (){
        oTable.columns(1).search(this.value).draw();
    });

    function inactiveDun(id) {
        $.ajax({
            url: "{{ URL::action('SettingController@inactiveDun') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function() {
                        window.location = "{{URL::action('SettingController@dun')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activeDun(id) {
        $.ajax({
            url: "{{ URL::action('SettingController@activeDun') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function() {
                        window.location = "{{URL::action('SettingController@dun')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deleteDun(id) {
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
                url: "{{ URL::action('SettingController@deleteDun') }}",
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
