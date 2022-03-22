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
                            @if ($file->management->draft)
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="{{URL::action('DraftController@management', \Helper\Helper::encode($file->id))}}">{{ trans('app.forms.management') }}</a>
                            </li>
                            @endif                            
                            <li class="nav-item">
                                <a class="nav-link active custom-tab">{{ trans('app.forms.others') }}</a>
                            </li>                            
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="others_tab" role="tabpanel">

                                @if ($other_details->draft)
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">                                    
                                        <div class="col-lg-12">
                                            <h4>{{ trans('app.forms.detail') }} <span class="label label-danger">{{ trans('app.forms.draft') }}</span></h4>
                                            <form id="others_draft">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <!-- Form -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.name') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" value="{{($other_details->draft ? $other_details->draft->name : '')}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.photo') }}</label>
                                                                    <br/>
                                                                    @if ($other_details->draft && $other_details->draft->image_url != "")
                                                                    <a href="{{asset($other_details->draft->image_url)}}" target="_blank"><img src="{{asset($other_details->draft->image_url)}}" style="width:50%; cursor: pointer;"/></a>
                                                                    @else
                                                                    <p>{{ trans('No information') }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.latitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.latitude') }}" value="{{$other_details->draft->latitude}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.longitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.longitude') }}" value="{{$other_details->draft->longitude}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if ($other_details->draft->latitude != "0" && $other_details->draft->longitude != "0")
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <a href="https://www.google.com.my/maps/preview?q={{$other_details->draft->latitude}},{{$other_details->draft->longitude}}" target="_blank">
                                                                        <button type="button" class="btn btn-success">
                                                                            <i class="fa fa-map-marker"> {{ trans('app.forms.view_map') }}</i>
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.description') }}</label>
                                                                    <textarea class="form-control" rows="4" placeholder="{{ trans('app.forms.description') }}" readonly="">{{($other_details->draft ? $other_details->draft->description : '')}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.pms_system') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.pms_system') }}" value="{{($other_details->draft ? $other_details->draft->pms_system : '')}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.owner_occupied') }}</label>
                                                                    <select class="form-control" disabled="">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details->draft && $other_details->draft->owner_occupied == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details->draft && $other_details->draft->owner_occupied == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.rented') }}</label>
                                                                    <select class="form-control" disabled="">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details->draft && $other_details->draft->rented == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details->draft && $other_details->draft->rented == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.lphs_donation') }}</label>
                                                                    <select class="form-control" disabled="">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details->draft && $other_details->draft->bantuan_lphs == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details->draft && $other_details->draft->bantuan_lphs == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.other_donation') }}</label>
                                                                    <select class="form-control" disabled="">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details->draft && $other_details->draft->bantuan_others == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details->draft && $other_details->draft->bantuan_others == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.rumah_selangorku') }}</label>
                                                                    <select class="form-control select2" disabled="">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="none" {{ ($other_details->draft && $other_details->draft->rsku == 'none' ? " selected" : "") }}>- {{ trans('app.forms.none') }} -</option>
                                                                        <option value="< 42,000" {{ ($other_details->draft && $other_details->draft->rsku == '< 42,000' ? " selected" : "") }}>< 42,000</option>
                                                                        <option value="< 100,000" {{ ($other_details->draft && $other_details->draft->rsku == '< 100,000' ? " selected" : "") }}>< 100,000</option>
                                                                        <option value="< 180,000" {{ ($other_details->draft && $other_details->draft->rsku == '< 180,000' ? " selected" : "") }}>< 180,000</option>
                                                                        <option value="< 250,000" {{ ($other_details->draft && $other_details->draft->rsku == '< 250,000' ? " selected" : "") }}>< 250,000</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.water_meter') }}</label>
                                                                    <select class="form-control select2" disabled="">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="none" {{ ($other_details->draft && $other_details->draft->water_meter == 'none' ? " selected" : "") }}>- {{ trans('app.forms.none') }} -</option>
                                                                        <option value="BULK" {{ ($other_details->draft && $other_details->draft->water_meter == 'BULK' ? " selected" : "") }}>{{ trans('app.forms.bulk') }}</option>
                                                                        <option value="INDIVIDUAL" {{ ($other_details->draft && $other_details->draft->water_meter == 'INDIVIDUAL' ? " selected" : "") }}>{{ trans('app.forms.individual') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.tnb') }}</label>
                                                                    <select id="tnb" class="form-control select2" disabled="">
                                                                        @foreach ($tnbLists as $key => $val)
                                                                            <option value="{{ $key }}" {{ ($other_details && $other_details->tnb == $key ? "selected" : "") }}>{{ $val }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.malay_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.malay_composition') }}" value="{{$other_details->draft ? $other_details->draft->malay_composition : ''}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.chinese_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.chinese_composition') }}" value="{{$other_details->draft ? $other_details->draft->chinese_composition : ''}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.indian_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.indian_composition') }}" value="{{$other_details->draft ? $other_details->draft->indian_composition : ''}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.others_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.others_composition') }}" value="{{$other_details->draft ? $other_details->draft->others_composition : ''}}" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.foreigner_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.foreigner_composition') }}" value="{{$other_details->draft ? $other_details->draft->foreigner_composition : ''}}" readonly="">
                                                                </div>
                                                            </div>
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
                                                url: "{{ URL::action('DraftController@submitOthers') }}",
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
                                                        location.reload();
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
                                            <h4>{{ trans('app.forms.detail') }}</h4>
                                            <form id="others">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <!-- Form -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.name') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="other_details_name" value="{{($other_details ? $other_details->name : '')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.photo') }}</label>
                                                            <br/>
                                                            @if ($other_details && $other_details->image_url != "")
                                                            <a href="{{asset($other_details->image_url)}}" target="_blank"><img src="{{asset($other_details->image_url)}}" style="width:50%; cursor: pointer;"/></a>
                                                            @else
                                                            <p>{{ trans('No information') }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.latitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.latitude') }} " id="latitude" value="{{$other_details->latitude}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.longitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.longitude') }} " id="longitude" value="{{$other_details->longitude}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if ($other_details->latitude != "0" && $other_details->longitude != "0")
                                                        <div class="row">
                                                            <div class="col-md-12">
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
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.description') }}</label>
                                                                    <textarea class="form-control" rows="4" id="other_details_description" placeholder="{{ trans('app.forms.description') }}">{{($other_details ? $other_details->description : '')}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.pms_system') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.pms_system') }}" id="pms_system" value="{{($other_details ? $other_details->pms_system : '')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.owner_occupied') }}</label>
                                                                    <select id="owner_occupied" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->owner_occupied == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->owner_occupied == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.rented') }}</label>
                                                                    <select id="rented" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->rented == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->rented == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.lphs_donation') }}</label>
                                                                    <select id="bantuan_lphs" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->bantuan_lphs == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->bantuan_lphs == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.other_donation') }}</label>
                                                                    <select id="bantuan_others" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->bantuan_others == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->bantuan_others == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.rumah_selangorku') }}</label>
                                                                    <select id="rsku" class="form-control select2">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="none" {{ ($other_details && $other_details->rsku == 'none' ? " selected" : "") }}>- {{ trans('app.forms.none') }} -</option>
                                                                        <option value="< 42,000" {{ ($other_details && $other_details->rsku == '< 42,000' ? " selected" : "") }}>< 42,000</option>
                                                                        <option value="< 100,000" {{ ($other_details && $other_details->rsku == '< 100,000' ? " selected" : "") }}>< 100,000</option>
                                                                        <option value="< 180,000" {{ ($other_details && $other_details->rsku == '< 180,000' ? " selected" : "") }}>< 180,000</option>
                                                                        <option value="< 250,000" {{ ($other_details && $other_details->rsku == '< 250,000' ? " selected" : "") }}>< 250,000</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.water_meter') }}</label>
                                                                    <select id="water_meter" class="form-control select2">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="none" {{ ($other_details && $other_details->water_meter == 'none' ? " selected" : "") }}>- {{ trans('app.forms.none') }} -</option>
                                                                        <option value="BULK" {{ ($other_details && $other_details->water_meter == 'BULK' ? " selected" : "") }}>{{ trans('app.forms.bulk') }}</option>
                                                                        <option value="INDIVIDUAL" {{ ($other_details && $other_details->water_meter == 'INDIVIDUAL' ? " selected" : "") }}>{{ trans('app.forms.individual') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(str_contains(Request::url(), 'https://ecob.mps.gov.my/'))
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.tnb') }}</label>
                                                                    <select id="tnb" class="form-control select2">
                                                                        @foreach ($tnbLists as $key => $val)
                                                                            <option value="{{ $key }}" {{ ($other_details && $other_details->tnb == $key ? "selected" : "") }}>{{ $val }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.malay_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.malay_composition') }}" id="malay_composition" value="{{$other_details ? $other_details->malay_composition : ''}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.chinese_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.chinese_composition') }}" id="chinese_composition" value="{{$other_details ? $other_details->chinese_composition : ''}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.indian_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.indian_composition') }}" id="indian_composition" value="{{$other_details ? $other_details->indian_composition : ''}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.others_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.others_composition') }}" id="others_composition" value="{{$other_details ? $other_details->others_composition : ''}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.foreigner_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.foreigner_composition') }}" id="foreigner_composition" value="{{$other_details ? $other_details->foreigner_composition : ''}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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