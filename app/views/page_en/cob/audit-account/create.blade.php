@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            @include('alert.bootbox')
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <h6>{{ trans('app.forms.ref_no') }}: {{$files->ref_no ? ucfirst($files->ref_no) : '-'}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file')
                        
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="audit_account_tab" role="tabpanel">
                                <section class="panel panel-pad">
                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <form id="audit-account-form" class="form-horizontal" method="POST" action="{{ route('cob.audit-account.store') }}" onsubmit="event.preventDefault();">

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                                                    </div>
                                                </div>

                                                <div id="main-container">
                                                    <div class="container-item">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group {{ $errors->has('name_0') ? 'has-danger' : '' }}">
                                                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                                                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="name_0" name="name[]" value="{{ Input::old('name_0') }}">
                                                                    @include('alert.feedback-ajax', ['field' => 'name_0'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group {{ $errors->has('submission_date_0') ? 'has-danger' : '' }}">
                                                                    <label class="form-control-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.submission_date') }}</label>
                                                                    <label class="input-group">
                                                                        <input type="text" class="form-control datepicker-only-init" placeholder="{{ trans('app.forms.submission_date') }}" id="submission_date_0" name="submission_date[]" value="{{ Input::old('submission_date_0') }}"/>
                                                                        <span class="input-group-addon">
                                                                            <i class="icmn-calendar"></i>
                                                                        </span>
                                                                    </label>
                                                                    @include('alert.feedback-ajax', ['field' => 'submission_date_0'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group {{ $errors->has('closing_date_0') ? 'has-danger' : '' }}">
                                                                    <label class="form-control-label">{{ trans('app.forms.closing_date') }}</label>
                                                                    <label class="input-group">
                                                                        <input type="text" class="form-control datepicker-only-init" placeholder="{{ trans('app.forms.closing_date') }}" id="closing_date_0" name="closing_date[]" value="{{ Input::old('closing_date_0') }}"/>
                                                                        <span class="input-group-addon">
                                                                            <i class="icmn-calendar"></i>
                                                                        </span>
                                                                    </label>
                                                                    @include('alert.feedback-ajax', ['field' => 'closing_date_0'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group {{ $errors->has('income_collection_0') ? 'has-danger' : '' }}">
                                                                    <label class="form-control-label">{{ trans('app.forms.income_collection') }}</label>
                                                                    <input type="text" class="form-control number" placeholder="{{ trans('app.forms.income_collection') }}" id="income_collection_0" name="income_collection[]" value="{{ Input::old('income_collection_0') }}" onkeyup="updateResult(this)">
                                                                    @include('alert.feedback-ajax', ['field' => 'income_collection_0'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group {{ $errors->has('expense_collection_0') ? 'has-danger' : '' }}">
                                                                    <label class="form-control-label">{{ trans('app.forms.expense_collection') }}</label>
                                                                    <input type="text" class="form-control number" placeholder="{{ trans('app.forms.expense_collection') }}" id="expense_collection_0" name="expense_collection[]" value="{{ Input::old('expense_collection_0') }}" onkeyup="updateResult(this)">
                                                                    @include('alert.feedback-ajax', ['field' => 'expense_collection_0'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group {{ $errors->has('collection_result_0') ? 'has-danger' : '' }}">
                                                                    <label class="form-control-label">{{ trans('app.forms.collection_result') }}</label>
                                                                    <select class="form-control" id="collection_result_0" name="collection_result[]" disabled>
                                                                        @foreach ($options as $key => $option)
                                                                            <option value="{{ $key }}">{{ $option }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @include('alert.feedback-ajax', ['field' => 'collection_result_0'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="form-control-label">{{ trans('app.forms.file') }}</label>
                                                                    <input type="file" class="form-control" name="audit_account[]" id="audit_account_0" onChange="onUpload(this)">
                                                                    @include('alert.feedback-ajax', ['field' => "audit_account_0_url"])
                                                                </div>
                                                                <input hidden id="audit_account_0_url" name="audit_account_url[]">
                                                            </div>
                                                            <div class="col-lg-2 margin-top-35">
                                                                <button class="remove-item btn btn-danger btn-xs">{{ trans('Remove') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group text-right">
                                                            <a href="javascript:void(0);" id="add-more" class="btn btn-success btn-xs">{{ trans('Add More Fields') }}</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-actions">
                                                    @if (AccessGroup::hasInsertModule('Audit Account'))
                                                    <input id="file" name="file" value="{{ \Helper\Helper::encode(Config::get('constant.module.cob.file.name'), $files->id) }}" hidden>
                                                    <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.save') }}</button>
                                                    @endif
                                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('cob.audit-account.index', [\Helper\Helper::encode($files->id)]) }}'">{{ trans('app.forms.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            afterRender:function() {
                $('.datepicker-only-init').datetimepicker({
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
                }).on('dp.change', function() {
                    let id = this.getAttribute('id');
                    id = id.replace("_raw", "");
                    let currentDate = $(this).val().split('-');
                    $(id).val(`${currentDate[0]}-${currentDate[1]}-${currentDate[2]}`);
                });
            },
        });

        $(".number").keyup(function () {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        });
        $('.datepicker-only-init').datetimepicker({
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
        }).on('dp.change', function() {
            let id = this.getAttribute('id');
            id = id.replace("_raw", "");
            let currentDate = $(this).val().split('-');
            $(id).val(`${currentDate[0]}-${currentDate[1]}-${currentDate[2]}`);
        });

        $("#submit_button").click(function (e) {
            e.preventDefault();
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            let formData = $('form').serialize();
            $.ajax({
                url: "{{ route('cob.audit-account.store') }}",
                type: "POST",
                data: formData,
                dataType: 'JSON',
                beforeSend: function() {
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                    for(let i = 0; i < $('[name="name[]"]').length; i++) {
                        $("#name_" + i + "_error").children("strong").text("");
                        $("#submission_date_" + i + "_error").children("strong").text("");
                    }
                },
                success: function (res) {
                    if (res.success == true) {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.audit_account.store') }}</span>", function () {
                            window.location = "{{ route('cob.audit-account.index', [\Helper\Helper::encode($files->id)]) }}";
                        });
                    } else {
                        if(res.errors !== undefined) {
                            $.each(res.errors, function (key, value) {
                                if($.isArray(value)) {
                                    $.each(value, function (key1, value1) {
                                        $("#" + key + "_" + key1 + "_error").children("strong").text(value1);
                                    });
                                } else {
                                    $.each(value, function (key1, value1) {
                                        $("#" + key + "_" + key1 + "_error").children("strong").text(value1);
                                    });
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

    function updateResult(e) {
        let id = e.getAttribute('id');
        let index = 0;
        if(id.includes('income_collection')) {
            index = id.substring(18);
        } else {
            index = id.substring(19);
        }
        let income = $('#income_collection_' + index).val();
        let expense = $('#expense_collection_' + index).val();
        
        if(income != '' && expense != '') {
            if(income == Math.max(income, expense)) {
                $('#collection_result_' + index).val('surplus').change();
            } else if(expense == Math.max(income, expense)) {
                $('#collection_result_' + index).val('deficit').change();
            }
        }
    }

    function onUpload(e) {
        let id = e.getAttribute('id');
        let data = new FormData();
        if(e.files.length > 0) {
            data.append(id, e.files[0]);
        }
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{ route('cob.audit-account.fileUpload') }}",
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
                    console.log(id)
                    $("#" + id + "_url_error").html("<button id='clear_" + id + "' class='btn btn-xs btn-danger' onclick='clearFile(this)'><i class='fa fa-times'></i> </button>&nbsp;&nbsp;<i class='fa fa-check' style='color:green;'></i>");
                    $("#clear_" + id).show();
                    $("#" + id + "_url_error").show();
                    $("#" + id).css("color", "green");
                    $("#" + id + "_url").val(response.file);
                }
            }
        });
    }

    function clearFile(e) {
        let id = e.getAttribute('id');
        id = id.replace("clear_", "");
        $("#" + id).val("");
        $("#" + id + "_url").val("");
        $("#" + id).css("color", "");
        $("#" + id + "_url_error").empty().hide();
        $("#" + id + "_download").html('<input type="file" class="form-control" name="' + id + '" id="' + id + '" onChange="onUpload(this)">');
    }
</script>
@endsection