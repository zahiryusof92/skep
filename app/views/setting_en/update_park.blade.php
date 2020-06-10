@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 18) {
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
                    <form id="add_fileprefix" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.dun') }}</label>
                            </div>
                            <div class="col-md-4">
                                <select id="dun" class="form-control">
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($dun as $duns)
                                    <option value="{{$duns->id}}" {{($park->dun == $duns->id ? " selected" : "")}}>{{$duns->description}}</option>
                                    @endforeach
                                </select>
                                <div id="parliament_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.park') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="description" class="form-control" placeholder="{{ trans('app.forms.park') }}" type="text" value="{{$park->description}}">
                                <div id="description_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.admin_status') }}</label>
                            </div>
                            <div class="col-md-4">
                                <select id="is_active" class="form-control">
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    <option value="1" {{($park->is_active==1 ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                    <option value="0" {{($park->is_active==0 ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                </select>
                                <div id="is_active_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <?php if ($update_permission == 1) { ?>
                            <button type="button" class="btn btn-primary" id="submit_button" onclick="updatePark()">{{ trans('app.forms.save') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("SettingController@park") }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    function updatePark() {
        $("#loading").css("display", "inline-block");

        var dun = $("#dun").val(),
                description = $("#description").val(),
                is_active = $("#is_active").val();

        var error = 0;

        if (dun.trim() == "") {
            $("#parliament_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"DUN"]) }}</span>');
            $("#parliament_error").css("display", "block");
            error = 1;
        }

        if (description.trim() == "") {
            $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Park"]) }}</span>');
            $("#description_error").css("display", "block");
            error = 1;
        }

        if (is_active.trim() == "") {
            $("#is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#is_active_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('SettingController@submitUpdatePark') }}",
                type: "POST",
                data: {
                    dun: dun,
                    description: description,
                    is_active: is_active,
                    id: '{{$park->id}}'

                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.parks.update') }}</span>", function () {
                            window.location = '{{URL::action("SettingController@park") }}';
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
