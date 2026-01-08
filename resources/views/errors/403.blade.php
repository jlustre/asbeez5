<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="icon" href="{{ asset('assets/images/favicon/7.png') }}" type="image/x-icon">
    <style>
        :root {
            --fg: #333;
            --muted: #6b7280;
        }

        body {
            font-family: 'Public Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            color: var(--fg);
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7fafc;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            padding: 32px;
            max-width: 640px;
            width: 100%;
            text-align: center;
        }

        .code {
            font-size: 72px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .desc {
            color: var(--muted);
            margin-bottom: 24px;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-primary {
            background: #ef4444;
            color: #fff;
            border: none;
        }

        .btn-outline {
            background: #fff;
            color: #ef4444;
            border-color: #fecaca;
        }

        .img {
            max-width: 200px;
            margin: 0 auto 16px;
            opacity: .9;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="card">
            <img class="img" src="{{ asset('assets/images/inner-page/default-403.png') }}" alt="403"
                onerror="this.style.display='none'">
            <div class="code">403</div>
            <div class="title">Access denied</div>
            @php($isSellerArea = str_starts_with(request()->path(), 'seller/'))
            @if ($isSellerArea)
            <p class="desc">This area is for sellers only. You’re not authorized to view this page.</p>
            @else
            <p class="desc">You don’t have permission to access this page.</p>
            @endif
            <div class="actions">
                @guest
                <a class="btn btn-primary" href="{{ route('login') }}">Log In</a>
                @else
                @if ($isSellerArea)
                <a class="btn btn-primary" href="{{ route('seller.login') }}">Seller Login</a>
                @endif
                @endguest
                @if ($isSellerArea)
                <a class="btn btn-outline" href="{{ route('contact') }}">Request Seller Access</a>
                @endif
                <a class="btn btn-outline" href="{{ route('home') }}">Go to Home</a>
                <a class="btn btn-outline" href="javascript:history.back()">Go Back</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>