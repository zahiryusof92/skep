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
                        @include('page_en.nav.cob_file', ['files' => $file, 'is_view' => true])

                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="house_scheme" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <h4>{{ trans('app.forms.detail') }}</h4>
                                            <!-- House Form -->
                                            <form id="house">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_developer') }}" id="is_developer" name="is_liquidator" value="no" {{($house_scheme->is_liquidator)? "" : "checked"}} disabled>
                                                            <label class="form-check-label" for="is_developer">
                                                                {{ trans('app.forms.is_developer') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="developer_form">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="name" value="{{$house_scheme->name}}" readonly="">
                                                                <div id="name_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.developer') }}</label>
                                                                <select class="form-control" id="developer" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($developer as $developers)
                                                                    <option value="{{$developers->id}}" {{($house_scheme->developer == $developers->id ? " selected" : "")}}>{{$developers->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="developer_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="address1" value="{{$house_scheme->address1}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="address2" value="{{$house_scheme->address2}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="address3" value="{{$house_scheme->address3}}" readonly="">
                                                                <div id="address_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control" id="city" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($house_scheme->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="city_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="poscode" value="{{$house_scheme->poscode}}" readonly="">
                                                                <div id="poscode_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control" id="state" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($house_scheme->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="state_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control" id="country" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($house_scheme->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="country_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no" value="{{$house_scheme->phone_no}}" readonly="">
                                                                <div id="phone_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="fax_no" value="{{$house_scheme->fax_no}}" readonly="">
                                                                <div id="fax_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.admin_status') }}</label>
                                                                <select id="is_active" class="form-control" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="1" {{($house_scheme->is_active==1 ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                                                    <option value="0" {{($house_scheme->is_active==0 ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                                                </select>
                                                                <div id="is_active_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }}</label>
                                                                <textarea class="form-control" rows="3" id="remarks" readonly="">{{$house_scheme->remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_liquidator') }}" id="is_liquidator" name="is_liquidator" value="yes" {{($house_scheme->is_liquidator)? "checked" : ""}} disabled>
                                                            <label class="form-check-label" for="is_liquidator">
                                                                {{ trans('app.forms.is_liquidator') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="liquidator_form" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="liquidator_name" value="{{$house_scheme->liquidator_name}}" readonly="">
                                                                <div id="liquidator_name_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.liquidator') }}</label>
                                                                <select class="form-control" id="liquidator" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($liquidator as $liquidators)
                                                                    <option value="{{$liquidators->id}}" {{($house_scheme->liquidator == $liquidators->id ? " selected" : "")}}>{{$liquidators->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="liquidator_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="liquidator_address1" value="{{$house_scheme->liquidator_address1}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="liquidator_address2" value="{{$house_scheme->liquidator_address2}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="liquidator_address3" value="{{$house_scheme->liquidator_address3}}" readonly="">
                                                                <div id="address_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control" id="liquidator_city" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($house_scheme->liquidator_city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="liquidator_city_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="liquidator_poscode" value="{{$house_scheme->liquidator_poscode}}" readonly="">
                                                                <div id="liquidator_poscode_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control" id="liquidator_state" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($house_scheme->liquidator_state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="liquidator_state_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }}</label>
                                                                <select class="form-control" id="liquidator_country" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($house_scheme->liquidator_country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="liquidator_country_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="liquidator_phone_no" value="{{$house_scheme->liquidator_phone_no}}" readonly="">
                                                                <div id="liquidator_phone_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="liquidator_fax_no" value="{{$house_scheme->liquidator_fax_no}}" readonly="">
                                                                <div id="liquidator_fax_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.admin_status') }}</label>
                                                                <select id="liquidator_is_active" class="form-control" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="1" {{($house_scheme->liquidator_is_active==1 ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                                                    <option value="0" {{($house_scheme->liquidator_is_active==0 ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                                                </select>
                                                                <div id="liquidator_is_active_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }}</label>
                                                                <textarea class="form-control" rows="3" id="liquidator_remarks" readonly="">{{$house_scheme->liquidator_remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- End House Form -->
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

<script>
    $(function() { 
        if("{{$house_scheme->is_liquidator}}" > 0) {
            $('#liquidator_form').show();
            $('#developer_form').hide();
        } else {
            $('#liquidator_form').hide();
            $('#developer_form').show();
        }
    });
</script>
@stop
