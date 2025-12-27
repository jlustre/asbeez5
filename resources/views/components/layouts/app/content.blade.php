<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.head2')

<body class="theme-color-7">
    <!-- Loader Start -->
    @include('partials.loader')
    <!-- Loader End -->

    <!-- Header Start -->
    @include('partials.header')
    <!-- Header End -->

    @yield('content')

    <!-- Tap to top and theme setting button start -->
    @include('partials.tap-to-top')
    <!-- Tap to top and theme setting button end -->

    <!-- Bg overlay Start -->
    <div class="bg-overlay"></div>
    <!-- Bg overlay End -->

    @include('partials.scripts')
</body>

</html>