<?php
$prefix = 'income_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>3. LAPORAN PENDAPATAN</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th width="5%" class="text-center">BIL</th>
                    <th width="55%" class="text-center">PENDAPATAN</th>
                    <th width="10%" class="text-center">TUNGGAKAN<br/>B</th>
                    <th width="10%" class="text-center">SEMASA<br/>A</th>
                    <th width="10%" class="text-center">ADVANCED<br/>C</th>
                    <th width="10%" class="text-center">JUMLAH<br/>A + B + C</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 0;
                $total_tunggakan = 0;
                $total_semasa = 0;
                $total_hadapan = 0;
                $total_all = 0;
                ?>

                @foreach ($incomeFile as $incomeFiles)
                <?php
                $total_tunggakan += $incomeFiles['tunggakan'];
                $total_semasa += $incomeFiles['semasa'];
                $total_hadapan += $incomeFiles['hadapan'];
                $total_income = $incomeFiles['tunggakan'] + $incomeFiles['semasa'] + $incomeFiles['hadapan'];
                $total_all += $total_income;
                ?>
                <tr id="income_row{{ ++$count }}">
                    <td class="text-center">
                        {{ $count }}
                        <input id="income_total_income_{{$count}}" value="{{ $total_income }}" hidden >
                        <input id="income_semasa_{{$count}}" value="{{ $incomeFiles['semasa'] }}" hidden >
                    </td>
                    <td class="text-left">{{ $incomeFiles['name'] }}</td>
                    <td class="text-right">{{ number_format($incomeFiles['tunggakan'], 2) }}</td>
                    <td class="text-right">{{ number_format($incomeFiles['semasa'], 2) }}</td>
                    <td class="text-right">{{ number_format($incomeFiles['hadapan'], 2) }}</td>
                    <td class="text-right">{{ number_format($total_income, 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH</th>
                    <th class="text-right">{{ number_format($total_tunggakan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_semasa, 2) }}</th>
                    <th class="text-right">{{ number_format($total_hadapan, 2) }}</th>
                    <th class="text-right">{{ number_format($total_all, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>