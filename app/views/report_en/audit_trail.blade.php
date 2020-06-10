@extends('layout.english_layout.default')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <form target="_blank" action="{{ url('/print/AuditTrail') }}" method="POST">
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
                                    <input type="hidden" name="start" id="start">
                                    <input type="hidden" name="end" id="end">
                                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr/>
                    <br/>
                    <div class="row text-center">
                        <div class="col-lg-12">
                            <span style="font-size: 12px;"><b>{{ trans('app.forms.date_audited') }}: </b></span>&nbsp;
                            <input style="font-size: 12px;" id="date_from" data-column="0" type="text" class="form-control width-150 display-inline-block" placeholder="From"/>
                            <span style="font-size: 12px;" class="margin-right-10">&nbsp; —</span>
                            <input style="font-size: 12px;" id="date_to" data-column="0" type="text" class="form-control width-150 display-inline-block" placeholder="To"/>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table" id="audit_trail" width="100%" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.date') }}</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.module') }}</th>
                                        <th style="width:50%; text-align: center !important;">{{ trans('app.forms.activities') }}</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.action_from') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
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
                "url": "{{ URL::action('AdminController@getAuditTrail') }}",
                "dataType": "json",
                "type": "POST"
            },
            "dom": '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            "order": [[0, "desc"]],
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": 25,
            "scrollX": true,
            "responsive": false,
            "columns": [
                {"data": "created_at"},
                {"data": "module"},
                {"data": "remarks"},
                {"data": "full_name"}
            ]
        });

        $('#date_from').on('dp.change', function () {
            $("#date_to").val("");

            let currentDate = $(this).val().split('-');
            $("#start").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $('#date_to').on('dp.change', function () {
            var i = $(this).attr('data-column');
            var to_date = $(this).val();
            var from_date = $("#date_from").val();
            if (from_date.trim() !== "" && to_date.trim() !== "") {
                var date = from_date + "&" + to_date;
                oTable.columns(i).search(date).draw();
            }

            let currentDate = $(this).val().split('-');
            $("#end").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });

    $(function () {
        $('#date_from').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD-MM-YYYY'
        });
        $('#date_to').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD-MM-YYYY'
        });

        $("[data-toggle=tooltip]").tooltip();
    });
</script>

@stop
