<!-- BEGIN TOP NAVIGATION -->
<?php
$company = Company::find(Auth::user()->company_id);

if ($company->is_main != 1) {
    $cob_logout = strtolower($company->short_name);
} else {
    $cob_logout = '';
}

$swith_lang = true;
?>

<nav class="top-menu">
    <div class="menu-icon-container hidden-md-up">
        <div class="animate-menu-button left-menu-toggle">
            <div><!-- --></div>
        </div>
    </div>
    <div class="menu">
        <div class="menu-user-block margin-top-5">
            @if ($swith_lang)
            <div class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle dropdown-inline-button" data-toggle="dropdown" aria-expanded="false">
                    <i class="dropdown-inline-button-icon fa fa-globe"></i>
                    <span class="hidden-lg-down">{{ (app()->getLocale() == 'en') ? trans('app.language.en') : trans('app.language.my') }}</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="" role="menu">
                    <a class="dropdown-item {{ (app()->getLocale() == 'ms') ? 'active' : '' }}" href="{{ url('/changeLanguage/ms') }}">{{ trans('app.language.my') }}</a>
                    <a class="dropdown-item {{ (app()->getLocale() == 'en') ? 'active' : '' }}" href="{{ url('/changeLanguage/en') }}">{{ trans('app.language.en') }}</a>
                </ul>
            </div>
            @endif
            <div class="menu-user-block">
                <div class="dropdown dropdown-avatar">
                    <a href="javascript: void(0);" class="dropdown-toggle dropdown-inline-button" data-toggle="dropdown" aria-expanded="false">
                        <i class="dropdown-inline-button-icon icmn-user"></i>
                        <span class="hidden-lg-down">{{Auth::user()->full_name}}</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="" role="menu">
                        <a class="dropdown-item" href="{{URL::action('AdminController@home')}}"><i class="dropdown-icon icmn-home2"></i> {{ trans('app.menus.home') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{URL::action('UserController@editProfile')}}"><i class="dropdown-icon icmn-profile"></i> {{ trans('app.menus.edit_profile') }}</a>
                        <a class="dropdown-item" href="{{URL::action('UserController@changePassword')}}"><i class="dropdown-icon fa fa-key"></i> {{ trans('app.menus.change_password') }}</a>
                        <div class="dropdown-divider"></div>
                        @if (!empty($cob_logout))
                        <a class="dropdown-item" href="{{ url('/' . $cob_logout . '/logout') }}"><i class="dropdown-icon icmn-exit"></i> {{ trans('app.menus.logout') }}</a>
                        @else
                        <a class="dropdown-item" href="{{ url('/logout') }}"><i class="dropdown-icon icmn-exit"></i> {{ trans('app.menus.logout') }}</a>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="menu-info-block">
            <div class="row">
                <!--                <div class="logo-container">
                                    <div class="logo">
                                        <img src="{{$company->image_url}}" alt="" style="width: 40px;"/>
                                    </div>
                                </div>          -->
                <h6 class="margin-top-10">{{ trans('app.app_sub_title', ['title' => $company->name]) }}</h6>
            </div>
        </div>
    </div>
</nav>
<!-- END TOP NAVIGATION -->

