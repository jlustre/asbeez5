<div class="header-nav-middle">
    <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky">
        <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
            <div class="offcanvas-header navbar-shadow">
                <h5>Menu</h5>
                <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                    @include('partials.menus.home-menu')

                    {{-- @include('partials.menus.shop-menu') --}}

                    @include('partials.menus.product-menu')

                    @include('partials.menus.mega-menu')

                    {{-- @include('partials.menus.blog-menu') --}}

                    {{-- @include('partials.menus.pages-menu') --}}

                    @include('partials.menus.seller-menu')
                </ul>
            </div>
        </div>
    </div>
</div>