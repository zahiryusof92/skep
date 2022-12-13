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

                        <form id="file-movement-form" class="form-horizontal" onsubmit="event.preventDefault();">

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-control-label"><span style="color: red;">* {{
                                            trans('app.forms.mandatory_fields') }}</span></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ trans('app.forms.name') }}</label>
                                        <select id="strata" name="strata" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group {{ $errors->has('file') ? 'has-danger' : '' }}">
                                        <label class="form-control-label">{{ trans('app.forms.file_no') }}</label>
                                        <select id="file_id" name="file_id" class="form-control">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                        </select>
                                        @include('alert.feedback', ['field' => 'file_id'])
                                    </div>
                                </div>
                            </div>

                            <div id="main-container">
                                <div class="container-item">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div
                                                class="form-group {{ $errors->has('assigned_to_0') ? 'has-danger' : '' }}">
                                                <label class="form-control-label"><span style="color: red;">*</span> {{
                                                    trans('app.forms.assigned_to') }}</label>
                                                <button class="remove-item btn btn-danger btn-xs">{{ trans('Remove')
                                                    }}</button>
                                                <select id="assigned_to_0" name="assigned_to[]"
                                                    class="form-control select3">
                                                    <option value="">{{ trans('app.forms.please_select') }}</option>
                                                    @foreach($userList as $user)
                                                    <option value="{{$user->id}}" {{
                                                        Input::old('assigned_to_0')==$user->id ? 'selected' : '' }}>{{
                                                        ucfirst($user->full_name) }}</option>
                                                    @endforeach
                                                </select>
                                                @include('alert.feedback-ajax', ['field' => 'assigned_to_0'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:void(0);" id="add-more" class="btn btn-success btn-xs">{{
                                            trans('Add More Fields') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group {{ $errors->has('remarks') ? 'has-danger' : '' }}">
                                        <label class="form-control-label"><span style="color: red;">*</span> {{
                                            trans('app.forms.remarks') }}</label>
                                        <textarea class="form-control" rows="3"
                                            placeholder="{{ trans('app.forms.remarks') }}" id="remarks"
                                            name="remarks">{{ Input::old('remarks') }}</textarea>
                                        @include('alert.feedback-ajax', ['field' => 'remarks'])
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                @if (AccessGroup::hasInsert(63))
                                <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.save')
                                    }}</button>
                                @endif
                                <button type="button" class="btn btn-default" id="cancel_button"
                                    onclick="window.location ='{{ route('file-movement.index') }}'">{{
                                    trans('app.forms.cancel') }}</button>
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
        select2Start();
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
            beforeRender: function() {
                select2Start(true);
            },
            afterRender:function() {
                select2Start();
            },
        });
        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
            let formData = $('form').serialize();
            $.ajax({
                url: "{{ route('file-movement.store') }}",
                type: "POST",
                data: formData,
                dataType: 'JSON',
                beforeSend: function() {
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                    let i = 0;
                    $.each($('form').serializeArray(), function (key, value) {
                        if(value['name'].includes('assigned_to')) {
                            $("#assigned_to_" + i + "_error").children("strong").text("");
                            i++;
                        } else {
                            $("#" + value['name'] + "_error").children("strong").text("");
                        }
                    });
                },
                success: function (res) {
                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.file_movement.store') }}</span>", function () {
                            window.location = "{{ route('file-movement.index') }}";
                        });
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                $("#" + key + "_error").children("strong").text(value);
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
    function select2Start(destroy = false) {
        if(destroy) {
            $("#strata").select2('destroy');
            $("#file_id").select2('destroy');
            $('.select3').select2('destroy');
        } else {
            $("#strata").select2({
                ajax: {
                    url: "{{ route('v3.api.strata.getOption') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    allowClear: true,
                    data: function(params) {
                        return {
                            term: params.term, // search term
                            file_id: $('#file_id').val()
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response.results
                        };
                    }
                }
            });
            $("#file_id").select2({
                ajax: {
                    url: "{{ route('v3.api.files.getOption') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    allowClear: true,
                    data: function(params) {
                        return {
                            term: params.term, // search term
                            strata: $('#strata').val()
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response.results
                        };
                    }
                }
            });
            $('.select3').select2();
        }
    }
</script>
@endsection