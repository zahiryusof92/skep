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

                        <form id="postpone_agm_reason_form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label">
                                        <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.reason') }}
                                        </label>
                                        <textarea id="reason" name="reason" class="form-control" rows="5"
                                            placeholder="{{ trans('app.forms.reason') }}">{{ $model->name }}</textarea>
                                        @include('alert.feedback-ajax', ['field' => 'reason'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.sort_no') }}
                                        </label>
                                        <input type="number" id="sort" name="sort" class="form-control"
                                            placeholder="{{ trans('app.forms.sort') }}" value="{{ $model->sort }}" />
                                        @include('alert.feedback-ajax', ['field' => 'sort'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.status') }}
                                        </label>
                                        <select id="active" name="active" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            <option value="1" {{ ($model->active == true ? 'selected' : '') }}>{{ trans('app.forms.active') }}</option>
                                            <option value="0" {{ ($model->active == false ? 'selected' : '') }}>{{ trans('app.forms.inactive') }}</option>
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'active'])
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('statusAGMReason.index') }}'">
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

<!-- Page Scripts -->
<script>
    $(document).ready( function () {
        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            let formData = $('form').serialize();
            $.ajax({
                url: "{{ route('statusAGMReason.update', \Helper\Helper::encode($model->id)) }}",
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
                        bootbox.alert("<span style='color:green;'>" + res.message + "</span>", function () {
                            let url = "{{ route('statusAGMReason.index') }}";
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
</script>
<!-- End Page Scripts-->
@endsection