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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
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
                            <div class="col-lg-6">
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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.public_liability_coverage') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.public_liability_coverage') }}" id="public_liability_coverage" value="{{ $insurance->public_liability_coverage }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.premium_per_year') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.premium_per_year') }}" id="plc_premium_per_year" value="{{ $insurance->plc_premium_per_year }}"/>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.validity') }}</label>
                                    <label class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ trans("app.forms.from") }}" id="plc_validity_from_raw" value="{{ (($insurance->plc_validity_from && $insurance->plc_validity_from != '0000-00-00') ? date('d-m-Y', strtotime($insurance->plc_validity_from)) : '') }}"/>
                                        <span class="input-group-addon">
                                            <i class="icmn-calendar"></i>
                                        </span>
                                    </label>
                                    <input type="hidden" id="plc_validity_from" value="{{ $insurance->plc_validity_from }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">&nbsp;</label>
                                    <label class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ trans("app.forms.to") }}" id="plc_validity_to_raw" value="{{ (($insurance->plc_validity_to && $insurance->plc_validity_to != '0000-00-00') ? date('d-m-Y', strtotime($insurance->plc_validity_to)) : '') }}"/>
                                        <span class="input-group-addon">
                                            <i class="icmn-calendar"></i>
                                        </span>
                                    </label>
                                    <input type="hidden" id="plc_validity_to" value="{{ $insurance->plc_validity_to }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.fire_insurance_coverage') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.fire_insurance_coverage') }}" id="fire_insurance_coverage" value="{{ $insurance->fire_insurance_coverage }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.premium_per_year') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.premium_per_year') }}" id="fic_premium_per_year" value="{{ $insurance->fic_premium_per_year }}"/>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.validity') }}</label>
                                    <label class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ trans("app.forms.from") }}" id="fic_validity_from_raw" value="{{ (($insurance->fic_validity_from && $insurance->fic_validity_from != '0000-00-00') ? date('d-m-Y', strtotime($insurance->fic_validity_from)) : '') }}"/>
                                        <span class="input-group-addon">
                                            <i class="icmn-calendar"></i>
                                        </span>
                                    </label>
                                    <input type="hidden" id="fic_validity_from" value="{{ $insurance->fic_validity_from }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">&nbsp;</label>
                                    <label class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ trans("app.forms.to") }}" id="fic_validity_to_raw" value="{{ (($insurance->fic_validity_to && $insurance->fic_validity_to != '0000-00-00') ? date('d-m-Y', strtotime($insurance->fic_validity_to)) : '') }}"/>
                                        <span class="input-group-addon">
                                            <i class="icmn-calendar"></i>
                                        </span>
                                    </label>
                                    <input type="hidden" id="fic_validity_to" value="{{ $insurance->fic_validity_to }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('app.forms.remarks') }}</label>
                                    <textarea id="remarks" name="remarks" rows="5" class="form-control" placeholder="{{ trans('app.forms.remarks') }}">{{ $insurance->remarks }}</textarea>
                                    <div id="remarks_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <?php if ($update_permission) { ?>
                                <button type="button" class="btn btn-own" id="submit_button" onclick="submitEditInsurance()">{{ trans('app.forms.submit') }}</button>
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
    $(function () {
        $("#plc_validity_from_raw").datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#plc_validity_from").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $("#plc_validity_to_raw").datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#plc_validity_to").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $("#fic_validity_from_raw").datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#fic_validity_from").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });

        $("#fic_validity_to_raw").datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD-MM-YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('-');
            $("#fic_validity_to").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });

    function submitEditInsurance() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");

        var file_id = $("#file_id").val(),
                insurance_provider = $("#insurance_provider").val(),
                public_liability_coverage = $("#public_liability_coverage").val(),
                plc_premium_per_year = $("#plc_premium_per_year").val(),
                plc_validity_from = $("#plc_validity_from").val(),
                plc_validity_to = $("#plc_validity_to").val(),
                fire_insurance_coverage = $("#fire_insurance_coverage").val(),
                fic_premium_per_year = $("#fic_premium_per_year").val(),
                fic_validity_from = $("#fic_validity_from").val(),
                fic_validity_to = $("#fic_validity_to").val(),
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
                    public_liability_coverage: public_liability_coverage,
                    plc_premium_per_year: plc_premium_per_year,
                    plc_validity_from: plc_validity_from,
                    plc_validity_to: plc_validity_to,
                    fire_insurance_coverage: fire_insurance_coverage,
                    fic_premium_per_year: fic_premium_per_year,
                    fic_validity_from: fic_validity_from,
                    fic_validity_to: fic_validity_to,
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
