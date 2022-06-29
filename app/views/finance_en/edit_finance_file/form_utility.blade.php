<?php
$prefix = 'util_';
$prefix2 = 'utilb_';
$prefix3 = 'utilab_';
?>

<div class="row padding-vertical-10">
    <div class="col-lg-12">

        @if(count($utilaOld) > 0 && count($utilbOld) > 0)
            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
        @endif
        <h6>4.1 LAPORAN PERBELANJAAN UTILITI</h6>

        <form id="form_utility">

            <div class="row">
                <table class="table table-sm borderless" id="dynamic_form_utility" style="font-size: 12px;">
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
                        <tr id="util_row{{ ++$count }}" class="util_row">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $utilas['is_custom'] }}">{{ $count }}</td>
                            <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $utilas['name'] }}" {{ $utilas['is_custom'] ? '' : 'readonly' }}></td>
                            <td><input type="currency" oninput="calculateUtilityA('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right" value="{{ $utilas['tunggakan'] }}"></td>
                            <td><input type="currency" oninput="calculateUtilityA('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right" value="{{ $utilas['semasa'] }}"></td>
                            <td><input type="currency" oninput="calculateUtilityA('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right" value="{{ $utilas['hadapan'] }}"></td>
                            <td><input type="currency" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right" value="{{ $total_income }}" readonly=""></td>
                            <td><input type="currency" oninput="calculateUtilityATotal()" id="{{ $prefix . 'tertunggak_' . $count }}" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right" value="{{ $utilas['tertunggak'] }}"></td>
                            @if ($utilas['is_custom'])
                            <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowUtility('util_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowUtility('{{ $prefix }}', 'util_row')" class="btn btn-own btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH BAHAGIAN A</th>
                            <th><input type="currency" id="{{ $prefix . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $total_tunggakan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $total_semasa }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $total_hadapan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $total_all }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $total_tertunggak }}" readonly=""></th>
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
                        <tr id="utility_row{{ ++$countb }}" class="utility_row">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $prefix2 }}is_custom[]" value="{{ $utilbs['is_custom'] }}">{{ $countb }}</td>
                            <td><input type="text" name="{{ $prefix2 }}name[]" class="form-control form-control-sm" value="{{ $utilbs['name'] }}" {{ $utilbs['is_custom'] ? '' : 'readonly' }}></td>
                            <td><input type="currency" oninput="calculateUtilityB('{{ $countb }}')" id="{{ $prefix2 . 'tunggakan_' . $countb }}" name="{{ $prefix2 }}tunggakan[]" class="form-control form-control-sm text-right" value="{{ $utilbs['tunggakan'] }}"></td>
                            <td><input type="currency" oninput="calculateUtilityB('{{ $countb }}')" id="{{ $prefix2 . 'semasa_' . $countb }}" name="{{ $prefix2 }}semasa[]" class="form-control form-control-sm text-right" value="{{ $utilbs['semasa'] }}"></td>
                            <td><input type="currency" oninput="calculateUtilityB('{{ $countb }}')" id="{{ $prefix2 . 'hadapan_' . $countb }}" name="{{ $prefix2 }}hadapan[]" class="form-control form-control-sm text-right" value="{{ $utilbs['hadapan'] }}"></td>
                            <td><input type="currency" id="{{ $prefix2 . 'total_income_' . $countb }}" name="{{ $prefix2 }}total_income[]" class="form-control form-control-sm text-right" value="{{ $totalb_income }}" readonly=""></td>
                            <td><input type="currency" oninput="calculateUtilityBTotal('{{ $countb }}')" id="{{ $prefix2 . 'tertunggak_' . $countb }}" name="{{ $prefix2 }}tertunggak[]" class="form-control form-control-sm text-right" value="{{ $utilbs['tertunggak'] }}"></td>
                            @if ($utilbs['is_custom'])
                            <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowUtility('utility_row<?php echo $countb ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowUtility('{{ $prefix2 }}', 'utility_row')" class="btn btn-own btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH BAHAGIAN B</th>
                            <th><input type="currency" id="{{ $prefix2 . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_tunggakan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix2 . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $totalb_semasa }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix2 . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $totalb_hadapan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix2 . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $totalb_income }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix2 . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $totalb_tertunggak }}" readonly=""></th>
                            <td>&nbsp;</td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH BAHAGIAN A + BAHAGIAN B</th>
                            <th><input type="currency" id="{{ $prefix3 . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $total_tunggakan + $totalb_tunggakan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix3 . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $total_semasa + $totalb_semasa }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix3 . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $total_hadapan + $totalb_hadapan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix3 . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $total_income + $totalb_income }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix3 . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $total_tertunggak + $totalb_tertunggak }}" readonly=""></th>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if ($update_permission == 1) { ?>
                <div class="form-actions">
                    <input type="hidden" name="finance_file_id" value="{{ \Helper\Helper::encode($financefiledata->id) }}"/>
                    <button type="button"class="btn btn-own submit_button" onclick="submitUtility()">{{ trans("app.forms.submit") }}</button>
                    <img class="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>   
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
        
        // summary utility
        let utility_amount = Number($('#utilb_total_all').val()) - (Number($('#utilb_total_income_1').val()) + Number($('#utilb_total_income_2').val()));
        $('#sum_utility').val(parseFloat(utility_amount).toFixed(2));
        calculateSummaryTotal();
    });

    function calculateUtilityA(id) {
        var util_sum_tunggakan = 0;
        var util_sum_semasa = 0;
        var util_sum_hadapan = 0;
        var util_sum_total_income = 0;

        var util_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        util_sum_tunggakan += Number(util_tunggakan.value);

        var util_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        util_sum_semasa += Number(util_semasa.value);

        var util_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        util_sum_hadapan += Number(util_hadapan.value);

        util_sum_total_income += Number(util_sum_tunggakan) + Number(util_sum_semasa) + Number(util_sum_hadapan);
        $('#{{ $prefix }}total_income_' + id).val(parseFloat(util_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateUtilityATotal();
        calculateUtilityABTotal();
        calculateSummaryTotal();
    }

    function calculateUtilityB(id) {
        var utilb_sum_tunggakan = 0;
        var utilb_sum_semasa = 0;
        var utilb_sum_hadapan = 0;
        var utilb_sum_total_income = 0;

        var utilb_tunggakan = document.getElementById("{{ $prefix2 }}tunggakan_" + id);
        utilb_sum_tunggakan += Number(utilb_tunggakan.value);

        var utilb_semasa = document.getElementById("{{ $prefix2 }}semasa_" + id);
        utilb_sum_semasa += Number(utilb_semasa.value);

        var utilb_hadapan = document.getElementById("{{ $prefix2 }}hadapan_" + id);
        utilb_sum_hadapan += Number(utilb_hadapan.value);

        utilb_sum_total_income += Number(utilb_sum_tunggakan) + Number(utilb_sum_semasa) + Number(utilb_sum_hadapan);
        $('#{{ $prefix2 }}total_income_' + id).val(parseFloat(utilb_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateUtilityBTotal();
        calculateUtilityABTotal();
        calculateSummaryTotal();
        
    }

    function calculateUtilityATotal() {
        var util_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var util_sum_total_tunggakan = 0;
        for (var i = 0; i < util_total_tunggakan.length; i++) {
            util_sum_total_tunggakan += Number(util_total_tunggakan[i].value);
        }
        $('#{{ $prefix }}total_tunggakan').val(parseFloat(util_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var util_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var util_sum_total_semasa = 0;
        for (var i = 0; i < util_total_semasa.length; i++) {
            util_sum_total_semasa += Number(util_total_semasa[i].value);
        }
        $('#{{ $prefix }}total_semasa').val(parseFloat(util_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var util_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var util_sum_total_hadapan = 0;
        for (var i = 0; i < util_total_hadapan.length; i++) {
            util_sum_total_hadapan += Number(util_total_hadapan[i].value);
        }
        $('#{{ $prefix }}total_hadapan').val(parseFloat(util_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var util_total_tertunggak = document.getElementsByName("{{ $prefix }}tertunggak[]");
        var util_sum_total_tertunggak = 0;
        for (var i = 0; i < util_total_tertunggak.length; i++) {
            util_sum_total_tertunggak += Number(util_total_tertunggak[i].value);
        }
        $('#{{ $prefix }}total_tertunggak').val(parseFloat(util_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var util_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < util_total_income.length; i++) {
            $('#' + util_total_income[i].id).val(parseFloat(util_total_income[i].value).toFixed(2));
        }

        var util_sum_total_all = Number(util_sum_total_tunggakan) + Number(util_sum_total_semasa) + Number(util_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix }}total_all').val(parseFloat(util_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C

        calculateUtilityABTotal();
    }

    function calculateUtilityBTotal() {
        var utilb_total_tunggakan = document.getElementsByName("{{ $prefix2 }}tunggakan[]");
        var utilb_sum_total_tunggakan = 0;
        for (var i = 0; i < utilb_total_tunggakan.length; i++) {
            utilb_sum_total_tunggakan += Number(utilb_total_tunggakan[i].value);
        }
        $('#{{ $prefix2 }}total_tunggakan').val(parseFloat(utilb_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var utilb_total_semasa = document.getElementsByName("{{ $prefix2 }}semasa[]");
        var utilb_sum_total_semasa = 0;
        for (var i = 0; i < utilb_total_semasa.length; i++) {
            utilb_sum_total_semasa += Number(utilb_total_semasa[i].value);
        }
        $('#{{ $prefix2 }}total_semasa').val(parseFloat(utilb_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var utilb_total_hadapan = document.getElementsByName("{{ $prefix2 }}hadapan[]");
        var utilb_sum_total_hadapan = 0;
        for (var i = 0; i < utilb_total_hadapan.length; i++) {
            utilb_sum_total_hadapan += Number(utilb_total_hadapan[i].value);
        }
        $('#{{ $prefix2 }}total_hadapan').val(parseFloat(utilb_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var utilb_total_tertunggak = document.getElementsByName("{{ $prefix2 }}tertunggak[]");
        var utilb_sum_total_tertunggak = 0;
        for (var i = 0; i < utilb_total_tertunggak.length; i++) {
            utilb_sum_total_tertunggak += Number(utilb_total_tertunggak[i].value);
        }
        $('#{{ $prefix2 }}total_tertunggak').val(parseFloat(utilb_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var utilb_total_income = document.getElementsByName("{{ $prefix2 }}total_income[]");
        for (var i = 0; i < utilb_total_income.length; i++) {
            $('#' + utilb_total_income[i].id).val(parseFloat(utilb_total_income[i].value).toFixed(2));
        }

        var utilb_sum_total_all = Number(utilb_sum_total_tunggakan) + Number(utilb_sum_total_semasa) + Number(utilb_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix2 }}total_all').val(parseFloat(utilb_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C

        calculateUtilityABTotal();
    }

    function calculateUtilityABTotal() {
        var utilab_sum_total_tunggakan = 0;
        utilab_sum_total_tunggakan += Number(util_total_tunggakan.value) + Number(utilb_total_tunggakan.value);
        $('#{{ $prefix3 }}total_tunggakan').val(parseFloat(utilab_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var utilab_sum_total_semasa = 0;
        utilab_sum_total_semasa += Number(util_total_semasa.value) + Number(utilb_total_semasa.value);
        $('#{{ $prefix3 }}total_semasa').val(parseFloat(utilab_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var utilab_sum_total_hadapan = 0;
        utilab_sum_total_hadapan += Number(util_total_hadapan.value) + Number(utilb_total_hadapan.value);
        $('#{{ $prefix3 }}total_hadapan').val(parseFloat(utilab_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var utilab_sum_total_all = 0;
        var util_total_income = document.getElementById("{{ $prefix }}total_all");
        var utilb_total_income = document.getElementById("{{ $prefix2 }}total_all");
        utilab_sum_total_all += Number(util_total_income.value) + Number(utilb_total_income.value);
        $('#{{ $prefix3 }}total_all').val(parseFloat(utilab_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C

        var utilab_sum_total_tertunggak = 0;
        var util_total_tertunggak = document.getElementById("{{ $prefix }}total_tertunggak");
        var utilb_total_tertunggak = document.getElementById("{{ $prefix2 }}total_tertunggak");
        utilab_sum_total_tertunggak += Number(util_total_tertunggak.value) + Number(utilb_total_tertunggak.value);
        $('#{{ $prefix3 }}total_tertunggak').val(parseFloat(utilab_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH SEMUA TERTUNGGAK
        
    }

    function addRowUtility(prefix, id) {
        changes = true;
        var latest_id = $("#dynamic_form_utility tr." + id + ":last").attr("id").split(id);
        var rowUtilityNo = Number(latest_id[1]) + 1;
        var detect_function = (prefix == 'util_')? "calculateUtilityA" : "calculateUtilityB";
        $("#dynamic_form_utility tr#"+ id + Number(latest_id[1]) +":last").after('<tr id="'+ id + rowUtilityNo + '" class="'+ id +'"><td class="text-center padding-table">'+
        '<input type="hidden" name="'+ prefix +'is_custom[]" value="1">' + rowUtilityNo + '</td>'+
        '<td><input type="text" name="'+ prefix +'name[]" class="form-control form-control-sm" value=""></td>'+
        '<td><input type="currency" oninput="'+ detect_function +'(\'' + rowUtilityNo + '\')" id="'+ prefix +'tunggakan_' + rowUtilityNo + '" name="'+ prefix +'tunggakan[]" class="form-control form-control-sm text-right" value="0"></td>'+
        '<td><input type="currency" oninput="'+ detect_function +'(\'' + rowUtilityNo + '\')" id="'+ prefix +'semasa_' + rowUtilityNo + '" name="'+ prefix +'semasa[]" class="form-control form-control-sm text-right" value="0"></td>'+
        '<td><input type="currency" oninput="'+ detect_function +'(\'' + rowUtilityNo + '\')" id="'+ prefix +'hadapan_' + rowUtilityNo + '" name="'+ prefix +'hadapan[]" class="form-control form-control-sm text-right" value="0"></td>'+
        '<td><input type="currency" id="'+ prefix +'total_income_' + rowUtilityNo + '" name="'+ prefix +'total_income[]" class="form-control form-control-sm text-right" value="0" readonly=""></td>'+
        '<td><input type="currency" oninput="'+ detect_function +'Total(\'' + rowUtilityNo + '\')" id="'+ prefix +'tertunggak_' + rowUtilityNo + '" name="'+ prefix +'tertunggak[]" class="form-control form-control-sm text-right" value="0"></td>'+
        '<td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowUtility(\''+ id + rowUtilityNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateUtilityATotal();
        calculateUtilityBTotal();
    }

    function deleteRowUtility(rowUtilityNo) {
        changes = true;

        $('#' + rowUtilityNo).remove();
        
        calculateUtilityATotal();
        calculateUtilityBTotal();
    }

    function submitUtility() {
        error = 0;
        var data = $("#form_utility").serialize();

        $(".loading").css("display", "inline-block");
        $(".submit_button").attr("disabled", "disabled");
        $("#check_mandatory_fields").css("display", "none");

        if (error == 0) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            $.ajax({
                method: "POST",
                url: "{{ URL::action('FinanceController@updateFinanceFileUtility') }}",
                data: data,
                success: function (response) {
                    changes = false;
                    $.unblockUI();
                    $(".loading").css("display", "none");
                    $(".submit_button").removeAttr("disabled");

                    if (response.trim() == "true") {
                        submitSummary();
                        $.notify({
                            message: "<div class='text-center'>{{ trans('app.successes.saved_successfully') }}</div>"
                        }, {
                            type: 'success',
                            allow_dismiss: false,
                            placement: {
                                from: "top",
                                align: "center"
                            },
                            delay: 100,
                            timer: 500
                        });
                        $('a[href="' + window.location.hash + '"]').trigger('click');
                        location.reload();
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
    }
</script>

@if(count($utilaOld) > 0 && count($utilbOld) > 0)
@include('finance_en.show.utility')
@endif