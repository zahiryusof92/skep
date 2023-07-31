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

                        <form id="form_edit_price" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="form-control-label" style="color: red; font-style: italic;">*
                                        {{ trans('app.forms.mandatory_fields') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        </span> {{ trans('app.forms.cob') }}
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"
                                        value="{{ ($model->company ? $model->company->name : '') }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        </span> {{ trans('app.forms.category') }}
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"
                                        value="{{ ($model->category ? $model->category->description : '') }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        </span> {{ trans('app.forms.type') }}
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{ $model->type }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <span style="color: red; font-style: italic;">* </span>
                                        {{ trans('app.forms.price') }} (RM)
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="price" name="price" class="form-control"
                                        placeholder="{{ trans('app.forms.price') }}" value="{{ $model->price }}">
                                    @include('alert.feedback-ajax', ['field' => 'price'])
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-own" id="submit_button">
                                    {{ trans('app.forms.save') }}
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('eservicePrice.index') }}'">
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
        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            let route = "{{ route('eservicePrice.update', [':id']) }}";
            route = route.replace(':id', "{{ $id }}");

            let formData = $('form').serialize();
            $.ajax({
                url: route,
                type: "PUT",
                data: formData,
                dataType: 'JSON',
                beforeSend: function() {
                    $('.help-block').text("");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                },
                success: function (res) {
                    console.log(res);

                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.eservice.update') }}</span>", function () {
                            let url = "{{ route('eservicePrice.index') }}";
                            window.location = url;
                        });
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                if (key.includes('_tmp')) {
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
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                },
            });
        });
    });
</script>
@endsection