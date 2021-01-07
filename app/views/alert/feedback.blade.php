@if ($errors->has($field))
<span class="help-block text-danger">{{ $errors->first($field) }}</span>
@endif