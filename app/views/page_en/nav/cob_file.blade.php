
<ul class="nav nav-pills nav-justified" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/house/*')) active @endif custom-tab" @if(!Request::is('update/house/*')) href="{{URL::action('AdminController@house', $files->id)}}" @endif>
            {{ trans('app.forms.housing_scheme') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/strata/*')) active @endif custom-tab" @if(!Request::is('update/strata/*')) href="{{URL::action('AdminController@strata', $files->id)}}" @endif>
            {{ trans('app.forms.developed_area') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/management/*')) active @endif custom-tab" @if(!Request::is('update/management/*')) href="{{URL::action('AdminController@management', $files->id)}}" @endif>
            {{ trans('app.forms.management') }}
        </a>
    </li>
    @if (!Auth::user()->isJMB())
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/monitoring/*')) active @endif custom-tab" @if(!Request::is('update/monitoring/*')) href="{{URL::action('AdminController@monitoring', $files->id)}}" @endif>
            {{ trans('app.forms.monitoring') }}
        </a>
    </li>
    @endif
    @if (!Auth::user()->isPreSale())
    <li class="nav-item">
        <a class="nav-link @if(Request::is('updateFile/others/*')) active @endif custom-tab" @if(!Request::is('updateFile/others/*')) href="{{URL::action('AdminController@others', $files->id)}}" @endif>
            {{ trans('app.forms.others') }}
        </a>
    </li>
    @endif
    @if (!Auth::user()->isJMB() && !Auth::user()->isPreSale())
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/scoring/*')) active @endif custom-tab" @if(!Request::is('update/scoring/*')) href="{{URL::action('AdminController@scoring', $files->id)}}" @endif>
            {{ trans('app.forms.scoring_component_value') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/buyer/*')) active @endif custom-tab" @if(!Request::is('update/buyer/*')) href="{{URL::action('AdminController@buyer', $files->id)}}" @endif>
            {{ trans('app.forms.buyer_list') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/document/*')) active @endif custom-tab" @if(!Request::is('update/document/*')) href="{{URL::action('AdminController@document', $files->id)}}" @endif>
            {{ trans('app.forms.document') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('insurance/*')) active @endif custom-tab" @if(!Request::is('insurance/*')) href="{{URL::action('AdminController@insurance', $files->id)}}" @endif>
            {{ trans('app.forms.insurance') }}
        </a>
    </li>
    @endif
</ul>