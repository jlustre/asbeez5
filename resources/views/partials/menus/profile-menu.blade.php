<style>
    .header-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: inline-block;
        box-shadow: 0 0 0 2px #ffffff, 0 2px 6px rgba(0, 0, 0, .15);
    }

    /* Ensure the avatar renders reliably across breakpoints */
    .delivery-login-box {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .delivery-login-box .delivery-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        min-height: 36px;
    }

    .delivery-login-box .delivery-icon .header-avatar {
        display: block !important;
    }

    @media (min-width: 992px) {
        .header-avatar {
            width: 44px;
            height: 44px;
        }

        .delivery-login-box .delivery-icon {
            min-width: 44px;
            min-height: 44px;
        }
    }

    /* Ensure hover/focus reveals the dropdown reliably */
    .onhover-dropdown {
        position: relative;
    }

    .onhover-div {
        position: absolute;
        right: 0;
        top: 100%;
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: opacity .15s ease, transform .15s ease, visibility .15s ease;
        pointer-events: none;
        z-index: 1000;
    }

    .onhover-dropdown:hover .onhover-div,
    .onhover-dropdown:focus-within .onhover-div {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }
</style>
@auth
@php
$displayName = optional(auth()->user()->profile)->fullName()
?? (filled(auth()->user()->username) ? auth()->user()->username : auth()->user()->email);
@endphp
@endauth
<li class="right-side onhover-dropdown">
    <div class="delivery-login-box" tabindex="0">
        <div class="delivery-icon">
            @auth
            @php
            $avatarUrl = auth()->user()->profile_photo_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->profile_photo_path)
            : asset('assets/images/inner-page/user/1.jpg');
            @endphp
            <span class="header-avatar" style="background-image: url('{{ $avatarUrl }}');"></span>
            @else
            <i data-feather="user"></i>
            @endauth
        </div>
        <div class="delivery-detail">
            @auth
            <h6>Hello,</h6>
            <h5>{{ $displayName }}</h5>
            @else
            <h6>Hello,</h6>
            <h5>My Account</h5>
            @endauth
        </div>
    </div>

    <div class="onhover-div onhover-div-login">

        <ul class="user-box-name">
            @guest
            <li class="product-box-contain">
                <i></i>
                <a class="profile-dropdown-item" href="{{ route('login') }}">Log In</a>
            </li>

            <li class="product-box-contain">
                <a class="profile-dropdown-item" href="{{ route('register') }}">Register</a>
            </li>
            @endguest

            @auth
            <li class="product-box-contain">
                <a class="profile-dropdown-item text-sm" href="{{ route('dashboard') }}">My Dashboard</a>
            </li>
            @if(auth()->user()->is_admin ?? false)
            <li class="product-box-contain">
                <a class="profile-dropdown-item text-sm" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            </li>
            @endif
            <li class="product-box-contain">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="profile-dropdown-btn text-sm background-none pl-0 -ml-2"
                        style="border: none; background: none;">Log Out</button>
                </form>
            </li>
            @endauth
        </ul>
    </div>
</li>