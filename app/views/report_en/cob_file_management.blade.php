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
                                <a href="{{URL::action('PrintController@printCobFileManagement')}}" target="_blank">
                                    <button type="button" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                
                <section class="panel panel-pad">
                    <div class="row">
                        <div class="col-lg-12">
                            <br/>
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
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;"></th>
                                    </tr>
                                </thead>

                                @if ($data)
                                <tbody>                                
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">KAWASAN PEMAJUAN</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['count_less10'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['count_more10'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">PEMAJU</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['developer'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all'] > 0 ? number_format((($data['developer'] / $data['total_all']) * 100), 2) : 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">LIQUIDATOR</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['liquidator'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all'] > 0 ? number_format((($data['liquidator'] / $data['total_all']) * 100), 2) : 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">JMB</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['jmb'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all'] > 0 ? number_format((($data['jmb'] / $data['total_all']) * 100), 2) : 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">MC</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['mc'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all'] > 0 ? number_format((($data['mc'] / $data['total_all']) * 100), 2) : 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">EJEN</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['agent'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all'] > 0 ? number_format((($data['agent'] / $data['total_all']) * 100), 2) : 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center !important; vertical-align:middle !important;">LAIN-LAIN</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;"></td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ $data['others'] }}</td>
                                        <td style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all'] > 0 ? number_format((($data['others'] / $data['total_all']) * 100), 2) : 0) }}</td>
                                    </tr>                                
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: center !important; vertical-align:middle !important;">JUMLAH JMB, MC & EJEN</th>
                                        <th colspan="3" style="text-align: center !important; vertical-align:middle !important;">{{ ($data['jmb'] + $data['mc'] + $data['agent']) }}</th>
                                        <th style="text-align: center !important; vertical-align:middle !important;">{{ ($data['total_all'] > 0 ? number_format(((($data['jmb'] + $data['mc'] + $data['agent']) / $data['total_all']) * 100), 2) : 0) }}</th>
                                    </tr>
                                </tfoot>
                                @endif

                            </table>
                        </div>
                    </div>
                    <br/>

                    @if ($data)
                    <div class="row padding-bottom-15">
                        <div class="col-lg-6">
                            <table border="1" id="cob_file" width="100%">
                                <thead>
                                    <tr>
                                        <th rowspan="3" style="width:60%; text-align: center !important; vertical-align:middle !important;">JUMLAH PETAK KESELURUHAN SEBENAR</th>
                                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">Kediaman</td>
                                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ $data['residential'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">Kedai / Pejabat</td>
                                        <td style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ $data['commercial'] }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">JUMLAH</th>
                                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ $data['sum_all'] }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </section>

            </div>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    $(function () {
        $("[data-toggle=tooltip]").tooltip();
    });
</script>

@stop
