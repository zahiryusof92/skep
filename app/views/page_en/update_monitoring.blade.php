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
                    <h6>{{ trans('app.forms.file_no') }}: {{$file->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file', ['files' => $file])
                        
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="monitoring" role="tabpanel">
                                <form id="monitoring">
                                    
                                    <section class="panel panel-pad">
                                        <div class="row padding-vertical-20">
                                            <div class="col-lg-12">
                                                <h4>{{ trans('app.forms.detail') }}</h4>
                                                <h6>{{ trans('app.forms.delivery_document_of_development_area') }}</h6>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="form-control-label">{{ trans('app.forms.pre_calculate_plan') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="precalculate_plan" name="precalculate_plan" value="1" {{($monitoring->pre_calculate == 1 ? " checked" : "")}}>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="precalculate_plan" name="precalculate_plan" value="0" {{($monitoring->pre_calculate == 0 ? " checked" : "")}}>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="form-control-label">{{ trans('app.forms.buyer_registration') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="buyer_registration" name="buyer_registration" value="1" {{($monitoring->buyer_registration == 1 ? " checked" : "")}}>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="buyer_registration" name="buyer_registration" value="0" {{($monitoring->buyer_registration == 0 ? " checked" : "")}}>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="form-control-label">{{ trans('app.forms.certificate_series_number') }}</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="certificate_series_no" value="{{$monitoring->certificate_no}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    
                                    @if (Auth::user()->getAdmin() || Auth::user()->isJMB())
                                    <hr/>
                                    <section class="panel panel-pad">
                                        <div class="row padding-vertical-20">
                                            <div class="col-lg-12">
                                                <h6>{{ trans('app.forms.delivery_document_of_each_meeting_by_jmb') }}</h6>
                                                <div class="table-responsive">
                                                    <?php if ($update_permission == 1) { ?>
                                                        <button type="button" class="btn btn-own pull-right margin-bottom-25" onclick="addAGMDetails('jmb')">
                                                            {{ trans('app.forms.add') }}
                                                        </button>
                                                        <br/><br/>
                                                    <?php } ?>
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <label class="form-control-label">{{ trans('app.forms.financial_report_start_month') }}</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select class="form-control select2" id="commercial_sinking_fund_option">
                                                                <option value="0" selected="">{{ trans('app.forms.all') }}</option>
                                                                <option value="1">{{ trans('app.forms.january') }}</option>
                                                                <option value="2">{{ trans('app.forms.february') }}</option>
                                                                <option value="3">{{ trans('app.forms.march') }}</option>
                                                                <option value="4">{{ trans('app.forms.april') }}</option>
                                                                <option value="5">{{ trans('app.forms.may') }}</option>
                                                                <option value="6">{{ trans('app.forms.june') }}</option>
                                                                <option value="7">{{ trans('app.forms.july') }}</option>
                                                                <option value="8">{{ trans('app.forms.august') }}</option>
                                                                <option value="9">{{ trans('app.forms.september') }}</option>
                                                                <option value="10">{{ trans('app.forms.october') }}</option>
                                                                <option value="11">{{ trans('app.forms.november') }}</option>
                                                                <option value="12">{{ trans('app.forms.december') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <table class="table table-hover nowrap table-own table-striped" id="agm_report_list" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:10%;text-align: center !important;">{{ trans('app.forms.agm_date') }}</th>
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
                                    @endif
                                    
                                    @if (Auth::user()->getAdmin() || Auth::user()->isMC())
                                    <hr/>
                                    <section class="panel panel-pad">
                                        <div class="row padding-vertical-20">
                                            <div class="col-lg-12">
                                                <h6>{{ trans('app.forms.delivery_document_of_each_meeting_by_mc') }}</h6>
                                                <div class="table-responsive">
                                                    <?php if ($update_permission == 1) { ?>
                                                        <button type="button" class="btn btn-own pull-right margin-bottom-25" onclick="addAGMDetails('mc')">
                                                            {{ trans('app.forms.add') }}
                                                        </button>
                                                        <br/><br/>
                                                    <?php } ?>
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <label class="form-control-label">{{ trans('app.forms.financial_report_start_month') }}</label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select class="form-control select2" id="commercial_sinking_fund_option">
                                                                <option value="0" selected="">{{ trans('app.forms.all') }}</option>
                                                                <option value="1">{{ trans('app.forms.january') }}</option>
                                                                <option value="2">{{ trans('app.forms.february') }}</option>
                                                                <option value="3">{{ trans('app.forms.march') }}</option>
                                                                <option value="4">{{ trans('app.forms.april') }}</option>
                                                                <option value="5">{{ trans('app.forms.may') }}</option>
                                                                <option value="6">{{ trans('app.forms.june') }}</option>
                                                                <option value="7">{{ trans('app.forms.july') }}</option>
                                                                <option value="8">{{ trans('app.forms.august') }}</option>
                                                                <option value="9">{{ trans('app.forms.september') }}</option>
                                                                <option value="10">{{ trans('app.forms.october') }}</option>
                                                                <option value="11">{{ trans('app.forms.november') }}</option>
                                                                <option value="12">{{ trans('app.forms.december') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <table class="table table-hover nowrap table-own table-striped" id="agm_by_mc_report_list" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:10%;text-align: center !important;">{{ trans('app.forms.agm_date') }}</th>
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
                                    @endif
                                    
                                    <hr/>
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
                                                                <th style="width:30%;text-align: center !important;">{{ trans('app.forms.designation') }}</th>
                                                                <th style="width:15%;">{{ trans('app.forms.name') }}</th>
                                                                <th style="width:15%;">{{ trans('app.forms.email') }}</th>
                                                                <th style="width:20%;">{{ trans('app.forms.phone_number') }}</th>
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
                                    <hr/>
                                    
                                    <section class="panel panel-pad">
                                        <div class="form-group row padding-vertical-20">
                                            <div class="col-md-3">
                                                <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                            </div>
                                            <div class="col-md-4">
                                                <textarea class="form-control" rows="3" id="monitoring_remarks">{{$monitoring->remarks}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <?php if ($update_permission == 1) { ?>
                                                <button type="button" class="btn btn-own" id="submit_button" onclick="updateMonitoring()">{{ trans('app.forms.submit') }}</button>
                                            <?php } ?>

                                            @if ($file->is_active != 2)
                                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                            @else
                                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileListBeforeVP')}}'">{{ trans('app.forms.cancel') }}</button>
                                            @endif
                                        </div>
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<div class="modal fade modal-size-large" id="add_agm_details" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('app.buttons.add_agm_details') }}</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.agm_date') }}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="input-group datepicker-only-init">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date_raw"/>
                                <span class="input-group-addon">
                                    <i class="icmn-calendar"></i>
                                </span>
                            </label>
                            <input type="hidden" id="agm_date"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.annual_general_meeting') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="agm" name="agm" value="1"> {{ trans("app.forms.yes") }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="agm" name="agm" value="0"> {{ trans("app.forms.no") }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.extra_general_meeting') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="egm" name="egm" value="1"> {{ trans("app.forms.yes") }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="egm" name="egm" value="0"> {{ trans("app.forms.no") }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.meeting_minutes') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="minit_meeting" name="minit_meeting" value="1"> {{ trans("app.forms.yes") }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="minit_meeting" name="minit_meeting" value="0"> {{ trans("app.forms.no") }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.jmc_spa_copy') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="jmc_copy" name="jmc_copy" value="1"> {{ trans("app.forms.yes") }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="jmc_copy" name="jmc_copy" value="0"> {{ trans("app.forms.no") }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.identity_card_list') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="ic_list" name="ic_list" value="1"> {{ trans("app.forms.yes") }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="ic_list" name="ic_list" value="0"> {{ trans("app.forms.no") }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.attendance_list') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="attendance_list" name="attendance_list" value="1"> {{ trans("app.forms.yes") }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="attendance_list" name="attendance_list" value="0"> {{ trans("app.forms.no") }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.audited_financial_report') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="audited_financial_report" name="audited_financial_report" value="1"> {{ trans("app.forms.yes") }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="audited_financial_report" name="audited_financial_report" value="0"> {{ trans("app.forms.no") }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.financial_audit_report') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.financial_audit_report') }}" id="audit_report"/>
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
                                <input type="text" class="form-control" placeholder="Start Date" id="audit_start_raw"/>
                                <span class="input-group-addon">
                                    <i class="icmn-calendar"></i>
                                </span>
                            </label>
                            <input type="hidden" id="audit_start"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.financial_audit_end_date') }}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="input-group datepicker-only-init">
                                <input type="text" class="form-control" placeholder="End Date" id="audit_end_raw"/>
                                <span class="input-group-addon">
                                    <i class="icmn-calendar"></i>
                                </span>
                            </label>
                            <input type="hidden" id="audit_end"/>
                        </div>
                    </div>
                </form>
                
                <hr/>
                
                <form id="upload_notice_agm_egm" enctype="multipart/form-data" method="post" action="{{ url('uploadNoticeAgmEgm') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_notice_agm_egm') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_notice_agm_egm" class="btn btn-xs btn-danger" onclick="clearNoticeAgmEgm()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="notice_agm_egm" id="notice_agm_egm">
                            <div id="validation-notice_agm_egm"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_minutes_agm_egm" enctype="multipart/form-data" method="post" action="{{ url('uploadMinutesAgmEgm') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_minutes_agm_egm') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_minutes_agm_egm" class="btn btn-xs btn-danger" onclick="clearMinutesAgmEgm()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="minutes_agm_egm" id="minutes_agm_egm">
                            <div id="validation-minutes_agm_egm"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_minutes_ajk" enctype="multipart/form-data" method="post" action="{{ url('uploadMinutesAjk') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_minutes_ajk') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_minutes_ajk" class="btn btn-xs btn-danger" onclick="clearMinutesAjk()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="minutes_ajk" id="minutes_ajk">
                            <div id="validation-minutes_ajk"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_eligible_vote" enctype="multipart/form-data" method="post" action="{{ url('uploadEligibleVote') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_eligible_vote') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_eligible_vote" class="btn btn-xs btn-danger" onclick="clearEligbleVote()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="eligible_vote" id="eligible_vote">
                            <div id="validation-eligible_vote"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_attend_meeting" enctype="multipart/form-data" method="post" action="{{ url('uploadAttendMeeting') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_attend_meeting') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_attend_meeting" class="btn btn-xs btn-danger" onclick="clearAttendMeeting()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="attend_meeting" id="attend_meeting">
                            <div id="validation-attend_meeting"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_proksi" enctype="multipart/form-data" method="post" action="{{ url('uploadProksi') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_proksi') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_proksi" class="btn btn-xs btn-danger" onclick="clearProksi()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="proksi" id="proksi">
                            <div id="validation-proksi"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_ajk_info" enctype="multipart/form-data" method="post" action="{{ url('uploadAjkInfo') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_ajk_info') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_ajk_info" class="btn btn-xs btn-danger" onclick="clearAjkInfo()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="ajk_info" id="ajk_info">
                            <div id="validation-ajk_info"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_ic" enctype="multipart/form-data" method="post" action="{{ url('uploadIc') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_ic') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_ic" class="btn btn-xs btn-danger" onclick="clearIc()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="ic" id="ic">
                            <div id="validation-ic"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_purchase_aggrement" enctype="multipart/form-data" method="post" action="{{ url('uploadPurchaseAggrement') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_purchase_aggrement') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_purchase_aggrement" class="btn btn-xs btn-danger" onclick="clearPurchaseAggrement()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="purchase_aggrement" id="purchase_aggrement">
                            <div id="validation-purchase_aggrement"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_strata_title" enctype="multipart/form-data" method="post" action="{{ url('uploadStrataTitle') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_strata_title') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_strata_title" class="btn btn-xs btn-danger" onclick="clearStrataTitle()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="strata_title" id="strata_title">
                            <div id="validation-strata_title"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_maintenance_statement" enctype="multipart/form-data" method="post" action="{{ url('uploadMaintenanceStatement') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_maintenance_statement') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_maintenance_statement" class="btn btn-xs btn-danger" onclick="clearMaintenanceStatement()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="maintenance_statement" id="maintenance_statement">
                            <div id="validation-maintenance_statement"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_integrity_pledge" enctype="multipart/form-data" method="post" action="{{ url('uploadIntegrityPledge') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_integrity_pledge') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_integrity_pledge" class="btn btn-xs btn-danger" onclick="clearIntegrityPledge()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="integrity_pledge" id="integrity_pledge">
                            <div id="validation-integrity_pledge"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_report_audited_financial" enctype="multipart/form-data" method="post" action="{{ url('uploadReportAuditedFinancial') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_report_audited_financial') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_report_audited_financial" class="btn btn-xs btn-danger" onclick="clearReportAuditedFinancial()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="report_audited_financial" id="report_audited_financial">
                            <div id="validation-report_audited_financial"></div>
                        </div>
                    </div>
                </form>
                
                <form id="upload_house_rules" enctype="multipart/form-data" method="post" action="{{ url('uploadHouseRules') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.upload_house_rules') }}</label>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clear_house_rules" class="btn btn-xs btn-danger" onclick="clearHouseRules()" style="display: none;"><i class="fa fa-times"></i></button>
                            &nbsp;
                            <input type="file" name="house_rules" id="house_rules">
                            <div id="validation-house_rules"></div>
                        </div>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
                <form>
                    <input type="hidden" id="audit_report_file_url"/>
                    <input type="hidden" id="letter_integrity_url"/>
                    <input type="hidden" id="letter_bankruptcy_url"/>
                    <input type="hidden" id="notice_agm_egm_url"/>
                    <input type="hidden" id="minutes_agm_egm_url"/>
                    <input type="hidden" id="minutes_ajk_url"/>
                    <input type="hidden" id="eligible_vote_url"/>
                    <input type="hidden" id="attend_meeting_url"/>
                    <input type="hidden" id="proksi_url"/>
                    <input type="hidden" id="ajk_info_url"/>
                    <input type="hidden" id="ic_url"/>
                    <input type="hidden" id="purchase_aggrement_url"/>
                    <input type="hidden" id="strata_title_url"/>
                    <input type="hidden" id="maintenance_statement_url"/>
                    <input type="hidden" id="integrity_pledge_url"/>
                    <input type="hidden" id="report_audited_financial_url"/>
                    <input type="hidden" id="house_rules_url"/>
                    <input type="hidden" id="type"/>
                    
                    <button type="button" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    <button type="button" class="btn btn-own" onclick="addAGMDetail()">
                        {{ trans('app.forms.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-size-large" id="edit_agm_details" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('app.buttons.edit_agm_details') }}</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.agm_date') }}</label>
                            </div>
                            <div class="col-md-4">
                                <label class="input-group datepicker-only-init">
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date_edit_raw"/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                                <input type="hidden" id="agm_date_edit"/>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="agmEdit"></div>
                <form>
                    <div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.financial_audit_start_date') }}</label>
                            </div>
                            <div class="col-md-4">
                                <label class="input-group datepicker-only-init">
                                    <input type="text" class="form-control" placeholder="Start Date" id="audit_start_edit_raw"/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                                <input type="hidden" id="audit_start_edit"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-control-label">{{ trans('app.forms.financial_audit_end_date') }}</label>
                            </div>
                            <div class="col-md-4">
                                <label class="input-group datepicker-only-init">
                                    <input type="text" class="form-control" placeholder="End Date" id="audit_end_edit_raw"/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                                <input type="hidden" id="audit_end_edit"/>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="agmEditNew"></div>
            </div>
            <div class="modal-footer">
                <form>
                    <input type="hidden" id="agm_id_edit"/>
                    <input type="hidden" id="audit_report_file_url_edit"/>
                    <input type="hidden" id="letter_integrity_url_edit"/>
                    <input type="hidden" id="letter_bankruptcy_url_edit"/>
                    <input type="hidden" id="notice_agm_egm_url_edit"/>
                    <input type="hidden" id="minutes_agm_egm_url_edit"/>
                    <input type="hidden" id="minutes_ajk_url_edit"/>
                    <input type="hidden" id="eligible_vote_url_edit"/>
                    <input type="hidden" id="attend_meeting_url_edit"/>
                    <input type="hidden" id="proksi_url_edit"/>
                    <input type="hidden" id="ajk_info_url_edit"/>
                    <input type="hidden" id="ic_url_edit"/>
                    <input type="hidden" id="purchase_aggrement_url_edit"/>
                    <input type="hidden" id="strata_title_url_edit"/>
                    <input type="hidden" id="maintenance_statement_url_edit"/>
                    <input type="hidden" id="integrity_pledge_url_edit"/>
                    <input type="hidden" id="report_audited_financial_url_edit"/>
                    <input type="hidden" id="house_rules_url_edit"/>
                    
                    <button type="button" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    <button type="button" class="btn btn-own" onclick="editAGMDetail()">
                        {{ trans('app.forms.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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

<!-- Page Scripts -->
<script src="{{ asset('assets/common/js/update_monitoring.js') }}"></script>
 
<script>
    var changes = false;
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });

    function getAGMDetails(id) {
        $.ajax({
            url: "{{ URL::action('AdminController@getAGMDetails') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                $("#agmEdit").html(data.result);
                $("#agmEditNew").html(data.result_new);
                $("#edit_agm_details").modal("show");
            }
        });
    }

    $(function () {
        $('#agm_date_raw').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#agm_date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#agm_date_edit_raw').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#agm_date_edit").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#audit_start_raw').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#audit_start").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#audit_start_edit_raw').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#audit_start_edit").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#audit_end_raw').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#audit_end").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#audit_end_edit_raw').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#audit_end_edit").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });
    
    $(document).ready(function () {
        $('#agm_report_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAGM', \Helper\Helper::encode($file->id))}}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[0, "asc"]],
            "responsive": false,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
        
        $('#agm_by_mc_report_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAGMByMC', \Helper\Helper::encode($file->id))}}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[0, "asc"]],
            "responsive": false,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
        
        $('#ajk_details_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAJK', \Helper\Helper::encode($file->id))}}",
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
    
    $(document).on("click", '.edit_agm', function (e) {
        var agm_id = $(this).data('agm_id');
        var agm_date = $(this).data('agm_date');
        var agm_date_raw = $(this).data('agm_date_raw');
        var audit_start_date = $(this).data('audit_start_date');
        var audit_start_date_raw = $(this).data('audit_start_date_raw');
        var audit_end_date = $(this).data('audit_end_date');
        var audit_end_date_raw = $(this).data('audit_end_date_raw');
        var audit_report_file_url = $(this).data('audit_report_file_url');
        var letter_integrity_url = $(this).data('letter_integrity_url');
        var letter_bankruptcy_url = $(this).data('letter_bankruptcy_url');

        $("#agm_id_edit").val(agm_id);
        if (agm_date == "") {
            $("#agm_date_edit").val("");
        } else {
            $("#agm_date_edit").val(agm_date);
        }
        if (agm_date_raw == "") {
            $("#agm_date_edit_raw").val("");
        } else {
            $("#agm_date_edit_raw").val(agm_date_raw);
        }
        if (audit_start_date == "") {
            $("#audit_start_edit").val("");
        } else {
            $("#audit_start_edit").val(audit_start_date);
        }
        if (audit_start_date_raw == "") {
            $("#audit_start_edit_raw").val("");
        } else {
            $("#audit_start_edit_raw").val(audit_start_date_raw);
        }
        if (audit_end_date == "") {
            $("#audit_end_edit").val("");
        } else {
            $("#audit_end_edit").val(audit_end_date);
        }
        if (audit_end_date_raw == "") {
            $("#audit_end_edit_raw").val("");
        } else {
            $("#audit_end_edit_raw").val(audit_end_date_raw);
        }
        $("#audit_report_file_url_edit").val(audit_report_file_url);
        $("#letter_integrity_url_edit").val(letter_integrity_url);
        $("#letter_bankruptcy_url_edit").val(letter_bankruptcy_url);
    });

    $(document).on("click", '.edit_ajk', function (e) {
        var ajk_id = $(this).data('ajk_id');
        var designation = $(this).data('designation');
        var name = $(this).data('name');
        var email = $(this).data('email');
        var phone_no = $(this).data('phone_no');
        var start_year = $(this).data('start_year');
        var end_year = $(this).data('end_year');

        $("#ajk_id_edit").val(ajk_id);
        $("#ajk_designation_edit").val(designation);
        $("#ajk_name_edit").val(name);
        $("#ajk_email_edit").val(email);
        $("#ajk_phone_no_edit").val(phone_no);
        $("#ajk_start_year_edit").val(start_year);
        $("#ajk_end_year_edit").val(end_year);
    });

    function addAGMDetails(type) {
        $("#type").val(type);
        
        if (type == 'mc') {
            $("#upload_strata_title").show();
            $("#upload_purchase_aggrement").hide();
        }
        if (type == 'jmb') {
            $("#upload_strata_title").hide();
            $("#upload_purchase_aggrement").show();
        }
        $("#add_agm_details").modal("show");
    }
    function editAGMDetails(type) {        
        if (type == 'mc') {
            $("#upload_strata_title").show();
            $("#upload_purchase_aggrement").hide();
        }
        if (type == 'jmb') {
            $("#upload_strata_title").hide();
            $("#upload_purchase_aggrement").show();
        }
        $("#edit_agm_details").modal("show");
    }

    function addAJKDetails() {
        $("#add_ajk_details").modal("show");
    }
    function editAJKDetails() {
        $("#edit_ajk_details").modal("show");
    }

    function updateMonitoring() {
        changes = false;
        $("#loading").css("display", "inline-block");

        var precalculate_plan;
        var buyer_registration;

        if (document.getElementById('precalculate_plan').checked) {
            precalculate_plan = 1;
        } else {
            precalculate_plan = 0;
        }
        if (document.getElementById('buyer_registration').checked) {
            buyer_registration = 1;
        } else {
            buyer_registration = 0;
        }

        var certificate_series_no = $("#certificate_series_no").val(),
                monitoring_remarks = $("#monitoring_remarks").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateMonitoring') }}",
                type: "POST",
                data: {
                    precalculate_plan: precalculate_plan,
                    buyer_registration: buyer_registration,
                    certificate_series_no: certificate_series_no,
                    monitoring_remarks: monitoring_remarks,
                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        window.location = "{{URL::action('AdminController@others', \Helper\Helper::encode($file->id))}}";
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function addAGMDetail() {
        changes = false;
        $("#loading").css("display", "inline-block");

        var agm,
                egm,
                minit_meeting,
                jmc_copy,
                ic_list,
                attendance_list,
                audited_financial_report;

        if (document.getElementById('agm').checked) {
            agm = $("#agm").val();
        } else {
            agm = 0;
        }
        if (document.getElementById('egm').checked) {
            egm = $("#egm").val();
        } else {
            egm = 0;
        }
        if (document.getElementById('minit_meeting').checked) {
            minit_meeting = $("#minit_meeting").val();
        } else {
            minit_meeting = 0;
        }
        if (document.getElementById('jmc_copy').checked) {
            jmc_copy = $("#jmc_copy").val();
        } else {
            jmc_copy = 0;
        }
        if (document.getElementById('ic_list').checked) {
            ic_list = $("#ic_list").val();
        } else {
            ic_list = 0;
        }
        if (document.getElementById('attendance_list').checked) {
            attendance_list = $("#attendance_list").val();
        } else {
            attendance_list = 0;
        }
        if (document.getElementById('audited_financial_report').checked) {
            audited_financial_report = $("#audited_financial_report").val();
        } else {
            audited_financial_report = 0;
        }

        var agm_date = $("#agm_date").val(),
                audit_report = $("#audit_report").val(),
                audit_start = $("#audit_start").val(),
                audit_end = $("#audit_end").val(),
                audit_report_file_url = $("#audit_report_file_url").val(),
                letter_integrity_url = $("#letter_integrity_url").val(),
                letter_bankruptcy_url = $("#letter_bankruptcy_url").val(),
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
                type = $("#type").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@addAGMDetails') }}",
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
                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#add_agm_details').modal('hide');
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

    function editAGMDetail() {
        changes = false;
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
                letter_bankruptcy_url = $("#letter_bankruptcy_url_edit").val(),
                notice_agm_egm_url = $("#notice_agm_egm_url_edit").val(),
                minutes_agm_egm_url = $("#minutes_agm_egm_url_edit").val(),
                minutes_ajk_url = $("#minutes_ajk_url_edit").val(),
                eligible_vote_url = $("#eligible_vote_url_edit").val(),
                attend_meeting_url = $("#attend_meeting_url_edit").val(),
                proksi_url = $("#proksi_url_edit").val(),
                ajk_info_url = $("#ajk_info_url_edit").val(),
                ic_url = $("#ic_url_edit").val(),
                purchase_aggrement_url = $("#purchase_aggrement_url_edit").val(),
                strata_title_url = $("#strata_title_url_edit").val(),
                maintenance_statement_url = $("#maintenance_statement_url_edit").val(),
                integrity_pledge_url = $("#integrity_pledge_url_edit").val(),
                report_audited_financial_url = $("#report_audited_financial_url_edit").val(),
                house_rules_url = $("#house_rules_url_edit").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@editAGMDetails') }}",
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

    function addAJKDetail() {
        changes = false;
        $("#loading").css("display", "inline-block");

        var ajk_designation = $("#ajk_designation").val(),
                ajk_name = $("#ajk_name").val(),
                ajk_email = $("#ajk_email").val(),
                ajk_phone_no = $("#ajk_phone_no").val(),
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
                url: "{{ URL::action('AdminController@deleteAGMDetails') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteNoticeAgmEgm') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteMinutesAgmEgm') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteMinutesAjk') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteEligibleVote') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteAttendMeeting') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteProksi') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteAjkInfo') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteIc') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deletePurchaseAggrement') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteStrataTitle') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteMaintenanceStatement') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteIntegrityPledge') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteReportAuditedFinancial') }}",
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
        }, function () {
            $.ajax({
                url: "{{ URL::action('AdminController@deleteHouseRules') }}",
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
