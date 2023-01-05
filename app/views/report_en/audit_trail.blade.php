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
            
            <div class="invoice-block">
                <form target="_blank" action="{{ route('print.log') }}" method="POST">
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
                                    <input type="hidden" name="print_date_from" id="print_date_from">
                                    <input type="hidden" name="print_date_to" id="print_date_to">
                                    <input type="hidden" name="print_cob" id="print_cob">
                                    <input type="hidden" name="print_role" id="print_role">
                                    <input type="hidden" name="print_module" id="print_module">
                                    <input type="hidden" name="print_file_id" id="print_file_id">
                                    <button type="submit" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr/>
                    <section class="panel panel-pad">
                        <div class="row margin-top-10">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.cob') }}</label>
                                    <select id="company_id" name="company_id" class="form-control select3" data-placeholder="{{ trans('app.forms.please_select') }}" data-ajax--url="{{ route('v3.api.company.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            @if(!Auth::user()->isJMB())
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.role') }}</label>
                                    <select id="role_id" name="role_id" class="form-control select3" data-placeholder="{{ trans('app.forms.please_select') }}" data-ajax--url="{{ route('v3.api.role.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.module') }}</label>
                                    <select id="module" name="module" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.file_no') }}</label>
                                    <select id="file_id" name="file_id" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.strata') }}</label>
                                    <select id="strata" name="strata" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-lg-12">
                                <span style="font-size: 12px;"><b>{{ trans('app.forms.date_audited') }}: </b></span>&nbsp;
                                <input style="font-size: 12px;" id="date_from" data-column="0" type="text" class="form-control width-150 display-inline-block datetimepicker" placeholder="From"/>
                                <span style="font-size: 12px;" class="margin-right-10">&nbsp; —</span>
                                <input style="font-size: 12px;" id="date_to" data-column="0" type="text" class="form-control width-150 display-inline-block datetimepicker" placeholder="To"/>
                            </div>
                            <div class="col-md-4 padding-top-25 padding-bottom-10">
                                <button type="button" class="btn btn-own" id="cancel_button" onclick="window.location ='{{ route('reporting.log.index') }}'">{{ trans('app.buttons.reset') }}&nbsp;<i class="fa fa-repeat"></i></button>
    
                            </div>
                        </div>
                    </section>
                </form>

                <br/>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="margin-bottom-50 chart-custom">
                            <div id="files_chart"></div>                            
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="margin-bottom-50 chart-custom">
                            <div id="jmb_chart"></div>                            
                        </div>
                    </div>
                </div>

                <section class="panel panel-pad">
                    <br/>

                    <div style="margin-bottom: 20px;">
                        <form target="_blank" action="{{ route('export.log') }}" method="POST">
                            <input type="hidden" name="export_company_id" id="export_company_id">
                            <input type="hidden" name="export_role_id" id="export_role_id">
                            <input type="hidden" name="export_module" id="export_module">
                            <input type="hidden" name="export_file_id" id="export_file_id">
                            <input type="hidden" name="export_date_from" id="export_date_from">
                            <input type="hidden" name="export_date_to" id="export_date_to">
                            <div class="text-right">
                                <button type="submit" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Export">
                                    <i class="fa fa-file-excel-o"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-hover table-own table-striped" id="audit_trail" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:5%; text-align: center !important;">{{ trans('app.forms.cob') }}</th>
                                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.module') }}</th>
                                        <th style="width:45%; text-align: center !important;">{{ trans('app.forms.activities') }}</th>
                                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.role') }}</th>
                                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.action_from') }}</th>
                                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.date') }}</th>
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
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;

    $(document).ready(function () {
        oTable = $('#audit_trail').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('reporting.log.index') }}",
                'data': function(data) {
                    var company_id = $('#company_id').val();
                    var role_id = $('#role_id').val();
                    var module_name = $('#module').val();
                    var file_id = $('#file_id').val();
                    // var strata = $('#strata').val();
                    var date_from = $('#date_from').val();
                    var date_to = $('#date_to').val();

                    $('#print_company_id').val(company_id);
                    $('#print_role_id').val(role_id);
                    $('#print_module').val(module_name);
                    $('#print_file_id').val(file_id);
                    $('#print_date_from').val(date_from);
                    $('#print_date_to').val(date_to);

                    $('#export_company_id').val(company_id);
                    $('#export_role_id').val(role_id);
                    $('#export_module').val(module_name);
                    $('#export_file_id').val(file_id);
                    $('#export_date_from').val(date_from);
                    $('#export_date_to').val(date_to);

                    // Append to data
                    data.company_id = company_id;
                    data.role_id = role_id;
                    data.module = module_name;
                    data.file_id = file_id;
                    // data.strata = strata;
                    data.date_from = date_from;
                    data.date_to = date_to;
                }
            },
            "dom": '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            "order": [[6, "desc"]],
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "pageLength": 25,
            "scrollX": true,
            "responsive": false,
            "columns": [
                {data: 'company_id', name: 'company.name'},
                {data: 'file_id', name: 'files.file_no'},
                {data: 'module', name: 'audit_trail.module'},
                {data: 'remarks', name: 'audit_trail.remarks'},
                {data: 'role_name', searchable: false},
                {data: 'audit_by', name: 'users.full_name'},
                {data: 'created_at', name: 'audit_trail.created_at'},
            ],
            "fnDrawCallback": function( oSettings ) {
                $.unblockUI();  
            }
        });
        generateColumn('files_chart', "{{ trans('app.forms.total_files') }}", <?php echo json_encode($data ? $data['data_files']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_files']['data'] : ''); ?>);
        generateColumn('jmb_chart', "{{ trans('app.forms.total_jmb') }}", <?php echo json_encode($data ? $data['data_jmb']['categories'] : ''); ?>, <?php echo json_encode($data ? $data['data_jmb']['data'] : ''); ?>);

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
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            getChartData(); 
        });

        $("[data-toggle=tooltip]").tooltip();

        $("#strata").select2({
            ajax: {
                url: "{{ route('v3.api.strata.getOption') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                cache: true,
                allowClear: true,
                data: function(params) {
                    return {
                        term: params.term, // search term
                        file_id: $('#file_id').val(),
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
        $("#module").select2({
            ajax: {
                url: "{{ route('v3.api.audit_trail.getModuleOption') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                cache: true,
                allowClear: true,
                data: function(params) {
                    return {
                        term: params.term, // search term
                        company_id: $('#company_id').val(),
                        file_id: $('#file_id').val(),
                    };
                },
                processResults: function(response) {
                    return {
                        results: response.results
                    };
                }
            }
        });
        $('.select3').select2();
    });
    
    function getChartData() {
        $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            $.ajax({
                url: "{{ route('reporting.log.index') }}",
                type: "GET",
                data: {
                    company_id : $('#company_id').val(),
                    role_id : $('#role_id').val(),
                    module : $('#module').val(),
                    file_id : $('#file_id').val(),
                    // strata : $('#strata').val(),
                    date_from : $('#date_from').val(),
                    date_to : $('#date_to').val(),
                    filter: true,
                },
                success: function (res) {
                    generateColumn('files_chart', "{{ trans('app.forms.total_files') }}", res.data.data_files.categories, res.data.data_files.data);
                    generateColumn('jmb_chart', "{{ trans('app.forms.total_jmb') }}", res.data.data_jmb.categories, res.data.data_jmb.data);
                    oTable.draw();
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
