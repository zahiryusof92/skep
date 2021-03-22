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
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewHouse', $files->id)}}">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewStrata', $files->id)}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewManagement', $files->id)}}">{{ trans('app.forms.management') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewMonitoring', $files->id)}}">{{ trans('app.forms.monitoring') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewOthers', $files->id)}}">{{ trans('app.forms.others') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewScoring', $files->id)}}">{{ trans('app.forms.scoring_component_value') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewBuyer', $files->id)}}">{{ trans('app.forms.buyer_list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active">{{ trans('app.forms.approval') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" role="tabpanel">
                                <h4>{{ trans('app.forms.file_approval') }}</h4>
                                @if ($files->status == 0)
                                @if ($role == 1)
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- Form -->
                                        <form id="approval">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><span style="color: red;">*</span> {{ trans('app.forms.admin_status') }}</label>
                                                        <select class="form-control" id="approval_status">
                                                            <option value="-1">{{ trans('app.forms.please_select') }}</option>
                                                            <option value="1" >Approve</option>
                                                            <option value="2" >Reject</option>
                                                        </select>
                                                        <div id="approval_status_error" style="display:none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>{{ trans('app.forms.remarks') }}</label>
                                                        <textarea class="form-control" rows="4" id="approval_remarks" placeholder="{{ trans('app.forms.remarks') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="button" class="btn btn-own" id="submit_button" onclick="submitFileApproval()">{{ trans('app.forms.submit') }}</button>
                                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                            </div>
                                        </form>
                                        <!-- End Form -->
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-lg-12">
                                        <dl class='dl-horizontal'>
                                            <dt>{{ trans('app.forms.status') }}</dt>
                                            <dd>{{$status}}</dd>
                                        </dl>
                                    </div>
                                </div>
                                @endif
                                @else
                                <dl class='dl-horizontal'>
                                    <dt>{{ trans('app.forms.status') }}</dt>
                                    <dd>{{$status}}</dd>
                                    <dt>{{ trans('app.forms.approved_by') }}</dt>
                                    <dd>{{$approveBy->username != "" ? $approveBy->username : "-"}}</dd>
                                    <dt>{{ trans('app.forms.approved_date') }}</dt>
                                    <dd>{{$files->approved_at != "0000-00-00 00:00:00" ? $files->approved_at : "-"}}</dd>
                                    <dt>{{ trans('app.forms.remarks') }}</dt>
                                    <dd>{{$files->remarks != "" ? $files->remarks : "-"}}</dd>
                                </dl>
                                @endif
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
    function submitFileApproval() {
        $("#cancel_button").attr("disabled", "disabled");

        var approval_status = $("#approval_status").val(),
                approval_remarks = $("#approval_remarks").val();

        var error = 0;

        if (approval_status.trim() == "-1") {
            $("#approval_status_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#approval_status_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitFileApproval') }}",
                type: "POST",
                data: {
                    approval_status: approval_status,
                    approval_remarks: approval_remarks,
                    id: '{{$files->id}}'
                },
                success: function (data) {
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                            window.location = '{{ URL::action("AdminController@fileList") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }
</script>
<!-- End Page Scripts-->

@stop
