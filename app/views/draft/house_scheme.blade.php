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
                            <li class="nav-item">
                                <a class="nav-link active custom-tab">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            @if ($file->strata->draft)
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('DraftController@strata', \Helper\Helper::encode($file->id))}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            @endif
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
                            <div class="tab-pane active" id="house_scheme" role="tabpanel">
                                
                                <!-- Start House Form -->
                                @if ($house_scheme->draft)
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">                                    
                                        <div class="col-lg-12">
                                            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
                                            <h4>{{ trans('app.forms.detail') }} <span class="label label-danger">{{ trans('app.forms.draft') }}</span></h4>
                                            <!-- House Form -->
                                            <form id="house_draft">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_developer') }}" id="is_developer" value="no" {{($house_scheme->draft->is_liquidator)? "" : "checked"}} disabled>
                                                            <label class="form-check-label" for="is_developer">
                                                                {{ trans('app.forms.is_developer') }} @include('components.is_changed', ['old_field' => $house_scheme->is_liquidator, 'new_field' => $house_scheme->draft->is_liquidator])
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="developer_form">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->name, 'new_field' => $house_scheme->draft->name])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" value="{{ $house_scheme->draft->name }}" readonly=""> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.developer') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->developer, 'new_field' => $house_scheme->draft->developer])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($developer as $developers)
                                                                    <option value="{{$developers->id}}" {{($house_scheme->draft->developer == $developers->id ? " selected" : "")}}>{{$developers->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->address1, 'new_field' => $house_scheme->draft->address1])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address1') }}" value="{{$house_scheme->draft->address1}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$house_scheme->draft->address2}}" readonly="">
                                                            </div>
                                                        </div>
                                                        @include('components.is_changed', ['old_field' => $house_scheme->address2, 'new_field' => $house_scheme->draft->address2, 'class' => 'margin-top-5'])
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$house_scheme->draft->address3}}" readonly="">
                                                            </div>
                                                        </div>
                                                        @include('components.is_changed', ['old_field' => $house_scheme->address3, 'new_field' => $house_scheme->draft->address3, 'class' => 'margin-top-5'])
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" value="{{$house_scheme->draft->address4}}" readonly="">
                                                            </div>
                                                        </div>
                                                        @include('components.is_changed', ['old_field' => $house_scheme->address4, 'new_field' => $house_scheme->draft->address4, 'class' => 'margin-top-5'])
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }}</label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->city, 'new_field' => $house_scheme->draft->city])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($house_scheme->draft->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->poscode, 'new_field' => $house_scheme->draft->poscode])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$house_scheme->draft->poscode}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->state, 'new_field' => $house_scheme->draft->state])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($house_scheme->draft->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->country, 'new_field' => $house_scheme->draft->country])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($house_scheme->draft->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->phone_no, 'new_field' => $house_scheme->draft->phone_no])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" value="{{$house_scheme->draft->phone_no}}" readonly="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->fax_no, 'new_field' => $house_scheme->draft->fax_no])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" value="{{$house_scheme->draft->fax_no}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.admin_status') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->is_active, 'new_field' => $house_scheme->draft->is_active])
                                                                <select id="is_active" class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="1" {{($house_scheme->draft->is_active == '1' ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                                                    <option value="0" {{($house_scheme->draft->is_active == '0' ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                                                    <option value="2" {{($house_scheme->draft->is_active == '2' ? " selected" : "")}}>{{ trans('app.forms.before_vp') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->remarks, 'new_field' => $house_scheme->draft->remarks])
                                                                <textarea class="form-control" rows="3" readonly="">{{$house_scheme->draft->remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_liquidator') }}" id="is_liquidator" value="yes" {{($house_scheme->draft->is_liquidator)? "checked" : ""}} disabled>
                                                            <label class="form-check-label" for="is_liquidator">
                                                                {{ trans('app.forms.is_liquidator') }} @include('components.is_changed', ['old_field' => $house_scheme->is_liquidator, 'new_field' => $house_scheme->draft->is_liquidator])
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="liquidator_form">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.name') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_name, 'new_field' => $house_scheme->draft->liquidator_name])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" value="{{ $house_scheme->draft->liquidator_name }}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.liquidator') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator, 'new_field' => $house_scheme->draft->liquidator])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($liquidator as $liquidators)
                                                                    <option value="{{$liquidators->id}}" {{($house_scheme->draft->liquidator == $liquidators->id ? " selected" : "")}}>{{$liquidators->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.address') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_address1, 'new_field' => $house_scheme->draft->liquidator_address1])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address1') }}" value="{{$house_scheme->draft->liquidator_address1}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" value="{{$house_scheme->draft->liquidator_address2}}" readonly="">
                                                            </div>
                                                        </div>
                                                        @include('components.is_changed', ['old_field' => $house_scheme->liquidator_address2, 'new_field' => $house_scheme->draft->liquidator_address2, 'class' => 'margin-top-5'])
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" value="{{$house_scheme->draft->liquidator_address3}}" readonly="">
                                                            </div>
                                                        </div>
                                                        @include('components.is_changed', ['old_field' => $house_scheme->liquidator_address3, 'new_field' => $house_scheme->draft->liquidator_address3, 'class' => 'margin-top-5'])
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" value="{{$house_scheme->draft->liquidator_address4}}" readonly="">
                                                            </div>
                                                        </div>
                                                        @include('components.is_changed', ['old_field' => $house_scheme->liquidator_address4, 'new_field' => $house_scheme->draft->liquidator_address4, 'class' => 'margin-top-5'])
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.city') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_city, 'new_field' => $house_scheme->draft->liquidator_city])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($city as $cities)
                                                                    <option value="{{$cities->id}}" {{($house_scheme->draft->liquidator_city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.postcode') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_poscode, 'new_field' => $house_scheme->draft->liquidator_poscode])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" value="{{$house_scheme->draft->liquidator_poscode}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.state') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_state, 'new_field' => $house_scheme->draft->liquidator_state])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($state as $states)
                                                                    <option value="{{$states->id}}" {{($house_scheme->draft->liquidator_state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.country') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_country, 'new_field' => $house_scheme->draft->liquidator_country])
                                                                <select class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    @foreach ($country as $countries)
                                                                    <option value="{{$countries->id}}" {{($house_scheme->draft->liquidator_country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.phone_number') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_phone_no, 'new_field' => $house_scheme->draft->liquidator_phone_no])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" value="{{$house_scheme->draft->liquidator_phone_no}}" readonly="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.fax_number') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_fax_no, 'new_field' => $house_scheme->draft->liquidator_fax_no])
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" value="{{$house_scheme->draft->liquidator_fax_no}}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.admin_status') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_is_active, 'new_field' => $house_scheme->draft->liquidator_is_active])
                                                                <select id="liquidator_is_active" class="form-control select2" disabled="">
                                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                    <option value="1" {{($house_scheme->draft->liquidator_is_active == '1' ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                                                    <option value="0" {{($house_scheme->draft->liquidator_is_active == '0' ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                                                    <option value="2" {{($house_scheme->draft->liquidator_is_active == '2' ? " selected" : "")}}>{{ trans('app.forms.before_vp') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>{{ trans('app.forms.remarks') }} </label>
                                                                @include('components.is_changed', ['old_field' => $house_scheme->liquidator_remarks, 'new_field' => $house_scheme->draft->liquidator_remarks])
                                                                <textarea class="form-control" rows="3" readonly="">{{$house_scheme->draft->liquidator_remarks}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <button type="button" class="btn btn-own" id="submit_button_draft" onclick="submitDraft()">{{ trans('app.buttons.accept') }}</button>
                                                    <button type="button" class="btn btn-danger" id="reject_button" onclick="submitReject()">{{ trans('app.buttons.reject') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </section>

                                <div id="modal-content"></div>
                                <script>
                                    $(function() { 
                                        if("{{$house_scheme->draft->is_liquidator}}" > 0) {
                                            $('#liquidator_form').show();
                                            $('#developer_form').hide();
                                        } else {
                                            $('#liquidator_form').hide();
                                            $('#developer_form').show();
                                        }
                                    });
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
                                                url: "{{ URL::action('DraftController@submitHouseScheme') }}",
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
                                                        window.location = "{{URL::action('DraftController@strata', \Helper\Helper::encode($file->id))}}";
                                                    } else {
                                                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                                                    }
                                                }
                                            });
                                        });
                                    }

                                    function submitReject() {
                                        $.ajax({
                                            url: "{{ route('file.draft.reject.create') }}",
                                            type: "GET",
                                            data: {
                                                file_id: "{{ $file->id}}",
                                                type: "house_scheme"
                                            },
                                            success: function (data) {
                                                $("#modal-content").html(data);
                                                $("#file-reject").modal("show");
                                            }
                                        });
                                    }
                                </script>
                                @endif

                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">                                    
                                        <div class="col-lg-12">
                                            <h6><u>{{ trans("app.forms.before_change") }}</u></h6>
                                            <h4>{{ trans('app.forms.detail') }} <span class="label label-danger">{{ trans('app.forms.draft') }}</span></h4>
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
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_developer') }}" id="is_developer1" value="no" {{($house_scheme->is_liquidator)? "" : "checked"}}>
                                                            <label class="form-check-label" for="is_developer">
                                                                {{ trans('app.forms.is_developer') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="developer_form1">
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
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" placeholder="{{ trans('app.forms.is_liquidator') }}" id="is_liquidator1" value="yes" {{($house_scheme->is_liquidator)? "checked" : ""}}>
                                                            <label class="form-check-label" for="is_liquidator">
                                                                {{ trans('app.forms.is_liquidator') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="liquidator_form1">
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
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="address1" value="{{$house_scheme->liquidator_address1}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="address2" value="{{$house_scheme->liquidator_address2}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="address3" value="{{$house_scheme->liquidator_address3}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="{{ trans('app.forms.address4') }}" id="address4" value="{{$house_scheme->liquidator_address4}}">
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
                                                                    <option value="{{$cities->id}}" {{($house_scheme->liquidator_city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="city_error" style="display:none;"></div>
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
                                            <script>
                                                $(function() { 
                                                    if("{{$house_scheme->is_liquidator}}" > 0) {
                                                        $('#liquidator_form1').show();
                                                        $('#developer_form1').hide();
                                                    } else {
                                                        $('#liquidator_form1').hide();
                                                        $('#developer_form1').show();
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </section>
                                <!-- End House Form -->                                
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