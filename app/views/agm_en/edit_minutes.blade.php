@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 32) {
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
                    <form id="">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.file_no') }}</label>
                            </div>
                            <div class="col-md-6">
                                <select id="file_id" class="form-control select2">
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($files as $file)
                                    <option value="{{$file->id}}" {{($file->id == $meeting_doc->file_id ? " selected" : "")}}>{{$file->file_no}}</option>
                                    @endforeach
                                </select>
                                <div id="file_id_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.agm_date') }}</label>
                            </div>
                            <div class="col-md-4">
                                <label class="input-group datepicker-only-init">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date" value="{{ $meeting_doc->agm_date }}"/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.annual_general_meeting') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="agm" name="agm" value="1" {{($meeting_doc->agm == 1 ? " checked" : "")}}> {{ trans('app.forms.yes') }}
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="agm" name="agm" value="0" {{($meeting_doc->agm == 0 ? " checked" : "")}}> {{ trans('app.forms.no') }}
                            </div>
                        </div>
                    </form>
                    <form id="upload_agm_file" enctype="multipart/form-data" method="post" action="{{ url('uploadAGMFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="agm_file" id="agm_file">
                                @if ($meeting_doc->agm_file_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->agm_file_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAGMFile('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="agm_file_error"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.extra_general_meeting') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="egm" name="egm" value="1" {{($meeting_doc->egm == 1 ? " checked" : "")}}> {{ trans('app.forms.yes') }}
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="egm" name="egm" value="0" {{($meeting_doc->egm == 0 ? " checked" : "")}}> {{ trans('app.forms.no') }}
                            </div>
                        </div>
                    </form>
                    <form id="upload_egm_file" enctype="multipart/form-data" method="post" action="{{ url('uploadEGMFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="egm_file" id="egm_file">
                                @if ($meeting_doc->egm_file_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->egm_file_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteEGMFile('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="egm_file_error"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.meeting_minutes') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="minit_meeting" name="minit_meeting" value="1" {{($meeting_doc->minit_meeting == 1 ? " checked" : "")}}> {{ trans('app.forms.yes') }}
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="minit_meeting" name="minit_meeting" value="0" {{($meeting_doc->minit_meeting == 0 ? " checked" : "")}}> {{ trans('app.forms.no') }}
                            </div>
                        </div>
                    </form>
                    <form id="upload_minutes_meeting_file" enctype="multipart/form-data" method="post" action="{{ url('uploadMinutesMeetingFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="minutes_meeting_file" id="minutes_meeting_file">
                                @if ($meeting_doc->minutes_meeting_file_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->minutes_meeting_file_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesMeetingFile('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="minutes_meeting_file_error"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.jmc_spa_copy') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="jmc_copy" name="jmc_copy" value="1" {{($meeting_doc->jmc_spa == 1 ? " checked" : "")}}> {{ trans('app.forms.yes') }}
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="jmc_copy" name="jmc_copy" value="0" {{($meeting_doc->jmc_spa == 0 ? " checked" : "")}}> {{ trans('app.forms.no') }}
                            </div>
                        </div>
                    </form>
                    <form id="upload_jmc_file" enctype="multipart/form-data" method="post" action="{{ url('uploadJMCFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="jmc_file" id="jmc_file">
                                @if ($meeting_doc->jmc_file_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->jmc_file_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteJMCFile('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="jmc_file_error"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.identity_card_list') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="ic_list" name="ic_list" value="1" {{($meeting_doc->identity_card == 1 ? " checked" : "")}}> {{ trans('app.forms.yes') }}
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="ic_list" name="ic_list" value="0" {{($meeting_doc->identity_card == 0 ? " checked" : "")}}> {{ trans('app.forms.no') }}
                            </div>
                        </div>
                    </form>
                    <form id="upload_ic_file" enctype="multipart/form-data" method="post" action="{{ url('uploadICFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="ic_file" id="ic_file">
                                @if ($meeting_doc->ic_file_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->ic_file_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteICFile('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="ic_file_error"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.attendance_list') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="attendance_list" name="attendance_list" value="1" {{($meeting_doc->attendance == 1 ? " checked" : "")}}> {{ trans('app.forms.yes') }}
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="attendance_list" name="attendance_list" value="0" {{($meeting_doc->attendance == 0 ? " checked" : "")}}> {{ trans('app.forms.no') }}
                            </div>
                        </div>
                    </form>
                    <form id="upload_attendance_file" enctype="multipart/form-data" method="post" action="{{ url('uploadAttendanceFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="attendance_file" id="attendance_file">
                                @if ($meeting_doc->attendance_file_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->attendance_file_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAttendanceFile('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="attendance_file_error"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.audited_financial_report') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="audited_financial_report" name="audited_financial_report" value="1" {{($meeting_doc->financial_report == 1 ? " checked" : "")}}> {{ trans('app.forms.yes') }}
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="audited_financial_report" name="audited_financial_report" value="0" {{($meeting_doc->financial_report == 0 ? " checked" : "")}}> {{ trans('app.forms.no') }}
                            </div>
                        </div>
                    </form>
                    <form id="upload_audited_financial_file" enctype="multipart/form-data" method="post" action="{{ url('uploadAuditedFinancialFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="audited_financial_file" id="audited_financial_file">
                                @if ($meeting_doc->audited_financial_file_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->audited_financial_file_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAuditedFinancialFile('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="audited_financial_file_error"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.financial_audit_report') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.financial_audit_report') }}" id="audit_report" value="{{ $meeting_doc->audit_report }}"/>
                            </div>
                        </div>
                    </form>
                    <form id="upload_audit_report_file" enctype="multipart/form-data" method="post" action="{{ url('uploadAuditReportFile') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">&nbsp;</label>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="clear_audit_report_file" class="btn btn-xs btn-danger" onclick="clearAuditFile()" style="display: none;"><i class="fa fa-times"></i></button>
                                &nbsp;
                                <input type="file" name="audit_report_file" id="audit_report_file">
                                @if ($meeting_doc->audit_report_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->audit_report_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAuditReport('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="validation-errors_audit_report_file"></div>
                            </div>
                        </div>
                    </form>

                    <form id="upload_letter_integrity" enctype="multipart/form-data" method="post" action="{{ url('uploadLetterIntegrity') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.pledge_letter_of_integrity') }}</label>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="clear_letter_integrity" class="btn btn-xs btn-danger" onclick="clearLetterIntegrity()" style="display: none;"><i class="fa fa-times"></i></button>
                                &nbsp;
                                <input type="file" name="letter_integrity" id="letter_integrity">
                                @if ($meeting_doc->letter_integrity_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->letter_integrity_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterIntegrity('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="validation-errors_letter_integrity"></div>
                            </div>
                        </div>
                    </form>

                    <form id="upload_letter_bankruptcy" enctype="multipart/form-data" method="post" action="{{ url('uploadLetterBankruptcy') }}" autocomplete="off">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.declaration_letter_of_non_bankruptcy') }}</label>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="clear_letter_bankruptcy" class="btn btn-xs btn-danger" onclick="clearLetterBankruptcy()" style="display: none;"><i class="fa fa-times"></i></button>
                                &nbsp;
                                <input type="file" name="letter_bankruptcy" id="letter_bankruptcy">
                                @if ($meeting_doc->letter_bankruptcy_url != "")
                                <div class="col-md-6">
                                    <a href="{{ asset($meeting_doc->letter_bankruptcy_url) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                        </button>
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterBankruptcy('{{ $meeting_doc->id }}')"><i class="fa fa-times"></i></button>
                                </div>
                                @endif
                                <div id="validation-errors_letter_bankruptcy"></div>
                            </div>
                        </div>
                    </form>

                    <form>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.financial_audit_start_date') }}</label>
                            </div>
                            <div class="col-md-4">
                                <label class="input-group datepicker-only-init">
                                    <input type="text" class="form-control datepicker" placeholder="Start Date" id="audit_start" value="{{ $meeting_doc->audit_start_date }}"/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.financial_audit_end_date') }}</label>
                            </div>
                            <div class="col-md-4">
                                <label class="input-group datepicker-only-init">
                                    <input type="text" class="form-control datepicker" placeholder="End Date" id="audit_end" value="{{ $meeting_doc->audit_end_date }}"/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                            </div>
                            <div class="col-md-6">
                                <textarea class="form-control" placeholder="{{ trans('app.forms.remarks') }}" id="remarks" rows="5">{{ $meeting_doc->remarks }}</textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <?php if ($update_permission == 1) { ?>
                                <input type="hidden" id="agm_file_url" value="{{ $meeting_doc->agm_file_url }}"/>
                                <input type="hidden" id="egm_file_url" value="{{ $meeting_doc->egm_file_url }}"/>
                                <input type="hidden" id="minutes_meeting_file_url" value="{{ $meeting_doc->minutes_meeting_file_url }}"/>
                                <input type="hidden" id="jmc_file_url" value="{{ $meeting_doc->jmc_file_url }}"/>
                                <input type="hidden" id="ic_file_url" value="{{ $meeting_doc->ic_file_url }}"/>
                                <input type="hidden" id="attendance_file_url" value="{{ $meeting_doc->attendance_file_url }}"/>
                                <input type="hidden" id="audited_financial_file_url" value="{{ $meeting_doc->audited_financial_file_url }}"/>
                                <input type="hidden" id="audit_report_file_url" value="{{ $meeting_doc->audit_report_file_url }}"/>
                                <input type="hidden" id="letter_integrity_url" value="{{ $meeting_doc->letter_integrity_url }}"/>
                                <input type="hidden" id="letter_bankruptcy_url" value="{{ $meeting_doc->letter_bankruptcy_url }}"/>
                                <button type="button" class="btn btn-primary" id="submit_button" onclick="editMinutes()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AgmController@minutes')}}'">{{ trans('app.forms.cancel') }}</button>
                            <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $('.datepicker').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'YYYY-MM-DD'
    });

    $(document).ready(function () {
        //add
        var options1 = {
            beforeSubmit: showRequest1,
            success: showResponse1,
            dataType: 'json'
        };
        var options2 = {
            beforeSubmit: showRequest2,
            success: showResponse2,
            dataType: 'json'
        };
        var options3 = {
            beforeSubmit: showRequest3,
            success: showResponse3,
            dataType: 'json'
        };

        //add
        $('body').delegate('#audit_report_file', 'change', function () {
            $('#upload_audit_report_file').ajaxForm(options1).submit();
        });
        $('body').delegate('#letter_integrity', 'change', function () {
            $('#upload_letter_integrity').ajaxForm(options2).submit();
        });
        $('body').delegate('#letter_bankruptcy', 'change', function () {
            $('#upload_letter_bankruptcy').ajaxForm(options3).submit();
        });

        // agm_file
        $('body').delegate('#agm_file', 'change', function () {
            $('#upload_agm_file').ajaxForm({
                beforeSubmit: function () {
                    $("#agm_file_error").empty().hide();
                    return true;
                },
                success: function (result) {
                    if (result.success) {
                        $("#agm_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearAGMFile()'><i class='fa fa-times'></i> {{ trans('app.forms.clear') }}</button>").show();
                        $("#agm_file_clear").show();
                        $("#agm_file").css("color", "green");
                        $("#agm_file_url").val(result.file);
                    } else {
                        $("#agm_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                        $("#agm_file").css("color", "red");
                    }
                },
                dataType: 'json'
            }).submit();
        });

        // egm_file
        $('body').delegate('#egm_file', 'change', function () {
            $('#upload_egm_file').ajaxForm({
                beforeSubmit: function () {
                    $("#egm_file_error").empty().hide();
                    return true;
                },
                success: function (result) {
                    if (result.success) {
                        $("#egm_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearEGMFile()'><i class='fa fa-times'></i> {{ trans('app.forms.clear') }}</button>").show();
                        $("#egm_file_clear").show();
                        $("#egm_file").css("color", "green");
                        $("#egm_file_url").val(result.file);
                    } else {
                        $("#egm_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                        $("#egm_file").css("color", "red");
                    }
                },
                dataType: 'json'
            }).submit();
        });

        // minutes_meeting_file
        $('body').delegate('#minutes_meeting_file', 'change', function () {
            $('#upload_minutes_meeting_file').ajaxForm({
                beforeSubmit: function () {
                    $("#minutes_meeting_file_error").empty().hide();
                    return true;
                },
                success: function (result) {
                    if (result.success) {
                        $("#minutes_meeting_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> {{ trans('app.forms.clear') }}</button>").show();
                        $("#minutes_meeting_file_clear").show();
                        $("#minutes_meeting_file").css("color", "green");
                        $("#minutes_meeting_file_url").val(result.file);
                    } else {
                        $("#minutes_meeting_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                        $("#minutes_meeting_file").css("color", "red");
                    }
                },
                dataType: 'json'
            }).submit();
        });

        // jmc_file
        $('body').delegate('#jmc_file', 'change', function () {
            $('#upload_jmc_file').ajaxForm({
                beforeSubmit: function () {
                    $("#jmc_file_error").empty().hide();
                    return true;
                },
                success: function (result) {
                    if (result.success) {
                        $("#jmc_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> {{ trans('app.forms.clear') }}</button>").show();
                        $("#jmc_file_clear").show();
                        $("#jmc_file").css("color", "green");
                        $("#jmc_file_url").val(result.file);
                    } else {
                        $("#jmc_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                        $("#jmc_file").css("color", "red");
                    }
                },
                dataType: 'json'
            }).submit();
        });

        // ic_file
        $('body').delegate('#ic_file', 'change', function () {
            $('#upload_ic_file').ajaxForm({
                beforeSubmit: function () {
                    $("#ic_file_error").empty().hide();
                    return true;
                },
                success: function (result) {
                    if (result.success) {
                        $("#ic_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> {{ trans('app.forms.clear') }}</button>").show();
                        $("#ic_file_clear").show();
                        $("#ic_file").css("color", "green");
                        $("#ic_file_url").val(result.file);
                    } else {
                        $("#ic_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                        $("#ic_file").css("color", "red");
                    }
                },
                dataType: 'json'
            }).submit();
        });

        // attendance_file
        $('body').delegate('#attendance_file', 'change', function () {
            $('#upload_attendance_file').ajaxForm({
                beforeSubmit: function () {
                    $("#attendance_file_error").empty().hide();
                    return true;
                },
                success: function (result) {
                    if (result.success) {
                        $("#attendance_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> {{ trans('app.forms.clear') }}</button>").show();
                        $("#attendance_file_clear").show();
                        $("#attendance_file").css("color", "green");
                        $("#attendance_file_url").val(result.file);
                    } else {
                        $("#attendance_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                        $("#attendance_file").css("color", "red");
                    }
                },
                dataType: 'json'
            }).submit();
        });

        // audited_financial_file
        $('body').delegate('#audited_financial_file', 'change', function () {
            $('#upload_audited_financial_file').ajaxForm({
                beforeSubmit: function () {
                    $("#audited_financial_file_error").empty().hide();
                    return true;
                },
                success: function (result) {
                    if (result.success) {
                        $("#audited_financial_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> {{ trans('app.forms.clear') }}</button>").show();
                        $("#audited_financial_file_clear").show();
                        $("#audited_financial_file").css("color", "green");
                        $("#audited_financial_file_url").val(result.file);
                    } else {
                        $("#audited_financial_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                        $("#audited_financial_file").css("color", "red");
                    }
                },
                dataType: 'json'
            }).submit();
        });
    });

function clearAGMFile() {
        $("#agm_file").val("");
        $("#agm_file_url").val("");
        $("#agm_file").css("color", "");
        $("#agm_file_error").empty().hide();
    }

    function clearEGMFile() {
        $("#egm_file").val("");
        $("#egm_file_url").val("");
        $("#egm_file").css("color", "");
        $("#egm_file_error").empty().hide();
    }

    function clearMinutesMeetingFile() {
        $("#minutes_meeting_file").val("");
        $("#minutes_meeting_file_url").val("");
        $("#minutes_meeting_file").css("color", "");
        $("#minutes_meeting_file_error").empty().hide();
    }

    function clearJMCFile() {
        $("#jmc_file").val("");
        $("#jmc_file_url").val("");
        $("#jmc_file").css("color", "");
        $("#jmc_file_error").empty().hide();
    }

    function clearICFile() {
        $("#ic_file").val("");
        $("#ic_file_url").val("");
        $("#ic_file").css("color", "");
        $("#ic_file_error").empty().hide();
    }

    function clearAttendanceFile() {
        $("#attendance_file").val("");
        $("#attendance_file_url").val("");
        $("#attendance_file").css("color", "");
        $("#attendance_file_error").empty().hide();
    }

    function clearAuditedFinancialFile() {
        $("#audited_financial_file").val("");
        $("#audited_financial_file_url").val("");
        $("#audited_financial_file").css("color", "");
        $("#audited_financial_file_error").empty().hide();
    }

    //upload audit report file
    function showRequest1(formData, jqForm, options1) {
        $("#validation-errors_audit_report_file").hide().empty();
        return true;
    }
    function showResponse1(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_audit_report_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_audit_report_file").show();
            $("#audit_report_file").css("color", "red");
        } else {
            $("#validation-errors_audit_report_file").html("<i class='fa fa-check' id='check_audit_report_file' style='color:green;'></i>");
            $("#clear_audit_report_file").show();
            $("#validation-errors_audit_report_file").show();
            $("#audit_report_file").css("color", "green");
            $("#audit_report_file_url").val(response.file);
        }
    }

    //upload letter integrity
    function showRequest2(formData, jqForm, options2) {
        $("#validation-errors_letter_integrity").hide().empty();
        return true;
    }
    function showResponse2(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_integrity").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_integrity").show();
            $("#letter_integrity").css("color", "red");
        } else {
            $("#validation-errors_letter_integrity").html("<i class='fa fa-check' id='check_letter_integrity' style='color:green;'></i>");
            $("#clear_letter_integrity").show();
            $("#validation-errors_letter_integrity").show();
            $("#letter_integrity").css("color", "green");
            $("#letter_integrity_url").val(response.file);
        }
    }

    //upload letter bankruptcy
    function showRequest3(formData, jqForm, options3) {
        $("#validation-errors_letter_bankruptcy").hide().empty();
        return true;
    }
    function showResponse3(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_bankruptcy").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_bankruptcy").show();
            $("#letter_bankruptcy").css("color", "red");
        } else {
            $("#validation-errors_letter_bankruptcy").html("<i class='fa fa-check' id='check_letter_bankruptcy' style='color:green;'></i>");
            $("#clear_letter_bankruptcy").show();
            $("#validation-errors_letter_bankruptcy").show();
            $("#letter_bankruptcy").css("color", "green");
            $("#letter_bankruptcy_url").val(response.file);
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

    function editMinutes() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");

        $("#file_id_error").css("display", "none");

        var file_id = $("#file_id").val(),
                agm_date = $("#agm_date").val(),
                agm,
                agm_file_url = $("#agm_file_url").val(),
                egm,
                egm_file_url = $("#egm_file_url").val(),
                minit_meeting,
                minutes_meeting_file_url = $("#minutes_meeting_file_url").val(),
                jmc_copy,
                jmc_file_url = $("#jmc_file_url").val(),
                ic_list,
                ic_file_url = $("#ic_file_url").val(),
                attendance_list,
                attendance_file_url = $("#attendance_file_url").val(),
                audited_financial_report,
                audited_financial_file_url = $("#audited_financial_file_url").val(),
                audit_report = $("#audit_report").val(),
                audit_report_file_url = $("#audit_report_file_url").val(),
                letter_integrity_url = $("#letter_integrity_url").val(),
                letter_bankruptcy_url = $("#letter_bankruptcy_url").val(),
                audit_start = $("#audit_start").val(),
                audit_end = $("#audit_end").val(),
                remarks = $("#remarks").val();

        if (document.getElementById('agm').checked) {
            agm = 1;
        } else {
            agm = 0;
        }
        if (document.getElementById('egm').checked) {
            egm = 1;
        } else {
            egm = 0;
        }
        if (document.getElementById('minit_meeting').checked) {
            minit_meeting = 1;
        } else {
            minit_meeting = 0;
        }
        if (document.getElementById('jmc_copy').checked) {
            jmc_copy = 1;
        } else {
            jmc_copy = 0;
        }
        if (document.getElementById('ic_list').checked) {
            ic_list = 1;
        } else {
            ic_list = 0;
        }
        if (document.getElementById('attendance_list').checked) {
            attendance_list = 1;
        } else {
            attendance_list = 0;
        }
        if (document.getElementById('audited_financial_report').checked) {
            audited_financial_report = 1;
        } else {
            audited_financial_report = 0;
        }

        var error = 0;

        if (file_id.trim() == "") {
            $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File"]) }}</span>');
            $("#file_id").focus();
            $("#file_id_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AgmController@submitEditMinutes') }}",
                type: "POST",
                data: {
                    file_id: file_id,
                    agm_date: agm_date,
                    agm: agm,
                    agm_file_url: agm_file_url,
                    egm: egm,
                    egm_file_url: egm_file_url,
                    minit_meeting: minit_meeting,
                    minutes_meeting_file_url: minutes_meeting_file_url,
                    jmc_copy: jmc_copy,
                    jmc_file_url: jmc_file_url,
                    ic_list: ic_list,
                    ic_file_url: ic_file_url,
                    attendance_list: attendance_list,
                    attendance_file_url: attendance_file_url,
                    audited_financial_report: audited_financial_report,
                    audited_financial_file_url: audited_financial_file_url,
                    audit_report: audit_report,
                    audit_report_file_url: audit_report_file_url,
                    letter_integrity_url: letter_integrity_url,
                    letter_bankruptcy_url: letter_bankruptcy_url,
                    audit_start: audit_start,
                    audit_end: audit_end,
                    remarks: remarks,
                    id: '{{ $meeting_doc->id }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");

                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.minutes.store') }}</span>", function () {
                            window.location = '{{URL::action("AgmController@minutes") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }

    function deleteAuditReport(id) {
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
                url: "{{ URL::action('AdminController@deleteAuditReport') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteLetterIntegrity(id) {
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
                url: "{{ URL::action('AdminController@deleteLetterIntegrity') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteLetterBankruptcy(id) {
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
                url: "{{ URL::action('AdminController@deleteLetterBankruptcy') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteAGMFile(id) {
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
                url: "{{ URL::action('AdminController@deleteAGMFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteEGMFile(id) {
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
                url: "{{ URL::action('AdminController@deleteEGMFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteMinutesMeetingFile(id) {
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
                url: "{{ URL::action('AdminController@deleteMinutesMeetingFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteJMCFile(id) {
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
                url: "{{ URL::action('AdminController@deleteJMCFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteICFile(id) {
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
                url: "{{ URL::action('AdminController@deleteICFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteAttendanceFile(id) {
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
                url: "{{ URL::action('AdminController@deleteAttendanceFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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

    function deleteAuditedFinancialFile(id) {
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
                url: "{{ URL::action('AdminController@deleteAuditedFinancialFile') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
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
<!-- End Page Scripts-->

@stop
