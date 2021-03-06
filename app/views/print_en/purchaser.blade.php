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

            <table border="1" id="audit_trail" width="100%" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th style="width:5%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.cob') }}</th>
                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.file_no') }}</th>
                        @if ($scheme_name)
                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.scheme_name') }}</th>
                        @endif
                        @if ($unit_no)
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.unit_number') }}</th>
                        @endif
                        @if ($unit_share)
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.unit_share') }}</th>
                        @endif
                        @if ($buyer)
                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.buyer') }}</th>
                        @endif
                        @if ($phone_number)
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.phone_number') }}</th>
                        @endif
                        @if ($email)
                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.email') }}</th>
                        @endif
                        @if ($race)
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.race') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($purchaser)
                    @foreach ($purchaser as $purchasers)
                    <tr>
                        <td>&nbsp; {{ $purchasers->file_id ? $purchasers->file->company->short_name : '-' }}</td>
                        <td>&nbsp; {{ $purchasers->file_id ? $purchasers->file->file_no : '-' }}</td>
                        @if ($scheme_name)
                        <td>&nbsp; {{ $purchasers->file_id ? $purchasers->file->strata->name : '-' }}</td>
                        @endif
                        @if ($unit_no)
                        <td>&nbsp; {{ $purchasers->unit_no }}</td>
                        @endif
                        @if ($unit_share)
                        <td>&nbsp; {{ $purchasers->unit_share }}</td>
                        @endif
                        @if ($buyer)
                        <td>&nbsp; {{ $purchasers->owner_name }}</td>
                        @endif
                        @if ($phone_number)
                        <td>&nbsp; {{ $purchasers->phone_no }}</td>
                        @endif
                        @if ($email)
                        <td>&nbsp; {{ $purchasers->email }}</td>
                        @endif
                        @if ($race)
                        <td>&nbsp; {{ $purchasers->race_id ? $purchasers->race->name_en : '-' }}</td>
                        @endif
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9">No data availabe</td>
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
