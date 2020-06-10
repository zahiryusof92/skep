<?php
$prefix = 'admin_';
?>

<div class="row">
    <div class="col-lg-12">

        <h6>4.6 LAPORAN PERBELANJAAN PENTADBIRAN</h6>

        <div class="row">
            <table class="table table-sm" id="dynamic_form_admin" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th width="5%">&nbsp;</th>
                        <th width="40%" style="text-align: center;">PERKARA</th>
                        <th width="10%" style="text-align: center;">TUNGGAKAN BULAN-BULAN TERDAHULU<br/>C</th>
                        <th width="10%" style="text-align: center;">BULAN SEMASA<br/>D</th>
                        <th width="10%" style="text-align: center;">BULAN HADAPAN<br/>E</th>
                        <th width="10%" style="text-align: center;">JUMLAH<br/>C + D + E</th>
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

                    @foreach ($adminFile as $adminFiles)
                    <?php
                    $total_tunggakan += $adminFiles['tunggakan'];
                    $total_semasa += $adminFiles['semasa'];
                    $total_hadapan += $adminFiles['hadapan'];
                    $total_tertunggak += $adminFiles['tertunggak'];
                    $total_income = $adminFiles['tunggakan'] + $adminFiles['semasa'] + $adminFiles['hadapan'];
                    $total_all += $total_income;
                    ?>

                    <tr id="admin_row{{ ++$count }}">
                        <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $adminFiles['is_custom'] }}">{{ $count }}</td>
                        <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $adminFiles['name'] }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateAdmin('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $adminFiles['tunggakan'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateAdmin('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $adminFiles['semasa'] }}"></td>
                        <td><input type="number" step="any" oninput="calculateAdmin('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $adminFiles['hadapan'] }}"></td>
                        <td><input type="number" step="any" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $total_income }}" readonly=""></td>
                        <td><input type="number" step="any" oninput="calculateAdminTotal('{{ $count }}')" id="{{ $prefix . 'tertunggak_' . $count }}" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="{{ $adminFiles['tertunggak'] }}"></td>
                        @if ($adminFiles['is_custom'])
                        <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowAdmin('admin_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                        @else
                        <td>&nbsp;</td>
                        @endif
                    </tr>
                    @endforeach

                    <tr>
                        <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowAdmin()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
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
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        calculateAdminTotal();
    });

    function calculateAdmin(id) {
        var admin_sum_tunggakan = 0;
        var admin_sum_semasa = 0;
        var admin_sum_hadapan = 0;
        var admin_sum_total_income = 0;

        var admin_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        admin_sum_tunggakan += parseFloat(admin_tunggakan.value);

        var admin_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        admin_sum_semasa += parseFloat(admin_semasa.value);

        var admin_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        admin_sum_hadapan += parseFloat(admin_hadapan.value);

        admin_sum_total_income += parseFloat(admin_sum_tunggakan) + parseFloat(admin_sum_semasa) + parseFloat(admin_sum_hadapan);
        $('#admin_total_income_' + id).val(parseFloat(admin_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateAdminTotal();
    }

    function calculateAdminTotal() {
        var admin_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var admin_sum_total_tunggakan = 0;
        for (var i = 0; i < admin_total_tunggakan.length; i++) {
            admin_sum_total_tunggakan += parseFloat(admin_total_tunggakan[i].value);
            $('#' + admin_total_tunggakan[i].id).val(parseFloat(admin_total_tunggakan[i].value).toFixed(2));
        }
        $('#admin_total_tunggakan').val(parseFloat(admin_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var admin_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var admin_sum_total_semasa = 0;
        for (var i = 0; i < admin_total_semasa.length; i++) {
            admin_sum_total_semasa += parseFloat(admin_total_semasa[i].value);
            $('#' + admin_total_semasa[i].id).val(parseFloat(admin_total_semasa[i].value).toFixed(2));
        }
        $('#admin_total_semasa').val(parseFloat(admin_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var admin_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var admin_sum_total_hadapan = 0;
        for (var i = 0; i < admin_total_hadapan.length; i++) {
            admin_sum_total_hadapan += parseFloat(admin_total_hadapan[i].value);
            $('#' + admin_total_hadapan[i].id).val(parseFloat(admin_total_hadapan[i].value).toFixed(2));
        }
        $('#admin_total_hadapan').val(parseFloat(admin_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var admin_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < admin_total_income.length; i++) {
            $('#' + admin_total_income[i].id).val(parseFloat(admin_total_income[i].value).toFixed(2));
        }

        var admin_total_tertunggak = document.getElementsByName("{{ $prefix }}tertunggak[]");
        var admin_sum_total_tertunggak = 0;
        for (var i = 0; i < admin_total_tertunggak.length; i++) {
            admin_sum_total_tertunggak += parseFloat(admin_total_tertunggak[i].value);
            $('#' + admin_total_tertunggak[i].id).val(parseFloat(admin_total_tertunggak[i].value).toFixed(2));
        }
        $('#admin_total_tertunggak').val(parseFloat(admin_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var admin_sum_total_all = parseFloat(admin_sum_total_tunggakan) + parseFloat(admin_sum_total_semasa) + parseFloat(admin_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#admin_total_all').val(parseFloat(admin_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function addRowAdmin() {
        var rowAdminNo = $("#dynamic_form_admin tr").length;
        rowAdminNo = rowAdminNo - 2;
        $("#dynamic_form_admin tr:last").prev().prev().after('<tr id="admin_row' + rowAdminNo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="1">' + rowAdminNo + '</td><td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value=""></td><td><input type="number" step="any" oninput="calculateAdmin(\'' + rowAdminNo + '\')" id="{{ $prefix }}tunggakan_' + rowAdminNo + '" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateAdmin(\'' + rowAdminNo + '\')" id="{{ $prefix }}semasa_' + rowAdminNo + '" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateAdmin(\'' + rowAdminNo + '\')" id="{{ $prefix }}hadapan_' + rowAdminNo + '" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" id="{{ $prefix }}total_income_' + rowAdminNo + '" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="0" readonly=""></td><td><input type="number" step="any" oninput="calculateAdminTotal(\'' + rowAdminNo + '\')" id="{{ $prefix }}tertunggak_' + rowAdminNo + '" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowAdmin(\'admin_row' + rowAdminNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateAdminTotal();
    }
</script>
