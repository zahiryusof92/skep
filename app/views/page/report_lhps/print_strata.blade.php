@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 35) {
        $insert_permission = $permission->insert_permission;
    }
}

$fields = array(
    'parliment_id' => 'parliment',
    'dun_id' => 'dun',
    'park_id' => 'park',
    'land_title' => 'land',
    'category_id' =>  'category'
);
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.parliment_id') }}</label>
                                    <select id="form_type" class="form-control" name="parliment_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($parliment as $f)
                                        <option value="{{$f->id}}">{{$f->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="parliment_id_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.dun_id') }}</label>
                                    <select id="form_type" class="form-control" name="dun_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($dun as $f)
                                        <option value="{{$f->id}}">{{$f->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="dun_id_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.park_id') }}</label>
                                    <select id="form_type" class="form-control" name="park_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($dun as $f)
                                        <option value="{{$f->id}}">{{$f->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="park_id_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.land_id') }}</label>
                                    <select id="form_type" class="form-control" name="land_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($land as $f)
                                        <option value="{{$f->id}}">{{$f->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="land_id_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.category_id') }}</label>
                                    <select id="form_type" class="form-control" name="category_id">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @foreach ($category as $f)
                                        <option value="{{$f->id}}">{{$f->description}}</option>
                                        @endforeach
                                    </select>
                                    <div id="category_id_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('report_lhps.form.strata') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="strata_id">
                                <div id="remark_error" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.export_to') }}</label>
                                    <select id="form_type" class="form-control" name="export_to">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                    <div id="export_to_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.bantuan') }}</label>
                                    <select id="form_type" class="form-control" name="bantuan_lhps">
                                        <option value="">-- ALL -- </option>
                                        <option value="1">{{ trans("app.forms.yes") }}</option>
                                        <option value="1">{{ trans("app.forms.no") }}</option>
                                    </select>
                                    <div id="bantuan_err" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('report_lhps.form.facility') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                $fields = [
                                    'management_office' => 'Management Office',
                                    'gym' => 'Gym',
                                    'kindergarten' => 'Kindergarten',
                                    'pool' => 'Pool',
                                    'lift' => 'Lift',
                                    'openspace' => 'Openspace',
                                    'surau' => 'Surau',
                                    'playground' => 'Play Ground',
                                    'rubbish_room' => 'Rubbish Room',
                                    'hall' => 'Hall',
                                    'guardhouse' => 'Guardhouse',
                                    'gated' => 'Gated'
                        ];
                            ?>
                            @foreach ($fields as $k => $v)
                            <div class="col-md-4">
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="{{ $k }}" name="facility[]">{{ $v }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("ReportController@reportStrataProfile") }}'">{{ trans('app.forms.cancel') }}</button>
                            <?php if ($insert_permission == 1) { ?>
                                <input type="submit" value="{{ trans('general.label_save') }}" class="btn btn-primary">
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
                url: "{{ URL::action('ReportController@reportStrataProfile') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.files.submit') }}</span>", function () {
                            window.location = '{{URL::action("ReportController@reportStrataProfile") }}';
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
