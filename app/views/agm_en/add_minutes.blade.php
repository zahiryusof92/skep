@extends('layout.english_layout.default')

@section('content')
    <?php
    $insert_permission = 0;
    
    foreach ($user_permission as $permission) {
        if ($permission->submodule_id == 32) {
            $insert_permission = $permission->insert_permission;
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

                                <?php if (Auth::user()->isJMB()) { ?>
                                <input type="hidden" id="type" value="jmb" />
                                <?php } else if (Auth::user()->isMC()) { ?>
                                <input type="hidden" id="type" value="mc" />
                                <?php } else { ?>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            <span style="color: red; font-style: italic;">*</span>
                                            {{ trans('app.forms.type_jmb_mc') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="type" class="form-control select2" onchange="typeDetails()">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            <option value="jmb">JMB</option>
                                            <option value="mc">MC</option>
                                        </select>
                                        <div id="type_error" style="display:none;"></div>
                                    </div>
                                </div>
                                <?php } ?>

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
                                                <option value="{{ $file->id }}">
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
                                                placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date_raw" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="agm_date" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.annual_general_meeting') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="agm" name="agm" value="1">
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="agm" name="agm" value="0">
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
                                        <input type="radio" id="egm" name="egm" value="1">
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="egm" name="egm" value="0">
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
                                        <input type="radio" id="minit_meeting" name="minit_meeting" value="1">
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="minit_meeting" name="minit_meeting" value="0">
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
                                        <div id="minutes_meeting_file_error"></div>
                                    </div>
                                </div>
                            </form>
                            
                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.jmc_spa_copy') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="jmc_copy" name="jmc_copy" value="1">
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="jmc_copy" name="jmc_copy" value="0">
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
                                        <div id="jmc_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.identity_card_list') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="ic_list" name="ic_list" value="1">
                                        {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="ic_list" name="ic_list" value="0">
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
                                        <input type="radio" id="attendance_list" name="attendance_list"
                                            value="1"> {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="attendance_list" name="attendance_list"
                                            value="0"> {{ trans('app.forms.no') }}
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
                                        <div id="attendance_file_error"></div>
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.audited_financial_report') }}
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="audited_financial_report"
                                            name="audited_financial_report" value="1"> {{ trans('app.forms.yes') }}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="audited_financial_report"
                                            name="audited_financial_report" value="0"> {{ trans('app.forms.no') }}
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
                                        <div id="audited_financial_file_error"></div>
                                    </div>
                                </div>
                            </form>
                            
                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.financial_audit_report') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control"
                                            placeholder="{{ trans('app.forms.financial_audit_report') }}"
                                            id="audit_report" />
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
                                        <input type="file" name="audit_report_file" id="audit_report_file">
                                        <div id="audit_report_file_error"></div>
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
                                        <input type="file" name="letter_integrity" id="letter_integrity">
                                        <div id="letter_integrity_error"></div>
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
                                        <input type="file" name="letter_bankruptcy" id="letter_bankruptcy">
                                        <div id="letter_bankruptcy_error"></div>
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
                                                id="audit_start_raw" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="audit_start" />
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
                                                id="audit_end_raw" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <input type="hidden" id="audit_end" />
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
                                        <input type="file" name="notice_agm_egm" id="notice_agm_egm">
                                        <div id="notice_agm_egm_error"></div>
                                    </div>
                                </div>
                            </form>

                            <!-- OCR -->
                            @if (Auth::user()->hasAccessOCR())
                                <form id="upload_notice_agm_egm_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="notice_agm_egm_ocr" id="notice_agm_egm_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="notice_agm_egm_ocr_error"></div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form id="upload_minutes_agm_egm" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadMinutesAgmEgm') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_minutes_agm_egm') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="minutes_agm_egm" id="minutes_agm_egm">
                                        <div id="minutes_agm_egm_error"></div>
                                    </div>
                                </div>
                            </form>

                            <!-- OCR -->
                            @if (Auth::user()->hasAccessOCR())
                                <form id="upload_minutes_agm_egm_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="minutes_agm_egm_ocr" id="minutes_agm_egm_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="minutes_agm_egm_ocr_error"></div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form id="upload_minutes_ajk" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadMinutesAjk') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_minutes_ajk') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="minutes_ajk" id="minutes_ajk">
                                        <div id="minutes_ajk_error"></div>
                                    </div>
                                </div>
                            </form>

                            <!-- OCR -->
                            @if (Auth::user()->hasAccessOCR())
                                <form id="upload_minutes_ajk_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="minutes_ajk_ocr" id="minutes_ajk_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="minutes_ajk_ocr_error"></div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form id="upload_eligible_vote" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadEligibleVote') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_eligible_vote') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="eligible_vote" id="eligible_vote">
                                        <div id="eligible_vote_error"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_attend_meeting" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadAttendMeeting') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_attend_meeting') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="attend_meeting" id="attend_meeting">
                                        <div id="attend_meeting_error"></div>
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
                                        <input type="file" name="proksi" id="proksi">
                                        <div id="proksi_error"></div>
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
                                        <input type="file" name="ajk_info" id="ajk_info">
                                        <div id="ajk_info_error"></div>
                                    </div>
                                </div>
                            </form>

                            <!-- OCR -->
                            @if (Auth::user()->hasAccessOCR())
                                <form id="upload_ajk_info_ocr" enctype="multipart/form-data" method="post"
                                    action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="ajk_info_ocr" id="ajk_info_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="ajk_info_ocr_error"></div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form id="upload_ic" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadIc') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_ic') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="ic" id="ic">
                                        <div id="ic_error"></div>
                                    </div>
                                </div>
                            </form>

                            <?php if ((Auth::user()->getAdmin() || Auth::user()->isCOB()) || Auth::user()->isLPHS()) { ?>
                            <form id="upload_purchase_aggrement" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadPurchaseAggrement') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_purchase_aggrement') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="purchase_aggrement" id="purchase_aggrement">
                                        <div id="purchase_aggrement_error"></div>
                                    </div>
                                </div>
                            </form>

                            <form id="upload_strata_title" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadStrataTitle') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_strata_title') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="strata_title" id="strata_title">
                                        <div id="strata_title_error"></div>
                                    </div>
                                </div>
                            </form>
                            <?php } else { ?>
                            <?php if (Auth::user()->isJMB()) { ?>
                            <form id="upload_purchase_aggrement" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadPurchaseAggrement') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_purchase_aggrement') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="purchase_aggrement" id="purchase_aggrement">
                                        <div id="purchase_aggrement_error"></div>
                                    </div>
                                </div>
                            </form>
                            <?php } else if (Auth::user()->isMC()) { ?>
                            <form id="upload_strata_title" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadStrataTitle') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_strata_title') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="strata_title" id="strata_title">
                                        <div id="strata_title_error"></div>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>
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
                                        <input type="file" name="maintenance_statement" id="maintenance_statement">
                                        <div id="maintenance_statement_error"></div>
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
                                        <input type="file" name="integrity_pledge" id="integrity_pledge">
                                        <div id="integrity_pledge_error"></div>
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
                                        <input type="file" name="report_audited_financial"
                                            id="report_audited_financial">
                                        <div id="report_audited_financial_error"></div>
                                    </div>
                                </div>
                            </form>

                            <!-- OCR -->
                            @if (Auth::user()->hasAccessOCR())
                                <form id="upload_report_audited_financial_ocr" enctype="multipart/form-data"
                                    method="post" action="{{ url('uploadOcr') }}" autocomplete="off">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label">&nbsp;</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label">OCR</label>
                                            <br />
                                            <input type="file" name="report_audited_financial_ocr"
                                                id="report_audited_financial_ocr">
                                            <div>
                                                <small>* Accept TXT only. Maximum size: 10MB.</small>
                                            </div>
                                            <div id="report_audited_financial_ocr_error"></div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form id="upload_house_rules" enctype="multipart/form-data" method="post"
                                action="{{ url('uploadHouseRules') }}" autocomplete="off">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.upload_house_rules') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="house_rules" id="house_rules">
                                        <div id="house_rules_error"></div>
                                    </div>
                                </div>
                            </form>

                            <!-- OCR -->
                            @if (Auth::user()->hasAccessOCR())
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
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <form>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="form-control-label">
                                            {{ trans('app.forms.remarks') }}
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.remarks') }}" id="remarks" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <?php if ($insert_permission == 1) { ?>
                                    <input type="hidden" id="agm_file_url" />
                                    <input type="hidden" id="egm_file_url" />
                                    <input type="hidden" id="minutes_meeting_file_url" />
                                    <input type="hidden" id="jmc_file_url" />
                                    <input type="hidden" id="ic_file_url" />
                                    <input type="hidden" id="attendance_file_url" />
                                    <input type="hidden" id="audited_financial_file_url" />
                                    <input type="hidden" id="audit_report_file_url" />
                                    <input type="hidden" id="letter_integrity_url" />
                                    <input type="hidden" id="letter_bankruptcy_url" />
                                    <input type="hidden" id="notice_agm_egm_url" />
                                    <input type="hidden" id="minutes_agm_egm_url" />
                                    <input type="hidden" id="minutes_ajk_url" />
                                    <input type="hidden" id="eligible_vote_url" />
                                    <input type="hidden" id="attend_meeting_url" />
                                    <input type="hidden" id="proksi_url" />
                                    <input type="hidden" id="ajk_info_url" />
                                    <input type="hidden" id="ic_url" />
                                    <input type="hidden" id="purchase_aggrement_url" />
                                    <input type="hidden" id="strata_title_url" />
                                    <input type="hidden" id="maintenance_statement_url" />
                                    <input type="hidden" id="integrity_pledge_url" />
                                    <input type="hidden" id="report_audited_financial_url" />
                                    <input type="hidden" id="house_rules_url" />
                                    {{-- OCR --}}
                                    <input type="hidden" id="notice_agm_egm_ocr_url" />
                                    <input type="hidden" id="minutes_agm_egm_ocr_url" />
                                    <input type="hidden" id="minutes_ajk_ocr_url" />
                                    <input type="hidden" id="ajk_info_ocr_url" />
                                    <input type="hidden" id="report_audited_financial_ocr_url" />
                                    <input type="hidden" id="house_rules_ocr_url" />

                                    <button type="button" class="btn btn-own" id="submit_button"
                                        onclick="addMinutes()">
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
    <script src="{{ asset('assets/common/js/add_monitoring.js') }}"></script>

    <script>
        function typeDetails() {
            $("#upload_strata_title").hide();
            $("#upload_purchase_aggrement").hide();

            var type = $("#type").val();

            if (type == 'mc') {
                $("#upload_strata_title").show();
                $("#upload_purchase_aggrement").hide();
            } else if (type == 'jmb') {
                $("#upload_strata_title").hide();
                $("#upload_purchase_aggrement").show();
            }
        }

        function addMinutes() {
            $("#loading").css("display", "inline-block");
            $("#submit_button").attr("disabled", "disabled");
            $("#cancel_button").attr("disabled", "disabled");
            $("#type_error").css("display", "none");
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
                type = $("#type").val(),
                remarks = $("#remarks").val(),
                // OCR
                notice_agm_egm_ocr_url = $("#notice_agm_egm_ocr_url").val(),
                minutes_agm_egm_ocr_url = $("#minutes_agm_egm_ocr_url").val(),
                minutes_ajk_ocr_url = $("#minutes_ajk_ocr_url").val(),
                ajk_info_ocr_url = $("#ajk_info_ocr_url").val(),
                report_audited_financial_ocr_url = $("#report_audited_financial_ocr_url").val(),
                house_rules_ocr_url = $("#house_rules_ocr_url").val();

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

            if (type.trim() == "") {
                $("#type_error").html(
                    '<span style="color:red;font-style:italic;font-size:13px;">{{ trans('app.errors.select', ['attribute' => 'Type']) }}</span>'
                );
                $("#type").focus();
                $("#type_error").css("display", "block");
                error = 1;
            }

            if (error == 0) {
                $.ajax({
                    url: "{{ URL::action('AgmController@submitAddMinutes') }}",
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
                        type: type,
                        remarks: remarks,
                        // OCR
                        notice_agm_egm_ocr_url: notice_agm_egm_ocr_url,
                        minutes_agm_egm_ocr_url: minutes_agm_egm_ocr_url,
                        minutes_ajk_ocr_url: minutes_ajk_ocr_url,
                        ajk_info_ocr_url: ajk_info_ocr_url,
                        report_audited_financial_ocr_url: report_audited_financial_ocr_url,
                        house_rules_ocr_url: house_rules_ocr_url
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
    </script>
    <!-- End Page Scripts-->
@endsection
