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
                            <td class="text-center">
                                <a href="{{URL::action('PrintController@printFileByLocation')}}" target="_blank">
                                    <button type="button" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                <section class="panel panel-pad">
                    <div class="row padding-vertical-10">
                        <div class="col-lg-12">
                            <br/>
                            <table class="table table-hover table-own table-striped" id="file_location_list" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.parliament') }}</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.dun') }}</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.park') }}</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.file_no') }}.</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.development_area') }}</th>
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
        oTable = $('#file_location_list').DataTable({
            "sAjaxSource": "{{URL::action('ReportController@getFileByLocation')}}",
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": 50,
            "order": [[0, "asc"]],
            "scrollX": true,
            "responsive": false
        });
    });

    $(function () {
        $("[data-toggle=tooltip]").tooltip();
    });
</script>

@stop
