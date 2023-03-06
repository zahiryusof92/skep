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
                        $total_all = 0;
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
                            <th><input type="text" class="form-control form-control-sm text-right" id="{{ $prefix }}jumlah_pembelanjaan" value="{{ $total_all }}" readonly=""></th>
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
        var bill_air_pukal = $("#updateFinanceFile [id=util_total_income_1]").val();
        var bill_air_pemilik = $("#updateFinanceFile [id=utilb_total_income_1]").val();
        var bill_air = Number(bill_air_pukal) + Number(bill_air_pemilik);
        $('#{{ $prefix }}bill_air').val(parseFloat(bill_air).toFixed(2));

        var bill_elektrik = $("#updateFinanceFile [id=util_total_income_2]").val();
        $('#{{ $prefix }}bill_elektrik').val(parseFloat(bill_elektrik).toFixed(2));

        // var caruman_insuran = $("#updateFinanceFile [id=contract_total_income_4]").val();
        // $('#{{ $prefix }}caruman_insuran').val(parseFloat(caruman_insuran).toFixed(2));

        var caruman_cukai = $("#updateFinanceFile [id=utilb_total_income_2]").val();
        $('#{{ $prefix }}caruman_cukai').val(parseFloat(caruman_cukai).toFixed(2));

        // var fi_firma = $("#updateFinanceFile [id=contract_total_income_1]").val();
        // $('#{{ $prefix }}fi_firma').val(parseFloat(fi_firma).toFixed(2));

        // var pembersihan1 = $("#updateFinanceFile [id=contract_total_income_2]").val();
        // var pembersihan2 = $("#updateFinanceFile [id=contract_total_income_10]").val();
        // var pembersihan3 = $("#updateFinanceFile [id=contract_total_income_14]").val();
        // var pembersihan = Number(pembersihan1) + Number(pembersihan2) + Number(pembersihan3);
        // $('#{{ $prefix }}pembersihan').val(parseFloat(pembersihan).toFixed(2));

        // var keselamatan1 = $("#updateFinanceFile [id=contract_total_income_3]").val();
        // var keselamatan2 = $("#updateFinanceFile [id=contract_total_income_7]").val();
        // var keselamatan3 = $("#updateFinanceFile [id=contract_total_income_11]").val();
        // var keselamatan4 = $("#updateFinanceFile [id=contract_total_income_12]").val();
        // var keselamatan5 = $("#updateFinanceFile [id=contract_total_income_13]").val();

        // var keselamatan = Number(keselamatan1) + Number(keselamatan2) + Number(keselamatan3) + Number(keselamatan4) + Number(keselamatan5);
        // $('#{{ $prefix }}keselamatan').val(parseFloat(keselamatan).toFixed(2));

        // var jurutera_elektrik = $("#updateFinanceFile [id=contract_total_income_5]").val();
        // $('#{{ $prefix }}jurutera_elektrik').val(parseFloat(jurutera_elektrik).toFixed(2));

        // var mechanical1 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_1]").val();
        // var mechanical2 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_8]").val();
        // var mechanical3 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_9]").val();
        // var mechanical4 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_14]").val();
        // var mechanical5 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_19]").val();
        // var mechanical = Number(mechanical1) + Number(mechanical2) + Number(mechanical3) + Number(mechanical4) + Number(mechanical5);

        // $('#{{ $prefix }}mechaninal').val(parseFloat(mechanical).toFixed(2));

        // var kawalan_serangga = $("#updateFinanceFile [id=contract_total_income_15]").val();
        // $('#{{ $prefix }}kawalan_serangga').val(parseFloat(kawalan_serangga).toFixed(2));

        // var kos_pekerja = $("#updateFinanceFile [id=staff_total_gaji]").val();
        // $('#{{ $prefix }}kos_pekerja').val(parseFloat(kos_pekerja).toFixed(2));

        // var civil1 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_2]").val();
        // var civil2 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_3]").val();
        // var civil3 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_5]").val();
        // var civil4 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_6]").val();
        // var civil5 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_7]").val();
        // var civil6 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_10]").val();
        // var civil7 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_11]").val();
        // var civil8 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_12]").val();
        // var civil9 = $("#updateFinanceFile [id=repair_maintenancefee_total_income_13]").val();
        // var civil = Number(civil1) + Number(civil2) + Number(civil3) + Number(civil4) + Number(civil5) + Number(civil6) + Number(civil7) + Number(civil8) + Number(civil9);
        // $('#{{ $prefix }}civil').val(parseFloat(civil).toFixed(2));

        // var pentadbiran = $("#updateFinanceFile [id=admin_total_all]").val();
        // $('#{{ $prefix }}pentadbiran').val(parseFloat(pentadbiran).toFixed(2));

        // var fi_ejen_pengurusan = $("#updateFinanceFile [id=admin_total_income_12]").val();
        // $('#{{ $prefix }}fi_ejen_pengurusan').val(parseFloat(fi_ejen_pengurusan).toFixed(2));

        // var lain_lain = 0;
        // $('#{{ $prefix }}lain_lain').val(parseFloat(lain_lain).toFixed(2));

        var summary_total = document.getElementsByName("{{ $prefix }}amount[]");
        var sum_total_summary = 0;
        for (var i = 0; i < summary_total.length; i++) {
            sum_total_summary += Number(summary_total[i].value);
        }
        $('#{{ $prefix }}jumlah_pendapatan').val(parseFloat(sum_total_summary).toFixed(2));
        $('#{{ $prefix }}jumlah_pembelanjaan').val(parseFloat(sum_total_summary).toFixed(2));
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
