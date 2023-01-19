@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <div class="panel-body">

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        @if (!empty($data))
                        <!-- Graph -->
                        <div class="margin-bottom-50 chart-custom">
                            <div id="report_chart"></div>
                        </div>
                        @endif

                    </div>
                </div>
            </section>

        </div>

    </section>
</div>

<script>
    let title = '{{ trans("Type vs Total Records") }}';
    let categories = <?php echo (isset($data['categories']) && !empty($data['categories']) ? json_encode($data['categories']) : '') ?>;
    let series = <?php echo (isset($data['series']) && !empty($data['series']) ? json_encode($data['series']) : '') ?>;
    
    generateColumn('report_chart', title, categories, series);
   
    function generateColumn(id, title, categories, series) {
        Highcharts.chart(id, {
            chart: {
                type: 'column',
            },
            title: {
                text: title
            },
            xAxis: {
                categories: categories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: '(records)'
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
            series: series
        });
    }
</script>
@endsection