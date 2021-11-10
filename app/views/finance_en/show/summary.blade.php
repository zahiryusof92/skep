<?php
$old_prefix = 'sum_old_';
?>
<div class="row">
    <div class="col-lg-12">

        <h6><u>{{ trans("app.forms.before_change") }}</u></h6>
        <div class="row">
            <table class="table table-sm borderless" style="font-size: 12px;" style="width: 100%">
                <tbody>
                    <?php
                    $no = 1;
                    $total_all = 0;
                    ?>
                    @foreach ($summaryOld as $item)
                    <tr>
                        <td width="5%" class="padding-table text-center">{{ $no }}</td>
                        <td width="80%" class="padding-table">{{ $item->name }}</td>
                        <td width="15%"><input type="text" class="form-control form-control-sm text-right" name="{{$old_prefix}}-{{$no}}-summary" value="{{$item->amount}}" readonly=""></td>
                        <?php $total_all += $item->amount; ?>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    <tr>
                        <td>&nbsp;</td>
                        <th class="padding-table">JUMLAH PERBELANJAAN</th>
                        <th><input type="text" class="form-control form-control-sm text-right" id="{{$old_prefix}}jumlah_pembelanjaan" value="{{ number_format($total_all, 2, '.', '') }}" readonly=""></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr>