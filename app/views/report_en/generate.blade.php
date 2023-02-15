@extends('layout.english_layout.default')

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
                                    <select id="city" name="city" class="form-control select2" multiple
                                        data-ajax--url="{{ route('v3.api.city.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.file_no') }}</label>
                                    <select id="file_id" name="file_id" class="form-control select2" multiple
                                        data-ajax--url="{{ route('v3.api.files.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.developer') }}</label>
                                    <select id="developer" name="developer" class="form-control select2" multiple
                                        data-ajax--url="{{ route('v3.api.developer.getOption') }}"
                                        data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.dun') }}</label>
                                    <select id="dun" name="dun" class="form-control select2" multiple
                                        data-ajax--url="{{ route('v3.api.dun.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.area') }}</label>
                                    <select id="area" name="area" class="form-control select2" multiple
                                        data-ajax--url="{{ route('v3.api.area.getOption') }}" data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.category') }}</label>
                                    <select id="category" name="category" class="form-control select2" multiple
                                        data-ajax--url="{{ route('v3.api.category.getOption') }}"
                                        data-ajax--cache="true">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover table-own table-striped" id="table-list" width="100%"
                            style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width:15%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="city" checked><br />
                                        {{ trans('app.forms.city') }}
                                    </th>
                                    <th style="width:15%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="housing_scheme" checked><br />
                                        {{ trans('app.forms.housing_scheme') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="developer" checked><br />
                                        {{ trans('app.forms.developer') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="lot_number" checked><br />
                                        {{ trans('app.forms.lot_number') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="ownership_number" checked><br />
                                        {{ trans('app.forms.ownership_number') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="strata" checked><br />
                                        {{ trans('app.forms.strata') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="category" checked><br />
                                        {{ trans('app.forms.category') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="management" checked><br />
                                        {{ trans('app.forms.management') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="file_no" checked><br />
                                        {{ trans('app.forms.file_no') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="remarks" checked><br />
                                        {{ trans('app.forms.remarks') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="file_draft_latest_date"
                                            checked><br />
                                        {{ trans('app.forms.file_draft_latest_date') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="latest_insurance_date"
                                            checked><br />
                                        {{ trans('app.forms.latest_insurance_date') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="jmb_date_formed" checked><br />
                                        {{ trans('app.forms.jmb_date_formed') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="mc_date_formed" checked><br />
                                        {{ trans('app.forms.mc_date_formed') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="malay" checked><br />
                                        {{ trans('app.forms.malay') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="chinese" checked><br />
                                        {{ trans('app.forms.chinese') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="indian" checked><br />
                                        {{ trans('app.forms.indian') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="foreigner" checked><br />
                                        {{ trans('app.forms.foreigner') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="others" checked><br />
                                        {{ trans('app.forms.others') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="total_floor" checked><br />
                                        {{ trans('app.forms.total_floor') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="residential_block"
                                            checked><br />
                                        {{ trans('app.forms.residential_block') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="commercial_block" checked><br />
                                        {{ trans('app.forms.commercial_block') }}
                                    </th>
                                    <th style="width:10%;" class="text-center">
                                        <input type="checkbox" name="selected[]" value="block" checked><br />
                                        {{ trans('app.forms.block') }}
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
{{-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script> --}}

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#table-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url' : "{{ route('report.generate.index') }}",
                'data': function(data) {
                    var city = $('#city').val();
                    var file_id = $('#file_id').val();
                    var developer = $('#developer').val();
                    var dun = $('#dun').val();
                    var area = $('#area').val();
                    var category = $('#category').val();
                    var management = $('#management').val();
                    // Append to data
                    data.city = city;
                    data.file_id = file_id;
                    data.developer = developer;
                    data.dun = dun;
                    data.area = area;
                    data.category = category;
                    data.management = management;
                }
            },
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[0, "asc"], [1, 'asc'], [3, 'desc'], [4, 'desc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'city', name: 'city.description'},
                {data: 'house_scheme.name', name: 'house_scheme.name'},
                {data: 'developer', name: 'developer.name'},
                {data: 'strata.lot_no', name: 'strata.lot_no'},
                {data: 'strata.ownership_no', name: 'strata.ownership_no'},
                {data: 'strata_name', name: 'strata.name'},
                {data: 'category', name: 'category.description'},
                {data: 'management', name: 'management.id' },
                {data: 'file_no', name: 'file_no'},
                {data: 'house_scheme.remarks', name: 'house_scheme.remarks'},
                {data: 'latest_file_draft_date', name: 'latest_file_draft_date', orderable: false, searchable: false },
                {data: 'latest_insurance_date', name: 'latest_insurance_date', orderable: false, searchable: false},
                {data: 'jmb_date_formed', name: 'jmb_date_formed', orderable: false, searchable: false},
                {data: 'mc_date_formed', name: 'mc_date_formed', orderable: false, searchable: false},
                {data: 'other.malay_composition', name: 'others_details.malay_composition'},
                {data: 'other.chinese_composition', name: 'others_details.chinese_composition'},
                {data: 'other.indian_composition', name: 'others_details.indian_composition'},
                {data: 'other.foreigner_composition', name: 'others_details.foreigner_composition'},
                {data: 'other.others_composition', name: 'others_details.others_composition'},
                {data: 'strata.total_floor', name: 'strata.total_floor'},
                {data: 'sum_residential', name: 'sum_residential', orderable: false, searchable: false },
                {data: 'sum_commercial', name: 'sum_commercial', orderable: false, searchable: false },
                {data: 'strata.block_no', name: 'strata.block_no'},
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
                        var developer = $('#developer').val();
                        var dun = $('#dun').val();
                        var area = $('#area').val();
                        
                        window.open("{{ route('report.generateSelected.index') }}" + "?city=" + city + "&management=" + management + "&file_id=" + file_id + "&category=" + category 
                                            + "&developer=" + developer + "&dun=" + dun + "&area=" + area + "&export=pdf", '_blank');
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
                        var developer = $('#developer').val();
                        var dun = $('#dun').val();
                        var area = $('#area').val();
                        
                        window.open("{{ route('report.generateSelected.index') }}" + "?city=" + city + "&management=" + management + "&file_id=" + file_id + "&category=" + category 
                                            + "&developer=" + developer + "&dun=" + dun + "&area=" + area + "&export=excel", '_blank');
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
            oTable.draw();
        });
        $('select').on('select2:unselect', function (e) {
            oTable.draw();
        });
        if("{{ $management }}" != '') {
            $("#management").val("{{ $management }}").trigger('change');
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            oTable.draw();
        }
        
        $("#reset").on("click", function () {
            $('select').val(null).trigger("change");
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
        });
    });
</script>

@endsection