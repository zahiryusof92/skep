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

            <div id="files_chart" width="100%"></div>
            
            <div id="jmb_chart" width="100%"></div>
            <hr />
            <table border="1" id="audit_trail" width="100%" style="font-size: 11px;">
                <thead>
                    <tr>
                        @if (Auth::user()->getAdmin())
                        <th style="width:5%; text-align: center !important;">{{ trans('app.forms.cob') }}</th>
                        @endif
                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.file_no') }}</th>
                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.module') }}</th>
                        <th style="width:45%; text-align: center !important;">{{ trans('app.forms.activities') }}</th>
                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.role') }}</th>
                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.action_from') }}</th>
                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($models) > 0)
                    @foreach ($models as $model)
                    <tr>
                        @if (Auth::user()->getAdmin())
                        <td>{{$model->user->company_id? $model->company : "-" }}</td>
                        @endif
                        <td>{{$model->user->file_id? $model->file_no : "-"}}</td>
                        <td>{{$model->module}}</td>
                        <td><?php echo $model->remarks;?></td>
                        <td>{{$model->role_name}}</td>
                        <td>{{$model->full_name}}</td>
                        <td>{{date('d/m/Y H:i:s', strtotime($model->created_at))}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" style="text-align: center">{{ trans('app.forms.no_data_available') }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
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
    $(function() {
        generateColumn('files_chart', "{{ trans('app.forms.total_files') }}", <?php echo json_encode($data ? $data['data_files']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_files']['data'] : ''); ?>);
        generateColumn('jmb_chart', "{{ trans('app.forms.total_jmb') }}", <?php echo json_encode($data ? $data['data_jmb']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_jmb']['data'] : ''); ?>);
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
                                short_name = this.category;
                                $('#tbl_custom_never_has_agm').show();
                                if(custom_never_table != undefined) {
                                    custom_never_table.draw();
                                } else {
                                    generate_never_agm();
                                }
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
