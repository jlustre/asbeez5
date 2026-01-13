@extends('admin.layout')

@section('content')
<h1>Add Employee</h1>
@if($errors->any())
<div style="background:#1f2937; padding:8px 12px; border-radius:8px; margin-bottom:12px;">
    <ul style="margin:0; padding-left:18px;">
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif
<form method="POST" action="{{ route('admin.employees.store') }}" style="display:grid; gap:12px; max-width:520px;">
    @csrf
    <label>Name
        <input name="name" value="{{ old('name') }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0f172a; color:#e5e7eb;">
    </label>
    <label>Email
        <input name="email" value="{{ old('email') }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0f172a; color:#e5e7eb;">
    </label>
    <label>Phone
        <input name="phone" value="{{ old('phone') }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0f172a; color:#e5e7eb;">
    </label>
    <label>Role
        <select name="role"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0f172a; color:#e5e7eb;">
            <option value="cashier" {{ old('role')==='cashier' ? 'selected' : '' }}>Cashier</option>
            <option value="manager" {{ old('role')==='manager' ? 'selected' : '' }}>Manager</option>
        </select>
    </label>
    <label>POS PIN
        <input name="pos_pin" value="{{ old('pos_pin') }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0f172a; color:#e5e7eb;">
    </label>
    <label>Hired At
        <input type="date" name="hired_at" value="{{ old('hired_at') }}"
            style="width:100%; padding:8px; border-radius:8px; border:1px solid #1f2937; background:#0f172a; color:#e5e7eb;">
    </label>
    <label style="display:flex; align-items:center; gap:8px;">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}> Active
    </label>
    <div style="display:flex; gap:10px;">
        <button class="btn" type="submit">Save</button>
        <a class="btn" href="{{ route('admin.employees.index') }}" style="background:#374151;">Cancel</a>
    </div>
</form>
@endsection