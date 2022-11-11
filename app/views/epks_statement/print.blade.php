@extends('layout.english_layout.print')

@section('content')
<table width="100%">
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td class="text-center">
                        <h4 class="margin-bottom-0">
                            <img src="{{ asset($company->image_url) }}" height="100px;" alt="">
                        </h4>
                    </td>
                    <td>
                        <h5 class="margin-bottom-10">
                            {{ $company->name }}
                        </h5>
                        <h6 class="margin-bottom-0">
                            {{ $title }}
                        </h6>
                    </td>
                </tr>
            </table>

            <hr />

            <div class="row pagebreak">
                <div class="col-lg-12">

                    <table class="table table-sm borderless" style="width: 100%">
                        <thead>
                            <tr>
                                <td style="width: 100%; text-align: center;">
                                    <strong>
                                        {{ Str::upper('LAPORAN BELIAN BARANGAN KITAR SEMULA') }}
                                        <br />
                                        {{ Str::upper('PUSAT KITAR STRATA BAGI PANGSAPURI') }}
                                        <br />
                                        {{ Str::upper($model->strata->strataName()) }}
                                    </strong>
                                    <br />
                                    {{ Str::upper('BAGI BULAN') }} {{ $model->monthName() }}
                                    {{ Str::upper('TAHUN') }} {{ $model->year }}
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <table class="table table-sm table-bordered" style="width: 100%">

                        <thead>
                            <tr>
                                <th style="text-align: center; width: 50%;" class="align-middle">
                                    {{ Str::upper(trans('app.epks_statement.date')) }}
                                </th>
                                <th style="text-align: center; width: 50%;" class="align-middle">
                                    {{ Str::Upper(trans('app.epks_statement.buy_amount')) }} (RM)
                                </th>
                            </tr>
                        </thead>

                        @if (!empty($buys))
                        <tbody>
                            @foreach ($buys as $date => $amount)
                            <tr>
                                <td style="text-align: center;" class="align-middle">
                                    {{ $date }}
                                </td>
                                <td style="text-align: center;" class="align-middle">
                                    {{ number_format($amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endif

                        @if (!empty($ledgers))
                        <tfoot>
                            <tr>
                                <th style="text-align: center;" class="align-middle">
                                    {{ Str::upper(trans('app.epks_statement.total_all')) }}
                                </th>
                                <th style="text-align: center;" class="align-middle">
                                    {{ number_format($ledgers['total_buy'], 2) }}
                                </th>
                            </tr>
                        </tfoot>
                        @endif

                    </table>
                </div>
            </div>

            <div class="row pagebreak">
                <div class="col-lg-12">

                    <table class="table table-sm borderless" style="width: 100%">
                        <thead>
                            <tr>
                                <td style="width: 100%; text-align: center;">
                                    <strong>
                                        {{ Str::upper('LAPORAN HASIL JUALAN KEPADA VENDOR BARANGAN KITAR SEMULA') }}
                                        <br />
                                        {{ Str::upper('PUSAT KITAR STRATA BAGI PANGSAPURI') }}
                                        <br />
                                        {{ Str::upper($model->strata->strataName()) }}
                                    </strong>
                                    <br />
                                    {{ Str::upper('BAGI BULAN') }} {{ $model->monthName() }}
                                    {{ Str::upper('TAHUN') }} {{ $model->year }}
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <table class="table table-sm table-bordered" style="width: 100%">

                        <thead>
                            <tr>
                                <th style="text-align: center; width: 50%;" class="align-middle">
                                    {{ Str::upper(trans('app.epks_statement.date')) }}
                                </th>
                                <th style="text-align: center; width: 50%;" class="align-middle">
                                    {{ Str::Upper(trans('app.epks_statement.sell_amount')) }} (RM)
                                </th>
                            </tr>
                        </thead>

                        @if (!empty($sells))
                        <tbody>
                            @foreach ($sells as $date => $amount)
                            <tr>
                                <td style="text-align: center;" class="align-middle">
                                    {{ $date }}
                                </td>
                                <td style="text-align: center;" class="align-middle">
                                    {{ number_format($amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endif

                        @if (!empty($ledgers))
                        <tfoot>
                            <tr>
                                <th style="text-align: center;" class="align-middle">
                                    {{ Str::upper(trans('app.epks_statement.total_all')) }}
                                </th>
                                <th style="text-align: center;" class="align-middle">
                                    {{ number_format($ledgers['total_sell'], 2) }}
                                </th>
                            </tr>
                        </tfoot>
                        @endif

                    </table>
                </div>
            </div>

            <div class="row pagebreak">
                <div class="col-lg-12">

                    <table class="table table-sm borderless" style="width: 100%">
                        <thead>
                            <tr>
                                <td style="width: 100%; text-align: center;">
                                    <strong>
                                        {{ Str::upper('Penyata Untung Rugi Pusat Kitar Strata') }}
                                        <br />
                                        {{ Str::upper($model->strata->strataName()) }}
                                    </strong>
                                    <br />
                                    {{ Str::upper('BAGI BULAN') }} {{ $model->monthName() }}
                                    {{ Str::upper('TAHUN') }} {{ $model->year }}
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <table class="table table-sm table-bordered" style="width: 100%">

                        <thead>
                            <tr>
                                <th style="text-align: center; width: 60%;" class="align-middle">
                                    &nbsp;
                                </th>
                                <th style="text-align: center; width: 20%;" class="align-middle">
                                    RM
                                </th>
                                <th style="text-align: center; width: 20%;" class="align-middle">
                                    RM
                                </th>
                            </tr>
                        </thead>

                        @if (!empty($ledgers))
                        <tbody>
                            <tr>
                                <th colspan="3" class="align-middle;">
                                    <u>
                                        {{ Str::upper(trans('app.epks_statement.income')) }}
                                    </u>
                                </th>
                            </tr>
                            <tr>
                                <td class="align-middle">
                                    {{ trans("app.epks_statement.sell") }}
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['total_sell'], 2) }}
                                </td>
                                <td class="align-middle">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle">
                                    {{ trans("app.epks_statement.others_income") }}
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['others_income'], 2) }}
                                </td>
                                <td class="align-middle">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="align-middle">
                                    {{ trans("app.epks_statement.total") }}
                                </th>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['total_income'], 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3" class="align-middle">
                                    <u>
                                        {{ Str::upper(trans('app.epks_statement.product_cost')) }}
                                    </u>
                                </th>
                            </tr>
                            <tr>
                                <td class="align-middle">
                                    {{ trans("app.epks_statement.sell_cost") }}
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['sell_cost'], 2) }}
                                </td>
                                <td class="align-middle">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="align-middle">
                                    {{ trans("app.epks_statement.total") }}
                                </th>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['total_product_cost'], 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="align-middle">
                                    {{ trans("app.epks_statement.gross_profit") }}
                                </th>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['gross_profit'], 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3" class="align-middle">
                                    <u>
                                        {{ Str::upper(trans('app.epks_statement.expenses')) }}
                                    </u>
                                </th>
                            </tr>
                            <tr>
                                <td class="align-middle">
                                    {{ trans("app.epks_statement.buy") }}
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['total_buy'], 2) }}
                                </td>
                                <td class="align-middle">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle">
                                    {{ trans("app.epks_statement.salary") }}
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['salary'], 2) }}
                                </td>
                                <td class="align-middle">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle">
                                    {{ trans("app.epks_statement.general") }}
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['general'], 2) }}
                                </td>
                                <td class="align-middle">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="align-middle">
                                    {{ trans("app.epks_statement.total") }}
                                </th>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['total_expenses'], 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="align-middle">
                                    {{ trans("app.epks_statement.profit") }}
                                </th>
                                <td style="text-align: center;">
                                    {{ number_format($ledgers['nett_profit'], 2) }}
                                </td>
                            </tr>
                        </tbody>
                        @endif

                    </table>
                </div>
            </div>

        </td>
    </tr>
</table>
@endsection