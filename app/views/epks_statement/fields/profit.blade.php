<div class="row padding-vertical-10">
    <div class="col-lg-12">
        <h5>
            {{ trans('app.epks_statement.profit') }}
        </h5>

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
                        {{ trans("app.epks_statement.income") }}
                    </th>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.sell") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="total_sell_profit" name="total_sell_profit"
                            class="form-control form-control-sm" value="" readonly />
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
                        <input type="currency" id="others_income" name="others_income" oninput="calcProfit()"
                            class="form-control form-control-sm" />
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
                        <input type="currency" id="total_income" name="total_income"
                            class="form-control form-control-sm" readonly />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="align-middle">
                        {{ trans("app.epks_statement.product_cost") }}
                    </th>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.sell_cost") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="sell_cost" name="sell_cost" class="form-control form-control-sm"
                            readonly />
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
                        <input type="currency" id="total_product_cost" name="total_product_cost"
                            class="form-control form-control-sm" readonly />
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
                        <input type="currency" id="gross_profit" name="gross_profit"
                            class="form-control form-control-sm" readonly />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="align-middle">
                        {{ trans("app.epks_statement.expenses") }}
                    </th>
                </tr>
                <tr>
                    <td class="align-middle">
                        {{ trans("app.epks_statement.buy") }}
                    </td>
                    <td style="text-align: center;">
                        <input type="currency" id="total_buy_profit" name="total_buy_profit"
                            class="form-control form-control-sm" readonly />
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
                        <input type="currency" id="salary" name="salary" oninput="calcProfit()"
                            class="form-control form-control-sm" />
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
                        <input type="currency" id="general" name="general" oninput="calcProfit()"
                            class="form-control form-control-sm" />
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
                        <input type="currency" id="total_expenses" name="total_expenses"
                            class="form-control form-control-sm" readonly />
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
                        <input type="currency" id="nett_profit" name="nett_profit" class="form-control form-control-sm"
                            readonly />
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