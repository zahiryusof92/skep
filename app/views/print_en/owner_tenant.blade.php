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
                            <img src="{{ asset($company->image_url) }}" height="100px;" alt="">
                        </h4>
                    </td>
                    <td>
                        <h5 class="margin-bottom-10">
                            {{ $company->name }}
                        </h5>
                        <h6 class="margin-bottom-0">
                            {{ $title }}
                        </h6>
                    </td>
                </tr>
            </table>

            <hr/>

            @if (!empty($file_id) && !empty($race))
            <?php $file = Files::find($file_id); ?>
            @if (!empty($owner))
            <table border="1" id="owner_table" width="100%" style="font-size: 12px;">
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
                        <?php $total_all_owner = Buyer::where('file_id', $file->id)->where('is_deleted', 0)->count(); ?>

                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $file->file_no }}</td>
                        @foreach ($race as $rc)
                        <?php $total_owner = Buyer::where('file_id', $file->id)->where('race_id', $rc->id)->where('is_deleted', 0)->count(); ?>
                        <?php $percentage_owner = 0; ?>
                        <?php if ($total_owner > 0) { ?>
                            <?php $percentage_owner = ($total_owner / $total_all_owner) * 100; ?>
                        <?php } ?>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ round($percentage_owner, 2) }}</td>

                        <?php
                        $ownerData[] = array(
                            'name' => $rc->name_my,
                            'y' => round($percentage_owner, 2)
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
            @endif

            <br/><br/>

            @if (!empty($tenant))
            <table border="1" id="tenant_table" width="100%" style="font-size: 12px;">
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
                        <?php $total_all_tenant = Buyer::where('file_id', $file->id)->where('is_deleted', 0)->count(); ?>

                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $file->file_no }}</td>
                        @foreach ($race as $rc)
                        <?php $total_tenant = Tenant::where('file_id', $file->id)->where('race_id', $rc->id)->where('is_deleted', 0)->count(); ?>
                        <?php $percentage_tenant = 0; ?>
                        <?php if ($total_tenant > 0) { ?>
                            <?php $percentage_tenant = ($total_tenant / $total_all_tenant) * 100; ?>
                        <?php } ?>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ round($percentage_tenant, 2) }}</td>

                        <?php
                        $tenantData[] = array(
                            'name' => $rc->name_my,
                            'y' => round($percentage_tenant, 2)
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
            @endif
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
