@extends('layout.english_layout.selected')

@section('content')

<?php
$company = Company::find(Auth::user()->company_id);
?>
<form action="{{ $route }}" method="POST">
    <input type="hidden" name="city" value="{{ $request_params['city'] }}">
    <input type="hidden" name="management" value="{{ $request_params['management'] }}">
    <input type="hidden" name="file_id" value="{{ $request_params['file_id'] }}">
    <input type="hidden" name="category" value="{{ $request_params['category'] }}">
    <input type="hidden" name="strata" value="{{ $request_params['strata'] }}">
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
                        <td class="text-center">
                            <h5 class="margin-bottom-10">
                                {{$company->name}}
                            </h5>
                            <h6 class="margin-bottom-0">
                                {{$title}}
                            </h6>
                        </td>
                        <td class="text-center">
                            <button type="submit" class="btn btn-own" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></button>
                        </td>
                    </tr>
                </table>

                <hr/>
                <table border="1" id="generate-table-list" style="font-size: 11px;display: block; overflow-x: auto; width: 100%;">
                    <thead>
                        <tr>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="file_no" checked><br/>
                                {{ trans('app.forms.file_no') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="city" checked><br/>
                                {{ trans('app.forms.city') }}
                            </th>
                            {{-- <th style="width:10%;">
                                <input type="checkbox" name="selected[]" value="house_scheme" checked><br/>
                                {{ trans('app.forms.scheme_name') }}
                            </th>
                            <th style="width:10%;">
                                <input type="checkbox" name="selected[]" value="developer" checked><br/>
                                {{ trans('app.forms.developer') }}
                            </th> --}}
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="strata" checked><br/>
                                {{ trans('app.forms.strata') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="category" checked><br/>
                                {{ trans('app.forms.category') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="file_draft_latest_date" checked><br/>
                                {{ trans('app.forms.file_draft_latest_date') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="latest_agm_date" checked><br/>
                                {{ trans('app.forms.latest_agm_date') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="latest_insurance_date" checked><br/>
                                {{ trans('app.forms.latest_insurance_date') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="jmb_date_formed" checked><br/>
                                {{ trans('app.forms.jmb_date_formed') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="mc_date_formed" checked><br/>
                                {{ trans('app.forms.mc_date_formed') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="total_floor" checked><br/>
                                {{ trans('app.forms.total_floor') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="residential_block" checked><br/>
                                {{ trans('app.forms.residential_block') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="commercial_block" checked><br/>
                                {{ trans('app.forms.commercial_block') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="block" checked><br/>
                                {{ trans('app.forms.block') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="management" checked><br/>
                                {{ trans('app.forms.management') }}
                            </th>
                            <th style="width:10%;" class="text-center">
                                <input type="checkbox" name="selected[]" value="is_active" checked><br/>
                                {{ trans('app.forms.is_active') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($models as $model)
                            <tr>
                                <td>{{ $model->file_no }}</td>
    
                                <td>{{ $model->city_name }}</td>
    
                                <td>{{ $model->strata->name }}</td>
    
                                <td class="text-center">{{ $model->strata->category? $model->strata->categories->description : "-" }}</td>
    
                                <td>{{ !empty($model->draft)? $model->draft->created_at->toDateTimeString() : '-' }}</td>
    
                                <td>{{ $model->latestMeetingDocument? $model->latestMeetingDocument->created_at->toDateTimeString() : "-" }}</td>
    
                                <td>{{ $model->insurance->count()? $model->insurance()->latest()->first()->created_at->toDateTimeString() : "-" }}</td>
    
                                <td>{{ $model->management->is_jmb? $model->managementJMB->date_formed : '-' }}</td>
    
                                <td>{{ $model->management->is_mc? $model->managementMC->date_formed : '-' }}</td>
    
                                <td>{{ $model->strata->total_floor }}</td>
    
                                <?php 
                                    $residential = Residential::where('file_id', $model->id)->sum('unit_no');
                                    $residential_extra = ResidentialExtra::where('file_id', $model->id)->sum('unit_no');
                                ?>
                                <td>{{ $residential + $residential_extra }}</td>
    
                                <?php 
                                    $commercial = Commercial::where('file_id', $model->id)->sum('unit_no');
                                    $commercial_extra = CommercialExtra::where('file_id', $model->id)->sum('unit_no');
                                ?>
                                <td>{{ $commercial + $commercial_extra }}</td>
    
                                <td>{{ $model->strata->block_no }}</td>
    
                                <td class="text-center">
                                    <?php
                                    $content = '';
                                        if($model->is_jmb && !$model->is_mc) {
                                            $content .= trans('JMB') .',';
                                        } 
                                        if($model->is_mc) {
                                            $content .= trans('MC') .',';
                                        } 
                                        if($model->is_agent && !$model->is_mc) {
                                            $content .= trans('Agent') .',';
                                        } 
                                        if($model->is_others && !$model->is_mc) {
                                            $content .= trans('Others') .',';
                                        } 
                                        if(!$model->is_jmb && !$model->is_mc && !$model->is_agent && !$model->is_agent && !$model->is_others && !$model->under_10_units && !$model->bankruptcy) {
                                            $content .= trans('Non-Set');
                                        }
                                    ?>
                                    {{ $content }}
                                </td>

                                <td>{{ $model->is_active ? trans('app.forms.yes') : trans('app.forms.no') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr/>
                <table width="100%">
                    <tr>
                        <td>
                            <p><b>{{ trans('app.forms.confidential') }}</b></p>
                        </td>
                        <td class="pull-right">
                            <p>{{ trans('app.forms.print_on', ['print' => date('d/m/Y h:i:s A', strtotime("now"))]) }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<!-- End  -->

@stop
