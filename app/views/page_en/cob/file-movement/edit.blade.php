@extends('layout.english_layout.default')

@section('content')
    <div class="page-content-inner">
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">
                @include('alert.bootbox')
                <div class="row">
                    <div class="col-lg-12">
                        <h6>{{ trans('app.forms.file_no') }}: {{ $files->file_no }}</h6>
                        <div id="update_files_lists">
                            @include('page_en.nav.cob_file')
                            <div class="tab-content padding-vertical-20">
                                <div class="tab-pane active" id="file_movement_tab" role="tabpanel">
                                    <section class="panel panel-pad">
                                        <div class="row padding-vertical-20">
                                            <div class="col-lg-12">
                                                <form id="file-movement-form" class="form-horizontal"
                                                    onsubmit="event.preventDefault();">
                                                    <input type="hidden" name="_method" value="PUT">

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label class="form-control-label">
                                                                <span style="color: red;">*
                                                                    {{ trans('app.forms.mandatory_fields') }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-control-label">
                                                                    {{ trans('app.forms.name') }}
                                                                </label>
                                                                <br />
                                                                <label id="lbl_strata_name">-</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $errors->has('title') ? 'has-danger' : '' }}">
                                                                <label class="form-control-label">
                                                                    <span style="color: red;">*</span>
                                                                    {{ trans('app.forms.title') }}
                                                                </label>
                                                                <input type="text" class="form-control" id="title"
                                                                    name="title"
                                                                    value="{{ Input::old('title') ? Input::old('title') : $model->title }}" />
                                                                @include('alert.feedback-ajax', [
                                                                    'field' => 'title',
                                                                ])
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if ($model->fileMovementUsers->count() > 0)
                                                        <div id="main-container">
                                                            @foreach ($model->fileMovementUsers as $fileMovementUser)
                                                                <div class="container-item">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div
                                                                                class="form-group {{ $errors->has("assigned_to_$fileMovementUser->id") ? 'has-danger' : '' }}">
                                                                                <label class="form-control-label">
                                                                                    <span style="color: red;">*</span>
                                                                                    {{ trans('app.forms.assigned_to') }}
                                                                                    <span id="lbl_date_{{ $fileMovementUser->id }}">
                                                                                        ({{ ($fileMovementUser->user ? $fileMovementUser->user->full_name : '') }})
                                                                                    </span>
                                                                                </label>
                                                                                <button
                                                                                    class="remove-item btn btn-danger btn-xs">
                                                                                    {{ trans('Remove') }}
                                                                                </button>
                                                                                <select
                                                                                    id="assigned_to_{{ $fileMovementUser->id }}"
                                                                                    name="assigned_to[]"
                                                                                    class="form-control select2">
                                                                                    <option value="">
                                                                                        {{ trans('app.forms.please_select') }}
                                                                                    </option>
                                                                                    @foreach ($userList as $user)
                                                                                        <option value="{{ $user->id }}"
                                                                                            {{ ($fileMovementUser->user ? $fileMovementUser->user->id : 0) == $user->id ? 'selected' : '' }}>
                                                                                            {{ ucfirst($user->full_name) }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @include(
                                                                                    'alert.feedback-ajax',
                                                                                    [
                                                                                        'field' => "assigned_to_$fileMovementUser->id",
                                                                                    ]
                                                                                )
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div id="main-container">
                                                            <div class="container-item">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div
                                                                            class="form-group {{ $errors->has('assigned_to_0') ? 'has-danger' : '' }}">
                                                                            <label class="form-control-label"><span
                                                                                    style="color: red;">*</span>
                                                                                {{ trans('app.forms.assigned_to') }}
                                                                            </label>
                                                                            <button
                                                                                class="remove-item btn btn-danger btn-xs">
                                                                                {{ trans('Remove') }}
                                                                            </button>
                                                                            <select id="assigned_to_0" name="assigned_to[]"
                                                                                class="form-control select2">
                                                                                <option value="">
                                                                                    {{ trans('app.forms.please_select') }}
                                                                                </option>
                                                                                @foreach ($userList as $user)
                                                                                    <option value="{{ $user->id }}"
                                                                                        {{ Input::old('assigned_to_0') == $user->id ? 'selected' : '' }}>
                                                                                        {{ ucfirst($user->full_name) }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @include(
                                                                                'alert.feedback-ajax',
                                                                                ['field' => 'assigned_to_0']
                                                                            )
                                                                        </div>
                                                                        <input id="created_at_0" name="created_at[]" hidden>
                                                                        @include('alert.feedback-ajax', [
                                                                            'field' => 'created_at_0',
                                                                        ])
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group text-right">
                                                                <a href="javascript:void(0);" id="add-more"
                                                                    class="btn btn-success btn-xs">
                                                                    {{ trans('Add More Fields') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $errors->has('remarks') ? 'has-danger' : '' }}">
                                                                <label class="form-control-label"><span
                                                                        style="color: red;">*</span>
                                                                    {{ trans('app.forms.remarks') }}
                                                                </label>
                                                                <textarea class="form-control" rows="5" placeholder="{{ trans('app.forms.remarks') }}" id="remarks"
                                                                    name="remarks">{{ Input::old('remarks') ? Input::old('remarks') : $model->remarks }}</textarea>
                                                                @include('alert.feedback', [
                                                                    'field' => 'remarks',
                                                                ])
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-actions">
                                                        @if (AccessGroup::hasUpdateModule('File Movement'))
                                                            <input id="file" name="file"
                                                                value="{{ \Helper\Helper::encode(Config::get('constant.module.cob.file.name'), $files->id) }}"
                                                                hidden>
                                                            <input id="strata" name="strata"
                                                                value="{{ $files->strata->name }}" hidden>
                                                            <button type="submit" class="btn btn-own"
                                                                id="submit_button">{{ trans('app.forms.save') }}</button>
                                                        @endif
                                                        <button type="button" class="btn btn-default" id="cancel_button"
                                                            onclick="window.location ='{{ route('cob.file-movement.index', [\Helper\Helper::encode($files->id)]) }}'">
                                                            {{ trans('app.forms.cancel') }}
                                                        </button>
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
            let file = $('#file').val();
            if (file != '') {
                getLatestFile(file);
            }

            $('#add-more').cloneData({
                mainContainerId: 'main-container', // container to hold the dulicated form fields
                cloneContainer: 'container-item', // Which you want to clone
                removeButtonClass: 'remove-item', // CSS lcass of remove button
                removeConfirm: true,
                removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
                minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
                minLimit: 1,
                maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
                defaultRender: 1, // Number of clone items rendered by default
                afterRender: function() {
                    $('#lbl_date_' + this.index).text("");
                },
            });

            $("#submit_button").click(function(e) {
                e.preventDefault();
                $.blockUI({
                    message: '{{ trans('app.confirmation.please_wait') }}'
                });
                let route = "{{ route('cob.file-movement.update', [':id']) }}";
                let url = route.replace(':id',
                    "{{ \Helper\Helper::encode(Config::get('constant.module.file_movement.name'), $model->id) }}"
                );
                let formData = $('form').serialize();
                console.log(formData)
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: formData,
                    dataType: 'JSON',
                    beforeSend: function() {
                        $("#loading").css("display", "inline-block");
                        $("#submit_button").attr("disabled", "disabled");
                        $("#cancel_button").attr("disabled", "disabled");
                        let i = 0;
                        let c = 0;
                        $.each($('form').serializeArray(), function(key, value) {
                            if (value['name'].includes('assigned_to')) {
                                $("#assigned_to_" + i + "_error").children("strong")
                                    .text("");
                                i++;
                            } else if (value['name'].includes('created_at')) {
                                $("#created_at_" + c + "_error").children("strong")
                                    .text("");

                                c++;
                            } else {
                                $("#" + value['name'] + "_error").children("strong")
                                    .text("");
                            }
                        });
                    },
                    success: function(res) {
                        if (res.success == true) {
                            bootbox.alert(
                                "<span style='color:green;'>{{ trans('app.successes.file_movement.update') }}</span>",
                                function() {
                                    window.location =
                                        "{{ route('cob.file-movement.index', [\Helper\Helper::encode($files->id)]) }}";
                                });
                        } else {
                            if (res.errors !== undefined) {
                                $.each(res.errors, function(key, value) {
                                    $("#" + key + "_error").children("strong").text(
                                        value);
                                });
                            }

                            if (res.message != "Validation Fail") {
                                bootbox.alert("<span style='color:red;'>" + res.message +
                                    "</span>");
                            } else {
                                bootbox.alert(
                                    "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                                );
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
        })

        $('#file').change(function(e) {
            getLatestFile(e.target.value);
        });

        function getLatestFile(value) {
            $.blockUI({
                message: '{{ trans('app.confirmation.please_wait') }}'
            });
            $.ajax({
                url: "{{ URL::action('AdminController@getLatestFile') }}",
                type: "GET",
                data: {
                    file_id: value,
                },
                success: function(res) {
                    $.unblockUI();
                    if (res.status == true) {
                        $('#lbl_strata_name').html(res.strata_name);
                    } else {
                        $('#lbl_strata_name').html('-');
                    }
                }
            });
        }
    </script>
@endsection
