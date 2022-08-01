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
                                <a href="#" onclick="print()" target="_blank">
                                    <button type="button" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                
                <section class="panel panel-pad">
                    <div class="row margin-top-10">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ trans('app.forms.city') }}</label>
                                <select id="city" name="city" class="form-control select2" data-placeholder="{{ trans('app.forms.please_select') }}" data-ajax--url="{{ route('v3.api.city.getOption') }}" data-ajax--cache="true">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top-25 padding-bottom-10">
                            <button type="button" class="btn btn-own" id="cancel_button" onclick="window.location ='{{ route('reporting.cobFileManagement') }}'">{{ trans('app.buttons.reset') }}&nbsp;<i class="fa fa-repeat"></i></button>

                        </div>
                    </div>
                </section>
                @if ($data)
                <div id="management_summary_detail">
                    @include('report_en.cob.management.summary')
                </div>
                @endif
                
                <section class="panel panel-pad">
                    <div class="row text-center padding-vertical-15">
                        <div class="col-lg-12">
                            <h3 class="text-left">{{ trans('app.forms.files_no_management') }}</h3>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-hover table-own table-striped" id="files_no_management" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:1%; text-align: center !important;">#</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.file_no') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                
                <section class="panel panel-pad">
                    <div class="row text-center padding-vertical-15">
                        <div class="col-lg-12">
                            <h3 class="text-left">{{ trans('app.forms.files_no_unit') }}</h3>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-hover table-own table-striped" id="files_no_unit" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:1%; text-align: center !important;">#</th>
                                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.file_no') }}</th>
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
    var management = '';
    $(function () {
        $('#files_no_unit').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('reporting.getFilesWithNoUnit') }}",
            },
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 15,
            order: [[1, 'asc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'id', name: 'files.id', orderable: false, searchable: false},
                {data: 'file_no', name: 'file_no'},
            ]
        });
        $('#files_no_management').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('reporting.getFilesWithNoManagement') }}",
            },
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 15,
            order: [[1, 'asc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'id', name: 'files.id', orderable: false, searchable: false},
                {data: 'file_no', name: 'file_no'},
            ]
        });
        $('select').on('select2:select', function (e) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            $.ajax({
                url: "{{ route('reporting.cobFileManagement') }}",
                type: "GET",
                data: {
                    city: this.value,
                    management: management,
                },
                success: function (res) {
                    $.unblockUI();
                    $('#management_summary_detail').html(res)
                }
            });
        });
    });

    function print() {
        let route = "{{ route('print.cob.file.management') }}";
        let city = $('#city').val() == null ? "" : $('#city').val();
        window.open(route + "?city=" + city + "&management=" + management);
    }

    function filterPetak(type) {
        management = type;
        let city = $('#city').val() == null ? "" : $('#city').val();
        $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
        $.ajax({
            url: "{{ route('reporting.cobFileManagement') }}",
            type: "GET",
            data: {
                city: city,
                management: type,
            },
            success: function (res) {
                $.unblockUI();
                $('#management_summary_detail').html(res)
            }
        });
    }

</script>

@stop
