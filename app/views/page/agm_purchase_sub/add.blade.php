@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 35) {
        $insert_permission = $permission->insert_permission;
    }
}

$fields = [
            'unit_no',
            'share_unit',
            'buyer',
            'nric',
            'address1',
            'address2',
            'address3',
            'address4',
            'postcode',
            'phone_number',
            'email',
        ];

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
                                    <label><span style="color: red;">*</span> {{ trans('agm_purchase_sub.form.file') }}</label>
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

                        @foreach ($fields as $field)
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans("agm_purchase_sub.form.{$field}") }}</label>
                            </div>
                            <div class="col-md-6">
                                <input id="{{ $field }}" name="{{ $field }}" class="form-control" type="text">
                                <div id="{{ $field }}_error" style="display:none;"></div>
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('agm_purchase_sub.form.remark') }}</label>
                            </div>
                            <div class="col-md-6">
                                <textarea name="remark" id="remark" cols="30" rows="10" class="form-control"></textarea>
                                <div id="remark_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("AgmController@agmPurchaseSub") }}'">{{ trans('app.forms.cancel') }}</button>
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
                url: "{{ URL::action('AgmController@submitAgmPurchaseSub') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.agm_purchaser.submit') }}</span>", function () {
                            window.location = '{{URL::action("AgmController@agmPurchaseSub") }}';
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
