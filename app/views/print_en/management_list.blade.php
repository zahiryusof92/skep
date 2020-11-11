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

            <table border="1" width="100%" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th style="width:5%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.cob') }}</th>
                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.file_no') }}</th>
                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.file_name') }}</th>
                        <th style="width:5%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.type') }}</th>
                        @if ($type_name)
                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.type_name') }}</th>
                        @endif
                        @if ($address)
                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.address') }}</th>
                        @endif
                        @if ($email)
                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.email') }}</th>
                        @endif
                        @if ($phone_number)
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.phone_number') }}</th>
                        @endif
                    </tr>
                </thead>                                
                <tbody>
                    @if ($result)
                    @foreach ($result as $res)
                    <tr>
                        <td>&nbsp; {{ $res[0] }}</td>
                        <td>&nbsp; {{ $res[1] }}</td>
                        <td>&nbsp; {{ $res[2] }}</td>
                        <td>&nbsp; {{ $res[3] }}</td>
                        @if ($type_name)
                        <td>&nbsp; {{ $res[4] }}</td>
                        @endif
                        @if ($address)
                        <td>&nbsp; {{ $res[5] }}</td>
                        @endif
                        @if ($email)
                        <td>&nbsp; {{ $res[6] }}</td>
                        @endif
                        @if ($phone_number)
                        <td>&nbsp; {{ $res[7] }}</td>
                        @endif
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7">No data availabe</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </td>
    </tr>
</table>

@stop
