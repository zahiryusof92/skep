<?php
$prefix = 'sum_';
?>

<div class="row">
    <div class="col-lg-12">
        <h6>{{ strtoupper(trans("app.forms.summary")) }}</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th class="text-center" width="5%">BIL</th>
                    <th class="text-center" width="80%">PERKARA</th>
                    <th class="text-center" width="15%">JUMLAH (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total_all = 0;
                ?>
                @foreach ($summary as $summaries)
                <?php $total_all += $summaries['amount']; ?>
                <tr>
                    <td class="text-center">{{ $no }}</td>
                    <td class="text-left">{{ $summaries->name }}</td>
                    <td class="text-right">{{ number_format($summaries->amount, 2) }}</td>
                </tr>
                <?php $no++; ?>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <th class="text-left">JUMLAH PERBELANJAAN</th>
                    <th class="text-right">{{ number_format($total_all, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>