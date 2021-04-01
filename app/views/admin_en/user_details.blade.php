@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 6) {
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
                <dl class="dl-horizontal padding-vertical-20">
                    <dt>{{ trans('app.forms.username') }}</dt>
                    <dd>{{($user->username != "" ? $user->username : "-")}}</dd>
                    <dt>{{ trans('app.forms.full_name') }}</dt>
                    <dd>{{($user->full_name != "" ? $user->full_name : "-")}}</dd>
                    <dt>{{ trans('app.forms.email') }}</dt>
                    <dd>{{($user->email != "" ? $user->email : "-")}}</dd>
                    <dt>{{ trans('app.forms.phone_number') }}</dt>
                    <dd>{{($user->phone_no != "" ? $user->phone_no : "-")}}</dd>
                    <dt>{{ trans('app.forms.cob') }}</dt>
                    <dd>{{($user->getCOB->name != "" ? $user->getCOB->name : "-")}}</dd>
                    <dt>{{ trans('app.forms.access_group') }}</dt>
                    <dd>{{($user->getRole->name != "" ? $user->getRole->name : "-")}}</dd>
                    @if ($user->getRole->name == 'JMB' || $user->getRole->name == 'MC')
                    <dt>{{ trans('app.forms.date_start') }}</dt>
                    <dd>{{($user->start_date != "" ? date('d-m-Y', strtotime($user->start_date)) : "-")}}</dd>
                    <dt>{{ trans('app.forms.date_end') }}</dt>
                    <dd>{{($user->end_date != "" ? date('d-m-Y', strtotime($user->end_date)) : "-")}}</dd>
                    <dt>{{ trans('app.forms.file_no') }}</dt>
                    <dd>{{($user->getFile->file_no != "" ? $user->getFile->file_no : "-")}}</dd>
                    @endif
                    <dt>{{ trans('app.forms.is_active') }}</dt>
                    <dd>{{ ($user->is_active == '1' ? 'Yes' : 'No') }}</dd>
                    @if ($user->status == 0)
                    <dt>{{ trans('app.forms.status') }}</dt>
                    <dd>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select id="status" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1">{{ trans('app.forms.approve') }}</option>
                                        <option value="2">{{ trans('app.forms.reject') }}</option>
                                    </select>
                                    <div id="status_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                    </dd>
                    <dt>{{ trans('app.forms.remarks') }}</dt>
                    <dd>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" placeholder="{{ trans('app.forms.remarks') }}" id="remarks"></textarea>
                                </div>
                            </div>
                        </div>
                    </dd>
                    <div class="form-actions">
                        <dt>&nbsp;</dt>
                        <dd>
                            <?php if ($update_permission == 1) { ?>
                                <button type="button" class="btn btn-own" id="submit_button" onclick="approvedUser()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@user')}}'">{{ trans('app.forms.cancel') }}</button>
                        </dd>
                    </div>
                    @else
                    <?php $admin = User::find($user->approved_by); ?>
                    @if ($user->status == 1)
                    <dt>{{ trans('app.forms.status') }}</dt>
                    <dd>{{ trans('app.forms.approve') }}</dd>
                    <dt>{{ trans('app.forms.approved_by') }}</dt>
                    <dd>{{($admin->full_name != "" ? $admin->full_name : "-")}}</dd>
                    <dt>{{ trans('app.forms.approved_date') }}</dt>
                    <dd>{{($user->approved_at != "" ? date('d-m-Y', strtotime($user->approved_at)) : "-")}}</dd>
                    <dt>{{ trans('app.forms.remarks') }}</dt>
                    <dd>{{($user->remarks != "" ? $user->remarks : "-")}}</dd>
                    @else
                    <dt>{{ trans('app.forms.status') }}</dt>
                    <dd>{{ trans('app.forms.reject') }}</dd>
                    <dt>{{ trans('app.forms.rejected_by') }}</dt>
                    <dd>{{($admin->full_name != "" ? $admin->full_name : "-")}}</dd>
                    <dt>{{ trans('app.forms.rejected_date') }}</dt>
                    <dd>{{($user->approved_at != "" ? date('d-m-Y', strtotime($user->approved_at)) : "-")}}</dd>
                    <dt>{{ trans('app.forms.remarks') }}</dt>
                    <dd>{{($user->remarks != "" ? $user->remarks : "-")}}</dd>
                    @endif
                    <div class="form-actions">
                        <dt>&nbsp;</dt>
                        <dd>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.history.back()">{{ trans('app.forms.back') }}</button>
                        </dd>
                    </div>
                    @endif
                </dl>
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>

    function approvedUser() {
        $("#loading").css("display", "inline-block");
        $("#status_error").css("display", "none");

        var status = $("#status").val(),
                remarks = $("#remarks").val();

        var error = 0;

        if (status.trim() === "") {
            $("#status_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#status_error").css("display", "block");
            error = 1;
        }

        if (error === 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitApprovedUser') }}",
                type: "POST",
                data: {
                    status: status,
                    remarks: remarks,
                    id: '{{$user->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() === "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.users.update') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@user") }}';
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
