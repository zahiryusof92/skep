@if(empty($is_view))
    <ul class="nav nav-pills nav-justified" role="tablist">
        
        @if(!Auth::user()->isMPS())
            <li class="nav-item">
                <a class="nav-link @if(Request::is('update/house/*')) active @endif custom-tab" @if(!Request::is('update/house/*')) href="{{URL::action('AdminController@house', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.housing_scheme') }}
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link @if(Request::is('update/strata/*')) active @endif custom-tab" @if(!Request::is('update/strata/*')) href="{{URL::action('AdminController@strata', \Helper\Helper::encode($files->id))}}" @endif>
                {{ trans('app.forms.developed_area') }}
            </a>
        </li>

        @if(!Auth::user()->isMPS())
            <li class="nav-item">
                <a class="nav-link @if(Request::is('update/management/*')) active @endif custom-tab" @if(!Request::is('update/management/*')) href="{{URL::action('AdminController@management', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.management') }}
                </a>
            </li>

            @if (!Auth::user()->isJMB() && !Auth::user()->isDeveloper())
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('update/monitoring/*')) active @endif custom-tab" @if(!Request::is('update/monitoring/*')) href="{{URL::action('AdminController@monitoring', \Helper\Helper::encode($files->id))}}" @endif>
                        {{ trans('app.forms.monitoring') }}
                    </a>
                </li>
            @endif

            @if (!Auth::user()->isPreSale())
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('updateFile/others/*')) active @endif custom-tab" @if(!Request::is('updateFile/others/*')) href="{{URL::action('AdminController@others', \Helper\Helper::encode($files->id))}}" @endif>
                        {{ trans('app.forms.others') }}
                    </a>
                </li>
            @endif
        @endif

        @if (!Auth::user()->isJMB() && !Auth::user()->isDeveloper() && !Auth::user()->isPreSale() && !Auth::user()->isMPS())
            <li class="nav-item">
                <a class="nav-link @if(Request::is('update/scoring/*')) active @endif custom-tab" @if(!Request::is('update/scoring/*')) href="{{URL::action('AdminController@scoring', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.scoring_component_value') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(Request::is('update/buyer/*') || Request::is('*Buyer/*')) active @endif custom-tab" @if(!Request::is('update/buyer/*') && !Request::is('*Buyer/*')) href="{{URL::action('AdminController@buyer', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.buyer_list') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(Request::is('update/document/*') || Request::is('*Document/*')) active @endif custom-tab" @if(!Request::is('update/document/*') && !Request::is('*Document/*')) href="{{URL::action('AdminController@document', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.document') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(Request::is('insurance/*') || Request::is('*Insurance/*')) active @endif custom-tab" @if(!Request::is('insurance/*') && !Request::is('*Insurance/*')) href="{{URL::action('AdminController@insurance', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.insurance') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(Request::is('*financeSupport/*') || Request::is('*FinanceSupport/*')) active @endif custom-tab" @if(!Request::is('*financeSupport/*') && !Request::is('*FinanceSupport/*')) href="{{URL::action('AdminController@financeSupport', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.finance_support') }}
                </a>
            </li>

            @if (AccessGroup::hasAccessModule('File Movement'))
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('*fileMovement/*') || Request::is('*FileMovement/*')) active @endif custom-tab" @if(!Request::is('*fileMovement/*') || !Request::is('*FileMovement/*')) href="{{ route('cob.file-movement.index', \Helper\Helper::encode($files->id)) }}" @endif>
                        {{ trans('app.forms.file_movement') }}
                    </a>
                </li>
            @endif

            @if (AccessGroup::hasAccessModule('Audit Account'))
                <li class="nav-item">
                    <a class="nav-link @if(Request::is('*auditAccount/*') || Request::is('*AuditAccount/*')) active @endif custom-tab" @if(!Request::is('*auditAccount/*') || !Request::is('*AuditAccount/*')) href="{{ route('cob.audit-account.index', \Helper\Helper::encode($files->id)) }}" @endif>
                        {{ trans('app.forms.audit_account') }}
                    </a>
                </li>
            @endif
        @else
            @if (Auth::user()->isJMB() || Auth::user()->isMC())
                @if (AccessGroup::hasAccessModule('Audit Account'))
                    <li class="nav-item">
                        <a class="nav-link @if(Request::is('*auditAccount/*') || Request::is('*AuditAccount/*')) active @endif custom-tab" @if(!Request::is('*auditAccount/*') || !Request::is('*AuditAccount/*')) href="{{ route('cob.audit-account.index', \Helper\Helper::encode($files->id)) }}" @endif>
                            {{ trans('app.forms.audit_account') }}
                        </a>
                    </li>
                @endif
            @endif
        @endif

        {{-- <li class="nav-item">
            <a class="nav-link @if(Request::is('update/fixedDeposit/*')) active @endif custom-tab" @if(!Request::is('update/fixedDeposit/*')) href="{{ route('cob.file.fixedDeposit.edit', \Helper\Helper::encode($files->id)) }}" @endif>
                {{ trans('app.forms.fixed_deposit') }}
            </a>
        </li> --}}

    </ul>
@else
    <ul class="nav nav-pills nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if(Request::is('view/house/*')) active @endif custom-tab" @if(!Request::is('view/house/*')) href="{{URL::action('AdminController@viewHouse', \Helper\Helper::encode($files->id))}}" @endif>
                {{ trans('app.forms.housing_scheme') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @if(Request::is('view/strata/*')) active @endif custom-tab" @if(!Request::is('view/strata/*')) href="{{URL::action('AdminController@viewStrata', \Helper\Helper::encode($files->id))}}" @endif>
                {{ trans('app.forms.developed_area') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @if(Request::is('view/management/*')) active @endif custom-tab" @if(!Request::is('view/management/*')) href="{{URL::action('AdminController@viewManagement', \Helper\Helper::encode($files->id))}}" @endif>
                {{ trans('app.forms.management') }}
            </a>
        </li>

        @if (!Auth::user()->isJMB() && !Auth::user()->isDeveloper())
            <li class="nav-item">
                <a class="nav-link @if(Request::is('view/monitoring/*')) active @endif custom-tab" @if(!Request::is('view/monitoring/*')) href="{{URL::action('AdminController@viewMonitoring', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.monitoring') }}
                </a>
            </li>
        @endif

        @if (!Auth::user()->isPreSale())
            <li class="nav-item">
                <a class="nav-link @if(Request::is('view/others/*')) active @endif custom-tab" @if(!Request::is('view/others/*')) href="{{URL::action('AdminController@viewOthers', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.others') }}
                </a>
            </li>
        @endif

        @if (!Auth::user()->isJMB() && !Auth::user()->isDeveloper() && !Auth::user()->isPreSale())
            <li class="nav-item">
                <a class="nav-link @if(Request::is('view/scoring/*')) active @endif custom-tab" @if(!Request::is('view/scoring/*')) href="{{URL::action('AdminController@viewScoring', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.scoring_component_value') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link @if(Request::is('view/buyer/*') || Request::is('*Buyer/*')) active @endif custom-tab" @if(!Request::is('view/buyer/*') && !Request::is('*Buyer/*')) href="{{URL::action('AdminController@viewBuyer', \Helper\Helper::encode($files->id))}}" @endif>
                    {{ trans('app.forms.buyer_list') }}
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link @if(Request::is('approval/*')) active @endif custom-tab" @if(!Request::is('approval/*')) href="{{URL::action('AdminController@fileApproval', \Helper\Helper::encode($files->id))}}" @endif>{{ trans('app.forms.approval') }}</a>
        </li>
    </ul>
@endif