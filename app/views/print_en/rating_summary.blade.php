@extends('layout.english_layout.print')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>

<table width="100%">
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td class="text-center">
                        <h4 class="margin-bottom-0">
                            <img src="{{asset($company->image_url)}}" height="100px;" alt="">
                        </h4>
                    </td>
                    <td>
                        <h5 class="margin-bottom-10">
                            {{$company->name}}
                        </h5>
                        <h6 class="margin-bottom-0">
                            {{$title}}
                        </h6>
                    </td>
                </tr>
            </table>
            <hr/>
            <table border="1" id="rating_summary" width="100%">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:20%; text-align: center !important; vertical-align:middle !important;">BILANGAN KAWASAN PEMAJUAN</th>
                        <th rowspan="2" style="width:20%; text-align: center !important; vertical-align:middle !important;">BILANGAN SAMPEL DAN PERATUSAN (%)</th>
                        <th colspan="6" style="width:60%; text-align: center !important; vertical-align:middle !important;">PENAKRIFAN BINTANG</th>
                    </tr>
                    <tr>
                        <th style="width:8%; text-align: center !important; vertical-align:middle !important;">5 BINTANG</th>
                        <th style="width:8%; text-align: center !important; vertical-align:middle !important;">4 BINTANG</th>
                        <th style="width:8%; text-align: center !important; vertical-align:middle !important;">3 BINTANG</th>
                        <th style="width:8%; text-align: center !important; vertical-align:middle !important;">2 BINTANG</th>
                        <th style="width:8%; text-align: center !important; vertical-align:middle !important;">1 BINTANG</th>
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">TIADA MAKLUMAT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$strata}}</td>
                        @if ($strata == 0)
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$rating}} (0%)</td>
                        @else
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$rating}} ({{number_format((($rating/$strata)*100), 2)}}%)</td>
                        @endif
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$fiveStar}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$fourStar}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$threeStar}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$twoStar}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$oneStar}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$strata - $rating}}</td>
                    </tr>
                </tbody>
            </table>
            <br/>
            <table id="" width="100%">
<!--                <tr>
                    <td class="text-center">
                        <h4>Rumusan Penakrifan Bintang Kawasan Pemajuan</h4>
                    </td>
                </tr>-->
                <tr>
                    <td id="rating_star"></td>
                </tr>
            </table>
            <hr/>
            <table width="100%">
                <tr>
                    <td>
                        <p><b>{{ trans('app.forms.confidential') }}</b></p>
                    </td>
                    <td class="pull-right">
                        <p>{{ trans('app.forms.print_on', ['print'=>date('d/m/Y h:i:s A', strtotime("now"))]) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End  -->

<!-- Page Scripts -->
<script>
    Highcharts.chart('rating_star', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Rumusan Penakrifan Bintang Kawasan Pemajuan'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}<br/><b>{point.percentage:.1f} %</b>',
                    distance: -50,
                    filter: {
                        property: 'percentage',
                        operator: '>',
                        value: 4
                    }
                },
                showInLegend: true
            }
        },
        series: [{
                name: 'Penakrifan Bintang',
                colorByPoint: true,
                data: [
                    {name: '1 Bintang', y: <?php echo $oneStar; ?>},
                    {name: '2 Bintang', y: <?php echo $twoStar; ?>},
                    {name: '3 Bintang', y: <?php echo $threeStar; ?>},
                    {name: '4 Bintang', y: <?php echo $fourStar; ?>},
                    {name: '5 Bintang', y: <?php echo $fiveStar; ?>}
                ]
            }]
    });
</script>

@stop
