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
            @if ($defect_category && $file_info)
            <div class="row">
                <div class="col-lg-12">
                    <p>LAPORAN</p>
                    <table border="1" id="complaint_report_table" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:30%; text-align: center !important; vertical-align:middle !important;">COB</th>
                                <th colspan="{{ count($defect_category) }}" style="width:75%; text-align: center !important; vertical-align:middle !important;">KATEGORI ADUAN</th>
                            </tr>
                            <tr>
                                @foreach ($defect_category as $dc)
                                <th style="width:{{ 70 / count($defect_category) }}%; text-align: center !important; vertical-align:middle !important;">{{ strtoupper($dc->name) }}</th>

                                <?php
                                ?>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($file_info as $file)                               
                            <tr>
                                <td style="text-align: left !important; vertical-align:middle !important;">&nbsp; {{ $file['company']['name'] }}</td>
                                @if ($file['category'])
                                @foreach ($file['category'] as $cat)
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $cat['total'] }}</td>

                                <?php
                                $barData[$cat['id']][] = array(
                                    $cat['total']
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

                            @foreach ($defect_category as $dc)
                            <?php
                            $chartData[] = array(
                                'name' => $dc->name,
                                'data' => $barData[$dc->id]
                            );
                            ?>
                            @endforeach

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
            type: 'bar'
        },
        title: {
            text: 'KATEGORI ADUAN'
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
                text: 'JUMLAH (ADUAN)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' Aduan'
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
        series: <?php echo json_encode($chartData); ?>
    });
</script>
@stop