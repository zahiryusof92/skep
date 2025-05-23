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
            @if(!Auth::user()->isMPS())
            <li class="left-menu-list-link" id="home">
                <a class="left-menu-link" href="{{ URL::action('HomeController@home') }}">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/home.png')}}"/>
                    {{ trans('app.menus.home') }}
                </a>
            </li>
            @endif

            @if (Module::hasAccess(1))
            <li class="left-menu-list-submenu" id="cob_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/setting.png')}}"/>
                    {{ trans('app.menus.cob.maintenance') }}
                    @if(FileDrafts::getTotalPending() > 0)
                        <span class="label label-danger">!</span>
                    @endif
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
                    
                    @if(!Auth::user()->isMPS())
                    @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                    <li id="cob_draft_list">
                        <a class="left-menu-link" href="{{URL::action('DraftController@fileList')}}">
                            {{ trans('app.menus.cob.file_draft_list') }}
                            <span class="label label-danger"> {{ FileDrafts::getTotalPending() . ' ' . trans('pending') }}</span>
                        </a>
                    </li>
                    @endif
                    @endif

                    <li id="cob_draft_reject_list">
                        <a class="left-menu-link" href="{{ route('file.draft.reject.index') }}">
                            {{ trans('app.menus.cob.file_reject_list') }}
                        </a>
                    </li>

                    @if (Auth::user()->getAdmin())
                    <li id="cob_sync">
                        <a class="left-menu-link" href="{{URL::action('CobSyncController@index')}}">
                            COB Sync
                        </a>
                    </li>
                    @if ((URL::to('/') == 'https://skep.lphs.gov.my' || URL::to('/') == 'https://selangor.ecob.my') || (URL::to('/') == 'https://test.odesi.tech'))
                    <li id="mps_sync">
                        <a class="left-menu-link" href="{{URL::action('MPSSyncController@index')}}">
                            MPS Sync
                        </a>
                    </li>
                    @endif
                    <li id="export_file">
                        <a class="left-menu-link" href="{{URL::action('ExportController@exportCOBFile')}}">
                            Export COB Files
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Module::hasAccess(1) && !Auth::user()->isMPS())
            <li class="left-menu-list-submenu" id="finance_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/finance.png')}}"/>
                    {{ trans('app.menus.finance.maintenance') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="finance_main">

                    @if (AccessGroup::hasAccess(37))
                    <li id="add_finance_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@addFinanceFileList')}}">
                            {{ trans('app.menus.finance.add_finance_file_list') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(38))
                    <li id="finance_file_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@financeList')}}">
                            {{ trans('app.menus.finance.finance_file_list') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(39))
                    <li id="finance_support_list">
                        <a class="left-menu-link" href="{{URL::action('FinanceController@financeSupport')}}">
                            {{ trans('app.menus.finance.finance_support') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
            
            @if (AccessGroup::hasAccess(45) && ((!empty(Session::get('admin_cob')) && !in_array(Session::get('admin_cob'),[2, 10])) || (!in_array(Auth::user()->company_id,[2, 10]) && empty(Session::get('admin_cob'))) || (Auth::user()->company_id > 0 && !in_array(Auth::user()->company_id,[2, 10]))))
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

            @if (AccessGroup::hasAccessModule('COB Letter'))
            <li class="left-menu-list-link" id="cob_letter_list">
                <a class="left-menu-link" href="{{ route('cob_letter.index') }}">
                    <i class="left-menu-link-icon fa fa-paper-plane" aria-hidden="true"></i>
                    {{ trans('app.menus.cob_letter.name') }}
                </a>
            </li>
            @endif

            @if ((Auth::user()->getAdmin() || (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name == "MPS")) && AccessGroup::hasAccessModule('EPKS'))
            <li class="left-menu-list-submenu" id="epks_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-recycle"><!-- --></i>
                    <span id="recycle">{{ trans('app.menus.epks.name1') }}</span> &nbsp;<span class="label left-menu-label label-danger">@if(Epks::self()->notDraft()->where('epks.status', '!=', Epks::REJECTED)->count()) ! @endif</span>
                </a>
                <ul class="left-menu-list list-unstyled" id="epks_main">
                    <li class="left-menu-list-link" id="epks_create">
                        <a class="left-menu-link" href="{{ route('epks.create') }}">
                            {{ trans('app.menus.epks.create') }}
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="epks_list">
                        <a class="left-menu-link" href="{{ route('epks.index') }}">
                            {{ trans('app.menus.epks.review') }} &nbsp;<span class="label left-menu-label label-danger">&nbsp;{{ trans('app.menus.epks.pending', ['count'=> Epks::self()->notDraft()->where('epks.status', '!=', Epks::REJECTED)->count()]) }}</span>
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="epks_approval">
                        <a class="left-menu-link" href="{{ route('epks.approval') }}">
                            {{ trans('app.menus.epks.approval') }}
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="epks_draft">
                        <a class="left-menu-link" href="{{ route('epks.draft') }}">
                            {{ trans('app.menus.epks.draft') }}
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="epks_statement">
                        <a class="left-menu-link" href="{{ route('epksStatement.index') }}">
                            {{ trans('app.menus.epks_statement.name') }}
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if (Module::hasAccessModule("e-Service"))
            {{-- e-service --}}
            @if ((Auth::user()->getAdmin() || (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name == "MBPJ")))
            <li class="left-menu-list-submenu" id="eservice_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-file-text"><!-- --></i>
                    <span id="recycle">{{ trans('app.menus.eservice.name1') }}</span> &nbsp;<span class="label left-menu-label label-danger">@if(EServiceOrder::self()->notDraft()->where('eservices_orders.status', '!=', EServiceOrder::REJECTED)->count()) ! @endif</span>
                </a>
                <ul class="left-menu-list list-unstyled" id="eservice_main">
                    @if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper())
                    <li class="left-menu-list-link" id="eservice_create">
                        <a class="left-menu-link" href="{{ route('eservice.create', '') }}">
                            {{ trans('app.menus.eservice.create') }}
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="eservice_draft">
                        <a class="left-menu-link" href="{{ route('eservice.draft') }}">
                            {{ trans('app.menus.eservice.draft') }}
                        </a>
                    </li>
                    @endif
                    <li class="left-menu-list-link" id="eservice_list">
                        <a class="left-menu-link" href="{{ route('eservice.index') }}">
                            {{ trans('app.menus.eservice.review') }} &nbsp;<span class="label left-menu-label label-danger">&nbsp;{{ trans('app.menus.eservice.pending', ['count'=> EServiceOrder::self()->notDraft()->where('eservices_orders.status', '!=', EServiceOrder::REJECTED)->count()]) }}</span>
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="eservice_approved">
                        <a class="left-menu-link" href="{{ route('eservice.approved') }}">
                            {{ trans('app.menus.eservice.approved') }}
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="eservice_rejected">
                        <a class="left-menu-link" href="{{ route('eservice.rejected') }}">
                            {{ trans('app.menus.eservice.rejected') }}
                        </a>
                    </li>
                    @if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper())
                    <li class="left-menu-list-link" id="eservice_payment_history">
                        <a class="left-menu-link" href="{{ route('eservice.paymentHistory') }}">
                            {{ trans('app.menus.eservice.payment_history') }}
                        </a>
                    </li>   
                    @endif             
                    @if (Auth::user()->getAdmin() || Auth::user()->isCOB())
                    <li class="left-menu-list-link" id="eservice_report">
                        <a class="left-menu-link" href="{{ route('eservice.report') }}">
                            {{ trans('app.menus.eservice.report') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @endif

            @if (Module::hasAccessModule("API Client"))
            <li class="left-menu-list-submenu" id="api_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-american-sign-language-interpreting" aria-hidden="true"></i>
                    {{ trans('app.menus.api.name') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="api_client_main">

                    @if (AccessGroup::hasAccessModule("API Client"))
                    <li id="api_client_list">
                        <a class="left-menu-link" href="{{ route('clients.index') }}">
                            {{ trans('app.menus.api.client') }}
                        </a>
                    </li>
                    @endif
                    
                    @if (AccessGroup::hasAccessModule("API Building"))
                    <li id="api_building_list">
                        <a class="left-menu-link" href="{{ route('clients.building.index') }}">
                            {{ trans('app.menus.api.building') }}
                        </a>
                    </li>
                    <li id="api_building_log_list">
                        <a class="left-menu-link" href="{{ route('clients.building.log') }}">
                            {{ trans('app.menus.api.building_log') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if (Module::hasAccess(2))
            <li class="left-menu-list-submenu" id="admin_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/user.png')}}"/>
                    {{ trans('app.menus.administration.administration') }}
                    @if(User::where('status', 0)->where('is_deleted', 0)->count() > 0)
                        <span class="label label-danger">!</span>
                    @endif
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

                    @if (AccessGroup::hasAccessModule('liquidator'))
                    <li id="liquidator_list">
                        <a class="left-menu-link" href="{{URL::action('SettingController@liquidator')}}">
                            {{ trans('app.menus.master.liquidator') }}
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

                    @if (AccessGroup::hasAccess(45) && ((!empty(Session::get('admin_cob')) && !in_array(Session::get('admin_cob'),[2, 10])) || (!in_array(Auth::user()->company_id,[2, 10]) && empty(Session::get('admin_cob'))) || (Auth::user()->company_id > 0 && !in_array(Auth::user()->company_id,[2, 10]))))
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

                    @if (AccessGroup::hasAccessModule('e-Service') && AccessGroup::hasAccessModule('e-Service Pricing'))
                    @if ((Auth::user()->getAdmin() || (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name == "MBPJ")))
                    <li id="eservice_price_list">
                        <a class="left-menu-link" href="{{ route('eservicePrice.index') }}">
                            {{ trans('app.menus.master.eservice_price') }}
                        </a>
                    </li>
                    @endif
                    @endif
                    
                    @if (AccessGroup::hasAccessModule('Postponed AGM Reason'))
                    <li id="postpone_agm_reason_list">
                        <a class="left-menu-link" href="{{ route('statusAGMReason.index') }}">
                            {{ trans('app.menus.master.postpone_agm_reason') }}
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif

            @if (Module::hasAccess(4) && (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper()))
            <li class="left-menu-list-submenu" id="reporting_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <img class="left-menu-link-icon" src="{{asset('assets/common/img/icon/report.png')}}"/>
                    {{ trans('app.menus.reporting.reporting') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="reporting_main">

                    @if (AccessGroup::hasAccess(24))
                    <li id="audit_trail_list">
                        <a class="left-menu-link" href="{{ route('reporting.log.index') }}">
                            {{ trans('app.menus.reporting.audit_trail') }}
                        </a>
                    </li>
                    <li id="audit_logon_list">
                        <a class="left-menu-link" href="{{ route('reporting.logon.index') }}">
                            {{ trans('app.menus.reporting.audit_logon') }}
                        </a>
                    </li>
                    <li id="audit_logon_old_list">
                        <a class="left-menu-link" href="{{ route('reporting.logon.old.index') }}">
                            {{ trans('app.menus.reporting.audit_logon') }} -  {{ trans('app.forms.old') }}
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

                    <li id="strata_profile_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@strataProfileV2') }}">
                            {{ trans('app.menus.reporting.strata_profile_v2') }}
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

                    @if (AccessGroup::hasAccess(45) && ((!empty(Session::get('admin_cob')) && !in_array(Session::get('admin_cob'),[2, 10])) || (!in_array(Auth::user()->company_id,[2, 10]) && empty(Session::get('admin_cob'))) || (Auth::user()->company_id > 0 && !in_array(Auth::user()->company_id,[2, 10]))))
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

                    @if ((Auth::user()->getAdmin() || (!Auth::user()->getAdmin() && Auth::user()->getCOB->short_name == "MPS")) && AccessGroup::hasAccess(64))
                    <li id="epks_report_list">
                        <a class="left-menu-link" href="{{ URL::action('ReportController@epks') }}">
                            {{ trans('app.menus.reporting.epks') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccess(65))
                    <li id="generate_report_list">
                        <a class="left-menu-link" href="{{ route('report.generate.index') }}">
                            {{ trans('app.menus.reporting.generate') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccessModule("Statistics Report"))
                    <li id="statistic_report_list">
                        <a class="left-menu-link" href="{{ route('report.statistic.index') }}">
                            {{ trans('app.menus.reporting.statistic') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccessModule("Email Log"))
                    <li id="email_log_list">
                        <a class="left-menu-link" href="{{ route('email_log.index') }}">
                            {{ trans('app.menus.reporting.email_log') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccessModule("Movement of File"))
                    <li id="file_movement_report_list">
                        <a class="left-menu-link" href="{{ route('report.fileMovement.index') }}">
                            {{ trans('app.menus.reporting.file_movement') }}
                        </a>
                    </li>
                    @endif

                    @if (AccessGroup::hasAccessModule("Finance / Month"))
                    <li id="finance_report_list">
                        <a class="left-menu-link" href="{{ route('report.finance.index') }}">
                            {{ trans('app.menus.reporting.finance') }}
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
                    @if (Auth::user()->getCOB->short_name == "MPKJ")
                    <li id="agmminutesub_list">
                        <a class="left-menu-link" href="{{URL::action('AGMMinuteController@index')}}">
                            {{ trans('app.menus.agm.upload_of_minutes') }}
                        </a>
                    </li>
                    @else
                    <li id="agmminutesub_list">
                        <a class="left-menu-link" href="{{URL::action('AgmController@minutes')}}">
                            {{ trans('app.menus.agm.upload_of_minutes') }}
                        </a>
                    </li>
                    @endif
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

            @if (AccessGroup::hasAccessModule('Postponed AGM'))
            @if ((Auth::user()->getAdmin() || Auth::user()->isCOB()) || Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper())
            <li class="left-menu-list-submenu" id="agm_postpone_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-file-text"><!-- --></i>
                    <span>{{ trans('app.menus.agm_postpone.name') }}</span>
                    @if (PostponedAGM::self()->notDraft()->where('postponed_agms.status', '!=', PostponedAGM::REJECTED)->count())
                    &nbsp;<span class="label left-menu-label label-danger">!</span>
                    @endif
                   </a>
                <ul class="left-menu-list list-unstyled" id="agm_postpone_main">
                    @if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper())
                    <li class="left-menu-list-link" id="agm_postpone_create">
                        <a class="left-menu-link" href="{{ route('statusAGM.create') }}">
                            {{ trans('app.menus.agm_postpone.create') }}
                        </a>
                    </li>
                    @endif
                    <li class="left-menu-list-link" id="agm_postpone_list">
                        <a class="left-menu-link" href="{{ route('statusAGM.index') }}">
                            {{ trans('app.menus.agm_postpone.review') }} &nbsp;
                            <span class="label left-menu-label label-danger">
                                {{ trans('app.menus.agm_postpone.pending', ['count'=> PostponedAGM::self()->notDraft()->where('postponed_agms.status', '!=', PostponedAGM::REJECTED)->count()]) }}
                            </span>
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="agm_postpone_approved">
                        <a class="left-menu-link" href="{{ route('statusAGM.approved') }}">
                            {{ trans('app.menus.agm_postpone.approved') }}
                        </a>
                    </li>
                    <li class="left-menu-list-link" id="agm_postpone_rejected">
                        <a class="left-menu-link" href="{{ route('statusAGM.rejected') }}">
                            {{ trans('app.menus.agm_postpone.rejected') }}
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endif

            @if (AccessGroup::hasAccessModule('Defect Liability Period'))
            @if ((Auth::user()->getAdmin() || Auth::user()->isCOB()) || Auth::user()->isDeveloper())            
            <li class="left-menu-list-submenu" id="dlp_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-clock-o"><!-- --></i>
                    <span>{{ trans('app.menus.dlp.name') }}</span>
                </a>
                <ul class="left-menu-list list-unstyled" id="dlp_main">
                    <li class="left-menu-list-link" id="dlp_deposit">
                        <a class="left-menu-link" href="{{ route('dlp.deposit') }}">
                            {{ trans('app.menus.dlp.deposit') }}
                        </a>
                    </li>
                    {{-- <li class="left-menu-list-link" id="dlp_progress">
                        <a class="left-menu-link" href="{{ route('dlp.progress') }}">
                            {{ trans('app.menus.dlp.progress') }}
                        </a>
                    </li> --}}
                    {{-- <li class="left-menu-list-link" id="dlp_period">
                        <a class="left-menu-link" href="{{ route('dlp.period') }}">
                            {{ trans('app.menus.dlp.period') }}
                        </a>
                    </li> --}}
                    @if (AccessGroup::hasAccess(31))
                    <li id="agmpurchasesub_list">
                        <a class="left-menu-link" href="{{ URL::action('AgmController@purchaser') }}">
                            {{ trans('app.menus.agm.purchaser') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @endif

            @if (AccessGroup::hasAccessModule('Ledger'))
            @if (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper())
            <li class="left-menu-list-link" id="ledger">
                <a class="left-menu-link" href="{{ route('ledger.index') }}">
                    <i class="left-menu-link-icon fa fa-book"><!-- --></i>
                    {{ trans('app.menus.ledger.name') }}
                </a>
            </li>
            @endif
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
            @if ((Auth::user()->getAdmin() || (!Auth::user()->getAdmin() && in_array(Auth::user()->getCOB->short_name, ['MPS', 'MPAJ']))) && (Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()))
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

            @if(!Auth::user()->getAdmin() && in_array(Auth::user()->getCOB->short_name, ['MPS', 'MPAJ']))
            <li id="my_point_list">
                <a class="left-menu-link" href="{{ route('myPoint.index') }}">
                    <i class="left-menu-link-icon fa fa-money"><!-- --></i>
                    {{ trans('app.my_point.title') }}
                </a>
            </li>
            @endif
            @endif
            @if ((Auth::user()->isHR() || Auth::user()->getAdmin() || Auth::user()->isLawyer() || Auth::user()->isCOBManager()) && (Auth::user()->getAdmin() || (!Auth::user()->getAdmin() && in_array(Auth::user()->getCOB->short_name, ['MPS', 'MPAJ']))))
            <li class="left-menu-list-submenu" id="summon_panel">
                <a class="left-menu-link" href="javascript: void(0);">
                    <i class="left-menu-link-icon fa fa-envelope"><!-- --></i>
                    {{ trans('app.summon.title') }}
                </a>
                <ul class="left-menu-list list-unstyled" id="summon_main">
                    @if(Auth::user()->isLawyer() || Auth::user()->isCOBManager())
                    <li id="summon_list">
                        <a class="left-menu-link" href="{{ route('summon.index') }}">
                            {{ trans('app.summon.list') }}
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->isHR())
                    <li id="summon_list">
                        <a class="left-menu-link" href="{{ URL::action('SummonController@councilSummonList') }}">
                            {{ trans('app.summon.list') }}
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->isHR() || Auth::user()->getAdmin() || Auth::user()->isCOBManager() )
                        <li id="summon_list">
                            <a class="left-menu-link" href="{{ URL::action('SummonController@paidListing') }}">
                                {{ trans('app.summon.paid') }}
                            </a>
                        </li>
                    @endif

                </ul>
            </li>
            @endif
            @endif
            <!-- Summon End -->            
            @endif
            
            @if (AccessGroup::hasAccess(61))
            @if(((Auth::user()->isJMB() || Auth::user()->isMC() || Auth::user()->isDeveloper()) && in_array(Auth::user()->getCOB->short_name, ['MPS', 'MPAJ'])) || Auth::user()->isHR() || Auth::user()->getAdmin() || Auth::user()->isCOBPaid() && (Auth::user()->getAdmin() || (!Auth::user()->getAdmin() && in_array(Auth::user()->getCOB->short_name, ['MPS', 'MPAJ']))))
                @if(!Auth::user()->isCOBPaid())
                <li id="transaction_list">
                    <a class="left-menu-link" href="{{ URL::action('TransactionController@index') }}">
                        <i class="left-menu-link-icon fa fa-credit-card"><!-- --></i>
                        {{ trans('app.transaction.title') }}
                    </a>
                </li>
                @endif
            @endif
            @endif

            @if (AccessGroup::hasAccessModule("Notification"))
            <li id="notification_list">
                <a class="left-menu-link" href="{{ route('notification.index') }}">
                    <i class="left-menu-link-icon fa fa-bell"><!-- --></i>
                    {{ trans('app.menus.reporting.notification') }}
                </a>
            </li>
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
                    $jmb = Company::where('is_active', 1)->where('short_name', '!=', '')->where('is_hidden', false)->where('is_deleted', 0)->orderBy('short_name')->get();
                    ?>

                    @foreach ($jmb as $cob)
                    <li id="{{ $cob->short_name . "_list" }}" class="{{ (Session::get('admin_cob') == $cob->id ? 'left-menu-list-active' : '') }}">
                        <a class="left-menu-link" href='{{ URL::action('UserController@changeCOB', $cob->id) }}'>{{ strtoupper($cob->short_name) }}</a>
                    </li>
                    @endforeach
                    @endif

                </ul>
            </li>
            @endif
        </ul>

        @if ($company->short_name != 'MBS')
        <div class="bottom-logo">
            <img src="{{asset('assets/common/img/odesi/logo.png')}}">
        </div>
        @endif

    </div>
</nav>
<!-- END SIDE NAVIGATION -->
