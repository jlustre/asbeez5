<div class="col-xxl-9 col-lg-8">
    <button class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">Show
        Menu</button>
    <div class="dashboard-right-sidebar">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-dashboard" role="tabpanel">
                <div class="dashboard-home">
                    <div class="title">
                        <h2>My Dashboard</h2>
                        <span class="title-leaf">
                            <svg class="icon-width bg-gray">
                                <use xlink:href="{{ asset('assets/svg/leaf.svg') }}#leaf"></use>
                            </svg>
                        </span>
                    </div>

                    <div class="dashboard-user-name">
                        <h6 class="text-content">Hello, <b class="text-title">{{
                                optional(auth()->user()->profile)->fullName() ?? auth()->user()->username
                                }}</b></h6>
                        <p class="text-content">From your Dashboard you have the ability to
                            view a snapshot of your recent account activity and update your account
                            information. Select a link below to view or edit information.</p>
                    </div>

                    @include('user.dashboard.total-box')

                    @include('user.dashboard.account-info')
                </div>
            </div>

            @include('user.dashboard.pane-wishlist')

            @include('user.dashboard.pane-order')

            @include('user.dashboard.pane-address')

            @include('user.dashboard.pane-card')

            @include('user.dashboard.pane-profile')

            @include('user.dashboard.pane-download')

            @include('user.dashboard.pane-privacy')
        </div>
    </div>
</div>