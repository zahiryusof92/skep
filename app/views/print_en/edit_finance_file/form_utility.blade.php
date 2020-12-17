<?php
$prefix = 'util_';
$prefix2 = 'utilb_';
$prefix3 = 'utilab_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>4.1 LAPORAN PERBELANJAAN UTILITI</h6>
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
                <tr>
                    <th colspan="7">BAHAGIAN A</th>
                </tr>

                <?php
                $count = 0;
                $total_tunggakan = 0;
                $total_semasa = 0;
                $total_hadapan = 0;
                $total_tertunggak = 0;
                $total_all = 0;
                ?>

                @foreach ($utila as $utilas)
                <?php
                $total_tunggakan += $utilas['tunggakan'];
                $total_semasa += $utilas['semasa'];
                $total_hadapan += $utilas['hadapan'];
                $total_tertunggak += $utilas['tertunggak'];
                $total_income = $utilas['tunggakan'] + $utilas['semasa'] + $utilas['hadapan'];
                $total_all += $total_income;
                ?>
                <tr id="util_row{{ ++$count }}">
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-left">{{ $utilas['name'] }}</td>
                    <td class="text-right">{{ number_format($utilas['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($utilas['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($utilas['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_income, 2) }}</td>
                    <td class="text-right">{{ number_format($utilas['tertunggak'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH BAHAGIAN A</th>
                    <th class="text-right">{{ number_format($total_tunggakan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_semasa, 2) }}</th>
                    <th class="text-right">{{ number_format($total_hadapan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_all, 2) }}</th>
                    <th class="text-right">{{ number_format($total_tertunggak, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="8">BAHAGIAN B</th>
                </tr>

                <?php
                $countb = 0;
                $totalb_tunggakan = 0;
                $totalb_semasa = 0;
                $totalb_hadapan = 0;
                $totalb_tertunggak = 0;
                $totalb_all = 0;
                ?>

                @foreach ($utilb as $utilbs)
                <?php
                $totalb_tunggakan += $utilbs['tunggakan'];
                $totalb_semasa += $utilbs['semasa'];
                $totalb_hadapan += $utilbs['hadapan'];
                $totalb_tertunggak += $utilbs['tertunggak'];
                $totalb_income = $utilbs['tunggakan'] + $utilbs['semasa'] + $utilbs['hadapan'];
                $totalb_all += $totalb_income;
                ?>

                <tr id="utility_row{{ ++$countb }}">
                    <td class="text-center">{{ $countb }}</td>
                    <td class="text-left">{{ $utilbs['name'] }}</td>
                    <td class="text-right">{{ number_format($utilbs['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($utilbs['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($utilbs['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($totalb_income, 2) }}</td>
                    <td class="text-right">{{ number_format($utilbs['tertunggak'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH BAHAGIAN B</th>
                    <th class="text-right">{{ number_format($totalb_tunggakan, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_semasa, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_hadapan, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_income, 2) }}</th>
                    <th class="text-right">{{ number_format($totalb_tertunggak, 2) }}</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH BAHAGIAN A + BAHAGIAN B</th>
                    <th class="text-right">{{ number_format($total_tunggakan + $totalb_tunggakan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_semasa + $totalb_semasa, 2) }}</th>
                    <th class="text-right">{{ number_format($total_hadapan + $totalb_hadapan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_income + $totalb_income, 2) }}</th>
                    <th class="text-right">{{ number_format($total_tertunggak + $totalb_tertunggak, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
