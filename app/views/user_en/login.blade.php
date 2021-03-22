@extends('layout.english_layout.loginlayout')

@section('content')

<?php
if (isset($cob) && !empty($cob)) {
    $company = Company::where('short_name', $cob)->first();
} else {
    $company = Company::orderBy('id')->first();
}
?>

{{-- <div class="page-content-inner" style="background-image: url({{asset('assets/common/img/temp/login/5.jpg')}})"> --}}
<div class="page-content-inner" style="background-image: linear-gradient( rgb(39 34 34 / 80%), rgb(0 0 0 / 80%) ), url(http://localhost:8000/assets/common/img/temp/login/5.jpg);">
    
    <!-- Login Page -->
    <div class="single-page-block">
        <div class="single-page-block-inner effect-3d-element">
            {{-- <div class="blur-placeholder"><!-- --></div> --}}
            <div class="single-page-block-form">
                <div class="row login-section-title">
                    <div class="col-md-2">
                        <div class="vertical-align">
                            <div class="vertical-align-middle">
                                <img src="{{asset($company->image_url)}}" style="width: 60px;" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="vertical-align margin-top-20">
                            <div class="vertical-align-middle">
                                {{-- <h5>{{ trans('app.app_name') }}</h5> --}}
                                <h4 class="login-company-title">{{$company->name}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <h3 class="text-center">
                    <i class="icmn-enter margin-right-10"></i> {{$title}}
                </h3> --}}
                {{ Form::open(array('url'=>'/loginAction', 'class'=>'form-signin', 'method'=>'POST')) }}

                @if(Session::has('login_error'))
                <div style='text-align: center;'>
                    <span style="color:red;font-style:italic;font-size:13px;">{{Session::get('login_error')}}</span>
                </div>
                <br />
                @endif

                <div class="form-group">
                    <input id="email" class="form-control" placeholder="{{ trans('app.forms.username') }}" name="username" type="text" value="{{ Input::old('username') }}" autocomplete="email" autofocus/>
                    @if($errors->has('username'))
                    <span style="color:red;font-style:italic;font-size:13px;">{{$errors->first('username')}}</span>
                    <br />
                    @endif
                </div>

                <div class="form-group">
                    <input id="password" class="form-control password" placeholder="{{ trans('app.forms.password') }}" name="password" type="password" autocomplete="current-password"/>
                    @if($errors->has('password'))
                    <span style="color:red;font-style:italic;font-size:13px;">{{$errors->first('password')}}</span>
                    <br />
                    @endif
                </div>
                {{-- <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="remember" name="remember" id="remember" checked="" >
                            {{ trans('app.forms.remember_me') }}
                        </label>
                    </div>
                </div> --}}
                <input type="checkbox" value="remember" name="remember" id="remember" checked="" hidden>
                <div class="button-group-section">
                    <input type="hidden" name="cob" value="{{ $cob }}"/>
                    <button type="submit" class="btn btn-own width-150" id="login_button">{{ trans('app.forms.login') }}</button>
                </div>

                {{Form::close()}}

                <p style="text-align: center;"><a href="https://odesi.tech/terms.html" target="_blank">{{ trans('User Terms & Conditions') }}</a></p>
                <h6 class="text-center padding-top-5">&copy; {{ date('Y') }} ODESI ECOB SDN BHD. All rights reserved.</h6>

            </div>
        </div>   
        <div class="copyright">
            <img src="{{asset('assets/common/img/odesi/logo.png')}}" />
            <h6 class="text-center padding-top-5">&copy; {{ date('Y') }} ODESI eCOB SDN BHD. All Rights Reserved.</h6>
        </div>     
    </div>
    <!-- End Login Page -->
</div>

<!-- Page Scripts -->
<script>
    $(function () {
        $('#login_button').click(function () {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
        });

        // Add class to body for change layout settings
        $('body').addClass('single-page single-page-inverse');

        // $('#password').password({
        //     eyeClass: '',
        //     eyeOpenClass: 'icmn-eye',
        //     eyeCloseClass: 'icmn-eye-blocked'
        // });

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
</script>
<!-- End Page Scripts -->

@stop
