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
            <section class="panel panel-pad">

                @if (Auth::user()->getAdmin())
                <div class="row padding-vertical-10">
                    <div class="col-md-2">
                        <button class="btn btn-own" data-toggle="modal" data-target="#importForm">
                            {{ trans('app.buttons.import_cob_files') }} &nbsp;<i class="fa fa-upload"></i>
                        </button>
                    </div>
                </div>

                <br/>

                <div class="modal fade" id="importForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <form id="form_import" enctype="multipart/form-data" class="form-horizontal" data-parsley-validate>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">{{ trans('app.forms.import_cob_files') }}</h4>
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
                                                    @if (count($cob) > 1)
                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                    @endif
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
                                    <input type="hidden" name="status" id="status" value="1"/>
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

                <div class="row padding-vertical-10">
                    <div class="col-lg-12 text-center">
                        <form>
                            <div class="row">
                                @if (Auth::user()->getAdmin())
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select id="company" class="form-control select2">
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
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.year') }}</label>
                                        <select id="year" class="form-control select2">
                                            @foreach ($year as $value => $years)
                                            <option value="{{ $value }}">{{ $years }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if (!empty($parkList))
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.park') }}</label>
                                        <select id="park" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($parkList as $value => $name)
                                            <option value="{{ $value }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                @if (!empty($categoryList))
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.category') }}</label>
                                        <select id="category" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($categoryList as $value => $name)
                                            <option value="{{ $value }}">{{ $name }}</option>
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
                        <table class="table table-hover nowrap table-own table-striped" id="filelist" style="width: 100%;">
                            <thead>
                                <tr>                                
                                    <th>{{ trans('app.forms.file_no') }}</th>
                                    <th>{{ trans('app.forms.name') }}</th>
                                    <th>{{ trans('app.forms.cob') }}</th>
                                    <th>{{ trans('app.forms.year') }}</th>
                                    <th>{{ trans('app.forms.park') }}</th>
                                    <th>{{ trans('app.forms.category') }}</th>
                                    <th>{{ trans('app.forms.active') }}</th>
                                    <th>{{ trans('app.forms.action') }}</th>
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
        oTable = $('#filelist').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ URL::action('AdminController@getFileList') }}",
                'data': function(data) {
                    var company = $('#company').val();

                    // Append to data
                    data.company = company;

                }
            },
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[2, "asc"], [1, 'asc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'cob', name: 'company.short_name'},
                {data: 'year', name: 'strata.year'},
                {data: 'park', name: 'park.description'},
                {data: 'category', name: 'category.description'},
                {data: 'active', name: 'files.is_active', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#company').on('change', function () {
            oTable.draw();
        });
        $('#year').on('change', function () {
            oTable.columns(3).search(this.value).draw();
        });
        $('#park').on('change', function () {
            oTable.columns(4).search(this.value).draw();
        });
        $('#category').on('change', function () {
            oTable.columns(5).search(this.value).draw();
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

@if (Auth::user()->role == 1)
<div class="modal fade" id="updateFileNoForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id="form_update_file_no" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('app.forms.update_file_no') }}</h4>
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
                                <label><span style="color: red;">*</span> {{ trans('app.forms.update_file_no') }}</label>
                                <input type="text" name="file_no" id="file_no" class="form-control"/>
                                <div id="file_no_error" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="file_id" id="file_id"/>
                    <img id="loading_update_file_no" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                    <button id="submit_button_update_file_no" class="btn btn-own" type="submit">
                        {{ trans('app.forms.submit') }}
                    </button>
                    <button data-dismiss="modal" id="cancel_button_update_file_no" class="btn btn-default" type="button">
                        {{ trans('app.forms.cancel') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on("click", ".modal-update-file-no", function () {
        $("#file_no_error").css("display", "none");

        var fileNo = $(this).data('file_no'),
                fileID = $(this).data('id');

        $(".modal-body #file_no").val(fileNo);
        $(".modal-footer #file_id").val(fileID);

    });

    $("#form_update_file_no").on('submit', (function (e) {
        e.preventDefault();

        $('#loading_update_file_no').css("display", "inline-block");
        $("#submit_button_update_file_no").attr("disabled", "disabled");
        $("#cancel_button_update_file_no").attr("disabled", "disabled");
        $("#file_no_error").css("display", "none");

        var file_no = $("#file_no").val();

        var error = 0;

        if (file_no.trim() == "") {
            $("#file_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"File No"]) }}</span>');
            $("#file_no_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            var formData = new FormData(this);
            $.ajax({
                url: "{{ URL::action('AdminController@updateFileNo') }}",
                type: "POST",
                data: formData,
                async: true,
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                success: function (data) { //function to be called if request succeeds
                    $('#loading_update_file_no').css("display", "none");
                    $("#submit_button_update_file_no").removeAttr("disabled");
                    $("#cancel_button_update_file_no").removeAttr("disabled");

                    if (data.trim() === "true") {
                        $("#updateFileNoForm").modal("hide");
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>", function () {
                            window.location.reload();
                        });
                    } else if (data.trim() === "exist") {
                        $("#file_no_error").html("<span style='color:red;font-style:italic;font-size:13px;'>{{ trans('app.errors.exist2', ['attribute'=>'File No']) }}</span>");
                        $("#file_no_error").css("display", "block");
                    } else {
                        $("#updateFileNoForm").modal("hide");
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>", function () {
                            window.location.reload();
                        });
                    }
                }
            });
        } else {
            $("#file_no").focus();
            $('#loading_update_file_no').css("display", "none");
            $("#submit_button_update_file_no").removeAttr("disabled");
            $("#cancel_button_update_file_no").removeAttr("disabled");
        }
    }));
</script>
@endif

@stop
