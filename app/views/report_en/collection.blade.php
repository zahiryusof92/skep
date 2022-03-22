@extends('layout.english_layout.default')

@section('content')

<?php $company = Company::find(Auth::user()->company_id); ?>

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
                                <a href="{{URL::action('PrintController@printCollection', $cob_id ? \Helper\Helper::encode($cob_id) : 'all')}}" target="_blank">
                                    <button type="button" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr/>

                <section class="panel panel-pad">
                    <div class="row padding-vertical-15">
                        <div class="col-lg-12">
                            <form action="{{ url('/reporting/collection') }}" method="GET" class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <select class="form-control select2" id="cob_id" name="cob_id" required="">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @foreach ($cob as $cobs)
                                                <option value="{{ \Helper\Helper::encode($cobs->id) }}" {{ ($cobs->id == $cob_id ? 'selected' : '') }}>{{ $cobs->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.submit') }}</button>
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@collection") }}'"">{{ trans('app.buttons.reset') }}</button>
                                        <img id="loading" style="display: none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($file_info)
                    <div class="row padding-bottom-15">
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
                </section>

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

            </div>
        </div>
    </section>
    <!-- End  -->
</div>

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
