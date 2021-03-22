@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 35) {
        $insert_permission = $permission->insert_permission;
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
                    <?php if ($insert_permission == 1) { ?>
                    <button onclick="window.location = '{{ URL::action('CobController@add', $company->id) }}'" type="button" class="btn btn-own">
                        {{ trans('general.label_create') }}
                    </button>
                    <br/><br/>
                    <?php } ?>
                    <table class="table table-hover nowrap" id="form" width="100%">
                        <thead>
                            <tr>
                                <th style="width:30%;">{{ trans('cob.table.file_name') }}</th>
                                <th style="width:70%;">{{ trans('cob.table.document') }}</th>
                                <?php if ($update_permission == 1) { ?>
                                <th style="width:10%;">{{ trans('general.label_action') }}</th>
                                <?php } ?>
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
        oTable = $('#form').DataTable({
            "sAjaxSource": "{{ url('cob/' . $company->id . '/get-data') }}",
            // "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[ 0, "asc" ]],
            responsive: true
        });
    });    
</script>
@stop