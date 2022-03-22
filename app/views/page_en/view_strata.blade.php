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
                            <div class="tab-pane active" id="strata" role="tabpanel">
                                <!-- strata Form -->
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <h4>{{ trans('app.forms.detail') }}</h4>
                                            <form id="strata">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.name') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="strata_name" value="{{$strata->name}}" readonly="">
                                                            <div id="strata_name_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.parliament') }}</label>
                                                            <select class="form-control" id="strata_parliament" onchange="findDUN()" disabled="">
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
                                                            <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.dun') }}</label>
                                                            <select class="form-control" id="strata_dun" onchange="findPark()" disabled="">
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
                                                            <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.park') }}</label>
                                                            <select class="form-control" id="strata_park" disabled="">
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="strata_address1" value="{{$strata->address1}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="strata_address2" value="{{$strata->address2}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="strata_address3" value="{{$strata->address3}}" readonly="">
                                                            <div id="strata_address_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city') }}</label>
                                                            <select class="form-control" id="strata_city" disabled="">
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="strata_poscode" value="{{$strata->poscode}}" readonly="">
                                                            <div id="strata_poscode_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.state') }}</label>
                                                            <select class="form-control" id="strata_state" disabled="">
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
                                                            <select class="form-control" id="strata_country" disabled="">
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_block') }}" id="strata_block_no" value="{{$strata->block_no}}" readonly="">
                                                            <div id="strata_block_no_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.ownership_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.ownership_number') }}" id="strata_ownership_no" value="{{$strata->ownership_no}}" readonly="">
                                                            <div id="strata_ownership_no_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city_town_district') }}</label>
                                                            <select class="form-control" id="strata_town" disabled="">
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
                                                            <select class="form-control" id="strata_area" disabled="">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.total_land_area') }}" id="strata_land_area" value="{{$strata->land_area}}" readonly="">
                                                                <select class="form-control" id="strata_land_area_unit" disabled="">
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.lot_number') }}" id="strata_lot_no" value="{{$strata->lot_no}}" readonly="">
                                                            <div id="starta_lot_no_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.date_vp') }}</label>
                                                            <label class="input-group datepicker-only-init">
                                                                <input type="text" class="form-control" placeholder="{{ trans("app.forms.date") }}" id="strata_date" value="{{$strata->date}}" readonly="">
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
                                                            <select class="form-control" id="strata_land_title" disabled="">
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
                                                            <select class="form-control" id="strata_category" disabled="">
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
                                                            <select class="form-control" id="strata_perimeter" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($perimeter as $perimeters)
                                                                <option value="{{$perimeters->id}}" {{($strata->perimeter == $perimeters->id ? " selected" : "")}}>{{$perimeters->description_en}}</option>
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
                                                            <button type="button" id="clear_strata_file" class="btn btn-xs btn-danger" onclick="clearStrataFile()" style="display: none;"><i class="fa fa-times"></i></button>
                                                            <!--&nbsp;<input type="file" name="strata_file" id="strata_file" />-->
                                                            <div id="validation-errors_strata_file"></div>
                                                            @if ($strata->file_url != "")
                                                            <br/>
                                                            <a href="{{asset($strata->file_url)}}" target="_blank"><button button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> {{ trans("app.forms.download") }}</button></a>

                                                            @else
                                                            <span>{{ trans('app.forms.no_uploaded_file') }}</span>
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
                                                <input type="checkbox" name="residential" id="residential" disabled/>
                                                <label><h4> {{ trans('app.forms.residential_block') }}</h4></label>
                                                <!-- residential Form -->
                                                <div id="residential_form" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" id="residential_unit_no" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                                <div class="form-inline">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" id="residential_maintenance_fee" readonly="">
                                                                    <select class="form-control" id="residential_maintenance_fee_option" disabled="">
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
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="residential_sinking_fund" readonly="">
                                                                    <select class="form-control" id="residential_sinking_fund_option" disabled="">
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
                                                <input type="checkbox" name="residential" id="residential" {{($strata->is_residential == 1 ? " checked" : "")}} disabled>
                                                <label><h4> {{ trans('app.forms.residential_block') }}</h4></label>
                                                <!-- residential Form -->
                                                <div id="residential_form">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" id="residential_unit_no" value="{{$residential->unit_no}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                                <div class="form-inline">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" id="residential_maintenance_fee" value="{{$residential->maintenance_fee}}" readonly="">
                                                                    <select class="form-control" id="residential_maintenance_fee_option" disabled="">
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
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="residential_sinking_fund" value="{{$residential->sinking_fund}}" readonly="">
                                                                    <select class="form-control" id="residential_sinking_fund_option" disabled="">
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
                                                <input type="checkbox" name="commercial" id="commercial" disabled/>
                                                <label><h4> {{ trans('app.forms.commercial_block') }}</h4></label>
                                                <!-- residential Form -->
                                                <div id="commercial_form" style="display:none">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" id="commercial_unit_no" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                                <div class="form-inline">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" id="commercial_maintenance_fee" readonly="">
                                                                    <select class="form-control" id="commercial_maintenance_fee_option" disabled="">
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
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="commercial_sinking_fund" readonly="">
                                                                    <select class="form-control" id="commercial_sinking_fund_option" disabled="">
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
                                                <input type="checkbox" name="commercial" id="commercial" {{($strata->is_commercial == 1 ? " checked" : "")}} disabled>
                                                <label><h4> {{ trans('app.forms.commercial_block') }}</h4></label>
                                                <!-- residential Form -->
                                                <div id="commercial_form">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" id="commercial_unit_no" value="{{$commercial->unit_no}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                                <div class="form-inline">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" id="commercial_maintenance_fee" value="{{$commercial->maintenance_fee}}" readonly="">
                                                                    <select class="form-control" id="commercial_maintenance_fee_option" disabled="">
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
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="commercial_sinking_fund" value="{{$commercial->sinking_fund}}" readonly="">
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
                                                        <input type="radio" id="management_office" name="management_office" value="1" {{($facility->management_office == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="management_office" name="management_office" value="0" {{($facility->management_office == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.swimming_pool') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="swimming_pool" name="swimming_pool" value="1" {{($facility->swimming_pool == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="swimming_pool" name="swimming_pool" value="0" {{($facility->swimming_pool == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.surau') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="surau" name="surau" value="1" {{($facility->surau == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="surau" name="surau" value="0" {{($facility->surau == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.multipurpose_hall') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="multipurpose_hall" name="multipurpose_hall" value="1" {{($facility->multipurpose_hall == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="multipurpose_hall" name="multipurpose_hall" value="0" {{($facility->multipurpose_hall == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.gym') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="gym" name="gym" value="1" {{($facility->gym == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="gym" name="gym" value="0" {{($facility->gym == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.playground') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="playground" name="playground" value="1" {{($facility->playground == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="playground" name="playground" value="0" {{($facility->playground == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.guardhouse') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="guardhouse" name="guardhouse" value="1" {{($facility->guardhouse == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="guardhouse" name="guardhouse" value="0" {{($facility->guardhouse == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.kindergarten') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="kindergarten" name="kindergarten" value="1" {{($facility->kindergarten == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="kindergarten" name="kindergarten" value="0" {{($facility->kindergarten == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.open_space') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="open_space" name="open_space" value="1" {{($facility->open_space == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="open_space" name="open_space" value="0" {{($facility->open_space == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.lift') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="lift" name="lift" value="1" {{($facility->lift == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="lift" name="lift" value="0" {{($facility->lift == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.rubbish_room') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="rubbish_room" name="rubbish_room" value="1" {{($facility->rubbish_room == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="rubbish_room" name="rubbish_room" value="0" {{($facility->rubbish_room == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.gated') }}</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="gated" name="gated" value="1" {{($facility->gated == 1 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.yes') }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="radio" id="gated" name="gated" value="0" {{($facility->gated == 0 ? " checked" : "")}} disabled>
                                                        {{ trans('app.forms.no') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="form-control-label">{{ trans('app.forms.others') }}</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <textarea class="form-control" rows="3" id="others" readonly="">{{$facility->others}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </section>
                            </div>
                            <!-- End Form -->
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
    $(document).ready(function () {
        //upload
        var options = {
            beforeSubmit: showRequest,
            success: showResponse,
            dataType: 'json'
        };

        $('body').delegate('#strata_file', 'change', function () {
            $('#upload_strata_file').ajaxForm(options).submit();
        });
    });

    //upload strata file
    function showRequest(formData, jqForm, options) {
        $("#validation-errors_strata_file").hide().empty();
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
                    $("#validation-errors_strata_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors_strata_file").show();
            $("#strata_file").css("color", "red");
        } else {
            $("#clear_strata_file").show();
            $("#validation-errors_strata_file").html("<i class='fa fa-check' id='check_strata_file' style='color:green;'></i>");
            $("#validation-errors_strata_file").show();
            $("#strata_file").css("color", "green");
            $("#strata_file_url").val(response.file);
        }
    }

    $(function () {
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
        $("[data-toggle=tooltip]").tooltip();
    });

    $(document).ready(function () {
        $('#residential').click(function () {
            if ($(this).is(':checked')) {
                $("#residential_form").fadeIn(500);
            } else {
                $("#residential_form").fadeOut(0);
            }
        });
        $('#commercial').click(function () {
            if ($(this).is(':checked')) {
                $("#commercial_form").fadeIn(500);
            } else {
                $("#commercial_form").fadeOut(0);
            }
        });
    });

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

        var is_commercial;
        var is_residential;

        if (document.getElementById('residential').checked) {
            is_residential = 1;
        } else {
            is_residential = 0;
        }
        if (document.getElementById('commercial').checked) {
            is_commercial = 1;
        } else {
            is_commercial = 0;
        }

        var error = 0;

        if (strata_name.trim() == "") {
            $("#strata_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#strata_name_error").css("display", "block");
            error = 1;
        }
        if (strata_parliament.trim() == "") {
            $("#strata_parliament_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Parliament"]) }}</span>');
            $("#strata_parliament_error").css("display", "block");
            error = 1;
        }
        if (strata_dun.trim() == "") {
            $("#strata_dun_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"DUN"]) }}</span>');
            $("#strata_dun_error").css("display", "block");
            error = 1;
        }
        if (strata_park.trim() == "") {
            $("#strata_park_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Park"]) }}</span>');
            $("#strata_park_error").css("display", "block");
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
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        window.location = "{{URL::action('AdminController@management', $file->id)}}";
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function findDUN() {
        $.ajax({
            url: "{{URL::action('AdminController@findDUN')}}",
            type: "POST",
            data: {
                parliament_id: $("#strata_parliament").val()
            },
            success: function (data) {
                $("#strata_dun").html(data);
                $("#strata_park").html("<option value=''>{{ trans('app.forms.please_select') }}</option>");
            }
        });
    }

    function findPark() {
        $.ajax({
            url: "{{URL::action('AdminController@findPark')}}",
            type: "POST",
            data: {
                dun_id: $("#strata_dun").val()
            },
            success: function (data) {
                $("#strata_park").html(data);
            }
        });
    }

    function deleteStrataFile(id) {
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
                url: "{{ URL::action('AdminController@deleteStrataFile') }}",
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

    function clearStrataFile() {
        $("#strata_file").val("");
        $("#clear_strata_file").hide();
        $("#strata_file").css("color", "grey");
        $("#check_strata_file").hide();
    }
</script>

<!-- End Page Scripts-->

@stop
