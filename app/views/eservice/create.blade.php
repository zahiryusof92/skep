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

                        <form id="eservice-form" class="form-horizontal" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label">
                                        <span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span>
                                    </label>
                                </div>
                            </div>

                            @if (Auth::user()->company_id > 1)
                            <input id="cob" name="cob" value="{{ Auth::user()->getCOB->short_name }}" hidden>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.type') }}
                                        </label>
                                        <select id="type" name="type" class="form-control select2"
                                            data-placeholder="{{ trans('app.forms.please_select') }}"
                                            data-ajax--url="{{ route('v3.api.eservice.getTypeOptions', 'cob=' . Auth::user()->getCOB->short_name) }}"
                                            data-ajax--cache="true">
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'cob'])
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.cob') }}
                                        </label>
                                        <select id="cob" name="cob" class="form-control select2"
                                            data-placeholder="{{ trans('app.forms.please_select') }}"
                                            data-ajax--url="{{ route('v3.api.company.getNameOption') }}"
                                            data-ajax--cache="true">
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'cob'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <span style="color: red;">*</span>
                                            {{ trans('app.forms.type') }}
                                        </label>
                                        <select id="type" name="type" class="form-control select2"
                                            data-placeholder="{{ trans('app.forms.please_select') }}">
                                        </select>
                                        @include('alert.feedback-ajax', ['field' => 'cob'])
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div id="form_field_container"></div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-own" id="submit_button">
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
    <!-- End -->
</div>

<script>
    $(document).ready( function () {
        $('.select2').select2();    
        $('#cob').on('select2:select', function (e) {
            getTypeOptions(this.value);
            $("#form_field_container").html("");
            $("#type").val("").trigger('change');
        });
        $('#type').on('select2:select', function (e) {
            getFields(this.value);
        });

        $("#eservice-form").submit(function (e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            let route = "{{ route('eservice.store') }}";
            $.ajax({
                url: route,
                type: "POST",
                data: formData,
                beforeSend: function() {
                    console.log(formData);
                    
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                    $.each(formData, function (key, value) {
                        $("#" + value['name'] + "_error").children("strong").text("");
                    });
                },
                success: function (res) {
                    if (res.success == true) {
                            $.notify({
                                message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                            }, {
                                type: 'success',
                                placement: {
                                    align: "center"
                                }
                            });
                            let location = "{{ route('eservice.show', ':id') }}";
                            location = location.replace(':id', res.id);
                            window.location = location;
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                $("#" + key + "_error").children("strong").text(value);
                            });
                        }
                        
                        if(res.message != "Validation Fail") {
                            $('#status-message').html("<span style='color:red;'>" + res.message + "</span>");
                        } else {
                            $('#status-message').html("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                        }
                    }
                },
                complete: function() {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                },
            });
        });
    });

    function getTypeOptions(cob) {       
        let route = "{{ route('v3.api.eservice.getTypeOptions') }}";
        route = route + '?cob=' + cob;
        $('#type').select2({
            ajax: {
                url: route,
                processResults: function (data, params) {
                    console.log(data);                 
                    return {
                        results: data.results,
                    };
                }
            }
        });
    }

    function getFields(type) {
        let route = "{{ route('eservice.getForm') }}";
        $.ajax({
            url: route,
            type: "GET",
            data: {
                cob: $('#cob').val(),
                type: type
            },
            beforeSend: function() {
                $("#loading").css("display", "inline-block");
                $("#submit_button").attr("disabled", "disabled");
                $("#cancel_button").attr("disabled", "disabled");
            },
            success: function (result) {
                if (result) {
                    $("#form_field_container").html(result);
                } else {
                    $("#form_field_container").html("");
                }
            },
            complete: function() {
                $("#loading").css("display", "none");
                $("#submit_button").removeAttr("disabled");
                $("#cancel_button").removeAttr("disabled");
            },
        });
    }
</script>
@endsection