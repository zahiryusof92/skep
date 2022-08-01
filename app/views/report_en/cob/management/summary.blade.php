<section class="panel panel-pad">
    <div class="row">
        <div class="col-lg-12">
            <br/>
            <table border="1" id="cob_file_management" width="100%" class="margin-bottom-20">
                <thead>
                    <tr>
                        <th colspan="3" style="width:50%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.management') }}</th>
                        <th colspan="4" style="width:50%; text-align: center !important; vertical-align:middle !important;">BIL KAWASAN PEMAJUAN</th>
                    </tr>
                    <tr>
                        <th style="width:17%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.items') }}</th>
                        <th style="width:17%; text-align: center !important; vertical-align:middle !important;">Bilangan Terkini</th>
                        <th style="width:16%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.percentage') }} (%)</th>
                        <th style="width:11%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.items') }}</th>
                        <th style="width:12%; text-align: center !important; vertical-align:middle !important;"><= 10</th>
                        <th style="width:12%; text-align: center !important; vertical-align:middle !important;">> 10</th>
                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">JUMLAH</th>
                    </tr>
                </thead>
    
                @if ($data)
                <tbody>   
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">JMB</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"><a href="javascript:void(0);" onclick="filterPetak('jmb')"><u>{{ $data['jmb'] }}</u></a></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['jmb'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">Kediaman</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_residential_less10'] }}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_residential_more10'] }}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_count_residential_less10'] + $data['total_count_residential_more10']) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">MC</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"><a href="javascript:void(0);" onclick="filterPetak('mc')"><u>{{ $data['mc'] }}</u></a></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['mc'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">Kedai / Pejabat</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_commercial_less10'] }}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['total_count_commercial_more10'] }}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_count_commercial_less10'] + $data['total_count_commercial_more10']) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">PEMAJU</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"><a href="javascript:void(0);" onclick="filterPetak('developer')"><u>{{ $data['management_developer'] }}</u></a></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['management_developer'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">EJEN</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"><a href="javascript:void(0);" onclick="filterPetak('agent')"><u>{{ $data['agent'] }}</u></a></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['agent'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">LAIN-LAIN</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"><a href="javascript:void(0);" onclick="filterPetak('others')"><u>{{ $data['others'] }}</u></a></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all_management'] > 0 ? number_format((($data['others'] / $data['total_all_management']) * 100), 2) : 0) }}</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">TIADA PENGURUSAN</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"><a href="javascript:void(0);" onclick="filterPetak('non')"><u>{{ $data['total_no_management'] }}</u></a></td>
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
    <div class="row">
        <div class="col-lg-12">
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
</section>
<div class="row padding-bottom-15">
    <div class="col-lg-6">
        <div class="margin-bottom-10 chart-custom">
            <div id="management_chart"></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="margin-bottom-10 chart-custom">
            <div id="housing_scheme_chart"></div>
        </div>
    </div>
</div>

<script>
    $(function() {
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