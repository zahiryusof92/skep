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

                            <form class="form-horizontal" method="POST"
                                action="{{ route('complaint.update', \Helper\Helper::encode($model->id)) }}"
                                enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ Session::getToken() }}">
                                <input type="hidden" name="_method" value="PUT">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label">
                                                <span style="color: red; font-style: italic;">*
                                                    {{ trans('app.forms.mandatory_fields') }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('file_id') ? 'has-danger' : '' }}">
                                            <label>
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.file_no') }}
                                            </label>
                                            <select id="file_id" name="file_id" class="form-control select2">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @foreach ($files as $file_no)
                                                    <option value="{{ $file_no->id }}"
                                                        {{ Input::old('file_id', $model->file_id) == $file_no->id ? 'selected' : '' }}>
                                                        {{ $file_no->strataName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @include('alert.feedback', ['field' => 'file_id'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10">
                                        <div
                                            class="form-group {{ $errors->has('complaint_category_id') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.category') }}
                                            </label>
                                            @if ($complaintCategory)
                                                @foreach ($complaintCategory as $category)
                                                    <div class="form-check">
                                                        <input class="form-check-input category-radio" type="radio"
                                                            name="complaint_category_id" id="category_{{ $category->id }}"
                                                            value="{{ $category->id }}" data-name="{{ $category->name }}"
                                                            data-types='{{ json_encode($category->types) }}'
                                                            {{ Input::old('complaint_category_id', $model->complaint_category_id) == $category->id ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="category_{{ $category->id }}"
                                                            style="margin-bottom:0rem">
                                                            {{ $category->name }}
                                                        </label>
                                                        @if ($category->description)
                                                            <div class="text-muted"
                                                                style="font-size: 0.9rem; margin-left:1.2rem; margin-bottom:0.5rem">
                                                                {{ $category->description }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                            @include('alert.feedback', [
                                                'field' => 'complaint_category_id',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('complaint_type_id') ? 'has-danger' : '' }}"
                                            id="type-select-container" style="display: none;">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.type') }}
                                            </label>
                                            <select id="complaint_type_id" name="complaint_type_id"
                                                class="form-control select2">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', [
                                                'field' => 'complaint_type_id',
                                            ])
                                        </div>

                                        <div class="form-group {{ $errors->has('complaint_type_text') ? 'has-danger' : '' }}"
                                            id="type-text-container" style="display: none;">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.type') }}
                                            </label>
                                            <input type="text" name="complaint_type_text" id="complaint_type_text"
                                                class="form-control"
                                                value="{{ Input::old('complaint_type_text') ?: (isset($model->type) ? $model->type->name : '') }}" />
                                            @include('alert.feedback', ['field' => 'complaint_type_text'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.name') }}
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                value="{{ Input::old('name') ?: $model->name }}" />
                                            @include('alert.feedback', ['field' => 'name'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="form-group {{ $errors->has('description') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.description') }}
                                            </label>
                                            <textarea id="description" name="description" class="form-control" rows="5">{{ Input::old('description') ?: $model->description }}</textarea>
                                            @include('alert.feedback', ['field' => 'description'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('attachment') ? 'has-danger' : '' }}">
                                            <label class="form-label">
                                                <span style="color: red; font-style: italic;">*</span>
                                                {{ trans('app.forms.complaint.attachment') }}
                                            </label>
                                            <input type="file" id="attachment" name="attachment"
                                                class="form-control-file" />
                                            <small class="form-text text-muted">
                                                <strong>Maksimum saiz fail: 10MB</strong>
                                            </small>
                                            <br />
                                            @if (!empty($model->attachment_url))
                                                <div class="mt-2">
                                                    <a href="{{ asset($model->attachment_url) }}" target="_blank"
                                                        class="btn btn-sm btn-success">
                                                        Lihat / Muat Turun
                                                    </a>
                                                </div>
                                            @endif
                                            @include('alert.feedback', ['field' => 'attachment'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $errors->has('letter_ref_no') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.letter_ref_no') }}
                                            </label>
                                            <input type="text" id="letter_ref_no" name="letter_ref_no"
                                                class="form-control"
                                                value="{{ Input::old('letter_ref_no') ?: $model->letter_ref_no }}">
                                            @include('alert.feedback', ['field' => 'letter_ref_no'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $errors->has('date_received') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.date_received') }}
                                            </label>
                                            <input type="text" id="date_received_temp" class="form-control">
                                            <input type="hidden" id="date_received" name="date_received"
                                                value="{{ Input::old('date_received') ?: $model->date_received }}" />
                                            @include('alert.feedback', ['field' => 'date_received'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('scheme_address') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.scheme_address') }}
                                            </label>
                                            <textarea id="scheme_address" name="scheme_address" class="form-control" rows="5">{{ Input::old('scheme_address') ?: $model->scheme_address }}</textarea>
                                            @include('alert.feedback', ['field' => 'scheme_address'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $errors->has('scheme_zone') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.scheme_zone') }}
                                            </label>
                                            <select id="scheme_zone" name="scheme_zone" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="zone_1"
                                                    {{ (Input::old('scheme_zone') ?: $model->scheme_zone) == 'zone_1' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.zone_1') }}
                                                </option>
                                                <option value="zone_2"
                                                    {{ (Input::old('scheme_zone') ?: $model->scheme_zone) == 'zone_2' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.zone_2') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'scheme_zone'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div
                                            class="form-group {{ $errors->has('complainant_phone_no') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_phone_no') }}
                                            </label>
                                            <input type="text" id="complainant_phone_no" name="complainant_phone_no"
                                                class="form-control"
                                                value="{{ Input::old('complainant_phone_no') ?: $model->complainant_phone_no }}" />
                                            @include('alert.feedback', [
                                                'field' => 'complainant_phone_no',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div
                                            class="form-group {{ $errors->has('complainant_email') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_email') }}
                                            </label>
                                            <input type="email" id="complainant_email" name="complainant_email"
                                                class="form-control"
                                                value="{{ Input::old('complainant_email') ?: $model->complainant_email }}" />
                                            @include('alert.feedback', ['field' => 'complainant_email'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div
                                            class="form-group {{ $errors->has('complainant_ic_no') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_ic_no') }}
                                            </label>
                                            <input type="text" id="complainant_ic_no" name="complainant_ic_no"
                                                class="form-control"
                                                value="{{ Input::old('complainant_ic_no') ?: $model->complainant_ic_no }}" />
                                            @include('alert.feedback', ['field' => 'complainant_ic_no'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('scheme_receiver') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.scheme_receiver') }}
                                            </label>
                                            <div>
                                                <div class="checkbox">
                                                    <?php
                                                    $schemeReceiverOld = Input::old('scheme_receiver');
                                                    $schemeReceiver = is_array($schemeReceiverOld) ? $schemeReceiverOld : explode(',', $model->scheme_receiver);
                                                    ?>
                                                    @foreach (['letter', 'sispa', 'phone_call', 'email', 'walkin'] as $receiver)
                                                        <label>
                                                            <input type="checkbox" name="scheme_receiver[]"
                                                                value="{{ $receiver }}"
                                                                {{ in_array($receiver, $schemeReceiver) ? 'checked' : '' }} />
                                                            {{ trans('app.forms.complaint.' . $receiver) }}
                                                        </label><br />
                                                    @endforeach
                                                </div>
                                            </div>
                                            @include('alert.feedback', ['field' => 'scheme_receiver'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div
                                            class="form-group {{ $errors->has('complainant_type') || $errors->has('complainant_type_others') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_type') }}
                                            </label>
                                            <select id="complainant_type" name="complainant_type" class="form-control"
                                                onchange="showComplainantTypeOthers(this)">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="individual"
                                                    {{ (Input::old('complainant_type') ?: $model->complainant_type) == 'individual' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.individual') }}
                                                </option>
                                                <option value="management"
                                                    {{ (Input::old('complainant_type') ?: $model->complainant_type) == 'management' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.management') }}
                                                </option>
                                                <option value="others"
                                                    {{ (Input::old('complainant_type') ?: $model->complainant_type) == 'others' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.others') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'complainant_type'])

                                            <div id="complainant_type_others_field"
                                                style="{{ (Input::old('complainant_type') ?: $model->complainant_type) == 'others' ? '' : 'display: none;' }}">
                                                <input type="text" id="complainant_type_others"
                                                    name="complainant_type_others" class="form-control"
                                                    value="{{ Input::old('complainant_type_others') ?: $model->complainant_type_others }}" />
                                                @include('alert.feedback', [
                                                    'field' => 'complainant_type_others',
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div
                                            class="form-group {{ $errors->has('complaint_category') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.category') }}
                                            </label>
                                            <select id="complaint_category" name="complaint_category"
                                                class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="common_area"
                                                    {{ (Input::old('complaint_category') ?: $model->complaint_category) == 'common_area' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.common_area') }}
                                                </option>
                                                <option value="owner_unit"
                                                    {{ (Input::old('complaint_category') ?: $model->complaint_category) == 'owner_unit' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.owner_unit') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'complaint_category'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div
                                            class="form-group {{ $errors->has('complaint_complication') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complication') }}
                                            </label>
                                            <select id="complaint_complication" name="complaint_complication"
                                                class="form-control">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                <option value="light"
                                                    {{ Input::old('complaint_complication', $model->complaint_complication) == 'light' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.light') }}
                                                </option>
                                                <option value="moderate"
                                                    {{ Input::old('complaint_complication', $model->complaint_complication) == 'moderate' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.moderate') }}
                                                </option>
                                                <option value="heavy"
                                                    {{ Input::old('complaint_complication', $model->complaint_complication) == 'heavy' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.heavy') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', [
                                                'field' => 'complaint_complication',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $errors->has('action_duration') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.action_duration') }}
                                            </label>
                                            <select id="action_duration" name="action_duration" class="form-control">
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                <option value="7_days"
                                                    {{ Input::old('action_duration', $model->action_duration) == '7_days' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.7_days') }}
                                                </option>
                                                <option value="14_days"
                                                    {{ Input::old('action_duration', $model->action_duration) == '14_days' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.14_days') }}
                                                </option>
                                                <option value="30_days"
                                                    {{ Input::old('action_duration', $model->action_duration) == '30_days' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.30_days') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'action_duration'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('status') ? 'has-danger' : '' }}">
                                            <label>
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.status') }}
                                            </label>
                                            <select id="status"name="status" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="1" {{ Input::old('status', $model->status) == 1 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.under_investigation_1') }}
                                                </option>
                                                <option value="2" {{ Input::old('status', $model->status) == 2 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.under_investigation_2') }}
                                                </option>
                                                <option value="3" {{ Input::old('status', $model->status) == 3 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.resolved') }}
                                                </option>
                                                <option value="4" {{ Input::old('status', $model->status) == 4 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.received') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'status'])
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="form-group {{ $errors->has('officer_review') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.officer_review') }}
                                            </label>
                                            <textarea id="officer_review" name="officer_review" class="form-control" rows="20">{{ Input::old('officer_review', $model->officer_review) }}</textarea>
                                            @include('alert.feedback', ['field' => 'officer_review'])
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    @if (AccessGroup::hasUpdateModule('Complaint Category'))
                                        <button type="submit" class="btn btn-own" id="submit_button">
                                            {{ trans('app.forms.save') }}
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-default" id="cancel_button"
                                        onclick="window.location ='{{ route('complaint.index') }}'">
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

    <script>
        $(document).ready(function() {
            function handleCategoryChange(types, selectedTypeId, selectedTextValue, categoryName) {
                var $dropdown = $('#complaint_type_id');
                var $dropdownContainer = $('#type-select-container');
                var $textContainer = $('#type-text-container');
                var $textInput = $('#complaint_type_text');

                $dropdown.empty().append('<option value="">{{ trans('app.forms.please_select') }}</option>');

                if (categoryName.trim().toLowerCase() === 'lain-lain') {
                    $dropdownContainer.hide();
                    $textContainer.show();

                    if (selectedTextValue) {
                        $textInput.val(selectedTextValue);
                    }

                } else if (types && types.length > 0) {
                    $.each(types, function(index, type) {
                        $dropdown.append('<option value="' + type.id + '">' + type.name + '</option>');
                    });
                    $dropdownContainer.show();
                    $textContainer.hide();

                    if (selectedTypeId) {
                        $dropdown.val(selectedTypeId);
                    }

                } else {
                    $dropdownContainer.hide();
                    $textContainer.show();

                    if (selectedTextValue) {
                        $textInput.val(selectedTextValue);
                    }
                }
            }

            $('.category-radio').on('change', function() {
                var types = $(this).data('types');
                var categoryName = $(this).data('name');
                handleCategoryChange(types, null, null, categoryName);
            });

            // Autopopulate semula selepas validation fail
            var selectedCategoryId =
                "{{ Input::old('complaint_category_id', isset($model) ? $model->complaint_category_id : '') }}";
            var selectedTypeId =
                "{{ Input::old('complaint_type_id', isset($model) ? $model->complaint_type_id : '') }}";
            var selectedTypeText =
                "{{ Input::old('complaint_type_text', isset($model) ? $model->complaint_type_text : '') }}";

            if (selectedCategoryId) {
                var $selectedRadio = $('.category-radio[value="' + selectedCategoryId + '"]');
                if ($selectedRadio.length) {
                    var types = $selectedRadio.data('types');
                    var categoryName = $selectedRadio.data('name');
                    $selectedRadio.prop('checked', true);
                    handleCategoryChange(types, selectedTypeId, selectedTypeText, categoryName);
                }
            }

            let dbDate = $('#date_received').val();
            if (dbDate) {
                let parts = dbDate.split('-');
                let formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];
                $('#date_received_temp').val(formattedDate);
            }

            $('#date_received_temp').datetimepicker({
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
                format: 'DD/MM/YYYY',
            }).on('dp.change', function() {
                let selectedDate = $(this).val();
                if (selectedDate) {
                    let receivedDate = selectedDate.split('/');
                    $("#date_received").val(`${receivedDate[2]}-${receivedDate[1]}-${receivedDate[0]}`);
                } else {
                    $("#date_received").val('');
                }
            });

            // Show complainant_type_others on load
            var complainantType =
                "{{ Input::old('complainant_type', isset($model) ? $model->complainant_type : '') }}";
            if (complainantType === 'others') {
                $('#complainant_type_others_field').show();
            } else {
                $('#complainant_type_others_field').hide();
            }
        });

        function showComplainantTypeOthers(e) {
            if (e.value === 'others') {
                $('#complainant_type_others_field').show();
            } else {
                $('#complainant_type_others_field').hide();
                $('#complainant_type_others').val('');
            }
        }
    </script>
@endsection
