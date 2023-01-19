@if (count($attributes) > 0)
@foreach ($attributes as $attribute)
@if (isset($management) && !empty($management))
<?php $preset_val = ''; ?>
@if ($fields[$attribute]['name'] == 'building_name')
<?php $preset_val = $management['strata']; ?>
@elseif ($fields[$attribute]['name'] == 'management_name')
<?php $preset_val = $management['name']; ?>
@elseif ($fields[$attribute]['name'] == 'management_address1')
<?php $preset_val = $management['address1']; ?>
@elseif ($fields[$attribute]['name'] == 'management_address2')
<?php $preset_val = $management['address2']; ?>
@elseif ($fields[$attribute]['name'] == 'management_address3')
<?php $preset_val = $management['address3']; ?>
@elseif ($fields[$attribute]['name'] == 'management_postcode')
<?php $preset_val = $management['postcode']; ?>
@elseif ($fields[$attribute]['name'] == 'management_city')
<?php $preset_val = $management['city']; ?>
@elseif ($fields[$attribute]['name'] == 'management_state')
<?php $preset_val = $management['state']; ?>
@elseif ($fields[$attribute]['name'] == 'management_phone')
<?php $preset_val = $management['phone_no']; ?>
@endif
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">

            @if (isset($fields[$attribute]['label']) && !empty($fields[$attribute]['label']))
            <label class="form-control-label">
                @if ($fields[$attribute]['required'])
                <span style="color: red;">*</span>
                @endif
                {{ $fields[$attribute]['label'] }}
            </label>
            @endif

            @if ($fields[$attribute]['type'] == 'textarea')
            <textarea type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control"
                rows="5">{{ !empty(Input::old($fields[$attribute]['name'])) ? Input::old($fields[$attribute]['name']) : (isset($model[$fields[$attribute]['name']]) ? $model[$fields[$attribute]['name']] : '') }}</textarea>
            @elseif ($fields[$attribute]['type'] == 'radio')
            <br />
            <div class="radio">
                @if ($fields[$attribute]['options'])
                @foreach ($fields[$attribute]['options'] as $value => $name)
                <label>
                    <input type="radio" name="{{ $fields[$attribute]['name'] }}" value="{{ $value }}" {{
                        Input::old($fields[$attribute]['name'])==$value ? 'checked' :
                        (isset($model[$fields[$attribute]['name']]) ? ($model[$fields[$attribute]['name']]==$value
                        ? 'checked' : '' ) : '' ) }} />
                    {{ $name }}
                </label>
                <br />
                @endforeach
                @endif
            </div>
            <br />
            @elseif ($fields[$attribute]['type'] == 'date')
            <label class="input-group">
                <input type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                    class="form-control date_picker"
                    value="{{ !empty(Input::old($fields[$attribute]['name'])) ? Input::old($fields[$attribute]['name']) : (isset($model[$fields[$attribute]['name']]) ? $model[$fields[$attribute]['name']] : '') }}" />
                <span class="input-group-addon">
                    <i class="icmn-calendar"></i>
                </span>
            </label>
            @elseif ($fields[$attribute]['type'] == 'file')
            <br />
            <input type="file" id="{{ $fields[$attribute]['name'] }}_tmp" name="{{ $fields[$attribute]['name'] }}_tmp"
                onChange="onUpload(this)" />
            <input hidden id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                value="{{ (isset($model[$fields[$attribute]['name']]) ? $model[$fields[$attribute]['name']] : '') }}">
            <br />
            <div id="{{ $fields[$attribute]['name'] }}_preview">
                @if (isset($model[$fields[$attribute]['name']]) && !empty($model[$fields[$attribute]['name']]))
                <a href="{{ asset($model[$fields[$attribute]['name']]) }}" target="_blank">
                    <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom"
                        title="Download File">
                        <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                    </button>
                </a>
                @endif
            </div>
            @else
            <input type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control"
                value="{{ !empty(Input::old($fields[$attribute]['name'])) ? Input::old($fields[$attribute]['name']) : (isset($model[$fields[$attribute]['name']]) ? $model[$fields[$attribute]['name']] : (isset($preset_val) ? $preset_val : '')) }}" />
            @endif
            @include('alert.feedback-ajax', ['field' => $fields[$attribute]['name']])
        </div>
    </div>
</div>
@endforeach
@endif

<script>
    $(function () {
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
    });
</script>