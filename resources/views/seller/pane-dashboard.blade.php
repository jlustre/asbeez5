<div class="tab-pane fade show active" id="pills-dashboard" role="tabpanel">
    <div class="dashboard-home">
        <div class="title">
            <h2>Seller Dashboard</h2>
            <span class="title-leaf">
                <svg class="icon-width bg-gray">
                    <use xlink:href="{{ asset('assets/svg/leaf.svg') }}#leaf"></use>
                </svg>
            </span>
        </div>

        <div class="dashboard-user-name">
            <h6 class="text-content">Hello, <b class="text-title">Vicki E. Pope</b></h6>
            <p class="text-content">From your My Account Dashboard you have the ability to
                view a snapshot of your recent account activity and update your account
                information. Select a link below to view or edit information.</p>
        </div>

        @include('seller.edit-seller-profile')

        <div class="row g-4">

            @include('seller.trending-products')

            @include('seller.recent-orders')
        </div>
    </div>
</div>