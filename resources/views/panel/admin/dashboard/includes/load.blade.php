<div class="row">
    <div class="col-lg-12 col-sm-12">
        @if (getSetting('order_activation', @$master_setting) == 1)
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="statistic-header">
                        <h5>@lang('ui.order_management')</h5>
                    </div>
                </div>
            </div>
            <div class="statistics-grid">
                @foreach (\App\Models\Order::STATUSES as $key => $order)
                    <a class="" href="{{ route('panel.admin.orders.index', ['status' => $key]) }}">
                        <div class="card m-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="state">
                                        <h3 class="text-secondary">{{ getOrderStatusCount($key) }}</h3>
                                        <h6 class="card-subtitle text-dark fw-700 mb-0">
                                            {{ isset($order['label']) ? $order['label'] : '' }}</h6>
                                    </div>
                                    <div class="col-auto icon-size">
                                        <i
                                            class="{{ isset($order['icon']) ? $order['icon'] : '' }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.subscription_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid row">
            <!-- User Subscriptions -->
            <a class="c" href="{{ route('panel.admin.user-subscriptions.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">
                                    {{ isset($stats['userSubscriptionCount']) ? $stats['userSubscriptionCount'] : '' }}
                                </h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0"> @lang('ui.user_subscription')</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-users text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Subscriptions -->
            <a class="" href="{{ route('panel.admin.subscriptions.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">
                                    {{ isset($stats['subscriptionCount']) ? $stats['subscriptionCount'] : '' }}</h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0"> @lang('ui.subscription')</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-file-alt text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.payouts_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\Payout::STATUSES as $key => $payout)
                <a class="" href="{{ route('panel.admin.payouts.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getPayoutStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0 blink-light-effect">
                                        {{ isset($payout['label']) ? $payout['label'] : '' }} <div class="">
                                            @if ($key == 0)
                                                <div class="blinking-light"></div>
                                            @endif
                                        </div>
                                    </h6>

                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ isset($payout['icon']) ? $payout['icon'] : '' }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.item_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid row">
            <a class="" href="{{ route('panel.admin.items.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">{{ isset($stats['itemCount']) ? $stats['itemCount'] : '' }}
                                </h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0"> @lang('ui.item')</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-users text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.administrator_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach ($roles as $key => $role)
                <a class=""
                    href="{{ route('panel.admin.users.index', ['role' => @$role->display_name ?? '']) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getUserCountByRole(@$role->display_name ?? '') }}
                                    </h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0">{{ @$role->display_name ?? '' }}
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ @$order['icon'] ?? '' }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.website_enquiry_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\WebsiteEnquiry::STATUSES as $key => $enquiry)
                <a class="" href="{{ route('panel.admin.website-enquiries.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getEnquiryStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0">
                                        {{ isset($enquiry['label']) ? $enquiry['label'] : '' }}
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ isset($enquiry['icon']) ? $enquiry['icon'] : '' }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.support_tickets_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\SupportTicket::STATUSES as $key => $ticket)
                <a class="" href="{{ route('panel.admin.support-tickets.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getSupportTicketStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0 d-flex">
                                        {{ isset($ticket['label']) ? $ticket['label'] : '' }}
                                        <div class="ml-2">
                                            @if ($key == 0)
                                                <div class="blinking-light"></div>
                                            @endif
                                        </div>
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ isset($ticket['icon']) ? $ticket['icon'] : '' }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.lead_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\Lead::STATUSES as $key => $lead)
                <a class="" href="{{ route('panel.admin.leads.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getLeadStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0 d-flex">
                                        {{ isset($lead['label']) ? $lead['label'] : '' }}
                                        <div class="ml-2">
                                            @if ($key == 1)
                                                <div class="blinking-light"></div>
                                            @endif
                                        </div>
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ isset($lead['icon']) ? $lead['icon'] : '' }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('ui.blogs')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid row">
            <!-- User Subscriptions -->
            <a class="" href="{{ route('panel.admin.blogs.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">{{ isset($stats['blogCount']) ? $stats['blogCount'] : '' }}
                                </h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0"> @lang('ui.blogs')</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-blog text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
