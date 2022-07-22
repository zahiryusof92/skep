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
            <form target="_blank" action="{{ route('print.statistic.index') }}" method="POST">
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
                                    <button type="submit" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr/>
                    <br/>
                    <section class="panel panel-pad">
                        <div class="row">
                            <div class="col-lg-12 padding-vertical-10">
                                <table class="table table-hover table-own table-striped table-bordered" id="statistic-table-list" width="100%" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center">{{ trans('app.forms.grains') }}</th>
                                            <th colspan="56" class="text-center">{{ trans('app.forms.city') }}</th>
                                            <th rowspan="2" class="text-center">{{ trans('app.forms.overall_total') }}</th>
                                        </tr>
                                        <tr>
                                            @foreach($cities as $city)
                                            <th class="text-center">{{ $city->description }}</th> 
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="statistic-table-body">
                                        @include('report_en.statistic.table')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- DataTables Button -->

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        // $('#year').on('change', function() {
        //     $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
        //     $.ajax({
        //         url: "{{ route('report.statistic.index') }}",
        //         type: "GET",
        //         data: {
        //             year: this.value,
        //         },
        //         success: function (res) {
        //             $.unblockUI();
        //             $('#statistic-table-body').html(res);
        //         }
        //     });
        // });
    });
</script>

@stop
