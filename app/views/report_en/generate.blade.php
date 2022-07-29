@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-10">
                    <div class="col-lg-2">
                        <button class="btn btn-own" id="reset">
                            {{ trans('app.buttons.reset') }} &nbsp;<i class="fa fa-repeat"></i>
                        </button>
                    </div>
                </div>
                <div class="row padding-vertical-10">
                    <div class="col-lg-12 text-center">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.city') }}</label>
                                    <select id="city" name="city" class="form-control select2" multiple data-ajax--url="{{ route('v3.api.city.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.developer') }}</label>
                                    <select id="developer" name="developer" class="form-control select2" multiple data-ajax--url="{{ route('v3.api.developer.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.strata') }}</label>
                                    <select id="strata" name="strata" class="form-control select2" multiple data-ajax--url="{{ route('v3.api.strata.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.category') }}</label>
                                    <select id="category" name="category" class="form-control select2" multiple data-ajax--url="{{ route('v3.api.category.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.file_no') }}</label>
                                    <select id="file_id" name="file_id" class="form-control select2" multiple data-ajax--url="{{ route('v3.api.files.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.status') }}</label>
                                    <select id="management" name="management" class="form-control select2" multiple>
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach($managementStatus as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover nowrap table-own table-striped" id="table-list" width="100%" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="file_no" checked><br/>
                                        {{ trans('app.forms.file_no') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="city" checked><br/>
                                        {{ trans('app.forms.city') }}
                                    </th>
                                    {{-- <th style="width:10%;">
                                        <input type="checkbox" name="selected[]" value="house_scheme" checked><br/>
                                        {{ trans('app.forms.scheme_name') }}
                                    </th> --}}
                                    {{-- <th style="width:10%;">
                                        <input type="checkbox" name="selected[]" value="developer" checked><br/>
                                        {{ trans('app.forms.developer') }}
                                    </th> --}}
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="strata" checked><br/>
                                        {{ trans('app.forms.strata') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="category" checked><br/>
                                        {{ trans('app.forms.category') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="file_draft_latest_date" checked><br/>
                                        {{ trans('app.forms.file_draft_latest_date') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="latest_agm_date" checked><br/>
                                        {{ trans('app.forms.latest_agm_date') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="latest_insurance_date" checked><br/>
                                        {{ trans('app.forms.latest_insurance_date') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="jmb_date_formed" checked><br/>
                                        {{ trans('app.forms.jmb_date_formed') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="mc_date_formed" checked><br/>
                                        {{ trans('app.forms.mc_date_formed') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="total_floor" checked><br/>
                                        {{ trans('app.forms.total_floor') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="residential_block" checked><br/>
                                        {{ trans('app.forms.residential_block') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="commercial_block" checked><br/>
                                        {{ trans('app.forms.commercial_block') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="block" checked><br/>
                                        {{ trans('app.forms.block') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="management" checked><br/>
                                        {{ trans('app.forms.management') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="is_active" checked><br/>
                                        {{ trans('app.forms.is_active') }}
                                    </th>
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
    $(document).ready(function () {
        oTable = $('#table-list').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ route('report.generate.index') }}",
                'data': function(data) {
                    var city = $('#city').val();
                    var file_id = $('#file_id').val();
                    var category = $('#category').val();
                    var management = $('#management').val();
                    var strata = $('#strata').val();

                    // Append to data
                    data.city = city;
                    data.file_id = file_id;
                    data.category = category;
                    data.management = management;
                    data.strata = strata;
                }
            },
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[0, "asc"], [1, 'asc'], [3, 'desc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'file_no', name: 'file_no'},
                {data: 'city', name: 'city.description'},
                // {data: 'house_scheme.name', name: 'house_scheme.name'},
                // {data: 'developer', name: 'developer.name'},
                {data: 'strata_name', name: 'strata.name'},
                {data: 'category', name: 'category.description'},
                {data: 'latest_file_draft_date', name: 'latest_file_draft_date', orderable: false, searchable: false },
                {data: 'latest_agm_date', name: 'latest_agm_date', orderable: false, searchable: false },
                {data: 'latest_insurance_date', name: 'latest_insurance_date', orderable: false, searchable: false },
                {data: 'jmb_date_formed', name: 'jmb_date_formed', orderable: false, searchable: false },
                {data: 'mc_date_formed', name: 'mc_date_formed', orderable: false, searchable: false },
                {data: 'strata.total_floor', name: 'strata.total_floor'},
                {data: 'sum_residential', name: 'sum_residential', orderable: false, searchable: false },
                {data: 'sum_commercial', name: 'sum_commercial', orderable: false, searchable: false },
                {data: 'strata.block_no', name: 'strata.block_no'},
                {data: 'management', name: 'management.id' },
                {data: 'status', name: 'status'}
            ],
            "dom": "<'row'<'col-md-12 margin-bottom-10'B>>" +
                "<'row'<'col-md-6'l><'col-md-6'f>>" +
                "<'row'<'col-md-12'tr>>" +
                "<'row'<'col-md-5'i><'col-md-7'p>>",
            "buttons": [
                { 
                    text: 'Print to PDF',
                    action: function ( e, dt, node, config ) {
                        var city = $('#city').val();
                        var management = $('#management').val();
                        var file_id = $('#file_id').val();
                        var category = $('#category').val();
                        var strata = $('#strata').val();
                        
                        window.open("{{ route('report.generateSelected.index') }}" + "?city=" + city + "&management=" + management + "&file_id=" + file_id + "&category=" + category + "&strata=" + strata + "&export=pdf", '_blank');
                    }
                } ,
                {
                    // extend: 'excel',
                    text: 'Export to Excel',
                    title: "{{$company->name}}",
                    action: function ( xlsx ) {
                        var city = $('#city').val();
                        var management = $('#management').val();
                        var file_id = $('#file_id').val();
                        var category = $('#category').val();
                        var strata = $('#strata').val();
                        
                        window.open("{{ route('report.generateSelected.index') }}" + "?city=" + city + "&management=" + management + "&file_id=" + file_id + "&category=" + category + "&strata=" + strata + "&export=excel", '_blank');
                    },
                    exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied'
                        },
                    }
                },
            ],
            fnDrawCallback: function( oSettings ) {
                $.unblockUI();  
                $("#table-list thead").remove();
            }
        });

        $('select').on('select2:select', function (e) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            oTable.draw();
        });

        $('select').on('select2:unselect', function (e) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            oTable.draw();
        });
        $('.select3').select2();
        if("{{ $management }}" != '') {
            $(".select3").val("{{ $management }}").trigger('change');
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            oTable.draw();
        }
        
        $("#reset").on("click", function () {
            $('select').val(null).trigger("change");
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
        });
    });
</script>

@stop
