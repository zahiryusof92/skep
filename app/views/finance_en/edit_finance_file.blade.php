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
    {{-- <section class="panel panel-with-borders"> --}}
    <section class="panel panel-style">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    <h3>{{ $title }}</h3>
                </div>
                <div class="col-md-2">
                    <a href="{{ url('print/financeFile', \Helper\Helper::encode($financefiledata->id)) }}" target="_blank" class="btn btn-sm btn-own margin-inline pull-right">Print</a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <section class="panel">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>{{ trans("app.forms.finance_management") }}
                                    <div></div></td>
                                    <td>{{ $financefiledata->file->file_no }}</td>
                                    <td>{{ trans("app.forms.finance_management_id") }}</td>
                                    <td>{{ $financefiledata->id }}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans("app.forms.year") }}</td>
                                    <td>{{ $financefiledata->year }}</td>
                                    <td>{{ trans("app.forms.month") }}</td>
                                    <td>{{ $financefiledata->monthName() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans("app.forms.strata") }}</td>
                                    <td colspan="3">{{ $financefiledata->file->strata->strataName() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </section>

    
    <section class="panel panel-style">
        <div class="panel-body">

            <div id="updateFinanceFile">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-pills nav-justified" id="financeTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active custom-tab" id="check-tab" data-toggle="tab" href="#check" role="tab" aria-controls="check" aria-selected="true">{{ trans("app.forms.check") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false">{{ trans("app.forms.summary") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="mfreport-tab" data-toggle="tab" href="#mfreport" role="tab" aria-controls="mfreport" aria-selected="false">{{ trans("app.forms.mf_report") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="sfreport-tab" data-toggle="tab" href="#sfreport" role="tab" aria-controls="sfreport" aria-selected="false">{{ trans("app.forms.sf_report") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="income-tab" data-toggle="tab" href="#income" role="tab" aria-controls="income" aria-selected="false">{{ trans("app.forms.income") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="utility-tab" data-toggle="tab" href="#utility" role="tab" aria-controls="utility" aria-selected="false">{{ trans("app.forms.utility") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="contractexp-tab" data-toggle="tab" href="#contractexp" role="tab" aria-controls="contractexp" aria-selected="false">{{ trans("app.forms.contract_expire") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="repair-tab" data-toggle="tab" href="#repair" role="tab" aria-controls="repair" aria-selected="false">{{ trans("app.forms.repair") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="vandalisme-tab" data-toggle="tab" href="#vandalisme" role="tab" aria-controls="vandalisme" aria-selected="false">{{ trans("app.forms.vandalism") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="staff-tab" data-toggle="tab" href="#staff" role="tab" aria-controls="staff" aria-selected="false">{{ trans("app.forms.staff") }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-tab" id="admin-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">{{ trans("app.forms.admin") }}</a>
                            </li>
                        </ul>
                        
                        <section class="panel panel-pad">
                            <div class="tab-content padding-vertical-10" id="financeTabContent">
                                <div class="tab-pane fade active show in" id="check" role="tabpanel" aria-labelledby="check-tab">
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
                        </section>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    var error = 0;

    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            window.location.hash = $(e.target).attr('href');
            setTabTitle();
        });

        if (window.location.hash) {
            $('#financeTab a[href="' + window.location.hash + '"]').tab('show');
            setTabTitle();
        }
    });

    function setTabTitle() {
        var title = '';
        if (window.location.hash == '#check') {
            title = 'Check';
        } else if (window.location.hash == '#summary') {
            title = 'Summary';
        } else if (window.location.hash == '#mfreport') {
            title = 'MF Report';
        } else if (window.location.hash == '#sfreport') {
            title = 'SF Report';
        } else if (window.location.hash == '#income') {
            title = 'Income';
        } else if (window.location.hash == '#utility') {
            title = 'Utility';
        } else if (window.location.hash == '#contractexp') {
            title = 'Contract';
        } else if (window.location.hash == '#repair') {
            title = 'Repair';
        } else if (window.location.hash == '#vandalisme') {
            title = 'Vandalisme';
        } else if (window.location.hash == '#staff') {
            title = 'Staff';
        } else if (window.location.hash == '#admin') {
            title = 'Admin';
        } else {
            title = 'Check';
        }
        $('#tab_title').text(title);

    }
    
    function submitForm() {
        if (window.location.hash == '#check') {
            submitCheck();
        } else if (window.location.hash == '#summary') {
            submitSummary();
        } else if (window.location.hash == '#mfreport') {
            submitMFReport();
        } else if (window.location.hash == '#sfreport') {
            submitSFReport();
        } else if (window.location.hash == '#income') {
            submitIncome();
        } else if (window.location.hash == '#utility') {
            submitUtility();
        } else if (window.location.hash == '#contractexp') {
            submitContract();
        } else if (window.location.hash == '#repair') {
            submitRepair();
        } else if (window.location.hash == '#vandalisme') {
            submitVandalisme();
        } else if (window.location.hash == '#staff') {
            submitStaff();
        } else if (window.location.hash == '#admin') {
            submitAdmin();
        } else {
            submitCheck();
        }
            submitSummary();
    }

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
                                // save data
                                submitForm();

                                if (!error) {
                                    current.tab('show');
                                }
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
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'DD/MM/YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('/');
            $("#mirror_date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });

    // Jquery Dependency
    $("input[type='currency']").on({
        keyup: function () {
            formatCurrency($(this));
        },
        blur: function () {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "");
    }

    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    // Jquery Dependency
    $("input[type='digit']").on({
        keyup: function () {
            formatDigit($(this));
        },
        blur: function () {
            formatDigit($(this));
        }
    });

    function formatDigit(input) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

@stop
