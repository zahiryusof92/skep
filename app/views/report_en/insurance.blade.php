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
                                <a href="{{URL::action('PrintController@printInsurance', $cob_id ? $cob_id : 'all')}}" target="_blank">
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
                            <form action="{{ url('/reporting/insurance') }}" method="GET" class="form-horizontal">
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
                                        <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.submit') }}</button>
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@insurance") }}'"">{{ trans('app.buttons.reset') }}</button>
                                        <img id="loading" style="display: none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($insurance_provider && $file_info)                
                    <div class="row padding-bottom-15">
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
                </section>

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

                <hr/>
                
                <p>{{ trans('app.forms.no_data_available')}}</p>
                
                @endif

            </div>
        </div>
    </section>
    <!-- End  -->
</div>

@stop
