@extends('layout.english_layout.print')

@section('content')

<?php $company = Company::find(Auth::user()->company_id); ?>

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

            @if (!empty($files))
            @if (!empty($result))
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

            <table border="1" id="fee_table" width="100%" style="margin-top: 25px;">
                <thead>
                    <tr>
                        <th style="width:35%; text-align: center !important; vertical-align:middle !important;">KADAR CAJ (RM)</th>
                        <th style="width:35%; text-align: center !important; vertical-align:middle !important;">KADAR SINKING FUND (RM)</th>
                        <th style="width:30%; text-align: center !important; vertical-align:middle !important;">% PURATA KUTIPAN TAHUNAN</th>
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
            @endif

            @if ($files->financeSupport)
            <table border="1" id="support_table" width="100%" style="margin-top: 25px;">
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
            @endif

            @if (!empty($race))
            @if ($files->owner)
            <table border="1" id="owner_table" width="100%" style="margin-top: 25px;">
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
            @endif

            <br/><br/>

            @if ($files->tenant)
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
            @endif
            @endif

            
            <div class="row margin-vertical-15">
                <div class="col-lg-12">
                    <h4>{{ trans('app.menus.finance.finance_file_list') }}</h4>
                </div>
                <div class="col-lg-12">
                    <table border="1" id="finance_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:50%; text-align: center !important; vertical-align:middle !important;">No. FAIL</th>
                                <th style="width:50%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.zone') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['finances'] as $finance)
                            <tr>
                                <td>{{ $finance->file_no . " " . $finance->year . "-" . strtoupper($finance->monthName()) }}</td>
                                <td style="text-align: center !important;">
                                    <?php

                                    $zone = 'Kelabu';
                                    $finance_income_semasa = $finance->financeIncome()->where('name', 'SINKING FUND')->sum('semasa');
                                    $finance_report_fee_semasa = $finance->financeReport()->where('type', 'SF')->sum('fee_semasa');
                                    if($finance_report_fee_semasa > 0) {
                                        $percentage = round(($finance_income_semasa / $finance_report_fee_semasa) * 100);
                                        
                                        if ($percentage >= 80) {
                                            $zone = 'Biru';
                                        } else if ($percentage < 79 && $percentage >= 50) {
                                            $zone = 'Kuning';
                                        } else {
                                            $zone = 'Merah';
                                        }
                                    } 
                                    ?>
                                    {{ $zone }} 
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

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

@stop
