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

                            <form class="form-horizontal" method="POST" action="{{ route('complaint.store') }}"
                                enctype="multipart/form-data" novalidate>
                                <input type="hidden" name="_token" value="{{ Session::getToken() }}">

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

                                {{-- COB selector: required before the rest of the form in All-COB view --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('cob') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.cob') }}
                                            </label>
                                            @if ($isAllCob)
                                                <select id="cob" name="cob" class="form-control">
                                                    <option value="">
                                                        {{ trans('app.forms.please_select') }}
                                                    </option>
                                                    @foreach ($cobOptions as $cob)
                                                        <option value="{{ $cob }}"
                                                            {{ Input::old('cob') == $cob ? 'selected' : '' }}>
                                                            {{ $cob }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" class="form-control"
                                                    value="{{ \Helper\Helper::isMBSJContext() ? 'MBSJ' : 'MPKL' }}"
                                                    readonly>
                                                <input type="hidden" id="cob" name="cob"
                                                    value="{{ \Helper\Helper::isMBSJContext() ? 'MBSJ' : 'MPKL' }}">
                                            @endif
                                            @include('alert.feedback', ['field' => 'cob'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Tarikh Aduan diterima --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('date_received') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.date_received') }}
                                            </label>
                                            <input type="text" id="date_received_temp" class="form-control">
                                            <input type="hidden" id="date_received" name="date_received"
                                                value="{{ Input::old('date_received') }}" />
                                            @include('alert.feedback', ['field' => 'date_received'])
                                        </div>
                                    </div>
                                </div>

                                {{-- No. Fail --}}
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
                                                        data-cob="{{ $file_no->company ? strtoupper($file_no->company->short_name) : '' }}"
                                                        data-zone="{{ $file_no->strata && $file_no->strata->parks ? e($file_no->strata->parks->description) : '' }}"
                                                        {{ Input::old('file_id') == $file_no->id ? 'selected' : '' }}>
                                                        {{ $file_no->strataName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @include('alert.feedback', ['field' => 'file_id'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Blok Perancangan MBSJ --}}
                                <div class="row mbsj-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('planning_block') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mbsj" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.planning_block') }}
                                            </label>
                                            <select id="planning_block" name="planning_block" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @foreach ($planningBlocks as $block)
                                                    <option value="{{ $block }}"
                                                        {{ Input::old('planning_block') == $block ? 'selected' : '' }}>
                                                        {{ $block }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @include('alert.feedback', ['field' => 'planning_block'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Zon MPP (from the selected file's park) --}}
                                <div class="row mbsj-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('mpp_zone') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mbsj" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.mpp_zone') }}
                                            </label>
                                            <select id="mpp_zone" name="mpp_zone" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @foreach ($mppZones as $zone)
                                                    <option value="{{ e($zone) }}"
                                                        {{ Input::old('mpp_zone') == $zone ? 'selected' : '' }}>
                                                        {{ e($zone) }}
                                                    </option>
                                                @endforeach
                                                @if (Input::old('mpp_zone') && !in_array(Input::old('mpp_zone'), $mppZones))
                                                    <option value="{{ e(Input::old('mpp_zone')) }}" selected>
                                                        {{ e(Input::old('mpp_zone')) }}
                                                    </option>
                                                @endif
                                            </select>
                                            @include('alert.feedback', ['field' => 'mpp_zone'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Tarikh Aduan diminitkan --}}
                                <div class="row mbsj-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('date_minuted') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mbsj" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.date_minuted') }}
                                            </label>
                                            <input type="text" id="date_minuted_temp" class="form-control">
                                            <input type="hidden" id="date_minuted" name="date_minuted"
                                                value="{{ Input::old('date_minuted') }}" />
                                            @include('alert.feedback', ['field' => 'date_minuted'])
                                        </div>
                                    </div>
                                </div>

                                {{-- No. Rujukan Aduan --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('letter_ref_no') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.letter_ref_no') }}
                                            </label>
                                            <input type="text" id="letter_ref_no" name="letter_ref_no"
                                                class="form-control" value="{{ Input::old('letter_ref_no') }}">
                                            @include('alert.feedback', ['field' => 'letter_ref_no'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Nama Pengadu --}}
                                <div class="row mbsj-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('complainant_name') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mbsj" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_name') }}
                                            </label>
                                            <input type="text" id="complainant_name" name="complainant_name"
                                                class="form-control" value="{{ Input::old('complainant_name') }}" />
                                            @include('alert.feedback', ['field' => 'complainant_name'])
                                        </div>
                                    </div>
                                </div>

                                {{-- No. Tel Pengadu --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('complainant_phone_no') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_phone_no') }}
                                            </label>
                                            <input type="text" id="complainant_phone_no" name="complainant_phone_no"
                                                class="form-control" value="{{ Input::old('complainant_phone_no') }}" />
                                            @include('alert.feedback', [
                                                'field' => 'complainant_phone_no',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                {{-- Emel Pengadu --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('complainant_email') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_email') }}
                                            </label>
                                            <input type="email" id="complainant_email" name="complainant_email"
                                                class="form-control" value="{{ Input::old('complainant_email') }}" />
                                            @include('alert.feedback', [
                                                'field' => 'complainant_email',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                {{-- Tajuk Aduan / Nama Aduan --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                <span class="cob-label"
                                                    data-mpkl="{{ trans('app.forms.complaint.name') }}"
                                                    data-mbsj="{{ trans('app.forms.complaint.title') }}">{{ trans('app.forms.complaint.name') }}</span>
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                value="{{ Input::old('name') }}" />
                                            @include('alert.feedback', ['field' => 'name'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Ringkasan Aduan / Keterangan Aduan --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('description') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                <span class="cob-label"
                                                    data-mpkl="{{ trans('app.forms.complaint.description') }}"
                                                    data-mbsj="{{ trans('app.forms.complaint.summary') }}">{{ trans('app.forms.complaint.description') }}</span>
                                            </label>
                                            <textarea id="description" name="description" class="form-control" rows="5">{{ Input::old('description') }}</textarea>
                                            @include('alert.feedback', ['field' => 'description'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Kategori Aduan --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('complaint_category_id') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.category') }}
                                            </label>
                                            @if ($complaintCategory)
                                                @foreach ($complaintCategory as $category)
                                                    <div class="form-check category-row"
                                                        data-cob="{{ $category->cob ? strtoupper($category->cob) : '' }}">
                                                        <input class="form-check-input category-radio" type="radio"
                                                            name="complaint_category_id" id="category_{{ $category->id }}"
                                                            value="{{ $category->id }}" data-name="{{ $category->name }}"
                                                            data-cob="{{ $category->cob ? strtoupper($category->cob) : '' }}"
                                                            data-types='{{ json_encode($category->types) }}'
                                                            {{ Input::old('complaint_category_id') == $category->id ? 'checked' : '' }}>
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

                                {{-- Jenis --}}
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
                                                class="form-control" value="{{ Input::old('complaint_type_text') }}">
                                            @include('alert.feedback', ['field' => 'complaint_type_text'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Muatnaik Aduan (Attachment) --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('attachment') ? 'has-danger' : '' }}">
                                            <label class="form-label">
                                                <span class="req-mpkl" style="color: red; font-style: italic;">*</span>
                                                <span class="cob-label"
                                                    data-mpkl="{{ trans('app.forms.complaint.attachment') }}"
                                                    data-mbsj="{{ trans('app.forms.complaint.attachment_mbsj') }}">{{ trans('app.forms.complaint.attachment') }}</span>
                                            </label>
                                            <input type="file" id="attachment" name="attachment"
                                                class="form-control-file" />
                                            <small class="form-text text-muted">
                                                <strong>Maksimum saiz fail: 10MB</strong>
                                            </small>
                                            <br />
                                            @include('alert.feedback', ['field' => 'attachment'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Penerimaan Aduan --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('scheme_receiver') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                <span class="cob-label"
                                                    data-mpkl="{{ trans('app.forms.complaint.scheme_receiver') }}"
                                                    data-mbsj="{{ trans('app.forms.complaint.reception') }}">{{ trans('app.forms.complaint.scheme_receiver') }}</span>
                                            </label>
                                            <div>
                                                <div class="checkbox">
                                                    @foreach (['letter', 'sispa', 'phone_call', 'email', 'walkin', 'whatsapp'] as $receiver)
                                                        <label>
                                                            <input type="checkbox" name="scheme_receiver[]"
                                                                value="{{ $receiver }}"
                                                                {{ is_array(Input::old('scheme_receiver')) && in_array($receiver, Input::old('scheme_receiver')) ? 'checked' : '' }}>
                                                            {{ trans('app.forms.complaint.' . $receiver) }}
                                                        </label><br />
                                                    @endforeach
                                                </div>
                                            </div>
                                            @include('alert.feedback', ['field' => 'scheme_receiver'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Jenis Pengadu --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('complainant_type') || $errors->has('complainant_type_others') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_type') }}
                                            </label>

                                            {{-- MPKL complainant types --}}
                                            <select id="complainant_type_mpkl" name="complainant_type"
                                                class="form-control mpkl-only" onchange="showComplainantTypeOthers(this)">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="individual"
                                                    {{ Input::old('complainant_type') == 'individual' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.individual') }}
                                                </option>
                                                <option value="management"
                                                    {{ Input::old('complainant_type') == 'management' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.management') }}
                                                </option>
                                                <option value="others"
                                                    {{ Input::old('complainant_type') == 'others' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.others') }}
                                                </option>
                                            </select>

                                            {{-- MBSJ complainant types --}}
                                            <select id="complainant_type_mbsj" name="complainant_type"
                                                class="form-control mbsj-only" onchange="showComplainantTypeOthers(this)">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @foreach ($mbsjComplainantTypes as $type)
                                                    <option value="{{ $type }}"
                                                        {{ Input::old('complainant_type') == $type ? 'selected' : '' }}>
                                                        {{ trans('app.forms.complaint.' . $type) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @include('alert.feedback', [
                                                'field' => 'complainant_type',
                                            ])

                                            <div id="complainant_type_others_field"
                                                style="{{ Input::old('complainant_type') == 'others' ? '' : 'display: none;' }}">
                                                <input type="text" id="complainant_type_others"
                                                    name="complainant_type_others" class="form-control"
                                                    value="{{ Input::old('complainant_type_others') }}" />
                                                @include('alert.feedback', [
                                                    'field' => 'complainant_type_others',
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- MPKL only: alamat skim --}}
                                <div class="row mpkl-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('scheme_address') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.scheme_address') }}
                                            </label>
                                            <textarea id="scheme_address" name="scheme_address" class="form-control" rows="5">{{ Input::old('scheme_address') }}</textarea>
                                            @include('alert.feedback', ['field' => 'scheme_address'])
                                        </div>
                                    </div>
                                </div>

                                {{-- MPKL only: zon --}}
                                <div class="row mpkl-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('scheme_zone') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.scheme_zone') }}
                                            </label>
                                            <select id="scheme_zone" name="scheme_zone" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="zone_1"
                                                    {{ Input::old('scheme_zone') == 'zone_1' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.zone_1') }}
                                                </option>
                                                <option value="zone_2"
                                                    {{ Input::old('scheme_zone') == 'zone_2' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.zone_2') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'scheme_zone'])
                                        </div>
                                    </div>
                                </div>

                                {{-- MPKL only: no. kad pengenalan --}}
                                <div class="row mpkl-only">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('complainant_ic_no') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complainant_ic_no') }}
                                            </label>
                                            <input type="text" id="complainant_ic_no" name="complainant_ic_no"
                                                class="form-control" value="{{ Input::old('complainant_ic_no') }}" />
                                            @include('alert.feedback', [
                                                'field' => 'complainant_ic_no',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                {{-- MPKL only: kategori aduan --}}
                                <div class="row mpkl-only">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('complaint_category') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.category') }}
                                            </label>
                                            <select id="complaint_category" name="complaint_category"
                                                class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="common_area"
                                                    {{ Input::old('complaint_category') == 'common_area' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.common_area') }}
                                                </option>
                                                <option value="owner_unit"
                                                    {{ Input::old('complaint_category') == 'owner_unit' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.owner_unit') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', [
                                                'field' => 'complaint_category',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                {{-- MPKL only: komplikasi aduan --}}
                                <div class="row mpkl-only">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('complaint_complication') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.complication') }}
                                            </label>
                                            <select id="complaint_complication" name="complaint_complication"
                                                class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="light"
                                                    {{ Input::old('complaint_complication') == 'light' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.light') }}
                                                </option>
                                                <option value="moderate"
                                                    {{ Input::old('complaint_complication') == 'moderate' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.moderate') }}
                                                </option>
                                                <option value="heavy"
                                                    {{ Input::old('complaint_complication') == 'heavy' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.heavy') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', [
                                                'field' => 'complaint_complication',
                                            ])
                                        </div>
                                    </div>
                                </div>

                                {{-- MPKL only: tempoh tindakan --}}
                                <div class="row mpkl-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('action_duration') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.action_duration') }}
                                            </label>
                                            <select id="action_duration" name="action_duration" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="7_days"
                                                    {{ Input::old('action_duration') == '7_days' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.7_days') }}
                                                </option>
                                                <option value="14_days"
                                                    {{ Input::old('action_duration') == '14_days' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.14_days') }}
                                                </option>
                                                <option value="30_days"
                                                    {{ Input::old('action_duration') == '30_days' ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.30_days') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'action_duration'])
                                        </div>
                                    </div>
                                </div>

                                {{-- MPKL only: status --}}
                                <div class="row mpkl-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('status') ? 'has-danger' : '' }}">
                                            <label>
                                                <span style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.status') }}
                                            </label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                <option value="1" {{ Input::old('status') == 1 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.under_investigation_1') }}
                                                </option>
                                                <option value="2" {{ Input::old('status') == 2 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.under_investigation_2') }}
                                                </option>
                                                <option value="3" {{ Input::old('status') == 3 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.resolved') }}
                                                </option>
                                                <option value="4" {{ Input::old('status') == 4 ? 'selected' : '' }}>
                                                    {{ trans('app.forms.complaint.received') }}
                                                </option>
                                            </select>
                                            @include('alert.feedback', ['field' => 'status'])
                                        </div>
                                    </div>
                                </div>

                                {{-- Ulasan Pegawai Bertugas --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('officer_review') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mpkl" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.officer_review') }}
                                            </label>
                                            <textarea id="officer_review" name="officer_review" class="form-control" rows="10">{{ Input::old('officer_review') }}</textarea>
                                            @include('alert.feedback', ['field' => 'officer_review'])
                                        </div>
                                    </div>
                                </div>

                                {{-- MBSJ only: tarikh tindakan --}}
                                <div class="row mbsj-only">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('action_date') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mbsj" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.action_date') }}
                                            </label>
                                            <input type="text" id="action_date_temp" class="form-control">
                                            <input type="hidden" id="action_date" name="action_date"
                                                value="{{ Input::old('action_date') }}" />
                                            @include('alert.feedback', ['field' => 'action_date'])
                                        </div>
                                    </div>
                                </div>

                                {{-- MBSJ only: kaedah tindakan penyelesaian --}}
                                <div class="row mbsj-only">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('resolution_method') ? 'has-danger' : '' }}">
                                            <label class="form-control-label">
                                                <span class="req-mbsj" style="color: red;">*</span>
                                                {{ trans('app.forms.complaint.resolution_method') }}
                                            </label>
                                            <select id="resolution_method" name="resolution_method"
                                                class="form-control">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @foreach ($resolutionMethods as $method)
                                                    <option value="{{ $method }}"
                                                        {{ Input::old('resolution_method') == $method ? 'selected' : '' }}>
                                                        {{ trans('app.forms.complaint.' . $method) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @include('alert.feedback', ['field' => 'resolution_method'])
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    @if (AccessGroup::hasInsertModule('Complaint Category'))
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
            function handleCategoryChange(types, selectedTypeId, selectedTextValue) {
                var $dropdown = $('#complaint_type_id');
                var $dropdownContainer = $('#type-select-container');
                var $textContainer = $('#type-text-container');
                var $textInput = $('#complaint_type_text');

                $dropdown.empty().append('<option value="">{{ trans('app.forms.please_select') }}</option>');

                if (types && types.length > 0) {
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

            var isAllCob = {{ $isAllCob ? 'true' : 'false' }};

            // Keep the full file list so it can be filtered by the selected COB.
            var allFiles = [];
            $('#file_id option').each(function() {
                var value = $(this).val();
                if (value) {
                    allFiles.push({
                        id: value,
                        name: $(this).text().trim(),
                        cob: ($(this).data('cob') || '').toUpperCase(),
                        zone: $(this).data('zone') || ''
                    });
                }
            });

            function populateFiles(cob) {
                var $file = $('#file_id');
                var current = $file.val();
                $file.empty().append('<option value="">{{ trans('app.forms.please_select') }}</option>');

                if (cob) {
                    $.each(allFiles, function(index, file) {
                        if (file.cob === cob) {
                            $file.append($('<option>').val(file.id).text(file.name)
                                .attr('data-cob', file.cob)
                                .attr('data-zone', file.zone));
                        }
                    });
                }

                if (current && $file.find('option[value="' + current + '"]').length) {
                    $file.val(current);
                }

                $file.trigger('change.select2');
            }

            // Zon MPP comes from the selected file's park (strata -> park).
            // force = overwrite the current value; otherwise only fill it when empty.
            function applyFileZone(force) {
                var $zone = $('#mpp_zone');
                if (!$zone.length) {
                    return;
                }
                if (!force && $.trim(String($zone.val() || '')) !== '') {
                    return;
                }

                var zone = $('#file_id option:selected').data('zone');
                $zone.val(zone || '');
            }

            function applyCobUi(cob) {
                cob = (cob || '').toUpperCase();
                var isMbsj = cob === 'MBSJ';
                var isMpkl = cob === 'MPKL';

                $('.mbsj-only').toggle(isMbsj);
                $('.mpkl-only').toggle(isMpkl);
                $('.req-mbsj').toggle(isMbsj);
                $('.req-mpkl').toggle(isMpkl);

                $('.cob-label').each(function() {
                    var $label = $(this);
                    $label.text(isMbsj ? $label.data('mbsj') : $label.data('mpkl'));
                });

                $('#complainant_type_mbsj').prop('disabled', !isMbsj);
                $('#complainant_type_mpkl').prop('disabled', !isMpkl);

                $('.category-row').each(function() {
                    var categoryCob = ($(this).data('cob') || '').toUpperCase();
                    var show = !!cob && (!categoryCob || categoryCob === cob);
                    $(this).toggle(show);

                    if (!show) {
                        var $radio = $(this).find('.category-radio');
                        if ($radio.is(':checked')) {
                            $radio.prop('checked', false);
                            $('#type-select-container').hide();
                            $('#type-text-container').hide();
                        }
                    }
                });
            }

            $('.category-radio').on('change', function() {
                var types = $(this).data('types');
                handleCategoryChange(types, null, null);
            });

            $('#cob').on('change', function() {
                var cob = ($(this).val() || '').toUpperCase();
                if (isAllCob) {
                    populateFiles(cob);
                    applyFileZone(true);
                    $('#complainant_type_mbsj').val('').trigger('change');
                    $('#complainant_type_mpkl').val('').trigger('change');
                }
                applyCobUi(cob);
                if (isAllCob) {
                    $('#submit_button').prop('disabled', !cob);
                }
            });

            $('#file_id').on('change', function() {
                var cob = ($('#file_id option:selected').data('cob') || '').toUpperCase();
                if (isAllCob && cob) {
                    $('#cob').val(cob);
                    $('#submit_button').prop('disabled', false);
                }
                applyCobUi(cob);
                if (cob === 'MBSJ') {
                    applyFileZone(true);
                }
            });

            // Initialise fields for the active COB.
            var initialCob =
                "{{ Input::old('cob') ?: ($isAllCob ? '' : (\Helper\Helper::isMBSJContext() ? 'MBSJ' : 'MPKL')) }}";
            var selectedFileCob = ($('#file_id option:selected').data('cob') || '').toUpperCase();
            if (selectedFileCob) {
                initialCob = selectedFileCob;
            }
            if (isAllCob) {
                $('#cob').val(initialCob);
                populateFiles(initialCob);
                $('#submit_button').prop('disabled', !initialCob);
            }
            applyCobUi(initialCob);
            if (initialCob === 'MBSJ') {
                applyFileZone(false);
            }

            // Autopopulate semula selepas validation fail
            var selectedCategoryId = "{{ Input::old('complaint_category_id') }}";
            var selectedTypeId = "{{ Input::old('complaint_type_id') }}";
            var selectedTypeText = "{{ Input::old('complaint_type_text') }}";

            if (selectedCategoryId) {
                var $selectedRadio = $('.category-radio[value="' + selectedCategoryId + '"]');
                if ($selectedRadio.length) {
                    var types = $selectedRadio.data('types');
                    $selectedRadio.prop('checked', true);
                    handleCategoryChange(types, selectedTypeId, selectedTypeText);
                }
            }

            function initDatePicker(tempId, hiddenId) {
                var oldDate = $('#' + hiddenId).val();
                if (oldDate) {
                    var parts = oldDate.split('-');
                    $('#' + tempId).val(parts[2] + '/' + parts[1] + '/' + parts[0]);
                }

                $('#' + tempId).datetimepicker({
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
                    var selectedDate = $(this).val();
                    if (selectedDate) {
                        var d = selectedDate.split('/');
                        $('#' + hiddenId).val(d[2] + '-' + d[1] + '-' + d[0]);
                    } else {
                        $('#' + hiddenId).val('');
                    }
                });
            }

            initDatePicker('date_received_temp', 'date_received');
            initDatePicker('date_minuted_temp', 'date_minuted');
            initDatePicker('action_date_temp', 'action_date');

            // ========================================
            // Client-side validation
            // ========================================
            var $complaintForm = $('form.form-horizontal');
            var isEditMode = false;
            var requiredTpl =
                {{ json_encode(trans('app.forms.field_required', array('field' => '__FIELD__'))) }};
            var emailTpl =
                {{ json_encode(trans('app.forms.field_email', array('field' => '__FIELD__'))) }};

            function validationLabel($el) {
                var $group = $el.closest('.form-group');
                var $label = $group.find('label.form-control-label').first();
                if (!$label.length) {
                    $label = $group.find('label').first();
                }
                return $.trim($label.text()).replace(/^\*+\s*/, '');
            }

            function addFieldError($el, message) {
                var $group = $el.closest('.form-group');
                if (!$group.length) {
                    return;
                }
                $group.addClass('has-danger');
                if (!$group.find('.js-inline-error').length) {
                    $group.append($('<span class="help-block text-danger js-inline-error"></span>').text(message));
                }
            }

            function clearComplaintErrors() {
                $complaintForm.find('.form-group > .help-block.text-danger').remove();
                $complaintForm.find('.form-group').removeClass('has-danger');
            }

            function validateComplaintForm() {
                clearComplaintErrors();

                var cob = $.trim(String($('#cob').val() || '')).toUpperCase();
                var isMbsj = cob === 'MBSJ';
                var isMpkl = cob === 'MPKL';
                var firstError = null;

                function isBlank($el) {
                    var value = $el.val();
                    if (value === null || value === undefined) {
                        return true;
                    }
                    if ($.isArray(value)) {
                        return value.length === 0;
                    }
                    return $.trim(String(value)) === '';
                }

                function fail($el, message) {
                    addFieldError($el, message);
                    if (!firstError) {
                        firstError = $el;
                    }
                }

                function requireField(selector) {
                    var $el = $(selector);
                    if (!$el.length || $el.is(':disabled')) {
                        return;
                    }
                    if (isBlank($el)) {
                        fail($el, requiredTpl.replace('__FIELD__', validationLabel($el)));
                    }
                }

                function failGroup($group, label) {
                    if (!$group.length) {
                        return;
                    }
                    $group.addClass('has-danger');
                    if (!$group.find('.js-inline-error').length) {
                        $group.append($('<span class="help-block text-danger js-inline-error"></span>').text(
                            requiredTpl.replace('__FIELD__', label)));
                    }
                    if (!firstError) {
                        firstError = $group;
                    }
                }

                // COB must be selected first (All-COB view)
                if (isAllCob && !cob) {
                    fail($('#cob'), requiredTpl.replace('__FIELD__', validationLabel($('#cob'))));
                }

                // Shared mandatory fields
                requireField('#file_id');
                requireField('#date_received');
                requireField('#name');
                requireField('#description');

                // Complaint category (radio group)
                var $visibleCategories = $('.category-row:visible .category-radio');
                if ($visibleCategories.length && !$visibleCategories.filter(':checked').length) {
                    var $categoryGroup = $('.category-row:visible').first().closest('.form-group');
                    failGroup($categoryGroup, validationLabel($categoryGroup.find('label.form-control-label')
                        .first()));
                }

                // Complaint type (depends on the selected category)
                if ($('#type-select-container').is(':visible')) {
                    requireField('#complaint_type_id');
                }
                if ($('#type-text-container').is(':visible')) {
                    requireField('#complaint_type_text');
                }

                // Penerimaan Aduan (at least one)
                var $receivers = $('input[name="scheme_receiver[]"]');
                if ($receivers.length && !$receivers.filter(':checked').length) {
                    var $receiverGroup = $receivers.first().closest('.form-group');
                    failGroup($receiverGroup, validationLabel($receiverGroup.find('label.form-control-label')
                        .first()));
                }

                // Jenis Pengadu
                var $complainantType = $('#complainant_type_mbsj').not(':disabled');
                if (!$complainantType.length) {
                    $complainantType = $('#complainant_type_mpkl').not(':disabled');
                }
                if ($complainantType.length) {
                    if (isBlank($complainantType)) {
                        fail($complainantType, requiredTpl.replace('__FIELD__', validationLabel($complainantType)));
                    } else if ($complainantType.val() === 'others') {
                        requireField('#complainant_type_others');
                    }
                }

                // E-mail format (optional for MBSJ, required for MPKL)
                var email = $.trim(String($('#complainant_email').val() || ''));
                if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    fail($('#complainant_email'), emailTpl.replace('__FIELD__', validationLabel($(
                        '#complainant_email'))));
                }

                if (isMpkl) {
                    requireField('#letter_ref_no');
                    requireField('#scheme_address');
                    requireField('#scheme_zone');
                    requireField('#complainant_phone_no');
                    requireField('#complainant_email');
                    requireField('#complainant_ic_no');
                    requireField('#complaint_category');
                    requireField('#complaint_complication');
                    requireField('#action_duration');
                    requireField('#status');
                    requireField('#officer_review');

                    if (!isEditMode && !$('#attachment').val()) {
                        fail($('#attachment'), requiredTpl.replace('__FIELD__', validationLabel($('#attachment'))));
                    }
                }

                if (isMbsj) {
                    requireField('#planning_block');
                    requireField('#mpp_zone');
                    requireField('#date_minuted');
                    requireField('#complainant_name');
                    requireField('#action_date');
                    requireField('#resolution_method');
                }

                if (firstError) {
                    var $scrollTarget = firstError.closest('.form-group');
                    if (!$scrollTarget.length) {
                        $scrollTarget = firstError;
                    }
                    $('html, body').animate({
                        scrollTop: Math.max(0, $scrollTarget.offset().top - 100)
                    }, 400);

                    var $focusTarget = $scrollTarget.find(
                        'input:visible, select:visible, textarea:visible').not(':disabled').first();
                    if ($focusTarget.length) {
                        setTimeout(function() {
                            try {
                                $focusTarget.focus();
                            } catch (e) {}
                        }, 420);
                    }
                    return false;
                }

                return true;
            }

            $complaintForm.on('submit', function(e) {
                if (!validateComplaintForm()) {
                    e.preventDefault();
                    return false;
                }
            });

            // Clear a field's error as soon as the user edits it.
            $complaintForm.on('input change dp.change', 'input, select, textarea', function() {
                var $group = $(this).closest('.form-group');
                $group.removeClass('has-danger');
                $group.find('.js-inline-error').remove();
            });

            // Reset errors when the COB (and therefore the visible fields) changes.
            $('#cob').on('change', clearComplaintErrors);
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
