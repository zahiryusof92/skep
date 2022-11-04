<div class="row padding-vertical-10">
    <div class="col-lg-12">
        <h5>
            {{ trans('app.epks_statement.sell') }}
        </h5>

        <label class="form-control-label">
            <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
        </label>

        <table class="table table-sm borderless" id="dynamic_form_sell" style="width: 100%;">
            <thead>
                <tr>
                    <th width="45%" style="text-align: center;" class="align-middle">
                        <span style="color: red;">*</span>
                        {{ trans("app.epks_statement.date") }}
                    </th>
                    <th width="45%" style="text-align: center;" class="align-middle">
                        <span style="color: red;">*</span>
                        {{ trans("app.epks_statement.sell_amount") }} (RM)
                    </th>
                    <th width="10%" style="text-align: center;" class="align-middle">
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>

                @if (!empty($sells))
                @foreach ($sells as $sell)
                <tr id="sell_row_{{ ++$count }}">
                    <td>
                        <label class="input-group">
                            <input type="text" id="sell_date_{{ $count }}" name="sell_date[]"
                                class="form-control form-control-sm date_picker"
                                value="{{ (!empty($sell->date) ? $sell->date : '') }}" required />
                            <span class="input-group-addon">
                                <i class="icmn-calendar"></i>
                            </span>
                        </label>
                    </td>
                    <td>
                        <input type="currency" id="sell_amount_{{ $count }}" name="sell_amount[]" oninput="totalSell()"
                            class="form-control form-control-sm"
                            value="{{ (!empty($sell->amount) ? number_format($sell->amount, 2) : '') }}" required />
                    </td>
                    <td class="align-middle">
                        @if ($count > 1)
                        <a href="javascript:void(0);" class="btn btn-danger btn-xs"
                            onclick="deleteSellRow('{{ $count }}')">
                            {{ trans("app.forms.remove") }}
                        </a>
                        @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr id="sell_row_{{ $count }}">
                    <td>
                        <label class="input-group">
                            <input type="text" id="sell_date_{{ $count }}" name="sell_date[]"
                                class="form-control form-control-sm date_picker" required />
                            <span class="input-group-addon">
                                <i class="icmn-calendar"></i>
                            </span>
                        </label>
                    </td>
                    <td>
                        <input type="currency" id="sell_amount_{{ $count }}" name="sell_amount[]" oninput="totalSell()"
                            class="form-control form-control-sm" required />
                    </td>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                @endif

                <tr>
                    <td colspan="2" class="align-middle">
                        &nbsp;
                    </td>
                    <td class="align-middle">
                        <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="addSellRow()">
                            {{ trans("app.forms.add_more") }}
                        </a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: center;" class="align-middle">
                        {{ trans("app.epks_statement.total_all") }}
                    </th>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">
                        <input type="currency" id="total_sell" name="total_sell"
                            class="form-control form-control-sm text-center" readonly />
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    function addSellRow() {        
        var rowSellNo = $("#dynamic_form_sell tr").length - 2;
        var field = '<tr id="sell_row_' + rowSellNo + '"><td><label class="input-group"><input type="text" id="sell_date_' + rowSellNo + '" name="sell_date[]" class="form-control form-control-sm date_picker" required /><span class="input-group-addon"><i class="icmn-calendar"></i></span></label></td><td><input type="currency" id="sell_amount_' + rowSellNo + '" name="sell_amount[]" oninput="totalSell()" class="form-control form-control-sm" required /></td><td class="align-middle"><a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteSellRow(' + rowSellNo + ')">{{ trans("app.forms.remove") }}</a></td></tr>';

        $("#dynamic_form_sell tbody>tr:last").prev().after(field);

        datePicker();
        convertCurrency();
    }

    function deleteSellRow(rowSellNo) {
        $('#sell_row_' + rowSellNo).remove();
        
        totalSell();
    }

    function totalSell() {
        var sum_sell_total = 0;
        var sell_total = document.getElementsByName("sell_amount[]");
        
        for (var i = 0; i < sell_total.length; i++) {
            sum_sell_total += Number(sell_total[i].value);
        }

        $('#total_sell').val(parseFloat(sum_sell_total).toFixed(2));
        $('#total_sell_profit').val(parseFloat(sum_sell_total).toFixed(2));
        $('#sell_cost').val(parseFloat(sum_sell_total).toFixed(2));

        calcProfit();
    }
</script>