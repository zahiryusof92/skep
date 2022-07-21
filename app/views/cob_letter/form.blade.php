@if(!empty($model))
    @if(count($attributes) > 0)
        @foreach ($attributes as $attribute)
            <div class="form-group row">
                <div class="col-md-5">
                    <label class="form-control-label">@if($fields[$attribute]['required'])<span style="color: red;">*</span>@endif {{ $fields[$attribute]['label'] }}</label>
                </div>
                <div class="col-md-7">
                    @if($fields[$attribute]['type'] == 'textarea')
                    <textarea type="text" id="{{$fields[$attribute]['name']}}" name="{{$fields[$attribute]['name']}}" class="form-control" rows="3">{{ $model->$fields[$attribute]['name'] }}</textarea>
                    @elseif($fields[$attribute]['type'] == 'date')
                    <input type="date" id="{{$fields[$attribute]['name']}}" name="{{$fields[$attribute]['name']}}" class="form-control" value="{{ $model->date }}">
                    @else
                    <input type="text" id="{{$fields[$attribute]['name']}}" name="{{$fields[$attribute]['name']}}" class="form-control" value="{{ $model->$fields[$attribute]['name'] }}">
                    @endif
                    @include('alert.feedback-ajax', ['field' => $fields[$attribute]['name']])
                </div>
            </div>
        @endforeach
    @endif
@else
    @if(count($attributes) > 0)
        @foreach ($attributes as $attribute)
            <div class="form-group row">
                <div class="col-md-5">
                    <label class="form-control-label">@if($fields[$attribute]['required'])<span style="color: red;">*</span>@endif {{ $fields[$attribute]['label'] }}</label>
                </div>
                <div class="col-md-7">
                    @if($fields[$attribute]['type'] == 'textarea')
                    <textarea type="text" id="{{$fields[$attribute]['name']}}" name="{{$fields[$attribute]['name']}}" class="form-control" rows="3"></textarea>
                    @elseif($fields[$attribute]['type'] == 'date')
                    <input type="date" id="{{$fields[$attribute]['name']}}" name="{{$fields[$attribute]['name']}}" class="form-control" value="">
                    @else
                    <input type="text" id="{{$fields[$attribute]['name']}}" name="{{$fields[$attribute]['name']}}" class="form-control" value="">
                    @endif
                    @include('alert.feedback-ajax', ['field' => $fields[$attribute]['name']])
                </div>
            </div>
        @endforeach
    @endif
@endif