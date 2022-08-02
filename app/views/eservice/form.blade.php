@if(!empty($model))
@if(count($attributes) > 0)
@foreach ($attributes as $attribute)
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label class="form-control-label">
                @if ($fields[$attribute]['required'])
                <span style="color: red;">*</span>
                @endif
                {{ $fields[$attribute]['label'] }}
            </label>
            @if ($fields[$attribute]['type'] == 'textarea')
            <textarea type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control" rows="4">{{ $model->$fields[$attribute]['name'] }}</textarea>
            @elseif ($fields[$attribute]['type'] == 'date')
            <label class="input-group">
                <input type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                    class="form-control date_picker" value="{{ $model->$fields[$attribute]['name'] }}" />
                <span class="input-group-addon">
                    <i class="icmn-calendar"></i>
                </span>
            </label>
            @elseif ($fields[$attribute]['type'] == 'file')
            <br />
            <input type="file" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                value="{{ $model->$fields[$attribute]['name'] }}" />
            @else
            <input type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control" value="{{ $model->$fields[$attribute]['name'] }}" />
            @endif
            @include('alert.feedback-ajax', ['field' => $fields[$attribute]['name']])
        </div>
    </div>
</div>
@endforeach
@endif
@else
@if(count($attributes) > 0)
@foreach ($attributes as $attribute)
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label class="form-control-label">
                @if ($fields[$attribute]['required'])
                <span style="color: red;">*</span>
                @endif
                {{ $fields[$attribute]['label'] }}
            </label>
            @if ($fields[$attribute]['type'] == 'textarea')
            <textarea type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control" rows="4"></textarea>
            @elseif ($fields[$attribute]['type'] == 'date')
            <label class="input-group">
                <input type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                    class="form-control date_picker" value="" />
                <span class="input-group-addon">
                    <i class="icmn-calendar"></i>
                </span>
            </label>
            @elseif ($fields[$attribute]['type'] == 'file')
            <br />
            <input type="file" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                value="" />
            @else
            <input type="text" id="{{ $fields[$attribute]['name'] }}" name="{{ $fields[$attribute]['name'] }}"
                class="form-control" value="" />
            @endif
            @include('alert.feedback-ajax', ['field' => $fields[$attribute]['name']])
        </div>
    </div>
</div>
@endforeach
@endif
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