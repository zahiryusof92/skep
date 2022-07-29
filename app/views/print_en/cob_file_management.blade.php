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
            <div class="row">
                <div class="col-lg-12">
                    <br/>
                    <table border="1" id="cob_file_management" width="100%" class="margin-bottom-20">
                        <thead>
                            <tr>
                                <th colspan="3" style="width:40%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.management') }}</th>
                                <th colspan="4" style="text-align: center !important; vertical-align:middle !important;">BIL KAWASAN PEMAJUAN (PETAK)</th>
                            </tr>
                            <tr>
                                <th style="width:17%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.items') }}</th>
                                <th style="width:16%; text-align: center !important; vertical-align:middle !important;">Bilangan Terkini</th>
                                <th style="width:16%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.percentage') }} (%)</th>
                                <th style="width:11%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.items') }}</th>
                                <th style="width:12%; text-align: center !important; vertical-align:middle !important;"><= 10 (PETAK)</th>
                                <th style="width:12%; text-align: center !important; vertical-align:middle !important;">> 10 (PETAK)</th>
                                <th style="width:15%; text-align: center !important; vertical-align:middle !important;">JUMLAH</th>
                            </tr>
                        </thead>

                        @if ($data)
                        <tbody>   
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">JMB</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['jmb'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['jmb'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">Kediaman</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_residential_less10'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_residential_more10'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_count_residential_less10'] + $data['total_count_residential_more10']) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">MC</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['mc'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['mc'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">Kedai / Pejabat</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_commercial_less10'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_commercial_more10'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_count_commercial_less10'] + $data['total_count_commercial_more10']) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">Pemaju</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['management_developer'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['management_developer'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">EJEN</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['agent'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['agent'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">LAIN-LAIN</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['others'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['others'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">TIADA PENGURUSAN</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_no_management'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['total_no_management'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                                <th colspan="3" style="text-align: center !important; vertical-align:middle !important;">JUMLAH</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_count_residential_less10'] + $data['total_count_residential_more10'] + $data['total_count_commercial_less10'] + $data['total_count_commercial_more10']) }}</th>
                            </tr>               
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align: center !important; vertical-align:middle !important;">JUMLAH JMB, MC, Pemaju & EJEN</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ ($data['jmb'] + $data['mc'] + $data['agent'] + $data['management_developer']) }}</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format(((($data['jmb'] + $data['mc'] + $data['agent'] + $data['management_developer']) / $data['total_all_management']) * 100), 2) : 0) }}</th>
                                <th colspan="3" style="text-align: center !important; vertical-align:middle !important;">Fail Tiada Petak</th>
                                <th style="text-align: center !important; vertical-align:middle !important;">{{ $data['unknown_kawasan'] }}</th>
                            </tr>
                        </tfoot>
                        @endif

                    </table>
                </div>
            </div>
            <div class="row padding-bottom-15" id="management_summary_detail">
                <div class="col-lg-12">
                    <br/>
                    <table border="1" id="cob_file_management_detail" width="100%">
                        <thead>
                            <tr>
                                <th colspan="3" style="width:40%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.housing_scheme') }}</th>
                            </tr>
                            <tr>
                                <th style="width:40%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.items') }}</th>
                                <th style="width:30%; text-align: center !important; vertical-align:middle !important;">Bilangan Terkini</th>
                                <th style="width:30%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.percentage') }} (%)</th>
                            </tr>
                        </thead>

                        @if ($data)
                        <tbody>       
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">PEMAJU</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['developer'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ (($data['developer'] + $data['liquidator']) > 0 ? number_format((($data['developer'] / ($data['developer'] + $data['liquidator'])) * 100), 2) : 0) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center !important; vertical-align:middle !important;">LIQUIDATOR</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['liquidator'] }}</td>
                                <td style="text-align: center !important; vertical-align:middle !important;">{{ (($data['developer'] + $data['liquidator']) > 0 ? number_format((($data['liquidator'] / ($data['developer'] + $data['liquidator'])) * 100), 2) : 0) }}</td>
                            </tr>                        
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-6">
                    <table width="100%">
                        <tr>
                            <td id="management_chart"></td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table width="100%">
                        <tr>
                            <td id="housing_scheme_chart"></td>
                        </tr>
                    </table>
                </div>
            </div>

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

<!-- Page Scripts -->
<script>
    $(function () {
        generateChart('management_chart', 'Fail COB Mengikut Jenis Pengurusan', <?php echo json_encode($data['management_chart_data']) ?>);
        generateChart('housing_scheme_chart', 'Fail COB Mengikut Jenis Skim Perumahan', <?php echo json_encode($data['house_scheme_chart_data']) ?>);
    });

    function generateChart(id, title, data) {
        Highcharts.chart(id, {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: title
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
                    data: data
                }]
        });
    }
</script>
@stop
