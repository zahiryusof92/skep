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
                            <li class="nav-item">
                                <a class="nav-link active custom-tab">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            @if ($file->management->draft)
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('DraftController@management', \Helper\Helper::encode($file->id))}}">{{ trans('app.forms.management') }}</a>
                            </li>
                            @endif
                            @if ($file->other->draft)
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('DraftController@others', \Helper\Helper::encode($file->id))}}">{{ trans('app.forms.others') }}</a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="strata" role="tabpanel">                                    

                                <!-- Start Strata Form -->
                                @if ($strata->draft)                                
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
                                            <h4>{{ trans('app.forms.detail') }} <span class="label label-danger">{{ trans('app.forms.draft') }}</span></h4>
                                            <form id="strata_draft">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.strata_title') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->title, 'new_field' => $strata->draft->title])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="1" {{($strata->draft->title == "1" ? " selected" : "")}}>{{ trans("app.forms.yes") }}</option>
                                                                <option value="0" {{($strata->draft->title == "0" ? " selected" : "")}}>{{ trans("app.forms.no") }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.name') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->name, 'new_field' => $strata->draft->name])
                                                            <input type="text" class="form-control" value="{{$strata->draft->name}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.parliament') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->parliament, 'new_field' => $strata->draft->parliament])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($parliament as $parliaments)
                                                                <option value="{{$parliaments->id}}" {{($strata->draft->parliament == $parliaments->id ? " selected" : "")}}>{{$parliaments->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.dun') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->dun, 'new_field' => $strata->draft->dun])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($dun as $duns)
                                                                <option value="{{$duns->id}}" {{($strata->draft->dun == $duns->id ? " selected" : "")}}>{{$duns->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.park') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->park, 'new_field' => $strata->draft->park])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($park as $parks)
                                                                <option value="{{$parks->id}}" {{($strata->draft->park == $parks->id ? " selected" : "")}}>{{$parks->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.address') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->address1, 'new_field' => $strata->draft->address1])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address1') }}" value="{{$strata->draft->address1}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$strata->draft->address2}}" readonly="">
                                                        </div>
                                                    </div>
                                                    @include('components.is_changed', ['old_field' => $strata->address2, 'new_field' => $strata->draft->address2, 'class' => 'margin-top-5'])
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$strata->draft->address3}}" readonly="">
                                                        </div>
                                                    </div>
                                                    @include('components.is_changed', ['old_field' => $strata->address3, 'new_field' => $strata->draft->address3, 'class' => 'margin-top-5'])
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" value="{{$strata->draft->address4}}" readonly="">
                                                            <div id="strata_address_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                    @include('components.is_changed', ['old_field' => $strata->address4, 'new_field' => $strata->draft->address4, 'class' => 'margin-top-5'])
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->city, 'new_field' => $strata->draft->city])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($city as $cities)
                                                                <option value="{{$cities->id}}" {{($strata->draft->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.postcode') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->poscode, 'new_field' => $strata->draft->poscode])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$strata->draft->poscode}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.state') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->state, 'new_field' => $strata->draft->state])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($state as $states)
                                                                <option value="{{$states->id}}" {{($strata->draft->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div id="strata_state_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.country') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->country, 'new_field' => $strata->draft->country])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($country as $countries)
                                                                <option value="{{$countries->id}}" {{($strata->draft->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
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
                                                            @include('components.is_changed', ['old_field' => $strata->block_no, 'new_field' => $strata->draft->block_no])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_block') }}" value="{{$strata->draft->block_no}}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.floor') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->total_floor, 'new_field' => $strata->draft->total_floor])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.floor') }}" id="strata_floor" value="{{$strata->draft->total_floor}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.year') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->year, 'new_field' => $strata->draft->year])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.year') }}" value="{{$strata->draft->year}}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.ownership_number') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->ownership_no, 'new_field' => $strata->draft->ownership_no])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.ownership_number') }}" value="{{$strata->draft->ownership_no}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city_town_district') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->town, 'new_field' => $strata->draft->town])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($city as $cities)
                                                                <option value="{{$cities->id}}" {{($strata->draft->town == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.area') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->area, 'new_field' => $strata->draft->area])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($area as $areas)
                                                                <option value="{{$areas->id}}" {{($strata->draft->area == $areas->id ? " selected" : "")}}>{{$areas->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.total_land_area') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->land_area, 'new_field' => $strata->draft->land_area])
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.total_land_area') }}" value="{{$strata->draft->land_area}}" readonly="">
                                                                <select class="form-control" disabled="">
                                                                    @foreach ($unit as $units)
                                                                    <option value="{{$units->id}}" {{($strata->draft->land_area_unit == $units->id ? " selected" : "")}}>{{$units->description}} &nbsp;&nbsp;</option>
                                                                    @endforeach
                                                                </select>
                                                                @include('components.is_changed', ['old_field' => $strata->land_area_unit, 'new_field' => $strata->draft->land_area_unit])
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.lot_number') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->lot_no, 'new_field' => $strata->draft->lot_no])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.lot_number') }}" value="{{$strata->draft->lot_no}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans("app.forms.vacant_possession_date") }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->date, 'new_field' => $strata->draft->date])
                                                            <label class="input-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans("app.forms.vacant_possession_date") }}" value="{{ ($strata->draft->date != '0000-00-00' ? date('d-m-Y', strtotime($strata->draft->date)) : '') }}" readonly=""/>
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
                                                            <label>{{ trans('app.forms.land_title') }}</label>
                                                            @if(!empty($strata->draft))
                                                            @include('components.is_changed', ['old_field' => $strata->land_title, 'new_field' => $strata->draft->land_title])
                                                            @endif
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($land_title as $land_titles)
                                                                <option value="{{$land_titles->id}}" {{($strata->draft->land_title == $land_titles->id ? " selected" : "")}}>{{$land_titles->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.category') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->category, 'new_field' => $strata->draft->category])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($category as $categories)
                                                                <option value="{{$categories->id}}" {{($strata->draft->category == $categories->id ? " selected" : "")}}>{{$categories->description}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.perimeter') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->perimeter, 'new_field' => $strata->draft->perimeter])
                                                            <select class="form-control select2" disabled="">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($perimeter as $perimeters)
                                                                <option value="{{$perimeters->id}}" {{($strata->draft->perimeter == $perimeters->id ? " selected" : "")}}>{{$perimeters->description_en}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.total_share_unit') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->total_share_unit, 'new_field' => $strata->draft->total_share_unit])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.total_share_unit') }}" value="{{$strata->draft->total_share_unit}}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.ccc_no') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->ccc_no, 'new_field' => $strata->draft->ccc_no])
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.ccc_no') }}" value="{{$strata->draft->ccc_no}}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.date_ccc') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->ccc_date, 'new_field' => $strata->draft->ccc_date])
                                                            <label class="input-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_ccc') }}" value="{{ ($strata->draft->ccc_date != '0000-00-00' ? date('d-m-Y', strtotime($strata->draft->ccc_date)) : '') }}" readonly=""/>
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
                                                            <label>{{ trans('app.forms.upload_file') }}</label>
                                                            @include('components.is_changed', ['old_field' => $strata->file_url, 'new_field' => $strata->draft->file_url])
                                                            <br/>
                                                            @if ($strata->draft->file_url != "")                                                            
                                                            <a href="{{asset($strata->draft->file_url)}}" target="_blank"><button button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> {{ trans("app.forms.download") }}</button></a>
                                                            @else
                                                            <p>{{ trans('No information') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                @if((($strata->is_commercial != $strata->draft->is_commercial) && ($strata->draft->is_commercial == false)) || ($strata->is_residential != $strata->draft->is_residential) && ($strata->draft->is_residential == false))
                                                    @include('components.is_changed', ['old_field' => $strata->is_commercial, 'new_field' => $strata->draft->is_commercial, 'text' => trans('app.forms.residential_or_commercial')])
                                                    <hr />
                                                @endif
                                                @if ($strata->draft->residential)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="residential" disabled="" {{($strata->draft->residential ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.residential_block') }}</h4></label>
                                                        @include('components.is_changed', ['old_field' => $strata->is_residential, 'new_field' => $strata->draft->is_residential])
                                                        @if(($strata->is_residential == $strata->draft->is_residential) && ($strata->residentialExtra->count() != $strata->draft->residentialExtra->count()))
                                                            @include('components.is_changed', ['old_field' => $strata->residentialExtra->count(), 'new_field' => $strata->draft->residentialExtra->count()])
                                                        @endif
                                                        <!-- residential Form -->
                                                        <div id="residential_form">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $strata->residential? $strata->residential->unit_no : "", 'new_field' => $strata->draft->residential? $strata->draft->residential->unit_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" value="{{$strata->draft->residential->unit_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $strata->residential? $strata->residential->maintenance_fee_option : "", 'new_field' => $strata->draft->residential? $strata->draft->residential->maintenance_fee_option : ""])
                                                                        <div class="form-inline">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" value="{{$strata->draft->residential->maintenance_fee}}" readonly="">
                                                                            @include('components.is_changed', ['old_field' => $strata->residential? $strata->residential->maintenance_fee : "", 'new_field' => $strata->draft->residential? $strata->draft->residential->maintenance_fee : "", 'class' => 'margin-top-5'])
                                                                            <select class="form-control select2" disabled="">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->draft->residential->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                        @include('components.is_changed', ['old_field' => $strata->residential? $strata->residential->sinking_fund_option : "", 'new_field' => $strata->draft->residential? $strata->draft->residential->sinking_fund_option : ""])
                                                                        <div class="form-inline">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" value="{{$strata->draft->residential->sinking_fund}}" readonly="">
                                                                            @include('components.is_changed', ['old_field' => $strata->residential? $strata->residential->sinking_fund : "", 'new_field' => $strata->draft->residential? $strata->draft->residential->sinking_fund : "", 'class' => 'margin-top-5'])
                                                                            <select class="form-control select2" disabled="">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->draft->residential->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($strata->draft->residentialExtra->count())
                                                @foreach ($strata->draft->residentialExtra as $key => $residentialExtra)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- residential Form -->
                                                            <div id="residential_form_{{ $key }}">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" value="{{$residentialExtra->unit_no}}" readonly="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                                            <div class="form-inline">
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" value="{{$residentialExtra->maintenance_fee}}" readonly="">
                                                                                <select class="form-control select2" disabled="">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($residentialExtra->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" value="{{$residentialExtra->sinking_fund}}" readonly="">
                                                                                <select class="form-control select2" disabled="">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($residentialExtra->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @endif
                                                <hr/>
                                                @endif
                                                @if ($strata->draft->commercial)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="commercial" id="commercial" {{($strata->draft->commercial ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.commercial_block') }}</h4></label>
                                                        @include('components.is_changed', ['old_field' => $strata->is_commercial, 'new_field' => $strata->draft->is_commercial])
                                                        @if(($strata->is_commercial == $strata->draft->is_commercial) && ($strata->commercialExtra->count() != $strata->draft->commercialExtra->count()))
                                                            @include('components.is_changed', ['old_field' => $strata->commercialExtra->count(), 'new_field' => $strata->draft->commercialExtra->count()])
                                                        @endif
                                                        <!-- commercial Form -->
                                                        <div id="commercial_form">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $strata->commercial? $strata->commercial->unit_no : "", 'new_field' => $strata->draft->commercial? $strata->draft->commercial->unit_no : ""])
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" value="{{$strata->draft->commercial->unit_no}}" readonly="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                                        @include('components.is_changed', ['old_field' => $strata->commercial? $strata->commercial->maintenance_fee_option : "", 'new_field' => $strata->draft->commercial? $strata->draft->commercial->maintenance_fee_option : ""])
                                                                        <div class="form-inline">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" value="{{$strata->draft->commercial->maintenance_fee}}" readonly="">
                                                                            @include('components.is_changed', ['old_field' => $strata->commercial? $strata->commercial->maintenance_fee : "", 'new_field' => $strata->draft->commercial? $strata->draft->commercial->maintenance_fee : "", 'class' => 'margin-top-5'])
                                                                            <select class="form-control select2" disabled="">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->draft->commercial->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                        @include('components.is_changed', ['old_field' => $strata->commercial? $strata->commercial->sinking_fund_option : "", 'new_field' => $strata->draft->commercial? $strata->draft->commercial->sinking_fund_option : ""])
                                                                        <div class="form-inline">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" value="{{$strata->draft->commercial->sinking_fund}}" readonly="">
                                                                            @include('components.is_changed', ['old_field' => $strata->commercial? $strata->commercial->sinking_fund : "", 'new_field' => $strata->draft->commercial? $strata->draft->commercial->sinking_fund : "", 'class' => 'margin-top-5'])
                                                                            <select class="form-control select2" disabled="">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->draft->commercial->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($strata->draft->commercialExtra->count())
                                                @foreach ($strata->draft->commercialExtra as $key => $commercialExtra)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- commercial Form -->
                                                            <div id="commercial_form_{{ $key }}">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" value="{{$commercialExtra->unit_no}}" readonly="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                                            <div class="form-inline">
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" value="{{$commercialExtra->maintenance_fee}}" readonly="">
                                                                                <select class="form-control select2" disabled="">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($commercialExtra->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" value="{{$commercialExtra->sinking_fund}}" readonly="">
                                                                                <select class="form-control select2" disabled="">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($commercialExtra->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @endif
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
                                                                <input type="radio" name="management_office" value="1" disabled="" {{($strata->draft->facility->management_office == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="management_office" value="0" disabled="" {{($strata->draft->facility->management_office == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->management_office_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->management_office, 'new_field' => $strata->draft->facility->management_office, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->management_office == $strata->draft->facility->management_office) && ($strata->facility->management_office_unit != $strata->draft->facility->management_office_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->management_office_unit, 'new_field' => $strata->draft->facility->management_office_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.swimming_pool') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="swimming_pool" value="1" disabled="" {{($strata->draft->facility->swimming_pool == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="swimming_pool" value="0" disabled="" {{($strata->draft->facility->swimming_pool == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->swimming_pool_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->swimming_pool, 'new_field' => $strata->draft->facility->swimming_pool, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->swimming_pool == $strata->draft->facility->swimming_pool) && ($strata->facility->swimming_pool_unit != $strata->draft->facility->swimming_pool_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->swimming_pool_unit, 'new_field' => $strata->draft->facility->swimming_pool_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.surau') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="surau" value="1" disabled="" {{($strata->draft->facility->surau == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="surau" value="0" disabled="" {{($strata->draft->facility->surau == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->surau_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->surau, 'new_field' => $strata->draft->facility->surau, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->surau == $strata->draft->facility->surau) && ($strata->facility->surau_unit != $strata->draft->facility->surau_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->surau_unit, 'new_field' => $strata->draft->facility->surau_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.multipurpose_hall') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="multipurpose_hall" value="1" disabled="" {{($strata->draft->facility->multipurpose_hall == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="multipurpose_hall" value="0" disabled="" {{($strata->draft->facility->multipurpose_hall == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->multipurpose_hall_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->multipurpose_hall, 'new_field' => $strata->draft->facility->multipurpose_hall, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->multipurpose_hall == $strata->draft->facility->multipurpose_hall) && ($strata->facility->multipurpose_hall_unit != $strata->draft->facility->multipurpose_hall_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->multipurpose_hall_unit, 'new_field' => $strata->draft->facility->multipurpose_hall_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.gym') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="gym" value="1" disabled="" {{($strata->draft->facility->gym == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="gym" value="0" disabled="" {{($strata->draft->facility->gym == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->gym_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->gym, 'new_field' => $strata->draft->facility->gym, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->gym == $strata->draft->facility->gym) && ($strata->facility->gym_unit != $strata->draft->facility->gym_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->gym_unit, 'new_field' => $strata->draft->facility->gym_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.playground') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="playground" value="1" disabled="" {{($strata->draft->facility->playground == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="playground" value="0" disabled="" {{($strata->draft->facility->playground == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->playground_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->playground, 'new_field' => $strata->draft->facility->playground, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->playground == $strata->draft->facility->playground) && ($strata->facility->playground_unit != $strata->draft->facility->playground_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->playground_unit, 'new_field' => $strata->draft->facility->playground_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.guardhouse') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="guardhouse" value="1" disabled="" {{($strata->draft->facility->guardhouse == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="guardhouse" value="0" disabled="" {{($strata->draft->facility->guardhouse == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->guardhouse_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->guardhouse, 'new_field' => $strata->draft->facility->guardhouse, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->guardhouse == $strata->draft->facility->guardhouse) && ($strata->facility->guardhouse_unit != $strata->draft->facility->guardhouse_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->guardhouse_unit, 'new_field' => $strata->draft->facility->guardhouse_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.kindergarten') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="kindergarten" value="1" disabled="" {{($strata->draft->facility->kindergarten == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="kindergarten" value="0" disabled="" {{($strata->draft->facility->kindergarten == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->kindergarten_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->kindergarten, 'new_field' => $strata->draft->facility->kindergarten, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->kindergarten == $strata->draft->facility->kindergarten) && ($strata->facility->kindergarten_unit != $strata->draft->facility->kindergarten_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->kindergarten_unit, 'new_field' => $strata->draft->facility->kindergarten_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.open_space') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="open_space" value="1" disabled="" {{($strata->draft->facility->open_space == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="open_space" value="0" disabled="" {{($strata->draft->facility->open_space == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->open_space_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->open_space, 'new_field' => $strata->draft->facility->open_space, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->open_space == $strata->draft->facility->open_space) && ($strata->facility->open_space_unit != $strata->draft->facility->open_space_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->open_space_unit, 'new_field' => $strata->draft->facility->open_space_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.lift') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="lift" value="1" disabled="" {{($strata->draft->facility->lift == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="lift" value="0" disabled="" {{($strata->draft->facility->lift == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->lift_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->lift, 'new_field' => $strata->draft->facility->lift, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->lift == $strata->draft->facility->lift) && ($strata->facility->lift_unit != $strata->draft->facility->lift_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->lift_unit, 'new_field' => $strata->draft->facility->lift_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.rubbish_room') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="rubbish_room" value="1" disabled="" {{($strata->draft->facility->rubbish_room == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="rubbish_room" value="0" disabled="" {{($strata->draft->facility->rubbish_room == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->rubbish_room_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->rubbish_room, 'new_field' => $strata->draft->facility->rubbish_room, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->rubbish_room == $strata->draft->facility->rubbish_room) && ($strata->facility->rubbish_room_unit != $strata->draft->facility->rubbish_room_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->rubbish_room_unit, 'new_field' => $strata->draft->facility->rubbish_room_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.gated') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="gated" value="1" disabled="" {{($strata->draft->facility->gated == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" name="gated" value="0" disabled="" {{($strata->draft->facility->gated == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" disabled="">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->draft->facility->gated_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->gated, 'new_field' => $strata->draft->facility->gated, 'class' => 'margin-top-5'])
                                                            @if(($strata->facility->gated == $strata->draft->facility->gated) && ($strata->facility->gated_unit != $strata->draft->facility->gated_unit))
                                                            @include('components.is_changed', ['old_field' => $strata->facility->gated_unit, 'new_field' => $strata->draft->facility->gated_unit, 'class' => 'margin-top-5'])
                                                            @endif
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.others') }}</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <textarea class="form-control" rows="3" readonly="">{{$strata->draft->facility->others}}</textarea>
                                                            </div>
                                                            @include('components.is_changed', ['old_field' => $strata->facility->others, 'new_field' => $strata->draft->facility->others, 'class' => 'margin-top-5'])
                                                        </div>
                                                    </div>
                                                </div>
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
                                                url: "{{ URL::action('DraftController@submitStrata') }}",
                                                type: "POST",
                                                data: {
                                                    file_id: '{{ \Helper\Helper::encode($file->id) }}'
                                                },
                                                success: function (data) {
                                                    $("#loading").css("display", "none");
                                                    $("#submit_button").removeAttr("disabled");
                                                    $("#cancel_button").removeAttr("disabled");
                                                    if (data.trim() === "true") {
                                                        $.notify({
                                                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>'
                                                        }, {
                                                            type: 'success',
                                                            placement: {
                                                                align: "center"
                                                            }
                                                        });
                                                        window.location = "{{URL::action('DraftController@management', \Helper\Helper::encode($file->id))}}";
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
                                            <form id="strata">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.strata_title') }}</label>
                                                            <select class="form-control" id="strata_title">
                                                                <option value="1" {{($strata->title == "1" ? " selected" : "")}}>{{ trans("app.forms.yes") }}</option>
                                                                <option value="0" {{($strata->title == "0" ? " selected" : "")}}>{{ trans("app.forms.no") }}</option>
                                                            </select>
                                                            <div id="strata_title_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.name') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="strata_name" value="{{$strata->name}}">
                                                            <div id="strata_name_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.parliament') }}</label>
                                                            <select class="form-control select2" id="strata_parliament" onchange="findDUN()">
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
                                                            <select class="form-control select2" id="strata_dun" onchange="findPark()">
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
                                                            <select class="form-control select2" id="strata_park">
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
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="strata_address4" value="{{$strata->address4}}">
                                                            <div id="strata_address_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.city') }}</label>
                                                            <select class="form-control select2" id="strata_city">
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
                                                            <select class="form-control select2" id="strata_state">
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
                                                            <select class="form-control select2" id="strata_country">
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
                                                            <label>{{ trans('app.forms.floor') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.floor') }}" id="strata_floor" value="{{$strata->total_floor}}">
                                                            <div id="floor_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.year') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.year') }}" id="strata_year" value="{{$strata->year}}">
                                                            <div id="year_error" style="display:none;"></div>
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
                                                            <select class="form-control select2" id="strata_town">
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
                                                            <select class="form-control select2" id="strata_area">
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
                                                                    <option value="{{$units->id}}" {{($strata->land_area_unit == $units->id ? " selected" : "")}}>{{$units->description}} &nbsp;&nbsp;</option>
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
                                                            <label>{{ trans("app.forms.vacant_possession_date") }}</label>
                                                            <label class="input-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans("app.forms.vacant_possession_date") }}" id="strata_date_raw" value="{{ ($strata->date != '0000-00-00' ? date('d-m-Y', strtotime($strata->date)) : '') }}"/>
                                                                <span class="input-group-addon">
                                                                    <i class="icmn-calendar"></i>
                                                                </span>
                                                            </label>
                                                            <input type="hidden" id="strata_date" value="{{ $strata->date }}">
                                                            <div id="strata_date_error" style="display:block;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.land_title') }}</label>
                                                            <select class="form-control select2" id="strata_land_title">
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
                                                            <select class="form-control select2" id="strata_category">
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
                                                            <select class="form-control select2" id="strata_perimeter">
                                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                @foreach ($perimeter as $perimeters)
                                                                <option value="{{$perimeters->id}}" {{($strata->perimeter == $perimeters->id ? " selected" : "")}}>{{$perimeters->description_en}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div id="starta_perimeter_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.total_share_unit') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.total_share_unit') }}" id="strata_total_share_unit" value="{{$strata->total_share_unit}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.ccc_no') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.ccc_no') }}" id="strata_ccc_no" value="{{$strata->ccc_no}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.date_ccc') }}</label>
                                                            <label class="input-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.date_ccc') }}" id="strata_ccc_date_raw" value="{{ ($strata->ccc_date != '0000-00-00' ? date('d-m-Y', strtotime($strata->ccc_date)) : '') }}"/>
                                                                <span class="input-group-addon">
                                                                    <i class="icmn-calendar"></i>
                                                                </span>
                                                            </label>
                                                            <input type="hidden" id="strata_ccc_date" value="{{ $strata->ccc_date }}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.upload_file') }}</label>
                                                            @if ($strata->file_url != "")
                                                            <br/><br/>
                                                            <a href="{{asset($strata->file_url)}}" target="_blank"><button button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File"><i class="icmn-file-download2"></i> {{ trans("app.forms.download") }}</button></a>
                                                            @else
                                                            <p>{{ trans('No information') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                                @if ($strata->is_residential)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="residential" id="residential" {{($strata->is_residential ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.residential_block') }}</h4></label>
                                                        <!-- residential Form -->
                                                        <div id="residential_form">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" id="residential_unit_no" value="{{$strata->residential->unit_no}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                                        <div class="form-inline">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" id="residential_maintenance_fee" value="{{$strata->residential->maintenance_fee}}">
                                                                            <select class="form-control select2" id="residential_maintenance_fee_option">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->residential->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="residential_sinking_fund" value="{{$strata->residential->sinking_fund}}">
                                                                            <select class="form-control select2" id="residential_sinking_fund_option">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->residential->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($strata->residentialExtra->count())
                                                @foreach($strata->residentialExtra as $key => $residentialExtra)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- residential Form -->
                                                            <div id="residential_form_{{ $key }}">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.number_of_residential_unit') }}</label>
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_residential_unit') }}" id="residential_unit_no" value="{{$residentialExtra->unit_no}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.maintenance_fee') }}</label>
                                                                            <div class="form-inline">
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.maintenance_fee') }}" id="residential_maintenance_fee" value="{{$residentialExtra->maintenance_fee}}">
                                                                                <select class="form-control select2" id="residential_maintenance_fee_option">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($residentialExtra->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="residential_sinking_fund" value="{{$residentialExtra->sinking_fund}}">
                                                                                <select class="form-control select2" id="residential_sinking_fund_option">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($residentialExtra->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @endif
                                                <hr/>
                                                @endif
                                                @if ($strata->is_commercial)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <input type="checkbox" name="commercial" id="commercial" {{($strata->is_commercial ? " checked" : "")}}/>
                                                        <label><h4> {{ trans('app.forms.commercial_block') }}</h4></label>
                                                        <!-- commercial Form -->
                                                        <div id="commercial_form">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" id="commercial_unit_no" value="{{$strata->commercial->unit_no}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                                        <div class="form-inline">
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" id="commercial_maintenance_fee" value="{{$strata->commercial->maintenance_fee}}">
                                                                            <select class="form-control select2" id="commercial_maintenance_fee_option">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->commercial->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="commercial_sinking_fund" value="{{$strata->commercial->sinking_fund}}">
                                                                            <select class="form-control select2" id="commercial_sinking_fund_option">
                                                                                @foreach ($unitoption as $unitoptions)
                                                                                <option value="{{$unitoptions->id}}" {{($strata->commercial->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($strata->commercialExtra->count())
                                                @foreach($strata->commercialExtra as $key => $commercialExtra)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- commercial Form -->
                                                            <div id="commercial_form_{{ $key }}">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.number_of_commercial_unit') }}</label>
                                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.number_of_commercial_unit') }}" id="commercial_unit_no" value="{{$commercialExtra->unit_no}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>{{ trans('app.forms.commercial_fee') }}</label>
                                                                            <div class="form-inline">
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.commercial_fee') }}" id="commercial_maintenance_fee" value="{{$commercialExtra->maintenance_fee}}">
                                                                                <select class="form-control select2" id="commercial_maintenance_fee_option">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($commercialExtra->maintenance_fee_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
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
                                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="commercial_sinking_fund" value="{{$commercialExtra->sinking_fund}}">
                                                                                <select class="form-control select2" id="commercial_sinking_fund_option">
                                                                                    @foreach ($unitoption as $unitoptions)
                                                                                    <option value="{{$unitoptions->id}}" {{($commercialExtra->sinking_fund_option == $unitoptions->id ? " selected" : "")}}>{{$unitoptions->description}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @endif
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
                                                                <input type="radio" id="management_office" name="management_office" value="1" {{($strata->facility->management_office == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="management_office" name="management_office" value="0" {{($strata->facility->management_office == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="management_office_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->management_office_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.swimming_pool') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="swimming_pool" name="swimming_pool" value="1" {{($strata->facility->swimming_pool == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="swimming_pool" name="swimming_pool" value="0" {{($strata->facility->swimming_pool == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="swimming_pool_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->swimming_pool_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.surau') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="surau" name="surau" value="1" {{($strata->facility->surau == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="surau" name="surau" value="0" {{($strata->facility->surau == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="surau_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->surau_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.multipurpose_hall') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="multipurpose_hall" name="multipurpose_hall" value="1" {{($strata->facility->multipurpose_hall == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="multipurpose_hall" name="multipurpose_hall" value="0" {{($strata->facility->multipurpose_hall == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="multipurpose_hall_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->multipurpose_hall_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.gym') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="gym" name="gym" value="1" {{($strata->facility->gym == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="gym" name="gym" value="0" {{($strata->facility->gym == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="gym_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->gym_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.playground') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="playground" name="playground" value="1" {{($strata->facility->playground == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="playground" name="playground" value="0" {{($strata->facility->playground == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="playground_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->playground_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.guardhouse') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="guardhouse" name="guardhouse" value="1" {{($strata->facility->guardhouse == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="guardhouse" name="guardhouse" value="0" {{($strata->facility->guardhouse == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="guardhouse_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->guardhouse_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.kindergarten') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="kindergarten" name="kindergarten" value="1" {{($strata->facility->kindergarten == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="kindergarten" name="kindergarten" value="0" {{($strata->facility->kindergarten == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="kindergarten_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->kindergarten_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.open_space') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="open_space" name="open_space" value="1" {{($strata->facility->open_space == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="open_space" name="open_space" value="0" {{($strata->facility->open_space == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="open_space_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->open_space_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.lift') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="lift" name="lift" value="1" {{($strata->facility->lift == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="lift" name="lift" value="0" {{($strata->facility->lift == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="lift_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->lift_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.rubbish_room') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="rubbish_room" name="rubbish_room" value="1" {{($strata->facility->rubbish_room == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="rubbish_room" name="rubbish_room" value="0" {{($strata->facility->rubbish_room == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="rubbish_room_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->rubbish_room_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.gated') }}</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="gated" name="gated" value="1" {{($strata->facility->gated == 1 ? " checked" : "")}}>
                                                                {{ trans('app.forms.yes') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <input type="radio" id="gated" name="gated" value="0" {{($strata->facility->gated == 0 ? " checked" : "")}}>
                                                                {{ trans('app.forms.no') }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <select class="form-control select2" id="gated_unit">
                                                                    @for ($x = 0; $x <= 50; $x++)
                                                                    <option value="{{ $x }}" {{ ($strata->facility->gated_unit == $x ? 'selected' : '') }}>{{ $x }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label class="form-control-label">{{ trans('app.forms.others') }}</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <textarea class="form-control" rows="3" id="others">{{$strata->facility->others}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

@stop
