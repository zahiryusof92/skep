<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ trans('app.app_name') }}</title>

    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-size: 14px;
            margin-top: 6cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }
    </style>
</head><body>
    @include('eservice.mbpj.component.header')

    @include('eservice.mbpj.component.footer')

    @yield('content')
    
</body></html>