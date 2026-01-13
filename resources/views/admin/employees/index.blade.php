@extends('admin.layout')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <h1 style="margin:0;">Employees</h1>
    <a class="btn" href="{{ route('admin.employees.create') }}">Add Employee</a>
</div>

@if(session('status'))
<div style="background:#1f2937; padding:8px 12px; border-radius:8px; margin-bottom:12px;">{{ session('status') }}</div>
@endif

<div style="background:#111827; border:1px solid #1f2937; border-radius:8px; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#0f172a;">
                <th style="text-align:left; padding:10px;">Name</th>
                <th style="text-align:left; padding:10px;">Email</th>
                <th style="text-align:left; padding:10px;">Role</th>
                <th style="text-align:left; padding:10px;">Active</th>
                <th style="text-align:left; padding:10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
            <tr style="border-top:1px solid #1f2937;">
                <td style="padding:10px;">{{ $emp->name }}</td>
                <td style="padding:10px;">{{ $emp->email }}</td>
                <td style="padding:10px;">{{ ucfirst($emp->role) }}</td>
                <td style="padding:10px;">{{ $emp->is_active ? 'Yes' : 'No' }}</td>
                <td style="padding:10px; display:flex; gap:8px;">
                    <a class="btn" href="{{ route('admin.employees.edit', $emp) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.employees.destroy', $emp) }}"
                        onsubmit="return confirm('Delete this employee?');">
                        @csrf @method('DELETE')
                        <button class="btn" type="submit" style="background:#ef4444;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:10px;">No employees found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:12px;">{{ $employees->links() }}</div>
@endsection