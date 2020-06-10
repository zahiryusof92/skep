@extends('layout.english_layout.print')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
$totalless10 = 0;
$totalmore10 = 0;

if (count($strata) > 0) {
    foreach ($strata as $stratas) {
        if ($stratas->is_residential == 1 && $stratas->is_commercial == 1) {
            $less10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            if (count($less10residential) <= 0) {
                $less10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            } else {
                $less10commercial = 0;
            }
            $totalless10 = $totalless10 + ($less10residential + $less10commercial);

            $more10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            if (count($less10residential) <= 0) {
                $more10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            } else {
                $more10commercial = 0;
            }
            $totalmore10 = $totalmore10 + ($more10residential + $more10commercial);
        } else if ($stratas->is_residential == 1 && $stratas->is_commercial == 0) {
            $less10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            $totalless10 = $totalless10 + $less10residential;

            $more10residential = Residential::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            $totalmore10 = $totalmore10 + $more10residential;
        } else if ($stratas->is_residential == 0 && $stratas->is_commercial == 1) {
            $less10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '<=', 10)->count();
            $totalless10 = $totalless10 + $less10commercial;

            $more10commercial = Commercial::where('strata_id', $stratas->id)->where('unit_no', '>', 10)->count();
            $totalmore10 = $totalmore10 + $more10commercial;
        } else {
            $totalless10 = $totalless10 + 1;
            $totalmore10 = $totalmore10 + 0;
        }
    }
}
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
            <table border="1" id="cob_file_management" width="100%">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:40%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.items') }}</th>
                        <th colspan="3" style="width:30%; text-align: center !important; vertical-align:middle !important;">Bilangan Terkini</th>
                        <th rowspan="2" style="width:30%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.percentage') }} (%)</th>
                    </tr>
                    <tr>
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;"><= 10</th>
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">> 10</th>
                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;"> </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">KAWASAN PEMAJUAN</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$totalless10}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$totalmore10}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">PEMAJU DAN LIQUIDATOR</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$developer}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{number_format((($developer / $total) * 100), 2)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">JMB</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$jmb}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{number_format((($jmb / $total) * 100), 2)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">MC</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$mc}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{number_format((($mc / $total) * 100), 2)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">EJEN</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$agent}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{number_format((($agent / $total) * 100), 2)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center !important; vertical-align:middle !important;">LAIN-LAIN</td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{$others}}</td>
                        <td style="text-align: center !important; vertical-align:middle !important;">{{number_format((($others / $total) * 100), 2)}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="text-align: center !important; vertical-align:middle !important;">JUMLAH JMB, MC & EJEN</th>
                        <th colspan="3" style="text-align: center !important; vertical-align:middle !important;">{{$jmb + $mc + $agent}}</th>
                        <th style="text-align: center !important; vertical-align:middle !important;">{{number_format((($jmb + $mc + $agent) / $total), 2)}}</th>
                    </tr>
                </tfoot>
            </table>
            <br/>
            <table border="1" id="cob_file" width="70%">
                <thead>
                    <tr>
                        <th rowspan="3" style="width:60%; text-align: center !important; vertical-align:middle !important;">JUMLAH PETAK KESELURUHAN SEBENAR</th>
                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">Kediaman</td>
                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">{{$residential}}</td>
                    </tr>
                    <tr>
                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">Kedai / Pejabat</td>
                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">{{$commercial}}</td>
                    </tr>
                    <tr>
                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">JUMLAH</th>
                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{$residential + $commercial}}</th>
                    </tr>
                </thead>
                <tbody>
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
