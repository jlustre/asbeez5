@extends('admin.layout')

@section('content')
<div style="display:flex; justify-content: space-between; align-items: center; gap: 12px;">
    <h1 style="margin:0;">Users</h1>
    <a class="btn" href="{{ route('admin.users.create') }}">New User</a>
</div>

@if(session('status'))
<div style="margin-top:12px; padding:10px; border-radius:8px; background:#052e16; border:1px solid #064e3b;">{{
    session('status') }}</div>
@endif
@if(session('error'))
<div style="margin-top:12px; padding:10px; border-radius:8px; background:#3f1d1d; border:1px solid #7f1d1d;">{{
    session('error') }}</div>
@endif

<form method="get" style="margin-top:12px; display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
    <input type="text" name="q" value="{{ $q }}" placeholder="Search username or email"
        style="width:280px; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
    <select name="role"
        style="padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
        <option value="">All Roles</option>
        <option value="admin" {{ $role==='admin' ? 'selected' : '' }}>Admin</option>
        <option value="seller" {{ $role==='seller' ? 'selected' : '' }}>Seller</option>
        <option value="member" {{ $role==='member' ? 'selected' : '' }}>Member</option>
    </select>
    <select name="country_id"
        style="padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
        <option value="">All Countries</option>
        @foreach($countries as $c)
        <option value="{{ $c->id }}" {{ old('country_id', $countryId)==$c->id ? 'selected' : '' }}>{{ $c->name }}
        </option>
        @endforeach
    </select>
    <select name="per_page"
        style="padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0b1220; color:#e5e7eb;">
        @foreach([10,15,25,50,100] as $n)
        <option value="{{ $n }}" {{ $perPage==$n ? 'selected' : '' }}>{{ $n }}/page</option>
        @endforeach
    </select>
    <button class="btn" type="submit">Apply</button>
    <a class="btn" href="{{ route('admin.users.index') }}" style="background:#374151;">Reset</a>
</form>

<div style="overflow:auto; margin-top:16px;">
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                @php($nextDir = $dir === 'asc' ? 'desc' : 'asc')
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">
                    <a
                        href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'id', 'dir' => $sort==='id' ? $nextDir : 'asc'])) }}">ID
                        @if($sort==='id')<span class="muted">({{ strtoupper($dir) }})</span>@endif
                    </a>
                </th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">
                    <a
                        href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'username', 'dir' => $sort==='username' ? $nextDir : 'asc'])) }}">Username
                        @if($sort==='username')<span class="muted">({{ strtoupper($dir) }})</span>@endif
                    </a>
                </th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">
                    <a
                        href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'email', 'dir' => $sort==='email' ? $nextDir : 'asc'])) }}">Email
                        @if($sort==='email')<span class="muted">({{ strtoupper($dir) }})</span>@endif
                    </a>
                </th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">
                    <a
                        href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'is_admin', 'dir' => $sort==='is_admin' ? $nextDir : 'asc'])) }}">Admin
                        @if($sort==='is_admin')<span class="muted">({{ strtoupper($dir) }})</span>@endif
                    </a>
                </th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">
                    <a
                        href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'is_seller', 'dir' => $sort==='is_seller' ? $nextDir : 'asc'])) }}">Seller
                        @if($sort==='is_seller')<span class="muted">({{ strtoupper($dir) }})</span>@endif
                    </a>
                </th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">Country</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">Hashed ID</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">Timezone</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">Sponsor</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #1f2937;">Actions</th>
                <th style="display:none"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
            <tr>
                <td style="padding:4px; border-bottom:1px solid #1f2937;">{{ $u->id }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937;" class="text-sm">{{ $u->username ?? '—' }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937;" class="text-sm">{{ $u->email }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937;" class="text-sm text-center">{{ $u->is_admin ?
                    'Yes' : 'No'
                    }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937;" class="text-sm text-center">{{ $u->is_seller ?
                    'Yes' :
                    'No' }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937;" class="text-sm">{{
                    optional(optional($u->profile)->country)->name ?? '—' }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937; display:flex; gap:8px; align-items:center;"
                    class="text-sm">
                    <span>{{ $u->hashed_id ?? '—' }}</span>
                    @if($u->hashed_id)
                    <button class="btn" type="button" title="Copy hashed id" aria-label="Copy hashed id"
                        onclick="navigator.clipboard.writeText('{{ $u->hashed_id }}').then(() => { this.textContent='Copied'; setTimeout(() => this.textContent='Copy', 1500); })"
                        style="padding:6px 8px;">
                        Copy
                    </button>
                    @endif
                </td>
                <td style="padding:4px; border-bottom:1px solid #1f2937;" class="text-sm">{{
                    optional(optional($u->profile)->timezone)->name ?? '—' }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937;" class="text-sm">{{
                    optional($u->sponsor)->username ?? '—' }}</td>
                <td style="padding:4px; border-bottom:1px solid #1f2937; display:flex; gap:6px; align-items:center;">
                    <a class="btn" href="{{ route('admin.users.show', $u) }}" title="View user" aria-label="View user"
                        style="padding:6px 4px; display:inline-flex; align-items:center; justify-content:center; background:#374151;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"
                                fill="currentColor" />
                        </svg>
                    </a>
                    <a class="btn" href="{{ route('admin.users.edit', $u) }}" title="Edit user" aria-label="Edit user"
                        style="padding:6px 4px; display:inline-flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" fill="currentColor" />
                            <path
                                d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"
                                fill="currentColor" />
                        </svg>
                    </a>
                    <form method="post" action="{{ route('admin.users.destroy', $u) }}"
                        onsubmit="return confirm('Delete this user?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn" type="submit" title="Delete user" aria-label="Delete user"
                            style="background:#b91c1c; padding:6px 4px; display:inline-flex; align-items:center; justify-content:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 7h12v13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7z" fill="currentColor" />
                                <path d="M9 4h6l1 2H8l1-2z" fill="currentColor" />
                            </svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:12px;">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">
    {{ $users->links() }}
</div>

@endsection