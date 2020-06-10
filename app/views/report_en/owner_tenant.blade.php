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
                                <a href="{{URL::action('PrintController@printOwnerTenant', $file_id)}}" target="_blank">
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ url('/reporting/ownerTenant') }}" method="GET" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="form-control select2" id="file_id" name="file_id" required="">
                                            <option value="">{{ trans('app.forms.file_no') }}</option>
                                            @foreach ($files as $file)
                                            <option value="{{$file->id}}" {{ ($file->id == $file_id ? 'selected' : '') }}>{{$file->file_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" id="submit_button">{{ trans('app.forms.submit') }}</button>
                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@ownerTenant") }}'"">{{ trans('app.forms.cancel') }}</button>
                                    <img id="loading" style="display: none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if (!empty($file_id) && !empty($race))
                <?php $file = Files::find($file_id); ?>
                @if (!empty($owner))
                <div class="row">
                    <div class="col-lg-12">
                        <p>DIDUDUKI PEMILIK</p>
                        <table border="1" id="owner_table" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:25%; text-align: center !important; vertical-align:middle !important;">NO. FAIL</th>
                                    <th colspan="{{ count($race) }}" style="width:75%; text-align: center !important; vertical-align:middle !important;">DIDUDUKI PEMILIK</th>
                                </tr>
                                <tr>
                                    @foreach ($race as $rc)
                                    <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ strtoupper($rc->name) }} (%)</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php $total_all_owner = Buyer::where('file_id', $file->id)->where('is_deleted', 0)->count(); ?>

                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $file->file_no }}</td>
                                    @foreach ($race as $rc)
                                    <?php $total_owner = Buyer::where('file_id', $file->id)->where('race_id', $rc->id)->where('is_deleted', 0)->count(); ?>
                                    <?php $percentage_owner = 0; ?>
                                    <?php if ($total_owner > 0) { ?>
                                        <?php $percentage_owner = ($total_owner / $total_all_owner) * 100; ?>
                                    <?php } ?>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $percentage_owner }}</td>

                                    <?php
                                    $ownerData[] = array(
                                        'name' => $rc->name,
                                        'y' => $percentage_owner
                                    );
                                    ?>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>

                        <div id="owner_chart"></div>
                        <script>
                            Highcharts.chart('owner_chart', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: 'Diduduki Pemilik'
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
                                            format: '{point.name}<br/><b>{point.percentage:.1f} %</b>',
                                            distance: -50,
                                            filter: {
                                                property: 'percentage',
                                                operator: '>',
                                                value: 4
                                            }
                                        },
                                        showInLegend: true
                                    }
                                },
                                series: [{
                                        name: 'Jenis Pengurusan',
                                        colorByPoint: true,
                                        data: <?php echo json_encode($ownerData); ?>
                                    }]
                            });
                        </script>
                    </div>
                </div>
                @endif

                <br/><br/>

                @if (!empty($tenant))
                <div class="row">
                    <div class="col-lg-12">
                        <p>DISEWA</p>
                        <table border="1" id="tenant_table" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:25%; text-align: center !important; vertical-align:middle !important;">NO. FAIL</th>
                                    <th colspan="{{ count($race) }}" style="width:75%; text-align: center !important; vertical-align:middle !important;">DISEWA</th>
                                </tr>
                                <tr>
                                    @foreach ($race as $rc)
                                    <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ strtoupper($rc->name) }} (%)</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php $total_all_tenant = Buyer::where('file_id', $file->id)->where('is_deleted', 0)->count(); ?>

                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $file->file_no }}</td>
                                    @foreach ($race as $rc)
                                    <?php $total_tenant = Tenant::where('file_id', $file->id)->where('race_id', $rc->id)->where('is_deleted', 0)->count(); ?>
                                    <?php $percentage_tenant = 0; ?>
                                    <?php if ($total_tenant > 0) { ?>
                                        <?php $percentage_tenant = ($total_tenant / $total_all_tenant) * 100; ?>
                                    <?php } ?>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $percentage_tenant }}</td>

                                    <?php
                                    $tenantData[] = array(
                                        'name' => $rc->name,
                                        'y' => $percentage_tenant
                                    );
                                    ?>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>

                        <div id="tenant_chart"></div>
                        <script>
                            Highcharts.chart('tenant_chart', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: 'Disewa'
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
                                            format: '{point.name}<br/><b>{point.percentage:.1f} %</b>',
                                            distance: -50,
                                            filter: {
                                                property: 'percentage',
                                                operator: '>',
                                                value: 4
                                            }
                                        },
                                        showInLegend: true
                                    }
                                },
                                series: [{
                                        name: 'Disewa',
                                        colorByPoint: true,
                                        data: <?php echo json_encode($tenantData); ?>
                                    }]
                            });
                        </script>
                    </div>
                </div>
                @endif
                @endif

            </div>
        </div>
    </section>
    <!-- End  -->
</div>

@stop
