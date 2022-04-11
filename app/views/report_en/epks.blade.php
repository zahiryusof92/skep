@extends('layout.english_layout.default_custom')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <form target="_blank" action="{{ route('reporting.print.epks') }}" method="POST">
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
                                    <input type="hidden" name="from" id="from">
                                    <input type="hidden" name="to" id="to">
                                    <button type="submit" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr/>
                    <section class="panel panel-pad">
                        <div class="row margin-top-10">
                            <div class="col-lg-12">
                                <span style="font-size: 12px;"><b>{{ trans('app.forms.date_audited') }}: </b></span>&nbsp;
                                <input style="font-size: 12px;" id="date_from" data-column="0" type="text" class="form-control width-150 display-inline-block datetimepicker" placeholder="From"/>
                                <span style="font-size: 12px;" class="margin-right-10">&nbsp; —</span>
                                <input style="font-size: 12px;" id="date_to" data-column="0" type="text" class="form-control width-150 display-inline-block datetimepicker" placeholder="To"/>
                            </div>
                            <div class="col-md-4 padding-top-25 padding-bottom-10">
                                <button type="button" class="btn btn-own" id="cancel_button" onclick="window.location ='{{ route('reporting.epks.index') }}'">{{ trans('app.buttons.reset') }}&nbsp;<i class="fa fa-repeat"></i></button>
    
                            </div>
                        </div>
                    </section>
                    <br/>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="margin-bottom-50 chart-custom">
                                <div id="monthly_chart"></div>                            
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="margin-bottom-50 chart-custom">
                                <div id="status_chart"></div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        generateColumn('status_chart', "{{ trans('app.forms.monthly_detail') }}", <?php echo json_encode($data ? $data['data_status']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_status']['data'] : ''); ?>);
        generateColumn('monthly_chart', "{{ trans('app.forms.total_monthly') }}", <?php echo json_encode($data ? $data['data_monthly']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_monthly']['data'] : ''); ?>);

        $('select').on('select2:select', function (e) {
            getChartData();
        });

        $('.datetimepicker').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'YYYY-MM-DD'
        }).on('dp.change', function () {
            console.log(this.id)
            let id = this.id.substring(5);
            console.log(id)
            $('#'+ id).val(this.value);
            getChartData(); 
        });

        $("[data-toggle=tooltip]").tooltip();

        $("#file_id").select2({
            ajax: {
                url: "{{ route('v3.api.files.getOption') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                cache: true,
                allowClear: true,
                data: function(params) {
                    return {
                        term: params.term, // search term
                        strata: $('#strata').val(),
                        company_id: $('#company_id').val(),
                    };
                },
                processResults: function(response) {
                    return {
                        results: response.results
                    };
                }
            }
        });
    });
    
    function getChartData() {
        $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            $.ajax({
                url: "{{ route('reporting.epks.index') }}",
                type: "GET",
                data: {
                    file_id : $('#file_id').val(),
                    // strata : $('#strata').val(),
                    date_from : $('#date_from').val(),
                    date_to : $('#date_to').val(),
                    filter: true,
                },
                success: function (res) {
                    $.unblockUI();  
                    generateColumn('status_chart', "{{ trans('app.forms.monthly_detail') }}", res.data.data_status.categories, res.data.data_status.data);
                    generateColumn('monthly_chart', "{{ trans('app.forms.total_monthly') }}", res.data.data_monthly.categories, res.data.data_monthly.data);
                }
            });
    }
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
