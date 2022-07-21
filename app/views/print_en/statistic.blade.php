@extends('layout.english_layout.print')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>

<table width="100%">
    <tr>
        <td>
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

            <hr/>
            <table border="1" id="statistic-table-list" width="100%" style="font-size: 11px;">
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
            <hr/>
            <table width="100%">
                <tr>
                    <td>
                        <p><b>{{ trans('app.forms.confidential') }}</b></p>
                    </td>
                    <td class="pull-right">
                        <p>{{ trans('app.forms.print_on', ['print'=>date('d/m/Y h:i:s A', strtotime("now"))]) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End  -->

@stop
