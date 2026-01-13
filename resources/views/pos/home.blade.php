@extends('pos.home_layout')

@section('title', 'AsBeez POS • Home')

@section('content')
<section class="menu" aria-label="POS main menu">
    <a class="card" href="{{ route('pos.register') }}" data-action="register">
        <div class="icon">🧾</div>
        <div class="title">CASH REGISTER</div>
    </a>
    <a class="card" href="#" data-action="timecard">
        <div class="icon">⏱️</div>
        <div class="title">TIME CARD</div>
    </a>
    <a class="card" href="#" data-action="items">
        <div class="icon">📝</div>
        <div class="title">ITEM MANAGEMENT</div>
    </a>
    <a class="card" href="#" data-action="inventory">
        <div class="icon">📋</div>
        <div class="title">INVENTORY MANAGEMENT</div>
    </a>
    <a class="card" href="#" data-action="loyalty">
        <div class="icon">🎖️</div>
        <div class="title">CUSTOMER / LOYALTY PRG</div>
    </a>
    <a class="card" href="#" data-action="reports">
        <div class="icon">📊</div>
        <div class="title">REPORTS</div>
    </a>
    <a class="card" href="#" data-action="backoffice">
        <div class="icon">🖥️</div>
        <div class="title">BACK OFFICE</div>
    </a>
</section>
@endsection

@section('footer')
@php
$displayBranchCode = config('app.pos_branch_code');
if (empty($displayBranchCode)) {
$bid = session('pos_branch_id');
if ($bid) {
$displayBranchCode = optional(\App\Models\Branch::find($bid))->code;
}
}
@endphp
<div class="kv"><span class="label">CASHIER:</span><strong> {{ session('pos_employee_name') ?? 'Not signed'}}</strong>
</div>
<div class="kv"><span class="label">REGISTER NO:</span><strong>{{ config('app.pos_register_number') }}</strong></div>
<div class="kv"><span class="label">BRANCH:</span><strong>{{ $displayBranchCode ?? 'N/A' }}</strong></div>
<div class="kv"><span class="label">VERSION:</span><strong>{{ config('app.pos_version') }}</strong></div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.card').forEach(c => {
            c.addEventListener('click', (e) => {
                const act = c.getAttribute('data-action');
                if (act === 'register') { return; }
                e.preventDefault();
                alert('Navigate: ' + act.toUpperCase());
            });
        });
</script>
@endsection