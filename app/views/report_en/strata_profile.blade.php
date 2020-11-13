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
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
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
                        
                    </div>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-hover" id="filelist" style="width: 100%">
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
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;

    $(document).ready(function () {
        oTable = $('#filelist').DataTable({
            "sAjaxSource": "{{URL::action('ReportController@getStrataProfile')}}",
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
    });
</script>
<!-- End Page Scripts-->

@stop
