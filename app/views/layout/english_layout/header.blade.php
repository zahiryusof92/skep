<!-- BEGIN TOP NAVIGATION -->
<?php
$company = Company::find(Auth::user()->company_id);

if ($company->is_main != 1) {
    $cob_logout = strtolower($company->short_name);
} else {
    $cob_logout = '';
}
$notifications = Notification::self()->notView()->latest('notifications.created_at')->take(5)->get();
$notification_total = Notification::self()->notView()->count();
$swith_lang = true;
?>

<nav class="top-menu">
    <div class="menu-icon-container hidden-md-up">
        <div class="animate-menu-button left-menu-toggle">
            <div>
                <!-- -->
            </div>
        </div>
    </div>
    <div class="menu">
        <div class="menu-user-block margin-top-5">
            @if (AccessGroup::hasAccessModule("Notification"))
            <li class="dropdown">
                <a class="dropdown-inline-button" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="dropdown-icon fa fa-bell theme-icon"></i>
                    @if($notifications->count())
                    <span class="badge badge-pill badge-danger navbar-badge">{{ $notification_total }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="" role="menu">
                    @foreach($notifications as $notification)
                    <li>
                        <a class="dropdown-item notification-link" href="javascript:void(0)" onclick="updateNotification({{ $notification->id }})">
                            <i class="dropdown-icon fa fa-star notification-img"></i>
                            <div>
                                {{-- <span class="dropdown-item-title">
                                    {{ $notification->user }}
                                </span> --}}
                                <p class="text-sm text-overflow notification-message">
                                    {{ $notification->description }}
                                </p>
                                <span class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                            </div>
                        </a>
                    </li>
                    @endforeach
                    <a href="{{ route('notification.index') }}" class="dropdown-item dropdown-footer text-center">See All Messages</a>
                </ul>
            </li>
            @endif
            @if ($swith_lang)
                <div class="dropdown dropdown-lang">
                    <a href="javascript: void(0);" class="dropdown-inline-button" data-toggle="dropdown"
                        aria-expanded="false">
                        <img class="dropdown-inline-button-icon"
                            src="{{ asset('assets/common/img/icon/lang.png') }}" />
                        <span
                            class="hidden-lg-down">{{ app()->getLocale() == 'en' ? trans('app.language.en') : trans('app.language.my') }}</span>
                        <span class="fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right lang" aria-labelledby="" role="menu">
                        <a class="dropdown-item {{ app()->getLocale() == 'ms' ? 'active' : '' }}"
                            href="{{ url('/changeLanguage/ms') }}">{{ trans('app.language.my') }}</a>
                        <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                            href="{{ url('/changeLanguage/en') }}">{{ trans('app.language.en') }}</a>
                    </ul>
                </div>
            @endif
            <div class="menu-user-block">
                <div class="dropdown dropdown-avatar">
                    <a href="javascript: void(0);" class="dropdown-inline-button" data-toggle="dropdown"
                        aria-expanded="false">
                        <img class="dropdown-inline-button-icon user"
                            src="{{ asset('assets/common/img/icon/user_round.png') }}" />
                        <span class="hidden-lg-down">{{ Auth::user()->full_name }}</span>
                        <span class="fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="" role="menu">
                        <a class="dropdown-item" href="{{ URL::action('HomeController@home') }}">
                            <img class="dropdown-inline-button-icon "
                                src="{{ asset('assets/common/img/icon/home.png') }}" />
                            <span>
                                {{ trans('app.menus.home') }}
                            </span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ URL::action('UserController@editProfile') }}">
                            <img class="dropdown-inline-button-icon "
                                src="{{ asset('assets/common/img/icon/profile.png') }}" />
                            <span>
                                {{ trans('app.menus.edit_profile') }}
                            </span>
                        </a>
                        <a class="dropdown-item" href="{{ URL::action('UserController@changePassword') }}">
                            <img class="dropdown-inline-button-icon "
                                src="{{ asset('assets/common/img/icon/key.png') }}" />
                            <span>
                                {{ trans('app.menus.change_password') }}
                            </span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @if (!empty($cob_logout))
                            <a class="dropdown-item" href="{{ url('/' . $cob_logout . '/logout') }}">
                                <i class="dropdown-icon icmn-exit"></i>
                                <span>
                                    {{ trans('app.menus.logout') }}
                                </span>
                            </a>
                        @else
                            <a class="dropdown-item" href="{{ url('/logout') }}">
                                <i class="dropdown-icon icmn-exit"></i>
                                <span>
                                    {{ trans('app.menus.logout') }}

                                </span>
                            </a>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="menu-info-block">
            <div class="row">
                <!--                <div class="logo-container">
                                    <div class="logo">
                                        <img src="{{ $company->image_url }}" alt="" style="width: 40px;"/>
                                    </div>
                                </div>          -->
                <h6 class="margin-top-10">{{ trans('app.app_sub_title', ['title' => $company->name]) }}</h6>
            </div>
        </div>
    </div>
    <script>
        function updateNotification(id) {
                let route = "{{ route('notification.update', ':id') }}";
                route = route.replace(':id', id);
                $.ajax({
                    url: route,
                    type: "PATCH",
                    beforeSend: function() {
                        $("#loading").css("display", "inline-block");
                    },
                    success: function (res) {
                        if (res.success == true) {
                            window.location = res.route;
                        }
                    },
                    complete: function() {
                        $("#loading").css("display", "none");
                    },
                });
        }
    </script> 
</nav>
<!-- END TOP NAVIGATION -->