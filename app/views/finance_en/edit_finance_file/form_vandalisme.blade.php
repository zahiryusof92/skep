<?php
$prefix = 'maintenancefee_';
$prefix2 = 'singkingfund_';
?>

<div class="row">
    <div class="col-lg-12">

        <h6>4.4 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN (VANDALISME) a. Guna Duit Maintenance Fee</h6>

        <div class="row">
            <table class="table table-sm" id="dynamic_form_vandal_a" style="font-size: 12px;">
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
                        <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $vandalas['is_custom'] }}">{{ $count }}</td>
                        <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $vandalas['name'] }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeA('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalas['tunggakan'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeA('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalas['semasa'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeA('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalas['hadapan'] }}"></td>
                        <td><input type="number" step="any" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $total_income }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeATotal('{{ $count }}')" id="{{ $prefix . 'tertunggak_' . $count }}" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalas['tertunggak'] }}"></td>
                        @if ($vandalas['is_custom'])
                        <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowVandalA('vandala_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                        @else
                        <td>&nbsp;</td>
                        @endif
                    </tr>
                    @endforeach

                    <tr>
                        <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowVandalA()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
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

        <h6>4.4 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN (VANDALISME) b. Guna Duit Sinking Fund</h6>

        <div class="row">
            <table class="table table-sm" id="dynamic_form_vandal_b" style="font-size: 12px;">
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
                        <td class="text-center padding-table"><input type="hidden" name="{{ $prefix2 }}is_custom[]" value="{{ $vandalbs['is_custom'] }}">{{ $countb }}</td>
                        <td><input type="text" name="{{ $prefix2 }}name[]" class="form-control form-control-sm" value="{{ $vandalbs['name'] }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeB('{{ $countb }}')" id="{{ $prefix2 . 'tunggakan_' . $countb }}" name="{{ $prefix2 }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalbs['tunggakan'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeB('{{ $countb }}')" id="{{ $prefix2 . 'semasa_' . $countb }}" name="{{ $prefix2 }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalbs['semasa'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeB('{{ $countb }}')" id="{{ $prefix2 . 'hadapan_' . $countb }}" name="{{ $prefix2 }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalbs['hadapan'] }}"></td>
                        <td><input type="number" step="any" id="{{ $prefix2 . 'total_income_' . $countb }}" name="{{ $prefix2 }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $totalb_income }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateVandalismeBTotal('{{ $countb }}')" id="{{ $prefix2 . 'tertunggak_' . $countb }}" name="{{ $prefix2 }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="{{ $vandalbs['tertunggak'] }}"></td>
                        @if ($vandalbs['is_custom'])
                        <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowVandalB('vandalb_row<?php echo $countb ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                        @else
                        <td>&nbsp;</td>
                        @endif
                    </tr>
                    @endforeach

                    <tr>
                        <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowVandalB()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <th class="padding-form">JUMLAH</th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_tunggakan }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $totalb_semasa }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_hadapan }}" readonly=""></th>
                        <th><input type="number" step="any" id="{{ $prefix2 . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $totalb_all }}" readonly=""></th>
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
        calculateVandalismeATotal();
        calculateVandalismeBTotal();
    });

    function calculateVandalismeA(id) {
        var vandala_sum_tunggakan = 0;
        var vandala_sum_semasa = 0;
        var vandala_sum_hadapan = 0;
        var vandala_sum_total_income = 0;

        var vandala_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        vandala_sum_tunggakan += parseFloat(vandala_tunggakan.value);

        var vandala_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        vandala_sum_semasa += parseFloat(vandala_semasa.value);

        var vandala_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        vandala_sum_hadapan += parseFloat(vandala_hadapan.value);

        vandala_sum_total_income += parseFloat(vandala_sum_tunggakan) + parseFloat(vandala_sum_semasa) + parseFloat(vandala_sum_hadapan);
        $('#{{ $prefix }}total_income_' + id).val(parseFloat(vandala_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateVandalismeATotal();
    }

    function calculateVandalismeATotal() {
        var vandala_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var vandala_sum_total_tunggakan = 0;
        for (var i = 0; i < vandala_total_tunggakan.length; i++) {
            vandala_sum_total_tunggakan += parseFloat(vandala_total_tunggakan[i].value);
            $('#' + vandala_total_tunggakan[i].id).val(parseFloat(vandala_total_tunggakan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_tunggakan').val(parseFloat(vandala_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var vandala_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var vandala_sum_total_semasa = 0;
        for (var i = 0; i < vandala_total_semasa.length; i++) {
            vandala_sum_total_semasa += parseFloat(vandala_total_semasa[i].value);
            $('#' + vandala_total_semasa[i].id).val(parseFloat(vandala_total_semasa[i].value).toFixed(2));
        }
        $('#maintenancefee_total_semasa').val(parseFloat(vandala_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var vandala_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var vandala_sum_total_hadapan = 0;
        for (var i = 0; i < vandala_total_hadapan.length; i++) {
            vandala_sum_total_hadapan += parseFloat(vandala_total_hadapan[i].value);
            $('#' + vandala_total_hadapan[i].id).val(parseFloat(vandala_total_hadapan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_hadapan').val(parseFloat(vandala_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var vandala_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < vandala_total_income.length; i++) {
            $('#' + vandala_total_income[i].id).val(parseFloat(vandala_total_income[i].value).toFixed(2));
        }

        var vandala_total_tertunggak = document.getElementsByName("{{ $prefix }}tertunggak[]");
        var vandala_sum_total_tertunggak = 0;
        for (var i = 0; i < vandala_total_tertunggak.length; i++) {
            vandala_sum_total_tertunggak += parseFloat(vandala_total_tertunggak[i].value);
            $('#' + vandala_total_tertunggak[i].id).val(parseFloat(vandala_total_tertunggak[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_tertunggak').val(parseFloat(vandala_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var vandala_sum_total_all = parseFloat(vandala_sum_total_tunggakan) + parseFloat(vandala_sum_total_semasa) + parseFloat(vandala_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix }}total_all').val(parseFloat(vandala_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function calculateVandalismeB(id) {
        var vandalb_sum_tunggakan = 0;
        var vandalb_sum_semasa = 0;
        var vandalb_sum_hadapan = 0;
        var vandalb_sum_total_income = 0;

        var vandalb_tunggakan = document.getElementById("{{ $prefix2 }}tunggakan_" + id);
        vandalb_sum_tunggakan += parseFloat(vandalb_tunggakan.value);

        var vandalb_semasa = document.getElementById("{{ $prefix2 }}semasa_" + id);
        vandalb_sum_semasa += parseFloat(vandalb_semasa.value);

        var vandalb_hadapan = document.getElementById("{{ $prefix2 }}hadapan_" + id);
        vandalb_sum_hadapan += parseFloat(vandalb_hadapan.value);

        vandalb_sum_total_income += parseFloat(vandalb_sum_tunggakan) + parseFloat(vandalb_sum_semasa) + parseFloat(vandalb_sum_hadapan);
        $('#{{ $prefix2 }}total_income_' + id).val(parseFloat(vandalb_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateVandalismeBTotal();
    }

    function calculateVandalismeBTotal() {
        var vandalb_total_tunggakan = document.getElementsByName("{{ $prefix2 }}tunggakan[]");
        var vandalb_sum_total_tunggakan = 0;
        for (var i = 0; i < vandalb_total_tunggakan.length; i++) {
            vandalb_sum_total_tunggakan += parseFloat(vandalb_total_tunggakan[i].value);
            $('#' + vandalb_total_tunggakan[i].id).val(parseFloat(vandalb_total_tunggakan[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_tunggakan').val(parseFloat(vandalb_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var vandalb_total_semasa = document.getElementsByName("{{ $prefix2 }}semasa[]");
        var vandalb_sum_total_semasa = 0;
        for (var i = 0; i < vandalb_total_semasa.length; i++) {
            vandalb_sum_total_semasa += parseFloat(vandalb_total_semasa[i].value);
            $('#' + vandalb_total_semasa[i].id).val(parseFloat(vandalb_total_semasa[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_semasa').val(parseFloat(vandalb_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var vandalb_total_hadapan = document.getElementsByName("{{ $prefix2 }}hadapan[]");
        var vandalb_sum_total_hadapan = 0;
        for (var i = 0; i < vandalb_total_hadapan.length; i++) {
            vandalb_sum_total_hadapan += parseFloat(vandalb_total_hadapan[i].value);
            $('#' + vandalb_total_hadapan[i].id).val(parseFloat(vandalb_total_hadapan[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_hadapan').val(parseFloat(vandalb_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var vandalb_total_income = document.getElementsByName("{{ $prefix2 }}total_income[]");
        for (var i = 0; i < vandalb_total_income.length; i++) {
            $('#' + vandalb_total_income[i].id).val(parseFloat(vandalb_total_income[i].value).toFixed(2));
        }

        var vandalb_total_tertunggak = document.getElementsByName("{{ $prefix2 }}tertunggak[]");
        var vandalb_sum_total_tertunggak = 0;
        for (var i = 0; i < vandalb_total_tertunggak.length; i++) {
            vandalb_sum_total_tertunggak += parseFloat(vandalb_total_tertunggak[i].value);
            $('#' + vandalb_total_tertunggak[i].id).val(parseFloat(vandalb_total_tertunggak[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_tertunggak').val(parseFloat(vandalb_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var vandalb_sum_total_all = parseFloat(vandalb_sum_total_tunggakan) + parseFloat(vandalb_sum_total_semasa) + parseFloat(vandalb_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix2 }}total_all').val(parseFloat(vandalb_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function addRowVandalA() {
        var rowVandalANo = $("#dynamic_form_vandal_a tr").length;
        rowVandalANo = rowVandalANo - 2;
        $("#dynamic_form_vandal_a tr:last").prev().prev().after('<tr id="vandala_row' + rowVandalANo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="1">' + rowVandalANo + '</td><td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value=""></td><td><input type="number" step="any" oninput="calculateVandalismeA(\'' + rowVandalANo + '\')" id="{{ $prefix }}tunggakan_' + rowVandalANo + '" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateVandalismeA(\'' + rowVandalANo + '\')" id="{{ $prefix }}semasa_' + rowVandalANo + '" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateVandalismeA(\'' + rowVandalANo + '\')" id="{{ $prefix }}hadapan_' + rowVandalANo + '" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" id="{{ $prefix }}total_income_' + rowVandalANo + '" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="0" readonly=""></td><td><input type="number" step="any" oninput="calculateVandalismeATotal(\'' + rowVandalANo + '\')" id="{{ $prefix }}tertunggak_' + rowVandalANo + '" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowVandalA(\'vandala_row' + rowVandalANo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateVandalismeATotal();
    }

    function deleteRowVandalA(rowVandalANo) {
        $('#' + rowVandalANo).remove();

        calculateVandalismeATotal();
    }

    function addRowVandalB() {
        var rowVandalBNo = $("#dynamic_form_vandal_b tr").length;
        rowVandalBNo = rowVandalBNo - 2;
        $("#dynamic_form_vandal_b tr:last").prev().prev().after('<tr id="vandalb_row' + rowVandalBNo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix2 }}is_custom[]" value="1">' + rowVandalBNo + '</td><td><input type="text" name="{{ $prefix2 }}name[]" class="form-control form-control-sm" value=""></td><td><input type="number" step="any" oninput="calculateVandalismeB(\'' + rowVandalBNo + '\')" id="{{ $prefix2 }}tunggakan_' + rowVandalBNo + '" name="{{ $prefix2 }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateVandalismeB(\'' + rowVandalBNo + '\')" id="{{ $prefix2 }}semasa_' + rowVandalBNo + '" name="{{ $prefix2 }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateVandalismeB(\'' + rowVandalBNo + '\')" id="{{ $prefix2 }}hadapan_' + rowVandalBNo + '" name="{{ $prefix2 }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" id="{{ $prefix2 }}total_income_' + rowVandalBNo + '" name="{{ $prefix2 }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="0" readonly=""></td><td><input type="number" step="any" oninput="calculateVandalismeBTotal(\'' + rowVandalBNo + '\')" id="{{ $prefix2 }}tertunggak_' + rowVandalBNo + '" name="{{ $prefix2 }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowVandalB(\'vandalb_row' + rowVandalBNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateVandalismeBTotal();
    }

    function deleteRowVandalB(rowVandalBNo) {
        $('#' + rowVandalBNo).remove();

        calculateVandalismeBTotal();
    }
</script>
