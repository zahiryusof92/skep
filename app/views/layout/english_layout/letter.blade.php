<!DOCTYPE html>
<html lang="{{  Session::get('lang') }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ trans('app.app_name') }}</title>

        <link rel="apple-touch-icon" type="image/png" sizes="57x57" href="{{asset('assets/common/img/favicon.57x57.png')}}">
        <link rel="apple-touch-icon" type="image/png" sizes="72x72" href="{{asset('assets/common/img/favicon.72x72.png')}}">
        <link rel="apple-touch-icon" type="image/png" sizes="114x114" href="{{asset('assets/common/img/favicon.114x114.png')}}">
        <link rel="apple-touch-icon" type="image/png" sizes="144x144" href="{{asset('assets/common/img/favicon.144x144.png')}}">
        <link rel="icon" type="image/png" href="{{asset('assets/common/img/favicon.png')}}">
        <link href="{{ asset('assets/common/img/favicon.ico') }}" rel="shortcut icon">

        <!-- Vendors Styles -->
        <!-- v1.0.0 -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}">

        <!-- Clean UI Styles -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/main.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/materialize/materialize.css')}}">
        <!-- Vendors Scripts -->
        <!-- v1.0.0 -->
        <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/jquery-wordexport/fileSaver.js')}}"></script>
        <script src="{{ asset('assets/vendors/jquery-wordexport/jquery.wordexport.js')}}"></script>

    </head>

    <body class="background-white">
        <!-- BEGIN CONTENT -->
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-left padding-5">
                        <a href="{{ route('cob_letter.index') }}" class="btn btn-secondary"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;{{ trans('app.forms.back') }}</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-right padding-5">
                        <button id="export" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;{{ trans('app.forms.export_doc') }}</button>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
        <!-- END CONTENT -->
        
        <script>
            $(document).ready(function($) {
                $("#export").click(function(event) {
                    $("#page-content").wordExport("{{ $filename }}");
                }); 
            });
        </script>
    </body>
</html>
