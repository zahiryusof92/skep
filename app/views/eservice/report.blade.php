@extends('layout.english_layout.default')

@section('content')
    <div class="page-content-inner">
        <section class="panel panel-style">

            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>

            <div class="panel-body">
                <div class="invoice-block">
                    <section class="panel panel-pad">

                        <form>
                            <div class="row margin-top-10">
                                <div class="col-lg-12 text-center">
                                    @if (Auth::user()->getAdmin())
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('app.forms.cob') }}</label>
                                                <select id="company" name="company" class="form-control select2">
                                                    @foreach ($company as $companies)
                                                        <option value="{{ $companies->id }}" {{ $companies->short_name == 'MBPJ' ? 'selected' : '' }}>
                                                            {{ $companies->name }} ({{ $companies->short_name }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ trans('app.forms.created_at') }} </label><br>
                                            <input id="start_date" name="start_date" type="text" style="width: 46%;"
                                                class="form-control display-inline-block datetimepicker"
                                                placeholder="From" />
                                            <span style="padding-right: 2%;padding-left: 2%;">&dash;</span>
                                            <input id="end_date" name="end_date" type="text" style="width: 46%;"
                                                class="form-control display-inline-block datetimepicker" placeholder="To" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 padding-top-25 padding-bottom-10">
                                    <button type="button" class="btn btn-own" id="cancel_button"
                                        onclick="window.location ='{{ route('eservice.report') }}'">
                                        {{ trans('app.buttons.reset') }}&nbsp;
                                        <i class="fa fa-repeat"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>

                    <div class="row">
                        <div class="col-lg-12">

                            @if (!empty($data))
                                <!-- Graph -->
                                <div class="margin-bottom-50 chart-custom">
                                    <div id="report_chart"></div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>

    <script>
        let title = '{{ trans('Type vs Total Records') }}';
        let categories = <?php echo isset($data['categories']) && !empty($data['categories']) ? json_encode($data['categories']) : ''; ?>;
        let series = <?php echo isset($data['series']) && !empty($data['series']) ? json_encode($data['series']) : ''; ?>;

        generateColumn('report_chart', title, categories, series);

        function generateColumn(id, title, categories, series) {
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
                        text: '(records)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: series
            });
        }

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
        }).on('dp.change', function() {
            getChartData();
        });

        $('#company').on('change', function () {
            getChartData();
        });

        function getChartData() {
            $.blockUI({
                message: '{{ trans('app.confirmation.please_wait') }}'
            });

            $.ajax({
                url: "{{ route('eservice.report') }}",
                type: "GET",
                data: {
                    company: $('#company').val(),
                    date_from: $('#start_date').val(),
                    date_to: $('#end_date').val(),
                },
                success: function(res) {
                    generateColumn('report_chart', title, res.data.categories, res.data.series);
                    $.unblockUI();
                }
            });
        }
    </script>
@endsection
