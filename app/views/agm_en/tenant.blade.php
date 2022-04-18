@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 43) {
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
                            <button onclick="window.location = '{{ URL::action('AgmController@addTenant') }}'" type="button" class="btn btn-own">
                                {{ trans('app.buttons.add_tenant') }}
                            </button>
                            &nbsp;

                            @if (strtoupper(Auth::user()->getRole->name) != 'JMB')
                            @if (strtoupper(Auth::user()->getRole->name) != 'MC')
                            <button class="btn btn-success" data-toggle="modal" data-target="#importForm">
                                {{ trans('app.menus.agm.import_tenant') }} &nbsp;<i class="fa fa-upload"></i>
                            </button>
                            &nbsp;
                            <a href="{{asset('files/tenant_template.xlsx')}}" target="_blank">
                                <button type="button" class="btn btn-warning">
                                    {{ trans('app.forms.download_csv_template') }} &nbsp;<i class="fa fa-download"></i>
                                </button>
                            </a>

                            <div class="modal fade" id="importForm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <form id="form_import" enctype="multipart/form-data" class="form-horizontal" data-parsley-validate>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">{{ trans('app.menus.agm.import_tenant') }}</h4>
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
                                                            <select id="file_id" name="file_id" class="form-control select2">
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
                                                <button id="submit_button_import" class="btn btn-own" type="submit">
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
                                            url: "{{ URL::action('ImportController@importTenant') }}",
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
                        <?php } ?>
                    </div>
                </div>

                <div class="row" style="margin-top: 30px;">
                    <div class="col-lg-12 text-center">
                        <form target="_blank" action="{{ url('/report/tenant') }}" method="POST">
                            <div class="row">
                                @if (Auth::user()->getAdmin())
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select id="company" name="company" class="form-control select2">
                                            @if (count($cob) > 1)
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @endif
                                            @foreach ($cob as $companies)
                                            <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.file_no') }}</label>
                                        <select id="file_no" name="file_no" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $files_no)
                                            <option value="{{ $files_no->file_no }}">{{ $files_no->file_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br/>
                                        <button type="submit" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-own table-striped" id="tenant_list" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.scheme_name') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.unit_number') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.unit_share') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.tenant') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.phone_number') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.email') }}</th>
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
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        var oTable = $('#tenant_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('AgmController@getTenant') }}",
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'files', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'unit_no', name: 'tenant.unit_no'},
                {data: 'unit_share', name: 'tenant.unit_share'},
                {data: 'tenant_name', name: 'tenant.tenant_name'},
                {data: 'phone_no', name: 'tenant.phone_no'},
                {data: 'email', name: 'tenant.email'},
                {data: 'race', name: 'tenant.race_id'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            lengthMenu: [[10, 25, 50], [10, 25, 50]],
            order: [[0, "asc"], [1, "asc"], [3, "asc"]],
            responsive: false
        });

        $('#company').on('change', function () {
            $.ajax({
                url: "{{ URL::action('AgmController@getFileListByCOB') }}",
                type: "POST",
                data: {
                    company: $("#company").val()
                },
                success: function (data) {
                    $("#file_no").html(data);
                    oTable.columns(1).search('').draw();
                }
            });

            oTable.columns(0).search(this.value).draw();
        });
        $('#file_no').on('change', function () {
            oTable.columns(1).search(this.value).draw();
        });
    });

    function deleteTenant(id) {
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
                url: "{{ URL::action('AgmController@deleteTenant') }}",
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

    $("[data-toggle=tooltip]").tooltip();
</script>
<!-- End Page Scripts-->

@stop
