@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 38) {
        $update_permission = $permission->update_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <form>
                        <div class="row">
                            @if (Auth::user()->getAdmin())
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.cob') }}</label>
                                    <select id="company" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($cob as $companies)
                                        <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.month') }}</label>
                                    <select id="month" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($month as $val => $months)
                                        <option value="{{ $val }}">{{ $months }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.year') }}</label>
                                    <select id="year" class="form-control select2">
                                        @foreach ($year as $value => $years)
                                        <option value="{{ $value }}">{{ $years }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-hover" id="filelist" width="100%" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.finance_management') }}</th>
                                <th style="width:25%;">{{ trans('app.forms.strata') }}</th>                                
                                <th style="width:10%;">{{ trans('app.forms.month') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                <th style="width:15%;">{{ trans('app.forms.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#filelist').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('FinanceController@getFinanceList') }}",
            lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
            pageLength: 30,
            order: [[0, "asc"], [1, 'asc'], [3, 'desc'], [4, 'desc']],
            responsive: false,
            scrollX: true,
            columns: [
                {data: 'cob', name: 'company.short_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'month', name: 'finance_file.month'},
                {data: 'year', name: 'finance_file.year'},
                {data: 'active', name: 'files.is_active', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#company').on('change', function () {
            oTable.columns(0).search(this.value).draw();
        });
        $('#month').on('change', function () {
            oTable.columns(3).search(this.value).draw();
        });
        $('#year').on('change', function () {
            oTable.columns(4).search(this.value).draw();
        });
    });

    function inactiveFinanceList(id) {
        $.ajax({
            url: "{{ URL::action('FinanceController@inactiveFinanceList') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('FinanceController@financeList')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activeFinanceList(id) {
        $.ajax({
            url: "{{ URL::action('FinanceController@activeFinanceList') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('FinanceController@financeList')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deleteFinanceList(id) {
        bootbox.confirm("{{ trans('app.confirmation.are_you_sure_delete_file') }}", function (result) {
            if (result) {
                $.ajax({
                    url: "{{ URL::action('FinanceController@deleteFinanceList') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data.trim() == "true") {
                            bootbox.alert("<span style='color:green;'>{{ trans('app.successes.deleted_successfully') }}</span>", function () {
                                window.location = "{{URL::action('FinanceController@financeList')}}";
                            });
                        } else {
                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    }
                });
            }
        });
    }
</script>

@stop
