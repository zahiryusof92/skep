<?php
$prefix = 'mfr_';
?>

<div class="row padding-vertical-10">
    <div class="col-lg-12">
        @if(!empty($mfreportOld))
            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
        @endif
        <h6>1. LAPORAN RINGKAS PENCAPAIAN KUTIPAN CAJ PENYENGGARAAN (MAINTENANCE FEE)</h6>

        <form id="form_reportMF">

            <div class="row">
                <table id="tbl_reportMF" class="table table-sm borderless" style="font-size: 12px;">
                    <tbody>
                        <tr>
                            <th class="text-center">
                                <span style="color: red;">*</span> MAINTENANCE FEE SEBULAN (PER UNIT)
                            </th>
                            <th>&nbsp;</th>
                            <th class="text-center">
                                <span style="color: red;">*</span> JUMLAH UNIT
                            </th>
                            <th>&nbsp;</th>
                            <th class="text-center">
                                <span style="color: red;">*</span> JUMLAH SERVICE FEE SEPATUT DIKUTIP SEMASA
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <td width="25%">
                                <input type="text" id="{{ $prefix }}fee_sebulan" name="{{ $prefix }}fee_sebulan" class="form-control form-control-sm text-right" value="{{ $mfreport['fee_sebulan'] }}" onkeyup="calculateMFFeeSemasa(this)">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="25%">
                                <input type="text" id="{{ $prefix }}unit" name="{{ $prefix }}unit" class="form-control form-control-sm text-right" value="{{ $mfreport['unit'] }}" onkeyup="calculateMFFeeSemasa(this)">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="25%">
                                <input type="currency" id="{{ $prefix }}fee_semasa"  name="{{ $prefix }}fee_semasa" class="form-control form-control-sm text-right text-right" value="{{ $mfreport['fee_semasa'] }}">
                            </td>
                            <td width="5%">&nbsp;</td>
                        </tr>
                        @if(count($mfreportExtra) > 0)
                            @foreach ($mfreportExtra as $key => $extra)
                                <tr id="mfrf_row{{($key+1)}}">
                                    <td width="25%">
                                        <input type="text" id="{{ $prefix }}fee_sebulan_is_custom_{{ $key }}" name="{{ $prefix }}fee_sebulan_is_custom[]" class="form-control form-control-sm text-right" value="{{ $extra['fee_sebulan'] }}" onkeyup="calculateMFFeeSemasa(this)">
                                    </td>
                                    <td width="5%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="text" id="{{ $prefix }}unit_is_custom_{{ $key }}" name="{{ $prefix }}unit_is_custom[]" class="form-control form-control-sm text-right" value="{{ $extra['unit'] }}" onkeyup="calculateMFFeeSemasa(this)">
                                    </td>
                                    <td width="5%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="currency" id="{{ $prefix }}fee_semasa_is_custom_{{ $key }}" name="{{ $prefix }}fee_semasa_is_custom[]" class="form-control form-control-sm text-right" value="{{ $extra['fee_semasa'] }}">
                                    </td>
                                    <td class="padding-table"><a href="javascript:void(0);" onclick="deleteRowMFExtra('mfrf_row{{($key+1)}}')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                                </tr>
                                
                            @endforeach
                        @endif
                        <tr>
                            <td class="padding-table text-right" colspan="6"><a href="javascript:void(0);" onclick="addRowMFExtra()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm borderless" style="font-size: 12px;">
                    <tbody>
                        <tr>
                            <td>JUMLAH DIKUTIP (TUNGGAKAN + SEMASA + ADVANCED [A])</td>
                            <td>
                                <input type="currency" id="{{ $prefix }}kutipan" name="{{ $prefix }}kutipan" class="form-control form-control-sm text-right" value="0.00" readonly="">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="5%">&nbsp;</td>
                            <td>
                                JUMLAH SERVICE FEE BERJAYA DIKUTIP SEMASA
                            </td>
                            <td>
                                <input type="currency" id="{{ $prefix }}total_income" name="{{ $prefix }}total_income" class="form-control form-control-sm text-right" value="0.00" readonly="">
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-table">JUMLAH TUNGGAKAN BELUM DIKUTIP</td>
                            <td class="padding-table">
                                <input type="currency" id="{{ $prefix }}tunggakan_belum_dikutip" name="{{ $prefix }}tunggakan_belum_dikutip" onkeyup="calculateMFR()" class="form-control form-control-sm text-right" value="{{ $mfreport['tunggakan_belum_dikutip'] }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="row">
                <table class="table table-sm borderless" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th width="20%" style="text-align: center">JUMLAH PERBELANJAAN</th>
                            <th width="65%" style="text-align: center">PERKARA</th>
                            <th width="15%" style="text-align: center">JUMLAH (RM)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @for($i=0 ; $i < count($reportMF) ; $i++)
                        <tr>
                            <td><input type="hidden" name="{{ $prefix }}report_key[]" value="{{ $reportMF[$i]['report_key'] }}">&nbsp;</td>
                            <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $reportMF[$i]['name'] }}" readonly=""></td>
                            <td><input type="currency" id="{{ $prefix . $reportMF[$i]['report_key'] }}" name="{{ $prefix }}amount[]" class="form-control form-control-sm text-right" value="{{ $reportMF[$i]['amount'] }}" readonly=""></td>
                        </tr>
                        @endfor

                        <tr>
                            <td>&nbsp;</td>
                            <td class="padding-form">JUMLAH TELAH BAYAR [B]</td>
                            <td><input type="currency" id="{{ $prefix }}bayar_total" name="{{ $prefix }}bayar_total" class="form-control form-control-sm text-right" value="0.00" readonly=""></td>
                        </tr>

                        <tr>
                            <td class="padding-table" colspan="2">LEBIHAN / KURANGAN PENDAPATAN (A) - TUNGGAKAN BELUM DIKUTIP - (B)</td>
                            <td><input type="currency" id="{{ $prefix }}lebihan_kurangan" name="{{ $prefix }}lebihan_kurangan" class="form-control form-control-sm text-right" value="0.00" readonly=""></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr/>

            <div class="row">
                <table class="table table-sm borderless" style="font-size: 12px;">
                    <tbody>
                        <tr>
                            <td class="padding-table" width="20%">
                                <span style="color: red;">*</span> NO. AKAUN
                            </td>
                            <td width="35%">
                                <input id="{{ $prefix }}no_akaun" name="{{ $prefix }}no_akaun" class="form-control form-control-sm" type="digit" value="{{ $mfreport['no_akaun'] }}">
                                <small id="{{ $prefix }}no_akaun_err" style="display: none;"></small>
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td class="padding-table" width="25%">
                                <span style="color: red;">*</span> BAKI BANK (AWAL)
                            </td>
                            <td width="15%">
                                <input type="text" name="{{ $prefix }}baki_bank_awal" class="form-control form-control-sm text-right" value="{{ $mfreport['baki_bank_awal'] }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-table">
                                <span style="color: red;">*</span> NAMA BANK
                            </td>
                            <td>
                                <input id="{{ $prefix }}nama_bank" name="{{ $prefix }}nama_bank" class="form-control form-control-sm" type="text" value="{{ $mfreport['nama_bank'] }}">
                                <small id="{{ $prefix }}nama_bank_err" style="display: none;"></small>
                            </td>
                            <td>&nbsp;</td>
                            <td class="padding-table">
                                <span style="color: red;">*</span> BAKI BANK (AKHIR)
                            </td>
                            <td>
                                <input type="text" name="{{ $prefix }}baki_bank_akhir" class="form-control form-control-sm text-right" value="{{ $mfreport['baki_bank_akhir'] }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if ($update_permission == 1) { ?>
                <div class="form-actions">
                    <input type="hidden" name="finance_file_id" value="{{ \Helper\Helper::encode($financefiledata->id) }}"/>
                    <button type="button"class="btn btn-own submit_button" onclick="submitMFReport()">{{ trans("app.forms.submit") }}</button>
                    <img class="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>    
                </div>
            <?php } ?>

        </form>

    </div>
</div>

<script type="text/javascript">
    var rowMFFeesNo = ("{{count($mfreportExtra)}}" > 0)? (Number("{{count($mfreportExtra)}}") + Number(1)) : 1;
    $(document).ready(function () {
        calculateMFR();
    });

    function calculateMFFeeSemasa(e) {
        let id = e.id;
        let fee_sebulan_id = "#{{ $prefix }}fee_sebulan";
        let unit_id = "#{{ $prefix }}unit";
        let fee_semasa_id = "#{{ $prefix }}fee_semasa";
        if(id.includes('_is_custom')) {
            id = id.substr(-2);
            id = id.includes('_')? id.split("_")[1] : id;
            fee_sebulan_id += '_is_custom_' + id;
            fee_semasa_id += '_is_custom_' + id;
            unit_id += '_is_custom_' + id;
        }
        let value = (parseFloat($(fee_sebulan_id).val()).toFixed(2) * Number($(unit_id).val()));
        $(fee_semasa_id).val(parseFloat(value).toFixed(2));
    }

    function calculateMFR() {
        var mfr_kutipan = $("#updateFinanceFile [id=income_total_income_1]").val();
        $('#{{ $prefix }}kutipan').val(parseFloat(mfr_kutipan).toFixed(2));

        var mfr_total_income = $("#updateFinanceFile [id=income_semasa_1]").val();
        $('#{{ $prefix }}total_income').val(parseFloat(mfr_total_income).toFixed(2));

        var mfr_utility = $("#updateFinanceFile [id=util_total_all]").val();
        $('#{{ $prefix }}utility').val(parseFloat(mfr_utility).toFixed(2));

        var mfr_contract = $("#updateFinanceFile [id=contract_total_all]").val();
        $('#{{ $prefix }}contract').val(parseFloat(mfr_contract).toFixed(2));

        var mfr_repair = $("#updateFinanceFile [id=repair_maintenancefee_total_all]").val();
        $('#{{ $prefix }}repair').val(parseFloat(mfr_repair).toFixed(2));

        var mfr_vandalisme = $("#updateFinanceFile [id=maintenancefee_total_all]").val();
        $('#{{ $prefix }}vandalisme').val(parseFloat(mfr_vandalisme).toFixed(2));

        var mfr_staff = $("#updateFinanceFile [id=staff_total_all]").val();
        $('#{{ $prefix }}staff').val(parseFloat(mfr_staff).toFixed(2));

        var mfr_admin = $("#updateFinanceFile [id=admin_total_all]").val();
        $('#{{ $prefix }}admin').val(parseFloat(mfr_admin).toFixed(2));

        var mfr_bayar = document.getElementsByName("{{ $prefix }}amount[]");
        var mfr_bayar_total = 0;
        for (var i = 0; i < mfr_bayar.length; i++) {
            mfr_bayar_total += Number(mfr_bayar[i].value);
        }
        $('#{{ $prefix }}bayar_total').val(parseFloat(mfr_bayar_total).toFixed(2));

        var mfr_tunggakan_belum_dikutip = $("#updateFinanceFile [id=mfr_tunggakan_belum_dikutip]").val();
        var mfr_lebihan_kurangan = Number(mfr_kutipan) - Number(mfr_tunggakan_belum_dikutip) - Number(mfr_bayar_total);
        $('#{{ $prefix }}lebihan_kurangan').val(parseFloat(mfr_lebihan_kurangan).toFixed(2));
    }

    function addRowMFExtra() {
        changes = true;

        $("#tbl_reportMF tr:last").prev().after(
            '<tr id="mfrf_row' + rowMFFeesNo + '">'+
                '<td width="25%"><input type="text" id="{{ $prefix }}fee_sebulan_is_custom_'+ rowMFFeesNo +'" name="{{ $prefix }}fee_sebulan_is_custom[]" class="form-control form-control-sm text-right" value="0.00" onkeyup="calculateMFFeeSemasa(this)"></td>'+
                '<td width="5%">&nbsp;</td>'+
                '<td width="25%"><input type="text" id="{{ $prefix }}unit_is_custom_'+ rowMFFeesNo +'" name="{{ $prefix }}unit_is_custom[]" class="form-control form-control-sm text-right" value="0" onkeyup="calculateMFFeeSemasa(this)"></td>'+
                '<td width="5%">&nbsp;</td>'+
                '<td width="25%"><input type="currency" id="{{ $prefix }}fee_semasa_is_custom_'+ rowMFFeesNo +'" name="{{ $prefix }}fee_semasa_is_custom[]" class="form-control form-control-sm text-right" value="0.00"></td>'+
                '<td class="padding-table"><a href="javascript:void(0);" onclick="deleteRowMFExtra(\'mfrf_row' + rowMFFeesNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');
        rowMFFeesNo++;
    }

    function deleteRowMFExtra(rowMFFeesNo) {
        changes = true;

        $('#' + rowMFFeesNo).remove();

    }

    function submitMFReport() {
        error = 0;
        var data = $("#form_reportMF").serialize();

        $(".loading").css("display", "inline-block");
        $(".submit_button").attr("disabled", "disabled");
        $("#check_mandatory_fields").css("display", "none");

        if (error == 0) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            $.ajax({
                method: "POST",
                url: "{{ URL::action('FinanceController@updateFinanceFileReportMf') }}",
                data: data,
                success: function (response) {
                    changes = false;
                    $.unblockUI();
                    $(".loading").css("display", "none");
                    $(".submit_button").removeAttr("disabled");

                    if (response.trim() == "true") {
                        submitSummary();
                        $.notify({
                            message: "<div class='text-center'>{{ trans('app.successes.saved_successfully') }}</div>"
                        }, {
                            type: 'success',
                            allow_dismiss: false,
                            placement: {
                                from: "top",
                                align: "center"
                            },
                            delay: 100,
                            timer: 500
                        });
                        $('a[href="' + window.location.hash + '"]').trigger('click');
                        location.reload();
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

@if(!empty($mfreportOld))
@include('finance_en.show.mf_report')
@endif