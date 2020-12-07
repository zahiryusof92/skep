<?php
$prefix = 'sum_';
?>

<div class="row">
    <div class="col-lg-12">

        <h6>{{ trans("app.forms.summary") }}</h6>

        <form id="form_summary">

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

            <?php if ($update_permission == 1) { ?>
                <div class="form-actions">
                    <input type="hidden" name="finance_file_id" value="{{ $financefiledata->id }}"/>
                    <input type="submit" value="{{ trans("app.forms.submit") }}" class="btn btn-primary" id="submit_button">
                    <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                </div>
            <?php } ?>

        </form>

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
    
    $(function () {
        $("#form_summary").submit(function (e) {
            e.preventDefault();
            changes = false;

            var data = $(this).serialize();

            $(".loading").css("display", "inline-block");
            $(".submit_button").attr("disabled", "disabled");
            $("#check_mandatory_fields").css("display", "none");

            var error = 0;

            if (error == 0) {
                $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

                $.ajax({
                    method: "POST",
                    url: "{{ URL::action('FinanceController@updateFinanceFileSummary') }}",
                    data: data,
                    success: function (response) {
                        $.unblockUI();
                        $(".loading").css("display", "none");
                        $(".submit_button").removeAttr("disabled");

                        if (response.trim() == "true") {
                            bootbox.alert("<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>", function () {
                                location.reload();
                            });
                        } else {
                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    }
                });
            } else {
                $(".loading").css("display", "none");
                $(".submit_button").removeAttr("disabled");
                $("#check_mandatory_fields").css("display", "block");
            }
        });
    });
</script>
