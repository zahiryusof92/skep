<?php
$prefix = 'sum_';
?>

<div class="row">
    <div class="col-lg-12">

        <h6>{{ trans("app.forms.summary") }}</h6>

            <div class="row">
                <table class="table table-sm" style="font-size: 12px;" style="width: 100%">
                    <tbody>
                        <?php
                        $no = 1;
                        $total_all = 0;
                        ?>
                        @foreach ($summary as $summaries)
                        <?php $total_all += $summaries['amount']; ?>
                        <tr>
                            <td width="5%" class="padding-table text-center"><input type="hidden" name="{{ $prefix }}summary_key[]" value="{{ $summaries->summary_key }}">{{ $no }}</td>
                            <td width="80%" class="padding-table"><input type="hidden" name="{{ $prefix }}name[]" value="{{ $summaries->name }}">{{ $summaries->name }}</td>
                            <td width="15%"><input type="number" step="0.01" oninput="calculateSummaryTotal()" class="form-control form-control-sm text-right" id="{{$prefix.$summaries->summary_key}}" name="{{ $prefix }}amount[]" value="{{ $summaries->amount }}"></td>
                        </tr>
						<?php $no++; ?>
                        @endforeach
                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-table">JUMLAH PERBELANJAAN</th>
                            <th><input type="number" step="0.01" class="form-control form-control-sm text-right" id="{{ $prefix }}jumlah_pembelanjaan" value="{{ $total_all }}" readonly=""></th>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        calculateSummaryTotal();
    });

    function calculateSummaryTotal() {
        var summary_total = document.getElementsByName("{{ $prefix }}amount[]");
        var sum_total_summary = 0;
        for (var i = 0; i < summary_total.length; i++) {
            sum_total_summary += parseFloat(summary_total[i].value);
            $('#' + summary_total[i].id).val(parseFloat(summary_total[i].value).toFixed(2));
        }
        $('#{{ $prefix }}jumlah_pembelanjaan').val(parseFloat(sum_total_summary).toFixed(2));
    }
</script>
