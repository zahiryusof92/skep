<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title id="">{{ trans('app.app_name') }}</title>

        <link rel="apple-touch-icon" type="image/png" sizes="57x57" href="{{asset('assets/common/img/favicon-57x57.png')}}">
        <link rel="apple-touch-icon" type="image/png" sizes="72x72" href="{{asset('assets/common/img/favicon-72x72.png')}}">
        <link rel="apple-touch-icon" type="image/png" sizes="96x96" href="{{asset('assets/common/img/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" href="{{asset('assets/common/img/favicon.png')}}">
        <link href="{{ asset('assets/common/img/favicon.ico') }}" rel="shortcut icon">

        <!-- Vendors Styles -->
        <!-- v1.0.0 -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}">

        <!-- Clean UI Styles -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/main.css') }}">

        <!-- Vendors Scripts -->
        <!-- v1.0.0 -->
        <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/autosize/dist/autosize.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/bootstrap-show-password/bootstrap-show-password.min.js') }}"></script>
        
        <!-- BlockUI -->
        <script src="{{ asset('assets/common/js/blockUI/blockUI.js')}}"></script>
        
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-9XT54L4WFR"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-9XT54L4WFR');
        </script>

    </head>

    <body class="theme-default single-page">

        <!-- BEGIN CONTENT -->
        <section class="page-content">
            @yield('content')
        </section>
        <!-- END CONTENT -->

        <div class="main-backdrop"><!-- --></div>
    </body>
</html>
