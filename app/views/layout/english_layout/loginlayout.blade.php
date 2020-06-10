<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title id="">{{ trans('app.app_name') }}</title>

        <link rel="apple-touch-icon" type="image/png" sizes="57x57" href="{{asset('assets/common/img/favicon.57x57.png')}}">
        <link rel="apple-touch-icon" type="image/png" sizes="72x72" href="{{asset('assets/common/img/favicon.72x72.png')}}">
	<link rel="apple-touch-icon" type="image/png" sizes="114x114" href="{{asset('assets/common/img/favicon.114x114.png')}}">
	<link rel="apple-touch-icon" type="image/png" sizes="144x144" href="{{asset('assets/common/img/favicon.144x144.png')}}">
	<link rel="icon" type="image/png" href="{{asset('assets/common/img/favicon.png')}}">
        <link href="favicon.ico" rel="shortcut icon">

        <!-- Vendors Styles -->
        <!-- v1.0.0 -->
        {{HTML::style("assets/vendors/bootstrap/dist/css/bootstrap.min.css")}}

        <!-- Clean UI Styles -->
        {{HTML::style("assets/common/css/source/main.css")}}

        <!-- Vendors Scripts -->
        <!-- v1.0.0 -->
        {{HTML::script("assets/vendors/jquery/jquery.min.js")}}
        {{HTML::script("assets/vendors/autosize/dist/autosize.min.js")}}
        {{HTML::script("assets/vendors/bootstrap-show-password/bootstrap-show-password.min.js")}}

    </head>

    <body class="theme-default">

        <!-- BEGIN CONTENT -->
        <section class="page-content">
            @yield('content')
        </section>
        <!-- END CONTENT -->

        <div class="main-backdrop"><!-- --></div>
    </body>
</html>
