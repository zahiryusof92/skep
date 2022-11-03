@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">

            @include('alert.bootbox')

            <section class="panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered  margin-bottom-0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <th colspan="6" style="width: 100%; text-align: center;">
                                            LAPORAN HASIL JUALAN KEPADA VENDOR BARANGAN KITAR SEMULA PUSAT KITAR STRATA
                                            <br />
                                            BAGI PANGSAPURI {{ Str::upper($model->strata->strataName()) }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">{{ trans("app.forms.file_no") }}</th>
                                        <td style="width: 50%;">{{ $model->file->file_no }}</td>
                                        <th style="width: 10%;">{{ trans("app.forms.month") }}</th>
                                        <td style="width: 10%;">{{ $model->monthName() }}</td>
                                        <th style="width: 10%;">{{ trans("app.forms.year") }}</th>
                                        <td style="width: 10%;">{{ $model->year }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </section>

    <section class="panel panel-style">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-pills nav-justified" id="statementTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active custom-tab" id="buy-tab" data-toggle="tab" href="#buy" role="tab"
                                aria-controls="buy" aria-selected="true">
                                {{ trans("app.forms.buy") }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-tab" id="sell-tab" data-toggle="tab" href="#sell" role="tab"
                                aria-controls="sell" aria-selected="false">
                                {{ trans("app.forms.sell") }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link custom-tab" id="profit-tab" data-toggle="tab" href="#profit" role="tab"
                                aria-controls="profit" aria-selected="false">
                                {{ trans("app.forms.profit") }}
                            </a>
                        </li>
                    </ul>

                    <section class="panel panel-pad">
                        <form id="epks_statement_form" action="{{ route('epksStatement.store') }}" method="POST">
                            
                            <div class="tab-content padding-vertical-10" id="statementTabContent">
                                <div class="tab-pane fade active show in" id="buy" role="tabpanel"
                                    aria-labelledby="buy-tab">
                                    @include('epks_statement.fields.buy')
                                </div>

                                <div class="tab-pane fade" id="sell" role="tabpanel" aria-labelledby="sell-tab">
                                    @include('epks_statement.fields.sell')
                                </div>

                                <div class="tab-pane fade" id="profit" role="tabpanel" aria-labelledby="profit-tab">

                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-own" id="submit_button">
                                        {{ trans('app.forms.save') }}
                                    </button>
                                    <button type="button" class="btn btn-default" id="cancel_button"
                                        onclick="window.location ='{{ route('epksStatement.index') }}'">
                                        {{ trans('app.forms.cancel') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </section>

                </div>
            </div>
        </div>
    </section>

</div>
@endsection