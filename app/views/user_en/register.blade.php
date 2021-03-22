@extends('layout.english_layout.loginlayout')

@section('content')

<?php
$company = Company::orderBy('id')->first();
?>

<div class="page-content-inner" style="background-image: url({{asset('assets/common/img/temp/login/4.jpg')}})">

    <!-- Login Page -->

    <div class="row">
        <div class="col-lg-4">
            <div class="logo">
                <a href="#">

                </a>
            </div>
        </div>
    </div>


    <div class="single-page-block">
        <div class="single-page-block-inner effect-3d-element">
            <div class="blur-placeholder"><!-- --></div>
            <div class="single-page-block-form">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{asset($company->image_url)}}" style="width: 100px;" alt="" />
                    </div>
                    <div class="col-md-9">
                        <div class="vertical-align margin-top-20">
                            <div class="vertical-align-middle">
                                <h5>{{ trans('app.app_name') }}</h5>
                                <h4 style="color: darkblue;">{{$company->name}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <br/><br/>
                <h3 class="text-center">
                    <i class="icmn-enter margin-right-10"></i>
                    {{$title}}
                </h3>
                <form>
                    <div class="form-group">
                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.username') }}" id="username">
                        <div id="username_error" style="display:none;"></div>
                        <div id="username_in_use" style="display:none"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{ trans('app.forms.password') }}" id="password">
                        <div id="password_error" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{ trans('app.forms.password_confirm') }}" id="retype_password">
                        <div id="retype_password_error" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.full_name') }}" id="name">
                        <div id="name_error" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email">
                        <div id="email_error" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no">
                        <div id="phone_no_error" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <select id="company" class="form-control">
                            <option value="">{{ trans('app.forms.please_select') }}</option>
                            @foreach ($cob as $cobs)
                            <option value="{{ $cobs->id }}">{{ $cobs->name }} - {{ $cobs->short_name }}</option>
                            @endforeach
                        </select>
                        <div id="company_error" style="display:none;"></div>
                    </div>
                    <div class="form-actions text-center">
                        <button type="button" class="btn btn-own width-150" id="submit_button" onclick="register()">{{ trans('app.forms.register') }}</button>
                    </div>
                    <a href="{{URL::action('UserController@login')}}">{{ trans('app.forms.login') }}</a>
                </form>
            </div>
        </div>
    </div>
    <div class="single-page-block-footer text-center">

    </div>
    <!-- End Login Page -->
</div>

<!-- Page Scripts -->
<script>
    $(function () {
        // Add class to body for change layout settings
        $('body').addClass('single-page single-page-inverse');

        $('#password, #retype_password').password({
            eyeClass: '',
            eyeOpenClass: 'icmn-eye',
            eyeCloseClass: 'icmn-eye-blocked'
        });

        // Set Background Image for Form Block
        function setImage() {
            var imgUrl = $('.page-content-inner').css('background-image');

            $('.blur-placeholder').css('background-image', imgUrl);
        }

        function changeImgPositon() {
            var width = $(window).width(),
                    height = $(window).height(),
                    left = -(width - $('.single-page-block-inner').outerWidth()) / 2,
                    top = -(height - $('.single-page-block-inner').outerHeight()) / 2;


            $('.blur-placeholder').css({
                width: width,
                height: height,
                left: left,
                top: top
            });
        }

        setImage();
        changeImgPositon();

        $(window).on('resize', function () {
            changeImgPositon();
        });
    });

    function register() {
        $("#loading").css("display", "inline-block");
        $("#username_error").css("display", "none");
        $("#username_in_use").css("display", "none");
        $("#password_error").css("display", "none");
        $("#retype_password_error").css("display", "none");
        $("#name_error").css("display", "none");
        $("#email_error").css("display", "none");
        $("#phone_no_error").css("display", "none");
        $("#company_error").css("display", "none");

        var username = $("#username").val(),
                password = $("#password").val(),
                retype_password = $("#retype_password").val(),
                name = $("#name").val(),
                email = $("#email").val(),
                phone_no = $("#phone_no").val(),
                company = $("#company").val();

        var error = 0;

        if (username.trim() === "") {
            $("#username_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Username"]) }}</span>');
            $("#username_error").css("display", "block");
            $("#username_in_use").css("display", "none");
            error = 1;
        }
        if (password.trim() === "") {
            $("#password_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Password"]) }}</span>');
            $("#password_error").css("display", "block");
            error = 1;
        }
        if (retype_password.trim() === "") {
            $("#retype_password_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Confirm Password"]) }}</span>');
            $("#retype_password_error").css("display", "block");
            error = 1;
        }
        if (password.trim() !== retype_password.trim()) {
            $("#retype_password_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.wrong_confirm_password") }}</span>');
            $("#retype_password_error").css("display", "block");
            error = 1;
        }
        if (name.trim() === "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Full Name"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }
        if (email.trim() === "" || !IsEmail(email)) {
            $("#email_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Email"]) }}</span>');
            $("#email_error").css("display", "block");
            error = 1;
        }
        if (phone_no.trim() === "" || isNaN(phone_no)) {
            $("#phone_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Phone Number"]) }}</span>');
            $("#phone_no_error").css("display", "block");
            error = 1;
        }
        if (company.trim() === "") {
            $("#company_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"COB"]) }}</span>');
            $("#company_error").css("display", "block");
            error = 1;
        }

        if (error === 0) {
            $.ajax({
                url: "{{ URL::action('UserController@submitRegister') }}",
                type: "POST",
                data: {
                    username: username,
                    password: password,
                    name: name,
                    email: email,
                    phone_no: phone_no,
                    company: company
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() === "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.successfully_register') }}</span>", function () {
                            window.location = '{{URL::action("UserController@login") }}';
                        });
                    } else if (data.trim() === "username_in_use") {
                        $("#username_in_use").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.exist2", ["attribute"=>"Username"]) }}</span>');
                        $("#username_in_use").css("display", "block");
                        $("#username_error").css("display", "none");
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>
<!-- End Page Scripts -->

@stop
