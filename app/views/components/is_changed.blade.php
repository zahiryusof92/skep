@if($old_field != $new_field)
<span class="label label-danger margin-left-5 @if(!empty($class)){{$class}}@endif">@if(!empty($text)){{$text}}@endif {{ trans('app.forms.have_changed') }}</span>
@endif