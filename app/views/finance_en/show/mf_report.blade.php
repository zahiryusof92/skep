<?php
$old_prefix = 'mfr_old_';
?>
<div class="row padding-vertical-10">
    <div class="col-lg-12">

        <h6><u>{{ trans("app.forms.before_change") }}</u></h6>
        <h6>1. LAPORAN RINGKAS PENCAPAIAN KUTIPAN CAJ PENYENGGARAAN (MAINTENANCE FEE)</h6>
        <fieldset disabled>
            <div class="row">
                <table id="tbl_reportMFOld" class="table table-sm borderless" style="font-size: 12px;">
                    <tbody>
                        <tr>
                            <th class="text-center">
                                MAINTENANCE FEE SEBULAN (PER UNIT)
                            </th>
                            <th>&nbsp;</th>
                            <th class="text-center">
                                JUMLAH UNIT
                            </th>
                            <th>&nbsp;</th>
                            <th class="text-center">
                                JUMLAH SERVICE FEE SEPATUT DIKUTIP SEMASA
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <td width="25%">
                                <input type="text" name="{{$old_prefix}}fee_sebulan" class="form-control form-control-sm text-right" value="{{ $mfreportOld->fee_sebulan }}">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="25%">
                                <input type="text" name="{{$old_prefix}}unit" class="form-control form-control-sm text-right" value="{{ $mfreportOld->unit }}">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="25%">
                                <input type="currency" name="{{$old_prefix}}fee_semasa" class="form-control form-control-sm text-right text-right" value="{{ $mfreportOld->fee_semasa }}">
                            </td>
                            <td width="5%">&nbsp;</td>
                        </tr>
                        @if(count($mfreportExtraOld) > 0)
                            @foreach ($mfreportExtraOld as $key => $item)
                                <tr id="mfrfOld_row{{($key+1)}}">
                                    <td width="25%">
                                        <input type="text" name="{{$old_prefix}}fee_sebulan_is_custom[]" class="form-control form-control-sm text-right" value="{{ $item->fee_sebulan }}">
                                    </td>
                                    <td width="5%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="text" name="{{$old_prefix}}unit_is_custom[]" class="form-control form-control-sm text-right" value="{{ $item->unit }}">
                                    </td>
                                    <td width="5%">&nbsp;</td>
                                    <td width="25%">
                                        <input type="currency" name="{{$old_prefix}}fee_semasa_is_custom[]" class="form-control form-control-sm text-right" value="{{ $item->fee_semasa }}">
                                    </td>
                                </tr>
                                
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="table table-sm borderless" style="font-size: 12px;">
                    <tbody>
                        <tr>
                            <td>JUMLAH DIKUTIP (TUNGGAKAN + SEMASA + ADVANCED [A])</td>
                            <td>
                                <input type="currency" id="{{$old_prefix}}kutipan" name="{{$old_prefix}}kutipan" class="form-control form-control-sm text-right" value="0.00" readonly="">
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td width="5%">&nbsp;</td>
                            <td>
                                JUMLAH SERVICE FEE BERJAYA DIKUTIP SEMASA
                            </td>
                            <td>
                                <input type="currency" id="{{$old_prefix}}total_income" name="{{$old_prefix}}total_income" class="form-control form-control-sm text-right" value="0.00" readonly="">
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-table">JUMLAH TUNGGAKAN BELUM DIKUTIP</td>
                            <td class="padding-table">
                                <input type="currency" id="{{ $old_prefix }}tunggakan_belum_dikutip" name="{{ $old_prefix }}tunggakan_belum_dikutip"  class="form-control form-control-sm text-right" value="{{ $mfreportOld->tunggakan_belum_dikutip }}">
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
                        <?php $total_b = 0;?>
                        @foreach ($reportMFOld as $key => $item)
                        <tr id="mfrOld_row{{ ($key + 1) }}">
                            <td><input type="hidden" name="{{$old_prefix}}report_key[]" value="{{ $item->report_key }}">&nbsp;</td>
                            <td><input type="text" name="{{$old_prefix}}name[]" class="form-control form-control-sm" value="{{ $item->name }}" readonly=""></td>
                            <td><input type="currency" name="{{$old_prefix}}amount[]" class="form-control form-control-sm text-right" value="{{ $item->amount }}" readonly=""></td>
                        </tr>
                        <?php $total_b += $item->amount; ?>
                        @endforeach

                        <tr>
                            <td>&nbsp;</td>
                            <td class="padding-form">JUMLAH TELAH BAYAR [B]</td>
                            <td><input type="currency" id="{{$old_prefix}}bayar_total" name="{{$old_prefix}}bayar_total" class="form-control form-control-sm text-right" value="{{$total_b}}" readonly=""></td>
                        </tr>

                        <tr>
                            <td class="padding-table" colspan="2">LEBIHAN / KURANGAN PENDAPATAN (A) - TUNGGAKAN BELUM DIKUTIP - (B)</td>
                            <td><input type="currency" id="{{$old_prefix}}lebihan_kurangan" name="{{$old_prefix}}lebihan_kurangan" class="form-control form-control-sm text-right" value="0.00" readonly=""></td>
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
                                NO. AKAUN
                            </td>
                            <td width="35%">
                                <input id="{{$old_prefix}}no_akaun" name="{{$old_prefix}}no_akaun" class="form-control form-control-sm" type="digit" value="{{ $mfreportOld->no_akaun }}">
                                <small id="{{$old_prefix}}no_akaun_err" style="display: none;"></small>
                            </td>
                            <td width="5%">&nbsp;</td>
                            <td class="padding-table" width="25%">
                                BAKI BANK (AWAL)
                            </td>
                            <td width="15%">
                                <input type="text" name="{{$old_prefix}}baki_bank_awal" class="form-control form-control-sm text-right" value="{{ $mfreportOld->baki_bank_awal }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-table">
                                NAMA BANK
                            </td>
                            <td>
                                <input id="{{$old_prefix}}nama_bank" name="{{$old_prefix}}nama_bank" class="form-control form-control-sm" type="text" value="{{ $mfreportOld->nama_bank }}">
                                <small id="{{$old_prefix}}nama_bank_err" style="display: none;"></small>
                            </td>
                            <td>&nbsp;</td>
                            <td class="padding-table">
                                BAKI BANK (AKHIR)
                            </td>
                            <td>
                                <input type="text" name="{{$old_prefix}}baki_bank_akhir" class="form-control form-control-sm text-right" value="{{ $mfreportOld->baki_bank_akhir }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>

<script>
    $(document).ready(function () {
        calculateMFROld();
    });

    function calculateMFROld() {
        var mfr_old_kutipan = $("#updateFinanceFile [id=income_old_total_income_1]").val();
        if(mfr_old_kutipan != undefined) {
            $('#{{ $old_prefix }}kutipan').val(parseFloat(mfr_old_kutipan).toFixed(2));
        }

        var mfr_old_total_income = $("#updateFinanceFile [id=income_old_semasa_1]").val();
        if(mfr_old_total_income != undefined) {
            $('#{{ $old_prefix }}total_income').val(parseFloat(mfr_old_total_income).toFixed(2));
        }
        
        var old_mfr_tunggakan_belum_dikutip = $("#{{ $old_prefix }}tunggakan_belum_dikutip").val();
        var mfr_old_bayar_total = $('#{{ $old_prefix }}bayar_total').val();
        var mfr_old_lebihan_kurangan = Number(mfr_old_kutipan) - Number(old_mfr_tunggakan_belum_dikutip) - Number(mfr_old_bayar_total);
        if(mfr_old_kutipan != undefined) {
            $('#{{ $old_prefix }}lebihan_kurangan').val(parseFloat(mfr_old_lebihan_kurangan).toFixed(2));
        }
    }
</script>
<hr>
