<?php
$prefix = 'mfr_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>1. LAPORAN RINGKAS PENCAPAIAN KUTIPAN CAJ PENYENGGARAAN (MAINTENANCE FEE)</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <tbody>
                <?php
                $mf_fee_semasa = $mfreport['fee_semasa'];
                ?>
                <tr>
                    <td class="text-left" width="35%">MAINTENANCE FEE SEBULAN (PER UNIT)</td>
                    <td class="text-right" width="15%">{{ number_format((double) $mfreport['fee_sebulan'], 2) }}</td>
                    <td class="text-left" width="35%">JUMLAH UNIT</td>
                    <td class="text-right" width="15%">{{ $mfreport['unit'] }}</td>
                </tr>

                @if ($mfreportExtras)
                @foreach ($mfreportExtras as $mfreportExtra)
                <?php
                $mf_fee_semasa += $mfreportExtra['fee_semasa'];
                ?>
                <tr>
                    <td class="text-left" width="35%">MAINTENANCE FEE SEBULAN (PER UNIT)</td>
                    <td class="text-right" width="15%">{{ number_format((double) $mfreportExtra['fee_sebulan'], 2) }}</td>
                    <td class="text-left" width="35%">JUMLAH UNIT</td>
                    <td class="text-right" width="15%">{{ $mfreportExtra['unit'] }}</td>
                </tr>
                @endforeach
                @endif
                <tr>
                    <td class="text-left">JUMLAH DIKUTIP (TUNGGAKAN + SEMASA + ADVANCED [A])</td>
                    <td class="text-right" id="{{$prefix}}kutipan"></td>
                    <td class="text-left">JUMLAH SERVICE FEE SEPATUT DIKUTIP SEMASA</td>
                    <td class="text-right">{{ number_format($mf_fee_semasa, 2) }}</td>
                </tr>
                <tr>
                    <th class="text-left">JUMLAH TUNGGAKAN BELUM DIKUTIP</th>
                    <th class="text-right">
                        <input type="hidden" id="{{$prefix}}tunggakan_belum_dikutip"
                            value="{{ $mfreport['tunggakan_belum_dikutip'] }}" />
                        {{ $mfreport['tunggakan_belum_dikutip'] }}
                    </th>
                    <th class="text-left">JUMLAH SERVICE FEE BERJAYA DIKUTIP SEMASA</th>
                    <th class="text-right" id="{{$prefix}}total_income"></th>
                </tr>
            </tbody>
        </table>

        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th class="text-center" width="20%">JUMLAH PERBELANJAAN</th>
                    <th class="text-center" width="65%">PERKARA</th>
                    <th class="text-center" width="15%">JUMLAH (RM)</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0 ; $i < count($reportMF) ; $i++) <tr>
                    <td>&nbsp;</td>
                    <td class="text-left">{{ $reportMF[$i]['name'] }}</td>
                    <td class="text-right">
                        <input type="text" class="{{ $prefix . $reportMF[$i]['report_key'] }}"
                            name="{{ $prefix }}amount[]" value="{{ $reportMF[$i]['amount'] }}" hidden>
                        {{ number_format($reportMF[$i]['amount'], 2) }}
                    </td>
                    </tr>
                    @endfor
                    <tr>
                        <td>&nbsp;</td>
                        <td class="text-left">JUMLAH TELAH BAYAR [B]</td>
                        <td class="text-right" id="{{ $prefix }}bayar_total"></td>
                    </tr>

                    <tr>
                        <td class="text-left" colspan="2">LEBIHAN / KURANGAN PENDAPATAN (A) - (B)</td>
                        <td class="text-right" id="{{ $prefix }}lebihan_kurangan"></td>
                    </tr>
            </tbody>
        </table>

        <table class="table table-sm table-bordered" style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-left" width="35%">NO. AKAUN</td>
                    <td class="text-left" width="15%">{{ $mfreport['no_akaun'] }}</td>
                    <td class="text-left" width="35%">BAKI BANK (AWAL)</td>
                    <td class="text-right" width="15%">{{ number_format($mfreport['baki_bank_awal'], 2) }}</td>
                </tr>
                <tr>
                    <td class="text-left">NAMA BANK</td>
                    <td class="text-left">{{ $mfreport['nama_bank'] }}</td>
                    <td class="text-left">BAKI BANK (AKHIR)</td>
                    <td class="text-right">{{ number_format($mfreport['baki_bank_akhir'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        calculateMFR();
    });

    function calculateMFR() {
        var mfr_kutipan = Number($("[id=income_total_income_1]").val());
        $('#{{ $prefix }}kutipan').text(toCommas(parseFloat(mfr_kutipan).toFixed(2)));

        var mfr_total_income = Number($("[id=income_semasa_1]").val());
        $('#{{ $prefix }}total_income').text(toCommas(parseFloat(mfr_total_income).toFixed(2)));

        var mfr_tunggakan_belum_dikutip = Number($("[id={{ $prefix }}tunggakan_belum_dikutip]").val());

        var mfr_bayar = document.getElementsByName("{{ $prefix }}amount[]");
        var mfr_bayar_total = 0;
        for (var i = 0; i < mfr_bayar.length; i++) {
            mfr_bayar_total += Number(mfr_bayar[i].value);
        }
        $('#{{ $prefix }}bayar_total').text(toCommas(parseFloat(mfr_bayar_total).toFixed(2)));

        var mfr_lebihan_kurangan = (Number(mfr_kutipan) - Number(mfr_bayar_total)) - Number(mfr_tunggakan_belum_dikutip);
        $('#{{ $prefix }}lebihan_kurangan').text(toCommas(parseFloat(mfr_lebihan_kurangan).toFixed(2)));
    }
</script>