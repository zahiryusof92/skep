@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 7) {
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
                        <button onclick="window.location = '{{ URL::action('AdminController@addMemo') }}'" type="button" class="btn btn-primary margin-bottom-25">
                            {{ trans('app.buttons.add_memo') }}
                        </button>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 text-center">
                    <form>
                        <div class="row">
                            @if (Auth::user()->getAdmin())
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.cob') }}</label>
                                    <select id="company" name="company" class="form-control select2">
                                        @if (count($cob) > 1)
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @endif
                                        @foreach ($cob as $id => $companies)
                                        <option value="{{ $id }}">{{ $companies }}</option>
                                        @endforeach
                                    </select>
                                    <div id="company_error" style="display:none;"></div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.memo_type') }}:</label>
                                    <select id="memo_type" class="form-control select2">
                                        <option value="">{{ trans('app.forms.all') }}</option>
                                        @foreach ($memotype as $memotypes)
                                        <option value="{{$memotypes->description}}">{{$memotypes->description}}</option>
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
                    <table class="table table-hover nowrap" id="memo" width="100%">
                        <thead>
                            <tr>                                
                                <th style="width:10%;">{{ trans('app.forms.memo_date') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.cob') }}</th>
                                <th style="width:20%;">{{ trans('app.forms.memo_type') }}</th>
                                <th style="width:30%;">{{ trans('app.forms.subject') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.publish_date') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.expired_date') }}</th>
                                <th style="width:10%;">{{ trans('app.forms.status') }}</th>
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
    $(document).ready(function () {
        oTable = $('#memo').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('AdminController@getMemo') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 5,
            order: [[0, "desc"]],
            responsive: true,
            columns: [                
                {data: 'memo_date', name: 'memo.memo_date'},
                {data: 'company', name: 'memo.company_id'},
                {data: 'memo_type', name: 'memo_type.description'},
                {data: 'subject', name: 'memo.subject'},
                {data: 'publish_date', name: 'memo.publish_date'},
                {data: 'expired_date', name: 'memo.expired_date'},
                {data: 'is_active', name: 'memo.is_active'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });

    $('#company').on('change', function () {
        oTable.columns(1).search(this.value).draw();
    });
    $('#memo_type').on('change', function () {
        oTable.columns(2).search(this.value).draw();
    });

    function inactiveMemo(id) {
        $.ajax({
            url: "{{ URL::action('AdminController@inactiveMemo') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('AdminController@memo')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function activeMemo(id) {
        $.ajax({
            url: "{{ URL::action('AdminController@activeMemo') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        window.location = "{{URL::action('AdminController@memo')}}";
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function deleteMemo(id) {
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
                url: "{{ URL::action('AdminController@deleteMemo') }}",
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
