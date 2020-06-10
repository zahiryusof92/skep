@extends('layout.english_layout.default')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="invoice-block">
                <div class="row">
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
                            <td class="text-center">
                                <a href="{{URL::action('PrintController@printRatingSummary')}}" target="_blank">
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-lg-12">
                        <br/>
                        <table border="1" id="rating_summary" width="100%" style="font-size: 12px;">
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
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <!--<h5 class="text-center">Rumusan Penakrifan Bintang Kawasan Pemajuan</h5>-->
                        <div class="margin-bottom-50">
                            <div id="rating_star"></div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </section>    
    <!-- End  -->
</div>

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