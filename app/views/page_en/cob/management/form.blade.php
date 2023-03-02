<?php $disable = !empty($is_view)? true : false; ?>
<form id="management-form" onsubmit="event.preventDefault();">
    @if (! $management->developers->count())
    <div class="row margin-top-20">
        <div class="col-lg-12">
            <input type="checkbox" name="is_developer" id="is_developer" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.developer') }}</h4></label>
            <!-- developer Form -->
            <div id="developer_form_container" style="display:none">
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.developer') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.developer') }}" id="developer_name_0" name="developer_name[]" value="{{$house_scheme->name}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="developer_address1_0" name="developer_address1[]" value="{{$house_scheme->address1}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="developer_address2_0" name="developer_address2[]" value="{{$house_scheme->address2}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="developer_address3_0" name="developer_address3[]" value="{{$house_scheme->address3}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="developer_address4_0" name="developer_address4[]" value="{{$house_scheme->address4}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="developer_city_0" name="developer_city[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="developer_poscode_0" name="developer_poscode[]" value="{{$house_scheme->poscode}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="developer_state_0" name="developer_state[]" {{ $disable? "disabled" : ""}}>
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
                                <select class="form-control select2" id="developer_country_0" name="developer_country[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="developer_phone_no_0" name="developer_phone_no[]" value="{{$house_scheme->phone_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="developer_fax_no_0" name="developer_fax_no[]" value="{{$house_scheme->fax_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.remarks') }}</label>
                                <textarea class="form-control" rows="3" id="developer_remarks_0" name="developer_remarks[]" {{ $disable? "readonly" : ""}}>{{$house_scheme->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="developer-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
            </div>
            @if(!$disable)
            <div id="developer_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-developer" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="row padding-vertical-20">
        <div class="col-lg-12">
            <input type="checkbox" name="is_developer" id="is_developer" {{($management->is_developer == 1 ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.developer') }}</h4></label>
            <!-- developer Form -->
            <div id="developer_form_container">
                @foreach($management->developers as $key => $developer)
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.developer') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.developer') }}" id="developer_name_{{ $key }}" name="developer_name[]" value="{{$developer->name}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="developer_address1_{{ $key }}" name="developer_address1[]" value="{{$developer->address_1}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="developer_address2_{{ $key }}" name="developer_address2[]"  value="{{$developer->address_2}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="developer_address3_{{ $key }}" name="developer_address3[]"  value="{{$developer->address_3}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="developer_address4_{{ $key }}" name="developer_address4[]"  value="{{$developer->address_4}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control" id="developer_city_{{ $key }}" name="developer_city[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($city as $cities)
                                    <option value="{{$cities->id}}" {{($developer->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.postcode') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="developer_poscode_{{ $key }}" name="developer_poscode[]" value="{{$developer->poscode}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control" id="developer_state_{{ $key }}" name="developer_state[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($state as $states)
                                    <option value="{{$states->id}}" {{($developer->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.country') }}</label>
                                <select class="form-control" id="developer_country_{{ $key }}" name="developer_country[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($country as $countries)
                                    <option value="{{$countries->id}}" {{($developer->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.phone_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="developer_phone_no_{{ $key }}" name="developer_phone_no[]" value="{{$developer->phone_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="developer_fax_no_{{ $key }}" name="developer_fax_no[]" value="{{$developer->fax_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.remarks') }}</label>
                                <textarea class="form-control" rows="3" id="developer_remarks_{{ $key }}" name="developer_remarks[]" {{ $disable? "readonly" : ""}}>{{$developer->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="developer-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
            @if(!$disable)
            <div id="developer_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-developer" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    <hr/>
    @if (! $management->liquidators->count())
    <div class="row margin-top-20">
        <div class="col-lg-12">
            <input type="checkbox" name="liquidator" id="liquidator" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.liquidator') }}</h4></label>
            <!-- liquidator Form -->
            <div id="liquidator_form_container" style="display:none">
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.liquidator') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.liquidator') }}" id="liquidator_name_0" name="liquidator_name[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="liquidator_address1_0" name="liquidator_address1[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="liquidator_address2_0" name="liquidator_address2[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="liquidator_address3_0" name="liquidator_address3[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="liquidator_address4_0" name="liquidator_address4[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="liquidator_city_0" name="liquidator_city[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="liquidator_poscode_0" name="liquidator_poscode[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="liquidator_state_0" name="liquidator_state[]" {{ $disable? "disabled" : ""}}>
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
                                <select class="form-control select2" id="liquidator_country_0" name="liquidator_country[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="liquidator_phone_no_0" name="liquidator_phone_no[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="liquidator_fax_no_0" name="liquidator_fax_no[]" value="" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.remarks') }}</label>
                                <textarea class="form-control" rows="3" id="liquidator_remarks_0" name="liquidator_remarks[]" {{ $disable? "readonly" : ""}}></textarea>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="liquidator-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
            </div>
            @if(!$disable)
            <div id="liquidator_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-liquidator" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="row padding-vertical-20">
        <div class="col-lg-12">
            <input type="checkbox" name="liquidator" id="liquidator" {{($management->liquidator == 1 ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.liquidator') }}</h4></label>
            <!-- liquidator Form -->
            <div id="liquidator_form_container">
                @foreach($management->liquidators as $key => $liquidator)
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.liquidator') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.liquidator') }}" id="liquidator_name_{{ $key }}" name="liquidator_name[]" value="{{$liquidator->name}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="liquidator_address1_{{ $key }}" name="liquidator_address1[]" value="{{$liquidator->address_1}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="liquidator_address2_{{ $key }}" name="liquidator_address2[]"  value="{{$liquidator->address_2}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="liquidator_address3_{{ $key }}" name="liquidator_address3[]"  value="{{$liquidator->address_3}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="liquidator_address4_{{ $key }}" name="liquidator_address4[]"  value="{{$liquidator->address_4}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control" id="liquidator_city_{{ $key }}" name="liquidator_city[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($city as $cities)
                                    <option value="{{$cities->id}}" {{($liquidator->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.postcode') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="liquidator_poscode_{{ $key }}" name="liquidator_poscode[]" value="{{$liquidator->poscode}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control" id="liquidator_state_{{ $key }}" name="liquidator_state[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($state as $states)
                                    <option value="{{$states->id}}" {{($liquidator->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.country') }}</label>
                                <select class="form-control" id="liquidator_country_{{ $key }}" name="liquidator_country[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($country as $countries)
                                    <option value="{{$countries->id}}" {{($liquidator->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.phone_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="liquidator_phone_no_{{ $key }}" name="liquidator_phone_no[]" value="{{$liquidator->phone_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="liquidator_fax_no_{{ $key }}" name="liquidator_fax_no[]" value="{{$liquidator->fax_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.remarks') }}</label>
                                <textarea class="form-control" rows="3" id="liquidator_remarks_{{ $key }}" name="liquidator_remarks[]" {{ $disable? "readonly" : ""}}>{{$liquidator->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="liquidator-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
            @if(!$disable)
            <div id="liquidator_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-liquidator" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    <hr/>
    @if (! $management->jmbs->count())
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_jmb" id="is_jmb" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.joint_management_body') }}</h4></label>
            <!-- jmb Form -->
            <div id="jmb_form_container" style="display:none">
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.date_formed') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_formed') }}" id="jmb_date_formed_0" name="jmb_date_formed[]" {{ $disable? "readonly" : ""}}/>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="jmb_certificate_no_0" name="jmb_certificate_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.name') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="jmb_name_0" name="jmb_name[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="jmb_address1_0" name="jmb_address1[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="jmb_address2_0" name="jmb_address2[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="jmb_address3_0" name="jmb_address3[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="jmb_city_0" name="jmb_city[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="jmb_poscode_0" name="jmb_poscode[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="jmb_state_0" name="jmb_state[]" {{ $disable? "disabled" : ""}}>
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
                                <select class="form-control select2" id="jmb_country_0" name="jmb_country[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="jmb_phone_no_0" name="jmb_phone_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="jmb_fax_no_0" name="jmb_fax_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="jmb_email_0" name="jmb_email[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="jmb-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
            </div>
            @if(!$disable)
            <div id="jmb_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-jmb" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_jmb" id="is_jmb" {{($management->is_jmb == 1 ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.joint_management_body') }}</h4></label>
            <!-- jmb Form -->
            <div id="jmb_form_container">
                @foreach($management->jmbs as $key => $jmb)
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.date_formed') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_formed') }}" id="jmb_date_formed_{{ $key }}" name="jmb_date_formed[]" value="{{ ($jmb->date_formed != '0000-00-00' ? date('d-m-Y', strtotime($jmb->date_formed)) : '') }}" {{ $disable? "readonly" : ""}}/>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="jmb_certificate_no_{{ $key }}" name="jmb_certificate_no[]" value="{{$jmb->certificate_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.name') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="jmb_name_{{ $key }}" name="jmb_name[]" value="{{$jmb->name}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="jmb_address1_{{ $key }}" name="jmb_address1[]" value="{{$jmb->address1}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="jmb_address2_{{ $key }}" name="jmb_address2[]" value="{{$jmb->address2}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="jmb_address3_{{ $key }}" name="jmb_address3[]" value="{{$jmb->address3}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="jmb_city_{{ $key }}" name="jmb_city[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($city as $cities)
                                    <option value="{{$cities->id}}" {{($jmb->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.postcode') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="jmb_poscode_{{ $key }}" name="jmb_poscode[]" value="{{$jmb->poscode}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="jmb_state_{{ $key }}" name="jmb_state[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($state as $states)
                                    <option value="{{$states->id}}" {{($jmb->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.country') }}</label>
                                <select class="form-control select2" id="jmb_country_{{ $key }}" name="jmb_country[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($country as $countries)
                                    <option value="{{$countries->id}}" {{($jmb->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.phone_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="jmb_phone_no_{{ $key }}" name="jmb_phone_no[]" value="{{$jmb->phone_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="jmb_fax_no_{{ $key }}" name="jmb_fax_no[]" value="{{$jmb->fax_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="jmb_email_{{ $key }}" name="jmb_email[]" value="{{$jmb->email}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="jmb-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
            @if(!$disable)
            <div id="jmb_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-jmb" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    <hr/>
    @if (! $management->mcs->count())
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_mc" id="is_mc" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.management_corporation') }}</h4></label>
            <!-- mc Form -->
            <div id="mc_form_container" style="display:none">
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.date_formed') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_formed') }}" id="mc_date_formed_0" name="mc_date_formed[]" {{ $disable? "readonly" : ""}}/>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="mc_certificate_no_0" name="mc_certificate_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.first_agm_date') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.first_agm_date') }}" id="mc_first_agm_0" name="mc_first_agm[]" {{ $disable? "readonly" : ""}}/>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="mc_name_0" name="mc_name[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="mc_address1_0" name="mc_address1[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="mc_address2_0" name="mc_address2[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="mc_address3_0" name="mc_address3[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="mc_city_0" name="mc_city[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="mc_poscode_0" name="mc_poscode[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="mc_state_0" name="mc_state[]" {{ $disable? "disabled" : ""}}>
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
                                <select class="form-control select2" id="mc_country_0" name="mc_country[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="mc_phone_no_0" name="mc_phone_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="mc_fax_no_0" name="mc_fax_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="mc_email_0" name="mc_email[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="mc-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
            </div>
            @if(!$disable)
            <div id="mc_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-mc" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_mc" id="is_mc" {{($management->is_mc == 1 ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.management_corporation') }}</h4></label>
            <!-- mc Form -->
            <div id="mc_form_container">
                @foreach($management->mcs as $key => $mc)
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.date_formed') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_formed') }}" id="mc_date_formed_{{ $key }}" name="mc_date_formed[]" value="{{ ($mc->date_formed != '0000-00-00' ? date('d-m-Y', strtotime($mc->date_formed)) : '') }}" {{ $disable? "readonly" : ""}}/>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.certificate_series_number') }}" id="mc_certificate_no_{{ $key }}" name="mc_certificate_no[]" value="{{$mc->certificate_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.first_agm_date') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.first_agm_date') }}" id="mc_first_agm_{{ $key }}" name="mc_first_agm[]" value="{{ ($mc->first_agm != '0000-00-00' ? date('d-m-Y', strtotime($mc->first_agm)) : '') }}" {{ $disable? "readonly" : ""}}/>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="mc_name_{{ $key }}" name="mc_name[]" value="{{$mc->name}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="mc_address1_{{ $key }}" name="mc_address1[]" value="{{$mc->address1}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="mc_address2_{{ $key }}" name="mc_address2[]" value="{{$mc->address2}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="mc_address3_{{ $key }}" name="mc_address3[]" value="{{$mc->address3}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="mc_city_{{ $key }}" name="mc_city[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($city as $cities)
                                    <option value="{{$cities->id}}" {{($mc->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.postcode') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="mc_poscode_{{ $key }}" name="mc_poscode[]" value="{{$mc->poscode}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="mc_state_{{ $key }}" name="mc_state[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($state as $states)
                                    <option value="{{$states->id}}" {{($mc->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.country') }}</label>
                                <select class="form-control select2" id="mc_country_{{ $key }}" name="mc_country[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($country as $countries)
                                    <option value="{{$countries->id}}" {{($mc->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.phone_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="mc_phone_no_{{ $key }}" name="mc_phone_no[]" value="{{$mc->phone_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="mc_fax_no_{{ $key }}" name="mc_fax_no[]" value="{{$mc->fax_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="mc_email_{{ $key }}" name="mc_email[]" value="{{$mc->email}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="mc-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
            @if(!$disable)
            <div id="mc_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-mc" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    <hr/>
    @if(! $management->agents->count())
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_agent" id="is_agent" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.agent') }}</h4></label>
            <!-- agent Form -->
            <div id="agent_form_container" style="display:none">
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.appointed_by') }}</label>
                                <select class="form-control select2" id="agent_selected_by_0" name="agent_selected_by[]" {{ $disable? "disabled" : ""}}>
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
                                <select class="form-control select2" id="agent_name_0" name="agent_name[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="agent_address1_0" name="agent_address1[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="agent_address2_0" name="agent_address2[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="agent_address3_0" name="agent_address3[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="agent_city_0" name="agent_city[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="agent_poscode_0" name="agent_poscode[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="agent_state_0" name="agent_state[]" {{ $disable? "disabled" : ""}}>
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
                                <select class="form-control select2" id="agent_country_0" name="agent_country[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="agent_phone_no_0" name="agent_phone_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="agent_fax_no_0" name="agent_fax_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="agent_email_0" name="agent_email[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="agent-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
            </div>
            @if(!$disable)
            <div id="agent_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-agent" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_agent" id="is_agent" {{($management->is_agent == 1 ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.agent') }}</h4></label>
            <!-- agent Form -->
            <div id="agent_form_container">
                @foreach($management->agents as $key => $mgnt_agent)
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.appointed_by') }}</label>
                                <select class="form-control select2" id="agent_selected_by_{{ $key }}" name="agent_selected_by[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    <option value="developer" {{($mgnt_agent->selected_by == "developer" ? " selected" : "")}}>{{ trans('app.forms.developer') }}</option>
                                    <option value="cob" {{($mgnt_agent->selected_by == "cob" ? " selected" : "")}}>{{ trans('app.forms.cob') }}</option>
                                    <option value="jmb" {{($mgnt_agent->selected_by == "jmb" ? " selected" : "")}}>{{ trans('app.forms.jmb') }}</option>
                                    <option value="mc" {{($mgnt_agent->selected_by == "mc" ? " selected" : "")}}>{{ trans('app.forms.mc') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.name') }}</label>
                                <select class="form-control select2" id="agent_name_{{ $key }}" name="agent_name[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($agent as $agents)
                                    <option value="{{$agents->id}}" {{($mgnt_agent->agent == $agents->id ? " selected" : "")}}>{{$agents->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="agent_address1_{{ $key }}" name="agent_address1[]" value="{{$mgnt_agent->address1}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="agent_address2_{{ $key }}" name="agent_address2[]" value="{{$mgnt_agent->address2}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="agent_address3_{{ $key }}" name="agent_address3[]" value="{{$mgnt_agent->address3}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="agent_city_{{ $key }}" name="agent_city[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($city as $cities)
                                    <option value="{{$cities->id}}" {{($mgnt_agent->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.postcode') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="agent_poscode_{{ $key }}" name="agent_poscode[]" value="{{$mgnt_agent->poscode}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="agent_state_{{ $key }}" name="agent_state[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($state as $states)
                                    <option value="{{$states->id}}" {{($mgnt_agent->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.country') }}</label>
                                <select class="form-control select2" id="agent_country_{{ $key }}" name="agent_country[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($country as $countries)
                                    <option value="{{$countries->id}}" {{($mgnt_agent->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.phone_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="agent_phone_no_{{ $key }}" name="agent_phone_no[]" value="{{$mgnt_agent->phone_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="agent_fax_no_{{ $key }}" name="agent_fax_no[]" value="{{$mgnt_agent->fax_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="agent_email_{{ $key }}" name="agent_email[]" value="{{$mgnt_agent->email}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="agent-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
            @if(!$disable)
            <div id="agent_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-agent" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    <hr/>
    @if (! $management->otherses->count())
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_others" id="is_others" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.others') }}</h4></label>
            <!-- other Form -->
            <div id="others_form_container" style="display:none">
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.name') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="others_name_0" name="others_name[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="others_address1_0" name="others_address1[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="others_address2_0" name="others_address2[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="others_address3_0" name="others_address3[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="others_city_0" name="others_city[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="others_poscode_0" name="others_poscode[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="others_state_0" name="others_state[]" {{ $disable? "disabled" : ""}}>
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
                                <select class="form-control select2" id="others_country_0" name="others_country[]" {{ $disable? "disabled" : ""}}>
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
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="others_phone_no_0" name="others_phone_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="others_fax_no_0" name="others_fax_no[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="others_email_0" name="others_email[]" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="others-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
            </div>
            @if(!$disable)
            <div id="others_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-others" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="is_others" id="is_others" {{($management->is_others == 1 ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.others') }}</h4></label>
            <!-- others Form -->
            <div id="others_form_container">
                @foreach($management->otherses as $key => $other)
                <div class="card padding-10 container-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('app.forms.name') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="others_name_{{ $key }}" name="others_name[]" value="{{$other->name}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.address') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="others_address1_{{ $key }}" name="others_address1[]" value="{{$other->address1}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="others_address2_{{ $key }}" name="others_address2[]" value="{{$other->address2}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="others_address3_{{ $key }}" name="others_address3[]" value="{{$other->address3}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select class="form-control select2" id="others_city_{{ $key }}" name="others_city[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($city as $cities)
                                    <option value="{{$cities->id}}" {{($other->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.postcode') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="others_poscode_{{ $key }}" name="others_poscode[]" value="{{$other->poscode}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.state') }}</label>
                                <select class="form-control select2" id="others_state_{{ $key }}" name="others_state[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($state as $states)
                                    <option value="{{$states->id}}" {{($other->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.country') }}</label>
                                <select class="form-control select2" id="others_country_{{ $key }}" name="others_country[]" {{ $disable? "disabled" : ""}}>
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($country as $countries)
                                    <option value="{{$countries->id}}" {{($other->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.phone_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="others_phone_no_{{ $key }}" name="others_phone_no[]" value="{{$other->phone_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.fax_number') }}</label>
                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="others_fax_no_{{ $key }}" name="others_fax_no[]" value="{{$other->fax_no}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.email') }}</label>
                                <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="others_email_{{ $key }}" name="others_email[]" value="{{$other->email}}" {{ $disable? "readonly" : ""}}>
                            </div>
                        </div>
                    </div>
                    @if(!$disable)
                    <button class="others-remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                    @endif
                </div>
                @endforeach
            </div>
            @if(!$disable)
            <div id="others_add_more_row" class="row" style="display: none;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <a href="javascript:void(0);" id="add-more-others" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    <hr/>
    @if (! $management->no_management)
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="no_management" id="no_management" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.no_management') }}</h4></label>
            <!-- no_management Form -->
            <div id="no_management_form_container" style="display:none">
                <div id="no_management_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.date_start') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_start') }}" id="no_management_date_start" name="no_management_date_start" {{ $disable? "readonly" : ""}}/>
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
                                <label>{{ trans('app.forms.date_end') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_end') }}" id="no_management_date_end" name="no_management_date_end" {{ $disable? "readonly" : ""}}/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="no_management" id="no_management" {{($management->no_management == 1 ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.no_management') }}</h4></label>
            <!-- no_management Form -->
            <div id="no_management_form_container">
                <div id="no_management_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.date_start') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_formed') }}" id="no_management_date_start" name="no_management_date_start" value="{{ ($management->start != '0000-00-00' ? date('d-m-Y', strtotime($management->start)) : '') }}" {{ $disable? "readonly" : ""}}/>
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
                                <label>{{ trans('app.forms.date_end') }}</label>
                                <label class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="{{ trans('app.forms.date_formed') }}" id="no_management_date_end" name="no_management_date_end" value="{{ ($management->end != '0000-00-00' ? date('d-m-Y', strtotime($management->end)) : '') }}" {{ $disable? "readonly" : ""}}/>
                                    <span class="input-group-addon">
                                        <i class="icmn-calendar"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <hr/>
    
    @if (! $management->bankruptcy)
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="bankruptcy" id="bankruptcy" {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.bankruptcy') }}</h4></label>
            <!-- bankruptcy Form -->
            <div id="bankruptcy_form_container" style="display:none">
                <div id="bankruptcy_form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.remarks') }}</label>
                                <textarea class="form-control" rows="3" id="bankruptcy_remarks" name="bankruptcy_remarks" {{ $disable? "readonly" : ""}}>{{$management->bankruptcy_remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <input type="checkbox" name="bankruptcy" id="bankruptcy" {{($management->bankruptcy ? " checked" : "")}} {{ $disable? "disabled" : ""}}/>
            <label><h4> {{ trans('app.forms.bankruptcy') }}</h4></label>
            <!-- bankruptcy Form -->
            <div id="bankruptcy_form_container">
                <div id="bankruptcy_form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ trans('app.forms.remarks') }}</label>
                                <textarea class="form-control" rows="3" id="bankruptcy_remarks" name="bankruptcy_remarks" {{ $disable? "readonly" : ""}}>{{$management->bankruptcy_remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="form-actions">
        <?php if ($update_permission == 1) { ?>
            <input type="hidden" id="file_id" name="file_id" value="{{ \Helper\Helper::encode(Config::get('constant.module.cob.file.name'), $file->id) }}"/>
            <input type="hidden" id="reference_id" name="reference_id" value="{{ ($management->reference_id ? $management->reference_id : $management->id) }}"/>
            <button type="submit" class="btn btn-own" id="submit_button" {{ $disable? "disabled" : ""}}>{{ trans('app.forms.submit') }}</button>
        <?php } ?>

        @if ($file->is_active != 2)
        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
        @else
        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileListBeforeVP')}}'">{{ trans('app.forms.cancel') }}</button>
        @endif
    </div>
</form>
<script>
    $(function() {
        containerChecking('is_developer');
        containerChecking('liquidator');
        containerChecking('is_jmb');
        containerChecking('is_mc');
        containerChecking('is_agent');
        containerChecking('is_others');
        containerChecking('no_management');
        containerChecking('under_10_units');
        containerChecking('bankruptcy');

        $('#is_developer').click(function () {
            containerChecking('is_developer');
        });
        $('#liquidator').click(function () {
            containerChecking('liquidator');
        });
        $('#is_jmb').click(function () {
            containerChecking('is_jmb');
        });
        $('#is_mc').click(function () {
            containerChecking('is_mc');
        });
        $('#is_agent').click(function () {
            containerChecking('is_agent');
        });
        $('#is_others').click(function () {
            containerChecking('is_others');
        });
        $('#no_management').click(function () {
            containerChecking('no_management');
        });
        $('#under_10_units').click(function () {
            containerChecking('under_10_units');
        });
        $('#bankruptcy').click(function () {
            containerChecking('bankruptcy');
        });
    });

    function containerChecking(id) {
        if ($('#' + id).is(':checked')) {
            $("#" + id.replace('is_', '') + "_form_container").fadeIn(500);
            $("#" + id.replace('is_', '') + '_add_more_row').fadeIn(500);
            if(id != 'no_management' && $('#no_management').is(':checked')) {
                $('#no_management').prop('checked', false);
                $("#no_management_form_container").fadeOut(0);
            } else if((id == 'no_management' && $('#no_management').is(':checked'))) {
                $('#is_developer').prop('checked', false);
                $("#developer_form_container").fadeOut(0);
                $('#developer_add_more_row').fadeOut(0);
                $('#liquidator').prop('checked', false);
                $("#liquidator_form_container").fadeOut(0);
                $('#liquidator_add_more_row').fadeOut(0);
                $('#is_jmb').prop('checked', false);
                $("#jmb_form_container").fadeOut(0);
                $('#jmb_add_more_row').fadeOut(0);
                $('#is_mc').prop('checked', false);
                $("#mc_form_container").fadeOut(0);
                $('#mc_add_more_row').fadeOut(0);
                $('#is_agent').prop('checked', false);
                $("#agent_form_container").fadeOut(0);
                $('#agent_add_more_row').fadeOut(0);
                $('#is_others').prop('checked', false);
                $("#others_form_container").fadeOut(0);
                $('#others_add_more_row').fadeOut(0);
                $('#under_10_units').prop('checked', false);
                $("#under_10_units_form_container").fadeOut(0);
                $('#bankruptcy').prop('checked', false);
                $("#bankruptcy_form_container").fadeOut(0);
            }
        } else {
            $("#" + id.replace('is_', '') + "_form_container").fadeOut(0);
            $("#" + id.replace('is_', '') + '_add_more_row').fadeOut(0);
            if(id != 'no_management' && (!$('#is_developer').is(':checked') && !$('#is_jmb').is(':checked') && !$('#is_mc').is(':checked')
            && !$('#is_agent').is(':checked') && !$('#is_others').is(':checked') && !$('#no_management').is(':checked') 
            && !$('#under_10_units').is(':checked') && !$('#bankruptcy').is(':checked'))) {
                $('#no_management').prop('checked', true);
                $("#no_management_form_container").fadeIn(500);
            }
        }
    }
</script>