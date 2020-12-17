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

            <table class="table table-sm table-bordered" style="width: 100%">
                <tbody>
                    <tr>
                        <th style="width: 20%">{{ trans("app.forms.finance_management") }}</th>
                        <td style="width: 80%">{{ $financefiledata->file->file_no }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans("app.forms.strata") }}</th>
                        <td>{{ $financefiledata->file->strata->strataName() }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans("app.forms.year") }}</th>
                        <td>{{ $financefiledata->year }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans("app.forms.month") }}</th>
                        <td>{{ $financefiledata->monthName() }}</td>
                    </tr>                            
                </tbody>
            </table>

            <hr/>

            <div>

                @include('print_en.edit_finance_file.form_check')

                <hr/>

                @include('print_en.edit_finance_file.form_summary')

                <hr/>

                @include('print_en.edit_finance_file.form_mfreport')

                <hr/>

                @include('print_en.edit_finance_file.form_sfreport')

                <hr/>

                @include('print_en.edit_finance_file.form_income')               

                <hr/>

                @include('print_en.edit_finance_file.form_utility')

                <hr/>

                @include('print_en.edit_finance_file.form_contractexp')

                <hr/>

                @include('print_en.edit_finance_file.form_repair')

                <hr/>

                @include('print_en.edit_finance_file.form_vandalisme')

                <hr/>

                @include('print_en.edit_finance_file.form_staff')

                <hr/>

                @include('print_en.edit_finance_file.form_admin')

            </div>

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

@stop
