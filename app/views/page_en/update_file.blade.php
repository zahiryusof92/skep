@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$file->file_no}}</h6>
                    <div id="update_files_lists">
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="#house_scheme" data-toggle="tab" role="tab">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@updateFileLists', 15)}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#management" data-toggle="tab" role="tab">{{ trans('app.forms.management') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#monitoring" data-toggle="tab" role="tab">{{ trans('app.forms.monitoring') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#others_tab" data-toggle="tab" role="tab">{{ trans('app.forms.others') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#scoring" data-toggle="tab" role="tab">{{ trans('app.forms.scoring_component_value') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#buyer" data-toggle="tab" role="tab">{{ trans('app.forms.buyer_list') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="house_scheme" role="tabpanel">
                                <div class="row">
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
                                                        <select class="form-control" id="developer">
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
                                                        <div id="address_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.city') }}</label>
                                                        <select class="form-control" id="city">
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
                                                        <select class="form-control" id="state">
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
                                                        <select class="form-control" id="country">
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
                                                        <select id="is_active" class="form-control">
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
                                                        <textarea class="form-control" rows="3" id="remarks">{{$house_scheme->remarks}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="button" class="btn btn-own" id="submit_button" onclick="updateHouseScheme()">{{ trans('app.forms.submit') }}</button>
                                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                            </div>
                                        </form>
                                        <!-- End House Form -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="strata" role="tabpanel">
                                <!-- strata Form -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>{{ trans('app.forms.detail') }}</h4>
                                        <form id="strata">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="strata_name" value="{{$strata->name}}">
                                                        <div id="strata_name_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.parliament') }}</label>
                                                        <select class="form-control" id="strata_parliament">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($parliament as $parliaments)
                                                            <option value="{{$parliaments->id}}" {{($strata->parliament == $parliaments->id ? " selected" : "")}}>{{$parliaments->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="strata_parliament_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.dun') }}</label>
                                                        <select class="form-control" id="strata_dun">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($dun as $duns)
                                                            <option value="{{$duns->id}}" {{($strata->dun == $duns->id ? " selected" : "")}}>{{$duns->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="strata_dun_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.park') }}</label>
                                                        <select class="form-control" id="strata_park">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($park as $parks)
                                                            <option value="{{$parks->id}}" {{($strata->park == $parks->id ? " selected" : "")}}>{{$parks->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="strata_park_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.address') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="strata_address1" value="{{$strata->address1}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="strata_address2" value="{{$strata->address2}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="strata_address3" value="{{$strata->address3}}">
                                                        <div id="strata_address_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.city') }}</label>
                                                        <select class="form-control" id="strata_city">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($city as $cities)
                                                            <option value="{{$cities->id}}" {{($strata->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="strata_city_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.postcode') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="strata_poscode" value="{{$strata->poscode}}">
                                                        <div id="strata_poscode_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.state') }}</label>
                                                        <select class="form-control" id="strata_state">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($state as $states)
                                                            <option value="{{$states->id}}" {{($strata->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="strata_state_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.country') }}</label>
                                                        <select class="form-control" id="strata_country">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($country as $countries)
                                                            <option value="{{$countries->id}}" {{($strata->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="starta_country_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.number_of_block') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_block') }}" id="strata_block_no" value="{{$strata->block_no}}">
                                                        <div id="strata_block_no_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.ownership_number') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.ownership_number') }}" id="strata_ownership_no" value="{{$strata->ownership_no}}">
                                                        <div id="strata_ownership_no_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.city_town_district') }}</label>
                                                        <select class="form-control" id="strata_town">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($city as $cities)
                                                            <option value="{{$cities->id}}" {{($strata->town == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="strata_town_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.area') }}</label>
                                                        <select class="form-control" id="strata_area">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($area as $areas)
                                                            <option value="{{$areas->id}}" {{($strata->area == $areas->id ? " selected" : "")}}>{{$areas->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="strata_area_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.total_land_area') }}</label>
                                                        <div class="form-inline">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.total_land_area') }}" id="strata_land_area" value="{{$strata->land_area}}">
                                                            <select class="form-control" id="strata_land_area_unit">
                                                                @foreach ($unit as $units)
                                                                <option value="{{$units->id}}" {{($strata->land_area_unit == $units->id ? " selected" : "")}}>{{$units->description}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div id="strata_land_area_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.lot_number') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.lot_number') }}" id="strata_lot_no" value="{{$strata->lot_no}}">
                                                        <div id="starta_lot_no_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.date_vp') }}</label>
                                                        <label class="input-group datepicker-only-init">
                                                            <input type="text" class="form-control" placeholder="{{ trans("app.forms.date") }}" id="strata_date" value="{{$strata->date}}"/>
                                                            <span class="input-group-addon">
                                                                <i class="icmn-calendar"></i>
                                                            </span>
                                                        </label>
                                                        <div id="strata_date_error" style="display:block;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.land_title') }}</label>
                                                        <select class="form-control" id="strata_land_title">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($land_title as $land_titles)
                                                            <option value="{{$land_titles->id}}" {{($strata->land_title == $land_titles->id ? " selected" : "")}}>{{$land_titles->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="starta_land_title_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.category') }}</label>
                                                        <select class="form-control" id="strata_category">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($category as $categories)
                                                            <option value="{{$categories->id}}" {{($strata->category == $categories->id ? " selected" : "")}}>{{$categories->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="starta_category_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.perimeter') }}</label>
                                                        <select class="form-control" id="strata_perimeter">
                                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                                            @foreach ($perimeter as $perimeters)
                                                            <option value="{{$perimeters->id}}" {{($strata->perimeter == $perimeters->id ? " selected" : "")}}>{{$perimeters->description}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="starta_perimeter_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form id="upload_strata_file" enctype="multipart/form-data" method="post" action="{{ url('uploadStrataFile') }}" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.upload_file') }}</label>
                                                        <br/>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                        <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
                                                        &nbsp;<input type="file" name="strata_file" id="strata_file" />
                                                        <div id="validation-errors_strata_file"></div>
                                                        @if ($strata->file_url != "")
                                                        <br/>
                                                        <a href="{{$strata->file_url}}" target="_blank"><button button type="button" class="btn btn-sm btn-own">{{ trans('app.forms.view_file') }}</button></a>
                                                        <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <hr/>
                                <form>
                                    @if (count($residential) <= 0)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="checkbox" name="residential" id="residential"/>
                                            <label><h4> {{ trans('app.forms.residential_block') }}</h4></label>
                                            <!-- residential Form -->
                                            <div id="residential_form" style="display:none">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" id="residential_unit_no">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" id="residential_maintenance_fee">
                                                                <select class="form-control" id="residential_maintenance_fee_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}">{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.sinking_fund') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="residential_sinking_fund">
                                                                <select class="form-control" id="residential_sinking_fund_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}">{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    @else
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="checkbox" name="residential" id="residential" {{($strata->is_residential == 1 ? " checked" : "")}}/>
                                            <label><h4> {{ trans('app.forms.residential_block') }}</h4></label>
                                            <!-- residential Form -->
                                            <div id="residential_form">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" id="residential_unit_no" value="{{$residential->unit_no}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" id="residential_maintenance_fee" value="{{$residential->maintenance_fee}}">
                                                                <select class="form-control" id="residential_maintenance_fee_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}" {{($residential->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.sinking_fund') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="residential_sinking_fund" value="{{$residential->sinking_fund}}">
                                                                <select class="form-control" id="residential_sinking_fund_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}" {{($residential->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    @endif
                                    @if (count($commercial) <= 0)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="checkbox" name="commercial" id="commercial"/>
                                            <label><h4> {{ trans('app.forms.commercial_block') }}</h4></label>
                                            <!-- residential Form -->
                                            <div id="commercial_form" style="display:none">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" id="commercial_unit_no">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" id="commercial_maintenance_fee">
                                                                <select class="form-control" id="commercial_maintenance_fee_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}">{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.sinking_fund') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="commercial_sinking_fund">
                                                                <select class="form-control" id="commercial_sinking_fund_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}">{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    @else
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="checkbox" name="commercial" id="commercial" {{($strata->is_commercial == 1 ? " checked" : "")}}/>
                                            <label><h4> {{ trans('app.forms.commercial_block') }}</h4></label>
                                            <!-- residential Form -->
                                            <div id="commercial_form">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" id="commercial_unit_no" value="{{$commercial->unit_no}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" id="commercial_maintenance_fee" value="{{$commercial->maintenance_fee}}">
                                                                <select class="form-control" id="commercial_maintenance_fee_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}" {{($commercial->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.sinking_fund') }}</label>
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="commercial_sinking_fund" value="{{$commercial->sinking_fund}}">
                                                                <select class="form-control" id="commercial_sinking_fund_option">
                                                                    @foreach ($unitoption as $unitoptions)
                                                                    <option value="{{$unitoptions->id}}" {{($commercial->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    @endif
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4>{{ trans('app.forms.facility') }}</h4>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.management_office') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="management_office" name="management_office" value="1" {{($facility->management_office == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="management_office" name="management_office" value="0" {{($facility->management_office == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.swimming_pool') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="swimming_pool" name="swimming_pool" value="1" {{($facility->swimming_pool == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="swimming_pool" name="swimming_pool" value="0" {{($facility->swimming_pool == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.surau') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="surau" name="surau" value="1" {{($facility->surau == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="surau" name="surau" value="0" {{($facility->surau == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.multipurpose_hall') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="multipurpose_hall" name="multipurpose_hall" value="1" {{($facility->multipurpose_hall == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="multipurpose_hall" name="multipurpose_hall" value="0" {{($facility->multipurpose_hall == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.gym') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="gym" name="gym" value="1" {{($facility->gym == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="gym" name="gym" value="0" {{($facility->gym == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.playground') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="playground" name="playground" value="1" {{($facility->playground == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="playground" name="playground" value="0" {{($facility->playground == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.guardhouse') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="guardhouse" name="guardhouse" value="1" {{($facility->guardhouse == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="guardhouse" name="guardhouse" value="0" {{($facility->guardhouse == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.kindergarten') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="kindergarten" name="kindergarten" value="1" {{($facility->kindergarten == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="kindergarten" name="kindergarten" value="0" {{($facility->kindergarten == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.open_space') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="open_space" name="open_space" value="1" {{($facility->open_space == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="open_space" name="open_space" value="0" {{($facility->open_space == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.lift') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="lift" name="lift" value="1" {{($facility->lift == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="lift" name="lift" value="0" {{($facility->lift == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.rubbish_room') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="rubbish_room" name="rubbish_room" value="1" {{($facility->rubbish_room == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="rubbish_room" name="rubbish_room" value="0" {{($facility->rubbish_room == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.gated') }}</label>
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="gated" name="gated" value="1" {{($facility->gated == 1 ? " checked" : "")}}>
                                                    {{ trans('app.forms.yes') }}
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="radio" id="gated" name="gated" value="0" {{($facility->gated == 0 ? " checked" : "")}}>
                                                    {{ trans('app.forms.no') }}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label class="form-control-label">{{ trans('app.forms.others') }}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" rows="3" id="others">{{$facility->others}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="hidden" id="strata_file_url" value="{{$strata->file_url}}"/>
                                        <button type="button" class="btn btn-own" id="submit_button" onclick="updateStrata()">{{ trans('app.forms.submit') }}</button>
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                    </div>
                                </form>
                            </div>
                            <!-- End Form -->
                            <div class="tab-pane" id="management" role="tabpanel">
                                <form id="management">
                                    @if (count($management_jmb) <= 0)
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="jmb_date_formed"/>
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
                                                            <select class="form-control" id="jmb_city">
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
                                                            <select class="form-control" id="jmb_state">
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
                                                            <select class="form-control" id="jmb_country">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="jmb_date_formed" value="{{$management_jmb->date_formed}}"/>
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="jmb_certificate_no" value="{{$management_jmb->certificate_no}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.name') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="jmb_name" value="{{$management_jmb->name}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.address') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="jmb_address1" value="{{$management_jmb->address1}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="jmb_address2" value="{{$management_jmb->address2}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="jmb_address3" value="{{$management_jmb->address3}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city') }}</label>
                                                            <select class="form-control" id="jmb_city">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($city as $cities)
                                                                <option value="{{$cities->id}}" {{($management_jmb->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.postcode') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="jmb_poscode" value="{{$management_jmb->poscode}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.state') }}</label>
                                                            <select class="form-control" id="jmb_state">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($state as $states)
                                                                <option value="{{$states->id}}" {{($management_jmb->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.country') }}</label>
                                                            <select class="form-control" id="jmb_country">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($country as $countries)
                                                                <option value="{{$countries->id}}" {{($management_jmb->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.phone_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="jmb_phone_no" value="{{$management_jmb->phone_no}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.fax_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="jmb_fax_no" value="{{$management_jmb->fax_no}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <hr/>
                                    @if (count($management_mc) <= 0)
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="mc_date_formed"/>
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
                                                            <label>{{ trans('app.forms.first_agm_date') }}</label>
                                                            <label class="input-group datepicker-only-init">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.first_agm_date') }}" id="mc_first_agm"/>
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
                                                            <select class="form-control" id="mc_city">
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
                                                            <select class="form-control" id="mc_state">
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
                                                            <select class="form-control" id="mc_country">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_formed') }}" id="mc_date_formed" value="{{$management_mc->date_formed}}"/>
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
                                                            <label>{{ trans('app.forms.first_agm_date') }}</label>
                                                            <label class="input-group datepicker-only-init">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.first_agm_date') }}" id="mc_first_agm" value="{{$management_mc->first_agm}}"/>
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="mc_name" value="{{$management_mc->name}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.address') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="mc_address1" value="{{$management_mc->address1}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="mc_address2" value="{{$management_mc->address2}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="mc_address3" value="{{$management_mc->address3}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city') }}</label>
                                                            <select class="form-control" id="mc_city">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($city as $cities)
                                                                <option value="{{$cities->id}}" {{($management_mc->country == $countries->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.postcode') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="mc_poscode" value="{{$management_mc->poscode}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.state') }}</label>
                                                            <select class="form-control" id="mc_state">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($state as $states)
                                                                <option value="{{$states->id}}" {{($management_mc->country == $countries->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.country') }}</label>
                                                            <select class="form-control" id="mc_country">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($country as $countries)
                                                                <option value="{{$countries->id}}" {{($management_mc->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.phone_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="mc_phone_no" value="{{$management_mc->phone_no}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.fax_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="mc_fax_no" value="{{$management_mc->fax_no}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <hr/>
                                    @if(count($management_agent) <= 0)
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
                                                            <select class="form-control" id="agent_selected_by">
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
                                                            <select class="form-control" id="agent_name">
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
                                                            <select class="form-control" id="agent_city">
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
                                                            <select class="form-control" id="agent_state">
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
                                                            <select class="form-control" id="agent_country">
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
                                                            <select class="form-control" id="agent_selected_by">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                <option value="developer" {{($management_agent->selected_by == "developer" ? " selected" : "")}}>{{ trans('app.forms.developer') }}</option>
                                                                <option value="cob" {{($management_agent->selected_by == "cob" ? " selected" : "")}}>{{ trans('app.forms.cob') }}</option>
                                                                <option value="jmb" {{($management_agent->selected_by == "jmb" ? " selected" : "")}}>{{ trans('app.forms.jmb') }}</option>
                                                                <option value="mc" {{($management_agent->selected_by == "mc" ? " selected" : "")}}>{{ trans('app.forms.mc') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.name') }}</label>
                                                            <select class="form-control" id="agent_name">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($agent as $agents)
                                                                <option value="{{$agents->id}}" {{($management_agent->agent == $agents->id ? " selected" : "")}}>{{$agents->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.address') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="agent_address1" value="{{$management_agent->address1}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="agent_address2" value="{{$management_agent->address2}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="agent_address3" value="{{$management_agent->address3}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city') }}</label>
                                                            <select class="form-control" id="agent_city">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($city as $cities)
                                                                <option value="{{$cities->id}}" {{($management_agent->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.postcode') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="agent_poscode" value="{{$management_agent->address1}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.state') }}</label>
                                                            <select class="form-control" id="agent_state">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($state as $states)
                                                                <option value="{{$states->id}}" {{($management_agent->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.country') }}</label>
                                                            <select class="form-control" id="agent_country">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($country as $countries)
                                                                <option value="{{$countries->id}}" {{($management_agent->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.phone_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="agent_phone_no" value="{{$management_agent->phone_no}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.fax_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="agent_fax_no" value="{{$management_agent->fax_no}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <hr/>
                                    @if (count($management_others) <= 0)
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
                                                            <select class="form-control" id="others_city">
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
                                                            <select class="form-control" id="others_state">
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
                                                            <select class="form-control" id="others_country">
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="others_name" value="{{$management_others->name}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.address') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="others_address1" value="{{$management_others->address1}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="others_address2" value="{{$management_others->address2}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="others_address3" value="{{$management_others->address3}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city') }}</label>
                                                            <select class="form-control" id="others_city">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($city as $cities)
                                                                <option value="{{$cities->id}}" {{($management_others->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.postcode') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="others_poscode" value="{{$management_others->poscode}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.state') }}</label>
                                                            <select class="form-control" id="others_state">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($state as $states)
                                                                <option value="{{$states->id}}" {{($management_others->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.country') }}</label>
                                                            <select class="form-control" id="others_country">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($country as $countries)
                                                                <option value="{{$countries->id}}" {{($management_others->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.phone_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="others_phone_no" value="{{$management_others->phone_no}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.fax_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="others_fax_no" value="{{$management_others->fax_no}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-actions">
                                        <button type="button" class="btn btn-own" id="submit_button" onclick="updateManagement()">{{ trans('app.forms.submit') }}</button>
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="monitoring" role="tabpanel">
                                <form id="monitoring">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4>{{ trans('app.forms.detail') }}</h4>
                                            <h6>1. {{ trans('app.forms.delivery_document_of_development_area') }}</h6>
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
                                    <hr/>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h6>2. {{ trans('app.forms.delivery_document_of_each_meeting') }}</h6>
                                            <div class="table-responsive">
                                                <button type="button" class="btn btn-own pull-right margin-bottom-25" onclick="addAGMDetails()">
                                                    {{ trans('app.forms.add') }}
                                                </button>
                                                <br/><br/>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="form-control-label">{{ trans('app.forms.financial_report_start_month') }}</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select class="form-control" id="commercial_sinking_fund_option">
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
                                                <table class="table table-hover nowrap" id="financial_report_list" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:15%;text-align: center !important;">{{ trans('app.forms.agm_date') }}</th>
                                                            <th style="width:20%;">{{ trans('app.forms.meeting') }}</th>
                                                            <th style="width:5%;"></th>
                                                            <th style="width:20%;">{{ trans('app.forms.copy_list') }}</th>
                                                            <th style="width:5%;"></th>
                                                            <th style="width:20%;">{{ trans('app.forms.financial_report') }}</th>
                                                            <th style="width:5%;"></th>
                                                            <th style="width:5%;">{{ trans('app.forms.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h6>{{ trans('app.forms.additional_info') }}</h6>
                                            <div class="table-responsive">
                                                <button type="button" class="btn btn-own pull-right margin-bottom-25" onclick="addAJKDetails()">
                                                    {{ trans('app.forms.add') }}
                                                </button>
                                                <br/><br/>
                                                <table class="table table-hover nowrap" id="ajk_details_list" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:30%;text-align: center !important;">{{ trans('app.forms.designation') }}</th>
                                                            <th style="width:15%;">{{ trans('app.forms.name') }}</th>
                                                            <th style="width:15%;">{{ trans('app.forms.email') }}</th>
                                                            <th style="width:20%;">{{ trans('app.forms.phone_number') }}</th>
                                                            <th style="width:5%;">{{ trans('app.forms.start_year') }}</th>
                                                            <th style="width:5%;">{{ trans('app.forms.end_year') }}</th>
                                                            <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                        </div>
                                        <div class="col-md-4">
                                            <textarea class="form-control" rows="3" id="monitoring_remarks">{{$monitoring->remarks}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" class="btn btn-own" id="submit_button" onclick="updateMonitoring()">{{ trans('app.forms.submit') }}</button>
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="others_tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>{{ trans('app.forms.detail') }}</h4>
                                        <!-- Form -->
                                        <form id="others">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.name') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="other_details_name" value="{{$other_details->name}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form id="upload_others_image" enctype="multipart/form-data" method="post" action="{{url('uploadOthersImage')}}" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.photo') }}</label>
                                                        <br />
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                        <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
                                                        &nbsp;<input type="file" name="image" id="image" />
<!--                                                        <br />
                                                        <small>Max image size: MB</small>-->
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($other_details->image_url != "")
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div id="others_image_output">
                                                            <img src='{{$other_details->image_url}}' style='width:50%; cursor: pointer;' onclick='window.open("{{$other_details->image_url}}")'/>
                                                            <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                                        </div>
                                                        <div id="validation-errors"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div id="others_image_output"></div>
                                                        <div id="validation-errors"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </form>
                                        <form id="others">
                                            @if ($other_details->latitude == "0")
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.latitude') }} </label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.latitude') }} " id="latitude">
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.latitude') }} </label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.latitude') }} " id="latitude" value="{{$other_details->latitude}}">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if ($other_details->longitude == "0")
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.longitude') }} </label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.longitude') }} " id="longitude">
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.longitude') }} </label>
                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.longitude') }} " id="longitude" value="{{$other_details->longitude}}">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if ($other_details->latitude != "0" && $other_details->longitude != "0")
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <a href="https://www.google.com.my/maps/preview?q={{$other_details->latitude}},{{$other_details->longitude}}" target="_blank">
                                                            <button type="button" class="btn btn-success">
                                                                <i class="fa fa-map-marker"> {{ trans('app.forms.view_map') }}</i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.description') }}</label>
                                                        <textarea class="form-control" rows="3" id="other_details_description" placeholder="{{ trans('app.forms.description') }}">{{$other_details->description}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <input type="hidden" id="others_image_url" value="{{$other_details->image_url}}"/>
                                                <button type="button" class="btn btn-own" id="submit_button" onclick="updateOtherDetails()">{{ trans('app.forms.submit') }}</button>
                                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="scoring" role="tabpanel">

                            </div>
                            <div class="tab-pane" id="buyer" role="tabpanel">

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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date"/>
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
                            <input type="radio" id="agm" name="agm" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="agm" name="agm" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.extra_general_meeting') }}</label>
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
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.meeting_minutes') }}</label>
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
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.jmc_spa_copy') }}</label>
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
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.identity_card_list') }}</label>
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
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.attendance_list') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="attendance_list" name="attendance_list" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="attendance_list" name="attendance_list" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.audited_financial_report') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="audited_financial_report" name="audited_financial_report" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="audited_financial_report" name="audited_financial_report" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.financial_audit_start_date') }}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="input-group datepicker-only-init">
                                <input type="text" class="form-control" placeholder="Start Date" id="audit_start"/>
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
                                <input type="text" class="form-control" placeholder="End Date" id="audit_end"/>
                                <span class="input-group-addon">
                                    <i class="icmn-calendar"></i>
                                </span>
                            </label>
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
                            <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
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
                            <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
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
                            <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
                            &nbsp;
                            <input type="file" name="letter_bankruptcy" id="letter_bankruptcy">
                            <div id="validation-errors_letter_bankruptcy"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <form>
                    <input type="hidden" id="audit_report_file_url"/>
                    <input type="hidden" id="letter_integrity_url"/>
                    <input type="hidden" id="letter_bankruptcy_url"/>
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
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.agm_date') }}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="input-group datepicker-only-init">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.agm_date') }}" id="agm_date_edit"/>
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
                            <input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" class="agm_edit" id="agm_edit" name="agm_edit" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.extra_general_meeting') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="egm_edit" name="egm_edit" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="egm_edit" name="egm_edit" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.meeting_minutes') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="minit_meeting_edit" name="minit_meeting_edit" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.jmc_spa_copy') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="jmc_copy_edit" name="jmc_copy_edit" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.identity_card_list') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="ic_list_edit" name="ic_list_edit" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="ic_list_edit" name="ic_list_edit" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.attendance_list') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="attendance_list_edit" name="attendance_list_edit" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.audited_financial_report') }}</label>
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="audited_financial_report_edit" name="audited_financial_report_edit" value="1">
                            {{ trans('app.forms.yes') }}
                        </div>
                        <div class="col-md-2">
                            <input type="radio" id="audited_financial_report_edit" name="audited_financial_report_edit" value="0">
                            {{ trans('app.forms.no') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.financial_audit_start_date') }}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="input-group datepicker-only-init">
                                <input type="text" class="form-control" placeholder="Start Date" id="audit_start_edit"/>
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
                                <input type="text" class="form-control" placeholder="End Date" id="audit_end_edit"/>
                                <span class="input-group-addon">
                                    <i class="icmn-calendar"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.financial_audit_report') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.financial_audit_report') }}" id="audit_report_edit"/>
                        </div>
                    </div>
                </form>
                <form id="upload_audit_report_file_edit" enctype="multipart/form-data" method="post" action="{{ url('uploadAuditReportFileEdit') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">&nbsp;</label>
                        </div>
                        <div class="col-md-6">
                            <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
                            &nbsp;
                            <input type="file" name="audit_report_file_edit" id="audit_report_file_edit">
                            <div id="validation-errors_audit_report_file_edit"></div>
                            <div id="view_audit_report_file_edit"></div>
                        </div>
                    </div>
                </form>
                <form id="upload_letter_integrity_edit" enctype="multipart/form-data" method="post" action="{{ url('uploadLetterIntegrityEdit') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.pledge_letter_of_integrity') }}</label>
                        </div>
                        <div class="col-md-6">
                            <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
                            &nbsp;
                            <input type="file" name="letter_integrity_edit" id="letter_integrity_edit">
                            <div id="validation-errors_letter_integrity_edit"></div>
                        </div>
                    </div>
                </form>
                <form id="upload_letter_bankruptcy_edit" enctype="multipart/form-data" method="post" action="{{ url('uploadLetterBankruptcyEdit') }}" autocomplete="off">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="form-control-label">{{ trans('app.forms.declaration_letter_of_non_bankruptcy') }}</label>
                        </div>
                        <div class="col-md-6">
                            <small><button type="button" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button></small>
                            &nbsp;
                            <input type="file" name="letter_bankruptcy_edit" id="letter_bankruptcy_edit">
                            <div id="validation-errors_letter_bankruptcy_edit"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <form>
                    <input type="hidden" id="agm_id_edit"/>
                    <input type="hidden" id="audit_report_file_url_edit"/>
                    <input type="hidden" id="letter_integrity_url_edit"/>
                    <input type="hidden" id="letter_bankruptcy_url_edit"/>
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
                            <label class="form-control-label">{{ trans('app.forms.designation') }}</label>
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
                            <label class="form-control-label">{{ trans('app.forms.name') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="ajk_name"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.email') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="ajk_email"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.phone_number') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="ajk_phone_no"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.start_year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.start_year') }}" id="ajk_start_year"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.end_year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.end_year') }}" id="ajk_end_year"/>
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
                            <label class="form-control-label">{{ trans('app.forms.designation') }}</label>
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
                            <label class="form-control-label">{{ trans('app.forms.name') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="ajk_name_edit"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.email') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="ajk_email_edit"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.phone_number') }}</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="ajk_phone_no_edit"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.start_year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.start_year') }}" id="ajk_start_year_edit"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ trans('app.forms.end_year') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.end_year') }}" id="ajk_end_year_edit"/>
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
 <script type="text/javascript">
    $(document).ready(function () {
        //upload
        var options = {
            beforeSubmit: showRequest,
            success: showResponse,
            dataType: 'json'
        };
        var options2 = {
            beforeSubmit: showRequest2,
            success: showResponse2,
            dataType: 'json'
        };
        //add
        var options3 = {
            beforeSubmit: showRequest3,
            success: showResponse3,
            dataType: 'json'
        };
        var options4 = {
            beforeSubmit: showRequest4,
            success: showResponse4,
            dataType: 'json'
        };
        var options5 = {
            beforeSubmit: showRequest5,
            success: showResponse5,
            dataType: 'json'
        };
        //edit
        var options6 = {
            beforeSubmit: showRequest6,
            success: showResponse6,
            dataType: 'json'
        };
        var options7 = {
            beforeSubmit: showRequest7,
            success: showResponse7,
            dataType: 'json'
        };
        var options8 = {
            beforeSubmit: showRequest8,
            success: showResponse8,
            dataType: 'json'
        };

        $('body').delegate('#image', 'change', function () {
            $('#upload_others_image').ajaxForm(options).submit();
        });
        $('body').delegate('#strata_file', 'change', function() {
            $('#upload_strata_file').ajaxForm(options2).submit();
        });
        //add
        $('body').delegate('#audit_report_file', 'change', function() {
            $('#upload_audit_report_file').ajaxForm(options3).submit();
        });
        $('body').delegate('#letter_integrity', 'change', function() {
            $('#upload_letter_integrity').ajaxForm(options4).submit();
        });
        $('body').delegate('#letter_bankruptcy', 'change', function() {
            $('#upload_letter_bankruptcy').ajaxForm(options5).submit();
        });
        //edit
        $('body').delegate('#audit_report_file_edit', 'change', function() {
            $('#upload_audit_report_file_edit').ajaxForm(options6).submit();
        });
        $('body').delegate('#letter_integrity_edit', 'change', function() {
            $('#upload_letter_integrity_edit').ajaxForm(options7).submit();
        });
        $('body').delegate('#letter_bankruptcy_edit', 'change', function() {
            $('#upload_letter_bankruptcy_edit').ajaxForm(options8).submit();
        });
    });

    function showRequest(formData, jqForm, options) {
        $("#validation-errors").css('display', 'none');
        return true;
    }
    function showResponse(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors").show();
            $("#others_image_output").css("color", "red");
        } else {
            $("#others_image_output").html("<img src='" + response.file + "' onclick='window.open(\"" + response.file + "\")' style='width:50%;cursor:pointer;'/>");
            $("#others_image_output").css('display', 'block');
            $("#others_image_url").val(response.file);
        }
    }

    //upload strata file
    function showRequest2(formData, jqForm, options) {
        $("#validation-errors_strata_file").hide().empty();
        return true;
    }
    function showResponse2(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function(index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_strata_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_strata_file").show();
            $("#strata_file").css("color", "red");
        } else {
            $("#validation-errors_strata_file").html("<i class='fa fa-check' style='color:green;'></i>");
            $("#validation-errors_strata_file").show();
            $("#strata_file").css("color", "green");
            $("#strata_file_url").val(response.file);
        }
    }

    //upload audit report file
    function showRequest3(formData, jqForm, options) {
        $("#validation-errors_audit_report_file").hide().empty();
        return true;
    }
    function showResponse3(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function(index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_audit_report_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_audit_report_file").show();
            $("#audit_report_file").css("color", "red");
        } else {
            $("#validation-errors_audit_report_file").html("<i class='fa fa-check' style='color:green;'></i>");
            $("#validation-errors_audit_report_file").show();
            $("#audit_report_file").css("color", "green");
            $("#audit_report_file_url").val(response.file);
        }
    }

    //upload letter integrity
    function showRequest4(formData, jqForm, options) {
        $("#validation-errors_letter_integrity").hide().empty();
        return true;
    }
    function showResponse4(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function(index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_integrity").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_integrity").show();
            $("#letter_integrity").css("color", "red");
        } else {
            $("#validation-errors_letter_integrity").html("<i class='fa fa-check' style='color:green;'></i>");
            $("#validation-errors_letter_integrity").show();
            $("#letter_integrity").css("color", "green");
            $("#letter_integrity_url").val(response.file);
        }
    }

    //upload letter bankruptcy
    function showRequest5(formData, jqForm, options) {
        $("#validation-errors_letter_bankruptcy").hide().empty();
        return true;
    }
    function showResponse5(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function(index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_bankruptcy").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_bankruptcy").show();
            $("#letter_bankruptcy").css("color", "red");
        } else {
            $("#validation-errors_letter_bankruptcy").html("<i class='fa fa-check' style='color:green;'></i>");
            $("#validation-errors_letter_bankruptcy").show();
            $("#letter_bankruptcy").css("color", "green");
            $("#letter_bankruptcy_url").val(response.file);
        }
    }

    //upload audit report file edit
    function showRequest6(formData, jqForm, options) {
        $("#validation-errors_audit_report_file_edit").hide().empty();
        return true;
    }
    function showResponse6(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function(index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_audit_report_file_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_audit_report_file_edit").show();
            $("#audit_report_file_edit").css("color", "red");
        } else {
            $("#validation-errors_audit_report_file_edit").html("<i class='fa fa-check' style='color:green;'></i>");
            $("#validation-errors_audit_report_file_edit").show();
            $("#audit_report_file_edit").css("color", "green");
            $("#audit_report_file_url_edit").val(response.file);
        }
    }

    //upload letter integrity edit
    function showRequest7(formData, jqForm, options) {
        $("#validation-errors_letter_integrity_edit").hide().empty();
        return true;
    }
    function showResponse7(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function(index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_integrity_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_integrity_edit").show();
            $("#letter_integrity_edit").css("color", "red");
        } else {
            $("#validation-errors_letter_integrity_edit").html("<i class='fa fa-check' style='color:green;'></i>");
            $("#validation-errors_letter_integrity_edit").show();
            $("#letter_integrity_edit").css("color", "green");
            $("#letter_integrity_url_edit").val(response.file);
        }
    }

    //upload letter bankruptcy edit
    function showRequest8(formData, jqForm, options) {
        $("#validation-errors_letter_bankruptcy_edit").hide().empty();
        return true;
    }
    function showResponse8(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function(index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors_letter_bankruptcy_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_letter_bankruptcy_edit").show();
            $("#letter_bankruptcy_edit").css("color", "red");
        } else {
            $("#validation-errors_letter_bankruptcy_edit").html("<i class='fa fa-check' style='color:green;'></i>");
            $("#validation-errors_letter_bankruptcy_edit").show();
            $("#letter_bankruptcy_edit").css("color", "green");
            $("#letter_bankruptcy_url_edit").val(response.file);
        }
    }
</script>

<script>
    $(document).on( "click", '.edit_agm',function(e) {
        var agm_id = $(this).data('agm_id');
        var agm_date = $(this).data('agm_date');
        var agm = $(this).data('agm');
        var egm = $(this).data('egm');
        var minit_meeting = $(this).data('minit_meeting');
        var letter_integrity_url = $(this).data('letter_integrity_url');
        var letter_bankruptcy_url = $(this).data('letter_bankruptcy_url');
        var jmc_spa = $(this).data('jmc_spa');
        var identity_card = $(this).data('identity_card');
        var attendance = $(this).data('attendance');
        var financial_report = $(this).data('financial_report');
        var audit_start_date = $(this).data('audit_start_date');
        var audit_end_date = $(this).data('audit_end_date');
        var audit_report = $(this).data('audit_report');
        var audit_report_url = $(this).data('audit_report_url');

        $("#agm_id_edit").val(agm_id);
        $("#agm_date_edit").val(agm_date);
        $("#agm_edit").val(agm);
        $("#egm_edit").val(egm);
        $("#minit_meeting_edit").val(minit_meeting);
        $("#jmc_copy_edit").val(jmc_spa);
        $("#ic_list_edit").val(identity_card);
        $("#attendance_list_edit").val(attendance);
        $("#audited_financial_report_edit").val(financial_report);
        $("#audit_start_edit").val(audit_start_date);
        $("#audit_end_edit").val(audit_end_date);
        $("#audit_report_edit").val(audit_report);
        $("#letter_integrity_url_edit").val(letter_integrity_url);
        $("#letter_bankruptcy_url_edit").val(letter_bankruptcy_url);
    });

    $(document).on( "click", '.edit_ajk',function(e) {
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

    function updateOtherDetails(){
        $("#loading").css("display", "inline-block");

        var other_details_name = $("#other_details_name").val(),
                others_image_url = $("#others_image_url").val(),
                latitude = $("#latitude").val(),
                longitude = $("#longitude").val(),
                other_details_description = $("#other_details_description").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateOtherDetails') }}",
                type: "POST",
                data: {
                    other_details_name: other_details_name,
                    others_image_url: others_image_url,
                    latitude: latitude,
                    longitude: longitude,
                    other_details_description: other_details_description,
                    id: '{{$other_details->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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

    function updateMonitoring(){
        $("#loading").css("display", "inline-block");

        if (document.getElementById('precalculate_plan').checked){
            var precalculate_plan = 1;
        } else {
            var precalculate_plan = 0;
        }
        if (document.getElementById('buyer_registration').checked){
            var buyer_registration = 1;
        } else {
            var buyer_registration = 0;
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
                    id: '{{$monitoring->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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

    function addAGMDetails() {
        $("#add_agm_details").modal("show");
    }
    function editAGMDetails() {
        $("#edit_agm_details").modal("show");
    }

    function addAGMDetail() {
        $("#loading").css("display", "inline-block");

        if (document.getElementById('agm').checked){
            var agm = $("#agm").val();
        } else {
            var agm = 0;
        }
        if (document.getElementById('egm').checked){
            var egm = $("#egm").val();
        } else {
            var egm = 0;
        }
        if (document.getElementById('minit_meeting').checked){
            var minit_meeting = $("#minit_meeting").val();
        } else {
            var minit_meeting = 0;
        }
        if (document.getElementById('jmc_copy').checked){
            var jmc_copy = $("#jmc_copy").val();
        } else {
            var jmc_copy = 0;
        }
        if (document.getElementById('ic_list').checked){
            var ic_list = $("#ic_list").val();
        } else {
            var ic_list = 0;
        }
        if (document.getElementById('attendance_list').checked){
            var attendance_list = $("#attendance_list").val();
        } else {
            var attendance_list = 0;
        }
        if (document.getElementById('audited_financial_report').checked){
            var audited_financial_report = $("#audited_financial_report").val();
        } else {
            var audited_financial_report = 0;
        }

        var agm_date = $("#agm_date").val(),
                audit_report = $("#audit_report").val(),
                audit_start = $("#audit_start").val(),
                audit_end = $("#audit_end").val(),
                audit_report_file_url = $("#audit_report_file_url").val(),
                letter_integrity_url = $("#letter_integrity_url").val(),
                letter_bankruptcy_url = $("#letter_bankruptcy_url").val();

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
                    file_id: '{{$file->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#add_agm_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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
        $("#loading").css("display", "inline-block");

        if (document.getElementById('agm_edit').checked = true){
            var agm = $("#agm_edit").val();
        } else {
            var agm = $("#agm_edit").val();
        }
        if (document.getElementById('egm_edit').checked = true){
            var egm = $("#egm_edit").val();
        } else {
            var egm = $("#egm_edit").val();
        }
        if (document.getElementById('minit_meeting_edit').checked){
            var minit_meeting = $("#minit_meeting_edit").val();
        } else {
            var minit_meeting = $("#minit_meeting_edit").val();
        }
        if (document.getElementById('jmc_copy_edit').checked){
            var jmc_copy = $("#jmc_copy_edit").val();
        } else {
            var jmc_copy = $("#minit_meeting_edit").val();
        }
        if (document.getElementById('ic_list_edit').checked){
            var ic_list = $("#ic_list_edit").val();
        } else {
            var ic_list = $("#minit_meeting_edit").val();
        }
        if (document.getElementById('attendance_list_edit').checked){
            var attendance_list = $("#attendance_list_edit").val();
        } else {
            var attendance_list = 0;
        }
        if (document.getElementById('audited_financial_report_edit').checked){
            var audited_financial_report = $("#audited_financial_report_edit").val();
        } else {
            var audited_financial_report = 0;
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
                    id: agm_id_edit
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#edit_agm_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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

    function addAJKDetails() {
        $("#add_ajk_details").modal("show");
    }
    function editAJKDetails() {
        $("#edit_ajk_details").modal("show");
    }

    function addAJKDetail() {
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
                    file_id: '{{$file->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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
                    ajk_id_edit: ajk_id_edit
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $('#edit_ajk_details').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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

    function deleteAGMDetails (id) {
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
                url: "{{ URL::action('AdminController@deleteAGMDetails') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.deleted_successfully") }}</p>',
                        },{
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

    function deleteAJKDetails (id) {
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
                url: "{{ URL::action('AdminController@deleteAJKDetails') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.deleted_successfully") }}</p>',
                        },{
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
</script>

<script>
    $(function(){
        $('#strata_date').datetimepicker({
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
        $('#jmb_date_formed').datetimepicker({
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
        $('#mc_date_formed').datetimepicker({
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
        $('#mc_first_agm').datetimepicker({
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
        $('#agm_date_edit').datetimepicker({
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
        $('#audit_start').datetimepicker({
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
        $('#audit_start_edit').datetimepicker({
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
        $('#audit_end').datetimepicker({
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
        $('#audit_end_edit').datetimepicker({
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
    });

    $(document).ready(function () {
        $('#financial_report_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAGM', $file->id)}}",
            "order": [[ 0, "desc" ]]
        });
        $('#ajk_details_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getAJKDetails', $file->id)}}",
            "order": [[ 0, "desc" ]]
        });
        $('#residential').click(function() {
            if ($(this).is(':checked')) {
                $("#residential_form").fadeIn(500);
            } else {
                $("#residential_form").fadeOut(0);
            }
        });
        $('#commercial').click(function() {
            if ($(this).is(':checked')) {
                $("#commercial_form").fadeIn(500);
            } else {
                $("#commercial_form").fadeOut(0);
            }
        });
        $('#is_jmb').click(function() {
            if ($(this).is(':checked')) {
                $("#jmb_form").fadeIn(500);
            } else {
                $("#jmb_form").fadeOut(0);
            }
        });
        $('#is_mc').click(function() {
            if ($(this).is(':checked')) {
                $("#mc_form").fadeIn(500);
            } else {
                $("#mc_form").fadeOut(0);
            }
        });
        $('#is_agent').click(function() {
            if ($(this).is(':checked')) {
                $("#agent_form").fadeIn(500);
            } else {
                $("#agent_form").fadeOut(0);
            }
        });
        $('#is_others').click(function() {
            if ($(this).is(':checked')) {
                $("#other_form").fadeIn(500);
            } else {
                $("#other_form").fadeOut(0);
            }
        });
    });
</script>

<script>
    function updateHouseScheme() {
        $("#loading").css("display", "inline-block");

        var name = $("#name").val(),
                developer = $("#developer").val(),
                address1 = $("#address1").val(),
                address2 = $("#address2").val(),
                address3 = $("#address3").val(),
                city = $("#city").val(),
                poscode = $("#poscode").val(),
                state = $("#state").val(),
                country = $("#country").val(),
                phone_no = $("#phone_no").val(),
                fax_no = $("#fax_no").val(),
                remarks = $("#remarks").val(),
                is_active = $("#is_active").val();

        var error = 0;

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

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateHouseScheme') }}",
                type: "POST",
                data: {
                    name: name,
                    developer: developer,
                    address1: address1,
                    address2: address2,
                    address3: address3,
                    city: city,
                    poscode: poscode,
                    state: state,
                    country: country,
                    phone_no: phone_no,
                    fax_no: fax_no,
                    remarks: remarks,
                    is_active: is_active,
                    id: '{{$house_scheme->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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

    function updateStrata() {
        $("#loading").css("display", "inline-block");

        var strata_name = $("#strata_name").val(),
                strata_parliament = $("#strata_parliament").val(),
                strata_dun = $("#strata_dun").val(),
                strata_park = $("#strata_park").val(),
                strata_address1 = $("#strata_address1").val(),
                strata_address2 = $("#strata_address2").val(),
                strata_address3 = $("#strata_address3").val(),
                strata_city = $("#strata_city").val(),
                strata_poscode = $("#strata_poscode").val(),
                strata_state = $("#strata_state").val(),
                strata_country = $("#strata_country").val(),
                strata_block_no = $("#strata_block_no").val(),
                strata_ownership_no = $("#strata_ownership_no").val(),
                strata_town = $("#strata_town").val(),
                strata_land_area = $("#strata_land_area").val(),
                strata_land_area_unit = $("#strata_land_area_unit").val(),
                strata_lot_no = $("#strata_lot_no").val(),
                strata_date = $("#strata_date").val(),
                strata_land_title = $("#strata_land_title").val(),
                strata_category = $("#strata_category").val(),
                strata_perimeter = $("#strata_perimeter").val(),
                strata_area = $("#strata_area").val(),
                strata_file_url = $("#strata_file_url").val(),
                //residential
                residential_unit_no = $("#residential_unit_no").val(),
                residential_maintenance_fee = $("#residential_maintenance_fee").val(),
                residential_maintenance_fee_option = $("#residential_maintenance_fee_option").val(),
                residential_sinking_fund = $("#residential_sinking_fund").val(),
                residential_sinking_fund_option = $("#residential_sinking_fund_option").val(),
                //commercial
                commercial_unit_no = $("#commercial_unit_no").val(),
                commercial_maintenance_fee = $("#commercial_maintenance_fee").val(),
                commercial_maintenance_fee_option = $("#commercial_maintenance_fee_option").val(),
                commercial_sinking_fund = $("#commercial_sinking_fund").val(),
                commercial_sinking_fund_option = $("#commercial_sinking_fund_option").val(),
                //facility
                management_office = $("#management_office:checked").val(),
                swimming_pool = $("#swimming_pool:checked").val(),
                surau = $("#surau:checked").val(),
                gym = $("#gym:checked").val(),
                playground = $("#playground:checked").val(),
                multipurpose_hall = $("#multipurpose_hall:checked").val(),
                guardhouse = $("#guardhouse:checked").val(),
                kindergarten = $("#kindergarten:checked").val(),
                open_space = $("#open_space:checked").val(),
                lift = $("#lift:checked").val(),
                rubbish_room = $("#rubbish_room:checked").val(),
                gated = $("#gated:checked").val(),
                others = $("#others").val();


        if (document.getElementById('residential').checked){
            var is_residential = 1;
        } else {
            var is_residential = 0;
        }
        if (document.getElementById('commercial').checked){
            var is_commercial = 1;
        } else {
            var is_commercial = 0;
        }

        var error = 0;

        if (strata_name.trim() == "") {
            $("#strata_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#strata_name_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateStrata') }}",
                type: "POST",
                data: {
                    file_id: '{{$file->id}}',
                    strata_name: strata_name,
                    strata_parliament: strata_parliament,
                    strata_dun: strata_dun,
                    strata_park: strata_park,
                    strata_address1: strata_address1,
                    strata_address2: strata_address2,
                    strata_address3: strata_address3,
                    strata_city: strata_city,
                    strata_poscode: strata_poscode,
                    strata_state: strata_state,
                    strata_country: strata_country,
                    strata_block_no: strata_block_no,
                    strata_ownership_no: strata_ownership_no,
                    strata_town: strata_town,
                    strata_land_area: strata_land_area,
                    strata_land_area_unit: strata_land_area_unit,
                    strata_lot_no: strata_lot_no,
                    strata_date: strata_date,
                    strata_land_title: strata_land_title,
                    strata_category: strata_category,
                    strata_perimeter: strata_perimeter,
                    strata_area: strata_area,
                    is_residential: is_residential,
                    is_commercial: is_commercial,
                    strata_file_url: strata_file_url,
                    strata_id: '{{$strata->id}}',
                    //residential
                    residential_unit_no: residential_unit_no,
                    residential_maintenance_fee: residential_maintenance_fee,
                    residential_maintenance_fee_option: residential_maintenance_fee_option,
                    residential_sinking_fund: residential_sinking_fund,
                    residential_sinking_fund_option: residential_sinking_fund_option,
                    //commercial
                    commercial_unit_no: commercial_unit_no,
                    commercial_maintenance_fee: commercial_maintenance_fee,
                    commercial_maintenance_fee_option: commercial_maintenance_fee_option,
                    commercial_sinking_fund: commercial_sinking_fund,
                    commercial_sinking_fund_option: commercial_sinking_fund_option,
                    //facility
                    management_office: management_office,
                    swimming_pool: swimming_pool,
                    surau: surau,
                    multipurpose_hall: multipurpose_hall,
                    gym: gym,
                    playground: playground,
                    guardhouse: guardhouse,
                    kindergarten: kindergarten,
                    open_space: open_space,
                    lift: lift,
                    rubbish_room: rubbish_room,
                    gated: gated,
                    others: others,
                    facility_id: '{{$facility->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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
</script>

<script>
    function updateManagement() {
        $("#loading").css("display", "inline-block");

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
                jmb_fax_no = $("#jmb_fax_no").val();

        //mc
        var mc_date_formed = $("#mc_date_formed").val(),
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
                mc_fax_no = $("#mc_fax_no").val();

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
                agent_fax_no = $("#agent_fax_no").val();

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
                others_fax_no = $("#others_fax_no").val();

        if (document.getElementById('is_jmb').checked){
            var is_jmb = 1;
        } else {
            var is_jmb = 0;
        }
        if (document.getElementById('is_mc').checked){
            var is_mc = 1;
        } else {
            var is_mc = 0;
        }
        if (document.getElementById('is_agent').checked){
            var is_agent = 1;
        } else {
            var is_agent = 0;
        }
        if (document.getElementById('is_others').checked){
            var is_others = 1;
        } else {
            var is_others = 0;
        }

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateManagement') }}",
                type: "POST",
                data: {
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
                    //mc
                    is_mc: is_mc,
                    mc_date_formed: mc_date_formed,
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
                    //id
                    management_id: '{{$management->id}}',
                    file_id: '{{$file->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
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
</script>

<script type="text/javascript">
    function invokeMeMaster() {
        var chkPostBack = '<%= Page.IsPostBack ? "true" : "false" %>';
        if (chkPostBack == 'false') {
            $(function () {
                // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    // save the latest tab; use cookies if you like 'em better:
                    localStorage.setItem('lastTab', $(this).attr('href'));
                });
            });
        } else {
            $(function () {
                // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    // save the latest tab; use cookies if you like 'em better:
                    localStorage.setItem('lastTab', $(this).attr('href'));
                });
                // go to the latest tab, if it exists:
                var lastTab = localStorage.getItem('lastTab');
                if (lastTab) {
                    $('[href="' + lastTab + '"]').tab('show');
                }
            });
        }
    }
    window.onload = function() { invokeMeMaster(); };
</script>


<!-- End Page Scripts-->

@stop
