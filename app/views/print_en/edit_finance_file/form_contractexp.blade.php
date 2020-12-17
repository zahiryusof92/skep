<?php
$prefix = 'contract_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>4.2 LAPORAN PERBELANJAAN PENYENGGARAAN</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th width="5%">&nbsp;</th>
                    <th width="45%" style="text-align: center;">PERKARA</th>
                    <th width="10%" style="text-align: center;">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>A</th>
                    <th width="10%" style="text-align: center;">BULAN SEMASA<br/>B</th>
                    <th width="10%" style="text-align: center;">BULAN HADAPAN<br/>C</th>
                    <th width="10%" style="text-align: center;">JUMLAH<br/>A + B + C</th>
                    <th width="10%" style="text-align: center;">JUMLAH BAKI BAYARAN MASIH TERTUNGGAK<br/>(BELUM BAYAR)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 0;
                $total_tunggakan = 0;
                $total_semasa = 0;
                $total_hadapan = 0;
                $total_tertunggak = 0;
                $total_all = 0;
                ?>

                @foreach ($contractFile as $contractFiles)
                <?php
                $total_tunggakan += $contractFiles['tunggakan'];
                $total_semasa += $contractFiles['semasa'];
                $total_hadapan += $contractFiles['hadapan'];
                $total_tertunggak += $contractFiles['tertunggak'];
                $total_income = $contractFiles['tunggakan'] + $contractFiles['semasa'] + $contractFiles['hadapan'];
                $total_all += $total_income;
                ?>
                <tr id="contract_row{{ ++$count }}">
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-left">{{ $contractFiles['name'] }}</td>
                    <td class="text-right">{{ number_format($contractFiles['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($contractFiles['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($contractFiles['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_income, 2) }}</td>
                    <td class="text-right">{{ number_format($contractFiles['tertunggak'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH</th>
                    <th class="text-right">{{ number_format($total_tunggakan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_semasa, 2) }}</th>
                    <th class="text-right">{{ number_format($total_hadapan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_all, 2) }}</th>
                    <th class="text-right">{{ number_format($total_tertunggak, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>