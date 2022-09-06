@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">

        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <?php 
            $readonly = ((Auth::user()->getAdmin() || Auth::user()->isCOB()) && in_array($model->status, [PostponedAGM::PENDING])) ? "" : "readonly";
        ?>

        <div class="panel-body">

            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <form id="postponed_agm_form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <dl class="row">
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.application_no') }}
                                </dt>
                                <dd class="col-sm-8">
                                    #{{ $model->application_no }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.created_at') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->created_at }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.file_no') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ ($model->file ? $model->file->file_no : '') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.strata') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ ($model->strata ? $model->strata->name : '') }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.reason') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->reason }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.attachment') }}
                                </dt>
                                <dd class="col-sm-8">
                                    @if (!empty($model->attachment))
                                    <a href="{{ asset($model->attachment) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-success" data-toggle="tooltip"
                                            data-placement="bottom" title="{{ trans('app.forms.attachment') }}">
                                            <i class="fa fa-file-pdf-o" style="margin-right: 2px;"></i>
                                            {{ trans('app.forms.attachment') }}
                                        </button>
                                    </a>
                                    @else
                                    -
                                    @endif
                                </dd>

                                <dt class="col-sm-4">
                                    {{ trans('app.forms.submit_by') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->user->full_name }} ({{ $model->user->email }})
                                </dd>

                                @if ($model->status == PostponedAGM::PENDING && (Auth::user()->getAdmin() ||
                                Auth::user()->isCOB()))
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
                                                <option value="{{ $key }}" {{ $model->status == $key ||
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

                                <div id="reject_field"
                                    style="display: {{ (Input::old('status') == PostponedAGM::REJECTED ? 'show' : 'none') }};">
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
                                    {{ $model->getStatusText() }}
                                </dd>

                                @if ($model->approver)
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_by') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->approver->full_name }}
                                </dd>
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_date') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ (!empty($model->approval_date) ? $model->approval_date : '') }}
                                </dd>
                                @if (!empty($model->approval_remark))
                                <dt class="col-sm-4">
                                    {{ trans('app.forms.approval_remark') }}
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $model->approval_remark }}
                                </dd>
                                @endif
                                @endif

                                @endif
                            </dl>


                            @if ($model->status == PostponedAGM::PENDING && (Auth::user()->getAdmin() ||
                            Auth::user()->isCOB()))
                            <div class="form-actions">
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                            </div>
                            @endif
                        </form>

                    </div>

                </div>
            </section>

            <div class="form-actions">
                @if ($model->status == PostponedAGM::APPROVED)
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('postponeAGM.index') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @elseif ($model->status == PostponedAGM::APPROVED)
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('postponeAGM.approved') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @elseif ($model->status == PostponedAGM::REJECTED)
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('postponeAGM.rejected') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @else
                <button type="button" class="btn btn-default" id="cancel_button"
                    onclick="window.location ='{{ route('postponeAGM.index') }}'">
                    {{ trans('app.forms.cancel') }}
                </button>
                @endif
            </div>

        </div>

    </section>
    <!-- End -->
</div>

<div class="modal fade" id="verifyModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form id="verifyForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('Verify') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="color: red; font-style: italic;">*
                                    {{ trans('app.forms.mandatory_fields') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">
                                    <span style="color: red;">*</span>
                                    {{ trans('app.forms.password') }}
                                </label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="{{ trans('app.forms.password') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <img id="loading_modal" style="display:none;"
                        src="{{asset('assets/common/img/input-spinner.gif')}}" />
                    <button type="modal" id="submit_button_modal" class="btn btn-own">
                        {{ trans('app.forms.submit') }}
                    </button>
                    <button data-dismiss="modal" id="cancel_button_modal" class="btn btn-default" type="button">
                        {{ trans('app.forms.cancel') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    let route = "{{ route('postponeAGM.submitByCOB', [':id']) }}";
                    route = route.replace(':id', "{{ \Helper\Helper::encode($model->id) }}");
                    let formData = $('#postponed_agm_form').serialize();
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
                                bootbox.alert("<span style='color:green;'>" + res.message + "</span>", function () {
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
                                
                                if (res.message != "Validation Fail") {
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

    function statusChange(value) {
        if (value == '{{ PostponedAGM::REJECTED }}') {
            $('#reject_field').show();
        }  else {
            $('#reject_field').hide();
        }
    }
</script>
@endsection