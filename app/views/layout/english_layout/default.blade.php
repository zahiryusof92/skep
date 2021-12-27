<!DOCTYPE html>
<html lang="{{  Session::get('lang') }}">
    <head>
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
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jscrollpane/style/jquery.jscrollpane.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/ladda/dist/ladda-themeless.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/fullcalendar/dist/fullcalendar.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/cleanhtmlaudioplayer/src/player.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/cleanhtmlvideoplayer/src/player.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-sweetalert/dist/sweetalert.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/summernote/dist/summernote.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl.carousel/dist/assets/owl.carousel.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/ionrangeslider/css/ion.rangeSlider.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/c3/c3.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/chartist/dist/chartist.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/fancybox/jquery.fancybox.min.css')}}">

        <!-- Clean UI Styles -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/main.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/materialize/materialize.css')}}">

        <!--Rating Star-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/common/css/source/ratingstar/rating.css')}}">

        <style>
            /* body {
                background: #ffffff !important;
            } */
            .panel {
                background: #ffffff !important;
            }
            .star-checked {
                color: orange;
            }
            .modal-open .select2-container--open { 
                z-index: 999999 !important; 
                width:100% !important; 
            }
        </style>

        <!-- Vendors Scripts -->
        <!-- v1.0.0 -->
        <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/tether/dist/js/tether.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/jquery-mousewheel/jquery.mousewheel.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/jscrollpane/script/jquery.jscrollpane.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/spin.js/spin.js')}}"></script>
        <script src="{{ asset('assets/vendors/ladda/dist/ladda.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/select2/dist/js/select2.full.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/html5-form-validation/dist/jquery.validation.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/jquery-typeahead/dist/jquery.typeahead.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/jquery-mask-plugin/dist/jquery.mask.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/autosize/dist/autosize.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/bootstrap-show-password/bootstrap-show-password.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/moment/min/moment.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/fullcalendar/dist/fullcalendar.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/cleanhtmlaudioplayer/src/jquery.cleanaudioplayer.js')}}"></script>
        <script src="{{ asset('assets/vendors/cleanhtmlvideoplayer/src/jquery.cleanvideoplayer.js')}}"></script>
        <script src="{{ asset('assets/vendors/bootstrap-sweetalert/dist/sweetalert.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/summernote/dist/summernote.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/owl.carousel/dist/owl.carousel.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/ionrangeslider/js/ion.rangeSlider.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/nestable/jquery.nestable.js')}}"></script>
        <script src="{{ asset('assets/vendors/datatables/media/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/datatables-fixedcolumns/js/dataTables.fixedColumns.js')}}"></script>
        <script src="{{ asset('assets/vendors/datatables-responsive/js/dataTables.responsive.js')}}"></script>
        <script src="{{ asset('assets/vendors/editable-table/mindmup-editabletable.js')}}"></script>
        <script src="{{ asset('assets/vendors/d3/d3.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/c3/c3.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/chartist/dist/chartist.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/peity/jquery.peity.min.js')}}"></script>
        <!-- v1.0.1 -->
        <script src="{{ asset('assets/vendors/chartist-plugin-tooltip/dist/chartist-plugin-tooltip.min.js')}}"></script>
        <!-- v1.1.1 -->
        <script src="{{ asset('assets/vendors/gsap/src/minified/TweenMax.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/hackertyper/hackertyper.js')}}"></script>
        <script src="{{ asset('assets/vendors/jquery-countTo/jquery.countTo.js')}}"></script>

        <!-- Fancybox -->
        <script src="{{ asset('assets/vendors/fancybox/jquery.fancybox.min.js')}}"></script>

        <!-- Clean UI Scripts -->
        <script src="{{ asset('assets/common/js/common.js')}}"></script>
        <script src="{{ asset('assets/common/js/demo.temp.js')}}"></script>

        <!-- Bootbox Scripts -->
        <script src="{{ asset('assets/common/js/bootbox/bootbox.min.js')}}"></script>

        <script src="{{ asset('assets/common/js/jQueryForm/form.js')}}"></script>

        <!-- Highcharts -->
        <script src="{{ asset('assets/highcharts/highcharts.js')}}"></script>
        <script src="{{ asset('assets/highcharts/modules/variable-pie.js')}}"></script>
        <script src="{{ asset('assets/highcharts/modules/exporting.js')}}"></script>

        <!-- Dynamic Form -->
        <script src="{{ asset('assets/common/js/dynamic-form.js')}}"></script>

        <!-- BlockUI -->
        <script src="{{ asset('assets/common/js/blockUI/blockUI.js')}}"></script>
        
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-9XT54L4WFR"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-9XT54L4WFR');
        </script>

    </head>

    <body id="" class="theme-default">
        <!-- BEGIN SIDE NAVIGATION -->
        @include('layout.english_layout.navigation')
        <!-- END SIDE NAVIGATION -->

        <!-- BEGIN TOP HEADER -->
        @include('layout.english_layout.header')
        <!-- END TOP HEADER -->

        <!-- BEGIN CONTENT -->
        <section class="page-content">
            @yield('content')

            @include('layout.english_layout.footer')
        </section>
        
        <!-- END CONTENT -->
        <div class="main-backdrop"><!-- --></div>

        <script>
            $(document).ready(function () {
                $(".numeric-only").on('keypress', function (e) {
                    var keyCode = e.which ? e.which : e.keyCode;
                    if (!(keyCode >= 48 && keyCode <= 57)) {
                        return false;
                    }
                });

                $('.select2').select2();
            });
        </script>
    </body>
</html>
