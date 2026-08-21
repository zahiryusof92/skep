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
            font-family: sans-serif;
            font-size: 14px;
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 3cm;
        }

        .page-break {
            page-break-before: always;
        }

        .pdf-content {
            position: relative;
            z-index: 1;
        }

        .page1-content {
            margin-top: 1.5cm;
        }
    </style>
</head><body>

    @if (isset($__env->getSections()['page1']))
        <div class="pdf-content">
            <img src="{{ public_path('assets/common/img/eservice/mbsj/letterhead.jpg') }}"
                style="position: fixed; top: 0; left: 0; width: 210mm; height: 297mm; z-index: -1;" />
            <div class="page1-content">
                @yield('page1')
            </div>
        </div>
    @endif

    @if (isset($__env->getSections()['page2']))
        <div class="page-break"></div>
        @include('eservice.mbsj.component.header')
        <div>
            @yield('page2')
        </div>
    @endif

    @if (isset($__env->getSections()['page3']))
        <div class="page-break"></div>
        @include('eservice.mbsj.component.header')
        <div>
            @yield('page3')
        </div>
    @endif

</body></html>
