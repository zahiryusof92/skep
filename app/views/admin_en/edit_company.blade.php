@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 4) {
        $update_permission = $permission->update_permission;
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
                    <form id="company">
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
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.company_name') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.company_name') }}" id="name" value="{{$company->name}}">
                                    <div id="name_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.short_name') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.short_name') }}" id="short_name" value="{{$company->short_name}}">
                                    <div id="short_name_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.roc_rob_number') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.roc_rob_number') }}" id="rob_roc_no" value="{{$company->rob_roc_no}}">
                                    <div id="rob_roc_no_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.address1') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.address1') }}" id="address1" value="{{$company->address1}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="address2" value="{{$company->address2}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="address3" value="{{$company->address3}}">
                                    <div id="address_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.city') }}</label>
                                    <select class="form-control" id="city">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($city as $cities)
                                        <option value="{{$cities->id}}" {{($company->city == $cities->id ? " selected" : "")}}>{{$cities->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="city_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.postcode') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.postcode') }}" id="poscode" value="{{$company->poscode}}">
                                    <div id="poscode_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.state') }}</label>
                                    <select class="form-control" id="state">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($state as $states)
                                        <option value="{{$states->id}}" {{($company->state == $states->id ? " selected" : "")}}>{{$states->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="state_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.country') }}</label>
                                    <select class="form-control" id="country">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($country as $countries)
                                        <option value="{{$countries->id}}" {{($company->country == $countries->id ? " selected" : "")}}>{{$countries->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="country_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.office_phone_number') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.office_phone_number') }}" id="phone_no" value="{{$company->phone_no}}">
                                    <div id="phone_no_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.fax_number') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.fax_number') }}" id="fax_no" value="{{$company->fax_no}}">
                                    <div id="fax_no_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><span style="color: red; font-style: italic;">* </span>{{ trans('app.forms.email') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email" value="{{$company->email}}">
                                    <div id="email_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="upload" enctype="multipart/form-data" method="post" action="{{url('logoImage')}}" autocomplete="off">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.logo') }}</label>
                                    <br />
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="file" name="image" id="image" />
                                    <br />
                                    <!--<small>Max image size: MB</small>-->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div id="output">
                                        @if ($company->image_url != "")
                                        <img src='{{asset($company->image_url)}}' style='width:150px; cursor: pointer;' onclick='window.open("{{asset($company->image_url)}}")'/>
                                        @endif
                                    </div>
                                    <div id="validation-errors"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="upload_nav_image" enctype="multipart/form-data" method="post" action="{{url('navbarImage')}}" autocomplete="off">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.navbar_image') }}</label>
                                    <br />
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="file" name="nav_image" id="nav_image" />
                                    <br />
                                    <!--<small>Max image size: MB</small>-->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div id="output_nav_image">
                                        @if ($company->nav_image_url != "")
                                        <img src='{{asset($company->nav_image_url)}}' style='width:300px; cursor: pointer;' onclick='window.open("{{asset($company->nav_image_url)}}")'/>
                                        @endif
                                    </div>
                                    <div id="nav_validation-errors"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form role="form">
                        <div class="form-actions">
                            <input type="hidden" id="image_url" value="{{$company->image_url}}"/>
                            <input type="hidden" id="nav_image_url" value="{{$company->nav_image_url}}"/>
                            <?php if ($update_permission == 1) { ?>
                            <button type="button" class="btn btn-primary" id="submit_button" onclick="editCompany()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@editCompany')}}'">{{ trans('app.forms.cancel') }}</button>
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
    $(document).ready(function () {
        $(function() {
            $("#image").change(function() {
                $("#validation-errors").empty(); // To remove the previous error message
                var file = this.files[0];
                var imagefile = file.type;
                var match = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]) || (imagefile == match[3]))) {
                    $("#validation-errors").html("<span id='error'>{{ trans('app.forms.please_select_valid_image') }}</span><br/>" + "<span id='error_message'>{{ trans('app.forms.only_image_allowed') }}</span>");
                    $("#validation-errors").css("color", "red");
                    return false;
                }
                else {
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        function imageIsLoaded(e) {
            $("#image").css("color", "green");
            $('#output').css("display", "block");
            $("#output").html("<img id='previewing' style='width: 50%;'/>");
            $('#previewing').attr('src', e.target.result);
        };

        $(function() {
            $("#nav_image").change(function() {
                $("#nav_validation-errors").empty(); // To remove the previous error message
                var nav_file = this.files[0];
                var navImagefile = nav_file.type;
                var nav_match = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
                if (!((navImagefile == nav_match[0]) || (navImagefile == nav_match[1]) || (navImagefile == nav_match[2])  || (navImagefile == match[3]))) {
                    $("#nav_validation-errors").html("<span id='error'>{{ trans('app.forms.please_select_valid_image') }}</span><br/>" + "<span id='error_message'>{{ trans('app.forms.only_image_allowed') }}</span>");
                    $("#nav_validation-errors").css("color", "red");
                    return false;
                }
                else {
                    var reader = new FileReader();
                    reader.onload = navImageIsLoaded;
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        function navImageIsLoaded(e) {
            $("#nav_image").css("color", "green");
            $('#output_nav_image').css("display", "block");
            $("#output_nav_image").html("<img id='nav_previewing' style='width: 50%;'/>");
            $('#nav_previewing').attr('src', e.target.result);
        };

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

        $('body').delegate('#image', 'change', function () {
            $('#upload').ajaxForm(options).submit();
        });

        $('body').delegate('#nav_image', 'change', function () {
            $('#upload_nav_image').ajaxForm(options2).submit();
        });
    });

    function showRequest(formData, jqForm, options) {
        $("#validation-errors").hide().empty();
        $("#output").css('display', 'none');
        return true;
    }
    function showRequest2(formData, jqForm, options) {
        $("#nav_validation-errors").hide().empty();
        $("#output_nav_image").css('display', 'none');
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
        } else {
            $("#image_url").val(response.file);
        }
    }
    function showResponse2(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#nav_validation-errors").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#nav_validation-errors").show();
        } else {
            $("#nav_image_url").val(response.file);
        }
    }

    function editCompany() {
        $("#loading").css("display", "inline-block");

        var name = $("#name").val(),
                short_name = $("#short_name").val(),
                rob_roc_no = $("#rob_roc_no").val(),
                address1 = $("#address1").val(),
                address2 = $("#address2").val(),
                address3 = $("#address3").val(),
                city = $("#city").val(),
                poscode = $("#poscode").val(),
                state = $("#state").val(),
                country = $("#country").val(),
                phone_no = $("#phone_no").val(),
                fax_no = $("#fax_no").val(),
                email = $("#email").val(),
                image_url = $("#image_url").val(),
                nav_image_url = $("#nav_image_url").val();

        var error = 0;

        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required",  ["attribute"=>"Name"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }
        if (short_name.trim() == "") {
            $("#short_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required",  ["attribute"=>"Short Name"]) }}</span>');
            $("#short_name_error").css("display", "block");
            error = 1;
        }
        if (rob_roc_no.trim() == "") {
            $("#rob_roc_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required",  ["attribute"=>"ROC / ROB No"]) }}</span>');
            $("#rob_roc_no_error").css("display", "block");
            error = 1;
        }
        if (address1.trim() == "") {
            $("#address_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required",  ["attribute"=>"Address"]) }}</span>');
            $("#address_error").css("display", "block");
            error = 1;
        }
        if (poscode.trim() == "") {
            $("#poscode_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required",  ["attribute"=>"Postcode"]) }}</span>');
            $("#poscode_error").css("display", "block");
            error = 1;
        }
        if (city.trim() == "") {
            $("#city_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select",  ["attribute"=>"City"]) }}</span>');
            $("#city_error").css("display", "block");
            error = 1;
        }
        if (state.trim() == "") {
            $("#state_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select",  ["attribute"=>"State"]) }}</span>');
            $("#state_error").css("display", "block");
            error = 1;
        }
        if (country.trim() == "") {
            $("#country_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select",  ["attribute"=>"Country"]) }}</span>');
            $("#country_error").css("display", "block");
            error = 1;
        }
        if (email.trim() == "") {
            $("#email_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required",  ["attribute"=>"Email"]) }}</span>');
            $("#email_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitEditCompany') }}",
                type: "POST",
                data: {
                    name: name,
                    short_name: short_name,
                    rob_roc_no: rob_roc_no,
                    address1: address1,
                    address2: address2,
                    address3: address3,
                    city: city,
                    poscode: poscode,
                    state: state,
                    country: country,
                    phone_no: phone_no,
                    fax_no: fax_no,
                    email: email,
                    image_url: image_url,
                    nav_image_url: nav_image_url,
                    id: '{{$company->id}}'

                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.organization.update') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@company") }}';
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
