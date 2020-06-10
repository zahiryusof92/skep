@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 36) {
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

            @if (Auth::user()->getAdmin())
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-success" data-toggle="modal" data-target="#importForm">
                        {{ trans('app.buttons.import_cob_files_before_vp') }} &nbsp;<i class="fa fa-upload"></i>
                    </button>
                </div>
            </div>

            <br/>

            <div class="modal fade" id="importForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <form id="form_import" enctype="multipart/form-data" class="form-horizontal" data-parsley-validate>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ trans('app.forms.import_cob_files_before_vp') }}</h4>
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
                                            <label><span style="color: red;">*</span> {{ trans('app.forms.cob') }}</label>
                                            <select name="import_company" id="import_company" class="form-control">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @foreach ($cob as $companies)
                                                <option value="{{ $companies->id }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                                @endforeach
                                            </select>
                                            <div id="import_company_error" style="display: none;"></div>
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
                                <input type="hidden" name="status" id="status" value="3"/>
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
                    $("#import_company_error").css("display", "none");
                    $("#import_file_error").css("display", "none");

                    var import_company = $("#import_company").val(),
                            import_file = $("#import_file").val();

                    var error = 0;

                    if (import_company.trim() == "") {
                        $("#import_company_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"COB"]) }}</span>');
                        $("#import_company_error").css("display", "block");
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
                            url: "{{ URL::action('ImportController@importCOBFile') }}",
                            type: "POST",
                            data: formData,
                            async: true,
                            contentType: false, // The content type used when sending data to the server.
                            cache: false, // To unable request pages to be cached
                            processData: false,
                            success: function (data) { //function to be called if request succeeds
                                console.log(data);

                                $('#loading_import').css("display", "none");
                                $("#submit_button_import").removeAttr("disabled");
                                $("#cancel_button_import").removeAttr("disabled");

                                if (data.trim() === "true") {
                                    $("#importForm").modal("hide");
                                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.import_successfully') }}</span>", function () {
                                        window.location.reload();
                                    });
                                } else if (data.trim() === "empty_file") {
                                    $("#importForm").modal("hide");
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
                        $("#import_company").focus();
                        $('#loading_import').css("display", "none");
                        $("#submit_button_import").removeAttr("disabled");
                        $("#cancel_button_import").removeAttr("disabled");
                    }
                }));
            </script>
            @endif

            <div class="row">
                <div class="col-lg-12 text-center">
                    <form>
                        <div class="row">
                            @if (Auth::user()->getAdmin())
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.cob') }}</label>
                                    <select id="company" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($cob as $companies)
                                        <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.year') }}</label>
                                    <select id="year" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @for ($i = 2012; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i}}</option>
                                        @endfor
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
                    <table class="table table-hover nowrap" id="filelist" width="100%">
                        <thead>
                            <tr>
                                <th style="width:20%;">{{ trans('app.forms.file_number') }}</th>
                                <th style="width:30%;">{{ trans('app.forms.name') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.active') }}</th>
                                <?php if ($update_permission == 1) { ?>
                                    <th style="width:20%;">{{ trans('app.forms.action') }}</th>
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
        oTable = $('#filelist').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getFileListBeforeVP')}}",
            "lengthMenu": [
                [15, 30, 50, 100, -1],
                [15, 30, 50, 100, "All"]
            ],
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ],
            "sorting": [
                [2, "asc"]
            ],
            "scrollX": true,
            "fixedColumns": true,
            "responsive": true
        });

        $('#company').on('change', function () {
            oTable.columns(2).search(this.value).draw();
        });
        $('#year').on('change', function () {
            oTable.columns(3).search(this.value).draw();
        });
    });

    function inactiveFileList(id) {
        $.ajax({
            url: "{{ URL::action('AdminController@inactiveFileList') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location.reload();
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activeFileList(id) {
        $.ajax({
            url: "{{ URL::action('AdminController@activeFileList') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location.reload();
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deleteFileList(id) {
        bootbox.confirm("{{ trans('app.confirmation.are_you_sure_delete_file') }}", function (result) {
            if (result) {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteFileList') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data.trim() == "true") {
                            bootbox.alert("<span style='color:green;'>{{ trans('app.successes.deleted_successfully') }}</span>", function () {
                                window.location.reload();
                            });
                        } else {
                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    }
                });
            }
        });
    }
</script>

@stop
