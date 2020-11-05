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
                        </tr>
                    </table>
                </div>

                <hr/>

                <div class="row" style="margin-top: 30px;">
                    <div class="col-lg-12">
                        <form action="{{ url('/print/managementList') }}" method="POST" target="_blank" >
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <input type="hidden" name="company" value="{{ $cob_company }}"/>
                                        <p>{{ $cob_name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.file_no') }}</label>
                                        <input type="hidden" name="file_no" value="{{ $file_no }}"/>
                                        <p>{{ $file_name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Filter</label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label><input type="checkbox" name="type_name">&nbsp; {{ trans('app.forms.type_name') }}</label><br/>
                                                <label><input type="checkbox" name="address">&nbsp; {{ trans('app.forms.address') }}</label><br/>
                                            </div>
                                            <div class="col-md-3">
                                                <label><input type="checkbox" name="email">&nbsp; {{ trans('app.forms.email') }}</label><br/> 
                                                <label><input type="checkbox" name="phone_number">&nbsp; {{ trans('app.forms.phone_number') }}</label><br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br/>
                                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <hr/>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover nowrap" id="management_list" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:5%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.cob') }}</th>
                                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.file_name') }}</th>
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.type') }}</th>
                                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.type_name') }}</th>
                                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.address') }}</th>
                                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.email') }}</th>
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.phone_number') }}</th>
                                    </tr>
                                </thead>                                
                                <tbody>
                                    @if ($result)
                                    @foreach ($result as $res)
                                    <tr>
                                        <td>{{ $res[0] }}</td>
                                        <td>{{ $res[1] }}</td>
                                        <td>{{ $res[2] }}</td>
                                        <td>{{ $res[3] }}</td>
                                        <td>{{ $res[4] }}</td>
                                        <td>{{ $res[5] }}</td>
                                        <td>{{ $res[6] }}</td>
                                        <td>{{ $res[7] }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7">No data availabe</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End  -->
</div>

@stop
