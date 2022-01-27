@extends('layout.english_layout.default')

@section('content')

<?php
$zone = [
    'Biru' => 'Biru',
    'Kuning' => 'Kuning',
    'Merah' => 'Merah'
];
?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-15">
                    <div class="col-lg-12 text-center">                    
                        <div class="row">
                            
                            @if (Auth::user()->getAdmin())
                            @if ($cob)
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.cob') }}</label>
                                    <select id="company" class="form-control select2">
                                        @if (count($cob) > 1)
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @endif
                                        @foreach ($cob as $companies)
                                        <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            @endif

                            @if ($parliament)
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.parliament') }}</label>
                                    <select id="parliament" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($parliament as $parliaments)
                                        <option value="{{$parliaments->description}}">{{$parliaments->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            
                            @if ($zone)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.zone') }}</label>
                                    <select id="zone" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($zone as $value => $zon)
                                        <option value="{{$value}}">{{ ucwords($zon) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="form-group float-left">
                                    <label>{{ trans('app.forms.date_strata') }} </label><br>
                                    <input id="start_date" data-column="0" type="text" class="form-control width-150 display-inline-block" placeholder="From"/>
                                    <span class="margin-right-10">&nbsp; —</span>
                                    <input id="end_date" data-column="0" type="text" class="form-control width-150 display-inline-block" placeholder="To"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="margin-bottom-50 chart-custom">
                            <div id="pie_chart"></div>                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover table-own table-striped" id="filelist" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width:25%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.file_name') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.cob') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.parliament') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.zone') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;

    $(document).ready(function () {
        oTable = $('#filelist').DataTable({
            "bProcessing": true,
            "sAjaxSource": "{{URL::action('ReportController@getStrataProfile')}}",
            "lengthMenu": [[15, 30, 50], [15, 30, 50]],
            "sorting": [
                [2, "asc"],
                [3, "asc"]
            ],
            "scrollX": true,
            "responsive": false,
            "paging": true,
            "info": true
        });
        

        $('#company').on('change', function () {
            oTable.columns(2).search(this.value).draw();
            $.ajax({
                url: "{{URL::action('ReportController@getStrataProfileAnalytic')}}",
                type: "GET",
                data: {
                    cob: this.value,
                },
                beforeSend: function () {
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                },
                success: function (result) {
                    $.unblockUI();
                    if (result) {
                        generatePie(result.data.pie_data);
                    }
                }
            });
        });
        $('#parliament').on('change', function () {
            oTable.columns(3).search(this.value).draw();
        });
        $('#zone').on('change', function () {
            oTable.columns(4).search(this.value).draw();
        });
        $('#start_date').datetimepicker({
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
            format: 'YYYY-MM-DD',
        }).on('dp.change', function () {
            $.ajax({
                url: "{{ URL::action('ReportController@getStrataProfile') }}",
                type: "GET",
                data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                },
                success: function (data) {
                    var rData = JSON.parse(data);
                    oTable.clear().draw();
                    oTable.rows.add(rData.aaData).draw(); // Add new data
                }
            });
            
        });
        
        $('#end_date').datetimepicker({
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
            format: 'YYYY-MM-DD',
        }).on('dp.change', function () {
            $.ajax({
                url: "{{ URL::action('ReportController@getStrataProfile') }}",
                type: "GET",
                data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                },
                success: function (data) {
                    var rData = JSON.parse(data);
                    oTable.clear().draw();
                    oTable.rows.add(rData.aaData).draw(); // Add new data
                }
            });
            
        });

        Highcharts.setOptions({
            colors: ['#24CBE5', '#DDDF00', '#FF0000']
        });
        generatePie(<?php echo json_encode($data ? $data['pie_data'] : ''); ?>);
    });

    function generatePie(data) {
        
        Highcharts.chart('pie_chart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                events: {
                }
            },
            title: {
                text: 'Strata Profile'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}% ({point.y})</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}<br/><b>{point.percentage:.1f} % ({point.y})</b>'
                    },
                    showInLegend: true
                },
            },
            series: [{
                    name: 'Strata Profile',
                    colorByPoint: true,
                    data: data
            }]
        });
    }
</script>
<!-- End Page Scripts-->

@stop
