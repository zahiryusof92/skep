@if (count($attributes) > 0)
@foreach ($attributes as $attribute)
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            @if (isset($fields[$attribute]['label']) && !empty($fields[$attribute]['label']))
            <label class="form-control-label">
                {{ $fields[$attribute]['label'] }}
            </label>
            @endif
            
            @if ($fields[$attribute]['type'] == 'textarea')
            <textarea type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control" rows="5"
                readonly>{{ (isset($model[$fields[$attribute]['name']]) ? $model[$fields[$attribute]['name']] : '') }}</textarea>
            @elseif ($fields[$attribute]['type'] == 'radio')
            <br />
            <div class="radio">
                @if ($fields[$attribute]['options'])
                @foreach ($fields[$attribute]['options'] as $value => $name)
                <label>
                    <input type="radio" name="{{ $fields[$attribute]['name'] }}" value="{{ $value }}" {{
                        (isset($model[$fields[$attribute]['name']]) ? ($model[$fields[$attribute]['name']]==$value
                        ? 'checked' : '' ) : '' ) }} disabled />
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
                    value="{{ (isset($model[$fields[$attribute]['name']]) ? $model[$fields[$attribute]['name']] : '') }}"
                    readonly />
                <span class="input-group-addon">
                    <i class="icmn-calendar"></i>
                </span>
            </label>
            @elseif ($fields[$attribute]['type'] == 'file')
            @if (isset($model[$fields[$attribute]['name']]) && !empty($model[$fields[$attribute]['name']]))
            <br />
            <a href="{{ asset($model[$fields[$attribute]['name']]) }}" target="_blank">
                <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom"
                    title="Download File">
                    <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                </button>
            </a>
            @endif
            @else
            <input type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control"
                value="{{ (isset($model[$fields[$attribute]['name']]) ? $model[$fields[$attribute]['name']] : '') }}"
                readonly />
            @endif
        </div>
    </div>
</div>
@endforeach
@endif