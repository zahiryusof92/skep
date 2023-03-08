<?php
$prefix = 'sum_';
?>

<div class="row">
    <div class="col-lg-12">
        {{-- <h6>{{ trans("app.forms.summary") }}</h6> --}}

        <form id="form_summary">

            <div class="row">
                <table class="table table-sm borderless" style="font-size: 12px;" style="width: 100%">
                    <tbody>
                        <?php
                        $no = 1;
                        $tbl_fields = Config::get('constant.others.tbl_fields_name');
                        ?>
                        @foreach (Config::get('constant.module.finance.tabs.summary.only') as $summaries)
                        <?php $name = $tbl_fields[$prefix . $summaries];?>
                        <tr>
                            <td width="5%" class="padding-table text-center"><input type="hidden" name="{{ $prefix }}summary_key[]" value="{{ $summaries }}">{{ $no }}</td>
                            <td width="80%" class="padding-table"><input type="hidden" name="{{ $prefix }}name[]" value="{{ $name }}">{{ $name }}</td>
                            <td width="15%"><input type="currency" oninput="calculateSummaryTotal()" class="form-control form-control-sm text-right" id="{{$prefix.$summaries}}" name="{{ $prefix }}amount[]" readonly=""></td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-table">JUMLAH PENDAPATAN</th>
                            <th><input type="text" class="form-control form-control-sm text-right" id="{{ $prefix }}jumlah_pendapatan" value="0" readonly=""></th>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-table">JUMLAH PERBELANJAAN</th>
                            <th><input type="text" class="form-control form-control-sm text-right" id="{{ $prefix }}jumlah_pembelanjaan" value="0" readonly=""></th>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <hr/>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-table">JUMLAH PENDAPATAN BERSIH</th>
                            <th><input type="text" class="form-control form-control-sm text-right" id="{{ $prefix }}jumlah_pendapatan_bersih" value="0" readonly=""></th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if ($update_permission == 1) { ?>
                <div class="form-actions">
                    <input type="hidden" name="finance_file_id" value="{{ \Helper\Helper::encode($financefiledata->id) }}"/>
                </div>
            <?php } ?>

        </form>

    </div>
</div>

<script>
    $(document).ready(function () {
        calculateSummaryTotal();
    });

    function calculateSummaryTotal() {
        // summary utility
        let others_a = Number($('#util_total_all').val()) - (Number($('#util_total_income_1').val()) + Number($('#util_total_income_2').val()));
        let others_b = Number($('#utilb_total_all').val()) - (Number($('#utilb_total_income_1').val()) + Number($('#utilb_total_income_2').val()));
        let utility_total = Number(others_a + others_b);
        $('#sum_utility').val(parseFloat(utility_total).toFixed(2));

        // summary contract
        let contract_total = Number($('#contract_total_all').val());
        $('#sum_contract').val(parseFloat(contract_total).toFixed(2));

        // summary repair
        let repair_total = Number($('#repair_maintenancefee_total_all').val()) + Number($('#repair_singkingfund_total_all').val());
        $('#sum_repair').val(parseFloat(repair_total).toFixed(2));

        // summary vandalisme
        let vandalisme_total = Number($('#maintenancefee_total_all').val()) + Number($('#singkingfund_total_all').val());
        $('#sum_vandalisme').val(parseFloat(vandalisme_total).toFixed(2));

        // summary staff
        let staff_total = Number($('#staff_total_all').val());
        $('#sum_staff').val(parseFloat(staff_total).toFixed(2));

        // summary admin
        let admin_total = Number($('#admin_total_all').val());
        $('#sum_admin').val(parseFloat(admin_total).toFixed(2));
        
        var bill_air_pukal = $("#updateFinanceFile [id=util_total_income_1]").val();
        var bill_air_pemilik = $("#updateFinanceFile [id=utilb_total_income_1]").val();
        var bill_air = Number(bill_air_pukal) + Number(bill_air_pemilik);
        $('#{{ $prefix }}bill_air').val(parseFloat(bill_air).toFixed(2));

        var bill_elektrik = $("#updateFinanceFile [id=util_total_income_2]").val();
        $('#{{ $prefix }}bill_elektrik').val(parseFloat(bill_elektrik).toFixed(2));

        var caruman_cukai = $("#updateFinanceFile [id=utilb_total_income_2]").val();
        $('#{{ $prefix }}caruman_cukai').val(parseFloat(caruman_cukai).toFixed(2));

        var summary_total = document.getElementsByName("{{ $prefix }}amount[]");
        var sum_total_summary = 0;
        for (var i = 0; i < summary_total.length; i++) {
            sum_total_summary += Number(summary_total[i].value);
        }
        $('#{{ $prefix }}jumlah_pembelanjaan').val(parseFloat(sum_total_summary).toFixed(2));

        var jumlah_pendapatan = $("#updateFinanceFile [id=income_total_all]").val();
        $('#{{ $prefix }}jumlah_pendapatan').val(parseFloat(jumlah_pendapatan).toFixed(2));
        
        var jumlah_pendapatan_bersih = jumlah_pendapatan - sum_total_summary;
        $('#{{ $prefix }}jumlah_pendapatan_bersih').val(parseFloat(jumlah_pendapatan_bersih).toFixed(2));
    }

    function submitSummary() {
        error = 0;
        var data = $("#form_summary").serialize();

        $(".loading").css("display", "inline-block");
        $(".submit_button").attr("disabled", "disabled");
        $("#check_mandatory_fields").css("display", "none");

        if (error == 0) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            $.ajax({
                method: "POST",
                url: "{{ URL::action('FinanceController@updateFinanceFileSummary') }}",
                data: data,
                success: function (response) {
                    changes = false;
                    $.unblockUI();
                    $(".loading").css("display", "none");
                    $(".submit_button").removeAttr("disabled");

                    if (response.trim() == "true") {
                        
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $(".loading").css("display", "none");
            $(".submit_button").removeAttr("disabled");
            $("#check_mandatory_fields").css("display", "block");
        }
    }
</script>
