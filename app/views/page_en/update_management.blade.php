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
                            <div class="tab-pane active" id="management" role="tabpanel">

                                <section class="panel panel-pad">
                                    <form id="management">
                                        @if (! $management->developer)
                                        <div class="row margin-top-20">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_developer" id="is_developer"/>
                                                <label><h4> {{ trans('app.forms.developer') }}</h4></label>
                                                <!-- developer Form -->
                                                <div id="developer_form" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.developer') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.developer') }}" id="developer_name" value="{{$house_scheme->name}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="developer_address1" value="{{$house_scheme->address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="developer_address2" value="{{$house_scheme->address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="developer_address3" value="{{$house_scheme->address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="developer_address4" value="{{$house_scheme->address4}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control" id="developer_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($house_scheme->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="developer_poscode" value="{{$house_scheme->poscode}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control" id="developer_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($house_scheme->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control" id="developer_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($house_scheme->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="developer_phone_no" value="{{$house_scheme->phone_no}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="developer_fax_no" value="{{$house_scheme->fax_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }}</label>
                                                                <textarea class="form-control" rows="3" id="developer_remarks">{{$house_scheme->remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="row padding-vertical-20">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_developer" id="is_developer" {{($management->is_developer == 1 ? " checked" : "")}}/>
                                                <label><h4> {{ trans('app.forms.developer') }}</h4></label>
                                                <!-- developer Form -->
                                                <div id="developer_form">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.developer') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.developer') }}" id="developer_name" value="{{$management->developer->name}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="developer_address1" value="{{$management->developer->address_1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="developer_address2"  value="{{$management->developer->address_2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="developer_address3"  value="{{$management->developer->address_3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="developer_address4"  value="{{$management->developer->address_4}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control" id="developer_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($management->developer->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="developer_poscode"  value="{{$management->developer->poscode}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control" id="developer_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($management->developer->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control" id="developer_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($management->developer->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="developer_phone_no" value="{{$management->developer->phone_no}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="developer_fax_no" value="{{$management->developer->fax_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }}</label>
                                                                <textarea class="form-control" rows="3" id="developer_remarks">{{$management->developer->remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <hr/>
                                        @if (! $management->jmb)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_jmb" id="is_jmb"/>
                                                <label><h4> {{ trans('app.forms.joint_management_body') }}</h4></label>
                                                <!-- jmb Form -->
                                                <div id="jmb_form" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.date_formed') }}</label>
                                                                <label class="input-group datepicker-only-init">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="jmb_date_formed_raw"/>
                                                                    <span class="input-group-addon">
                                                                        <i class="icmn-calendar"></i>
                                                                    </span>
                                                                </label>
                                                                <input type="hidden" id="jmb_date_formed"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.certificate_series_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="jmb_certificate_no">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="jmb_name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="jmb_address1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="jmb_address2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="jmb_address3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="jmb_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}">{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="jmb_poscode">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="jmb_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}">{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="jmb_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}">{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="jmb_phone_no">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="jmb_fax_no">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="jmb_email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_jmb" id="is_jmb" {{($management->is_jmb == 1 ? " checked" : "")}}/>
                                                <label><h4> {{ trans('app.forms.joint_management_body') }}</h4></label>
                                                <!-- jmb Form -->
                                                <div id="jmb_form">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.date_formed') }}</label>
                                                                <label class="input-group datepicker-only-init">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="jmb_date_formed_raw" value="{{ ($management->jmb->date_formed != '0000-00-00' ? date('d-m-Y', strtotime($management->jmb->date_formed)) : '') }}"/>
                                                                    <span class="input-group-addon">
                                                                        <i class="icmn-calendar"></i>
                                                                    </span>
                                                                </label>
                                                                <input type="hidden" id="jmb_date_formed" value="{{ $management->jmb->date_formed }}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.certificate_series_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="jmb_certificate_no" value="{{$management->jmb->certificate_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="jmb_name" value="{{$management->jmb->name}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="jmb_address1" value="{{$management->jmb->address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="jmb_address2" value="{{$management->jmb->address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="jmb_address3" value="{{$management->jmb->address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="jmb_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($management->jmb->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="jmb_poscode" value="{{$management->jmb->poscode}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="jmb_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($management->jmb->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="jmb_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($management->jmb->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="jmb_phone_no" value="{{$management->jmb->phone_no}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="jmb_fax_no" value="{{$management->jmb->fax_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="jmb_email" value="{{$management->jmb->email}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <hr/>
                                        @if (! $management->mc)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_mc" id="is_mc"/>
                                                <label><h4> {{ trans('app.forms.management_corporation') }}</h4></label>
                                                <!-- jmb Form -->
                                                <div id="mc_form" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.date_formed') }}</label>
                                                                <label class="input-group datepicker-only-init">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="mc_date_formed_raw"/>
                                                                    <span class="input-group-addon">
                                                                        <i class="icmn-calendar"></i>
                                                                    </span>
                                                                </label>
                                                                <input type="hidden" id="mc_date_formed"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.certificate_series_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="mc_certificate_no">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.first_agm_date') }}</label>
                                                                <label class="input-group datepicker-only-init">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.first_agm_date') }}" id="mc_first_agm_raw"/>
                                                                    <span class="input-group-addon">
                                                                        <i class="icmn-calendar"></i>
                                                                    </span>
                                                                </label>
                                                                <input type="hidden" id="mc_first_agm"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="mc_name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="mc_address1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="mc_address2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="mc_address3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="mc_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}">{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="mc_poscode">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="mc_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}">{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="mc_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}">{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="mc_phone_no">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="mc_fax_no">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="mc_email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_mc" id="is_mc" {{($management->is_mc == 1 ? " checked" : "")}}/>
                                                <label><h4> {{ trans('app.forms.management_corporation') }}</h4></label>
                                                <!-- mc Form -->
                                                <div id="mc_form">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.date_formed') }}</label>
                                                                <label class="input-group datepicker-only-init">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="mc_date_formed_raw" value="{{ ($management->mc->date_formed != '0000-00-00' ? date('d-m-Y', strtotime($management->mc->date_formed)) : '') }}"/>
                                                                    <span class="input-group-addon">
                                                                        <i class="icmn-calendar"></i>
                                                                    </span>
                                                                </label>
                                                                <input type="hidden" id="mc_date_formed" value="{{ $management->mc->date_formed }}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.certificate_series_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="mc_certificate_no" value="{{$management->mc->certificate_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.first_agm_date') }}</label>
                                                                <label class="input-group datepicker-only-init">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.first_agm_date') }}" id="mc_first_agm_raw" value="{{ ($management->mc->first_agm != '0000-00-00' ? date('d-m-Y', strtotime($management->mc->first_agm)) : '') }}"/>
                                                                    <span class="input-group-addon">
                                                                        <i class="icmn-calendar"></i>
                                                                    </span>
                                                                </label>
                                                                <input type="hidden" id="mc_first_agm" value="{{$management->mc->first_agm}}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="mc_name" value="{{$management->mc->name}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="mc_address1" value="{{$management->mc->address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="mc_address2" value="{{$management->mc->address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="mc_address3" value="{{$management->mc->address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="mc_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($management->mc->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="mc_poscode" value="{{$management->mc->poscode}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="mc_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($management->mc->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="mc_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($management->mc->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="mc_phone_no" value="{{$management->mc->phone_no}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="mc_fax_no" value="{{$management->mc->fax_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="mc_email" value="{{$management->mc->email}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <hr/>
                                        @if(! $management->agent)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_agent" id="is_agent"/>
                                                <label><h4> {{ trans('app.forms.agent') }}</h4></label>
                                                <!-- agent Form -->
                                                <div id="agent_form" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.appointed_by') }}</label>
                                                                <select class="form-control select2" id="agent_selected_by">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="developer">{{ trans('app.forms.developer') }}</option>
                                                                    <option value="cob">{{ trans('app.forms.cob') }}</option>
                                                                    <option value="jmb">{{ trans('app.forms.jmb') }}</option>
                                                                    <option value="mc">{{ trans('app.forms.mc') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <select class="form-control select2" id="agent_name">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($agent as $agents)
                                                                    <option value="{{$agents->id}}">{{$agents->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="agent_address1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="agent_address2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="agent_address3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="agent_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}">{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="agent_poscode">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="agent_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}">{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="agent_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}">{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="agent_phone_no">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="agent_fax_no">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="agent_email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_agent" id="is_agent" {{($management->is_agent == 1 ? " checked" : "")}}/>
                                                <label><h4> {{ trans('app.forms.agent') }}</h4></label>
                                                <!-- agent Form -->
                                                <div id="agent_form">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.appointed_by') }}</label>
                                                                <select class="form-control select2" id="agent_selected_by">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="developer" {{($management->agent->selected_by == "developer" ? " selected" : "")}}>{{ trans('app.forms.developer') }}</option>
                                                                    <option value="cob" {{($management->agent->selected_by == "cob" ? " selected" : "")}}>{{ trans('app.forms.cob') }}</option>
                                                                    <option value="jmb" {{($management->agent->selected_by == "jmb" ? " selected" : "")}}>{{ trans('app.forms.jmb') }}</option>
                                                                    <option value="mc" {{($management->agent->selected_by == "mc" ? " selected" : "")}}>{{ trans('app.forms.mc') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <select class="form-control select2" id="agent_name">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($agent as $agents)
                                                                    <option value="{{$agents->id}}" {{($management->agent->agent == $agents->id ? " selected" : "")}}>{{$agents->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="agent_address1" value="{{$management->agent->address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="agent_address2" value="{{$management->agent->address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="agent_address3" value="{{$management->agent->address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="agent_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($management->agent->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="agent_poscode" value="{{$management->agent->address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="agent_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($management->agent->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="agent_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($management->agent->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="agent_phone_no" value="{{$management->agent->phone_no}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="agent_fax_no" value="{{$management->agent->fax_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="agent_email" value="{{$management->agent->email}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <hr/>
                                        @if (! $management->others)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_others" id="is_others"/>
                                                <label><h4> {{ trans('app.forms.others') }}</h4></label>
                                                <!-- jmb Form -->
                                                <div id="other_form" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="others_name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="others_address1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="others_address2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="others_address3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="others_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}">{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="others_poscode">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="others_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}">{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="others_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}">{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="others_phone_no">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="others_fax_no">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="others_email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="is_others" id="is_others" {{($management->is_others == 1 ? " checked" : "")}}/>
                                                <label><h4> {{ trans('app.forms.others') }}</h4></label>
                                                <!-- jmb Form -->
                                                <div id="other_form">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="others_name" value="{{$management->others->name}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="others_address1" value="{{$management->others->address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="others_address2" value="{{$management->others->address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="others_address3" value="{{$management->others->address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="others_city">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($management->others->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="others_poscode" value="{{$management->others->poscode}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="others_state">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($management->others->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control select2" id="others_country">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($management->others->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="others_phone_no" value="{{$management->others->phone_no}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="others_fax_no" value="{{$management->others->fax_no}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.email') }}</label>
                                                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="others_email" value="{{$management->others->email}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-actions">
                                            <?php if ($update_permission == 1) { ?>
                                                <button type="button" class="btn btn-own" id="submit_button" onclick="updateManagement()">{{ trans('app.forms.submit') }}</button>
                                            <?php } ?>

                                            @if ($file->is_active != 2)
                                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                            @else
                                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileListBeforeVP')}}'">{{ trans('app.forms.cancel') }}</button>
                                            @endif
                                        </div>
                                    </form>
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
    var changes = false;
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });

    $(function () {
        $('#jmb_date_formed_raw').datetimepicker({
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
            $("#jmb_date_formed").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#mc_date_formed_raw').datetimepicker({
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
            $("#mc_date_formed").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#mc_first_agm_raw').datetimepicker({
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
            $("#mc_first_agm").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $("[data-toggle=tooltip]").tooltip();
    });

    $(document).ready(function () {
        $('#is_developer').click(function () {
            if ($(this).is(':checked')) {
                $("#developer_form").fadeIn(500);
            } else {
                $("#developer_form").fadeOut(0);
            }
        });
        $('#is_jmb').click(function () {
            if ($(this).is(':checked')) {
                $("#jmb_form").fadeIn(500);
            } else {
                $("#jmb_form").fadeOut(0);
            }
        });
        $('#is_mc').click(function () {
            if ($(this).is(':checked')) {
                $("#mc_form").fadeIn(500);
            } else {
                $("#mc_form").fadeOut(0);
            }
        });
        $('#is_agent').click(function () {
            if ($(this).is(':checked')) {
                $("#agent_form").fadeIn(500);
            } else {
                $("#agent_form").fadeOut(0);
            }
        });
        $('#is_others').click(function () {
            if ($(this).is(':checked')) {
                $("#other_form").fadeIn(500);
            } else {
                $("#other_form").fadeOut(0);
            }
        });
    });

    function updateManagement() {
        changes = false;
        $("#loading").css("display", "inline-block");

        //developer
        var developer_name = $("#developer_name").val(),
                developer_address1 = $("#developer_address1").val(),
                developer_address2 = $("#developer_address2").val(),
                developer_address3 = $("#developer_address3").val(),
                developer_address4 = $("#developer_address4").val(),
                developer_city = $("#developer_city").val(),
                developer_poscode = $("#developer_poscode").val(),
                developer_state = $("#developer_state").val(),
                developer_country = $("#developer_country").val(),
                developer_fax_no = $("#developer_fax_no").val(),
                developer_phone_no = $("#developer_phone_no").val(),
                developer_remarks = $("#developer_remarks").val();

        //jmb
        var jmb_date_formed = $("#jmb_date_formed").val(),
                jmb_certificate_no = $("#jmb_certificate_no").val(),
                jmb_name = $("#jmb_name").val(),
                jmb_address1 = $("#jmb_address1").val(),
                jmb_address2 = $("#jmb_address2").val(),
                jmb_address3 = $("#jmb_address3").val(),
                jmb_city = $("#jmb_city").val(),
                jmb_poscode = $("#jmb_poscode").val(),
                jmb_state = $("#jmb_state").val(),
                jmb_country = $("#jmb_country").val(),
                jmb_phone_no = $("#jmb_phone_no").val(),
                jmb_fax_no = $("#jmb_fax_no").val(),
                jmb_email = $("#jmb_email").val();

        //mc
        var mc_date_formed = $("#mc_date_formed").val(),
                mc_certificate_no = $("#mc_certificate_no").val(),
                mc_first_agm = $("#mc_first_agm").val(),
                mc_name = $("#mc_name").val(),
                mc_address1 = $("#mc_address1").val(),
                mc_address2 = $("#mc_address2").val(),
                mc_address3 = $("#mc_address3").val(),
                mc_city = $("#mc_city").val(),
                mc_poscode = $("#mc_poscode").val(),
                mc_state = $("#mc_state").val(),
                mc_country = $("#mc_country").val(),
                mc_phone_no = $("#mc_phone_no").val(),
                mc_fax_no = $("#mc_fax_no").val(),
                mc_email = $("#mc_email").val();

        //agent
        var agent_selected_by = $("#agent_selected_by").val(),
                agent_name = $("#agent_name").val(),
                agent_address1 = $("#agent_address1").val(),
                agent_address2 = $("#agent_address2").val(),
                agent_address3 = $("#agent_address3").val(),
                agent_city = $("#agent_city").val(),
                agent_poscode = $("#agent_poscode").val(),
                agent_state = $("#agent_state").val(),
                agent_country = $("#agent_country").val(),
                agent_phone_no = $("#agent_phone_no").val(),
                agent_fax_no = $("#agent_fax_no").val(),
                agent_email = $("#agent_email").val();

        //others
        var others_name = $("#others_name").val(),
                others_address1 = $("#others_address1").val(),
                others_address2 = $("#others_address2").val(),
                others_address3 = $("#others_address3").val(),
                others_city = $("#others_city").val(),
                others_poscode = $("#others_poscode").val(),
                others_state = $("#others_state").val(),
                others_country = $("#others_country").val(),
                others_phone_no = $("#others_phone_no").val(),
                others_fax_no = $("#others_fax_no").val(),
                others_email = $("#others_email").val(),
                is_developer,
                is_jmb,
                is_mc,
                is_agent,
                is_others;

        if (document.getElementById('is_developer').checked) {
            is_developer = 1;
        } else {
            is_developer = 0;
        }

        if (document.getElementById('is_jmb').checked) {
            is_jmb = 1;
        } else {
            is_jmb = 0;
        }
        if (document.getElementById('is_mc').checked) {
            is_mc = 1;
        } else {
            is_mc = 0;
        }
        if (document.getElementById('is_agent').checked) {
            is_agent = 1;
        } else {
            is_agent = 0;
        }
        if (document.getElementById('is_others').checked) {
            is_others = 1;
        } else {
            is_others = 0;
        }

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateManagement') }}",
                type: "POST",
                data: {
                    //developer
                    is_developer: is_developer,
                    developer_name: developer_name,
                    developer_address1: developer_address1,
                    developer_address2: developer_address2,
                    developer_address3: developer_address3,
                    developer_address4: developer_address4,
                    developer_city: developer_city,
                    developer_poscode: developer_poscode,
                    developer_state: developer_state,
                    developer_country: developer_country,
                    developer_fax_no: developer_fax_no,
                    developer_phone_no: developer_phone_no,
                    developer_remarks: developer_remarks,
                    //jmb
                    is_jmb: is_jmb,
                    jmb_date_formed: jmb_date_formed,
                    jmb_certificate_no: jmb_certificate_no,
                    jmb_name: jmb_name,
                    jmb_address1: jmb_address1,
                    jmb_address2: jmb_address2,
                    jmb_address3: jmb_address3,
                    jmb_city: jmb_city,
                    jmb_poscode: jmb_poscode,
                    jmb_state: jmb_state,
                    jmb_country: jmb_country,
                    jmb_phone_no: jmb_phone_no,
                    jmb_fax_no: jmb_fax_no,
                    jmb_email: jmb_email,
                    //mc
                    is_mc: is_mc,
                    mc_date_formed: mc_date_formed,
                    mc_certificate_no: mc_certificate_no,
                    mc_first_agm: mc_first_agm,
                    mc_name: mc_name,
                    mc_address1: mc_address1,
                    mc_address2: mc_address2,
                    mc_address3: mc_address3,
                    mc_city: mc_city,
                    mc_poscode: mc_poscode,
                    mc_state: mc_state,
                    mc_country: mc_country,
                    mc_phone_no: mc_phone_no,
                    mc_fax_no: mc_fax_no,
                    mc_email: mc_email,
                    //agent
                    is_agent: is_agent,
                    agent_selected_by: agent_selected_by,
                    agent_name: agent_name,
                    agent_address1: agent_address1,
                    agent_address2: agent_address2,
                    agent_address3: agent_address3,
                    agent_city: agent_city,
                    agent_poscode: agent_poscode,
                    agent_state: agent_state,
                    agent_country: agent_country,
                    agent_phone_no: agent_phone_no,
                    agent_fax_no: agent_fax_no,
                    agent_email: agent_email,
                    //others
                    is_others: is_others,
                    others_name: others_name,
                    others_address1: others_address1,
                    others_address2: others_address2,
                    others_address3: others_address3,
                    others_city: others_city,
                    others_poscode: others_poscode,
                    others_state: others_state,
                    others_country: others_country,
                    others_phone_no: others_phone_no,
                    others_fax_no: others_fax_no,
                    others_email: others_email,
                    //id
                    file_id: '{{ \Helper\Helper::encode($file->id) }}',
                    reference_id: '{{ ($management->reference_id ? $management->reference_id : $management->id) }}'
                },
                beforeSend: function() {
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        window.location = "{{URL::action('AdminController@monitoring', \Helper\Helper::encode($file->id))}}";
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
</script>

<!-- End Page Scripts-->

@stop
