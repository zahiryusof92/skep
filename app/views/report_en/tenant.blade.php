@extends('layout.english_layout.default')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>
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

                <div class="row" style="margin-top: 30px;">
                    <div class="col-lg-12"> 
                        <form target="_blank" action="{{ url('/print/tenant') }}" method="POST">
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
                                                <label><input type="checkbox" name="scheme_name">&nbsp; {{ trans('app.forms.scheme_name') }}</label><br/>
                                                <label><input type="checkbox" name="unit_no">&nbsp; {{ trans('app.forms.unit_number') }}</label><br/>
                                            </div>
                                            <div class="col-md-3">
                                                <label><input type="checkbox" name="unit_share">&nbsp; {{ trans('app.forms.unit_share') }}</label><br/> 
                                                <label><input type="checkbox" name="tenant">&nbsp; {{ trans('app.forms.tenant') }}</label><br/>
                                            </div>
                                            <div class="col-md-3">
                                                <label><input type="checkbox" name="phone_number">&nbsp; {{ trans('app.forms.phone_number') }}</label><br/>
                                                <label><input type="checkbox" name="email">&nbsp; {{ trans('app.forms.email') }}</label><br/>
                                            </div>
                                            <div class="col-md-3">
                                                <label><input type="checkbox" name="race">&nbsp; {{ trans('app.forms.race') }}</label><br/>
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
                            <table class="table table-hover nowrap" id="purchaser_list" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:5%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.cob') }}</th>
                                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.file_no') }}</th>
                                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.scheme_name') }}</th>
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.unit_number') }}</th>
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.unit_share') }}</th>
                                        <th style="width:20%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.tenant') }}</th>
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.phone_number') }}</th>
                                        <th style="width:15%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.email') }}</th>
                                        <th style="width:10%; text-align: center !important; vertical-align:middle !important;">{{ trans('app.forms.race') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($tenant)
                                    @foreach ($tenant as $tenants)
                                    <tr>
                                        <td>{{ $tenants->file_id ? $tenants->file->company->short_name : '-' }}</td>
                                        <td>{{ $tenants->file_id ? $tenants->file->file_no : '-' }}</td>
                                        <td>{{ $tenants->file_id ? $tenants->file->strata->name : '-' }}</td>
                                        <td>{{ $tenants->unit_no }}</td>
                                        <td>{{ $tenants->unit_share }}</td>
                                        <td>{{ $tenants->tenant_name }}</td>
                                        <td>{{ $tenants->phone_no }}</td>
                                        <td>{{ $tenants->email }}</td>
                                        <td>{{ $tenants->race_id ? $tenants->race->name_en : '-' }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="9">No data availabe</td>
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

<!-- Page Scripts -->
<script>
    $("[data-toggle=tooltip]").tooltip();
</script>
<!-- End Page Scripts-->

@stop
