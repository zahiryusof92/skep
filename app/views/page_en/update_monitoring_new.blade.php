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
            
            @include('alert.bootbox')

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
                                    
                                    @if (Auth::user()->getAdmin() || Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper())
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
                                                                <th style="width:10%;">{{ trans('app.forms.agm_type') }}</th>
                                                                <th style="width:10%;">{{ trans('app.forms.agm_date') }}</th>
                                                                <th style="width:55%;">{{ trans('app.forms.description') }}</th>
                                                                <th style="width:5%;"></th>
                                                                <th style="width:10%;">{{ trans('app.forms.recent_update') }}</th>
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
                                                                <th style="width:10%;">{{ trans('app.forms.agm_type') }}</th>
                                                                <th style="width:10%;">{{ trans('app.forms.agm_date') }}</th>
                                                                <th style="width:60%;">{{ trans('app.forms.description') }}</th>
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
                                                <label class="form-control-label">{{ trans('app.forms.upcoming_agm_date') }}</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" value="{{ ($file->latestAgmDate ? (!empty($file->latestAgmDate->agm_date) && $file->latestAgmDate->agm_date != '0000-00-00' ? date('Y-m-d', strtotime($file->latestAgmDate->agm_date . ' + 1 year')) : '') : '') }}" readonly />
                                            </div>
                                        </div>
                                        <div class="form-group row">
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

<div class="modal fade modal-size-large" id="agm_minute_details" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('app.buttons.add_agm_details') }}</h4>
            </div>
            <form id="agm-minute-form" class="form-horizontal" method="POST" action="{{ route('agm-minute.store') }}">
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6" id="status-message"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.agm_type') }}</label>
                        </div>
                        <div class="col-md-6">
                            <select id="agm_type" name="agm_type" class="form-control select2">
                                <option value="">{{ trans('app.forms.please_select') }}</option>   
                                <option value="agm" {{ Input::old('agm_type') == 'agm' ? 'selected' : '' }}>{{ strtoupper(trans('agm')) }}</option>
                                <option value="egm" {{ Input::old('agm_type') == 'egm' ? 'selected' : '' }}>{{ strtoupper(trans('egm')) }}</option>
                            </select>
                            @include('alert.feedback-ajax', ['field' => 'agm_type'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.agm_date') }}</label>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('agm_date') ? 'has-danger' : '' }}">
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker-only-init" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date" name="agm_date" value="{{ Input::old('agm_date') }}"/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                                @include('alert.feedback-ajax', ['field' => 'agm_date'])
                            </div>
                        </div>
                    </div>

                    <div id="form_container">
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('remarks') ? 'has-danger' : '' }}">
                                <textarea class="form-control" rows="4" placeholder="{{ trans('app.forms.remarks') }}" id="remarks" name="remarks">{{ Input::old('remarks') }}</textarea>
                                @include('alert.feedback', ['field' => 'remarks'])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="minute_id" name="minute_id"/>
                    <input type="hidden" id="type" name="type"/>
                    <input type="hidden" id="file_no" name="file_no" value="{{ \Helper\Helper::encode(Config::get('constant.module.cob.file.name'), $file->id) }}"/>
                    
                    <button type="button" id="modal_cancel_button" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    <button type="submit" id="modal_submit_button" class="btn btn-own">
                        {{ trans('app.forms.submit') }}
                    </button>
                </div>
            </form>
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

    $('body').on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        let formId = $(this).data('id');

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
            $('#' + formId).submit();
        });
    });
    
    $(document).ready(function () {
        $('#agm_report_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAGM', \Helper\Helper::encode($file->id))}}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[0, "asc"]],
            "responsive": false,
            "columns": [
                {data: 'agm_type', name: 'agm_type'},
                {data: 'agm_date', name: 'agm_date'},
                {data: 'description', name: 'description'},
                {data: 'check_status', name: 'description', orderable: false, searchable: false},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
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
            "columns": [
                {data: 'agm_type', name: 'agm_type'},
                {data: 'agm_date', name: 'agm_date'},
                {data: 'description', name: 'description'},
                {data: 'check_status', name: 'description', orderable: false, searchable: false},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
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
        $('#agm_date').datetimepicker({
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
            format: 'YYYY-MM-DD'
        });

        $('#agm_type').change(function() {
            getForm();
        });

        $("#agm-minute-form").submit(function (e) {
            e.preventDefault();
            changes = false;
            let formData = $(this).serializeArray();
            let route = "{{ route('agm-minute.store') }}";
            let method = "POST";
            if($('#minute_id').val() != '') {
                route = "{{ route('agm-minute.update', [':id']) }}";
                route = route.replace(':id', $('#minute_id').val());
                method = "PUT";
            }
            $.ajax({
                url: route,
                type: method,
                data: formData,
                beforeSend: function() {
                    $("#loading").css("display", "inline-block");
                    $("#modal_submit_button").attr("disabled", "disabled");
                    $("#modal_cancel_button").attr("disabled", "disabled");
                    $.each(formData, function (key, value) {
                        if(value['name'].includes('question')) {
                            $("#" + value['name'] + "_file_url_error").children("strong").text("");
                        } else {
                            $("#" + value['name'] + "_error").children("strong").text("");
                        }
                    });
                },
                success: function (res) {
                    if (res.success == true) {
                            $('#agm_minute_details').modal('hide');
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
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                $("#" + key + "_error").children("strong").text(value);
                            });
                        }
                        
                        if(res.message != "Validation Fail") {
                            $('#status-message').html("<span style='color:red;'>" + res.message + "</span>");
                        } else {
                            $('#status-message').html("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    }
                },
                complete: function() {
                    $("#loading").css("display", "none");
                    $("#modal_submit_button").removeAttr("disabled");
                    $("#modal_cancel_button").removeAttr("disabled");
                },
            });
        });
    });
    
    $(document).on("click", '.edit_agm', function (e) {
        var id = $(this).data('id');
        var type = $(this).data('type');
        var agm_type = $(this).data('agm_type');
        var agm_date = $(this).data('agm_date');
        var remarks = $(this).data('remarks');

        $("#agm_minute_details").modal("show");
        $("#minute_id").val(id);
        $("#type").val(type);
        $("#agm_type").val(agm_type).change();
        $("#agm_date").val(agm_date);
        $("#remarks").val(remarks).change();
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
        $("#minute_id").val("");
        $("#type").val(type);
        $("#agm_minute_details").modal("show");
        $("#agm_type").val('').change();
        $("#agm_date").val('');
        $("#remarks").val('').change();
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
                beforeSend: function() {
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
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
                },
                complete: function() {
                    $.unblockUI();
                },
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

    function getForm() {
        $.ajax({
            url: "{{ route('agm-minute.getForm') }}",
            type: "POST",
            data: {
                id: $('#minute_id').val(),
                type: $('#type').val(),
                agm_type: $('#agm_type').val(),
            },
            success: function (result) {
                if (result) {
                    $("#form_container").html('');
                    $('#form_container').html(result);
                } else {
                    $("#form_container").html('');
                }
            }
        });
    }
</script>
<!-- End Page Scripts-->

@stop
