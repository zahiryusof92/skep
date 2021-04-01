@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 17) {
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
                        <form id="add_fileprefix" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.parliament') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <select id="parliament" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($parliament as $parliaments)
                                        <option value="{{$parliaments->id}}" {{($dun->parliament == $parliaments->id ? " selected" : "")}}>{{$parliaments->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="parliament_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.dun') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input id="description" class="form-control" placeholder="{{ trans('app.forms.dun') }}" type="text" value="{{$dun->description}}">
                                    <div id="description_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.code') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input id="code" class="form-control" placeholder="{{ trans('app.forms.code') }}" type="text" value="{{ $dun->code }}">
                                    <div id="code_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.status') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <select id="is_active" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" {{($dun->is_active==1 ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                        <option value="0" {{($dun->is_active==0 ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                    </select>
                                    <div id="is_active_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($update_permission == 1) { ?>
                                <button type="button" class="btn btn-own" id="submit_button" onclick="updateDun()">{{ trans('app.forms.save') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("SettingController@dun") }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    function updateDun() {
        $("#loading").css("display", "inline-block");

        var parliament = $("#parliament").val(),
                description = $("#description").val(),
                code = $("#code").val(),
                is_active = $("#is_active").val();

        var error = 0;

        if (parliament.trim() == "") {
            $("#parliament_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Parliament"]) }}</span>');
            $("#parliament_error").css("display", "block");
            error = 1;
        }

        if (description.trim() == "") {
            $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"DUN"]) }}</span>');
            $("#description_error").css("display", "block");
            error = 1;
        }

        if (code.trim() == "") {
            $("#code_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Code"]) }}</span>');
            $("#code_error").css("display", "block");
            error = 1;
        }

        if (is_active.trim() == "") {
            $("#is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#is_active_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('SettingController@submitUpdateDun') }}",
                type: "POST",
                data: {
                    parliament: parliament,
                    description: description,
                    code: code,
                    is_active: is_active,
                    id: '{{$dun->id}}'

                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.dun.update') }}</span>", function () {
                            window.location = '{{URL::action("SettingController@dun") }}';
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
