@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        @include('alert.bootbox')

                        <dl class="row">
                            <dt class="col-sm-3">
                                {{ trans('app.forms.cob') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->company ? $model->company->name : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.file_no') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->file ? $model->file->file_no : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.strata') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ($model->strata ? $model->strata->name : '') }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.type') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ ucwords($model->type) }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.development_cost') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->development_cost }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.amount') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->amount }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.date_start') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->start_date }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.maturity_date') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->maturity_date }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.balance') }} (RM)
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->balance }}
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.attachment') }}
                            </dt>
                            <dd class="col-sm-9">
                                @if (!empty($model->attachment))
                                <a href="{{ asset($model->attachment) }}" target="_blank">
                                    <button type="button" class="btn btn-xs btn-success" data-toggle="tooltip"
                                        data-placement="bottom" title="{{ trans('app.forms.attachment') }}">
                                        <i class="fa fa-file-pdf-o" style="margin-right: 2px;"></i>
                                        {{ trans('app.forms.attachment') }}
                                    </button>
                                </a>
                                @else
                                -
                                @endif
                            </dd>
                            <dt class="col-sm-3">
                                {{ trans('app.forms.status') }}
                            </dt>
                            <dd class="col-sm-9">
                                {{ $model->getStatusBadge() }}
                            </dd>
                            <dt class="col-sm-3">
                                &nbsp;
                            </dt>
                            <dd class="col-sm-9">
                                <button class="btn btn-sm btn-info" onclick="returnDeposit()">
                                    {{ trans('Return the deposit') }}
                                </button>
                            </dd>
                        </dl>
                    </div>
                </div>
            </section>

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <table class="table table-hover nowrap table-own table-striped" id="dlp_deposit_usage_table"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.created_at') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.description') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.amount') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-default" id="cancel_button"
                        onclick="window.location ='{{ route('dlp.deposit') }}'">
                        {{ trans('app.forms.back') }}
                    </button>
                </div>
            </section>

        </div>
    </section>
</div>

<script>
    $(document).ready( function () {
        let oTable = $('#dlp_deposit_usage_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dlp.deposit.usage', \Helper\Helper::encode($model->id)) }}",
            lengthMenu: [[15, 30, 50], [15, 30, 50]],
            pageLength: 15,
            order: [[0, "desc"]],
            columns: [         
                {data: 'created_at', name: 'created_at'},   
                {data: 'description', name: 'description'}, 
                {data: 'amount', name: 'amount'},          
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}],
            responsive: false,
            scrollX: true,
        });
    });

    function returnDeposit(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Return",
            closeOnConfirm: true
        }, function () {
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
                    if (data.trim() == "true") {
                        swal({
                            title: "{{ trans('app.successes.deleted_title') }}",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            closeOnConfirm: false
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        });
    }
</script>
@endsection