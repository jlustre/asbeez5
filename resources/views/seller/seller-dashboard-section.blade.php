<section class="user-dashboard-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-xxl-3 col-lg-4">
                <div class="dashboard-left-sidebar">
                    <div class="close-button d-flex d-lg-none">
                        <button class="close-sidebar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="profile-box">
                        <div class="cover-image">
                            <img src="{{ asset('assets/images/inner-page/cover-img.jpg') }}"
                                class="img-fluid blur-up lazyload" alt="">
                        </div>

                        <div class="profile-contain">
                            <div class="profile-image">
                                <div class="position-relative">
                                    <img src="{{ asset('assets/images/vendor-page/logo.png') }}"
                                        class="blur-up lazyload update_img" alt="">
                                </div>
                            </div>

                            <div class="profile-name">
                                <h3>{{ auth()->user()->username }}</h3>
                                <h6 class="text-content">{{ auth()->user()->email }}</h6>
                            </div>
                        </div>
                    </div>

                    @include('seller.sidebar-menu')
                </div>
            </div>

            <div class="col-xxl-9 col-lg-8">
                <button class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">Show
                    Menu</button>
                <div class="dashboard-right-sidebar">
                    <div class="tab-content" id="pills-tabContent">
                        @include('seller.pane-dashboard')

                        @include('seller.pane-products')

                        @include('seller.pane-orders')

                        @include('seller.pane-profile')

                        @include('seller.pane-settings')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>