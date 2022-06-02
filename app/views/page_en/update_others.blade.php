@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 3) {
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
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$file->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file', ['files' => $file])
                        
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="others_tab" role="tabpanel">

                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h4>{{ trans('app.forms.detail') }}</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <!-- Form -->
                                                    <form>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.name') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="other_details_name" value="{{($other_details ? $other_details->name : '')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <form id="upload_others_image" enctype="multipart/form-data" method="post" action="{{url('uploadOthersImage')}}" autocomplete="off">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.photo') }}</label>
                                                                    <br />
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                                    <button type="button" id="clear_image" data-toggle="tooltip" data-placement="top" title="Clear" class="btn btn-xs btn-danger" onclick="clearImage()" style="display: none;"><i class="fa fa-times"></i></button>
                                                                    &nbsp;
                                                                    <input type="file" name="image" id="image" />
                                                                    <br />
                                                                    <small class="text-danger">File uploaded should be below 2MB</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if ($other_details && $other_details->image_url != "")
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div id="others_image_output">
                                                                        <a href="{{asset($other_details->image_url)}}" target="_blank"><img src="{{asset($other_details->image_url)}}" style="width:50%; cursor: pointer;"/></a>
                                                                        <?php if ($update_permission == 1) { ?>
                                                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteImageOthers('{{ \Helper\Helper::encode($other_details->id) }}')"><i class="fa fa-times"></i></button>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div id="validation-errors"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div id="others_image_output"></div>
                                                                    <div id="validation-errors"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </form>
                                                    <form>
                                                        @if ($other_details && $other_details->latitude != "0")
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.latitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.latitude') }} " id="latitude" value="{{$other_details->latitude}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.latitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.latitude') }} " id="latitude" value="{{$other_details->latitude}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if ($other_details && $other_details->longitude != "0")
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.longitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.longitude') }} " id="longitude" value="{{$other_details->longitude}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.longitude') }} </label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.longitude') }} " id="longitude" value="{{$other_details->longitude}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if ($other_details)
                                                        @if ($other_details->latitude != "0" && $other_details->longitude != "0")
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <a href="https://www.google.com.my/maps/preview?q={{$other_details->latitude}},{{$other_details->longitude}}" target="_blank">
                                                                        <button type="button" class="btn btn-success">
                                                                            <i class="fa fa-map-marker"> {{ trans('app.forms.view_map') }}</i>
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.description') }}</label>
                                                                    <textarea class="form-control" rows="4" id="other_details_description" placeholder="{{ trans('app.forms.description') }}">{{($other_details ? $other_details->description : '')}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-4">
                                                    <form>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.pms_system') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.pms_system') }}" id="pms_system" value="{{($other_details ? $other_details->pms_system : '')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.owner_occupied') }}</label>
                                                                    <select id="owner_occupied" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->owner_occupied == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->owner_occupied == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.rented') }}</label>
                                                                    <select id="rented" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->rented == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->rented == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.lphs_donation') }}</label>
                                                                    <select id="bantuan_lphs" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->bantuan_lphs == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->bantuan_lphs == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.other_donation') }}</label>
                                                                    <select id="bantuan_others" class="form-control">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="1" {{ ($other_details && $other_details->bantuan_others == '1' ? " selected" : "") }}>{{ trans("app.forms.yes") }}</option>
                                                                        <option value="0" {{ ($other_details && $other_details->bantuan_others == '0' ? " selected" : "") }}>{{ trans("app.forms.no") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.rumah_selangorku') }}</label>
                                                                    <select id="rsku" class="form-control select2">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="none" {{ ($other_details && $other_details->rsku == 'none' ? " selected" : "") }}>- {{ trans('app.forms.none') }} -</option>
                                                                        <option value="< 42,000" {{ ($other_details && $other_details->rsku == '< 42,000' ? " selected" : "") }}>< 42,000</option>
                                                                        <option value="< 100,000" {{ ($other_details && $other_details->rsku == '< 100,000' ? " selected" : "") }}>< 100,000</option>
                                                                        <option value="< 180,000" {{ ($other_details && $other_details->rsku == '< 180,000' ? " selected" : "") }}>< 180,000</option>
                                                                        <option value="< 250,000" {{ ($other_details && $other_details->rsku == '< 250,000' ? " selected" : "") }}>< 250,000</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.water_meter') }}</label>
                                                                    <select id="water_meter" class="form-control select2">
                                                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                                                        <option value="none" {{ ($other_details && $other_details->water_meter == 'none' ? " selected" : "") }}>- {{ trans('app.forms.none') }} -</option>
                                                                        <option value="BULK" {{ ($other_details && $other_details->water_meter == 'BULK' ? " selected" : "") }}>{{ trans('app.forms.bulk') }}</option>
                                                                        <option value="INDIVIDUAL" {{ ($other_details && $other_details->water_meter == 'INDIVIDUAL' ? " selected" : "") }}>{{ trans('app.forms.individual') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-4">
                                                    <form>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.parking_bay') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.parking_bay') }}" id="parking_bay" value="{{($other_details ? $other_details->parking_bay : '')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.parking_area') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.parking_area') }}" id="parking_area" value="{{($other_details ? $other_details->parking_area : '')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(str_contains(Request::url(), 'https://ecob.mps.gov.my/'))
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.tnb') }}</label>
                                                                    <select id="tnb" class="form-control select2">
                                                                        @foreach ($tnbLists as $key => $val)
                                                                            <option value="{{ $key }}" {{ ($other_details && $other_details->tnb == $key ? "selected" : "") }}>{{ $val }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                        <input type="hidden" id="tnb" name="tnb" value="">
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.malay_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.malay_composition') }}" id="malay_composition" value="{{Buyer::getCompositionByRace($file->id, 'Malay')}}" disabled="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.chinese_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.chinese_composition') }}" id="chinese_composition" value="{{Buyer::getCompositionByRace($file->id, 'Chinese')}}" disabled="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.indian_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.indian_composition') }}" id="indian_composition" value="{{Buyer::getCompositionByRace($file->id, 'Indian')}}" disabled="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.others_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.others_composition') }}" id="others_composition" value="{{Buyer::getCompositionByRace($file->id, 'Others')}}" disabled="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('app.forms.foreigner_composition') }}</label>
                                                                    <input type="number" step="0.01" class="form-control text-right" placeholder="{{ trans('app.forms.foreigner_composition') }}" id="foreigner_composition" value="{{Buyer::getForeignerComposition($file->id)}}" disabled="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>                                        

                                            <form>
                                                <div class="form-actions">
                                                    <input type="hidden" id="others_image_url" value="{{$other_details ? $other_details->image_url : ''}}"/>
                                                    <?php if ($update_permission == 1) { ?>
                                                        <button type="button" class="btn btn-own" id="submit_button" onclick="updateOtherDetails()">{{ trans('app.forms.submit') }}</button>
                                                    <?php } ?>

                                                    @if ($file->is_active != 2)
                                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileList')}}'">{{ trans('app.forms.cancel') }}</button>
                                                    @else
                                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@fileListBeforeVP')}}'">{{ trans('app.forms.cancel') }}</button>
                                                    @endif
                                                </div>
                                            </form>
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
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });

    $(document).ready(function () {
        $(function() {
            $("#image").change(function() {
                $("#validation-errors").empty(); // To remove the previous error message
                var file = this.files[0];
                var imagefile = file.type;
                var size = file.size;
                
                var match = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]) || (imagefile == match[3]))) {
                    $("#validation-errors").html("<span id='error'>{{ trans('app.forms.please_select_valid_image') }}</span><br/>" + "<span id='error_message'>{{ trans('app.forms.only_image_allowed') }}</span>");
                    $("#validation-errors").css("color", "red");
                    return false;
                } else if (size > 2000000) {
                    $("#validation-errors").html("<span id='error_message'>Image size is exceeding 2MB</span>");
                    $("#validation-errors").css("color", "red");
                    return false;
                } else {
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });

        function imageIsLoaded(e) {
            $("#clear_image").show();
            $("#image").css("color", "green");
            $('#others_image_output').css("display", "block");
            $("#others_image_output").html("<img id='previewing' style='width: 50%;'/>");
            $('#previewing').attr('src', e.target.result);
        };

        //upload
        var options = {
            beforeSubmit: showRequest,
            success: showResponse,
            dataType: 'json'
        };

        $('body').delegate('#image', 'change', function () {
            $('#upload_others_image').ajaxForm(options).submit();
        });
    });

    function showRequest(formData, jqForm, options) {
        $("#validation-errors").css('display', 'none');
        return true;
    }
    function showResponse(response, statusText, xhr, $form) {
        if (response.success == false)
        {
            var arr = response.errors;
            $.each(arr, function (index, value)
            {
                if (value.length != 0)
                {
                    $("#validation-errors").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                }
            });
            $("#validation-errors").show();
            $("#image").css("color", "red");
        } else {
            $("#others_image_url").val(response.file);
        }
    }

    function updateOtherDetails(){
        changes = false;
        $("#loading").css("display", "inline-block");

        var other_details_name = $("#other_details_name").val(),
                others_image_url = $("#others_image_url").val(),
                latitude = $("#latitude").val(),
                longitude = $("#longitude").val(),
                other_details_description = $("#other_details_description").val(),
                pms_system = $("#pms_system").val(),
                owner_occupied = $("#owner_occupied").val(),
                rented = $("#rented").val(),
                bantuan_lphs = $("#bantuan_lphs").val(),
                bantuan_others = $("#bantuan_others").val(),
                rsku = $("#rsku").val(),
                water_meter = $("#water_meter").val(),
                tnb = $("#tnb").val(),
                parking_bay = $("#parking_bay").val(),
                parking_area = $("#parking_area").val(),
                malay_composition = $("#malay_composition").val(),
                chinese_composition = $("#chinese_composition").val(),
                indian_composition = $("#indian_composition").val(),
                others_composition = $("#others_composition").val(),
                foreigner_composition = $("#foreigner_composition").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateOtherDetails') }}",
                type: "POST",
                data: {
                    other_details_name: other_details_name,
                    others_image_url: others_image_url,
                    latitude: latitude,
                    longitude: longitude,
                    other_details_description: other_details_description,
                    pms_system: pms_system,
                    owner_occupied: owner_occupied,
                    rented: rented,
                    bantuan_lphs: bantuan_lphs,
                    bantuan_others: bantuan_others,
                    rsku: rsku,
                    water_meter: water_meter,
                    tnb: tnb,
                    parking_bay: parking_bay,
                    parking_area: parking_area,
                    malay_composition: malay_composition,
                    chinese_composition: chinese_composition,
                    indian_composition: indian_composition,
                    others_composition: others_composition,
                    foreigner_composition: foreigner_composition,
                    file_id : '{{ \Helper\Helper::encode($file->id) }}',
                    reference_id: '{{ ($other_details->reference_id ? $other_details->reference_id : $other_details->id) }}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        },{
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        window.location = "{{URL::action('AdminController@scoring', \Helper\Helper::encode($file->id))}}";
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function clearImage() {
        $("#image").val("");
        $("#others_image_url").val("");
        $("#image").css("color", "grey");
        $("#clear_image").hide();
        $("#validation-errors").hide();
        $("#others_image_output").css('display', 'none');
    }

    function deleteImageOthers(id){
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
        function(){
            $.ajax({
                url: "{{ URL::action('AdminController@deleteImageOthers') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.trim() == "true") {
                        swal({
                            title: "{{ trans('app.successes.deleted_title') }}",
                            text: "{{ trans('app.successes.deleted_text_file') }}",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            closeOnConfirm: false
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        });
    }

    $(function () {
        $("[data-toggle=tooltip]").tooltip();
    });
</script>


<!-- End Page Scripts-->

@stop