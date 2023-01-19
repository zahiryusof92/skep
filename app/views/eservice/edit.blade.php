@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        @include('alert.bootbox')

                        <form id="eservice-form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label">
                                        <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
                                    </label>
                                </div>
                            </div>

                            @if (!empty($form))
                            {{ $form }}
                            @endif

                            <div class="form-actions">
                                <button type="submit" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('eservice.show', \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id)) }}'">
                                    {{ trans('app.forms.cancel') }}
                                </button>
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
    $(document).ready( function () {
        $('.select2').select2();

        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            let route = "{{ route('eservice.update', [':id']) }}";
            route = route.replace(':id', "{{ \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id) }}");

            let formData = $('form').serialize();
            $.ajax({
                url: route,
                type: "PUT",
                data: formData,
                dataType: 'JSON',
                beforeSend: function() {
                    $('.help-block').text("");
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                },
                success: function (res) {
                    console.log(res);

                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.eservice.update') }}</span>", function () {
                            let url = "{{ route('eservice.show', [':id']) }}";
                            url = url.replace(":id", res.id);
                            window.location = url;
                        });
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                if(key.includes('_tmp')) {
                                    let myId = key.replace(/_tmp/g, '');
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
        let myId = id.replace(/_tmp/g, '');
        let data = new FormData();

        if (e.files.length > 0) {
            data.append(myId, e.files[0]);
        }

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{ route('eservice.fileUpload') }}",
            data: data,
            async: true,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            beforeSubmit: function() {
                console.log(myId);
                $("#"+ myId + "_error").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#"+ myId + "_error").html(value);
                        }
                    });
                    $("#"+ myId + "_error").show();
                    $("#"+ myId + "_tmp").css("color", "red");
                } else {
                    if(response.error == true) {
                        bootbox.alert("<span style='color:red;'>" + response.message + "</span>");
                    } else {
                        $("#" + myId + "_preview").html("<button id='clear_" + id + "' class='btn btn-xs btn-danger' onclick='clearFile(this)'><i class='fa fa-times'></i> Delete</button>");
                        $("#clear_" + myId).show();
                        $("#" + myId + "_preview").show();
                        $("#" + myId + "_tmp").css("color", "green");
                        $("#" + myId).val(response.file);
                    }
                }
            }
        });
    }

    function clearFile(e) {
        let id = e.getAttribute('id');
        let name = e.getAttribute('name');
        id = id.replace("clear_", "");
        let myId = id.replace(/_tmp/g, '');;;
        
        $("#" + myId).val("");
        $("#" + myId + "_tmp").val("");
        $("#" + myId + "_tmp").css("color", "");
        $("#" + myId + "_preview").empty().hide();
    }
</script>
@endsection