@php($editing = isset($user) && $user->exists)

@if ($errors->any())
<div style="margin:12px 0; padding:10px; border-radius:8px; background:#3f1d1d; border:1px solid #7f1d1d;">
    <div><strong>There were some problems with your input:</strong></div>
    <ul style="margin:6px 0 0 18px;">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px;">
    <div>
        <label>Sponsor</label>
        <select name="sponsor_id" required
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
            <option value="">Select sponsor</option>
            @foreach(($sponsors ?? []) as $s)
            <option value="{{ $s->id }}" {{ (string)old('sponsor_id', $user->sponsor_id) === (string)$s->id ? 'selected'
                : '' }}>{{ $s->username }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Username</label>
        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Password @if($editing)<span class="muted">(leave blank to keep)</span>@endif</label>
        <input type="password" name="password" @if(!$editing) required @endif
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div style="display:flex; gap:18px; align-items:center; padding-top:24px;">
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
            Admin
        </label>
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="is_seller" value="1" {{ old('is_seller', $user->is_seller) ? 'checked' : '' }}>
            Seller
        </label>
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="is_online" value="1" {{ old('is_online', $user->is_online) ? 'checked' : '' }}>
            Online
        </label>
    </div>
    <div>
        <label>Last Login At</label>
        @php($lastLoginValue = old('last_login_at', optional($user->last_login_at)->format('Y-m-d\TH:i')))
        <input type="datetime-local" name="last_login_at" value="{{ $lastLoginValue }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Last Login IP</label>
        <input type="text" name="last_login_ip" value="{{ old('last_login_ip', $user->last_login_ip) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>First Name</label>
        <input type="text" name="first_name" value="{{ old('first_name', optional($user->profile)->first_name) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Last Name</label>
        <input type="text" name="last_name" value="{{ old('last_name', optional($user->profile)->last_name) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone', optional($user->profile)->phone) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Avatar URL</label>
        <input type="text" name="avatar_url" value="{{ old('avatar_url', optional($user->profile)->avatar_url) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Birthdate</label>
        @php($birthValue = old('birthdate', optional(optional($user->profile)->birthdate)->format('Y-m-d')))
        <input type="date" name="birthdate" value="{{ $birthValue }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Address Line 1</label>
        <input type="text" name="address_line1"
            value="{{ old('address_line1', optional($user->defaultAddress)->address_line1) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Address Line 2</label>
        <input type="text" name="address_line2"
            value="{{ old('address_line2', optional($user->defaultAddress)->address_line2) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>City</label>
        <input type="text" name="city" value="{{ old('city', optional($user->defaultAddress)->city) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>State</label>
        <input type="text" name="state" value="{{ old('state', optional($user->defaultAddress)->state) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Postal Code</label>
        <input type="text" name="postal_code"
            value="{{ old('postal_code', optional($user->defaultAddress)->postal_code) }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    </div>
    <div>
        <label>Country</label>
        <select name="country_id"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
            <option value="">Select country</option>
            @foreach(($countries ?? []) as $c)
            <option value="{{ $c->id }}" {{ (string)old('country_id', optional($user->defaultAddress)->country_id) ===
                (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Timezone</label>
        <select name="timezone_id"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
            <option value="">Select timezone</option>
            @foreach(($timezones ?? []) as $tz)
            <option value="{{ $tz->id }}" {{ (string)old('timezone_id', optional($user->profile)->timezone_id) ===
                (string)$tz->id ? 'selected' : '' }}>{{ $tz->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div style="margin-top:16px; display:flex; gap:8px;">
    <button class="btn" type="submit">{{ $editing ? 'Update' : 'Create' }}</button>
    <a class="btn" href="{{ route('admin.users.index') }}" style="background:#374151;">Cancel</a>
</div>