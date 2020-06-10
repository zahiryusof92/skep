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
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <button onclick="window.location = '{{ URL::action('FinanceController@addFinanceSupport') }}'" type="button" class="btn btn-primary">
                        {{ trans('app.buttons.add_finance_support') }}
                    </button>
                    <br/><br/>
                    <table class="table table-hover nowrap" id="filelist" width="100%">
                        <thead>
                            <tr>
                                <th style="width:30%;">{{ trans('app.forms.file_no') }}.</th>
                                <th style="width:20%;">{{ trans('app.forms.date') }}</th>
                                <th style="width:30%;">{{ trans('app.forms.donation_name') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.donation_amount') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.action') }}</th>
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
            "sAjaxSource": "{{URL::action('FinanceController@getFinanceSupportList')}}",
            "lengthMenu": [[15, 30, 50, -1], [15, 30, 50, "All"]],
            "order": [[0, "asc"]],
            responsive: true
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
