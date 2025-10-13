@extends('layout.english_layout.default_custom')

@section('content')
    <style>
        /* ========================================
                                                                                           GENERAL FORM STYLING
                                                                                        ======================================== */
        .mandatory-notice {
            margin-bottom: 20px;
        }

        .form-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-section h5 {
            background-color: #e9ecef;
            color: #495057;
            padding: 12px 15px;
            margin: -20px -20px 20px -20px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }

        .form-group {
            margin-bottom: 15px;
        }

        /* Special styling for unit count fields group */
        .unit-count-group .row {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .unit-count-group .row>[class*="col-"] {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .unit-count-group .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .unit-count-group .form-control-label {
            font-weight: 600;
            color: #495057;
            min-height: 2.5em;
            line-height: 1.2;
            display: flex;
            align-items: flex-start;
            padding-top: 0.25em;
        }

        .unit-count-group .form-control {
            margin-top: auto;
        }

        /* Fix alignment when validation errors are present */
        .unit-count-group .form-group {
            position: relative;
        }

        .unit-count-group .invalid-feedback {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
            z-index: 1;
        }

        /* Ensure consistent spacing when errors are present */
        .unit-count-group .form-group:has(.invalid-feedback) {
            margin-bottom: 2.5rem;
            /* Space for error message */
        }

        /* Fallback for browsers that don't support :has() */
        .unit-count-group .form-group {
            margin-bottom: 2.5rem;
            /* Always reserve space for potential error messages */
        }

        .unit-count-group .form-group:not(:has(.invalid-feedback)) {
            margin-bottom: 15px;
            /* Normal spacing when no errors */
        }

        .form-control-label {
            font-weight: 600;
            color: #495057;
            display: block;
        }

        /* Simple and direct approach for red asterisk spacing */
        span[style*="color: red"] {
            margin-right: 2px !important;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control-file {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8f9fa;
            font-size: 14px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            width: 100%;
            min-width: 300px;
        }

        .form-control-file:hover {
            border-color: #007bff;
            background-color: #e3f2fd;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
        }

        .form-control-file:focus {
            outline: none;
            border-color: #007bff;
            background-color: #e3f2fd;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control-file::before {
            content: "📁 {{ trans('app.forms.tppm.file_upload') }}";
            display: block;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-control-file::after {
            content: "{{ trans('app.forms.tppm.file_upload_text') }}";
            display: block;
            font-size: 12px;
            color: #6c757d;
            font-style: italic;
        }

        .form-control-file input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .form-control-file.has-file {
            border-color: #28a745;
            background-color: #d4edda;
        }

        .form-control-file.has-file::before {
            content: "✅ {{ trans('app.forms.tppm.file_upload_selected') }}";
            color: #155724;
        }

        .form-control-file.has-file::after {
            content: "{{ trans('app.forms.tppm.change_file_upload') }}";
            color: #155724;
        }

        /* Category radio button styling */
        .category-radio-group {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-top: 8px;
        }

        .category-row {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .category-option {
            flex: 1;
            margin-bottom: 0;
        }

        .category-option:last-child {
            margin-bottom: 0;
        }

        .category-label {
            display: flex;
            align-items: flex-start;
            cursor: pointer;
            margin-bottom: 0;
            padding: 10px;
            border-radius: 6px;
            transition: background-color 0.15s ease-in-out;
        }

        .category-label:hover {
            background-color: #e9ecef;
        }

        .category-label input[type="radio"] {
            margin-right: 12px;
            margin-top: 2px;
            transform: scale(1.2);
        }

        .category-content {
            flex: 1;
        }

        .category-title {
            font-weight: bold;
            font-size: 16px;
            color: #212529;
            margin-bottom: 8px;
        }

        .category-subtitle {
            font-weight: 600;
            font-size: 14px;
            color: #495057;
            margin-bottom: 6px;
        }

        .price-range {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 3px;
            margin-left: 8px;
        }

        .category-separator {
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .category-label input[type="radio"]:checked+.category-content .category-title {
            color: #007bff;
        }

        .category-label input[type="radio"]:checked+.category-content .category-subtitle {
            color: #0056b3;
        }

        /* Responsive design for category */
        @media (max-width: 768px) {
            .category-row {
                flex-direction: column;
                gap: 15px;
            }

            .category-separator {
                order: 2;
            }

            .category-option:first-child {
                order: 1;
            }

            .category-option:last-child {
                order: 3;
            }
        }

        /* Attention notice styling */
        .attention-notice {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        /* Arahan notice styling - left aligned */
        .attention-notice.arahan-notice {
            text-align: left;
        }

        .attention-title {
            font-weight: bold;
            font-size: 16px;
            color: #212529;
            margin-bottom: 8px;
        }

        .attention-text {
            font-size: 14px;
            color: #495057;
            margin-bottom: 4px;
        }

        .attention-text:last-child {
            margin-bottom: 0;
        }

        .help-block {
            font-size: 12px;
            margin-top: 5px;
        }

        .text-success {
            color: #28a745 !important;
        }

        /* ========================================
                                       VALIDATION STYLES
                                       ======================================== */

        /* Base validation styles */
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        /* Select2 validation styles */
        .select2-container .is-invalid {
            border-color: #dc3545 !important;
        }

        .select2-container--default .select2-selection--single.is-invalid {
            border: 1px solid #dc3545 !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single.is-invalid {
            border: 1px solid #dc3545 !important;
        }

        /* Special field validation styles */
        .category-radio-group.is-invalid {
            border: 2px solid #dc3545;
            border-radius: 4px;
            padding: 10px;
            background-color: rgba(220, 53, 69, 0.05);
        }

        .scope-table.is-invalid {
            border: 2px solid #dc3545;
            border-radius: 4px;
        }

        /* File upload validation styles */
        .form-control-file.is-invalid,
        .checklist-item .form-control-file.is-invalid {
            border: 2px solid #dc3545 !important;
            border-radius: 4px;
            padding: 8px;
            background-color: rgba(220, 53, 69, 0.05);
        }

        /* Field group validation styles for scope fields */
        .field-group.is-invalid {
            border: 2px solid #dc3545;
            border-radius: 4px;
            padding: 10px;
            background-color: rgba(220, 53, 69, 0.05);
        }

        /* Radio button group validation styles */
        .radio-options.is-invalid {
            border: 2px solid #dc3545;
            border-radius: 4px;
            padding: 10px;
            background-color: rgba(220, 53, 69, 0.05);
        }

        /* ========================================
                                                                                           BAHAGIAN A - APPLICATION DETAILS
                                                                                        ======================================== */
        /* (Uses general form styling above) */

        /* ========================================
                                                                                           BAHAGIAN B - SCOPE OF WORKS
                                                                                        ======================================== */
        .scope-table {
            margin-bottom: 0;
        }

        .scope-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            padding: 8px 4px;
            text-align: center;
        }

        .scope-table .header-center {
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
        }

        .scope-table .header-center input[type="checkbox"] {
            transform: scale(1.3);
            margin-bottom: 5px;
        }

        .scope-table .header-checkbox {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .scope-table td {
            padding: 8px 4px;
            vertical-align: top;
            text-align: center;
        }

        .scope-table input[type="checkbox"] {
            transform: scale(1.2);
        }

        .scope-table input[type="radio"] {
            transform: scale(1.1);
        }

        .scope-table .form-control {
            font-size: 11px;
            padding: 2px 4px;
        }

        .scope-table label {
            font-size: 11px;
            margin-bottom: 2px;
        }

        .scope-table .alert {
            margin-top: 15px;
            font-size: 12px;
        }

        .scope-table .alert ul {
            margin-bottom: 0;
        }

        .scope-table .alert li {
            margin-bottom: 3px;
        }

        .scope-table .radio-options label {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            font-size: 10px;
            margin: 0;
            cursor: pointer;
            white-space: nowrap;
            width: 100%;
            text-align: left;
        }

        .scope-table .radio-options input[type="radio"] {
            margin-right: 3px;
            transform: scale(0.9);
            flex-shrink: 0;
        }

        .scope-table .radio-options {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            gap: 4px;
            width: 100%;
            padding: 8px 4px;
        }

        /* Styling for mengecat and pendawaian fields */
        .scope-table .field-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 8px 4px;
        }

        .scope-table .field-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .scope-table .field-item label {
            font-size: 11px;
            margin: 0;
            min-width: 20px;
        }

        .scope-table .field-item input {
            flex: 1;
            font-size: 11px;
            padding: 2px 4px;
        }

        @media (max-width: 768px) {
            .scope-table {
                font-size: 10px;
            }

            .scope-table th,
            .scope-table td {
                padding: 4px 2px;
            }
        }

        /* ========================================
                                                                                           BAHAGIAN C - APPLICANT CHECKLIST
                                                                                        ======================================== */
        .checklist-section {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }

        .checklist-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .checklist-item:hover {
            background-color: #e9ecef;
            border-color: #ced4da;
        }

        .checklist-item .form-control-label {
            font-size: 16px;
            font-weight: 600;
            color: #495057;
        }

        .checklist-item .help-block {
            font-size: 13px;
            color: #6c757d;
            margin-top: 8px;
        }

        .checklist-item .form-control-file {
            width: 100%;
            max-width: 100%;
        }

        /* ========================================
                                                                                           FORM ACTIONS
                                                                                        ======================================== */
        .form-actions {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 20px;
            margin: 20px -15px -20px -15px;
            border-radius: 0 0 8px 8px;
        }

        .btn-own {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
        }

        .btn-own:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-default {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
        }

        .btn-default:hover {
            background-color: #545b62;
            border-color: #4e555b;
        }
    </style>

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

                            <div class="text-center" style="margin-bottom: 15px;">
                                <h3 class="text-bold" style="margin: 0;">
                                    {{ trans('app.forms.tppm.borang_permohonan') }}
                                </h3>
                                <div style="font-size: 16px;">
                                    {{ trans('app.forms.tppm.tabung_penyenggaraan_perumahan_malaysia') }}
                                </div>
                            </div>

                            <form id="tppm-form" class="form-horizontal" onsubmit="event.preventDefault();">

                                <div class="mandatory-notice">
                                    <span style="color: red;">
                                        * {{ trans('app.forms.mandatory_fields') }}
                                    </span>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label">
                                                <span style="color: red;">*</span>&nbsp;
                                                {{ trans('app.forms.tppm.category') }}
                                            </label>
                                            <div class="category-radio-group">
                                                <div class="category-row">
                                                    <div class="category-option">
                                                        <label class="category-label">
                                                            <input type="radio" name="cost_category" value="low_cost"
                                                                {{ Input::old('cost_category') == 'low_cost' ? 'checked' : '' }}>
                                                            <div class="category-content">
                                                                <div class="category-title">
                                                                    {{ trans('app.forms.tppm.low_cost') }}</div>
                                                                <div class="category-subtitle">
                                                                    {{ trans('app.forms.tppm.original_purchase_price') }}
                                                                </div>
                                                                <div class="price-range">
                                                                    {{ trans('app.forms.tppm.peninsular_low_cost_price') }}
                                                                </div>
                                                                <div class="price-range">
                                                                    {{ trans('app.forms.tppm.sabah_sarawak_low_cost_price') }}
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>

                                                    <div class="category-separator">{{ trans('app.forms.tppm.or') }}</div>

                                                    <div class="category-option">
                                                        <label class="category-label">
                                                            <input type="radio" name="cost_category"
                                                                value="low_medium_cost"
                                                                {{ Input::old('cost_category') == 'low_medium_cost' ? 'checked' : '' }}>
                                                            <div class="category-content">
                                                                <div class="category-title">
                                                                    {{ trans('app.forms.tppm.low_medium_cost') }}</div>
                                                                <div class="category-subtitle">
                                                                    {{ trans('app.forms.tppm.original_purchase_price') }}
                                                                </div>
                                                                <div class="price-range">
                                                                    {{ trans('app.forms.tppm.peninsular_low_medium_cost_price') }}
                                                                </div>
                                                                <div class="price-range">
                                                                    {{ trans('app.forms.tppm.sabah_sarawak_low_medium_cost_price') }}
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="attention-notice">
                                    <div class="attention-title">{{ trans('app.forms.tppm.attention_title') }}</div>
                                    <div class="attention-text">{{ trans('app.forms.tppm.attention_text_1') }}</div>
                                    <div class="attention-text">{{ trans('app.forms.tppm.attention_text_2') }}</div>
                                </div>

                                <div class="form-section">
                                    <h5 class="text-bold">
                                        A. {{ trans('app.forms.tppm.application_details') }}
                                    </h5>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.scheme_name') }}

                                                </label>
                                                <select id="strata_id" name="strata_id" class="form-control select3"
                                                    data-placeholder="{{ trans('app.forms.please_select') }}"
                                                    data-ajax--url="{{ route('v3.api.strata.getOption', ['type' => 'id', 'company_id' => Company::where('short_name', 'MPS')->first()->id]) }}"
                                                    data-ajax--cache="true">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.applicant_name') }}
                                                </label>
                                                <input type="text" class="form-control" id="applicant_name"
                                                    name="applicant_name" value="{{ Input::old('applicant_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.phone_no') }}
                                                </label>
                                                <input type="tel" class="form-control" id="applicant_phone"
                                                    name="applicant_phone" value="{{ Input::old('applicant_phone') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.email') }}
                                                </label>
                                                <input type="email" class="form-control" id="applicant_email"
                                                    name="applicant_email" value="{{ Input::old('applicant_email') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.jmb_mc_name') }}
                                                </label>
                                                <input type="text" class="form-control" id="organization_name"
                                                    name="organization_name"
                                                    value="{{ Input::old('organization_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.position') }}
                                                </label>
                                                <input type="text" class="form-control" id="applicant_position"
                                                    name="applicant_position"
                                                    value="{{ Input::old('applicant_position') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.address1') }}
                                                </label>
                                                <input type="text" class="form-control" id="organization_address_1"
                                                    name="organization_address_1"
                                                    value="{{ Input::old('organization_address_1') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    {{ trans('app.forms.tppm.address2') }}
                                                </label>
                                                <input type="text" class="form-control" id="organization_address_2"
                                                    name="organization_address_2"
                                                    value="{{ Input::old('organization_address_2') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    {{ trans('app.forms.tppm.address3') }}
                                                </label>
                                                <input type="text" class="form-control" id="organization_address_3"
                                                    name="organization_address_3"
                                                    value="{{ Input::old('organization_address_3') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.parliament') }}
                                                </label>
                                                <select id="parliament_id" name="parliament_id"
                                                    class="form-control select3"
                                                    data-placeholder="{{ trans('app.forms.please_select') }}"
                                                    data-ajax--url="{{ route('v3.api.parliment.getOption') }}"
                                                    data-ajax--cache="true">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.dun') }}
                                                </label>
                                                <select id="dun_id" name="dun_id" class="form-control select3"
                                                    data-placeholder="{{ trans('app.forms.please_select') }}"
                                                    data-ajax--url="{{ route('v3.api.dun.getOption') }}"
                                                    data-ajax--cache="true">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.district') }}
                                                </label>
                                                <select id="district_id" name="district_id" class="form-control select3"
                                                    data-placeholder="{{ trans('app.forms.please_select') }}"
                                                    data-ajax--url="{{ route('v3.api.area.getOption') }}"
                                                    data-ajax--cache="true">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.first_purchase_price') }}
                                                    ({{ trans('app.forms.tppm.first_purchase_price_label') }})
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">RM</span>
                                                    <input type="number" class="form-control" id="first_purchase_price"
                                                        name="first_purchase_price"
                                                        value="{{ Input::old('first_purchase_price') }}">
                                                </div>
                                                <div class="help-block" style="font-style: italic;">
                                                    [{{ trans('app.forms.tppm.first_purchase_price_note') }}]
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.year_built') }}
                                                </label>
                                                <input type="number" class="form-control" id="year_built"
                                                    name="year_built" value="{{ Input::old('year_built') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.year_occupied') }}
                                                </label>
                                                <input type="number" class="form-control" id="year_occupied"
                                                    name="year_occupied" value="{{ Input::old('year_occupied') }}">
                                                <div class="help-block" style="font-style: italic;">
                                                    [{{ trans('app.forms.tppm.year_occupied_note') }}]</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="unit-count-group">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.block_count') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_blocks"
                                                        value="{{ Input::old('num_blocks') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.unit_count') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_units"
                                                        value="{{ Input::old('num_units') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.units_occupied') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_units_occupied"
                                                        value="{{ Input::old('num_units_occupied') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.units_owner') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_units_owner"
                                                        value="{{ Input::old('num_units_owner') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.units_malaysian') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_units_malaysian"
                                                        value="{{ Input::old('num_units_malaysian') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.storey_count') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_storeys"
                                                        value="{{ Input::old('num_storeys') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.resident_count') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_residents"
                                                        value="{{ Input::old('num_residents') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.units_vacant') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_units_vacant"
                                                        value="{{ Input::old('num_units_vacant') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.units_tenant') }}
                                                    </label>
                                                    <input type="number" class="form-control" name="num_units_tenant"
                                                        value="{{ Input::old('num_units_tenant') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.units_non_malaysian') }}
                                                    </label>
                                                    <input type="number" class="form-control"
                                                        name="num_units_non_malaysian"
                                                        value="{{ Input::old('num_units_non_malaysian') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.block_name') }}
                                                </label>
                                                <input type="text" class="form-control" name="requested_block_name"
                                                    value="{{ Input::old('requested_block_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <span style="color: red;">*</span>&nbsp;
                                                    {{ trans('app.forms.tppm.block_no') }}
                                                </label>
                                                <input type="number" class="form-control" name="requested_block_no"
                                                    value="{{ Input::old('requested_block_no') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h5 class="text-bold">
                                        B. {{ trans('app.forms.tppm.scope_of_works') }}
                                    </h5>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered scope-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>A</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="lift_avas">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>B</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="water_tank">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>C</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="sanitary_pipe">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>D</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="roof">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>E</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="stair_handrail">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>F</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="painting">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>G</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="electrical">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>H</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="public_infrastructure">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>I</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="fence">
                                                                </div>
                                                            </th>
                                                            <th style="width: 10%; text-align: center;">
                                                                <div class="header-checkbox">
                                                                    <span>J</span>
                                                                    <input type="checkbox" class="scope-item"
                                                                        value="slope">
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.lif_avas') }}</th>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.water_tank') }}</th>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.sanitary_pipe') }}</th>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.roof') }}</th>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.stair_handrail') }}</th>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.painting') }}</th>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.electrical') }}</th>
                                                            <th class="header-center">
                                                                {{ trans('app.forms.tppm.public_infrastructure') }}</th>
                                                            <th class="header-center">{{ trans('app.forms.tppm.fence') }}
                                                            </th>
                                                            <th class="header-center">{{ trans('app.forms.tppm.slope') }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <label
                                                                    style="font-size: 11px;">{{ trans('app.forms.tppm.lift_count') }}:</label><br>
                                                                <input type="text" class="form-control input-sm"
                                                                    name="lift_count"
                                                                    style="width: 60px; margin: 0 auto;"><br>
                                                                <div class="radio-options">
                                                                    <label>
                                                                        <input type="radio" name="lift_type"
                                                                            value="repair">
                                                                        {{ trans('app.forms.tppm.repair') }}
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="lift_type"
                                                                            value="replace">
                                                                        {{ trans('app.forms.tppm.replace') }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <label
                                                                    style="font-size: 11px;">{{ trans('app.forms.tppm.water_tank_count') }}:</label><br>
                                                                <input type="text" class="form-control input-sm"
                                                                    name="water_tank_count"
                                                                    style="width: 60px; margin: 0 auto;"><br>
                                                                <div class="radio-options">
                                                                    <label>
                                                                        <input type="radio" name="water_tank_type"
                                                                            value="repair">
                                                                        {{ trans('app.forms.tppm.repair') }}
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="water_tank_type"
                                                                            value="replace">
                                                                        {{ trans('app.forms.tppm.replace') }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <div class="radio-options">
                                                                    <label>
                                                                        <input type="radio" name="sanitary_pipe_type"
                                                                            value="repair">
                                                                        {{ trans('app.forms.tppm.repair') }}
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="sanitary_pipe_type"
                                                                            value="replace">
                                                                        {{ trans('app.forms.tppm.replace') }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <div class="radio-options">
                                                                    <label>
                                                                        <input type="radio" name="roof_type"
                                                                            value="repair">
                                                                        {{ trans('app.forms.tppm.repair') }}
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="roof_type"
                                                                            value="replace">
                                                                        {{ trans('app.forms.tppm.replace') }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <div class="radio-options">
                                                                    <label>
                                                                        <input type="radio" name="stair_handrail_type"
                                                                            value="repair">
                                                                        {{ trans('app.forms.tppm.repair') }}
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="stair_handrail_type"
                                                                            value="replace">
                                                                        {{ trans('app.forms.tppm.replace') }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <div class="field-group">
                                                                    <div class="field-item">
                                                                        <label>i)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="painting_i">
                                                                    </div>
                                                                    <div class="field-item">
                                                                        <label>ii)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="painting_ii">
                                                                    </div>
                                                                    <div class="field-item">
                                                                        <label>iii)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="painting_iii">
                                                                    </div>
                                                                    <div class="field-item">
                                                                        <label>iv)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="painting_iv">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <div class="radio-options">
                                                                    <label>
                                                                        <input type="radio" name="electrical_type"
                                                                            value="repair">
                                                                        {{ trans('app.forms.tppm.repair') }}
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="electrical_type"
                                                                            value="replace">
                                                                        {{ trans('app.forms.tppm.replace') }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <div class="field-group">
                                                                    <div class="field-item">
                                                                        <label>i)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="public_infrastructure_i">
                                                                    </div>
                                                                    <div class="field-item">
                                                                        <label>ii)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="public_infrastructure_ii">
                                                                    </div>
                                                                    <div class="field-item">
                                                                        <label>iii)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="public_infrastructure_iii">
                                                                    </div>
                                                                    <div class="field-item">
                                                                        <label>iv)</label>
                                                                        <input type="text"
                                                                            class="form-control input-sm"
                                                                            name="public_infrastructure_iv">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <div class="radio-options">
                                                                    <label>
                                                                        <input type="radio" name="fence_type"
                                                                            value="repair">
                                                                        {{ trans('app.forms.tppm.repair') }}
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="fence_type"
                                                                            value="replace">
                                                                        {{ trans('app.forms.tppm.replace') }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: top;">
                                                                <label
                                                                    style="font-size: 11px;">{{ trans('app.forms.tppm.repair') }}</label>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="attention-notice arahan-notice" style="margin-top: 15px;">
                                                <ul class="attention-text" style="margin-left: -15px;">
                                                    <li>{{ trans('app.forms.tppm.instruction_1') }}</li>
                                                    <li>{{ trans('app.forms.tppm.instruction_2', ['repair' => trans('app.forms.tppm.repair'), 'replace' => trans('app.forms.tppm.replace')]) }}
                                                    </li>
                                                    <li>{{ trans('app.forms.tppm.instruction_3') }}</li>
                                                    <li>{{ trans('app.forms.tppm.instruction_4') }}</li>
                                                </ul>
                                            </div>

                                            <input type="hidden" id="scope" name="scope"
                                                value="{{ Input::old('scope') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h5 class="text-bold">
                                        C. {{ trans('app.forms.tppm.applicant_checklist') }}
                                    </h5>

                                    <div class="checklist-section">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checklist-item">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.spa_copy') }}
                                                    </label>
                                                    <input type="file" class="form-control-file" name="spa_copy_file"
                                                        id="spa_copy_file" onChange="onUploadChecklist(this, 'spa_copy')">
                                                    <div class="help-block" style="font-style: italic;">
                                                        [{{ trans('app.forms.tppm.spa_copy_note') }}]</div>
                                                    <input type="hidden" id="spa_copy" name="spa_copy"
                                                        value="{{ Input::old('spa_copy') }}">
                                                    <div id="spa_copy_feedback" class="help-block small"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checklist-item">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.detail_report') }} (Berwarna)
                                                    </label>
                                                    <input type="file" class="form-control-file"
                                                        name="detail_report_file" id="detail_report_file"
                                                        onChange="onUploadChecklist(this, 'detail_report')">
                                                    <div class="help-block" style="font-style: italic;">
                                                        [{{ trans('app.forms.tppm.detail_report_note') }}]</div>
                                                    <input type="hidden" id="detail_report" name="detail_report"
                                                        value="{{ Input::old('detail_report') }}">
                                                    <div id="detail_report_feedback" class="help-block small"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checklist-item">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.meeting_minutes') }}
                                                    </label>
                                                    <input type="file" class="form-control-file"
                                                        name="meeting_minutes_file" id="meeting_minutes_file"
                                                        onChange="onUploadChecklist(this, 'meeting_minutes')">
                                                    <div class="help-block" style="font-style: italic;">
                                                        [{{ trans('app.forms.tppm.meeting_minutes_note') }}]</div>
                                                    <input type="hidden" id="meeting_minutes" name="meeting_minutes"
                                                        value="{{ Input::old('meeting_minutes') }}">
                                                    <div id="meeting_minutes_feedback" class="help-block small"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checklist-item">
                                                    <label class="form-control-label">
                                                        <span style="color: red;">*</span>&nbsp;
                                                        {{ trans('app.forms.tppm.cost_estimate') }}
                                                    </label>
                                                    <input type="file" class="form-control-file"
                                                        name="cost_estimate_file" id="cost_estimate_file"
                                                        onChange="onUploadChecklist(this, 'cost_estimate')">
                                                    <div class="help-block" style="font-style: italic;">
                                                        [{{ trans('app.forms.tppm.cost_estimate_note') }}]</div>
                                                    <input type="hidden" id="cost_estimate" name="cost_estimate"
                                                        value="{{ Input::old('cost_estimate') }}">
                                                    <div id="cost_estimate_feedback" class="help-block small"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (AccessGroup::hasUpdateModule('TPPM'))
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    {{ trans('app.forms.tppm.created_at') }}
                                                </label>
                                                <input type="text" class="form-control datetimepicker"
                                                    id="created_at_raw"
                                                    value="{{ Input::old('created_at') ? date('d-m-Y', strtotime(Input::old('created_at'))) : date('d-m-Y') }}"
                                                    placeholder="dd-mm-yyyy">
                                                <input type="hidden" name="created_at" id="created_at"
                                                    value="{{ Input::old('created_at') ?: date('Y-m-d') }}">
                                                <div id="created_at_feedback" class="invalid-feedback"
                                                    style="display:none;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-own" id="submit_button">
                                        {{ trans('app.forms.save') }}
                                    </button>
                                    <button type="button" class="btn btn-default" id="cancel_button"
                                        onclick="window.location ='{{ route('tppm.index') }}'">
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
        $(function() {
            // ========================================
            // GENERAL INITIALIZATION
            // ========================================
            $('.select3').select2();

            // Initialize created_at datepicker and sync hidden field
            if ($.fn.datetimepicker && $('#created_at_raw').length) {
                $('#created_at_raw').datetimepicker({
                    format: 'DD-MM-YYYY',
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down",
                        previous: "fa fa-chevron-left",
                        next: "fa fa-chevron-right"
                    }
                }).on('dp.change', function() {
                    var currentDate = $(this).val().split('-');
                    if (currentDate.length === 3) {
                        $('#created_at').val(currentDate[2] + '-' + currentDate[1] + '-' + currentDate[0]);
                    }
                });

                // Set default to today if empty
                if (!$('#created_at').val()) {
                    if (typeof moment !== 'undefined') {
                        var todayRaw = moment().format('DD-MM-YYYY');
                        var todayIso = moment().format('YYYY-MM-DD');
                        $('#created_at_raw').val(todayRaw);
                        $('#created_at').val(todayIso);
                    } else {
                        var d = new Date();
                        var dd = ('0' + d.getDate()).slice(-2);
                        var mm = ('0' + (d.getMonth() + 1)).slice(-2);
                        var yyyy = d.getFullYear();
                        $('#created_at_raw').val(dd + '-' + mm + '-' + yyyy);
                        $('#created_at').val(yyyy + '-' + mm + '-' + dd);
                    }
                }
            }

            // ========================================
            // VALIDATION FUNCTIONS
            // ========================================
            function displayValidationErrors(errors) {
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        // Special handling for cost_category and scope
                        if (field === 'cost_category') {
                            $('.category-radio-group').addClass('is-invalid');
                            if (!$('.category-radio-group').next('.invalid-feedback').length) {
                                $('.category-radio-group').after('<div class="invalid-feedback">' + errors[field][
                                    0
                                ] + '</div>');
                            }
                            continue;
                        }
                        if (field === 'scope') {
                            $('.scope-table').addClass('is-invalid');
                            if (!$('.scope-table').next('.invalid-feedback').length) {
                                $('.scope-table').after('<div class="invalid-feedback">' + errors[field][0] +
                                    '</div>');
                            }
                            continue;
                        }

                        // Handle scope-specific field errors
                        if (['lift_count', 'water_tank_count'].includes(field)) {
                            let $field = $(`input[name="${field}"]`);
                            if ($field.length) {
                                $field.addClass('is-invalid');
                                if (!$field.next('.invalid-feedback').length) {
                                    $field.after('<div class="invalid-feedback">' + errors[field][0] + '</div>');
                                }
                            }
                            continue;
                        }

                        // Handle radio button group errors
                        if (['lift_type', 'water_tank_type', 'sanitary_pipe_type', 'roof_type',
                                'stair_handrail_type', 'electrical_type', 'fence_type'
                            ].includes(field)) {
                            let $field = $(`input[name="${field}"]`);
                            if ($field.length) {
                                // Find the parent radio-options container
                                let $radioGroup = $field.closest('.radio-options');
                                if ($radioGroup.length) {
                                    $radioGroup.addClass('is-invalid');
                                    if (!$radioGroup.next('.invalid-feedback').length) {
                                        $radioGroup.after(
                                            '<div class="invalid-feedback" style="white-space: normal; word-wrap: break-word;">' +
                                            errors[field][0] + '</div>');
                                    }
                                } else {
                                    // Fallback to individual field
                                    $field.addClass('is-invalid');
                                    if (!$field.next('.invalid-feedback').length) {
                                        $field.after(
                                            '<div class="invalid-feedback" style="white-space: normal; word-wrap: break-word;">' +
                                            errors[field][0] + '</div>');
                                    }
                                }
                            }
                            continue;
                        }

                        // Handle painting, public_infrastructure "at least one" validation
                        if (['painting_i', 'public_infrastructure_i'].includes(field)) {
                            let $field = $(`input[name="${field}"]`);
                            if ($field.length) {
                                // Find the parent field-group container
                                let $fieldGroup = $field.closest('.field-group');
                                if ($fieldGroup.length) {
                                    $fieldGroup.addClass('is-invalid');
                                    if (!$fieldGroup.next('.invalid-feedback').length) {
                                        $fieldGroup.after('<div class="invalid-feedback">' + errors[field][0] +
                                            '</div>');
                                    }
                                } else {
                                    // Fallback to individual field
                                    $field.addClass('is-invalid');
                                    if (!$field.next('.invalid-feedback').length) {
                                        $field.after('<div class="invalid-feedback">' + errors[field][0] +
                                            '</div>');
                                    }
                                }
                            }
                            continue;
                        }

                        // Handle regular fields
                        let fieldElement = $('[name="' + field + '"]');
                        if (fieldElement.length) {
                            fieldElement.addClass('is-invalid');

                            // Special handling for Select2 fields
                            if (fieldElement.hasClass('select3')) {
                                let select2Container = fieldElement.next('.select2-container');
                                if (select2Container.length) {
                                    select2Container.find('.select2-selection--single').addClass('is-invalid');
                                }
                            }

                            // Special handling for file upload fields (checklist items)
                            if (fieldElement.closest('.checklist-item').length) {
                                let checklistItem = fieldElement.closest('.checklist-item');
                                checklistItem.find('.form-control-file').addClass('is-invalid');
                                if (!checklistItem.find('.invalid-feedback').length) {
                                    checklistItem.append('<div class="invalid-feedback">' + errors[field][0] +
                                        '</div>');
                                }
                            } else {
                                // Find the parent form-group and add error message there
                                let formGroup = fieldElement.closest('.form-group');
                                if (formGroup.length && !formGroup.find('.invalid-feedback').length) {
                                    formGroup.append('<div class="invalid-feedback">' + errors[field][0] +
                                        '</div>');
                                }
                            }
                        }
                    }
                }
            }

            // ========================================
            // BAHAGIAN A - APPLICATION DETAILS
            // ========================================
            // Handle radio button changes for category
            $(document).on('change', 'input[name="cost_category"]', function() {
                // Add any specific logic for category change if needed
                console.log('Category changed to:', $(this).val());
            });

            // ========================================
            // BAHAGIAN B - SCOPE OF WORKS
            // ========================================
            function refreshScope() {
                let scopeData = {};

                // Collect checked items
                let checkedItems = [];
                $('.scope-item:checked').each(function() {
                    checkedItems.push($(this).val());
                });
                scopeData.items = checkedItems;

                // Collect additional data for specific items
                if (checkedItems.includes('lift_avas')) {
                    scopeData.lift_count = $('input[name="lift_count"]').val();
                    scopeData.lift_type = $('input[name="lift_type"]:checked').val();
                }
                if (checkedItems.includes('water_tank')) {
                    scopeData.water_tank_count = $('input[name="water_tank_count"]').val();
                    scopeData.water_tank_type = $('input[name="water_tank_type"]:checked').val();
                }
                if (checkedItems.includes('sanitary_pipe')) {
                    scopeData.sanitary_pipe_type = $('input[name="sanitary_pipe_type"]:checked').val();
                }
                if (checkedItems.includes('roof')) {
                    scopeData.roof_type = $('input[name="roof_type"]:checked').val();
                }
                if (checkedItems.includes('stair_handrail')) {
                    scopeData.stair_handrail_type = $('input[name="stair_handrail_type"]:checked').val();
                }
                if (checkedItems.includes('fence')) {
                    scopeData.fence_type = $('input[name="fence_type"]:checked').val();
                }
                if (checkedItems.includes('painting')) {
                    scopeData.painting = {
                        i: $('input[name="painting_i"]').val(),
                        ii: $('input[name="painting_ii"]').val(),
                        iii: $('input[name="painting_iii"]').val(),
                        iv: $('input[name="painting_iv"]').val()
                    };
                }
                if (checkedItems.includes('electrical')) {
                    scopeData.electrical_type = $('input[name="electrical_type"]:checked').val();
                }
                if (checkedItems.includes('public_infrastructure')) {
                    scopeData.public_infrastructure = {
                        i: $('input[name="public_infrastructure_i"]').val(),
                        ii: $('input[name="public_infrastructure_ii"]').val(),
                        iii: $('input[name="public_infrastructure_iii"]').val(),
                        iv: $('input[name="public_infrastructure_iv"]').val()
                    };
                }

                $('#scope').val(JSON.stringify(scopeData));
            }

            // Event listeners for Bahagian B
            $(document).on('change', '.scope-item', refreshScope);
            $(document).on('change', 'input[name$="_type"]', refreshScope);
            $(document).on('input',
                'input[name="lift_count"], input[name="water_tank_count"], input[name^="painting_"], input[name^="electrical_"], input[name^="public_infrastructure_"]',
                refreshScope);
            refreshScope();

            // ========================================
            // BAHAGIAN C - APPLICANT CHECKLIST
            // ========================================
            window.onUploadChecklist = function(input, field) {
                let data = new FormData();
                if (input.files.length > 0) {
                    data.append(input.id, input.files[0]);
                    // Add has-file class to parent
                    $(input).closest('.form-control-file').addClass('has-file');
                }
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: "{{ route('tppm.fileUpload') }}",
                    data: data,
                    async: true,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response.error == true) {
                            $('#' + field + '_feedback').html('<span class="text-danger">' + (
                                response.message || 'Upload failed') + '</span>');
                            // Remove has-file class on error
                            $(input).closest('.form-control-file').removeClass('has-file');
                        } else {
                            $('#' + field).val(response.file);
                            $('#' + field + '_feedback').html(
                                '<span class="text-success"><i class="fa fa-check"></i> ' +
                                response.filename + '</span>');
                        }
                    },
                    error: function() {
                        // Remove has-file class on error
                        $(input).closest('.form-control-file').removeClass('has-file');
                    }
                });
            }

            // Handle file input change to add has-file class
            $(document).on('change', 'input[type="file"]', function() {
                if (this.files.length > 0) {
                    $(this).closest('.form-control-file').addClass('has-file');
                } else {
                    $(this).closest('.form-control-file').removeClass('has-file');
                }
            });

            // ========================================
            // HELPER FUNCTIONS
            // ========================================
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Clear validation errors when user starts typing
            $(document).on('input change', '.form-control, input[type="radio"], input[type="checkbox"]',
                function() {
                    // Clear individual field errors
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();

                    // Clear Select2 validation
                    if ($(this).hasClass('select3')) {
                        let select2Container = $(this).next('.select2-container');
                        if (select2Container.length) {
                            select2Container.find('.select2-selection--single').removeClass('is-invalid');
                        }
                    }

                    // Clear special validation classes
                    if ($(this).attr('name') === 'cost_category') {
                        $('.category-radio-group').removeClass('is-invalid');
                        $('.category-radio-group').next('.invalid-feedback').remove();
                    }
                    if ($(this).hasClass('scope-item')) {
                        $('.scope-table').removeClass('is-invalid');
                        $('.scope-table').next('.invalid-feedback').remove();
                    }
                });

            // Clear validation errors for scope fields specifically
            $(document).on('input change',
                'input[name^="painting_"], input[name^="electrical_"], input[name^="public_infrastructure_"], input[name="lift_count"], input[name="water_tank_count"]',
                function() {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();

                    // Clear field-group validation for painting, electrical, public_infrastructure
                    let $fieldGroup = $(this).closest('.field-group');
                    if ($fieldGroup.length) {
                        $fieldGroup.removeClass('is-invalid');
                        $fieldGroup.next('.invalid-feedback').remove();
                    }
                });

            // Clear validation errors for scope radio buttons
            $(document).on('change', 'input[name$="_type"]',
                function() {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();

                    // Clear radio group validation
                    let $radioGroup = $(this).closest('.radio-options');
                    if ($radioGroup.length) {
                        $radioGroup.removeClass('is-invalid');
                        $radioGroup.next('.invalid-feedback').remove();
                    }
                });

            // Clear validation errors for scope checkboxes
            $(document).on('change', '.scope-item',
                function() {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                    // Also clear scope table validation
                    $('.scope-table').removeClass('is-invalid');
                    $('.scope-table').next('.invalid-feedback').remove();
                });

            // Function to clear all validation errors
            function clearAllValidationErrors() {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                $('.category-radio-group').removeClass('is-invalid');
                $('.scope-table').removeClass('is-invalid');
                $('.field-group').removeClass('is-invalid');
                $('.radio-options').removeClass('is-invalid');
                $('.select2-selection--single').removeClass('is-invalid');
            }

            // ========================================
            // FORM SUBMISSION
            // ========================================
            $("#submit_button").click(function(e) {
                e.preventDefault();

                // Clear previous validation errors
                clearAllValidationErrors();

                $.blockUI({
                    message: '{{ trans('app.confirmation.please_wait') }}'
                });

                refreshScope();
                let formData = $('form').serialize();
                $.ajax({
                    url: "{{ route('tppm.store') }}",
                    type: "POST",
                    data: formData,
                    dataType: 'JSON',
                    beforeSend: function(xhr, settings) {
                        $("#loading").css("display", "inline-block");
                        $("#submit_button").attr("disabled", "disabled");
                        $("#cancel_button").attr("disabled", "disabled");
                    },
                    success: function(res) {
                        if (res.success == true) {
                            // Clear all validation errors on success
                            clearAllValidationErrors();

                            bootbox.alert(
                                "<span style='color:green;'>{{ trans('app.successes.saved_successfully') }}</span>",
                                function() {
                                    let url = "{{ route('tppm.index') }}";
                                    window.location = url;
                                });
                        } else if (res.error == true) {
                            // Handle validation errors
                            if (res.errors) {
                                displayValidationErrors(res.errors);

                                // Scroll to first error field
                                let firstErrorField = $('.is-invalid').first();
                                if (firstErrorField.length) {
                                    $('html, body').animate({
                                        scrollTop: firstErrorField.offset().top - 100
                                    }, 500);
                                    firstErrorField.focus();
                                }
                            } else {
                                // Display general error message
                                bootbox.alert("<span style='color:red;'>" + (res.message ||
                                    '{{ trans('app.errors.occurred') }}') + "</span>");
                            }
                        } else {
                            bootbox.alert("<span style='color:red;'>" + (res.message ||
                                '{{ trans('app.errors.occurred') }}') + "</span>");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', xhr, status, error); // Debug logging
                        console.log('Response:', xhr.responseJSON); // Debug logging

                        $.unblockUI();
                        $("#loading").css("display", "none");
                        $("#submit_button").removeAttr("disabled");
                        $("#cancel_button").removeAttr("disabled");

                        let errorMessage = 'An error occurred while submitting the form.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 422) {
                            errorMessage = 'Validation failed. Please check your input.';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Server error. Please try again later.';
                        }

                        bootbox.alert("<span style='color:red;'>" + errorMessage + "</span>");
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
@endsection
