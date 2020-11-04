@extends('layout.english_layout.default')

@section('content')

<?php $company = Company::find(Auth::user()->company_id); ?>

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
                                <a href="{{URL::action('PrintController@printDun', $cob_id ? $cob_id : 'all')}}" target="_blank">
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ url('/reporting/dun') }}" method="GET" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="form-control select2" id="cob_id" name="cob_id" required="">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($cob as $cobs)
                                            <option value="{{ $cobs->id }}" {{ ($cobs->id == $cob_id ? 'selected' : '') }}>{{ $cobs->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" id="submit_button">{{ trans('app.forms.submit') }}</button>
                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@dun") }}'"">{{ trans('app.buttons.reset') }}</button>
                                    <img id="loading" style="display: none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

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
                                $totalChart = '';
                                $dunChart = '';
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

            </div>
        </div>
    </section>
    <!-- End  -->
</div>

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
