@php
    $notifications_count = App\Models\Notification::select('user_id', 'id', 'is_read')
        ->where('user_id', auth()->id())
        ->latest()
        ->where('is_read', 0)
        ->limit(10)
        ->count();
    $notifications = App\Models\Notification::where('user_id', auth()->id())
        ->latest()
        ->where('is_read', 0)
        ->limit(5)
        ->get();
@endphp
<style>
    .img-custom-width {
        width: 35px !important;
        height: 35px !important;
        object-fit: cover !important;
    }

    .notification-icon {
        min-height: 8px;
        min-width: 8px;
        margin-right: 12px;
        background-color: #e43f52 !important;
        border-radius: 50%;
        display: inline-block;
        position: absolute;
        right: -4px !important;
        top: 3px !important;
    }

    .align-middle {
        font-size: 28px !important;
    }

    .icon {
        color: grey;
    }

    .profile-dropdown {
        padding: 2px 4px;
    }

    .notification-popup {
        position: absolute;
        right: 0;
        top: 306%;
        width: 300px;
        /* max-height: 400px; */
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 1010;
        /* overflow-y: auto; */
        border-radius: 4px;
        box-sizing: border-box;
    }

    .notification-bell {
        position: relative;
        background-color: #f6f7f9;
        padding: 5px 10px;
    }

    .notification-bell:hover {
        background-color: #e1e1e1;
    }

    .notification-check-icon {
        font-size: 1.3rem;
        color: #545454;
    }

    .media-body {
        /* margin-left: 15px; */
        line-height: 1.4;
        /* Adjusted line-height for better spacing */
    }

    .media-body-content {
        color: black !important;
        font-weight: 700;
        font-size: 16px;
        margin-top: 0;
    }

    .notification-popup h4 {
        font-weight: 500;
        margin: 0;
        padding: 12px 15px;
        font-size: 16px;
    }

    .notifications-wrap {
        padding: 10px 15px;
        background-color: #f9f9f9;
        border-top: 1px solid #e3e3e3;
        border-bottom: 1px solid #e3e3e3;
        max-height: 45vh;
        overflow-y: auto;
    }
</style>
<header class="header-top" header-theme="light">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="top-menu d-flex align-items-center">
                <x-button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></x-button>

                <a href="javascript:void(0)" onclick="window.history.back();" title="Back" type="button" id=""
                    class="nav-link bg-gray mr-1"><i class="ik ik-arrow-left"></i></a>

                <x-button type="button" id="navbar-fullscreen" title="@lang('ui.full_screen')" class="nav-link"><i
                        class="ik ik-maximize"></i></x-button>
                <a href="{{ url('/') }}" type="button" id="" title="Go to Home"
                    class="nav-link bg-gray ml-1"><i class="ik ik-home"></i></a>
                @if (getSetting('toggling_broadcast_activation', @$master_setting))
                    @if (Route::is('panel.admin.dashboard.index'))
                        <x-button type="button" class="nav-link bg-gray ml-1" data-toggle="modal"
                            data-target="#addBrodcast">
                            <i class="ik ik ik-radio" title="Broadcast"></i>
                        </x-button>
                    @endif
                @endif
            </div>
            <div class="top-menu d-flex align-items-center">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <img class="avatar img-custom-width"
                            src="{{ auth()->user() && auth()->user()->avatar ? auth()->user()->avatar : asset('backend/default/default-avatar.png') }}"
                            alt="">
                        <span class="user-name font-weight-bolder"
                            style="top: -0.8rem;position: relative;margin-left: 8px;">{{ auth()->user()->full_name }}
                            <span class="text-muted"
                                style="font-size: 10px;position: absolute;top: 16px;left: 0px;">{{ auth()->user()->role_name }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('panel.admin.profile.index') }}"><i
                                class="ik ik-user dropdown-icon"></i> @lang('ui.profile')</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a href="" onClick="event.preventDefault();this.closest('form').submit();"
                                class="dropdown-item text-danger fw-700">
                                <i class="ik ik-power dropdown-icon text-danger"></i> @lang('Logout')
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

