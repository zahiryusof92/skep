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
            @if ($file_info)
            <?php
            $total_strata = 0;
            $total_jmb = 0;
            $total_mc = 0;
            $total_buyer = 0;
            $total_tenant = 0;
            ?>            
            <div class="row">
                <div class="col-lg-12">
                    <p>LAPORAN</p>
                    <table border="1" id="council_report_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:25%; text-align: center !important; vertical-align:middle !important;">COB</th>
                                <th style="width:15%; text-align: center !important; vertical-align:middle !important;">JUMLAH STRATA</th>
                                <th style="width:15%; text-align: center !important; vertical-align:middle !important;">JUMLAH JMB</th>
                                <th style="width:15%; text-align: center !important; vertical-align:middle !important;">JUMLAH MC</th>
                                <th style="width:15%; text-align: center !important; vertical-align:middle !important;">JUMLAH PEMBELI</th>
                                <th style="width:15%; text-align: center !important; vertical-align:middle !important;">JUMLAH PENYEWA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($file_info as $file)
                            <tr>
                                <td style="text-align: left !important; vertical-align:middle !important;">&nbsp; {{ $file['company']['name'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $file['total_strata'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $file['total_jmb'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $file['total_mc'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $file['total_buyer'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $file['total_tenant'] }}</td>
                            </tr>

                            <?php
                            $total_strata += $file['total_strata'];
                            $total_jmb += $file['total_jmb'];
                            $total_mc += $file['total_mc'];
                            $total_buyer += $file['total_buyer'];
                            $total_tenant += $file['total_tenant'];
                            ?>

                            <?php
                            $cobData[] = array(
                                $file['company']['short_name']
                            );
                            $strataData[] = array(
                                $file['total_strata']
                            );
                            $jmbData[] = array(
                                $file['total_jmb']
                            );
                            $mcData[] = array(
                                $file['total_mc']
                            );
                            $buyerData[] = array(
                                $file['total_buyer']
                            );
                            $tenantData[] = array(
                                $file['total_tenant']
                            );
                            ?>
                            @endforeach

                            <?php
                            $chartData[] = array(
                                'name' => 'Jumlah Strata',
                                'data' => $strataData
                            );
                            $chartData[] = array(
                                'name' => 'Jumlah JMB',
                                'data' => $jmbData
                            );
                            $chartData[] = array(
                                'name' => 'Jumlah MC',
                                'data' => $mcData
                            );
                            $chartData[] = array(
                                'name' => 'Jumlah Pembeli',
                                'data' => $buyerData
                            );
                            $chartData[] = array(
                                'name' => 'Jumlah Penyewa',
                                'data' => $tenantData
                            );
                            ?>
                        </tbody>

                        @if (count($file_info) > 1)
                        <tfoot>
                            <tr>
                                <th style="text-align: left !important; vertical-align:middle !important;">&nbsp; JUMLAH</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ $total_strata }}</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ $total_jmb }}</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ $total_mc }}</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ $total_buyer }}</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ $total_tenant }}</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            @endif
            <hr/>
            <div id="chart"></div>
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

<script type="text/javascript">
    Highcharts.chart('chart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'LAPORAN'
        },
        xAxis: {
            categories: <?php echo json_encode($cobData); ?>,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'JUMLAH'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: <?php echo json_encode($chartData); ?>
    });
</script>

@stop