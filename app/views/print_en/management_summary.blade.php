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
