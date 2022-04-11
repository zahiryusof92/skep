@extends('layout.english_layout.default_custom')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        @include('alert.bootbox')

                        <form id="epks-form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.scheme_name') }}</label>
                                        <select id="scheme_name" name="scheme_name" class="form-control select3" data-placeholder="{{ trans('app.forms.please_select') }}" data-ajax--url="{{ route('v3.api.strata.getOption', ['type' => 'id']) }}" data-ajax--cache="true">
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'scheme_name'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.email') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email" name="email" value="{{ Input::old('email') }}">
                                        @include('alert.feedback-ajax', ['field' => 'email'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.address') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address1') }}" id="address1" name="address1" value="{{ Input::old('address1') }}">
                                        @include('alert.feedback-ajax', ['field' => 'address1'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="address2" name="address2" value="{{ Input::old('address2') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="address3" name="address3" value="{{ Input::old('address3') }}">
                                    </div>
                                </div>
                            </div>

                            <div id="main-container">
                                <label class="form-control-label">{{ trans('app.forms.place_suggestions') }}</label>
                                <div class="container-item">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.image') }}</label>
                                                <input type="file" class="form-control" name="place_proposal[][filename]" id="place_proposal.0.filename" onChange="onUpload(this)">
                                                @include('alert.feedback-ajax', ['field' => "place_proposal.0.filename_url"])
                                            </div>
                                            <input hidden id="place_proposal.0.filename_url" name="place_proposal[][filename_url]">
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.location') }}</label>
                                                <input type="text" class="form-control" name="place_proposal[][location]" id="place_proposal.0.location">
                                                @include('alert.feedback-ajax', ['field' => "place_proposal.0.location"])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label class="form-control-label"> {{ trans('app.forms.remarks') }}</label>
                                                <textarea type="text" class="form-control" name="place_proposal[][remarks]" id="place_proposal.0.remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 margin-top-35">
                                            <button class="remove-item btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:void(0);" id="add-more" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div id="main-container-lakaran">
                                <label class="form-control-label">{{ trans('app.forms.pks_sketch_proposal') }}</label>
                                <div class="container-item-lakaran">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.image') }}</label>
                                                <input type="file" class="form-control" name="sketch_proposal[][filename]" id="sketch_proposal.0.filename" onChange="onUpload(this)">
                                                @include('alert.feedback-ajax', ['field' => "sketch_proposal.0.filename_url"])
                                            </div>
                                            <input hidden id="sketch_proposal.0.filename_url" name="sketch_proposal[][filename_url]">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label class="form-control-label"> {{ trans('app.forms.remarks') }}</label>
                                                <textarea type="text" class="form-control" name="sketch_proposal[][remarks]" id="sketch_proposal.0.remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 margin-top-35">
                                            <button class="remove-item-lakaran btn btn-danger btn-xs">{{ trans('app.forms.remove') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:void(0);" id="add-more-lakaran" class="btn btn-success btn-xs">{{ trans('app.forms.add_more') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                @if (AccessGroup::hasInsert(63))
                                <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.save') }}</button>
                                @endif
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('epks.index') }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>
<script>
    $(function() {
        $('#add-more').cloneData({
            mainContainerId:'main-container', // container to hold the dulicated form fields
            cloneContainer:'container-item', // Which you want to clone
            removeButtonClass:'remove-item', // CSS lcass of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default,
        });
        $('#add-more-lakaran').cloneData({
            mainContainerId:'main-container-lakaran', // container to hold the dulicated form fields
            cloneContainer:'container-item-lakaran', // Which you want to clone
            removeButtonClass:'remove-item-lakaran', // CSS lcass of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default,
        });
        $('.select3').select2();

        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            let formData = $('form').serialize();
            $.ajax({
                url: "{{ route('epks.store') }}",
                type: "POST",
                data: formData,
                dataType: 'JSON',
                beforeSend: function() {
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                    
                    $('.help-block').text("");
                },
                success: function (res) {
                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.epks.store') }}</span>", function () {
                            let url = "{{ route('epks.show', [':id']) }}";
                            url = url.replace(":id", res.id);
                            window.location = url;
                        });
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                if(key.includes('.')) {
                                    let myId = key.replace(/\./g,'\\.');
                                    $("#" + myId + "_error").children("strong").text(value);
                                } else {
                                    $("#" + key + "_error").children("strong").text(value);
                                }
                            });
                        }
                        
                        if(res.message != "Validation Fail") {
                            bootbox.alert("<span style='color:red;'>" + res.message + "</span>");
                        } else {
                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    }
                },
                complete: function() {
                    $.unblockUI();
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                },
            });
        });
    });

    function onUpload(e) {
        let id = e.getAttribute('id');
        let myId = id.replace(/\./g,'\\.');
        let data = new FormData();
        if(e.files.length > 0) {
            data.append(id, e.files[0]);
        }
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{ route('epks.imageUpload') }}",
            data: data,
            async: true,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            beforeSubmit: function() {
                $("#"+ id + "_url_error").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#"+ id + "_url_error").html(value);
                        }
                    });
                    $("#"+ myId + "_url_error").show();
                    $("#"+ myId).css("color", "red");
                } else {
                    if(response.error == true) {
                        bootbox.alert("<span style='color:red;'>" + response.message + "</span>");
                    } else {
                        console.log(myId)
                        $("#" + myId + "_url_error").html("<button id='clear_" + id + "' class='btn btn-xs btn-danger' onclick='clearFile(this)'><i class='fa fa-times'></i> </button>&nbsp;&nbsp;<i class='fa fa-check' style='color:green;'></i>");
                        $("#clear_" + myId).show();
                        $("#" + myId + "_url_error").show();
                        $("#" + myId).css("color", "green");
                        $("#" + myId + "_url").val(response.file);
                    }
                }
            }
        });
    }

    function clearFile(e) {
        let id = e.getAttribute('id');
        let name = e.getAttribute('name');
        id = id.replace("clear_", "");
        let myId = id.replace(/\./g,'\\.');
        console.log(myId)
        $("#" + myId).val("");
        $("#" + myId + "_url").val("");
        $("#" + myId).css("color", "");
        $("#" + myId + "_url_error").empty().hide();
        $("#" + myId + "_download").html('<input type="file" class="form-control" name="' + name + '" id="' + id + '" onChange="onUpload(this)">');
    }
</script>
@endsection