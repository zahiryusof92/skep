@if(empty($is_view))
<ul class="nav nav-pills nav-justified" role="tablist">
    @if(!Auth::user()->isMPS())
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/house/*')) active @endif custom-tab" @if(!Request::is('update/house/*')) href="{{URL::action('AdminController@house', $files->id)}}" @endif>
            {{ trans('app.forms.housing_scheme') }}
        </a>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link @if(Request::is('update/strata/*')) active @endif custom-tab" @if(!Request::is('update/strata/*')) href="{{URL::action('AdminController@strata', $files->id)}}" @endif>
            {{ trans('app.forms.developed_area') }}
        </a>
    </li>
    @if(!Auth::user()->isMPS())
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
    @endif
    @if (!Auth::user()->isJMB() && !Auth::user()->isPreSale() && !Auth::user()->isMPS())
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
@else
<ul class="nav nav-pills nav-justified" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if(Request::is('view/house/*')) active @endif custom-tab" @if(!Request::is('view/house/*')) href="{{URL::action('AdminController@viewHouse', $files->id)}}" @endif>
            {{ trans('app.forms.housing_scheme') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('view/strata/*')) active @endif custom-tab" @if(!Request::is('view/strata/*')) href="{{URL::action('AdminController@viewStrata', $files->id)}}" @endif>
            {{ trans('app.forms.developed_area') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('view/management/*')) active @endif custom-tab" @if(!Request::is('view/management/*')) href="{{URL::action('AdminController@viewManagement', $files->id)}}" @endif>
            {{ trans('app.forms.management') }}
        </a>
    </li>
    @if (!Auth::user()->isJMB())
    <li class="nav-item">
        <a class="nav-link @if(Request::is('view/monitoring/*')) active @endif custom-tab" @if(!Request::is('view/monitoring/*')) href="{{URL::action('AdminController@viewMonitoring', $files->id)}}" @endif>
            {{ trans('app.forms.monitoring') }}
        </a>
    </li>
    @endif
    @if (!Auth::user()->isPreSale())
    <li class="nav-item">
        <a class="nav-link @if(Request::is('view/others/*')) active @endif custom-tab" @if(!Request::is('view/others/*')) href="{{URL::action('AdminController@viewOthers', $files->id)}}" @endif>
            {{ trans('app.forms.others') }}
        </a>
    </li>
    @endif
    @if (!Auth::user()->isJMB() && !Auth::user()->isPreSale())
    <li class="nav-item">
        <a class="nav-link @if(Request::is('view/scoring/*')) active @endif custom-tab" @if(!Request::is('view/scoring/*')) href="{{URL::action('AdminController@viewScoring', $files->id)}}" @endif>
            {{ trans('app.forms.scoring_component_value') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(Request::is('view/buyer/*') || Request::is('*Buyer/*')) active @endif custom-tab" @if(!Request::is('view/buyer/*') && !Request::is('*Buyer/*')) href="{{URL::action('AdminController@viewBuyer', $files->id)}}" @endif>
            {{ trans('app.forms.buyer_list') }}
        </a>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link @if(Request::is('approval/*')) active @endif custom-tab" @if(!Request::is('approval/*')) href="{{URL::action('AdminController@fileApproval', $files->id)}}" @endif>{{ trans('app.forms.approval') }}</a>
    </li>
</ul>
@endif