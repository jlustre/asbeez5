@extends('admin.layout')

@section('content')
<h1 class="text-xl font-semibold mb-4">Vendor Details</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <div><span class="muted">ID:</span> {{ $vendor->id }}</div>
        <div><span class="muted">Name:</span> {{ $vendor->name }}</div>
        <div><span class="muted">Slug:</span> {{ $vendor->slug }}</div>
        <div><span class="muted">Email:</span> {{ $vendor->email }}</div>
        <div><span class="muted">Phone:</span> {{ $vendor->phone }}</div>
        <div><span class="muted">Country:</span> {{ $vendor->country->name ?? '-' }}</div>
        <div><span class="muted">Timezone:</span> {{ $vendor->timezone->name ?? '-' }}</div>
        <div><span class="muted">Active:</span> {{ $vendor->is_active ? 'Yes' : 'No' }}</div>
        <div><span class="muted">Verification:</span> {{ $vendor->verification_status }}</div>
        <div><span class="muted">Commission Rate:</span> {{ $vendor->commission_rate }}%</div>
    </div>
    <div>
        <div><span class="muted">Address:</span> {{ $vendor->address_line1 }} {{ $vendor->address_line2 }}</div>
        <div><span class="muted">City:</span> {{ $vendor->city }}</div>
        <div><span class="muted">State:</span> {{ $vendor->state }}</div>
        <div><span class="muted">Postal Code:</span> {{ $vendor->postal_code }}</div>
        <div><span class="muted">Logo URL:</span> {{ $vendor->logo_url }}</div>
        <div><span class="muted">Banner URL:</span> {{ $vendor->banner_url }}</div>
    </div>
</div>

<div class="mt-4">
    <a class="btn" href="{{ route('admin.vendors.edit', $vendor) }}">Edit</a>
    <a class="btn" style="background:#374151" href="{{ route('admin.vendors.index') }}">Back</a>
</div>
@endsection