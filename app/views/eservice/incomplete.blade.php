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

                        <form class="form-horizontal" onsubmit="event.preventDefault();">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label">
                                        <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.approval_remark') }}
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea id="approval_remark" name="approval_remark"
                                            class="form-control {{ $errors->has('approval_remark') ? 'has-danger' : '' }}"
                                            rows="5">{{ Input::old('approval_remark') }}</textarea>
                                        @include('alert.feedback-ajax', ['field' => "approval_remark"])
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                @if ($orders)
                                @foreach ($orders as $order)
                                <input type="hidden" name="bill_no[{{ $order->id }}]"/>
                                @endforeach
                                @endif
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('eservice.index') }}'">
                                    {{ trans('app.forms.cancel') }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </section>
        </div>
    </section>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready( function () {
        $("#submit_button").click(function (e) {
            e.preventDefault();
            (async () => {
                const { value: password } = await Swal.fire({
                    title: 'Your password',
                    input: 'password',
                    inputPlaceholder: 'Enter your password',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please enter your password!'
                        }
                    }
                })

                if (password) {
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

                    let formData = $('form').serialize();
                    $.ajax({
                        url: "{{ route('eservice.submitIncomplete') }}",
                        type: "POST",
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
                                    let url = "{{ route('eservice.incomplete') }}";
                                    window.location = url;
                                });
                            } else {
                                if(res.errors !== undefined) {
                                    $.each(res.errors, function (key, value) {
                                        if(key.includes('_tmp')) {                                    
                                            let myId = key.replace(/_tmp/g, '');
                                            $("#" + myId + "_error").children("strong").text(value);
                                        } else if(key.includes('.')) { 
                                            let id = key.replace(/\./g, '');
                                            $("#" + id + "_error").children("strong").text(value);
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
                }
            })()
        });
    });
</script>
@endsection