<?php
$prefix = 'repair_maintenancefee_';
$prefix2 = 'repair_singkingfund_';
?>

<div class="row">
    <div class="col-lg-12">

        <h6>4.3 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN a. Guna Duit Maintenance Fee</h6>

        <div class="row">
            <table class="table table-sm" id="dynamic_form_repair_a" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th width="5%">&nbsp;</th>
                        <th width="40%" style="text-align: center;">PERKARA</th>
                        <th width="10%" style="text-align: center;">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>A</th>
                        <th width="10%" style="text-align: center;">BULAN SEMASA<br/>B</th>
                        <th width="10%" style="text-align: center;">BULAN HADAPAN<br/>C</th>
                        <th width="10%" style="text-align: center;">JUMLAH<br/>A + B + C</th>
                        <th width="10%" style="text-align: center;">JUMLAH<br/>BAKI BAYARAN MASIH TERTUNGGAK<br/>(BELUM BAYAR)</th>
                        <td width="5%" style="text-align: center;">&nbsp;</td>
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
                        <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $repairas['is_custom'] }}">{{ $count }}</td>
                        <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $repairas['name'] }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateRepairA('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairas['tunggakan'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateRepairA('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairas['semasa'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateRepairA('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairas['hadapan'] }}"></td>
                        <td><input type="number" step="any" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $total_income }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateRepairATotal('{{ $count }}')" id="{{ $prefix . 'tertunggak_' . $count }}" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairas['tertunggak'] }}"></td>
                        @if ($repairas['is_custom'])
                        <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowRepairA('repaira_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                        @else
                        <td>&nbsp;</td>
                        @endif
                    </tr>
                    @endforeach

                    <tr>
                        <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowRepairA()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <th class="padding-form">JUMLAH</th>
                        <th><input type="number" step="any" id="{{ $prefix . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $total_tunggakan }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $total_semasa }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $total_hadapan }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $total_all }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $total_tertunggak }}" readonly=""></th>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr/>

        <h6>4.3 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN b. Guna Duit Sinking Fund</h6>

        <div class="row">
            <table class="table table-sm" id="dynamic_form_repair_b" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th width="5%">&nbsp;</th>
                        <th width="40%" style="text-align: center;">PERKARA</th>
                        <th width="10%" style="text-align: center;">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>A</th>
                        <th width="10%" style="text-align: center;">BULAN SEMASA<br/>B</th>
                        <th width="10%" style="text-align: center;">BULAN HADAPAN<br/>C</th>
                        <th width="10%" style="text-align: center;">JUMLAH<br/>A + B + C</th>
                        <th width="10%" style="text-align: center;">JUMLAH<br/>BAKI BAYARAN MASIH TERTUNGGAK<br/>(BELUM BAYAR)</th>
                        <td width="5%" style="text-align: center;">&nbsp;</td>
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
                        <td class="text-center padding-table"><input type="hidden" name="{{ $prefix2 }}is_custom[]" value="{{ $repairbs['is_custom'] }}">{{ $countb }}</td>
                        <td><input type="text" name="{{ $prefix2 }}name[]" class="form-control form-control-sm" value="{{ $repairbs['name'] }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateRepairB('{{ $countb }}')" id="{{ $prefix2 . 'tunggakan_' . $countb }}" name="{{ $prefix2 }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairbs['tunggakan'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateRepairB('{{ $countb }}')" id="{{ $prefix2 . 'semasa_' . $countb }}" name="{{ $prefix2 }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairbs['semasa'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateRepairB('{{ $countb }}')" id="{{ $prefix2 . 'hadapan_' . $countb }}" name="{{ $prefix2 }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairbs['hadapan'] }}"></td>
                        <td><input type="number" step="any" id="{{ $prefix2 . 'total_income_' . $countb }}" name="{{ $prefix2 }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $totalb_income }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateRepairBTotal('{{ $countb }}')" id="{{ $prefix2 . 'tertunggak_' . $countb }}" name="{{ $prefix2 }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="{{ $repairbs['tertunggak'] }}"></td>
                        @if ($repairbs['is_custom'])
                        <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowRepairB('repaira_row<?php echo $countb ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                        @else
                        <td>&nbsp;</td>
                        @endif
                    </tr>
                    @endforeach

                    <tr>
                        <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowRepairB()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <th class="padding-form">JUMLAH</th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_tunggakan }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $totalb_semasa }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_hadapan }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $totalb_income }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $totalb_tertunggak }}" readonly=""></th>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        calculateRepairATotal();
        calculateRepairBTotal();
    });

    function calculateRepairA(id) {
        var repaira_sum_tunggakan = 0;
        var repaira_sum_semasa = 0;
        var repaira_sum_hadapan = 0;
        var repaira_sum_total_income = 0;

        var repaira_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        repaira_sum_tunggakan += parseFloat(repaira_tunggakan.value);

        var repaira_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        repaira_sum_semasa += parseFloat(repaira_semasa.value);

        var repaira_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        repaira_sum_hadapan += parseFloat(repaira_hadapan.value);

        repaira_sum_total_income += parseFloat(repaira_sum_tunggakan) + parseFloat(repaira_sum_semasa) + parseFloat(repaira_sum_hadapan);
        $('#{{ $prefix }}total_income_' + id).val(parseFloat(repaira_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateRepairATotal();
    }

    function calculateRepairATotal() {
        var repaira_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var repaira_sum_total_tunggakan = 0;
        for (var i = 0; i < repaira_total_tunggakan.length; i++) {
            repaira_sum_total_tunggakan += parseFloat(repaira_total_tunggakan[i].value);
            $('#' + repaira_total_tunggakan[i].id).val(parseFloat(repaira_total_tunggakan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_tunggakan').val(parseFloat(repaira_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var repaira_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var repaira_sum_total_semasa = 0;
        for (var i = 0; i < repaira_total_semasa.length; i++) {
            repaira_sum_total_semasa += parseFloat(repaira_total_semasa[i].value);
            $('#' + repaira_total_semasa[i].id).val(parseFloat(repaira_total_semasa[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_semasa').val(parseFloat(repaira_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var repaira_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var repaira_sum_total_hadapan = 0;
        for (var i = 0; i < repaira_total_hadapan.length; i++) {
            repaira_sum_total_hadapan += parseFloat(repaira_total_hadapan[i].value);
            $('#' + repaira_total_hadapan[i].id).val(parseFloat(repaira_total_hadapan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_hadapan').val(parseFloat(repaira_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var repaira_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < repaira_total_income.length; i++) {
            $('#' + repaira_total_income[i].id).val(parseFloat(repaira_total_income[i].value).toFixed(2));
        }

        var repaira_total_tertunggak = document.getElementsByName("{{ $prefix }}tertunggak[]");
        var repaira_sum_total_tertunggak = 0;
        for (var i = 0; i < repaira_total_tertunggak.length; i++) {
            repaira_sum_total_tertunggak += parseFloat(repaira_total_tertunggak[i].value);
            $('#' + repaira_total_tertunggak[i].id).val(parseFloat(repaira_total_tertunggak[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_tertunggak').val(parseFloat(repaira_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var repaira_sum_total_all = parseFloat(repaira_sum_total_tunggakan) + parseFloat(repaira_sum_total_semasa) + parseFloat(repaira_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix }}total_all').val(parseFloat(repaira_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function calculateRepairB(id) {
        var repairb_sum_tunggakan = 0;
        var repairb_sum_semasa = 0;
        var repairb_sum_hadapan = 0;
        var repairb_sum_total_income = 0;

        var repairb_tunggakan = document.getElementById("{{ $prefix2 }}tunggakan_" + id);
        repairb_sum_tunggakan += parseFloat(repairb_tunggakan.value);

        var repairb_semasa = document.getElementById("{{ $prefix2 }}semasa_" + id);
        repairb_sum_semasa += parseFloat(repairb_semasa.value);

        var repairb_hadapan = document.getElementById("{{ $prefix2 }}hadapan_" + id);
        repairb_sum_hadapan += parseFloat(repairb_hadapan.value);

        repairb_sum_total_income += parseFloat(repairb_sum_tunggakan) + parseFloat(repairb_sum_semasa) + parseFloat(repairb_sum_hadapan);
        $('#{{ $prefix2 }}total_income_' + id).val(parseFloat(repairb_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateRepairBTotal();
    }

    function calculateRepairBTotal() {
        var repairb_total_tunggakan = document.getElementsByName("{{ $prefix2 }}tunggakan[]");
        var repairb_sum_total_tunggakan = 0;
        for (var i = 0; i < repairb_total_tunggakan.length; i++) {
            repairb_sum_total_tunggakan += parseFloat(repairb_total_tunggakan[i].value);
            $('#' + repairb_total_tunggakan[i].id).val(parseFloat(repairb_total_tunggakan[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_tunggakan').val(parseFloat(repairb_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var repairb_total_semasa = document.getElementsByName("{{ $prefix2 }}semasa[]");
        var repairb_sum_total_semasa = 0;
        for (var i = 0; i < repairb_total_semasa.length; i++) {
            repairb_sum_total_semasa += parseFloat(repairb_total_semasa[i].value);
            $('#' + repairb_total_semasa[i].id).val(parseFloat(repairb_total_semasa[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_semasa').val(parseFloat(repairb_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var repairb_total_hadapan = document.getElementsByName("{{ $prefix2 }}hadapan[]");
        var repairb_sum_total_hadapan = 0;
        for (var i = 0; i < repairb_total_hadapan.length; i++) {
            repairb_sum_total_hadapan += parseFloat(repairb_total_hadapan[i].value);
            $('#' + repairb_total_hadapan[i].id).val(parseFloat(repairb_total_hadapan[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_hadapan').val(parseFloat(repairb_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var repairb_total_income = document.getElementsByName("{{ $prefix2 }}total_income[]");
        for (var i = 0; i < repairb_total_income.length; i++) {
            $('#' + repairb_total_income[i].id).val(parseFloat(repairb_total_income[i].value).toFixed(2));
        }

        var repairb_total_tertunggak = document.getElementsByName("{{ $prefix2 }}tertunggak[]");
        var repairb_sum_total_tertunggak = 0;
        for (var i = 0; i < repairb_total_tertunggak.length; i++) {
            repairb_sum_total_tertunggak += parseFloat(repairb_total_tertunggak[i].value);
            $('#' + repairb_total_tertunggak[i].id).val(parseFloat(repairb_total_tertunggak[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_tertunggak').val(parseFloat(repairb_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var repairb_sum_total_all = parseFloat(repairb_sum_total_tunggakan) + parseFloat(repairb_sum_total_semasa) + parseFloat(repairb_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix2 }}total_all').val(parseFloat(repairb_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function addRowRepairA() {
        var rowRepairANo = $("#dynamic_form_repair_a tr").length;
        rowRepairANo = rowRepairANo - 2;
        $("#dynamic_form_repair_a tr:last").prev().prev().after('<tr id="repaira_row' + rowRepairANo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="1">' + rowRepairANo + '</td><td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value=""></td><td><input type="number" step="any" oninput="calculateRepairA(\'' + rowRepairANo + '\')" id="{{ $prefix }}tunggakan_' + rowRepairANo + '" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateRepairA(\'' + rowRepairANo + '\')" id="{{ $prefix }}semasa_' + rowRepairANo + '" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateRepairA(\'' + rowRepairANo + '\')" id="{{ $prefix }}hadapan_' + rowRepairANo + '" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" id="{{ $prefix }}total_income_' + rowRepairANo + '" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="0" readonly=""></td><td><input type="number" step="any" oninput="calculateRepairATotal(\'' + rowRepairANo + '\')" id="{{ $prefix }}tertunggak_' + rowRepairANo + '" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowRepairA(\'repaira_row' + rowRepairANo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateRepairATotal();
    }

    function deleteRowRepairA(rowRepairANo) {
        $('#' + rowRepairANo).remove();

        calculateRepairATotal();
    }

    function addRowRepairB() {
        var rowRepairBNo = $("#dynamic_form_repair_b tr").length;
        rowRepairBNo = rowRepairBNo - 2;
        $("#dynamic_form_repair_b tr:last").prev().prev().after('<tr id="repairb_row' + rowRepairBNo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix2 }}is_custom[]" value="1">' + rowRepairBNo + '</td><td><input type="text" name="{{ $prefix2 }}name[]" class="form-control form-control-sm" value=""></td><td><input type="number" step="any" oninput="calculateRepairB(\'' + rowRepairBNo + '\')" id="{{ $prefix2 }}tunggakan_' + rowRepairBNo + '" name="{{ $prefix2 }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateRepairB(\'' + rowRepairBNo + '\')" id="{{ $prefix2 }}semasa_' + rowRepairBNo + '" name="{{ $prefix2 }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateRepairB(\'' + rowRepairBNo + '\')" id="{{ $prefix2 }}hadapan_' + rowRepairBNo + '" name="{{ $prefix2 }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" id="{{ $prefix2 }}total_income_' + rowRepairBNo + '" name="{{ $prefix2 }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="0" readonly=""></td><td><input type="number" step="any" oninput="calculateRepairBTotal(\'' + rowRepairBNo + '\')" id="{{ $prefix2 }}tertunggak_' + rowRepairBNo + '" name="{{ $prefix2 }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowRepairB(\'repairb_row' + rowRepairBNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateRepairBTotal();
    }

    function deleteRowRepairB(rowRepairBNo) {
        $('#' + rowRepairBNo).remove();

        calculateRepairBTotal();
    }
</script>
