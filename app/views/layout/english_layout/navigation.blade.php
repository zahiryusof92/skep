<script>
    $(document).ready(function () {
        $("#{{ $panel_nav_active }}").addClass("left-menu-list-opened");
        $("#{{ $main_nav_active }}").css("display", "block");
        $("#{{ $sub_nav_active }}").addClass("left-menu-list-active");
    });
</script>

<?php
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
    <div class="logo-container hidden-sm-down">
        <div class="logo">
            <a href="{{URL::action('HomeController@home')}}"><img src="{{asset($company->image_url)}}" alt=""/></a>
        </div>
    </div>
    <div class="left-menu-inner scroll-pane">
        <div id="image_nav">

        </div>
        <ul class="left-menu-list left-menu-list-root list-unstyled">

            <li class="left-menu-list-link hidden-md-up">
                <a class="left-menu-link" href="{{ URL::action('HomeController@home') }}">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/home.png')}}"/>
                    {{ trans('app.menus.home') }}
                </a>
            </li>

            @if (Module::hasAccess(1))
            <li class="left-menu-list-submenu" id="cob_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/setting.png')}}"/>
                    {{ trans('app.menus.cob.maintenance') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="cob_main">

                    @if (AccessGroup::hasAccess(1))
                    <li id="prefix_file">
                        <a class="left-menu-link" href="{{URL::action('AdminController@filePrefix')}}">
                            {{ trans('app.menus.cob.file_prefix') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(2))
                    <li id="add_cob">
                        <a class="left-menu-link" href="{{URL::action('AdminController@addFile')}}">
                            {{ trans('app.menus.cob.add_cob_file') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(3))
                    <li id="cob_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@fileList')}}">
                            {{ trans('app.menus.cob.file_list') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(36))
                    <li id="cob_before_vp_list">
                        <a class="left-menu-link" href="{{ URL::action('AdminController@fileListBeforeVP') }}">
                            {{ trans('app.menus.cob.file_list_before_vp') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(37))
                    <li id="add_finance_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@addFinanceFileList')}}">
                            {{ trans('app.menus.cob.add_finance_file_list') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(38))
                    <li id="finance_file_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@financeList')}}">
                            {{ trans('app.menus.cob.finance_file_list') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(39))
                    <li id="finance_support_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@financeSupport')}}">
                            {{ trans('app.menus.cob.finance_support') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (AccessGroup::hasAccess(45))
            <li class="left-menu-list-link" id="defect_list">
                <a class="left-menu-link" href="{{ URL::action('AdminController@defect') }}">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/complaint.png')}}"/>
                    {{ trans('app.menus.agm.defect') }}
                </a>
            </li>
            @endif

            @if (AccessGroup::hasAccess(46))
            <li class="left-menu-list-link" id="insurance_list">
                <a class="left-menu-link" href="{{ URL::action('AdminController@insurance', 'All') }}">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/protect.png')}}"/>
                    {{ trans('app.menus.agm.insurance') }}
                </a>
            </li>
            @endif

            @if (Module::hasAccess(2))
            <li class="left-menu-list-submenu" id="admin_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/user.png')}}"/>
                    {{ trans('app.menus.administration.administration') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="admin_main">

                    @if (AccessGroup::hasAccess(4))
                    <li id="profile_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@company')}}">
                            {{ trans('app.menus.administration.organization_profile') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(5))
                    <li id="access_group_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@accessGroups')}}">
                            {{ trans('app.menus.administration.access_group_management') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(6))
                    <li id="user_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@user')}}">
                            {{ trans('app.menus.administration.user_management') }}<span class="label left-menu-label label-danger">&nbsp;{{ trans('app.menus.administration.pending', ['count'=> User::where('status', 0)->where('is_deleted', 0)->count()]) }}</span>
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(7))
                    <li id="memo_maintenence_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@memo')}}">
                            {{ trans('app.menus.administration.memo_maintenance') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(40))
                    <li id="rating_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@rating')}}">
                            {{ trans('app.menus.administration.rating') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(41))
                    <li id="form_list">
                        <a class="left-menu-link" href="{{URL::action('AdminController@form')}}">
                            {{ trans('app.menus.administration.form') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Module::hasAccess(3))
            <li class="left-menu-list-submenu" id="master_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/setting1.png')}}"/>
                    {{ trans('app.menus.master.setup') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="master_main">

                    @if (AccessGroup::hasAccess(8))
                    <li id="country_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@country')}}">
                            {{ trans('app.menus.master.country') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(9))
                    <li id="state_list"><a class="left-menu-link" href="{{URL::action('SettingController@state')}}">
                            {{ trans('app.menus.master.state') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(10))
                    <li id="area_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@area')}}">
                            {{ trans('app.menus.master.area') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(11))
                    <li id="city_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@city')}}">
                            {{ trans('app.menus.master.city') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(12))
                    <li id="category_list">
                        <a class="left-menu-link" href="{{ route('category.index') }}">
                            {{ trans('app.menus.master.category') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(13))
                    <li id="land_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@landTitle')}}">
                            {{ trans('app.menus.master.land_title') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(14))
                    <li id="developer_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@developer')}}">
                            {{ trans('app.menus.master.developer') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(15))
                    <li id="agent_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@agent')}}">
                            {{ trans('app.menus.master.agent') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(16))
                    <li id="parliament_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@parliment')}}">
                            {{ trans('app.menus.master.parliament') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(17))
                    <li id="dun_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@dun')}}">
                            {{ trans('app.menus.master.dun') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(18))
                    <li id="park_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@park')}}">
                            {{ trans('app.menus.master.park') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(19))
                    <li id="memo_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@memoType')}}">
                            {{ trans('app.menus.master.memo_type') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(20))
                    <li id="designation_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@designation')}}">
                            {{ trans('app.menus.master.designation') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(21))
                    <li id="unit_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@unitMeasure')}}">
                            {{ trans('app.menus.master.unit_of_measure') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(22))
                    <li id="formtype_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@formtype')}}">
                            {{ trans('app.menus.master.form_type') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(23))
                    <li id="documenttype_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@documenttype')}}">
                            {{ trans('app.menus.master.document_type') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(42))
                    <li id="race_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@race')}}">
                            {{ trans('app.menus.master.race') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(44))
                    <li id="nationality_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@nationality')}}">
                            {{ trans('app.menus.master.nationality') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(47))
                    <li id="defect_category_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@defectCategory')}}">
                            {{ trans('app.menus.master.defect_category') }}
                        </a>
                    </li>
                    @endif                    

                    @if (AccessGroup::hasAccess(48))
                    <li id="insurance_provider_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@insuranceProvider')}}">
                            {{ trans('app.menus.master.insurance_provider') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(61))
                    @if (Auth::user()->isSuperadmin())                    
                    <li id="point_package_list">
                        <a class="left-menu-link" href="{{ route('pointPackage.index') }}">
                            {{ trans('app.point_package.title') }}
                        </a>
                    </li>
                    @endif

                    @if (Auth::user()->isSuperadmin())
                    <li id="conversion_list">
                        <a class="left-menu-link" href="{{ route('conversion.index') }}">
                            {{ trans('app.menus.master.conversion') }}
                        </a>
                    </li>
                    @endif
                    @endif

                </ul>
            </li>
            @endif

            @if (Module::hasAccess(4))
            <li class="left-menu-list-submenu" id="reporting_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/report.png')}}"/>
                    {{ trans('app.menus.reporting.reporting') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="reporting_main">

                    @if (AccessGroup::hasAccess(24))
                    <li id="audit_trail_list">
                        <a class="left-menu-link" href="{{URL::action('ReportController@auditTrail')}}">
                            {{ trans('app.menus.reporting.audit_trail') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(25))
                    <li id="file_by_location_list">
                        <a class="left-menu-link" href="{{URL::action('ReportController@fileByLocation')}}">
                            {{ trans('app.menus.reporting.file_by_location') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(26))
                    <li id="rating_summary_list">
                        <a class="left-menu-link" href="{{URL::action('ReportController@ratingSummary')}}">
                            {{ trans('app.menus.reporting.rating_summary') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(27))
                    <li id="management_summary_list">
                        <a class="left-menu-link" href="{{URL::action('ReportController@managementSummary')}}">
                            {{ trans('app.menus.reporting.management_summary') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(28))
                    <li id="cob_file_management_list">
                        <a class="left-menu-link" href="{{URL::action('ReportController@cobFileManagement')}}">
                            {{ trans('app.menus.reporting.cob_file') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(29))
                    <li id="strata_profile_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@strataProfile') }}">
                            {{ trans('app.menus.reporting.strata_profile') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(49))
                    <li id="owner_tenant_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@ownerTenant') }}">
                            {{ trans('app.menus.reporting.owner') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(50))
                    <li id="insurance_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@insurance') }}">
                            {{ trans('app.menus.reporting.insurance') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(51))
                    <li id="complaint_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@complaint') }}">
                            {{ trans('app.menus.reporting.complaint') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(52))
                    <li id="collection_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@collection') }}">
                            {{ trans('app.menus.reporting.collection') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(53))
                    <li id="council_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@council') }}">
                            {{ trans('app.menus.reporting.council') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(54))
                    <li id="dun_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@dun') }}">
                            {{ trans('app.menus.reporting.dun') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(55))
                    <li id="parliment_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@parliment') }}">
                            {{ trans('app.menus.reporting.parliment') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(56))
                    <li id="vp_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@vp') }}">
                            {{ trans('app.menus.reporting.vp') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(57))
                    <li id="management_list_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@management') }}">
                            {{ trans('app.menus.reporting.management_list') }}
                        </a>
                    </li>
                    @endif
                    
                    @if (AccessGroup::hasAccess(60))
                    <li id="land_title_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@landTitle') }}">
                            {{ trans('app.menus.reporting.land_title') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Module::hasAccess(5))
            <li class="left-menu-list-submenu" id="agm_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/agm.png')}}"/>
                    {{ trans('app.menus.agm.submission') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="agm_main">

                    @if (AccessGroup::hasAccess(30))
                    <li id="agmdesignsub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@AJK')}}">
                            {{ trans('app.menus.agm.designation') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(31))
                    <li id="agmpurchasesub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@purchaser')}}">
                            {{ trans('app.menus.agm.purchaser') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(43))
                    <li id="agmtenantsub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@tenant')}}">
                            {{ trans('app.menus.agm.tenant') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(32))
                    <li id="agmminutesub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@minutes')}}">
                            {{ trans('app.menus.agm.upload_of_minutes') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(33))
                    <li id="agmdocumentsub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@document')}}">
                            {{ trans('app.menus.agm.upload_document') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Module::hasAccess(6))
            <li class="left-menu-list-submenu" id="form_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/form.png')}}"/>
                    {{ trans('app.menus.form.management') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="form_main">
                    @if (AccessGroup::hasAccess(34))
                    <li id="form_download_list">
                        <a class="left-menu-link" href="{{ URL::action('AdminController@formDownload') }}">
                            {{ trans('app.menus.form.download') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if (Module::hasAccess(8))
            <li class="left-menu-list-submenu" id="directory_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/dir.png')}}"/>
                    {{ trans('app.directory.title') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="directory_main">

                    @if (AccessGroup::hasAccess(58))
                    <li id="vendor_directory_list">
                        <a class="left-menu-link" href="{{ route('vendors.index') }}">
                            {{ trans('app.directory.vendors.title') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(59))
                    <li id="property_agent_directory_list">
                        <a class="left-menu-link" href="{{ route('propertyAgents.index') }}">
                            {{ trans('app.directory.property_agents.title') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Module::hasAccess(9))
            <!-- Summon Start -->
            @if (AccessGroup::hasAccess(61))
            @if (Auth::user()->isJMB())
            <li class="left-menu-list-submenu" id="summon_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-envelope"><!-- --></i>
                    {{ trans('app.summon.title') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="summon_main">

                    <li id="letter_of_reminder_list">
                        <a class="left-menu-link" href="{{ route('summon.create', Summon::LETTER_OF_REMINDER) }}">
                            {{ trans('app.summon.letter_of_reminder') }}
                        </a>
                    </li>

                    <li id="letter_of_demand_list">
                        <a class="left-menu-link" href="{{ route('summon.create', Summon::LETTER_OF_DEMAND) }}">
                            {{ trans('app.summon.letter_of_demand') }}
                        </a>
                    </li>

                    <li id="summon_list">
                        <a class="left-menu-link" href="{{ route('summon.index') }}">
                            {{ trans('app.summon.list') }}
                        </a>
                    </li>
                </ul>
            </li>

            <li id="my_point_list">
                <a class="left-menu-link" href="{{ route('myPoint.index') }}">
                    <i class="left-menu-link-icon fa fa-money"><!-- --></i>
                    {{ trans('app.my_point.title') }}
                </a>
            </li>
            @endif

            @if (Auth::user()->isLawyer() || Auth::user()->isCOBManager())
            <li class="left-menu-list-submenu" id="summon_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-envelope"><!-- --></i>
                    {{ trans('app.summon.title') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="summon_main">

                    <li id="summon_list">
                        <a class="left-menu-link" href="{{ route('summon.index') }}">
                            {{ trans('app.summon.list') }}
                        </a>
                    </li>

                </ul>
            </li>
            @endif
            @endif
            <!-- Summon End -->            
            @endif

            @if (Module::hasAccess(7))
            <li class="left-menu-list-submenu" id="change_cob_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/exchange.png')}}"/>
                    {{ trans('app.menus.change_cob') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="change_cob_main">

                    @if (AccessGroup::hasAccess(35))
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
        <div class="bottom-logo">
            <img src="{{asset('assets/common/img/odesi/logo.png')}}">
        </div>
    </div>
</nav>
<!-- END SIDE NAVIGATION -->
