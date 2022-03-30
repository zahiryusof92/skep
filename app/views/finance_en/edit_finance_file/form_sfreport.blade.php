<?php
$prefix = 'sfr_';

$count = 0;
?>

<div class="row padding-vertical-10">
    <div class="col-lg-12">
        @if(!empty($sfreportOld))
            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
        @endif
        <h6>2. LAPORAN RINGKAS PENCAPAIAN KUTIPAN KUMPULAN WANG PENJELAS (SINKING FUND)</h6>

        <form id="form_reportSF">

            <div class="row">
                <table id="tbl_reportSF" class="table table-sm borderless" style="font-size: 12px;">
                    <tbody>
                        <tr>
                            <th class="text-center">
                                <span style="color: red;">*</span> SINKING FEE SEBULAN (PER UNIT)
                            </th>
                            <th>&nbsp;</th>
                            <th class="text-center">
                                <span style="color: red;">*</span> JUMLAH UNIT
                            </th>
                            <th>&nbsp;</th>
                            <th class="text-center">
                                <span style="color: red;">*</span> JUMLAH SINKING FUND SEPATUT DIKUTIP SEMASA
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <td width="25%">
                                <input type="text" id="{{ $prefix }}fee_sebulan" name="{{ $prefix }}fee_sebulan" class="form-control form-control-sm text-right" value="{{ $sfreport['fee_sebulan'] }}" onkeyup="calculateSFFeeSemasa(this)">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="25%">
                                <input type="text" id="{{ $prefix }}unit" name="{{ $prefix }}unit" class="form-control form-control-sm text-right" value="{{ $sfreport['unit'] }}" onkeyup="calculateSFFeeSemasa(this)">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="25%">
                                <input type="currency" id="{{ $prefix }}fee_semasa" name="{{ $prefix }}fee_semasa" class="form-control form-control-sm text-right" value="{{ $sfreport['fee_semasa'] }}">
                            </td>
                            <td width="5%">&nbsp;</td>
                        </tr>
                        @if(count($sfreportExtra) > 0)
                            @foreach ($sfreportExtra as $key => $extra)
                                <tr id="sfrf_row{{($key+1)}}">
                                    <td width="25%">
                                        <input type="text" id="{{ $prefix }}fee_sebulan_is_custom_{{ $key }}" name="{{ $prefix }}fee_sebulan_is_custom[]" class="form-control form-control-sm text-right" value="{{ $extra['fee_sebulan'] }}" onkeyup="calculateSFFeeSemasa(this)">
                                    </td>
                                    <td width="5%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="text" id="{{ $prefix }}unit_is_custom_{{ $key }}" name="{{ $prefix }}unit_is_custom[]" class="form-control form-control-sm text-right" value="{{ $extra['unit'] }}" onkeyup="calculateSFFeeSemasa(this)">
                                    </td>
                                    <td width="5%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="currency" id="{{ $prefix }}fee_semasa_is_custom_{{ $key }}" name="{{ $prefix }}fee_semasa_is_custom[]" class="form-control form-control-sm text-right" value="{{ $extra['fee_semasa'] }}">
                                    </td>
                                    <td class="padding-table"><a href="javascript:void(0);" onclick="deleteRowSFExtra('sfrf_row{{($key+1)}}')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                                </tr>
                                
                            @endforeach
                        @endif
                        <tr>
                            <td class="padding-table text-right" colspan="6"><a href="javascript:void(0);" onclick="addRowSFExtra()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
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

                    </tbody>
                </table>
            </div>

            <hr>

            <div class="row">
                <table id="dynamic_form_sfr" class="table table-sm borderless" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th width="20%" style="text-align: center">JUMLAH PERBELANJAAN</th>
                            <th width="65%" style="text-align: center">PERKARA</th>
                            <th width="15%" style="text-align: center">JUMLAH (RM)</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($reportSF as $reportSFs)
                        <tr id="sfr_row{{ ++$count }}">
                            <td><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $reportSFs['is_custom'] }}"><input type="hidden" name="{{ $prefix }}report_key[]" value="{{ $reportSFs['report_key'] }}">&nbsp;</td>
                            <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $reportSFs['name'] }}" {{ $reportSFs['is_custom'] ? '' : 'readonly' }}></td>
                            <td><input type="currency" id="{{ $prefix . $reportSFs['report_key'] }}" name="{{ $prefix }}amount[]" class="form-control form-control-sm text-right" value="{{ $reportSFs['amount'] }}" readonly=""></td>
                            @if ($reportSFs['is_custom'])
                            <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowSFR('sfr_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td class="padding-table text-right" colspan="5"><a href="javascript:void(0);" onclick="addRowSFR()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <td class="padding-form">JUMLAH TELAH BAYAR [B]</td>
                            <td><input type="currency" id="{{ $prefix }}bayar_total" name="{{ $prefix }}bayar_total" class="form-control form-control-sm text-right" value="0.00" readonly=""></td>
                        </tr>

                        <tr>
                            <td class="padding-table" colspan="2">LEBIHAN / KURANGAN PENDAPATAN (A) - (B)</td>
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
                                <input id="{{ $prefix }}no_akaun" name="{{ $prefix }}no_akaun" class="form-control form-control-sm" type="digit" value="{{ $sfreport['no_akaun'] }}">
                                <small id="{{ $prefix }}no_akaun_err" style="display: none;"></small>
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td class="padding-table" width="25%">
                                <span style="color: red;">*</span> BAKI BANK (AWAL)
                            </td>
                            <td width="15%">
                                <input type="currency" name="{{ $prefix }}baki_bank_awal" class="form-control form-control-sm text-right" value="{{ $sfreport['baki_bank_awal'] }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-table">
                                <span style="color: red;">*</span> NAMA BANK
                            </td>
                            <td>
                                <input id="{{ $prefix }}nama_bank" name="{{ $prefix }}nama_bank" class="form-control form-control-sm" type="text" value="{{ $sfreport['nama_bank'] }}">
                                <small id="{{ $prefix }}nama_bank_err" style="display: none;"></small>
                            </td>
                            <td>&nbsp;</td>
                            <td class="padding-table">
                                <span style="color: red;">*</span> BAKI BANK (AKHIR)
                            </td>
                            <td>
                                <input type="currency" name="{{ $prefix }}baki_bank_akhir" class="form-control form-control-sm text-right" value="{{ $sfreport['baki_bank_akhir'] }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if ($update_permission == 1) { ?>
                <div class="form-actions">
                    <input type="hidden" name="finance_file_id" value="{{ \Helper\Helper::encode($financefiledata->id) }}"/>
                    <button type="button"class="btn btn-own submit_button" onclick="submitSFReport()">{{ trans("app.forms.submit") }}</button>
                    <img class="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>   
                </div>
            <?php } ?>

        </form>

    </div>
</div>

<script type="text/javascript">
    var rowSFFeesNo = ("{{count($sfreportExtra)}}" > 0)? (Number("{{count($sfreportExtra)}}") + Number(1)) : 1;
    $(document).ready(function () {
        calculateSFR();
    });

    function calculateSFFeeSemasa(e) {
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
        $(fee_semasa_id).val(value);
    }

    function calculateSFR() {
        var sfr_kutipan = $("#updateFinanceFile [id=income_total_income_2]").val();
        $('#{{ $prefix }}kutipan').val(parseFloat(sfr_kutipan).toFixed(2));

        var sfr_total_income = $("#updateFinanceFile [id=income_semasa_2]").val();
        $('#{{ $prefix }}total_income').val(parseFloat(sfr_total_income).toFixed(2));

        var sfr_repair = $("#updateFinanceFile [id=repair_singkingfund_total_all]").val();
        $('#{{ $prefix }}repair').val(parseFloat(sfr_repair).toFixed(2));

        var sfr_vandalisme = $("#updateFinanceFile [id=singkingfund_total_all]").val();
        $('#{{ $prefix }}vandalisme').val(parseFloat(sfr_vandalisme).toFixed(2));

        var sfr_bayar = document.getElementsByName("{{ $prefix }}amount[]");
        var sfr_bayar_total = 0;
        
        for (var i = 0; i < sfr_bayar.length; i++) { 
            sfr_bayar_total += Number(sfr_bayar[i].value);
        }
        $('#{{ $prefix }}bayar_total').val(parseFloat(sfr_bayar_total).toFixed(2));

        var sfr_lebihan_kurangan = Number(sfr_kutipan) - Number(sfr_bayar_total);
        $('#{{ $prefix }}lebihan_kurangan').val(parseFloat(sfr_lebihan_kurangan).toFixed(2));
    }

    function addRowSFR() {
        changes = true;

        var rowSFRNo = $("#dynamic_form_sfr tr").length;
        rowSFRNo = rowSFRNo - 3;
        $("#dynamic_form_sfr tr:last").prev().prev().prev().after('<tr id="sfr_row' + rowSFRNo + '"><td><input type="hidden" name="{{ $prefix }}is_custom[]" value="1"><input type="hidden" name="{{ $prefix }}report_key[]" value="custom' + rowSFRNo + '">&nbsp;</td><td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value=""></td><td><input type="currency" oninput="calculateSFR()" id="{{ $prefix }}amount_' + rowSFRNo + '" name="{{ $prefix }}amount[]" class="form-control form-control-sm text-right" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowSFR(\'sfr_row' + rowSFRNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateSFR();
    }

    function deleteRowSFR(rowSFRNo) {
        changes = true;

        $('#' + rowSFRNo).remove();

        calculateSFR();
    }

    function addRowSFExtra() {
        changes = true;

        $("#tbl_reportSF tr:last").prev().after(
            '<tr id="sfrf_row' + rowSFFeesNo + '">'+
                '<td width="25%"><input type="text" id="{{ $prefix }}fee_sebulan_is_custom_'+ rowSFFeesNo +'" name="{{ $prefix }}fee_sebulan_is_custom[]" class="form-control form-control-sm text-right" value="0.00" onkeyup="calculateSFFeeSemasa(this)"></td>'+
                '<td width="5%">&nbsp;</td>'+
                '<td width="25%"><input type="text" id="{{ $prefix }}unit_is_custom_'+ rowSFFeesNo +'" name="{{ $prefix }}unit_is_custom[]" class="form-control form-control-sm text-right" value="0" onkeyup="calculateSFFeeSemasa(this)"></td>'+
                '<td width="5%">&nbsp;</td>'+
                '<td width="25%"><input type="currency" id="{{ $prefix }}fee_semasa_is_custom_'+ rowSFFeesNo +'" name="{{ $prefix }}fee_semasa_is_custom[]" class="form-control form-control-sm text-right" value="0.00"></td>'+
                '<td class="padding-table"><a href="javascript:void(0);" onclick="deleteRowSFExtra(\'mfrf_row' + rowSFFeesNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');
        rowSFFeesNo++;
    }

    function deleteRowSFExtra(rowSFFeesNo) {
        changes = true;

        $('#' + rowSFFeesNo).remove();

    }

    function submitSFReport() {
        error = 0;
        var data = $("#form_reportSF").serialize();

        $(".loading").css("display", "inline-block");
        $(".submit_button").attr("disabled", "disabled");
        $("#check_mandatory_fields").css("display", "none");

        if (error == 0) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            $.ajax({
                method: "POST",
                url: "{{ URL::action('FinanceController@updateFinanceFileReportSf') }}",
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

@if(!empty($sfreportOld))
@include('finance_en.show.sf_report')
@endif