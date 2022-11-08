@extends('layout.english_layout.default')

@section('content')
<style>
    .align-middle {
        vertical-align: middle !important;
    }
</style>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    <h3>{{ $title }}</h3>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('epksStatement.print', \Helper\Helper::encode($model->id)) }}" target="_blank"
                        class="btn btn-sm btn-own margin-inline pull-right">
                        {{ trans('Print') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">

            @include('alert.bootbox')

            <section class="panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered  margin-bottom-0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <th colspan="6" style="width: 100%; text-align: center;">
                                            LAPORAN HASIL JUALAN KEPADA VENDOR BARANGAN KITAR SEMULA PUSAT KITAR STRATA
                                            <br />
                                            BAGI PANGSAPURI {{ Str::upper($model->strata->strataName()) }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">{{ trans("app.forms.file_no") }}</th>
                                        <td style="width: 50%;">{{ $model->file->file_no }}</td>
                                        <th style="width: 10%;">{{ trans("app.forms.month") }}</th>
                                        <td style="width: 10%;">{{ $model->monthName() }}</td>
                                        <th style="width: 10%;">{{ trans("app.forms.year") }}</th>
                                        <td style="width: 10%;">{{ $model->year }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </section>

    <section class="panel panel-style">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-pills nav-justified" id="statementTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active custom-tab" id="buy-tab" data-toggle="tab" href="#buy" role="tab"
                                aria-controls="buy" aria-selected="true">
                                {{ trans("app.epks_statement.buy") }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-tab" id="sell-tab" data-toggle="tab" href="#sell" role="tab"
                                aria-controls="sell" aria-selected="false">
                                {{ trans("app.epks_statement.sell") }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-tab" id="profit-tab" data-toggle="tab" href="#profit" role="tab"
                                aria-controls="profit" aria-selected="false">
                                {{ trans("app.epks_statement.profit") }}
                            </a>
                        </li>
                    </ul>

                    <section class="panel panel-pad">
                        <form id="epks_statement_form"
                            action="{{ route('epksStatement.update', \Helper\Helper::encode($module, $model->id)) }}"
                            method="POST" autocomplete="off">
                            <input type="hidden" name="_method" value="PUT">

                            <div class="tab-content padding-vertical-10" id="statementTabContent">
                                <div class="tab-pane fade active show in" id="buy" role="tabpanel"
                                    aria-labelledby="buy-tab">
                                    @include('epks_statement.fields.buy')
                                </div>

                                <div class="tab-pane fade" id="sell" role="tabpanel" aria-labelledby="sell-tab">
                                    @include('epks_statement.fields.sell')
                                </div>

                                <div class="tab-pane fade" id="profit" role="tabpanel" aria-labelledby="profit-tab">
                                    @include('epks_statement.fields.profit')
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-own" id="submit_button">
                                        {{ trans('app.forms.save') }}
                                    </button>
                                    <button type="button" class="btn btn-default" id="cancel_button"
                                        onclick="window.location ='{{ route('epksStatement.index') }}'">
                                        {{ trans('app.forms.cancel') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </section>

                </div>
            </div>
        </div>
    </section>

</div>

<script>
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            window.location.hash = $(e.target).attr('href');
        });

        if (window.location.hash) {
            $('#statementTab a[href="' + window.location.hash + '"]').tab('show');
        }

        datePicker();
        convertCurrency();
        totalBuy();
        totalSell();
    });

    function datePicker() {
        $(".date_picker").datetimepicker({
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
            format: 'YYYY-MM-DD',
            useCurrent: false
        });
    }

    function convertCurrency() {
        $("input[type='currency']").on({
            keyup: function () {
                formatCurrency($(this));
            },
            blur: function () {
                formatCurrency($(this), "blur");
            }
        });
    }

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
</script>
@endsection