<?php
$prefix = 'admin_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>4.6 LAPORAN PERBELANJAAN PENTADBIRAN</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th width="5%">&nbsp;</th>
                    <th width="45%" class="text-center">PERKARA</th>
                    <th width="10%" class="text-center">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>C</th>
                    <th width="10%" class="text-center">BULAN SEMASA<br/>D</th>
                    <th width="10%" class="text-center">BULAN HADAPAN<br/>E</th>
                    <th width="10%" class="text-center">JUMLAH<br/>C + D + E</th>
                    <th width="10%" class="text-center">JUMLAH<br/>BAKI BAYARAN MASIH TERTUNGGAK<br/>(BELUM BAYAR)</th>
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

                @foreach ($adminFile as $adminFiles)
                <?php
                $total_tunggakan += $adminFiles['tunggakan'];
                $total_semasa += $adminFiles['semasa'];
                $total_hadapan += $adminFiles['hadapan'];
                $total_tertunggak += $adminFiles['tertunggak'];
                $total_income = $adminFiles['tunggakan'] + $adminFiles['semasa'] + $adminFiles['hadapan'];
                $total_all += $total_income;
                ?>

                <tr id="admin_row{{ ++$count }}">
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-left">{{ $adminFiles['name'] }}</td>
                    <td class="text-right">{{ number_format($adminFiles['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($adminFiles['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($adminFiles['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_income, 2) }}</td>
                    <td class="text-right">{{ number_format($adminFiles['tertunggak'], 2) }}</td>
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