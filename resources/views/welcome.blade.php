<!DOCTYPE html>
<html lang="en">

@include('partials.head2')

<body class="theme-color-1">
    <!-- Loader Start -->
    @include('partials.loader')
    <!-- Loader End -->

    <!-- Header Start -->
    @include('partials.header')
    <!-- Header End -->

    <!-- mobile fix menu start -->
    @include('partials.mobile-fix-menu')
    <!-- mobile fix menu end -->

    <!-- Home Section Start -->
    @include('partials.home-section')
    <!-- Home Section End -->

    <!-- Category Section Start -->
    @include('partials.category-section')
    <!-- Category Section End -->

    <!-- feature section start -->
    @include('partials.feature-section')
    <!-- feature section end -->

    <!-- Product Section Start -->
    @include('partials.product-section')
    <!-- Product Section End -->

    <!-- Blog Section Start -->
    @include('about.blog-section')
    <!-- Blog Section End -->

    <!-- Footer Section Start -->
    @include('partials.footer-section')
    <!-- Footer Section End -->

    <!-- Quick View Modal Box Start -->
    @include('partials.quick-view-modal')
    <!-- Quick View Modal Box End -->

    <!-- Location Modal Start -->
    @include('partials.location-modal')
    <!-- Location Modal End -->

    <!-- Cookie Bar Box Start -->
    {{-- @include('partials.cookie-bar-box') --}}
    <!-- Cookie Bar Box End -->

    <!-- Deal Box Modal Start -->
    @include('partials.deal-box-modal')
    <!-- Deal Box Modal End -->

    <!-- Tap to top and theme setting button start -->
    @include('partials.tap-to-top')
    <!-- Tap to top and theme setting button end -->

    <!-- Bg overlay Start -->
    <div class="bg-overlay"></div>
    <!-- Bg overlay End -->

    @include('partials.scripts')
</body>

</html>