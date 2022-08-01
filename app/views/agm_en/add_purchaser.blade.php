@extends('layout.english_layout.default_custom')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 31) {
        $insert_permission = $permission->insert_permission;
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
                        <!-- Buyer Form -->
                        <form id="add_buyer">
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
                                        <select id="file_id" name="file_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'file_id'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.strata') }}</label>
                                        <select id="strata_id" name="strata_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'strata_id'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.unit_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.unit_number') }}" id="unit_no" name="unit_no">
                                        @include('alert.feedback-ajax', ['field' => 'unit_no'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.no_petak') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.no_petak') }}" id="no_petak" name="no_petak">
                                        @include('alert.feedback-ajax', ['field' => 'no_petak'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.no_petak_aksesori') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.no_petak_aksesori') }}" id="no_petak_aksesori" name="no_petak_aksesori">
                                        @include('alert.feedback-ajax', ['field' => 'no_petak_aksesori'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.keluasan_lantai_petak') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.keluasan_lantai_petak') }}" id="keluasan_lantai_petak" name="keluasan_lantai_petak">
                                        @include('alert.feedback-ajax', ['field' => 'keluasan_lantai_petak'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.keluasan_lantai_petak_aksesori') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.keluasan_lantai_petak_aksesori') }}" id="keluasan_lantai_petak_aksesori" name="keluasan_lantai_petak_aksesori">
                                        @include('alert.feedback-ajax', ['field' => 'keluasan_lantai_petak_aksesori'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.unit_share') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.unit_share') }}" id="unit_share" name="unit_share">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.jenis_kegunaan') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.jenis_kegunaan') }}" id="jenis_kegunaan" name="jenis_kegunaan">
                                        @include('alert.feedback-ajax', ['field' => 'jenis_kegunaan'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.owner_name') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.owner_name') }}" id="owner_name" name="owner_name">
                                        @include('alert.feedback-ajax', ['field' => 'owner_name'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.ic_company_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.ic_company_number') }}" id="ic_company_no" name="ic_company_no">
                                        @include('alert.feedback-ajax', ['field' => 'ic_company_no'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.nama2') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.nama2') }}" id="nama2" name="nama2">
                                        @include('alert.feedback-ajax', ['field' => 'nama2'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.ic_no2') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.ic_no2') }}" id="ic_no2" name="ic_no2">
                                        @include('alert.feedback-ajax', ['field' => 'ic_no2'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.address') }}</label>
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.address') }}" rows="3" id="address" name="address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.alamat_surat_menyurat') }}</label>
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.alamat_surat_menyurat') }}" rows="3" id="alamat_surat_menyurat" name="alamat_surat_menyurat"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.phone_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no" name="phone_no">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.email') }}</label>
                                        <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email" name="email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.race') }}</label>
                                        <select id="race" name="race" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($race as $races)
                                            <option value="{{ $races->id }}">{{ $races->name_en }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'race'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.nationality') }}</label>
                                        <select id="nationality" name="nationality" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($nationality as $national)
                                            <option value="{{ $national->id }}">{{ $national->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'nationality'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.caj_penyelenggaraan') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.caj_penyelenggaraan') }}" id="caj_penyelenggaraan" name="caj_penyelenggaraan">
                                        @include('alert.feedback-ajax', ['field' => 'caj_penyelenggaraan'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.sinking_fund') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="sinking_fund" name="sinking_fund">
                                        @include('alert.feedback-ajax', ['field' => 'sinking_fund'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.remarks') }}</label>
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.remarks') }}" rows="3" id="remarks" name="remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($insert_permission == 1) { ?>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="addPurchaser()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AgmController@purchaser')}}'">{{ trans('app.forms.cancel') }}</button>
                                <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(function() {
        $('.select2').select2();
        $("#strata_id").select2({
            ajax: {
                url: "{{ route('v3.api.strata.getOption') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                cache: true,
                allowClear: true,
                data: function(params) {
                    return {
                        term: params.term, // search term
                        file_id: $('#file_id').val(),
                        type: 'id',
                    };
                },
                processResults: function(response) {
                    return {
                        results: response.results
                    };
                }
            }
        });
        $("#file_id").select2({
            ajax: {
                url: "{{ route('v3.api.files.getOption') }}",
                type: "get",
                dataType: 'json',
                delay: 250,
                cache: true,
                allowClear: true,
                data: function(params) {
                    return {
                        term: params.term, // search term
                        strata: $('#strata_id').val()
                    };
                },
                processResults: function(response) {
                    return {
                        results: response.results
                    };
                }
            }
        });
    });
    function addPurchaser() {
        let formData = $('form').serializeArray();
        $.ajax({
            url: "{{ URL::action('AgmController@submitPurchaser') }}",
            type: "POST",
            data: formData,
            beforeSend: function() {
                $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                $("#loading").css("display", "inline-block");
                $("#submit_button").attr("disabled", "disabled");
                $("#cancel_button").attr("disabled", "disabled");
                $.each(formData, function (key, value) {
                    $("#" + value['name'] + "_error").children("strong").text("");
                });
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.purchaser.store') }}</span>", function () {
                        window.location = '{{URL::action("AgmController@purchaser") }}';
                    });
                }
            },
            error: function (err) {
                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                if(err.responseJSON.errors) {
                    $.each(err.responseJSON.errors, function (key, value) {
                        $("#" + key + "_error").children("strong").text(value);
                    });
                }
            },
            complete: function() {
                $.unblockUI();
                $("#loading").css("display", "none");
                $("#submit_button").removeAttr("disabled");
                $("#cancel_button").removeAttr("disabled");
            },
        });
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>
<!-- End Page Scripts-->

@stop
