<?php
$prefix = 'staff_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>4.5 LAPORAN PERBELANJAAN PEKERJA</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th width="5%">&nbsp;</th>
                    <th width="15%" class="text-center">PERKARA</th>
                    <th width="10%" class="text-center">GAJI PERORANG (RM)<br/>A</th>
                    <th width="10%" class="text-center">BIL. PEKERJA<br/>B</th>
                    <th width="10%" class="text-center">JUMLAH GAJI<br/>A x B</th>
                    <th width="10%" class="text-center">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>C</th>
                    <th width="10%" class="text-center">BULAN SEMASA<br/>D</th>
                    <th width="10%" class="text-center">BULAN HADAPAN<br/>E</th>
                    <th width="10%" class="text-center">JUMLAH<br/>C + D + E</th>
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

                @foreach ($staffFile as $staffFiles)
                <?php
                $gaji_per_person = $staffFiles['gaji_per_orang'];
                $bil_pekerja = $staffFiles['bil_pekerja'];
                $total_gaji = $staffFiles['gaji_per_orang'] * $staffFiles['bil_pekerja'];
                $total_tunggakan += $staffFiles['tunggakan'];
                $total_semasa += $staffFiles['semasa'];
                $total_hadapan += $staffFiles['hadapan'];
                $total_tertunggak += $staffFiles['tertunggak'];
                $total_income = $staffFiles['tunggakan'] + $staffFiles['semasa'] + $staffFiles['hadapan'];
                $total_all += $total_income;
                ?>

                <tr id="staff_row{{ ++$count }}">
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-left">{{ $staffFiles['name'] }}</td>
                    <td class="text-right">{{ number_format($staffFiles['gaji_per_orang'], 2) }}</td>
                    <td class="text-right">{{ number_format($staffFiles['bil_pekerja'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_gaji, 2) }}</td>
                    <td class="text-right">{{ number_format($staffFiles['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($staffFiles['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($staffFiles['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_income, 2) }}</td>
                    <td class="text-right">{{ number_format($staffFiles['tertunggak'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left" colspan="3">JUMLAH</th>
                    <th class="text-right">{{ number_format($total_gaji, 2) }}</th>
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