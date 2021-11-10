<?php
$old_prefix = 'income_old_';
?>

<div class="row padding-vertical-10">
    <div class="col-lg-12">

        <h6><u>{{ trans("app.forms.before_change") }}</u></h6>
        <h6>3. LAPORAN PENDAPATAN</h6>

        <fieldset disabled>
            <div class="row">
                <table class="table table-sm borderless" id="dynamic_form_income_old" style="font-size: 12px;">
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

                        @foreach ($incomeOldFile as $item)
                        <?php
                        $total_tunggakan += $item->tunggakan;
                        $total_semasa += $item->semasa;
                        $total_hadapan += $item->hadapan;
                        $total_income = $item->tunggakan + $item->semasa + $item->hadapan;
                        $total_all += $total_income;
                        ?>
                        <tr id="income_row{{ ++$count }}">
                            <td class="text-center padding-table"><input type="hidden" name="{{ $old_prefix }}is_custom[]" value="{{ $item->is_custom }}">{{ $count }}</td>
                            <td><input type="text" name="{{ $old_prefix }}name[]" class="form-control form-control-sm" value="{{ $item->name }}" {{ $item->is_custom ? '' : 'readonly' }}></td>
                            <td><input type="currency" id="{{ $old_prefix . 'tunggakan_' . $count }}" name="{{ $old_prefix }}tunggakan[]" class="form-control form-control-sm text-right income_tunggakan" value="{{ $item->tunggakan }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'semasa_' . $count }}" name="{{ $old_prefix }}semasa[]" class="form-control form-control-sm text-right" value="{{ $item->semasa }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'hadapan_' . $count }}" name="{{ $old_prefix }}hadapan[]" class="form-control form-control-sm text-right" value="{{ $item->hadapan }}"></td>
                            <td><input type="currency" id="{{ $old_prefix . 'total_income_' . $count }}" name="{{ $old_prefix }}total_income[]" class="form-control form-control-sm text-right" value="{{ number_format($total_income, 2, '.', '') }}" readonly=""></td>
                            <td>&nbsp;</td>
                        </tr>
                        @endforeach

                        <tr>
                            <td>&nbsp;</td>
                            <th class="padding-form">JUMLAH</th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_tunggakan' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_tunggakan, 2, '.', '') }}" readonly=""></th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_semasa' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_semasa, 2, '.', '') }}" readonly=""></th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_hadapan' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_hadapan, 2, '.', '') }}" readonly=""></th>
                            <th><input type="currency" id="{{ $old_prefix . 'total_all' }}" class="form-control form-control-sm text-right" value="{{ number_format($total_all, 2, '.', '') }}" readonly=""></th>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </fieldset>

    </div>
</div>

<hr>