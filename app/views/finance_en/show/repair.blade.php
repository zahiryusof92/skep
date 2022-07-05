<?php
$old_prefix = 'repair_maintenancefee_old_';
$old_prefix2 = 'repair_singkingfund_old_';
?>

<div class="row padding-vertical-10">
    <div class="col-lg-12">

        <h6><u>{{ trans("app.forms.before_change") }}</u></h6>
        <h6>4.3 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN a. Guna Duit Maintenance Fee</h6>

        <fieldset disabled>

            <div class="row">
                <table class="table table-sm borderless" id="old_dynamic_form_repair_a" style="font-size: 12px;">
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

                        @foreach ($repairaOld as $item)
                        <?php
                        $total_tunggakan += $item->tunggakan;
                        $total_semasa += $item->semasa;
                        $total_hadapan += $item->hadapan;
                        $total_tertunggak += $item->tertunggak;
                        $total_income = $item->tunggakan + $item->semasa + $item->hadapan;
                        $total_all += $total_income;
                        ?>
                        <tr id="repaira_row{{ ++$count }}">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $old_prefix }}is_custom[]" value="{{ $item->is_custom }}">{{ $count }}</td>
                            <td><input type="text" name="{{ $old_prefix }}name[]" class="form-control form-control-sm" value="{{ $item->name }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'tunggakan_' . $count }}" name="{{ $old_prefix }}tunggakan[]" class="form-control form-control-sm text-right" value="{{ $item->tunggakan }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'semasa_' . $count }}" name="{{ $old_prefix }}semasa[]" class="form-control form-control-sm text-right" value="{{ $item->semasa }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'hadapan_' . $count }}" name="{{ $old_prefix }}hadapan[]" class="form-control form-control-sm text-right" value="{{ $item->hadapan }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'total_income_' . $count }}" name="{{ $old_prefix }}total_income[]" class="form-control form-control-sm text-right" value="{{ number_format($total_income, 2, '.', '') }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'tertunggak_' . $count }}" name="{{ $old_prefix }}tertunggak[]" class="form-control form-control-sm text-right" value="{{ $item->tertunggak }}"></td>
                            <td>&nbsp;</td>
                        </tr>
                        @endforeach

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH</th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_tunggakan, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_semasa, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_hadapan, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_all, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_tertunggak, 2, '.', '') }}"></th>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr/>

            <h6>4.3 PEMBAIKAN/PENGGANTIAN/PEMBELIAN/NAIKTARAF/PEMBAHARUAN b. Guna Duit Sinking Fund</h6>

            <div class="row">
                <table class="table table-sm" id="old_dynamic_form_repair_b" style="font-size: 12px;">
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

                        @foreach ($repairbOld as $item)
                        <?php
                        $totalb_tunggakan += $item->tunggakan;
                        $totalb_semasa += $item->semasa;
                        $totalb_hadapan += $item->hadapan;
                        $totalb_tertunggak += $item->tertunggak;
                        $totalb_income = $item->tunggakan + $item->semasa + $item->hadapan;
                        $totalb_all += $totalb_income;
                        ?>
                        <tr id="repairb_row{{ ++$countb }}">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $old_prefix2 }}is_custom[]" value="{{ $item->is_custom }}">{{ $countb }}</td>
                            <td><input type="text" name="{{ $old_prefix2 }}name[]" class="form-control form-control-sm" value="{{ $item->name }}"></td>
                            <td><input type="currency"id="{{ $old_prefix2 . 'tunggakan_' . $countb }}" name="{{ $old_prefix2 }}tunggakan[]" class="form-control form-control-sm text-right" value="{{ $item->tunggakan }}"></td>
                            <td><input type="currency"id="{{ $old_prefix2 . 'semasa_' . $countb }}" name="{{ $old_prefix2 }}semasa[]" class="form-control form-control-sm text-right" value="{{ $item->semasa }}"></td>
                            <td><input type="currency"id="{{ $old_prefix2 . 'hadapan_' . $countb }}" name="{{ $old_prefix2 }}hadapan[]" class="form-control form-control-sm text-right" value="{{ $item->hadapan }}"></td>
                            <td><input type="currency" id="{{ $old_prefix2 . 'total_income_' . $countb }}" name="{{ $old_prefix2 }}total_income[]" class="form-control form-control-sm text-right" value="{{ number_format($totalb_income, 2, '.', '')  }}"></td>
                            <td><input type="currency" id="{{ $old_prefix2 . 'tertunggak_' . $countb }}" name="{{ $old_prefix2 }}tertunggak[]" class="form-control form-control-sm text-right" value="{{ $item->tertunggak }}"></td>
                            <td>&nbsp;</td>
                        </tr>
                        @endforeach

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH</th>
                            <th><input type="currency" id="{{ $old_prefix2 . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ number_format($totalb_tunggakan, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix2 . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ number_format($totalb_semasa, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix2 . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ number_format($totalb_hadapan, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix2 . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ number_format($totalb_all, 2, '.', '') }}"></th>
                            <th><input type="currency" id="{{ $old_prefix2 . 'total_tertunggak' }}" class="form-control form-control-sm text-right" value="{{ number_format($totalb_tertunggak, 2, '.', '') }}"></th>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </fieldset>

    </div>
</div>

<hr>