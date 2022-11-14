@extends('layout.english_layout.default')

@section('content')

<?php $company = Company::find(Auth::user()->company_id); ?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
            <h5 class="text-right">{{ trans('app.forms.created_at') }} : {{date('d/m/Y', strtotime($files->created_at))}}</h5>
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
                                <a href="{{ URL::action('PrintController@printStrataProfile', \Helper\Helper::encode($files->id)) }}" target="_blank">
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr/>

                @if (!empty($files))
                @if (!empty($result))
                <div class="row">
                    <div class="col-lg-12">
                        <table border="1" id="strata_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">PIHAK BERKUASA TEMPATAN</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">NAMA PANGSAPURI</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">JUMLAH UNIT</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">JUMLAH BLOK</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">BIL. TINGKAT</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">LIF</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">BIL. UNIT LIF</th>
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">AKAUN METER AIR</th>
                                    @if(str_contains(Request::url(), 'https://ecob.mps.gov.my/'))
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">TNB</th>
                                    @endif
                                    <th style="width:10%; text-align: center !important; vertical-align:middle !important;">ZON</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['pbt'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['strata_name'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['total_unit'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['total_block'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['total_floor'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['lif'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['lif_unit'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['type_meter'] }}</td>
                                    @if(str_contains(Request::url(), 'https://ecob.mps.gov.my/'))
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['tnb'] }}</td>
                                    @endif
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['zone'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <pre>{{ print_r($result['collection'], true) }}</pre>

                @if (!empty($result['collection']))
                <div class="row" style="margin-top: 25px;">
                    <div class="col-lg-12">
                        <table border="1" id="fee_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:35%; text-align: center !important; vertical-align:middle !important;">KADAR CAJ (RM)</th>
                                    <th style="width:35%; text-align: center !important; vertical-align:middle !important;">KADAR SINKING FUND (RM)</th>
                                    <th style="width:30%; text-align: center !important; vertical-align:middle !important;">PURATA KUTIPAN TAHUNAN (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['mf_rate'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['sf_rate'] }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $result['purata_dikutip'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @endif

                @if (!empty($result['ageing']))
                <div class="row" style="margin-top: 25px;">
                    <div class="col-lg-12">
                        <table border="1" id="support_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:35%; text-align: center !important; vertical-align:middle !important;">
                                        {{ trans('TAHUN') }}
                                    </th>
                                    <th style="width:35%; text-align: center !important; vertical-align:middle !important;">
                                        {{ trans('BULAN') }}
                                    </th>
                                    <th style="width:30%; text-align: center !important; vertical-align:middle !important;">
                                        {{ trans('PURATA KUTIPAN') }} (%)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result['ageing'] as $year => $months)
                                    @foreach ($months as $month => $data)
                                        <tr>
                                            <td style="text-align: center !important; vertical-align:middle !important;">
                                                {{ $year }}
                                            </td>
                                            <td style="text-align: center !important; vertical-align:middle !important;">
                                                {{ $month }}
                                            </td>
                                            <td style="text-align: center !important; vertical-align:middle !important;">
                                                {{ $data['percentage'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if ($files->financeSupport)
                <div class="row" style="margin-top: 25px;">
                    <div class="col-lg-12">
                        <table border="1" id="support_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%; text-align: center !important; vertical-align:middle !important;">BIL</th>
                                    <th style="width:80%; text-align: center !important; vertical-align:middle !important;">JENIS BANTUAN</th>
                                    <th style="width:15%; text-align: center !important; vertical-align:middle !important;">KOS (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($files->financeSupport) > 0)
                                <?php $count = 1 ?>
                                @foreach ($files->financeSupport as $support)
                                <tr>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $count }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $support->name }}</td>
                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $support->amount }}</td>
                                </tr>
                                <?php $count = $count + 1 ?>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3" style="text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.no_data_available') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if (!empty($race))                
                @if ($files->owner)
                <div class="row" style="margin-top: 25px;">
                    <div class="col-lg-12">
                        <table border="1" id="owner_table" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:25%; text-align: center !important; vertical-align:middle !important;">NO. FAIL</th>
                                    <th colspan="{{ count($race) }}" style="width:75%; text-align: center !important; vertical-align:middle !important;">DIDUDUKI PEMILIK</th>
                                </tr>
                                <tr>
                                    @foreach ($race as $rc)
                                    <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ strtoupper($rc->name_my) }} (%)</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                    
                                    <?php $total_all_owner = Buyer::where('file_id', $files->id)->where('is_deleted', 0)->count(); ?>

                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $files->file_no }}</td>                               
                                    @foreach ($race as $rc)
                                    <?php $total_owner = Buyer::where('file_id', $files->id)->where('race_id', $rc->id)->where('is_deleted', 0)->count(); ?>
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

                        <div id="owner_chart" style="margin-top: 10px;"></div>
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
                                            format: '{point.name}<br/><b>{point.percentage:.1f} %</b>'
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

                @if ($files->tenant)
                <div class="row">
                    <div class="col-lg-12">
                        <table border="1" id="tenant_table" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:25%; text-align: center !important; vertical-align:middle !important;">NO. FAIL</th>
                                    <th colspan="{{ count($race) }}" style="width:75%; text-align: center !important; vertical-align:middle !important;">DISEWA</th>
                                </tr>
                                <tr>
                                    @foreach ($race as $rc)
                                    <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ strtoupper($rc->name_my) }} (%)</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php $total_all_tenant = Buyer::where('file_id', $files->id)->where('is_deleted', 0)->count(); ?>

                                    <td style="text-align: center !important; vertical-align:middle !important;">{{ $files->file_no }}</td>                               
                                    @foreach ($race as $rc)
                                    <?php $total_tenant = Tenant::where('file_id', $files->id)->where('race_id', $rc->id)->where('is_deleted', 0)->count(); ?>
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

                        <div id="tenant_chart" style="margin-top: 10px;"></div>
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
                                            format: '{point.name}<br/><b>{point.percentage:.1f} %</b>'
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

                <div class="row margin-vertical-15">
                    <div class="col-lg-12">
                        <h4>{{ trans('app.menus.finance.finance_file_list') }}</h4>
                    </div>
                    <div class="col-lg-12">
                        <table class="table table-hover table-own table-striped" id="finance_table" width="100%" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width:55%; text-align: center !important; vertical-align:middle !important;">No. FAIL</th>
                                    <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.zone') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        var route = "{{ route('reporting.strataProfile.finance', ':file_id') }}";
                        route = route.replace(':file_id', "{{ $files->id }}");
                        $('#finance_table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                'url' : route,
                            },
                            lengthMenu: [[15, 30, 50], [15, 30, 50]],
                            pageLength: 15,
                            scrollX: true,
                            columns: [
                                {data: 'file_id', name: 'files.file_no', orderable: false},
                                {data: 'zone', name: 'zone', orderable: false, searchable: false}
                            ],
                            columnDefs: [
                                {
                                    targets: 1, // your case first column
                                    className: "text-center",
                                },
                            ]
                        });
                    });
                </script>
                @endif

            </div>           
        </div>
    </section>    
    <!-- End  -->
</div>

@stop