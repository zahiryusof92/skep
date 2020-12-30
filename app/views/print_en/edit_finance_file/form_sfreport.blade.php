<?php
$prefix = 'sfr_';

$count = 0;
?>

<div class="row">
    <div class="col-lg-12">
        <h6>2. LAPORAN RINGKAS PENCAPAIAN KUTIPAN KUMPULAN WANG PENJELAS (SINGKING FUND)</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-left" width="35%">SINKING FUND SEBULAN (PER UNIT)</td>
                    <td class="text-right" width="15%">{{ $sfreport['fee_sebulan'] }}</td>
                    <td class="text-left" width="35%">JUMLAH UNIT</td>
                    <td class="text-right" width="15%">{{ $sfreport['unit'] }}</td>
                </tr>
                <tr>
                    <td class="text-left">JUMLAH DIKUTIP (TUNGGAKAN + SEMASA + ADVANCED [A])</td>
                    <td class="text-right"></td>
                    <td class="text-left">JUMLAH SINKING FUND SEPATUT DIKUTIP SEMASA</td>
                    <td class="text-right">{{ number_format($sfreport['fee_semasa'], 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <th class="padding-table">JUMLAH SINKING FUND BERJAYA DIKUTIP SEMASA</th>
                    <th></th>
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
                    <td class="text-right">{{ number_format($reportSFs['amount'], 2) }}</td>
                </tr>
                @endforeach
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
                    <td class="text-left"width="15%">{{ $sfreport['no_akaun'] }}</td>
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