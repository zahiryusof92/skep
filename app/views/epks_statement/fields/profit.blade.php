<div class="row padding-vertical-10">
    <div class="col-lg-12">

        <div class="text-center">
            <strong>
                {{ Str::upper('Penyata Untung Rugi Pusat Kitar Strata') }}
                <br />
                {{ Str::upper($model->strata->strataName()) }}
            </strong>
            <br />
            {{ Str::upper('BAGI BULAN') }} {{ $model->monthName() }}
            {{ Str::upper('TAHUN') }} {{ $model->year }}
        </div>

        <hr />

        <table class="table table-sm table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th width="60%" style="text-align: center;" class="align-middle">
                        &nbsp;
                    </th>
                    <th width="20%" style="text-align: center;" class="align-middle">
                        RM
                    </th>
                    <th width="20%" style="text-align: center;" class="align-middle">
                        RM
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="3" class="align-middle">
                        <u>
                            {{ Str::upper(trans('app.epks_statement.income')) }}
                        </u>
                    </th>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.sell") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="total_sell_profit" name="ledger[total_sell]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['total_sell'] : NULL) }}" readonly />
                    </td>
                    <td class="align-middle">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.others_income") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="others_income" name="ledger[others_income]" oninput="calcProfit()"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['others_income'] : NULL) }}" />
                    </td>
                    <td class="align-middle">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">
                        {{ trans("app.epks_statement.total") }}
                    </th>
                    <td style="text-align: center;">
                        <input type="currency" id="total_income" name="ledger[total_income]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['total_income'] : NULL) }}" readonly />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="align-middle">
                        <u>
                            {{ Str::upper(trans('app.epks_statement.product_cost')) }}
                        </u>
                    </th>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.sell_cost") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="sell_cost" name="ledger[sell_cost]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['sell_cost'] : NULL) }}" readonly />
                    </td>
                    <td class="align-middle">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">
                        {{ trans("app.epks_statement.total") }}
                    </th>
                    <td style="text-align: center;">
                        <input type="currency" id="total_product_cost" name="ledger[total_product_cost]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['total_product_cost'] : NULL) }}" readonly />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">
                        {{ trans("app.epks_statement.gross_profit") }}
                    </th>
                    <td style="text-align: center;">
                        <input type="currency" id="gross_profit" name="ledger[gross_profit]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['gross_profit'] : NULL) }}" readonly />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="align-middle">
                        <u>
                            {{ Str::upper(trans('app.epks_statement.expenses')) }}
                        </u>
                    </th>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.buy") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="total_buy_profit" name="ledger[total_buy]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['total_buy'] : NULL) }}" readonly />
                    </td>
                    <td class="align-middle">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.salary") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="salary" name="ledger[salary]" oninput="calcProfit()"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['salary'] : NULL) }}" />
                    </td>
                    <td class="align-middle">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.general") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="general" name="ledger[general]" oninput="calcProfit()"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['general'] : NULL) }}" />
                    </td>
                    <td class="align-middle">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">
                        {{ trans("app.epks_statement.total") }}
                    </th>
                    <td style="text-align: center;">
                        <input type="currency" id="total_expenses" name="ledger[total_expenses]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['total_expenses'] : NULL) }}" readonly />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">
                        {{ trans("app.epks_statement.profit") }}
                    </th>
                    <td style="text-align: center;">
                        <input type="currency" id="nett_profit" name="ledger[nett_profit]"
                            class="form-control form-control-sm"
                            value="{{ (!empty($ledgers) ? $ledgers['nett_profit'] : NULL) }}" readonly />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function calcProfit() {
        var total_income = 0;
        var total_expenses = 0;
        var gross_profit = 0;
        var nett_profit = 0;

        var total_sell_profit = Number(document.getElementById("total_sell_profit").value);
        var total_buy_profit = Number(document.getElementById("total_buy_profit").value);
        var others_income = Number(document.getElementById("others_income").value);
        var total_sale = Number(document.getElementById("sell_cost").value);
        var salary = Number(document.getElementById("salary").value);
        var general = Number(document.getElementById("general").value);      

        total_income = total_sell_profit + others_income;
        total_expenses = total_buy_profit + salary + general;
        gross_profit = total_income - total_sale;
        nett_profit = total_sale - total_expenses;

        $('#total_income').val(parseFloat(total_income).toFixed(2));
        $('#total_expenses').val(parseFloat(total_expenses).toFixed(2));
        $('#total_product_cost').val(parseFloat(total_sale).toFixed(2));
        $('#gross_profit').val(parseFloat(gross_profit).toFixed(2));
        $('#nett_profit').val(parseFloat(nett_profit).toFixed(2));
    }
</script>