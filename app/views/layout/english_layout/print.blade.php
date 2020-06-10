<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
        {{HTML::style("assets/vendors/jscrollpane/style/jquery.jscrollpane.css")}}
        {{HTML::style("assets/vendors/ladda/dist/ladda-themeless.min.css")}}
        {{HTML::style("assets/vendors/select2/dist/css/select2.min.css")}}
        {{HTML::style("assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css")}}
        {{HTML::style("assets/vendors/fullcalendar/dist/fullcalendar.min.css")}}
        {{HTML::style("assets/vendors/cleanhtmlaudioplayer/src/player.css")}}
        {{HTML::style("assets/vendors/cleanhtmlvideoplayer/src/player.css")}}
        {{HTML::style("assets/vendors/bootstrap-sweetalert/dist/sweetalert.css")}}
        {{HTML::style("assets/vendors/summernote/dist/summernote.css")}}
        {{HTML::style("assets/vendors/owl.carousel/dist/assets/owl.carousel.min.css")}}
        {{HTML::style("assets/vendors/ionrangeslider/css/ion.rangeSlider.css")}}
        {{HTML::style("assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css")}}
        {{HTML::style("assets/vendors/c3/c3.min.css")}}
        {{HTML::style("assets/vendors/chartist/dist/chartist.min.css")}}

        <!-- Clean UI Styles -->
        {{HTML::style("assets/common/css/source/main.css")}}
        {{HTML::style("assets/common/css/source/materialize/materialize.css")}}

        <!--Preloader-->
        {{HTML::style("assets/common/css/source/preloader/main.css")}}

        <!--Rating Star-->
        {{HTML::style("assets/common/css/source/ratingstar/rating.css")}}

        <!-- Vendors Scripts -->
        <!-- v1.0.0 -->
        {{HTML::script("assets/vendors/jquery/jquery.min.js")}}
        {{HTML::script("assets/vendors/tether/dist/js/tether.min.js")}}
        {{HTML::script("assets/vendors/bootstrap/dist/js/bootstrap.min.js")}}
        {{HTML::script("assets/vendors/jquery-mousewheel/jquery.mousewheel.min.js")}}
        {{HTML::script("assets/vendors/jscrollpane/script/jquery.jscrollpane.min.js")}}
        {{HTML::script("assets/vendors/spin.js/spin.js")}}
        {{HTML::script("assets/vendors/ladda/dist/ladda.min.js")}}
        {{HTML::script("assets/vendors/select2/dist/js/select2.full.min.js")}}
        {{HTML::script("assets/vendors/html5-form-validation/dist/jquery.validation.min.js")}}
        {{HTML::script("assets/vendors/jquery-typeahead/dist/jquery.typeahead.min.js")}}
        {{HTML::script("assets/vendors/jquery-mask-plugin/dist/jquery.mask.min.js")}}
        {{HTML::script("assets/vendors/autosize/dist/autosize.min.js")}}
        {{HTML::script("assets/vendors/bootstrap-show-password/bootstrap-show-password.min.js")}}
        {{HTML::script("assets/vendors/moment/min/moment.min.js")}}
        {{HTML::script("assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js")}}
        {{HTML::script("assets/vendors/fullcalendar/dist/fullcalendar.min.js")}}
        {{HTML::script("assets/vendors/cleanhtmlaudioplayer/src/jquery.cleanaudioplayer.js")}}
        {{HTML::script("assets/vendors/cleanhtmlvideoplayer/src/jquery.cleanvideoplayer.js")}}
        {{HTML::script("assets/vendors/bootstrap-sweetalert/dist/sweetalert.min.js")}}
        {{HTML::script("assets/vendors/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js")}}
        {{HTML::script("assets/vendors/summernote/dist/summernote.min.js")}}
        {{HTML::script("assets/vendors/owl.carousel/dist/owl.carousel.min.js")}}
        {{HTML::script("assets/vendors/ionrangeslider/js/ion.rangeSlider.min.js")}}
        {{HTML::script("assets/vendors/nestable/jquery.nestable.js")}}
        {{HTML::script("assets/vendors/datatables/media/js/jquery.dataTables.min.js")}}
        {{HTML::script("assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js")}}
        {{HTML::script("assets/vendors/datatables-fixedcolumns/js/dataTables.fixedColumns.js")}}
        {{HTML::script("assets/vendors/datatables-responsive/js/dataTables.responsive.js")}}
        {{HTML::script("assets/vendors/editable-table/mindmup-editabletable.js")}}
        {{HTML::script("assets/vendors/d3/d3.min.js")}}
        {{HTML::script("assets/vendors/c3/c3.min.js")}}
        {{HTML::script("assets/vendors/chartist/dist/chartist.min.js")}}
        {{HTML::script("assets/vendors/peity/jquery.peity.min.js")}}
        <!-- v1.0.1 -->
        {{HTML::script("assets/vendors/chartist-plugin-tooltip/dist/chartist-plugin-tooltip.min.js")}}
        <!-- v1.1.1 -->
        {{HTML::script("assets/vendors/gsap/src/minified/TweenMax.min.js")}}
        {{HTML::script("assets/vendors/hackertyper/hackertyper.js")}}
        {{HTML::script("assets/vendors/jquery-countTo/jquery.countTo.js")}}

        <!-- Clean UI Scripts -->
        {{HTML::script("assets/common/js/common.js")}}
        {{HTML::script("assets/common/js/demo.temp.js")}}

        <!-- QR Code Scripts -->
        {{HTML::script("assets/common/js/qrcode/jquery.qrcode.js")}}
        {{HTML::script("assets/common/js/qrcode/qrcode.js")}}

        <!-- Bootbox Scripts -->
        {{HTML::script("assets/common/js/bootbox/bootbox.min.js")}}

        {{HTML::script("assets/common/js/jQueryForm/form.js")}}

        <!-- Highcharts -->
        {{HTML::script("assets/highcharts/highcharts.js")}}
        {{HTML::script("assets/highcharts/modules/exporting.js")}}

    </head>

    <body class="background-white">
        <!-- BEGIN CONTENT -->
            @yield('content')
        <!-- END CONTENT -->
        <!--<div class="main-backdrop"> </div>-->
    </body>
</html>

<script>
    $(window).load(function(){
        setTimeout(function(){
            window.print();
        }, 1000);
    });
</script>
