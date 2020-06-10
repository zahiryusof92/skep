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
                                        @foreach ($month as $months)
                                        <option value="{{ $months }}">{{ $months }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.year') }}</label>
                                    <select id="year" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @for ($i = 2012; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i}}</option>
                                        @endfor
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
                    <table class="table table-hover nowrap" id="filelist" width="100%">
                        <thead>
                            <tr>
                                <th style="width:20%;">{{ trans('app.forms.finance_management') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.strata') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.cob') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.month') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.year') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                @if ($update_permission == 1)
                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                @endif
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
            "sAjaxSource": "{{URL::action('FinanceController@getFinanceList')}}",
            "lengthMenu": [[15, 30, 50, -1], [15, 30, 50, "All"]],
            "order": [[0, "asc"]],
            responsive: true
        });

        $('#company').on('change', function () {
            oTable.columns(2).search(this.value).draw();
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
