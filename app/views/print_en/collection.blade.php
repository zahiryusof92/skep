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
            <div class="row">
                <div class="col-lg-12">
                    <p>LAPORAN</p>
                    <table border="1" id="collection_report_table" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:40%; text-align: center !important; vertical-align:middle !important;">COB</th>
                                <th colspan="3" style="width:60%; text-align: center !important; vertical-align:middle !important;">ZON</th>
                            </tr>
                            <tr>
                                <th style="width:20%; text-align: center !important; vertical-align:middle !important;">BIRU</th>
                                <th style="width:20%; text-align: center !important; vertical-align:middle !important;">KUNING</th>
                                <th style="width:20%; text-align: center !important; vertical-align:middle !important;">MERAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($file_info as $info)
                            <tr>
                                <td style="text-align: left !important; vertical-align:middle !important;">&nbsp; {{ $info['company_name'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $info['zon_biru'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $info['zon_kuning'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $info['zon_merah'] }}</td>
                            </tr>

                            <?php
                            $category[] = $info['company_name'];
                            $barBiru[] = $info['zon_biru'];
                            $barKuning[] = $info['zon_kuning'];
                            $barMerah[] = $info['zon_merah'];
                            ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <?php
            $barData[] = array(
                'name' => 'Zon Biru',
                'data' => $barBiru,
                'color' => 'blue'
            );

            $barData[] = array(
                'name' => 'Zon Kuning',
                'data' => $barKuning,
                'color' => 'yellow'
            );

            $barData[] = array(
                'name' => 'Zon Merah',
                'data' => $barMerah,
                'color' => 'red'
            );

            $height = (count($file_info) * 3) * 30;
            ?>
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
            type: 'bar',
            height: <?php echo ($height > 250 ? $height : 250); ?>
        },
        title: {
            text: 'Status Kutipan'
        },
        xAxis: {
            categories: <?php echo json_encode($category); ?>,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' fail'
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
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: <?php echo json_encode($barData); ?>
    });
</script>
@stop