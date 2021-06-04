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
                            <div class="col-lg-12">
                                <span style="font-size: 12px;"><b>{{ trans('app.forms.date_strata') }}: </b></span>&nbsp;
                                <input style="font-size: 12px;" id="start_date" data-column="0" type="text" class="form-control width-150 display-inline-block" placeholder="From"/>
                                <span style="font-size: 12px;" class="margin-right-10">&nbsp; —</span>
                                <input style="font-size: 12px;" id="end_date" data-column="0" type="text" class="form-control width-150 display-inline-block" placeholder="To"/>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>

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
            "bServerSide": true,
            "bProcessing": true,
            "sAjaxSource": "{{URL::action('ReportController@getStrataProfile')}}",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
                // Append to data
                aoData.push( { "name": "start_date", "value": $('#start_date').val() } );
                aoData.push( { "name": "end_date", "value": $('#end_date').val() } );
                
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": "{{URL::action('ReportController@getStrataProfile')}}",
                    "data": aoData,
                    "success": fnCallback
                } );
            },
            "lengthMenu": [
                [15, 30, 50, 100, -1],
                [15, 30, 50, 100, "All"]
            ],
            "sorting": [
                [2, "asc"],
                [3, "asc"]
            ],
            "scrollX": true,
            "responsive": false
        });

        $('#company').on('change', function () {
            oTable.columns(2).search(this.value).draw();
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
            oTable.draw();
            
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
            oTable.draw();
            
        });
    });
</script>
<!-- End Page Scripts-->

@stop
