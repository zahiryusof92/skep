@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>Dashboard</h3>
        </div>

        <div class="panel-body">

            <div class="widget widget-four background-transparent">
                <div class="row">

                    @if (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper())
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <a href="{{ route('cob.file.index') }}">
                                <div class="step-block">
                                    <span class="step-digit">
                                        <img src="{{asset('assets/common/img/icon/strata.png')}}" />
                                    </span>
                                    <div class="step-desc">
                                        <span class="step-title">Strata</span>
                                        <p class="dashboard-stat" data-stat="total_strata">{{ $data['total_strata'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <a href="{{ route('cob.file.index') }}">
                                <div class="step-block">
                                    <span class="step-digit">
                                        <i class="fa fa-thumbs-up"></i>
                                    </span>
                                    <div class="step-desc">
                                        <span class="step-title">{{ trans('Active Strata') }}</span>
                                        <p class="dashboard-stat" data-stat="total_active_strata">
                                            {{ $data['total_active_strata'] }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <a href="{{ route('cob.file.index') }}">
                                <div class="step-block">
                                    <span class="step-digit">
                                        <i class="fa fa-thumbs-down"></i>
                                    </span>
                                    <div class="step-desc">
                                        <span class="step-title">{{ trans('Inactive Strata') }}</span>
                                        <p class="dashboard-stat" data-stat="total_inactive_strata">
                                            {{ $data['total_inactive_strata'] }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="step-block">
                                <span class="step-digit">
                                    <i class="fa fa-cubes"></i>
                                </span>
                                <div class="step-desc">
                                    <span class="step-title">{{ trans('Strata <10 Units') }}</span>
                                    <p class="dashboard-stat" data-stat="total_less_10_units">
                                        {{ $data['total_less_10_units'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="step-block">
                                <span class="step-digit">
                                    <img src="{{asset('assets/common/img/icon/hirechy.png')}}" />
                                </span>
                                <div class="step-desc">
                                    <span class="step-title">JMB</span>
                                    <p class="dashboard-stat" data-stat="total_jmb">
                                        {{ $data['total_jmb'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="step-block">
                                <span class="step-digit">
                                    <img src="{{asset('assets/common/img/icon/profile.png')}}" />
                                </span>
                                <div class="step-desc">
                                    <span class="step-title">MC</span>
                                    <p class="dashboard-stat" data-stat="total_mc">
                                        {{ $data['total_mc'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="step-block">
                                <span class="step-digit">
                                    <i class="fa fa-user-secret"></i>
                                </span>
                                <div class="step-desc">
                                    <span class="step-title">{{ trans('Developer') }}</span>
                                    <p class="dashboard-stat" data-stat="total_developer">
                                        {{ $data['total_developer'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="step-block">
                                <span class="step-digit">
                                    <i class="fa fa-users"></i>
                                </span>
                                <div class="step-desc">
                                    <span class="step-title">{{ trans('Liquidator') }}</span>
                                    <p class="dashboard-stat" data-stat="total_liquidator">
                                        {{ $data['total_liquidator'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="step-block">
                                <span class="step-digit">
                                    <i class="fa fa-user"></i>
                                </span>
                                <div class="step-desc">
                                    <span class="step-title">{{ trans('Agent') }}</span>
                                    <p class="dashboard-stat" data-stat="total_agent">
                                        {{ $data['total_agent'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="step-block">
                                <span class="step-digit">
                                    <i class="fa fa-ban"></i>
                                </span>
                                <div class="step-desc">
                                    <span class="step-title">{{ trans('No Management') }}</span>
                                    <p class="dashboard-stat" data-stat="total_no_management">
                                        {{ $data['total_no_management'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="step-block">
                            <span class="step-digit">
                                <img src="{{asset('assets/common/img/icon/key.png')}}" />
                            </span>
                            <div class="step-desc">
                                <span class="step-title">Owner</span>
                                <p class="dashboard-stat" data-stat="total_owner">
                                    {{ $data['total_owner'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="step-block">
                            <span class="step-digit">
                                <img src="{{asset('assets/common/img/icon/tenant1.png')}}" />
                            </span>
                            <div class="step-desc">
                                <span class="step-title">Tenant</span>
                                <p class="dashboard-stat" data-stat="total_tenant">
                                    {{ $data['total_tenant'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if (!empty($ageing))
                <hr />
                
                <div class="row">
                    <div class="col-lg-12">
                        <h4>{{ trans('app.menus.reporting.strata_profile') }}</h4>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <table class="table table-sm table-hover table-own table-striped" id="ageing_table" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:15%; text-align: center !important; vertical-align:middle !important;">
                                                {{ trans('TAHUN') }}
                                            </th>
                                            <th style="width:25%; text-align: center !important; vertical-align:middle !important;">
                                                {{ trans('BULAN') }}
                                            </th>
                                            <th style="width:30%; text-align: center !important; vertical-align:middle !important;">
                                                {{ trans('PERATUSAN KUTIPAN BULANAN') }} (%)
                                            </th>
                                            <th style="width:30%; text-align: center !important; vertical-align:middle !important;">
                                                {{ trans('JUMLAH PENDAPATAN BERSIH') }} (RM)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($ageing['data']))
                                        @foreach ($ageing['data'] as $ageing_data)
                                        <tr>
                                            <td style="text-align: center !important; vertical-align:middle !important;">
                                                {{ $ageing_data['year'] }}
                                            </td>
                                            <td style="text-align: center !important; vertical-align:middle !important;">
                                                {{ Str::upper($ageing_data['month_name']) }}
                                            </td>
                                            <td style="text-align: center !important; vertical-align:middle !important;">
                                                {{ $ageing_data['percentage'] }}
                                            </td>
                                            <td style="text-align: center !important; vertical-align:middle !important;">
                                                {{ $ageing_data['nett_income'] }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                @if (!empty($ageing['graph']))
                                    <div class="margin-top-25 chart-custom">
                                        <div id="ageing_chart"></div>
                                    </div>

                                    <script>
                                        Highcharts.chart('ageing_chart', {
                                            chart: {
                                                type: 'line'
                                            },
                                            title: {
                                                text: '{{ trans("Peratusan Kutipan Bulanan") }}'
                                            },
                                            xAxis: {
                                                categories : <?php echo json_encode($ageing['graph']['months']); ?>
                                            },
                                            yAxis: {
                                                title: {
                                                    text: 'Peratus (%)'
                                                }
                                            },
                                            plotOptions: {
                                                line: {
                                                    dataLabels: {
                                                        enabled: true
                                                    },
                                                }
                                            },
                                            series: [{
                                                name: 'Peratusan Kutipan',
                                                data: <?php echo json_encode($ageing['graph']['percentages']); ?>
                                            }]
                                        });
                                    </script>
                                @endif

                            </div>
                        </section>
                    </div>
                </div>
            @endif

            <hr />

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

            <hr />

            <div class="row">
                <div class="col-lg-6">
                    <div class="margin-bottom-50 chart-custom">
                        <div id="rating_star"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="margin-bottom-50 chart-custom">
                        <div id="management_type"></div>
                    </div>
                </div>
            </div>

            @if (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper())
            <div class="row">
                <div class="col-lg-12">
                    <div class="margin-bottom-50 chart-custom">
                        <div id="never_has_agm_chart"></div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row" id="tbl_custom_never_has_agm" style="display: none;">
                <div class="col-lg-12">
                    <div>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <h4>{{ trans('app.forms.never_has_agm') }}<button
                                        class='btn btn-xs btn-danger float-right'
                                        onclick='closeTableCustomNeverAGM()'><i class='fa fa-times'></i> </button></h4>
                                <div class="tab-pane active" id="tabInsurance1" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="custom_never_has_agm"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:25%;">{{ trans('app.forms.file_no') }}</th>
                                                <th style="width:25%;">{{ trans('app.forms.scheme_name') }}</th>
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

            <hr />

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
                                                    <option value="{{ $companies->short_name }}">{{ $companies->name }}
                                                        ({{ $companies->short_name }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-hover table-own table-striped" id="file_draft_list"
                                    width="100%">
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

            <hr />

            <script type="text/javascript">
                $(document).ready(function () {
                    var oTable = $('#file_draft_list').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ URL::action('DraftController@getFileList') }}",
                        lengthMenu: [[5, 25, 50], [5, 25, 50]],
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
                                <a class="nav-link active custom-tab" href="javascript: void(0);" data-toggle="tab"
                                    data-target="#tab1" role="tab">{{ trans('app.forms.agm_reminder') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="javascript: void(0);" data-toggle="tab"
                                    data-target="#tab2" role="tab">{{ trans('app.forms.never_has_agm') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="javascript: void(0);" data-toggle="tab"
                                    data-target="#tab3" role="tab">{{ trans('app.forms.more_than_12_months') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" href="javascript: void(0);" data-toggle="tab"
                                    data-target="#tab4" role="tab">{{ trans('app.forms.more_than_15_months') }}</a>
                            </li>
                        </ul>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="tab1" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="agm_remainder"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.last_agm_date') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.agm_due_date') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab2" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="never_agm"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:40%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:40%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab3" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="more_12months"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.last_agm_date') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.agm_due_date') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab4" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="more_15months"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.file_number') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.last_agm_date') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.agm_due_date') }}</th>
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

            <hr />

            <div class="row" id="dashboard_designation_section">
                <div class="col-lg-12">
                    <h4>{{ trans('app.forms.designation') }}</h4>
                    <div>
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active custom-tab" href="javascript: void(0);" data-toggle="tab"
                                    data-target="#tabDesignation1" role="tab">{{ trans('app.forms.designation_reminder')
                                    }}</a>
                            </li>
                        </ul>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="tabDesignation1" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="designation_remainder"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:5%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.file_no') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.designation') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.name') }}</th>
                                                <th style="width:10%;">{{ trans('app.forms.email') }}</th>
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

            <hr />

            <div class="row" id="dashboard_insurance_section">
                <div class="col-lg-12">
                    <h4>{{ trans('app.forms.insurance') }}</h4>
                    <div>
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active custom-tab" href="javascript: void(0);" data-toggle="tab"
                                    data-target="#tabInsurance1" role="tab">{{ trans('app.forms.insurance_reminder')
                                    }}</a>
                            </li>
                        </ul>
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="tabInsurance1" role="tabpanel">
                                    <table class="table table-hover table-own table-striped" id="insurance_remainder"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                                <th style="width:25%;">{{ trans('app.forms.file_no') }}</th>
                                                <th style="width:25%;">{{ trans('app.forms.scheme_name') }}</th>
                                                <th style="width:20%;">{{ trans('app.forms.insurance_provider') }}</th>
                                                <th style="width:15%;">{{ trans('app.forms.validity') }} {{
                                                    trans("app.forms.to") }}</th>
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

        </div>
    </section>
</div>

<div class="modal fade modal" id="memoDetailsModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    var custom_never_table, short_name;
    var dashboardTables = {};

    function initDashboardTable(key, selector, options) {
        if (dashboardTables[key]) {
            if ($.fn.DataTable.isDataTable(selector)) {
                $(selector).DataTable().columns.adjust();
            }
            return dashboardTables[key];
        }
        dashboardTables[key] = $(selector).DataTable(options);
        return dashboardTables[key];
    }

    function agmReminderColumns() {
        return [
            {data: 'cob', name: 'company.short_name'},
            {data: 'file_no', name: 'files.file_no'},
            {data: 'strata', name: 'strata.name'},
            {data: 'agm_date', name: 'meeting_document.agm_date'},
            {data: 'agm_expiry_date', name: 'agm_expiry_date', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ];
    }

    $(document).ready(function () {
        // Active AGM tab only on first paint
        initDashboardTable('agm_remainder', '#agm_remainder', {
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('HomeController@getAGMRemainder') }}",
            lengthMenu: [[5, 25, 50], [5, 25, 50]],
            pageLength: 5,
            order: [[3, "desc"]],
            responsive: true,
            columns: agmReminderColumns()
        });

        // Defer below-the-fold tables slightly so first paint stays light
        setTimeout(function () {
            initDashboardTable('memo', '#memo', {
                processing: true,
                serverSide: true,
                ajax: "{{ URL::action('HomeController@getMemoHome') }}",
                lengthMenu: [[5, 25, 50], [5, 25, 50]],
                pageLength: 5,
                order: [[1, "desc"]],
                responsive: true,
                columns: [
                    {data: 'subject', name: 'subject'},
                    {data: 'memo_date', name: 'memo_date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }, 150);

        // Designation & Insurance: only when scrolled into view (heaviest tables)
        function initDesignationTable() {
            initDashboardTable('designation', '#designation_remainder', {
                processing: true,
                serverSide: true,
                ajax: "{{ URL::action('HomeController@getDesignationRemainder') }}",
                lengthMenu: [[5, 25, 50], [5, 25, 50]],
                pageLength: 5,
                order: [[7, 'asc'], [6, 'asc']],
                responsive: true,
                columns: [
                    {data: 'cob', name: 'company.short_name'},
                    {data: 'file_no', name: 'files.file_no'},
                    {data: 'strata', name: 'strata.name'},
                    {data: 'designation', name: 'designation.description'},
                    {data: 'name', name: 'ajk_details.name'},
                    {data: 'email', name: 'ajk_details.email'},
                    {data: 'phone_no', name: 'ajk_details.phone_no'},
                    {data: 'month', name: 'ajk_details.month'},
                    {data: 'start_year', name: 'ajk_details.start_year'},
                    {data: 'end_year', name: 'ajk_details.end_year'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        function initInsuranceTable() {
            initDashboardTable('insurance', '#insurance_remainder', {
                processing: true,
                serverSide: true,
                ajax: "{{ URL::action('HomeController@getInsuranceRemainder') }}",
                lengthMenu: [[5, 25, 50], [5, 25, 50]],
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
        }

        function observeSection(selector, callback) {
            var el = document.querySelector(selector);
            if (!el) {
                return;
            }
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    if (entries[0].isIntersecting) {
                        callback();
                        observer.disconnect();
                    }
                }, { rootMargin: '200px' });
                observer.observe(el);
            } else {
                setTimeout(callback, 2000);
            }
        }

        observeSection('#dashboard_designation_section', initDesignationTable);
        observeSection('#dashboard_insurance_section', initInsuranceTable);

        // Lazy-load AGM tabs when opened
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('data-target') || $(e.target).data('target');
            if (target === '#tab2') {
                initDashboardTable('never_agm', '#never_agm', {
                    processing: true,
                    serverSide: true,
                    ajax: "{{ URL::action('HomeController@getNeverAGM') }}",
                    lengthMenu: [[5, 25, 50], [5, 25, 50]],
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
            } else if (target === '#tab3') {
                initDashboardTable('more_12months', '#more_12months', {
                    processing: true,
                    serverSide: true,
                    ajax: "{{ URL::action('HomeController@getAGM12Months') }}",
                    lengthMenu: [[5, 25, 50], [5, 25, 50]],
                    pageLength: 5,
                    order: [[3, "desc"]],
                    responsive: true,
                    columns: agmReminderColumns()
                });
            } else if (target === '#tab4') {
                initDashboardTable('more_15months', '#more_15months', {
                    processing: true,
                    serverSide: true,
                    ajax: "{{ URL::action('HomeController@getAGM15Months') }}",
                    lengthMenu: [[5, 25, 50], [5, 25, 50]],
                    pageLength: 5,
                    order: [[3, "desc"]],
                    responsive: true,
                    columns: agmReminderColumns()
                });
            }
        });

        // Heavy stats/charts — after HTML is already visible
        loadDashboardStats();
        loadActiveMemoAlerts();
    });

    function loadDashboardStats() {
        $.ajax({
            url: "{{ URL::action('HomeController@getDashboardStats') }}",
            type: "GET",
            dataType: "json",
            success: function (res) {
                if (!res || !res.success || !res.data) {
                    return;
                }
                var data = res.data;
                $('.dashboard-stat').each(function () {
                    var key = $(this).data('stat');
                    if (data[key] !== undefined && data[key] !== null) {
                        $(this).text(data[key]);
                    }
                });
                if (data.management) {
                    generatePie('management_type', 'COB File By Management Type', 'Management Type', data.management);
                }
                if (data.rating) {
                    generatePie('rating_star', 'Star Rating of Development Area', 'Star Rating', data.rating);
                }
                if (data.never) {
                    generateColumn('never_has_agm_chart', "{{ trans('app.forms.never_has_agm') }}", data.never.categories || [], data.never.data || []);
                }
            }
        });
    }

    function loadActiveMemoAlerts() {
        $.ajax({
            url: "{{ URL::action('HomeController@getActiveMemoAlerts') }}",
            type: "GET",
            dataType: "json",
            success: function (res) {
                if (!res || !res.success || !res.ids) {
                    return;
                }
                $.each(res.ids, function (i, id) {
                    getMemoDetails(id);
                });
            }
        });
    }

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

    function generatePie(id, title, series_title, data) {
        Highcharts.chart(id, {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                events: {
                    click: function(evt, item) {
                    }
                }
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
                    name: series_title,
                    colorByPoint: true,
                    data: data
                }]
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
                max: 100,
                title: {
                    text: '%'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr>' +
                    '<td style="padding:0"><b>{point.y} %</b></td></tr>',
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

    function generate_never_agm() {
        custom_never_table = $('#custom_never_has_agm').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ URL::action('HomeController@getNeverAGM') }}",
                'data': function(data) {
                    // Append to data
                    data.short_name = short_name;
                }
            },
            lengthMenu: [[5, 25, 50], [5, 25, 50]],
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
    }

    function closeTableCustomNeverAGM() {
        $('#tbl_custom_never_has_agm').hide();
    }
</script>

@stop
