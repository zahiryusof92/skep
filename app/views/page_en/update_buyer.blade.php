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
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <div id="update_files_lists">
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@house', $files->id)}}">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@strata', $files->id)}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@management', $files->id)}}">{{ trans('app.forms.management') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@monitoring', $files->id)}}">{{ trans('app.forms.monitoring') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@others', $files->id)}}">{{ trans('app.forms.others') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@scoring', $files->id)}}">{{ trans('app.forms.scoring_component_value') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active custom-tab">{{ trans('app.forms.buyer_list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@document', $files->id)}}">{{ trans('app.forms.document') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('AdminController@insurance', $files->id)}}">{{ trans('app.forms.insurance') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="buyer_tab" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <?php if ($update_permission == 1) { ?>
                                                    <button onclick="window.location = '{{ URL::action('AdminController@addBuyer', $files->id) }}'" type="button" class="btn btn-own">
                                                        {{ trans('app.buttons.add_buyer') }}  &nbsp;<i class="fa fa-plus-circle"></i>
                                                    </button>
                                                    &nbsp;

                                                    @if (strtoupper(Auth::user()->getRole->name) != 'JMB')
                                                    @if (strtoupper(Auth::user()->getRole->name) != 'MC')
                                                    <button class="btn btn-success" data-toggle="modal" data-target="#importForm">
                                                        {{ trans('app.forms.import_buyer') }} &nbsp;<i class="fa fa-upload"></i>
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
                                                                        <h4 class="modal-title">{{ trans('app.forms.import_buyer') }}</h4>
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
                                                                                    <label><span style="color: red;">*</span> {{ trans('app.forms.excel_file') }}</label>
                                                                                    <input type="file" name="import_file" id="import_file" class="form-control form-control-file"/>
                                                                                    <div id="import_file_error" style="display: none;"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="file_id" id="file_id" value="{{ $files->id }}"/>
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
                                                            $("#import_file_error").css("display", "none");

                                                            var import_file = $("#import_file").val();

                                                            var error = 0;

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
                                                    
                                                <table class="table table-hover nowrap table-own table-striped" id="buyer_list">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5%;">{{ trans("app.forms.no") }}</th>
                                                            <th style="width:10%;">{{ trans('app.forms.unit_number') }}</th>
                                                            <th style="width:10%;">{{ trans('app.forms.unit_share') }}</th>
                                                            <th style="width:30%;">{{ trans('app.forms.owner_name') }}</th>
                                                            <th style="width:20%;">{{ trans('app.forms.ic_company_number') }}</th>
                                                            <th style="width:20%;">{{ trans('app.forms.race_name_en') }}</th>
                                                            <?php if ($update_permission == 1) { ?>
                                                                <th style="width:5%;">{{ trans('app.forms.action') }}</th>
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
    $(document).ready(function () {
        $('#buyer_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getBuyerList', $files->id)}}",
            "order": [[0, "asc"]],
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
    });

    function deleteBuyer(id) {
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
                function () {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteBuyer') }}",
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
