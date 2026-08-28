@php
    $segment1 = request()->segment(1);
    $segment2 = request()->segment(2);
    $segment3 = request()->segment(3);
    $segment4 = request()->segment(4);
@endphp

<div class="app-sidebar colored">
    <div class="sidebar-header" style="background-color: #ffffff;">
        <a class="header-brand" href="{{ route('panel.admin.dashboard.index') }}">
            <div class="logo-img">
                <img height="45px" src="{{ getBackendLogo(getSetting('white_app_logo')) }}" class="header-brand-img" title="App Logo">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-chevron-left"></i></div>
        <x-button id="sidebarClose" class="nav-close"></x-button>
    </div>
    <div class="sidebar-content">
        <div class="nav-container">
            <div class="px-20px mt-3 mb-3">
                <x-input class="form-control bg-soft-secondary border-0 form-control-sm form-sidebar" type="text" name="" placeholder="{{ __('ui.left_sidebar_search_in_menu') }}" id="menu-search" oninput="menuSearch()" value="" />
            </div>
            <nav id="search-menu-navigation" class="navigation-main">
            </nav>
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ $segment2 == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('panel.admin.dashboard.index') }}" class="a-item"><i
                            class="ik ik-bar-chart-2"></i><span> @lang('ui.left_sidebar_dashboard') </span></a>
                </div>
                <div class="nav-item {{ $segment2 == 'products' ? 'active' : '' }}">
                    <a href="{{ route('panel.admin.products.index') }}" class="a-item"><i
                            class="ik ik-package"></i><span> @lang('ui.product') @lang('ui.left_sidebar_management')</span></a>
                </div>
                <div class="nav-item {{ $segment2 == 'branches' ? 'active' : '' }}">
                    <a href="{{ route('panel.admin.branches.index') }}" class="a-item"><i
                            class="ik ik-layers"></i><span>  @lang('ui.branch') @lang('ui.left_sidebar_management') </span></a>
                </div>
                <div
                    class="nav-item {{ activeClassIfRoutes([
                        'panel.admin.orders.index'
                    ], 'active open') }} has-sub">
                    <a href="#"><i class="ik ik-file-text"></i><span>@lang('ui.order_management')</span></a>

                    <div class="submenu-content">

                        {{-- Express Orders --}}
                        <a href="{{ route('panel.admin.orders.index', ['type' => App\Models\Order::TYPE_EXPRESS]) }}"
                            class="menu-item a-item {{ $segment2 == 'orders' && request()->get('type') == App\Models\Order::TYPE_EXPRESS ? 'active' : '' }}"> 
                            <span>@lang('ui.express') @lang('ui.orders')</span>
                        </a>

                        {{-- Subscription Orders --}}
                        <a href="{{ route('panel.admin.orders.index', ['type' => App\Models\Order::TYPE_SUBSCRIPTION]) }}"
                            class="menu-item a-item {{ $segment2 == 'orders' && request()->get('type') == App\Models\Order::TYPE_SUBSCRIPTION ? 'active' : '' }}">  
                            <span>@lang('ui.subscription') @lang('ui.orders')</span>
                        </a>
                    </div>
                </div>
                @if ($master_permissions->contains('admin_view_rp'))
                    @if ($master_permissions->contains('manage_administrator_view_rp'))
                        @if (getSetting('user_management_activation', @$master_setting) ||
                                getSetting('roles_and_permission_activation', @$master_setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.admin.users.index', 'panel.admin.users.show', 'panel.admin.users.create', 'panel.admin.user_log.index', 'panel.admin.roles.index', 'panel.admin.permissions.index', 'panel.admin.roles.edit', 'panel.admin.users.edit'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-users"></i><span> @lang('ui.administrator') </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('user_management_activation', @$master_setting) == 1)
                                        <a href="{{ route('panel.admin.users.index') }}?role={{ @'User' ?? '' }}"
                                            class="menu-item a-item @if (request()->has('role') && request()->get('role') == 'User') active @endif">{{ @'Customer' ?? '' }}
                                            @lang('ui.left_sidebar_management')</a>
                                        <a href="{{ route('panel.admin.users.index') }}?role={{ @'Driver' ?? '' }}"
                                        class="menu-item a-item @if (request()->has('role') && request()->get('role') == 'Driver') active @endif">{{ @'Driver' ?? '' }}
                                        @lang('ui.left_sidebar_management')</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif

                    <div
                        class="nav-item {{ activeClassIfRoutes(['panel.admin.leads.index', 'panel.admin.leads.create', 'panel.admin.leads.edit', 'panel.admin.leads.show', 'panel.admin.website-enquiries.index', 'panel.admin.website-enquiries.create', 'panel.admin.website-enquiries.edit', 'panel.admin.support-tickets.index', 'panel.admin.support-tickets.show', 'panel.admin.support-tickets.show'], 'active open') }} has-sub">
                        <a href="#"><i class="ik ik-mail"></i><span> @lang('ui.left_sidebar_contacts_enquiry') </span></a>
                        <div class="submenu-content">
                            <a href="{{ route('panel.admin.support-tickets.index') }}"
                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.support-tickets.index', 'panel.admin.support-tickets.show'], 'active') }}">
                                @lang('ui.left_sidebar_support_tickets') </a>
                        </div>
                    </div>
                    
                    <div  class="nav-item {{ activeClassIfRoutes(['panel.admin.reports.index'], 'active open') }} has-sub">
                        <a href="#"><i class="ik ik-pie-chart"></i><span> @lang('ui.left_sidebar_reports') </span></a>
                        <div class="submenu-content">
                            <a href="{{ route('panel.admin.reports.index') }}" class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.reports.index'], 'active') }}">
                                @lang('ui.order_tacking_report')
                            </a>
                        </div>
                    </div>

                    <div
                        class="nav-item {{ activeClassIfRoutes(['panel.admin.website-pages.index','panel.admin.website-pages.create'],'active open') }} has-sub">
                        <a href="#"><i class="ik ik-hard-drive"></i><span> @lang('ui.content_management') </span></a>
                        <div class="submenu-content">
                            @if (getSetting('pages_activation', @$master_setting) == 1)
                                @if ($master_permissions->contains('page_view_rp'))
                                    <a href="{{ route('panel.admin.website-pages.index') }}"
                                        class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.website-pages.index', 'panel.admin.website-pages.create', 'panel.admin.website-pages.edit'], 'active') }}">
                                        @lang('ui.left_sidebar_pages')</a>
                                @endif
                            @endif
                        </div>
                    </div>
                    @if ($master_permissions->contains('manage_setup_configuration_view_rp'))
                        @if (getSetting('basic_details_activation', @$master_setting) ||
                                getSetting('manage_general_configuration_activation', @$master_setting) ||
                                getSetting('mail_sms_configuration_activation', @$master_setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.admin.setting.index', 'panel.admin.social-login', 'panel.admin.website-pages.social-login', 'panel.admin.general.index', 'panel.admin.setting.payment', 'panel.admin.mail-sms-configuration.index', 'panel.admin.setting.payment', 'panel.admin.setting.features-activation', 'panel.admin.personalization.index', 'panel.admin.troubleshoot.index'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-settings"></i><span> @lang('ui.setup_and_configurations') </span></a>
                                <div class="submenu-content">

                                    {{-- @if (getSetting('basic_details_activation', @$master_setting) == 1 && $master_permissions->contains('control_basic_detail_view_rp'))
                                        <a href="{{ route('panel.admin.setting.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.admin.setting.index', 'active') }}">
                                            @lang('ui.basic_details') </a>
                                    @endif --}}

                                    @if (getSetting('manage_general_configuration_activation', @$master_setting) == 1)
                                        @if (
                                            $master_permissions->contains('general_setting_view_rp') ||
                                                $master_permissions->contains('currency_setting_view_rp') ||
                                                $master_permissions->contains('date_time_setting_view_rp') ||
                                                $master_permissions->contains('notification_setting_view_rp') ||
                                                $master_permissions->contains('troubleshoot_setting_view_rp'))
                                            <a href="{{ route('panel.admin.general.index',['active'=> "general"]) }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.admin.general.index', 'active') }}">
                                                @lang('ui.general_configuration') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('mail_sms_configuration_activation', @$master_setting) == 1)
                                        @if (
                                            $master_permissions->contains('email_setting_view_rp') ||
                                                $master_permissions->contains('sms_setting_view_rp') ||
                                                $master_permissions->contains('fcm_setting_view_rp'))
                                            <a href="{{ route('panel.admin.mail-sms-configuration.index', ['name' => 'mail_config']) }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.admin.mail-sms-configuration.index', 'active') }}">
                                                @lang('ui.api_configuration') </a>
                                        @endif
                                    @endif

                                    @if (getSetting('toggling_theme_activation', @$master_setting) == 1)
                                        {{-- @if ($master_permissions->contains('toggling_theme_activation') && env('DEV_MODE') == 1) --}}
                                        <a href="{{ route('panel.admin.personalization.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.admin.personalization.index', 'active') }}">
                                            @lang('ui.theme') </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </nav>
        </div>
    </div>
</div>
