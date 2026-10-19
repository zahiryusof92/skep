@extends('layout.english_layout.default')

@section('content')

    <?php $company = Company::find(Auth::user()->company_id); ?>

    <style>
        .wrap-text {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        #complaint_table td {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>

    <div class="page-content-inner">
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">
                <div class="invoice-block">
                    <div class="row">
                        <table width="100%">
                            <tr>
                                <td class="text-center">
                                    <h4 class="margin-bottom-0">
                                        <img src="{{ asset($company->image_url) }}" height="100px;" alt="">
                                    </h4>
                                </td>
                                <td>
                                    <h5 class="margin-bottom-10">
                                        {{ $company->name }}
                                    </h5>
                                    <h6 class="margin-bottom-0">
                                        {{ $title }}
                                    </h6>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <hr />

                    <section class="panel panel-pad">
                        <div class="row padding-vertical-30">
                            <div class="col-lg-12 ">
                                <form>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('app.forms.complaint.category') }}
                                                </label>
                                                <select id="category" name="category" class="form-control select2"
                                                    {{ isset($request['category']) ? 'disabled' : '' }}>
                                                    <option value="">
                                                        {{ trans('app.forms.please_select') }}
                                                    </option>
                                                    @if ($categoryList)
                                                        @foreach ($categoryList as $category)
                                                            <option value="{{ \Helper\Helper::encode($category->id) }}"
                                                                data-types='{{ json_encode($category->types) }}'
                                                                {{ isset($request['category']) && \Helper\Helper::decode($request['category']) == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>{{ trans('app.forms.complaint.type') }}</label>
                                                <select id="type" name="type" class="form-control select2">
                                                    <option value="">
                                                        {{ trans('app.forms.please_select') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('app.forms.year') }}
                                                </label>
                                                <select id="year" name="year" class="form-control select2"
                                                    {{ isset($request['year']) ? 'disabled' : '' }}>
                                                    <option value="">
                                                        {{ trans('app.forms.please_select') }}
                                                    </option>
                                                    @if ($yearList)
                                                        @foreach ($yearList as $year)
                                                            <option value="{{ $year }}"
                                                                {{ isset($request['year']) && $request['year'] == $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('app.forms.file_no') }}
                                                </label>
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
                                        @if (\Helper\Helper::isAllCobContext())
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>
                                                        {{ trans('app.forms.complaint.cob') }}
                                                    </label>
                                                    <select id="cob" name="cob" class="form-control select2">
                                                        <option value="">
                                                            {{ trans('app.forms.please_select') }}
                                                        </option>
                                                        <option value="MPKL">MPKL</option>
                                                        <option value="MBSJ">MBSJ</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        @if (\Helper\Helper::showMBSJModules())
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>
                                                        {{ trans('app.forms.complaint.planning_block') }}
                                                    </label>
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

                                    <div class="row">
                                        <div class="col-md-4 padding-bottom-10">
                                            <button type="button" class="btn btn-own" id="cancel_button"
                                                onclick="window.location ='{{ url('reporting/complaints') }}'">
                                                {{ trans('app.buttons.reset') }}&nbsp;<i class="fa fa-repeat"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">

                                    <table class="table table-hover nowrap table-own table-striped" id="complaint_table"
                                        style="table-layout: fixed; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">
                                                    {{ trans('app.forms.complaint.date_received') }}
                                                </th>
                                                @if (\Helper\Helper::isAllCobContext())
                                                    <th style="width:8%;">
                                                        {{ trans('app.forms.complaint.cob') }}
                                                    </th>
                                                @endif
                                                @if (\Helper\Helper::showMBSJModules())
                                                    <th style="width:10%;">
                                                        {{ trans('app.forms.complaint.planning_block') }}
                                                    </th>
                                                    <th style="width:10%;">
                                                        {{ trans('app.forms.complaint.mpp_zone') }}
                                                    </th>
                                                    <th style="width:12%;">
                                                        {{ trans('app.forms.complaint.resolution_method') }}
                                                    </th>
                                                @endif
                                                <th style="width:25%;">
                                                    {{ trans('app.forms.complaint.category') }}
                                                </th>
                                                <th style="width:25%;">
                                                    {{ trans('app.forms.complaint.type') }}
                                                </th>
                                                <th style="width:30%;">
                                                    @if (\Helper\Helper::showMBSJModules())
                                                        {{ trans('app.forms.complaint.title') }}
                                                    @else
                                                        {{ trans('app.forms.complaint.name') }}
                                                    @endif
                                                </th>
                                                <th style="width:10%;">
                                                    {{ trans('app.forms.complaint.status') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </section>
                </div>
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

    <script>
        var oTable;

        $(document).ready(function() {
            oTable = $('#complaint_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "{{ URL::action('ReportController@complaints') }}",
                    'data': function(data) {
                        var category = $('#category').val();
                        var type = $('#type').val();
                        var year = $('#year').val();
                        var file_id = $('#file_id').val();
                        var planning_block = $('#planning_block').val();
                        var cob = $('#cob').val();

                        // Append to data
                        data.category = category;
                        data.type = type;
                        data.year = year;
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
                        {
                            data: 'resolution_method',
                            name: 'complaints.resolution_method',
                            searchable: false
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
                    },
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
                            columns: ':not(:last-child)', // exclude the action column
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
                            columns: ':not(:last-child)', // exclude the action column
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            }
                        }
                    }
                ]
            });

            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            const selectedType = getQueryParam('type');

            $('#category').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const typesData = selectedOption.data('types');

                const $typeSelect = $('#type');
                $typeSelect.empty();

                $typeSelect.append(`<option value="">{{ trans('app.forms.please_select') }}</option>`);

                if (typesData && Array.isArray(typesData)) {
                    typesData.forEach(function(type) {
                        const isSelected = (selectedType && selectedType === type.encoded_id) ?
                            'selected' : '';
                        $typeSelect.append(
                            `<option value="${type.encoded_id}" ${isSelected}>${type.name}</option>`
                        );
                    });
                }

                if (selectedType) {
                    $typeSelect.prop('disabled', true);
                } else {
                    $typeSelect.prop('disabled', false);
                }

                $typeSelect.trigger('change.select2');
                oTable.draw();
            });

            if ($('#category').val()) {
                $('#category').trigger('change');
            }

            $('#year, #type, #file_id, #planning_block, #cob').on('change', function() {
                oTable.draw();
            });
        });
    </script>
@stop
