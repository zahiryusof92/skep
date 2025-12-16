<!DOCTYPE html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ trans('app.app_name') }}</title>

    <!-- Vendors Styles -->
    <!-- v1.0.0 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Clean UI Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/materialize/materialize.css') }}">
    <!-- Vendors Scripts -->
    <!-- v1.0.0 -->
    <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-wordexport/fileSaver.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-wordexport/jquery.wordexport.js') }}"></script>

    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: sans-serif;
            font-size: 14px;
            margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        .page-break {
            page-break-before: always;
            display: block;
        }
    </style>
</head>

<body class="background-white">
    <!-- BEGIN CONTENT -->
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="text-left padding-5">
                    <a href="{{ route('eservice.show', \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id)) }}"
                        class="btn btn-secondary">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;{{ trans('app.forms.back') }}
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-right padding-5">
                    <button id="export" class="btn btn-primary">
                        <i class="fa fa-download" aria-hidden="true"></i>&nbsp;{{ trans('app.forms.export_doc') }}
                    </button>
                </div>
            </div>
        </div>

        <div id="page-content">

            @if (isset($__env->getSections()['page1']))
                <div>
                    @yield('page1')
                </div>
            @endif

            @if (isset($__env->getSections()['page2']))
                <div class="page-break"></div>
                <div>
                    @yield('page2')
                </div>
            @endif

            @if (isset($__env->getSections()['page3']))
                <div class="page-break"></div>
                <div>
                    @yield('page3')
                </div>
            @endif
            
        </div>
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
