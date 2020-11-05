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
                    <table border="1" id="dun_report_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:30%; text-align: center !important; vertical-align:middle !important;">COB</th>
                                <th style="width:30%; text-align: center !important; vertical-align:middle !important;">DUN</th>
                                <th style="width:30%; text-align: center !important; vertical-align:middle !important;">KATEGORI</th>
                                <th style="width:10%; text-align: center !important; vertical-align:middle !important;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $dunChart = array();
                            $totalChart = array();
                            ?>
                            @foreach ($file_info as $file)                                
                            @if ($file['dun'])
                            @foreach ($file['dun'] as $dun)

                            <?php
                            $dunChart[] = array(
                                $dun['name']
                            );
                            ?>

                            @if ($dun['category'])
                            @foreach ($dun['category'] as $cat)

                            <?php
                            $totalChart[$cat['id']][] = array(
                                $cat['total']
                            );
                            ?>
                            <tr>
                                <td rowspan="" style="text-align: left !important; vertical-align:middle !important;">&nbsp; {{ $file['company']['name'] }}</td>
                                <td rowspan="" style="text-align: center !important; vertical-align:middle !important;">{{ $dun['name'] }}</td>
                                <td rowspan="" style="text-align: center !important; vertical-align:middle !important;">{{ $cat['name'] }}</td>
                                <td rowspan="" style="text-align: center !important; vertical-align:middle !important;">{{ $cat['total'] }}</td>
                            </tr>
                            @endforeach
                            @endif                                
                            @endforeach
                            @endif
                            @endforeach

                            @if ($category)
                            <?php $catChart = array(); ?>
                            @foreach ($category as $cats)
                            <?php
                            $catChart[] = array(
                                'name' => $cats->description,
                                'data' => ($totalChart ? $totalChart[$cats->id] : 0)
                            );
                            ?>
                            @endforeach
                            @endif
                        </tbody>
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
            type: 'bar',
            height: <?php echo count($file_info) * count($category) * 50; ?>
        },
        title: {
            text: 'Jumlah berdasarkan dun'
        },
        xAxis: {
            categories: <?php echo json_encode($dunChart); ?>,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Population (millions)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' millions'
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
        series: <?php echo json_encode($catChart); ?>
    });
</script>

@stop