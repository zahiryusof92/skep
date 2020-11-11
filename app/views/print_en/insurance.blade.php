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
            @if ($insurance_provider && $file_info)
            <div class="row">
                <div class="col-lg-12">
                    <p>LAPORAN</p>
                    <table border="1" id="complaint_report_table" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:30%; text-align: center !important; vertical-align:middle !important;">COB</th>
                                <th colspan="{{ count($insurance_provider) }}" style="width:75%; text-align: center !important; vertical-align:middle !important;">PENYEDIA INSURANS</th>
                            </tr>
                            <tr>
                                @foreach ($insurance_provider as $ip)
                                <th style="width:{{ 70 / count($insurance_provider) }}%; text-align: center !important; vertical-align:middle !important;">{{ strtoupper($ip->name) }}</th>

                                <?php
                                ?>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($file_info as $file)                               
                            <tr>
                                <td style="text-align: left !important; vertical-align:middle !important;">&nbsp; {{ $file['company']['name'] }}</td>
                                @if ($file['provider'])
                                @foreach ($file['provider'] as $pro)
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $pro['total'] }}</td>

                                <?php
                                $barData[$pro['id']][] = array(
                                    $pro['total']
                                );
                                ?>
                                @endforeach
                                @endif                                    
                            </tr>

                            <?php
                            $cobData[] = array(
                                $file['company']['short_name']
                            );
                            ?>
                            @endforeach

                            @foreach ($insurance_provider as $ip)
                            <?php
                            $chartData[] = array(
                                'name' => $ip->name,
                                'data' => $barData[$ip->id]
                            );
                            ?>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <hr/>

            <div id="chart"></div>

            <script type="text/javascript">
                Highcharts.chart('chart', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: 'PENYEDIA INSURANS'
                    },
                    xAxis: {
                        categories: <?php echo json_encode($cobData); ?>,
                        title: {
                            text: null
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'JUMLAH (INSURANS)',
                            align: 'high'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' Insurans'
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -40,
                        y: 80,
                        floating: true,
                        borderWidth: 1,
                        backgroundColor:
                                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                        shadow: true
                    },
                    credits: {
                        enabled: false
                    },
                    series: <?php echo json_encode($chartData); ?>
                });
            </script>
            @else

            <p>{{ trans('app.forms.no_data_available')}}</p>
            
            <hr/>

            @endif

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

@stop