@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = false;
foreach ($user_permission as $permissions) {
    if ($permissions->submodule_id == 46) {
        $update_permission = $permissions->update_permission;
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
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.file_no') }}</label>
                                    <select id="file_id" class="form-control select2" name="file_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($files as $file_no)
                                        <option value="{{$file_no->id}}"  {{ $insurance->file_id == $file_no->id ? 'selected' : '' }}>{{$file_no->file_no}}</option>
                                        @endforeach
                                    </select>
                                    <div id="file_id_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.insurance_provider') }}</label>
                                    <select id="insurance_provider" class="form-control select2" name="insurance_provider">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($insuranceProvider as $provider)
                                        <option value="{{ $provider->id }}"  {{ $insurance->insurance_provider_id == $provider->id ? 'selected' : '' }}>{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="insurance_provider_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('app.forms.remarks') }}</label>
                                    <textarea id="remarks" name="remarks" rows="5" class="form-control" placeholder="{{ trans('app.forms.remarks') }}">{{ $insurance->remarks }}</textarea>
                                    <div id="remarks_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <?php if ($update_permission) { ?>
                                <button type="button" class="btn btn-primary" id="submit_button" onclick="submitEditInsurance()">{{ trans('app.forms.submit') }}</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@insurance', ['All']) }}'">{{ trans('app.forms.cancel') }}</button>
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
    function submitEditInsurance() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");

        var file_id = $("#file_id").val(),
                insurance_provider = $("#insurance_provider").val(),
                remarks = $("#remarks").val();

        var error = 0;

        if (file_id.trim() == "") {
            $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
            $("#file_id_error").css("display", "block");
            error = 1;
        }
        if (insurance_provider.trim() == "") {
            $("#insurance_provider_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Insurance Provider"]) }}</span>');
            $("#insurance_provider_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateInsurance') }}",
                type: "POST",
                data: {
                    file_id: file_id,
                    insurance_provider: insurance_provider,
                    remarks: remarks,
                    id: "{{ $insurance->id }}"
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.updated_successfully') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@insurance", ["All"]) }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#file_id").focus();
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }
</script>
<!-- End Page Scripts-->

@stop
