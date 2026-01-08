@extends('admin.layout')

@section('content')
<h1 style="margin:0 0 12px 0;">Admin Dashboard</h1>
<p class="muted">Only admins can access this area.</p>

<div style="display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap:16px; margin-top:16px;">
    <div style="background:#111827; border:1px solid #1f2937; border-radius:12px; padding:16px;">
        <div class="muted">Total Users</div>
        <div style="font-size:24px; font-weight:600;">{{ \App\Models\User::count() }}</div>
    </div>
    <div style="background:#111827; border:1px solid #1f2937; border-radius:12px; padding:16px;">
        <div class="muted">Active Vendors</div>
        <div style="font-size:24px; font-weight:600;">{{ \App\Models\Vendor::where('is_active', true)->count() }}</div>
    </div>
    <div style="background:#111827; border:1px solid #1f2937; border-radius:12px; padding:16px;">
        <div class="muted">Commission Tiers</div>
        <div style="font-size:24px; font-weight:600;">{{ \App\Models\CommissionRate::count() }}</div>
    </div>
</div>

@endsection