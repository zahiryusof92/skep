@extends('layout.english_layout.default')

@section('content')

<?php $company = Company::find(Auth::user()->company_id); ?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="invoice-block">
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
                        </tr>
                    </table>
                </div>

                <hr/>

                <div class="row" style="margin-top: 30px;">
                    <div class="col-lg-12 text-center">
                        <form action="{{ url('/reporting/managementList') }}" method="POST" target="_blank" >
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select class="form-control select2" id="company" name="company">
                                            @if (count($cob) > 1)
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @endif
                                            @foreach ($cob as $cobs)
                                            <option value="{{ $cobs->short_name }}">{{ $cobs->name }} ({{ $cobs->short_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.file_no') }}</label>
                                        <select id="file_no" name="file_no" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $files_no)
                                            <option value="{{ $files_no->id }}">{{ $files_no->file_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br/>
                                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover nowrap" id="management_list" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">{{ trans('app.forms.cob') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.type') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.type_name') }}</th>
                                        <th style="width:25%;">{{ trans('app.forms.address') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.email') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.phone_number') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End  -->
</div>

<script>
    $(document).ready(function () {
        var oTable = $('#management_list').DataTable({
            "sAjaxSource": "{{URL::action('ReportController@getManagementList')}}",
            "lengthMenu": [
                [15, 30, 50, 100, -1],
                [15, 30, 50, 100, "All"]
            ],
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ],
            "order": [[0, "asc"], [1, "asc"]],
            "scrollX": true,
            "responsive": false
        });

        $('#company').on('change', function () {
            $.ajax({
                url: "{{ URL::action('AgmController@getFileListByCOB') }}",
                type: "POST",
                data: {
                    company: $("#company").val()
                },
                success: function (data) {
                    $("#file_no").html(data);
//                    oTable.columns(1).search('').draw();
                }
            });

            oTable.columns(0).search(this.value).draw();
        });

        $('#file_no').on('change', function () {
            oTable.columns(1).search(this.value).draw();
        });
    });
</script>

@stop
