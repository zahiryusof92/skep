@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 1) {
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
                    <form id="add_fileprefix" class="form-horizontal" name="add_fileprefix">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-control-label" style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                            </div>
                        </div>
                        @if (Auth::user()->getAdmin())
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.cob') }}</label>
                            </div>
                            <div class="col-md-6">
                                <select id="company" class="form-control select2">
                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                    @foreach ($cob as $companies)
                                    <option value="{{ $companies->id }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                    @endforeach
                                </select>
                                <div id="company_error" style="display:none;"></div>
                            </div>
                        </div>
                        @else
                        <input type="hidden" id="company" value="{{ Auth::user()->company_id }}">
                        @endif
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.description') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input id="description" class="form-control" placeholder="{{ trans('app.forms.description') }}" type="text">
                                <div id="description_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.sort_no') }}</label>
                            </div>
                            <div class="col-md-2">
                                <input id="sort_no" class="form-control" placeholder="{{ trans('app.forms.sort_no') }}" type="number">
                                <div id="sort_no_error" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.admin_status') }}</label>
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
                                <button type="button" class="btn btn-primary" id="submit_button" onclick="addFilePrefix()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@filePrefix')}}'">{{ trans('app.forms.cancel') }}</button>
                            <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
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
    function addFilePrefix() {
        $("#loading").css("display", "inline-block");
        $("#company_error").css("display", "none");
        $("#description_error").css("display", "none");
        $("#sort_no_error").css("display", "none");
        $("#is_active_error").css("display", "none");

        var company_id = $("#company").val(),
                description = $("#description").val(),
                sort_no = $("#sort_no").val(),
                is_active = $("#is_active").val();

        var error = 0;

        if (company_id.trim() == "") {
            $("#company_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"COB"]) }}</span>');
            $("#company_error").css("display", "block");
            error = 1;
        }
        if (description.trim() == "") {
            $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Description"]) }}</span>');
            $("#description_error").css("display", "block");
            error = 1;
        }

        if (sort_no.trim() <= 0) {
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
                url: "{{ URL::action('AdminController@submitFilePrefix') }}",
                type: "POST",
                data: {
                    company_id: company_id,
                    description: description,
                    sort_no: sort_no,
                    is_active: is_active

                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.cob_file_prefix.store') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@filePrefix") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#description").focus();
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }
</script>
<!-- End Page Scripts-->

@stop
