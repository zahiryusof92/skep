@extends('layout.english_layout.default')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
$totalless10 = 0;
$totalmore10 = 0;

if (count($strata) > 0) {    
    foreach ($strata as $stratas) {
        if ($stratas->is_residential == 1 && $stratas->is_commercial == 1) {
            $less10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            if (count($less10residential) <= 0) {
                $less10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            } else {
                $less10commercial = 0;
            }
            $totalless10 = $totalless10 + ($less10residential + $less10commercial);

            $more10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            if (count($less10residential) <= 0) {
                $more10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            } else {
                $more10commercial = 0;
            }
            $totalmore10 = $totalmore10 + ($more10residential + $more10commercial);
        } else if ($stratas->is_residential == 1 && $stratas->is_commercial == 0) {
            $less10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            $totalless10 = $totalless10 + $less10residential;
            
            $more10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            $totalmore10 = $totalmore10 + $more10residential;
        } else if ($stratas->is_residential == 0 && $stratas->is_commercial == 1) {            
            $less10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            $totalless10 = $totalless10 + $less10commercial;
            
            $more10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            $totalmore10 = $totalmore10 + $more10commercial;
        } else {
            $totalless10 = $totalless10 + 1;
            $totalmore10 = $totalmore10 + 0;
        }
    }
}
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
                                <tr>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$totalless10}} ({{$residential_less10 + $commercial_less10}})</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$totalmore10}} ({{$residential_more10 + $commercial_more10}})</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{count($strata)}} ({{$residential + $commercial}})</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$developer}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$jmb}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$mc}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$agent}}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{$others}}</td>                                    
                                </tr>
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
                    {name: 'Pemaju', y: <?php echo $developer; ?>},
                    {name: 'JMB', y: <?php echo $jmb; ?>},
                    {name: 'MC', y: <?php echo $mc; ?>},
                    {name: 'Ejen', y: <?php echo $agent; ?>},
                    {name: 'Lain-lain', y: <?php echo $others; ?>}
                ]
            }]
    });
</script>

@stop