@extends('admin.layout')

@section('content')
<div style="display:flex; justify-content: space-between; align-items:center;">
    <h1 style="margin:0;">View User</h1>
    <div style="display:flex; gap:8px;">
        <a class="btn" href="{{ route('admin.users.edit', $user) }}" title="Edit user">Edit</a>
        <a class="btn" href="{{ route('admin.users.index') }}" style="background:#374151;" title="Back to list">Back</a>
    </div>
</div>

<div style="margin-top:16px; display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px;">
    <div>
        <label>ID</label>
        <input type="text" value="{{ $user->id }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Hashed ID</label>
        <div style="display:flex; gap:8px; align-items:center;">
            <input type="text" value="{{ $user->hashed_id }}" disabled
                style="flex:1; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
            @if($user->hashed_id)
            <button class="btn" type="button" title="Copy hashed id" aria-label="Copy hashed id"
                onclick="navigator.clipboard.writeText('{{ $user->hashed_id }}').then(() => { this.textContent='Copied'; setTimeout(() => this.textContent='Copy', 1500); })">
                Copy
            </button>
            @endif
        </div>
    </div>
    <div>
        <label>Email Verified At</label>
        <input type="text" value="{{ optional($user->email_verified_at)->format('Y-m-d H:i:s') }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Username</label>
        <input type="text" value="{{ $user->username }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Email</label>
        <input type="text" value="{{ $user->email }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Sponsor</label>
        <input type="text" value="{{ optional($user->sponsor)->username }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div style="display:flex; gap:18px; align-items:center; padding-top:24px;">
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" disabled {{ $user->is_admin ? 'checked' : '' }}> Admin
        </label>
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" disabled {{ $user->is_seller ? 'checked' : '' }}> Seller
        </label>
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" disabled {{ $user->is_online ? 'checked' : '' }}> Online
        </label>
    </div>
    <div>
        <label>First Name</label>
        <input type="text" value="{{ optional($user->profile)->first_name }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Last Name</label>
        <input type="text" value="{{ optional($user->profile)->last_name }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Phone</label>
        <input type="text" value="{{ optional($user->profile)->phone }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Avatar URL</label>
        <input type="text" value="{{ optional($user->profile)->avatar_url }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Birthdate</label>
        <input type="text" value="{{ optional(optional($user->profile)->birthdate)->format('Y-m-d') }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Address Line 1</label>
        <input type="text" value="{{ optional($user->defaultAddress)->address_line1 }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Address Line 2</label>
        <input type="text" value="{{ optional($user->defaultAddress)->address_line2 }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>City</label>
        <input type="text" value="{{ optional($user->defaultAddress)->city }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>State</label>
        <input type="text" value="{{ optional($user->defaultAddress)->state }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Postal Code</label>
        <input type="text" value="{{ optional($user->defaultAddress)->postal_code }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Country</label>
        <input type="text" value="{{ optional(optional($user->profile)->country)->name }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Timezone</label>
        <input type="text" value="{{ optional(optional($user->profile)->timezone)->name }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Last Login At</label>
        <input type="text" value="{{ optional($user->last_login_at)->format('Y-m-d H:i:s') }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Last Login IP</label>
        <input type="text" value="{{ $user->last_login_ip }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Created At</label>
        <input type="text" value="{{ optional($user->created_at)->format('Y-m-d H:i:s') }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Updated At</label>
        <input type="text" value="{{ optional($user->updated_at)->format('Y-m-d H:i:s') }}" disabled
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
</div>
@endsection