<?php
$prefix = 'util_';
$prefix2 = 'utilb_';
$prefix3 = 'utilab_';
?>

<div class="row">
    <div class="col-lg-12">

        <h6>4.1 LAPORAN PERBELANJAAN UTILITI</h6>

        <form id="form_utility">

            <div class="row">
                <table class="table table-sm" id="dynamic_form_utility" style="font-size: 12px;">
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
                        <tr>
                            <th colspan="8">BAHAGIAN A</th>
                        </tr>

                        <?php
                        $count = 0;
                        $total_tunggakan = 0;
                        $total_semasa = 0;
                        $total_hadapan = 0;
                        $total_tertunggak = 0;
                        $total_all = 0;
                        ?>

                        @foreach ($utila as $utilas)
                        <?php
                        $total_tunggakan += $utilas['tunggakan'];
                        $total_semasa += $utilas['semasa'];
                        $total_hadapan += $utilas['hadapan'];
                        $total_tertunggak += $utilas['tertunggak'];
                        $total_income = $utilas['tunggakan'] + $utilas['semasa'] + $utilas['hadapan'];
                        $total_all += $total_income;
                        ?>
                        <tr id="util_row{{ ++$count }}">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $utilas['is_custom'] }}">{{ $count }}</td>
                            <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $utilas['name'] }}" readonly=""></td>
                            <td><input type="number" step="any" oninput="calculateUtilityA('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilas['tunggakan'] }}"></td>
                            <td><input type="number" step="any" oninput="calculateUtilityA('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilas['semasa'] }}"></td>
                            <td><input type="number" step="any" oninput="calculateUtilityA('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilas['hadapan'] }}"></td>
                            <td><input type="number" step="any" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $total_income }}" readonly=""></td>
                            <td><input type="number" step="any" oninput="calculateUtilityATotal()" id="{{ $prefix . 'tertunggak_' . $count }}" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilas['tertunggak'] }}"></td>
                            <td>&nbsp;</td>
                        </tr>
                        @endforeach

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH BAHAGIAN A</th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $total_tunggakan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $total_semasa }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $total_hadapan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $total_all }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $total_tertunggak }}" readonly=""></th>
                            <td>&nbsp;</td>
                        </tr>

                        <tr>
                            <th colspan="8">BAHAGIAN B</th>
                        </tr>

                        <?php
                        $countb = 0;
                        $totalb_tunggakan = 0;
                        $totalb_semasa = 0;
                        $totalb_hadapan = 0;
                        $totalb_tertunggak = 0;
                        $totalb_all = 0;
                        ?>

                        @foreach ($utilb as $utilbs)
                        <?php
                        $totalb_tunggakan += $utilbs['tunggakan'];
                        $totalb_semasa += $utilbs['semasa'];
                        $totalb_hadapan += $utilbs['hadapan'];
                        $totalb_tertunggak += $utilbs['tertunggak'];
                        $totalb_income = $utilbs['tunggakan'] + $utilbs['semasa'] + $utilbs['hadapan'];
                        $totalb_all += $totalb_income;
                        ?>
                        <tr id="utility_row{{ ++$countb }}">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $prefix2 }}is_custom[]" value="{{ $utilbs['is_custom'] }}">{{ $countb }}</td>
                            <td><input type="text" name="{{ $prefix2 }}name[]" class="form-control form-control-sm" value="{{ $utilbs['name'] }}" readonly=""></td>
                            <td><input type="number" step="any" oninput="calculateUtilityB('{{ $countb }}')" id="{{ $prefix2 . 'tunggakan_' . $countb }}" name="{{ $prefix2 }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilbs['tunggakan'] }}"></td>
                            <td><input type="number" step="any" oninput="calculateUtilityB('{{ $countb }}')" id="{{ $prefix2 . 'semasa_' . $countb }}" name="{{ $prefix2 }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilbs['semasa'] }}"></td>
                            <td><input type="number" step="any" oninput="calculateUtilityB('{{ $countb }}')" id="{{ $prefix2 . 'hadapan_' . $countb }}" name="{{ $prefix2 }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilbs['hadapan'] }}"></td>
                            <td><input type="number" step="any" id="{{ $prefix2 . 'total_income_' . $countb }}" name="{{ $prefix2 }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="{{ $totalb_income }}" readonly=""></td>
                            <td><input type="number" step="any" oninput="calculateUtilityBTotal('{{ $countb }}')" id="{{ $prefix2 . 'tertunggak_' . $countb }}" name="{{ $prefix2 }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="{{ $utilbs['tertunggak'] }}"></td>
                            @if ($utilbs['is_custom'])
                            <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowUtility('utility_row<?php echo $countb ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowUtility()" class="btn btn-primary btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH BAHAGIAN B</th>
                            <th><input type="number" step="any" id="{{ $prefix2 . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_tunggakan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix2 . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $totalb_semasa }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix2 . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_hadapan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix2 . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $totalb_income }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix2 . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $totalb_tertunggak }}" readonly=""></th>
                            <td>&nbsp;</td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH BAHAGIAN A + BAHAGIAN B</th>
                            <th><input type="number" step="any" id="{{ $prefix3 . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $total_tunggakan + $totalb_tunggakan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix3 . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $total_semasa + $totalb_semasa }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix3 . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $total_hadapan + $totalb_hadapan }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix3 . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $total_income + $totalb_income }}" readonly=""></th>
                            <th><input type="number" step="any" id="{{ $prefix3 . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $total_tertunggak + $totalb_tertunggak }}" readonly=""></th>
                            <td>&nbsp;</td>
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

<script type="text/javascript">
    $(document).ready(function () {
        calculateUtilityATotal();
        calculateUtilityBTotal();
        calculateUtilityABTotal();
    });

    function calculateUtilityA(id) {
        var util_sum_tunggakan = 0;
        var util_sum_semasa = 0;
        var util_sum_hadapan = 0;
        var util_sum_total_income = 0;

        var util_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        util_sum_tunggakan += parseFloat(util_tunggakan.value);

        var util_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        util_sum_semasa += parseFloat(util_semasa.value);

        var util_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        util_sum_hadapan += parseFloat(util_hadapan.value);

        util_sum_total_income += parseFloat(util_sum_tunggakan) + parseFloat(util_sum_semasa) + parseFloat(util_sum_hadapan);
        $('#{{ $prefix }}total_income_' + id).val(parseFloat(util_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateUtilityATotal();
        calculateUtilityABTotal();
    }

    function calculateUtilityB(id) {
        var utilb_sum_tunggakan = 0;
        var utilb_sum_semasa = 0;
        var utilb_sum_hadapan = 0;
        var utilb_sum_total_income = 0;

        var utilb_tunggakan = document.getElementById("{{ $prefix2 }}tunggakan_" + id);
        utilb_sum_tunggakan += parseFloat(utilb_tunggakan.value);

        var utilb_semasa = document.getElementById("{{ $prefix2 }}semasa_" + id);
        utilb_sum_semasa += parseFloat(utilb_semasa.value);

        var utilb_hadapan = document.getElementById("{{ $prefix2 }}hadapan_" + id);
        utilb_sum_hadapan += parseFloat(utilb_hadapan.value);

        utilb_sum_total_income += parseFloat(utilb_sum_tunggakan) + parseFloat(utilb_sum_semasa) + parseFloat(utilb_sum_hadapan);
        $('#{{ $prefix2 }}total_income_' + id).val(parseFloat(utilb_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateUtilityBTotal();
        calculateUtilityABTotal();
    }

    function calculateUtilityATotal() {
        var util_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var util_sum_total_tunggakan = 0;
        for (var i = 0; i < util_total_tunggakan.length; i++) {
            util_sum_total_tunggakan += parseFloat(util_total_tunggakan[i].value);
            $('#' + util_total_tunggakan[i].id).val(parseFloat(util_total_tunggakan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_tunggakan').val(parseFloat(util_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var util_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var util_sum_total_semasa = 0;
        for (var i = 0; i < util_total_semasa.length; i++) {
            util_sum_total_semasa += parseFloat(util_total_semasa[i].value);
            $('#' + util_total_semasa[i].id).val(parseFloat(util_total_semasa[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_semasa').val(parseFloat(util_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var util_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var util_sum_total_hadapan = 0;
        for (var i = 0; i < util_total_hadapan.length; i++) {
            util_sum_total_hadapan += parseFloat(util_total_hadapan[i].value);
            $('#' + util_total_hadapan[i].id).val(parseFloat(util_total_hadapan[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_hadapan').val(parseFloat(util_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var util_total_tertunggak = document.getElementsByName("{{ $prefix }}tertunggak[]");
        var util_sum_total_tertunggak = 0;
        for (var i = 0; i < util_total_tertunggak.length; i++) {
            util_sum_total_tertunggak += parseFloat(util_total_tertunggak[i].value);
            $('#' + util_total_tertunggak[i].id).val(parseFloat(util_total_tertunggak[i].value).toFixed(2));
        }
        $('#{{ $prefix }}total_tertunggak').val(parseFloat(util_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var util_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < util_total_income.length; i++) {
            $('#' + util_total_income[i].id).val(parseFloat(util_total_income[i].value).toFixed(2));
        }

        var util_sum_total_all = parseFloat(util_sum_total_tunggakan) + parseFloat(util_sum_total_semasa) + parseFloat(util_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix }}total_all').val(parseFloat(util_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C

        calculateUtilityABTotal();
    }

    function calculateUtilityBTotal() {
        var utilb_total_tunggakan = document.getElementsByName("{{ $prefix2 }}tunggakan[]");
        var utilb_sum_total_tunggakan = 0;
        for (var i = 0; i < utilb_total_tunggakan.length; i++) {
            utilb_sum_total_tunggakan += parseFloat(utilb_total_tunggakan[i].value);
            $('#' + utilb_total_tunggakan[i].id).val(parseFloat(utilb_total_tunggakan[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_tunggakan').val(parseFloat(utilb_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var utilb_total_semasa = document.getElementsByName("{{ $prefix2 }}semasa[]");
        var utilb_sum_total_semasa = 0;
        for (var i = 0; i < utilb_total_semasa.length; i++) {
            utilb_sum_total_semasa += parseFloat(utilb_total_semasa[i].value);
            $('#' + utilb_total_semasa[i].id).val(parseFloat(utilb_total_semasa[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_semasa').val(parseFloat(utilb_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var utilb_total_hadapan = document.getElementsByName("{{ $prefix2 }}hadapan[]");
        var utilb_sum_total_hadapan = 0;
        for (var i = 0; i < utilb_total_hadapan.length; i++) {
            utilb_sum_total_hadapan += parseFloat(utilb_total_hadapan[i].value);
            $('#' + utilb_total_hadapan[i].id).val(parseFloat(utilb_total_hadapan[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_hadapan').val(parseFloat(utilb_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var utilb_total_tertunggak = document.getElementsByName("{{ $prefix2 }}tertunggak[]");
        var utilb_sum_total_tertunggak = 0;
        for (var i = 0; i < utilb_total_tertunggak.length; i++) {
            utilb_sum_total_tertunggak += parseFloat(utilb_total_tertunggak[i].value);
            $('#' + utilb_total_tertunggak[i].id).val(parseFloat(utilb_total_tertunggak[i].value).toFixed(2));
        }
        $('#{{ $prefix2 }}total_tertunggak').val(parseFloat(utilb_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var utilb_total_income = document.getElementsByName("{{ $prefix2 }}total_income[]");
        for (var i = 0; i < utilb_total_income.length; i++) {
            $('#' + utilb_total_income[i].id).val(parseFloat(utilb_total_income[i].value).toFixed(2));
        }

        var utilb_sum_total_all = parseFloat(utilb_sum_total_tunggakan) + parseFloat(utilb_sum_total_semasa) + parseFloat(utilb_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix2 }}total_all').val(parseFloat(utilb_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C

        calculateUtilityABTotal();
    }

    function calculateUtilityABTotal() {
        var utilab_sum_total_tunggakan = 0;
        utilab_sum_total_tunggakan += parseFloat(util_total_tunggakan.value) + parseFloat(utilb_total_tunggakan.value);
        $('#{{ $prefix3 }}total_tunggakan').val(parseFloat(utilab_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var utilab_sum_total_semasa = 0;
        utilab_sum_total_semasa += parseFloat(util_total_semasa.value) + parseFloat(utilb_total_semasa.value);
        $('#{{ $prefix3 }}total_semasa').val(parseFloat(utilab_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var utilab_sum_total_hadapan = 0;
        utilab_sum_total_hadapan += parseFloat(util_total_hadapan.value) + parseFloat(utilb_total_hadapan.value);
        $('#{{ $prefix3 }}total_hadapan').val(parseFloat(utilab_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var utilab_sum_total_all = 0;
        var util_total_income = document.getElementById("{{ $prefix }}total_all");
        var utilb_total_income = document.getElementById("{{ $prefix2 }}total_all");
        utilab_sum_total_all += parseFloat(util_total_income.value) + parseFloat(utilb_total_income.value);
        $('#{{ $prefix3 }}total_all').val(parseFloat(utilab_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C

        var utilab_sum_total_tertunggak = 0;
        var util_total_tertunggak = document.getElementById("{{ $prefix }}total_tertunggak");
        var utilb_total_tertunggak = document.getElementById("{{ $prefix2 }}total_tertunggak");
        utilab_sum_total_tertunggak += parseFloat(util_total_tertunggak.value) + parseFloat(utilb_total_tertunggak.value);
        $('#{{ $prefix3 }}total_tertunggak').val(parseFloat(utilab_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH SEMUA TERTUNGGAK
    }

    function addRowUtility() {
        var rowUtilityNo = $("#dynamic_form_utility tr").length;
        rowUtilityNo = rowUtilityNo - 8;
        $("#dynamic_form_utility tr:last").prev().prev().prev().after('<tr id="utility_row' + rowUtilityNo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix2 }}is_custom[]" value="1">' + rowUtilityNo + '</td><td><input type="text" name="{{ $prefix2 }}name[]" class="form-control form-control-sm" value=""></td><td><input type="number" step="any" oninput="calculateUtilityB(\'' + rowUtilityNo + '\')" id="{{ $prefix2 }}tunggakan_' + rowUtilityNo + '" name="{{ $prefix2 }}tunggakan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateUtilityB(\'' + rowUtilityNo + '\')" id="{{ $prefix2 }}semasa_' + rowUtilityNo + '" name="{{ $prefix2 }}semasa[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" oninput="calculateUtilityB(\'' + rowUtilityNo + '\')" id="{{ $prefix2 }}hadapan_' + rowUtilityNo + '" name="{{ $prefix2 }}hadapan[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td><input type="number" step="any" id="{{ $prefix2 }}total_income_' + rowUtilityNo + '" name="{{ $prefix2 }}total_income[]" class="form-control form-control-sm text-right numeric-only" value="0" readonly=""></td><td><input type="number" step="any" oninput="calculateUtilityBTotal(\'' + rowUtilityNo + '\')" id="{{ $prefix2 }}tertunggak_' + rowUtilityNo + '" name="{{ $prefix2 }}tertunggak[]" class="form-control form-control-sm text-right numeric-only" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowUtility(\'utility_row' + rowUtilityNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateUtilityBTotal();
    }

    function deleteRowUtility(rowUtilityNo) {
        $('#' + rowUtilityNo).remove();

        calculateUtilityBTotal();
    }

    $(function () {
        $("#form_utility").submit(function (e) {
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
                    url: "{{ URL::action('FinanceController@updateFinanceFileUtility') }}",
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
