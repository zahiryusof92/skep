<script>
    $(document).ready(function () {
        $("#{{ $panel_nav_active }}").addClass("left-menu-list-opened");
        $("#{{ $main_nav_active }}").css("display", "block");
        $("#{{ $sub_nav_active }}").addClass("left-menu-list-active");
    });
</script>

<?php
$access_permission1 = 0;
$access_permission2 = 0;
$access_permission3 = 0;
$access_permission4 = 0;
$access_permission5 = 0;
$access_permission6 = 0;
$access_permission7 = 0;
$access_permission8 = 0;
$access_permission9 = 0;
$access_permission10 = 0;
$access_permission11 = 0;
$access_permission12 = 0;
$access_permission13 = 0;
$access_permission14 = 0;
$access_permission15 = 0;
$access_permission16 = 0;
$access_permission17 = 0;
$access_permission18 = 0;
$access_permission19 = 0;
$access_permission20 = 0;
$access_permission21 = 0;
$access_permission22 = 0;
$access_permission23 = 0;
$access_permission24 = 0;
$access_permission25 = 0;
$access_permission26 = 0;
$access_permission27 = 0;
$access_permission28 = 0;
$access_permission29 = 0;
$access_permission30 = 0;
$access_permission31 = 0;
$access_permission32 = 0;
$access_permission33 = 0;
$access_permission34 = 0;
$access_permission35 = 0;
$access_permission36 = 0;
$access_permission37 = 0;
$access_permission38 = 0;
$access_permission39 = 0;
$access_permission40 = 0;
$access_permission41 = 0;
$access_permission42 = 0;
$access_permission43 = 0;
$access_permission44 = 0;
$access_permission45 = 0;
$access_permission46 = 0;
$access_permission47 = 0;
$access_permission48 = 0;

$user_permission = AccessGroup::getAccessPermission(Auth::user()->id);

if ($user_permission) {
//    echo "<pre>" . print_r($user_permission, 1) . "</pre>";

    foreach ($user_permission as $permission) {
        if ($permission->submodule_id == 1) {
            $access_permission1 = $permission->access_permission;
        }
        if ($permission->submodule_id == 2) {
            $access_permission2 = $permission->access_permission;
        }
        if ($permission->submodule_id == 3) {
            $access_permission3 = $permission->access_permission;
        }
        if ($permission->submodule_id == 4) {
            $access_permission4 = $permission->access_permission;
        }
        if ($permission->submodule_id == 5) {
            $access_permission5 = $permission->access_permission;
        }
        if ($permission->submodule_id == 6) {
            $access_permission6 = $permission->access_permission;
        }
        if ($permission->submodule_id == 7) {
            $access_permission7 = $permission->access_permission;
        }
        if ($permission->submodule_id == 8) {
            $access_permission8 = $permission->access_permission;
        }
        if ($permission->submodule_id == 9) {
            $access_permission9 = $permission->access_permission;
        }
        if ($permission->submodule_id == 10) {
            $access_permission10 = $permission->access_permission;
        }
        if ($permission->submodule_id == 11) {
            $access_permission11 = $permission->access_permission;
        }
        if ($permission->submodule_id == 12) {
            $access_permission12 = $permission->access_permission;
        }
        if ($permission->submodule_id == 13) {
            $access_permission13 = $permission->access_permission;
        }
        if ($permission->submodule_id == 14) {
            $access_permission14 = $permission->access_permission;
        }
        if ($permission->submodule_id == 15) {
            $access_permission15 = $permission->access_permission;
        }
        if ($permission->submodule_id == 16) {
            $access_permission16 = $permission->access_permission;
        }
        if ($permission->submodule_id == 17) {
            $access_permission17 = $permission->access_permission;
        }
        if ($permission->submodule_id == 18) {
            $access_permission18 = $permission->access_permission;
        }
        if ($permission->submodule_id == 19) {
            $access_permission19 = $permission->access_permission;
        }
        if ($permission->submodule_id == 20) {
            $access_permission20 = $permission->access_permission;
        }
        if ($permission->submodule_id == 21) {
            $access_permission21 = $permission->access_permission;
        }
        if ($permission->submodule_id == 22) {
            $access_permission22 = $permission->access_permission;
        }
        if ($permission->submodule_id == 23) {
            $access_permission23 = $permission->access_permission;
        }
        if ($permission->submodule_id == 24) {
            $access_permission24 = $permission->access_permission;
        }
        if ($permission->submodule_id == 25) {
            $access_permission25 = $permission->access_permission;
        }
        if ($permission->submodule_id == 26) {
            $access_permission26 = $permission->access_permission;
        }
        if ($permission->submodule_id == 27) {
            $access_permission27 = $permission->access_permission;
        }
        if ($permission->submodule_id == 28) {
            $access_permission28 = $permission->access_permission;
        }
        if ($permission->submodule_id == 29) {
            $access_permission29 = $permission->access_permission;
        }
        if ($permission->submodule_id == 30) {
            $access_permission30 = $permission->access_permission;
        }
        if ($permission->submodule_id == 31) {
            $access_permission31 = $permission->access_permission;
        }
        if ($permission->submodule_id == 32) {
            $access_permission32 = $permission->access_permission;
        }
        if ($permission->submodule_id == 33) {
            $access_permission33 = $permission->access_permission;
        }
        if ($permission->submodule_id == 34) {
            $access_permission34 = $permission->access_permission;
        }
        if ($permission->submodule_id == 35) {
            $access_permission35 = $permission->access_permission;
        }
        if ($permission->submodule_id == 36) {
            $access_permission36 = $permission->access_permission;
        }
        /*
         * Finance
         */
        if ($permission->submodule_id == 37) {
            $access_permission37 = $permission->access_permission;
        }
        if ($permission->submodule_id == 38) {
            $access_permission38 = $permission->access_permission;
        }
        if ($permission->submodule_id == 39) {
            $access_permission39 = $permission->access_permission;
        }
        /*
         * End Finance
         */

        if ($permission->submodule_id == 40) {
            $access_permission40 = $permission->access_permission;
        }
        if ($permission->submodule_id == 41) {
            $access_permission41 = $permission->access_permission;
        }

        if ($permission->submodule_id == 42) {
            $access_permission42 = $permission->access_permission;
        }
        if ($permission->submodule_id == 43) {
            $access_permission43 = $permission->access_permission;
        }
        if ($permission->submodule_id == 44) {
            $access_permission44 = $permission->access_permission;
        }
        if ($permission->submodule_id == 45) {
            $access_permission45 = $permission->access_permission;
        }
        if ($permission->submodule_id == 46) {
            $access_permission46 = $permission->access_permission;
        }
        if ($permission->submodule_id == 47) {
            $access_permission47 = $permission->access_permission;
        }
        if ($permission->submodule_id == 48) {
            $access_permission48 = $permission->access_permission;
        }
    }
}

$company = Company::find(Auth::user()->company_id);
if (!Auth::user()->getAdmin()) {
    $pending = Files::where('company_id', Auth::user()->company_id)->where('status', 0)->where('is_deleted', 0)->count();
} else {
    if (empty(Session::get('admin_cob'))) {
        $pending = Files::where('status', 0)->where('is_deleted', 0)->count();
    } else {
        $pending = Files::where('company_id', Session::get('admin_cob'))->where('status', 0)->where('is_deleted', 0)->count();
    }
}
?>
<!-- BEGIN SIDE NAVIGATION -->
<nav class="left-menu" left-menu>
    <div class="logo-container">
        <div class="logo">
            <a href="{{URL::action('AdminController@home')}}"><img src="{{asset($company->image_url)}}" alt=""/></a>
        </div>
    </div>
    <div class="left-menu-inner scroll-pane">
        <div id="image_nav">

            @if ($image == "")
            @if ($company->nav_image_url != "")
            <img src="{{asset($company->nav_image_url)}}" style="width: 100%;" alt="" />
            @endif
            @else
            <img src="{{asset($image)}}" style="width: 100%;" alt="" />
            @endif
        </div>
        <ul class="left-menu-list left-menu-list-root list-unstyled">

            <li class="left-menu-list-link hidden-md-up">
                <a class="left-menu-link" href="{{ URL::action('AdminController@home') }}">
                    <i class="left-menu-link-icon fa fa-home"><!-- --></i>
                    {{ trans('app.menus.home') }}
                </a>
            </li>

            @if ($access_permission1 == 1 || $access_permission2 == 1 || $access_permission3 == 1 || $access_permission36 == 1 || $access_permission37 == 1 || $access_permission38 == 1 || $access_permission39 == 1)
            <li class="left-menu-list-submenu" id="cob_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-file"><!-- --></i>
                    {{ trans('app.menus.cob.maintenance') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="cob_main">
                    @if ($access_permission1 == 1)
                    <li id="prefix_file">
                        <a class="left-menu-link" href="{{URL::action('AdminController@filePrefix')}}">
                            {{ trans('app.menus.cob.file_prefix') }}
                        </a>
                    </li>
                    @endif
                    @if($access_permission2 == 1)
                    <li id="add_cob">
                        <a class="left-menu-link" href="{{URL::action('AdminController@addFile')}}">
                            {{ trans('app.menus.cob.add_cob_file') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission3 == 1)
                    <li id="cob_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@fileList')}}">
                            {{ trans('app.menus.cob.file_list') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission36 == 1)
                    <li id="cob_before_vp_list">
                        <a class="left-menu-link" href="{{ URL::action('AdminController@fileListBeforeVP') }}">
                            {{ trans('app.menus.cob.file_list_before_vp') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission37 == 1)
                    <li id="add_finance_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@addFinanceFileList')}}">
                            {{ trans('app.menus.cob.add_finance_file_list') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission38 == 1)
                    <li id="finance_file_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@financeList')}}">
                            {{ trans('app.menus.cob.finance_file_list') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission39 == 1)
                    <li id="finance_support_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@financeSupport')}}">
                            {{ trans('app.menus.cob.finance_support') }}
                        </a>
                    </li>
                    @endif                    
                </ul>
            </li>
            @endif
            
            @if ($access_permission45)
            <li class="left-menu-list-link" id="defect_list">
                <a class="left-menu-link" href="{{ URL::action('AdminController@defect') }}">
                    <i class="left-menu-link-icon fa fa-comments"><!-- --></i>
                    {{ trans('app.menus.agm.defect') }}
                </a>
            </li>
            @endif
            
            @if ($access_permission46)
            <li class="left-menu-list-link" id="insurance_list">
                <a class="left-menu-link" href="{{ URL::action('AdminController@insurance') }}">
                    <i class="left-menu-link-icon fa fa-medkit"><!-- --></i>
                    {{ trans('app.menus.agm.insurance') }}
                </a>
            </li>
            @endif

            @if ($access_permission4 == 1 || $access_permission5 == 1 || $access_permission6 == 1 || $access_permission7 == 1)
            <li class="left-menu-list-submenu" id="admin_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-user"><!-- --></i>
                    {{ trans('app.menus.administration.administration') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="admin_main">
                    @if ($access_permission4 == 1)
                    <li id="profile_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@company')}}">
                            {{ trans('app.menus.administration.organization_profile') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission5 == 1)
                    <li id="access_group_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@accessGroups')}}">
                            {{ trans('app.menus.administration.access_group_management') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission6 == 1)
                    <li id="user_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@user')}}">
                            {{ trans('app.menus.administration.user_management') }}<span class="label left-menu-label label-danger">&nbsp;{{ trans('app.menus.administration.pending', ['count'=> User::where('status', 0)->where('is_deleted', 0)->count()]) }}</span>
                        </a>
                    </li>
                    @endif
                    @if ($access_permission7 == 1)
                    <li id="memo_maintenence_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@memo')}}">
                            {{ trans('app.menus.administration.memo_maintenance') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission40 == 1)
                    <li id="rating_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@rating')}}">
                            {{ trans('app.menus.administration.rating') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission41 == 1)
                    <li id="form_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@form')}}">
                            {{ trans('app.menus.administration.form') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if ($access_permission8 == 1 || $access_permission9 == 1 || $access_permission10 == 1 || $access_permission11 == 1 ||
            $access_permission12 == 1 || $access_permission13 == 1 || $access_permission14 == 1 || $access_permission15 == 1 ||
            $access_permission16 == 1 || $access_permission17 == 1 || $access_permission18 == 1 || $access_permission19 == 1 ||
            $access_permission20 == 1 || $access_permission21 == 1 || $access_permission22 == 1 || $access_permission23 == 1)
            <li class="left-menu-list-submenu" id="master_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-gears"><!-- --></i>
                    {{ trans('app.menus.master.setup') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="master_main">
                    @if ($access_permission8 == 1)
                    <li id="country_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@country')}}">
                            {{ trans('app.menus.master.country') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission9 == 1)
                    <li id="state_list"><a class="left-menu-link" href="{{URL::action('SettingController@state')}}">
                            {{ trans('app.menus.master.state') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission10 == 1)
                    <li id="area_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@area')}}">
                            {{ trans('app.menus.master.area') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission11 == 1)
                    <li id="city_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@city')}}">
                            {{ trans('app.menus.master.city') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission12 == 1)
                    <li id="category_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@category')}}">
                            {{ trans('app.menus.master.category') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission13 == 1)
                    <li id="land_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@landTitle')}}">
                            {{ trans('app.menus.master.land_title') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission14 == 1)
                    <li id="developer_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@developer')}}">
                            {{ trans('app.menus.master.developer') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission15 == 1)
                    <li id="agent_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@agent')}}">
                            {{ trans('app.menus.master.agent') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission16 == 1)
                    <li id="parliament_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@parliment')}}">
                            {{ trans('app.menus.master.parliament') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission17 == 1)
                    <li id="dun_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@dun')}}">
                            {{ trans('app.menus.master.dun') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission18 == 1)
                    <li id="park_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@park')}}">
                            {{ trans('app.menus.master.park') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission19 == 1)
                    <li id="memo_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@memoType')}}">
                            {{ trans('app.menus.master.memo_type') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission20 == 1)
                    <li id="designation_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@designation')}}">
                            {{ trans('app.menus.master.designation') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission21 == 1)
                    <li id="unit_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@unitMeasure')}}">
                            {{ trans('app.menus.master.unit_of_measure') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission22 == 1)
                    <li id="formtype_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@formtype')}}">
                            {{ trans('app.menus.master.form_type') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission23 == 1)
                    <li id="documenttype_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@documenttype')}}">
                            {{ trans('app.menus.master.document_type') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission42 == 1)
                    <li id="race_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@race')}}">
                            {{ trans('app.menus.master.race') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission44 == 1)
                    <li id="nationality_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@nationality')}}">
                            {{ trans('app.menus.master.nationality') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission47)
                    <li id="defect_category_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@defectCategory')}}">
                            {{ trans('app.menus.master.defect_category') }}
                        </a>
                    </li>
                    @endif                    
                    @if ($access_permission48)
                    <li id="insurance_provider_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@insuranceProvider')}}">
                            {{ trans('app.menus.master.insurance_provider') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if ($access_permission24 == 1 || $access_permission25 == 1 || $access_permission26 == 1 || $access_permission27 == 1 || $access_permission28 == 1 || $access_permission29 == 1)
            <li class="left-menu-list-submenu" id="reporting_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-file-pdf-o"><!-- --></i>
                    {{ trans('app.menus.reporting.reporting') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="reporting_main">
                    @if ($access_permission24 == 1)
                    <li id="audit_trail_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@auditTrail')}}">
                            {{ trans('app.menus.reporting.audit_trail') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission25 == 1)
                    <li id="file_by_location_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@fileByLocation')}}">
                            {{ trans('app.menus.reporting.file_by_location') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission26 == 1)
                    <li id="rating_summary_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@ratingSummary')}}">
                            {{ trans('app.menus.reporting.rating_summary') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission27 == 1)
                    <li id="management_summary_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@managementSummary')}}">
                            {{ trans('app.menus.reporting.management_summary') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission28 == 1)
                    <li id="cob_file_management_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@cobFileManagement')}}">
                            {{ trans('app.menus.reporting.cob_file') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission29 == 1)
                    <li id="strata_profile_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@strataProfile') }}">
                            {{ trans('app.menus.reporting.strata_profile') }}
                        </a>
                    </li>
                    <li id="owner_tenant_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@ownerTenant') }}">
                            {{ trans('app.menus.reporting.owner') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if ($access_permission30 == 1 || $access_permission31 == 1 || $access_permission32 == 1 || $access_permission33 == 1)
            <li class="left-menu-list-submenu" id="agm_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-sitemap"><!-- --></i>
                    {{ trans('app.menus.agm.submission') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="agm_main">
                    @if ($access_permission30 == 1)
                    <li id="agmdesignsub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@AJK')}}">
                            {{ trans('app.menus.agm.designation') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission31 == 1)
                    <li id="agmpurchasesub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@purchaser')}}">
                            {{ trans('app.menus.agm.purchaser') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission43 == 1)
                    <li id="agmtenantsub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@tenant')}}">
                            {{ trans('app.menus.agm.tenant') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission32 == 1)
                    <li id="agmminutesub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@minutes')}}">
                            {{ trans('app.menus.agm.upload_of_minutes') }}
                        </a>
                    </li>
                    @endif
                    @if ($access_permission33 == 1)
                    <li id="agmdocumentsub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@document')}}">
                            {{ trans('app.menus.agm.upload_document') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if ($access_permission34 == 1)
            <li class="left-menu-list-submenu" id="form_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-file-text-o"><!-- --></i>
                    {{ trans('app.menus.form.management') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="form_main">
                    @if ($access_permission34 == 1)
                    <li id="form_download_list">
                        <a class="left-menu-link" href="{{ URL::action('AdminController@formDownload') }}">
                            {{ trans('app.menus.form.download') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if ($access_permission35 == 1)
            <li class="left-menu-list-submenu" id="change_cob_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-exchange"><!-- --></i>
                    {{ trans('app.menus.change_cob') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="change_cob_main">
                    @if ($access_permission34 == 1)
                    <?php
                    $jmb = Company::where('is_active', 1)->where('short_name', '!=', '')->where('is_deleted', 0)->orderBy('short_name')->get();
                    ?>

                    @foreach ($jmb as $cob)
                    <li id="{{ $cob->short_name . "_list" }}">
                        <a class="left-menu-link" href='{{ URL::action('UserController@changeCOB', $cob->id) }}'>{{ strtoupper($cob->short_name) }}</a>
                    </li>
                    @endforeach
                    @endif
                </ul>
            </li>
            @endif
        </ul>

    </div>
</nav>
<!-- END SIDE NAVIGATION -->
