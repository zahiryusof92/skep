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
                            <div class="tab-pane active" id="house_scheme" role="tabpanel">

                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">                                    
                                        <div class="col-lg-12">
                                            <h4>{{ trans('app.forms.detail') }}</h4>
                                            <!-- House Form -->
                                            <form id="house">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_developer') }}" id="is_developer" name="is_liquidator" value="no" {{($house_scheme->is_liquidator)? "" : "checked"}}>
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
                                                                <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="name" value="{{$house_scheme->name}}">
                                                                <div id="name_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>                                           
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.developer') }}</label>
                                                                <select class="form-control select2" id="developer">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="address1" value="{{$house_scheme->address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="address2" value="{{$house_scheme->address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="address3" value="{{$house_scheme->address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="address4" value="{{$house_scheme->address4}}">
                                                                <div id="address_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="city">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="poscode" value="{{$house_scheme->poscode}}">
                                                                <div id="poscode_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="state">
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
                                                                <select class="form-control select2" id="country">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no" value="{{$house_scheme->phone_no}}">
                                                                <div id="phone_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="fax_no" value="{{$house_scheme->fax_no}}">
                                                                <div id="fax_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><span style="color: red;">*</span> {{ trans('app.forms.admin_status') }}</label>
                                                                <select id="is_active" class="form-control select2">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="1" {{($house_scheme->is_active == '1' ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                                                    <option value="0" {{($house_scheme->is_active == '0' ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                                                    <option value="2" {{($house_scheme->is_active == '2' ? " selected" : "")}}>{{ trans('app.forms.before_vp') }}</option>
                                                                </select>
                                                                <div id="is_active_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }}</label>
                                                                <textarea class="form-control" rows="3" id="remarks">{{$house_scheme->remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr/>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_liquidator') }}" id="is_liquidator" name="is_liquidator" value="yes" {{($house_scheme->is_liquidator)? "checked" : ""}}>
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
                                                                <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="liquidator_name" value="{{$house_scheme->liquidator_name}}">
                                                                <div id="liquidator_name_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.liquidator') }}</label>
                                                                <select class="form-control select2" id="liquidator">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="liquidator_address1" value="{{$house_scheme->liquidator_address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="liquidator_address2" value="{{$house_scheme->liquidator_address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="liquidator_address3" value="{{$house_scheme->liquidator_address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="liquidator_address4" value="{{$house_scheme->liquidator_address4}}">
                                                                <div id="liquidator_address_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                <select class="form-control select2" id="liquidator_city">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="liquidator_poscode" value="{{$house_scheme->liquidator_poscode}}">
                                                                <div id="liquidator_poscode_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }}</label>
                                                                <select class="form-control select2" id="liquidator_state">
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
                                                                <select class="form-control select2" id="liquidator_country">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="liquidator_phone_no" value="{{$house_scheme->liquidator_phone_no}}">
                                                                <div id="liquidator_phone_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="liquidator_fax_no" value="{{$house_scheme->liquidator_fax_no}}">
                                                                <div id="liquidator_fax_no_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label><span style="color: red;">*</span> {{ trans('app.forms.admin_status') }}</label>
                                                                <select id="liquidator_is_active" class="form-control select2">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="1" {{($house_scheme->liquidator_is_active == '1' ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                                                    <option value="0" {{($house_scheme->liquidator_is_active == '0' ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                                                    <option value="2" {{($house_scheme->liquidator_is_active == '2' ? " selected" : "")}}>{{ trans('app.forms.before_vp') }}</option>
                                                                </select>
                                                                <div id="liquidator_is_active_error" style="display:none;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }}</label>
                                                                <textarea class="form-control" rows="3" id="liquidator_remarks">{{$house_scheme->liquidator_remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <?php if ($update_permission == 1) { ?>
                                            <button type="button" class="btn btn-own" id="submit_button" onclick="updateHouseScheme()">{{ trans('app.forms.submit') }}</button>
                                        <?php } ?>

                                        @if ($file->is_active != 2)
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                        @else
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileListBeforeVP')}}'">{{ trans('app.forms.cancel') }}</button>
                                        @endif
                                    </div>
                                </section>
                                <!-- End House Form -->

                                @if (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper())
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>{{ trans('app.forms.cob_person_in_charge') }}</h4>

                                                <?php if ($update_permission == 1) { ?>
                                                    <button class="btn btn-own margin-bottom-25" data-toggle="modal" data-target="#houseSchemeForm">
                                                        {{ trans('app.forms.add_cob_person_in_charge') }}
                                                    </button>

                                                    <div class="modal fade" id="houseSchemeForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                        <div class="modal-dialog">
                                                            <form id="form_housing_scheme">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">{{ trans('app.forms.add_cob_person_in_charge') }}</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label><span style="color: red;">*</span> {{ trans('app.forms.person_in_charge') }}</label>
                                                                                    <select name="housing_scheme" id="housing_scheme" class="form-control">
                                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                                        @if ($users)
                                                                                        @foreach ($users as $user)
                                                                                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                                                                        @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                    <div id="housing_scheme_error" style="display: none;"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" id="file_id" name="file_id" value="{{ \Helper\Helper::encode($file->id) }}"/>
                                                                            <img id="loading_housing_scheme" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                                                            <button id="submit_button_housing_scheme" class="btn btn-own" type="submit">
                                                                                {{ trans('app.forms.submit') }}
                                                                            </button>
                                                                            <button data-dismiss="modal" id="cancel_button_housing_scheme" class="btn btn-default" type="button">
                                                                                {{ trans('app.forms.cancel') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- modal -->

                                                    <script> 
                                                        $("#form_housing_scheme").on('submit', (function (e) {
                                                            changes = false;
                                                            e.preventDefault();
                                                            $('#loading_housing_scheme').css("display", "inline-block");
                                                            $("#submit_button_housing_scheme").attr("disabled", "disabled");
                                                            $("#cancel_button_housing_scheme").attr("disabled", "disabled");
                                                            $("#housing_scheme_error").css("display", "none");
                                                            var housing_scheme = $("#housing_scheme").val();
                                                            var error = 0;
                                                            if (housing_scheme.trim() == "") {
                                                            $("#housing_scheme_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"User"]) }}</span>');
                                                            $("#housing_scheme_error").css("display", "block");
                                                            error = 1;
                                                            }

                                                            if (error == 0) {
                                                            var formData = new FormData(this);
                                                            $.ajax({
                                                            url: "{{ URL::action('AdminController@submitAddHousingScheme') }}",
                                                                    type: "POST",
                                                                    data: formData,
                                                                    async: true,
                                                                    contentType: false, // The content type used when sending data to the server.
                                                                    cache: false, // To unable request pages to be cached
                                                                    processData: false,
                                                                    success: function (data) { //function to be called if request succeeds
                                                                    $('#loading_housing_scheme').css("display", "none");
                                                                    $("#submit_button_housing_scheme").removeAttr("disabled");
                                                                    $("#cancel_button_housing_scheme").removeAttr("disabled");
                                                                    if (data.trim() === "true") {
                                                                    $("#houseSchemeForm").modal("hide");
                                                                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>", function () {
                                                                    window.location.reload();
                                                                    });
                                                                    } else if (data.trim() === "data_exist") {
                                                                    $("#housing_scheme_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.exist2", ["attribute"=>"User"]) }}</span>');
                                                                    $("#housing_scheme_error").css("display", "block");
                                                                    } else {
                                                                    $("#houseSchemeForm").modal("hide");
                                                                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>", function () {
                                                                    window.location.reload();
                                                                    });
                                                                    }
                                                                    },
                                                            });
                                                            } else {
                                                            $("#housing_scheme").focus();
                                                            $('#loading_housing_scheme').css("display", "none");
                                                            $("#submit_button_housing_scheme").removeAttr("disabled");
                                                            $("#cancel_button_housing_scheme").removeAttr("disabled");
                                                            }
                                                        }));
                                                    </script>
                                                <?php } ?>

                                                <section class="panel panel-pad">
                                                    <div class="table-responsive padding-vertical-10">                                                            
                                                        <table class="table table-hover table-own table-striped" id="housing_scheme_list" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:45%;">{{ trans('app.forms.name') }}</th>
                                                                    <th style="width:20%;">{{ trans('app.forms.phone_number') }}</th>
                                                                    <th style="width:30%;">{{ trans('app.forms.email') }}</th>
                                                                    <?php if ($update_permission == 1) { ?>
                                                                        <th style="width:5%;">{{ trans('app.forms.action') }}</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
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
    var is_liquidator = ("{{$house_scheme->is_liquidator}}" == '1')? "yes" : "no";
    $(function() {
        if("{{$house_scheme->is_liquidator}}" > 0) {
            $('#liquidator_form').show();
            $('#developer_form').hide();
        } else {
            $('#liquidator_form').hide();
            $('#developer_form').show();
        }
    })
    $("input[name='is_liquidator']").on('click', function(e) {
        is_liquidator = this.value;
        if(this.value == 'yes') {
            $('#liquidator_form').show();
            $('#developer_form').hide();
        } else {
            $('#liquidator_form').hide();
            $('#developer_form').show();
        }
    })
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });

    function updateHouseScheme() {
        changes = false;
        $("#loading").css("display", "inline-block");

        var name = $("#name").val(),
                developer = $("#developer").val(),
                liquidator = $("#liquidator").val(),
                address1 = $("#address1").val(),
                address2 = $("#address2").val(),
                address3 = $("#address3").val(),
                address4 = $("#address4").val(),
                city = $("#city").val(),
                poscode = $("#poscode").val(),
                state = $("#state").val(),
                country = $("#country").val(),
                phone_no = $("#phone_no").val(),
                fax_no = $("#fax_no").val(),
                remarks = $("#remarks").val(),
                is_active = $("#is_active").val(),
                /*liquidator info*/
                liquidator_name = $("#liquidator_name").val(),
                liquidator_address1 = $("#liquidator_address1").val(),
                liquidator_address2 = $("#liquidator_address2").val(),
                liquidator_address3 = $("#liquidator_address3").val(),
                liquidator_address4 = $("#liquidator_address4").val(),
                liquidator_city = $("#liquidator_city").val(),
                liquidator_poscode = $("#liquidator_poscode").val(),
                liquidator_state = $("#liquidator_state").val(),
                liquidator_country = $("#liquidator_country").val(),
                liquidator_phone_no = $("#liquidator_phone_no").val(),
                liquidator_fax_no = $("#liquidator_fax_no").val(),
                liquidator_remarks = $("#liquidator_remarks").val(),
                liquidator_is_active = $("#liquidator_is_active").val();

        var error = 0;

        if(is_liquidator == 'yes') {
            if (liquidator_name.trim() == "") {
                $("#liquidator_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
                $("#liquidator_name_error").css("display", "block");
                error = 1;
            }

            if (liquidator_is_active.trim() == "") {
                $("#liquidator_is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
                $("#liquidator_is_active_error").css("display", "block");
                error = 1;
            }
        } else {
            if (name.trim() == "") {
                $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
                $("#name_error").css("display", "block");
                error = 1;
            }

            if (is_active.trim() == "") {
                $("#is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
                $("#is_active_error").css("display", "block");
                error = 1;
            }
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateHouseScheme') }}",
                type: "POST",
                data: {
                    name: name,
                    developer: developer,
                    liquidator: liquidator,
                    address1: address1,
                    address2: address2,
                    address3: address3,
                    address4: address4,
                    city: city,
                    poscode: poscode,
                    state: state,
                    country: country,
                    phone_no: phone_no,
                    fax_no: fax_no,
                    remarks: remarks,
                    is_active: is_active,
                    liquidator_name: liquidator_name,
                    liquidator_address1: liquidator_address1,
                    liquidator_address2: liquidator_address2,
                    liquidator_address3: liquidator_address3,
                    liquidator_address4: liquidator_address4,
                    liquidator_city: liquidator_city,
                    liquidator_poscode: liquidator_poscode,
                    liquidator_state: liquidator_state,
                    liquidator_country: liquidator_country,
                    liquidator_phone_no: liquidator_phone_no,
                    liquidator_fax_no: liquidator_fax_no,
                    liquidator_remarks: liquidator_remarks,
                    liquidator_is_active: liquidator_is_active,
                    is_liquidator: is_liquidator,
                    file_id: '{{ \Helper\Helper::encode($file->id) }}',
                    reference_id: '{{ ($house_scheme->reference_id ? $house_scheme->reference_id : $house_scheme->id) }}'
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
                        window.location = "{{URL::action('AdminController@strata', \Helper\Helper::encode($file->id))}}";
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
    
    $(document).ready(function () {
        $('#housing_scheme_list').DataTable({
            "sAjaxSource": "{{ URL::action('AdminController@getHousingScheme', \Helper\Helper::encode($file->id)) }}",
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

    function deleteHousingScheme(id){
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
        function(){
            $.ajax({
                url: "{{ URL::action('AdminController@deleteHousingScheme') }}",
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
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        });
    }
</script>


<!-- End Page Scripts-->

@stop
