@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 48) {
        $update_permission = $permissions->update_permission;
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
                        <form id="update_nationality" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.insurance_provider_name') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input id="name" class="form-control" placeholder="{{ trans('app.forms.insurance_provider_name') }}" type="text" value="{{ $insuranceProvider->name }}">
                                    <div id="name_error" style="display:none;"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.sort_no') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input id="sort_no" class="form-control" placeholder="{{ trans('app.forms.sort_no') }}" type="text" value="{{ $insuranceProvider->sort_no }}">
                                    <div id="sort_no_error" style="display:none;"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.status') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <select id="is_active" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" {{($insuranceProvider->is_active==1 ? " selected" : "")}}>{{ trans('app.forms.active') }}</option>
                                        <option value="0" {{($insuranceProvider->is_active==0 ? " selected" : "")}}>{{ trans('app.forms.inactive') }}</option>
                                    </select>
                                    <div id="is_active_error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($update_permission) { ?>
                                <button type="button" class="btn btn-own" id="submit_button" onclick="updateInsuranceProvider()">{{ trans('app.forms.save') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("SettingController@insuranceProvider") }}'" >{{ trans('app.forms.cancel') }}</button>
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
    function updateInsuranceProvider() {
        $("#loading").css("display", "inline-block");

        var name = $("#name").val(),
            sort_no = $("#sort_no").val(),
            is_active = $("#is_active").val();

        var error = 0;

        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Insurance Provider"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }

        if (sort_no.trim() == "") {
            $("#sort_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Sort No"]) }}</span>');
            $("#sort_no_error").css("display", "block");
            error = 1;
        }

        if (is_active.trim() == "") {
            $("#is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#is_active_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('SettingController@submitUpdateInsuranceProvider') }}",
                type: "POST",
                data: {
                    name: name,
                    sort_no : sort_no,
                    is_active: is_active,
                    id: "{{ \Helper\Helper::encode($insuranceProvider->id) }}"
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.updated_successfully') }}</span>", function () {
                            window.location = '{{URL::action("SettingController@insuranceProvider") }}';
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
