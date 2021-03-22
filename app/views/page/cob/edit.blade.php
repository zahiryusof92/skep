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
                        <input type="hidden" name="id" value="{{ $cob->id }}">
                        <input type="hidden" name="type" value="{{ $cob->type }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('cob.form.file') }}</label>
                                    <select id="file" class="form-control" name="file_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($files as $file)
                                        <option value="{{$file->id}}"
                                            <?php if($cob->file_id == $file->id) echo 'selected'; ?>
                                        >{{$file->file_no}}</option>
                                        @endforeach
                                    </select>
                                    <div id="file_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('cob.form.document') }}</label>
                                    <select id="document" class="form-control" name="document_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>

                                        @foreach ($documents as $document)
                                        <option value="{{$document->id}}"
                                            <?php if($cob->document_id == $document->id) echo 'selected'; ?>
                                        >{{$document->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="document_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('cob.form.document_type') }}</label>
                            </div>
                            <div class="col-md-6">
                                {{ Form::text('name', $cob->name, array('class' => 'form-control', 'id' => 'name')) }}
                                <div id="name_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('cob.form.hidden') }}</label>
                                    <select id="hidden" class="form-control" name="is_hidden">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" <?php if($cob->is_hidden === 1) echo 'selected' ?>>{{ trans("app.forms.yes") }}</option>
                                        <option value="0" <?php if($cob->is_hidden === 0) echo 'selected' ?>>{{ trans("app.forms.no") }}</option>
                                    </select>
                                    <div id="hidden_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('cob.form.readonly') }}</label>
                                    <select id="readonly" class="form-control" name="is_readonly">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" <?php if($cob->is_readonly === 1) echo 'selected' ?>>{{ trans("app.forms.yes") }}</option>
                                        <option value="0" <?php if($cob->is_readonly === 0) echo 'selected' ?>>{{ trans("app.forms.no") }}</option>
                                    </select>
                                    <div id="readonly_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('cob.form.remark') }}</label>
                            </div>
                            <div class="col-md-6">
                                <textarea name="remark" id="remark" cols="30" rows="10" class="form-control">{{ $cob->remark }}</textarea>
                                <div id="name_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href='{{ url("cob/get/{$name}") }}' class="btn btn-default" id="cancel_button">{{ trans('app.forms.cancel') }}</a>
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
                url: "{{ URL::action('CobController@update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.cob.update') }}</span>", function () {
                            window.location = '{{URL::action("CobController@get", ["name" => $cob->type]) }}';
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
