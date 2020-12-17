<?php
$prefix = 'mfr_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>1. LAPORAN RINGKAS PENCAPAIAN KUTIPAN CAJ PENYENGGARAAN (MAINTENANCE FEE)</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-left" width="35%">MAINTENANCE FEE SEBULAN (PER UNIT)</td>
                    <td class="text-right" width="15%">{{ $mfreport['fee_sebulan'] }}</td>
                    <td class="text-left" width="35%">JUMLAH UNIT</td>
                    <td class="text-right" width="15%">{{ $mfreport['unit'] }}</td>
                </tr>
                <tr>
                    <td class="text-left">JUMLAH DIKUTIP (TUNGGAKAN + SEMASA + ADVANCED [A])</td>
                    <td class="text-right"></td>
                    <td class="text-left">JUMLAH SERVICE FEE SEPATUT DIKUTIP SEMASA</td>
                    <td class="text-right">{{ number_format($mfreport['fee_semasa'], 2) }}</td>
                </tr>
                <tr>
                    <th colspan="2"></th>
                    <th class="text-left">JUMLAH SERVICE FEE BERJAYA DIKUTIP SEMASA</th>
                    <th class="text-right"></th>
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
                @for($i=0 ; $i < count($reportMF) ; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-left">{{ $reportMF[$i]['name'] }}</td>
                    <td class="text-right">{{ number_format($reportMF[$i]['amount'], 2) }}</td>
                </tr>
                @endfor
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-left">JUMLAH TELAH BAYAR [B]</td>
                    <td class="text-right"></td>
                </tr>

                <tr>
                    <td class="text-left" colspan="2">LEBIHAN / KURANGAN PENDAPATAN (A) - (B)</td>
                    <td class="text-right"></td>
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
