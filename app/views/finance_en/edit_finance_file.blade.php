@extends('layout.english_layout.default')

@section('content')

<?php
$access_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 38) {
        $access_permission = $permission->access_permission;
        $update_permission = $permission->update_permission;
    }
}
?>

<style>
    .padding-form {
        padding-left: 20px !important;
        padding-top: 15px !important;
    }
    .padding-table {
        padding-top: 15px !important;
    }
</style>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>{{ trans("app.forms.finance_management") }}</td>
                                <td>{{ $financefiledata->file->file_no }}</td>
                                <td>{{ trans("app.forms.finance_management_id") }}</td>
                                <td>{{ $financefiledata->id }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans("app.forms.year") }}</td>
                                <td>{{ $financefiledata->year }}</td>
                                <td>{{ trans("app.forms.month") }}</td>
                                <td>{{ $financefiledata->month }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans("app.forms.strata") }}</td>
                                <td colspan="3">{{ $financefiledata->file->strata->strataName() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr/>

            <div id="updateFinanceFile">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                    <br/>
                                    <span style="font-weight: bold; color: red; font-style: italic; display: none;" id="check_mandatory_fields">* {{ trans('app.forms.check_mandatory_fields') }}</span>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-pills nav-justified" id="financeTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ trans("app.forms.check") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false">{{ trans("app.forms.summary") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="mfreport-tab" data-toggle="tab" href="#mfreport" role="tab" aria-controls="mfreport" aria-selected="false">{{ trans("app.forms.mf_report") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sfreport-tab" data-toggle="tab" href="#sfreport" role="tab" aria-controls="sfreport" aria-selected="false">{{ trans("app.forms.sf_report") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="income-tab" data-toggle="tab" href="#income" role="tab" aria-controls="income" aria-selected="false">{{ trans("app.forms.income") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="utility-tab" data-toggle="tab" href="#utility" role="tab" aria-controls="utility" aria-selected="false">{{ trans("app.forms.utility") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contractexp-tab" data-toggle="tab" href="#contractexp" role="tab" aria-controls="contractexp" aria-selected="false">{{ trans("app.forms.contract_expire") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="repair-tab" data-toggle="tab" href="#repair" role="tab" aria-controls="repair" aria-selected="false">{{ trans("app.forms.repair") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vandalisme-tab" data-toggle="tab" href="#vandalisme" role="tab" aria-controls="vandalisme" aria-selected="false">{{ trans("app.forms.vandalism") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="staff-tab" data-toggle="tab" href="#staff" role="tab" aria-controls="staff" aria-selected="false">{{ trans("app.forms.staff") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="admin-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">{{ trans("app.forms.admin") }}</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-vertical-20" id="financeTabContent">
                            <div class="tab-pane fade active show in" id="home" role="tabpanel" aria-labelledby="home-tab">
                                @include('finance_en.edit_finance_file.form_check')
                            </div>
                            <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                                @include('finance_en.edit_finance_file.form_summary')
                            </div>
                            <div class="tab-pane fade" id="mfreport" role="tabpanel" aria-labelledby="mfreport-tab">
                                @include('finance_en.edit_finance_file.form_mfreport')
                            </div>
                            <div class="tab-pane fade" id="income" role="tabpanel" aria-labelledby="income-tab">
                                @include('finance_en.edit_finance_file.form_income')
                            </div>
                            <div class="tab-pane fade" id="sfreport" role="tabpanel" aria-labelledby="sfreport-tab">
                                @include('finance_en.edit_finance_file.form_sfreport')
                            </div>
                            <div class="tab-pane fade" id="utility" role="tabpanel" aria-labelledby="utility-tab">
                                @include('finance_en.edit_finance_file.form_utility')
                            </div>
                            <div class="tab-pane fade" id="contractexp" role="tabpanel" aria-labelledby="contractexp-tab">
                                @include('finance_en.edit_finance_file.form_contractexp')
                            </div>
                            <div class="tab-pane fade" id="repair" role="tabpanel" aria-labelledby="repair-tab">
                                @include('finance_en.edit_finance_file.form_repair')
                            </div>
                            <div class="tab-pane fade" id="vandalisme" role="tabpanel" aria-labelledby="vandalisme-tab">
                                @include('finance_en.edit_finance_file.form_vandalisme')
                            </div>
                            <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">
                                @include('finance_en.edit_finance_file.form_staff')
                            </div>
                            <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                                @include('finance_en.edit_finance_file.form_admin')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            window.location.hash = $(e.target).attr('href');
        });
        
        if (window.location.hash) {
            $('#financeTab a[href="' + window.location.hash + '"]').tab('show');
        }
    });

    var changes = false;
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });

    $(function () {
        $('#financeTab a[data-toggle="tab"]').click(function (e) {
            e.preventDefault();

            var current = $(this);

            if (!$(this).hasClass('active')) {
                if (changes) {
                    bootbox.confirm({
                        message: "{{ trans('app.confirmation.want_to_leave') }}",
                        buttons: {
                            confirm: {
                                label: 'Yes',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'No',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                current.tab('show');
                            }
                        }
                    });
                } else {
                    return true;
                }
            }

            return false;
        });
    });

    $(function () {
        $('#date').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD/MM/YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('/');
            $("#mirror_date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });
</script>

@stop
