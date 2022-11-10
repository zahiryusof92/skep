<div class="row padding-vertical-10">
    <div class="col-lg-12">

        <div>
            <label class="form-control-label">
                <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
            </label>
        </div>

        <div class="text-center">
            <strong>
                {{ Str::upper('LAPORAN BELIAN BARANGAN KITAR SEMULA') }}
                <br />
                {{ Str::upper('PUSAT KITAR STRATA BAGI PANGSAPURI') }}
                <br />
                {{ Str::upper($model->strata->strataName()) }}
            </strong>
            <br />
            {{ Str::upper('BAGI BULAN') }} {{ $model->monthName() }}
            {{ Str::upper('TAHUN') }} {{ $model->year }}
        </div>

        <hr />

        <table class="table table-sm borderless" id="dynamic_form_buy" style="width: 100%;">
            <thead>
                <tr>
                    <th width="45%" style="text-align: center;" class="align-middle">
                        <span style="color: red;">*</span>
                        {{ Str::upper(trans("app.epks_statement.date")) }}
                    </th>
                    <th width="45%" style="text-align: center;" class="align-middle">
                        <span style="color: red;">*</span>
                        {{ Str::upper(trans("app.epks_statement.buy_amount")) }} (RM)
                    </th>
                    <th width="10%" style="text-align: center;" class="align-middle">
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>

                @if (!empty($buys))
                @foreach ($buys as $date => $amount)
                <tr id="buy_row_{{ ++$count }}">
                    <td>
                        <label class="input-group">
                            <input type="text" id="buy_date_{{ $count }}" name="buy_date[]"
                                class="form-control form-control-sm date_picker"
                                value="{{ $date }}" required />
                            <span class="input-group-addon">
                                <i class="icmn-calendar"></i>
                            </span>
                        </label>
                    </td>
                    <td>
                        <input type="currency" id="buy_amount_{{ $count }}" name="buy_amount[]" oninput="totalBuy()"
                            class="form-control form-control-sm"
                            value="{{ $amount }}" required />
                    </td>
                    <td class="align-middle">
                        @if ($count > 1)
                        <a href="javascript:void(0);" class="btn btn-danger btn-xs"
                            onclick="deleteBuyRow('{{ $count }}')">
                            {{ trans("app.forms.remove") }}
                        </a>
                        @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr id="buy_row_{{ $count }}">
                    <td>
                        <label class="input-group">
                            <input type="text" id="buy_date_{{ $count }}" name="buy_date[]"
                                class="form-control form-control-sm date_picker" required />
                            <span class="input-group-addon">
                                <i class="icmn-calendar"></i>
                            </span>
                        </label>
                    </td>
                    <td>
                        <input type="currency" id="buy_amount_{{ $count }}" name="buy_amount[]" oninput="totalBuy()"
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
                        <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="addBuyRow()">
                            {{ trans("app.forms.add_more") }}
                        </a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: center;" class="align-middle">
                        {{ Str::upper(trans("app.epks_statement.total_all")) }}
                    </th>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle">
                        <input type="currency" id="total_buy" name="total_buy"
                            class="form-control form-control-sm text-center"
                            value="{{ (!empty($ledgers) ? $ledgers['total_buy'] : NULL) }}"
                            readonly />
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    function addBuyRow() {        
        var rowBuyNo = $("#dynamic_form_buy tr").length - 2;
        var field = '<tr id="buy_row_' + rowBuyNo + '"><td><label class="input-group"><input type="text" id="buy_date_' + rowBuyNo + '" name="buy_date[]" class="form-control form-control-sm date_picker" required /><span class="input-group-addon"><i class="icmn-calendar"></i></span></label></td><td><input type="currency" id="buy_amount_' + rowBuyNo + '" name="buy_amount[]" oninput="totalBuy()" class="form-control form-control-sm" required /></td><td class="align-middle"><a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteBuyRow(' + rowBuyNo + ')">{{ trans("app.forms.remove") }}</a></td></tr>';

        $("#dynamic_form_buy tbody>tr:last").prev().after(field);

        datePicker();
        convertCurrency();
    }

    function deleteBuyRow(rowBuyNo) {
        $('#buy_row_' + rowBuyNo).remove();

        totalBuy();
    }

    function totalBuy() {
        var buy_amount = 0;
        var sum_buy_total = 0;
        var buy_total = document.getElementsByName("buy_amount[]");
        
        for (var i = 0; i < buy_total.length; i++) {
            buy_amount = parseFloat(buy_total[i].value.replace(/,/g, ''));
            if (buy_amount > 0) {
                sum_buy_total += Number(buy_amount);
            }
        }        

        $('#total_buy').val(parseFloat(sum_buy_total).toFixed(2));
        $('#total_buy_profit').val(parseFloat(sum_buy_total).toFixed(2));

        calcProfit();
    }
</script>