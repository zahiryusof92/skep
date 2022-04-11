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

            <br/>
            <table width="100%">
                <tr>
                    <td id="monthly_chart"></td>
                </tr>
            </table>
            <hr/>
            <table width="100%">
                <tr>
                    <td id="status_chart"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End  -->

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        generateColumn('status_chart', "{{ trans('app.forms.monthly_detail') }}", <?php echo json_encode($data ? $data['data_status']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_status']['data'] : ''); ?>);
        generateColumn('monthly_chart', "{{ trans('app.forms.total_monthly') }}", <?php echo json_encode($data ? $data['data_monthly']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_monthly']['data'] : ''); ?>);
    });
    function generateColumn(id, title, categories, data) {
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
                    text: '(total)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr>' +
                    '<td style="padding:0"><b>{point.y} total</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                
                            }
                        }
                    }
                }
            },
            series: [{
                name: title,
                data: data,

            }]
        });
    }
</script>

@stop
