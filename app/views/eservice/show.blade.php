@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <?php 
            $readonly = ((Auth::user()->getAdmin() || Auth::user()->isCOB()) && in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS])) ? "" : "readonly";
        ?>

        <div class="panel-body">

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <form id="eservice-form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <dl class="row">
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.order_no') }}
                                </dt>
                                <dd class="col-sm-8">
                                    #{{ $order->order_no }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.created_at') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->created_at }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.file_no') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->file->file_no }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.strata') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->strata->name }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.amount') }}
                                </dt>
                                <dd class="col-sm-8">
                                    RM {{ $order->details->price }}
                                </dd>

                                @if ($order->transaction)
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.payment_method') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->transaction->payment_method }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.payment_status') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->transaction->getStatusText() }}
                                </dd>
                                @endif

                                @if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS])
                                && (Auth::user()->getAdmin() || Auth::user()->isCOB()))
                                <dt class="col-sm-4">
                                    <span style="color: red;">*</span>
                                    {{ trans('app.forms.status') }}
                                </dt>
                                <dd class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select
                                                class="form-control select2 {{ $errors->has('status') ? 'has-danger' : '' }}"
                                                id="status" name="status" onchange="statusChange(this.value)" {{
                                                $readonly ? "disabled" : "" }}>
                                                @foreach($statusOptions as $key => $option)
                                                <option value="{{ $key }}" {{ $order->status == $key ||
                                                    Input::old('status') == $key ? "selected" :
                                                    ""
                                                    }}>
                                                    {{ $option }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('alert.feedback-ajax', ['field' => "status"])
                                        </div>
                                    </div>
                                </dd>

                                <div id="approve_field"
                                    style="display: {{ (Input::old('status') == EServiceOrder::APPROVED ? 'show' : 'none') }};">
                                    <dt class="col-sm-4">
                                        <span style="color: red;">*</span>
                                        {{ trans('app.forms.bill_no') }}
                                    </dt>
                                    <dd class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input type="text" id="bill_no" name="bill_no"
                                                    class="form-control {{ $errors->has('bill_no') ? 'has-danger' : '' }}">
                                                @include('alert.feedback-ajax', ['field' => "bill_no"])
                                            </div>
                                        </div>
                                    </dd>
                                    <dt class="col-sm-4">
                                        <span style="color: red;">*</span>
                                        {{ trans('app.forms.date') }}
                                    </dt>
                                    <dd class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="input-group">
                                                    <input type="text" id="date" name="date"
                                                        class="form-control date_picker {{ $errors->has('date') ? 'has-danger' : '' }}"
                                                        value="{{ !empty(Input::old('date')) ? Input::old('date') : '' }}" />
                                                    <span class="input-group-addon">
                                                        <i class="icmn-calendar"></i>
                                                    </span>
                                                </label>
                                                @include('alert.feedback-ajax', ['field' => "date"])
                                            </div>
                                        </div>
                                    </dd>
                                </div>

                                <div id="reject_field"
                                    style="display: {{ (Input::old('status') == EServiceOrder::REJECTED ? 'show' : 'none') }};">
                                    <dt class="col-sm-4">
                                        <span style="color: red;">*</span>
                                        {{ trans('app.forms.approval_remark') }}
                                    </dt>
                                    <dd class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <textarea id="approval_remark" name="approval_remark"
                                                    class="form-control {{ $errors->has('approval_remark') ? 'has-danger' : '' }}"
                                                    rows="5">{{ Input::old('approval_remark') }}</textarea>
                                                @include('alert.feedback-ajax', ['field' => "approval_remark"])
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                                @else
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.status') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->getStatusText() }}
                                </dd>

                                @if ($order->approver)
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_by') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->approver->full_name }}
                                </dd>
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_date') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ (!empty($order->approval_date) ? $order->approval_date : '') }}
                                </dd>
                                @if (!empty($order->approval_remark))
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_remark') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $order->approval_remark }}
                                </dd>
                                @endif
                                @endif

                                @if ($order->status == EServiceOrder::APPROVED)
                                <dt class="col-sm-4">
                                    {{ trans('Download Letter Here') }}
                                </dt>
                                <dd class="col-sm-8">
                                    @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                                    <a href="{{ route('eservice.getLetterWord', \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id)) }}"
                                        target="_blank">
                                        <button type="button" class="btn btn-sm btn-primary btn-rounded"
                                            data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-word"></i>&nbsp;{{ $letter_type }}
                                        </button>
                                    </a>
                                    @endif
                                    <a href="{{ route('eservice.getLetterPDF', \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id)) }}"
                                        target="_blank">
                                        <button type="button" class="btn btn-sm btn-success btn-rounded"
                                            data-toggle="tooltip" data-placement="bottom" title="Download File">
                                            <i class="icmn-file-pdf"></i>&nbsp;{{ $letter_type }}
                                        </button>
                                    </a>
                                </dd>
                                @endif

                                @endif
                            </dl>
                        </form>

                        @if ($order->status == EServiceOrder::DRAFT)
                        <div class="form-actions">
                            <button type="button" class="btn btn-success" id="edit_button"
                                onclick="window.location = '{{ route('eservice.edit', \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id)) }}'">
                                {{ trans('app.forms.edit') }}
                            </button>
                            <button type="button" class="btn btn-own" id="payment_button"
                                onclick="window.location = '{{ route('eservice.payment', \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id)) }}'">
                                {{ trans('app.forms.eservice.proceed_to_pay') }}
                            </button>
                        </div>
                        @endif

                        @if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS]) &&
                        (Auth::user()->getAdmin() || Auth::user()->isCOB()))
                        <div class="form-actions">
                            <button type="submit" class="btn btn-own" id="submit_button">
                                {{ trans('app.forms.save') }}
                            </button>
                        </div>
                        @endif
                    </div>

                </div>
            </section>

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        <h4>{{ trans('Form') }}</h4>

                        @if ($order->details)
                        @if (!empty($order->details->bill_no))
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">
                                        {{ trans('app.forms.bill_no') }}
                                    </label>
                                    <input type="text" class="form-control" value="{{ $order->details->bill_no }}"
                                        readonly="">
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif

                        @if (!empty($form))
                        {{ $form }}
                        @endif

                    </div>
                </div>
            </section>

            <div class="form-actions">
                @if (in_array($order->status, [EServiceOrder::PENDING, EServiceOrder::INPROGRESS]))
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('eservice.index') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @elseif ($order->status == EServiceOrder::APPROVED)
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('eservice.approval') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @else
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('eservice.draft') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @endif
            </div>

        </div>

    </section>
    <!-- End -->
</div>

<script>
    $(document).ready( function () {
        $('.select2').select2();

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
            swal({
                title: "Verification Code",
                text: "Please verification code",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Verification Code"
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    return false
                }

                swal.close();

                $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                let route = "{{ route('eservice.submitByCOB', [':id']) }}";
                route = route.replace(':id', "{{ \Helper\Helper::encode(Config::get('constant.module.eservice.name'), $order->id) }}");

                let formData = $('form').serialize();
                $.ajax({
                    url: route,
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
                                location.reload();
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
    });

    function statusChange(value) {
        if (value == '{{ EServiceOrder::REJECTED }}') {
            $('#reject_field').show();
            $('#approve_field').hide();
        } else if (value == '{{ EServiceOrder::APPROVED }}') {
            $('#reject_field').hide();
            $('#approve_field').show();            
        } else {
            $('#reject_field').hide();
            $('#approve_field').hide();
        }
    }
</script>
@endsection