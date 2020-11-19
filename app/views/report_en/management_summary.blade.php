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
                                <a href="{{URL::action('PrintController@printManagementSummary')}}" target="_blank">
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
                        <table border="1" id="file_location_list" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th colspan="3" style="width:30%; text-align: center !important; vertical-align:middle !important;">BIL KAWASAN PEMAJUAN (PETAK)</th>
                                    <th colspan="5" style="width:70%; text-align: center !important; vertical-align:middle !important;">BILANGAN SAMPEL DAN PERATUSAN (%)</th>
                                </tr>
                                <tr>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;"><= 10 (PETAK)</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">> 10 (PETAK)</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">JUMLAH (PETAK)</th>
                                    <th style="width:7%; text-align: center !important; vertical-align:middle !important;">PEMAJU & LIQUIDATOR</th>
                                    <th style="width:7%; text-align: center !important; vertical-align:middle !important;">JMB</th>
                                    <th style="width:7%; text-align: center !important; vertical-align:middle !important;">MC</th>
                                    <th style="width:7%; text-align: center !important; vertical-align:middle !important;">EJEN</th>
                                    <th style="width:7%; text-align: center !important; vertical-align:middle !important;">LAIN-LAIN</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($data)
                                <tr>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['count_less10'] }} ({{ $data['sum_less10'] }})</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['count_more10'] }} ({{ $data['sum_more10'] }})</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['count_all'] }} ({{ $data['sum_all'] }})</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['developer'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['jmb'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['mc'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['agent'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['others'] }}</td>                                    
                                </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <!--<h5 class="text-center">Rumusan Fail COB Mengikut Jenis Pengurusan</h5>-->
                        <div class="margin-bottom-50">
                            <div id="management_type"></div>
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
    Highcharts.chart('management_type', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Fail COB Mengikut Jenis Pengurusan'
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
                name: 'Jenis Pengurusan',
                colorByPoint: true,
                data: [
                    {name: 'Pemaju', y: <?php echo $data['developer']; ?>},
                    {name: 'JMB', y: <?php echo $data['jmb']; ?>},
                    {name: 'MC', y: <?php echo $data['mc']; ?>},
                    {name: 'Ejen', y: <?php echo $data['agent']; ?>},
                    {name: 'Lain-lain', y: <?php echo $data['others']; ?>}
                ]
            }]
    });
</script>

@stop