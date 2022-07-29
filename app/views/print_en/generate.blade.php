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
            <table border="1" id="generate-table-list" width="100%" style="font-size: 11px;">
                <thead>
                    @if(in_array('file_no', $selected))
                        <th style="width:10%;" class="text-center">
                            {{ trans('app.forms.file_no') }}
                        </th>
                    @endif
                    @if(in_array('city', $selected))
                        <th style="width:15%;" class="text-center">{{ trans('app.forms.city') }}</th>
                    @endif
                    @if(in_array('strata', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.strata') }}</th>
                    @endif
                    @if(in_array('category', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.category') }}</th>
                    @endif
                    @if(in_array('file_draft_latest_date', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.file_draft_latest_date') }}</th>
                    @endif
                    @if(in_array('latest_agm_date', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.latest_agm_date') }}</th>
                    @endif
                    @if(in_array('latest_insurance_date', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.latest_insurance_date') }}</th>
                    @endif
                    @if(in_array('jmb_date_formed', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.jmb_date_formed') }}</th>
                    @endif
                    @if(in_array('mc_date_formed', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.mc_date_formed') }}</th>
                    @endif
                    @if(in_array('total_floor', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.total_floor') }}</th>
                    @endif
                    @if(in_array('residential_block', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.residential_block') }}</th>
                    @endif
                    @if(in_array('commercial_block', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.commercial_block') }}</th>
                    @endif
                    @if(in_array('block', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.block') }}</th>
                    @endif
                    @if(in_array('management', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.management') }}</th>
                    @endif
                    @if(in_array('is_active', $selected))
                        <th style="width:10%;" class="text-center">{{ trans('app.forms.status') }}</th>
                    @endif
                </thead>
                <tbody>
                    @foreach($models as $model)
                        <tr>
                            @if(in_array('file_no', $selected))
                                <td>{{ $model->file_no }}</td>
                            @endif
                            @if(in_array('city', $selected))
                                <td class="text-center">{{ $model->city_name }}</td>
                            @endif
                            @if(in_array('strata', $selected))
                                <td>{{ $model->strata->name }}</td>
                            @endif
                            @if(in_array('category', $selected))
                                <td class="text-center">{{ $model->strata->category? $model->strata->categories->description : "-" }}</td>
                            @endif
                            @if(in_array('file_draft_latest_date', $selected))
                                <td class="text-center">{{ !empty($model->draft)? $model->draft->created_at->toDateTimeString() : '-' }}</td>
                            @endif
                            @if(in_array('latest_agm_date', $selected))
                                <td class="text-center">{{ $model->latestMeetingDocument? $model->latestMeetingDocument->created_at->toDateTimeString() : "-" }}</td>
                            @endif
                            @if(in_array('latest_insurance_date', $selected))
                                <td class="text-center">{{ $model->insurance->count()? $model->insurance()->latest()->first()->created_at->toDateTimeString() : "-" }}</td>
                            @endif
                            @if(in_array('jmb_date_formed', $selected))
                                <td class="text-center">{{ $model->management->is_jmb? $model->managementJMB->date_formed : '-' }}</td>
                            @endif
                            @if(in_array('mc_date_formed', $selected))
                                <td class="text-center">{{ $model->management->is_mc? $model->managementMC->date_formed : '-' }}</td>
                            @endif
                            @if(in_array('total_floor', $selected))
                                <td class="text-center">{{ $model->strata->total_floor }}</td>
                            @endif
                            @if(in_array('residential_block', $selected))
                                <?php 
                                    $residential = Residential::where('file_id', $model->id)->sum('unit_no');
                                    $residential_extra = ResidentialExtra::where('file_id', $model->id)->sum('unit_no');
                                ?>
                                <td class="text-center">{{ $residential + $residential_extra }}</td>
                            @endif
                            @if(in_array('commercial_block', $selected))
                                <?php 
                                    $commercial = Commercial::where('file_id', $model->id)->sum('unit_no');
                                    $commercial_extra = CommercialExtra::where('file_id', $model->id)->sum('unit_no');
                                ?>
                                <td class="text-center">{{ $commercial + $commercial_extra }}</td>
                            @endif
                            @if(in_array('block', $selected))
                                <td class="text-center">{{ $model->strata->block_no }}</td>
                            @endif
                            @if(in_array('management', $selected))
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
                                        if($model->liquidator) {
                                            $content = trans('app.forms.liquidator');
                                        } 
                                        if($model->is_developer) {
                                            $content = trans('app.forms.developer');
                                        } 
                                        if($model->under_10_units) {
                                            $content = trans('app.forms.under_10_units');
                                        } 
                                        if($model->bankruptcy) {
                                            $content = trans('app.forms.bankruptcy');
                                        }
                                    ?>
                                    {{ $content }}
                                </td>
                            @endif
                            @if(in_array('is_active', $selected))
                                <td class="text-center">{{ $model->is_active ? trans('app.forms.yes') : trans('app.forms.no') }}</td>
                            @endif
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
<!-- End  -->

@stop
