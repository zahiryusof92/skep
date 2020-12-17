<?php
$prefix = 'repair_maintenancefee_';
$prefix2 = 'repair_singkingfund_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>4.3 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN</h6>

        <h6>a) Guna Duit Maintenance Fee</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th width="5%">&nbsp;</th>
                    <th width="45%" class="text-center">PERKARA</th>
                    <th width="10%" class="text-center">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>A</th>
                    <th width="10%" class="text-center">BULAN SEMASA<br/>B</th>
                    <th width="10%" class="text-center">BULAN HADAPAN<br/>C</th>
                    <th width="10%" class="text-center">JUMLAH<br/>A + B + C</th>
                    <th width="10%" class="text-center">JUMLAH BAKI BAYARAN MASIH TERTUNGGAK<br/>(BELUM BAYAR)</th>
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

                @foreach ($repaira as $repairas)
                <?php
                $total_tunggakan += $repairas['tunggakan'];
                $total_semasa += $repairas['semasa'];
                $total_hadapan += $repairas['hadapan'];
                $total_tertunggak += $repairas['tertunggak'];
                $total_income = $repairas['tunggakan'] + $repairas['semasa'] + $repairas['hadapan'];
                $total_all += $total_income;
                ?>
                <tr id="repaira_row{{ ++$count }}">
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-left">{{ $repairas['name'] }}</td>
                    <td class="text-right">{{ number_format($repairas['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($repairas['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($repairas['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_income, 2) }}</td>
                    <td class="text-right">{{ number_format($repairas['tertunggak'], 2) }}</td>
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

        <h6>b) Guna Duit Sinking Fund</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th width="5%">&nbsp;</th>
                    <th width="45%" class="text-center">PERKARA</th>
                    <th width="10%" class="text-center">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>A</th>
                    <th width="10%" class="text-center">BULAN SEMASA<br/>B</th>
                    <th width="10%" class="text-center">BULAN HADAPAN<br/>C</th>
                    <th width="10%" class="text-center">JUMLAH<br/>A + B + C</th>
                    <th width="10%" class="text-center">JUMLAH BAKI BAYARAN MASIH TERTUNGGAK<br/>(BELUM BAYAR)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $countb = 0;
                $totalb_tunggakan = 0;
                $totalb_semasa = 0;
                $totalb_hadapan = 0;
                $totalb_tertunggak = 0;
                $totalb_all = 0;
                ?>

                @foreach ($repairb as $repairbs)
                <?php
                $totalb_tunggakan += $repairbs['tunggakan'];
                $totalb_semasa += $repairbs['semasa'];
                $totalb_hadapan += $repairbs['hadapan'];
                $totalb_tertunggak += $repairbs['tertunggak'];
                $totalb_income = $repairbs['tunggakan'] + $repairbs['semasa'] + $repairbs['hadapan'];
                $totalb_all += $totalb_income;
                ?>
                <tr id="repairb_row{{ ++$countb }}">
                    <td class="text-center">{{ $countb }}</td>
                    <td class="text-left">{{ $repairbs['name'] }}</td>
                    <td class="text-right">{{ number_format($repairbs['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($repairbs['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($repairbs['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($totalb_income, 2) }}</td>
                    <td class="text-right">{{ number_format($repairbs['tertunggak'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH</th>
                    <th class="text-right">{{ number_format($totalb_tunggakan, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_semasa, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_hadapan, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_income, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_tertunggak, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>