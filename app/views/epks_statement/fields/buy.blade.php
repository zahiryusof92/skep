<div class="row padding-vertical-10">
    <div class="col-lg-12">
        <h5>
            {{ trans('app.forms.buy') }}
        </h5>

        <table class="table table-sm borderless" id="dynamic_form_buy" style="width: 100%;">
            <thead>
                <tr>
                    <th width="45%" style="text-align: center;">
                        {{ trans("app.forms.date") }}
                    </th>
                    <th width="45%" style="text-align: center;">
                        {{ trans("app.forms.amount") }} (RM)
                    </th>
                    <th width="10%" style="text-align: center;">
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
            $count = 0;
            ?>

                <tr id="buy_row_{{ $count }}">
                    <td>
                        <label class="input-group">
                            <input type="text" id="buy_date_{{ $count }}" name="buy_date[]"
                                class="form-control form-control-sm date_picker" />
                            <span class="input-group-addon">
                                <i class="icmn-calendar"></i>
                            </span>
                        </label>
                    </td>
                    <td>
                        <input type="text" id="buy_amount_{{ $count }}" name="buy_amount[]"
                            class="form-control form-control-sm" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        &nbsp;
                    </td>
                    <td class="padding-table">
                        <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="addBuyRow()">
                            {{ trans("app.forms.add_more") }}
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        datePicker();
    });

    function addBuyRow() {
        changes = true;
        
        var rowBuyNo = $("#dynamic_form_buy tr").length - 2;
        var field = '<tr id="buy_row_' + rowBuyNo + '"><td><label class="input-group"><input type="text" id="buy_date_' + rowBuyNo + '" name="buy_date[]" class="form-control form-control-sm date_picker" /><span class="input-group-addon"><i class="icmn-calendar"></i></span></label></td><td><input type="text" id="buy_amount_' + rowBuyNo + '" name="buy_amount[]" class="form-control form-control-sm" /></td><td class="padding-table"><a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteBuyRow(' + rowBuyNo + ')">{{ trans("app.forms.remove") }}</a></td></tr>';

        $("#dynamic_form_buy tr:last").prev().after(field);
        datePicker();

        // calculateIncomeTotal();
    }

    function deleteBuyRow(rowBuyNo) {
        changes = true;

        $('#buy_row_' + rowBuyNo).remove();

        // calculateIncomeTotal();
    }

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
</script>