<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name') }}</title>
    <style>
        :root {
            --bg: #0f172a;
            --panel: #111827;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #6366f1;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            background: var(--bg);
            color: var(--text);
        }

        .layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: var(--panel);
            border-right: 1px solid #1f2937;
            padding: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .nav a {
            display: block;
            padding: 10px 12px;
            margin: 4px 0;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text);
        }

        .nav a.active,
        .nav a:hover {
            background: #1f2937;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            border-bottom: 1px solid #1f2937;
        }

        .content {
            padding: 20px;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            background: var(--accent);
        }

        .muted {
            color: var(--muted);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7l10 5 10-5-10-5Zm0 7L2 14l10 5 10-5-10-5Z" fill="currentColor" />
                </svg>
                <span>Admin</span>
            </div>
            <nav class="nav">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.vendors.index') }}"
                    class="{{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">Vendors</a>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                <a href="#">Orders</a>
                <a href="#">Reports</a>
                <a href="#">Settings</a>
            </nav>
        </aside>
        <main>
            <div class="header">
                <div>
                    <span class="muted">Signed in as</span>
                    <strong>{{ auth()->user()->username ?? auth()->user()->email }}</strong>
                </div>
                <div>
                    <a class="btn" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf
                    </form>
                </div>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>