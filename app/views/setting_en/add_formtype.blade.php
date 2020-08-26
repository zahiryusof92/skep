@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 22) {
        $insert_permission = $permission->insert_permission;
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
                    <form id="form_formtype" class="form-horizontal" name="add_fileprefix">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.form_type_bi') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="bi_type" class="form-control" type="text">
                                <div id="bi_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.form_type_bm') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="bm_type" class="form-control" type="text">
                                <div id="bm_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.sort_no') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="sort_no" class="form-control" placeholder="{{ trans('app.forms.sort_no') }}" type="text">
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
                                    <option value="1">{{ trans('app.forms.active') }}</option>
                                    <option value="0">{{ trans('app.forms.inactive') }}</option>
                                </select>
                                <div id="is_active_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <?php if ($insert_permission == 1) { ?>
                            <button type="button" class="btn btn-primary" id="submit_button" onclick="submitFormtype()">{{ trans('app.forms.save') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("SettingController@formtype") }}'">{{ trans('app.forms.cancel') }}</button>
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
    function submitFormtype() {
        $("#loading").css("display", "inline-block");

        let bi = $("#bi_type").val();
        let bm = $("#bm_type").val();
        let sort_no = $("#sort_no").val();
        let is_active = $("#is_active").val();

        var error = 0;

        if (bi.trim() == "") {
            $("#bi_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Form Type (BI) Name"]) }}</span>');
            $("#bi_error").css("display", "block");
            error = 1;
        }

        if (bm.trim() == "") {
            $("#bm_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Form Type (BM) Name"]) }}</span>');
            $("#bm_error").css("display", "block");
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
                url: "{{ URL::action('SettingController@submitFormtype') }}",
                type: "POST",
                data: {
                    bi_type: bi,
                    bm_type: bm,
                    sort_no : sort_no,
                    is_active: is_active

                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.form_types.store') }}</span>", function () {
                            window.location = '{{URL::action("SettingController@formtype") }}';
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
