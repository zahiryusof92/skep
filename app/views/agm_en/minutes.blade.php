@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 32) {
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
                            <button type="button" class="btn btn-own margin-bottom-25" onclick="window.location = '{{ URL::action('AgmController@addMinutes') }}'">
                                {{ trans('app.forms.add') }}
                            </button>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <form>
                            <div class="row">                           
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.file_no') }}</label>
                                        <select id="file_no" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $files_no)
                                            <option value="{{ $files_no->file_no }}">{{ $files_no->file_no }}</option>
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
                        <div class="table-responsive">                        
                            <table class="table table-hover nowrap table-own table-striped" id="financial_report_list" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.agm_date') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.meeting') }}</th>
                                        <th style="width:5%;"></th>
                                        <th style="width:20%;">{{ trans('app.forms.copy_list') }}</th>
                                        <th style="width:5%;"></th>
                                        <th style="width:20%;">{{ trans('app.forms.financial_report') }}</th>
                                        <th style="width:5%;"></th>
                                        <th style="width:10%;">{{ trans('app.forms.recent_update') }}</th>
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
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        var oTable = $('#financial_report_list').DataTable({
            "sAjaxSource": "{{URL::action('AgmController@getMinutes')}}",
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[1, "asc"]],
            "responsive": false,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [1, 2, 3, 4, 5, 6, -1]
                }
            ]
        });
        
        $('#file_no').on('change', function () {
            oTable.columns(0).search(this.value).draw();
        });
    });

    function deleteAGMDetails(id) {
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
                url: "{{ URL::action('AgmController@deleteMinutes') }}",
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

    function editAGMDetail() {
        $("#loading").css("display", "inline-block");

        var agm,
                egm,
                minit_meeting,
                jmc_copy,
                ic_list,
                attendance_list,
                audited_financial_report;

        if (document.getElementById('agm_edit').checked) {
            agm = $("#agm_edit").val();
        } else {
            agm = 0;
        }
        if (document.getElementById('egm_edit').checked) {
            egm = $("#egm_edit").val();
        } else {
            egm = 0;
        }
        if (document.getElementById('minit_meeting_edit').checked) {
            minit_meeting = $("#minit_meeting_edit").val();
        } else {
            minit_meeting = 0;
        }
        if (document.getElementById('jmc_copy_edit').checked) {
            jmc_copy = $("#jmc_copy_edit").val();
        } else {
            jmc_copy = 0;
        }
        if (document.getElementById('ic_list_edit').checked) {
            ic_list = $("#ic_list_edit").val();
        } else {
            ic_list = 0;
        }
        if (document.getElementById('attendance_list_edit').checked) {
            attendance_list = $("#attendance_list_edit").val();
        } else {
            attendance_list = 0;
        }
        if (document.getElementById('audited_financial_report_edit').checked) {
            audited_financial_report = $("#audited_financial_report_edit").val();
        } else {
            audited_financial_report = 0;
        }

        var agm_id_edit = $("#agm_id_edit").val(),
                agm_date = $("#agm_date_edit").val(),
                audit_report = $("#audit_report_edit").val(),
                audit_start = $("#audit_start_edit").val(),
                audit_end = $("#audit_end_edit").val(),
                audit_report_file_url = $("#audit_report_file_url_edit").val(),
                letter_integrity_url = $("#letter_integrity_url_edit").val(),
                letter_bankruptcy_url = $("#letter_bankruptcy_url_edit").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AgmController@editMinutes') }}",
                type: "POST",
                data: {
                    agm_date: agm_date,
                    agm: agm,
                    egm: egm,
                    minit_meeting: minit_meeting,
                    jmc_copy: jmc_copy,
                    ic_list: ic_list,
                    attendance_list: attendance_list,
                    audited_financial_report: audited_financial_report,
                    audit_report: audit_report,
                    audit_start: audit_start,
                    audit_end: audit_end,
                    audit_report_file_url: audit_report_file_url,
                    letter_integrity_url: letter_integrity_url,
                    letter_bankruptcy_url: letter_bankruptcy_url,
                    id: agm_id_edit
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#edit_agm_details').modal('hide');
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

    function clearAuditFile() {
        $("#audit_report_file").val("");
        $("#audit_report_file_url").val("");
        $("#audit_report_file").css("color", "grey");
        $("#clear_audit_report_file").hide();
        $("#check_audit_report_file").hide();
    }

    function clearLetterIntegrity() {
        $("#letter_integrity").val("");
        $("#letter_integrity_url").val("");
        $("#letter_integrity").css("color", "grey");
        $("#clear_letter_integrity").hide();
        $("#check_letter_integrity").hide();
    }

    function clearLetterBankruptcy() {
        $("#letter_bankruptcy").val("");
        $("#letter_bankruptcy_url").val("");
        $("#letter_bankruptcy").css("color", "grey");
        $("#clear_letter_bankruptcy").hide();
        $("#check_letter_bankruptcy").hide();
    }
    function clearAuditFileEdit() {
        $("#audit_report_file_edit").val("");
        $("#audit_report_file_url_edit").val("");
        $("#audit_report_file_edit").css("color", "grey");
        $("#clear_audit_report_file_edit").hide();
        $("#check_audit_report_file_edit").hide();
    }

    function clearLetterIntegrityEdit() {
        $("#letter_integrity_edit").val("");
        $("#letter_integrity_url_edit").val("");
        $("#letter_integrity_edit").css("color", "grey");
        $("#clear_letter_integrity_edit").hide();
        $("#check_letter_integrity_edit").hide();
    }

    function clearLetterBankruptcyEdit() {
        $("#letter_bankruptcy_edit").val("");
        $("#letter_bankruptcy_url_edit").val("");
        $("#letter_bankruptcy_edit").css("color", "grey");
        $("#clear_letter_bankruptcy_edit").hide();
        $("#check_letter_bankruptcy_edit").hide();
    }
</script>
<!-- End Page Scripts-->

@stop
