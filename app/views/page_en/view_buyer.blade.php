@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 3) {
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
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file', ['files' => $files, 'is_view' => true])

                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="buyer_tab" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-20">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover nowrap" id="buyer_list">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5%;">{{ trans("app.forms.no") }}</th>
                                                            <th style="width:10%;">{{ trans('app.forms.unit_number') }}</th>
                                                            <th style="width:10%;">{{ trans('app.forms.unit_share') }}</th>
                                                            <th style="width:50%;">{{ trans('app.forms.owner_name') }}</th>
                                                            <th style="width:20%;">{{ trans('app.forms.ic_company_number') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(document).ready(function () {
        $('#buyer_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getBuyerList', $files->id)}}",
            "order": [[0, "asc"]]
        });
    });

    function deleteBuyer(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
                function () {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteBuyer') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.trim() == "true") {
                                $.notify({
                                    message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.deleted_successfully") }}</p>'
                                }, {
                                    type: 'success',
                                    placement: {
                                        align: "center"
                                    }
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
<!-- End Page Scripts-->

@stop
