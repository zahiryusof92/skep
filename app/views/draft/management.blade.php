@extends('layout.english_layout.default')

@section('content')

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
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            @if ($file->houseScheme->draft)
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('DraftController@houseScheme', \Helper\Helper::encode($file->id))}}">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            @endif
                            @if ($file->strata->draft)
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('DraftController@strata', \Helper\Helper::encode($file->id))}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            @endif                            
                            <li class="nav-item">
                                <a class="nav-link active custom-tab">{{ trans('app.forms.management') }}</a>
                            </li>                            
                            @if ($file->other->draft)
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('DraftController@others', \Helper\Helper::encode($file->id))}}">{{ trans('app.forms.others') }}</a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="management" role="tabpanel">

                                @if ($management->draft)
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">                                    
                                        <div class="col-lg-12">
                                            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
                                            <h4>{{ trans('app.forms.detail') }} <span class="label label-danger">{{ trans('app.forms.draft') }}</span></h4>
                                            <form id="management_draft">
                                                @if (($management->is_developer != $management->draft->is_developer) && !$management->draft->is_developer)
                                                    @include('components.is_changed', ['old_field' => $management->is_developer, 'new_field' => $management->draft->is_developer, 'text' => trans('app.forms.developer')])
                                                    <hr />
                                                @endif
                                                @if ($management->draft->developer)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" disabled="" {{($management->draft->developer ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.developer') }}</h4> 
                                                        </label>
                                                        @include('components.is_changed', ['old_field' => $management->is_developer, 'new_field' => $management->draft->is_developer])
                                                        <!-- developer Form -->
                                                        <div id="developer_form_draft">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.developer') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->name : "", 'new_field' => $management->draft->developer? $management->draft->developer->name : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.developer') }}" value="{{$management->draft->developer->name}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.address') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->address_1 : "", 'new_field' => $management->draft->developer? $management->draft->developer->address_1 : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" value="{{$management->draft->developer->address_1}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$management->draft->developer->address_2}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->developer? $management->developer->address_2 : "", 'new_field' => $management->draft->developer? $management->draft->developer->address_2 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$management->draft->developer->address_3}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->developer? $management->developer->address_3 : "", 'new_field' => $management->draft->developer? $management->draft->developer->address_3 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" value="{{$management->draft->developer->address_4}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->developer? $management->developer->address_4 : "", 'new_field' => $management->draft->developer? $management->draft->developer->address_4 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.city') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->city : "", 'new_field' => $management->draft->developer? $management->draft->developer->city : ""])
                                                                        <select class="form-control" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($city as $cities)
                                                                            <option value="{{$cities->id}}" {{($management->draft->developer->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.postcode') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->poscode : "", 'new_field' => $management->draft->developer? $management->draft->developer->poscode : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$management->draft->developer->poscode}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.state') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->state : "", 'new_field' => $management->draft->developer? $management->draft->developer->state : ""])
                                                                        <select class="form-control" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($state as $states)
                                                                            <option value="{{$states->id}}" {{($management->draft->developer->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.country') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->country : "", 'new_field' => $management->draft->developer? $management->draft->developer->country : ""])
                                                                        <select class="form-control" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($country as $countries)
                                                                            <option value="{{$countries->id}}" {{($management->draft->developer->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.phone_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->phone_no : "", 'new_field' => $management->draft->developer? $management->draft->developer->phone_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" value="{{$management->draft->developer->phone_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.fax_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->fax_no : "", 'new_field' => $management->draft->developer? $management->draft->developer->fax_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" value="{{$management->draft->developer->fax_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.remarks') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->developer? $management->developer->remarks : "", 'new_field' => $management->draft->developer? $management->draft->developer->remarks : ""])
                                                                        <textarea class="form-control" rows="3" readonly="">{{$management->draft->developer->remarks}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                @endif

                                                @if (($management->is_jmb != $management->draft->is_jmb) && !$management->draft->is_jmb)
                                                    @include('components.is_changed', ['old_field' => $management->is_jmb, 'new_field' => $management->draft->is_jmb, 'text' => trans('app.forms.jmb')])
                                                    <hr />
                                                @endif
                                                @if ($management->draft->jmb)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" disabled="" {{($management->draft->jmb ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.joint_management_body') }}</h4></label>
                                                        @include('components.is_changed', ['old_field' => $management->is_jmb, 'new_field' => $management->draft->is_jmb])
                                                        <!-- jmb Form -->
                                                        <div id="jmb_form_draft">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.date_formed') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->date_formed : "", 'new_field' => ($management->draft->jmb && $management->draft->jmb->date_formed != '0000-00-00')? $management->draft->jmb->date_formed : ""])
                                                                        <label class="input-group datepicker-only-init">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" value="{{ ($management->draft->jmb->date_formed != '0000-00-00' ? date('d-m-Y', strtotime($management->draft->jmb->date_formed)) : '') }}" readonly=""/>
                                                                            <span class="input-group-addon">
                                                                                <i class="icmn-calendar"></i>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.certificate_series_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->certificate_no : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->certificate_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" value="{{$management->draft->jmb->certificate_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.name') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->name : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->name : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" value="{{$management->draft->jmb->name}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.address') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->address1 : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->address1 : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" value="{{$management->draft->jmb->address1}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$management->draft->jmb->address2}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->address2 : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->address2 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$management->draft->jmb->address3}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->address3 : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->address3 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.city') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->city : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->city : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($city as $cities)
                                                                            <option value="{{$cities->id}}" {{($management->draft->jmb->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.postcode') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->poscode : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->poscode : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$management->draft->jmb->poscode}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.state') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->state : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->state : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($state as $states)
                                                                            <option value="{{$states->id}}" {{($management->draft->jmb->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.country') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->country : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->country : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($country as $countries)
                                                                            <option value="{{$countries->id}}" {{($management->draft->jmb->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.phone_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->phone_no : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->phone_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" value="{{$management->draft->jmb->phone_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.fax_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->fax_no : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->fax_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" value="{{$management->draft->jmb->fax_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.email') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->jmb? $management->jmb->email : "", 'new_field' => $management->draft->jmb? $management->draft->jmb->email : ""])
                                                                        <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" value="{{$management->draft->jmb->email}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                @endif 

                                                @if (($management->is_mc != $management->draft->is_mc) && !$management->draft->is_mc)
                                                    @include('components.is_changed', ['old_field' => $management->is_mc, 'new_field' => $management->draft->is_mc, 'text' => trans('app.forms.mc')])
                                                    <hr />
                                                @endif
                                                @if ($management->draft->mc)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" disabled="" {{($management->draft->mc ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.management_corporation') }}</h4></label>
                                                        @include('components.is_changed', ['old_field' => $management->is_mc, 'new_field' => $management->draft->is_mc])
                                                        <!-- mc Form -->
                                                        <div id="mc_form_draft">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.date_formed') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->date_formed : "", 'new_field' => ($management->draft->mc && $management->draft->mc->date_formed != '0000-00-00')? $management->draft->mc->date_formed : ""])
                                                                        <label class="input-group datepicker-only-init">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" value="{{ ($management->draft->mc->date_formed != '0000-00-00' ? date('d-m-Y', strtotime($management->draft->mc->date_formed)) : '') }}" readonly=""/>
                                                                            <span class="input-group-addon">
                                                                                <i class="icmn-calendar"></i>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.certificate_series_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->certificate_no : "", 'new_field' => $management->draft->mc? $management->draft->mc->certificate_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" value="{{$management->draft->mc->certificate_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.first_agm_date') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->first_agm : "", 'new_field' => ($management->draft->mc && $management->draft->mc->first_agm != '0000-00-00')? $management->draft->mc->first_agm : ""])
                                                                        <label class="input-group datepicker-only-init">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.first_agm_date') }}" value="{{ ($management->draft->mc->first_agm != '0000-00-00' ? date('d-m-Y', strtotime($management->draft->mc->first_agm)) : '') }}" readonly=""/>
                                                                            <span class="input-group-addon">
                                                                                <i class="icmn-calendar"></i>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.name') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->name : "", 'new_field' => $management->draft->mc? $management->draft->mc->name : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" value="{{$management->draft->mc->name}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.address') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->address1 : "", 'new_field' => $management->draft->mc? $management->draft->mc->address1 : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" value="{{$management->draft->mc->address1}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$management->draft->mc->address2}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->mc? $management->mc->address2 : "", 'new_field' => $management->draft->mc? $management->draft->mc->address2 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$management->draft->mc->address3}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->mc? $management->mc->address3 : "", 'new_field' => $management->draft->mc? $management->draft->mc->address3 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.city') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->city : "", 'new_field' => $management->draft->mc? $management->draft->mc->city : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($city as $cities)
                                                                            <option value="{{$cities->id}}" {{($management->draft->mc->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.postcode') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->poscode : "", 'new_field' => $management->draft->mc? $management->draft->mc->poscode : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$management->draft->mc->poscode}}" disabled="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.state') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->state : "", 'new_field' => $management->draft->mc? $management->draft->mc->state : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($state as $states)
                                                                            <option value="{{$states->id}}" {{($management->draft->mc->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.country') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->country : "", 'new_field' => $management->draft->mc? $management->draft->mc->country : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($country as $countries)
                                                                            <option value="{{$countries->id}}" {{($management->draft->mc->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.phone_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->phone_no : "", 'new_field' => $management->draft->mc? $management->draft->mc->phone_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" value="{{$management->draft->mc->phone_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.fax_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->fax_no : "", 'new_field' => $management->draft->mc? $management->draft->mc->fax_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" value="{{$management->draft->mc->fax_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.email') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->mc? $management->mc->email : "", 'new_field' => $management->draft->mc? $management->draft->mc->email : ""])
                                                                        <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" value="{{$management->draft->mc->email}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                @endif

                                                @if (($management->is_agent != $management->draft->is_agent) && !$management->draft->is_agent)
                                                    @include('components.is_changed', ['old_field' => $management->is_agent, 'new_field' => $management->draft->is_agent, 'text' => trans('app.forms.agent')])
                                                    <hr />
                                                @endif
                                                @if($management->draft->agent)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" disabled="" {{($management->draft->agent ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.agent') }}</h4></label>
                                                        @include('components.is_changed', ['old_field' => $management->is_agent, 'new_field' => $management->draft->is_agent])
                                                        <!-- agent Form -->
                                                        <div id="agent_form_draft">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.appointed_by') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->selected_by : "", 'new_field' => $management->draft->agent? $management->draft->agent->selected_by : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            <option value="developer" {{($management->draft->agent->selected_by == "developer" ? " selected" : "")}}>{{ trans('app.forms.developer') }}</option>
                                                                            <option value="cob" {{($management->draft->agent->selected_by == "cob" ? " selected" : "")}}>{{ trans('app.forms.cob') }}</option>
                                                                            <option value="jmb" {{($management->draft->agent->selected_by == "jmb" ? " selected" : "")}}>{{ trans('app.forms.jmb') }}</option>
                                                                            <option value="mc" {{($management->draft->agent->selected_by == "mc" ? " selected" : "")}}>{{ trans('app.forms.mc') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.name') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->agent : "", 'new_field' => $management->draft->agent? $management->draft->agent->agent : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($agent as $agents)
                                                                            <option value="{{$agents->id}}" {{($management->draft->agent->agent == $agents->id ? " selected" : "")}}>{{$agents->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.address') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->address1 : "", 'new_field' => $management->draft->agent? $management->draft->agent->address1 : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" value="{{$management->draft->agent->address1}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$management->draft->agent->address2}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->agent? $management->agent->address2 : "", 'new_field' => $management->draft->agent? $management->draft->agent->address2 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$management->draft->agent->address3}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->agent? $management->agent->address3 : "", 'new_field' => $management->draft->agent? $management->draft->agent->address3 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.city') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->city : "", 'new_field' => $management->draft->agent? $management->draft->agent->city : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($city as $cities)
                                                                            <option value="{{$cities->id}}" {{($management->draft->agent->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.postcode') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->poscode : "", 'new_field' => $management->draft->agent? $management->draft->agent->poscode : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$management->draft->agent->poscode}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.state') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->state : "", 'new_field' => $management->draft->agent? $management->draft->agent->state : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($state as $states)
                                                                            <option value="{{$states->id}}" {{($management->draft->agent->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.country') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->country : "", 'new_field' => $management->draft->agent? $management->draft->agent->country : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($country as $countries)
                                                                            <option value="{{$countries->id}}" {{($management->draft->agent->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.phone_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->phone_no : "", 'new_field' => $management->draft->agent? $management->draft->agent->phone_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" value="{{$management->draft->agent->phone_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.fax_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->fax_no : "", 'new_field' => $management->draft->agent? $management->draft->agent->fax_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" value="{{$management->draft->agent->fax_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.email') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->agent? $management->agent->email : "", 'new_field' => $management->draft->agent? $management->draft->agent->email : ""])
                                                                        <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" value="{{$management->draft->agent->email}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                @endif

                                                @if (($management->is_others != $management->draft->is_others) && !$management->draft->is_others)
                                                    @include('components.is_changed', ['old_field' => $management->is_others, 'new_field' => $management->draft->is_others, 'text' => trans('app.forms.others')])
                                                    <hr />
                                                @endif
                                                @if ($management->draft->others)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" disabled="" {{($management->draft->others ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.others') }}</h4></label>
                                                        @include('components.is_changed', ['old_field' => $management->is_others, 'new_field' => $management->draft->is_others])
                                                        <!-- jmb Form -->
                                                        <div id="other_form_draft">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.name') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->name : "", 'new_field' => $management->draft->others? $management->draft->others->name : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" value="{{$management->draft->others->name}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.address') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->address1 : "", 'new_field' => $management->draft->others? $management->draft->others->address1 : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" value="{{$management->draft->others->address1}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$management->draft->others->address2}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->others? $management->others->address2 : "", 'new_field' => $management->draft->others? $management->draft->others->address2 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$management->draft->others->address3}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                @include('components.is_changed', ['old_field' => $management->others? $management->others->address3 : "", 'new_field' => $management->draft->others? $management->draft->others->address3 : "", 'class' => 'margin-top-5'])
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.city') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->city : "", 'new_field' => $management->draft->others? $management->draft->others->city : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($city as $cities)
                                                                            <option value="{{$cities->id}}" {{($management->draft->others->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.postcode') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->poscode : "", 'new_field' => $management->draft->others? $management->draft->others->poscode : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$management->draft->others->poscode}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.state') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->state : "", 'new_field' => $management->draft->others? $management->draft->others->state : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($state as $states)
                                                                            <option value="{{$states->id}}" {{($management->draft->others->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.country') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->country : "", 'new_field' => $management->draft->others? $management->draft->others->country : ""])
                                                                        <select class="form-control select2" disabled="">
                                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                            @foreach ($country as $countries)
                                                                            <option value="{{$countries->id}}" {{($management->draft->others->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.phone_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->phone_no : "", 'new_field' => $management->draft->others? $management->draft->others->phone_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" value="{{$management->draft->others->phone_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.fax_number') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->fax_no : "", 'new_field' => $management->draft->others? $management->draft->others->fax_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" value="{{$management->draft->others->fax_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.email') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $management->others? $management->others->email : "", 'new_field' => $management->draft->others? $management->draft->others->email : ""])
                                                                        <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" value="{{$management->draft->others->email}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                <div class="form-actions">
                                                    <button type="button" class="btn btn-own" id="submit_button_draft" onclick="submitDraft()">{{ trans('app.buttons.accept') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </section>

                                <script>
                                    function submitDraft() {
                                        swal({
                                            title: "{{ trans('app.confirmation.are_you_sure') }}",
                                            text: "{{ trans('app.confirmation.no_recover_file') }}",
                                            type: "warning",
                                            showCancelButton: true,
                                            confirmButtonClass: "btn-primary",
                                            cancelButtonClass: "btn-danger",
                                            confirmButtonText: "Proceed",
                                            closeOnConfirm: true
                                        }, function () {
                                            $.ajax({
                                                url: "{{ URL::action('DraftController@submitManagement') }}",
                                                type: "POST",
                                                data: {
                                                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
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
                                                        window.location = "{{URL::action('DraftController@others', \Helper\Helper::encode($file->id))}}";
                                                    } else {
                                                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                                                    }
                                                }
                                            });
                                        });
                                    }
                                </script>
                                @endif

                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">                                    
                                        <div class="col-lg-12">
                                            <h6><u>{{ trans("app.forms.before_change") }}</u></h6>
                                            <h4>{{ trans('app.forms.detail') }}</h4>
                                            <form id="management">
                                                @if ($management->developer)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="is_developer" id="is_developer" {{($management->developer ? " checked" : "")}}/>
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
                                                <hr/>
                                                @endif

                                                @if ($management->jmb)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="is_jmb" id="is_jmb" {{($management->jmb ? " checked" : "")}}/>
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
                                                <hr/>
                                                @endif

                                                @if ($management->mc)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="is_mc" id="is_mc" {{($management->mc ? " checked" : "")}}/>
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
                                                <hr/>
                                                @endif

                                                @if($management->agent)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="is_agent" id="is_agent" {{($management->agent ? " checked" : "")}}/>
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
                                                <hr/>
                                                @endif

                                                @if ($management->others)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="is_others" id="is_others" {{($management->others ? " checked" : "")}}/>
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
                                            </form>
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

@stop
