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
                                <a href="{{URL::action('PrintController@printCouncil', $cob_id ? $cob_id : 'all')}}" target="_blank">
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ url('/reporting/council') }}" method="GET" class="form-horizontal">
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
                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@council") }}'"">{{ trans('app.buttons.reset') }}</button>
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