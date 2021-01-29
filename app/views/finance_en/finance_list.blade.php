@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 38) {
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
            {{-- Import process --}}
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-success" data-toggle="modal" data-target="#importForm">
                        {{ trans('app.buttons.import_finance_files') }} &nbsp;<i class="fa fa-upload"></i>
                    </button>
                </div>
            </div>
            <br/>
            
            
            <div class="modal fade" id="importForm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="form_import" enctype="multipart/form-data" class="form-horizontal" data-parsley-validate>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ trans('app.forms.import_finance_files') }}</h4>
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
                                            <select name="import_file_id" id="import_file_id" class="form-control select2">
                                                @if (count($file) > 1)
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @endif
                                                @foreach ($file as $files)
                                                <option value="{{ $files->id }}">{{ $files->file_no }}</option>
                                                @endforeach
                                            </select>
                                            <div id="import_file_id_error" style="display: none;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.forms.month') }}</label>
                                            <select name="import_month" id="import_month" class="form-control">
                                                @if (count($month) > 1)
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @endif
                                                @foreach ($month as $val => $months)
                                                    <option value="{{ $val }}">{{ $months }}</option>
                                                @endforeach
                                            </select>
                                            <div id="import_month_error" style="display: none;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><span style="color: red;">*</span> {{ trans('app.forms.year') }}</label>
                                            <select name="import_year" id="import_year" class="form-control">
                                                @foreach ($year as $value => $years)
                                                    <option value="{{ $value }}">{{ $years }}</option>
                                                @endforeach
                                            </select>
                                            <div id="import_year_error" style="display: none;"></div>
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
                    $("#import_file_id_error").css("display", "none");
                    $("#import_month_error").css("display", "none");
                    $("#import_year_error").css("display", "none");
                    $("#import_file_error").css("display", "none");

                    var import_file_id = $("#import_file_id").val(),
                            import_month = $("#import_month").val(),
                            import_year = $("#import_year").val(),
                            import_file = $("#import_file").val();

                    var error = 0;

                    if (import_file_id.trim() == "") {
                        $("#import_file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
                        $("#import_file_id_error").css("display", "block");
                        error = 1;
                    }
                    if (import_month.trim() == "") {
                        $("#import_month_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Month"]) }}</span>');
                        $("#import_month_error").css("display", "block");
                        error = 1;
                    }
                    if (import_year.trim() == "") {
                        $("#import_year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Year"]) }}</span>');
                        $("#import_year_error").css("display", "block");
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
                            url: "{{ URL::action('ImportController@importFinanceFile') }}",
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
                        $("#import_file_id").focus();
                        $('#loading_import').css("display", "none");
                        $("#submit_button_import").removeAttr("disabled");
                        $("#cancel_button_import").removeAttr("disabled");
                    }
                }));
            </script>
            {{-- End Import process --}}

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
                                    <label>{{ trans('app.forms.month') }}</label>
                                    <select id="month" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($month as $val => $months)
                                        <option value="{{ $val }}">{{ $months }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                    </form>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-hover" id="filelist" width="100%" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.finance_management') }}</th>
                                <th style="width:25%;">{{ trans('app.forms.strata') }}</th>                                
                                <th style="width:10%;">{{ trans('app.forms.month') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                <th style="width:15%;">{{ trans('app.forms.action') }}</th>
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
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('FinanceController@getFinanceList') }}",
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[0, "asc"], [1, 'asc'], [3, 'desc'], [4, 'desc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'month', name: 'finance_file.month'},
                {data: 'year', name: 'finance_file.year'},
                {data: 'active', name: 'files.is_active', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#company').on('change', function () {
            oTable.columns(0).search(this.value).draw();
        });
        $('#month').on('change', function () {
            oTable.columns(3).search(this.value).draw();
        });
        $('#year').on('change', function () {
            oTable.columns(4).search(this.value).draw();
        });
    });

    function inactiveFinanceList(id) {
        $.ajax({
            url: "{{ URL::action('FinanceController@inactiveFinanceList') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('FinanceController@financeList')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activeFinanceList(id) {
        $.ajax({
            url: "{{ URL::action('FinanceController@activeFinanceList') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('FinanceController@financeList')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deleteFinanceList(id) {
        bootbox.confirm("{{ trans('app.confirmation.are_you_sure_delete_file') }}", function (result) {
            if (result) {
                $.ajax({
                    url: "{{ URL::action('FinanceController@deleteFinanceList') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data.trim() == "true") {
                            bootbox.alert("<span style='color:green;'>{{ trans('app.successes.deleted_successfully') }}</span>", function () {
                                window.location = "{{URL::action('FinanceController@financeList')}}";
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
