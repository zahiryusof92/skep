<?php
$prefix = 'contract_';
?>

<div class="row padding-vertical-10">
    <div class="col-lg-12">

        <h6>4.2 LAPORAN PERBELANJAAN PENYENGGARAAN</h6>

        <form id="form_contract">

            <div class="row">
                <table class="table table-sm borderless" id="dynamic_form_contract" style="font-size: 12px;">
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

                        @foreach ($contractFile as $contractFiles)
                        <?php
                        $total_tunggakan += $contractFiles['tunggakan'];
                        $total_semasa += $contractFiles['semasa'];
                        $total_hadapan += $contractFiles['hadapan'];
                        $total_tertunggak += $contractFiles['tertunggak'];
                        $total_income = $contractFiles['tunggakan'] + $contractFiles['semasa'] + $contractFiles['hadapan'];
                        $total_all += $total_income;
                        ?>
                        <tr id="contract_row{{ ++$count }}">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $contractFiles['is_custom'] }}">{{ $count }}</td>
                            <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $contractFiles['name'] }}" {{ $contractFiles['is_custom'] ? '' : 'readonly' }}></td>
                            <td><input type="currency" oninput="calculateContract('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right" value="{{ $contractFiles['tunggakan'] }}"></td>
                            <td><input type="currency" oninput="calculateContract('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right" value="{{ $contractFiles['semasa'] }}"></td>
                            <td><input type="currency" oninput="calculateContract('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right" value="{{ $contractFiles['hadapan'] }}"></td>
                            <td><input type="currency" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right" value="{{ $total_income }}" readonly=""></td>
                            <td><input type="currency" oninput="calculateContractTotal('{{ $count }}')" id="{{ $prefix . 'tertunggak_' . $count }}" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right" value="{{ $contractFiles['tertunggak'] }}"></td>
                            @if ($contractFiles['is_custom'])
                            <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowContract('contract_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td class="padding-table text-right" colspan="8"><a href="javascript:void(0);" onclick="addRowContract()" class="btn btn-own btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH</th>
                            <th><input type="currency" id="{{ $prefix . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ $total_tunggakan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ $total_semasa }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ $total_hadapan }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ $total_all }}" readonly=""></th>
                            <th><input type="currency" id="{{ $prefix . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ $total_tertunggak }}" readonly=""></th>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if ($update_permission == 1) { ?>
                <div class="form-actions">
                    <input type="hidden" name="finance_file_id" value="{{ $financefiledata->id }}"/>
                    <button type="button"class="btn btn-own submit_button" onclick="submitContract()">{{ trans("app.forms.submit") }}</button>
                    <img class="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>    
                </div>
            <?php } ?>

        </form>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        calculateContractTotal();
    });

    function calculateContract(id) {
        var contract_sum_tunggakan = 0;
        var contract_sum_semasa = 0;
        var contract_sum_hadapan = 0;
        var contract_sum_total_income = 0;

        var contract_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        contract_sum_tunggakan += Number(contract_tunggakan.value);

        var contract_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        contract_sum_semasa += Number(contract_semasa.value);

        var contract_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        contract_sum_hadapan += Number(contract_hadapan.value);

        contract_sum_total_income += Number(contract_sum_tunggakan) + Number(contract_sum_semasa) + Number(contract_sum_hadapan);
        $('#{{ $prefix }}total_income_' + id).val(parseFloat(contract_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateContractTotal();
        calculateSummaryTotal();
    }

    function calculateContractTotal() {
        var contract_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var contract_sum_total_tunggakan = 0;
        for (var i = 0; i < contract_total_tunggakan.length; i++) {
            contract_sum_total_tunggakan += Number(contract_total_tunggakan[i].value);
        }
        $('#{{ $prefix }}total_tunggakan').val(parseFloat(contract_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var contract_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var contract_sum_total_semasa = 0;
        for (var i = 0; i < contract_total_semasa.length; i++) {
            contract_sum_total_semasa += Number(contract_total_semasa[i].value);
        }
        $('#{{ $prefix }}total_semasa').val(parseFloat(contract_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var contract_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var contract_sum_total_hadapan = 0;
        for (var i = 0; i < contract_total_hadapan.length; i++) {
            contract_sum_total_hadapan += Number(contract_total_hadapan[i].value);
        }
        $('#{{ $prefix }}total_hadapan').val(parseFloat(contract_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var contract_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < contract_total_income.length; i++) {
            $('#' + contract_total_income[i].id).val(parseFloat(contract_total_income[i].value).toFixed(2));
        }

        var contract_total_tertunggak = document.getElementsByName("{{ $prefix }}tertunggak[]");
        var contract_sum_total_tertunggak = 0;
        for (var i = 0; i < contract_total_tertunggak.length; i++) {
            contract_sum_total_tertunggak += Number(contract_total_tertunggak[i].value);
        }
        $('#{{ $prefix }}total_tertunggak').val(parseFloat(contract_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var contract_sum_total_all = Number(contract_sum_total_tunggakan) + Number(contract_sum_total_semasa) + Number(contract_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#{{ $prefix }}total_all').val(parseFloat(contract_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function addRowContract() {
        changes = true;

        var rowContractNo = $("#dynamic_form_contract tr").length;
        rowContractNo = rowContractNo - 2;
        $("#dynamic_form_contract tr:last").prev().prev().after('<tr id="contract_row' + rowContractNo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="1">' + rowContractNo + '</td><td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value=""></td><td><input type="currency" oninput="calculateContract(\'' + rowContractNo + '\')" id="{{ $prefix }}tunggakan_' + rowContractNo + '" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" oninput="calculateContract(\'' + rowContractNo + '\')" id="{{ $prefix }}semasa_' + rowContractNo + '" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" oninput="calculateContract(\'' + rowContractNo + '\')" id="{{ $prefix }}hadapan_' + rowContractNo + '" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" id="{{ $prefix }}total_income_' + rowContractNo + '" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right" value="0" readonly=""></td><td><input type="currency" oninput="calculateContractTotal(\'' + rowContractNo + '\')" id="{{ $prefix }}tertunggak_' + rowContractNo + '" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowContract(\'contract_row' + rowContractNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateContractTotal();
    }

    function deleteRowContract(rowContractNo) {
        changes = true;

        $('#' + rowContractNo).remove();

        calculateContractTotal();
    }

    function submitContract() {
        error = 0;
        var data = $("#form_contract").serialize();

        $(".loading").css("display", "inline-block");
        $(".submit_button").attr("disabled", "disabled");
        $("#check_mandatory_fields").css("display", "none");

        if (error == 0) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            $.ajax({
                method: "POST",
                url: "{{ URL::action('FinanceController@updateFinanceFileContract') }}",
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
