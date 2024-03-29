@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file')
                        
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="buyer_tab" role="tabpanel">
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
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><span style="color: red;">*</span> {{ trans('app.forms.unit_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.unit_number') }}" id="unit_no">
                                                            <div id="unit_no_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.no_petak') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.no_petak') }}" id="no_petak">
                                                            <div id="no_petak_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.no_petak_aksesori') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.no_petak_aksesori') }}" id="no_petak_aksesori">
                                                            <div id="no_petak_aksesori_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.keluasan_lantai_petak') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.keluasan_lantai_petak') }}" id="keluasan_lantai_petak">
                                                            <div id="keluasan_lantai_petak_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.keluasan_lantai_petak_aksesori') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.keluasan_lantai_petak_aksesori') }}" id="keluasan_lantai_petak_aksesori">
                                                            <div id="keluasan_lantai_petak_aksesori_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.unit_share') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.unit_share') }}" id="unit_share">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.jenis_kegunaan') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.jenis_kegunaan') }}" id="jenis_kegunaan">
                                                            <div id="jenis_kegunaan_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><span style="color: red;">*</span> {{ trans('app.forms.owner_name') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.owner_name') }}" id="owner_name">
                                                            <div id="owner_name_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.ic_company_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.ic_company_number') }}" id="ic_company_no">
                                                            <div id="ic_company_no_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.email') }}</label>
                                                            <input type="email" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.phone_number') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.address') }}</label>
                                                            <textarea class="form-control" placeholder="{{ trans('app.forms.address') }}" rows="3" id="address"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.alamat_surat_menyurat') }}</label>
                                                            <textarea class="form-control" placeholder="{{ trans('app.forms.alamat_surat_menyurat') }}" rows="3" id="alamat_surat_menyurat"></textarea>
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
                                                                <option value="{{ $races->id }}">{{ $races->name_en }}</option>
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
                                                                <option value="{{ $national->id }}">{{ $national->name }}</option>
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
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.caj_penyelenggaraan') }}" id="caj_penyelenggaraan">
                                                            <div id="caj_penyelenggaraan_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.sinking_fund') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.sinking_fund') }}" id="sinking_fund">
                                                            <div id="sinking_fund_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.remarks') }}</label>
                                                            <textarea class="form-control" placeholder="{{ trans('app.forms.remarks') }}" rows="3" id="remarks"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.nama2') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.nama2') }}" id="nama2">
                                                            <div id="nama2_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.ic_no2') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.ic_no2') }}" id="ic_no2">
                                                            <div id="ic_no2_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.email2') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.email2') }}" id="email2">
                                                            <div id="email2_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.phone_no2') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_no2') }}" id="phone_no2">
                                                            <div id="phone_no2_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.nama3') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.nama3') }}" id="nama3">
                                                            <div id="nama3_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.ic_no3') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.ic_no3') }}" id="ic_no3">
                                                            <div id="ic_no3_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.email3') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.email3') }}" id="email3">
                                                            <div id="email3_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.phone_no3') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_no3') }}" id="phone_no3">
                                                            <div id="phone_no3_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.lawyer_name') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.lawyer_name') }}" id="lawyer_name">
                                                            <div id="lawyer_name_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.lawyer_address') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.lawyer_address') }}" id="lawyer_address">
                                                            <div id="lawyer_address_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('app.forms.lawyer_fail_ref_no') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.lawyer_fail_ref_no') }}" id="lawyer_fail_ref_no">
                                                            <div id="lawyer_fail_ref_no_error" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <button type="button" class="btn btn-own" id="submit_button" onclick="addBuyer()">{{ trans('app.forms.submit') }}</button>
                                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@buyer', $files->id)}}'">{{ trans('app.forms.cancel') }}</button>
                                                    <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                                </div>
                                            </form>
                                            <!-- End Form -->
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    var changes = false;
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "Data you have entered may not be saved, do you really want to leave?";
        }
    });
    
    function addBuyer() {
        changes = false;
        $("#submit_button").attr("disabled", "disabled");
        $("#unit_no_error").css("display", "none");
        $("#owner_name_error").css("display", "none");
        $("#race_error").css("display", "none");
        $("#nationality_error").css("display", "none");

        var unit_no = $("#unit_no").val(),
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
                email2 = $("#email2").val(),
                phone_no2 = $("#phone_no2").val(),
                nama3 = $("#nama3").val(),
                ic_no3 = $("#ic_no3").val(),
                email3 = $("#email3").val(),
                phone_no3 = $("#phone_no3").val(),
                alamat_surat_menyurat = $("#alamat_surat_menyurat").val(),
                caj_penyelenggaraan = $("#caj_penyelenggaraan").val(),
                sinking_fund = $("#sinking_fund").val(),
                lawyer_name = $("#lawyer_name").val(),
                lawyer_address = $("#lawyer_address").val(),
                lawyer_fail_ref_no = $("#lawyer_fail_ref_no").val();

        var error = 0;

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
                url: "{{ URL::action('AdminController@submitBuyer') }}",
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
                    email2: email2,
                    phone_no2: phone_no2,
                    nama3: nama3,
                    ic_no3: ic_no3,
                    email3: email3,
                    phone_no3: phone_no3,
                    alamat_surat_menyurat: alamat_surat_menyurat,
                    caj_penyelenggaraan: caj_penyelenggaraan,
                    sinking_fund: sinking_fund,
                    lawyer_name: lawyer_name,
                    lawyer_address: lawyer_address,
                    lawyer_fail_ref_no: lawyer_fail_ref_no,
                    file_id: '{{ \Helper\Helper::encode($files->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location = '{{URL::action("AdminController@buyer", \Helper\Helper::encode($files->id)) }}';
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
        }
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>
<!-- End Page Scripts-->

@stop
