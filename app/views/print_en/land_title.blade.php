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
            @if ($file_info)
            <div class="row">
                <div class="col-lg-12">
                    <table border="1" id="land_title_report_table" width="100%">
                        <thead>
                            <tr>
                                <th style="width:45%; text-align: center !important; vertical-align:middle !important;">COB</th>
                                <th style="width:45%; text-align: center !important; vertical-align:middle !important;">KATEGORI</th>
                                <th style="width:10%; text-align: center !important; vertical-align:middle !important;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($file_info as $file)
                            @if (count($file['category']) > 0)
                            @foreach ($file['category'] as $cat)
                            <tr>
                                <td rowspan="" style="text-align: left !important; vertical-align:middle !important;">&nbsp; {{ $file['company']['name'] }}</td>
                                <td rowspan="" style="text-align: center !important; vertical-align:middle !important;">{{ $cat['name'] }}</td>
                                <td rowspan="" style="text-align: center !important; vertical-align:middle !important;">{{ $cat['total'] }}</td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                        </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
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