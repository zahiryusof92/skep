@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 35) {
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
                    <form id="formSubmit" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label class="form-control-label" style="color: red; font-style: italic;">* {{trans('general.label_mandatory')}}</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('agm_design_sub.form.file') }}</label>
                                    <select id="form_type" class="form-control" name="file_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($file as $f)
                                        <option value="{{$f->id}}">{{$f->file_no}}</option>
                                        @endforeach
                                    </select>
                                    <div id="file_id_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('agm_design_sub.form.designation') }}</label>
                                    <select id="design_id" class="form-control" name="design_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($design as $f)
                                        <option value="{{$f->id}}">{{$f->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="design_id_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('agm_design_sub.form.name') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input id="name" name="name" class="form-control" type="text">
                                <div id="bi_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('agm_design_sub.form.phone_number') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input id="phone_number" name="phone_number" class="form-control" type="text">
                                <div id="bm_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('agm_design_sub.form.email') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input id="email" class="form-control" name="email" type="text">
                                <div id="email_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('agm_design_sub.form.ajk_year') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input id="ajk_year" class="form-control" name="ajk_year" type="text">
                                <div id="ajk_year_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('agm_design_sub.form.remark') }}</label>
                            </div>
                            <div class="col-md-6">
                                <textarea name="remark" id="remark" cols="30" rows="10" class="form-control"></textarea>
                                <div id="remark_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("AgmController@agmDesignSub") }}'">{{ trans('app.forms.cancel') }}</button>
                            <?php if ($insert_permission == 1) { ?>
                                <input type="submit" value="{{ trans('general.label_save') }}" class="btn btn-own">
                            <?php } ?>
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
    $("#formSubmit").submit(function(e){
        e.preventDefault();
        $("#loading").css("display", "inline-block");

        let error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AgmController@submitAgmDesignSub') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.agm_design.submit') }}</span>", function () {
                            window.location = '{{URL::action("AgmController@agmDesignSub") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    });
</script>
<!-- End Page Scripts-->

@stop
