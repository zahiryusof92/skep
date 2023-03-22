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
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">
                <section class="panel panel-pad">
                    <div class="row padding-vertical-20">
                        <div class="col-lg-12">
                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="color: red; font-style: italic;">
                                                * {{ trans('app.forms.mandatory_fields') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            <span style="color: red; font-style: italic;">*</span>
                                            {{ trans('app.forms.file_no') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="file_id" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $file)
                                                <option value="{{ $file->id }}"
                                                    {{ $file->id == $meeting_doc->file_id ? ' selected' : '' }}>
                                                    {{ $file->file_no }}
                                                </option>
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
                                            <input type="text" class="form-control"
                                                placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date_raw"
                                                value="{{ $meeting_doc->agm_date != '0000-00-00' ? date('d-m-Y', strtotime($meeting_doc->agm_date)) : '' }}" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="agm_date" value="{{ $meeting_doc->agm_date }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.annual_general_meeting') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="agm" name="agm" value="1"
                                            {{ $meeting_doc->agm == 1 ? ' checked' : '' }}> {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="agm" name="agm" value="0"
                                            {{ $meeting_doc->agm == 0 ? ' checked' : '' }}>
                                        {{ trans('app.forms.no') }}
                                    </div>
                                </div>
                            </form>

                            <form id="upload_agm_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadAGMFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="agm_file" id="agm_file">
                                        @if ($meeting_doc->agm_file_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->agm_file_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAGMFile('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="agm_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.extra_general_meeting') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="egm" name="egm" value="1"
                                            {{ $meeting_doc->egm == 1 ? ' checked' : '' }}>
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="egm" name="egm" value="0"
                                            {{ $meeting_doc->egm == 0 ? ' checked' : '' }}>
                                        {{ trans('app.forms.no') }}
                                    </div>
                                </div>
                            </form>

                            <form id="upload_egm_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadEGMFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="egm_file" id="egm_file">
                                        @if ($meeting_doc->egm_file_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->egm_file_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteEGMFile('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="egm_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.meeting_minutes') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="minit_meeting" name="minit_meeting" value="1"
                                            {{ $meeting_doc->minit_meeting == 1 ? ' checked' : '' }}>
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="minit_meeting" name="minit_meeting" value="0"
                                            {{ $meeting_doc->minit_meeting == 0 ? ' checked' : '' }}>
                                        {{ trans('app.forms.no') }}
                                    </div>
                                </div>
                            </form>

                            <form id="upload_minutes_meeting_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadMinutesMeetingFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="minutes_meeting_file" id="minutes_meeting_file">
                                        @if ($meeting_doc->minutes_meeting_file_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->minutes_meeting_file_url) }}"
                                                    target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesMeetingFile('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="minutes_meeting_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            @if (Auth::user()->getAdmin())
                                <!-- OCR -->
                                <form id="upload_minutes_meeting_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="minutes_meeting_ocr" id="minutes_meeting_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="minutes_meeting_ocr_error"></div>
                                            @if ($meeting_doc->minutesMeetingOcr && !empty($meeting_doc->minutesMeetingOcr->url))
                                                <div>
                                                    <a href="{{ asset($meeting_doc->minutesMeetingOcr->url) }}"
                                                        target="_blank">
                                                        <button type="button" class="btn btn-xs btn-own"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Download File">
                                                            <i class="icmn-file-download2"></i>
                                                            {{ trans('app.forms.download') }}
                                                        </button>
                                                    </a>
                                                    &nbsp;
                                                    {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesMeetingOcr('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.jmc_spa_copy') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="jmc_copy" name="jmc_copy" value="1"
                                            {{ $meeting_doc->jmc_spa == 1 ? ' checked' : '' }}>
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="jmc_copy" name="jmc_copy" value="0"
                                            {{ $meeting_doc->jmc_spa == 0 ? ' checked' : '' }}>
                                        {{ trans('app.forms.no') }}
                                    </div>
                                </div>
                            </form>

                            <form id="upload_jmc_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadJMCFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="jmc_file" id="jmc_file">
                                        @if ($meeting_doc->jmc_file_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->jmc_file_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteJMCFile('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="jmc_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            @if (Auth::user()->getAdmin())
                                <!-- OCR -->
                                <form id="upload_copy_of_spa_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="copy_of_spa_ocr" id="copy_of_spa_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="copy_of_spa_ocr_error"></div>
                                            @if ($meeting_doc->copyOfSpaOcr && !empty($meeting_doc->copyOfSpaOcr->url))
                                                <div>
                                                    <a href="{{ asset($meeting_doc->copyOfSpaOcr->url) }}" target="_blank">
                                                        <button type="button" class="btn btn-xs btn-own"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Download File">
                                                            <i class="icmn-file-download2"></i>
                                                            {{ trans('app.forms.download') }}
                                                        </button>
                                                    </a>
                                                    &nbsp;
                                                    {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesMeetingOcr('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.identity_card_list') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="ic_list" name="ic_list" value="1"
                                            {{ $meeting_doc->identity_card == 1 ? ' checked' : '' }}>
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="ic_list" name="ic_list" value="0"
                                            {{ $meeting_doc->identity_card == 0 ? ' checked' : '' }}>
                                        {{ trans('app.forms.no') }}
                                    </div>
                                </div>
                            </form>

                            <form id="upload_ic_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadICFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="ic_file" id="ic_file">
                                        @if ($meeting_doc->ic_file_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->ic_file_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteICFile('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="ic_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.attendance_list') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="attendance_list" name="attendance_list" value="1"
                                            {{ $meeting_doc->attendance == 1 ? ' checked' : '' }}>
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="attendance_list" name="attendance_list" value="0"
                                            {{ $meeting_doc->attendance == 0 ? ' checked' : '' }}>
                                        {{ trans('app.forms.no') }}
                                    </div>
                                </div>
                            </form>

                            <form id="upload_attendance_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadAttendanceFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="attendance_file" id="attendance_file">
                                        @if ($meeting_doc->attendance_file_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->attendance_file_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAttendanceFile('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="attendance_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            @if (Auth::user()->getAdmin())
                                <!-- OCR -->
                                <form id="upload_attendance_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="attendance_ocr" id="attendance_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="attendance_ocr_error"></div>
                                            @if ($meeting_doc->attendanceOcr && !empty($meeting_doc->attendanceOcr->url))
                                                <div>
                                                    <a href="{{ asset($meeting_doc->attendanceOcr->url) }}" target="_blank">
                                                        <button type="button" class="btn btn-xs btn-own"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Download File">
                                                            <i class="icmn-file-download2"></i>
                                                            {{ trans('app.forms.download') }}
                                                        </button>
                                                    </a>
                                                    &nbsp;
                                                    {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAttendanceOcr('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.audited_financial_report') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="audited_financial_report" name="audited_financial_report"
                                            value="1" {{ $meeting_doc->financial_report == 1 ? ' checked' : '' }}>
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="audited_financial_report" name="audited_financial_report"
                                            value="0" {{ $meeting_doc->financial_report == 0 ? ' checked' : '' }}>
                                        {{ trans('app.forms.no') }}
                                    </div>
                                </div>
                            </form>

                            <form id="upload_audited_financial_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadAuditedFinancialFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="audited_financial_file" id="audited_financial_file">
                                        @if ($meeting_doc->audited_financial_file_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->audited_financial_file_url) }}"
                                                    target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAuditedFinancialFile('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="audited_financial_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            @if (Auth::user()->getAdmin())
                                <!-- OCR -->
                                <form id="upload_audited_financial_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="audited_financial_ocr" id="audited_financial_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="audited_financial_ocr_error"></div>
                                            @if ($meeting_doc->auditedFinancialOcr && !empty($meeting_doc->auditedFinancialOcr->url))
                                                <div>
                                                    <a href="{{ asset($meeting_doc->auditedFinancialOcr->url) }}"
                                                        target="_blank">
                                                        <button type="button" class="btn btn-xs btn-own"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Download File">
                                                            <i class="icmn-file-download2"></i>
                                                            {{ trans('app.forms.download') }}
                                                        </button>
                                                    </a>
                                                    &nbsp;
                                                    {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAuditedFinancialOcr('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.financial_audit_report') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control"
                                            placeholder="{{ trans('app.forms.financial_audit_report') }}" id="audit_report"
                                            value="{{ $meeting_doc->audit_report }}" />
                                    </div>
                                </div>
                            </form>

                            <form id="upload_audit_report_file" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadAuditReportFile') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_audit_report_file" class="btn btn-xs btn-danger"
                                            onclick="clearAuditFile()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="audit_report_file" id="audit_report_file">
                                        @if ($meeting_doc->audit_report_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->audit_report_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAuditReport('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-errors_audit_report_file"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_letter_integrity" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadLetterIntegrity') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.pledge_letter_of_integrity') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_letter_integrity" class="btn btn-xs btn-danger"
                                            onclick="clearLetterIntegrity()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="letter_integrity" id="letter_integrity">
                                        @if ($meeting_doc->letter_integrity_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->letter_integrity_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterIntegrity('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-errors_letter_integrity"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_letter_bankruptcy" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadLetterBankruptcy') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.declaration_letter_of_non_bankruptcy') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_letter_bankruptcy" class="btn btn-xs btn-danger"
                                            onclick="clearLetterBankruptcy()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="letter_bankruptcy" id="letter_bankruptcy">
                                        @if ($meeting_doc->letter_bankruptcy_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->letter_bankruptcy_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteLetterBankruptcy('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-errors_letter_bankruptcy"></div>
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.financial_audit_start_date') }}
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-group datepicker-only-init">
                                            <input type="text" class="form-control" placeholder="Start Date"
                                                id="audit_start_raw"
                                                value="{{ $meeting_doc->audit_start_date != '0000-00-00' ? date('d-m-Y', strtotime($meeting_doc->audit_start_date)) : '' }}" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="audit_start"
                                            value="{{ $meeting_doc->audit_start_date }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.financial_audit_end_date') }}
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-group datepicker-only-init">
                                            <input type="text" class="form-control" placeholder="End Date"
                                                id="audit_end_raw"
                                                value="{{ $meeting_doc->audit_end_date != '0000-00-00' ? date('d-m-Y', strtotime($meeting_doc->audit_end_date)) : '' }}" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="audit_end" value="{{ $meeting_doc->audit_end_date }}" />
                                    </div>
                                </div>
                            </form>

                            <hr />

                            <form id="upload_notice_agm_egm" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadNoticeAgmEgm') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_notice_agm_egm') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_notice_agm_egm" class="btn btn-xs btn-danger"
                                            onclick="clearNoticeAgmEgm()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="notice_agm_egm" id="notice_agm_egm">
                                        @if ($meeting_doc->notice_agm_egm_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->notice_agm_egm_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteNoticeAgmEgm('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-notice_agm_egm"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_minutes_agm_egm" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadMinutesAgmEgm') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_minutes_agm_egm') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_minutes_agm_egm" class="btn btn-xs btn-danger"
                                            onclick="clearMinutesAgmEgm()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="minutes_agm_egm" id="minutes_agm_egm">
                                        @if ($meeting_doc->minutes_agm_egm_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->minutes_agm_egm_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesAgmEgm('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-minutes_agm_egm"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_minutes_ajk" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadMinutesAjk') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_minutes_ajk') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_minutes_ajk" class="btn btn-xs btn-danger"
                                            onclick="clearMinutesAjk()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="minutes_ajk" id="minutes_ajk">
                                        @if ($meeting_doc->minutes_ajk_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->minutes_ajk_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMinutesAjk('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-minutes_ajk"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_eligible_vote" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadEligibleVote') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_eligible_vote') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_eligible_vote" class="btn btn-xs btn-danger"
                                            onclick="clearEligbleVote()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="eligible_vote" id="eligible_vote">
                                        @if ($meeting_doc->eligible_vote_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->eligible_vote_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteEligibleVote('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-eligible_vote"></div>
                                    </div>
                                </div>
                            </form>

                            @if (Auth::user()->getAdmin())
                                <!-- OCR -->
                                <form id="upload_eligible_vote_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="eligible_vote_ocr" id="eligible_vote_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="eligible_vote_ocr_error"></div>
                                            @if ($meeting_doc->eligibleVoteOcr && !empty($meeting_doc->eligibleVoteOcr->url))
                                                <div>
                                                    <a href="{{ asset($meeting_doc->eligibleVoteOcr->url) }}"
                                                        target="_blank">
                                                        <button type="button" class="btn btn-xs btn-own"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Download File">
                                                            <i class="icmn-file-download2"></i>
                                                            {{ trans('app.forms.download') }}
                                                        </button>
                                                    </a>
                                                    &nbsp;
                                                    {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteEligibleVoteOcr('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form id="upload_attend_meeting" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadAttendMeeting') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_attend_meeting') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_attend_meeting" class="btn btn-xs btn-danger"
                                            onclick="clearAttendMeeting()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="attend_meeting" id="attend_meeting">
                                        @if ($meeting_doc->attend_meeting_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->attend_meeting_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAttendMeeting('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-attend_meeting"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_proksi" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadProksi') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_proksi') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_proksi" class="btn btn-xs btn-danger"
                                            onclick="clearProksi()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="proksi" id="proksi">
                                        @if ($meeting_doc->proksi_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->proksi_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteProksi('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-proksi"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_ajk_info" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadAjkInfo') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_ajk_info') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_ajk_info" class="btn btn-xs btn-danger"
                                            onclick="clearAjkInfo()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="ajk_info" id="ajk_info">
                                        @if ($meeting_doc->ajk_info_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->ajk_info_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteAjkInfo('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-ajk_info"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_ic" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadIc') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_ic') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_ic" class="btn btn-xs btn-danger"
                                            onclick="clearIc()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="ic" id="ic">
                                        @if ($meeting_doc->ic_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->ic_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteIc('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-ic"></div>
                                    </div>
                                </div>
                            </form>

                            <?php if ($meeting_doc->type == 'JMB') { ?>
                            <form id="upload_purchase_aggrement" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadPurchaseAggrement') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_purchase_aggrement') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_purchase_aggrement" class="btn btn-xs btn-danger"
                                            onclick="clearPurchaseAggrement()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="purchase_aggrement" id="purchase_aggrement">
                                        @if ($meeting_doc->purchase_aggrement_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->purchase_aggrement_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deletePurchaseAggrement('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-purchase_aggrement"></div>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>

                            <?php if ($meeting_doc->type == 'MC') { ?>
                            <form id="upload_strata_title" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadStrataTitle') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_strata_title') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_strata_title" class="btn btn-xs btn-danger"
                                            onclick="clearStrataTitle()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="strata_title" id="strata_title">
                                        @if ($meeting_doc->strata_title_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->strata_title_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteStrataTitle('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-strata_title"></div>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>

                            <form id="upload_maintenance_statement" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadMaintenanceStatement') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_maintenance_statement') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_maintenance_statement" class="btn btn-xs btn-danger"
                                            onclick="clearMaintenanceStatement()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="maintenance_statement" id="maintenance_statement">
                                        @if ($meeting_doc->maintenance_statement_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->maintenance_statement_url) }}"
                                                    target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteMaintenanceStatement('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-maintenance_statement"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_integrity_pledge" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadIntegrityPledge') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_integrity_pledge') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_integrity_pledge" class="btn btn-xs btn-danger"
                                            onclick="clearIntegrityPledge()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="integrity_pledge" id="integrity_pledge">
                                        @if ($meeting_doc->integrity_pledge_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->integrity_pledge_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteIntegrityPledge('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-integrity_pledge"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_report_audited_financial" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadReportAuditedFinancial') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_report_audited_financial') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_report_audited_financial"
                                            class="btn btn-xs btn-danger" onclick="clearReportAuditedFinancial()"
                                            style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="report_audited_financial" id="report_audited_financial">
                                        @if ($meeting_doc->report_audited_financial_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->report_audited_financial_url) }}"
                                                    target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteReportAuditedFinancial('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-report_audited_financial"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_house_rules" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadHouseRules') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_house_rules') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" id="clear_house_rules" class="btn btn-xs btn-danger"
                                            onclick="clearHouseRules()" style="display: none;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        &nbsp;
                                        <input type="file" name="house_rules" id="house_rules">
                                        @if ($meeting_doc->house_rules_url != '')
                                            <div>
                                                <a href="{{ asset($meeting_doc->house_rules_url) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip"
                                                        data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i>
                                                        {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteHouseRules('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                            </div>
                                        @endif
                                        <div id="validation-house_rules"></div>
                                    </div>
                                </div>
                            </form>

                            @if (Auth::user()->getAdmin())
                                <!-- OCR -->
                                <form id="upload_house_rules_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="house_rules_ocr" id="house_rules_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="house_rules_ocr_error"></div>
                                            @if ($meeting_doc->houseRulesOcr && !empty($meeting_doc->houseRulesOcr->url))
                                                <div>
                                                    <a href="{{ asset($meeting_doc->houseRulesOcr->url) }}" target="_blank">
                                                        <button type="button" class="btn btn-xs btn-own"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Download File">
                                                            <i class="icmn-file-download2"></i>
                                                            {{ trans('app.forms.download') }}
                                                        </button>
                                                    </a>
                                                    &nbsp;
                                                    {{-- <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="deleteHouseRulesOcr('{{ \Helper\Helper::encode($meeting_doc->id) }}')"><i class="fa fa-times"></i></button> --}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.remarks') }}" id="remarks" rows="5">{{ $meeting_doc->remarks }}</textarea>
                                    </div>
                                </div>

                                <hr />

                                <h5>{{ trans('Endorsement') }}</h5>

                                @if ($endorse)
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                <span style="color: red; font-style: italic;">*</span>
                                                {{ trans('app.forms.status') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="status" name="status" class="form-control select2">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                <option value="pending"
                                                    {{ $meeting_doc->meetingDocumentStatus && $meeting_doc->meetingDocumentStatus->status == 'pending' ? ' selected' : '' }}>
                                                    {{ trans('app.forms.pending') }}</option>
                                                <option value="approved"
                                                    {{ $meeting_doc->meetingDocumentStatus && $meeting_doc->meetingDocumentStatus->status == 'approved' ? ' selected' : '' }}>
                                                    {{ trans('app.forms.approved') }}</option>
                                                <option value="rejected"
                                                    {{ $meeting_doc->meetingDocumentStatus && $meeting_doc->meetingDocumentStatus->status == 'rejected' ? ' selected' : '' }}>
                                                    {{ trans('app.forms.rejected') }}</option>
                                            </select>
                                            <div id="status_error" style="display:none;"></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">{{ trans('app.forms.reason') }}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea class="form-control" placeholder="{{ trans('app.forms.reason') }}" id="reason" name="reason"
                                                rows="5">{{ $meeting_doc->meetingDocumentStatus ? $meeting_doc->meetingDocumentStatus->reason : null }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                <span style="color: red; font-style: italic;">*</span>
                                                {{ trans('app.forms.endorsed_by') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="endorsed_by" name="endorsed_by"
                                                placeholder="{{ trans('app.forms.endorsed_by') }}"
                                                value="{{ $meeting_doc->meetingDocumentStatus ? $meeting_doc->meetingDocumentStatus->endorsed_by : '' }}" />
                                            <div id="endorsed_by_error" style="display:none;"></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                <span style="color: red; font-style: italic;">*</span>
                                                {{ trans('app.forms.endorsed_email') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" id="endorsed_email"
                                                name="endorsed_email" placeholder="{{ trans('app.forms.endorsed_email') }}"
                                                value="{{ $meeting_doc->meetingDocumentStatus ? $meeting_doc->meetingDocumentStatus->endorsed_email : '' }}" />
                                            <div id="endorsed_email_error" style="display:none;"></div>
                                        </div>
                                    </div>

                                    @if ($meeting_doc->meetingDocumentStatus && !empty($meeting_doc->meetingDocumentStatus->created_at))
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                <span style="color: red; font-style: italic;">*</span>
                                                {{ trans('app.forms.endorsed_date') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" id="endorsed_date"
                                                name="endorsed_date" placeholder="{{ trans('app.forms.endorsed_date') }}"
                                                value="{{ date('Y-m-d H:i:s', strtotime($meeting_doc->meetingDocumentStatus->created_at)) }}" />
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                {{ trans('app.forms.status') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control select2" disabled>
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                <option value="pending"
                                                    {{ $meeting_doc->meetingDocumentStatus && $meeting_doc->meetingDocumentStatus->status == 'pending' ? ' selected' : '' }}>
                                                    {{ trans('app.forms.pending') }}</option>
                                                <option value="approved"
                                                    {{ $meeting_doc->meetingDocumentStatus && $meeting_doc->meetingDocumentStatus->status == 'approved' ? ' selected' : '' }}>
                                                    {{ trans('app.forms.approved') }}</option>
                                                <option value="rejected"
                                                    {{ $meeting_doc->meetingDocumentStatus && $meeting_doc->meetingDocumentStatus->status == 'rejected' ? ' selected' : '' }}>
                                                    {{ trans('app.forms.rejected') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">{{ trans('app.forms.reason') }}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea class="form-control" placeholder="{{ trans('app.forms.reason') }}" rows="5" disabled>{{ $meeting_doc->meetingDocumentStatus ? $meeting_doc->meetingDocumentStatus->reason : null }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                {{ trans('app.forms.endorsed_by') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                placeholder="{{ trans('app.forms.endorsed_by') }}"
                                                value="{{ $meeting_doc->meetingDocumentStatus ? $meeting_doc->meetingDocumentStatus->endorsed_by : '' }}"
                                                disabled />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">
                                                {{ trans('app.forms.endorsed_email') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" class="form-control"
                                                placeholder="{{ trans('app.forms.endorsed_email') }}"
                                                value="{{ $meeting_doc->meetingDocumentStatus ? $meeting_doc->meetingDocumentStatus->endorsed_email : '' }}"
                                                disabled />
                                        </div>
                                    </div>
                                    <input type="hidden" id="status" value="" />
                                    <input type="hidden" id="reason" value="" />
                                    <input type="hidden" id="endorsed_by" value="" />
                                    <input type="hidden" id="endorsed_email" value="" />
                                @endif

                                <div class="form-actions">
                                    <?php if ($update_permission == 1) { ?>
                                    <input type="hidden" id="agm_file_url" value="{{ $meeting_doc->agm_file_url }}" />
                                    <input type="hidden" id="egm_file_url" value="{{ $meeting_doc->egm_file_url }}" />
                                    <input type="hidden" id="minutes_meeting_file_url"
                                        value="{{ $meeting_doc->minutes_meeting_file_url }}" />
                                    <input type="hidden" id="jmc_file_url" value="{{ $meeting_doc->jmc_file_url }}" />
                                    <input type="hidden" id="ic_file_url" value="{{ $meeting_doc->ic_file_url }}" />
                                    <input type="hidden" id="attendance_file_url"
                                        value="{{ $meeting_doc->attendance_file_url }}" />
                                    <input type="hidden" id="audited_financial_file_url"
                                        value="{{ $meeting_doc->audited_financial_file_url }}" />
                                    <input type="hidden" id="audit_report_file_url"
                                        value="{{ $meeting_doc->audit_report_file_url }}" />
                                    <input type="hidden" id="letter_integrity_url"
                                        value="{{ $meeting_doc->letter_integrity_url }}" />
                                    <input type="hidden" id="letter_bankruptcy_url"
                                        value="{{ $meeting_doc->letter_bankruptcy_url }}" />
                                    <input type="hidden" id="notice_agm_egm_url"
                                        value="{{ $meeting_doc->notice_agm_egm_url }}" />
                                    <input type="hidden" id="minutes_agm_egm_url"
                                        value="{{ $meeting_doc->minutes_agm_egm_url }}" />
                                    <input type="hidden" id="minutes_ajk_url"
                                        value="{{ $meeting_doc->minutes_ajk_url }}" />
                                    <input type="hidden" id="eligible_vote_url"
                                        value="{{ $meeting_doc->eligible_vote_url }}" />
                                    <input type="hidden" id="attend_meeting_url"
                                        value="{{ $meeting_doc->attend_meeting_url }}" />
                                    <input type="hidden" id="proksi_url" value="{{ $meeting_doc->proksi_url }}" />
                                    <input type="hidden" id="ajk_info_url" value="{{ $meeting_doc->ajk_info_url }}" />
                                    <input type="hidden" id="ic_url" value="{{ $meeting_doc->ic_url }}" />
                                    <input type="hidden" id="purchase_aggrement_url"
                                        value="{{ $meeting_doc->purchase_aggrement_url }}" />
                                    <input type="hidden" id="strata_title_url"
                                        value="{{ $meeting_doc->strata_title_url }}" />
                                    <input type="hidden" id="maintenance_statement_url"
                                        value="{{ $meeting_doc->maintenance_statement_url }}" />
                                    <input type="hidden" id="integrity_pledge_url"
                                        value="{{ $meeting_doc->integrity_pledge_url }}" />
                                    <input type="hidden" id="report_audited_financial_url"
                                        value="{{ $meeting_doc->report_audited_financial_url }}" />
                                    <input type="hidden" id="house_rules_url"
                                        value="{{ $meeting_doc->house_rules_url }}" />
                                    <input type="hidden" id="minutes_meeting_ocr_url"
                                        value="{{ $meeting_doc->minutesMeetingOcr ? $meeting_doc->minutesMeetingOcr->url : '' }}" />
                                    <input type="hidden" id="copy_of_spa_ocr_url"
                                        value="{{ $meeting_doc->copyOfSpaOcr ? $meeting_doc->copyOfSpaOcr->url : '' }}" />
                                    <input type="hidden" id="attendance_ocr_url"
                                        value="{{ $meeting_doc->attendanceOcr ? $meeting_doc->attendanceOcr->url : '' }}" />
                                    <input type="hidden" id="audited_financial_ocr_url"
                                        value="{{ $meeting_doc->auditedFinancialOcr ? $meeting_doc->auditedFinancialOcr->url : '' }}" />
                                    <input type="hidden" id="eligible_vote_ocr_url"
                                        value="{{ $meeting_doc->eligibleVoteOcr ? $meeting_doc->eligibleVoteOcr->url : '' }}" />
                                    <input type="hidden" id="house_rules_ocr_url"
                                        value="{{ $meeting_doc->houseRulesOcr ? $meeting_doc->houseRulesOcr->url : '' }}" />

                                    <button type="button" class="btn btn-own" id="submit_button"
                                        onclick="editMinutes()">
                                        {{ trans('app.forms.submit') }}
                                    </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default" id="cancel_button"
                                        onclick="window.location ='{{ URL::action('AgmController@minutes') }}'">
                                        {{ trans('app.forms.cancel') }}
                                    </button>
                                    <img id="loading" style="display:none;"
                                        src="{{ asset('assets/common/img/input-spinner.gif') }}" />
                                </div>
                            </form>
                            <!-- End Form -->
                        </div>
                    </div>
                </section>
            </div>
        </section>
    <!-- End -->
    </div>

    <!-- Page Scripts -->
    <script src="{{ asset('assets/common/js/edit_monitoring.js') }}"></script>

    <script>
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
                notice_agm_egm_url = $("#notice_agm_egm_url").val(),
                minutes_agm_egm_url = $("#minutes_agm_egm_url").val(),
                minutes_ajk_url = $("#minutes_ajk_url").val(),
                eligible_vote_url = $("#eligible_vote_url").val(),
                attend_meeting_url = $("#attend_meeting_url").val(),
                proksi_url = $("#proksi_url").val(),
                ajk_info_url = $("#ajk_info_url").val(),
                ic_url = $("#ic_url").val(),
                purchase_aggrement_url = $("#purchase_aggrement_url").val(),
                strata_title_url = $("#strata_title_url").val(),
                maintenance_statement_url = $("#maintenance_statement_url").val(),
                integrity_pledge_url = $("#integrity_pledge_url").val(),
                report_audited_financial_url = $("#report_audited_financial_url").val(),
                house_rules_url = $("#house_rules_url").val(),
                remarks = $("#remarks").val(),
                minutes_meeting_ocr_url = $("#minutes_meeting_ocr_url").val(),
                copy_of_spa_ocr_url = $("#copy_of_spa_ocr_url").val(),
                attendance_ocr_url = $("#attendance_ocr_url").val(),
                audited_financial_ocr_url = $("#audited_financial_ocr_url").val(),
                eligible_vote_ocr_url = $("#eligible_vote_ocr_url").val(),
                house_rules_ocr_url = $("#house_rules_ocr_url").val(),
                status = $("#status").val(),
                reason = $("#reason").val(),
                endorsed_by = $("#endorsed_by").val(),
                endorsed_email = $("#endorsed_email").val();

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
                $("#file_id_error").html(
                    '<span style="color:red;font-style:italic;font-size:13px;">{{ trans('app.errors.select', ['attribute' => 'File']) }}</span>'
                );
                $("#file_id").focus();
                $("#file_id_error").css("display", "block");
                error = 1;
            }

            if ('{{ $endorse }}') {
                if (status.trim() == "") {
                    $("#status_error").html(
                        '<span style="color:red;font-style:italic;font-size:13px;">{{ trans('app.errors.select', ['attribute' => 'Status']) }}</span>'
                    );
                    $("#status_error").css("display", "block");
                    error = 1;
                }
                if (endorsed_by.trim() == "") {
                    $("#endorsed_by_error").html(
                        '<span style="color:red;font-style:italic;font-size:13px;">{{ trans('app.errors.required', ['attribute' => 'Endorsed By']) }}</span>'
                    );
                    $("#endorsed_by_error").css("display", "block");
                    error = 1;
                }
                if (endorsed_email.trim() == "") {
                    $("#endorsed_email_error").html(
                        '<span style="color:red;font-style:italic;font-size:13px;">{{ trans('app.errors.required', ['attribute' => 'Endorsed E-mail']) }}</span>'
                    );
                    $("#endorsed_email_error").css("display", "block");
                    error = 1;
                }
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
                        notice_agm_egm_url: notice_agm_egm_url,
                        minutes_agm_egm_url: minutes_agm_egm_url,
                        minutes_ajk_url: minutes_ajk_url,
                        eligible_vote_url: eligible_vote_url,
                        attend_meeting_url: attend_meeting_url,
                        proksi_url: proksi_url,
                        ajk_info_url: ajk_info_url,
                        ic_url: ic_url,
                        purchase_aggrement_url: purchase_aggrement_url,
                        strata_title_url: strata_title_url,
                        maintenance_statement_url: maintenance_statement_url,
                        integrity_pledge_url: integrity_pledge_url,
                        report_audited_financial_url: report_audited_financial_url,
                        house_rules_url: house_rules_url,
                        remarks: remarks,
                        minutes_meeting_ocr_url: minutes_meeting_ocr_url,
                        copy_of_spa_ocr_url: copy_of_spa_ocr_url,
                        attendance_ocr_url: attendance_ocr_url,
                        audited_financial_ocr_url: audited_financial_ocr_url,
                        eligible_vote_ocr_url: eligible_vote_ocr_url,
                        house_rules_ocr_url: house_rules_ocr_url,
                        status: status,
                        reason: reason,
                        endorsed_by: endorsed_by,
                        endorsed_email: endorsed_email,
                        id: '{{ \Helper\Helper::encode($meeting_doc->id) }}'
                    },
                    beforeSend: function() {
                        $.blockUI({
                            message: '{{ trans('app.confirmation.please_wait') }}'
                        });
                    },
                    success: function(data) {
                        $("#loading").css("display", "none");
                        $("#submit_button").removeAttr("disabled");
                        $("#cancel_button").removeAttr("disabled");

                        if (data.trim() == "true") {
                            bootbox.alert(
                                "<span style='color:green;'>{{ trans('app.successes.minutes.store') }}</span>",
                                function() {
                                    window.location = '{{ URL::action('AgmController@minutes') }}';
                                });
                        } else {
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                });
            } else {
                $("#loading").css("display", "none");
                $("#submit_button").removeAttr("disabled");
                $("#cancel_button").removeAttr("disabled");
            }
        }

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
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteAGMDetails') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.trim() == "true") {
                            $.notify({
                                message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans('app.successes.deleted_successfully') }}</p>'
                            }, {
                                type: 'success',
                                placement: {
                                    align: "center"
                                }
                            });
                            location.reload();
                        } else {
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
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
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteAJKDetails') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.trim() == "true") {
                            $.notify({
                                message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans('app.successes.deleted_successfully') }}</p>'
                            }, {
                                type: 'success',
                                placement: {
                                    align: "center"
                                }
                            });
                            location.reload();
                        } else {
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
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
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteAuditReport') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
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
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteLetterIntegrity') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
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
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteLetterBankruptcy') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteNoticeAgmEgm(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteNoticeAgmEgm') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteMinutesAgmEgm(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteMinutesAgmEgm') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteMinutesAjk(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteMinutesAjk') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteEligibleVote(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteEligibleVote') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteAttendMeeting(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteAttendMeeting') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteProksi(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteProksi') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteAjkInfo(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteAjkInfo') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteIc(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteIc') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deletePurchaseAggrement(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deletePurchaseAggrement') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteStrataTitle(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteStrataTitle') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteMaintenanceStatement(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteMaintenanceStatement') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteIntegrityPledge(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteIntegrityPledge') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteReportAuditedFinancial(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteReportAuditedFinancial') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }

        function deleteHouseRules(id) {
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $.ajax({
                    url: "{{ URL::action('AdminController@deleteHouseRules') }}",
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
                            bootbox.alert(
                                "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                            );
                        }
                    }
                });
            });
        }
    </script>
    <!-- End Page Scripts-->
@endsection
