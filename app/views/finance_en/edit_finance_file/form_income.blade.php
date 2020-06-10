<?php
$prefix = 'income_';
?>

<div class="row">
    <div class="col-lg-12">

        <h6>3. LAPORAN PENDAPATAN</h6>

            <div class="row">
                <table class="table table-sm" id="dynamic_form_income" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th width="5%">&nbsp;</th>
                            <th width="50%" style="text-align: center;">PENDAPATAN</th>
                            <th width="10%" style="text-align: center;">TUNGGAKAN<br/>B</th>
                            <th width="10%" style="text-align: center;">SEMASA<br/>A</th>
                            <th width="10%" style="text-align: center;">ADVANCED<br/>C</th>
                            <th width="10%" style="text-align: center;">JUMLAH<br/>A + B + C</th>
                            <td width="5%" style="text-align: center;">&nbsp;</td>
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
                            <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $incomeFiles['is_custom'] }}">{{ $count }}</td>
                            <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $incomeFiles['name'] }}" readonly=""></td>
                            <td><input type="number" step="any" oninput="calculateIncome('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only income_tunggakan" value="{{ $incomeFiles['tunggakan'] }}"></td>
                            <td><input type="number" step="any" oninput="calculateIncome('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $incomeFiles['semasa'] }}"></td>
                            <td><input type="number" step="any" oninput="calculateIncome('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $incomeFiles['hadapan'] }}"></td>
                            <td><input type="number" step="any" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $total_income }}" readonly=""></td>
                            @if ($incomeFiles['is_custom'])
                            <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowIncome('income_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td class="padding-table text-right" colspan="7"><a href="javascript:void(0);" onclick="addRowIncome()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH</th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $total_tunggakan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $total_semasa }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $total_hadapan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $total_all }}" readonly=""></th>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        calculateIncomeTotal();
    });

    function calculateIncome(id) {
        var income_sum_tunggakan = 0;
        var income_sum_semasa = 0;
        var income_sum_hadapan = 0;
        var income_sum_total_income = 0;

        var income_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        income_sum_tunggakan += parseFloat(income_tunggakan.value);

        var income_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        income_sum_semasa += parseFloat(income_semasa.value);

        var income_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        income_sum_hadapan += parseFloat(income_hadapan.value);

        income_sum_total_income += parseFloat(income_sum_tunggakan) + parseFloat(income_sum_semasa) + parseFloat(income_sum_hadapan);
        $('#{{ $prefix }}total_income_' + id).val(parseFloat(income_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateIncomeTotal();
        calculateMFR();
        calculateSFR();
    }

    function calculateIncomeTotal() {
        var income_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var income_sum_total_tunggakan = 0;
        for (var i = 0; i < income_total_tunggakan.length; i++) {
            income_sum_total_tunggakan += parseFloat(income_total_tunggakan[i].value);
            $('#' + income_total_tunggakan[i].id).val(parseFloat(income_total_tunggakan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_tunggakan').val(parseFloat(income_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var income_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var income_sum_total_semasa = 0;
        for (var i = 0; i < income_total_semasa.length; i++) {
            income_sum_total_semasa += parseFloat(income_total_semasa[i].value);
            $('#' + income_total_semasa[i].id).val(parseFloat(income_total_semasa[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_semasa').val(parseFloat(income_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var income_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var income_sum_total_hadapan = 0;
        for (var i = 0; i < income_total_hadapan.length; i++) {
            income_sum_total_hadapan += parseFloat(income_total_hadapan[i].value);
            $('#' + income_total_hadapan[i].id).val(parseFloat(income_total_hadapan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_hadapan').val(parseFloat(income_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var income_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < income_total_income.length; i++) {
            $('#' + income_total_income[i].id).val(parseFloat(income_total_income[i].value).toFixed(2));
        }

        var income_sum_total_all = parseFloat(income_sum_total_tunggakan) + parseFloat(income_sum_total_semasa) + parseFloat(income_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix }}total_all').val(parseFloat(income_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function addRowIncome() {
        var rowIncomeNo = $("#dynamic_form_income tr").length;
        rowIncomeNo = rowIncomeNo - 2;
        $("#dynamic_form_income tr:last").prev().prev().after('<tr id="income_row' + rowIncomeNo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="1">' + rowIncomeNo + '</td><td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value=""></td><td><input type="number" step="any" oninput="calculateIncome(\'' + rowIncomeNo + '\')" id="{{ $prefix }}tunggakan_' + rowIncomeNo + '"  name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateIncome(\'' + rowIncomeNo + '\')" id="{{ $prefix }}semasa_' + rowIncomeNo + '"  name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateIncome(\'' + rowIncomeNo + '\')" id="{{ $prefix }}hadapan_' + rowIncomeNo + '"  name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" id="{{ $prefix }}total_income_' + rowIncomeNo + '" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="0" readonly=""></td><td class="padding-table"><a href="javascript:void(0);" onclick="deleteRowIncome(\'income_row' + rowIncomeNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateIncomeTotal();
    }

    function deleteRowIncome(rowIncomeNo) {
        $('#' + rowIncomeNo).remove();

        calculateIncomeTotal();
    }
</script>
