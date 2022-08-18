<?php
$prefix = 'staff_';
?>

<div class="row padding-vertical-10">
    <div class="col-lg-12">

        @if(count($staffOldFile) > 0)
            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
        @endif
        <h6>4.5 LAPORAN PERBELANJAAN PEKERJA</h6>

        <form id="form_staff">

            <div class="row">
                <table class="table table-sm borderless" id="dynamic_form_staff" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th width="5%">&nbsp;</th>
                            <th width="10%" style="text-align: center;">PERKARA</th>
                            <th width="10%" style="text-align: center;">GAJI PERORANG (RM)<br/>A</th>
                            <th width="10%" style="text-align: center;">BIL. PEKERJA<br/>B</th>
                            <th width="10%" style="text-align: center;">JUMLAH GAJI<br/>A x B</th>
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
                        $total_all_gaji = 0;
                        $total_tunggakan = 0;
                        $total_semasa = 0;
                        $total_hadapan = 0;
                        $total_tertunggak = 0;
                        $total_all = 0;
                        ?>

                        @foreach ($staffFile as $staffFiles)
                        <?php
                        $gaji_per_person = $staffFiles['gaji_per_orang'];
                        $bil_pekerja = $staffFiles['bil_pekerja'];
                        $total_gaji = $staffFiles['gaji_per_orang'] * $staffFiles['bil_pekerja'];
                        $total_all_gaji += $staffFiles['gaji_per_orang'] * $staffFiles['bil_pekerja'];
                        $total_tunggakan += $staffFiles['tunggakan'];
                        $total_semasa += $staffFiles['semasa'];
                        $total_hadapan += $staffFiles['hadapan'];
                        $total_tertunggak += $staffFiles['tertunggak'];
                        $total_income = $staffFiles['tunggakan'] + $staffFiles['semasa'] + $staffFiles['hadapan'];
                        $total_all += $total_income;
                        ?>

                        <tr id="staff_row{{ ++$count }}">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="{{ $staffFiles['is_custom'] }}">{{ $count }}</td>
                            <td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value="{{ $staffFiles['name'] }}" {{ $staffFiles['is_custom'] ? '' : 'readonly' }}></td>
                            <td><input type="currency" oninput="calculateStaff('{{ $count }}')" id="{{ $prefix . 'gaji_per_orang_' . $count }}" name="{{ $prefix }}gaji_per_orang[]" class="form-control form-control-sm text-right" value="{{ $staffFiles['gaji_per_orang'] }}"></td>
                            <td><input type="digit" oninput="calculateStaff('{{ $count }}')" id="{{ $prefix . 'bil_pekerja_' . $count }}" name="{{ $prefix }}bil_pekerja[]" class="form-control form-control-sm text-right" value="{{ $staffFiles['bil_pekerja'] }}"></td>
                            <td><input type="currency" id="{{ $prefix . 'total_gaji_' . $count }}" name="{{ $prefix }}total_gaji[]" class="form-control form-control-sm text-right" value="{{ $total_gaji }}" readonly=""></td>
                            <td><input type="currency" oninput="calculateStaff('{{ $count }}')" id="{{ $prefix . 'tunggakan_' . $count }}" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right" value="{{ $staffFiles['tunggakan'] }}"></td>
                            <td><input type="currency" oninput="calculateStaff('{{ $count }}')" id="{{ $prefix . 'semasa_' . $count }}" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right" value="{{ $staffFiles['semasa'] }}"></td>
                            <td><input type="currency" oninput="calculateStaff('{{ $count }}')" id="{{ $prefix . 'hadapan_' . $count }}" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right" value="{{ $staffFiles['hadapan'] }}"></td>
                            <td><input type="currency" id="{{ $prefix . 'total_income_' . $count }}" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right" value="{{ $total_income }}" readonly=""></td>
                            <td><input type="currency" oninput="calculateStaffTotal('{{ $count }}')" id="{{ $prefix . 'tertunggak_' . $count }}" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right" value="{{ $staffFiles['tertunggak'] }}"></td>
                            @if ($staffFiles['is_custom'])
                            <td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowStaff('staff_row<?php echo $count ?>')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td>
                            @else
                            <td>&nbsp;</td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td class="padding-table text-right" colspan="11"><a href="javascript:void(0);" onclick="addRowStaff()" class="btn btn-success btn-xs">{{ trans("app.forms.add_more") }}</a></td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form" colspan="3">JUMLAH</th>
                            <th><input type="currency" id="{{ $prefix . 'total_gaji' }}" class="form-control form-control-sm text-right" value="{{ $total_all_gaji }}" readonly=""></th>
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
                    <input type="hidden" name="finance_file_id" value="{{ \Helper\Helper::encode($financefiledata->id) }}"/>
                    <button type="button"class="btn btn-own submit_button" onclick="submitStaff()">{{ trans("app.forms.submit") }}</button>
                    <img class="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>   
                </div>
            <?php } ?>

        </form>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        calculateStaffTotal();
        
        // summary staff
        $('#sum_staff').val($('#staff_total_all').val());
        calculateSummaryTotal();
    });

    function calculateStaff(id) {
        var staff_sum_gaji_per_orang = 0;
        var staff_sum_bil_pekerja = 0;
        var staff_sum_total_gaji = 0;
        var staff_sum_tunggakan = 0;
        var staff_sum_semasa = 0;
        var staff_sum_hadapan = 0;
        var staff_sum_total_income = 0;

        var staff_gaji_per_orang = document.getElementById("{{ $prefix }}gaji_per_orang_" + id);
        staff_sum_gaji_per_orang += Number(staff_gaji_per_orang.value);

        var staff_bil_pekerja = document.getElementById("{{ $prefix }}bil_pekerja_" + id);
        staff_sum_bil_pekerja += Number(staff_bil_pekerja.value);

        staff_sum_total_gaji += Number(staff_sum_gaji_per_orang) * Number(staff_sum_bil_pekerja);
        $('#staff_total_gaji_' + id).val(parseFloat(staff_sum_total_gaji).toFixed(2)); // UPDATE JUMLAH A + B + C

        var staff_tunggakan = document.getElementById("{{ $prefix }}tunggakan_" + id);
        staff_sum_tunggakan += Number(staff_tunggakan.value);

        var staff_semasa = document.getElementById("{{ $prefix }}semasa_" + id);
        staff_sum_semasa += Number(staff_semasa.value);

        var staff_hadapan = document.getElementById("{{ $prefix }}hadapan_" + id);
        staff_sum_hadapan += Number(staff_hadapan.value);

        staff_sum_total_income += Number(staff_sum_tunggakan) + Number(staff_sum_semasa) + Number(staff_sum_hadapan);
        $('#staff_total_income_' + id).val(parseFloat(staff_sum_total_income).toFixed(2)); // UPDATE JUMLAH A + B + C

        calculateStaffTotal();
        calculateSummaryTotal();
    }

    function calculateStaffTotal() {
        var staff_total_gaji = document.getElementsByName("{{ $prefix }}total_gaji[]");
        var staff_sum_total_gaji = 0;
        for (var i = 0; i < staff_total_gaji.length; i++) {
            staff_sum_total_gaji += Number(staff_total_gaji[i].value);
            $('#' + staff_total_gaji[i].id).val(Number(staff_total_gaji[i].value).toFixed(2));
        }
        $('#staff_total_gaji').val(parseFloat(staff_sum_total_gaji).toFixed(2)); // UPDATE JUMLAH SEMUA GAJI

        var staff_total_tunggakan = document.getElementsByName("{{ $prefix }}tunggakan[]");
        var staff_sum_total_tunggakan = 0;
        for (var i = 0; i < staff_total_tunggakan.length; i++) {
            staff_sum_total_tunggakan += Number(staff_total_tunggakan[i].value);
        }
        $('#staff_total_tunggakan').val(parseFloat(staff_sum_total_tunggakan).toFixed(2)); // UPDATE JUMLAH SEMUA A

        var staff_total_semasa = document.getElementsByName("{{ $prefix }}semasa[]");
        var staff_sum_total_semasa = 0;
        for (var i = 0; i < staff_total_semasa.length; i++) {
            staff_sum_total_semasa += Number(staff_total_semasa[i].value);
        }
        $('#staff_total_semasa').val(parseFloat(staff_sum_total_semasa).toFixed(2)); // UPDATE JUMLAH SEMUA B

        var staff_total_hadapan = document.getElementsByName("{{ $prefix }}hadapan[]");
        var staff_sum_total_hadapan = 0;
        for (var i = 0; i < staff_total_hadapan.length; i++) {
            staff_sum_total_hadapan += Number(staff_total_hadapan[i].value);
        }
        $('#staff_total_hadapan').val(parseFloat(staff_sum_total_hadapan).toFixed(2)); // UPDATE JUMLAH SEMUA C

        var staff_total_income = document.getElementsByName("{{ $prefix }}total_income[]");
        for (var i = 0; i < staff_total_income.length; i++) {
            $('#' + staff_total_income[i].id).val(parseFloat(staff_total_income[i].value).toFixed(2));
        }

        var staff_total_tertunggak = document.getElementsByName("{{ $prefix }}tertunggak[]");
        var staff_sum_total_tertunggak = 0;
        for (var i = 0; i < staff_total_tertunggak.length; i++) {
            staff_sum_total_tertunggak += Number(staff_total_tertunggak[i].value);
        }
        $('#staff_total_tertunggak').val(parseFloat(staff_sum_total_tertunggak).toFixed(2)); // UPDATE JUMLAH TERTUNGGAK

        var staff_sum_total_all = Number(staff_sum_total_tunggakan) + Number(staff_sum_total_semasa) + Number(staff_sum_total_hadapan); // JUMLAH SEMUA A + B + C
        $('#staff_total_all').val(parseFloat(staff_sum_total_all).toFixed(2)); // UPDATE JUMLAH SEMUA A + B + C
    }

    function addRowStaff() {
        changes = true;

        var rowStaffNo = $("#dynamic_form_staff tr").length;
        rowStaffNo = rowStaffNo - 2;
        $("#dynamic_form_staff tr:last").prev().prev().after('<tr id="staff_row' + rowStaffNo + '"><td class="text-center padding-table"><input type="hidden" name="{{ $prefix }}is_custom[]" value="1">' + rowStaffNo + '</td><td><input type="text" name="{{ $prefix }}name[]" class="form-control form-control-sm" value=""></td><td><input type="currency" oninput="calculateStaff(\'' + rowStaffNo + '\')" id="{{ $prefix }}gaji_per_orang_' + rowStaffNo + '" name="{{ $prefix }}gaji_per_orang[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" oninput="calculateStaff(\'' + rowStaffNo + '\')" id="{{ $prefix }}bil_pekerja_' + rowStaffNo + '" name="{{ $prefix }}bil_pekerja[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" id="{{ $prefix }}total_gaji_' + rowStaffNo + '" name="{{ $prefix }}total_gaji[]" class="form-control form-control-sm text-right" value="0" readonly=""></td><td><input type="currency" oninput="calculateStaff(\'' + rowStaffNo + '\')" id="{{ $prefix }}tunggakan_' + rowStaffNo + '" name="{{ $prefix }}tunggakan[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" oninput="calculateStaff(\'' + rowStaffNo + '\')" id="{{ $prefix }}semasa_' + rowStaffNo + '" name="{{ $prefix }}semasa[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" oninput="calculateStaff(\'' + rowStaffNo + '\')" id="{{ $prefix }}hadapan_' + rowStaffNo + '" name="{{ $prefix }}hadapan[]" class="form-control form-control-sm text-right" value="0"></td><td><input type="currency" id="{{ $prefix }}total_income_' + rowStaffNo + '" name="{{ $prefix }}total_income[]" class="form-control form-control-sm text-right" value="0" readonly=""></td><td><input type="currency" oninput="calculateStaffTotal(\'' + rowStaffNo + '\')" id="{{ $prefix }}tertunggak_' + rowStaffNo + '" name="{{ $prefix }}tertunggak[]" class="form-control form-control-sm text-right" value="0"></td><td class="padding-table text-right"><a href="javascript:void(0);" onclick="deleteRowStaff(\'staff_row' + rowStaffNo + '\')" class="btn btn-danger btn-xs">{{ trans("app.forms.remove") }}</a></td></tr>');

        calculateStaffTotal();
    }

    function deleteRowStaff(rowStaffNo) {
        changes = true;

        $('#' + rowStaffNo).remove();

        calculateStaffTotal();
    }

    function submitStaff() {
        error = 0;
        var data = $("#form_staff").serialize();

        $(".loading").css("display", "inline-block");
        $(".submit_button").attr("disabled", "disabled");
        $("#check_mandatory_fields").css("display", "none");

        if (error == 0) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            $.ajax({
                method: "POST",
                url: "{{ URL::action('FinanceController@updateFinanceFileStaff') }}",
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

@if(count($staffOldFile) > 0)
@include('finance_en.show.staff')
@endif
