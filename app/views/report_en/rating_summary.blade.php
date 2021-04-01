@extends('layout.english_layout.default')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>

<div class="page-content-inner">
    <section class="panel panel-style">
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
                                    <button type="button" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                
                <section class="panel panel-pad">
                    @if ($rating_data)
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
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $rating_data['total_strata'] }}</td>
                                        @if ($rating_data['total_strata'] > 0 && $rating_data['total_rating'] > 0)
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $rating_data['total_rating'] }} ({{number_format((($rating_data['total_rating'] / $rating_data['total_strata']) * 100), 2)}}%)</td>
                                        @else
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $rating_data['total_rating'] }} (0%)</td>
                                        @endif
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($rating_data['rating'][0] ? $rating_data['rating'][0]['y'] : '0') }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($rating_data['rating'][1] ? $rating_data['rating'][1]['y'] : '0') }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($rating_data['rating'][2] ? $rating_data['rating'][2]['y'] : '0') }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($rating_data['rating'][3] ? $rating_data['rating'][3]['y'] : '0') }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($rating_data['rating'][4] ? $rating_data['rating'][4]['y'] : '0') }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $rating_data['total_strata'] - $rating_data['total_rating'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if ($summary_data)
                    <br/>                
                    <div class="row padding-bottom-15">
                        <div class="col-lg-12">
                            <table border="1" id="rating_category_summary" width="100%" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width:20%; text-align: center !important; vertical-align:middle !important;">KATEGORI</th>
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
                                    @foreach ($summary_data as $data)                             
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['category'] }}</td>
                                        @if ($data['percentage'] > 0)
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_rating'] }} ({{ $data['percentage'] }}%)</td>
                                        @else
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_rating'] }} (0%)</td>
                                    
                                        @endif
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['rating'][0]['y'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['rating'][1]['y'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['rating'][2]['y'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['rating'][3]['y'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['rating'][4]['y'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['no_info'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </section>

                @if ($rating_data)
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <!--<h5 class="text-center">Rumusan Penakrifan Bintang Kawasan Pemajuan</h5>-->
                        <div class="margin-bottom-50 chart-custom">
                            <div id="rating_star"></div>
                        </div>
                    </div>
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
                                    format: '{point.name}<br/><b>{point.percentage:.1f} %</b>'
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                                name: 'Penakrifan Bintang',
                                colorByPoint: true,
                                data: <?php echo json_encode($rating_data ? $rating_data['rating'] : ''); ?>
                            }]
                    });
                </script>
                @endif
            </div>
        </div>        
    </section>    
    <!-- End  -->
</div>

@stop