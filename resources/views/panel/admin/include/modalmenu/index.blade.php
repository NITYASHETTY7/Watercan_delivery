<div class="modal fade apps-modal" id="appsModal" tabindex="-1" role="dialog" aria-labelledby="appsModalLabel"
    aria-hidden="true" data-backdrop="false">
    <x-button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
            class="ik ik-x-circle"></i></x-button>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="quick-search">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 ml-auto mr-auto">
                            <div class="input-wrap">
                                <x-input type="text" id="quick-search" class="form-control" placeholder="{{ __('ui.left_sidebar_search') }}" value="" name="" />
                                <i class="ik ik-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="container">
                    <div class="apps-wrap">
                        <div class="app-item">
                            <a href="#"><i class="ik ik-bar-chart-2"></i><span> @lang('ui.left_sidebar_dashboard') </span></a>
                        </div>
                        <div class="app-item dropdown">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ik ik-command"></i><span> @lang('ui.ui') </span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#"> @lang('ui.actions') </a>
                                <a class="dropdown-item" href="#"> @lang('ui.another_action') </a>
                                <a class="dropdown-item" href="#"> @lang('ui.sonething_else') </a>
                            </div>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-mail"></i><span> @lang('ui.message') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-users"></i><span> @lang('ui.account') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-shopping-cart"></i><span> @lang('ui.sales') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-briefcase"></i><span> @lang('ui.purchase') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-server"></i><span> @lang('ui.menus') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-clipboard"></i><span> @lang('ui.pages') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-message-square"></i><span> @lang('ui.chats') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-map-pin"></i><span> @lang('ui.contacts') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-box"></i><span> @lang('ui.blocks') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-calendar"></i><span> @lang('ui.events') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-bell"></i><span> @lang('ui.notifications') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-pie-chart"></i><span> @lang('ui.left_sidebar_reports') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-layers"></i><span> @lang('ui.tasks') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-edit"></i><span> @lang('ui.blogs') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-settings"></i><span> @lang('ui.settings') </span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-more-horizontal"></i><span> @lang('ui.more') </span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
