<?php
$prefix = 'sfr_';

$count = 0;
?>

<div class="row">
    <div class="col-lg-12">
        <h6>2. LAPORAN RINGKAS PENCAPAIAN KUTIPAN KUMPULAN WANG PENJELAS (SINGING FUND)</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <tbody>
                <?php
                $sf_fee_semasa = $sfreport['fee_semasa'];
                ?>
                <tr>
                    <td class="text-left" width="35%">SINKING FUND SEBULAN (PER UNIT)</td>
                    <td class="text-right" width="15%">{{ number_format((double) $sfreport['fee_sebulan'], 2) }}</td>
                    <td class="text-left" width="35%">JUMLAH UNIT</td>
                    <td class="text-right" width="15%">{{ $sfreport['unit'] }}</td>
                </tr>
                @if ($sfreportExtras)
                @foreach ($sfreportExtras as $sfreportExtra)
                <?php
                $sf_fee_semasa += $sfreportExtra['fee_semasa'];
                ?>
                <tr>
                    <td class="text-left" width="35%">SINKING FUND SEBULAN (PER UNIT)</td>
                    <td class="text-right" width="15%">{{ number_format((double) $sfreportExtra['fee_sebulan'], 2) }}</td>
                    <td class="text-left" width="35%">JUMLAH UNIT</td>
                    <td class="text-right" width="15%">{{ $sfreportExtra['unit'] }}</td>
                </tr>
                @endforeach
                @endif
                <tr>
                    <td class="text-left">JUMLAH DIKUTIP (TUNGGAKAN + SEMASA + ADVANCED [A])</td>
                    <td class="text-right" id="{{$prefix}}kutipan"></td>
                    <td class="text-left">JUMLAH SINKING FUND SEPATUT DIKUTIP SEMASA</td>
                    <td class="text-right">{{ number_format($sf_fee_semasa, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <th class="text-left">JUMLAH SINKING FUND BERJAYA DIKUTIP SEMASA</th>
                    <th class="text-right" id="{{$prefix}}total_income"></th>
                </tr>
            </tbody>
        </table>

        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th width="20%" class="text-center">JUMLAH PERBELANJAAN</th>
                    <th width="65%" class="text-center">PERKARA</th>
                    <th width="15%" class="text-center">JUMLAH (RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportSF as $reportSFs)
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-left">{{ $reportSFs['name'] }}</td>
                    <td class="text-right">
                        <input type="text" class="{{ $prefix . $reportSFs['report_key'] }}" name="{{ $prefix }}amount[]"
                            value="{{ $reportSFs['amount'] }}" hidden>
                        {{ number_format($reportSFs['amount'], 2) }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-left">JUMLAH TELAH BAYAR [B]</td>
                    <td class="text-right" id="{{$prefix . 'bayar_total'}}"></td>
                </tr>
                <tr>
                    <td class="text-left" colspan="2">LEBIHAN / KURANGAN PENDAPATAN (A) - (B)</td>
                    <td class="text-right" id="{{$prefix . 'lebihan_kurangan'}}"></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-sm table-bordered" style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-left" width="35%">NO. AKAUN</td>
                    <td class="text-left" width="15%">{{ $sfreport['no_akaun'] }}</td>
                    <td class="text-left" width="35%">BAKI BANK (AWAL)</td>
                    <td class="text-right" width="15%">{{ number_format($sfreport['baki_bank_awal'], 2) }}</td>
                </tr>
                <tr>
                    <td class="text-left">NAMA BANK</td>
                    <td class="text-left">{{ $sfreport['nama_bank'] }}</td>
                    <td class="text-left">BAKI BANK (AKHIR)</td>
                    <td class="text-right">{{ number_format($sfreport['baki_bank_akhir'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        calculateSFR();
    });

    function calculateSFR() {
        var sfr_kutipan = $("[id=income_total_income_2]").val();
        $('#{{ $prefix }}kutipan').text(toCommas(parseFloat(sfr_kutipan).toFixed(2)));

        var sfr_total_income = $("[id=income_semasa_2]").val();
        $('#{{ $prefix }}total_income').text(toCommas(parseFloat(sfr_total_income).toFixed(2)));

        var sfr_bayar = document.getElementsByName("{{ $prefix }}amount[]");
        var sfr_bayar_total = 0;
        for (var i = 0; i < sfr_bayar.length; i++) {
            sfr_bayar_total += Number(sfr_bayar[i].value);
        }
        $('#{{ $prefix }}bayar_total').text(toCommas(parseFloat(sfr_bayar_total).toFixed(2)));

        var sfr_lebihan_kurangan = Number(sfr_kutipan) - Number(sfr_bayar_total);
        $('#{{ $prefix }}lebihan_kurangan').text(toCommas(parseFloat(sfr_lebihan_kurangan).toFixed(2)));
    }
</script>