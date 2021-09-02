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
            <table border="1" id="finance_support" width="100%" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.file_no') }}</th>
                        <th style="width:25%; text-align: center !important;">{{ trans('app.forms.strata') }}</th>
                        <th style="width:10%; text-align: center !important;">{{ trans('app.forms.date') }}</th>
                        <th style="width:25%; text-align: center !important;">{{ trans('app.forms.donation') }}</th>
                        <th style="width:20%; text-align: center !important;">{{ trans('app.forms.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($items) > 0)
                    @foreach ($items as $item)
                    <tr>
                        <td>{{!empty($item->file())? $item->file->file_no : '-'}}</td>
                        <td>{{!empty($item->file())? $item->file->strata->strataName() : '-'}}</td>
                        <td>{{date('d/m/Y', strtotime($item->created_at))}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{number_format($item->amount, 2)}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" style="text-align: center">{{ trans('app.forms.no_data_available') }}</td>
                    </tr>
                    @endif
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

<!-- Page Scripts -->

@stop
