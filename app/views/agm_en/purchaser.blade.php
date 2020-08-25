@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 31) {
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
                        <button onclick="window.location = '{{ URL::action('AgmController@addPurchaser') }}'" type="button" class="btn btn-primary">
                            {{ trans('app.buttons.add_purchaser') }}
                        </button>
                        &nbsp;

                        @if (strtoupper(Auth::user()->getRole->name) != 'JMB')
                        @if (strtoupper(Auth::user()->getRole->name) != 'MC')
                        <button class="btn btn-success" data-toggle="modal" data-target="#importForm">
                            {{ trans('app.menus.agm.import_purchaser') }} &nbsp;<i class="fa fa-upload"></i>
                        </button>
                        &nbsp;
                        <a href="{{asset('files/buyer_template.xlsx')}}" target="_blank">
                            <button type="button" class="btn btn-success pull-right">
                                {{ trans('app.forms.download_csv_template') }}
                            </button>
                        </a>

                        <div class="modal fade" id="importForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <form id="form_import" enctype="multipart/form-data" class="form-horizontal" data-parsley-validate>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">{{ trans('app.menus.agm.import_purchaser') }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><span style="color: red;">*</span> {{ trans('app.forms.file_no') }}</label>
                                                        <select id="file_id" name="file_id" class="form-control">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($files as $file)
                                                            <option value="{{$file->id}}">{{$file->file_no}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="file_id_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><span style="color: red;">*</span> {{ trans('app.forms.excel_file') }}</label>
                                                        <input type="file" name="import_file" id="import_file" class="form-control form-control-file"/>
                                                        <div id="import_file_error" style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <img id="loading_import" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                            <button id="submit_button_import" class="btn btn-primary" type="submit">
                                                {{ trans('app.forms.submit') }}
                                            </button>
                                            <button data-dismiss="modal" id="cancel_button_import" class="btn btn-default" type="button">
                                                {{ trans('app.forms.cancel') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- modal -->

                        <script>
                            $("#form_import").on('submit', (function (e) {
                                e.preventDefault();

                                $('#loading_import').css("display", "inline-block");
                                $("#submit_button_import").attr("disabled", "disabled");
                                $("#cancel_button_import").attr("disabled", "disabled");
                                $("#file_id_error").css("display", "none");
                                $("#import_file_error").css("display", "none");

                                var file_id = $("#file_id").val(),
                                        import_file = $("#import_file").val();

                                var error = 0;

                                if (file_id.trim() == "") {
                                    $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File"]) }}</span>');
                                    $("#file_id_error").css("display", "block");
                                    error = 1;
                                }
                                if (import_file.trim() == "") {
                                    $("#import_file_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"Excel File"]) }}</span>');
                                    $("#import_file_error").css("display", "block");
                                    error = 1;
                                }

                                if (error == 0) {
                                    var formData = new FormData(this);
                                    $.ajax({
                                        url: "{{ URL::action('ImportController@importBuyer') }}",
                                        type: "POST",
                                        data: formData,
                                        async: true,
                                        contentType: false, // The content type used when sending data to the server.
                                        cache: false, // To unable request pages to be cached
                                        processData: false,
                                        success: function (data) { //function to be called if request succeeds
                                            $('#loading_import').css("display", "none");
                                            $("#submit_button_import").removeAttr("disabled");
                                            $("#cancel_button_import").removeAttr("disabled");

                                            if (data.trim() === "true") {
                                                $("#importForm").modal("hide");
                                                bootbox.alert("<span style='color:green;'>{{ trans('app.successes.import_successfully') }}</span>", function () {
                                                    window.location.reload();
                                                });
                                            } else if (data.trim() === "empty_file") {
                                                $("#import_file_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"Excel File"]) }}</span>');
                                                $("#import_file_error").css("display", "block");
                                            } else if (data.trim() === "empty_data") {
                                                $("#importForm").modal("hide");
                                                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.empty_or_exist') }}</span>", function () {
                                                    window.location.reload();
                                                });
                                            } else {
                                                $("#importForm").modal("hide");
                                                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>", function () {
                                                    window.location.reload();
                                                });
                                            }
                                        }
                                    });
                                } else {
                                    $('#loading_import').css("display", "none");
                                    $("#submit_button_import").removeAttr("disabled");
                                    $("#cancel_button_import").removeAttr("disabled");
                                }
                            }));
                        </script>   
                        @endif
                        @endif

                        <br/><br/>
                    <?php } ?>
                    <div class="table-responsive">
                        <table class="table table-hover nowrap" id="purchaser" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:10%;">{{ trans('app.forms.unit_number') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.unit_share') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.buyer') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.nric') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.phone_number') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.email') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.race') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('#purchaser').DataTable({
            "sAjaxSource": "{{URL::action('AgmController@getPurchaser')}}",
            "order": [[0, "asc"]],
            "responsive": false,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
    });
    function deletePurchaser(id) {
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
                url: "{{ URL::action('AgmController@deletePurchaser') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.deleted_successfully") }}</p>'
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
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
