@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 30) {
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
                        <button type="button" class="btn btn-primary margin-bottom-25" onclick="window.location = '{{ URL::action('AgmController@addAJK') }}'" >
                            {{ trans('app.buttons.add_designation') }}
                        </button>
                    <?php } ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12 text-center">
                    <form>
                        <div class="row">
                            @if (Auth::user()->getAdmin())
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-2">
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
                            <div class="col-md-2">
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
                    <div class="table-responsive">                        
                        <table class="table table-hover" id="ajk_details_list" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.designation') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.phone_number') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.month') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.year') }}</th>
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
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#ajk_details_list').DataTable({
            "sAjaxSource": "{{URL::action('AgmController@getAJK')}}",
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[5, 'desc'], [6, 'desc']],
            "responsive": true,
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [-1]
                }
            ]
        });

        $('#company').on('change', function () {
            oTable.columns(0).search(this.value).draw();
        });
        $('#file_no').on('change', function () {
            oTable.columns(1).search(this.value).draw();
        });
        $('#month').on('change', function () {
            oTable.columns(5).search(this.value).draw();
        });
        $('#year').on('change', function () {
            oTable.columns(6).search(this.value).draw();
        });
    });

    function deleteAJKDetails(id) {
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
                url: "{{ URL::action('AgmController@deleteAJK') }}",
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
