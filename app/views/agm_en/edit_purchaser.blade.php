@extends('layout.english_layout.default_custom')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 32) {
        $update_permission = $permission->update_permission;
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
                        <form id="edit_buyer">
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
                                        <select id="file_id" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $file)
                                            <option value="{{$file->id}}" {{($file->id == $buyer->file_id ? " selected" : "")}}>{{$file->file_no}}</option>
                                            @endforeach
                                        </select>
                                        <div id="file_id_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.unit_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.unit_number') }}" id="unit_no" value="{{$buyer->unit_no}}">
                                        <div id="unit_no_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.no_petak') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.no_petak') }}" id="no_petak" value="{{$buyer->no_petak}}">
                                        <div id="no_petak_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.no_petak_aksesori') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.no_petak_aksesori') }}" id="no_petak_aksesori" value="{{$buyer->no_petak_aksesori}}">
                                        <div id="no_petak_aksesori_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.keluasan_lantai_petak') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.keluasan_lantai_petak') }}" id="keluasan_lantai_petak" value="{{$buyer->keluasan_lantai_petak}}">
                                        <div id="keluasan_lantai_petak_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.keluasan_lantai_petak_aksesori') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.keluasan_lantai_petak_aksesori') }}" id="keluasan_lantai_petak_aksesori" value="{{$buyer->keluasan_lantai_petak_aksesori}}">
                                        <div id="keluasan_lantai_petak_aksesori_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.unit_share') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.unit_share') }}" id="unit_share" value="{{$buyer->unit_share}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.jenis_kegunaan') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.jenis_kegunaan') }}" id="jenis_kegunaan" value="{{$buyer->jenis_kegunaan}}">
                                        <div id="jenis_kegunaan_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.owner_name') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.owner_name') }}" id="owner_name" value="{{$buyer->owner_name}}">
                                        <div id="owner_name_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.ic_company_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.ic_company_number') }}" id="ic_company_no" value="{{$buyer->ic_company_no}}">
                                        <div id="ic_company_no_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.nama2') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.nama2') }}" id="nama2" value="{{$buyer->nama2}}">
                                        <div id="nama2_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.ic_no2') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.ic_no2') }}" id="ic_no2" value="{{$buyer->ic_no2}}">
                                        <div id="ic_no2_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.address') }}</label>
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.address') }}" rows="3" id="address">{{$buyer->address}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.alamat_surat_menyurat') }}</label>
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.alamat_surat_menyurat') }}" rows="3" id="alamat_surat_menyurat">{{$buyer->alamat_surat_menyurat}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.phone_number') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no" value="{{$buyer->phone_no}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.email') }}</label>
                                        <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email" value="{{$buyer->email}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.race') }}</label>
                                        <select id="race" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($race as $races)
                                            <option value="{{ $races->id }}" {{($buyer->race_id == $races->id ? " selected" : "")}}>{{ $races->name_en }}</option>
                                            @endforeach
                                        </select>
                                        <div id="race_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.nationality') }}</label>
                                        <select id="nationality" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($nationality as $national)
                                            <option value="{{ $national->id }}" {{($buyer->nationality_id == $national->id ? " selected" : "")}}>{{ $national->name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="nationality_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.caj_penyelenggaraan') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.caj_penyelenggaraan') }}" id="caj_penyelenggaraan" value="{{$buyer->caj_penyelenggaraan}}">
                                        <div id="caj_penyelenggaraan_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.sinking_fund') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="sinking_fund" value="{{$buyer->sinking_fund}}">
                                        <div id="sinking_fund_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.remarks') }}</label>
                                        <textarea class="form-control" placeholder="{{ trans('app.forms.remarks') }}" rows="3" id="remarks">{{$buyer->remarks}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($update_permission == 1) { ?>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="editPurchaser()">{{ trans('app.forms.submit') }}</button>
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
    function editPurchaser() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        $("#file_id_error").css("display", "none");
        $("#unit_no_error").css("display", "none");
        $("#owner_name_error").css("display", "none");
        $("#race_error").css("display", "none");
        $("#nationality_error").css("display", "none");

        var file_id = $("#file_id").val(),
                unit_no = $("#unit_no").val(),
                unit_share = $("#unit_share").val(),
                owner_name = $("#owner_name").val(),
                ic_company_no = $("#ic_company_no").val(),
                address = $("#address").val(),
                phone_no = $("#phone_no").val(),
                email = $("#email").val(),
                race = $("#race").val(),
                nationality = $("#nationality").val(),
                remarks = $("#remarks").val(),
                no_petak = $("#no_petak").val(),
                no_petak_aksesori = $("#no_petak_aksesori").val(),
                keluasan_lantai_petak = $("#keluasan_lantai_petak").val(),
                keluasan_lantai_petak_aksesori = $("#keluasan_lantai_petak_aksesori").val(),
                jenis_kegunaan = $("#jenis_kegunaan").val(),
                nama2 = $("#nama2").val(),
                ic_no2 = $("#ic_no2").val(),
                alamat_surat_menyurat = $("#alamat_surat_menyurat").val(),
                caj_penyelenggaraan = $("#caj_penyelenggaraan").val(),
                sinking_fund = $("#sinking_fund").val();

        var error = 0;

        if (file_id.trim() == "") {
            $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File"]) }}</span>');
            $("#file_id_error").css("display", "block");
            error = 1;
        }
        if (unit_no.trim() == "") {
            $("#unit_no_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Unit Number"]) }}</span>');
            $("#unit_no_error").css("display", "block");
            error = 1;
        }
        if (owner_name.trim() == "") {
            $("#owner_name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Owner Name"]) }}</span>');
            $("#owner_name_error").css("display", "block");
            error = 1;
        }
        if (race.trim() == "") {
            $("#race_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Race"]) }}</span>');
            $("#race_error").css("display", "block");
            error = 1;
        }
        if (nationality.trim() == "") {
            $("#nationality_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Nationality"]) }}</span>');
            $("#nationality_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AgmController@submitEditPurchaser') }}",
                type: "POST",
                data: {
                    unit_no: unit_no,
                    unit_share: unit_share,
                    owner_name: owner_name,
                    ic_company_no: ic_company_no,
                    address: address,
                    phone_no: phone_no,
                    email: email,
                    remarks: remarks,
                    race: race,
                    nationality: nationality,
                    no_petak: no_petak,
                    no_petak_aksesori: no_petak_aksesori,
                    keluasan_lantai_petak: keluasan_lantai_petak,
                    keluasan_lantai_petak_aksesori: keluasan_lantai_petak_aksesori,
                    jenis_kegunaan: jenis_kegunaan,
                    nama2: nama2,
                    ic_no2: ic_no2,
                    alamat_surat_menyurat: alamat_surat_menyurat,
                    caj_penyelenggaraan: caj_penyelenggaraan,
                    sinking_fund: sinking_fund,
                    file_id: file_id,
                    id: '{{ \Helper\Helper::encode($buyer->id) }}'
                },
                beforeSend: function() {
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.purchaser.update') }}</span>", function () {
                            window.location = '{{URL::action("AgmController@purchaser") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                },
                complete: function() {
                    $.unblockUI();
                },
            });
        } else {
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>
<!-- End Page Scripts-->

@stop
