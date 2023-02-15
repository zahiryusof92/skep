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
                                            {{ trans('app.forms.date') }}
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="input-group">
                                            <input type="text" id="date" name="date" class="form-control date_picker"
                                                placeholder="{{ trans('YYYY-MM-DD') }}"
                                                value="{{ !empty(Input::old('date')) ? Input::old('date') : '' }}" />
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        @include('alert.feedback-ajax', ['field' => "date"])
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <table class="table table-hover table-own table-striped nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">
                                            {{ trans('app.forms.order_no') }}
                                        </th>
                                        <th style="width: 25%;">
                                            {{ trans('app.forms.type') }}
                                        </th>
                                        <th style="width: 25%;">
                                            {{ trans('app.forms.strata') }}
                                        </th>                                        
                                        <th style="width: 25%;">
                                            {{ trans('app.forms.bill_no') }}
                                        </th>
                                    </tr>
                                </thead>
                                @if ($orders)
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            {{ $order->order_no }}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            {{ $order->getTypeText() }}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            {{ ($order->strata ? $order->strata->name : '') }}
                                        </td>                                        
                                        <td style="vertical-align: middle;">
                                            <input type="text" id="bill_no{{ $order->id }}"
                                                name="bill_no[{{ $order->id }}]" class="form-control"
                                                placeholder="{{ trans('app.forms.bill_no') }}">
                                            @include('alert.feedback-ajax', ['field' => 'bill_no' . $order->id])
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @endif
                            </table>

                            <div class="form-actions">
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
        $(".date_picker").datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'YYYY-MM-DD'
        });

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
                        url: "{{ route('eservice.submitApprove') }}",
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
                            if (res.success == true) {
                                bootbox.alert("<span style='color:green;'>{{ trans('app.successes.eservice.update') }}</span>", function () {
                                    let url = "{{ route('eservice.approved') }}";
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