@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 1;
$update_permission = 1;
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
                        <button onclick="window.location = '{{ URL::action('AgmController@addDefect') }}'" type="button" class="btn btn-primary margin-bottom-25">
                            {{ trans('app.buttons.add_defect') }}
                        </button>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 text-center">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.file_no') }}</label>
                                    <select id="file_no" class="form-control select2">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($files as $files_no)
                                        <option value="{{ $files_no->file_no }}">{{ $files_no->file_no }}</option>
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
                    <table class="table table-hover nowrap" id="defect" width="100%">
                        <thead>
                            <tr>
                                <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                <th style="width:15%;">{{ trans('app.forms.defect_category') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.defect_name') }}</th>
                                <th style="width:35%;">{{ trans('app.forms.defect_description') }}</th>
                                <th style="width:35%;">{{ trans('app.forms.status') }}</th>
                                <?php if ($update_permission == 1) { ?>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
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
    $(defect).ready(function () {
        oTable = $('#defect').DataTable({
            "sAjaxSource": "{{URL::action('AgmController@getDefect')}}",
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[0, "asc"]],
            "responsive": true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });

        $('#file_no').on('change', function () {
            oTable.columns(0).search(this.value).draw();
        });
    });

    function deleteDefect(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        }, function () {
            $.ajax({
                url: "{{ URL::action('AgmController@deleteDefect') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function (data) {
                    if (data.trim() == "true") {
                        swal({
                            title: "{{ trans('app.successes.deleted_title') }}",
                            text: "{{ trans('app.successes.deleted_text_file') }}",
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

@stop
