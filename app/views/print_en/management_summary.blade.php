@extends('layout.english_layout.print')

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
            <table border="1" id="" width="100%">
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
            <br/>

            <table id="" width="100%">
<!--                <tr>
                    <td class="text-center">
                        <h4>Rumusan Fail COB Mengikut Jenis Pengurusan</h4>
                    </td>
                </tr>-->
                <tr>
                    <td id="management_type"></td>
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
</section>
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
