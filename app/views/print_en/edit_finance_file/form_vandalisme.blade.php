<?php
$prefix = 'maintenancefee_';
$prefix2 = 'singkingfund_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>4.4 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN (VANDALISME)</h6>

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

                @foreach ($vandala as $vandalas)
                <?php
                $total_tunggakan += $vandalas['tunggakan'];
                $total_semasa += $vandalas['semasa'];
                $total_hadapan += $vandalas['hadapan'];
                $total_tertunggak += $vandalas['tertunggak'];
                $total_income = $vandalas['tunggakan'] + $vandalas['semasa'] + $vandalas['hadapan'];
                $total_all += $total_income;
                ?>
                <tr id="vandala_row{{ ++$count }}">
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-left">{{ $vandalas['name'] }}</td>
                    <td class="text-right">{{ number_format($vandalas['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($vandalas['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($vandalas['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_income, 2) }}</td>
                    <td class="text-right">{{ number_format($vandalas['tertunggak'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="padding-form">JUMLAH</th>
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

                @foreach ($vandalb as $vandalbs)
                <?php
                $totalb_tunggakan += $vandalbs['tunggakan'];
                $totalb_semasa += $vandalbs['semasa'];
                $totalb_hadapan += $vandalbs['hadapan'];
                $totalb_tertunggak += $vandalbs['tertunggak'];
                $totalb_income = $vandalbs['tunggakan'] + $vandalbs['semasa'] + $vandalbs['hadapan'];
                $totalb_all += $totalb_income;
                ?>
                <tr id="vandalb_row{{ ++$countb }}">
                    <td class="text-center">{{ $countb }}</td>
                    <td class="text-left">{{ $vandalbs['name'] }}</td>
                    <td class="text-right">{{ number_format($vandalbs['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($vandalbs['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($vandalbs['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($totalb_income, 2) }}</td>
                    <td class="text-right">{{ number_format($vandalbs['tertunggak'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH</th>
                    <th class="text-right">{{ number_format($totalb_tunggakan, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_semasa, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_hadapan, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_all, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_tertunggak, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>