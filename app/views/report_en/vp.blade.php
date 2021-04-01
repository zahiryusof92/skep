@extends('layout.english_layout.default')

@section('content')

<?php $company = Company::find(Auth::user()->company_id); ?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
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
                                <form action="{{ URL::action('PrintController@printVp') }}" method="GET" class="form-horizontal" target="_blank">
                                    <input type="hidden" name="cob_id" value="{{ $cob_id }}"/>
                                    <input type="hidden" name="year" value="{{ $year_id }}"/>
                                    <button type="submit" class="btn btn-own" target="_blank" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr/>

                <section class="panel panel-pad">
                    <div class="row padding-vertical-20">
                        <div class="col-lg-12">
                            <form action="{{ url('/reporting/vp') }}" method="GET" class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <select class="form-control select2" id="cob_id" name="cob_id">
                                                @if (count($cob) > 1)
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @endif
                                                @foreach ($cob as $cobs)
                                                <option value="{{ $cobs->id }}" {{ ($cobs->id == $cob_id ? 'selected' : '') }}>{{ $cobs->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control select2" id="year" name="year">
                                                @foreach ($year as $value => $years)
                                                <option value="{{ $value }}" {{ ($value == $year_id ? 'selected' : '') }}>{{ $years }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.submit') }}</button>
                                        <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@vp") }}'"">{{ trans('app.buttons.reset') }}</button>
                                        <img id="loading" style="display: none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <?php
                if ($file_info) {
                    $pieData = array(
                        array(
                            'name' => 'Sebelum VP',
                            'y' => $file_info['total_before_vp'],
                        ),
                        array(
                            'name' => 'Selepas VP',
                            'y' => $file_info['total_after_vp']
                        )
                    );
                }
                ?>

                <div id="chart"></div>

            </div>
        </div>
    </section>
    <!-- End  -->
</div>

<script type="text/javascript">
    Highcharts.chart('chart', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Fail Sebelum VP vs Selepas VP Berdasarkan Tahun <?php echo $file_info['year']; ?>'
        },
        subtitle: {
            text: '<?php echo $cob_name; ?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                showInLegend: true
            }
        },
        series: [{
                name: 'Brands',
                colorByPoint: true,
                data: <?php echo json_encode($pieData); ?>
            }]
    });
</script>
@stop
