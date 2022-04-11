@extends('layout.english_layout.default')

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
                                        <select id="scheme_name" name="scheme_name" class="form-control select2" placeholder="{{ trans('app.forms.please_select') }}" disabled>
                                            @foreach($strataOptions as $option)
                                                <option value="{{ $option->id }}" {{ $model->strata_id == $option->id ? "selected" : "" }}>{{ $option->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => "scheme_name"])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.email') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email" name="email" value="{{ $model->email }}" readonly>
                                        @include('alert.feedback-ajax', ['field' => "email"])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.address') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address1') }}" id="address1" name="address1" value="{{ $model->address_1 }}" readonly>
                                        @include('alert.feedback-ajax', ['field' => "address1"])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address2') }}" id="address2" name="address2" value="{{ $model->address_2 }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{ trans('app.forms.address3') }}" id="address3" name="address3" value="{{ $model->address_3 }}" readonly>
                                    </div>
                                </div>
                            </div>

                            @if(in_array($model->status, [Epks::PENDING, Epks::INPROGRESS, Epks::APPROVED, Epks::REJECTED]))
                            <?php 
                                $readonly = ((Auth::user()->getAdmin() || Auth::user()->isCOB()) && in_array($model->status, [Epks::PENDING, Epks::INPROGRESS]))? "" : "readonly";
                            ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.status') }}</label>
                                        <select class="form-control" id="status" name="status" {{ $readonly? "disabled" : "" }}>
                                            @foreach($statusOptions as $key => $option)
                                                <option value="{{ $key }}" {{ $model->status == $key? "selected" : "" }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => "email"])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.upload_file') }}</label>
                                        @if ($model->filename != "")
                                            <div id="filename_download">
                                                <a href="{{ asset($model->filename) }}" target="_blank">
                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                                        <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                                    </button>
                                                </a>
                                                &nbsp;
                                                @if(in_array($model->status, [Epks::PENDING, Epks::INPROGRESS]))
                                                <button type="button" id="clear_filename" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="clearFile(this)">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        @else
                                        <input type="file" class="form-control" name="filename" id="filename" onChange="onFileUpload(this)" {{ $readonly? "disabled" : "" }}>
                                        @endif
                                        @include('alert.feedback-ajax', ['field' => "filename_url"])
                                    </div>
                                    <input hidden id="filename_url" name="filename_url" value="{{ $model->filename }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.remarks') }}</label>
                                        <textarea type="text" class="form-control" id="remarks" name="remarks" placeholder="{{ trans('app.forms.remarks') }}" rows="3" {{ $readonly}}>{{ $model->remarks }}</textarea>
                                        @include('alert.feedback-ajax', ['field' => "remarks"])
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div id="main-container">
                                <label class="form-control-label">{{ trans('app.forms.place_suggestions') }}</label>
                                @if(!empty($model->place_proposal))
                                    @foreach(json_decode($model->place_proposal) as $key => $place_proposal)
                                    <div class="container-item">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.image') }}</label>
                                                    @if ($place_proposal->filename_url != "")
                                                        <div id="place_proposal_{{$key}}_download">
                                                            <a href="{{ asset($place_proposal->filename_url) }}" target="_blank">
                                                                <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                                                    <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                                                </button>
                                                            </a>
                                                        </div>
                                                    @else
                                                    <input type="file" class="form-control" name="place_proposal[][filename]" id="place_proposal.{{ $key }}.filename" onChange="onUpload(this)" disabled>
                                                    @endif
                                                    @include('alert.feedback-ajax', ['field' => "place_proposal.{{ $key }}.filename_url"])
                                                </div>
                                                <input hidden id="place_proposal.{{ $key }}.filename_url" name="place_proposal[][filename_url]">
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.location') }}</label>
                                                    <input type="text" class="form-control" name="place_proposal[][location]" id="place_proposal.{{ $key }}.location" value="{{ $place_proposal->location }}" readonly>
                                                    @include('alert.feedback-ajax', ['field' => "place_proposal.{{ $key }}.location"])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <label class="form-control-label"> {{ trans('app.forms.remarks') }}</label>
                                                    <textarea type="text" class="form-control" name="place_proposal[][remarks]" id="place_proposal.{{ $key }}.remarks" readonly>{{ $place_proposal->remarks }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="container-item">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <h3>{{ trans('app.forms.no_suggestions') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                
                                @endif
                            </div>

                            <div id="main-container-lakaran">
                                <label class="form-control-label">{{ trans('app.forms.pks_sketch_proposal') }}</label>
                                @if(!empty($model->sketch_proposal))
                                    @foreach(json_decode($model->sketch_proposal) as $key => $sketch_proposal)
                                        <div class="container-item-lakaran">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.image') }}</label>
                                                        @if ($sketch_proposal->filename_url != "")
                                                            <div id="sketch_proposal_{{$key}}_download">
                                                                <a href="{{ asset($sketch_proposal->filename_url) }}" target="_blank">
                                                                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File">
                                                                        <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        @else
                                                        <input type="file" class="form-control" name="sketch_proposal[][filename]" id="sketch_proposal.{{ $key }}.filename" onChange="onUpload(this)" disabled>
                                                        @endif
                                                        @include('alert.feedback-ajax', ['field' => "sketch_proposal.{{ $key }}.filename_url"])
                                                    </div>
                                                    <input hidden id="sketch_proposal.{{ $key }}.filename_url" name="sketch_proposal[][filename_url]">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="form-group">
                                                        <label class="form-control-label"> {{ trans('app.forms.remarks') }}</label>
                                                        <textarea type="text" class="form-control" name="sketch_proposal[][remarks]" id="sketch_proposal.{{ $key }}.remarks" readonly>{{ $sketch_proposal->remarks }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="container-item-lakaran">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <h3>{{ trans('app.forms.no_suggestions') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @if($model->status == Epks::DRAFT)
                        </form>
                            <div class="form-actions">
                                @if(AccessGroup::hasUpdate(63))
                                    <a href="{{ route('epks.edit', [\Helper\Helper::encode(Config::get('constant.module.epks.name'), $model->id)]) }}" class="btn btn-success" id="edit_button">{{ trans('app.forms.edit') }}</a>
                                    <form action="{{ route('epks.submitConfirm', \Helper\Helper::encode(Config::get('constant.module.epks.name'), $model->id)) }}" method="POST" id="confirmation_form_{{ \Helper\Helper::encode(Config::get('constant.module.epks.name'), $model->id) }}" style="display:inline-block;">
                                    <input type="hidden" name="_method" value="POST">
                                    <button type="submit" class="btn btn-own submit-confirm" id="confirm_button" data-id="confirmation_form_{{ \Helper\Helper::encode(Config::get('constant.module.epks.name'), $model->id) }}" title="Confirmation">{{ trans('app.forms.submit_application') }}</button>
                                    </form>
                                @endif
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('epks.draft') }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        @elseif(in_array($model->status, [Epks::PENDING, Epks::INPROGRESS]) && (Auth::user()->getAdmin() || Auth::user()->isCOB()))
                            <div class="form-actions">
                                @if(AccessGroup::hasUpdate(63))
                                <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.save') }}</button>
                                @endif
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('epks.index') }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </form>
                        @else
                            <div class="form-actions">
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('epks.index') }}'">{{ trans('app.forms.cancel') }}</button>
                            </div>
                        </form>
                        @endif
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

        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            let route = "{{ route('epks.submitByCOB', [':id']) }}";
            route = route.replace(':id', "{{ \Helper\Helper::encode(Config::get('constant.module.epks.name'), $model->id) }}");
            let formData = $('form').serialize();
        
            $.ajax({
                url: route,
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
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.epks.update') }}</span>", function () {
                            let url = "{{ route('epks.index') }}";
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

    $('body').on('click', '.submit-confirm', function (e) {
        e.preventDefault();
        let formId = $(this).data('id');

        $("#loading").css("display", "inline-block");
        $("#edit_button").addClass('disabled');
        $("#confirm_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.submit_epks') }}",
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-info",
            cancelButtonClass: "btn-default",
            cancelButtonText: "{{ trans('app.forms.cancel') }}",
            confirmButtonText: "{{ trans('app.forms.submit') }}",
            closeOnConfirm: true
        }, function (isConfirm) {
            if(isConfirm) {
                $('#' + formId).submit();
            } else {
                $("#loading").css("display", "none");
                $("#edit_button").removeClass('disabled');
                $("#confirm_button").removeAttr("disabled");
                $("#cancel_button").removeAttr("disabled");
            }
        });
    });

    function onUpload(e) {
        let id = e.getAttribute('id');
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
                    $("#"+ id + "_url_error").show();
                    $("#"+ id).css("color", "red");
                } else {
                    if(response.error == true) {
                        bootbox.alert("<span style='color:red;'>" + response.message + "</span>");
                    } else {
                        $("#" + id + "_url_error").html("<button id='clear_" + id + "' class='btn btn-xs btn-danger' onclick='clearFile(this)'><i class='fa fa-times'></i> </button>&nbsp;&nbsp;<i class='fa fa-check' style='color:green;'></i>");
                        $("#clear_" + id).show();
                        $("#" + id + "_url_error").show();
                        $("#" + id).css("color", "green");
                        $("#" + id + "_url").val(response.file);
                    }
                }
            }
        });
    }

    function onFileUpload(e) {
        let id = e.getAttribute('id');
        let data = new FormData();
        if(e.files.length > 0) {
            data.append(id, e.files[0]);
        }
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{ route('epks.fileUpload') }}",
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
                    $("#"+ id + "_url_error").show();
                    $("#"+ id).css("color", "red");
                } else {
                    if(response.error == true) {
                        bootbox.alert("<span style='color:red;'>" + response.message + "</span>");
                    } else {
                        $("#" + id + "_url_error").html("<button id='clear_" + id + "' class='btn btn-xs btn-danger' onclick='clearFile(this)'><i class='fa fa-times'></i> </button>&nbsp;&nbsp;<i class='fa fa-check' style='color:green;'></i>");
                        $("#clear_" + id).show();
                        $("#" + id + "_url_error").show();
                        $("#" + id).css("color", "green");
                        $("#" + id + "_url").val(response.file);
                    }
                }
            }
        });
    }

    function clearFile(e) {
        let id = e.getAttribute('id');
        let name = e.getAttribute('name');
        id = id.replace("clear_", "");
        $("#" + id).val("");
        $("#" + id + "_url").val("");
        $("#" + id).css("color", "");
        $("#" + id + "_url_error").empty().hide();
        $("#" + id + "_download").html('<input type="file" class="form-control" name="' + name + '" id="' + id + '" onChange="onUpload(this)">');
    }
</script>
@endsection