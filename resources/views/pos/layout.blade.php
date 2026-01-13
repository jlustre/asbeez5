<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AsBeez POS')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @yield('head')
</head>

<body>
    <header class="topbar">
        <div class="top-left">
            <div class="brand" aria-label="AsBeez POS">
                <img src="{{ asset('assets/images/logo/4.png') }}" alt="AsBeez Logo" style="height:32px;">
            </div>
            @yield('top-left')
        </div>
        <div class="top-right">
            <span class="text-sm">{{ now()->format('D, M d, Y') }}</span>
            <span class="text-sm">{{ now()->format('h:i:s A') }}</span>
            <span id="currentCashier" style="font-weight:800;">Cashier: <span style="font-weight:500;">{{
                    session('pos_employee_name') ?? 'Not signed
                    in' }}</span>
                @php
                $displayBranchCode = config('app.pos_branch_code');
                if (empty($displayBranchCode)) {
                $bid = session('pos_branch_id');
                if ($bid) {
                $displayBranchCode = optional(\App\Models\Branch::find($bid))->code;
                }
                }
                @endphp
                <span class="text-sm" style="margin-left:12px;">Branch: <span style="font-weight:500;">{{
                        $displayBranchCode ?? 'N/A' }}</span>
                    @yield('top-right')
                </span>
        </div>
    </header>

    <main class="@yield('main-class', 'shell')">
        @yield('content')
    </main>

    <footer class="footer">
        @yield('footer')
    </footer>

    @yield('modals')
    @yield('scripts')
</body>

</html>