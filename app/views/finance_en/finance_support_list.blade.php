@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 39) {
        $update_permission = $permission->update_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <form target="_blank" action="{{ url('/print/financeSupport') }}" method="POST">
                        <button onclick="window.location = '{{ URL::action('FinanceController@addFinanceSupport') }}'" type="button" class="btn btn-own">
                            {{ trans('app.buttons.add_finance_support') }}
                        </button>
                        <button type="submit" class="btn btn-own float-right">
                            <i class="fa fa-print"></i>
                        </button>
                        <br/><br/>
                        @if (Auth::user()->getAdmin())
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select id="company" name="company" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($cob as $companies)
                                            <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        </form>
                        <table class="table table-hover table-own table-striped" id="filelist" width="100%" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.strata') }}</th> 
                                    <th style="width:10%;">{{ trans('app.forms.date') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.donation_name') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.donation_amount') }}</th>
                                    <th style="width:5%;">{{ trans('app.forms.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#filelist').DataTable({
            "sAjaxSource": "{{URL::action('FinanceController@getFinanceSupportList')}}",
            "lengthMenu": [[15, 30, 50], [15, 30, 50]],
            "order": [[0, "asc"]],
            "scrollX": true,
            "responsive": false,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });
        $('#company').on('change',function() {
            oTable.columns(0).search(this.value).draw();
        });
    });

    function deleteFinanceSupport(id) {
        bootbox.confirm("{{ trans('app.confirmation.are_you_sure_delete_file') }}", function (result) {
            if (result) {
                $.ajax({
                    url: "{{ URL::action('FinanceController@deleteFinanceSupport') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data.trim() == "true") {
                            bootbox.alert("<span style='color:green;'>{{ trans('app.successes.deleted_successfully') }}</span>", function () {
                                window.location = "{{URL::action('FinanceController@financeSupport')}}";
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
