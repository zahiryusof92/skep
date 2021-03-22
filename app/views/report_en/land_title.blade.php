@extends('layout.english_layout.default')

@section('content')

<?php $company = Company::find(Auth::user()->company_id); ?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
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
                                <a href="{{ url('print/landTitle/' . (!empty($cob_id) ? $cob_id : 'all') . '/' . (!empty($land_title_id) ? $land_title_id : 'all')) }}" target="_blank">
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ url('/reporting/landTitle') }}" method="GET" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select class="form-control select2" id="cob_id" name="cob_id">
                                            @if (count($cob) > 1)
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @endif
                                            @foreach ($cob as $cobs)
                                            <option value="{{ $cobs->id }}" {{ ($cobs->id == $cob_id ? 'selected' : '') }}>{{ $cobs->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.land_title') }}</label>
                                        <select class="form-control select2" id="land_title_id" name="land_title_id">
                                            @if (count($category) > 1)
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @endif
                                            @foreach ($category as $categories)
                                            <option value="{{ $categories->id }}" {{ ($categories->id == $land_title_id ? 'selected' : '') }}>{{ $categories->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>&nbsp;</label><br/>
                                    <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.submit') }}</button>
                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@landTitle") }}'"">{{ trans('app.buttons.reset') }}</button>
                                    <img id="loading" style="display: none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($file_info)                
                <div class="row margin-top-10">
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
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>
    <!-- End  -->
</div>
@stop
