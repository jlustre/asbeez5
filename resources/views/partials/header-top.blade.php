<div class="header-top" style="background-color: #d99f46;">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-xxl-3 d-xxl-block d-none">
                <div class="top-left-header">
                    <i class="iconly-Location icli text-white"></i>
                    <span class="text-white">{{ config('company.address') }}</span>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-9 d-lg-block d-none">
                <div class="header-offer">
                    @include('partials.notification-slider')
                </div>
            </div>

            <div class="col-lg-3">
                <ul class="about-list right-nav-about">
                    @include('partials.language-selector')
                    @include('partials.currency-selector')
                </ul>
            </div>
        </div>
    </div>
</div>