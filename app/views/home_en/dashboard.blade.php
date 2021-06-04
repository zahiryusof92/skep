@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 9) {
        $update_permission = $permission->update_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>Dashboard</h3>
        </div>
        <div class="panel-body">
            <div class="widget widget-four background-transparent">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-15 col-lg-15">
                        <div class="step-block">
                            <span class="step-digit">
                                <img src="{{asset('assets/common/img/icon/strata.png')}}"/>
                            </span>
                            <div class="step-desc">
                                <span class="step-title">Strata</span>
                                <p>{{ $data['total_strata'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-15 col-lg-15">
                        <div class="step-block">
                            <span class="step-digit">
                                <img src="{{asset('assets/common/img/icon/hirechy.png')}}"/>
                            </span>
                            <div class="step-desc">
                                <span class="step-title">JMB</span>
                                <p>
                                    {{ $data['total_jmb'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-15 col-lg-15">
                        <div class="step-block">
                            <span class="step-digit">
                                <img src="{{asset('assets/common/img/icon/profile.png')}}"/>
                            </span>
                            <div class="step-desc">
                                <span class="step-title">MC</span>
                                <p>
                                    {{ $data['total_mc'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-15 col-lg-15">
                        <div class="step-block">
                            <span class="step-digit">
                                <img src="{{asset('assets/common/img/icon/key.png')}}"/>
                            </span>
                            <div class="step-desc">
                                <span class="step-title">Owner</span>
                                <p>
                                    {{ $data['total_owner'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-15 col-lg-15">
                        <div class="step-block">
                            <span class="step-digit">
                                <img src="{{asset('assets/common/img/icon/tenant1.png')}}"/>
                            </span>
                            <div class="step-desc">
                                <span class="step-title">Tenant</span>
                                <p>
                                    {{ $data['total_tenant'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="margin-bottom-50 chart-custom">
                        <!--<h4 class="text-center">Star Rating of Development Area</h4>-->
                        <!--<div class="chart-pie-chart"></div>-->
                        <div id="rating_star"></div>                            
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="margin-bottom-50 chart-custom">
                        <div id="management_type"></div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
            <div class="row">
                <div class="col-lg-12">
                    <h4>{{ trans('app.forms.file_draft') }}</h4>
                    <section class="panel panel-pad">                
                        <div class="row margin-top-20">
                            <div class="col-lg-12 text-center">
                                <form>
                                    <div class="row">
                                        @if (Auth::user()->getAdmin())
                                        <div class="col-md-6">
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
                                    </div>
                                </form>
                            </div>
                        </div>

                        <hr/>

                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-hover table-own table-striped" id="file_draft_list" width="100%">
                                    <thead>
                                        <tr>                                
                                            <th style="width:35%;">{{ trans('app.forms.file_no') }}</th>
                                            <th style="width:35%;">{{ trans('app.forms.name') }}</th>
                                            <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                            <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                            <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <hr/>

            <script type="text/javascript">
                $(document).ready(function () {
                    var oTable = $('#file_draft_list').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ URL::action('DraftController@getFileList') }}",
                        lengthMenu: [[5, 25, 50, 100, -1], [5, 25, 50, 100, "All"]],
                        pageLength: 5,
                        order: [[2, "asc"], [1, 'asc']],
                        responsive: false,
                        scrollX: true,
                        columns: [
                            {data: 'file_no', name: 'files.file_no'},
                            {data: 'strata', name: 'strata.name'},
                            {data: 'cob', name: 'company.short_name'},
                            {data: 'year', name: 'strata.year'},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ]
                    });

                    $('#company').on('change', function () {
                        oTable.columns(2).search(this.value).draw();
                    });
                });
            </script>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <h4>{{ trans('app.forms.agm') }}</h4>
                    <div>
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active custom-tab" href="javascript: void(0);" data-toggle="tab" data-target="#tab1" role="tab">{{ trans('app.forms.agm_reminder') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="javascript: void(0);" data-toggle="tab" data-target="#tab2" role="tab">{{ trans('app.forms.never_has_agm') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="javascript: void(0);" data-toggle="tab" data-target="#tab3" role="tab">{{ trans('app.forms.more_than_12_months') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="javascript: void(0);" data-toggle="tab" data-target="#tab4" role="tab">{{ trans('app.forms.more_than_15_months') }}</a>
                            </li>
                        </ul>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="tab1" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="agm_remainder" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:30%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.last_agm_date') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.agm_due_date') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab2" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="never_agm" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:55%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:25%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab3" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="more_12months" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:30%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.last_agm_date') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.agm_due_date') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab4" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="more_15months" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:30%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.last_agm_date') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.agm_due_date') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-lg-12">
                    <h4>{{ trans('app.forms.designation') }}</h4>
                    <div>
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active custom-tab" href="javascript: void(0);" data-toggle="tab" data-target="#tabDesignation1" role="tab">{{ trans('app.forms.designation_reminder') }}</a>
                            </li>
                        </ul>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="tabDesignation1" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="designation_remainder" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.file_no') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.designation') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.name') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.phone_number') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.month') }}</th>
                                                <th style="width:5%;">{{ trans('app.forms.start_year') }}</th>
                                                <th style="width:5%;">{{ trans('app.forms.end_year') }}</th>
                                                <th style="width:5%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-lg-12">
                    <h4>{{ trans('app.forms.insurance') }}</h4>
                    <div>
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active custom-tab" href="javascript: void(0);" data-toggle="tab" data-target="#tabInsurance1" role="tab">{{ trans('app.forms.insurance_reminder') }}</a>
                            </li>
                        </ul>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="tabInsurance1" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="insurance_remainder" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:25%;">{{ trans('app.forms.file_no') }}</th>
                                                <th style="width:25%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.insurance_provider') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.validity') }} {{ trans("app.forms.to") }}</th>
                                                <th style="width:5%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-lg-12">
                    <h4>{{ trans('app.forms.memo') }}</h4>
                    <section class="panel panel-pad">
                        <div class="tab-content padding-vertical-20">
                            <table class="table table-hover table-own table-striped" id="memo" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:70%;">{{ trans('app.forms.subject') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.memo_date') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade modal" id="memoDetailsModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#agm_remainder').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getAGMRemainder') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[3, "desc"]],
            responsive: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'agm_date', name: 'meeting_document.agm_date'},
                {data: 'agm_expiry_date', name: 'agm_expiry_date', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#never_agm').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getNeverAGM') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[0, 'asc'], [1, 'asc']],
            responsive: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#more_12months').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getAGM12Months') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[3, "desc"]],
            responsive: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'agm_date', name: 'meeting_document.agm_date'},
                {data: 'agm_expiry_date', name: 'agm_expiry_date', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#more_15months').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getAGM15Months') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[3, "desc"]],
            responsive: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'agm_date', name: 'meeting_document.agm_date'},
                {data: 'agm_expiry_date', name: 'agm_expiry_date', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#memo').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getMemoHome') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[1, "desc"]],
            responsive: true,
            columns: [
                {data: 'subject', name: 'subject'},
                {data: 'memo_date', name: 'memo_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#designation_remainder').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getDesignationRemainder') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[7, 'asc'], [6, 'asc']],
            responsive: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strate.name'},
                {data: 'designation', name: 'designation.description'},
                {data: 'name', name: 'ajk_details.name'},
                {data: 'phone_no', name: 'ajk_details.phone_no'},
                {data: 'month', name: 'ajk_details.month'},
                {data: 'start_year', name: 'ajk_details.start_year'},
                {data: 'end_year', name: 'ajk_details.end_year'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#insurance_remainder').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getInsuranceRemainder') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [0, 'asc'],
            responsive: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'provider', name: 'insurance_provider.name'},
                {data: 'plc_validity_to', name: 'insurance.plc_validity_to'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });

    function getMemoDetails(id) {
        $.ajax({
            url: "{{ URL::action('HomeController@getMemoDetails') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                $(".modal-content").html(data);
                $("#memoDetailsModal").modal("show");
            }
        });
    }

    // Build the chart
    Highcharts.chart('management_type', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'COB File By Management Type'
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
                name: 'Management Type',
                colorByPoint: true,
                data: <?php echo json_encode($data ? $data['management'] : ''); ?>
            }]
    });

    Highcharts.chart('rating_star', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Star Rating of Development Area'
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
                name: 'Star Rating',
                colorByPoint: true,
                data: <?php echo json_encode($data ? $data['rating'] : ''); ?>
            }]
    });
</script>

@stop