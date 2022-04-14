@extends('layout.english_layout.default_custom')

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
                                    <label>{{ trans('app.forms.file_no') }}</label>
                                    <select id="file_id" name="file_id" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.status') }}</label>
                                    <select id="management" name="management" class="form-control select3" multiple>
                                        <option value="jmb">{{ trans('JMB') }}</option>
                                        <option value="mc">{{ trans('MC') }}</option>
                                        <option value="agent">{{ trans('Agent') }}</option>
                                        <option value="others">{{ trans('Others') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover table-own table-striped" id="table-list" width="100%" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width:10%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.management') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.is_active') }}</th>
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
                    var file_id = $('#file_id').val();
                    var management = $('#management').val();

                    // Append to data
                    data.file_id = file_id;
                    data.management = management;
                }
            },
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[0, "asc"], [1, 'asc'], [3, 'desc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'file_no', name: 'file_no'},
                {data: 'strata_name', name: 'strata.name'},
                {data: 'management', name: 'management.id' },
                {data: 'status', name: 'status'}
            ],
            fnDrawCallback: function( oSettings ) {
                $.unblockUI();  
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
                        management: $('#management').val(),
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
