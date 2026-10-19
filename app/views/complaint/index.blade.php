@extends('layout.english_layout.default')

@section('content')
    <style>
        .wrap-text {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        #complaint_table td,
        #complaint_table th {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>

    <?php
        // The table uses table-layout: fixed, so the column widths must add up to
        // 100%. The extra COB / MBSJ / action columns push the total over 100%
        // (MBSJ = 116%, All-COB = 124%) which overflows the table and breaks the
        // header, so scale the weights to always total 100%.
        $thDate = 10;
        $thCob = 8;
        $thBlock = 8;
        $thZone = 8;
        $thCat = 20;
        $thType = 20;
        $thTitle = 30;
        $thStatus = 10;
        $thAction = 10;

        $thTotal = $thDate + $thCat + $thType + $thTitle + $thStatus;
        if (\Helper\Helper::isAllCobContext()) {
            $thTotal += $thCob;
        }
        if (\Helper\Helper::showMBSJModules()) {
            $thTotal += $thBlock + $thZone;
        }
        if (AccessGroup::hasUpdateModule('Complaint')) {
            $thTotal += $thAction;
        }
        $thScale = $thTotal > 0 ? 100 / $thTotal : 1;
    ?>

    <div class="page-content-inner">
        <section class="panel panel-style">

            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>

            <div class="panel-body">

                @include('alert.bootbox')

                <section class="panel panel-pad">
                    <div class="row padding-vertical-20">
                        <div class="col-lg-12">
                            @if (AccessGroup::hasInsertModule('Complaint'))
                                <div class="margin-bottom-30">
                                    <a href="{{ route('complaint.create') }}" class="btn btn-own">
                                        {{ trans('app.buttons.add_complaint') }}
                                    </a>
                                </div>
                            @endif

                            <form>
                                <div class="row text-center">
                                    <div class="col-lg-4">
                                        <div class="form-group float-left">
                                            <label>
                                                {{ trans('app.forms.complaint.date_received') }}
                                            </label>
                                            <br>
                                            <input id="start_date" data-column="0" type="text"
                                                class="form-control width-150 display-inline-block" placeholder="From" />
                                            <span class="margin-right-10">&nbsp; —</span>
                                            <input id="end_date" data-column="0" type="text"
                                                class="form-control width-150 display-inline-block" placeholder="To" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ trans('app.forms.file_no') }}</label>
                                            <select id="file_id" name="file_id" class="form-control select2">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @foreach ($files as $file)
                                                    <option value="{{ $file->id }}">
                                                        {{ $file->strataName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if (\Helper\Helper::showMBSJModules())
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>{{ trans('app.forms.complaint.planning_block') }}</label>
                                                <select id="planning_block" name="planning_block"
                                                    class="form-control select2">
                                                    <option value="">
                                                        {{ trans('app.forms.please_select') }}
                                                    </option>
                                                    @foreach (\Helper\Helper::mbsjPlanningBlocks() as $block)
                                                        <option value="{{ $block }}">{{ $block }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if (\Helper\Helper::isAllCobContext())
                                    <div class="row text-center">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>{{ trans('app.forms.complaint.cob') }}</label>
                                                <select id="cob" name="cob" class="form-control select2">
                                                    <option value="">
                                                        {{ trans('app.forms.please_select') }}
                                                    </option>
                                                    <option value="MPKL">MPKL</option>
                                                    <option value="MBSJ">MBSJ</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-4 padding-bottom-10">
                                        <button type="button" class="btn btn-own" id="cancel_button"
                                            onclick="window.location ='{{ route('complaint.index') }}'">
                                            {{ trans('app.buttons.reset') }}
                                            <i class="fa fa-repeat"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <hr />

                            <table class="table table-hover nowrap table-own table-striped" id="complaint_table"
                                style="table-layout: fixed; width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width:{{ round($thDate * $thScale, 2) }}%;">
                                            {{ trans('app.forms.complaint.date_received') }}
                                        </th>
                                        @if (\Helper\Helper::isAllCobContext())
                                            <th style="width:{{ round($thCob * $thScale, 2) }}%;">
                                                {{ trans('app.forms.complaint.cob') }}
                                            </th>
                                        @endif
                                        @if (\Helper\Helper::showMBSJModules())
                                            <th style="width:{{ round($thBlock * $thScale, 2) }}%;">
                                                {{ trans('app.forms.complaint.planning_block') }}
                                            </th>
                                            <th style="width:{{ round($thZone * $thScale, 2) }}%;">
                                                {{ trans('app.forms.complaint.mpp_zone') }}
                                            </th>
                                        @endif
                                        <th style="width:{{ round($thCat * $thScale, 2) }}%;">
                                            {{ trans('app.forms.complaint.category') }}
                                        </th>
                                        <th style="width:{{ round($thType * $thScale, 2) }}%;">
                                            {{ trans('app.forms.complaint.type') }}
                                        </th>
                                        <th style="width:{{ round($thTitle * $thScale, 2) }}%;">
                                            @if (\Helper\Helper::showMBSJModules())
                                                {{ trans('app.forms.complaint.title') }}
                                            @else
                                                {{ trans('app.forms.complaint.name') }}
                                            @endif
                                        </th>
                                        <th style="width:{{ round($thStatus * $thScale, 2) }}%;">
                                            {{ trans('app.forms.complaint.status') }}
                                        </th>
                                        @if (AccessGroup::hasUpdateModule('Complaint'))
                                            <th style="width:{{ round($thAction * $thScale, 2) }}%;">
                                                {{ trans('app.forms.action') }}
                                            </th>
                                        @endif
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
        <!-- End  -->
    </div>

    <!-- DataTables Button -->
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

    <!-- Page Scripts -->
    <script>
        var oTable;
        // When the user cannot update complaints the action column is not
        // rendered, so export every column instead of dropping the last one.
        var hasActionColumn = {{ AccessGroup::hasUpdateModule('Complaint') ? 'true' : 'false' }};

        $(document).ready(function() {
            oTable = $('#complaint_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ route('complaint.index') }}",
                    'data': function(data) {
                        var from_date = $('#start_date').val();
                        var to_date = $('#end_date').val();
                        var file_id = $('#file_id').val();
                        var planning_block = $('#planning_block').val();
                        var cob = $('#cob').val();

                        // Append to data
                        data.start_date = from_date;
                        data.end_date = to_date;
                        data.file_id = file_id;
                        data.planning_block = planning_block;
                        data.cob = cob;
                    }
                },
                lengthMenu: [
                    [15, 30, 50],
                    [15, 30, 50]
                ],
                pageLength: 30,
                order: [
                    [0, "desc"],
                ],
                responsive: false,
                columns: [{
                        data: 'date_received',
                        name: 'complaints.date_received'
                    },
                    @if (\Helper\Helper::isAllCobContext())
                        {
                            data: 'cob',
                            name: 'company.short_name'
                        },
                    @endif
                    @if (\Helper\Helper::showMBSJModules())
                        {
                            data: 'planning_block',
                            name: 'complaints.planning_block'
                        },
                        {
                            data: 'mpp_zone',
                            name: 'complaints.mpp_zone'
                        },
                    @endif
                    {
                        data: 'complaint_category_id',
                        name: 'complaints.complaint_category_id'
                    },
                    {
                        data: 'complaint_type_id',
                        name: 'complaints.complaint_type_id'
                    },
                    {
                        data: 'name',
                        name: 'complaints.name'
                    },
                    {
                        data: 'status',
                        name: 'complaints.status',
                        searchable: false
                    }@if (AccessGroup::hasUpdateModule('Complaint')),
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    @endif
                ],
                "dom": "<'row'<'col-md-12 margin-bottom-10'B>>" +
                    "<'row'<'col-md-6'l><'col-md-6'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",
                "buttons": [{
                        extend: 'excel',
                        text: 'Export to Excel',
                        filename: function() {
                            const today = new Date();
                            const year = today.getFullYear();
                            const month = String(today.getMonth() + 1).padStart(2, '0');
                            const day = String(today.getDate()).padStart(2, '0');
                            return 'Complaint_' + year + '-' + month + '-' + day;
                        },
                        exportOptions: {
                            columns: hasActionColumn ? ':not(:last-child)' : ':visible',
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Export to PDF',
                        filename: function() {
                            const today = new Date();
                            const year = today.getFullYear();
                            const month = String(today.getMonth() + 1).padStart(2, '0');
                            const day = String(today.getDate()).padStart(2, '0');
                            return 'Complaint_' + year + '-' + month + '-' + day;
                        },
                        exportOptions: {
                            columns: hasActionColumn ? ':not(:last-child)' : ':visible',
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            }
                        }
                    }
                ],
                columnDefs: [{
                    "targets": -1,
                    "className": "text-center"
                }]
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
            }).on('dp.change', function() {
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
            }).on('dp.change', function() {
                oTable.draw();

            });

            $("#file_id").on('change', function() {
                oTable.draw();
            });

            $("#planning_block").on('change', function() {
                oTable.draw();
            });

            $("#cob").on('change', function() {
                oTable.draw();
            });
        });

        $('body').on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            let formId = $(this).data('id');
            swal({
                title: "{{ trans('app.confirmation.are_you_sure') }}",
                text: "{{ trans('app.confirmation.no_recover_file') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                cancelButtonClass: "btn-default",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            }, function() {
                $('#' + formId).submit();
            });
        });
    </script>
@endsection
