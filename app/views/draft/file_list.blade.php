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

                    @if (Auth::user()->getAdmin())
                    <div class="col-lg-12 text-center">
                        <form>
                            <div class="row">                                
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
                            </div>
                        </form>                        
                    </div>

                    <hr/>
                    @endif

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-hover table-own table-striped" id="filelist" width="100%">
                            <thead>
                                <tr>                                
                                    <th style="width:30%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.active') }}</th>
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
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#filelist').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('DraftController@getFileList') }}",
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[2, "asc"], [1, 'asc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'cob', name: 'company.short_name'},
                {data: 'year', name: 'strata.year'},
                {data: 'active', name: 'files.is_active', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#company').on('change', function () {
            oTable.columns(2).search(this.value).draw();
        });
    });

    function deleteFile(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        }, function () {
            $.ajax({
                url: "{{ URL::action('DraftController@deleteFile') }}",
                type: "POST",
                data: {
                    file_id: id
                },
                success: function (data) {
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.deleted_successfully") }}</p>'
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        });
    }
</script>

@stop