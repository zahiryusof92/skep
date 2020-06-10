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
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <div id="update_files_lists">
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@house', $files->id)}}">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@strata', $files->id)}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@management', $files->id)}}">{{ trans('app.forms.management') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@monitoring', $files->id)}}">{{ trans('app.forms.monitoring') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@others', $files->id)}}">{{ trans('app.forms.others') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@scoring', $files->id)}}">{{ trans('app.forms.scoring_component_value') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active">{{ trans('app.forms.buyer_list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@document', $files->id)}}">{{ trans('app.forms.document') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="buyer_tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <?php if ($update_permission == 1) { ?>
                                            <button onclick="window.location = '{{ URL::action('AdminController@addBuyer', $files->id) }}'" type="button" class="btn btn-primary">
                                                {{ trans('app.buttons.add_buyer') }}
                                            </button>
                                            &nbsp;

                                            @if (strtoupper(Auth::user()->getRole->name) != 'JMB')
                                            <button onclick="window.location = '{{ URL::action('AdminController@importBuyer', $files->id) }}'" type="button" class="btn btn-primary">
                                                {{ trans('app.forms.import_csv_file') }}
                                            </button>
                                            &nbsp;
                                            <a href="{{asset('files/buyer_template.csv')}}" target="_blank">
                                                <button type="button" class="btn btn-success pull-right">
                                                    {{ trans('app.forms.download_csv_template') }}
                                                </button>
                                            </a>
                                            @endif

                                            <br/><br/>
                                            <?php } ?>
                                            <table class="table table-hover nowrap" id="buyer_list">
                                                <thead>
                                                    <tr>
                                                        <th style="width:5%;">{{ trans("app.forms.no") }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.unit_number') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.unit_share') }}</th>
                                                        <th style="width:50%;">{{ trans('app.forms.owner_name') }}</th>
                                                        <th style="width:20%;">{{ trans('app.forms.ic_company_number') }}</th>
                                                        <?php if ($update_permission == 1) { ?>
                                                        <th style="width:5%;">{{ trans('app.forms.action') }}</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
            "order": [[0, "asc"]],
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
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
