<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('partials.head2')

<body class="theme-color-1">
    <!-- Loader Start -->
    @include('partials.loader')
    <!-- Loader End -->

    <!-- Header Start -->
    @include('partials.header2')
    <!-- Header End -->

    <!-- mobile fix menu start -->
    @include('partials.mobile-fix-menu')
    <!-- mobile fix menu end -->

    {{-- <main id="content" class="container-fluid-lg section-b-space"> --}}
        @yield('content')
        {{-- </main> --}}

    <!-- Footer Section Start -->
    @include('partials.footer2-section')
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