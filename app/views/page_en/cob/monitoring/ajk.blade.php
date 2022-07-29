
<section class="panel panel-pad">
    <div class="row padding-vertical-20">
        <div class="col-lg-12">
            <h6>{{ trans('app.forms.additional_info') }}</h6>
            <div class="table-responsive">
                <?php if ($update_permission == 1) { ?>
                    <button type="button" class="btn btn-own pull-right margin-bottom-25" onclick="addAJKDetails()">
                        {{ trans('app.forms.add') }}
                    </button>
                    <br/><br/>
                <?php } ?>
                <table class="table table-hover nowrap table-own table-striped" id="ajk_details_list" width="100%">
                    <thead>
                        <tr>
                            <th style="width:20%;text-align: center !important;">{{ trans('app.forms.designation') }}</th>
                            <th style="width:15%;">{{ trans('app.forms.name') }}</th>
                            <th style="width:15%;">{{ trans('app.forms.email') }}</th>
                            <th style="width:20%;">{{ trans('app.forms.phone_number') }}</th>
                            <th style="width:10%;">{{ trans('app.forms.allowance') }}</th>
                            <th style="width:5%;">{{ trans('app.forms.start_year') }}</th>
                            <th style="width:5%;">{{ trans('app.forms.end_year') }}</th>
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

<div class="modal fade modal" id="add_ajk_details" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('app.buttons.add_ajk_details') }}</h4>
            </div>
            <form id="add_ajk">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.designation') }}</label>
                        </div>
                        <div class="col-md-8">
                            <select id="ajk_designation" class="form-control">
                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                @foreach ($designation as $designations)
                                <option value="{{$designations->id}}">{{$designations->description}}</option>
                                @endforeach
                            </select>
                            <div id="ajk_designation_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.name') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="ajk_name"/>
                            <div id="ajk_name_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.email') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="ajk_email"/>
                            <div id="ajk_email_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.phone_number') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="ajk_phone_no"/>
                            <div id="ajk_phone_no_error" style="display:none;"></div>
                            <div id="ajk_phone_no_invalid_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.allowance') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.allowance') }}" id="ajk_allowance"/>
                            <div id="ajk_allowance_error" style="display:none;"></div>
                            <div id="ajk_allowance_invalid_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.start_year') }}" id="ajk_start_year"/>
                            <div id="ajk_start_year_error" style="display:none;"></div>
                            <div id="ajk_start_year_invalid_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.end_year') }}" id="ajk_end_year"/>
                            <div id="ajk_end_year_error" style="display:none;"></div>
                            <div id="ajk_end_year_invalid_error" style="display:none;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    <button id="submit_button" onclick="addAJKDetail()" type="button" class="btn btn-own">
                        {{ trans('app.forms.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal" id="edit_ajk_details" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('app.buttons.edit_ajk_details') }}</h4>
            </div>
            <form id="edit_ajk">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.designation') }}</label>
                        </div>
                        <div class="col-md-8">
                            <select id="ajk_designation_edit" class="form-control">
                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                @foreach ($designation as $designations)
                                <option value="{{$designations->id}}">{{$designations->description}}</option>
                                @endforeach
                            </select>
                            <div id="ajk_designation_edit_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.name') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="ajk_name_edit"/>
                            <div id="ajk_name_edit_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.email') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="ajk_email_edit"/>
                            <div id="ajk_email_edit_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.phone_number') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="ajk_phone_no_edit"/>
                            <div id="ajk_phone_no_edit_error" style="display:none;"></div>
                            <div id="ajk_phone_no_invalid_edit_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.allowance') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.allowance') }}" id="ajk_allowance_edit"/>
                            <div id="ajk_allowance_edit_error" style="display:none;"></div>
                            <div id="ajk_allowance_invalid_edit_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.start_year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.start_year') }}" id="ajk_start_year_edit"/>
                            <div id="ajk_start_year_edit_error" style="display:none;"></div>
                            <div id="ajk_start_year_invalid_edit_error" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.end_year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.end_year') }}" id="ajk_end_year_edit"/>
                            <div id="ajk_start_year_edit_error" style="display:none;"></div>
                            <div id="ajk_start_year_invalid_edit_error" style="display:none;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="ajk_id_edit"/>
                    <button type="button" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    <button id="submit_button" onclick="editAJK()" type="button" class="btn btn-own">
                        {{ trans('app.forms.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#ajk_details_list').DataTable({
            "sAjaxSource": "{{ route('cob.file.ajk.index', \Helper\Helper::encode($file->id)) }}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[0, "asc"]],
            "responsive": true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
    });

    function addAJKDetails() {
        $("#add_ajk_details").modal("show");
    }
    function editAJKDetails() {
        $("#edit_ajk_details").modal("show");
    }

    function addAJKDetail() {
        changes = false;
        $("#loading").css("display", "inline-block");

        var ajk_designation = $("#ajk_designation").val(),
                ajk_name = $("#ajk_name").val(),
                ajk_email = $("#ajk_email").val(),
                ajk_phone_no = $("#ajk_phone_no").val(),
                ajk_allowance = $("#ajk_allowance").val(),
                ajk_start_year = $("#ajk_start_year").val(),
                ajk_end_year = $("#ajk_end_year").val();

        var error = 0;

        if (ajk_designation.trim() == "") {
            $("#ajk_designation_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Designation"]) }}</span>');
            $("#ajk_designation_error").css("display", "block");
            error = 1;
        }

        if (ajk_name.trim() == "") {
            $("#ajk_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#ajk_name_error").css("display", "block");
            error = 1;
        }

        if (ajk_email.trim() == "") {
            $("#ajk_email_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Email"]) }}</span>');
            $("#ajk_email_error").css("display", "block");
            error = 1;
        }

        if (ajk_phone_no.trim() == "") {
            $("#ajk_phone_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_error").css("display", "block");
            $("#ajk_phone_no_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_phone_no)) {
            $("#ajk_phone_no_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_invalid_error").css("display", "block");
            $("#ajk_phone_no_error").css("display", "none");
            error = 1;
        }

        if (ajk_start_year.trim() == "") {
            $("#ajk_start_year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_error").css("display", "block");
            $("#ajk_start_year_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_start_year)) {
            $("#ajk_start_year_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_invalid_error").css("display", "block");
            $("#ajk_start_year_error").css("display", "none");
            error = 1;
        }

        if (ajk_end_year.trim() == "") {
            $("#ajk_end_year_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"End Year"]) }}</span>');
            $("#ajk_end_year_error").css("display", "block");
            $("#ajk_end_year_invalid_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_end_year)) {
            $("#ajk_end_year_invalid_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"End Year"]) }}</span>');
            $("#ajk_end_year_invalid_error").css("display", "block");
            $("#ajk_end_year_error").css("display", "none");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@addAJKDetails') }}",
                type: "POST",
                data: {
                    ajk_designation: ajk_designation,
                    ajk_name: ajk_name,
                    ajk_email: ajk_email,
                    ajk_phone_no: ajk_phone_no,
                    ajk_allowance: ajk_allowance,
                    ajk_start_year: ajk_start_year,
                    ajk_end_year: ajk_end_year,
                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#add_ajk_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
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
        }
    }

    function editAJK() {
        changes = false;
        $("#loading").css("display", "inline-block");

        var ajk_id_edit = $("#ajk_id_edit").val(),
                ajk_designation = $("#ajk_designation_edit").val(),
                ajk_name = $("#ajk_name_edit").val(),
                ajk_email = $("#ajk_email_edit").val(),
                ajk_phone_no = $("#ajk_phone_no_edit").val(),
                ajk_allowance = $("#ajk_allowance_edit").val(),
                ajk_start_year = $("#ajk_start_year_edit").val(),
                ajk_end_year = $("#ajk_end_year_edit").val();

        var error = 0;

        if (ajk_designation.trim() == "") {
            $("#ajk_designation_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Designation"]) }}</span>');
            $("#ajk_designation_edit_error").css("display", "block");
            error = 1;
        }

        if (ajk_name.trim() == "") {
            $("#ajk_name_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#ajk_name_edit_error").css("display", "block");
            error = 1;
        }

        if (ajk_email.trim() == "") {
            $("#ajk_email_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Email"]) }}</span>');
            $("#ajk_email_edit_error").css("display", "block");
            error = 1;
        } 

        if (ajk_phone_no.trim() == "") {
            $("#ajk_phone_no_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_edit_error").css("display", "block");
            $("#ajk_phone_no_invalid_edit_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_phone_no)) {
            $("#ajk_phone_no_invalid_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Phone Number"]) }}</span>');
            $("#ajk_phone_no_invalid_edit_error").css("display", "block");
            $("#ajk_phone_no_edit_error").css("display", "none");
            error = 1;
        }

        if (ajk_start_year.trim() == "") {
            $("#ajk_start_year_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_edit_error").css("display", "block");
            $("#ajk_start_year_invalid_edit_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_start_year)) {
            $("#ajk_start_year_invalid_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Start Year"]) }}</span>');
            $("#ajk_start_year_invalid_edit_error").css("display", "block");
            $("#ajk_start_year_edit_error").css("display", "none");
            error = 1;
        }

        if (ajk_end_year.trim() == "") {
            $("#ajk_end_year_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"End Year"]) }}</span>');
            $("#ajk_end_year_edit_error").css("display", "block");
            $("#ajk_end_year_invalid_edit_error").css("display", "none");
            error = 1;
        }

        if (isNaN(ajk_end_year)) {
            $("#ajk_end_year_invalid_edit_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"End Year"]) }}</span>');
            $("#ajk_end_year_invalid_edit_error").css("display", "block");
            $("#ajk_end_year_edit_error").css("display", "none");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@editAJKDetails') }}",
                type: "POST",
                data: {
                    ajk_designation: ajk_designation,
                    ajk_name: ajk_name,
                    ajk_email: ajk_email,
                    ajk_phone_no: ajk_phone_no,
                    ajk_allowance: ajk_allowance,
                    ajk_start_year: ajk_start_year,
                    ajk_end_year: ajk_end_year,
                    ajk_id_edit: ajk_id_edit,
                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#edit_ajk_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
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
        }
    }

    function deleteAJKDetails(id) {
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
                url: "{{ URL::action('AdminController@deleteAJKDetails') }}",
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