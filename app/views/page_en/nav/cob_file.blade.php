
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
        <a class="nav-link @if(Request::is('update/buyer/*') || Request::is('*Buyer/*')) active @endif custom-tab" @if(!Request::is('update/buyer/*') && !Request::is('*Buyer/*')) href="{{URL::action('AdminController@buyer', $files->id)}}" @endif>
            {{ trans('app.forms.buyer_list') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/document/*') || Request::is('*Document/*')) active @endif custom-tab" @if(!Request::is('update/document/*') && !Request::is('*Document/*')) href="{{URL::action('AdminController@document', $files->id)}}" @endif>
            {{ trans('app.forms.document') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('insurance/*') || Request::is('*Insurance/*')) active @endif custom-tab" @if(!Request::is('insurance/*') && !Request::is('*Insurance/*')) href="{{URL::action('AdminController@insurance', $files->id)}}" @endif>
            {{ trans('app.forms.insurance') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('*financeSupport/*') || Request::is('*FinanceSupport/*')) active @endif custom-tab" @if(!Request::is('*financeSupport/*') && !Request::is('*FinanceSupport/*')) href="{{URL::action('AdminController@financeSupport', $files->id)}}" @endif>
            {{ trans('app.forms.finance_support') }}
        </a>
    </li>
    @endif
</ul>