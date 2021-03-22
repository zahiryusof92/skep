@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 15) {
        $insert_permission = $permission->insert_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Vertical Form -->
                    <form id="add_developer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="name">
                                    <div id="name_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.address') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.address') }}" id="address1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="address2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="address3">
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
                                        <option value="{{$cities->id}}">{{$cities->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="city_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.postcode') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="poscode">
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
                                        <option value="{{$states->id}}">{{$states->name}}</option>
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
                                        <option value="{{$countries->id}}">{{$countries->name}}</option>
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
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no">
                                    <div id="phone_no_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.fax_number') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="fax_no">
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
                                        <option value="1">{{ trans('app.forms.active') }}</option>
                                        <option value="0">{{ trans('app.forms.inactive') }}</option>
                                    </select>
                                    <div id="is_active_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.remarks') }}</label>
                                    <textarea class="form-control" rows="3" id="remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <?php if ($insert_permission == 1) { ?>
                            <button type="button" class="btn btn-own" id="submit_button" onclick="addAgent()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('SettingController@agent')}}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                    </form>
                    <!-- End Vertical Form -->
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>

    function addAgent() {
        $("#loading").css("display", "inline-block");

        var name = $("#name").val(),
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
                url: "{{ URL::action('SettingController@submitAgent') }}",
                type: "POST",
                data: {
                    name: name,
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
                    is_active: is_active
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.agents.store') }}</span>", function () {
                            window.location = '{{URL::action("SettingController@agent") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }
</script>
<!-- End Page Scripts-->

@stop
